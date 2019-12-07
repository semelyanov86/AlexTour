/* ********************************************************************************
 * The content of this file is subject to the Calendar Popup ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
Vtiger.Class("CalendarPopup_Edit_Js",{
    editInstance:false,
    getInstance: function(){
        if(CalendarPopup_Edit_Js.editInstance == false){
            var instance = new CalendarPopup_Edit_Js();
            CalendarPopup_Edit_Js.editInstance = instance;
            return instance;
        }
        return CalendarPopup_Edit_Js.editInstance;
    },

    /*
     * function to trigger Convert Lead action
     * @param: Convert Lead url, currentElement.
     */
    convertLead : function(convertLeadUrl, buttonElement) {
        var leadid = jQuery(buttonElement).closest('div.referenceField').find('[name="leadid"]').val();
        if(leadid == '') {
            alert('Please select Lead record first');
            return;
        }
        app.helper.showProgress();
        var contactid=[];
        var relatedid=[];
        var saveCalendarLeads = function (contactid, relatedid) {
            var formCalendar=jQuery(document).find("#module_Events_Fields");
            jQuery('<input>').attr({
                type: 'hidden',
                id: 'contactids',
                name: 'contactids',
                value: JSON.parse(JSON.stringify(contactid))
            }).appendTo(formCalendar);
            jQuery('<input>').attr({
                type: 'hidden',
                id: 'related_id',
                name: 'related_id',
                value: JSON.parse(JSON.stringify(relatedid))
            }).appendTo(formCalendar);

            var recurringCheck = formCalendar.find('input[name="recurringcheck"]').is(':checked');

            //If the recurring check is not enabled then recurring type should be --None--
            if(recurringCheck == false) {
                formCalendar.find('#recurringType').append(jQuery('<option value="--None--">None</option>')).val('--None--');
            }

            var quickCreateCalUrl = formCalendar.serializeFormData();
            AppConnector.request(quickCreateCalUrl).then(
                function(data) {
                    app.helper.hideProgress();
                    //app.hideModalWindow();
                    convertLeadUrl +='&record='+leadid;
                    var instance= new CalendarPopup_Edit_Js();
                    function getParameterByName(name, url) {
                        if (!url) url = window.location.href;
                        name = name.replace(/[\[\]]/g, "\\$&");
                        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                            results = regex.exec(url);
                        if (!results) return null;
                        if (!results[2]) return '';
                        return decodeURIComponent(results[2].replace(/\+/g, " "));
                    }
                    var eventid = getParameterByName('eventid', convertLeadUrl);
                    var params = {
                        module : 'CalendarPopup',
                        view : 'ConvertLead',
                        eventid : eventid,
                        record : leadid,
                    };
                    app.request.post({'data' : params}).then(
                        function(err,data) {
                            if (err === null) {
                                instance.displayConvertLeadModel(data, buttonElement);
                            }
                        }
                    );
                }
            );
        };
        var count_done=0;
        jQuery.each(selected_module, function(i, item) {
            if(item != 'Calendar') {
                var form=jQuery(document).find("#module_"+item+"_Fields");
                // Check disable form
                if(form.find('input[name="editmode"]').val() == "") {
                    count_done++;
                    if(count_done == selected_module.length-1) {
                        // After save others record, save event record
                        saveCalendarLeads(contactid,relatedid);
                    }
                }else {
                    var quickCreateSaveUrl = form.serializeFormData();
                    AppConnector.request(quickCreateSaveUrl).then(
                        function(data) {
                            count_done++;
                            if(data.result._recordModule == 'Contacts'){
                                contactid.push(data.result._recordId);
                            }else {
                                relatedid.push(data.result._recordId);
                            }
                            if(count_done == selected_module.length-1) {
                                // After save others record, save event record
                                saveCalendarLeads(contactid,relatedid);
                            }
                        }
                    );
                }
            }
        });
    },
    requestToShowComposeEmailForm : function(selectedId,fieldname,fieldmodule){
        var selectedFields = [];
        selectedFields.push(fieldname);
        var selectedIds =  [];
        selectedIds.push(selectedId);
        var params = {
            'module' : 'Emails',
            'fieldModule' : fieldmodule,
            'selectedFields' : selectedFields,
            'selected_ids' : selectedIds,
            'view' : 'ComposeEmail'
        };
        Emails_MassEdit_Js.prototype.showPopupComposeEmailForm = function (params,cb,windowName) {
            var popupInstance = Vtiger_Popup_Js.getInstance();
            return popupInstance.show(params,cb,windowName);
        };
        var emailsMassEditInstance = Vtiger_Helper_Js.getEmailMassEditInstance();
        emailsMassEditInstance.showPopupComposeEmailForm(params);
    },

    /*
     * Function to get the compose email popup
     */
    getInternalMailer  : function(selectedId,fieldname,fieldmodule){
        var module = 'Emails';
        var cacheResponse = Vtiger_Helper_Js.checkServerConfigResponseCache;
        var  checkServerConfigPostOperations = function (data) {
            if(data == true){
                var form=jQuery(document).find("#module_"+fieldmodule+"_Fields");
                var quickCreateSaveUrl = form.serializeFormData();
                AppConnector.request(quickCreateSaveUrl).then(
                    function(data) {
                        CalendarPopup_Edit_Js.requestToShowComposeEmailForm(selectedId,fieldname,fieldmodule);
                    }
                );
            } else {
                alert(app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION'));
            }
        };
        if(cacheResponse === ''){
            var checkServerConfig = Vtiger_Helper_Js.checkServerConfig(module);
            checkServerConfig.then(function(data){
                Vtiger_Helper_Js.checkServerConfigResponseCache = data;
                checkServerConfigPostOperations(Vtiger_Helper_Js.checkServerConfigResponseCache);
            });
        } else {
            checkServerConfigPostOperations(Vtiger_Helper_Js.checkServerConfigResponseCache);
        }
    },
},{
    /*
     * function to display the convert lead model
     * @param: data used to show the model, currentElement.
     */
    displayConvertLeadModel : function(data, buttonElement) {
        var thisInstance = this;
        var instance = new Leads_Detail_Js();
        var errorElement = jQuery(data).find('#convertLeadError');
        if(errorElement.length != '0') {
            var errorMsg = errorElement.val();
            var errorTitle = jQuery(data).find('#convertLeadErrorTitle').val();
            var params = {
                title: errorTitle,
                text: errorMsg,
                addclass: "convertLeadNotify",
                width: '35%',
                pnotify_after_open: function(){
                    instance.disableConvertLeadButton(buttonElement);
                },
                pnotify_after_close: function(){
                    instance.enableConvertLeadButton(buttonElement);
                }
            };
            app.helper.showPnotify(params);
        } else {
            // var callBackFunction = function(data){
            //     var editViewObj = Vtiger_Edit_Js.getInstance();
            //     jQuery(data).find('.fieldInfo').collapse({
            //         'parent': '#leadAccordion',
            //         'toggle' : false
            //     });
            //     app.helper.showScroll(jQuery(data).find('#leadAccordion'), {'height':'350px'});
            //     editViewObj.registerBasicEvents(data);
            //     var checkBoxElements = instance.getConvertLeadModules();
            //     jQuery.each(checkBoxElements, function(index, element){
            //         instance.checkingModuleSelection(element);
            //     });
            //     instance.registerForReferenceField();
            //     instance.registerForDisableCheckEvent();
            //     instance.registerConvertLeadEvents();
            //     instance.getConvertLeadForm().vtValidate(app.validationEngineOptions);
            //     thisInstance.registerConvertLeadSave();
            // };

            var container = jQuery('.myModal');
            container.html('');
            // container.on('shown.bs.modal', function () {
            //     callBackFunction(container);
            // });
            container.html(data).modal('show');
            //This event is fired when the modal has been made visible to the user
                var editViewObj = Vtiger_Edit_Js.getInstance();
                jQuery(container).find('.fieldInfo').collapse({
                    'parent': '#leadAccordion',
                    'toggle' : false
                });
                app.helper.showScroll(jQuery(container).find('#leadAccordion'), {'height':'350px'});
                editViewObj.registerBasicEvents(container);
                var checkBoxElements = instance.getConvertLeadModules();
                jQuery.each(checkBoxElements, function(index, element){
                    instance.checkingModuleSelection(element);
                });
                instance.registerForReferenceField();
                instance.registerForDisableCheckEvent();
                instance.registerConvertLeadEvents();
                instance.getConvertLeadForm().vtValidate(app.validationEngineOptions);
                thisInstance.registerConvertLeadSave();
            vtUtils.applyFieldElementsView(container);
            // app.helper.showModal();
        }
    },

    registerConvertLeadSave : function () {
        var thisInstance=this;
        var instance = new Leads_Detail_Js();
        var form=jQuery('#convertLeadForm');
        form.on('click','[name="saveConvertButton"]', function (e) {
            var params = {
                "ignore": "disabled",
                submitHandler: function () {
                    var form=jQuery('#convertLeadForm');
                    var convertLeadModuleElements = instance.getConvertLeadModules();
                    var moduleArray = [];
                    var contactModel = form.find('#ContactsModule');
                    var accountModel = form.find('#AccountsModule');

                    jQuery.each(convertLeadModuleElements, function(index, element) {
                        if(jQuery(element).is(':checked')) {
                            moduleArray.push(jQuery(element).val());
                        }
                    });
                    form.find('input[name="modules"]').val(JSON.stringify(moduleArray));

                    var contactElement = contactModel.length;
                    var organizationElement = accountModel.length;

                    if(contactElement != '0' && organizationElement != '0') {
                        if(jQuery.inArray('Accounts',moduleArray) == -1 && jQuery.inArray('Contacts',moduleArray) == -1) {
                            alert(app.vtranslate('JS_SELECT_ORGANIZATION_OR_CONTACT_TO_CONVERT_LEAD'));
                            return;
                        }
                    } else if(organizationElement != '0') {
                        if(jQuery.inArray('Accounts',moduleArray) == -1) {
                            alert(app.vtranslate('JS_SELECT_ORGANIZATION'));
                            return;
                        }
                    } else if(contactElement != '0') {
                        if(jQuery.inArray('Contacts',moduleArray) == -1) {
                            alert(app.vtranslate('JS_SELECT_CONTACTS'));
                            return;
                        }
                    }
                    app.helper.showProgress();

                    var data = form.serializeFormData();

                    AppConnector.request(data).then(
                        function(data) {
                            if(data.result.indexOf('SUCCESS') != -1) {
                                var temp=data.result.split('::');
                                var eventId=temp[1];
                                app.helper.hideProgress();
                                app.helper.hideModal();
                                // var actionParams = {
                                //     "type":"POST",
                                //     "url":'index.php?module=CalendarPopup&view=MassActionAjax&mode=showCalendarPopup',
                                //     "dataType":"html",
                                //     "data" : {
                                //         record : eventId
                                //     }
                                // };
                                var actionParams = {
                                    module : 'CalendarPopup',
                                    view : 'MassActionAjax',
                                    mode : 'showCalendarPopup',
                                    record : eventId,
                                }
                                AppConnector.request(actionParams).then(
                                    function(data) {
                                        app.helper.hideProgress();
                                        if(data) {
                                            app.helper.showModal(data.result,{'cb' :function(data){
                                                app.helper.showScroll(jQuery('div[name="massEditContent"]'), {'height':'400px'});
                                                var listModules=selected_module;
                                                for (i = 0; i < listModules.length; i++) {
                                                    if(listModules[i] == 'Calendar'){

                                                    }else{
                                                        var massEditForm = jQuery(document).find("#module_"+listModules[i]+"_Fields");
                                                        massEditForm.vtValidate(app.validationEngineOptions);

                                                        var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(listModules[i]);
                                                        editInstance.registerBasicEvents(massEditForm);
                                                        var selectedRecord=massEditForm.find('input[name="record"]').val();
                                                        if(selectedRecord == '') {
                                                            massEditForm.find('input, textarea, button, select').attr('disabled','disabled');
                                                            massEditForm.find('select').trigger("liszt:updated");
                                                        }
                                                    }
                                                }
                                                thisInstance.registerBasicEvents(jQuery(document).find("#massEdit"));
                                            }},{'width':'65%'})
                                        }
                                    },
                                    function(error,err){
                                        app.helper.hideProgress();
                                    }
                                );
                            }
                        },
                        function(error) {
                            app.helper.hideProgress();
                            //TODO : Handle error
                        }
                    );
                }
            }
            form.vtValidate(params);
            // e.preventDefault();
            e.stopPropagation();
        });
    },


    getPopUpParams : function(container) {
        var params = {};
        var sourceModule = app.getModuleName();
        var popupReferenceModule = jQuery('input[name="popupReferenceModule"]',container).val();
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);
        var sourceField = sourceFieldElement.attr('name');
        var sourceRecordElement = jQuery('input[name="record"]');
        var sourceRecordId = '';
        if(sourceRecordElement.length > 0) {
            sourceRecordId = sourceRecordElement.val();
        }

        var params = {
            'module' : popupReferenceModule,
            'src_module' : sourceModule,
            'src_field' : sourceField,
            'src_record' : sourceRecordId
        };


        if(sourceField == 'contactid' || sourceFieldElement.attr('name') == 'potentialid') {
            var accountForm = container.closest('div.massEditContent').find('div#module_Accounts');
            if(accountForm.length>0) {
                var parentIdElement = accountForm.find('[name="accountid"]');
                var accountid = parentIdElement.val();
                if(parentIdElement.length > 0 && parentIdElement.val().length > 0 && parentIdElement.val() != 0) {
                    params['related_parent_id'] = parentIdElement.val();
                    params['related_parent_module'] = 'Accounts';
                }
            }
        }

        return params;
    },
    openPopUp : function(e){
        var thisInstance = this;
        var parentElem = jQuery(e.target).closest('div.referenceField');
        var tabno=parentElem.closest('div.tab-pane').data('tabno');

        var params = this.getPopUpParams(parentElem);

        var sourceFieldElement = jQuery('input[class="sourceField"]',parentElem);

        var prePopupOpenEvent = jQuery.Event(Vtiger_Edit_Js.preReferencePopUpOpenEvent);
        sourceFieldElement.trigger(prePopupOpenEvent);

        if(prePopupOpenEvent.isDefaultPrevented()) {
            return ;
        }

        var popupInstance =Vtiger_Popup_Js.getInstance();
        popupInstance.show(params,function(data){
            var responseData = JSON.parse(data);
            var dataList = [];
            for(var id in responseData){
                var data = {
                    'name' : responseData[id].name,
                    'id' : id
                };
                dataList.push(data);
                thisInstance.setReferenceFieldValue(parentElem, data,tabno);
            }
            sourceFieldElement.trigger(Vtiger_Edit_Js.postReferenceSelectionEvent,{'data':responseData});
        });
    },
    setReferenceFormData : function (source_module, record, mode, tabno) {
        var actionParams = {
            module : 'CalendarPopup',
            view : 'MassActionAjax',
            mode : 'getEditForm',
            source_module : source_module,
            record : record,
            editmode : mode,
        }
        // prefill account to contact and potential
        if(mode == 'create' && (source_module == 'Contacts' || source_module == 'Potentials')) {
            // Get account id
            var massEditForm = jQuery(document).find("#module_"+source_module+tabno+"_Fields");
            var accountForm = massEditForm.closest('div.massEditContent').find('div#module_Accounts');
            if(accountForm.length>0) {
                var accountid = accountForm.find('[name="accountid"]').val();
                if(source_module == 'Contacts') {
                    actionParams['account_id']=accountid;
                }else{
                    actionParams['related_to']=accountid;
                }
            }
        }
        AppConnector.request(actionParams).then(
            function(data) {
//                jQuery("#module_"+source_module+"_Fields").html(data);
                var massEditForm = jQuery(document).find("#module_"+source_module+tabno+"_Fields");
                massEditForm.html(data.result);

                if(mode == '') {
                    massEditForm.find('input, textarea, button, select').attr('disabled','disabled');
                }else {
                    vtUtils.applyFieldElementsView(massEditForm);
                    //register all select2 Elements
                    vtUtils.applyFieldElementsView(massEditForm.find('select.select2'));
                    //register date fields event to show mini calendar on click of element
                    vtUtils.applyFieldElementsView(massEditForm);
                }
                massEditForm.vtValidate(app.validationEngineOptions);
                var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(source_module);
                editInstance.registerBasicEvents(massEditForm);
                if(mode == 'create') {
                    if(massEditForm.find('input[name="account_id"]').val() !='' && massEditForm.find('input[name="account_id"]').length !='0') {
                        var accountData={};
                        accountData['record'] = massEditForm.find('input[name="account_id"]').val();
                        accountData['source_module'] = 'Accounts';
                        accountData['selectedName'] = massEditForm.find('#account_id_display').val();
                        var thisInstance = this;
                        thisInstance.referenceSelectionEventHandler(accountData, massEditForm);
                    }
                }
            }
        );
    },
    referenceSelectionEventHandler: function (data, container) {
        var thisInstance = this;
        var message = app.vtranslate('OVERWRITE_EXISTING_MSG1') + app.vtranslate('SINGLE_' + data['source_module']) + ' (' + data['selectedName'] + ') ' + app.vtranslate('OVERWRITE_EXISTING_MSG2');
        Vtiger_Helper_Js.showConfirmationBox({'message': message}).then(
            function (e) {
                thisInstance.copyAddressDetails(data, container);
            },
            function (error, err) {
            });
    },

    setReferenceFieldValue : function(container, params,tabno) {
        var sourceField = $(container).find('input[class="sourceField"]').attr('name');
        var fieldElement = $(container).find('input[name="'+sourceField+'"]');
        var sourceFieldDisplay = sourceField+"_display";
        var fieldDisplayElement = $(container).find('input[name="'+sourceFieldDisplay+'"]');
        var popupReferenceModule = $(container).find('input[name="popupReferenceModule"]').val();

        var selectedName = params.name;
        var id = params.id;

        fieldElement.val(id);
        fieldDisplayElement.val(selectedName).attr('readonly',true);
        this.setReferenceFormData(popupReferenceModule,id,null,tabno);
//        fieldElement.trigger(Vtiger_Edit_Js.referenceSelectionEvent, {'source_module' : popupReferenceModule, 'record' : id, 'selectedName' : selectedName});

        // fieldDisplayElement.vtValidate('closePrompt',fieldDisplayElement);
    },

    getReferencedModuleName : function(parenElement){
        return jQuery('input[name="popupReferenceModule"]',parenElement).val();
    },

    searchModuleNames : function(params) {
        var aDeferred = jQuery.Deferred();

        if(typeof params.module == 'undefined') {
            params.module = app.getModuleName();
        }

        if(typeof params.action == 'undefined') {
            params.action = 'BasicAjax';
        }
        AppConnector.request(params).then(
            function(data){
                aDeferred.resolve(data);
            },
            function(error){
                //TODO : Handle error
                aDeferred.reject();
            }
        );
        return aDeferred.promise();
    },

    /**
     * Function to get reference search params
     */
    getReferenceSearchParams : function(element){
        var tdElement = jQuery(element).closest('div.referencefield-wrapper');
        var params = {};
        var searchModule = this.getReferencedModuleName(tdElement);
        params.search_module = searchModule;
        return params;
    },

    /**
     * Function which will handle the reference auto complete event registrations
     * @params - container <jQuery> - element in which auto complete fields needs to be searched
     */
    registerAutoCompleteFields : function(container) {
        var thisInstance = this;
        $(container).find('input.autoComplete').autocomplete({
            'minLength' : '3',
            'source' : function(request, response){
                //element will be array of dom elements
                //here this refers to auto complete instance
                var inputElement = jQuery(this.element[0]);
                var searchValue = request.term;
                var params = thisInstance.getReferenceSearchParams(inputElement);
                params.search_value = searchValue;
                thisInstance.searchModuleNames(params).then(function(data){
                    var reponseDataList = [];
                    var serverDataFormat = data.result;
                    if(serverDataFormat.length <= 0) {
                        jQuery(inputElement).val('');
                        serverDataFormat = new Array({
                            'label' : app.vtranslate('JS_NO_RESULTS_FOUND'),
                            'type'  : 'no results'
                        });
                    }
                    for(var id in serverDataFormat){
                        var responseData = serverDataFormat[id];
                        reponseDataList.push(responseData);
                    }
                    response(reponseDataList);
                });
            },
            'select' : function(event, ui ){
                var selectedItemData = ui.item;
                //To stop selection if no results is selected
                if(typeof selectedItemData.type != 'undefined' && selectedItemData.type=="no results"){
                    return false;
                }
                selectedItemData.name = selectedItemData.value;
                var element = jQuery(this);
                var tdElement = element.closest('div.referenceField');
                var tabno=tdElement.closest('div.tab-pane').data('tabno');
                thisInstance.setReferenceFieldValue(tdElement, selectedItemData,tabno);

                var sourceField = tdElement.find('input[class="sourceField"]').attr('name');
                var fieldElement = tdElement.find('input[name="'+sourceField+'"]');
                fieldElement.trigger(Vtiger_Edit_Js.postReferenceSelectionEvent,{'data':selectedItemData});
            },
            'change' : function(event, ui) {
                var element = jQuery(this);
                //if you dont have readonly attribute means the user didnt select the item
                if(element.attr('readonly')== undefined) {
                    element.closest('div.referenceField').find('.clearReferenceSelectionC').trigger('click');
                }
            },
            // 'open' : function(event,ui) {
            //     //To Make the menu come up in the case of quick create
            //     jQuery(this).data('autocomplete').menu.element.css('z-index','100001');
            //
            // }
        });
    },
    /**
     * Function which will register reference field clear event
     * @params - container <jQuery> - element in which auto complete fields needs to be searched
     */
    registerClearReferenceSelectionEvent : function(container) {
        var thisInstance=this;

        $(container).find('.clearReferenceSelectionC').on('click', function(e,params){
            if (typeof params == 'undefined') {
                params = {};
            }
            var mode=params.mode;
            if(typeof mode == 'undefined') {
                mode = '';
            }
            var element = jQuery(e.currentTarget);
            var tabno=element.closest('div.tab-pane').data('tabno');
            var parentTdElement = element.closest('div.referenceField');

            var searchModule = thisInstance.getReferencedModuleName(parentTdElement);
            thisInstance.setReferenceFormData(searchModule,'',mode,tabno);

            var fieldNameElement = parentTdElement.find('.sourceField');
            var fieldName = fieldNameElement.attr('name');
            fieldNameElement.val('');
            parentTdElement.find('#'+fieldName+'_display').removeAttr('readonly').val('');
        })
    },
    /**
     * Function which will register event for create of reference record
     * This will allow users to create reference record from edit view of other record
     */
    registerReferenceCreate : function(container) {
        var thisInstance = this;
        $(container).on('click','.createReferenceRecordC', function(e){
            var element = jQuery(e.currentTarget);
            var parentTdElement = element.closest('div.referenceField');
            parentTdElement.find('.clearReferenceSelectionC').trigger('click', {'mode':'create'});
        })
    },
    referenceModulePopupRegisterEvent : function(container){
        var thisInstance = this;
        $(container).on("click",'.relatedPopupC',function(e){
            thisInstance.openPopUp(e);
        });
    },

    registerSaveEvent: function(container) {
        var thisInstance = this;
        var aDeferred = jQuery.Deferred();
        $(container).on('click','button[name="saveButton"]', function(e){
            jQuery(this).attr('disabled','disabled');
            app.helper.showProgress();
            var contactid=[];
            var relatedid=[];
            var saveCalendar = function (contactid, relatedid) {
                var formCalendar=jQuery(document).find("#module_Events_Fields");
                jQuery('<input>').attr({
                    type: 'hidden',
                    id: 'contactids',
                    name: 'contactids',
                    value: JSON.parse(JSON.stringify(contactid))
                }).appendTo(formCalendar);
                jQuery('<input>').attr({
                    type: 'hidden',
                    id: 'related_id',
                    name: 'related_id',
                    value: JSON.parse(JSON.stringify(relatedid))
                }).appendTo(formCalendar);

                var recurringCheck = formCalendar.find('input[name="recurringcheck"]').is(':checked');

                //If the recurring check is not enabled then recurring type should be --None--
                if(recurringCheck == false) {
                    formCalendar.find('#recurringType').append(jQuery('<option value="--None--">None</option>')).val('--None--');
                }

                var quickCreateCalUrl = formCalendar.serializeFormData();

                AppConnector.request(quickCreateCalUrl).then(
                    function(data) {
                        app.helper.hideProgress();
                        app.hideModalWindow();
                        var calendarInstance = new Calendar_Calendar_Js();
                        calendarInstance.updateListView();
                        calendarInstance.updateCalendarView("Event");
                    }
                );
            };
            var count_done=0;
            jQuery.each(selected_module, function(i, item) {
                if(item != 'Calendar') {
                    var form=jQuery(document).find("#module_"+item+"_Fields");

                    // Check disable form
                    if(form.find('input[name="editmode"]').val() == "") {
                        count_done++;
                        if(count_done == selected_module.length-1) {
                            // After save others record, save event record
                            saveCalendar(contactid,relatedid);
                        }
                    }else {
                        var quickCreateSaveUrl = form.serializeFormData();
                        AppConnector.request(quickCreateSaveUrl).then(
                            function(data) {
                                count_done++;
                                if(data.result._recordModule == 'Contacts'){
                                    contactid.push(data.result._recordId);
                                }else {
                                    relatedid.push(data.result._recordId);
                                }
                                if(count_done == selected_module.length-1) {
                                    // After save others record, save event record
                                    saveCalendar(contactid,relatedid);
                                }
                            }
                        );
                    }
                }
            });
        });
    },

    registerReminderFieldCheckBox : function(container) {
        $(container).find('input[name="set_reminder"]').on('change', function(e) {
            var element = jQuery(e.currentTarget);
            var closestDiv = element.closest('div').next();
            if(element.is(':checked')) {
                closestDiv.show();
            } else {
                closestDiv.hide();
            }
        })
    },

    /**
     * Function which will register change event on recurrence field checkbox
     */
    registerRecurrenceFieldCheckBox : function(container) {
        var thisInstance = this;
        $(container).find('input[name="recurringcheck"]').on('change', function(e) {
            var element = jQuery(e.currentTarget);
            var repeatUI = $(container).find('#repeatUI');
            if(element.is(':checked')) {
                repeatUI.show();
            } else {
                repeatUI.hide();
            }
        });
    },

    /**
     * Function which will register the change event for recurring type
     */
    registerRecurringTypeChangeEvent : function() {
        var thisInstance = this;
        jQuery('#recurringType').on('change', function(e) {
            var currentTarget = jQuery(e.currentTarget);
            var recurringType = currentTarget.val();
            thisInstance.changeRecurringTypesUIStyles(recurringType);

        });
    },

    /**
     * Function which will register the change event for repeatMonth radio buttons
     */
    registerRepeatMonthActions : function(container) {
        var thisInstance = this;
        $(container).find('input[name="repeatMonth"]').on('change', function(e) {
            //If repeatDay radio button is checked then only select2 elements will be enable
            thisInstance.repeatMonthOptionsChangeHandling();
        });
    },


    /**
     * Function which will change the UI styles based on recurring type
     * @params - recurringType - which recurringtype is selected
     */
    changeRecurringTypesUIStyles : function(recurringType) {
        var thisInstance = this;
        if(recurringType == 'Daily' || recurringType == 'Yearly') {
            jQuery('#repeatWeekUI').removeClass('show').addClass('hide');
            jQuery('#repeatMonthUI').removeClass('show').addClass('hide');
        } else if(recurringType == 'Weekly') {
            jQuery('#repeatWeekUI').removeClass('hide').addClass('show');
            jQuery('#repeatMonthUI').removeClass('show').addClass('hide');
        } else if(recurringType == 'Monthly') {
            jQuery('#repeatWeekUI').removeClass('show').addClass('hide');
            jQuery('#repeatMonthUI').removeClass('hide').addClass('show');
        }
    },

    /**
     * This function will handle the change event for RepeatMonthOptions
     */
    repeatMonthOptionsChangeHandling : function() {
        //If repeatDay radio button is checked then only select2 elements will be enable
        if(jQuery('#repeatDay').is(':checked')) {
            jQuery('#repeatMonthDate').attr('disabled', true);
            jQuery('#repeatMonthDayType').select2("enable");
            jQuery('#repeatMonthDay').select2("enable");
        } else {
            jQuery('#repeatMonthDate').removeAttr('disabled');
            jQuery('#repeatMonthDayType').select2("disable");
            jQuery('#repeatMonthDay').select2("disable");
        }
    },

    /**
     * Function to register the event status change event
     */
    registerEventStatusChangeEvent : function(container){
        var followupContainer = $(container).find('.followUpContainer');
        //if default value is set to Held then display follow up container
        var defaultStatus = $(container).find('select[name="eventstatus"]').val();
        if(defaultStatus == 'Held'){
            followupContainer.show();
        }
        $(container).find('select[name="eventstatus"]').on('change',function(e){
            var selectedOption = jQuery(e.currentTarget).val();
            if(selectedOption == 'Held'){
                followupContainer.show();
            } else{
                followupContainer.hide();
            }
        });
    },

    registerEventForCreateAnotherBtn : function (container) {
        var thisInstance=this;
        $(container).on('click', '.btnCreateAnother', function (e) {
            var element=jQuery(e.currentTarget);
            var module=element.data('module');
            var moduleLabel=element.data('module-label');
            // Get total tabs
            var totalTab=$(container).find('.module_'+module).length;
            var tabno=totalTab+1;
            // Add new tab
            $(container).find('ul.massEditTabs li a.module_'+module+':last').closest('li').after('<li><a href="#module_'+module+tabno+'" class="module_'+module+'" data-toggle="tab">'+moduleLabel+' '+tabno+'</a></li>');

            // Add content of new tab
            var actionParams = {
                "module": "CalendarPopup",
                "view": "MassActionAjax",
                "mode": "getNewRecordView",
                "rel_module":module,
                "tabno":totalTab
            };

            AppConnector.request(actionParams).then(
                function(data) {
                    $(container).find('div.massEditContent').append(data.result);
                    var tabContent = $(container).find("#module_"+module+tabno);
                    var massEditForm = jQuery(document).find("#module_"+module+tabno+"_Fields");
                    massEditForm.find('input, textarea, button, select').attr('disabled','disabled');
                    thisInstance.referenceModulePopupRegisterEvent(tabContent);
                    thisInstance.registerAutoCompleteFields(tabContent);
                    thisInstance.registerClearReferenceSelectionEvent(tabContent);
                    thisInstance.registerReferenceCreate(massEditForm);
                }
            );
        });
    },
    getQueryParams:function(qs) {
        if(typeof(qs) != 'undefined' ){
            qs = qs.toString().split('+').join(' ');
            var params = {},
                tokens,
                re = /[?&]?([^=]+)=([^&]*)/g;
            while (tokens = re.exec(qs)) {
                params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
            }
            return params;
        }
    },

    /**
     * Function which will register basic events which will be used in quick create as well
     *
     */
    registerBasicEvents : function(container) {
        this.referenceModulePopupRegisterEvent(container);
        this.registerAutoCompleteFields(container);
        this.registerClearReferenceSelectionEvent(container);
        this.registerReferenceCreate(container);
        this.registerReminderFieldCheckBox(container);
        this.registerRecurrenceFieldCheckBox(container);
        this.registerRecurringTypeChangeEvent(container);
        this.registerRepeatMonthActions(container);
        this.registerEventStatusChangeEvent(container);
        this.registerSaveEvent(container);
        this.registerEventForCreateAnotherBtn(container);
    }
});

