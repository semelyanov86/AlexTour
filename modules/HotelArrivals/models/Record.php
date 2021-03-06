<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Class HotelArrivals_Record_Model extends Vtiger_Record_Model
{
    public function getContactsList()
    {
        global $adb;
        $ids = array();
        if (!$this->getId()) {
            return '';
        }
        $pagingModel = new Vtiger_Paging_Model();
        $pagingModel->set('page', 1);
        if(!empty($limit)) {
            $pagingModel->set('limit', 100);
        }
        $relationModel = Vtiger_RelationListView_Model::getInstance($this, 'Contacts');
        $entries = $relationModel->getEntries($pagingModel);
        foreach ($entries as $entry) {
            $ids[] = (int) $entry->getId();
        }

        if (empty($entries)) {
            return '';
        } else {
            return json_encode($ids);
        }
    }
}

?>