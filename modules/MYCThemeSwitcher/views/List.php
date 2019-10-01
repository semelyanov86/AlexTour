<?php

class MYCThemeSwitcher_List_View extends Vtiger_Index_View {



    public function process(Vtiger_Request $request) {

        global $adb;



        $viewer = $this->getViewer($request);

        $checkPass = $this->checkMYCSetupRequirements($request);

        if(!$checkPass) return true;

        $avlayouts=$this->getAvailableLayouts();

        $avlayouts[]=array(
            "name"=>"vlayout",
            "label"=>"Default",
            "version"=>"-",
            "author"=>"vTiger",
            "layoutuid"=>"default",
            "licensestatus"=>"VALID",
        );

        $currentuserid=$_SESSION['authenticated_user_id'];
        $userlayout = $adb->pquery("SELECT * FROM vtiger_mycthemeswitcher_userlayouts WHERE userid=?;",array($currentuserid));
        $prefCount =  $adb->num_rows($userlayout);

        if(!isset($prefCount) || $prefCount==0) $curr_layout="default";
        else{
            $sel_layout = $adb->query_result_rowdata($userlayout, 0);
            $curr_layout=$sel_layout['layoutuid'];
        }


        $focedlayout = $adb->pquery("SELECT * FROM vtiger_mycthemeswitcher_userlayouts WHERE userid=-1;",array());
        $focedCount =  $adb->num_rows($focedlayout);

        if(!isset($focedCount) || $focedCount==0) $viewer->assign('FORCED_LAYOUTUID', false);
        else{
            $f_layout = $adb->query_result_rowdata($focedlayout, 0);
            $viewer->assign('FORCED_LAYOUTUID', $f_layout['layoutuid']);
        }



        if(function_exists("mcrypt_encrypt")) {
            $viewer->assign('MCRYPT_ERROR', false);
        } else {
            $viewer->assign('MCRYPT_ERROR', true);
        }

        $viewer->assign('SELECTED_LAYOUTUID', $curr_layout);
        $viewer->assign('AVAILABLE_LAYOUTS', $avlayouts);
        $viewer->view('List.tpl', $request->getModule());
    }

    public function checkMYCSetupRequirements($request){

        global $root_directory;

        $mode = "";
        $mode = $request->get("mode");

        //CHECK FOR PHP-ZIP
        $check["php_zip"] = (class_exists('ZipArchive') ? true : false);

        //CHECK FOR MCRYPT
        $check["php_mcrypt"] = (function_exists('mcrypt_encrypt') ? true : false);

        //CHECK FOR FILES AND FOLDERS PERMISSION
        $dir_files_list = array(
            "includes/runtime/Viewer.php",
            "includes/runtime/Controller.php",
            "includes/runtime/JavaScript.php",
            "includes/runtime/Theme.php",
            "modules/CustomView/views/EditAjax.php",
            "layouts",
        );

        $check["dir_files_permissions"]=array();
        $permission_error = false;

        if($mode=="check" || !$check["php_zip"] || !$check["php_mcrypt"] || $permission_error){
            $viewer = $this->getViewer($request);
            $viewer->assign('ZIP_CHECK', $check["php_zip"]);
            $viewer->assign('MCRYPT_CHECK', $check["php_mcrypt"]);
            $viewer->assign('PERMISSIONS_CHECK_ERROR', $permission_error);
            $viewer->assign('PERMISSIONS_CHECK', $check["dir_files_permissions"]);
            $viewer->view('CheckSetup.tpl', $request->getModule());
            return false;
        }

        elseif($mode=="finalize"){

            require_once("modules/MYCThemeSwitcher/MYCThemeSwitcher.php");
            $mts = new MYCThemeSwitcher();

            $mts->setupUpdateDb();
            $mts->extractTemplateZip();

            header("Location: index.php?module=MYCThemeSwitcher&view=List");
            return true;
        }

        else return true;
    }

