<?php

class VTEPopupReminder_Record_Model extends Vtiger_Record_Model
{
    public static function getRecordActivity()
    {
        global $adb;
        global $current_user;
        $userId = $current_user->id;
        $currentDate = strtotime(date("Y-m-d H:i:s"));
        $rs = $adb->pquery("SELECT\r\n                                a.*\r\n                            FROM\r\n                                `vtiger_activity` AS a\r\n                            INNER JOIN vtiger_crmentity AS b ON a.activityid = b.crmid\r\n                            WHERE\r\n                            b.smownerid = ? AND\r\n                            a.`activitytype` NOT IN ('Emails') AND (a.`eventstatus` NOT IN ('Held', 'Completed', '') OR a.status NOT IN ('Held', 'Completed'))\r\n                            AND b.deleted = 0 AND a.popup_reminder_date != '2000-01-01' ORDER BY a.date_start, a.time_start ASC", array($userId));
        $record = array();
        if (0 < $adb->num_rows($rs)) {
            while ($row = $adb->fetchByAssoc($rs)) {
                $popupReminderDateTime = strtotime(date("Y-m-d H:i:s", strtotime($row["popup_reminder_date"] . " " . $row["popup_reminder_time"])));
                if (strtotime(date("Y-m-d", strtotime($row["popup_reminder_date"]))) <= strtotime(date("Y-m-d"))) {
                    if ($popupReminderDateTime <= $currentDate) {
                        $record["isShow"] = "isShow";
                    }
                    $record[] = $row;
                }
            }
        }
        return $record;
    }
    public static function getAllSnooze()
    {
        global $adb;
        $rs = $adb->pquery("SELECT `reminder_interval` FROM `vtiger_reminder_interval`", array());
        $record = array();
        $record[] = "Select options";
        if (0 < $adb->num_rows($rs)) {
            while ($row = $adb->fetchByAssoc($rs)) {
                $record[] = $row["reminder_interval"];
            }
        }
        return $record;
    }
}

?>