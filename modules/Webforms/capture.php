<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */
// Switch the working directory to base
chdir(dirname(__FILE__) . '/../..');

include_once 'includes/Loader.php';
include_once 'include/Zend/Json.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'include/utils/VtlibUtils.php';
include_once 'include/Webservices/Create.php';
include_once 'modules/Webforms/model/WebformsModel.php';
include_once 'modules/Webforms/model/WebformsFieldModel.php';
include_once 'include/QueryGenerator/QueryGenerator.php';
include_once 'includes/runtime/EntryPoint.php';
include_once 'includes/main/WebUI.php';
include_once 'include/Webservices/AddRelated.php';
include_once 'include/Webservices/RetrieveRelated.php';
include_once 'include/Webservices/Retrieve.php';
include_once 'include/Webservices/Revise.php';

class Webform_Capture {
    const TRID_ID = 5990;
    const ONE_ROOM = 5992;
    const GRUNDPAKET = 5991;

    public $fieldsMapping = array(
        "Contacts" => array(
            "firstname" => "firstname",
            "lastname" => "lastname",
            "email" => "email",
            "phone" => "phone",
            "mailingcountry" => "country",
            "mailingstreet" => "contact_address"
        ),
        "Traveller" => array(
            "firstname" => "firstname",
            "lastname" => "lastname",
            "salutationtype" => "salutationtype"
        ),
        "PackageServices" => array(
            "one_room" => "one_room",
            "trip_service" => "trip_service"
        )
    );

	function captureNow($request) {
		$isURLEncodeEnabled = $request['urlencodeenable'];
		$currentLanguage = Vtiger_Language_Handler::getLanguage();
		$moduleLanguageStrings = Vtiger_Language_Handler::getModuleStringsFromFile($currentLanguage);
		vglobal('app_strings', $moduleLanguageStrings['languageStrings']);
        //honey pot field
        $honeypot = $request['unname'];
		$returnURL = false;
//		var_dump($request);die;
		try {
            //check if the honeypot field is filled out. If not, send a mail.
            if( $honeypot != '' ){
                throw new Exception ('Sorry, but we blocked your request due to antispam policy');
                return; //you may add code here to echo an error etc.
            }
			if (!vtlib_isModuleActive('Webforms'))
				throw new Exception('webforms is not active');

			$webform = Webforms_Model::retrieveWithPublicId(vtlib_purify($request['publicid']));
			if (empty($webform))
				throw new Exception("Webform not found.");

			$returnURL = $webform->getReturnUrl();
			$roundrobin = $webform->getRoundrobin();

			// Retrieve user information
			$user = CRMEntity::getInstance('Users');
			$user->id = $user->getActiveAdminId();
			$user->retrieve_entity_info($user->id, 'Users');

			// Prepare the parametets
			$parameters = array();
			$webformFields = $webform->getFields();
			foreach ($webformFields as $webformField) {
				if ($webformField->getDefaultValue() != null) {
					$parameters[$webformField->getFieldName()] = decode_html($webformField->getDefaultValue());
				} else {
					//If urlencode is enabled then skipping decoding field names
					if ($isURLEncodeEnabled == 1) {
						$webformNeutralizedField = $webformField->getNeutralizedField();
					} else {
						$webformNeutralizedField = html_entity_decode($webformField->getNeutralizedField(), ENT_COMPAT, "UTF-8");
					}

					if (isset($request[$webformField->getFieldName()])) {
						$webformNeutralizedField = $webformField->getFieldName();
					}
					if (is_array(vtlib_purify($request[$webformNeutralizedField]))) {
						$fieldData = implode(" |##| ", vtlib_purify($request[$webformNeutralizedField]));
					} else {
						$fieldData = vtlib_purify($request[$webformNeutralizedField]);
						$fieldData = decode_html($fieldData);
					}

					$parameters[$webformField->getFieldName()] = stripslashes($fieldData);
				}
				if ($webformField->getRequired()) {
					if (!isset($parameters[$webformField->getFieldName()]))
						throw new Exception("Required fields not filled");
				}
			}

			if ($roundrobin) {
				$ownerId = $webform->getRoundrobinOwnerId();
				$ownerType = vtws_getOwnerType($ownerId);
				$parameters['assigned_user_id'] = vtws_getWebserviceEntityId($ownerType, $ownerId);
			} else {
				$ownerId = $webform->getOwnerId();
				$ownerType = vtws_getOwnerType($ownerId);
				$parameters['assigned_user_id'] = vtws_getWebserviceEntityId($ownerType, $ownerId);
			}

			$moduleModel = Vtiger_Module_Model::getInstance($webform->getTargetModule());
			$fieldInstances = Vtiger_Field_Model::getAllForModule($moduleModel);
			foreach ($fieldInstances as $blockInstance) {
				foreach ($blockInstance as $fieldInstance) {
					$fieldName = $fieldInstance->getName();
					if($fieldInstance->get('uitype') == 56 && $fieldInstance->getDefaultFieldValue() == '') {
						$defaultValue = $request[$fieldName];
					} else if (empty($parameters[$fieldName])) {
						$defaultValue = $fieldInstance->getDefaultFieldValue();
						if ($defaultValue) {
							$parameters[$fieldName] = decode_html($defaultValue);
						}
					} else if ($fieldInstance->get("uitype") == 71 || $fieldInstance->get("uitype") == 72) {
						//ignore comma(,) if it is currency field
						$parameters[$fieldName] = str_replace(",", "", $parameters[$fieldName]);
					}
				}
			}

			// New field added to show Record Source
			$parameters['source'] = 'Webform';
            if ($request['isOrderFromWeb'] == 'On') {
                $contact = $this->createPayableContact($request, $parameters, $user);
                if ($contact && $contact['id']) {
                    $parameters['contact_id'] = $contact['id'];
                }
                $parameters = $this->extendPotential($parameters, $request);
//                $this->createRecordsFromWeb($record, $request);
                $returnURL = 'https://' . $request['cf_1962'] . '/' . $returnURL;
            }
			// Create the record

                $record = vtws_create($webform->getTargetModule(), $parameters, $user);

            if ($request['isOrderFromWeb'] == 'On') {
                $this->createRecordsFromWeb($record, $request, $parameters, $user);
                $tourData = $this->createMovings($record, $request, $parameters, $user);
                $this->createServiceDetails($record, $request, $parameters, $user, $tourData);
                $this->attachHotels($record, $request);
                $this->changeSalesStage($record, $user);
            }
			$webform->createDocuments($record);

			$this->sendResponse($returnURL, 'ok');
			return;
		} catch (DuplicateException $e) {
			$sourceModule = $webform->getTargetModule();
			$mailBody = vtranslate('LBL_DUPLICATION_FAILURE_FROM_WEBFORMS', $sourceModule, vtranslate('SINGLE_'.$sourceModule, $sourceModule), $webform->getName(), vtranslate('SINGLE_'.$sourceModule, $sourceModule));

			$userModel = Users_Record_Model::getInstanceFromPreferenceFile($user->id);
			sendMailToUserOnDuplicationPrevention($sourceModule, $parameters, $mailBody, $userModel);

			$this->sendResponse($returnURL, false, $e->getMessage());
			return;
		} catch (Exception $e) {
			$this->sendResponse($returnURL, false, $e->getMessage());
			return;
		}
	}

