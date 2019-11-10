<?php

class VTEAdvanceMenu_NewModulesForm_View extends Vtiger_Basic_View
{
    public function __construct()
    {
        parent::__construct();
        $this->vteLicense();
    }
    public function vteLicense()
    {
        $vTELicense = new VTEAdvanceMenu_VTELicense_Model("VTEAdvanceMenu");
        if (!$vTELicense->validate()) {
            $menu = "<div class=\"dropdown vte-advance-menu-nav col-lg-3 col-md-2 col-sm-2 col-xs-2\">";
            $menu .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"layouts/v7/modules/VTEAdvanceMenu/resources/VTEAdvanceMenu.css\" media=\"screen\">";
            $menu .= "<button class=\"btn btn-primary dropdown-toggle vte-advance-menu-nav-btn\" type=\"button\" data-toggle=\"dropdown\">";
            $menu .= vtranslate("LBL_MENU_BTN", "VTEAdvanceMenu");
            $menu .= "</button>";
            $menu .= "<div class=\"dropdown-menu dropdown-menu-left\">";
            $menu .= "<p class=\"marginboth10px\">";
            $menu .= vtranslate("LBL_LICENSE_INVALID", "VTEAdvanceMenu");
            $menu .= "</p>";
            $menu .= "</div>";
            $menu .= "</div>";
            exit($menu);
        }
    }
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->getModule();
        $current_user = Users_Record_Model::getCurrentUserModel();
        if (!$current_user->isAdminUser()) {
            throw new AppException(vtranslate($moduleName, $moduleName) . " " . vtranslate("LBL_NOT_ACCESSIBLE"));
        }
    }
    public function preProcess(Vtiger_Request $request)
    {
        return true;
    }
    public function postProcess(Vtiger_Request $request)
    {
        return true;
    }
    public function process(Vtiger_Request $request)
    {
        global $current_language;
        include "languages/" . $current_language . "/VTEAdvanceMenu.php";
        $menu_id = $request->get("menu_id");
        $moduleName = $request->getModule();
        $viewer = $this->getViewer($request);
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $newEntityModules = $moduleModel->getNewEntityModules($menu_id);
        $viewer->assign("MODULE_NAME", $moduleName);
        $viewer->assign("USER_MODEL", Users_Record_Model::getCurrentUserModel());
        $viewer->assign("NEW_ENTITY_MODULES", $newEntityModules);
        $viewer->assign("NUMBER_NEW_ENTITY_MODULES", count($newEntityModules));
        $viewer->assign("MENU_ID", $menu_id);
        echo $viewer->view("AddNewModules.tpl", $moduleName, true);
    }
}

?>