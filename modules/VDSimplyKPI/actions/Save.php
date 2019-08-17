<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vordoom.net
 * The Initial Developer of the Original Code is vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/


class VDSimplyKPI_Save_Action extends Vtiger_Save_Action {
        public function checkPermission(Vtiger_Request $request) {
                global $current_user;
                
		$moduleName = $request->getModule();
		$record = $request->get('record');

		if(!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record)) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
                
	}
	public function process(Vtiger_Request $request) {
                global $adb;
//		$assign_list = explode(',',$request->get('assigned_user'));
		$assign_list = $request->get('assigned_user');

                $request->set('netto',1);
                $distance = $request->get('distance');
                $request->set('date_off', $this->getDateOff($distance));
                $request->set('advanced_filter', Zend_Json::encode($request->get('conditions')));
                $datafield = $request->get('datafields');
                if ($datafield == 'count(*)'){
                     $request->set('typevdkpi', 'Number of records');
                }
                else {
                    $request->set('typevdkpi', 'Sum the field');
                }
                $recordID = $request->get('record');
                
                foreach ($assign_list as $assigned_user_id){
                    if (empty($recordID)){
                        $result = $adb->pquery('SELECT MAX(number_kpi) as number_kpi FROM vd_simplykpi', array());
                        $kpiId = $adb->query_result($result,0 , 'number_kpi')+1;
                        $request->set('number_kpi', $kpiId);
                    }
                    
                    $request->set('assigned_user_id',$assigned_user_id);
                    $recordModel = $this->saveRecord($request);
                    
                    
                }
                
		$loadUrl = $recordModel->getDetailViewUrl();
		
		header("Location: $loadUrl");
	}
        function getDateOff($distance){
            $date = time();
            switch(vtranslate($distance,'VDSimplyKPI')){
                case vtranslate('Week','VDSimplyKPI'): 
                    return date("Y-m-d", strtotime("Sunday",$date));
                    break;
                case vtranslate('Mounth','VDSimplyKPI') :
                    $month = (int)date('m')+1;
                    $year = (int)date('Y');
                    
                    return date("Y-m-d", mktime(0, 0, 0, $month, 0, $year));
                    break;
                case vtranslate('Quater','VDSimplyKPI') :
                     $month = date('m');
                     if ($month < 4){
                        
                         return date("Y-03-31",$date);
                     }
                     else if ($month < 7){
                         return date("Y-06-30",$date);
                     }
                     else if ($month < 10){
                         return date("Y-09-30",$date);
                     }
                     else {
                         return date("Y-12-31",$date);
                     }
                    break;
                case vtranslate('Half-year','VDSimplyKPI'):
                     $month = date('m');
                     if ($month < 7){
                         return date("Y-06-30",$date);
                     }
                     else {
                         return date("Y-12-31",$date);
                     }
                    break;
                case vtranslate('Year','VDSimplyKPI') :
                      return date("Y-12-31",$date);
                    
                    break;   
                
                    
            }
            
        
        }
        protected function getRecordModelFromRequest(Vtiger_Request $request) {

		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		if(!empty($recordId)) {
			$recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('id', $recordId);
			$recordModel->set('mode', 'edit');
		} else {
			$recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('mode', '');
		}

		$fieldModelList = $moduleModel->getFields();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName, null);
                        $fieldDataType = $fieldModel->getFieldDataType();
			if($fieldDataType == 'time'){
				$fieldValue = Vtiger_Time_UIType::getTimeValueWithSeconds($fieldValue);
			}
			if($fieldValue !== null) {
                                if($fieldName == 'advanced_filter'){
                                    $fieldValue = Zend_Json::encode($fieldValue);
                                }
				if(!is_array($fieldValue)) {
					$fieldValue = trim($fieldValue);
				}
				$recordModel->set($fieldName, $fieldValue);
			}
		}
                return $recordModel;
	}
        
}