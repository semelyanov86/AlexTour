/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Popup_Js("Potentials_Popup_Js",{},{
    selectedRecordItem : new Array(),

    getModuleName : function() {
        return jQuery('#module').val();
    },

    getElementId : function() {
        return jQuery('#element_id').val();
    },

    getStoreInput : function() {
        var nameFromField = jQuery('#element_id').val();
        return jQuery('#' + nameFromField);
    },
    /**
     * Function to get complete params
     */
    getCompleteParams : function(){
        var params = {};
        params['view'] = this.getView();
        params['src_module'] = this.getSourceModule();
        params['src_record'] = this.getSourceRecord();
        params['src_field'] = this.getSourceField();
        params['search_key'] =  this.getSearchKey();
        params['search_value'] =  this.getSearchValue();
        params['orderby'] =  this.getOrderBy();
        params['sortorder'] =  this.getSortOrder();
        params['page'] = this.getPageNumber();
        params['related_parent_module'] = this.getRelatedParentModule();
        params['related_parent_id'] = this.getRelatedParentRecord();
        params['module'] = this.getModuleName();

        params.search_params = JSON.stringify(this.getPopupListSearchParams());
        if(this.isMultiSelectMode()) {
            params['multi_select'] = true;
        }
        params['relationId'] = this.getRelationId();
        return params;
    },
    getPopupListSearchParams : function(){
        var listViewPageDiv = jQuery('div.iTL-popupEntriesDiv');
        var listViewTable = listViewPageDiv.find('.listViewEntriesTable');
        var searchParams = new Array();
        var currentSearchParams = new Array();
        if(jQuery('#currentSearchParams').val())
            currentSearchParams = JSON.parse(jQuery('#currentSearchParams').val());
        listViewTable.find('.listSearchContributor').each(function(index,domElement){
            var searchInfo = new Array();
            var searchContributorElement = jQuery(domElement);
            var fieldName = searchContributorElement.attr('name');
            var fieldInfo = searchContributorElement.data('fieldinfo');
            if(fieldName in currentSearchParams) {
                delete currentSearchParams[fieldName];
            }

            var searchValue = searchContributorElement.val();

            if(typeof searchValue == "object") {
                if(searchValue == null) {
                    searchValue = "";
                }else{
                    searchValue = searchValue.join(',');
                }
            }
            searchValue = searchValue.trim();
            if(searchValue.length <=0 ) {
                //continue
                return true;
            }
            var searchOperator = 'c';
            if(fieldInfo.type == "date" || fieldInfo.type == "datetime") {
                searchOperator = 'bw';
            }else if (fieldInfo.type == 'percentage' || fieldInfo.type == "double" || fieldInfo.type == "integer"
                || fieldInfo.type == 'currency' || fieldInfo.type == "number" || fieldInfo.type == "boolean" ||
                fieldInfo.type == "picklist") {
                searchOperator = 'e';
            }
            searchInfo.push(fieldName);
            searchInfo.push(searchOperator);
            searchInfo.push(searchValue);
            searchParams.push(searchInfo);
        });
        for(var i in currentSearchParams) {
            var fieldName = currentSearchParams[i]['fieldName'];
            var searchValue = currentSearchParams[i]['searchValue'];
            var searchOperator = currentSearchParams[i]['comparator'];
            if(fieldName== null || fieldName.length <=0 ){
                continue;
            }
            var searchInfo = new Array();
            searchInfo.push(fieldName);
            searchInfo.push(searchOperator);
            searchInfo.push(searchValue);
            searchParams.push(searchInfo);
        }
        return new Array(searchParams);
    },

    updatePopUpContenTableHeight:function(){
        var  viewPortHeight= $(window).height()-120;
        var params = {setHeight: (viewPortHeight)+'px'};
        var params2 = {setHeight: (viewPortHeight-125)+'px'};
        var params2_1 = {setHeight: (viewPortHeight-100)+'px'};
        app.helper.showVerticalScroll(jQuery('#itemLookUpPopupModal').find('.modal-body'), params);
        app.helper.showVerticalScroll(jQuery('#itemLookUpPopupModal').find('.lockup-item-main'), params2_1);
        app.helper.showVerticalScroll(jQuery('#itemLookUpPopupModal').find('.popupFillContainer_filter_fields_scroll'), params2);
        var container = jQuery('.iTL-listViewEntriesTable');
        var thead_h = container.find('thead').height();
        var params3 = {setHeight: (viewPortHeight-125-thead_h)+'px'};
        app.helper.showVerticalScroll(container.find('tbody'), params3);
    },

    getPopupContainer:function(){
        var PopupContainer =  jQuery('#ContactsPopupContainer');
        return PopupContainer;
    },

    registerEventForItemModuleCheckBoxSelect:function(){
        var self = this;
        var popupPageContentsContainer = self.getPopupContainer();

    },

    pushSelectedRecordToStack:function(recordId){
        this.selectedRecordItem.push(recordId);
    },


    addItem:function(e,tr,module){
        var self = this;
        e.preventDefault();
        var preEvent = jQuery.Event('pre.popupSelect.click');
        app.event.trigger(preEvent);
        if(preEvent.isDefaultPrevented()){
            return;
        }
        var thisInstance = this;
        var recordId = tr.data('id');
        self.pushSelectedRecordToStack(recordId);
        this.updateItemsInStore();
    },

    removeItem:function(recordID){
        var self = this;
        var selectedRecords = this.selectedRecordItem;
        selectedRecords.forEach(function(item,key){
            if(item == recordID){
                self.selectedRecordItem.splice(key, 1);
            }
        });
        this.updateItemsInStore();
    },

    updateItemsInStore : function() {
        var input = this.getStoreInput();
        var txt = JSON.stringify(this.selectedRecordItem);
        input.val(txt);
    },

    registerEventForAddAnItem:function(){
        var self = this;
        var popupPageContentsContainer = self.getPopupPageContainer();
        popupPageContentsContainer.off('click', '.vdLookUpAddAnItem');
        popupPageContentsContainer.on('click','.vdLookUpAddAnItem',function(e){
            var element = jQuery(e.currentTarget);
            var tr = $(this).closest('tr');
            var td = $(this).closest('td');
            var checkbox = tr.find('input[type="checkbox"]');
            var module = $(this).data('item-module');
            var value = $(this).data('item-value');
            var actionInput = td.find('input.action');
            var action = actionInput.val();
            if(action == 'add'){
                actionInput.val('remove');
                tr.addClass('added');
                tr.css('background-color','#d3ffd5');
                checkbox[0].checked = false;
                self.addItem(e,tr,module);
                app.helper.showSuccessNotification({message:value+' has been added'});
                $(this).html('Remove');
                tr.find('input.entryCheckBox[type="checkbox"]').attr('disabled','disabled');
            }else{
                actionInput.val('add');
                tr.removeClass('added');
                tr.css('background-color','#fff');
                var recordID = tr.data('id');
                self.removeItem(recordID);
                var section_value = tr.data('section-field-value');
                if(!self.check_is_exists_items_in_section(section_value)){
                    self.remove_section(section_value);
                }
                app.helper.showSuccessNotification({message:value+' has been removed'});
                $(this).html('Add');
                tr.find('input.entryCheckBox[type="checkbox"]').removeAttr('disabled');
            }
        });
    },
    check_is_exists_items_in_section:function(section_value){
        var items = $('tr.lineItemRow[data-of-section="'+section_value+'"]');
        if(items.length > 0){
            return true;
        }
        return false;
    },
    remove_section:function(section_value){
        $('tr.section[data-of-section="'+section_value+'"]').find('.deleteSection').trigger('click');
        $('tr.running_item[data-sub-total-of-section="'+section_value+'"]').find('.delete_running_item').trigger('click');
    },
    registerEventForAddAllRecord:function(){
        var self = this;
        var thisInstance = this;
        var popupPageContentsContainer = self.getPopupPageContainer();
        popupPageContentsContainer.off('click', '#item_lookup_select');
        popupPageContentsContainer.on('click','#item_lookup_select',function(e){
            var all_filter_record_id = $('#all_filter_record_id').val();
            var arr = all_filter_record_id.split(',');
            arr.forEach(function(id){
                var recordId = id;
                self.pushSelectedRecordToStack(recordId);
                self.highlightSelectedRecord();
                self.updateItemsInStore();
            });
        });
    },

    registerPostPopupLoadEvents : function(){
        //var popupContainer = jQuery('#popupModal');
        //var Options= {
        //    axis:"x",
        //    scrollInertia: 200,
        //    mouseWheel:{ enable: false }
        //};
        //app.helper.showVerticalScroll(popupContainer.find('.popupEntriesDiv'), Options);
    },
    highlightSelectedRecord:function(){
        var self = this;
        var selected = this.selectedRecordItem;
        selected.forEach(function(item){
            var tr = $('tr.itemLookUp-listViewEntries[data-id="'+item+'"]');
            var actionInput = tr.find('input.action');
            var addButton = tr.find('button.vdLookUpAddAnItem');
            tr.css('background-color','#d3ffd5');
            actionInput.val('remove');
            addButton.html('Remove');
            tr.find('input.entryCheckBox[type="checkbox"]').attr('disabled','disabled');
        });
    },
    fillSelectedStack : function() {
        if (this.selectedRecordItem.length < 1) {
            var fieldValue = this.getStoreInput().value;
            if (fieldValue) {
                this.selectedRecordItem = JSON.parse(document.getElementById('ports_list').value);
            }
        }
    },

    registerEventScrollModal:function(){
        //var self = this;
        //$('#itemLookUpPopupModal div.modal-body').on('scroll',function(){
        //    var content = $('div.popupEntriesDiv');
        //    var navigation = $('div.lookup-item-popup-navigation');
        //    var popupFillContainer_filter_fields_scroll = $('div.popupFillContainer_filter_fields_scroll').closest('div.col-md-12');
        //    var table = content.find('table.listViewEntriesTable');
        //    var thead = table.find('thead');
        //    var search = table.find('tbody tr.searchRow');
        //    var scroll = $(this).scrollTop();
        //    var top =scroll -34;
        //    var positon = top - 1;
        //    var zIndex = 9999;
        //    if(scroll > 147){
        //        thead.css({"position": "absolute","top": (positon + 3)+'px',"background-color": '#fff',"z-index":zIndex});
        //        search.css({"position": "absolute","top": (positon + 43)+'px',"background-color": '#fff',"z-index":zIndex});
        //        navigation.css({"position": "relative","top": (positon + 19)+'px',"background-color": '#fff',"z-index":zIndex + 1});
        //    }else{
        //        thead.attr('style','');
        //        search.attr('style','');
        //        navigation.attr('style','');
        //    }
        //    if(scroll > 97){
        //        popupFillContainer_filter_fields_scroll.css({"position": "relative","top": positon+'px',"background-color": '#fff',"z-index":zIndex});
        //    }else{
        //        popupFillContainer_filter_fields_scroll.attr('style','');
        //    }
        //});
    },
    registerEventFixWithColumn:function(){

    },
    updateAddedItem:function(recordID,type,value,itemName){
        var self = this;
        console.log('runned updateAddedItem function: ' + recordID + ' ' + type);
    },

    /**
     * Function to register event for popup list Search
     */
    registerEventForPopupListSearch : function(){
        var thisInstance = this;
        var popupPageContainer = this.getPopupPageContainer();
        popupPageContainer.on('click','[data-trigger="PopupListSearch"]',function(e){
            jQuery('#searchvalue').val("");
            jQuery('#totalPageCount').text("");
            thisInstance.searchHandler().then(function(data){
                jQuery('#pageNumber').val(1);
                jQuery('#pageToJump').val(1);
                thisInstance.updatePagination();
            });
        }).on('keypress',function(e){
            var code = e.keyCode || e.which;
            if(code == 13){
                var element = popupPageContainer.find('[data-trigger="PopupListSearch"]');
                jQuery(element).trigger('click');
            }
        });
    },

    /**
     * Function to handle search event
     */
    searchHandler : function(){
        var aDeferred = jQuery.Deferred();
        var completeParams = this.getCompleteParams();
        completeParams['page'] = 1;
        this.getPageRecords(completeParams).then(
            function(data){
                aDeferred.resolve(data);
            });
        return aDeferred.promise();
    },

    registerEventFillStack : function() {
        var values = this.getStoreInput().val();
        if (values) {
            var valuesArr = JSON.parse(values);
            if (valuesArr.length > 0) {
                this.selectedRecordItem = valuesArr;
            } else {
                this.selectedRecordItem = new Array();
            }
        } else {
            this.selectedRecordItem = new Array();
        }
    },

    addTopScroll:function(){
        //var popupContainer = jQuery('#popupModal');
        //var popupEntriesDivTopScroll = $('div.popupEntriesDivTopScroll');
        //var w = popupContainer.find('.popupEntriesDiv').width();
        //popupEntriesDivTopScroll.height(40);
        //popupEntriesDivTopScroll.width(w);
        //var Options= {
        //    axis:"yx",
        //    scrollInertia: 0,
        //    setWidth: w,
        //    mouseWheel:{ enable: false }
        //};
        //app.helper.showHorizontalScroll(popupEntriesDivTopScroll, Options);
    },

    itemLookUpregisterEvents: function(){
        this.registerEventFillStack();
        this.registerEventForItemModuleCheckBoxSelect();
        this.registerEventForAddAnItem();
        this.registerEventForAddAllRecord();
        //this.registerEventScrollModal();
        this.registerEventFixWithColumn();
        this.fillSelectedStack();
        this.highlightSelectedRecord();
        this.addTopScroll();
        this.registerEventForPopupListSearch();
    }
});

