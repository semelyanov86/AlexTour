<?php

class VTEPopupReminder_Module_Model extends Vtiger_Module_Model
{
    public function getSettingLinks()
    {
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Settings", "linkurl" => "index.php?module=VTEPopupReminder&parent=Settings&view=Settings", "linkicon" => "");
        $settingsLinks[] = array("linktype" => "MODULESETTING", "linklabel" => "Uninstall", "linkurl" => "index.php?module=VTEPopupReminder&parent=Settings&view=Uninstall", "linkicon" => "");
        return $settingsLinks;
    }
}

?>