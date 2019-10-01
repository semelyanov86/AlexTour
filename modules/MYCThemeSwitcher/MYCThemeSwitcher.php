<?php
class MYCThemeSwitcher {
	
	var $layoutinfo;
	
	public function __construct(){
		
		$this->layoutinfo=array(
            	"name"=>"rainbow",
            	"label"=>"Rainbow",
            	"version"=>"1.0.0",
            	"author"=>"MakeYourCloud", 
            	"zipname"=>"MYC_RAINBOW_SRC.zip",
            	"mycpid"=>83541,
        );
        
	}
	
	function vtlib_handler($module_name, $event_type)
	{
	
		$module = Vtiger_Module::getInstance($module_name);
	
		if($event_type == 'module.postinstall')
		{
			$this->setupUpdateDb();
            //$this->backupOriginalFiles();
            $this->extractTemplateZip();   
			
		}
		else if($event_type == 'module.disabled')
		{
			$this->restoreOriginalFiles();
		}
		else if($event_type == 'module.enabled')
		{
			$this->setupUpdateDb();
			//$this->backupOriginalFiles();
            $this->extractTemplateZip();
		}
		else if($event_type == 'module.preuninstall')
		{
			$this->restoreOriginalFiles();
		}
		else if($event_type == 'module.preupdate')
		{
			// TODO Handle actions before this module is updated.
		}
		else if($event_type == 'module.postupdate')
		{
			
			$this->setupUpdateDb();
            $this->extractTemplateZip(); 
           
		}
	}
	
	
	public function setupUpdateDb(){
			global $adb;
			
			$adb->pquery("CREATE TABLE IF NOT EXISTS vtiger_mycthemeswitcher_userlayouts (
                    userid INT(10) NOT NULL,
                    layoutuid TEXT NOT NULL,
                    PRIMARY KEY (userid)
                  ) ENGINE=InnoDB;",array());
            
            $adb->pquery("CREATE TABLE IF NOT EXISTS vtiger_mycthemeswitcher_licensekeys (                   
                    layoutuid TEXT NOT NULL,
                    activationkey TEXT NOT NULL,
                    productkey TEXT NOT NULL
                  ) ENGINE=InnoDB;",array());
                  
            $adb->pquery("CREATE TABLE IF NOT EXISTS vtiger_mycthemeswitcher_layouts (
                    layoutuid TEXT NOT NULL,
                    layoutinfo TEXT NOT NULL
                  ) ENGINE=InnoDB;",array());
             
            if(function_exists('mcrypt_encrypt')) {
             
	            $newLayoutInfo = $this->layoutinfo;           
	           
	            $ekey=preg_replace('#^www\.(.+\.)#i', '$1', $_SERVER['SERVER_NAME']);
	            
				$ec1=base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/"."MYC"), base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/".$ekey."MYC"), base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC")), $newLayoutInfo['name'], MCRYPT_MODE_CBC, md5(md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC"))))), MCRYPT_MODE_CBC, md5(md5("/^\\d*/".$ekey."MYC")))), MCRYPT_MODE_CBC, md5(md5("/^\\d*/"."MYC"))));
				
				
				$ec2=base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/"."MYC"), base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5("/^\\d*/".$ekey.$ec1."MYC"), base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC")), json_encode($newLayoutInfo), MCRYPT_MODE_CBC, md5(md5(md5("/^\\d*/M".$ekey."YC/^\\d*/".$ekey."MYC"))))), MCRYPT_MODE_CBC, md5(md5("/^\\d*/".$ekey.$ec1."MYC")))), MCRYPT_MODE_CBC, md5(md5("/^\\d*/"."MYC"))));
										
				$adb->pquery("DELETE FROM vtiger_mycthemeswitcher_layouts WHERE layoutuid = ?;",array($ec1)); 
				$adb->pquery("INSERT INTO vtiger_mycthemeswitcher_layouts (layoutuid,layoutinfo) VALUES (?,?);",array($ec1,$ec2)); 

			}
			
	}      
    
       
    public function backupOriginalFiles() {
		   global $log;
	       $log->debug('Starting backup of current files to replace...');

		   copy('includes/runtime/Viewer.php', 'modules/MYCThemeSwitcher/files_bk/Viewer_BK.php');
		   //copy('includes/runtime/Controller.php', 'modules/MYCThemeSwitcher/files_bk/Controller_BK.php');
		   copy('includes/runtime/JavaScript.php', 'modules/MYCThemeSwitcher/files_bk/JavaScript_BK.php');
		   //copy('modules/CustomView/views/EditAjax.php', 'modules/MYCThemeSwitcher/files_bk/EditAjax_BK.php');
		   copy('includes/runtime/Theme.php','modules/MYCThemeSwitcher/files_bk/Theme_BK.php');
		   	
		   $log->debug('File backup completed!');
	}
       
    public function restoreOriginalFiles() {
       	   global $log;
       	   
       	   $log->debug('Restoring files from backup...');
       	   
	       copy('modules/MYCThemeSwitcher/vtiger_src/Viewer.php','includes/runtime/Viewer.php');
	       //copy('modules/MYCThemeSwitcher/vtiger_src/Controller.php','includes/runtime/Controller.php');
		   copy('modules/MYCThemeSwitcher/vtiger_src/JavaScript.php','includes/runtime/JavaScript.php');
		   //copy('modules/MYCThemeSwitcher/vtiger_src/EditAjax.php','modules/CustomView/views/EditAjax.php');
		   copy('modules/MYCThemeSwitcher/vtiger_src/Theme.php','includes/runtime/Theme.php');
		   
		   $log->debug('File restore completed!');
   }
       
   public function extractTemplateZip() {

		   	 
	       	 global $root_directory, $log;
	       	 
	       	 
	       	 
             $zip = new ZipArchive();
             $fileName = 'modules/MYCThemeSwitcher/theme_src/'.$this->layoutinfo['zipname'];
			 
             	
             if ($zip->open($fileName)) {
             
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                           $log->debug('Filename: ' . $zip->getNameIndex($i) . '<br />');
                    }
                    
                    if ($zip->extractTo($root_directory)){
	                    $zip->close();
	                    $log->debug('THEME ZIP EXTRACTED TO: '.$root_directory.'!<br />');
                    } 

                    else $log->fatal('ERROR EXTRACTING MIGRATION ZIP FILE!<br />');
                    
             }
             
             copy('modules/MYCThemeSwitcher/theme_src/myc_patch/Viewer.php','includes/runtime/Viewer.php');
             copy('modules/MYCThemeSwitcher/theme_src/myc_patch/Theme.php','includes/runtime/Theme.php');
			 //copy('modules/MYCThemeSwitcher/theme_src/myc_patch/Controller.php','includes/runtime/Controller.php');
			 copy('modules/MYCThemeSwitcher/theme_src/myc_patch/JavaScript.php','includes/runtime/JavaScript.php');
			 //copy('modules/MYCThemeSwitcher/theme_src/myc_patch/EditAjax.php','modules/CustomView/views/EditAjax.php');
			 //if(file_exists('modules/MYCThemeSwitcher/theme_src/myc_patch/Grid.php'))
			 	//copy('modules/MYCThemeSwitcher/theme_src/myc_patch/Grid.php','modules/Vtiger/views/Grid.php');
             
             $log->debug('Zip Extraction Completed, theme installed!');
    }
       


	
}
?>

