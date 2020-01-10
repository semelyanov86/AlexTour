<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * https://smartoffice.alex-reisen.de//shorturl.php?id=5e1740184e4166.01058243
 * *********************************************************************************** */

include_once 'include/Webservices/Utils.php';
include_once 'include/Webservices/ModuleTypes.php';
include_once 'include/Webservices/Revise.php';
include_once 'include/Webservices/Create.php';
include_once 'include/Webservices/RetrieveRelated.php';

class Tours_ReceiveFlights_Handler {

    public $fields = array('name', 'flightsno', 'type', 'id', 'transport', 'cf_airports_from_id', 'cf_airports_to_id', 'description', 'cf_airlines_id', 'time_departure', 'time_arrival');

    public $namesFields = array('cf_airports_from_id', 'cf_airports_to_id', 'cf_airlines_id');

    public function receiveFlights($data){
        global $site_URL;
        global $adb;
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $current_user = CRMEntity::getInstance('Users');
        $current_user->retrieveCurrentUserInfoFromFile(1);
        $tourId = $_GET['tourid'];
        $tourEntity = vtws_getWebserviceEntityId('Tours', $tourId);
        $result = vtws_retrieve_related($tourEntity, 'Flights', 'Flights', $current_user);
        $finalData = array();
        foreach ($result as $key=>$entity) {
            foreach ($this->fields as $field) {
                if (in_array($field, $this->namesFields)) {
                    $crmid = vtws_getCRMEntityId($entity[$field]);
                    $crmname = Vtiger_Util_Helper::getRecordName($crmid);
                    $finalData[$key][$field] = $crmname;
                } elseif ($field == 'id') {
                    $finalData[$key][$field] = vtws_getCRMEntityId($entity[$field]);
                } else {
                    $finalData[$key][$field] = $entity[$field];
                }
            }
        }
        $response->setResult(array("result" => $finalData));
        $response->emit();
    }

}