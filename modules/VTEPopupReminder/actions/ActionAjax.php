<?php

class VTEPopupReminder_ActionAjax_Action extends Vtiger_Action_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("updatePopupReminderDateTime");
        $this->exposeMethod("enableModule");
        $this->exposeMethod("checkEnable");
        $this->exposeMethod("updateRecordActivity");
    }
    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function checkEnable(Vtiger_Request $request)
    {
        global $adb;
        $rs = $adb->pquery("SELECT `enable` FROM `vtepopupreminder_settings`;", array());
        $enable = $adb->query_result($rs, 0, "enable");
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array("enable" => $enable));
        $response->emit();
    }
    public function enableModule(Vtiger_Request $request)
    {
        global $adb;
        $value = $request->get("value");
        $adb->pquery("UPDATE `vtepopupreminder_settings` SET `enable`=?", array($value));
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array("result" => "success"));
        $response->emit();
    }
    public function process(Vtiger_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
    public function updateRecordActivity(Vtiger_Request $request)
    {
        global $adb;
        global $current_user;
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $interval = $request->get("interval");
        $currentDate = date("Y-m-d H:i:s");
        $idCurrentUser = $current_user->id;
        $rs = $adb->pquery("select a.* from `vtiger_activity` AS a INNER JOIN vtiger_crmentity AS b ON a.activityid = b.crmid\r\n                            WHERE b.smownerid = ? AND CONCAT(a.`date_start`, ' ', a.`time_start`) >= ? AND b.deleted = 0 \r\n                            AND a.`activitytype` NOT in ('Emails') AND (a.`popup_reminder_date` IS NULL OR a.`popup_reminder_date` = '0000-00-00')", array($idCurrentUser, $currentDate));
        if (0 < $adb->num_rows($rs)) {
            while ($row = $adb->fetch_array($rs)) {
                $time_start = $row["time_start"];
                $date_start = $row["date_start"];
                $time = strtotime($date_start . " " . $time_start);
                $newtime = date("Y-m-d H:i:s", $time);
                $subtractTime = date_sub(date_create($newtime), date_interval_create_from_date_string($interval));
                $popup_reminder_date = date_format($subtractTime, "Y-m-d");
                $popup_reminder_time = date_format($subtractTime, "H:i:s");
                $adb->pquery("UPDATE `vtiger_activity` SET `popup_reminder_date`=?,`popup_reminder_time` =?  WHERE `activityid`= ?", array($popup_reminder_date, $popup_reminder_time, $row["activityid"]));
            }
        }
        $response->setResult("ok");
        return $response->emit();
    }
    public function updatePopupReminderDateTime(Vtiger_Request $request)
    {
        global $adb;
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $recordEvent = $request->get("recordEvent");
        $snooze = $request->get("snooze");
        $currentDate = date("Y-m-d H:i:s");
        $subtractTime = date_add(date_create($currentDate), date_interval_create_from_date_string($snooze));
        $popup_reminder_date = date_format($subtractTime, "Y-m-d");
        $popup_reminder_time = date_format($subtractTime, "H:i:s");
        if ($snooze == "Do Not Remind") {
            $popup_reminder_date = "2000-01-01";
            $popup_reminder_time = "00:00:00";
        }
        foreach ($recordEvent as $value) {
            $adb->pquery("UPDATE `vtiger_activity` SET `popup_reminder_date`=?,`popup_reminder_time` =?  WHERE `activityid`= ?", array($popup_reminder_date, $popup_reminder_time, $value));
        }
        $response->setResult("ok");
        return $response->emit();
    }
}

?>