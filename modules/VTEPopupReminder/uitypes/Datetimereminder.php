<?php

class Vtiger_DateTimeReminder_UIType extends Vtiger_Date_UIType
{
    public function getTemplateName()
    {
        return "uitypes/DateTimeReminder.tpl";
    }
    public function getDisplayValue($value, $record = false, $recordInstance = false)
    {
        if ($recordInstance) {
            $dateTimeValue = $value . " " . $recordInstance->get("popup_reminder_time");
            $value = $this->getDisplayDateTimeValue($dateTimeValue);
            list($startDate, $startTime) = explode(" ", $value);
            $currentUser = Users_Record_Model::getCurrentUserModel();
            if ($currentUser->get("hour_format") == "12") {
                $startTime = Vtiger_Time_UIType::getTimeValueInAMorPM($startTime);
            }
            return $startDate . " " . $startTime;
        }
        return parent::getDisplayValue($value, $record, $recordInstance);
    }
}

?>