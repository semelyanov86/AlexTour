<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: Vordoom.net
 * The Initial Developer of the Original Code is Vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/
ini_set('display_errors',1);
error_reporting(E_ERROR);
Class VDSimplyKPI_Edit_View extends Vtiger_Edit_View {
   
	public function process(Vtiger_Request $request) {
		$viewer = $this->getViewer ($request);
		
                $moduleName = $request->getModule();
		$record = $request->get('record');
                
//                $step = $request->get('mode');
//                if (empty($step)){
//                    $step = 'Step1';
//                }
//                $this->$step($request);
        $this->v7Edit($request);
                // parent::process($request);
	}

	function isAjaxEnabled($recordModel) {
        return false;
    }

        function Step1(Vtiger_Request $request){
               $viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$record = $request->get('record');
                if(!empty($record)) {
                    $recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($record, $moduleName);
                    $viewer->assign('RECORD_ID', $record);
                    $viewer->assign('MODE', 'edit');
                    
                } else {
                    $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
                    $viewer->assign('MODE', '');
                }
                if(!$this->record){
                    $this->record = $recordModel;
                }
        
		$moduleModel = $recordModel->getModule();
		$fieldList = $moduleModel->getFields();
		$requestFieldList = array_intersect_key($request->getAll(), $fieldList);

		foreach($requestFieldList as $fieldName=>$fieldValue){
			$fieldModel = $fieldList[$fieldName];
			$specialField = false;
			// We collate date and time part together in the EditView UI handling 
			// so a bit of special treatment is required if we come from QuickCreate 
			
            
            
			if($fieldModel->isEditable() || $specialField) {
				$recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
			}
		}
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
                $listModules = new VDSimplyKPI_Module_Model();
		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('MODULE', $moduleName);
                $viewer->assign('MODULELIST', $listModules->getSupportedModules());
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('step1.tpl', $moduleName);
        }
        function Step2(Vtiger_Request $request){
            
            $viewer = new Vtiger_Viewer();
            $moduleName = $request->getModule();
            $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
            $fieldModelList = $moduleModel->getFields();
            $fields = array();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName, null);
			$fieldDataType = $fieldModel->getFieldDataType();
			if($fieldDataType == 'time'){
				$fieldValue = Vtiger_Time_UIType::getTimeValueWithSeconds($fieldValue);
			}
			if($fieldValue !== null) {
				if(!is_array($fieldValue)) {
					$fieldValue = trim($fieldValue);
				}
				$fields[$fieldName] = $fieldValue;
			}
		}
            $viewer->assign('FIELDS',$fields);
            $viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
            
            $recordId = $request->get('record');

            if ($recordId) {
                $recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
                $viewer->assign('RECORD_ID', $recordId);
                $viewer->assign('MODE', 'edit');
                $viewer->assign('SELECTED_ADVANCED_FILTER_FIELDS',$recordModel->getArrayAdvanceFiltred());
                $viewer->assign('assigned_user_id',$recordModel->get('assigned_user_id'));
                $request->set('setype', $recordModel->get('setype'));
            } else {
                $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
                $viewer->assign('MODE', '');
                $viewer->assign('assigned_user_id',implode(',',$request->get('assigned_user_id')));
            }
            $selectedModuleName = $request->get('setype');
            
            $recordModel->module_list = $listModules->module_list;
            $ModuleFields = $recordModel->getStructure($selectedModuleName);
            if($selectedModuleName == 'HelpDesk'){
                foreach($selectedModuleName as $module => $blockFields){
                    foreach($blockFields as $key => $value){
                        if(isset($value)){
                            foreach($value as $key1 => $value1){
                                if($key1 == 'vtiger_troubletickets:update_log:HelpDesk_Update_History:update_log:V'){
                                    unset($selectedModuleName[$module][$key][$key1]);
                                }
                            }
                        }
                    }
                }
            }
            
            $viewer->assign('MODULE_RECORD_STRUCTURE', $ModuleFields);
             $viewer->assign('setype', $selectedModuleName);
             $dateFilters = Vtiger_Field_Model::getDateFilterTypes();
            foreach($dateFilters as $comparatorKey => $comparatorInfo) {
                $comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
                $comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
                $comparatorInfo['label'] = vtranslate($comparatorInfo['label'], $qualifiedModuleName);
                $dateFilters[$comparatorKey] = $comparatorInfo;
            }
            $viewer->assign('DATE_FILTERS', $dateFilters);
            if(($selectedModuleName == 'Calendar')){
			$advanceFilterOpsByFieldType = Calendar_Field_Model::getAdvancedFilterOpsByFieldType();
		} else{
			$advanceFilterOpsByFieldType = Vtiger_Field_Model::getAdvancedFilterOpsByFieldType();
		}
		$viewer->assign('ADVANCED_FILTER_OPTIONS', Vtiger_Field_Model::getAdvancedFilterOptions());
		$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', $advanceFilterOpsByFieldType);
                
                $viewer->assign('DATAFIELS', $recordModel->get('datafields'));
                $viewer->assign('CALCULATION_FIELDS', $recordModel->getModuleCalculationFields($selectedModuleName));
		$viewer->assign('MODULE', $moduleName);
                $viewer->assign('IS_FILTER_SAVED_NEW', true);
              $viewer->view('step2.tpl', $moduleName);
        }

        function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
            "layouts.v7.modules.Vtiger.resources.AdvanceFilter",
            '~libraries/jquery/jquery.datepick.package-4.1.0/jquery.datepick.js',
		    "modules.$moduleName.resources.VDSimplyKPIEdit",
