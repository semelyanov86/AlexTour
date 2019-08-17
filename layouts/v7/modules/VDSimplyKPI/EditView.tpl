{*+***********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}
<style>
    #add_more{
        display: inline;
        float: right;
        margin-right: 34px;
        margin-top: 7px;
    }
    .symbol{
        width: 30px;
        display: inline;
        text-align: center;
    }
    .alignTop1{
        margin-left: -8px;
    }
    .d_symbol{
        width: 330px;
    }
</style>
{strip}
{*<script type="text/javascript" src="libraries/jquery/colorpicker/js/colorpicker.js"></script>*}
{*<link type="text/css" rel="stylesheet" href="libraries/jquery/colorpicker/css/colorpicker.css"/>*}
    {*<div class="editViewPageDiv">*}
      {*<div class="col-sm-12 col-xs-12" id="EditView">*}
    <div class="main-container clearfix">
    <div id="modnavigator" class="module-nav editViewModNavigator">
        <div class="hidden-xs hidden-sm mod-switcher-container">
            {*{include file="partials/Menubar.tpl"|vtemplate_path:$MODULE}*}
        </div>
    </div>
    <div class="editViewPageDiv viewContent">
        <div class="col-sm-12 col-xs-12 content-area {if $LEFTPANELHIDE eq '1'} full-width {/if}">
         <form name="EditWorkflow" action="index.php" method="post" id="EditView" class="form-horizontal">
            {*{assign var=WORKFLOW_MODEL_OBJ value=$WORKFLOW_MODEL->getWorkflowObject()}*}
             <input type="hidden" name="module" value="{$MODULE}"/>
             <input type="hidden" name="view" value="Edit"/>
             <input type="hidden" name="step" value="1"/>
             <input type="hidden" name="mode" value="step2"/>
             <input type="hidden" name="record" value="{$RECORD_ID}" id="record"/>
            <input type="hidden" name="action" value="Save" />
            <input type="hidden" name="returnsourcemodule" value="{$RETURN_SOURCE_MODULE}" />
            <input type="hidden" name="returnpage" value="{$RETURN_PAGE}" />
            <input type="hidden" name="returnsearch_value" value="{$RETURN_SEARCH_VALUE}" />
             {foreach key=NAME item=VALUE from=$FIELDS}
                 {if $NAME eq 'advanced_filter' or $NAME eq 'datafields' or $NAME eq 'result' or $NAME eq 'procent' or $NAME eq 'assigned_user_id'} {continue}{/if}
                 <input type="hidden" name="{$NAME}" value="{$VALUE}"/>
             {/foreach}
             <input type="hidden" name="assigned_user_id" value="{$assigned_user_id}"/>
             <input type="hidden" name="date_filters" data-value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATE_FILTERS))}' />
             <input type="hidden" name="advanced_filter" id="advanced_filter" value="" />
             <input type="hidden" name='datafields' value={$DATAFIELS}>

            <div class="editViewHeader">
               <div class='row'>
                  <div class="col-lg-12 col-md-12 col-lg-pull-0">
                     <h4>{vtranslate('LBL_BASIC_INFORMATION', $QUALIFIED_MODULE)}</h4>
                  </div>
               </div>
            </div>
            <hr style="margin-top: 0px !important;">
            <div class="editViewBody">
                <div class="editViewContents" style="text-align: center; ">

                        {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
                            {foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
                                {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                                {if $FIELD_NAME eq 'advanced_filter' or $FIELD_NAME eq 'result' or $FIELD_NAME eq 'netto' or $FIELD_NAME eq 'datafields' or $FIELD_NAME eq 'procent' or $FIELD_NAME eq 'typevdkpi' or $FIELD_NAME eq 'date_off' or $FIELD_NAME eq 'number_kpi' or $FIELD_NAME eq 'createnewperiod'}{continue}{/if}
                                <div class="form-group">
                                    {if $isReferenceField neq "reference"}<label class="col-sm-2 control-label">{/if}

                                        {if $isReferenceField eq "reference"}
                                            {assign var="REFERENCE_LIST" value=$FIELD_MODEL->getReferenceList()}
                                            {assign var="REFERENCE_LIST_COUNT" value=count($REFERENCE_LIST)}
                                            {if $REFERENCE_LIST_COUNT > 1}
                                                {assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
                                                {assign var="REFERENCED_MODULE_STRUCT" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
                                                {if !empty($REFERENCED_MODULE_STRUCT)}
                                                    {assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCT->get('name')}
                                                {/if}

                                                {if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
                                                <select id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->getName()}_dropDown" class="chzn-select select2 referenceModulesList streched" style="width:160px;">
                                                    <optgroup>
                                                        {foreach key=index item=value from=$REFERENCE_LIST}
                                                            <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if}>{vtranslate($value, $MODULE)}</option>
                                                        {/foreach}
                                                    </optgroup>
                                                </select>

                                            {else}
                                                <label class="col-sm-2 control-label">{vtranslate($FIELD_MODEL->get('label'), $MODULE)}{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}</label>
                                            {/if}
                                        {else if $FIELD_MODEL->get('uitype') eq "83"}
                                            {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE}
                                        {else}
                                            {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                        {/if}
                                        {if $isReferenceField neq "reference"}{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}</label>{/if}

                                    <div class="col-sm-2 controls">
                                        <div class="span8 row-fluid">
                                            {if $FIELD_NAME eq 'setype'}

                                                <select id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->getName()}" class="chzn-select inputElement select2" {if $MODE eq 'edit'}disabled{/if} name="{$FIELD_NAME}">
                                                    {if $FIELD_MODEL->get('fieldvalue')}
                                                        <option id="multiply-choice" value="{$FIELD_MODEL->get('fieldvalue')}" selected>{vtranslate($FIELD_MODEL->get('fieldvalue'),$FIELD_MODEL->get('fieldvalue'))}</option>
                                                    {else}
                                                        <option id="multiply-choice" value="0">{vtranslate('LBL_PLEASE_SELECT_MODULE', $QUALIFIED_MODULE)}</option>
                                                        {foreach key=index item=value from=$MODULELIST}
                                                            <option id="multiply-choice" value="{$index}">{vtranslate($index, $index)}</option>
                                                        {/foreach}
                                                    {/if}
                                                </select>


                                            {else if $FIELD_NAME eq 'assigned_user_id' and $MODE neq 'edit'}
                                                {include file=vtemplate_path('uitypes/MultiOwner.tpl',$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}

                                            {else}

                                                {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            {/foreach}
                        {/foreach}
                </div>

            </div>

            <div id="workflow_condition">
            </div>
			<div class="modal-overlay-footer clearfix">
				<div class="row clearfix">
					<div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
						<button type='button' class='btn btn-success saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
						<a class='cancelLink' href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					</div>
				</div>
			</div>
         </form>
      </div>
     </div>
    </div>
{/strip}