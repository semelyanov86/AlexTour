{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************}
{* modules/Vtiger/views/Popup.php *}

{strip}
    <style>
        body::-webkit-scrollbar {
            width: 1px;
        }
        body::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        }
        body::-webkit-scrollbar-thumb {
            background-color: darkgrey;
            outline: 1px solid slategrey;
        }
    </style>
    <div id="ContactsPopupModal" class="modal-dialog modal-lg" style="width: 99%;">
        <div class="modal-content">
            {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE={vtranslate($VTE_MODULE,$VTE_MODULE)}}
            <div class="modal-body" style="">
                <div class="row">
                    <div class="col-lg-12" id="ContactsPopupContainer">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="popupPageContainer" class="contentsDiv">
                                    <input type="hidden" id="module" value="{$MODULE}"/>
                                    <input type="hidden" id="vd_module" value="{$VD_MODULE}"/>
                                    <input type="hidden" id="parent" value="{$PARENT_MODULE}"/>
                                    <input type="hidden" id="element_id" value="{$ELEMENT_ID}"/>
                                    <input type="hidden" id="multi_select" value="{$MULTI_SELECT}" />
                                    {*                                    <input type="hidden" id="relatedParentModule" value="{$RELATED_PARENT_MODULE}"/>*}
                                    {*                                    <input type="hidden" id="relatedParentId" value="{$RELATED_PARENT_ID}"/>*}
                                    <input type="hidden" id="view" name="view" value="{$VIEW}"/>
                                    {*                                    <input type="hidden" id="relationId" value="{$RELATION_ID}" />*}
                                    <input type="hidden" id="selectedIds" name="selectedIds">
                                    <input type="hidden" id="decimalSeparator" value="{$DECIMAL_SEPARATOR}" name="decimalSeparator">
                                    <input type="hidden" id="digitGroupingSeparator" value="{$DIGIT_GROUPING_SEPARATOR}" name="digitGroupingSeparator">

                                    <div id="popupContents" style="min-height: 500px;" class="">
                                        {include file='MPopupContents.tpl'|vtemplate_path:$VD_MODULE}
                                    </div>
                                    <input type="hidden" class="triggerEventName" value="{$smarty.request.triggerEventName}"/>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
{/strip}