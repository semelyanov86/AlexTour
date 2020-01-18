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

//https://smartoffice.alex-reisen.de//shorturl.php?id=5e20804c856270.73614175

include_once 'include/Webservices/Utils.php';
include_once 'include/Webservices/ModuleTypes.php';
include_once 'include/Webservices/Revise.php';
include_once 'include/Webservices/Create.php';
include_once 'include/Webservices/RetrieveRelated.php';
include_once 'include/Webservices/Retrieve.php';
include_once 'include/Webservices/Delete.php';

class VTEPayments_DeletePayments_Handler {

    public function deletePayments($data){
        global $adb;
        $result = $adb->pquery(
            "SELECT paymentid FROM vtiger_payments INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_payments.paymentid WHERE vtiger_crmentity.deleted = ?",
            [0]
        );
        $current_user = CRMEntity::getInstance('Users');
        $current_user->retrieveCurrentUserInfoFromFile(1);

        $payList = [];
        while($resultRow = $adb->fetchByAssoc($result)) {
            $payList[] = $resultRow['paymentid'];
        }
        foreach($payList as $pay) {
            $wsid = vtws_getWebserviceEntityId('VTEPayments', $pay);
            try {
                vtws_delete($wsid, $current_user);
            } catch (WebServiceException $ex) {
                echo $ex->getMessage();
            }
        }
        echo 'OK!';die;
    }

}