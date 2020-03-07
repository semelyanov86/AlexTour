<?php

/**
 * Class VTEPopupReminder_MassActionAjax_View
 */
class VTEPopupReminder_MassActionAjax_View extends Vtiger_Index_View
{
    /**
     * @param Vtiger_Request $request
     */
    public function checkPermission(Vtiger_Request $request)
    {
    }
    /**
     * @param Vtiger_Request $request
     */
    public function process(Vtiger_Request $request)
    {
        global $current_user;
        $currentDate = date_create(date("Y-m-d H:i:s"));
        $activitiesRecord = VTEPopupReminder_Record_Model::getRecordActivity();
        $clickShowModal = $request->get("isShow");
        $reminder_interval = $current_user->reminder_interval;
        $listActivity = array();
        $activityRecordIsShow = $activitiesRecord["isShow"];
        if ($clickShowModal == "isShow") {
            $activityRecordIsShow = "isShow";
        }
        if ($reminder_interval == "Do Not Remind" || count($activitiesRecord) == 0 || $activityRecordIsShow != "isShow") {
            $response = new Vtiger_Response();
            $response->setEmitType(Vtiger_Response::$EMIT_JSON);
            $response->setResult("notShow");
            $response->emit();
        } else {
            $snooze = VTEPopupReminder_Record_Model::getAllSnooze();
            if (!empty($activitiesRecord)) {
                $index = 0;
                unset($activitiesRecord["isShow"]);
                foreach ($activitiesRecord as $value) {
                    $popupReminderDateTime = strtotime(date("Y-m-d H:i:s", strtotime($value["popup_reminder_date"] . " " . $value["popup_reminder_time"])));
                    if (strtotime(date("Y-m-d H:i:s")) < $popupReminderDateTime && $clickShowModal != "isShow") {
                        continue;
                    }
                    $startDateTime = date_create(date("Y-m-d H:i:s", strtotime($value["date_start"] . " " . $value["time_start"])));
                    $interval = date_diff($startDateTime, $currentDate);
                    $interval->format("%h Hours %i Minute %s Seconds");
                    $total_days = $interval->days;
                    $total_hours = $interval->h;
                    if ($total_days !== false || $total_days != "0") {
                        $total_hours += 24 * $total_days;
                    }
                    $total_minutes = $interval->i;
                    $dueIn = "";
                    $isRed = "";
                    $dueInHours = $total_hours . " Hrs ";
                    $dueInMin = $total_minutes . " Min";
                    if ($total_hours == 0) {
                        $dueInHours = "";
                    }
                    if ($startDateTime < $currentDate) {
                        if ($total_minutes == 0 && $total_days == 0 && $total_hours == 0) {
                            $dueIn = "Now";
                            $isRed = "green";
                        } else {
                            $dueIn = "- " . $dueInHours . $dueInMin;
                            $isRed = "red";
                        }
                    } else {
                        $dueIn = $dueInHours . $dueInMin;
                    }
                    $ActivitesRecordModel = Vtiger_Record_Model::getInstanceById($value["activityid"]);
                    $activityType = $ActivitesRecordModel->getType();
                    if ($activityType == "Events") {
                        $moduleName = "Events";
                    } else {
                        $moduleName = $ActivitesRecordModel->getModuleName();
                    }
                    $detailViewModel = Vtiger_DetailView_Model::getInstance($moduleName, $value["activityid"]);
                    $ActivitesRecordModel = $detailViewModel->getRecord();
                    $relatedContacts = self::getRelatedContactInfo($value["activityid"]);
                    foreach ($relatedContacts as $key => $contactInfo) {
                        $contactRecordModel = Vtiger_Record_Model::getCleanInstance("Contacts");
                        $contactRecordModel->setId($contactInfo["id"]);
                        $contactInfo["_model"] = $contactRecordModel;
                        $relatedContacts[$key] = $contactInfo;
                    }
                    $startDateTimeByUser = DateTimeField::convertToUserTimeZone(date("Y-m-d H:i:s", strtotime($value["date_start"] . " " . $value["time_start"])));
                    $startDateTimeByUserFormat = DateTimeField::convertToUserFormat($startDateTimeByUser->format("Y-m-d H:i"));
                    $dueDateTimeByUser = DateTimeField::convertToUserTimeZone(date("Y-m-d H:i:s", strtotime($value["due_date"] . " " . $value["time_end"])));
                    $dueDateTimeByUserFormat = DateTimeField::convertToUserFormat($dueDateTimeByUser->format("Y-m-d H:i"));
                    list($startDate, $startTime) = explode(" ", $startDateTimeByUserFormat);
                    list($dueDate, $endTime) = explode(" ", $dueDateTimeByUserFormat);
                    $currentUser = Users_Record_Model::getCurrentUserModel();
                    if ($currentUser->get("hour_format") == "12") {
                        $startTime = Vtiger_Time_UIType::getTimeValueInAMorPM($startTime);
                        $endTime = Vtiger_Time_UIType::getTimeValueInAMorPM($endTime);
                    }
                    $startAt = $startDate . " " . $startTime;
                    $listActivity[$index]["activityid"] = $ActivitesRecordModel->get("activityid");
                    $listActivity[$index]["subject"] = $ActivitesRecordModel->get("subject");
                    $listActivity[$index]["url"] = $ActivitesRecordModel->getDetailViewUrl();
                    $listActivity[$index]["activityid"] = $ActivitesRecordModel->getId();
                    $listActivity[$index]["activitytype"] = $ActivitesRecordModel->get("activitytype");
                    $listActivity[$index]["description"] = nl2br($ActivitesRecordModel->get("description"));
                    $listActivity[$index]["contacts"] = $relatedContacts;
                    $listActivity[$index]["startsat"] = $startAt;
                    $listActivity[$index]["relatedto"] = $ActivitesRecordModel->getDisplayValue("parent_id");
                    $listActivity[$index]["duein"] = $dueIn;
                    $listActivity[$index]["isred"] = $isRed;
                    $listActivity[$index]["module"] = $moduleName;
                    $index++;
                }
            }
            $viewer = $this->getViewer($request);
            $viewer->assign("MODULE_NAME", "VTEPopupReminder");
            $viewer->assign("ACTIVES", "VTEPopupReminder");
            $viewer->assign("SNOOZE", $snooze);
            $viewer->assign("LISTACTIVITY", $listActivity);
            $viewer->assign("REMINDER_INTERVAL", $reminder_interval);
            $viewer->assign("MODULE_RECORD", $moduleName);
            echo $viewer->view("PopupReminder.tpl", "VTEPopupReminder", true);
        }
    }
    public function getRelatedContactInfo($calendarid)
    {
        $contactIdList = self::getRelatedToContactIdList($calendarid);
        $relatedContactInfo = array();
        foreach ($contactIdList as $contactId) {
            $relatedContactInfo[] = array("name" => decode_html(Vtiger_Util_Helper::toSafeHTML(Vtiger_Util_Helper::getRecordName($contactId))), "id" => $contactId);
        }
        return $relatedContactInfo;
    }
    public function getRelatedToContactIdList($calendarid)
    {
        $adb = PearDatabase::getInstance();
        $query = "SELECT * from vtiger_cntactivityrel where activityid=?";
        $result = $adb->pquery($query, array($calendarid));
        $num_rows = $adb->num_rows($result);
        $contactIdList = array();
        for ($i = 0; $i < $num_rows; $i++) {
            $row = $adb->fetchByAssoc($result, $i);
            $contactIdList[$i] = $row["contactid"];
        }
        return $contactIdList;
    }
}

?>