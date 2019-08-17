{*<!--
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: Vordoom.net
 * The Initial Developer of the Original Code is Vordoom.net.
 * All Rights Reserved.
 * If you have any questions or comments, please email: support@vordoom.net
 ************************************************************************************/
 -->*}

<form id="VDSimplyKPI_step2" name="install" method="POST" action="index.php" class="form-horizontal">
	<input type="hidden" name="module" value="{$MODULE}"/>
	<input type="hidden" name="action" value="Save"/>
	{foreach key=NAME item=VALUE from=$FIELDS}
		{if $NAME eq 'advanced_filter' or $NAME eq 'datafields' or $NAME eq 'result' or $NAME eq 'procent' or $NAME eq 'assigned_user_id'} {continue}{/if}
		<input type="hidden" name="{$NAME}" value="{$VALUE}"/>
	{/foreach}
	<input type="hidden" name="assigned_user_id" value="{$assigned_user_id}"/>
	<input type="hidden" name="record" value="{$RECORD_ID}"/>
	<input type="hidden" name="date_filters" data-value='{Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATE_FILTERS))}' />
	<input type="hidden" name="advanced_filter" id="advanced_filter" value="" />
	<input type="hidden" name='datafields' value={$DATAFIELS}>

	<div  class="padding1per" style="border:1px solid #ccc;" >
		{assign var=RECORD_STRUCTURE value=array()}
		{assign var=MODULE_LABEL value=vtranslate($setype, $setype)}
		{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$MODULE_RECORD_STRUCTURE}
			{assign var=MODULE_BLOCK_LABEL value=vtranslate($BLOCK_LABEL, $setype)}
			{assign var=key value="$MODULE_LABEL $MODULE_BLOCK_LABEL"}
			{if $LINEITEM_FIELD_IN_CALCULATION eq false && $BLOCK_LABEL eq 'LBL_ITEM_DETAILS'}
				{* dont show the line item fields block when Inventory fields are selected for calculations *}
			{else}
				{$RECORD_STRUCTURE[$key] = $BLOCK_FIELDS}
			{/if}
		{/foreach}

		<div class="row-fluid" style="border:1px solid #ccc;">
			<div id="advanceFilterContainer" {if $IS_FILTER_SAVED_NEW == false} class="zeroOpacity conditionsContainer padding1per" {else} class="conditionsContainer padding1per" {/if}>
				<h5 class="padding-bottom1per"><strong>{vtranslate('LBL_CHOOSE_FILTER_CONDITIONS',$MODULE)}</strong></h5>

				<span class="span10" >

						{include file='modules/VDSimplyKPI/AdvanceFilter.tpl' RECORD_STRUCTURE=$RECORD_STRUCTURE ADVANCE_CRITERIA=$SELECTED_ADVANCED_FILTER_FIELDS }
					</span>

			</div>
		</div><br>
		<div class="row-fluid" style="border:1px solid #ccc;">


			<div class="conditionsContainer padding1per">
				<h5 class="padding-bottom1per"><strong>{vtranslate('LBL_SELECT_DATA_FIELD', $MODULE)}</strong><span class="redColor">*</span><h5>
						<div class="span6" >
							<div class="row-fluid">
								<select id='datafields' name='datafields' class="span10 validate[required]" data-validation-engine="validate[required]" style='min-width:300px;' {if $MODE eq 'edit'}disabled{/if} >
									<option value='count(*)'>{vtranslate('LBL_RECORD_COUNT', $MODULE)}</option>
									{foreach key=CALCULATION_FIELDS_MODULE_LABEL item=CALCULATION_FIELDS_MODULE from=$CALCULATION_FIELDS}
										<optgroup label="{vtranslate($CALCULATION_FIELDS_MODULE_LABEL, $CALCULATION_FIELDS_MODULE_LABEL)}">
											{foreach key=CALCULATION_FIELD_KEY item=CALCULATION_FIELD_TRANSLATED_LABEL from=$CALCULATION_FIELDS_MODULE}
												<option value="{$CALCULATION_FIELD_KEY}" {if $CALCULATION_FIELD_KEY eq $DATAFIELS} selected {/if}>{$CALCULATION_FIELD_TRANSLATED_LABEL}</option>
											{/foreach}
										</optgroup>
									{/foreach}
								</select>
							</div><br />
						</div>
			</div>
		</div>
		<br >
		<div class="pull-right">
			<button class="btn btn-danger backStep" type="button"><strong>{vtranslate('LBL_BACK', $MODULE)}</strong></button>&nbsp;&nbsp;

			<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>

			<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
		</div>
		<br><br>


	</div>
</form>
