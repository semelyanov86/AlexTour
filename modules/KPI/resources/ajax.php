<?php

ini_set("display_errors", "on");
ini_set("max_execution_time", 6000);
chdir("../../..");
require_once "includes/runtime/BaseModel.php";
require_once "modules/Vtiger/models/Record.php";
require_once "modules/Users/models/Record.php";
require_once "includes/runtime/Globals.php";
require_once "include/utils/utils.php";
require_once "includes/runtime/LanguageHandler.php";
require_once "includes/Loader.php";
ini_set("display_errors", 0);
error_reporting(32767 & ~2 & ~8 & ~8192 & ~2048);
$adb = PearDatabase::getInstance();
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
$kpiday = 2610;
$kpimonth = 2609;
$kpiyear = 2611;
$data = array();
$data['day']['target'] = getKPIByQuery($kpiday);
$data['day']['current'] = getFactByPeriod('day');
$data['month']['target'] = getKPIByQuery($kpimonth);
$data['month']['current'] = getFactByPeriod('month');
$data['year']['target'] = getKPIByQuery($kpiyear);
$data['year']['current'] = getFactByPeriod('year');
echo json_encode($data) . PHP_EOL;
echo PHP_EOL;
ob_flush();
flush();
exit;

function getKPIByQuery($id)
{
    $adb = PearDatabase::getInstance();
    $query = "SELECT * FROM vtiger_kpi INNER JOIN vtiger_kpicf ON vtiger_kpi.kpiid = vtiger_kpicf.kpiid WHERE vtiger_kpi.kpiid = ?";
    $result = $adb->pquery($query, array($id));
    if ($adb->num_rows($result) > 0) {
        $plan = $adb->query_result($result, 0, 'cf_1189');
    } else {
        $plan = 0;
    }
    return $plan;
}

function getFactByPeriod($period)
{
    $adb = PearDatabase::getInstance();
    switch ($period) {
        case 'day':
            $count_date = date('Y-m-d') . ' ' . '00:00:00';
            break;
        case 'month':
            $count_date = date('Y-m-01') . ' ' . '00:00:00';
            break;
        case 'year':
            $count_date = date('Y-01-01') . ' ' . '00:00:00';
            break;
        default:
            $count_date = date('Y-m-d');
    }
    $query = "SELECT SUM(amount) as amount FROM vtiger_potential INNER JOIN vtiger_potentialscf ON vtiger_potential.potentialid = vtiger_potentialscf.potentialid INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_potential.sales_stage = ? AND vtiger_crmentity.createdtime > ?";
    $result = $adb->pquery($query, array('Closed Won', $count_date));
    if ($adb->num_rows($result) > 0) {
        $plan = $adb->query_result($result, 0, 'amount');
        if (!$plan) {
            $plan = 0;
        }
    } else {
        $plan = 0;
    }
    return $plan;
}