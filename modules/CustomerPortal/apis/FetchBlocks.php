<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class CustomerPortal_FetchBlocks extends CustomerPortal_API_Abstract {

	protected function processRetrieve(CustomerPortal_API_Request $request) {
		global $adb;
		$parentId = $request->get('parentId');
		$recordId = $request->get('recordId');
		$module = VtigerWebserviceObject::fromId($adb, $recordId)->getEntityName();

		if (!CustomerPortal_Utils::isModuleActive($module)) {
			throw new Exception("Records not Accessible for this module", 1412);
			exit;
		}

		if (!empty($parentId)) {
			if (!$this->isRecordAccessible($parentId)) {
				throw new Exception("Parent record not Accessible", 1412);
				exit;
			}
			$relatedRecordIds = $this->relatedRecordIds($module, CustomerPortal_Utils::getRelatedModuleLabel($module), $parentId);

			if (!in_array($recordId, $relatedRecordIds)) {
				throw new Exception("Record not Accessible", 1412);
				exit;
			}
		} else {
			if (!$this->isRecordAccessible($recordId, $module)) {
				throw new Exception("Record not Accessible", 1412);
				exit;
			}
		}

		$fields = CustomerPortal_Utils::getActiveFields($module);
		$moduleModel = Vtiger_Module_Model::getInstance($module);
		$blocksList = Vtiger_Block::getAllForModule($moduleModel);
        $blocksModel = Vtiger_Field::getAllForModule($moduleModel);
        $blocksArray = array();
        foreach ($blocksList as $list) {
            $blocksArray[vtranslate($list->label, $module)] = array();
        }
        foreach ($blocksModel as $model) {
            if (in_array($model->name, $fields)) {
                $blocksArray[vtranslate($model->block->label, $module)][] = $model->name;
            }
        }
        foreach ($blocksArray as $key=>$value) {
            if (empty($blocksArray[$key])) {
                unset($blocksArray[$key]);
            }
        }

		return $blocksArray;
	}

	function process(CustomerPortal_API_Request $request) {
		$response = new CustomerPortal_API_Response();
		$current_user = $this->getActiveUser();

		if ($current_user) {
			$record = $this->processRetrieve($request);
			$response->setResult(array('record' => $record));
		}
		return $response;
	}

}
