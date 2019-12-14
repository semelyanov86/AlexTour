<?php
function addCities($ws_entity){
    // WS id
    $ws_id = $ws_entity->getId();
    $module = $ws_entity->getModuleName();
    if (empty($ws_id) || empty($module)) {
        return;
    }

    // CRM id
    $crmid = vtws_getCRMEntityId($ws_id);
    if ($crmid <= 0) {
        return;
    }

    //получение объекта со всеми данными о текущей записи Модуля "Potentials"
    $potentialModuleInstance = Vtiger_Record_Model::getInstanceById($crmid);

    //получение id Тура
    $tourId = $potentialModuleInstance->get('cf_tours_id');
    $pagingModel = new Vtiger_Paging_Model();
    $pagingModel->set('page', '1');
    $pagingModel->set('limit', 10000);

    if($tourId) {
        $potentialModel = Vtiger_Module_Model::getInstance($module);
//        $oldRelatedList = Vtiger_RelationListView_Model::getInstance($potentialModuleInstance, 'Cities', 'Cities')->getEntries($pagingModel);
        $tourModel = Vtiger_Record_Model::getInstanceById($tourId, 'Tours');
        $cityModel = Vtiger_Module_Model::getInstance('Cities');
        $relationInstance = Vtiger_Relation_Model::getInstance($potentialModel, $cityModel, 'Cities');
        $relationModel = Vtiger_RelationListView_Model::getInstance($tourModel, 'Cities');
        $citiesList = $relationModel->getEntries($pagingModel);
        /*foreach ($oldRelatedList as $oldOrder){
            $relationInstance->deleteRelation($crmid, $oldOrder->getId());
        }*/
        foreach ($citiesList as $order) {
            $relationInstance->addRelation($crmid, $order->getId());
        }
    }
}