<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.2
 * ("License.txt"); You may not use this file except in compliance with the License
 * The Original Code is: Vtiger CRM Open Source
 * The Initial Developer of the Original Code is Vtiger.
 * Portions created by Vtiger are Copyright (C) Vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class Portal_FetchProfile_API extends Portal_Default_API {

	protected $allowedFields = array(
		'contactFields' => array('firstname', 'lastname', 'email', 'secondaryemail', 'mobile', 'phone', 'imagename', 'imagedata', 'imagetype'),
		'accountFields' => array('firstname', 'lastname', 'website', 'email1', 'phone', 'imagename', 'imagedata', 'imagetype', 'accountname'),
        'companyFields' => array('organizationname', 'address', 'city', 'state', 'country', 'phone', 'fax', 'website', 'logoname', 'logopath', 'inn', 'kpp'),
        'userFields' => array('user_name', 'first_name', 'last_name', 'email1', 'title', 'phone_work', 'department', 'phone_mobile', 'phone_other', 'email2', 'phone_fax', 'secondaryemail', 'phone_home', 'description', 'address_street', 'address_city', 'address_state', 'address_postalcode', 'address_country', 'imagename')
        );

	public function process(Portal_Request $request) {
		$result = Vtiger_Connector::getInstance()->fetchProfile();
		$response = new Portal_Response();
		$response->setResult($this->filterResponse($result, $this->allowedFields));

		return $response;
	}

	protected function filterResponse($result, $allowedFields) {
		$data = array();
		if (isset($result['customer_details'])) {
			foreach ($result['customer_details'] as $conKey => $conValues) {
				if (!in_array($conKey, $allowedFields['contactFields'])) {
					unset($result['customer_details'][$conKey]);
				}
			}
		}
		if (isset($result['company_details'])) {
			foreach ($result['company_details'] as $accKey => $accValues) {
				if (!in_array($accKey, $allowedFields['accountFields'])) {
					unset($result['company_details'][$accKey]);
				}
			}
		}
        if (isset($result['company_info'])) {
            foreach ($result['company_info'] as $accKey => $accValues) {
                if (!in_array($accKey, $allowedFields['companyFields'])) {
                    unset($result['company_info'][$accKey]);
                }
            }
        }
        if (isset($result['user_info'])) {
            foreach ($result['user_info'] as $accKey => $accValues) {
                if (!in_array($accKey, $allowedFields['userFields'])) {
                    unset($result['user_info'][$accKey]);
                }
            }
        }
		$data['customer_details'] = $result['customer_details'];
		$data['company_details'] = $result['company_details'];
		$data['company_info'] = $result['company_info'];
		$data['user_info'] = $result['user_info'];

		if (isset($data['company_details'])) {
			foreach ($data['company_details'] as $label => $value) {
				if ($label == 'website' && !empty($value)) {
					$matchPattern = "^[\w]+:\/\/^";
					preg_match($matchPattern, $value, $matches);
					if (!empty($matches[0])) {
						$value = $value;
					} else {
						$value = 'http://'.$value;
					}
					$data['company_details']['weburl'] = $value;
				}
			}
		}

		return $data;
	}

}
