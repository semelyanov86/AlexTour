<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: Vordoom.net
 * The Initial Developer of the Original Code is Vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';

class VDSimplyKPI extends Vtiger_CRMEntity {
	var $table_name = 'vd_simplykpi';
	var $table_index= 'simplykpiid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vd_simplykpicf', 'simplykpiid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vd_simplykpi', 'vd_simplykpicf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vd_simplykpi' => 'simplykpiid',
		'vd_simplykpicf'=>'simplykpiid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (

	);
	var $list_fields_name = Array (

	);

	// Make the field link to detail view
	var $list_link_field = 'subject';

	// For Popup listview and UI type support
	var $search_fields = Array(

	);
	var $search_fields_name = Array (

	);

	// For Popup window record selection
	var $popup_fields = Array ('subject');

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'subject';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('subject','assigned_user_id');

	var $default_order_by = 'subject';
	var $default_sort_order='ASC';

	function VDSimplyKPI() {
		$this->log =LoggerManager::getLogger('VDSimplyKPI');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('VDSimplyKPI');
	}

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
 			//Enable ModTracker for the module
 			static::enableModTracker($moduleName);
			//Create Related Lists
			static::createRelatedLists();
            static::createWorkflow();
			$EventManager = new VTEventsManager($adb);
                $createEvent = 'vtiger.entity.aftersave.final';
                $deleteEVent = 'vtiger.entity.beforedelete';
                $restoreEvent = 'vtiger.entity.afterrestore';
                $handler_path = 'modules/VDSimplyKPI/VDSimplyKPIHandler.php';
                $className = 'VDSimplyKPIHandler';
               
                $EventManager->registerHandler($createEvent, $handler_path, $className);
                $EventManager->registerHandler($deleteEVent, $handler_path, $className);
                $EventManager->registerHandler($restoreEvent, $handler_path, $className);
                        
		} else if($eventType == 'module.disabled') {
			// Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// Handle actions when this module is about to be deleted.
			static::deleteWorkflow();
			$EventManager = new VTEventsManager($adb);
            $className = 'VDSimplyKPIHandler';
            $EventManager->unregisterHandler($className);
			$adb->pquery('DROP TABLE vd_simplykpi_records', array());
           
		} else if($eventType == 'module.preupdate') {
			 
			// Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			//Create Related Lists
			static::createRelatedLists();
		}
 	}
	
	/**
	 * Enable ModTracker for the module
	 */
	public static function enableModTracker($moduleName)
	{
		include_once 'vtlib/Vtiger/Module.php';
		include_once 'modules/ModTracker/ModTracker.php';
			
		//Enable ModTracker for the module
		$moduleInstance = Vtiger_Module::getInstance($moduleName);
		ModTracker::enableTrackingForModule($moduleInstance->getId());
	}
	
	protected static function createRelatedLists()
	{
		include_once('vtlib/Vtiger/Module.php');	

	}
        protected static function createWorkflow(){
            global $adb;
            
            require_once 'modules/com_vtiger_workflow/VTWorkflowManager.inc';
            require_once 'modules/com_vtiger_workflow/VTTaskManager.inc';
            require_once 'modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc';
            require_once 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
            $emm = new VTEntityMethodManager($adb);
	    $emm->addEntityMethod("VDSimplyKPI","CreateNewKPI","modules/VDSimplyKPI/VDSimplyKPIHandler.php","createNewKPI");
            $workflow = new stdClass();
            $workflow->conditionStrategy = new VTJsonCondition();
            
            $workflow->type = 'basic';
            $workflow->moduleName = 'VDSimplyKPI';
            $workflow->description = 'Creating a new recordSimplyKPI for the next period';
            $workflow->test = '[{"fieldname":"createnewperiod","operation":"is","value":"0","valuetype":"rawtext","joincondition":"and","groupjoin":"and","groupid":"0"}]';
            $workflow->executionCondition = 6;
            $workflow->filtersavedinnew = 6;
            $wfManager = new VTWorkflowManager($adb);
            $wfManager->save($workflow);
            $task = new VTEntityMethodTask();
            $taskManager = new VTTaskManager($adb);
            $task->executeImmediately = 1;
            $task->workflowId = $workflow->id;
            $task->summary = 'Creating a new recordSimplyKPI for the next period';
            $task->active = 1;
            $task->methodName = 'CreateNewKPI';
            $taskManager->saveTask($task);
        }
		static function deleteWorkflow(){
           
            
            require_once 'modules/com_vtiger_workflow/VTWorkflowManager.inc';
            require_once 'modules/com_vtiger_workflow/VTTaskManager.inc';
            require_once 'modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc';
            require_once 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
            $emm = new VDEntityMethodManager($adb);
            $emm->removeEntityMethod("VDSimplyKPI","CreateNewKPI");
            
            
            

        }
       
}