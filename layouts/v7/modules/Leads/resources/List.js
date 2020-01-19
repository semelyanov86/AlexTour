/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_List_Js("Leads_List_Js", {

    changeLeadStatus : function(status, recordId) {
        var params = {};
        params['module'] = 'Leads';
        params['action'] = 'ActionAjax';
        params['mode'] = 'changeStatus';
        params['record'] = recordId;
        params['status'] = status;
        app.helper.showProgress();
        app.request.post({data:params}).then(
            function(err,data) {
                if(err == null){
                    app.helper.showSuccessNotification({'message': data.message});
                } else {
                    console.error(err);
                }
                app.helper.hideProgress();
            },
            function(error) {
                console.error(error);
                app.helper.hideProgress();
            }
        );
    }

}, {});