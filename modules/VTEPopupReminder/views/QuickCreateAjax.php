<?php

class VTEPopupReminder_QuickCreateAjax_View extends Vtiger_QuickCreateAjax_View
{
    public function process(Vtiger_Request $request)
    {
        global $adb;
        $moduleName = $request->getModule();
        $activityid = $request->get("record");
        $viewer = $this->getViewer($request);
        $linkedRecordStructures = array();
        $sourceModule = $request->get("sourceModule");
        $recordModel = Vtiger_Record_Model::getInstanceById($activityid, $sourceModule);
        $recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
        $linkedRecordStructures["Events"] = $recordStructureInstance;
        $EntityField = array();
        $linkedModules = array();
        $picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
        $viewer->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));
        $viewer->assign("CURRENTDATE", date("Y-n-j"));
        $viewer->assign("SELECTED_MODULES", $linkedModules);
        $viewer->assign("ENTITY_FIELDS", $EntityField);
        $viewer->assign("LINKED_RECORD_STRUCTURES", $linkedRecordStructures);
        $viewer->assign("USER_MODEL", Users_Record_Model::getCurrentUserModel());
        $viewer->assign("SCRIPTS", $this->getHeaderScripts($request));
        $viewer->assign("ACTIVITYID", $activityid);
        $viewer->assign("SOURCE_MODULE", $sourceModule);
        echo $viewer->view("MassEditForm.tpl", $moduleName, true);
    }
    public function getHeaderScripts(Vtiger_Request $request)
    {
        $jsFileNames = array("modules.Calendar.resources.Edit");
        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        return $jsScriptInstances;
    }
}

?>