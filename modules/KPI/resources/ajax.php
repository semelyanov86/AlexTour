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
require_once "includes/http/Response.php";
//ini_set("display_errors", 0);
//error_reporting(32767 & ~2 & ~8 & ~8192 & ~2048);
$adb = PearDatabase::getInstance();
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
$kpiday = 2610;
$kpimonth = 2609;
$kpiyear = 2611;
$currentdate = getdate();

foreach ( $currentdate as $key => $val )
{
    $currentday = $currentdate['mday'];
    $currentmonth = $currentdate['mon'];
    $currentyear = $currentdate['year'];
}

$datestartmonth = date_create($currentyear . '-' . $currentmonth . '-01');
$datecurrentmonth = date_create($currentyear . '-' . $currentmonth . '-' . $currentday);

$diffdays = (date_diff($datestartmonth, $datecurrentmonth)->days)+1;

$targetmonth = getKPIByQuery($kpimonth) - getFactByPeriod('month');

$weekdays = getWeekdays($currentmonth, $currentyear);
$res = $weekdays - $diffdays + 10;
if($res === 0) {
    $targetday = 0;
} else {
    $targetday = $targetmonth/$res;
}


$data = array();
$data['day']['target'] = $targetday;
$data['day']['current'] = getFactByPeriod('day');
$data['month']['target'] = getKPIByQuery($kpimonth);
$data['month']['current'] = getFactByPeriod('month');
$data['year']['target'] = getKPIByQuery($kpiyear);
$data['year']['current'] = getFactByPeriod('year');
$data['res'] = $res;
$data['weekdays'] = $weekdays;
$data['targetmonth'] = $targetmonth;
$response = new Vtiger_Response();
$response->setResult($data);
$response->emit();
//echo json_encode($data) . PHP_EOL;
//echo PHP_EOL;
//ob_flush();
//flush();
exit;

function getWeekdays($m, $y = NULL){
    $arrDtext = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri');

    if(is_null($y) || (!is_null($y) && $y == ''))
        $y = date('Y');

    $d = 1;
    $timestamp = mktime(0,0,0,$m,$d,$y);
    $lastDate = date('t', $timestamp);
    $workingDays = 0;
    for($i=$d; $i<=$lastDate; $i++){
        if(in_array(date('D', mktime(0,0,0,$m,$i,$y)), $arrDtext)){
            $workingDays++;
        }
    }
    return $workingDays;
}
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
            $count_date = getWeekendDate(date('Y-m-d') . ' ' . '20:15:00');
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
    $query = "SELECT SUM(amount) as amount FROM vtiger_potential INNER JOIN vtiger_potentialscf ON vtiger_potential.potentialid = vtiger_potentialscf.potentialid INNER JOIN vtiger_crmentity ON vtiger_potential.potentialid = vtiger_crmentity.crmid WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentity.createdtime > ?";
    $result = $adb->pquery($query, array($count_date));
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

function getWeekendDate($date)
{
    $dayofweek = date('w', strtotime($date));
    if ($dayofweek == '1') {
        return date('Y-m-d 20:15:00', strtotime('-3 days'));
    } else {
        return date('Y-m-d 20:15:00', strtotime('-1 days'));
    }
}