jQuery(document).ready(function () {
    // Only load when loadHeaderScript=1 BEGIN #241208
    /*if (typeof VTECheckLoadHeaderScript == 'function') {
        if (!VTECheckLoadHeaderScript('CalendarPopup')) {
            return;
        }
    }*/
    // Only load when loadHeaderScript=1 END #241208
    if(app.module() != 'Calendar' && app.view() != 'Calendar' && app.view() != 'SharedCalendar' ) {
        return;
    }

    $('#calendarview_basicaction_addevent').removeAttrs('onclick');
    $('#calendarview_basicaction_addevent').on('click', function () {
        var actionParams = {
            module : 'CalendarPopup',
            view : 'MassActionAjax',
            mode : 'showCalendarPopup',
            record : ''
        };
        app.helper.showProgress();
        app.request.post({'data' : actionParams}).then(
            function(err,data){
                if(err === null) {
                    app.helper.hideProgress();
                    if(data) {
                        app.helper.showModal(data,{'width': '90%','cb' : function(data){
                                app.helper.showScroll(jQuery('div[name="massEditContent"]'), {'height':'600px'});
                                var listModules= selected_module;
                                for (i = 0; i < listModules.length; i++) {
                                    if(listModules[i] == 'Calendar'){
                                        var massEditForm = jQuery(document).find("#module_Events_Fields");
                                        massEditForm.vtValidate(app.validationEngineOptions);
                                        var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(listModules[i]);
                                        editInstance.registerBasicEvents(massEditForm);
                                    }else{
                                        var massEditForm = jQuery(document).find("#module_"+listModules[i]+"_Fields");
                                        massEditForm.vtValidate(app.validationEngineOptions);

                                        var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(listModules[i]);
                                        editInstance.registerBasicEvents(massEditForm);
                                        var selectedRecord=massEditForm.find('input[name="record"]').val();
                                        if(selectedRecord == '') {
                                            massEditForm.find('input, textarea, button, select').attr('disabled','disabled');
                                            massEditForm.find('select').trigger("liszt:updated");
                                        }else {

                                        }
                                    }
                                }
                                var editInstanceC = CalendarPopup_Edit_Js.getInstance();
                                editInstanceC.registerBasicEvents(jQuery(document).find("#massEdit"));
                            }}
                            ,{'width':'90%'})
                    }
                }else {
                    app.helper.hideProgress();
                }
            }
        );

    });
    // Update recordurl of records
    jQuery( document ).ajaxComplete(function(event, xhr, settings) {
        var instance = new CalendarPopup_Edit_Js();
        var url = settings.data;
        if(typeof url == 'undefined' && settings.url) url = settings.url;
        var other_url = instance.getQueryParams(url);
        if(other_url.module == 'Calendar' && other_url.action == 'Feed' && other_url.mode == 'batch' ||(other_url.module == 'CalendarHorizontal'&& other_url.action == 'HorizontalAjax' && other_url.modeType == 'batch')) {
            $(".time-sch-item-content").off('click');
            jQuery("a.fc-event, tr.listViewCalendar, div.time-sch-item, div.activityEntries a[class!='fieldValue']").on("click", function(e) {
                if(jQuery(e.target).hasClass('delete') || jQuery(e.target).closest('.delete').length > 0 || jQuery(e.target).hasClass('listViewEntriesCheckBox') ) {
                    return;
                }
                var orgUrl = '';
                if(jQuery(e.target).closest('div.time-sch-item').hasClass("time-sch-item")) {
                    var dataItemScheduler =  jQuery(e.target).closest('div.time-sch-item').data('item');
                    orgUrl = dataItemScheduler.link;
                }else{
                    if(jQuery(this).is('a')) {
                        orgUrl=jQuery(this).attr('href');
                    }else if(jQuery(this).is('tr')) {
                        orgUrl=jQuery(this).data('recordurl');
                    }
                }

                if(orgUrl.indexOf('module=Calendar') != -1) {
                    var url = orgUrl.replace('Calendar','CalendarPopup');
                    url = url.replace('Detail','MassActionAjax');
                    var CalendarPopupUrl = instance.getQueryParams(url);
                    var checkParams = {
                        module : 'CalendarPopup',
                        view : 'MassActionAjax',
                        mode : 'checkReordType',
                        record : CalendarPopupUrl.record
                    };
                    app.request.post({'data': checkParams}).then(
                        function(err,data){
                                if(data == 'Events') {
                                    var actionParams = {
                                        module : 'CalendarPopup',
                                        view : 'MassActionAjax',
                                        mode : 'showCalendarPopup',
                                        record : CalendarPopupUrl.record
                                    };
                                    // var actionParams = {
                                    //     "type":"POST",
                                    //     "url":url +'&mode=showCalendarPopup',
                                    //     "dataType":"html",
                                    //     "data" : {}
                                    // };
                                    app.helper.showProgress();
                                    app.request.post({'data' : actionParams}).then(
                                        function(err,data){
                                            if(err === null) {
                                                app.helper.hideProgress();
                                                if(data) {
                                                    app.helper.showModal(data,{'width': '90%','cb' : function(data){
                                                            app.helper.showScroll(jQuery('div[name="massEditContent"]'), {'height':'600px'});
                                                            var listModules= selected_module;
                                                            for (i = 0; i < listModules.length; i++) {
                                                                if(listModules[i] == 'Calendar'){
                                                                    var massEditForm = jQuery(document).find("#module_Events_Fields");
                                                                    massEditForm.vtValidate(app.validationEngineOptions);
                                                                    var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(listModules[i]);
                                                                    editInstance.registerBasicEvents(massEditForm);
                                                                }else{
                                                                    var massEditForm = jQuery(document).find("#module_"+listModules[i]+"_Fields");
                                                                    massEditForm.vtValidate(app.validationEngineOptions);

                                                                    var editInstance = Vtiger_Edit_Js.getInstanceByModuleName(listModules[i]);
                                                                    editInstance.registerBasicEvents(massEditForm);
                                                                    var selectedRecord=massEditForm.find('input[name="record"]').val();
                                                                    if(selectedRecord == '') {
                                                                        massEditForm.find('input, textarea, button, select').attr('disabled','disabled');
                                                                        massEditForm.find('select').trigger("liszt:updated");
                                                                    }else {

                                                                    }
                                                                }
                                                            }
                                                            var editInstanceC = CalendarPopup_Edit_Js.getInstance();
                                                            editInstanceC.registerBasicEvents(jQuery(document).find("#massEdit"));
                                                        }}
                                                    ,{'width':'90%'})
                                                }
                                            }else {
                                                app.helper.hideProgress();
                                            }
                                        }
                                    );
                                }else {
                                    document.location.href=orgUrl;
                                }
                        }
                    );
                }else {
                    document.location.href=orgUrl;
                }
                e.preventDefault();
            });
        }
    });
    app.listenPostAjaxReady(function() {
        if(typeof jQuery('div.relatedContainer') != 'undefined' || jQuery('#module').val() == 'Calendar') {
            if(jQuery('div.relatedContainer').find('[name="relatedModuleName"]').val() == 'Calendar' || jQuery('#module').val() == 'Calendar') {
                jQuery('tr.listViewEntries').each(function (idx,el) {
                    jQuery(el).removeClass('listViewEntries');
                    jQuery(el).addClass('listViewCalendar');
                    jQuery(el).css('cursor','pointer');
                });
            }
        }

    });
});