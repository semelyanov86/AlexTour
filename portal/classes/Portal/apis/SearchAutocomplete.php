<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.2
 * ("License.txt"); You may not use this file except in compliance with the License
 * The Original Code is: Vtiger CRM Open Source
 * The Initial Developer of the Original Code is Vtiger.
 * Portions created by Vtiger are Copyright (C) Vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

class Portal_SearchAutocomplete_API extends Portal_Default_API {

    public function process(Portal_Request $request) {
        $searchKey = $request->get('key');
        $parent = $request->get('parent');
        $child = $request->get('child');
        $response = new Portal_Response();
        if (!$parent || !$child || !$searchKey) {
            $response->setError(422, 'Invalid values for searching');
            return $response;
        }

        $result = Vtiger_Connector::getInstance()->searchAutocomplete($parent, $child, $searchKey);

        $response->setResult($result);
        return $response;
    }
  }
