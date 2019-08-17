<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vordoom.net
 * The Initial Developer of the Original Code is vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/

class VDSimplyKPI_Module_Model extends Vtiger_Module_Model {
        public $module_list;
       
        public function getSupportedModules() {
            $this->initListOfModules();
           
        foreach($this->module_list as $key=>$value) {
            if(isPermitted($key,'index') == "yes") {
                $modules [$key] = vtranslate($key, $key);
            }
        }
        asort($modules);
		return $modules;
	}
        public function initListOfModules() {
		global $adb, $current_user, $old_related_modules;

		$restricted_modules = array('Webmails');
		$restricted_blocks = array('LBL_COMMENTS','LBL_COMMENT_INFORMATION');

		$this->module_id = array();
		$this->module_list = array();

		// Prefetch module info to check active or not and also get list of tabs
		$modulerows = vtlib_prefetchModuleActiveInfo(false);

		$cachedInfo = VTCacheUtils::lookupReport_ListofModuleInfos();

		if($cachedInfo !== false) {
			$this->module_list = $cachedInfo['module_list'];
			

		} else {

			if($modulerows) {
				foreach($modulerows as $resultrow) {
					if($resultrow['presence'] == '1') continue;      // skip disabled modules
					if($resultrow['isentitytype'] != '1') continue;  // skip extension modules
					if(in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
						continue;
					}
					if($resultrow['name']!='Calendar'){
						$this->module_id[$resultrow['tabid']] = $resultrow['name'];
					} else {
						$this->module_id[9] = $resultrow['name'];
						$this->module_id[16] = $resultrow['name'];

					}
					$this->module_list[$resultrow['name']] = array();
				}

				$moduleids = array_keys($this->module_id);
				$reportblocks =
					$adb->pquery("SELECT blockid, blocklabel, tabid FROM vtiger_blocks WHERE tabid IN (" .generateQuestionMarks($moduleids) .")",
						array($moduleids));
				$prev_block_label = '';
				if($adb->num_rows($reportblocks)) {
					while($resultrow = $adb->fetch_array($reportblocks)) {
						$blockid = $resultrow['blockid'];
						$blocklabel = $resultrow['blocklabel'];
						$module = $this->module_id[$resultrow['tabid']];

						if(in_array($blocklabel, $restricted_blocks) ||
							in_array($blockid, $this->module_list[$module]) ||
							isset($this->module_list[$module][getTranslatedString($blocklabel,$module)])
						) {
							continue;
						}

						if(!empty($blocklabel)){
							if($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION')
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							else
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							$prev_block_label = $blocklabel;
						} else {
							$this->module_list[$module][$blockid] = getTranslatedString($prev_block_label,$module);
						}
					}
				}

				
				
				// Put the information in cache for re-use
				VTCacheUtils::updateReport_ListofModuleInfos($this->module_list, $this->related_modules);
			}
		}
	}
	public function isCustomizable() {
            return false;
        }
	
        function serializeKPI($kpi) {
            global $adb;
            $users = array();
            $filters = array();
            $typeResult = array();
            $userToKpi = array();
            $id = array();
            foreach ($kpi as $key=>$row){
                $users[] = $row['smownerid'];
                $id[$row['number_kpi']] = $row['simplykpiid'];
                if (!empty($row['groupid'])){
                    $result = $adb->pquery('Select userid FROM vtiger_users2group where groupid = ?)', array($row['groupid']));
                    $numRows = $adb->num_rows($result);
                    $addUser = array();
                    for($i=0;$i<$numRows;$i++){
                        $addUser[] = $adb->query_result($result,$i,'userid');
                        $users[] = $adb->query_result($result,$i,'userid');
                        $userToKpi[$adb->query_result($result,$i,'userid')][] = $row['number_kpi'];
                    }
                    $kpi[$key]['adduser'] = implode(',',$addUser);
                }
                
                $userToKpi[$row['smownerid']][] = $row['number_kpi'];
               
                $filters[$row['number_kpi']] = $this->convertFiltres($row['advanced_filter']);
                if ($row['datafields'] == 'count(*)' || empty($row['datafields'])){
                    $typeResult[$row['number_kpi']] = 1;
                }
                else {
                    $typeResult[$row['number_kpi']] = $this->parseDatafields($row['datafields']);
                }
            }
            
            $users = array_unique($users);
           
            return array('KPI'=>$kpi, 'Users'=>$users, 'Users2kpi'=>$userToKpi, 'TypeResult'=>$typeResult, 'Filtre' => $filters, 'Id' => $id);
        }
        public function arrayAdvanceFiltred($str){
            $filter = Zend_Json::decode(htmlspecialchars_decode($str));
            
            return $filter;
        }
        public function parseDatafields($str){
            $array = explode(':',$str);
            return $array[3];
        }
        public function convertFiltres($str){
            $filters = $this->arrayAdvanceFiltred($str);
           // $mandatory = $filters[1]
            return $filters;
        }
        public function saveRecord($recordModel) {
            $target = $recordModel->get('target');
            $result = $recordModel->get('result');
            $procent = round((($result/$target)*100), 0);
            $recordModel->set('procent', $procent);
            parent::saveRecord($recordModel);
        }
}