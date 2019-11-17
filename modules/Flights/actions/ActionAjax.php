<?php

require_once 'modules/Flights/vendor/autoload.php';

class Flights_ActionAjax_Action extends Vtiger_Action_Controller
{

    const API_KEY = 'cVXi6EZouBXBA8HFMlFLRMAc7snC7ZXr';
    const API_SECRET = 'T7EoSNAYpSXeGH8a';

    public function checkPermission(Vtiger_Request $request)
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->exposeMethod("getFromAmadeus");
    }
    public function process(Vtiger_Request $request)
    {
        $mode = $request->get("mode");
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }
    public function getFromAmadeus(Vtiger_Request $request)
    {
        $amadeus_api = new AmadeusDahabtours\SelfServiceApiClient(self::API_KEY,self::API_SECRET);

        $result = 'SUCCESS';
        $response = new Vtiger_Response();
        $response->setEmitType(Vtiger_Response::$EMIT_JSON);
        $response->setResult(array("result" => $result));
        $response->emit();
    }
}

?>