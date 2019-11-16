<?php

ini_set("display_errors", "0");
class VTEPayments_List_View extends Vtiger_List_View
{
    public function __construct()
    {
        parent::__construct();
    }
    public function preProcess(Vtiger_Request $request)
    {
        parent::preProcess($request);
        $adb = PearDatabase::getInstance();
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->assign("QUALIFIED_MODULE", $module);
        /*$rs = $adb->pquery("SELECT * FROM `vte_modules` WHERE module=? AND valid='1';", array($module));
        if ($adb->num_rows($rs) == 0) {
            $viewer->view("InstallerHeader.tpl", $module);
        }*/
    }
    public function step2(Vtiger_Request $request, $vTELicense)
    {
        global $site_URL;
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->assign("VTELICENSE", $vTELicense);
        $viewer->assign("SITE_URL", $site_URL);
        $viewer->view("Step2.tpl", $module);
    }
    public function step3(Vtiger_Request $request)
    {
        $module = $request->getModule();
        $viewer = $this->getViewer($request);
        $viewer->view("Step3.tpl", $module);
    }
    public function process(Vtiger_Request $request)
    {
        $module = $request->getModule();
        $adb = PearDatabase::getInstance();
        $vTELicense = new VTEPayments_VTELicense_Model($module);
        if (!$vTELicense->validate()) {
            $this->step2($request, $vTELicense);
        } else {
            $rs = $adb->pquery("SELECT * FROM `vte_modules` WHERE module=? AND valid='1';", array($module));
            if ($adb->num_rows($rs) == 0) {
                $this->step3($request);
            } else {
                $mode = $request->getMode();
                if ($mode) {
                    $this->{$mode}($request);
                } else {
                    parent::process($request);
                }
            }
        }
    }
    public function getHeaderCss(Vtiger_Request $request)
    {
        $headerCssInstances = parent::getHeaderCss($request);
        if ($this->isVtiger7()) {
            $cssFileNames = array("~layouts/v7/modules/VTEPayments/resources/VTEPayments.css");
        }
        $cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
        $headerCssInstances = array_merge($headerCssInstances, $cssInstances);
        return $headerCssInstances;
    }
    /**
     * Check Vtiger version
     * @return mixed
     */
    public function isVtiger7()
    {
        $current_version = $_SESSION["vtiger_version"];
        if (!empty($current_version)) {
            return version_compare($current_version, "7.0.0", ">=");
        }
        require_once "vtlib/Vtiger/Version.php";
        return Vtiger_Version::check("7.0.0", ">=");
    }
}

?>