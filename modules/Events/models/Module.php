<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Calendar Module Model Class
 */
class Events_Module_Model extends Calendar_Module_Model {

    /**
	 * Function to get the url for list view of the module
	 * @return <string> - url
	 */
	public function getListViewUrl() {
		return 'index.php?module=Calendar&view='.$this->getListViewName();
	}

   /**
	 * Function to save a given record model of the current module
	 * @param Vtiger_Record_Model $recordModel
	 */
	public function saveRecord(Vtiger_Record_Model $recordModel) {
        $recordModel = parent::saveRecord($recordModel);
        
        //code added to send mail to the vtiger_invitees
        $selectUsers = $recordModel->get('selectedusers');
        if(!empty($selectUsers))
        {
            $invities = implode(';',$selectUsers);
            $mail_contents = $recordModel->getInviteUserMailData();
            $activityMode = ($recordModel->getModuleName()=='Calendar') ? 'Task' : 'Events';
            sendInvitation($invities,$activityMode,$recordModel,$mail_contents);
        }
    }

	/**
	 * Function to retrieve name fields of a module
	 * @return <array> - array which contains fields which together construct name fields
	 */
	public function getNameFields(){
        $nameFieldObject = Vtiger_Cache::get('EntityField',$this->getName());
        $moduleName = $this->getName();
		if($nameFieldObject && $nameFieldObject->fieldname) {
			$this->nameFields = explode(',', $nameFieldObject->fieldname);
		} else {
			$adb = PearDatabase::getInstance();

			$query = "SELECT fieldname, tablename, entityidfield FROM vtiger_entityname WHERE tabid = ?";
			$result = $adb->pquery($query, array(getTabid('Calendar')));
			$this->nameFields = array();
			if($result){
				$rowCount = $adb->num_rows($result);
				if($rowCount > 0){
					$fieldNames = $adb->query_result($result,0,'fieldname');
					$this->nameFields = explode(',', $fieldNames);
				}
			}
			
			$entiyObj = new stdClass();
			$entiyObj->basetable = $adb->query_result($result, 0, 'tablename');
			$entiyObj->basetableid =  $adb->query_result($result, 0, 'entityidfield');
			$entiyObj->fieldname =  $fieldNames;
			Vtiger_Cache::set('EntityField',$this->getName(), $entiyObj);
		}
        return $this->nameFields;
	}

    public function getAllTasksbyPriority($conditions = false, $pagingModel) {
        global $current_user;
        $db = PearDatabase::getInstance();

        $queryGenerator = new QueryGenerator("Events",$current_user);

        $moduleModel = Vtiger_Module_Model::getInstance("Events");
        $quickCreateFields = $moduleModel->getQuickCreateFields();
        $mandatoryFields = array("id","taskpriority","parent_id","contact_id");
        $fields = array_unique(array_merge($mandatoryFields,array_keys($quickCreateFields)));
        $queryGenerator->setFields($fields);
        $queryGenerator->addCondition("activitytype","Task","n","AND");
        if($conditions){
            foreach($conditions as $condition){
                if($condition["comparator"] === 'bw'){
                    $condition['fieldValue'] = implode(",",$condition['fieldValue']);
                }
                $queryGenerator->addCondition($condition['fieldName'],$condition['fieldValue'],$condition['comparator'],"AND");
            }
        }
        $query = $queryGenerator->getQuery();

        $startIndex = $pagingModel->getStartIndex();
        $pageLimit = $pagingModel->getPageLimit();

        $query .= " LIMIT $startIndex,".($pageLimit+1);
        $result = $db->pquery($query,array());
        $noOfRows = $db->num_rows($result);

        $mandatoryReferenceFields = array("parent_id","contact_id");
        $tasks = array();
        //SalesPlatform.ru begin
        $currentUser = Users_Record_Model::getCurrentUserModel();
        //SalesPlatform.ru end
        for($i=0;$i<$noOfRows;$i++){
            $newRow = $db->query_result_rowdata($result, $i);
            //SalesPlatform.ru begin
            if(!$currentUser->isAdminUser() && isToDoPermittedBySharing($newRow['activityid']) == 'no') {
                continue;
            }
            //SalesPlatform.ru end


            $model = Vtiger_Record_Model::getCleanInstance('Events');
            $model->setData($newRow);
            $model->setId($newRow['activityid']);
            $basicInfo = array();
            foreach($quickCreateFields as $fieldName => $fieldModel){
                if(in_array($fieldName,$mandatoryReferenceFields)){
                    continue;
                }
                $columnName = $fieldModel->get("column");
                $fieldType = $fieldModel->getFieldDataType();
                $value = $model->get($columnName);
                switch($fieldType){
                    case "reference":	if(!empty($value)){
                        $value = array("id"=>$value,"display_value"=>Vtiger_Functions::getCRMRecordLabel($value),"module"=>Vtiger_Functions::getCRMRecordType($value));

                    }
                        break;
                    case "datetime":	$value = Vtiger_Date_UIType::getDisplayDateValue($value);
                        break;
                }
                $basicInfo[$fieldName] = $value;
            }

            foreach($mandatoryReferenceFields as $fieldName){
                if($fieldName == "parent_id"){
                    $value = $model->get("crmid");
                } else {
                    $value = $model->get("contactid");
                }
                if(!empty($value)){
                    $value = array("id"=>$value,"display_value"=>Vtiger_Functions::getCRMRecordLabel($value),"module"=>Vtiger_Functions::getCRMRecordType($value));

                }
                $basicInfo[$fieldName] = $value;
            }

            $model->set("basicInfo",  $basicInfo);

            $priority = $model->get('priority');
            if($priority){
                $tasks[$priority][$model->getId()] = $model;
            }
        }

        if(count($tasks[$priority]) > $pageLimit){
            array_pop($tasks[$priority]);
            $pagingModel->set('nextPageExists', true);
        }else{
            $pagingModel->set('nextPageExists', false);
        }

        return $tasks;
    }
	
}
