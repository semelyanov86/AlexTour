<?php
/* * *******************************************************************************
 * The content of this file is subject to the EMAIL Maker license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */


require_once('include/events/SqlResultIterator.inc');
require_once('modules/com_vtiger_workflow/VTEntityCache.inc');

require_once 'include/Webservices/Utils.php';
require_once("modules/Users/Users.php");
require_once("include/Webservices/VtigerCRMObject.php");
require_once("include/Webservices/VtigerCRMObjectMeta.php");
require_once("include/Webservices/DataTransform.php");
require_once("include/Webservices/WebServiceError.php");
require_once 'include/utils/utils.php';
require_once 'include/Webservices/ModuleTypes.php';
require_once('include/Webservices/Retrieve.php');
require_once('include/Webservices/Update.php');
require_once 'include/Webservices/WebserviceField.php';
require_once 'include/Webservices/EntityMeta.php';
require_once 'include/Webservices/VtigerWebserviceObject.php';
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');

class EMAILMaker_EMAILMaker_Model extends Vtiger_Module_Model {

    private $version_type;
    private $license_key;
    private $version_no;
    private $basicModules;
    private $pageFormats;
    private $profilesActions;
    private $profilesPermissions;
    private $workflows = array("VTEMAILMakerMailTask");
    private $LUD = array();
    var $log;
    var $db;

    static $metaVariables = array(
        'Current Date' => '(general : (__VtigerMeta__) date) ($_DATE_FORMAT_)',
        'Current Time' => '(general : (__VtigerMeta__) time)',
        'System Timezone' => '(general : (__VtigerMeta__) dbtimezone)',
        'User Timezone' => '(general : (__VtigerMeta__) usertimezone)',
        'CRM Detail View URL' => '(general : (__VtigerMeta__) crmdetailviewurl)',
        'Portal Detail View URL' => '(general : (__VtigerMeta__) portaldetailviewurl)',
        'Site Url' => '(general : (__VtigerMeta__) siteurl)',
        'Portal Url' => '(general : (__VtigerMeta__) portalurl)',
        'Record Id' => '(general : (__VtigerMeta__) recordId)',
        'LBL_HELPDESK_SUPPORT_NAME' => '(general : (__VtigerMeta__) supportName)',
        'LBL_HELPDESK_SUPPORT_EMAILID' => '(general : (__VtigerMeta__) supportEmailid)',
    );
    