	protected function createPayableContact($request, $params, $user)
    {
        $email = $request['email'];
        $q = "SELECT * FROM Contacts WHERE email = '$email'";
        $q = $q . ';'; // NOTE: Make sure to terminate query with ;
        $records = vtws_query($q, $user);
        if ($records && count($records) > 0) {
            return $records[0];
        } else {
            $parameters = array();
            foreach ($this->fieldsMapping["Contacts"] as $key=>$value) {
                $parameters[$key] = $request[$value];
            }
//            $parameters['cf_2030'] = $request['salutationtype'];
            $parameters['cf_1960'] = 1;
            $parameters['assigned_user_id'] = $params['assigned_user_id'];
            $parameters['source'] = $params['source'];
            return vtws_create('Contacts', $parameters, $user);
        }
    }

	protected function createRecordsFromWeb($record, $request, $params, $user)
    {
        $relModel = Vtiger_Relation_Model::getInstance(Vtiger_Module_Model::getInstance('Potentials'), Vtiger_Module_Model::getInstance('Contacts'), 'Contacts');
        $potentialId = vtws_getCRMEntityId($record['id']);
        for ($i = 0; $i < 11; $i++) {
            if (!isset($request['traveller_lastname'][$i]) || !$request['traveller_lastname'][$i]) {
                continue;
            }
            $parameters = array(
                'assigned_user_id' => $params['assigned_user_id'],
                'source' => $params['source']
            );
            $paramServices = array(
                'assigned_user_id' => $params['assigned_user_id'],
                'source' => $params['source']
            );
            foreach ($this->fieldsMapping['Traveller'] as $key=>$value) {
                $parameters[$key] = $request['traveller_' . $value][$i];
            }
            foreach ($this->fieldsMapping['PackageServices'] as $key=>$value) {
                $paramServices[$key] = $request['traveller_' . $value][$i];
            }
            $parameters['cf_2030'] = $request['traveller_title'][$i];
            $contact = vtws_create('Contacts', $parameters, $user);
            $contactId = vtws_getCRMEntityId($contact['id']);
            $relModel->addRelation($potentialId, $contactId);
            $paramServices['name'] = $record['potentialname'];
            $paramServices['cf_potentials_id'] = $record['id'];
            $paramServices['cf_contacts_id'] = $contact['id'];
            $packageServices = vtws_create('PackageServices', $paramServices, $user);
        }
    }

