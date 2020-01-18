<?php
require_once 'include/utils/utils.php';
require_once 'include/utils/VtlibUtils.php';
require_once 'modules/Vtiger/helpers/ShortURL.php';
global $adb;
$adb = PearDatabase::getInstance();
$options = array(
    'handler_path' => 'modules/VTEPayments/handlers/DeletePayments.php',
    'handler_class' => 'VTEPayments_DeletePayments_Handler',
    'handler_function' => 'deletePayments',
    'handler_data' => array()
);
$trackURL = Vtiger_ShortURL_Helper::generateURL($options);
var_dump($trackURL);