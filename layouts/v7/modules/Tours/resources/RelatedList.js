/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
var is_tours_not_runned  = true;

Vtiger_RelatedList_Js("Tours_RelatedList_Js",{

},{
    registerSortableRelatedEvent : function() {
        var urlArr = app.convertUrlToDataParams(window.location.href);
        var notBusy = true;
        if (urlArr.relatedModule == 'Flights') {
            var vdactions = jQuery('.related-list-actions');
            var drags = jQuery('.vddraggable');
            if (drags.length < 1) {
                vdactions.append('<span class="pull-left vddraggable"><img class="cursorDrag alignMiddle" src="layouts/v7/skins/images/drag.png"></span>');
                sortable('#listview-table > tbody', {
                    items: "tr.listViewEntries",
                    placeholder: "<tr><td colspan=\"3\"><span class=\"center\">" + app.vtranslate('JS_MOVE_CHANGE_ORDER') + "</span></td></tr>",
                    forcePlaceholderSize: false
                });
                if (sortable('#listview-table > tbody')[0]) {
                    sortable('#listview-table > tbody')[0].addEventListener('sortupdate', function(e) {
                        var curItem = e.detail;
                        if (notBusy) {
                            notBusy = false;
                            app.helper.showProgress();
                            var items = curItem.destination.items.map(function (element) {
                                return element.dataset.id;
                            });
                            var params = {
                                'relatedModule' : urlArr.relatedModule,
                                'relatedRecords' : items,
                                'order' : curItem.destination.index,
                                'module' : app.getModuleName(),
                                'action' : 'RelationAjax',
                                'sourceRecord' : app.getRecordId(),
                                'mode' : 'updateOrder'
                            };
                            app.request.post({"data" : params}).then(function(error, responseData) {
                                    if(responseData) {
                                        app.helper.hideProgress();
                                        notBusy = true;
                                    }
                                },
                                function(textStatus, errorThrown) {}
                            );
                        }
                    });
                }
                is_tours_not_runned = false;
            }
        }

    },

    registerEvents : function() {
        this.registerSortableRelatedEvent();

    },

    init : function(parentId, parentModule, selectedRelatedTabElement, relatedModuleName) {
        this._super(parentId, parentModule, selectedRelatedTabElement, relatedModuleName);
        this.registerEvents();
    }
});