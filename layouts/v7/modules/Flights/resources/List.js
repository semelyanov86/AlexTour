/* ********************************************************************************
 * The content of this file is subject to the VTEPayments("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
Vtiger.Class("Flights_List_Js",{
    getFromAmadeus : function () {
        var params = {
            module: 'Flights',
            action: 'ActionAjax',
            mode: 'getFromAmadeus',
        };
        app.helper.showProgress();
        app.request.post({data: params}).then(function (err, data) {
            if(err==null) {
                console.log(data);
                // window.location.reload();
            } else {
                app.helper.hideProgress();
                app.helper.showErrorNotification({'message': err});
            }
        });
    }
},{
// Vtiger_List_Js("VTEPayments_List_Js", {}, {

    /**
     * Function to register events
     */
    registerEvents : function(){
        this._super();
    },


});

jQuery(document).ready(function(){
    var instance = new Flights_List_Js();
    instance.registerEvents();

    // Fix issue on list
    var Vtiger_List_Js_obj = new Vtiger_List_Js();
    Vtiger_List_Js_obj.intializeComponents();
    Vtiger_List_Js_obj.registerEvents();


});