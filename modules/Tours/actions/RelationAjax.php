<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Tours_RelationAjax_Action extends Vtiger_RelationAjax_Action {

    public $supportedOrderModules = array('Flights');

    public function __construct() {
        parent::__construct();
        $this->exposeMethod('updateOrder');
    }


    /**
     * Function to update Relation status
     * @param Vtiger_Request $request
     */
    public function updateOrder(Vtiger_Request $request)
    {
        $relatedModuleName = $request->get('relatedModule');
        $relatedRecords = $request->get('relatedRecords');
        $sourceModuleModel = Vtiger_Module_Model::getInstance($request->getModule());
        $relatedModuleModel = Vtiger_Module_Model::getInstance($relatedModuleName);

        $relationModel = Vtiger_Relation_Model::getInstance($sourceModuleModel, $relatedModuleModel);
        $response = new Vtiger_Response();
        if (count($relatedRecords)) {
            foreach ($relatedRecords as $order=>$relatedRecordId) {
                if ($relatedRecordId && $order >= 0) {
                    $relationModel->updateOrder($request->get('sourceRecord'), array($relatedRecordId => $order));
                }
            }
            $response->setResult(array(true));
        } else {
            $response->setError('500', 'Error in updating status');
        }
        $response->emit();
    }
}