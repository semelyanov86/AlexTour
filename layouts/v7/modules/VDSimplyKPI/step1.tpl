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
<div class="contentsDiv marginLeftZero" >

    <div class="padding1per">

        <div class="editContainer" style="padding-left: 3%;padding-right: 3%">
            {assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
            {if $RECORD_ID neq ''}
                <h3 title="{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} {$RECORD_STRUCTURE_MODEL->getRecordName()}">{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} - {$RECORD_STRUCTURE_MODEL->getRecordName()}</h3>
            {else}
                <h3>{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}</h3>
            {/if}
            <hr>
            <div id="breadcrumbStep1">
                <ul class="crumbs marginLeftZero">
                    <li class="first step1 active" style="z-index:10;" id="step1">
                        <a><span class="stepNum">1</span><span class="stepText">{vtranslate('LBL_GENERAL_INFO',$MODULE)}</span></a>
                    </li>
                    <li class="step2 last" style="z-index:7;" id="step2">
                        <a><span class="stepNum">2</span><span class="stepText">{vtranslate('LBL_KPI_FILTRED',$MODULE)}</span></a>
                    </li>
                </ul>
            </div>
            <div class="clearfix">
            </div>
            <div class="VDSimplyKPIContents">
                <form id="VDSimplyKPI_step1" name="install" method="POST" action="index.php" class="form-horizontal">
                    <input type="hidden" name="module" value="{$MODULE}"/>
                    <input type="hidden" name="view" value="Edit"/>
                    <input type="hidden" name="step" value="1"/>
                    <input type="hidden" name="mode" value="step2"/>
                    <input type="hidden" name="record" value="{$RECORD_ID}"/>

                    <div  class="padding1per" style="border:1px solid #ccc;" >
                        {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name="EditViewBlockLevelLoop"}
                            {foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
                                {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                                {if $FIELD_NAME eq 'advanced_filter' or $FIELD_NAME eq 'result' or $FIELD_NAME eq 'netto' or $FIELD_NAME eq 'datafields' or $FIELD_NAME eq 'procent' or $FIELD_NAME eq 'typevdkpi' or $FIELD_NAME eq 'date_off' or $FIELD_NAME eq 'number_kpi' or $FIELD_NAME eq 'createnewperiod'}{continue}{/if}
                                <div class="control-group">
                                    {if $isReferenceField neq "reference"}<label class="control-label">{/if}

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
                                                <select id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->getName()}_dropDown" class="chzn-select referenceModulesList streched" style="width:160px;">
                                                    <optgroup>
                                                        {foreach key=index item=value from=$REFERENCE_LIST}
                                                            <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if}>{vtranslate($value, $MODULE)}</option>
                                                        {/foreach}
                                                    </optgroup>
                                                </select>

                                            {else}
                                                <label class="control-label">{vtranslate($FIELD_MODEL->get('label'), $MODULE)}{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}</label>
                                            {/if}
                                        {else if $FIELD_MODEL->get('uitype') eq "83"}
                                            {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE}
                                        {else}
                                            {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                        {/if}
                                        {if $isReferenceField neq "reference"}{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}</label>{/if}

                                    <div class="controls row-fluid">
                                        <div class="span8 row-fluid">
                                            {if $FIELD_NAME eq 'setype'}

                                                <select id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->getName()}" class="chzn-select " {if $MODE eq 'edit'}disabled{/if} name="{$FIELD_NAME}">
                                                    {if $FIELD_MODEL->get('fieldvalue')}
                                                        <option value="{$FIELD_MODEL->get('fieldvalue')}" selected>{vtranslate($FIELD_MODEL->get('fieldvalue'),$FIELD_MODEL->get('fieldvalue'))}</option>
                                                    {else}
                                                        {foreach key=index item=value from=$MODULELIST}
                                                            <option value="{$index}">{vtranslate($index, $index)}</option>
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
                        <div class="controls">
                            <div>

                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success nextStep"><strong>{vtranslate('Next', $MODULE)}</strong></button>
                                    &nbsp;&nbsp;<a onclick="window.history.back()" class="cancelLink cursorPointer">{vtranslate('Cancel', $MODULE)}</a>
                                </div>

                            </div>

                            <div class="clearfix">
                            </div>
                        </div>
                    </div>



                </form>
            </div>
        </div>
    </div>
</div>