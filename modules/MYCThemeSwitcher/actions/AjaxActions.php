<?php

class MYCThemeSwitcher_AjaxActions_Action extends Vtiger_SaveAjax_Action {
    
	function __construct() {
		parent::__construct();
		$this->exposeMethod('activateMYCSubscriptionKey');
		$this->exposeMethod('deActivateMYCSubscriptionKey');
		$this->exposeMethod('setDefaultLayout');
		$this->exposeMethod('setLayoutForCurrentUser');
		$this->exposeMethod('setLayoutForAllUsers');
		$this->exposeMethod('blockLayoutGlobally');
		$this->exposeMethod('removeGlobalLayoutBlock');
		
		$this->exposeMethod('setStyleForCurrentUser');
		$this->exposeMethod('setStyleForAllUsers');
		$this->exposeMethod('getStyleForCurrentUser');
		
		$this->exposeMethod('getCSSForCurrentUser');
		$this->exposeMethod('saveStylePreset');
		$this->exposeMethod('deleteStylePreset');
		$this->exposeMethod('getStylePresets');
	}
		
    public function process(Vtiger_Request $request) {
        $mode = $request->get('mode');
        $this->invokeExposedMethod($mode, $request);
    }
    
    public function getCSSForCurrentUser(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();       
        $currentuserid=$_SESSION['authenticated_user_id'];
        
        $style = $db->pquery("SELECT * FROM vtiger_mycthemeswitcher_userstyles WHERE userid=?;",array($currentuserid));
        $rowCount =  $db->num_rows($style);
		if(isset($rowCount) && $rowCount!="" && $rowCount>0){
        	$styleData = $db->query_result_rowdata($style, 0);
        }
        else $styleData = array("styleid"=>"style-myc-1");

		require_once("customStyle.php?cs=".urlencode($styleData["styleid"]));
		die();
    }
    
