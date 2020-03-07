<?php

class VTEPopupReminder_SaveAjax_Action extends Vtiger_Save_Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function process(Vtiger_Request $request)
    {
        global $adb;
        if ($request->get("editmode") != "") {
            $recordModel = $this->saveRecord($request);
            if ($request->get("sourceModule") == "Events") {
                $arrRelatedId = explode(",", $request->get("related_id"));
                $focus = $recordModel->getEntity();
                if (0 < count($arrRelatedId)) {
                    $adb->pquery("DELETE from vtiger_seactivityrel WHERE activityid = ?", array($recordModel->getId()));
                    $count = count($arrRelatedId);
                    $sql = "INSERT INTO vtiger_seactivityrel VALUES ";
                    for ($i = 0; $i < $count; $i++) {
                        if ($arrRelatedId[$i] != "" && $arrRelatedId[$i] != 0) {
                            $sql .= " (" . $arrRelatedId[$i] . ", " . $recordModel->getId() . ")";
                            if ($i != $count - 1) {
                                $sql .= ",";
                            }
                        }
                    }
                    $adb->pquery($sql, array());
                }
                if ($request->get("contactids") != "") {
                    $adb->pquery("DELETE from vtiger_cntactivityrel WHERE activityid = ?", array($recordModel->getId()));
                    $contactIdsList = explode(",", $request->get("contactids"));
                    $count = count($contactIdsList);
                    $sql = "INSERT INTO vtiger_cntactivityrel VALUES ";
                    for ($i = 0; $i < $count; $i++) {
                        $sql .= " (" . $contactIdsList[$i] . ", " . $recordModel->getId() . ")";
                        if ($i != $count - 1) {
                            $sql .= ",";
                        }
                    }
                    $adb->pquery($sql, array());
                }
                $followupMode = $request->get("followup");
                if ($followupMode == "on") {
                    $oldId = $recordModel->getId();
                    $seRecordsRel = array();
                    $contactRecordsRel = array();
                    $rsSeRecordsRel = $adb->pquery("SELECT * FROM vtiger_seactivityrel WHERE activityid=? LIMIT 1", array($oldId));
                    if (0 < $adb->num_rows($rsSeRecordsRel)) {
                        while ($rowSeRecordsRel = $adb->fetch_array($rsSeRecordsRel)) {
                            if (!in_array($rowSeRecordsRel["crmid"], $seRecordsRel)) {
                                $seRecordsRel[] = $rowSeRecordsRel["crmid"];
                            }
                        }
                    }
                    $rsContactRecordsRel = $adb->pquery("SELECT * FROM vtiger_cntactivityrel WHERE activityid=? LIMIT 1", array($oldId));
                    if (0 < $adb->num_rows($rsContactRecordsRel)) {
                        while ($rowContactRecordsRel = $adb->fetch_array($rsContactRecordsRel)) {
                            if (!in_array($rowContactRecordsRel["contactid"], $contactRecordsRel)) {
                                $contactRecordsRel[] = $rowContactRecordsRel["contactid"];
                            }
                        }
                    }
                    $startTime = Vtiger_Time_UIType::getTimeValueWithSeconds($request->get("followup_time_start"));
                    $startDateTime = Vtiger_Datetime_UIType::getDBDateTimeValue($request->get("followup_date_start") . " " . $startTime);
                    list($startDate, $startTime) = explode(" ", $startDateTime);
                    $subject = $request->get("subject");
                    if ($startTime != "" && $startDate != "") {
                        $recordModel->set("eventstatus", "Planned");
                        $recordModel->set("subject", $subject);
                        $recordModel->set("date_start", $startDate);
                        $recordModel->set("time_start", $startTime);
                        $currentUser = Users_Record_Model::getCurrentUserModel();
                        $activityType = $recordModel->get("activitytype");
                        if ($activityType == "Call") {
                            $minutes = $currentUser->get("callduration");
                        } else {
                            $minutes = $currentUser->get("othereventduration");
                        }
                        $dueDateTime = date("Y-m-d H:i:s", strtotime((string) $startDateTime . "+" . $minutes . " minutes"));
                        list($endDate, $endTime) = explode(" ", $dueDateTime);
                        $recordModel->set("due_date", $endDate);
                        $recordModel->set("time_end", $endTime);
                        $recordModel->set("mode", "create");
                        $recordModel->save();
                        $eventId = $recordModel->getId();
                        if (0 < count($seRecordsRel)) {
                            foreach ($seRecordsRel as $opptid) {
                                $adb->pquery("INSERT INTO vtiger_seactivityrel VALUES (?,?)", array($opptid, $eventId));
                            }
                        }
                        if (0 < count($contactRecordsRel)) {
                            foreach ($contactRecordsRel as $contactId) {
                                $adb->pquery("INSERT INTO vtiger_cntactivityrel VALUES (?,?)", array($contactId, $eventId));
                            }
                        }
                    }
                }
            }
            $result = array();
            $result["_recordLabel"] = $recordModel->getName();
            $result["_recordId"] = $recordModel->getId();
            $result["_recordModule"] = $request->get("sourceModule");
        } else {
            $result["_recordId"] = "";
        }
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult($result);
        $response->emit();
    }
    /**
     * Function to save record
     * @param <Vtiger_Request> $request - values of the record
     * @return <RecordModel> - record Model of saved record
     */
    public function saveRecord($request)
    {
        $recordModel = $this->getRecordModelFromRequest($request);
        $recordModel->save();
        return $recordModel;
    }
    /**
     * Function to get the record model based on the request parameters
     * @param Vtiger_Request $request
     * @return Vtiger_Record_Model or Module specific Record Model instance
     */
    protected function getRecordModelFromRequest(Vtiger_Request $request)
    {
        $moduleName = $request->get("sourceModule");
        $recordId = $request->get("record");
        $editmode = $request->get("editmode");
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        if (!empty($recordId)) {
            $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
            $modelData = $recordModel->getData();
            $recordModel->set("id", $recordId);
            $recordModel->set("mode", "edit");
        } else {
            if ($editmode == "create") {
                $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
                $modelData = $recordModel->getData();
                $recordModel->set("mode", $editmode);
            }
        }
        $fieldModelList = $moduleModel->getFields();
        foreach ($fieldModelList as $fieldName => $fieldModel) {
            $fieldValue = $request->get($fieldName, NULL);
            $fieldDataType = $fieldModel->getFieldDataType();
            if ($fieldDataType == "time") {
                $fieldValue = Vtiger_Time_UIType::getTimeValueWithSeconds($fieldValue);
            }
            if ($fieldValue !== NULL && ($moduleName != "Events" || $moduleName == "Events" && $fieldName != "parent_id")) {
                if (!is_array($fieldValue)) {
                    $fieldValue = trim($fieldValue);
                }
                $recordModel->set($fieldName, $fieldValue);
            }
        }
        if ($moduleName == "Events") {
            $startTime = Vtiger_Time_UIType::getTimeValueWithSeconds($request->get("time_start"));
            $startDateTime = Vtiger_Datetime_UIType::getDBDateTimeValue($request->get("date_start") . " " . $startTime);
            list($startDate, $startTime) = explode(" ", $startDateTime);
            $recordModel->set("date_start", $startDate);
            $recordModel->set("time_start", $startTime);
            $endTime = $request->get("time_end");
            $endDate = Vtiger_Date_UIType::getDBInsertedValue($request->get("due_date"));
            if ($endTime) {
                $endTime = Vtiger_Time_UIType::getTimeValueWithSeconds($endTime);
                $endDateTime = Vtiger_Datetime_UIType::getDBDateTimeValue($request->get("due_date") . " " . $endTime);
                list($endDate, $endTime) = explode(" ", $endDateTime);
            }
            $recordModel->set("time_end", $endTime);
            $recordModel->set("due_date", $endDate);
            $activityType = $request->get("activitytype");
            if (empty($activityType)) {
                $recordModel->set("activitytype", "Task");
                $recordModel->set("visibility", "Private");
            }
            $setReminder = $request->get("set_reminder");
            if ($setReminder) {
                $_REQUEST["set_reminder"] = "Yes";
            } else {
                $_REQUEST["set_reminder"] = "No";
            }
            $time = strtotime($request->get("due_date")) - strtotime($request->get("date_start"));
            $hours = (double) $time / 3600;
            $minutes = ((double) $hours - (int) $hours) * 60;
            $recordModel->set("duration_hours", (int) $hours);
            $recordModel->set("duration_minutes", $minutes);
        }
        return $recordModel;
    }
}

?>