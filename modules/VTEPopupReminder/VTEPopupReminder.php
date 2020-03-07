<?php

/**
 * Class VTEPopupReminder
 */
class VTEPopupReminder
{
    public function vtlib_handler($modulename, $event_type)
    {
        if ($event_type == "module.postinstall") {
            self::iniData();
            self::addWidgetTo($modulename);
            self::createHandle($modulename);
            self::checkEnable();
            self::updateSettings();
            self::addSettings();
            self::updateFieldUser();
            self::resetValid();
        } else {
            if ($event_type == "module.disabled") {
                self::removeSettings();
                self::removeWidgetTo($modulename);
                self::removeHandle();
            } else {
                if ($event_type == "module.enabled") {
                    self::addWidgetTo($modulename);
                    self::updateFieldUser();
                    self::createHandle($modulename);
                    self::addSettings();
                    self::updateRecordWhenEnable();
                } else {
                    if ($event_type == "module.preuninstall") {
                        self::removeValid();
                        self::removeSettings();
                        self::removeHandle();
                    } else {
                        if ($event_type == "module.preupdate") {
                            global $adb;
                            $sql = "ALTER TABLE `vtiger_activity_reminder_popup` ADD INDEX `date_time_index` (`date_start`, `time_start`) USING BTREE ;";
                            $adb->pquery($sql, array());
                        } else {
                            if ($event_type == "module.postupdate") {
                                self::removeWidgetTo($modulename);
                                self::addWidgetTo($modulename);
                                self::updateFieldUser();
                                self::removeSettings();
                                self::checkEnable();
                                self::addSettings();
                                self::updateFiles();
                                self::resetValid();
                                self::removeHandle();
                                self::createHandle($modulename);
                            }
                        }
                    }
                }
            }
        }
    }
    private function removeHandle()
    {
        global $adb;
        $em = new VTEventsManager($adb);
        $em->unregisterHandler("VTEPopupReminderHandler");
    }
    /**
     * @param string $moduleName
     */
    public static function addWidgetTo($moduleName)
    {
        global $adb;
        global $vtiger_current_version;
        $module = Vtiger_Module::getInstance($moduleName);
        $widgetName = "VTEPopupReminder";
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        if ($module) {
            $css_widgetType = "HEADERCSS";
            $css_widgetLabel = vtranslate($widgetName, $moduleName);
            $css_link = $template_folder . "/modules/" . $moduleName . "/resources/" . $moduleName . "CSS.css";
            $js_widgetType = "HEADERSCRIPT";
            $js_widgetLabel = vtranslate($widgetName, $moduleName);
            $js_link = $template_folder . "/modules/" . $moduleName . "/resources/" . $moduleName . "JS.js";
            $module->addLink($css_widgetType, $css_widgetLabel, $css_link);
            $module->addLink($js_widgetType, $js_widgetLabel, $js_link);
        }
        $sql = "ALTER TABLE `vtiger_activity_reminder_popup` ADD INDEX `date_time_index` (`date_start`, `time_start`) USING BTREE ;";
        $adb->pquery($sql, array());
    }
    /**
     * @param string $moduleName
     */
    public static function removeWidgetTo($moduleName)
    {
        global $adb;
        global $vtiger_current_version;
        $module = Vtiger_Module::getInstance($moduleName);
        $widgetName = "VTEPopupReminder";
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        if ($module) {
            $css_widgetType = "HEADERCSS";
            $css_widgetLabel = vtranslate($widgetName, $moduleName);
            $css_link = $template_folder . "/modules/" . $moduleName . "/resources/" . $moduleName . "CSS.css";
            $js_widgetType = "HEADERSCRIPT";
            $js_widgetLabel = vtranslate($widgetName, $moduleName);
            $js_link = $template_folder . "/modules/" . $moduleName . "/resources/" . $moduleName . "JS.js";
            $module->deleteLink($css_widgetType, $css_widgetLabel, $css_link);
            $module->deleteLink($js_widgetType, $js_widgetLabel, $js_link);
        }
    }
    private function createHandle($moduleName)
    {
        include_once "include/events/VTEventsManager.inc";
        global $adb;
        $em = new VTEventsManager($adb);
        $em->setModuleForHandler($moduleName, (string) $moduleName . "Handler.php");
        $em->registerHandler("vtiger.entity.aftersave", "modules/" . $moduleName . "/" . $moduleName . "Handler.php", (string) $moduleName . "Handler");
    }
    public static function updateSettings()
    {
        global $adb;
        $adb->pquery("UPDATE `vtiger_users` SET `reminder_interval`='1 Hour'", array());
        self::updateRecordActivity("1 Hour");
    }
    public static function updateRecordWhenEnable()
    {
        global $adb;
        $rsInterval = $adb->pquery("SELECT `reminder_interval` FROM `vtiger_users`", array());
        $interval = $adb->query_result($rsInterval, 0, "reminder_interval");
        self::updateRecordActivity($interval);
    }
    public static function updateRecordActivity($reminderInterval)
    {
        global $adb;
        $currentDate = date("Y-m-d H:i:s");
        $rs = $adb->pquery("select a.* from `vtiger_activity` AS a INNER JOIN vtiger_crmentity AS b ON a.activityid = b.crmid\r\n                            WHERE CONCAT(a.`date_start`, ' ', a.`time_start`) >= ? AND b.deleted = 0 \r\n                            AND a.`activitytype` NOT in ('Emails') AND (a.`popup_reminder_date` IS NULL OR a.`popup_reminder_date` = '0000-00-00')", array($currentDate));
        if (0 < $adb->num_rows($rs)) {
            while ($row = $adb->fetch_array($rs)) {
                $time_start = $row["time_start"];
                $date_start = $row["date_start"];
                $time = strtotime($date_start . " " . $time_start);
                $newtime = date("Y-m-d H:i:s", $time);
                $subtractTime = date_sub(date_create($newtime), date_interval_create_from_date_string($reminderInterval));
                $popup_reminder_date = date_format($subtractTime, "Y-m-d");
                $popup_reminder_time = date_format($subtractTime, "H:i:s");
                $adb->pquery("UPDATE `vtiger_activity` SET `popup_reminder_date`=?,`popup_reminder_time` =?  WHERE `activityid`= ?", array($popup_reminder_date, $popup_reminder_time, $row["activityid"]));
            }
        }
    }
    public static function updateFieldUser()
    {
        global $adb;
        global $vtiger_current_version;
        $rs = $adb->pquery("SELECT `reminder_interval` FROM `vtiger_reminder_interval`", array());
        if (0 < $adb->num_rows($rs)) {
            $listReminder = array();
            while ($row = $adb->fetchByAssoc($rs)) {
                $listReminder[] = $row["reminder_interval"];
            }
            $listUpdate = array("2 Hours", "4 Hours", "6 Hours", "12 Hours", "2 Days", "3 Days", "5 Days", "Do Not Remind");
            $startSort = 7;
            foreach ($listUpdate as $value) {
                if (!in_array($value, $listReminder)) {
                    $startSort++;
                    $adb->pquery("INSERT INTO `vtiger_reminder_interval` (`reminder_interval`, `sortorderid`, `presence`) VALUES (?, ?, ?)", array($value, $startSort, "1"));
                }
            }
        }
        $moduleInstance = Vtiger_Module::getInstance("Events");
        $blockInstance = Vtiger_Block::getInstance("LBL_REMINDER_INFORMATION", $moduleInstance);
        if ($blockInstance) {
            $popup_reminder_date = Vtiger_Field::getInstance("popup_reminder_date", $moduleInstance);
            if (!$popup_reminder_date) {
                $popup_reminder_date = new Vtiger_Field();
                $popup_reminder_date->label = "Popup Reminder Date";
                $popup_reminder_date->name = "popup_reminder_date";
                $popup_reminder_date->table = "vtiger_activity";
                $popup_reminder_date->column = "popup_reminder_date";
                $popup_reminder_date->columntype = "DATE";
                $popup_reminder_date->uitype = 666;
                $popup_reminder_date->typeofdata = "D~O";
                $popup_reminder_date->displaytype = 2;
                $popup_reminder_date->quickcreate = 1;
                $blockInstance->addField($popup_reminder_date);
            }
            $popup_reminder_time = Vtiger_Field::getInstance("popup_reminder_time", $moduleInstance);
            if (!$popup_reminder_time) {
                $popup_reminder_time = new Vtiger_Field();
                $popup_reminder_time->label = "Popup Reminder Time";
                $popup_reminder_time->name = "popup_reminder_time";
                $popup_reminder_time->table = "vtiger_activity";
                $popup_reminder_time->column = "popup_reminder_time";
                $popup_reminder_time->columntype = "TIME";
                $popup_reminder_time->uitype = 2;
                $popup_reminder_time->typeofdata = "T~O";
                $popup_reminder_time->displaytype = 3;
                $blockInstance->addField($popup_reminder_time);
            }
        }
        $moduleCalendarInstance = Vtiger_Module::getInstance("Calendar");
        $blockInstance = Vtiger_Block::getInstance("Reminder Details", $moduleCalendarInstance);
        if (!$blockInstance) {
            $blockInstance = new Vtiger_Block();
            $blockInstance->label = "Reminder Details";
            $moduleCalendarInstance->addBlock($blockInstance);
        }
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $rs = $adb->pquery("SELECT * FROM `vtiger_field` WHERE `tabid` = ? AND `fieldname` =?", array(9, "popup_reminder_date"));
            if ($adb->num_rows($rs) == 0) {
                $maxId = $adb->pquery("SELECT * FROM `vtiger_field_seq`", array());
                $rsMaxId = $adb->query_result($maxId, 0, "id");
                $rsMaxId++;
                $blockId = $blockInstance->id;
                $adb->pquery("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreatesequence`, `info_type`)\r\n                          VALUES ('9', " . $rsMaxId . ", 'popup_reminder_date', 'vtiger_activity', '1', '666', 'popup_reminder_date', 'Popup Reminder Date', '1', '2', '100', '2', " . $blockId . ", '2', 'D~O', '0', 'BAS')", array());
                $rsMaxId++;
                $adb->pquery("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreatesequence`, `info_type`)\r\n                          VALUES ('9', " . $rsMaxId . ", 'popup_reminder_time', 'vtiger_activity', '1', '2', 'popup_reminder_time', 'Popup Reminder Time', '1', '2', '100', '3', " . $blockId . ", '3', 'T~O', '0', 'BAS')", array());
            }
        } else {
            $popup_reminder_date = Vtiger_Field::getInstance("popup_reminder_date", $moduleCalendarInstance);
            if (!$popup_reminder_date) {
                $popup_reminder_date = new Vtiger_Field();
                $popup_reminder_date->label = "Popup Reminder Date";
                $popup_reminder_date->name = "popup_reminder_date";
                $popup_reminder_date->table = "vtiger_activity";
                $popup_reminder_date->column = "popup_reminder_date";
                $popup_reminder_date->columntype = "DATE";
                $popup_reminder_date->uitype = 666;
                $popup_reminder_date->typeofdata = "D~O";
                $popup_reminder_date->displaytype = 2;
                $popup_reminder_date->quickcreate = 1;
                $blockInstance->addField($popup_reminder_date);
            }
            $popup_reminder_time = Vtiger_Field::getInstance("popup_reminder_time", $moduleCalendarInstance);
            if (!$popup_reminder_time) {
                $popup_reminder_time = new Vtiger_Field();
                $popup_reminder_time->label = "Popup Reminder Time";
                $popup_reminder_time->name = "popup_reminder_time";
                $popup_reminder_time->table = "vtiger_activity";
                $popup_reminder_time->column = "popup_reminder_time";
                $popup_reminder_time->columntype = "TIME";
                $popup_reminder_time->uitype = 2;
                $popup_reminder_time->typeofdata = "T~O";
                $popup_reminder_time->displaytype = 3;
                $blockInstance->addField($popup_reminder_time);
            }
        }
        $sql = "ALTER TABLE `vtiger_activity` ADD INDEX `popup_reminder_date_index` (`popup_reminder_date`) USING BTREE ;";
        $adb->pquery($sql, array());
        $sql = "ALTER TABLE `vtiger_activity` ADD INDEX `popup_reminder_time_index` (`popup_reminder_time`) USING BTREE ;";
        $adb->pquery($sql, array());
        $sql = "ALTER TABLE `vtiger_activity` ADD INDEX `popup_reminder_date_time_index` (`popup_reminder_date`,`popup_reminder_time`) USING BTREE ;";
        $adb->pquery($sql, array());
        $adb->pquery("UPDATE vtiger_field SET displaytype='2'  WHERE columnname='popup_reminder_date'", array());
    }
    public static function resetValid()
    {
        global $adb;
        $adb->pquery("DELETE FROM `vte_modules` WHERE module=?;", array("VTEPopupReminder"));
        $adb->pquery("INSERT INTO `vte_modules` (`module`, `valid`) VALUES (?, ?);", array("VTEPopupReminder", "0"));
    }
    public static function removeValid()
    {
        global $adb;
        $adb->pquery("DELETE FROM `vte_modules` WHERE module=?;", array("VTEPopupReminder"));
    }
    public static function checkEnable()
    {
        global $adb;
        $rs = $adb->pquery("SELECT `enable` FROM `vtepopupreminder_settings`;", array());
        if ($adb->num_rows($rs) == 0) {
            $adb->pquery("INSERT INTO `vtepopupreminder_settings` (`enable`) VALUES ('1');", array());
        }
    }
    public static function addSettings()
    {
        global $adb;
        $blockid = 4;
        $res = $adb->pquery("SELECT blockid FROM `vtiger_settings_blocks` WHERE label='LBL_OTHER_SETTINGS'", array());
        if (0 < $adb->num_rows($res)) {
            while ($row = $adb->fetch_row($res)) {
                $blockid = $row["blockid"];
            }
        }
        $adb->pquery("UPDATE vtiger_settings_field_seq SET id=(SELECT MAX(fieldid) FROM vtiger_settings_field)", array());
        $max_id = $adb->getUniqueID("vtiger_settings_field");
        $adb->pquery("INSERT INTO `vtiger_settings_field` (`fieldid`, `blockid`, `name`, `description`, `linkto`, `sequence`) VALUES (?, ?, ?, ?, ?, ?)", array($max_id, $blockid, "Activity Reminder (Popup)", "Settings area for VTE Popup Reminder", "index.php?module=VTEPopupReminder&parent=Settings&view=Settings", $max_id));
    }
    public static function removeSettings()
    {
        global $adb;
        $adb->pquery("DELETE FROM `vtiger_settings_field` WHERE `name` = ?", array("VTE Popup Reminder"));
        $adb->pquery("DELETE FROM `vtiger_settings_field` WHERE `name` = ?", array("Activity Reminder (Popup)"));
    }
    public static function iniData()
    {
        global $adb;
        global $vtiger_current_version;
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        $moduleFolder = "modules/Vtiger/uitypes";
        $templateFolder = $template_folder . "/modules/Vtiger/uitypes";
        self::recurse_copy("modules/VTEPopupReminder/uitypes", $moduleFolder);
        self::recurse_copy($template_folder . "/modules/VTEPopupReminder/uitypes", $templateFolder);
        $adb->pquery("INSERT INTO `vtiger_ws_fieldtype` (`uitype`, `fieldtype`) VALUES (?,?);", array("666", "datetimereminder"));
    }
    public static function updateFiles()
    {
        global $adb;
        global $vtiger_current_version;
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        if (is_writeable($template_folder . "/modules/Vtiger/uitypes/DateTimeReminder.tpl")) {
            unlink($template_folder . "/modules/Vtiger/uitypes/DateTimeReminder.tpl");
        } else {
            chmod($template_folder . "/modules/Vtiger/uitypes/DateTimeReminder.tpl", 438);
            unlink($template_folder . "/modules/Vtiger/uitypes/DateTimeReminder.tpl");
        }
        if (is_writeable("modules/Vtiger/uitypes/Datetimereminder.php")) {
            unlink("modules/Vtiger/uitypes/Datetimereminder.php");
        } else {
            chmod("modules/Vtiger/uitypes/Datetimereminder.php", 438);
            unlink("modules/Vtiger/uitypes/Datetimereminder.php");
        }
        $moduleFolder = "modules/Vtiger/uitypes";
        $templateFolder = $template_folder . "/modules/Vtiger/uitypes";
        self::recurse_copy("modules/VTEPopupReminder/uitypes", $moduleFolder);
        self::recurse_copy($template_folder . "/modules/VTEPopupReminder/uitypes", $templateFolder);
        $rs = $adb->pquery("select * from vtiger_ws_fieldtype where fieldtype = ?", array("datetimereminder"));
        if ($adb->num_rows($rs) == 0) {
            $adb->pquery("INSERT INTO `vtiger_ws_fieldtype` (`uitype`, `fieldtype`) VALUES (?,?);", array("666", "datetimereminder"));
        }
    }
    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                if (is_dir($src . "/" . $file)) {
                    $result = self::recurse_copy($src . "/" . $file, $dst . "/" . $file);
                } else {
                    $result = copy($src . "/" . $file, $dst . "/" . $file);
                }
            }
        }
        closedir($dir);
    }
}

?>