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
{strip}
	{*{$ADVANCE_CRITERIA|@debug_print_var}*}
	{assign var=ALL_CONDITION_CRITERIA value=$ADVANCE_CRITERIA[1] }
	{assign var=ANY_CONDITION_CRITERIA value=$ADVANCE_CRITERIA[2] }

	{if empty($ALL_CONDITION_CRITERIA) }
		{assign var=ALL_CONDITION_CRITERIA value=array()}
	{/if}

	{if empty($ANY_CONDITION_CRITERIA) }
		{assign var=ANY_CONDITION_CRITERIA value=array()}
	{/if}
<div id="filterContainer" class="filterContainer">
	<input type="hidden" name="date_filters" data-value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATE_FILTERS))}' />
	<input type=hidden name="advanceFilterOpsByFieldType" data-value='{ZEND_JSON::encode($ADVANCED_FILTER_OPTIONS_BY_TYPE)}' />
	{foreach key=ADVANCE_FILTER_OPTION_KEY item=ADVANCE_FILTER_OPTION from=$ADVANCED_FILTER_OPTIONS}
		{$ADVANCED_FILTER_OPTIONS[$ADVANCE_FILTER_OPTION_KEY] = vtranslate($ADVANCE_FILTER_OPTION, $MODULE)}
	{/foreach}
	<input type=hidden name="advanceFilterOptions" data-value='{ZEND_JSON::encode($ADVANCED_FILTER_OPTIONS)}' />
    <div class="allConditionContainer conditionGroup contentsBackground" style="padding-bottom:15px;">
        <div class="header">
			<span><strong>{vtranslate('LBL_ALL_CONDITIONS',$MODULE)}</strong></span>
			&nbsp;
			<span>({vtranslate('LBL_ALL_CONDITIONS_DESC',$MODULE)})</span>
		</div>
        <br>
		<div class="contents">
			<div class="conditionList">
			 {foreach item=CONDITION_INFO from=$ALL_CONDITION_CRITERIA['columns']}
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=$CONDITION_INFO MODULE=$MODULE}
			{/foreach}
			{if count($ALL_CONDITION_CRITERIA) eq 0}
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array()}
			{/if}
			</div>
			<div class="hide basic">
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=array() MODULE=$MODULE NOCHOSEN=true}
			</div>
            <br>
            <div class="addCondition">
				<button type="button" class="btn btn-default">{vtranslate('LBL_ADD_CONDITION',$MODULE)}</button>
			</div>
			<div class="groupCondition">
				{assign var=GROUP_CONDITION value=$ALL_CONDITION_CRITERIA['condition']}
				{if empty($GROUP_CONDITION)}
					{assign var=GROUP_CONDITION value="and"}
				{/if}
				<input type="hidden" name="condition" value="{$GROUP_CONDITION}" />
			</div>
		</div>
	</div>
    <br>
    <br>
	<div class="anyConditionContainer conditionGroup contentsBackground">
		<div class="header">
			<span><strong>{vtranslate('LBL_ANY_CONDITIONS',$MODULE)}</strong></span>
			&nbsp;
			<span>({vtranslate('LBL_ANY_CONDITIONS_DESC',$MODULE)})</span>
		</div>
        <br>
		<div class="contents">
			<div class="conditionList">
			{foreach item=CONDITION_INFO from=$ANY_CONDITION_CRITERIA['columns']}
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=$CONDITION_INFO MODULE=$MODULE CONDITION="or"}
			{/foreach}
			{if count($ANY_CONDITION_CRITERIA) eq 0}
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array() CONDITION="or"}
			{/if}
			</div>
			<div class="hide basic">
				{include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array() CONDITION="or" NOCHOSEN=true}
			</div>
            <br>
			<div class="addCondition">
				<button type="button" class="btn  btn-default">{vtranslate('LBL_ADD_CONDITION',$MODULE)}</button>
			</div>
		</div>
	</div>
	<br>
    <br>
	<div class="conditionsContainer padding1per">
		<h5 class="padding-bottom1per"><strong>{vtranslate('LBL_SELECT_DATA_FIELD', $MODULE)}</strong><span class="redColor">*</span><h5>
				<div class="span6" >
					<div class="row-fluid">
						<select id='datafields' name='datafields' class="chzn-select select2 validate[required]" data-validation-engine="validate[required]" style='min-width:300px;' {if $MODE eq 'edit'}disabled{/if} >
{*							<option value='count(*)'>{vtranslate('LBL_RECORD_COUNT', $MODULE)}</option>
							{foreach key=CALCULATION_FIELDS_MODULE_LABEL item=CALCULATION_FIELDS_MODULE from=$CALCULATION_FIELDS}
								<optgroup label="{vtranslate($CALCULATION_FIELDS_MODULE_LABEL, $CALCULATION_FIELDS_MODULE_LABEL)}">
									{foreach key=CALCULATION_FIELD_KEY item=CALCULATION_FIELD_TRANSLATED_LABEL from=$CALCULATION_FIELDS_MODULE[$MODULE]}
										<option value="{$CALCULATION_FIELD_KEY}" {if $CALCULATION_FIELD_KEY eq $DATAFIELS} selected {/if}>{$CALCULATION_FIELD_TRANSLATED_LABEL}</option>
									{/foreach}
								</optgroup>
							{/foreach}*}
						</select>
					</div><br />
				</div>
	</div>
</div>
{/strip}