    function __construct(){
        $this->log = LoggerManager::getLogger('account');
        $this->db = PearDatabase::getInstance();
        $this->setLicenseInfo();
        $this->basicModules = array("20", "21", "22", "23");
        $this->profilesActions = array("EDIT" => "EditView", // Create/Edit
            "DETAIL" => "DetailView", // View
            "DELETE" => "Delete", // Delete
            "EXPORT_RTF" => "Export", // Export to RTF
        );
        $this->profilesPermissions = array();

        $this->name = "EMAILMaker";
        $this->id = getTabId("EMAILMaker");
        
        $_SESSION['KCFINDER']['uploadURL'] = "test/upload"; 
        $_SESSION['KCFINDER']['uploadDir'] = "../test/upload";
    }
    public function GetVersionType(){
        return $this->version_type;
    }
    public function GetLicenseKey(){
        return $this->license_key;
    }
    public function GetPageFormats(){
        return $this->pageFormats;
    }
    public function GetBasicModules(){
        return $this->basicModules;
    }
    public function GetProfilesActions(){
        return $this->profilesActions;
    }
    public function GetSearchSelectboxData() {
    
        $Search_Selectbox_Data = array();
        $sql = "SELECT * FROM vtiger_emakertemplates WHERE is_theme = '0' AND deleted = '0'";

        $result = $this->db->pquery($sql, array());
        $num_rows = $this->db->num_rows($result);
        for ($i = 0; $i < $num_rows; $i++) {
            $currModule = $this->db->query_result($result, $i, 'module');
            $templateid = $this->db->query_result($result, $i, 'templateid');
            $Template_Permissions_Data = $this->returnTemplatePermissionsData($currModule, $templateid);
            if ($Template_Permissions_Data["detail"] === false)
                continue;   

            $ownerid = $this->db->query_result($result, $i, 'owner');
            
            if (!isset($Search_Selectbox_Data["modules"][$currModule])) {
                $Search_Selectbox_Data["modules"][$currModule] = vtranslate($currModule, $currModule);
            }
            
            if (!isset($Search_Selectbox_Data["owners"][$ownerid])) {
                $Search_Selectbox_Data["owners"][$ownerid] = getUserFullName($ownerid);
            }
        }    
        
        return $Search_Selectbox_Data;
    }
    public function GetListviewData($orderby = "templateid", $sortorder = "asc", $formodule = "", $load_body = false, Vtiger_Request $request){


        $MODULE = "EMAILMaker";
        $current_user = Users_Record_Model::getCurrentUserModel();
        $status_sql = "SELECT * FROM vtiger_emakertemplates_userstatus
		             INNER JOIN vtiger_emakertemplates USING(templateid)
		             WHERE userid=? AND deleted = '0' ";
        $status_res = $this->db->pquery($status_sql, array($current_user->id));
        $status_arr = array();
        while ($status_row = $this->db->fetchByAssoc($status_res)){
            $status_arr[$status_row["templateid"]]["is_active"] = $status_row["is_active"];
            $status_arr[$status_row["templateid"]]["is_default"] = $status_row["is_default"];
            $status_arr[$status_row["templateid"]]["sequence"] = $status_row["sequence"];
        }
        
        $originOrderby = $orderby;
        $originDir = $sortorder;
        if ($orderby == "order"){
            $orderby = "module";
            $sortorder = "asc";
        }
        $R_Atr = array();
        $sql = "SELECT * FROM vtiger_emakertemplates WHERE is_theme = '0' AND deleted = '0' ";
        if ($formodule != "") $sql .= "AND (module = '".$formodule."' OR module IS NULL OR module = '') ";
        
        $Search = array();
        $Search_Types = array("templatename","category","formodule","description","sharingtype","owner");

        if ($request){
            if ($request->has('search_params') && !$request->isEmpty('search_params')) {

                $listSearchParams = $request->get('search_params');

                foreach($listSearchParams as $groupInfo){
                    if(empty($groupInfo)){
                        continue;
                    }
                    foreach($groupInfo as $fieldSearchInfo){
                        $st = $fieldSearchInfo[0];
                        $operator = $fieldSearchInfo[1];
                        $search_val = $fieldSearchInfo[2];

                        if (in_array($st,$Search_Types)){
                            if ($st == "templatename" || $st == "description" || $st == "category") {
                                $search_val = "%".$search_val."%";
                                $Search[] = "vtiger_emakertemplates.".$st." LIKE ?";
                            } elseif ($st == "formodule") {
                                $Search[] = "vtiger_emakertemplates.module = ?";
                            } else {
                                $Search[] = "vtiger_emakertemplates.".$st." = ?";
                            }
                            $R_Atr[] = $search_val;
                        }
                        if ($st == "status"){
                            $search_status =  $search_val;
                        }


                    }
                }
            }

            if (count($Search) > 0){
                $sql .= " AND ";
                $sql .= implode(" AND ",$Search);
            }
        }
        $sql .= "ORDER BY " . $orderby . " " . $sortorder;
        
        $result = $this->db->pquery($sql, $R_Atr);

        $return_data = Array();
        $num_rows = $this->db->num_rows($result);
        
        for ($i = 0; $i < $num_rows; $i++) {
            $currModule = $this->db->query_result($result, $i, 'module');
            $templateid = $this->db->query_result($result, $i, 'templateid');

            $Template_Permissions_Data = $this->returnTemplatePermissionsData($currModule, $templateid);
            if ($Template_Permissions_Data["detail"] === false)
                continue;
            
            $emailtemplatearray = array();
            $suffix = "";
            
            if (isset($status_arr[$templateid])){
                if ($status_arr[$templateid]["is_active"] == "0")
                    $emailtemplatearray['status'] = 0;
                else {
                    $emailtemplatearray['status'] = 1;
                    switch ($status_arr[$templateid]["is_default"]){
                        case "1":
                            $suffix = " (" . vtranslate("LBL_DEFAULT_NOPAR", "EMAILMaker") . " " . vtranslate("LBL_FOR_DV", "EMAILMaker") . ")";
                            break;
                        case "2":
                            $suffix = " (" . vtranslate("LBL_DEFAULT_NOPAR", "EMAILMaker") . " " . vtranslate("LBL_FOR_LV", "EMAILMaker") . ")";
                            break;
                        case "3":
                            $suffix = " (" . vtranslate("LBL_DEFAULT_NOPAR", "EMAILMaker") . ")";
                            break;
                    }
                }
                $emailtemplatearray['order'] = $status_arr[$templateid]["sequence"];
            } else {
                $emailtemplatearray['status'] = 1;
                $emailtemplatearray['order'] = 1;
            }

            if (!empty($search_status)) {
                if ($search_status !=  "status_".$emailtemplatearray['status']) {
                    continue;
                }
            }

            $emailtemplatearray['status_lbl'] = ($emailtemplatearray['status'] == 1 ? vtranslate("Active") : vtranslate("Inactive", "EMAILMaker"));
            $emailtemplatearray['name'] = $this->db->query_result($result, $i, 'templatename');
            $emailtemplatearray['templateid'] = $templateid;
            $emailtemplatearray['description'] = $this->db->query_result($result, $i, 'description');
            $emailtemplatearray['subject'] = $this->db->query_result($result, $i, 'subject');
            $emailtemplatearray['is_listview'] = $this->db->query_result($result, $i, 'is_listview');
            if ($load_body) $emailtemplatearray['body'] = $this->db->query_result($result, $i, 'body');
            $emailtemplatearray['module'] = vtranslate($currModule, $currModule);
            $emailtemplatearray['templatename'] = "<a href=\"index.php?module=EMAILMaker&view=Detail&record=" . $templateid . "&return_module=EMAILMaker&return_view=List\">" . $this->db->query_result($result, $i, 'templatename') . $suffix . "</a>";

            $pdftemplatearray['edit'] = "";
            if ($Template_Permissions_Data["edit"]) {
                $emailtemplatearray['edit'] .= "<li><a href=\"index.php?module=EMAILMaker&view=Edit&return_view=List&record=" . $templateid . "\">" . vtranslate("LBL_EDIT", $MODULE) . "</a></li>"
                    . "<li><a href=\"index.php?module=EMAILMaker&view=Edit&return_view=List&record=" . $templateid . "&isDuplicate=true\">" . vtranslate("LBL_DUPLICATE", $MODULE) . "</a></li>";
            }
            if ($Template_Permissions_Data["delete"]) {
                $emailtemplatearray['edit'] .= "<li><a data-id=\"" . $templateid . "\" href=\"javascript:void(0);\" class=\"deleteRecordButton\">".vtranslate('LBL_DELETE', $MODULE)."</a></li>";
            }

            
            $emailtemplatearray['category'] = $this->db->query_result($result, $i, 'category');
            
            $owner = $this->db->query_result($result, $i, 'owner');
            $emailtemplatearray['owner'] = getUserFullName($owner);
            $sharingtype = $this->db->query_result($result, $i, 'sharingtype');
            $emailtemplatearray['sharingtype'] = vtranslate(strtoupper($sharingtype)."_FILTER",'EMAILMaker');
            
            
            $return_data [] = $emailtemplatearray;
        }

        if ($originOrderby == "order"){
            $modules = array();
            foreach ($return_data as $key => $templateArr)
                $modules[$templateArr["module"]][$key] = $templateArr["order"];

            $tmpArr = array();
            foreach ($modules as $orderArr){
                if ($originDir == "asc")
                    asort($orderArr, SORT_NUMERIC);
                else
                    arsort($orderArr, SORT_NUMERIC);

                foreach ($orderArr as $rdIdx => $order)
                    $tmpArr[] = $return_data[$rdIdx];
            }
            $return_data = $tmpArr;
        }

        return $return_data;
    }
    public function GetDetailViewData($templateid,$skipperrmisions = false){
        $no_img = '&nbsp;<img src="layouts/vlayout/skins/images/no.gif" alt="no" />';
        $yes_img = '&nbsp;<img src="layouts/vlayout/skins/images/Enable.png" alt="yes" />';
        $result = $this->db->pquery("SELECT * FROM vtiger_emakertemplates WHERE templateid=? AND deleted = '0'", array($templateid));
        $emailtemplateResult = $this->db->fetch_array($result);
        if (!$skipperrmisions) {
            $Template_Permissions_Data = $this->returnTemplatePermissionsData($emailtemplateResult["module"], $templateid);
            if ($Template_Permissions_Data["detail"] === false){
                $this->DieDuePermission();
            }        
        }
        $data = $this->getUserStatusData($templateid);
        if (count($data) > 0){
            if ($data["is_active"] == "1"){
                $is_active = vtranslate("Active");
                $activateButton = vtranslate("LBL_SETASINACTIVE", "EMAILMaker");
            } else {
                $is_active = vtranslate("Inactive", "EMAILMaker");
                $activateButton = vtranslate("LBL_SETASACTIVE", "EMAILMaker");
            }
            switch ($data["is_default"]){
                case "0":
                    $is_default = vtranslate("LBL_FOR_DV", "EMAILMaker") . $no_img . '&nbsp;&nbsp;';
                    $is_default .= vtranslate("LBL_FOR_LV", "EMAILMaker") . $no_img;
                    $defaultButton = vtranslate("LBL_SETASDEFAULT", "EMAILMaker");
                    break;
                case "1":
                    $is_default = vtranslate("LBL_FOR_DV", "EMAILMaker") . $yes_img . '&nbsp;&nbsp;';
                    $is_default .= vtranslate("LBL_FOR_LV", "EMAILMaker") . $no_img;
                    $defaultButton = vtranslate("LBL_UNSETASDEFAULT", "EMAILMaker");
                    break;
                case "2":
                    $is_default = vtranslate("LBL_FOR_DV", "EMAILMaker") . $no_img . '&nbsp;&nbsp;';
                    $is_default .= vtranslate("LBL_FOR_LV", "EMAILMaker") . $yes_img;
                    $defaultButton = vtranslate("LBL_UNSETASDEFAULT", "EMAILMaker");
                    break;
                case "3":
                    $is_default = vtranslate("LBL_FOR_DV", "EMAILMaker") . $yes_img . '&nbsp;&nbsp;';
                    $is_default .= vtranslate("LBL_FOR_LV", "EMAILMaker") . $yes_img;
                    $defaultButton = vtranslate("LBL_UNSETASDEFAULT", "EMAILMaker");
                    break;
            }
        } else {
            $is_active = vtranslate("Active");
            $activateButton = vtranslate("LBL_SETASINACTIVE", "EMAILMaker");
            $is_default = vtranslate("LBL_FOR_DV", "EMAILMaker") .  $no_img . '&nbsp;&nbsp;';
            $is_default .= vtranslate("LBL_FOR_LV", "EMAILMaker") .  $no_img ;
            $defaultButton = vtranslate("LBL_SETASDEFAULT", "EMAILMaker");
        }
        $emailtemplateResult["is_active"] = $is_active;
        $emailtemplateResult["is_default"] = $is_default;
        $emailtemplateResult["activateButton"] = $activateButton;
        $emailtemplateResult["defaultButton"] = $defaultButton;
        $emailtemplateResult["templateid"] = $templateid;
        $emailtemplateResult["permissions"] = $Template_Permissions_Data;
        return $emailtemplateResult;
    }
    public function GetAttachmentsData($templateid){
        $Attachments = array();
        $sql = "SELECT vtiger_seattachmentsrel.attachmentsid as documentid FROM vtiger_notes 
                          INNER JOIN vtiger_crmentity 
                             ON vtiger_crmentity.crmid = vtiger_notes.notesid
                          INNER JOIN vtiger_seattachmentsrel 
                             ON vtiger_seattachmentsrel.crmid = vtiger_notes.notesid   
                          INNER JOIN vtiger_emakertemplates_documents 
                             ON vtiger_emakertemplates_documents.documentid = vtiger_notes.notesid
                          WHERE vtiger_crmentity.deleted = '0' AND vtiger_emakertemplates_documents.templateid = ?";
        $result = $this->db->pquery($sql, array($templateid));
        $num_rows = $this->db->num_rows($result);  
        if ($num_rows > 0){
            while($row = $this->db->fetchByAssoc($result)){
                $Attachments[] = $row["documentid"]; 
            }
        }         
        return $Attachments;
    }
    public function GetEditViewData($templateid){
        $result = $this->db->pquery("SELECT vtiger_emakertemplates_displayed.*, vtiger_emakertemplates.* FROM vtiger_emakertemplates "
                                    ."LEFT JOIN vtiger_emakertemplates_displayed USING(templateid) "
                                    ."WHERE vtiger_emakertemplates.templateid=?", array($templateid));
        $emailtemplateResult = $this->db->fetch_array($result);
        $data = $this->getUserStatusData($templateid);
        if (count($data) > 0) {
            $emailtemplateResult["is_active"] = $data["is_active"];
            $emailtemplateResult["is_default"] = $data["is_default"];
            $emailtemplateResult["order"] = $data["order"];
        } else {
            $emailtemplateResult["is_active"] = "1";
            $emailtemplateResult["is_default"] = "0";
            $emailtemplateResult["order"] = "1";
        }
        
        $Template_Permissions_Data = $this->returnTemplatePermissionsData($emailtemplateResult["module"], $templateid);
        $emailtemplateResult["permissions"] = $Template_Permissions_Data;
        
        return $emailtemplateResult;
    }
    private function GetStatusArr(){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $status_sql = "SELECT templateid, is_active, is_default, sequence 
                        FROM vtiger_emakertemplates_userstatus
                        INNER JOIN vtiger_emakertemplates USING(templateid)
                        WHERE userid=?";
        $status_res = $this->db->pquery($status_sql, array($current_user->id));
        $status_arr = array();
        while ($status_row = $this->db->fetchByAssoc($status_res)) {
            $status_arr[$status_row["templateid"]]["is_active"] = $status_row["is_active"];
            $status_arr[$status_row["templateid"]]["is_default"] = $status_row["is_default"];
            $status_arr[$status_row["templateid"]]["sequence"] = $status_row["sequence"];
        }        
        return $status_arr;
    }
    private function GetAvailableTemplatesResult($currModule, $forListView = false, $all = false){
        $is_listview = "";

        $params = array('0','0',$currModule);
        if ($all) {
            $where_lv = " (module=? OR module='' OR module IS NULL) ";
        } else {
            $where_lv = " module=? ";
        }

        if ($forListView == false) {
            $where_lv .= " AND is_listview=?";
            $params[] = "0";
        }

        $sql = "SELECT vtiger_emakertemplates_displayed.*, vtiger_emakertemplates.* FROM vtiger_emakertemplates "
              ."LEFT JOIN  vtiger_emakertemplates_displayed USING(templateid) "
              ."WHERE is_theme = ? AND deleted = ? AND " . $where_lv . " ORDER BY vtiger_emakertemplates.templateid";

        return $this->db->pquery($sql, $params);
    }
    public function GetAvailableTemplates($currModule, $forListView = false){
        $return_array = array();       
        $status_arr = $this->GetStatusArr();        
        $result = $this->GetAvailableTemplatesResult($currModule,$forListView);
        
        while ($row = $this->db->fetchByAssoc($result)){
            $templateid = $row["templateid"];
            if ($this->CheckTemplatePermissions($currModule, $templateid, false) == false)
                continue;
            if (isset($status_arr[$templateid]["is_active"]) && $status_arr[$templateid]["is_active"] == "0"){
                continue;
            }
            if (trim($row["category"]) == "")
                $return_array[$row["templateid"]] = $row["templatename"];
            else    
                $return_array[$row["category"]][$row["templateid"]] = $row["templatename"];
        }
        return $return_array;
    }
    public function GetAvailableTemplatesArray($currModule, $forListView = false, $recordId = false, $all = false){
        include_once 'include/Webservices/Retrieve.php';
        
        $return_array = array();
        $status_arr = $this->GetStatusArr();
        $result = $this->GetAvailableTemplatesResult($currModule,$forListView,$all);
        $num_rows = $this->db->num_rows($result);

        $current_user = Users_Record_Model::getCurrentUserModel();
        $entityCache = new VTEntityCache($current_user);
        $entityData = false;

        if ($num_rows > 0){
            if ($forListView == false){
                if ($recordId) {
                    $wsId = vtws_getWebserviceEntityId($currModule,$recordId);
                    $entityData = $entityCache->forId($wsId);
                }
            }

            while ($row = $this->db->fetchByAssoc($result)){
                $templateid = $row["templateid"];

                if ($this->CheckTemplatePermissions($currModule, $templateid, false) == false)
                    continue;

                if (isset($status_arr[$templateid]["is_active"]) && $status_arr[$templateid]["is_active"] == "0"){
                    continue;
                }

                if ($recordId && !$forListView) {
                    $EMAILMaker_Display_Model = new EMAILMaker_Display_Model();
                    if ($EMAILMaker_Display_Model->CheckDisplayConditions($row,$entityData,$currModule,$entityCache) == false)
                        continue;
                }

                if ($all) {
                    $return_array[] = $row;
                } else {
                    $option = array("value"=> $templateid,"label"=>$row["templatename"],"title" => $row["description"]);

                    if (trim($row["category"]) == "")
                        $return_array[0][$templateid] = $option;
                    else
                        $return_array[1][$row["category"]][$templateid] = $option;
                }
            }
        }
        return $return_array;
    } 
    public function GetDefaultTemplateId($currModule, $forListView = false){
        $current_user = Users_Record_Model::getCurrentUserModel();

        if (!$forListView) 
            $did = "1";
        else
            $did = "2";

        $sql = "SELECT templateid, is_active, is_default, sequence 
                       FROM vtiger_emakertemplates_userstatus  
                       INNER JOIN vtiger_emakertemplates USING(templateid)
                       WHERE userid=? AND vtiger_emakertemplates.module = ? AND is_active = '1' AND is_default IN (?,3)";
        $res = $this->db->pquery($sql, array($current_user->id,$currModule,$did));

        while ($row = $this->db->fetchByAssoc($res)){
            return $row["templateid"];
        }
        return "";
    }    
    public function GetAllModules(){
        $Modulenames = Array('' => vtranslate("LBL_PLS_SELECT", "EMAILMaker"));
        $disallowed_modules = '10, 28';
        if (in_array($_SESSION['VTIGER_DB_VERSION'], array('5.1.0', '5.2.0')))
            $disallowed_modules .= ', 9, 16';
        $sql = "SELECT tabid, name, tablabel
			FROM vtiger_tab
			WHERE isentitytype=1
				AND presence=0
				AND tabid NOT IN ($disallowed_modules)
			ORDER BY name ASC";
        $result = $this->db->pquery($sql,array());
        while ($row = $this->db->fetchByAssoc($result)){
            if (file_exists("modules/" . $row['name'])){
                if (isPermitted($row['name'], '') != "yes")
                    continue;
                $Modulenames[$row['name']] = vtranslate($row['tablabel'],$row['name']);
                $ModuleIDS[$row['name']] = $row['tabid'];
            }
        }
        return array($Modulenames, $ModuleIDS);
    }
    public function GetPreparedMPDF(&$mpdf, $records, $templates, $module, $language, $preContent = ""){
        require_once("modules/EMAILMaker/resources/mpdf/mpdf.php");
        $focus = CRMEntity::getInstance($module);
        $TemplateContent = array();
        $name = '';
        foreach ($records as $record){
            foreach ($focus->column_fields as $cf_key => $cf_value){
                $focus->column_fields[$cf_key] = '';
            }
            if ($module == 'Calendar'){
                $cal_res = $this->db->pquery("select activitytype from vtiger_activity where activityid=?", array($record));
                $cal_row = $this->db->fetchByAssoc($cal_res);
                if ($cal_row['activitytype'] == 'Task')
                    $focus->retrieve_entity_info($record, $module);
                else
                    $focus->retrieve_entity_info($record, 'Events');
            } else
                $focus->retrieve_entity_info($record, $module);
            $focus->id = $record;

            foreach ($templates AS $templateid) {
                $PDFContent = $this->GetPDFContentRef($templateid, $module, $focus, $language);

                $Settings = $PDFContent->getSettings();
                if ($name == "")
                    $name = $PDFContent->getFilename();

                if ($this->CheckTemplatePermissions($module, $templateid, false) == false){
                    $header_html = "";
                    $body_html = vtranslate("LBL_PERMISSION", "EMAILMaker");
                    $footer_html = "";
                } else {
                    if ($preContent != ""){
                        $PDFContent->getContent();
                        $header_html = $preContent["header" . $templateid];
                        $body_html = $preContent["body" . $templateid];
                        $footer_html = $preContent["footer" . $templateid];
                    } else {
                        $pdf_content = $PDFContent->getContent();
                        $header_html = $pdf_content["header"];
                        $body_html = $pdf_content["body"];
                        $footer_html = $pdf_content["footer"];
                    }
                }
                if ($Settings["orientation"] == "landscape")
                    $orientation = "L";
                else
                    $orientation = "P";

                $format = $Settings["format"];
                $formatPB = $format;
                if (strpos($format, ";") > 0) {
                    $tmpArr = explode(";", $format);
                    $format = array($tmpArr[0], $tmpArr[1]);
                    $formatPB = $format[0] . "mm " . $format[1] . "mm";
                } elseif ($Settings["orientation"] == "landscape") {
                    $format .= "-L";
                    $formatPB .= "-L";
                }
                $ListViewBlocks = array();
                if (strpos($body_html, "#LISTVIEWBLOCK_START#") !== false && strpos($body_html, "#LISTVIEWBLOCK_END#") !== false)
                    preg_match_all("|#LISTVIEWBLOCK_START#(.*)#LISTVIEWBLOCK_END#|sU", $body_html, $ListViewBlocks, PREG_PATTERN_ORDER);

                if (count($ListViewBlocks) > 0){
                    $TemplateContent[$templateid] = $pdf_content;
                    $TemplateSettings[$templateid] = $Settings;
                    $num_listview_blocks = count($ListViewBlocks[0]);
                    for ($i = 0; $i < $num_listview_blocks; $i++) {
                        $ListViewBlock[$templateid][$i] = $ListViewBlocks[0][$i];
                        $ListViewBlockContent[$templateid][$i][$record][] = $ListViewBlocks[1][$i];
                    }
                } else {
                    if (!is_object($mpdf)) {
                        $mpdf = new mPDF('', $format, '', '', $Settings["margin_left"], $Settings["margin_right"], 0, 0, $Settings["margin_top"], $Settings["margin_bottom"], $orientation);
                        $mpdf->SetAutoFont();
                        $this->mpdf_preprocess($mpdf, $templateid, $PDFContent->bridge2mpdf);
                        $this->mpdf_prepare_header_footer_settings($mpdf, $templateid, $Settings);
                        @$mpdf->SetHTMLHeader($header_html);
                    } else {
                        $this->mpdf_preprocess($mpdf, $templateid, $PDFContent->bridge2mpdf);
                        @$mpdf->SetHTMLHeader($header_html);
                        @$mpdf->WriteHTML('<pagebreak sheet-size="' . $formatPB . '" orientation="' . $orientation . '" margin-left="' . $Settings["margin_left"] . 'mm" margin-right="' . $Settings["margin_right"] . 'mm" margin-top="0mm" margin-bottom="0mm" margin-header="' . $Settings["margin_top"] . 'mm" margin-footer="' . $Settings["margin_bottom"] . 'mm" />');
                    }
                    @$mpdf->SetHTMLFooter($footer_html);
                    @$mpdf->WriteHTML($body_html);
                    $this->mpdf_postprocess($mpdf, $templateid, $PDFContent->bridge2mpdf);
                }
            }
        }
        if (count($TemplateContent) > 0){
            foreach ($TemplateContent AS $templateid => $TContent){
                $header_html = $TContent["header"];
                $body_html = $TContent["body"];
                $footer_html = $TContent["footer"];
                $Settings = $TemplateSettings[$templateid];

                foreach ($ListViewBlock[$templateid] AS $id => $text){
                    $replace = "";
                    $cridx = 1;
                    foreach ($records as $record) {
                        $replace .= implode("", $ListViewBlockContent[$templateid][$id][$record]);
                        $replace = str_ireplace('$CRIDX$', $cridx++, $replace);
                    }
                    $body_html = str_replace($text, $replace, $body_html);
                }
                if ($Settings["orientation"] == "landscape")
                    $orientation = "L";
                else
                    $orientation = "P";

                $format = $Settings["format"];
                $formatPB = $format; 
                if (strpos($format, ";") > 0){
                    $tmpArr = explode(";", $format);
                    $format = array($tmpArr[0], $tmpArr[1]);
                    $formatPB = $format[0] . "mm " . $format[1] . "mm";
                } elseif ($Settings["orientation"] == "landscape") {
                    $format .= "-L";
                    $formatPB .= "-L";
                }
                if (!is_object($mpdf)){
                    $mpdf = new mPDF('', $format, '', '', $Settings["margin_left"], $Settings["margin_right"], 0, 0, $Settings["margin_top"], $Settings["margin_bottom"], $orientation);
                    $mpdf->SetAutoFont();
                    $this->mpdf_preprocess($mpdf, $templateid);
                    $this->mpdf_prepare_header_footer_settings($mpdf, $templateid, $Settings);
                    @$mpdf->SetHTMLHeader($header_html);
                } else {
                    $this->mpdf_preprocess($mpdf, $templateid);
                    @$mpdf->SetHTMLHeader($header_html);
                    @$mpdf->WriteHTML('<pagebreak sheet-size="' . $formatPB . '" orientation="' . $orientation . '" margin-left="' . $Settings["margin_left"] . 'mm" margin-right="' . $Settings["margin_right"] . 'mm" margin-top="0mm" margin-bottom="0mm" margin-header="' . $Settings["margin_top"] . 'mm" margin-footer="' . $Settings["margin_bottom"] . 'mm" />');
                }
                @$mpdf->SetHTMLFooter($footer_html);
                @$mpdf->WriteHTML($body_html);
                $this->mpdf_postprocess($mpdf, $templateid);
            }
        }
        if (!is_object($mpdf)){
            @$mpdf = new mPDF();
            @$mpdf->WriteHTML(vtranslate("LBL_PERMISSION", "EMAILMaker"));
        }
        if ($name == ""){
            $name = $this->GenerateName($records, $templates, $module);
        }
        $name = str_replace(array(' ', '/', ','), array('-', '-', '-'), $name);
        return $name;
    }
    public function GenerateName($records, $templates, $module){
        $focus = CRMEntity::getInstance($module);
        $focus->retrieve_entity_info($records[0], $module);
        if (count($records) > 1){
            $name = "BatchPDF";
        } else {
            $module_tabid = getTabId($module);
            $result = $this->db->pquery("SELECT fieldname FROM vtiger_field WHERE uitype = 4 AND tabid = ?",array($module_tabid));
            $fieldname = $this->db->query_result($result, 0, "fieldname");
            if (isset($focus->column_fields[$fieldname]) && $focus->column_fields[$fieldname] != ""){
                $name = $this->generate_cool_uri($focus->column_fields[$fieldname]);
            } else { 
                $templatesStr = implode("_", $templates);
                $recordsStr = implode("_", $records);
                $name = $templatesStr . $recordsStr . date("ymdHi");
            }
        }
        return $name;
    }
    public function GetPDFContentRef($templateid, $module, $focus, $language){
        return new EMAILMaker_PDFContent_Model($templateid, $module, $focus, $language);
    }
    public function DeleteAllRefLinks(){
        require_once('vtlib/Vtiger/Link.php');
        $link_res = $this->db->pquery("SELECT tabid FROM vtiger_tab WHERE isentitytype = ?",array('1'));
        while ($link_row = $this->db->fetchByAssoc($link_res)){
            Vtiger_Link::deleteLink($link_row["tabid"], "DETAILVIEWSIDEBARWIDGET", "EMAILMaker");
            Vtiger_Link::deleteLink($link_row["tabid"], "DETAILVIEWWIDGET", "EMAILMaker");
            Vtiger_Link::deleteLink($link_row["tabid"], "LISTVIEWMASSACTION", "Send Emails with EMAILMaker");
        }
    }
    public function AddLinks($modulename){
        require_once('vtlib/Vtiger/Module.php');
       
        if ($modulename != ""){
            $link_module = Vtiger_Module::getInstance($modulename);
            $link_module->addLink('DETAILVIEWSIDEBARWIDGET', 'EMAILMaker', 'module=EMAILMaker&view=GetEMAILActions&record=$RECORD$');
            $link_module->addLink('LISTVIEWMASSACTION', 'Send Emails with EMAILMaker', 'javascript:EMAILMaker_Actions_Js.getListViewPopup(this,\'$MODULE$\');');

            // remove non-standardly created links (difference in linkicon column makes the links twice when updating from previous version)
            $tabid = getTabId($modulename);
            $res = $this->db->pquery("SELECT * FROM vtiger_links WHERE tabid=? AND linktype=? AND linklabel=? AND linkurl=? ORDER BY linkid DESC", array($tabid, 'DETAILVIEWSIDEBARWIDGET', 'EMAILMaker', 'module=EMAILMaker&view=GetEMAILActions&record=$RECORD$'));
            $i = 0;
            while ($row = $this->db->fetchByAssoc($res)){
                $i++;
                if ($i > 1)
                    $this->db->pquery("DELETE FROM vtiger_links WHERE linkid=?", array($row['linkid']));
            }
            $res = $this->db->pquery("SELECT * FROM vtiger_links WHERE tabid=? AND linktype=? AND linklabel=? AND linkurl=? ORDER BY linkid DESC", array($tabid, 'LISTVIEWMASSACTION', 'Send Emails with EMAILMaker', 'javascript:EMAILMaker_Actions_Js.javascript:getListViewPopup(this,\'$MODULE$\');'));
            $i = 0;
            while ($row = $this->db->fetchByAssoc($res)){
                $i++;
                if ($i > 1)
                    $this->db->pquery("DELETE FROM vtiger_links WHERE linkid=?", array($row['linkid']));
            }
        }
    }
    public function AddHeaderLinks(){
        require_once('vtlib/Vtiger/Module.php');
        $link_module = Vtiger_Module::getInstance("EMAILMaker");
        $link_module->addLink('HEADERSCRIPT', 'EMAILMakerJS', 'layouts/vlayout/modules/EMAILMaker/resources/EMAILMakerActions.js', "", "1");
    }
    public function actualizeLinks(){        
        $Related_Modules = getEmailRelatedModules();        
        $result1 = $this->db->pquery("SELECT module FROM vtiger_emakertemplates WHERE deleted = ? GROUP BY module",array('0'));
        $num_rows1 = $this->db->num_rows($result1);          
        if ($num_rows1 > 0) {
            while ($row = $this->db->fetchByAssoc($result1)){
                if (!in_array($row["module"], $Related_Modules)) $Related_Modules[] = $row["module"];
            }
        }        
        if (count($Related_Modules) > 0){
            foreach ($Related_Modules AS $module){
                $this->AddLinks($module);
            }
        }
        $this->AddHeaderLinks();
    }
    public function DieDuePermission(){    
        global $current_user, $default_theme;        
        if (isset($_SESSION['vtiger_authenticated_user_theme']) && $_SESSION['vtiger_authenticated_user_theme'] != '')
            $theme = $_SESSION['vtiger_authenticated_user_theme'];
        else {
            if (!empty($current_user->theme)){
                $theme = $current_user->theme;
            } else {
                $theme = $default_theme;
            }
        }
        $output = "<link rel='stylesheet' type='text/css' href='themes/$theme/style.css'>";
        $output .= "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
        $output .= "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>
      		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
      		<tbody><tr>
      		<td rowspan='2' width='11%'><img src='layouts/vlayout/skins/images/denied.gif'></td>
      		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>" . vtranslate("LBL_PERMISSION", "EMAILMaker") . "</span></td>
      		</tr>
      		<tr>
      		<td class='small' align='right' nowrap='nowrap'>
      		<a href='javascript:window.history.back();'>" . vtranslate("LBL_GO_BACK") . "</a><br></td>
      		</tr>
      		</tbody></table>
      		</div>";
        $output .= "</td></tr></table>";
        die($output);   
    }
    public function CheckTemplatePermissions($selected_module, $templateid = '', $die = true){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $result = true;
        if (!is_admin($current_user)){          
            if ($selected_module != "" && isPermitted($selected_module, '') != "yes"){
                $result = false;
            } elseif ($templateid != "" && $this->CheckSharing($templateid) === false){
                $result = false;
            }
            if ($result === false){
                $profileGlobalPermission = array();
                require('user_privileges/user_privileges_'.$current_user->id.'.php');
                require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

                if($profileGlobalPermission[1] ==0){
                    $result = true;
                }
            }            
            if ($die === true && $result === false){
                $this->DieDuePermission();
            }
        }
        return $result;
    }    
    public function returnTemplatePermissionsData($selected_module = "", $templateid = ""){
        $current_user = Users_Record_Model::getCurrentUserModel();        
        $result = true;        
        if (!is_admin($current_user)){        
            if ($selected_module != "" && isPermitted($selected_module, '') != "yes"){
                $result = false;
            } elseif ($templateid != "" && $this->CheckSharing($templateid) === false){
                $result = false;
            }        
            $detail_result = $result;

            if (!$this->CheckPermissions("EDIT")){
                $edit_result = false;
            } else {
                $edit_result = $result;
            }

            if (!$this->CheckPermissions("DELETE")){
                $delete_result = false;
            } else {
                $delete_result = $result;
            }
            
            if ($detail_result === false || $edit_result === false || $delete_result === false){
                $profileGlobalPermission = array();
                require('user_privileges/user_privileges_'.$current_user->id.'.php');
                require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

                if($profileGlobalPermission[1] == 0){
                    $detail_result = true;
                }
                if($profileGlobalPermission[2] == 0){
                    $edit_result = $delete_result = true;
                }
            }  
        } else {            
            $detail_result = $edit_result = $delete_result = $result;
        }
        return array("detail"=>$detail_result,"edit"=>$edit_result ,"delete"=>$delete_result);
    }
    public function GetProfilesPermissions(){
        if (count($this->profilesPermissions) == 0){
            $profiles = Settings_Profiles_Record_Model::getAll();
            $res = $this->db->pquery("SELECT * FROM vtiger_emakertemplates_profilespermissions", array());
            $permissions = array();
            while ($row = $this->db->fetchByAssoc($res)){
                if (isset($profiles[$row["profileid"]]))
                    $permissions[$row["profileid"]][$row["operation"]] = $row["permissions"];
            }

            foreach ($profiles as $profileid => $profilename){
                foreach ($this->profilesActions as $actionName){
                    $actionId = getActionid($actionName);
                    if (!isset($permissions[$profileid][$actionId])){
                        $permissions[$profileid][$actionId] = "0";
                    }
                }
            }
            ksort($permissions);
            $this->profilesPermissions = $permissions;
        }
        return $this->profilesPermissions;
    }
    public function CheckPermissions($actionKey){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $profileid = getUserProfile($current_user->id);
        $result = false;

        if (isset($this->profilesActions[$actionKey])) {
            $actionid = getActionid($this->profilesActions[$actionKey]);
            $permissions = $this->GetProfilesPermissions();
            
             if (isset($permissions[$profileid[0]][$actionid]) && $permissions[$profileid[0]][$actionid] == "0")
                $result = true;
        }
        return $result;
    }
    public function CheckSharing($templateid){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $result = $this->db->pquery("SELECT owner, sharingtype FROM vtiger_emakertemplates WHERE templateid = ?", array($templateid));
        $row = $this->db->fetchByAssoc($result);
        $owner = $row["owner"];
        $sharingtype = $row["sharingtype"];
        $result = false;
        if ($owner == $current_user->id){
            $result = true;
        } else {
            switch ($sharingtype){
                case "public":
                    $result = true;
                    break;
                case "private":
                    $subordinateUsers = $this->getSubRoleUserIds($current_user->roleid);
                    if (!empty($subordinateUsers) && count($subordinateUsers) > 0){
                        $result = in_array($owner, $subordinateUsers);
                    }
                    else
                        $result = false;
                    break;
                case "share":
                    $subordinateUsers = $this->getSubRoleUserIds($current_user->roleid);
                    if (!empty($subordinateUsers) && count($subordinateUsers) > 0 && in_array($owner, $subordinateUsers))
                        $result = true;
                    else {
                        $member_array = $this->GetSharingMemberArray($templateid);
                        if (isset($member_array["users"]) && in_array($current_user->id, $member_array["users"]))
                            $result = true;
                        elseif (isset($member_array["roles"]) && in_array($current_user->roleid, $member_array["roles"]))
                            $result = true;
                        else {
                            if (isset($member_array["rs"])) {
                                foreach ($member_array["rs"] as $roleid){
                                    $roleAndsubordinateRoles = getRoleAndSubordinatesRoleIds($roleid);
                                    if (in_array($current_user->roleid, $roleAndsubordinateRoles)) {
                                        $result = true;
                                        break;
                                    }
                                }
                            }
                            if ($result == false && isset($member_array["groups"])){
                                $current_user_groups = explode(",", fetchUserGroupids($current_user->id));
                                $res_array = array_intersect($member_array["groups"], $current_user_groups);
                                if (!empty($res_array) && count($res_array) > 0)
                                    $result = true;
                                else
                                    $result = false;
                            }
                        }
                    }
                    break;
            }
        }
        return $result;
    }
    private function getSubRoleUserIds($roleid){
        $subRoleUserIds = array();
        $subordinateUsers = getRoleAndSubordinateUsers($roleid);
        if (!empty($subordinateUsers) && count($subordinateUsers) > 0) {
            $currRoleUserIds = getRoleUserIds($roleid);
            $subRoleUserIds = array_diff($subordinateUsers, $currRoleUserIds);
        }
        return $subRoleUserIds;
    }
    public function GetSharingMemberArray($templateid,$foredit = false){

        $Types = array('users' => 'Users',
            'groups' => 'Groups',
            'roles' => 'Roles',
            'rs'  => 'RoleAndSubordinates');

        $result = $this->db->pquery("SELECT shareid, setype FROM vtiger_emakertemplates_sharing WHERE templateid = ? ORDER BY setype ASC", array($templateid));
        $memberArray = array();
        while ($row = $this->db->fetchByAssoc($result)){
            $setype =  $row["setype"];
            if ($foredit) $setype = $Types[$setype];

            $memberArray[$setype][$Types[$row["setype"]].":".$row["shareid"]] = $row["shareid"];
        }
        return $memberArray;
    }
    private function setLicenseInfo(){
        $t = $k = "";
        $this->version_no = EMAILMaker_Version_Helper::$version;
        $result = $this->db->pquery("SELECT version_type, license_key, license_info FROM vtiger_emakertemplates_license",array());

        if ($this->db->num_rows($result) > 0) {
            $license_info = $this->db->query_result($result,0,"license_info");            
            if ($license_info != "")
                $t = $this->db->query_result($result, 0, "version_type");
            else
                $t = "deactivate";

            $k = $this->db->query_result($result, 0, "license_key");
        }         
        $this->version_type = $t;
        $this->license_key = $k;
    }
    private function getUserStatusData($templateid){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $result = $this->db->pquery("SELECT is_active, is_default, sequence FROM vtiger_emakertemplates_userstatus WHERE templateid=? AND userid=?", array($templateid, $current_user->id));

        $data = array();
        if ($this->db->num_rows($result) > 0) {
            $data["is_active"] = $this->db->query_result($result, 0, "is_active");
            $data["is_default"] = $this->db->query_result($result, 0, "is_default");
            $data["order"] = $this->db->query_result($result, 0, "sequence");
        }
        return $data;
    }
    private function mpdf_preprocess(&$mpdf, $templateid, $bridge = ''){
        if ($bridge != '' && is_array($bridge)) {
            $mpdf->EMAILMakerRecord = $bridge["record"];
            $mpdf->EMAILMakerTemplateid = $bridge["templateid"];

            if (isset($bridge["subtotalsArray"]))
                $mpdf->EMAILMakerSubtotalsArray = $bridge["subtotalsArray"];
        }

        $this->mpdf_processing($mpdf, $templateid, 'pre');
    }
    private function mpdf_postprocess(&$mpdf, $templateid, $bridge = ''){
        $this->mpdf_processing($mpdf, $templateid, 'post');
    }
    private function mpdf_processing(&$mpdf, $templateid, $when){
        $path = 'modules/EMAILMaker/resources/mpdf_processing/';
        switch ($when) {
            case "pre":
                $filename = 'preprocessing.php';
                $functionname = 'emakertemplates_mpdf_preprocessing';
                break;
            case "post":
                $filename = 'postprocessing.php';
                $functionname = 'emakertemplates_mpdf_postprocessing';
                break;
        }
        if (is_file($path . $filename) && is_readable($path . $filename)){
            require_once($path . $filename);
            $functionname($mpdf, $templateid);
        }
    }
    private function mpdf_prepare_header_footer_settings(&$mpdf, $templateid, &$Settings){
        $mpdf->EMAILMakerTemplateid = $templateid;
        $disp_header = $Settings["disp_header"];
        $disp_optionsArr = array("dh_first", "dh_other");
        $disp_header_bin = str_pad(base_convert($disp_header, 10, 2), 2, "0", STR_PAD_LEFT);
        for ($i = 0; $i < count($disp_optionsArr); $i++) {
            if (substr($disp_header_bin, $i, 1) == "1")
                $mpdf->EMAILMakerDispHeader[$disp_optionsArr[$i]] = true;
            else
                $mpdf->EMAILMakerDispHeader[$disp_optionsArr[$i]] = false;
        }

        $disp_footer = $Settings["disp_footer"];
        $disp_optionsArr = array("df_first", "df_last", "df_other");
        $disp_footer_bin = str_pad(base_convert($disp_footer, 10, 2), 3, "0", STR_PAD_LEFT);
        for ($i = 0; $i < count($disp_optionsArr); $i++) {
            if (substr($disp_footer_bin, $i, 1) == "1")
                $mpdf->EMAILMakerDispFooter[$disp_optionsArr[$i]] = true;
            else
                $mpdf->EMAILMakerDispFooter[$disp_optionsArr[$i]] = false;
        }
    }
    public function GetReleasesNotif(){
        $mpdf_ver = $releases = $notif = "";
        $user_prefs = $this->GetUserSettings();
        if ($user_prefs["is_notified"] == "0")
            return $notif;

        if ($this->version_type != "deactivate") {
            $client = new soapclient2("http://www.crm4you.sk/EMAILMaker/ITS4YouWS.php", false);
            $client->soap_defencoding = 'UTF-8';
            $err = $client->getError();

            $params = array("EMAILMaker" => $this->version_no,
                "mpdf" => $mpdf_ver
            );

            $releases = $client->call("check_last_releases", $params);
            $checkArr = explode("_", $releases);
            if (count($checkArr) == 4) {
                if ($checkArr[1] != "ok")
                    $notif = '<a href="' . $checkArr[0] . '" onclick="return confirm(\'' . vtranslate("ARE_YOU_SURE", "EMAILMaker") . '\');" title="PDF Maker download" style="color:red;">' . vtranslate("LBL_NEW_EMAILMaker", "EMAILMaker") . " " . $checkArr[1] . " " . vtranslate("LBL_AVAILABLE", "EMAILMaker") . ".</a> ";
                if ($checkArr[3] != "ok")
                    $notif .= '<a href="javascript:void(0)" onclick="downloadNewRelease(\'mpdf\', \'' . $checkArr[2] . '\', \'' . vtranslate("ARE_YOU_SURE", "EMAILMaker") . '\');" title="mPDF download" style="color:red;">' . vtranslate("LBL_NEW_MPDF", "EMAILMaker") . " " . $checkArr[3] . " " . vtranslate("LBL_AVAILABLE", "EMAILMaker") . ".</a>";
            }
        }

        return $notif;
    }
    public function GetCustomLabels(){        
        require_once("modules/EMAILMaker/resources/classes/EMAILMakerLabel.class.php");
        $oLblArr = array();
        $languages = array();

        $sql = "SELECT k.label_id, k.label_key, v.lang_id, v.label_value
                FROM vtiger_emakertemplates_label_keys AS k
                LEFT JOIN vtiger_emakertemplates_label_vals AS v
                    USING(label_id)";
        $result = $this->db->pquery($sql, array());

        while ($row = $this->db->fetchByAssoc($result)) {
            if (!isset($oLblArr[$row["label_id"]])) {
                $oLbl = new EMAILMakerLabel($row["label_id"], $row["label_key"]);
                $oLblArr[$row["label_id"]] = $oLbl;
            } else {
                $oLbl = $oLblArr[$row["label_id"]];
            }
            $oLbl->SetLangValue($row["lang_id"], $row["label_value"]);
        }

        //getting the langs from vtiger_language
        $result = $this->db->pquery("SELECT * FROM vtiger_language WHERE active = ? ORDER BY id ASC", array("1"));
        while ($row = $this->db->fetchByAssoc($result)) {
            $languages[$row["id"]]["name"] = $row["name"];
            $languages[$row["id"]]["prefix"] = $row["prefix"];
            $languages[$row["id"]]["label"] = $row["label"];

            foreach ($oLblArr as $objLbl) {
                if ($objLbl->IsLangValSet($row["id"]) == false)
                    $objLbl->SetLangValue($row["id"], "");
            }
        }

        return array($oLblArr, $languages);
    }
    public function GetAvailableSettings(){
        $menu_array = array();
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        
        if($currentUserModel->isAdminUser()){
            $menu_array["EMAILMakerPrivilegies"]["location"] = "index.php?module=EMAILMaker&view=ProfilesPrivilegies";
            $menu_array["EMAILMakerPrivilegies"]["image_src"] = "themes/images/ico-profile.gif";
            $menu_array["EMAILMakerPrivilegies"]["desc"] = "LBL_PROFILES_DESC";
            $menu_array["EMAILMakerPrivilegies"]["label"] = "LBL_PROFILES";
            
            $menu_array["EMAILMakerCustomLables"]["location"] = "index.php?module=EMAILMaker&view=CustomLabels";
            $menu_array["EMAILMakerCustomLables"]["image_src"] = "themes/images/picklist.gif";
            $menu_array["EMAILMakerCustomLables"]["desc"] = "LBL_CUSTOM_LABELS_DESC";
            $menu_array["EMAILMakerCustomLables"]["label"] = "LBL_CUSTOM_LABELS";

            $menu_array["EMAILMakerProductBlockTpl"]["location"] = "index.php?module=EMAILMaker&view=ProductBlocks";
            $menu_array["EMAILMakerProductBlockTpl"]["image_src"] = "themes/images/terms.gif";
            $menu_array["EMAILMakerProductBlockTpl"]["desc"] = "LBL_PRODUCTBLOCKTPL_DESC";
            $menu_array["EMAILMakerProductBlockTpl"]["label"] = "LBL_PRODUCTBLOCKTPL";

            $menu_array["EMAILMakerLicense"]["location"] = "index.php?module=EMAILMaker&view=License";
            $menu_array["EMAILMakerLicense"]["image_src"] = Vtiger_Theme::getImagePath('proxy.gif');
            $menu_array["EMAILMakerLicense"]["desc"] = "LICENSE_SETTINGS_INFO";
            $menu_array["EMAILMakerLicense"]["label"] = "LBL_LICENSE";

            $menu_array["EMAILMakerButtons"]["location"] = "index.php?module=EMAILMaker&view=Buttons";
            $menu_array["EMAILMakerButtons"]["image_src"] = Vtiger_Theme::getImagePath('proxy.gif');
            $menu_array["EMAILMakerButtons"]["desc"] = "LBL_EMAIL_BUTTONS_DESC";
            $menu_array["EMAILMakerButtons"]["label"] = "LBL_EMAIL_BUTTONS";
            
            $menu_array["EMAILMakerButtons"]["location"] = "index.php?module=EMAILMaker&view=Extensions";
            $menu_array["EMAILMakerButtons"]["image_src"] = Vtiger_Theme::getImagePath('proxy.gif');
            $menu_array["EMAILMakerButtons"]["desc"] = "LBL_EXTENSIONS_DESC";
            $menu_array["EMAILMakerButtons"]["label"] = "LBL_EXTENSIONS";
            
            $menu_array["EMAILMakerUpgrade"]["location"] = "index.php?module=ModuleManager&parent=Settings&view=ModuleImport&mode=importUserModuleStep1";
            $menu_array["EMAILMakerUpgrade"]["desc"] = "LBL_UPGRADE_DESC";
            $menu_array["EMAILMakerUpgrade"]["label"] = "LBL_UPGRADE";
            
            $menu_array["EMAILMakerUninstall"]["location"] = "index.php?module=EMAILMaker&view=Uninstall";
            $menu_array["EMAILMakerUninstall"]["desc"] = "LBL_UNINSTALL_DESC";
            $menu_array["EMAILMakerUninstall"]["label"] = "LBL_UNINSTALL";
        }
        return $menu_array;
    }
    public function GetProductBlockFields(){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $result = array();
        
        $Article_Strings = array("" => vtranslate("LBL_PLS_SELECT", "EMAILMaker"),
            vtranslate("LBL_PRODUCTS_AND_SERVICES", "EMAILMaker") => array(
            "PRODUCTBLOC_START" => vtranslate("LBL_ARTICLE_START", "EMAILMaker"),
            "PRODUCTBLOC_END" => vtranslate("LBL_ARTICLE_END", "EMAILMaker")),
            vtranslate("LBL_PRODUCTS_ONLY", "EMAILMaker") => array(
            "PRODUCTBLOC_PRODUCTS_START" => vtranslate("LBL_ARTICLE_START", "EMAILMaker"),
            "PRODUCTBLOC_PRODUCTS_END" => vtranslate("LBL_ARTICLE_END", "EMAILMaker")),
            vtranslate("LBL_SERVICES_ONLY", "EMAILMaker") => array(
            "PRODUCTBLOC_SERVICES_START" => vtranslate("LBL_ARTICLE_START", "EMAILMaker"),
            "PRODUCTBLOC_SERVICES_END" => vtranslate("LBL_ARTICLE_END", "EMAILMaker")),
        );
        
        $result["ARTICLE_STRINGS"] = $Article_Strings;
        $Product_Fields = array("PS_CRMID" => vtranslate("LBL_RECORD_ID", "EMAILMaker"),
            "PS_NO" => vtranslate("LBL_PS_NO", "EMAILMaker"),
            "PRODUCTPOSITION" => vtranslate("LBL_PRODUCT_POSITION", "EMAILMaker"),
            "CURRENCYNAME" => vtranslate("LBL_CURRENCY_NAME", "EMAILMaker"),
            "CURRENCYCODE" => vtranslate("LBL_CURRENCY_CODE", "EMAILMaker"),
            "CURRENCYSYMBOL" => vtranslate("LBL_CURRENCY_SYMBOL", "EMAILMaker"),
            "PRODUCTNAME" => vtranslate("LBL_VARIABLE_PRODUCTNAME", "EMAILMaker"),
            "PRODUCTTITLE" => vtranslate("LBL_VARIABLE_PRODUCTTITLE", "EMAILMaker"),
            "PRODUCTEDITDESCRIPTION" => vtranslate("LBL_VARIABLE_PRODUCTEDITDESCRIPTION", "EMAILMaker"),
            "PRODUCTDESCRIPTION" => vtranslate("LBL_VARIABLE_PRODUCTDESCRIPTION", "EMAILMaker")            
        );

        if ($this->db->num_rows($this->db->pquery("SELECT tabid FROM vtiger_tab WHERE name = ?",array('Pdfsettings'))) > 0)
            $Product_Fields["CRMNOWPRODUCTDESCRIPTION"] = vtranslate("LBL_CRMNOW_DESCRIPTION", "EMAILMaker");

        $Product_Fields["PRODUCTQUANTITY"] = vtranslate("LBL_VARIABLE_QUANTITY", "EMAILMaker");
        $Product_Fields["PRODUCTUSAGEUNIT"] = vtranslate("LBL_VARIABLE_USAGEUNIT", "EMAILMaker");
        $Product_Fields["PRODUCTLISTPRICE"] = vtranslate("LBL_VARIABLE_LISTPRICE", "EMAILMaker");
        $Product_Fields["PRODUCTTOTAL"] = vtranslate("LBL_PRODUCT_TOTAL", "EMAILMaker");
        $Product_Fields["PRODUCTDISCOUNT"] = vtranslate("LBL_VARIABLE_DISCOUNT", "EMAILMaker");
        $Product_Fields["PRODUCTDISCOUNTPERCENT"] = vtranslate("LBL_VARIABLE_DISCOUNT_PERCENT", "EMAILMaker");
        $Product_Fields["PRODUCTSTOTALAFTERDISCOUNT"] = vtranslate("LBL_VARIABLE_PRODUCTTOTALAFTERDISCOUNT", "EMAILMaker");
        $Product_Fields["PRODUCTVATPERCENT"] = vtranslate("LBL_PRODUCT_VAT_PERCENT", "EMAILMaker");
        $Product_Fields["PRODUCTVATSUM"] = vtranslate("LBL_PRODUCT_VAT_SUM", "EMAILMaker");
        $Product_Fields["PRODUCTTOTALSUM"] = vtranslate("LBL_PRODUCT_TOTAL_VAT", "EMAILMaker");
        $result["SELECT_PRODUCT_FIELD"] = $Product_Fields;

        //Available fields for products
        $prod_fields = array();
        $serv_fields = array();

        $in = '0';
        if (vtlib_isModuleActive('Products'))
            $in = getTabId('Products');
        if (vtlib_isModuleActive('Services')){
            if ($in == '0')
                $in = getTabId('Services');
            else
                $in .= ', ' . getTabId('Services');
        }
        $sql = "SELECT  t.tabid, t.name,
                        b.blockid, b.blocklabel,
                        f.fieldname, f.fieldlabel
                FROM vtiger_tab AS t
                INNER JOIN vtiger_blocks AS b USING(tabid)
                INNER JOIN vtiger_field AS f ON b.blockid = f.block
                WHERE t.tabid IN (" . $in . ")
                    AND (f.displaytype != 3 OR f.uitype = 55)
                ORDER BY t.name ASC, b.sequence ASC, f.sequence ASC, f.fieldid ASC";
        $res = $this->db->pquery($sql,array());
        while ($row = $this->db->fetchByAssoc($res)){
            $module = $row["name"];
            $fieldname = $row["fieldname"];
            if (getFieldVisibilityPermission($module, $current_user->id, $fieldname) != '0')
                continue;

            $trans_field_nam = strtoupper($module) . "_" . strtoupper($fieldname);
            switch ($module) {
                case "Products":
                    $trans_block_lbl = vtranslate($row["blocklabel"], 'Products');
                    $trans_field_lbl = vtranslate($row["fieldlabel"], 'Products');
                    $prod_fields[$trans_block_lbl][$trans_field_nam] = $trans_field_lbl;
                    break;

                case "Services":
                    $trans_block_lbl = vtranslate($row["blocklabel"], 'Services');
                    $trans_field_lbl = vtranslate($row["fieldlabel"], 'Services');
                    $serv_fields[$trans_block_lbl][$trans_field_nam] = $trans_field_lbl;
                    break;

                default:
                    continue;
            }
        }
        $result["PRODUCTS_FIELDS"] = $prod_fields;
        $result["SERVICES_FIELDS"] = $serv_fields;

        return $result;
    }
    public function GetRelatedBlocks($select_module,$select_too = true){

        if($select_too) $Related_Blocks[""] = vtranslate("LBL_PLS_SELECT", "EMAILMaker");
        if ($select_module != "") {
            $Related_Modules = EMAILMaker_RelatedBlock_Model::getRelatedModulesList($select_module);

            if (count($Related_Modules) > 0){
                $sql = "SELECT * FROM vtiger_emakertemplates_relblocks
                        WHERE secmodule IN(" . generateQuestionMarks($Related_Modules) . ")
                            AND deleted = 0
                        ORDER BY relblockid";
                $result = $this->db->pquery($sql, $Related_Modules);
                while ($row = $this->db->fetchByAssoc($result)) {                    
                    if ($row["module"] == "PriceBooks" && $row["module"] != $select_module) {
                        $csql = "SELECT * FROM vtiger_pdfmaker_relblockcol WHERE relblockid = ? AND columnname LIKE ?";
                        $cresult = $this->db->pquery($csql, array($row["relblockid"],"vtiger_pricebookproductreltmp%"));
                        if ($this->db->num_rows($cresult) > 0) continue;
                    }                   
                    
                    $Related_Blocks[$row["relblockid"]] = $row["name"];
                }
            }
        }

        return $Related_Blocks;
    }
    public function GetUserSettings($userid = ""){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $userid = ($userid == "" ? $current_user->id : $userid);
        $result = $this->db->pquery("SELECT * FROM vtiger_emakertemplates_usersettings WHERE userid = ?", array($userid));

        $settings = array();
        if ($this->db->num_rows($result) > 0){
            while ($row = $this->db->fetchByAssoc($result)){
                $settings["is_notified"] = $row["is_notified"];
            }
        } else {
            $settings["is_notified"] = "0";
        }
        return $settings;
    }
    function generate_cool_uri($name){
        $Search = array("$", "€", "&", "%", ")", "(", ".", " - ", "/", " ", ",", "ľ", "š", "č", "ť", "ž", "ý", "á", "í", "é", "ó", "ö", "ů", "ú", "ü", "ä", "ň", "ď", "ô", "ŕ", "Ľ", "Š", "Č", "Ť", "Ž", "Ý", "Á", "Í", "É", "Ó", "Ú", "Ď", "\"", "°", "ß");
        $Replace = array("", "", "", "", "", "", "-", "-", "-", "-", "-", "l", "s", "c", "t", "z", "y", "a", "i", "e", "o", "o", "u", "u", "u", "a", "n", "d", "o", "r", "l", "s", "c", "t", "z", "y", "a", "i", "e", "o", "u", "d", "", "", "ss");
        $return = str_replace($Search, $Replace, $name);
        return $return;
    }    
    function createPDFAndSaveFile($request,$templates, $focus, $modFocus, $file_name, $moduleName, $language){
        $cu = Users_Record_Model::getCurrentUserModel();
        $dl = Vtiger_Language_Handler::getLanguage();
       
        $date_var = date("Y-m-d H:i:s");
        $ownerid = $focus->column_fields["assigned_user_id"];
        if (!isset($ownerid) || $ownerid == "") $ownerid = $cu->id;

        $current_id = $this->db->getUniqueID("vtiger_crmentity");
        $templates = rtrim($templates, ";");

        if ($templates != "0")
            $Templateids = explode(";", $templates);
        else
            $Templateids = array();

        $name = "";
        if (!$language || $language == "") $language = $dl;

        $preContent = "";
        $mode = $request->get('mode');
        $module = $request->get('module');
        if (isset($mode) && $mode == "edit" && isset($module) && $module == "EMAILMaker"){
            foreach ($Templateids as $templateid) {
                $preContent["header" . $templateid] = $request->get("header" . $templateid);
                $preContent["body" . $templateid] = $request->get("body" . $templateid);
                $preContent["footer" . $templateid] = $request->get("footer" . $templateid);
            }
        }
        $mpdf = "";
        $Records = array($modFocus->id);
        $name = $this->GetPreparedMPDF($mpdf, $Records, $Templateids, $moduleName, $language, $preContent);
        $name = $this->generate_cool_uri($name);
        $upload_file_path = decideFilePath();

        if ($name != "") $file_name = $name . ".pdf";

        $mpdf->Output($upload_file_path . $current_id . "_" . $file_name);

        $filesize = filesize($upload_file_path . $current_id . "_" . $file_name);
        $filetype = "application/pdf";

        $this->db->pquery("insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)", array($current_id, $cu->id, $ownerid, "Documents Attachment", $focus->column_fields["description"], $this->db->formatDate($date_var, true), $this->db->formatDate($date_var, true)));
        $this->db->pquery("insert into vtiger_attachments(attachmentsid, name, description, type, path) values(?, ?, ?, ?, ?)", array($current_id, $file_name, $focus->column_fields["description"], $filetype, $upload_file_path));
        $this->db->pquery('insert into vtiger_seattachmentsrel values(?,?)', array($focus->id, $current_id));
        $this->db->pquery("UPDATE vtiger_notes SET filesize=?, filename=? WHERE notesid=?", array($filesize, $file_name, $focus->id));

        return true;
    }    
    function getRecipientModulenames(){    
        $RecipientModulenames = array(""=> vtranslate('LBL_PLS_SELECT','EMAILMaker'),
                                      "Contacts" => vtranslate('Contacts'),
                                      "Accounts" => vtranslate('Accounts'),
                                      "Vendors" => vtranslate('Vendors'),
                                      "Leads" => vtranslate('Leads'),
                                      "Users" => vtranslate('LBL_USERS'));
        return $RecipientModulenames;
    }    
    function getSubjectFields(){
        $subjectFields = array("##DD.MM.YYYY##"=>vtranslate('LBL_CURDATE_DD.MM.YYYY','EMAILMaker'),
                               "##DD-MM-YYYY##"=>vtranslate('LBL_CURDATE_DD-MM-YYYY','EMAILMaker'),
                               "##DD/MM/YYYY##"=>vtranslate('LBL_CURDATE_DD/MM/YYYY','EMAILMaker'),
                               "##MM-DD-YYYY##"=>vtranslate('LBL_CURDATE_MM-DD-YYYY','EMAILMaker'),
                               "##MM/DD/YYYY##"=>vtranslate('LBL_CURDATE_MM/DD/YYYY','EMAILMaker'),
                               "##YYYY-MM-DD##"=>vtranslate('LBL_CURDATE_YYYY-MM-DD','EMAILMaker'));  
        return $subjectFields;   
    }    
    public function GetThemesData($orderby = "templateid", $sortorder = "asc"){
        $current_user = Users_Record_Model::getCurrentUserModel();

        $status_sql = "SELECT * FROM vtiger_emakertemplates_userstatus
		             INNER JOIN vtiger_emakertemplates USING(templateid)
		             WHERE userid=?";
        $status_res = $this->db->pquery($status_sql, array($current_user->id));
        $status_arr = array();
        while ($status_row = $this->db->fetchByAssoc($status_res)) {
            $status_arr[$status_row["templateid"]]["is_active"] = $status_row["is_active"];
        }
        $result = $this->db->pquery("SELECT * FROM vtiger_emakertemplates WHERE is_theme = '1' AND deleted = '0'", array());
        $Return_Data = Array();
        $num_rows = $this->db->num_rows($result);
        
        for ($i = 0; $i < $num_rows; $i++){
            $templateid = $this->db->query_result($result, $i, 'templateid');
            $Email_Theme_Array = array();
            $suffix = "";
            
            $Email_Theme_Array['themeid'] = $templateid;
            $Email_Theme_Array['themename'] = $this->db->query_result($result, $i, 'templatename');
            $Email_Theme_Array['description'] = $this->db->query_result($result, $i, 'description');

            if ($this->CheckPermissions("EDIT")){
                $Email_Theme_Array['edit'] = "<a href=\"index.php?module=EMAILMaker&view=Edit&themeid=" . $templateid . "&mode=EditTheme&return_module=EMAILMaker&return_view=List\"><i class=\"fa fa-pencil\" title=\"" . vtranslate("LBL_EDIT") . "\" ></i></a>&nbsp;";
                $Email_Theme_Array['edit'] .= "<a href=\"index.php?module=EMAILMaker&view=Edit&themeid=" . $templateid . "&mode=EditTheme&isDuplicate=true&return_module=EMAILMaker&return_view=List\"><i title=\"" . vtranslate("LBL_DUPLICATE") . "\" class=\"fa fa-clone alignMiddle\"></i></a>&nbsp;";
            }  
            if ($this->CheckPermissions("DELETE")){
                $Email_Theme_Array['edit'] .= "<a href=\"index.php?module=EMAILMaker&action=IndexAjax&mode=DeleteTheme&themeid=" . $templateid . "&return_module=EMAILMaker&return_view=List\"><i title=\"" . vtranslate("LBL_DELETE") . "\" class=\"fa fa-trash alignMiddle\"></i></a>";
            }                                     
            $Return_Data [] = $Email_Theme_Array;            
        }
        return $Return_Data;
    }    
    public function getDetailViewLinks($templateid = ''){
	$linkTypes = array('DETAILVIEWTAB');
        $detail_url = 'index.php?module=EMAILMaker&view=Detail&record='.$templateid;

        $detailViewLinks = array(
                        array('linktype' => 'DETAILVIEWTAB',
                              'linklabel' => vtranslate('LBL_PROPERTIES','EMAILMaker'),
                              'linkurl' => $detail_url,      
                              'linkicon' => ''),
                        array('linktype' => 'DETAILVIEWTAB',
                              'linklabel' => vtranslate('Documents'),
                              'linkurl' => $detail_url.'&relatedModule=Documents&mode=showDocuments',
                              'linkicon' => '')
        );

            $detailViewLinks[] = array('linktype' => 'DETAILVIEWTAB',
                                       'linklabel' => vtranslate('LBL_EMAIL_CAMPAIGNS_LIST','EMAILMaker'),
                                       'linkurl' => $detail_url.'&mode=showEmailCampaigns',
                                       'linkicon' => '');
            
            $current_user = Users_Record_Model::getCurrentUserModel();
            if ($current_user->isAdminUser()) {
                $detailViewLinks[] = array('linktype' => 'DETAILVIEWTAB',
                                           'linklabel' => vtranslate('LBL_EMAIL_WORKFLOWS_LIST','EMAILMaker'),
                                           'linkurl' => $detail_url.'&mode=showEmailWorkflows',
                                           'linkicon' => '');
            }

        if (vtlib_isModuleActive("ITS4YouStyles")){
                $detailViewLinks[] = array(
                                'linktype' => 'DETAILVIEWTAB',
                                'linklabel' => vtranslate('LBL_STYLES_LIST','ITS4YouStyles'),
                                'linkurl' => $detail_url.'&relatedModule=ITS4YouStyles&mode=showRelatedList',
                                'linkicon' => ''
                );
        }
        foreach ($detailViewLinks as $detailViewLink){
                $linkModelList['DETAILVIEWTAB'][] = Vtiger_Link_Model::getInstanceFromValues($detailViewLink);
        }     

        return $linkModelList;
    }
    public function getEmailTemplateDocuments($templateid = ''){
        $Documents_Records = array();
        $query = "SELECT vtiger_notes.*, vtiger_crmentity.*, vtiger_attachmentsfolder.foldername FROM vtiger_notes 
        INNER JOIN vtiger_crmentity 
         ON vtiger_crmentity.crmid = vtiger_notes.notesid
        INNER JOIN vtiger_emakertemplates_documents 
         ON vtiger_emakertemplates_documents.documentid = vtiger_notes.notesid
        INNER JOIN vtiger_attachmentsfolder 
         ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid  
        WHERE vtiger_crmentity.deleted = '0' AND vtiger_emakertemplates_documents.templateid = ?";
    	$list_result = $this->db->pquery($query, array($templateid));
        $num_rows = $this->db->num_rows($list_result);

        if ($num_rows > 0){
            while($row = $this->db->fetchByAssoc($list_result)){
                $assigned_to_name = getUserFullName($row["smownerid"]);
                $Documents_Records[] = array("id"=> $row["notesid"],"title"=> $row["title"], "name" => $row["filename"],"assigned_to"=> $assigned_to_name,"folder"=> $row["foldername"],"filesize" =>$row["filesize"]);
            }
        }
        return $Documents_Records;
    }    
    function retrieve_entity_info($record, $module){
    }
    function getEmailFieldToAdressat($mycrmid,$temp,$pmodule = ""){
        if ($temp == "-1"){
            $emailadd = getUserEmail($mycrmid);
        }elseif ($pmodule == "Users"){
            $ufocus = new Users();
            $ufocus->id = $mycrmid;
            $ufocus->retrieve_entity_info($mycrmid, 'Users');
            $emailadd = $ufocus->column_fields[$temp];
        }else{
            if ($pmodule == "") $pmodule=getSalesEntityType($mycrmid);

            $focus = CRMEntity::getInstance($pmodule);
            $focus->retrieve_entity_info($mycrmid,$pmodule);
            $emailadd=br2nl($focus->column_fields[$temp]);
        }

        $def_charset = vglobal("default_charset");
        $emailadd = html_entity_decode($emailadd, ENT_QUOTES, $def_charset);
        
        return $emailadd;
    }    
    function getEmailToAdressat($mycrmid,$temp, $pmodule = ""){
        if ($temp == "-1"){
            $emailadd = getUserEmail($mycrmid);
        }else{
            if ($pmodule == "") $pmodule=getSalesEntityType($mycrmid);

            $myquery='Select columnname from vtiger_field where fieldid = ? and vtiger_field.presence in (0,2)';
            $fresult=$this->db->pquery($myquery, array($temp));			
            if ($pmodule=='Contacts'){
                    require_once('modules/Contacts/Contacts.php');
                    $myfocus = new Contacts();
                    $myfocus->retrieve_entity_info($mycrmid,"Contacts");
            }elseif ($pmodule=='Accounts'){
                    require_once('modules/Accounts/Accounts.php');
                    $myfocus = new Accounts();
                    $myfocus->retrieve_entity_info($mycrmid,"Accounts");
            }elseif ($pmodule=='Leads'){
                    require_once('modules/Leads/Leads.php');
                    $myfocus = new Leads();
                    $myfocus->retrieve_entity_info($mycrmid,"Leads");
            }elseif ($pmodule=='Vendors'){
                    require_once('modules/Vendors/Vendors.php');
                    $myfocus = new Vendors();
                    $myfocus->retrieve_entity_info($mycrmid,"Vendors");
            }else {
                    $myfocus = CRMEntity::getInstance($pmodule);
                    $myfocus->retrieve_entity_info($mycrmid, $pmodule);
            }
            $fldname=$this->db->query_result($fresult,0,"columnname");
            $emailadd=br2nl($myfocus->column_fields[$fldname]);
        }
        
        $def_charset = vglobal("default_charset");
        $emailadd = html_entity_decode($emailadd, ENT_QUOTES, $def_charset);
        
        return $emailadd;
    }    
    public function getEmailsInfo($esentid){

        $content = "";
        $result = $this->db->pquery("SELECT total_emails FROM vtiger_emakertemplates_sent WHERE esentid = ?", array($esentid));
        $total_emails = $this->db->query_result($result,0,"total_emails");
        $result2 = $this->db->pquery("SELECT count(emailid) as total FROM vtiger_emakertemplates_emails WHERE status = '1' AND esentid = ?", array($esentid));
        $sent_emails = $this->db->query_result($result2,0,"total");  
/*
        if ($sent_emails == $total_emails){
            $status = "END";
            if ($total_emails > 1)
                $status_title = vtranslate("LBL_EMAILS_HAS_BEEN_SENT","EMAILMaker");
            else
                $status_title = vtranslate("LBL_EMAIL_HAS_BEEN_SENT","EMAILMaker");
        }else{
            $status_title = vtranslate("LBL_EMAILS_DISTRIBUTION","EMAILMaker");
            $status = "IN_PROCESS";
        }   */
        $status_title = vtranslate("LBL_MODULE_NAME","EMAILMaker");

        //$content = $sent_emails." ".vtranslate("LBL_EMAILS_SENT_FROM","EMAILMaker")." ".$total_emails;

        if ($sent_emails != $total_emails){
            $content .= vtranslate("LBL_EMAILS_DISTRIBUTION","EMAILMaker");
        } else {

            $total_send_emails = $total_emails;

            $result3 = $this->db->pquery("SELECT error FROM vtiger_emakertemplates_emails WHERE status = '1' AND error IS NOT NULL AND esentid = ?",array($esentid));
            $error_emails = $this->db->num_rows($result3);
            $error_info = "";
            if ($error_emails > 0){
                $total_send_emails -= $error_emails;
                //$content = vtranslate("LBL_FAILED_TO_SEND","EMAILMaker");

                $Errors = array();
                while($row3 = $this->db->fetchByAssoc($result3)){
                    $Errors[] = $row3['error'];
                }
                $error_info = implode("<br>",$Errors);
            }

            if ($total_send_emails > 0) {
                if ($total_send_emails > 1) {
                    $content .= $total_send_emails." ".vtranslate("LBL_EMAILS_HAS_BEEN_SENT","EMAILMaker");
                } else {
                    $content .= vtranslate("LBL_EMAIL_HAS_BEEN_SENT","EMAILMaker");
                }
            }

            if ($error_emails > 0){
                if ($content != "") $content .= "<br><br>";
                $content .= vtranslate("LBL_EMAILS_SENDING_FAILED","EMAILMaker");
                $content .= "<br>";
                $content .= $error_emails." ".vtranslate("LBL_TOTAL_EMAILS_COULD_NOT_BE_SENT","EMAILMaker");

                if ($error_info != ""){
                    $content .= "<br><br>".$error_info;
                }
            }
        }

        $buttons = "";
        /*
        if ($sent_emails != $total_emails){
            $buttons = "<div class='span5 textAlignRight'>";
                $buttons .= "<div class='marginRight10px'>";
                    $buttons .= "<button id='emailmaker_notifi_btn_".$esentid."' class='btn btn-success' type='button'><strong>".vtranslate("LBL_OPEN_EMAIL_POPUP","EMAILMaker")."</strong></button>";
                    $buttons .= "&nbsp;<button id='emailmaker_notifi_btn_stop_".$esentid."' class='btn btn-danger' type='button'><strong>".vtranslate("LBL_CANCEL")."</strong></button>";
                $buttons .= "</div>";
            $buttons .= "</div>";
        }*/
        
        $stop_q = vtranslate("LBL_CLOSE_EMAIL_POPUP","EMAILMaker");
        
        return array("id" => $esentid, "title" => $status_title, "content" => $content, "buttons" => $buttons, "sent_emails" => $sent_emails, "total_emails" => $total_emails, "error_emails" => $error_emails, "error_info" => $error_info, "stop_q" => $stop_q);
    }
    public function getRecordsEmails($sourceModule, $recordIds, $basic = ""){ 
        $source_data = $emailFields = array();

        $crmid = "";
        $single_record = false;

        if ($recordIds != "all" && !empty($recordIds)){
            if (!is_array($recordIds)) {
                $recordIds = explode(";",$recordIds);
            }
            if (count($recordIds) == 1 && $recordIds != "all"){
                $focus = CRMEntity::getInstance($sourceModule);
                $focus->id = $recordIds[0];
                $focus->retrieve_entity_info($focus->id, $sourceModule);
                $source_data = $focus->column_fields;
                $single_record = true;
                $crmid = $focus->id;
            }
        }
        $Emails = $this->getEmailFieldsFromModule($single_record,$sourceModule,$recordIds);
        if (count($Emails) > 0) $emailFields[] = array("crmid" => $crmid, "module" => $sourceModule, "data" => $source_data, "emails" => $Emails);

        if ($basic == ""){
            $querystr = "select uitype, fieldid, fieldname, fieldlabel, columnname from vtiger_field where tabid=? and uitype IN (50,51,57,73,75,81,68,10)";
            $res=$this->db->pquery($querystr, array(getTabid($sourceModule)));
            $numrows = $this->db->num_rows($res);
            if ($numrows > 0){    
                for($i = 0; $i < $numrows; $i++){
                    $uitype = $this->db->query_result($res,$i,'uitype');
                    $fieldname = $this->db->query_result($res,$i,'fieldname');
                    $fieldid = $this->db->query_result($res,$i,'fieldid');
                    $fieldlabel = $this->db->query_result($res,$i,'fieldlabel');
                    $name = getTranslatedString($fieldlabel);

                    if ($single_record){    
                        $related_id = $focus->column_fields[$fieldname];
                        if ($related_id == "" || $related_id == "0") continue;
                        if (Vtiger_Util_Helper::checkRecordExistance($related_id) == "1") continue;
                        $related_module = getSalesEntityType($related_id); 
                        $entity_name = getEntityName($related_module, $related_id);
                        $related_label = vtranslate($fieldlabel, $related_module);
                        $focus2 = CRMEntity::getInstance($related_module);
                        $focus2->id = $related_id;
                        $focus2->retrieve_entity_info($related_id, $related_module);
                        $related_data = $focus2->column_fields;
                        $RelatedIds = array($related_id);
                    } else {
                        $related_id = $related_module = $entity_name = "";
                        $related_data = array();
                        $related_label = vtranslate($fieldlabel);
                        $RelatedIds = array();
                    }
                    
                    if ($related_id == "" || $related_id == "0") $set_crmid = $fieldname; else $set_crmid = $related_id;
                    
                    if ($uitype == "10" || $uitype == "68"){
                        if ($single_record){
                            $emailFields[] = array("crmid" => $set_crmid, "label" => $related_label, "name" => $entity_name[$set_crmid], "module" => $related_module, "data" => $related_data, "emails" =>$this->getEmailFieldsFromModule($single_record,$related_module,array($related_id)));
                        }else{
                            if ($uitype == "68"){
                                $a_module_lang = getTranslatedString("Accounts","Accounts");
                                $c_module_lang = getTranslatedString("Contacts","Contacts");
                            }else{
                                $querystr2 = "select relmodule from vtiger_fieldmodulerel where fieldid=? and relmodule IN (?,?,?,?)";
                                $res2 = $this->db->pquery($querystr2, array($fieldid,"Accounts","Contacts","Vendors","Leads"));
                                $num_rows2 = $this->db->num_rows($res2);

                                if ($num_rows2 > 0){
                                    while($row2 = $this->db->fetchByAssoc($res2)){
                                        $module_lang = getTranslatedString($row2["relmodule"],$row2["relmodule"]);
                                        $emailFields[] = array("crmid" => $set_crmid, "label" => $related_label." (".$module_lang.")", "name" => $entity_name[$related_id], "module" => $row2["relmodule"], "data" => $related_data, "emails" =>$this->getEmailFieldsFromModule($single_record,$row2["relmodule"],$RelatedIds));
                                    } 
                                }
                            }
                        }
                    }else{
                        if ($related_module == ""){
                            switch ($uitype){
                                case "50":
                                case "51":
                                case "73": $related_module = "Accounts"; break;
                                case "57": $related_module = "Contacts"; break;
                                case "75": $related_module = "Vendors"; break;
                                case "81": $related_module = "Vendors"; break;
                            }
                            $related_name = vtranslate($fieldlabel, $related_module);
                        }
                        $emailFields[] = array("crmid" => $set_crmid, "label" => $related_label, "name" => $entity_name[$related_id], "module" => $related_module, "data" => $related_data, "emails" =>$this->getEmailFieldsFromModule($single_record,$related_module,$RelatedIds));
                    }
                }
            }
        }

        $Emails_Types = array("standard" => $emailFields);

        $UsersRes=$this->db->pquery("select uitype, fieldid, fieldname, fieldlabel, columnname from vtiger_field where tabid=? and uitype IN (52,53)", array(getTabid($sourceModule)));
        while($row = $this->db->fetchByAssoc($UsersRes)) {
                $U_Source_Data = $UserIds = array();
                if (isset($source_data[$row["fieldname"]])){
                        $user_id = $source_data[$row["fieldname"]];      
                        $UserIds = array($user_id);
                        $U_Source_Data = $this->getUserData($user_id);
                } else {
                        $user_id = $row["fieldname"]; 
                }
            
                $AUser_Emails = $this->getEmailFieldsFromModule($single_record, "Users", $UserIds);
                if (count($AUser_Emails) > 0) $Emails_Types["logged"][] = array("crmid" => $user_id, "module" => "Users", "data" => $U_Source_Data, "emails" => $AUser_Emails, "label" => vtranslate($row["fieldlabel"],$sourceModule));
        }
        
        return $Emails_Types;
    }    
    public function getEmailFieldsFromModule($single_record, $sourceModule, $recordIds){
        if (!is_array($recordIds)) $recordIds = array($recordIds);
        $moduleModel = Vtiger_Module_Model::getInstance($sourceModule);
        $emailFields = $moduleModel->getFieldsByType('email');
        $accesibleEmailFields = array();
        $emailColumnNames = array();
        $emailColumnModelMapping = array();

        foreach($emailFields as $index=>$emailField){
            $fieldName = $emailField->getName();
            if($emailField->isViewable()){
                $accesibleEmailFields[] = $emailField;
                $emailColumnNames[] = $emailField->get('column');
                $emailColumnModelMapping[$emailField->get('column')] = $emailField;
            }
        }
        
        $emailFields = $accesibleEmailFields;

        $emailFieldCount = count($emailFields);
        $tableJoined = array();

        if($emailFieldCount > 0){
            if ($single_record && count($recordIds) > 0){
                $moduleMeta = $moduleModel->getModuleMeta();
                $wsModuleMeta = $moduleMeta->getMeta();
                $tabNameIndexList = $wsModuleMeta->getEntityTableIndexList();

                
                if ($sourceModule == "Users") {
                    $main_table = 'vtiger_users';
                    $tableJoined = array($main_table);
                    $main_column = 'id';
                } else {
                    $main_table = 'vtiger_crmentity';
                    $main_column = 'crmid';
                }
                
                $queryWithFromClause = 'SELECT '. implode(',',$emailColumnNames). ' FROM '.$main_table;
                
                foreach($emailFields as $emailFieldModel) {
                    $fieldTableName = $emailFieldModel->table;
                    if(in_array($fieldTableName, $tableJoined)){
                        continue;
                    }
                    $tableJoined[] = $fieldTableName;
                    $queryWithFromClause .= ' INNER JOIN '.$fieldTableName.' ON '.$fieldTableName.'.'.$tabNameIndexList[$fieldTableName].'= vtiger_crmentity.crmid';
                }
                $query =  $queryWithFromClause . ' WHERE '.$main_table.'.deleted = 0 AND '.$main_column.' IN ('.  generateQuestionMarks($recordIds).') AND (';

                for($i=0; $i<$emailFieldCount;$i++){
                    for($j=($i+1);$j<$emailFieldCount;$j++){
                        $query .= ' (' . $emailFields[$i]->getName() .' != \'\' and '. $emailFields[$j]->getName().' != \'\')';
                        if(!($i == ($emailFieldCount-2) && $j == ($emailFieldCount-1))) {
                            $query .= ' or ';
                        }
                    }
                }
                $query .= ') LIMIT 1';

                $db = PearDatabase::getInstance();
                $result = $db->pquery($query,$recordIds);
                $num_rows = $db->num_rows($result);

                if($num_rows == 0){
                    $query = $queryWithFromClause . ' WHERE '.$main_table.'.deleted = 0 AND '.$main_column.' IN ('.  generateQuestionMarks($recordIds).') AND (';
                    foreach($emailColumnNames as $index =>$columnName) {
                        $query .= " $columnName != ''";
                        //add glue or untill unless it is the last email field
                        if($index != ($emailFieldCount -1 ) ){
                            $query .= ' or ';
                        }
                    }
                    $query .= ') LIMIT 1';
                    $result = $db->pquery($query, $recordIds);
                    if($db->num_rows($result) > 0) {
                        $row = $db->query_result_rowdata($result,0);
                        foreach($emailColumnNames as $emailColumnName){
                            if(!empty($row[$emailColumnName])) {
                                $emailFields = array($emailColumnModelMapping[$emailColumnName]);
                                break;
                            }
                        }
                    }else{
                        foreach($emailColumnNames as $emailColumnName){
                            $emailFields = array($emailColumnModelMapping[$emailColumnName]);
                            break;
                        }
                    }
                }
                
            }else{
                foreach($emailColumnNames as $emailColumnName){
                    $emailFields[] = $emailColumnModelMapping[$emailColumnName];
                }
            }
        }
        return $emailFields;
    }     
    public function GetEMAILPDFListData($PDFTemplateIds){
        $return_data = Array();

        if (vtlib_isModuleActive('PDFMaker')){

            $PDFMakerModel = Vtiger_Module_Model::getInstance('PDFMaker');

            $sql = 'SELECT templateid, description, filename, module
                            FROM vtiger_pdfmaker
                            WHERE templateid IN ('.  generateQuestionMarks($PDFTemplateIds).')';
            $result = $this->db->pquery($sql, $PDFTemplateIds);
            $num_rows = $this->db->num_rows($result);

            for ($i = 0; $i < $num_rows; $i++) {
                $currModule = $this->db->query_result($result, $i, 'module');
                $templateid = $this->db->query_result($result, $i, 'templateid');

                if ($PDFMakerModel->CheckTemplatePermissions($currModule, $templateid, false) === false)
                    continue;

                $return_data [$templateid] = $this->db->query_result($result, $i, 'filename');
            }
        }
        return $return_data;
    }    
    public function isTemplateForListView($templateid) {
        $result = $this->db->pquery("SELECT * FROM vtiger_emakertemplates WHERE templateid=? AND deleted = '0' AND is_listview = '1'", array($templateid));
        $num_rows = $this->db->num_rows($result);

        if ($num_rows > 0)
            return true;
        else
            return false;
    }    
    public function controlActiveDelay(){
        $result = $this->db->pquery("SELECT delay_active FROM vtiger_emakertemplates_delay", array());
        $delay_active = $this->db->query_result($result,0,"delay_active");  

        if ($delay_active == "1")
           return true;
        else
           return false;  
    }      
    public function geEmailCampaignsQuery($templateid, $formodule){
        $listQuery = "SELECT vtiger_emakertemplates_me.*, et.module, et.templatename, es.total_sent_emails FROM vtiger_emakertemplates_me "
             . "INNER JOIN vtiger_emakertemplates as et USING (templateid) "
             . "LEFT JOIN vtiger_emakertemplates_sent as es USING (esentid) "
             . "WHERE vtiger_emakertemplates_me.deleted = '0' ";
        if ($formodule != "") $listQuery .= "AND et.module = '".$formodule."' ";
        if ($templateid != "") $listQuery .= "AND et.templateid = '".$templateid."' ";
        return $listQuery;
    } 
    public function geEmailCampaignsCount($templateid = "", $formodule = ""){
        $listQuery = $this->geEmailCampaignsQuery($templateid, $formodule);
        $listResult = $this->db->pquery($listQuery, array());
        return $this->db->num_rows($listResult);
    }    
    public function geEmailCampaignsData($pagingModel, $templateid = "", $orderby = "templateid", $sortorder = "asc", $formodule = ""){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $listQuery = $this->geEmailCampaignsQuery($templateid, $formodule);
        $listQuery .= "ORDER BY " . $orderby . " " . $sortorder;
        
        $startIndex = $pagingModel->getStartIndex();
        $pageLimit = $pagingModel->getPageLimit();
        
        $nextListQuery = $listQuery.' LIMIT '.($startIndex+$pageLimit).',1';
        $listQuery .= " LIMIT $startIndex,".($pageLimit+1);
        $listResult = $this->db->pquery($listQuery, array());

        $listViewRecordModels = Array();
        $num_rows = $this->db->num_rows($listResult);

        for ($i = 0; $i < $num_rows; $i++){
            $ME_Data = $this->db->fetchByAssoc($listResult, $i); 
     
            if ($this->CheckTemplatePermissions($ME_Data["module"], $ME_Data["templateid"], false) == false)
                continue;
            
            $listViewRecordModels[] = EMAILMaker_RecordME_Model::getInstanceObject($ME_Data);
        }
        $pagingModel->calculatePageRange($listViewRecordModels);

        if($num_rows > $pageLimit){
            array_pop($listViewRecordModels);
            $pagingModel->set('nextPageExists', true);
        }else{
            $pagingModel->set('nextPageExists', false);
        }

        $nextPageResult = $this->db->pquery($nextListQuery, array());
        $nextPageNumRows = $this->db->num_rows($nextPageResult);
        if($nextPageNumRows <= 0) {
            $pagingModel->set('nextPageExists', false);
        }
        return $listViewRecordModels;
    }    
    public function GetWorkflowsList(){
        return $this->workflows;
    }    
    public function controlWorkflows(){
        $control = 0;
        $Workflows = $this->GetWorkflowsList(); 
        foreach ($Workflows AS $name){    
            $dest1 = "modules/com_vtiger_workflow/tasks/".$name.".inc";
            $dest2 = "layouts/v7/modules/Settings/Workflows/Tasks/".$name.".tpl";
            if (file_exists($dest1) && file_exists($dest2)) {
                $result1 = $this->db->pquery("SELECT * FROM com_vtiger_workflow_tasktypes WHERE tasktypename = ?",array($name));
                if ($this->db->num_rows($result1) > 0) $control++;
            } 
        }
        
        if (count($Workflows) == $control)
            return true;
        else
            return false;
    }    
    public function getEmailContent($emailid){
        $content = "";
        $sql = "SELECT vtiger_emakertemplates_contents.content FROM vtiger_emakertemplates_contents 
                INNER JOIN vtiger_activity using(activityid)
                INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_activity.activityid
                WHERE vtiger_emakertemplates_contents.activityid = ? AND vtiger_crmentity.deleted = 0";
        $result = $this->db->pquery($sql,array($emailid)); 
        $num_rows = $this->db->num_rows($result); 
        
        if ($num_rows > 0){
            $content = html_entity_decode($this->db->query_result($result,0,"content"));
        }
        return $content;
    }    
    function addAllAttachments($mail,$record){
        global $log, $root_directory;

        $adb = PearDatabase::getInstance();
        $res = $adb->pquery("select vtiger_attachments.* from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_attachments.attachmentsid = vtiger_seattachmentsrel.attachmentsid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_attachments.attachmentsid where vtiger_crmentity.deleted=0 and vtiger_seattachmentsrel.crmid=?", array($record));
        $count = $adb->num_rows($res);

        for($i=0;$i<$count;$i++){
            $fileid = $adb->query_result($res,$i,'attachmentsid');
            $filename = decode_html($adb->query_result($res,$i,'name'));
            $filepath = $adb->query_result($res,$i,'path');
            $filewithpath = $root_directory.$filepath.$fileid."_".$filename;
            if(is_file($filewithpath)){
                    $mail->AddAttachment($filewithpath,$filename);
            }
        }
        return $mail;
    }    
    public function getTrackImageDetails($crmId, $emailId, $emailTrack = true){
        $siteURL = vglobal('site_URL');
        $applicationKey = vglobal('application_unique_key');
        $trackURL = "$siteURL/modules/Emails/actions/TrackAccess.php?record=$emailId&parentId=$crmId&applicationKey=$applicationKey";
        $imageDetails = "<img src='$trackURL' alt='' width='1' height='1'>";
        return $imageDetails;
    }
   
