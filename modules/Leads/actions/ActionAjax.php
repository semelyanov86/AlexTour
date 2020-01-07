<?php

class Leads_ActionAjax_Action extends Vtiger_Action_Controller
{
    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("changeStatus");
    }
    public function process(Vtiger_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
    public function changeStatus(Vtiger_Request $request)
    {
        $recordId = $request->get("record");
        $moduleName = $request->getModule();
        $status = $request->get('status');
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
        $recordModel->set('mode', 'edit');
        $recordModel->set('leadstatus', $status);
        $recordModel->save();
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array("message" => 'Status Changed Successfully'));
        $response->emit();
    }

}

?>