jQuery(document).ready(function() {
    app.event.on("post.Popup.Load",function(event,params){
        vtUtils.applyFieldElementsView(jQuery('.myModal'));
        var popupInstance = new Potentials_Popup_Js();
        var eventToTrigger = params.eventToTrigger;
        if(typeof eventToTrigger != "undefined"){
            popupInstance.setEventName(params.eventToTrigger);
        }
        if(eventToTrigger == 'Vtiger.Reference.Popup.Selection' && app.getViewName() == 'Edit'){
            popupInstance.itemLookUpregisterEvents();
            popupInstance.registerPostPopupLoadEvents();
        }
        /*if(app.getModuleName()=='Invoice'||app.getModuleName()=='SalesOrder'||app.getModuleName()=='PurchaseOrder'||app.getModuleName()=='Quotes') {
         $('body').css({overflow: 'hidden'});
         $('#popupModal').on('hidden.bs.modal', function () {
         $('body').css({overflow: 'scroll'});
         })
         }*/
    });
});
(function($) {
    $.fn.ibox = function() {

        // set zoom ratio //
        resize = 80;
        ////////////////////
        var img = this;
        img.parent().append('<div id="ibox" style="position:absolute;overflow-y:none;background:#fff;border:1px solid #ccc;z-index:1001;display:none;padding:4px;-webkit-box-shadow: 0px 0px 6px 0px #bbb;-moz-box-shadow: 0px 0px 6px 0px #bbb;box-shadow: 0px 0px 6px 0px #bbb; " />');
        var ibox = $('#ibox');
        var elX = 0;
        var elY = 0;

        img.each(function() {
            var el = $(this);

            el.mouseenter(function() {

                ibox.html('');
                var elH = el.height();
                elX = el.position().left - 6; // 6 = CSS#ibox padding+border
                elY = el.position().top - 6;
                var h = el.height();
                var w = el.width();
                var wh;
                checkwh = (h < w) ? (wh = (w / h * resize) / 2) : (wh = (w * resize / h) / 2);

                $(this).clone().prependTo(ibox);
                ibox.css({
                    top: elY + 'px',
                    left: elX + 'px'
                });

                ibox.stop().fadeTo(200, 1, function() {
                    $(this).animate({top: '-='+(resize/2), left:'-='+wh},400).children('img').animate({height:'+='+resize,width:'+='+resize},400);
                });

            });

            ibox.mouseleave(function() {
                ibox.html('').hide();

            });
        });
    };
})(jQuery);