    public static function getExpressions() {
        
        require_once 'modules/com_vtiger_workflow/include.inc';
        require_once 'modules/com_vtiger_workflow/expression_engine/VTExpressionsManager.inc';
       
        $db = PearDatabase::getInstance();

        $mem = new VTExpressionsManager($db);
        return $mem->expressionFunctions();
    }

    public static function getMetaVariables() {
        return self::$metaVariables;
    }
    
    public function geEmailWorkflowsQuery($templateid = "", $formodule = ""){
        $listQuery = "SELECT com_vtiger_workflows.*, com_vtiger_workflowtasks.task FROM com_vtiger_workflows "
             . "INNER JOIN com_vtiger_workflowtasks ON  com_vtiger_workflowtasks.workflow_id =  com_vtiger_workflows.workflow_id "
             . "WHERE com_vtiger_workflowtasks.task LIKE '%VTEMAILMakerMailTask%' ";
        if ($formodule != "") $listQuery .= "AND com_vtiger_workflows.module_name = '".$formodule."' ";
        if ($templateid != "") {
            $template_nl = strlen($templateid);
            $templatestring = '%;s:8:"template";s:'.$template_nl.':"'.$templateid.'";%';            
            $listQuery .= "AND com_vtiger_workflowtasks.task LIKE '".$templatestring."' ";
        }
        return $listQuery;
    } 
    public function geEmailWorkflowsCount($templateid = "", $formodule = ""){
        $listQuery = $this->geEmailWorkflowsQuery($templateid, $formodule);
        $listResult = $this->db->pquery($listQuery, array());
        return $this->db->num_rows($listResult);
    }    
    public function geEmailWorkflowsData($templateid = "", $orderby = "workflow_id", $sortorder = "asc", $formodule = ""){
        $current_user = Users_Record_Model::getCurrentUserModel();
        $listQuery = $this->geEmailWorkflowsQuery($templateid, $formodule);
        $listQuery .= "ORDER BY " . $orderby . " " . $sortorder;
        $listResult = $this->db->pquery($listQuery, array());

        $listViewRecordModels = Array();
        $num_rows = $this->db->num_rows($listResult);

        for ($i = 0; $i < $num_rows; $i++){
            $row = $this->db->fetchByAssoc($listResult, $i); 
            $record = Settings_Workflows_Record_Model::getInstance($row["workflow_id"]);
            
            $module_name = $row['module_name'];
            //To handle translation of calendar to To Do
            if($module_name == 'Calendar'){
                    $module_name = vtranslate('LBL_TASK', $module_name);
            }else{
                    $module_name = vtranslate($module_name, $module_name);
            }

            $row['module_name'] = $module_name;
            $row['execution_condition'] = vtranslate($record->executionConditionAsLabel($row['execution_condition']), 'Settings:Workflows');
            $record->setData($row);
            
            $listViewRecordModels[] = $record;
        }
        return $listViewRecordModels;
    }
    
