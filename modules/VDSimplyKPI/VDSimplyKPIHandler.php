<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vordoom.net
 * The Initial Developer of the Original Code is vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/
require_once'include/database/PearDatabase.php';
require_once 'include/Webservices/Query.php';
require_once 'data/VTEntityDelta.php';

class VDSimplyKPIHandler extends VTEventHandler {
    
	function handleEvent($eventName, $data) {
                global $log, $current_module, $adb, $current_user;
              
                if (!vtlib_isModuleActive('VDSimplyKPI') && !$this->checkModuleKpi($data->getModuleName())) {
                    
                        return true;
                }
                 
                if($eventName == 'vtiger.entity.aftersave.final' OR $eventName == 'vtiger.entity.afterrestore') {
                    
                    $this->calculateKPI($data, $eventName);
                }elseif ($eventName == 'vtiger.entity.beforedelete'){
                   $this->checkDeleteKPI($data, $eventName); 
                }
               
        }
        function checkModuleKpi($moduleName){
            $db = PearDatabase::getInstance();
            $sql = "SELECT * FROM vd_simplykpi WHERE setype = ?";
            $result=$db->pquery($sql, array($moduleName));
            $numRows = $db->num_rows($result);
            if ($numRows > 0) return true;
            else return false;
            
        }
        function checkDeleteKPI($entity, $eventName){
            $db = PearDatabase::getInstance();
            $moduleName = $entity->getModuleName();
            $this->checkKpiOldUser($entity->focus->column_fields['assigned_user_id'],$entity->getId());
        }

function calculateKPI($entity, $eventName){
    $db = PearDatabase::getInstance();
    $moduleName = $entity->getModuleName();
    $cache = Vtiger_Cache::get('simplykpi', $moduleName);
    $entityId = $entity->getId();
    
    $vtEntityDelta = new VTEntityDelta();
    $delta = $vtEntityDelta->getEntityDelta($moduleName, $entityId, true);
    if(isset($delta['assigned_user_id'])){
        $old_assign_user = $delta['assigned_user_id']['oldValue']; 
        $this->checkKpiOldUser($old_assign_user,$entityId);
    }

    if (empty($cache)){
        $SimplyKPIModel = Vtiger_Module_Model::getInstance('VDSimplyKPI');
        $result = $db->pquery("Select a.*, c.smownerid, g.groupid from vd_simplykpi as a left join vtiger_crmentity as c ON c.crmid = a.simplykpiid left join vtiger_groups as g on g.groupid = c.smownerid where c.deleted = 0 and a.createnewperiod = 0 and a.setype = ? ", array($moduleName));
        $numRows = $db->num_rows($result);

        if ($numRows > 0){
            $kpi = array();
            for ($i=0;$i<$numRows;$i++){
                $kpi[$i] = $db->query_result_rowdata($result,$i);
                if ($kpi[$i]['date_off'] < date('Y-m-d')){
                    unset($kpi[$i]);
                    createNewKPI(array());
                }
            }
            $cache = $SimplyKPIModel->serializeKPI($kpi);
            Vtiger_Cache::set('simplykpi', $moduleName, $cache);
        }
        else {
            return;
        }
        
        
    }

    if (!$this->calculateKPIstage1($entity,$cache)){
        return;
    }
    
    
}
function checkKpiOldUser($user,$entityId){
    $db = PearDatabase::getInstance();
    $result = $db->pquery('SELECT entitykpiid, result FROM vd_simplykpi_records as a LEFT JOIN vtiger_crmentity as c ON c.crmid = a.entitykpiid WHERE c.smownerid = ? and a.entityid = ?',array($user, $entityId));
   
    $numRows = $db->num_rows($result);
    if ($numRows == 0) return;
    $kpiId = array();
    for ($i=0;$i<$numRows;$i++){
        $kpiId[] = $db->query_result_rowdata($result,$i);
    }
    foreach ($kpiId as $kpi){
        $this->deleteResult($kpi,$entityId);
    }
    return;
}
function deleteResult($kpi,$entityId){
    $db = PearDatabase::getInstance();
    $db->pquery('DELETE FROM vd_simplykpi_records WHERE entitykpiid = ? and entityid = ?', array($kpi['entitykpiid'], $entityId));
    $recordModel = Vtiger_Record_Model::getInstanceById($kpi['entitykpiid'], 'VDSimplyKPI');
    $recordModel->set('mode', 'edit');
    $recordModel->set('result', $recordModel->get('result')-$kpi['result']);
    $recordModel->save();
    
}
function calculateKPIstage1($entity,$cache){
   
    $userId = $entity->focus->column_fields['assigned_user_id'];

    if (in_array($userId, $cache['Users'])){
        
        return $this->calculateKPIstage2($entity,$cache,$userId);
    }
   
    return false;
}

function calculateKPIstage2($entity,$cache,$userId){

    if(!isset($cache['Users2kpi'][$userId]) || count($cache['Users2kpi'][$userId])== 0){
        return false;
    }

    foreach ($cache['Users2kpi'][$userId] as $kpiId){
        $filtre = $cache['Filtre'][$kpiId];
        if(!$this->calculateKpiFiltreCheck($filtre,$entity)){
            continue;
        }
        $entityId = $entity->getId();
        
        if (!$this->checkRecord($entityId,$kpiId)){
            continue;
        }

        $this->addRecord($entityId,$kpiId,$entity,$cache);
    }
}
function addRecord($entityId,$kpiId,$entity,$cache){
    $db = PearDatabase::getInstance();
    $result = $cache['TypeResult'][$kpiId];
    $recordEntity = Vtiger_Record_Model::getInstanceById($entityId);
    if ($result != 1){
        $result = $recordEntity->get($result);
    }
    
    if (!empty($result)){
        $db->pquery('INSERT INTO vd_simplykpi_records (simplykpiid, entityid, result, entitykpiid) values(?,?,?,?)', array($kpiId,$entityId,$result, $cache['Id'][$kpiId]));
        $recordModel = Vtiger_Record_Model::getInstanceById($cache['Id'][$kpiId], 'VDSimplyKPI');
        $recordModel->set('mode', 'edit');
        $recordModel->set('result', $recordModel->get('result') + $result);
       
        $recordModel->save();
        
    }
}
function checkRecord($entityId,$kpiId) {
    $db = PearDatabase::getInstance();
    $result = $db->pquery('SELECT * FROM vd_simplykpi_records WHERE simplykpiid = ? and entityid = ?', array($kpiId, $entityId));
    
    $numRows = $db->num_rows($result);
    if($numRows > 0){
        return false;
    }
    return true;
}
function calculateKpiFiltreCheck($filtre,$entity){
    if (count($filtre[1]['columns']) == 0 && count($filtre[2]['columns'])){
        return true;
    }

    if (count($filtre[1]['columns']) > 0){
        if(!$this->checkMandatoryFiltre($filtre[1],$entity)){
            return false;
        }
       
    }
   
    if (count($filtre[2]['columns']) > 0){
        if(!$this->checkFiltre($filtre[2],$entity)){
           
            return false;
        }
    }
    
    return true;
}

function checkMandatoryFiltre($filtre,$entity){

    foreach ($filtre['columns'] as $row){
        
        $test = $this->testEntiteToFiltre($row, $entity);
        if (!$test) return false;
    }
    return $test;
}
function checkFiltre($filtre,$entity){
    
    foreach ($filtre['columns'] as $row){
        
        $test = $this->testEntiteToFiltre($row, $entity);
        if ($test){
            return true;
        }
    }
    return false;
    
}

function testEntiteToFiltre($row, $entity){
    
     $columname = explode(':', $row['columnname']);

	 if (!empty($entity->focus->column_fields[$columname[2]])){
		 $columname = $columname[2];
	 } elseif(!empty($entity->focus->column_fields[$columname[1]])) {
		 $columname = $columname[1];
	 } else {
	     $columname = $columname[0];
     }
     $comparator = $row['comparator'];
      $entityValue = $entity->focus->column_fields[$columname];

     $entityValue=html_entity_decode(trim($entityValue),ENT_QUOTES,'utf-8');

      switch ($comparator){
          case 'y':
               $result = (empty($entityValue));
                break;
            case 'ny':
               $result = (!empty($entityValue));
                break;
          default:
                $result = false;
      }
      if ($result){
          return true;
      }
     $values = html_entity_decode(trim($row['value']),ENT_QUOTES,$default_charset);
     $values = explode(',', $values);

     foreach ($values as $value){
        $result = false;
        switch ($comparator){
            case 'e': 
                if($value == $entityValue){
                    $result = true;
                }
                break;
            case 'n':
                if($value != $entityValue){
                     $result = true;
                }
                 break; 
            case 's':
                $result = $this->String_Begins_With($value, $entityValue);
                break;
            case 'is':
                $result = ($value === $entityValue);
                break;
            case 'ew':
                $result = $this->String_Ends_With($value, $entityValue);
                break;
            case 'c':
                $result = strpos($entityValue, $value);
                break;
            case 'k':
                $test = trpos($entityValue, $value);
                if($test===false){
                    $result = true;
                }
                break;
            case 'l':
                $result = ($entityValue < $value);
                break;
            case 'g':
                $result = ($entityValue > $value);
                break;
            case 'm':
                $result = ($entityValue <= $value);
                break;
            case 'h':
               $result = ($entityValue > $value);
                break;
            case 'b':
               $result = ($entityValue < $value);
                break;
            case 'a':
                $result = ($entityValue > $value);
                break;
            case 'bw':
                $result = ($entityValue > $values[0] && $entityValue < $values[1]);
                break;
            default: 
                $result = false;
        }
        if ($result){
            return true;
        }
     }
     return false;
}
function String_Begins_With($needle, $haystack) {

	    return (substr($haystack, 0, strlen($needle))==$needle);

	}
function String_Ends_With($needle, $haystack) {

	    return (substr($haystack, strlen($needle) - strlen($haystack), strlen($haystack))==$needle);

	}
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

function createNewKPI($entityArray){
    global $current_user;
    $current_user = Users::getActiveAdminUser();
    $sql = "SELECT vd_simplykpi.* FROM vd_simplykpi INNER JOIN vtiger_crmentity ON vd_simplykpi.simplykpiid = vtiger_crmentity.crmid  WHERE vtiger_crmentity.deleted = 0 and vd_simplykpi.date_off < ? and vd_simplykpi.createnewperiod = 0";
    $db = PearDatabase::getInstance();
    $result = $db->pquery($sql,array(date('Y-m-d')));
    
    $numRows = $db->num_rows($result);
    $moduleModel = Vtiger_Module_Model::getInstance('VDSimplyKPI');
    $fieldModelList = $moduleModel->getFields();
    for ($i=0; $i<$numRows; $i++){
        $crmid = $db->query_result($result,$i,'simplykpiid');
        echo $crmid;
        $oldRecord = Vtiger_Record_Model::getInstanceById($crmid);
        
        $recordModel = Vtiger_Record_Model::getCleanInstance('VDSimplyKPI');
        $recordModel->set('mode', '');
        
        foreach ($fieldModelList as $fieldName => $fieldModel){
            if ($fieldName == 'id' 
                    || $fieldName == 'createdtime' 
                    || $fieldName == 'modifiedtime' 
                    || $fieldName == 'number_kpi'){
                continue;
            
            } else if ($fieldName == 'procent' || $fieldName == 'result'){
                $recordModel->set($fieldName, 0);
            } else if ($fieldName == 'date_off'){
                $date_off = getDateOff($oldRecord->get('distance'));
                $recordModel->set($fieldName, $date_off);
            } else {
                $recordModel->set($fieldName, $oldRecord->get($fieldName));
            }
        }
       $recordModel->set('createnewperiod', 0);
       $recordModel->save();
        
        $oldRecord->set('mode', 'edit');
        $oldRecord->set('createnewperiod', 1);
        $oldRecord->save() ;
    }
    return;
    
} 