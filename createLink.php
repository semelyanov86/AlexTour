<?php
require_once 'include/utils/utils.php';
require_once 'include/utils/VtlibUtils.php';
require_once 'modules/Vtiger/helpers/ShortURL.php';
global $adb;
$adb = PearDatabase::getInstance();
$options = array(
    'handler_path' => 'modules/Tours/handlers/ReceiveFlights.php',
    'handler_class' => 'Tours_ReceiveFlights_Handler',
    'handler_function' => 'receiveFlights',
    'handler_data' => array()
);
$trackURL = Vtiger_ShortURL_Helper::generateURL($options);
var_dump($trackURL);