    protected function createMovings($record, $request, $parameters, $user)
    {
        global $adb;
        $potentialId = $record['id'];
        $tourId = $record['cf_tours_id'];
        $tourData = vtws_retrieve($tourId, $user);
        $airportId = vtws_getWebserviceEntityId('Airports', $request['airportsid']);
        $flights = vtws_retrieve_related($tourId, 'Flights', 'Flights', $user);

        foreach ($flights as $flight) {
            $params = array(
                'assigned_user_id' => $parameters['assigned_user_id'],
                'source' => $parameters['source'],
                'cf_potentials_id' => $potentialId
            );
            if ($flight['cf_airports_from_id'] == $airportId) {
                $params['cf_flights_id'] = $flight['id'];
                $params['name'] = $flight['name'];
                $params['date_flight'] = $parameters['cf_1637'];
                $params['mtype'] = 'In';
                $moving = vtws_create('Movings', $params, $user);
            } elseif ($flight['cf_airports_to_id'] == $airportId) {
                $params['cf_flights_id'] = $flight['id'];
                $params['name'] = $flight['name'];
                $params['date_flight'] = $parameters['closingdate'];
                $params['mtype'] = 'Out';
                $moving = vtws_create('Movings', $params, $user);
            } elseif($flight['cf_2017'] > 0 && $tourData['cf_2015'] > 0) {
                $params['cf_flights_id'] = $flight['id'];
                $params['name'] = $flight['name'];
                $params['date_flight'] = date('Y-m-d', strtotime($parameters['closingdate'] . ' + ' . $tourData['cf_2015'] . ' days'));
                $params['mtype'] = 'Through';
                $moving = vtws_create('Movings', $params, $user);
            }
        }
        return $tourData;
    }

    protected function createServiceDetails($record, $request, $parameters, $user, $tourData = array())
    {
        $tourId = $record['cf_tours_id'];
        $potentialId = $record['id'];
        if (empty($tourData)) {
            $tourData = vtws_retrieve($tourId, $user);
        }
        $tripId = vtws_getWebserviceEntityId('Services', self::TRID_ID);
        $roomId = vtws_getWebserviceEntityId('Services', self::ONE_ROOM);
        $grundId = vtws_getWebserviceEntityId('Services', self::GRUNDPAKET);
        $oneRoomCnt = 0;
        $tripCnt = 0;
        $oneRoomPrice = $request["service_price"];
        $tripPrice = $tourData["cf_1904"];
        $grundPrice = $request['tour_price'];
        foreach ($request["traveller_one_room"] as $traveller) {
            if ($traveller == 'on' || $traveller == 'On') {
                $oneRoomCnt++;
            }
        }
        foreach ($request["traveller_trip_service"] as $traveller) {
            if ($traveller == 'on' || $traveller == 'On') {
                $tripCnt++;
            }
        }
        $params = array(
            'assigned_user_id' => $parameters['assigned_user_id'],
            'source' => $parameters['source'],
            'cf_potentials_id' => $potentialId,
            'name' => '##SERVICE_DETAIL_NAME##',
            'service_qty' => count($request['traveller_last_name']),
            'service_price' => $grundPrice,
            'cf_services_id' => $grundId,
            'description' => $tourData['name']
        );
        $result = vtws_create('ServiceDetails', $params, $user);
        $params['description'] = '';
        if ($oneRoomCnt > 0) {
            $params['service_qty'] = $oneRoomCnt;
            $params['service_price'] = $oneRoomPrice;
            $params['cf_services_id'] = $roomId;
            $result = vtws_create('ServiceDetails', $params, $user);
        }
        if ($tripCnt > 0) {
            $params['service_qty'] = $tripCnt;
            $params['service_price'] = $tripPrice;
            $params['cf_services_id'] = $tripId;
            $result = vtws_create('ServiceDetails', $params, $user);
        }
        return $result;
    }