    private function getUserData($userid){
        
        if (!isset($this->LUD[$userid])){
            $focus = CRMEntity::getInstance("Users");
            $focus->id = $userid;            
            $focus->retrieve_entity_info($userid, "Users");
            $this->LUD[$userid] = $focus->column_fields;
        }
        
        return $this->LUD[$userid];
    }

    function savePDFIntoEmail($request,$focus,$parentid,$pdf_template_ids,$pdf_language,$pmodule = ''){
        $adb = PearDatabase::getInstance();
        if(vtlib_isModuleActive('PDFMaker')) {
            if (class_exists('PDFMaker_PDFMaker_Model')){
                $PDFMaker = new PDFMaker_PDFMaker_Model();
                if (is_array($parentid)) {
                    $modFocus = $parentid;
                    $f_date = date("ymdHi");
                    $file_name = "doc_".$f_date.".pdf";
                    $Records = $parentid;

                } else {
                    $pmodule=getSalesEntityType($parentid);
                    $modFocus = CRMEntity::getInstance($pmodule);
                    if(isset($parentid)) {
                        $modFocus->retrieve_entity_info($parentid,$pmodule);
                        $modFocus->id = $parentid;
                    }
                    $module_tabid = getTabId($pmodule);
                    $result=$adb->pquery("SELECT fieldname FROM vtiger_field WHERE uitype=4 AND tabid=?",array($module_tabid));
                    $fieldname=$adb->query_result($result,0,"fieldname");
                    if(isset($modFocus->column_fields[$fieldname]) && $modFocus->column_fields[$fieldname]!=""){
                        $file_name = $PDFMaker->generate_cool_uri($modFocus->column_fields[$fieldname]).".pdf";
                    } else {
                        $f_date = date("ymdHi");
                        $file_name = "doc_".$f_date.".pdf";
                    }
                    $Records = array($parentid);
                }

                if (is_array($pdf_template_ids)){
                    $Templateids = $pdf_template_ids;
                } else {
                    $Templateids = explode(";",$pdf_template_ids);
                }

                foreach ($Templateids As $template_id) {
                    $PDFMaker->createPDFAndSaveFile($request, $template_id, $focus, $Records, $file_name, $pmodule, $pdf_language);
                }
            }
        }
    }

