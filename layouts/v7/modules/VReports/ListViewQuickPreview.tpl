{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{*<div class = "quickPreview">*}
    {*<div class='quick-preview-modal modal-content'>*}
        {*<div class='modal-body'>*}
            {*<div class="quickPreviewModuleHeader row">*}
                {*<div class = "col-lg-10">*}
                    {*<div class="row qp-heading">*}
                        {*<div class="col-lg-6 col-md-6 col-sm-6">*}
                            {*<div class="record-header clearfix">*}
                                {*<div class="hidden-sm hidden-xs recordImage">*}
                                    {*<div class="name"><span class='fa fa-bar-chart'></span></div>*}
                                {*</div>*}
                                {*<div class="recordBasicInfo">*}
                                    {*<div class="info-row">*}
                                        {*<h4>*}
                                            {*<span class="recordLabel pushDown" title="">*}
                                                {*{$REPORT_MODEL->get('reportname')}*}
                                            {*</span>*}
                                        {*</h4>*}
                                    {*</div>*}
                                {*</div>*}
                            {*</div>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}
                {*<div class = "col-lg-2 pull-right">*}
                    {*<button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>*}
                {*</div>*}
            {*</div>*}
            {*<div class="quickPreviewActions clearfix">*}
                {*<div class="btn-group pull-left">*}
                {*</div>*}
            {*</div>*}
            {*<div class="quickPreviewSummary">*}
                {*<input type='hidden' name='charttype' value="{$CHART_TYPE}" />*}
                {*<input type='hidden' name='data' value='{Vtiger_Functions::jsonEncode($DATA)}' />*}
                {*<input type='hidden' name='clickthrough' value="{$CLICK_THROUGH}" />*}
                {*<br>*}
                {*<div style="margin:0px 20px;">*}
                    {*<div class='border1px' style="padding:30px;">*}
                        {*<div id='chartcontent' name='chartcontent' style="min-height:400px;" data-mode='Reports'></div>*}
                        {*<br>*}
                    {*</div>*}
                {*</div>*}
                {*<br>*}
            {*</div>*}
            {*<br>*}
        {*</div>*}
    {*</div>*}
{*</div>*}
{*{if $CHART_TYPE eq 'pieChart'}*}
    {*{assign var=CLASS_NAME value='Report_Piechart_Js'}*}
{*{else if $CHART_TYPE eq 'verticalbarChart'}*}
    {*{assign var=CLASS_NAME value='Report_Verticalbarchart_Js'}*}
{*{else if $CHART_TYPE eq 'horizontalbarChart'}*}
    {*{assign var=CLASS_NAME value='Report_Horizontalbarchart_Js'}*}
{*{else}*}
    {*{assign var=CLASS_NAME value='Report_Linechart_Js'}*}
{*{/if}*}

{*<script type="text/javascript">*}
    {*{$CLASS_NAME}('Vtiger_ChartReportWidget_{$RECORD_ID}',{}, {*}
        {*init: function () {*}
            {*this._super(jQuery(".quickPreviewSummary"));*}
        {*}*}
    {*});*}

    {*var i = new Vtiger_ChartReportWidget_{$RECORD_ID}();*}
    {*jQuery('.quickPreviewSummary').trigger(Vtiger_Widget_Js.widgetPostLoadEvent);*}
{*</script>*}

<div class = "quickPreview">
    <input type="hidden" name="sourceModuleName" id="sourceModuleName" value="{$MODULE_NAME}" />
    <input type="hidden" id = "nextRecordId" value ="{$NEXT_RECORD_ID}">
    <input type="hidden" id = "previousRecordId" value ="{$PREVIOUS_RECORD_ID}">

    <div class='quick-preview-modal modal-content'>
        <div class='modal-body'>
            <div class = "quickPreviewModuleHeader row">
                <div class = "col-lg-10">
                    <div class="row qp-heading">
                        {include file="ListViewQuickPreviewHeaderTitle.tpl"|vtemplate_path:$MODULE_NAME MODULE_MODEL=$MODULE_MODEL RECORD=$RECORD}
                    </div>
                </div>
                <div class = "col-lg-2 pull-right">
                    <button class="close" aria-hidden="true" data-dismiss="modal" type="button" title="{vtranslate('LBL_CLOSE')}">x</button>
                </div>
            </div>

            <div class="quickPreviewActions clearfix">
                <div class="btn-group pull-left">
                    <button class="btn btn-success btn-xs" onclick="window.open('{$RECORD->getFullDetailViewUrl()}&app={$SELECTED_MENU_CATEGORY}')">
                        {vtranslate('Open', $MODULE_NAME)}
                    </button>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class="btn-group">
                    <button class="btn btn-xs" onclick="window.open('{$RECORD->getEditViewUrl()}&app={$SELECTED_MENU_CATEGORY}')">
                        {vtranslate('LBL_EDIT', $MODULE_NAME)}
                    </button>
                </div>
                {if $NAVIGATION}
                    <div class="btn-group pull-right">
                        <button class="btn btn-default btn-xs" id="quickPreviewPreviousRecordButton" data-record="{$PREVIOUS_RECORD_ID}" data-app="{$SELECTED_MENU_CATEGORY}" {if empty($PREVIOUS_RECORD_ID)} disabled="disabled" {*{else} onclick="Vtiger_List_Js.triggerPreviewForRecord({$PREVIOUS_RECORD_ID})"*}{/if} >
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-default btn-xs" id="quickPreviewNextRecordButton" data-record="{$NEXT_RECORD_ID}" data-app="{$SELECTED_MENU_CATEGORY}" {if empty($NEXT_RECORD_ID)} disabled="disabled" {*{else} onclick="Vtiger_List_Js.triggerPreviewForRecord({$NEXT_RECORD_ID})"*}{/if}>
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                {/if}

            </div>
            <div class = "quickPreviewSummary">
                <table class="summary-table no-border" style="width:100%;">
                    <tbody>
                    {foreach item=FIELD_MODEL key=FIELD_NAME from=$SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']}
                        {if $FIELD_MODEL->get('name') neq 'modifiedtime' && $FIELD_MODEL->get('name') neq 'createdtime'}
                            <tr class="summaryViewEntries">
                                <td class="fieldLabel col-lg-5" ><label class="muted">{vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}</label></td>
                                <td class="fieldValue col-lg-7">
                                    <div class="row">
                                            <span class="value textOverflowEllipsis" {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '20' or $FIELD_MODEL->get('uitype') eq '21'}style="word-wrap: break-word;"{/if}>
                                                {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
                                            </span>
                                    </div>
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                    </tbody>
                </table>
            </div>

            <div class="engagementsContainer">
                {include file="ListViewQuickPreviewSectionHeader.tpl"|vtemplate_path:$MODULE_NAME TITLE="{vtranslate('LBL_UPDATES',$MODULE_NAME)}"}
                {include file="RecentActivities.tpl"|vtemplate_path:$MODULE_NAME}
            </div>

            <br>
            {if $MODULE_MODEL->isCommentEnabled()}
                <div class="quickPreviewComments">
                    {include file="ListViewQuickPreviewSectionHeader.tpl"|vtemplate_path:$MODULE_NAME TITLE="{vtranslate('LBL_RECENT_COMMENTS',$MODULE_NAME)}"}
                    {include file="QuickViewCommentsList.tpl"|vtemplate_path:$MODULE_NAME}
                </div>
            {/if}
        </div>
    </div>
</div>