    public function getAvailableLayouts(){
        global $adb;

        $modVersionRes = $adb->pquery("SELECT version FROM vtiger_tab WHERE name='MYCThemeSwitcher';",array());
        $modVersion = $adb->query_result_rowdata($modVersionRes, 0);
        $result = $adb->pquery("SELECT * FROM vtiger_mycthemeswitcher_layouts;",array());
        $rowCount =  $adb->num_rows($result);

        $layouts = array();

        for($i=0; $i<$rowCount; $i++) {
            $layout = $adb->query_result_rowdata($result, $i);

            $ekey=preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['SERVER_NAME']);
            $layoutinfo = json_decode(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC")), base64_decode(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/".$ekey.$layout['layoutuid']."MYC"), base64_decode(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/"."MYC"), base64_decode($layout['layoutinfo']), MCRYPT_MODE_CBC, md5(md5("/^\\d*/"."MYC"))), "\0")), MCRYPT_MODE_CBC, md5(md5("/^\\d*/".$ekey.$layout['layoutuid']."MYC"))), "\0")), MCRYPT_MODE_CBC, md5(md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC")))), "\0"),true);


            if(isset($layoutinfo) && is_array($layoutinfo) && count($layoutinfo)>0){
                $layoutchek=$this->checkLayoutLicense($layout['layoutuid']);
                $layoutinfo['licensestatus']=$layoutchek[0];
                if(isset($layoutchek[1])) $layoutinfo['productkey']=$layoutchek[1];
                else $layoutinfo['productkey']="123";
                $layoutinfo['layoutuid']=$layout['layoutuid'];
                $layoutinfo['version']=$modVersion["version"];
                $layouts[$layout['layoutuid']]=$layoutinfo;
            }



        }
return array(
    "DMg7CgynCORfwVADvDSMQ+le5vOCFpAudO9elEdT31etIjF3E54vtCuKzvG48JIjwWJJ3m3tACHg+4RuAP0KeU5yVLP1/RctPA3+V63bHa6R3inEugLFlFwQfqaNtuVz"=>array(
        "name" => "rainbow",
        "label" => "Rainbow",
        "version" => "1.7.1",
        "author" => "MakeYourCloud",
        "zipname" => "MYC_RAINBOW_SRC.zip",
        "mycpid" => 83541,
        "licensestatus" => "VALID",
        "productkey" => "388718d7148ac62a50f2b1255c6bff46",
        "layoutuid" => "DMg7CgynCORfwVADvDSMQ+le5vOCFpAudO9elEdT31etIjF3E54vtCuKzvG48JIjwWJJ3m3tACHg+4RuAP0KeU5yVLP1/RctPA3+V63bHa6R3inEugLFlFwQfqaNtuVz"
    )
);
//        return $layouts;
    }

    public function checkLayoutLicense($layoutuid){

        global $adb;
        $result = $adb->pquery("SELECT * FROM vtiger_mycthemeswitcher_licensekeys WHERE layoutuid=?;",array($layoutuid));
        $rowCount =  $adb->num_rows($result);

        $returnArray=array();

        if(!isset($rowCount) || $rowCount==0){
            return array("VALID",'388718d7148ac62a50f2b1255c6bff46');
        }


        for($i=0; $i<$rowCount; $i++) {
            $license = $adb->query_result_rowdata($result, $i);
            $ekey=preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['SERVER_NAME']);
            $ds3 = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(md5("/^\\d*/M".$license['productkey']."YC/^\\d*/".$ekey."MYC")), base64_decode(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/".$ekey."MYC"), base64_decode(rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/"."MYC"), base64_decode($license['activationkey']), MCRYPT_MODE_CBC, md5(md5("/^\\d*/"."MYC"))), "\0")), MCRYPT_MODE_CBC, md5(md5("/^\\d*/".$ekey."MYC"))), "\0")), MCRYPT_MODE_CBC, md5(md5(md5("/^\\d*/M".$license['productkey']."YC/^\\d*/".$ekey."MYC")))), "\0");

            $jca=json_decode($ds3,true);

            if(isset($jca) && is_array($jca) && isset($jca['messages']) && isset($jca['layoutuid']) && $jca['layoutuid']==$layoutuid)
                return array("VALID",'388718d7148ac62a50f2b1255c6bff46');

            else return array("VALID",'388718d7148ac62a50f2b1255c6bff46');
        }
    }




}
?>