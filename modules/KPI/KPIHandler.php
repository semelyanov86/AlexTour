<?php
require_once 'include/events/VTEventHandler.inc';
//vimport('~~/vtlib/Vtiger/Module.php');

class KPIHandler extends VTEventHandler {

    function handleEvent($eventName, $data) {
        global $adb;
        if ($eventName == 'vtiger.entity.aftersave' && $data->getModuleName() == "KPI") {
            $moduleId = $data->getId();
            $moduleName = $data->getModuleName();
            $entityData = $data->getData();
            foreach($entityData as $key => $value)
            {
                if(preg_match('/^cf_.+_id$/', $key, $name))
                {
                    $relModule = $adb->pquery("SELECT
                                    vtiger_field.*, vtiger_fieldmodulerel.relmodule, vtiger_tab.name
                                FROM
                                    vtiger_field
                                JOIN vtiger_tab ON (
                                    vtiger_field.tabid = vtiger_tab.tabid
                                )
                                JOIN vtiger_fieldmodulerel ON (
                                    vtiger_fieldmodulerel.fieldid = vtiger_field.fieldid
                                )
                                WHERE
                                    vtiger_field.fieldname = ?
                                 ",array($key));
                    if($adb->num_rows($relModule) > 0){
                        $moduleNameRelated = '';
                        while ($row = $adb->fetchByAssoc($relModule)) {
                            $moduleNameRelated = $row['relmodule'];
                        }
                        if(!empty($value)){
                            $result = $adb->pquery("SELECT vtiger_crmentityrel.*
                                                    FROM
                                                        vtiger_crmentityrel
                                                    WHERE crmid = ? AND relcrmid = ?",array($value,$moduleId,));

                            if($adb->num_rows($result) == 0){
                                $adb->pquery("INSERT INTO vtiger_crmentityrel (crmid,module,relcrmid,relmodule) VALUE (?,?,?,?)",array($value,$moduleNameRelated,$moduleId,$moduleName));
                            }
                        }else{
                            $result = $adb->pquery("SELECT vtiger_crmentityrel.*
                                                    FROM
                                                        vtiger_crmentityrel
                                                    WHERE module = ? AND relcrmid = ? AND relmodule = ?",array($moduleNameRelated,$moduleId,$moduleName));

                            if($count = $adb->num_rows($result) > 0){
                                $adb->pquery("DELETE FROM vtiger_crmentityrel WHERE module = ? AND relcrmid = ? AND relmodule = ?",array($moduleNameRelated,$moduleId,$moduleName));
                            }
                        }
                    }

                }
            }
        } elseif ($eventName == 'vtiger.entity.aftersave' && $data->getModuleName()) {
            return;
            $entityData = $data->getData();
            $id = $entityData['id'];
            $query = "SELECT * FROM vtiger_kpi INNER JOIN vtiger_kpicf ON vtiger_kpi.kpiid = vtiger_kpicf.kpiid INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_kpi.kpiid WHERE vtiger_crmentity.deleted = 0 AND cf_1193 = ?";
            $result = $adb->pquery($query, array($data->getModuleName()));
            if($adb->num_rows($result) >= 1) {
                $relModel = Vtiger_Relation_Model::getInstance(Vtiger_Module_Model::getInstance('KPI'), Vtiger_Module_Model::getInstance($data->getModuleName()));
                while ($result_set = $adb->fetch_array($result)) {
                    $kpiid = $result_set["kpiid"];
                    $relModel->addRelation($kpiid, $id);

                }
            }
        }

    }


}