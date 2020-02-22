<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/
include_once 'include/Webservices/Query.php';
include_once 'include/Webservices/Retrieve.php';
class CustomerPortal_SearchAutocomplete extends CustomerPortal_API_Abstract {

	function process(CustomerPortal_API_Request $request) {
		$response = new CustomerPortal_API_Response();
		$current_user = $this->getActiveUser();
		global $adb;
		if ($current_user) {
            $parent = $request->get('parent');
            $child = $request->get('child');
            $customerId = $this->getActiveCustomer()->id;
            $contactWebserviceId = vtws_getWebserviceEntityId('Contacts', $customerId);
            $accountId = $this->getParent($contactWebserviceId);
            try {
                $parentModel = vtws_describe($child, $current_user);
            } catch (WebServiceException $ex) {
                $response->setError($ex->getCode(), $ex->getMessage());
                return $response;
            }
            $relatedType = $parentModel['name'];
            $relatedLabel = $parentModel['label'];
            $fieldName = $parentModel['labelFields'];
            if ($contactWebserviceId) {
                $contactRelated = vtws_retrieve_related($contactWebserviceId, $relatedType, $relatedLabel, $current_user);
            }
            if ($accountId) {
                $accountRelated = vtws_retrieve_related($accountId, $relatedType, $relatedLabel, $current_user);
            }
			$searchKey = $request->get('searchKey');
            $result = array();
			if (!empty($searchKey)) {
                foreach ($contactRelated as $relatedCont) {
                    if (strpos($relatedCont[$fieldName], $searchKey) !== false) {
                        $result[$relatedCont['id']] = $relatedCont[$fieldName];
                    }
                }
                foreach ($accountRelated as $relatedAcc) {
                    if (strpos($relatedAcc[$fieldName], $searchKey) !== false) {
                        $result[$relatedAcc['id']] = $relatedAcc[$fieldName];
                    }
                }
                if (!empty($result)) {
                    $result = $this->parseResult($result);
                }
                $response->setResult($result);
			} else {
				throw new Exception("Search key is empty", 1412);
			}
			return $response;
		}
	}

	public function parseResult($result)
    {
        $res = array();
        foreach($result as $key=>$value) {
            $res[] = array('id' => $key, 'name' => $value);
        }
        return $res;
    }

}