    protected function extendPotential($parameters, $request)
    {
        $parameters['potentialname'] = $request['first_name'] . ' ' . $request['last_name'];
//        if (!isset($parameters['cf_1962']) || !$parameters['cf_1962']) {
            $parameters['cf_1962'] = $request['cf_1962'];
//        }
        $parameters['cf_tours_id'] = vtws_getWebserviceEntityId('Tours', $request['toursid']);
        $parameters['cf_1647'] = 'Airport-Hotel-Airport';
        $parameters['cf_1639'] = $this->countDaysBetweenDates($request["cf_1637"], $request["closingdate"]);
        $parameters['cf_1641'] = $this->countNightsBetweenDates($request['cf_1637'], $request['closingdate']);
        $hasTrip = 0;
        foreach ($request["traveller_trip_service"] as $trip) {
            if ($trip == 'on') {
                $hasTrip = 1;
                break;
            }
        }
        $parameters['cf_2028'] = $hasTrip;
//        $parameters['cf_1962'] = 'russland-reisen.de';
        return $parameters;
    }

    protected function attachHotels($record, $request)
    {
        $relModel = Vtiger_Relation_Model::getInstance(Vtiger_Module_Model::getInstance('Potentials'), Vtiger_Module_Model::getInstance('Hotels'), 'Hotels');
        $potentialId = vtws_getCRMEntityId($record['id']);
        $hotelsId = $request['hotelsid'];
        if (!is_array($hotelsId)) {
            $hotelsId = [$hotelsId];
        }
        foreach($hotelsId as $hotelId) {
            $relModel->addRelation($potentialId, $hotelId);
        }
    }

	protected function sendResponse($url, $success = false, $failure = false) {
		if (empty($url)) {
			if ($success)
				$response = Zend_Json::encode(array('success' => true, 'result' => $success));
			else
				$response = Zend_Json::encode(array('success' => false, 'error' => array('message' => $failure)));

			// Support JSONP
			if (!empty($_REQUEST['callback'])) {
				$callback = vtlib_purify($_REQUEST['callback']);
				echo sprintf("%s(%s)", $callback, $response);
			} else {
				echo $response;
			}
		} else {
			$pos = strpos($url, 'http');
			if ($pos !== false) {
				header(sprintf("Location: %s?%s=%s", $url, ($success ? 'success' : 'error'), ($success ? $success : $failure)));
			} else {
				header(sprintf("Location: http://%s?%s=%s", $url, ($success ? 'success' : 'error'), ($success ? $success : $failure)));
			}
		}
	}

	protected function changeSalesStage($record, $user)
    {
        $wsid = $record['id']; // Module_Webservice_ID x CRM_ID
        $data = array('sales_stage' => 'Value Proposition', 'id' => $wsid);
        $potential = vtws_revise($data, $user);
    }

    /**
     * Функция считает количество дней между двумя датами
     *
     * @param string $d1 первая дата
     * @param string $d2 вторая дата
     *
     * @return number количество дней
     */
    public function countDaysBetweenDates($d1, $d2)
    {
        $d1_ts = strtotime($d1);
        $d2_ts = strtotime($d2);

        $seconds = abs($d1_ts - $d2_ts);

        return round($seconds / 86400);
    }

    /**
     * Функция считает количество ночей между двумя датами
     *
     * @param string $d1 первая дата
     * @param string $d2 вторая дата
     *
     * @return number количество дней
     */
    public function countNightsBetweenDates($d1, $d2)
    {
        $date1 = new DateTime($d1);
        $date2 = new DateTime($d2);

        return $date2->diff($date1)->format("%a");
    }

}

// NOTE: Take care of stripping slashes...
$webformCapture = new Webform_Capture();
$request = vtlib_purify($_REQUEST);
$isURLEncodeEnabled = $request['urlencodeenable'];
header('Access-Control-Allow-Origin: *');
//Do urldecode conversion only if urlencode is enabled in a form. 
if ($isURLEncodeEnabled == 1) {
	$requestParameters = array();
	// Decoding the form element name attributes.
	foreach ($request as $key => $value) {
		$requestParameters[urldecode($key)] = $value;
	}
	//Replacing space with underscore to make request parameters equal to webform fields
	$neutralizedParameters = array();
	foreach ($requestParameters as $key => $value) {
		$modifiedKey = str_replace(" ", "_", $key);
		$neutralizedParameters[$modifiedKey] = $value;
	}
	$webformCapture->captureNow($neutralizedParameters);
} else {
	$webformCapture->captureNow($request);
}
?>