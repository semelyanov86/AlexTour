<?php

include_once "vtlib/Vtiger/Module.php";
class VTEPopupReminder_Uninstall_View extends Settings_Vtiger_Index_View
{
    public function process(Vtiger_Request $request)
    {
        global $adb;
        global $vtiger_current_version;
        echo "<div class=\"container-fluid\">\r\n                <div class=\"widget_header row-fluid\">\r\n                    <h3>VTE Popup Reminder</h3>\r\n                </div>\r\n                <hr>";
        $module = Vtiger_Module::getInstance("VTEPopupReminder");
        if ($module) {
            $module->delete();
        }
        $moduleInstance = Vtiger_Module::getInstance("Events");
        if ($moduleInstance) {
            $popup_reminder_date = Vtiger_Field::getInstance("popup_reminder_date", $moduleInstance);
            if ($popup_reminder_date) {
                $popup_reminder_date->delete();
            }
            $popup_reminder_time = Vtiger_Field::getInstance("popup_reminder_time", $moduleInstance);
            if ($popup_reminder_time) {
                $popup_reminder_time->delete();
            }
        }
        $moduleCalendarInstance = Vtiger_Module::getInstance("Calendar");
        if ($moduleInstance) {
            $popup_reminder_date = Vtiger_Field::getInstance("popup_reminder_date", $moduleCalendarInstance);
            if ($popup_reminder_date) {
                $popup_reminder_date->delete();
            }
            $popup_reminder_time = Vtiger_Field::getInstance("popup_reminder_time", $moduleCalendarInstance);
            if ($popup_reminder_time) {
                $popup_reminder_time->delete();
            }
        }
        $message = $this->removeData();
        echo $message;
        $res_template = $this->delete_folder("layouts/vlayout/modules/VTEPopupReminder");
        echo "&nbsp;&nbsp;- Delete VTE Popup Reminder template folder";
        if ($res_template) {
            echo " - DONE";
        } else {
            echo " - <b>ERROR</b>";
        }
        echo "<br>";
        $res_template_v7 = $this->delete_folder("layouts/v7/modules/VTEPopupReminder");
        echo "&nbsp;&nbsp;- Delete VTE Popup Reminder template folder";
        if ($res_template_v7) {
            echo " - DONE";
        } else {
            echo " - <b>ERROR</b>";
        }
        echo "<br>";
        $res_module = $this->delete_folder("modules/VTEPopupReminder");
        echo "&nbsp;&nbsp;- Delete VTE Popup Reminder module folder";
        if ($res_module) {
            echo " - DONE";
        } else {
            echo " - <b>ERROR</b>";
        }
        echo "<br>";
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        if (is_writeable($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl")) {
            unlink($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl");
        } else {
            chmod($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl", 438);
            unlink($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl");
        }
        if (is_writeable("modules/Events/uitypes/Datetimereminder.php")) {
            unlink("modules/Events/uitypes/Datetimereminder.php");
        } else {
            chmod("modules/Events/uitypes/Datetimereminder.php", 438);
            unlink("modules/Events/uitypes/Datetimereminder.php");
        }
        echo "Module was Uninstalled.</div>";
    }
    public function delete_folder($tmp_path)
    {
        if (!is_writeable($tmp_path) && is_dir($tmp_path) && isFileAccessible($tmp_path)) {
            chmod($tmp_path, 511);
        }
        $handle = opendir($tmp_path);
        while ($tmp = readdir($handle)) {
            if ($tmp != ".." && $tmp != "." && $tmp != "") {
                if (is_writeable($tmp_path . DS . $tmp) && is_file($tmp_path . DS . $tmp) && isFileAccessible($tmp_path)) {
                    unlink($tmp_path . DS . $tmp);
                } else {
                    if (!is_writeable($tmp_path . DS . $tmp) && is_file($tmp_path . DS . $tmp) && isFileAccessible($tmp_path)) {
                        chmod($tmp_path . DS . $tmp, 438);
                        unlink($tmp_path . DS . $tmp);
                    }
                }
                if (is_writeable($tmp_path . DS . $tmp) && is_dir($tmp_path . DS . $tmp) && isFileAccessible($tmp_path)) {
                    $this->delete_folder($tmp_path . DS . $tmp);
                } else {
                    if (!is_writeable($tmp_path . DS . $tmp) && is_dir($tmp_path . DS . $tmp) && isFileAccessible($tmp_path)) {
                        chmod($tmp_path . DS . $tmp, 511);
                        $this->delete_folder($tmp_path . DS . $tmp);
                    }
                }
            }
        }
        closedir($handle);
        rmdir($tmp_path);
        if (!is_dir($tmp_path)) {
            return true;
        }
        return false;
    }
    public function removeData()
    {
        global $adb;
        global $vtiger_current_version;
        $message = "";
        $adb->pquery("DELETE FROM vtiger_settings_field WHERE `name` = ?", array("VTE Popup Reminder"));
        $sql = "DROP TABLE `vtepopupreminder_settings`;";
        $result = $adb->pquery($sql, array());
        $message .= "&nbsp;&nbsp;- Delete vtepopupreminder_settings tables";
        if ($result) {
            $message .= " - DONE";
        } else {
            $message .= " - <b>ERROR</b>";
        }
        $message .= "<br>";
        if (version_compare($vtiger_current_version, "7.0.0", "<")) {
            $template_folder = "layouts/vlayout";
        } else {
            $template_folder = "layouts/v7";
        }
        if (is_writeable($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl")) {
            unlink($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl");
        } else {
            chmod($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl", 438);
            unlink($template_folder . "/modules/Events/uitypes/DateTimeReminder.tpl");
        }
        if (is_writeable("modules/Events/uitypes/Datetimereminder.php")) {
            unlink("modules/Events/uitypes/Datetimereminder.php");
        } else {
            chmod("modules/Events/uitypes/Datetimereminder.php", 438);
            unlink("modules/Events/uitypes/Datetimereminder.php");
        }
        $moduleInstance = Vtiger_Module::getInstance("Events");
        if ($moduleInstance) {
            $popup_reminder_date = Vtiger_Field::getInstance("popup_reminder_date", $moduleInstance);
            if ($popup_reminder_date) {
                $popup_reminder_date->delete();
            }
            $popup_reminder_time = Vtiger_Field::getInstance("popup_reminder_time", $moduleInstance);
            if ($popup_reminder_time) {
                $popup_reminder_time->delete();
            }
        }
        return $message;
    }
}

?>