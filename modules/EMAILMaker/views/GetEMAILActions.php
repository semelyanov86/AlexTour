<?php
class EMAILMaker_GetEMAILActions_View extends Vtiger_BasicAjax_View
{
    public function process(Vtiger_Request $request) {
        $current_user = $cu_model = Users_Record_Model::getCurrentUserModel();
        $currentLanguage = Vtiger_Language_Handler::getLanguage();
        $adb = PearDatabase::getInstance();
        $mode = $request->get('mode');
        $source_module = $request->get('source_module');
        $viewer = $this->getViewer($request);
        $EMAILMaker = new EMAILMaker_EMAILMaker_Model();
        $version_type = $EMAILMaker->GetVersionType();
        $SourceModuleModel = Vtiger_Module_Model::getInstance($source_module);
        if ($EMAILMaker->CheckPermissions("DETAIL") == false || !$SourceModuleModel || !$SourceModuleModel->isEntityModule()) {
            die("");
        }
        $single_record = true;
        $record = $request->get('record');
        $relmodule = getSalesEntityType($record);
        $viewer->assign('MODULE', $relmodule);
        $viewer->assign('ID', $record);
        if ($single_record) $viewer->assign('SINGLE_RECORD', 'yes');
        require ('user_privileges/user_privileges_' . $current_user->id . '.php');
        if ($EMAILMaker->CheckPermissions("DETAIL")) {
            $viewer->assign("ENABLE_EMAILMAKER", 'true');
        } else {
            $viewer->assign("ENABLE_EMAILMAKER", "false");
        }
        if (!isset($_SESSION["template_languages"]) || $_SESSION["template_languages"] == "") {
            $temp_res = $adb->pquery("SELECT label, prefix FROM vtiger_language WHERE active = ?", array('1'));
            while ($temp_row = $adb->fetchByAssoc($temp_res)) {
                $template_languages[$temp_row["prefix"]] = $temp_row["label"];
            }
            $_SESSION["template_languages"] = $template_languages;
        }
        $EMAILMaker_License_Action = new EMAILMaker_License_Action();
        $license = $EMAILMaker_License_Action->checkLicense();
        if (substr($license, 5, 1) <= 1 && substr($license, 0, 5) == "proem" && $version_type == "professional") {
            $type = "professional";
        } elseif (substr($license, 0, 5) == "basem" && substr($license, 5, 1) <= 1 && $version_type == "basic") {
            $type = "basic";
        } else {
            die("");
        }
        $viewer->assign('TEMPLATE_LANGUAGES', $_SESSION["template_languages"]);
        $viewer->assign('CURRENT_LANGUAGE', $currentLanguage);
        $viewer->assign('IS_ADMIN', is_admin($current_user));
        $templates = $EMAILMaker->GetAvailableTemplatesArray($relmodule, false, $record);
        if (count($templates) > 0) $no_templates_exist = 0;
        else $no_templates_exist = 1;
        $viewer->assign('CRM_TEMPLATES', $templates);
        $viewer->assign('CRM_TEMPLATES_EXIST', $no_templates_exist);
        $viewer->assign('MODE', $mode);
        $def_templateid = $EMAILMaker->GetDefaultTemplateId($relmodule);
        $viewer->assign('DEFAULT_TEMPLATE', $def_templateid);
        if (is_dir("modules/PDFMaker") && vtlib_isModuleActive('PDFMaker')) {
            $PDFMakerModel = Vtiger_Module_Model::getInstance('PDFMaker');
            if ($PDFMakerModel->CheckPermissions("DETAIL") && $request->has('record') && !$request->isEmpty('record')) {
                $pdftemplates = $PDFMakerModel->GetAvailableTemplates($relmodule, false, $record);
                if (count($pdftemplates) > 0) $no_templates_exist = 0;
                else $no_templates_exist = 1;
                $viewer->assign('PDF_TEMPLATES', $pdftemplates);
                $viewer->assign('PDF_TEMPLATES_EXIST', $no_templates_exist);
            }
            if (!$no_templates_exist) $viewer->assign("IS_PDFMAKER", 'yes');
        }
        $tpl_name = "GetEMAILActions";
        if ($request->has('mode') && !$request->isEmpty('mode')) {
            $mode = $request->get('mode');
            if ($mode == "getButtons") {
                $tpl_name = "GetEMAILButtons";
            }
        }
        $viewer->view($tpl_name . ".tpl", 'EMAILMaker');
    }
    function getRecordsListFromRequest(Vtiger_Request $request) {
        $cvId = $request->get('cvid');
        $selectedIds = $request->get('selected_ids');
        $excludedIds = $request->get('excluded_ids');
        if (!empty($selectedIds) && $selectedIds != 'all') {
            if (!empty($selectedIds) && count($selectedIds) > 0) {
                return $selectedIds;
            }
        }
        $customViewModel = CustomView_Record_Model::getInstanceById($cvId);
        if ($customViewModel) {
            $searchKey = $request->get('search_key');
            $searchValue = $request->get('search_value');
            $operator = $request->get('operator');
            if (!empty($operator)) {
                $customViewModel->set('operator', $operator);
                $customViewModel->set('search_key', $searchKey);
                $customViewModel->set('search_value', $searchValue);
            }
            return $customViewModel->getRecordIds($excludedIds);
        }
    }
}
?>