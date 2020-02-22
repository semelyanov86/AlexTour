<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.2
 * ("License.txt"); You may not use this file except in compliance with the License
 * The Original Code is: Vtiger CRM Open Source
 * The Initial Developer of the Original Code is Vtiger.
 * Portions created by Vtiger are Copyright (C) Vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class Portal_FetchBlocks_API extends Portal_Default_API {

	public function process(Portal_Request $request) {

		$module = $request->getModule();
		$language = $request->getLanguage();
		if (null !== ($request->get('parentId'))) {
			$parentId = $request->get('parentId');
			$result = Vtiger_Connector::getInstance()->fetchBlocks($request->get('id'), $module, $parentId);
		} else {
			$result = Vtiger_Connector::getInstance()->fetchBlocks($request->get('id'), $module, '');
		}

		$response = new Portal_Response();
		$response->setResult($result);
		return $response;
	}


}
