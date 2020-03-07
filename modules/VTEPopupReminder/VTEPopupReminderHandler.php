<?php

require_once "include/events/VTEventHandler.inc";
class VTEPopupReminderHandler extends VTEventHandler
{
    public function handleEvent($eventName, $data)
    {
        global $adb;
        global $current_user;
        if ($eventName == "vtiger.entity.aftersave") {
            $moduleId = $data->getId();
            $moduleName = $data->getModuleName();
            $entityData = $data->getData();
            if ($moduleName == "Users") {
                $currentDate = date("Y-m-d H:i:s");
                $interval = $data->get("reminder_interval");
                $idCurrentUser = $current_user->id;
                $rs = $adb->pquery("select a.* from `vtiger_activity` as a \r\n                            INNER JOIN vtiger_crmentity AS b ON a.activityid = b.crmid\r\n                            WHERE b.smownerid = ? AND CONCAT(a.`date_start`, ' ', a.`time_start`) >= ? \r\n                            AND a.activitytype NOT in ('Emails') AND (a.`popup_reminder_date` IS NULL OR a.`popup_reminder_date` = '0000-00-00')", array($idCurrentUser, $currentDate));
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
            }
            $reminder_interval = $current_user->reminder_interval;
            $time_start = $data->get("time_start");
            $date_start = $data->get("date_start");
            $time = strtotime($date_start . " " . $time_start);
            $newtime = date("Y-m-d H:i:s", $time);
            $subtractTime = date_sub(date_create($newtime), date_interval_create_from_date_string($reminder_interval));
            $popup_reminder_date = date_format($subtractTime, "Y-m-d");
            $popup_reminder_time = date_format($subtractTime, "H:i:s");
            if ($reminder_interval == "Do Not Remind") {
                $popup_reminder_date = "2000-01-01";
                $popup_reminder_time = "00:00:00";
            }
            if ($moduleName != "Events" && $moduleName != "Calendar" || $_REQUEST["isWfReminder"] || $data->get("popup_reminder_date") != "") {
                if ($data->get("popup_reminder_date") != "" && $_REQUEST["isWfReminder"] != true && ($moduleName == "Events" || $moduleName == "Calendar")) {
                    $entityDelta = new VTEntityDelta();
                    $isDate_startChanged = $entityDelta->hasChanged($moduleName, $moduleId, "date_start");
                    $isTime_startChanged = $entityDelta->hasChanged($moduleName, $moduleId, "time_start");
                    if ($isDate_startChanged || $isTime_startChanged) {
                        $adb->pquery("UPDATE `vtiger_activity` SET `popup_reminder_date`=? , `popup_reminder_time` = ? WHERE `activityid`= ? ", array($popup_reminder_date, $popup_reminder_time, $moduleId));
                    }
                }
                return NULL;
            }
            $_REQUEST["isWfReminder"] = true;
            $query = "UPDATE `vtiger_activity` SET `popup_reminder_date` = ?, `popup_reminder_time` = ? WHERE `activityid` = ?";
            $adb->pquery($query, array($popup_reminder_date, $popup_reminder_time, $moduleId));
        }
    }
}

?>