    public function getStyleForCurrentUser(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();       
        $currentuserid=$_SESSION['authenticated_user_id'];
        
        $style = $db->pquery("SELECT * FROM vtiger_mycthemeswitcher_userstyles WHERE userid=?;",array($currentuserid));
        
        
        $rowCount =  $db->num_rows($style);
		if(isset($rowCount) && $rowCount!="" && $rowCount>0){
        	$styleData = $db->query_result_rowdata($style, 0);
        }
        else $styleData = array("styleid"=>"style-myc-1");
        
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
		
		$response = new Vtiger_Response();
		$responsemessage=array();
		$responsemessage['success']=true;
		$responsemessage['style']=$styleData["styleid"];
		$responsemessage['user']=$currentuserid;
		$responsemessage['isAdmin']=$currentUserModel->isAdminUser();
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function setStyleForCurrentUser(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
        $styleid = $request->getRaw('styleid');
        $currentuserid=$_SESSION['authenticated_user_id'];
        
        $db->pquery("CREATE TABLE IF NOT EXISTS vtiger_mycthemeswitcher_userstyles (
		  userid int(11) NOT NULL,
		  styleid varchar(255) NOT NULL,
		  PRIMARY KEY (userid)
		) ENGINE=InnoDB;",array());
        
        $db->pquery("INSERT INTO vtiger_mycthemeswitcher_userstyles (userid,styleid) VALUES (?,?) ON DUPLICATE KEY UPDATE    
styleid=VALUES(styleid);",array($currentuserid,$styleid));

		$response = new Vtiger_Response();
		$responsemessage=array();
		$responsemessage['success']=true;
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    
    public function saveStylePreset(Vtiger_Request $request){
	    $styleId = $request->get('presetKey');
	    $styleParams = $request->get('presetparams');
	    $actualStyles = json_decode(file_get_contents(__DIR__."/../utils/stylePresets.json"),true);
		$styleParams["owner"] = $_SESSION['authenticated_user_id'];
		$actualStyles[$styleId]=$styleParams;
		
		file_put_contents(__DIR__."/../utils/stylePresets.json", json_encode($actualStyles, JSON_PRETTY_PRINT));

		$response = new Vtiger_Response();
        $response->setResult(array('success'=>true));        
        $response->emit();
    }
    
     public function deleteStylePreset(Vtiger_Request $request){
	    $styleId = $request->get('presetKey');
	    $actualStyles = json_decode(file_get_contents(__DIR__."/../utils/stylePresets.json"),true);
		unset($actualStyles[$styleId]);
		file_put_contents(__DIR__."/../utils/stylePresets.json", json_encode($actualStyles, JSON_PRETTY_PRINT));

		$response = new Vtiger_Response();
        $response->setResult(array('success'=>true));        
        $response->emit();
    }
    
    public function getStylePresets(){
	    $actualStyles = json_decode(file_get_contents(__DIR__."/../utils/stylePresets.json"),true);
	
		$response = new Vtiger_Response();
        $response->setResult($actualStyles);        
        $response->emit();
    }

    
    
    public function setLayoutForCurrentUser(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
        $layoutuid = $request->getRaw('layoutuid');
        $currentuserid=$_SESSION['authenticated_user_id'];
        $db->pquery("INSERT INTO vtiger_mycthemeswitcher_userlayouts (userid,layoutuid) VALUES (?,?) ON DUPLICATE KEY UPDATE    
layoutuid=VALUES(layoutuid);",array($currentuserid,$layoutuid));

		$response = new Vtiger_Response();
		$responsemessage=array();
		$responsemessage['success']=true;
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function setStyleForAllUsers(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
	    
	    $db->pquery("CREATE TABLE IF NOT EXISTS vtiger_mycthemeswitcher_userstyles (
		  userid int(11) NOT NULL,
		  styleid varchar(255) NOT NULL,
		  PRIMARY KEY (userid)
		) ENGINE=InnoDB;",array());
		
        $layoutuid = $request->getRaw('styleid');
        $currentuserid=$_SESSION['authenticated_user_id'];
        
        $usersresult = $db->pquery("SELECT * FROM vtiger_users;");
		$usersCount =  $db->num_rows($usersresult);
		
		$response = new Vtiger_Response();
		$responsemessage=array();
		
		
			
		if(!isset($usersCount) || $usersCount==0) $responsemessage['success']=false;	
		else{
		
			
	        
			$vtusers=array();
			for($c=0;$c<$usersCount;$c++){
				$vtuser = $db->query_result_rowdata($usersresult, $c);
				$vtusers[$vtuser['id']]=$vtuser;
			}	
			
			
			if($vtusers[$currentuserid]["is_admin"]!="on" && $vtusers[$currentuserid]["is_admin"]!="1"){
				$responsemessage['success']=false;
			}
			else{
				foreach($vtusers as $vuid => $vudet){
					$db->pquery("INSERT INTO vtiger_mycthemeswitcher_userstyles (userid,styleid) VALUES (?,?) ON DUPLICATE KEY UPDATE    
styleid=VALUES(styleid);",array($vuid,$layoutuid));
				}
				$responsemessage['success']=true;
				
			}
		}
		
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function blockLayoutGlobally(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
        $layoutuid = $request->getRaw('layoutuid');
        $currentuserid="-1";
        $db->pquery("INSERT INTO vtiger_mycthemeswitcher_userlayouts (userid,layoutuid) VALUES (?,?) ON DUPLICATE KEY UPDATE    
layoutuid=VALUES(layoutuid);",array($currentuserid,$layoutuid));

		$response = new Vtiger_Response();
		$responsemessage=array();
		$responsemessage['success']=true;
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function removeGlobalLayoutBlock(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
        $currentuserid="-1";
        $db->pquery("DELETE FROM vtiger_mycthemeswitcher_userlayouts  WHERE userid=-1;",array());

		$response = new Vtiger_Response();
		$responsemessage=array();
		$responsemessage['success']=true;
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function setLayoutForAllUsers(Vtiger_Request $request){
	    $db = PearDatabase::getInstance();                    
        $layoutuid = $request->getRaw('layoutuid');
        $currentuserid=$_SESSION['authenticated_user_id'];
        
        $usersresult = $db->pquery("SELECT * FROM vtiger_users;");
		$usersCount =  $db->num_rows($usersresult);
		
		$response = new Vtiger_Response();
		$responsemessage=array();
		
		
			
		if(!isset($usersCount) || $usersCount==0) $responsemessage['success']=false;	
		else{
		
			
	        
			$vtusers=array();
			for($c=0;$c<$usersCount;$c++){
				$vtuser = $db->query_result_rowdata($usersresult, $c);
				$vtusers[$vtuser['id']]=$vtuser;
			}	
			
			
			if($vtusers[$currentuserid]["is_admin"]!="on" && $vtusers[$currentuserid]["is_admin"]!="1"){
				$responsemessage['success']=false;
			}
			else{
				foreach($vtusers as $vuid => $vudet){
					$db->pquery("INSERT INTO vtiger_mycthemeswitcher_userlayouts (userid,layoutuid) VALUES (?,?) ON DUPLICATE KEY UPDATE    
layoutuid=VALUES(layoutuid);",array($vuid,$layoutuid));
				}
				$responsemessage['success']=true;
				
			}
		}
		
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function activateMYCSubscriptionKey(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		$mycKey = $request->getRaw('myckey');
		$layoutUid = $request->getRaw('layoutuid');

		$keyResult=array();

		$result = $db->pquery("SELECT * FROM vtiger_mycthemeswitcher_layouts WHERE layoutuid=?;",array($layoutUid));
		$rowCount =  $db->num_rows($result);
		if(isset($rowCount) && $rowCount!="" && $rowCount>0){
			$layoutInfo = $db->query_result_rowdata($result, 0);
			$keyResult= $this->getKeyFile($mycKey,$layoutUid,$layoutInfo['layoutinfo']);
		}
		else $keyResult[0]=false;

		$response = new Vtiger_Response();
		//$responsestatus=array("messages"=>json_encode());
		$responsemessage=array();
		//activation success
		$responsemessage['success']=true;

		$db->pquery("DELETE FROM vtiger_mycthemeswitcher_licensekeys WHERE layoutuid=?;",array($layoutUid));
		$db->pquery("INSERT INTO vtiger_mycthemeswitcher_licensekeys (layoutuid,activationkey,productkey) VALUES (?,?,?) ON DUPLICATE KEY UPDATE    
activationkey=VALUES(activationkey), productkey=VALUES(productkey);",array($layoutUid,$keyResult[2],$mycKey));


		$responsemessage['messages']="Ok";
		$response->setResult($responsemessage);
		$response->emit();
    }
    
     public function deActivateMYCSubscriptionKey(Vtiger_Request $request) {
        $db = PearDatabase::getInstance();                    
        $mycKey = $request->getRaw('myckey');
        $layoutUid = $request->getRaw('layoutuid');
        
        $keyResult=array();
        
        $result = $db->pquery("SELECT * FROM vtiger_mycthemeswitcher_layouts WHERE layoutuid=?;",array($layoutUid));
		$rowCount =  $db->num_rows($result);
		if(isset($rowCount) && $rowCount!="" && $rowCount>0){
			$layoutInfo = $db->query_result_rowdata($result, 0);      
			$keyResult= $this->getKeyFile($mycKey,$layoutUid,$layoutInfo['layoutinfo'],"deactivation");
		}
		else $keyResult[0]=false;
		
		$response = new Vtiger_Response();
		//$responsestatus=array("messages"=>json_encode());
		$responsemessage=array();
		if($keyResult[0]){
		   //activation success
		   $responsemessage['success']=true;
           
           $db->pquery("DELETE FROM vtiger_mycthemeswitcher_licensekeys WHERE layoutuid=?;",array($layoutUid)); 
		}
		else $responsemessage['success']=false;
        $responsemessage['messages']=$keyResult[1];
        $response->setResult($responsemessage);        
        $response->emit();
    }
    
    public function getKeyFile($licensekey,$layoutUid,$layoutInfo,$keyAction="activation"){
			return array(true,"OK","rK4T85mfVd1C+7Dd18Riy+7VMoS3eu/ffsjRifIn/AmPTKk9ejWu9za1hG9PEgxsEl/DU6Sf3hZPunlzuz8ptdaVfqbf48aA6qHKT5dEr2SBzqCWthfczmAfKPaVc1lZWA6JW9nvjA81WKiD18F8xQZckbK0dYeFkFSp84ie+3x5SQa8eEGXCNMcDA+iQO5lfGCNRUZMKcqUXPnWtVSFRqEip8uqsOLGfBHEs4oUUfCp1bRwY2V8QA/MP5CGAPkaYe5VTPHjMAIhQSBgnbtf+j5GMBz2UEu+1AmxnwgHb6ZHCCCdmwk0ZyE6ck/zSSnlSoUjzdoE4WBC5ehJHt7j3BjagFLodQ5DJt81XCwUOusUnLQ5oN3O1G9lKA3ohH/mMTMvIxk3142/tQ5rtzxvyL5mbIB+QWasNgNEcnfxW4pdQ0advOK5m1FbpKCNIr470OW1wOaijeWW+MtjfDE08IFwomSj7hOWk6/pt1FPcH7WQmwQUKTRJOMSV3obJNubnz/bWlHbHpoO9/gaYVdpfpYupci/3wiTMpMxN3uYWkWRzfy/akkWE18jbzMgn8mGWIQE7rg13a5vY9TARmvlO3kwMF0/MfXAPkk/Yepf/upOXcSSUV3zFHjSTp+6+HDbc6ZBhop3Gm+MhjL0PD41xIqK/n1y9NDLLXuqt7rKSyo=");

		}
    
}
?>

