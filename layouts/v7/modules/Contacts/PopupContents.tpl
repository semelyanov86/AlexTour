{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************}
{strip}
    <div class="row">

        <div class="col-lg-10">


        </div>
    </div>
    {include file="PicklistColorMap.tpl"|vtemplate_path:$VD_MODULE}
    <div class="row lookup-item-popup-navigation">
        {include file='PopupNavigation.tpl'|vtemplate_path:$VD_MODULE}
    </div>
    <div class="row lockup-item-main" style="overflow-x: auto;height: 485px;">
        <div class="col-md-12">
            <input type='hidden' id='pageNumber' value="{$PAGE_NUMBER}">
            <input type='hidden' id='pageLimit' value="{$PAGING_MODEL->getPageLimit()}">
            <input type="hidden" id="noOfEntries" value="{$LISTVIEW_ENTRIES_COUNT}">
            <input type="hidden" id="pageStartRange" value="{$PAGING_MODEL->getRecordStartRange()}" />
            <input type="hidden" id="pageEndRange" value="{$PAGING_MODEL->getRecordEndRange()}" />
            <input type="hidden" id="previousPageExist" value="{$PAGING_MODEL->isPrevPageExists()}" />
            <input type="hidden" id="nextPageExist" value="{$PAGING_MODEL->isNextPageExists()}" />
            <input type="hidden" id="totalCount" value="{$LISTVIEW_COUNT}" />
            <input type="hidden" value="{Vtiger_Util_Helper::toSafeHTML(Zend_JSON::encode($SEARCH_DETAILS))}" id="currentSearchParams" />
            <input type="hidden" value="{$ALL_FILTER_RECORD_ID}" id="all_filter_record_id" />
            <div class="contents-topscroll">
                <div class="topscroll-div popupEntriesDivTopScroll">&nbsp;</div>
            </div>
            <div class="iTL-popupEntriesDiv relatedContents" style="min-height: 450px; height: 100%; {if count($LISTVIEW_ENTRIES) > 0}overflow-y: auto;{/if}">
                <input type="hidden" value="{$ORDER_BY}" id="orderBy">
                <input type="hidden" value="{$SORT_ORDER}" id="sortOrder">

                {assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
                <div class="popupEntriesTableContainer">
                    <table style="border-top: 1px solid #eee;" class="table listview-table table-bordered listViewEntriesTable iTL-listViewEntriesTable">
                        <thead style="display: block;">
                        <tr class="listViewHeaders">
                            {if $MULTI_SELECT}
                                <th class="{$WIDTHTYPE}" style="width: 90px !important;min-width: 90px !important;">
                                    <input type="checkbox"  class="selectAllInCurrentPage hide" />
                                </th>
                            {elseif $MODULE neq 'EmailTemplates'}
                                <th class="{$WIDTHTYPE}">&nbsp;</th>
                            {/if}

                            {foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
                                <th class="{$WIDTHTYPE}" style="width: 150px !important;min-width: 150px !important;">
                                    <a href="javascript:void(0);" class="listViewContentHeaderValues listViewHeaderValues {if $LISTVIEW_HEADER->get('name') eq 'listprice'} noSorting {/if}" data-nextsortorderval="{if $ORDER_BY eq $LISTVIEW_HEADER->get('name')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-columnname="{$LISTVIEW_HEADER->get('name')}">
                                        {if $ORDER_BY eq $LISTVIEW_HEADER->get('name')}
                                            <i class="fa fa-sort {$FASORT_IMAGE}"></i>
                                        {else}
                                            <i class="fa fa-sort customsort"></i>
                                        {/if}
                                        &nbsp;{vtranslate($LISTVIEW_HEADER->get('label'), $MODULE)|strip_tags|truncate:18}&nbsp;
                                    </a>
                                </th>
                            {/foreach}
                        </tr>
                        {if $MODULE_MODEL && $MODULE_MODEL->isQuickSearchEnabled() && false}
                            <tr class="searchRow">
                                <th class="textAlignCenter" style="width: 90px !important;min-width: 90px !important;">
                                    <button class="btn btn-success" data-trigger="PopupListSearch">{vtranslate('LBL_SEARCH', $MODULE )}</button>
                                </th>
                                <th style="width: 51px !important;min-width: 51px !important;"></th>
                                <th style="width: 91px !important;min-width: 91px !important;"></th>
                                {if $MODULE eq 'Products' && $CONFIGURE['product_show_picture_column'] == 1}
                                    <th style="width: 100px !important;min-width: 100px !important;"></th>
                                {/if}
                                {foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
                                    <th style="width: 50px !important;min-width: 50px !important;">
                                        {assign var=FIELD_UI_TYPE_MODEL value=$LISTVIEW_HEADER->getUITypeModel()}
                                        {include file=vtemplate_path($FIELD_UI_TYPE_MODEL->getListSearchTemplateName(),$MODULE_NAME)
                                        FIELD_MODEL= $LISTVIEW_HEADER SEARCH_INFO=$SEARCH_DETAILS[$LISTVIEW_HEADER->getName()] USER_MODEL=$CURRENT_USER_MODEL}
                                    </th>
                                {/foreach}
                            </tr>
                        {/if}
                        {if $MODULE_MODEL && $MODULE_MODEL->isQuickSearchEnabled()}
                            <tr class="searchRow">
                                <th class="textAlignCenter" style="width: 90px !important;min-width: 90px !important;">
                                    <button class="btn btn-success" data-trigger="PopupListSearch">{vtranslate('LBL_SEARCH', $MODULE )}</button>
                                </th>
                                {if $MODULE eq 'Products' && $CONFIGURE['product_show_picture_column'] == 1}
                                    <th style="width: 100px !important;min-width: 100px !important;"></th>
                                {/if}
                                {foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
                                    <th style="width: 50px !important;min-width: 50px !important;">
                                        {assign var=FIELD_UI_TYPE_MODEL value=$LISTVIEW_HEADER->getUITypeModel()}
                                        {include file=vtemplate_path($FIELD_UI_TYPE_MODEL->getListSearchTemplateName(),$MODULE_NAME)
                                        FIELD_MODEL= $LISTVIEW_HEADER SEARCH_INFO=$SEARCH_DETAILS[$LISTVIEW_HEADER->getName()] USER_MODEL=$CURRENT_USER_MODEL}
                                    </th>
                                {/foreach}
                            </tr>
                        {/if}
                        </thead>
                        {if count($LISTVIEW_ENTRIES) == 0}
                        {else}
                            <tbody style="display: block;  overflow-x: hidden; overflow-y: auto;">
                            {foreach item=LISTVIEW_ENTRY from=$LISTVIEW_ENTRIES name=popupListView}
                                {assign var="RECORD_DATA" value="{$LISTVIEW_ENTRY->getRawData()}"}
                                {assign var="RECORD_MODEL" value=Vtiger_Record_Model::getInstanceById($LISTVIEW_ENTRY->getId())}
                                {assign var="RECORD_ID" value="{$LISTVIEW_ENTRY->getId()}"}
                                <tr class="itemLookUp-listViewEntries" style="cursor: pointer;" data-section-field-value="{if $MODULE == 'Products'}{$RECORD_MODEL->get($QUOTER_SECTION_VALUE['product'])}{else}{$RECORD_MODEL->get($QUOTER_SECTION_VALUE['service'])}{/if}" data-id="{$LISTVIEW_ENTRY->getId()}" {if $MODULE eq 'EmailTemplates'} data-name="{$RECORD_DATA['subject']}" data-info="{$LISTVIEW_ENTRY->get('body')}" {else} data-name="{$LISTVIEW_ENTRY->getName()}" data-info='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($LISTVIEW_ENTRY->getRawData()))}' {/if}
                                        {if $GETURL neq ''} data-url='{$LISTVIEW_ENTRY->$GETURL()}' {/if}  id="{$MODULE}_popUpListView_row_{$smarty.foreach.popupListView.index+1}">
                                    {if $MULTI_SELECT}
                                        <td style="border-bottom: 1px solid #ddd !important;border-right: 1px solid #ddd !important;padding: 0px 5px 0px 5px; width: 90px !important;min-width: 90px !important; text-align: center;" class="{$WIDTHTYPE}">
                                            {assign var="ARR_BUNDLES" value=array()}
                                            {if $MODULE eq 'Products' && count($LISTVIEW_ENTRY->getSubProducts()) > 0}
                                                {assign var="SECTION_VALUE" value=$QUOTER_SECTION_VALUE['product']}
                                                {if $MODULE == 'Services'}
                                                    {assign var="SECTION_VALUE" value=$QUOTER_SECTION_VALUE['service']}
                                                {/if}
                                                {foreach key=PRDT_ID item=OBJ_PRDT from=$LISTVIEW_ENTRY->getSubProducts()}
                                                    {append var='ARR_BUNDLES' value=['id'=>$PRDT_ID,'qty'=>$OBJ_PRDT->get('quantityInBundle'),'unit_price'=>$OBJ_PRDT->get('unit_price'),'section_value'=>{decode_html($OBJ_PRDT->get($SECTION_VALUE))}]}
                                                {/foreach}
                                            {/if}
                                            <input class="entryCheckBox hide" type="checkbox" /><button class="vdLookUpAddAnItem" type="button" data-action="add" data-record-id="{$LISTVIEW_ENTRY->getId()}" data-item-module="{$MODULE}" data-item-value="{$LISTVIEW_ENTRY->getName()}" class="btn btn-default btn-xs">Add</button> <input type="hidden" value="add" class="action">
                                        </td>
                                    {elseif $MODULE neq 'EmailTemplates'}
                                        <td style="border-bottom: 1px solid #ddd !important;border-right: 1px solid #ddd !important;padding: 0px 5px 0px 5px; "></td>
                                    {/if}

                                    {foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
                                        {assign var=LISTVIEW_HEADERNAME value=$LISTVIEW_HEADER->get('name')}
                                        {assign var=LISTVIEW_ENTRY_VALUE value=$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
                                        <td style="border-bottom: 1px solid #ddd !important;border-right: 1px solid #ddd !important;padding: 0px 5px 0px 5px; width: 150px !important;min-width: 150px !important;" class="listViewEntryValue value textOverflowEllipsis {$WIDTHTYPE}" title="{$RECORD_DATA[$LISTVIEW_HEADERNAME]}">
                                            {if $LISTVIEW_HEADER->isNameField() eq true or $LISTVIEW_HEADER->get('uitype') eq '4'}
                                                <a href="index.php?module=Products&view=Detail&record={$RECORD_ID}" target="_blank">{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)|strip_tags|truncate:18}</a>&nbsp; <a class="vtePrdtBundle" data-module="Products" data-bundles="{$RECORD_ID}"> {if $MODULE eq 'Products' && count($LISTVIEW_ENTRY->getSubProducts()) > 0} <i style="font-size: 12px;line-height: 1;" class="vicon-inventory icon-module" data-info="\e639"></i>{/if}</a>
                                            {else if $LISTVIEW_HEADER->get('uitype') eq '72'}
                                                {assign var=CURRENCY_SYMBOL_PLACEMENT value={$CURRENT_USER_MODEL->get('currency_symbol_placement')}}
                                                {if $CURRENCY_SYMBOL_PLACEMENT eq '1.0$'}
                                                    {$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}{$LISTVIEW_ENTRY->get('currencySymbol')}
                                                {else}
                                                    {$LISTVIEW_ENTRY->get('currencySymbol')}{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
                                                {/if}
                                            {else if $LISTVIEW_HEADERNAME eq 'listprice'}
                                                {CurrencyField::convertToUserFormat($LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME), null, true, true)}
                                            {else if $LISTVIEW_HEADER->getFieldDataType() eq 'picklist'}
                                                <span {if !empty($LISTVIEW_ENTRY_VALUE)} class="picklist-color picklist-{$LISTVIEW_HEADER->getId()}-{Vtiger_Util_Helper::convertSpaceToHyphen($LISTVIEW_ENTRY->getRaw($LISTVIEW_HEADERNAME))}" {/if}> {$LISTVIEW_ENTRY_VALUE} </span>
                                            {else if $LISTVIEW_HEADER->getFieldDataType() eq 'multipicklist'}
                                                {assign var=MULTI_RAW_PICKLIST_VALUES value=explode('|##|',$LISTVIEW_ENTRY->getRaw($LISTVIEW_HEADERNAME))}
                                                {assign var=MULTI_PICKLIST_VALUES value=explode(',',$LISTVIEW_ENTRY_VALUE)}
                                                {foreach item=MULTI_PICKLIST_VALUE key=MULTI_PICKLIST_INDEX from=$MULTI_RAW_PICKLIST_VALUES}
                                                    <span {if !empty($LISTVIEW_ENTRY_VALUE)} class="picklist-color picklist-{$LISTVIEW_HEADER->getId()}-{Vtiger_Util_Helper::convertSpaceToHyphen(trim($MULTI_PICKLIST_VALUE))}" {/if}> {trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX])} </span>
                                                {/foreach}
                                            {else}
                                                {$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)|strip_tags|truncate:18}
                                            {/if}
                                        </td>
                                    {/foreach}
                                </tr>
                            {/foreach}
                            </tbody>
                        {/if}

                    </table>
                </div>

                <!--added this div for Temporarily -->
                {if $LISTVIEW_ENTRIES_COUNT eq '0'}
                    <div class="row">
                        <div class="emptyRecordsDiv">
                            {if $IS_MODULE_DISABLED eq 'true'}
                                {vtranslate($RELATED_MODULE, $RELATED_MODULE)}
                                {vtranslate('LBL_MODULE_DISABLED', $RELATED_MODULE)}
                            {else}
                                {if count($LISTVIEW_ENTRIES) == 0 && $PRODUCT_BUNDLES == 1}
                                    Please select a bundle
                                {else}
                                    {vtranslate('LBL_NO', $MODULE)} {vtranslate($RELATED_MODULE, $RELATED_MODULE)} {vtranslate('LBL_FOUND', $MODULE)}.
                                {/if}

                            {/if}
                        </div>
                    </div>
                {/if}
                {if $FIELDS_INFO neq null}
                    <script type="text/javascript">
                        var popup_uimeta = (function() {
                            var fieldInfo  = {$FIELDS_INFO};
                            return {
                                field: {
                                    get: function(name, property) {
                                        if(name && property === undefined) {
                                            return fieldInfo[name];
                                        }
                                        if(name && property) {
                                            return fieldInfo[name][property]
                                        }
                                    },
                                    isMandatory : function(name){
                                        if(fieldInfo[name]) {
                                            return fieldInfo[name].mandatory;
                                        }
                                        return false;
                                    },
                                    getType : function(name){
                                        if(fieldInfo[name]) {
                                            return fieldInfo[name].type
                                        }
                                        return false;
                                    }
                                },
                            };
                        })();
                    </script>
                {/if}
            </div>
        </div>
    </div>
{/strip}