    public function sendEmails($esentid, $request){

        $def_charset = vglobal("default_charset");
        $currentUserModel = Users_Record_Model::getCurrentUserModel();

        $rootDirectory =  vglobal('root_directory');
        $adb = PearDatabase::getInstance();
        $message = "";

        $result0 = $adb->pquery("select from_email_field from vtiger_systems where server_type=?",array('email'));
        $from_email_field = $adb->query_result($result0,0,'from_email_field');

        $result = $adb->pquery("SELECT * FROM vtiger_emakertemplates_sent WHERE esentid = ?",array($esentid));
        $from_name = decode_html(decode_html($adb->query_result($result,0,"from_name")));
        $from_address = $adb->query_result($result,0,"from_email");
        $type = $adb->query_result($result,0,"type");
        $load_subject = $adb->query_result($result,0,"subject");
        $load_body = $adb->query_result($result,0,"body");
        $total_emails = $adb->query_result($result,0,"total_emails");
        $pdf_template_ids = $adb->query_result($result,0,"pdf_template_ids");
        $pdf_language = $adb->query_result($result,0,"pdf_language");
        $ids_for_pdf = $adb->query_result($result,0,"ids_for_pdf");
        $attachments = $adb->query_result($result,0,"attachments");
        $att_documents = $adb->query_result($result,0,"att_documents");
        $pmodule = $adb->query_result($result,0,"pmodule");
        $language = $adb->query_result($result,0,"language");
        if ($language == "") $language = $currentUserModel->get('language');
        $correct = "false";
        $cc = $bcc = $cc_ids = $bcc_ids = "";
        $all_emails_count = $sent_emails_count = 0;
        $result2 = $adb->pquery("SELECT * FROM vtiger_emakertemplates_emails WHERE esentid = ? AND status = '0' AND deleted = '0'",array($esentid));
        $not_emails_sent_num = $adb->num_rows($result2);

        if ($not_emails_sent_num > 0){
            if ($type != "2"){
                for($i=0; $i<$not_emails_sent_num; $i++) {

                    $mailer = Emails_Mailer_Model::getInstance();
                    $mailer->IsHTML(true);


                    $Inserted_Emails = array();
                    $semailid = $adb->query_result($result2,$i,"emailid");
                    $mailer->reinitialize();

                    $replyToEmail = $from_address;
                    if(isset($from_email_field) && $from_email_field!=''){
                        $from_address = $from_email_field;
                    }

                    $mailer->ConfigSenderInfo($from_address, $from_name, $replyToEmail);
                    $pid = $adb->query_result($result2,$i,"pid");
                    if ($pid != "" && $pid != "0") $formodule = getSalesEntityType($pid); else $formodule = "";
                    $myid = $adb->query_result($result2,$i,"email");
                    $emailadd = $adb->query_result($result2,$i,"email_address");

                    $parent_id = $adb->query_result($result2,$i,"parent_id");

                    if (strpos($myid, "|")){
                        list($mycrmid,$temp,$rmodule) = explode("|",$myid,3);
                    } else {
                        list($mycrmid,$temp) = explode("@",$myid,2);
                        $rmodule  = "";
                    }

                    if ($emailadd == "" && ($mycrmid == "email" || $mycrmid == "massemail")) {
                        $emailadd = $temp;
                    }

                    if ($mycrmid == "email"){
                        if (!empty($rmodule)){
                            $formodule = $rmodule;
                        }
                        $mycrmid = $rmodule = "";
                    } elseif ($mycrmid == "massemail"){
                        $mycrmid = $pid;
                        $rmodule = $pmodule;
                    } else {
                        if ($rmodule == ""){
                            if ($temp == "-1")
                                $rmodule = "Users";
                            else
                                $rmodule=getSalesEntityType($mycrmid);
                        }
                    }

                    if ($emailadd != ""){
                        $emailadd = html_entity_decode($emailadd, ENT_QUOTES, $def_charset);
                    }

                    if ($formodule == "" && $mycrmid != "" && $rmodule != "") {
                        $pid = $mycrmid;
                        $formodule = $rmodule;
                    }

                    if($temp == "-1" || $rmodule == "Users"){
                        $ufocus = new Users();
                        $ufocus->id = $mycrmid;
                        $ufocus->retrieve_entity_info($mycrmid, 'Users');
                        if ($emailadd == "") {
                            $emailadd = $ufocus->column_fields[$temp];
                            $emailadd = html_entity_decode($emailadd, ENT_QUOTES, $def_charset);
                        }
                        $user_full_name = getUserFullName($mycrmid);
                        $saved_toid = $user_full_name."<".$emailadd.">";
                    } else {
                        if ($mycrmid != ""){
                            if ($emailadd == "") {
                            // if ($delimeter == "@")
                            //    $emailadd = $EMAILMaker->getEmailToAdressat($mycrmid,$temp,$rmodule);
                            //else
                                $emailadd = $this->getEmailFieldToAdressat($mycrmid,$temp,$rmodule);
                            }
                            if ($rmodule !=""){
                                $entityNames = getEntityName($rmodule, $mycrmid);
                                $pname = $entityNames[$mycrmid];
                            }
                            $saved_toid = $pname."<".$emailadd.">";
                        } else {
                            $saved_toid = $emailadd;
                        }
                    }

                    $EMAILContentModel = EMAILMaker_EMAILContent_Model::getInstance($formodule, $pid, $language, $mycrmid, $rmodule);
                    $EMAILContentModel->setSubject($load_subject);
                    $EMAILContentModel->setBody($load_body);
                    $EMAILContentModel->getContent();

                    $subject = $EMAILContentModel->getSubject();
                    $body = $EMAILContentModel->getBody();

                    $mailer->Body = $body;
                    $mailer->Subject = $subject;

                    $mailer->AddAddress($emailadd);

                    $EC = array("cc","bcc");
                    $cc_ids = $adb->query_result($result2,$i,"cc_ids");
                    $bcc_ids = $adb->query_result($result2,$i,"bcc_ids");

                    foreach ($EC AS $et ) {

                        $AddEMails[$et] = array();

                        $ids = $adb->query_result($result2,$i,$et."_ids");
                        if ($ids != ""){
                            $IDs = explode(",",$ids);
                            foreach ($IDs AS $email_crm_id){
                                list($emailcrmid,$te,$emodule) = explode("|",trim($email_crm_id),3);

                                if ($te != "") {
                                    if (!in_array($te, $AddEMails[$et])) $AddEMails[$et][] = $te;

                                    if ($emailcrmid != "email" && $emailcrmid != "massemail" && is_numeric($emailcrmid) && $emodule != "Users") {
                                        $mycrmid = $emailcrmid;
                                        if (!in_array($mycrmid,$Inserted_Emails)){
                                            $Inserted_Emails[] = $mycrmid;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $ccs = $AddEMails["cc"];
                    $bccs = $AddEMails["bcc"];

                    $cc = implode(",",$ccs);
                    $bcc = implode(",",$bccs);

                    $focus = CRMEntity::getInstance("Emails");

                    if ($parent_id != "" && $parent_id != "0"){
                        $focus->retrieve_entity_info($parent_id,"Emails");
                        $focus->id = $parent_id;
                        $focus->mode = "edit";
                    }

                    $focus->column_fields["subject"] = $subject;
                    $focus->column_fields["description"] = $body;
                    $focus->column_fields["date_start"] = date(getNewDisplayDate());//This will be converted to db date format in save
                    $focus->column_fields["time_start"] = date('H:i');

                    if($rmodule != "Users" && $mycrmid != "") $focus->column_fields["parent_id"] = $mycrmid;

                    if ($parent_id == "" || $parent_id == "0"){
                        $focus->filename = $focus->parent_id = $focus->parent_type = "";
                        $focus->column_fields["assigned_user_id"]=$currentUserModel->id;
                        $focus->column_fields["activitytype"]="Emails";

                        $focus->column_fields["saved_toid"] = $saved_toid;
                        $focus->column_fields["ccmail"] = $cc;
                        $focus->column_fields["bccmail"] = $bcc;
                        $focus->save("Emails");

                        if ($mycrmid != "" && $rmodule != "Users" && !in_array($mycrmid,$Inserted_Emails)){
                            $Inserted_Emails[] = $mycrmid;
                        }

                        if (count($Inserted_Emails) > 0) {
                            foreach ($Inserted_Emails AS $eid) {
                                if ($eid != "email") $adb->pquery('insert into vtiger_seactivityrel values(?,?)',array($eid,$focus->id));
                            }
                        }
                        $parent_id = $focus->id;
                    } else {
                        $focus->column_fields["saved_toid"] = $saved_toid;
                        $focus->column_fields["ccmail"] = $cc;
                        $focus->column_fields["bccmail"] = $bcc;
                        $focus->save("Emails");
                    }

                    if ($formodule != "" && $pid != "") {
                        $adb->pquery('insert into vtiger_crmentityrel (crmid, module, relcrmid, relmodule) values(?,?,?,?)',array($pid,$formodule,$focus->id,'Emails'));
                    }
                    if ($pdf_template_ids != ""){
                        if ($ids_for_pdf != ""){
                            $IDs_for_pdf = explode(";",$ids_for_pdf);
                            if (count($IDs_for_pdf) == 1) $IDs_for_pdf = $IDs_for_pdf[0];
                        } else {
                            $IDs_for_pdf = $pid;
                        }
                        $this->savePDFIntoEmail($request,$focus,$IDs_for_pdf ,$pdf_template_ids,$pdf_language,$pmodule);
                    }

                    $mailer = $this->addAllAttachments($mailer,$focus->id);
                    /*
                                $pos = strpos($description, '$logo$');
                                if ($pos !== false){
                                    $description =str_replace('$logo$','<img src="cid:logo" />',$description);
                                    $logo = true;
                                } */

                    if ($att_documents != ""){
                        $attachments = $this->getAttachmentDetails($att_documents);
                        if(is_array($attachments)){
                            foreach($attachments as $attachment){
                                $fileNameWithPath = $rootDirectory.$attachment['path'].$attachment['fileid']."_".$attachment['attachment'];
                                if(is_file($fileNameWithPath)){
                                    $mailer->AddAttachment($fileNameWithPath, $attachment['attachment']);
                                    $result_att = $adb->pquery("SELECT * FROM vtiger_seattachmentsrel WHERE crmid = ? AND attachmentsid = ?", array($parent_id,$attachment['crmid']));
                                    $num_rows_att = $adb->num_rows($result_att);

                                    if ($num_rows_att == 0) $adb->pquery("INSERT INTO vtiger_seattachmentsrel (crmid, attachmentsid) VALUES(?, ?)", array($parent_id, $attachment['crmid']));
                                }
                            }
                        }
                    }
                    /*
                                    if ($logo){
                                            $mailer->AddEmbeddedImage(vimage_path('logo_mail.jpg'), 'logo', 'logo.jpg', 'base64', 'image/jpg');
                                    }
                    */
                    $Email_Images = $EMAILContentModel->getEmailImages();
                    if (count($Email_Images) > 0){
                        foreach ($Email_Images AS $cid => $cdata){
                            $mailer->AddEmbeddedImage($cdata["path"], $cid, $cdata["name"]);
                        }
                    }

                    if(!empty($ccs)){
                        foreach($ccs as $cc){
                            $mailer->AddCC($cc);
                        }
                    }
                    if(!empty($bccs)){
                        foreach($bccs as $bcc){
                            $mailer->AddBCC($bcc);
                        }
                    }
                    if($temp != "-1") {
                        if ($mycrmid != "") $mailer->Body .= $this->getTrackImageDetails($mycrmid,$parent_id);
                    }
                    $status = $mailer->Send(true);
                    if(!$status){
                        $mail_status = $mailer->getError();
                    }
                    $sql_u = "UPDATE vtiger_emakertemplates_emails SET email_send_date = now(), status = '1', parent_id = ?";
                    if (!$status){
                        $sql_u .= ", error = '".$mail_status."'";
                    }
                    $sql_u .= " WHERE emailid = ?";
                    $adb->pquery($sql_u,array($parent_id, $semailid));

                    $message = vtranslate('LBL_EMAIL_INFO','EMAILMaker')." ";
                    $message .= $emailadd;
                    if ($status){
                        $correct = "true";
                        $sql_u2 = "UPDATE vtiger_emaildetails SET email_flag = 'SENT' WHERE emailid = ?";
                        $adb->pquery($sql_u2,array($parent_id));
                        $sql_u2 = "UPDATE vtiger_emakertemplates_sent SET total_sent_emails = total_sent_emails + 1 WHERE esentid = ?";
                        $adb->pquery($sql_u2,array($esentid));
                        $message .= " ".vtranslate('LBL_EMAIL_INFO_YES','EMAILMaker');
                    } else {
                        $message .= " ".vtranslate('LBL_EMAIL_INFO_NO','EMAILMaker');
                        if ($mail_status != "") {
                            $message .= " (".$mail_status.")";
                        } else {
                            $sql_u3 = "UPDATE vtiger_emakertemplates_emails SET error = ? WHERE emailid = ?";
                            $adb->pquery($sql_u3,array($message,$semailid));
                        }
                    }

                    if (class_exists(EMAILMaker_AfterSend_Helper)){
                        EMAILMaker_AfterSend_Helper::runAfterSend($parent_id,$status,$mailer);
                    }
                }
            }
        }
    }

    function getAttachmentDetails($att_documents){
        $adb = PearDatabase::getInstance();
        $rootDirectory =  vglobal('root_directory');
        $Att_Documents = explode(",",$att_documents);
        $attachmentRes = $adb->pquery('SELECT vtiger_seattachmentsrel.crmid, vtiger_attachments.* FROM vtiger_attachments
                                      LEFT JOIN vtiger_seattachmentsrel ON vtiger_attachments.attachmentsid = vtiger_seattachmentsrel.attachmentsid
                                      WHERE vtiger_seattachmentsrel.crmid IN ('.  generateQuestionMarks($Att_Documents).') OR vtiger_attachments.attachmentsid IN ('.  generateQuestionMarks($Att_Documents).')', array($Att_Documents, $Att_Documents));
        $numOfRows = $adb->num_rows($attachmentRes);
        $attachmentsList = array();
        if($numOfRows) {
            for($i=0; $i<$numOfRows; $i++) {
                $attachmentsList[$i]['crmid'] = $adb->query_result($attachmentRes, $i, 'attachmentsid');
                $attachmentsList[$i]['fileid'] = $adb->query_result($attachmentRes, $i, 'attachmentsid');
                $attachmentsList[$i]['attachment'] = decode_html($adb->query_result($attachmentRes, $i, 'name'));
                $path = $adb->query_result($attachmentRes, $i, 'path');
                $attachmentsList[$i]['path'] = $path;
                $attachmentsList[$i]['size'] = filesize($rootDirectory.$path.$attachmentsList[$i]['fileid'].'_'.$attachmentsList[$i]['attachment']);
                $attachmentsList[$i]['type'] = $adb->query_result($attachmentRes, $i, 'type');
            }
        }

        return $attachmentsList;
    }
}