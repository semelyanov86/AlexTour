/**
 * VGS Listview Colors
 *
 * @package        VGS Listview Colors
 * @author         Conrado Maggi
 * @license        Commercial
 * @copyright      2018 VGS Global - www.vgsglobal.com
 * @version        Release: 1.0
 */

jQuery.Class('VDSimplyKPIEdit_Js', {}, {
    
    registerEventFilterCondition: function () {
        var thisInstance = this;
        jQuery('#VDSimplyKPI_editView_fieldName_setype').on('change', function (e) {
            var currentElement = jQuery(e.currentTarget);
            var params = {
               'module': 'VDSimplyKPI',
               'view': 'EditAjax',
               'mode': 'getWorkflowConditions',
               'record': jQuery("input[name='record']").val(),
               'module_name': currentElement.val()
            };

            app.helper.showProgress();
            app.request.get({data: params}).then(function (error, data) {
                app.helper.hideProgress();
                jQuery('#workflow_condition').html(data);
                var advanceFilterContainer = jQuery('#advanceFilterContainer');
                vtUtils.applyFieldElementsView(jQuery('#workflow_condition'));
                thisInstance.advanceFilterInstance = VDSimplyKPI_AdvanceFilter_Js.getInstance(jQuery('#filterContainer', advanceFilterContainer));
                thisInstance.getPopUp(advanceFilterContainer);
                thisInstance.getFieldsData(params.module_name);

                //Action
                // thisInstance.registerActionEvents(jQuery('#workflow_action'));
                
                app.helper.registerLeavePageWithoutSubmit(jQuery('#workflow_edit'));
            });
        });
        jQuery('#module_name').trigger('change');
    },
    getFieldsData: function(moduleName) {
        $('#datafields').val('').trigger("liszt:updated");
        var params = {
            'module': 'VDSimplyKPI',
            'view': 'EditAjax',
            'mode': 'getModuleCalculationFields',
            'moduleName': moduleName
        };
        app.request.get({data: params}).then(function (error, data) {
            var new_options = [];
            var keys = Object.keys(data[moduleName]);
            for (let i = 0; i < keys.length; i++) {
                new_options.push(data[moduleName][keys[i]])
            }
            var modelList = document.getElementById("datafields");
            while (modelList.options.length) {
                modelList.remove(0);
            }
            if (keys) {
                var i;
                for (i = 0; i < keys.length; i++) {
                    var car = new Option(data[moduleName][keys[i]], keys[i]);
                    modelList.options.add(car);
                }
                var car = new Option(app.vtranslate('JS_NUMBER_OF_RECORDS'), 'count(*)');
                modelList.options.add(car);
                $('#datafields').val('').trigger("liszt:updated");

            }
        });
    },

    //this function is not working
    getPopUp: function (container) {
        var thisInstance = this;
        if (typeof container == 'undefined') {
           container = jQuery('#EditView');
        }
        var isPopupShowing = false;
        container.on('click', '.getPopupUi', function (e) {
           // Added to prevent multiple clicks event
           if(isPopupShowing) {
               return false;
           }
           var fieldValueElement = jQuery(e.currentTarget);
           var fieldValue = fieldValueElement.val();
           var fieldUiHolder = fieldValueElement.closest('.fieldUiHolder');
           var valueType = fieldUiHolder.find('[name="valuetype"]').val();
           if (valueType == '' || valueType == 'null') {
              valueType = 'rawtext';
           }
           var conditionsContainer = fieldValueElement.closest('.conditionsContainer');
           var conditionRow = fieldValueElement.closest('.conditionRow');

           var clonedPopupUi = conditionsContainer.find('.popupUi').clone(true, true).removeClass('hide').removeClass('popupUi').addClass('clonedPopupUi');
           clonedPopupUi.find('select').addClass('select2');
           clonedPopupUi.find('.fieldValue').val(fieldValue);
           clonedPopupUi.find('.fieldValue').removeClass('hide');
           if (fieldValueElement.hasClass('date')) {
              clonedPopupUi.find('.textType').find('option[value="rawtext"]').attr('data-ui', 'input');
              var dataFormat = fieldValueElement.data('date-format');
              if (valueType == 'rawtext') {
                 var value = fieldValueElement.val();
              } else {
                 value = '';
              }
              var clonedDateElement = '<input type="text" style="width: 30%;" class="dateField fieldValue inputElement" value="' + value + '" data-date-format="' + dataFormat + '" data-input="true" >'
              clonedPopupUi.find('.fieldValueContainer div').prepend(clonedDateElement);
           } else if (fieldValueElement.hasClass('time')) {
              clonedPopupUi.find('.textType').find('option[value="rawtext"]').attr('data-ui', 'input');
              if (valueType == 'rawtext') {
                 var value = fieldValueElement.val();
              } else {
                 value = '';
              }
              var clonedTimeElement = '<input type="text" style="width: 30%;" class="timepicker-default fieldValue inputElement" value="' + value + '" data-input="true" >'
              clonedPopupUi.find('.fieldValueContainer div').prepend(clonedTimeElement);
           } else if (fieldValueElement.hasClass('boolean')) {
              clonedPopupUi.find('.textType').find('option[value="rawtext"]').attr('data-ui', 'input');
              if (valueType == 'rawtext') {
                 var value = fieldValueElement.val();
              } else {
                 value = '';
              }
              var clonedBooleanElement = '<input type="checkbox" style="width: 30%;" class="fieldValue inputElement" value="' + value + '" data-input="true" >';
              clonedPopupUi.find('.fieldValueContainer div').prepend(clonedBooleanElement);

              var fieldValue = clonedPopupUi.find('.fieldValueContainer input').val();
              if (value == 'true:boolean' || value == '') {
                 clonedPopupUi.find('.fieldValueContainer input').attr('checked', 'checked');
              } else {
                 clonedPopupUi.find('.fieldValueContainer input').removeAttr('checked');
              }
           }
           var callBackFunction = function (data) {
              isPopupShowing = false;
              data.find('.clonedPopupUi').removeClass('hide');
              var moduleNameElement = conditionRow.find('[name="modulename"]');
              if (moduleNameElement.length > 0) {
                 var moduleName = moduleNameElement.val();
                 data.find('.useFieldElement').addClass('hide');
                 jQuery(data.find('[name="' + moduleName + '"]').get(0)).removeClass('hide');
              }          
              thisInstance.postShowModalAction(data, valueType);
              thisInstance.registerChangeFieldEvent(data);
              thisInstance.registerSelectOptionEvent(data);
              thisInstance.registerPopUpSaveEvent(data, fieldUiHolder);
              thisInstance.registerRemoveModalEvent(data);
              data.find('.fieldValue').filter(':visible').trigger('focus');
           };
           conditionsContainer.find('.clonedPopUp').html(clonedPopupUi);
           jQuery('.clonedPopupUi').on('shown', function () {
              if (typeof callBackFunction == 'function') {
                 callBackFunction(jQuery('.clonedPopupUi', conditionsContainer));
              }
           });
           isPopupShowing = true;
           app.helper.showModal(jQuery('.clonedPopUp', conditionsContainer).find('.clonedPopupUi'), {cb: callBackFunction});
        });
     },
     //this function is not working
     registerRemoveModalEvent: function (data) {
      data.on('click', '.closeModal', function (e) {
         data.modal('hide');
      });
   },
   //this function is not working
    registerPopUpSaveEvent: function (data, fieldUiHolder) {
       jQuery('[name="saveButton"]', data).on('click', function (e) {
          var valueType = jQuery('select.textType', data).val();

          fieldUiHolder.find('[name="valuetype"]').val(valueType);
          var fieldValueElement = fieldUiHolder.find('.getPopupUi');
          if (valueType != 'rawtext') {
             fieldValueElement.addClass('ignore-validation');
          } else {
             fieldValueElement.removeClass('ignore-validation');
          }
          var fieldType = data.find('.fieldValue').filter(':visible').attr('type');
          var fieldValue = data.find('.fieldValue').filter(':visible').val();
          //For checkbox field type, handling fieldValue
          if (fieldType == 'checkbox') {
             if (data.find('.fieldValue').filter(':visible').is(':checked')) {
                fieldValue = 'true:boolean';
             } else {
                fieldValue = 'false:boolean';
             }
          }
          fieldValueElement.val(fieldValue);
          data.modal('hide');
       });
    },
//this function is not working
    registerSelectOptionEvent: function (data) {
       jQuery('.useField,.useFunction', data).on('change', function (e) {
          var currentElement = jQuery(e.currentTarget);
          var newValue = currentElement.val();
          var oldValue = data.find('.fieldValue').filter(':visible').val();
          var textType = currentElement.closest('.clonedPopupUi').find('select.textType').val();
          if (currentElement.hasClass('useField')) {
             //If it is fieldname mode then we need to allow only one field
             if (oldValue != '' && textType != 'fieldname') {
                var concatenatedValue = oldValue + ' ' + newValue;
             } else {
                concatenatedValue = newValue;
             }
          } else {
             concatenatedValue = oldValue + newValue;
          }
          data.find('.fieldValue').val(concatenatedValue);
          currentElement.val('').select2("val", '');
       });
    },
    //this function is not working
    registerChangeFieldEvent: function (data) {
       jQuery('.textType', data).on('change', function (e) {
          var valueType = jQuery(e.currentTarget).val();
          var useFieldContainer = jQuery('.useFieldContainer', data);
          var useFunctionContainer = jQuery('.useFunctionContainer', data);
          var uiType = jQuery(e.currentTarget).find('option:selected').data('ui');
          jQuery('.fieldValue', data).hide();
          jQuery('[data-' + uiType + ']', data).show();
          if (valueType == 'fieldname') {
             useFieldContainer.removeClass('hide');
             useFunctionContainer.addClass('hide');
          } else if (valueType == 'expression') {
             useFieldContainer.removeClass('hide');
             useFunctionContainer.removeClass('hide');
          } else {
             useFieldContainer.addClass('hide');
             useFunctionContainer.addClass('hide');
          }
          jQuery('.helpmessagebox', data).addClass('hide');
          jQuery('#' + valueType + '_help', data).removeClass('hide');
          data.find('.fieldValue').val('');
       });
    },
    postShowModalAction: function (data, valueType) {
       if (valueType == 'fieldname') {
          jQuery('.useFieldContainer', data).removeClass('hide');
          jQuery('.textType', data).val(valueType).trigger('change');
       } else if (valueType == 'expression') {
          jQuery('.useFieldContainer', data).removeClass('hide');
          jQuery('.useFunctionContainer', data).removeClass('hide');
          jQuery('.textType', data).val(valueType).trigger('change');
       }
       jQuery('#' + valueType + '_help', data).removeClass('hide');
       var uiType = jQuery('.textType', data).find('option:selected').data('ui');
       jQuery('.fieldValue', data).hide();
       jQuery('[data-' + uiType + ']', data).show();
    },
    isEmptyFieldSelected: function (fieldSelect) {
        var selectedOption = fieldSelect.find('option:selected');
        //assumption that empty field will be having value none
        if (selectedOption.val() == 'none') {
           return true;
        }
        return false;
    },
    /**
	 * Function to retrieve the values of the filter
	 * @return : object
	 */
    getFilterValues : function() {
        var thisInstance = this;
        var filterContainer = this.getFilterContainer();

        var fieldList = new Array('columnname', 'comparator', 'value', 'valuetype', 'column_condition');

        var values = {};
        var columnIndex = 0;
        var conditionGroups = jQuery('.conditionGroup', filterContainer);
        conditionGroups.each(function(index,domElement){
            var groupElement = jQuery(domElement);

            var conditions = jQuery('.conditionList .conditionRow',groupElement);
            if(conditions.length <=0) {
                return true;
            }

            var iterationValues = {};
            conditions.each(function(i, conditionDomElement){
                var rowElement = jQuery(conditionDomElement);
                var fieldSelectElement = jQuery('[name="columnname"]', rowElement);
                var valueSelectElement = jQuery('[data-value="value"]',rowElement);
                //To not send empty fields to server
                if(thisInstance.isEmptyFieldSelected(fieldSelectElement)) {
                    return true;
                }
                var fieldDataInfo = fieldSelectElement.find('option:selected').data('fieldinfo');
                var fieldType = fieldDataInfo.type;
                var rowValues = {};
				if (fieldType == 'picklist' || fieldType == 'multipicklist') {
                    for(var key in fieldList) {
                        var field = fieldList[key];
                        if(field == 'value' && valueSelectElement.is('input')) {
                            var commaSeperatedValues = valueSelectElement.val();
                            var pickListValues = valueSelectElement.data('picklistvalues');
                            var valuesArr = commaSeperatedValues.split(',');
                            var newvaluesArr = [];
                            for(i=0;i<valuesArr.length;i++){
                                if(typeof pickListValues[valuesArr[i]] != 'undefined'){
                                    newvaluesArr.push(pickListValues[valuesArr[i]]);
                                } else {
                                    newvaluesArr.push(valuesArr[i]);
                                }
                            }
                            var reconstructedCommaSeperatedValues = newvaluesArr.join(',');
                            rowValues[field] = reconstructedCommaSeperatedValues;
                        } else if(field == 'value' && valueSelectElement.is('select') && fieldType == 'picklist'){
                            rowValues[field] = valueSelectElement.val();
                        } else if(field == 'value' && valueSelectElement.is('select') && fieldType == 'multipicklist'){
                            var value = valueSelectElement.val();
                            if(value == null){
                                rowValues[field] = value;
                            } else {
                                rowValues[field] = value.join(',');
                            }
                        } else {
                            rowValues[field] = jQuery('[name="'+field+'"]', rowElement).val();
                        }
                    }
                 } else {
                    for(var key in fieldList) {
                        var field = fieldList[key];
                        if(field == 'value'){
                            if((fieldType == 'date' || fieldType == 'datetime') && valueSelectElement.length > 0) {
                                var value = valueSelectElement.val();
                                var dateFormat = app.getDateFormat();
                                var dateFormatParts = dateFormat.split("-");
                                var valueArray = value.split(',');
                                for(i = 0; i < valueArray.length; i++) {
                                    var valueParts = valueArray[i].split("-");
                                    var dateInstance = new Date(valueParts[dateFormatParts.indexOf('yyyy')], parseInt(valueParts[dateFormatParts.indexOf('mm')]) - 1, valueParts[dateFormatParts.indexOf('dd')]);
                                    if(!isNaN(dateInstance.getTime())) {
                                        valueArray[i] = app.getDateInVtigerFormat('yyyy-mm-dd', dateInstance);
                                    }
                                }
                                rowValues[field] = valueArray.join(',');
                            } else {
                                rowValues[field] = valueSelectElement.val();
                            }
						}  else {
                            rowValues[field] = jQuery('[name="'+field+'"]', rowElement).val();
                        }
                    }
                }

                if(jQuery('[name="valuetype"]', rowElement).val() == 'false' || (jQuery('[name="valuetype"]', rowElement).length == 0)) {
                    rowValues['valuetype'] = 'rawtext';
                }

                if(index == '0') {
                    rowValues['groupid'] = '0';
                } else {
                    rowValues['groupid'] = '1';
                }

                if(rowElement.is(":last-child")) {
                    rowValues['column_condition'] = '';
                }
                iterationValues[columnIndex] = rowValues;
                columnIndex++;
            });

            if(!jQuery.isEmptyObject(iterationValues)) {
                values[index+1] = {};
                //values[index+1]['columns'] = {};
                values[index+1]['columns'] = iterationValues;
            }
            if(groupElement.find('div.groupCondition').length > 0 && !jQuery.isEmptyObject(values[index+1])) {
                values[index+1]['condition'] = conditionGroups.find('div.groupCondition [name="condition"]').val();
            }
        });
        return values;

    },
    registerSaveEvent: function (container) {
        var thisInstance = this;
        container.on('click', '.saveButton', function (e) {
            app.getSelect2ElementFromSelect(jQuery('#datafields'));
            var error = false;
            var texterr = false;
            var subject = jQuery('[name="subject"]').val();
            if (!subject) {
                error = true;
                texterr = "Field Subject can not be empty!"
            }
            var distance = jQuery('[name="distance"]').val();
            if (!distance) {
                error = true;
                texterr = "Field Period can not be empty!"
            }
            var setype = jQuery('[name="setype"]').val();
            var target = jQuery('[name="target"]').val();
            if (!target) {
                error = true;
                texterr = "Field Target can not be empty!"
            }
            var record = jQuery('[name="record"]').val();
            var description = jQuery('[name="description"]').val();
            var datafields = jQuery("#datafields").val();
            if (!datafields) {
                error = true;
                texterr = "Field Select Field can not be empty!"
            }
            var filterValues = thisInstance.getFilterValues();
            if (jQuery.isEmptyObject(filterValues)) {
                error = true;
                texterr = "You did not select any conditions value!"
            }
            if (setype === "0") {
                error = true;
                texterr = "Field Module can not be empty!"
            }
            var assigned_user = jQuery('[name="assigned_user_id[]"]').val();
            if (error) {
                alert(texterr);
            } else {
                params = {
                    'module': 'VDSimplyKPI',
                    'action': "Save",
                    // 'mode': "saveListviewColor",
                    'subject': subject,
                    'distance': distance,
                    'description': description,
                    'conditions': filterValues,
                    'setype': setype,
                    'target': target,
                    'datafields': datafields,
                    'assigned_user': assigned_user,
                    'record': record
                };
                AppConnector.request(params).then(
                        function (data) {
                            var params;
                            if (data.success == true) {
                                params = {
                                    animation: "show",
                                    type: 'info',
                                    title: app.vtranslate('JS_RECORD_SAVED_SUCCESSFULLY')
                                };
                                Vtiger_Helper_Js.showPnotify(params);
                                document.location = "index.php?module=VDSimplyKPI&view=List&viewname=100";
                            } else {
                                var errorMessage = app.vtranslate(data.error.message);
                                params = {
                                    animation: "show",
                                    type: 'error',
                                    text: errorMessage,
                                    title: app.vtranslate('JS_RECORD_NOT_SAVED')
                                };
                                Vtiger_Helper_Js.showPnotify(params);
                            }
                        },
                        function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                        });
            }


        });
    },
    getContainer: function(){
        return jQuery('#EditView');
    },
    getFilterContainer: function(){
        return jQuery('#advanceFilterContainer');
    },
    
    registerColorPickerEvent : function(container) {
        var colorPickerDiv = container.find('.colorPicker');
        var selectedColorElement = container.find('[name=selectedColor]');
        app.helper.initializeColorPicker(colorPickerDiv, {}, function(hsb, hex, rgb) {
            var selectedColorCode = '#'+hex;
            selectedColorElement.val(selectedColorCode);
        });
        var color = selectedColorElement.val();
        if(!color) {
            color = '#ffffff';
            selectedColorElement.val(color);
        }
        colorPickerDiv.ColorPickerSetColor(color);
    },

    runEdit: function () {
        var thisInstance = this;
        // jQuery('#VDSimplyKPI_editView_fieldName_setype').on('change', function (e) {
            var currentElement = jQuery("#VDSimplyKPI_editView_fieldName_setype").val();
            var params = {
                'module': 'VDSimplyKPI',
                'view': 'EditAjax',
                'mode': 'getWorkflowConditions',
                'record': jQuery("input[name='record']").val(),
                'module_name': currentElement
            };

            app.helper.showProgress();
            app.request.get({data: params}).then(function (error, data) {
                app.helper.hideProgress();
                jQuery('#workflow_condition').html(data);
                var advanceFilterContainer = jQuery('#advanceFilterContainer');
                vtUtils.applyFieldElementsView(jQuery('#workflow_condition'));
                thisInstance.advanceFilterInstance = VDSimplyKPI_AdvanceFilter_Js.getInstance(jQuery('#filterContainer', advanceFilterContainer));
                thisInstance.getPopUp(advanceFilterContainer);
                thisInstance.getFieldsData(params.module_name);

                //Action
                // thisInstance.registerActionEvents(jQuery('#workflow_action'));

                app.helper.registerLeavePageWithoutSubmit(jQuery('#workflow_edit'));
            });
        // });
        // jQuery('#module_name').trigger('change');
    },

    registerEvents: function () {
        this.registerEventFilterCondition();
        this.registerSaveEvent(this.getContainer());
        var curRecord = $("#record").val();
        if (curRecord) {
            this.runEdit();
        }
        // this.registerColorPickerEvent($(document));
    }
});

jQuery(document).ready(function () {
    var instance = new VDSimplyKPIEdit_Js();
    instance.registerEvents();
      $(window).bind("beforeunload", function(){ return(false); });
        
    $(".saveButton").click(function(){
      $(window).off('beforeunload');
       window.onbeforeunload = null;
    });

    $('.assigned_user_id').select2({
        placeholder: 'Select user or Group',
        width: '268px'
    });

    /*
     * Функция реализует множественный множественный выбор в поле select без необходимости нажатия Ctrl или Shift
     * В файлах EditView.tpl MultiOwner.tpl Owner.tpl у тега option прописан id="multiply-choice"
     */
    $('option#multiply-choice').mousedown(function(e) {
        e.preventDefault();
        var originalScrollTop = $(this).parent().scrollTop();
        console.log(originalScrollTop);
        $(this).prop('selected', $(this).prop('selected') ? false : true);
        var self = this;
        $(this).parent().focus();
        setTimeout(function() {
            $(self).parent().scrollTop(originalScrollTop);
        }, 0);

        return false;
    });

});

