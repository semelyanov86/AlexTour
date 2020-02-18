<?php
require_once 'include/events/VTEventHandler.inc';
//vimport('~~/vtlib/Vtiger/Module.php');

class TourPricesHandler extends VTEventHandler {

    function handleEvent($eventName, $data) {
        global $adb;
        if ($eventName == 'vtiger.entity.aftersave' && $data->getModuleName() == "TourPrices") {
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
                } elseif ($key == 'cf_2072') {
                    $airports = json_decode($value);
//                    $decodedAirports = json_encode($value);
                    $recModel = Vtiger_Record_Model::getInstanceById($moduleId, 'TourPrices');
                    $this->deleteOldEntities($recModel, 'Airports');
                    $this->linkEntities($airports, $recModel, 'Airports');
//                    $this->updateFieldValue($key, $decodedAirports, $moduleId);
                }
            }
        }

    }

    private function linkEntities($entries, $recordModel, $module)
    {
        $relatedModule = Vtiger_Module_Model::getInstance($module);
        $relationModel = Vtiger_Relation_Model::getInstance($recordModel->getModule(), $relatedModule);
        foreach ($entries as $entry) {
//            $contactModel = Vtiger_Record_Model::getInstanceById($entry, $module);
//            if ($contactModel) {
                $relationModel->addRelation($recordModel->getId(), $entry);
//            }
        }
    }

    public function deleteOldEntities($recordModel, $module)
    {
        $pagingModel = new Vtiger_Paging_Model();
        $pagingModel->set('page', 1);
        if(!empty($limit)) {
            $pagingModel->set('limit', 100);
        }
        $relationModel = Vtiger_Relation_Model::getInstance(Vtiger_Module_Model::getInstance($recordModel->getModuleName()), Vtiger_Module_Model::getInstance($module));
        $relatedListModel = Vtiger_RelationListView_Model::getInstance($recordModel, $module);
        $entries = $relatedListModel->getEntries($pagingModel);
        foreach ($entries as $entry) {
            $relationModel->deleteRelation($recordModel->getId(), $entry->getId());
        }
    }

    public function updateFieldValue($field, $value, $id)
    {
        global $adb;
        $res = $adb->pquery("UPDATE vtiger_tourpricescf SET $field = ?, WHERE tourpricesid = ?", array($value, $id));
    }


}