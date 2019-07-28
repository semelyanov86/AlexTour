<?php

include_once 'config.php';
include_once 'include/Webservices/Relation.php';

include_once 'vtlib/Vtiger/Module.php';
include_once 'includes/main/WebUI.php';
require_once 'modules/Visa/cron/MassImport.php';
require_once 'modules/Potentials/PotentialsImport.php';
//ini_set('display_errors','on'); error_reporting(E_ALL);

$dir = 'import';
//$files = scandir($dir);
$cleanFiles = array();
foreach (glob("import/*.pdf") as $filename) {
    $filename = basename($filename);
    $cleanFiles[] = $filename;
}

//$massObject = new MassImport($cleanFiles, $dir, false);
//$massObject->process();

$cleanInvoices = array();
foreach (glob("import/Invoices/*.pdf") as $filename) {
    $filename = basename($filename);
    $cleanInvoices[] = $filename;
}
$massInvoices = new PotentialsImport($cleanInvoices, $dir . '/Invoices/', true);
$massInvoices->process();