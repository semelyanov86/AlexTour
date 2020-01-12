<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Tours_Relation_Model extends Vtiger_Relation_Model {

    /**
     * Function to get Email enabled modules list for detail view of record
     * @return <array> List of modules
     */
    public function getToursModulesInfoForDetailView() {
        return array(
            'Flights' => array('fieldName' => 'flightsid', 'tableName' => 'vtiger_toursflightsrel')
        );
    }

    public function getOrderForTourModel($model, $relatedName) {
        global $adb;
        $orderList = array();
        $toursEnabledModulesInfo = $this->getToursModulesInfoForDetailView();
        if (array_key_exists($relatedName, $toursEnabledModulesInfo)) {
            $fieldName = $toursEnabledModulesInfo[$relatedName]['fieldName'];
            $tableName = $toursEnabledModulesInfo[$relatedName]['tableName'];

            $query = "SELECT $fieldName FROM $tableName WHERE `toursid` = ? ORDER BY `order`";
            $result = $adb->pquery($query, array($model->getId()));
            if($adb->num_rows($result)){
                for($i=0;$i<($adb->num_rows($result));$i++){
                    $orderList[$i] = $adb->query_result($result,$i,$fieldName);
                }
            }
        }
        return $orderList;
    }


    /**
     * Function to update the status of relation
     * @param <Number> Tour record id
     * @param <array> $statusDetails
     */
    public function updateOrder($sourceRecordId, $orderDetails = array()) {
        if ($sourceRecordId && $orderDetails) {
            $relatedModuleName = $this->getRelationModuleModel()->getName();
            $toursEnabledModulesInfo = $this->getToursModulesInfoForDetailView();
            if (array_key_exists($relatedModuleName, $toursEnabledModulesInfo)) {
                $fieldName = $toursEnabledModulesInfo[$relatedModuleName]['fieldName'];
                $tableName = $toursEnabledModulesInfo[$relatedModuleName]['tableName'];
                $db = PearDatabase::getInstance();

                foreach ($orderDetails as $relatedRecordId => $order) {
                    $updateQuery = "UPDATE $tableName SET `order` = ? WHERE `$fieldName` = ? AND `toursid` = ?";
                    $db->pquery($updateQuery, array($order, $relatedRecordId, $sourceRecordId));
                }
            }
        }
    }

    public function addRelation($sourcerecordId, $destinationRecordId)
    {
        global $adb;
        $destinationModuleName = $this->getRelationModuleModel()->get('name');
        $toursEnabledModulesInfo = $this->getToursModulesInfoForDetailView();
        if (array_key_exists($destinationModuleName, $toursEnabledModulesInfo)) {
            $fieldName = $toursEnabledModulesInfo[$destinationModuleName]['fieldName'];
            $tableName = $toursEnabledModulesInfo[$destinationModuleName]['tableName'];
            $searchSql = "SELECT MAX(`order`) as 'maxorder' FROM $tableName WHERE `toursid` = ?";
            $result = $adb->pquery($searchSql, array($sourcerecordId));
            $noofrows = $adb->num_rows($result);
            if($noofrows != 0){
                $ordernum = $adb->query_result($result,0,"maxorder");
                if ($ordernum != NULL) {
                    $ordernum++;
                } else {
                    $ordernum = 0;
                }
            } else {
                $ordernum = 0;
            }
            $sql = "INSERT INTO $tableName (`toursid`, $fieldName, `order`) VALUES (?, ?, ?)";
            $adb->pquery($sql, array($sourcerecordId, $destinationRecordId, $ordernum));
        }
        parent::addRelation($sourcerecordId, $destinationRecordId);
    }

    private function getMaxOrder($tableName, $sourcerecordId)
    {
        global $adb;
        $searchSql = "SELECT MAX(`order`) as 'maxorder' FROM $tableName WHERE `toursid` = ?";
        $result = $adb->pquery($searchSql, array($sourcerecordId));
        $noofrows = $adb->num_rows($result);
        if($noofrows != 0){
            return $adb->query_result($result,0,"maxorder");
        } else {
            return null;
        }
    }

    public function getCurrentOrderFromId($tableName, $sourcerecordId, $destinationRecordId, $fieldName)
    {
        global $adb;
        $searchSql = "SELECT `order` FROM $tableName WHERE `toursid` = ? AND `$fieldName` = ?";
        $result = $adb->pquery($searchSql, array($sourcerecordId, $destinationRecordId));
        $noofrows = $adb->num_rows($result);
        if($noofrows != 0){
            return $adb->query_result($result,0,"order");
        } else {
            return null;
        }
    }

    public function deleteRelation($sourceRecordId, $relatedRecordId)
    {
        global $adb;
        $destinationModuleName = $this->getRelationModuleModel()->get('name');
        $toursEnabledModulesInfo = $this->getToursModulesInfoForDetailView();
        if (array_key_exists($destinationModuleName, $toursEnabledModulesInfo)) {
            $fieldName = $toursEnabledModulesInfo[$destinationModuleName]['fieldName'];
            $tableName = $toursEnabledModulesInfo[$destinationModuleName]['tableName'];
            $sql = "DELETE FROM $tableName WHERE `toursid` = ? AND $fieldName = ?";
            $adb->pquery($sql, array($sourceRecordId, $relatedRecordId));
        }
        parent::deleteRelation($sourceRecordId, $relatedRecordId);
    }

    public function getMinOrderFromId($tableName, $sourcerecordId, $destinationRecordId, $fieldName)
    {
        global $adb;
        $searchSql = "SELECT `$fieldName`, MIN(`order`) FROM $tableName WHERE `toursid` = ?";
        $result = $adb->pquery($searchSql, array($sourcerecordId, $destinationRecordId));
        $noofrows = $adb->num_rows($result);
        if($noofrows != 0){
            return $adb->query_result($result,0,$fieldName);
        } else {
            return null;
        }
    }
}