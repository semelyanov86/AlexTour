<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/


class VTEPayments_MassActionAjax_View extends Project_MassActionAjax_View {

    function __construct() {
        parent::__construct();
        $this->exposeMethod('saveAjax');
    }


    public function saveAjax(Vtiger_Request $request)
    {
        $bank = $request->get('cf_2068');
        $paymentModule = Vtiger_Module_Model::getInstance('VTEPayments');
        $url = $paymentModule->getListViewUrl();
        if (!$bank) {
            echo 'Bank name is Empty!';
            header( "Location: $url" );
            exit;
        }
        $bankModelName = 'VTEPayments_' . $bank . '_Model';
        $bankParser = new $bankModelName;
        $result = $bankParser->doParsing();
        $bankParser->createPayments();
    }

}
