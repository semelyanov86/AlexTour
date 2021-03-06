<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * Class Visa_Module_Model
 */
class Visa_Module_Model extends Vtiger_Module_Model
{
    /**
     * @return array
     */
    function getSettingLinks()
    {
        $settingsLinks = parent::getSettingLinks();

//        $settingsLinks[] = array(
//            'linktype' => 'MODULESETTING',
//            'linklabel' => 'Uninstall',
//            'linkurl' => 'index.php?module=Visa&parent=Settings&view=Uninstall',
//            'linkicon' => ''
//        );

        return $settingsLinks;
    }

    /**
     * Function to get relation query for particular module with function name
     * @param <record> $recordId
     * @param <String> $functionName
     * @param Vtiger_Module_Model $relatedModule
     * @return <String>
     */
    public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
        if ($functionName === 'get_activities') {
            $focus = CRMEntity::getInstance($this->getName());
            $focus->id = $recordId;
            $userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');

            $query = "SELECT CASE WHEN (vtiger_users.user_name not like '') THEN $userNameSql ELSE vtiger_groups.groupname END AS user_name,
						vtiger_crmentity.*, vtiger_activity.activitytype, vtiger_activity.subject, vtiger_activity.date_start, vtiger_activity.time_start,
						vtiger_activity.recurringtype, vtiger_activity.due_date, vtiger_activity.time_end, vtiger_activity.visibility, vtiger_seactivityrel.crmid AS parent_id,
						CASE WHEN (vtiger_activity.activitytype = 'Task') THEN (vtiger_activity.status) ELSE (vtiger_activity.eventstatus) END AS status
						FROM vtiger_activity
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_activity.activityid
						LEFT JOIN vtiger_seactivityrel ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_cntactivityrel ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
							WHERE vtiger_crmentity.deleted = 0 AND vtiger_activity.activitytype <> 'Emails'
								AND (vtiger_seactivityrel.crmid = ".$recordId;
            $query .= ")";

            $relatedModuleName = $relatedModule->getName();
            $query .= $this->getSpecificRelationQuery($relatedModuleName);
            $nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
            if ($nonAdminQuery) {
                $query = appendFromClauseToQuery($query, $nonAdminQuery);
            }

            // There could be more than one contact for an activity.
            $query .= ' GROUP BY vtiger_activity.activityid';
        } else {
            $query = parent::getRelationQuery($recordId, $functionName, $relatedModule, $relationId);
        }

        return $query;
    }
    public function getModuleIcon() {
        $moduleName = $this->getName();
        $lowerModuleName = strtolower($moduleName);
        $title = vtranslate($moduleName, $moduleName);
        $moduleIcon = "<i class='vicon-$lowerModuleName' title='$title'></i>";

        return $moduleIcon;
    }

    public static function isRecordExist($name)
    {
        global $adb;
        $query = "select * from vtiger_visa inner join vtiger_crmentity on vtiger_visa.visaid = vtiger_crmentity.crmid where vtiger_visa.name=? and vtiger_crmentity.deleted = ?";
        $result = $adb->pquery($query, array($name, 0));
        $noofrows = $adb->num_rows($result);
        if($noofrows != 0)
        {
            $id = $adb->query_result($result,0,"visaid");
            return Vtiger_Record_Model::getInstanceById($id, 'Visa');
        } else {
            return false;
        }
    }

    public static function isContactExist($firstname, $lastname)
    {
        global $adb;
        $query = "select * from vtiger_contactdetails inner join vtiger_crmentity on vtiger_contactdetails.contactid = vtiger_crmentity.crmid where vtiger_contactdetails.firstname=? and vtiger_contactdetails.lastname=? and vtiger_crmentity.deleted = ?";
        $result = $adb->pquery($query, array($firstname, $lastname, 0));
        $noofrows = $adb->num_rows($result);
        if($noofrows != 0)
        {
            $id = $adb->query_result($result,0,"contactid");
            return Vtiger_Record_Model::getInstanceById($id, 'Contacts');
        } else {
            return false;
        }
    }

    public static function createRecordFromArray($array, $relModel = false)
    {
        $recordModel = false;
        foreach ($array as $module=>$fields) {
            $recordModel = Vtiger_Record_Model::getCleanInstance($module);
            $recordModel->set('mode', 'create');
            foreach ($fields as $fieldname => $fieldvalue) {
                $recordModel->set($fieldname, $fieldvalue);
            }
            if ($relModel && $relModel->getModuleName() === 'Contacts') {
                $recordModel->set('cf_contacts_id', $relModel->getId());
            }
            $recordModel->set('assigned_user_id', Users_Record_Model::getCurrentUserModel()->getId());
            $recordModel->save();
        }
        return $recordModel;
    }

}