//			"modules.$moduleName.resources.Edit2",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

    function getHeaderCss(Vtiger_Request $request) {
        $headerCssInstances = parent::getHeaderCss($request);
        $moduleName = $request->getModule();
        $cssFileNames = array(
            '~libraries/jquery/jquery.datepick.package-4.1.0/jquery.datepick.css',
            '~/libraries/jquery/bootstrapswitch/css/bootstrap3/bootstrap-switch.min.css',
        );
        $cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
        $headerCssInstances = array_merge($cssInstances, $headerCssInstances);
        return $headerCssInstances;
    }

    function v7Edit(Vtiger_Request $request) {
        $viewer = $this->getViewer ($request);
        $moduleName = $request->getModule();
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $record = $request->get('record');
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $qualifiedModuleName = $request->getModule(false);
        $fieldModelList = $moduleModel->getFields();
        $fields = array();
        foreach ($fieldModelList as $fieldName => $fieldModel) {
            $fieldValue = $request->get($fieldName, null);
            $fieldDataType = $fieldModel->getFieldDataType();
            if($fieldDataType == 'time'){
                $fieldValue = Vtiger_Time_UIType::getTimeValueWithSeconds($fieldValue);
            }
            if($fieldValue !== null) {
                if(!is_array($fieldValue)) {
                    $fieldValue = trim($fieldValue);
                }
                $fields[$fieldName] = $fieldValue;
            }
        }
        $viewer->assign('FIELDS',$fields);
        $viewer->assign('CURRENT_USER_MODEL', $currentUser);
//var_dump($fields);die;
        if(!empty($record)) {
            $recordModel = $this->record?$this->record:Vtiger_Record_Model::getInstanceById($record, $moduleName);
            $viewer->assign('RECORD_ID', $record);
            $viewer->assign('MODE', 'edit');
            $viewer->assign('SELECTED_ADVANCED_FILTER_FIELDS',$recordModel->getArrayAdvanceFiltred());
            $viewer->assign('assigned_user_id',$recordModel->get('assigned_user_id'));
            $request->set('setype', $recordModel->get('setype'));
        } else {
            $recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
            $viewer->assign('MODE', '');
            $viewer->assign('assigned_user_id',implode(',',$request->get('assigned_user_id')));
        }
        if(!$this->record){
            $this->record = $recordModel;
        }
        $moduleModel = $recordModel->getModule();
        $fieldList = $moduleModel->getFields();
        $requestFieldList = array_intersect_key($request->getAll(), $fieldList);

        foreach($requestFieldList as $fieldName=>$fieldValue){
            $fieldModel = $fieldList[$fieldName];
            $specialField = false;
            // We collate date and time part together in the EditView UI handling
            // so a bit of special treatment is required if we come from QuickCreate

            if($fieldModel->isEditable() || $specialField) {
                $recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
            }
        }
        $recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Vtiger_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
        $picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
        $listModules = new VDSimplyKPI_Module_Model();
        $selectedModuleName = $request->get('setype');

        $recordModel->module_list = $listModules->module_list;
//        $ModuleFields = $recordModel->getStructure($selectedModuleName);
        if($selectedModuleName == 'HelpDesk'){
            foreach($selectedModuleName as $module => $blockFields){
                foreach($blockFields as $key => $value){
                    if(isset($value)){
                        foreach($value as $key1 => $value1){
                            if($key1 == 'vtiger_troubletickets:update_log:HelpDesk_Update_History:update_log:V'){
                                unset($selectedModuleName[$module][$key][$key1]);
                            }
                        }
                    }
                }
            }
        }
        $dateFilters = Vtiger_Field_Model::getDateFilterTypes();
        foreach($dateFilters as $comparatorKey => $comparatorInfo) {
            $comparatorInfo['startdate'] = DateTimeField::convertToUserFormat($comparatorInfo['startdate']);
            $comparatorInfo['enddate'] = DateTimeField::convertToUserFormat($comparatorInfo['enddate']);
            $comparatorInfo['label'] = vtranslate($comparatorInfo['label'], $qualifiedModuleName);
            $dateFilters[$comparatorKey] = $comparatorInfo;
        }
        $viewer->assign('DATE_FILTERS', $dateFilters);
        if(($selectedModuleName == 'Calendar')){
            $advanceFilterOpsByFieldType = Calendar_Field_Model::getAdvancedFilterOpsByFieldType();
        } else{
            $advanceFilterOpsByFieldType = Vtiger_Field_Model::getAdvancedFilterOpsByFieldType();
        }
//        $viewer->assign('MODULE_RECORD_STRUCTURE', $ModuleFields);
        $viewer->assign('setype', $selectedModuleName);
        $viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
        $viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
        $viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
        $viewer->assign('MODULE', $moduleName);
        $viewer->assign('MODULELIST', $listModules->getSupportedModules());
        $viewer->assign('CURRENTDATE', date('Y-n-j'));
        $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
        $viewer->assign('RETURN_SOURCE_MODULE', $request->get("returnsourceModule"));
        $viewer->assign('RETURN_PAGE', $request->get("returnpage"));
        $viewer->assign('RETURN_SEARCH_VALUE',$request->get("returnsearch_value"));
        $viewer->assign('ADVANCED_FILTER_OPTIONS', Vtiger_Field_Model::getAdvancedFilterOptions());
        $viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', $advanceFilterOpsByFieldType);
        $viewer->assign('DATAFIELS', $recordModel->get('datafields'));
        $viewer->assign('CALCULATION_FIELDS', $recordModel->getModuleCalculationFields($listModules->getSupportedModules()));
        $viewer->assign('IS_FILTER_SAVED_NEW', true);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->view('EditView.tpl', $qualifiedModuleName);

    }
}