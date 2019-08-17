<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vordoom.net
 * The Initial Developer of the Original Code is vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/
class VDSimplyKPI_Record_Model extends Vtiger_Record_Model {
        public $filtred;
        public $function;
        public $method_name_workfolow = "CalculateKPI";
       
        
        public function getStructure($moduleName) {
		if (!empty($this->structuredValues[$moduleName])) {
			return $this->structuredValues[$moduleName];
		}
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if ($moduleName === 'Emails') {
			$restrictedTablesList = array('vtiger_emaildetails', 'vtiger_attachments');
			$moduleRecordStructure = array();
			$blockModelList = $moduleModel->getBlocks();
			foreach ($blockModelList as $blockLabel => $blockModel) {
				$fieldModelList = $blockModel->getFields();
				if (!empty($fieldModelList)) {
					$moduleRecordStructure[$blockLabel] = array();
					foreach ($fieldModelList as $fieldName => $fieldModel) {
						if (!in_array($fieldModel->get('table'), $restrictedTablesList) && $fieldModel->isViewable()) {
							$moduleRecordStructure[$blockLabel][$fieldName] = $fieldModel;
						}
					}
				}
			}
		} else if($moduleName === 'Calendar') { 
			$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);
			$moduleRecordStructure = array();
			$calendarRecordStructure = $recordStructureInstance->getStructure();
			
			$eventsModel = Vtiger_Module_Model::getInstance('Events');
			$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($eventsModel);
			$eventRecordStructure = $recordStructureInstance->getStructure();
			
			$blockLabel = 'LBL_CUSTOM_INFORMATION';
			if($eventRecordStructure[$blockLabel]) {
				if($calendarRecordStructure[$blockLabel]) {
					$calendarRecordStructure[$blockLabel] = array_merge($calendarRecordStructure[$blockLabel], $eventRecordStructure[$blockLabel]);
				} else {
					$calendarRecordStructure[$blockLabel] = $eventRecordStructure[$blockLabel];
				}
			}
			$moduleRecordStructure = $calendarRecordStructure;
		} else {
			$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);
			$moduleRecordStructure = $recordStructureInstance->getStructure();
		}
		$this->structuredValues[$moduleName] = $moduleRecordStructure;
		return $moduleRecordStructure;
	}
         public function getArrayAdvanceFiltred(){
            $filter = Zend_Json::decode(htmlspecialchars_decode($this->get('advanced_filter')));
            
            return $filter;
        }
        function getModuleCalculationFields($modules){
            $result = array();
            foreach ($modules as $module) {
                $aggregateFunctions = $this->getAggregateFunctions();
                $moduleFields = array();
                $moduleFields = $this->getModuleFields($module);

                foreach ($moduleFields as $moduleName => $fieldList) {
                    $fields = array();
                    if(!empty($fieldList)){
                        foreach ($fieldList as $column => $label) {
                            foreach ($aggregateFunctions as $function) {
                                $fLabel = vtranslate($label, $moduleName).' ('.vtranslate('LBL_'.$function, 'Reports').')';
                                $fColumn = $column.':'.$function;
                                $fields[$fColumn] = $fLabel;
                            }
                        }
                    }
                    $moduleFields[$moduleName] = $fields;
                    $result[$module] = $moduleFields[$moduleName];
                }
            }
		return $result;
    }
     function isLinkField($fieldDetails, $moduleName) {
        
        /* Get field by name */
        $isLinkField = false;
        $fieldModel = Vtiger_Field_Model::getInstance($fieldDetails[3], Vtiger_Module_Model::getInstance($moduleName));
        if($fieldModel != null) {
            $isLinkField = ($fieldModel->isOwnerField() || $fieldModel->isReferenceField());
        }
        
        return $isLinkField;
    }
    function getModuleFields($module) {
		
		$ModuleFields = $this->getModuleColumnsList($module);
                
		$calculationFields = array();
		foreach ($ModuleFields[$module] as $blocks) {
			if (!empty ($blocks)) {
				foreach ($blocks as $fieldType => $fieldName) {
					$fieldDetails = explode(':', $fieldType);
                    if($fieldName == 'Send Reminder' && $primaryModule == 'Calendar') continue;
                   
                    if ( ($fieldDetails[4] === "I" || $fieldDetails[4] === "N" || $fieldDetails[4] === "NN") && !$this->isLinkField($fieldDetails, $module) ) {
					
						$calculationFields[$fieldType] = $fieldName;
					}
				}
			}
		}
		$ModuleCalculationFields[$module] = $calculationFields;
		return $ModuleCalculationFields;
	}
    function getAggregateFunctions(){
        $functions = array('SUM');
        return $functions;
    }
    function getColumnsListbyBlock($module,$block,$group_res_by_block=false)
	{
		global $adb;
		global $log;
		global $current_user;

		if(is_string($block)) $block = explode(",", $block);
		$skipTalbes = array('vtiger_emaildetails','vtiger_attachments');

		$tabid = getTabid($module);
		if ($module == 'Calendar') {
			$tabid = array('9','16');
		}
		$params = array($tabid, $block);

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		//Security Check
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$sql = "select * from vtiger_field where vtiger_field.tabid in (". generateQuestionMarks($tabid) .") and vtiger_field.block in (". generateQuestionMarks($block) .") and vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2) AND tablename NOT IN (".generateQuestionMarks($skipTalbes).") ";

			
			if($module == "Calendar")
				$sql.=" group by vtiger_field.fieldlabel order by sequence";
			else
			$sql.=" order by sequence";
		}
		else
		{

			$profileList = getCurrentUserProfileList();
			$sql = "select * from vtiger_field inner join vtiger_profile2field on vtiger_profile2field.fieldid=vtiger_field.fieldid inner join vtiger_def_org_field on vtiger_def_org_field.fieldid=vtiger_field.fieldid where vtiger_field.tabid in (". generateQuestionMarks($tabid) .")  and vtiger_field.block in (". generateQuestionMarks($block) .") and vtiger_field.displaytype in (1,2,3) and vtiger_profile2field.visible=0 and vtiger_def_org_field.visible=0 and vtiger_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$sql .= " and vtiger_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params, $profileList);
			}
			$sql .= ' and tablename NOT IN ('.generateQuestionMarks($skipTalbes).') ';

			
			if($module == "Calendar")
				$sql.=" group by vtiger_field.fieldlabel order by sequence";
			else
				$sql.=" group by vtiger_field.fieldid order by sequence";
		}
		array_push($params, $skipTalbes);

		$result = $adb->pquery($sql, $params);
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldtype = $adb->query_result($result,$i,"typeofdata");
			$uitype = $adb->query_result($result,$i,"uitype");
			$fieldtype = explode("~",$fieldtype);
			$fieldtypeofdata = $fieldtype[0];
			$blockid = $adb->query_result($result, $i, "block");

			//Here we Changing the displaytype of the field. So that its criteria will be displayed correctly in Reports Advance Filter.
			$fieldtypeofdata=ChangeTypeOfData_Filter($fieldtablename,$fieldcolname,$fieldtypeofdata);

			if($uitype == 68 || $uitype == 59)
			{
				$fieldtypeofdata = 'V';
			}
			if($fieldtablename == "vtiger_crmentity")
			{
				$fieldtablename = $fieldtablename.$module;
			}
			if($fieldname == "assigned_user_id")
			{
				$fieldtablename = "vtiger_users".$module;
				$fieldcolname = "user_name";
			}
			if($fieldname == "assigned_user_id1")
			{
				$fieldtablename = "vtiger_usersRel1";
				$fieldcolname = "user_name";
			}

			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			if ($module == 'Emails' and $fieldlabel == 'Date & Time Sent') {
				$fieldlabel = 'Date Sent';
				$fieldtypeofdata = 'D';
			}
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;

			$adv_rel_field_tod_value = '$'.$module.'#'.$fieldname.'$'."::".getTranslatedString($module,$module)." ".getTranslatedString($fieldlabel,$module);
			if (!is_array($this->adv_rel_fields[$fieldtypeofdata]) ||
					!in_array($adv_rel_field_tod_value, $this->adv_rel_fields[$fieldtypeofdata])) {
				$this->adv_rel_fields[$fieldtypeofdata][] = $adv_rel_field_tod_value;
			}
			//added to escape attachments fields in Reports as we have multiple attachments
            if($module == 'HelpDesk' && $fieldname =='filename') continue;

			if (is_string($block) || $group_res_by_block == false) {
				$module_columnlist[$optionvalue] = $fieldlabel;
			} else {
				$module_columnlist[$blockid][$optionvalue] = $fieldlabel;
			}
		}
		if (is_string($block)) {
		    $this->fixGetColumnsListbyBlockForInventory($module, $block, $module_columnlist);
		}
		return $module_columnlist;
	}
    function fixGetColumnsListbyBlockForInventory($module, $blockid, &$module_columnlist) {
		global $log;

		$blockname = getBlockName($blockid);
		if($blockname == 'LBL_RELATED_PRODUCTS' && ($module=='PurchaseOrder' || $module=='SalesOrder' || $module=='Quotes' || $module=='Invoice')){
			$fieldtablename = 'vtiger_inventoryproductrel';
			$fields = array('productid'=>getTranslatedString('Product Name',$module),
							'serviceid'=>getTranslatedString('Service Name',$module),
							'listprice'=>getTranslatedString('List Price',$module),
							'discount_amount'=>getTranslatedString('Discount',$module),
							'quantity'=>getTranslatedString('Quantity',$module),
							'comment'=>getTranslatedString('Comments',$module),
			);
			$fields_datatype = array('productid'=>'V',
							'serviceid'=>'V',
							'listprice'=>'I',
							'discount_amount'=>'I',
							'quantity'=>'I',
							'comment'=>'V',
			);
			foreach($fields as $fieldcolname=>$label){
				$column_name = str_replace(' ', '_', $label);
				$fieldtypeofdata = $fields_datatype[$fieldcolname];
				$optionvalue =  $fieldtablename.":".$fieldcolname.":".$module."_".$column_name.":".$fieldcolname.":".$fieldtypeofdata;
				$module_columnlist[$optionvalue] = $label;
			}
		}
		$log->info("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock".$module.$block);
		return $module_columnlist;
	}
    function getModuleColumnsList($module)
	{
		$this->initListOfModules();
               
                $allColumnsListByBlocks =& $this->getColumnsListbyBlock($module, array_keys($this->module_list[$module]), true);
                 
		foreach($this->module_list[$module] as $key=>$value) {
			$temp = $allColumnsListByBlocks[$key];
			$this->fixGetColumnsListbyBlockForInventory($module, $key, $temp);

			if (!empty($ret_module_list[$module][$value])) {
				if (!empty($temp)) {
					$ret_module_list[$module][$value] = array_merge($ret_module_list[$module][$value], $temp);
				}
			} else {
				$ret_module_list[$module][$value] = $temp;
			}
		}
		if($module == 'Emails') {
			foreach($ret_module_list[$module] as $key => $value) {
				foreach($value as $key1 => $value1) {
					if($key1 == 'vtiger_activity:time_start:Emails_Time_Start:time_start:T') {
						unset($ret_module_list[$module][$key][$key1]);
					}
				}
			}
		}
		
		return $ret_module_list;
	}
    function initListOfModules() {
		global $adb, $current_user, $old_related_modules;

		$restricted_modules = array('Webmails');
		$restricted_blocks = array('LBL_COMMENTS','LBL_COMMENT_INFORMATION');

		$this->module_id = array();
		$this->module_list = array();

		// Prefetch module info to check active or not and also get list of tabs
		$modulerows = vtlib_prefetchModuleActiveInfo(false);

		

			if($modulerows) {
				foreach($modulerows as $resultrow) {
					if($resultrow['presence'] == '1') continue;      // skip disabled modules
					if($resultrow['isentitytype'] != '1') continue;  // skip extension modules
					if(in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
						continue;
					}
					if($resultrow['name']!='Calendar'){
						$this->module_id[$resultrow['tabid']] = $resultrow['name'];
					} else {
						$this->module_id[9] = $resultrow['name'];
						$this->module_id[16] = $resultrow['name'];

					}
					$this->module_list[$resultrow['name']] = array();
				}

				$moduleids = array_keys($this->module_id);
				$reportblocks =
					$adb->pquery("SELECT blockid, blocklabel, tabid FROM vtiger_blocks WHERE tabid IN (" .generateQuestionMarks($moduleids) .")",
						array($moduleids));
				$prev_block_label = '';
				if($adb->num_rows($reportblocks)) {
					while($resultrow = $adb->fetch_array($reportblocks)) {
						$blockid = $resultrow['blockid'];
						$blocklabel = $resultrow['blocklabel'];
						$module = $this->module_id[$resultrow['tabid']];

						if(in_array($blocklabel, $restricted_blocks) ||
							in_array($blockid, $this->module_list[$module]) ||
							isset($this->module_list[$module][getTranslatedString($blocklabel,$module)])
						) {
							continue;
						}

						if(!empty($blocklabel)){
							if($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION')
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							else
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							$prev_block_label = $blocklabel;
						} else {
							$this->module_list[$module][$blockid] = getTranslatedString($prev_block_label,$module);
						}
					}
				

				
				
			}
		}
	}    
        public function calculateKPI(){
            $this->filtred = $this->getArrayAdvanceFiltred();
            $datafields = $this->get('datafields');
            if ($datafields != 'count(*)'){
                $array = explode(":",$datafields);
                $this->function = 'SUM('.$array[0].'.'.$array[1].')';
            }
            else {
                $this->function = $datafields;
            }
            $this->getDataPeriod();
            $this->generateQuery();
        }
        
        public function generateQuery(){
             $where = $this->qWhere();
        }
        public function getDataPeriod($period = false){
           
            $date = time();
            switch(vtranslate($this->get('distance'),'VDSimplyKPI')){
                case vtranslate('Week','VDSimplyKPI'): 
                    $this->startDate = date("Y-m-d 00:00:00", strtotime("last Monday",$date));
                    $this->stopDate = date("Y-m-d 23:59:59", strtotime("Sunday",$date));
                    break;
                case vtranslate('Mounth','VDSimplyKPI') :
                    $month = (int)date('m')+1;
                    $year = (int)date('Y');
                    $this->startDate = date("Y-m-01 00:00:00",$date);
                    $this->stopDate = date("Y-m-d 23:59:59", mktime(0, 0, 0, $month, 0, $year));
                    break;
                case vtranslate('Quater','VDSimplyKPI') :
                     $month = date('m');
                     if ($month < 4){
                         $this->startDate = date("Y-01-01 00:00:00",$date);
                         $this->stopDate = date("Y-03-31 23:59:59",$date);
                     }
                     else if ($month < 7){
                         $this->startDate = date("Y-04-01 00:00:00",$date);
                         $this->stopDate = date("Y-06-30 23:59:59",$date);
                     }
                     else if ($month < 10){
                         $this->startDate = date("Y-07-01 00:00:00",$date);
                         $this->stopDate = date("Y-09-30 23:59:59",$date);
                     }
                     else {
                         $this->startDate = date("Y-10-01 00:00:00",$date);
                         $this->stopDate = date("Y-12-31 23:59:59",$date);
                     }
                    break;
                case vtranslate('Half-year','VDSimplyKPI'):
                     $month = date('m');
                     if ($month < 7){
                         $this->startDate = date("Y-01-01 00:00:00",$date);
                         $this->stopDate = date("Y-06-30 23:59:59",$date);
                     }
                     else {
                         $this->startDate = date("Y-07-01 00:00:00",$date);
                         $this->stopDate = date("Y-12-31 23:59:59",$date);
                     }
                    break;
                case vtranslate('Year','VDSimplyKPI') :
                      $this->startDate = date("Y-01-01 00:00:00",$date);
                      $this->stopDate = date("Y-12-31 23:59:59",$date);
                    
                    break;
                
                    
            }
            
        }
        function qWhere(){
            $sql = 'WHERE vtiger_crmentity.deleted = 0';
            $group = Vtiger_Functions::getGroupRecordLabel($this->get('assigned_user_id'));
            if (!$group){
                $sql .= " and vtiger_crmentity.smownerid = ".(int)$this->get('assigned_user_id');
            }
            $sql .= " ";
            
        }
        
        function getKPIResult(){
            global $adb;
            
            $this->getDataPeriod();
            $id = $this->get('id');
            $result = $adb->pquery("SELECT sum(result) from vd_simplykpi_records where simplykpiid = ? and datecreate >= ? and datecreate <= ?", array($id,$this->startDate,$this->stopDate));
            $this->set('result', $adb->query_result($result,0));
            
            
           return $this->get('result');
        }

    function getConditions() {
        return Zend_Json::decode(html_entity_decode($this->get('advanced_filter'),ENT_COMPAT));
    }

    function transformToAdvancedFilterCondition() {
        $conditions = $this->getConditions();
        $transformedConditions = array();

        if(!empty($conditions)) {
            foreach($conditions as $index => $info) {
                $columnName = $info['fieldname'];
                $value = $info['value'];
                // To convert date value from yyyy-mm-dd format to user format
                $valueArray = explode(',', $value);
                $isDateValue = false;
                for($i = 0; $i < count($valueArray); $i++) {
                    if(Vtiger_Functions::isDateValue($valueArray[$i])) {
                        $isDateValue = true;
                        $valueArray[$i] = DateTimeField::convertToUserFormat($valueArray[$i]);
                    }
                }
                if($isDateValue) {
                    $value = implode(',', $valueArray);
                }
                // End
                if($columnName == 'filelocationtype')
                    $value = ($value == 'I') ? vtranslate('LBL_INTERNAL','Documents') : vtranslate('LBL_EXTERNAL','Documents');
                elseif ($columnName == 'folderid') {
                    $folderInstance = Documents_Folder_Model::getInstanceById($value);
                    $value = $folderInstance->getName();
                }
                if(!($info['groupid'])) {
                    $firstGroup[] = array('columnname' => $columnName, 'comparator' => $info['operation'], 'value' => $value,
                        'column_condition' => $info['joincondition'], 'valuetype' => $info['valuetype'], 'groupid' => $info['groupid']);
                } else {
                    $secondGroup[] = array('columnname' => $columnName, 'comparator' => $info['operation'], 'value' => $value,
                        'column_condition' => $info['joincondition'], 'valuetype' => $info['valuetype'], 'groupid' => $info['groupid']);
                }
            }
        }
        $transformedConditions[1] = array('columns'=>$firstGroup);
        $transformedConditions[2] = array('columns'=>$secondGroup);
        return $transformedConditions;
    }
}