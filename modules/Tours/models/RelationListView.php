<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Tours_RelationListView_Model extends Vtiger_RelationListView_Model {

    /**
     * Function to get list of record models in this relation
     * @param <Vtiger_Paging_Model> $pagingModel
     * @return <array> List of record models <Vtiger_Record_Model>
     */
    public function getEntries($pagingModel) {
        $relationModel = $this->getRelationModel();
        $parentRecordModel = $this->getParentRecordModel();
        $relatedModuleName = $relationModel->getRelationModuleModel()->getName();

        $relatedRecordModelsList = parent::getEntries($pagingModel);
        $toursEnabledModulesInfo = $relationModel->getToursModulesInfoForDetailView();
        $orderList = $relationModel->getOrderForTourModel($parentRecordModel, $relatedModuleName);
        $newRelatedList = array();

        if (array_key_exists($relatedModuleName, $toursEnabledModulesInfo) && $relatedRecordModelsList) {
            foreach ($orderList as $v) {
                $newRelatedList[$v] = $relatedRecordModelsList[$v];
            }
        }
        if (empty($newRelatedList)) {
            return $relatedRecordModelsList;
        } else {
            return $newRelatedList;
        }
    }
}