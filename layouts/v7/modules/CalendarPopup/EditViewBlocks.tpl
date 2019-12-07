{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Calendar Popup ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}

{strip}
    <input type="hidden" name="module" value="CalendarPopup"/>
    <input type="hidden" name="action" value="SaveAjax"/>
    <input type="hidden" name="sourceModule" value="{$MODULE}"/>
    <input type="hidden" name="record"  value="{$RECORD_STRUCTURE_MODEL->getRecord()->getId()}"/>
    {if $RECORD_STRUCTURE_MODEL->getRecord()->getId() neq "" && $RECORD_STRUCTURE_MODEL->getRecord()->getId() neq "0"}
        <input type="hidden" name="editmode" value="edit"/>
    {else}
        {if $MODULE == 'Events'}
            <input type="hidden" name="editmode" value="create"/>
        {else}
            <input type="hidden" name="editmode" value="{$MODE}"/>
        {/if}
    {/if}

    {if $MODULE eq 'Events'}
        <input type="hidden" name="defaultCallDuration" value="{$USER_MODEL->get('callduration')}" />
        <input type="hidden" name="defaultOtherEventDuration" value="{$USER_MODEL->get('othereventduration')}" />
    {/if}
    <div name='editContent' style="margin-top: 10px; background: #f9f9f9;">
        {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
            {if $BLOCK_FIELDS|@count lte 0 || $BLOCK_LABEL eq 'LBL_RELATED_TO'}{continue}{/if}
                <div class='fieldBlockContainer'>
                    <h4 class='fieldBlockHeader'>{vtranslate($BLOCK_LABEL, $MODULE)}</h4>
                    <hr>
                    <table class="table table-borderless">
                        <tr>
                            {assign var=COUNTER value=0}
                            {foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
                            {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                            {assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
                            {assign var="refrenceListCount" value=count($refrenceList)}
                            {if $FIELD_MODEL->isEditable() eq true}
                            {if $FIELD_MODEL->get('uitype') eq "19"}
                            {if $COUNTER eq '1'}
                            <td></td><td></td></tr><tr>
                            {assign var=COUNTER value=0}
                            {/if}
                            {/if}
                            {if $COUNTER eq 2}
                        </tr><tr>
                            {assign var=COUNTER value=1}
                            {else}
                            {assign var=COUNTER value=$COUNTER+1}
                            {/if}
                            <td class="fieldLabel alignMiddle">
                                {if $isReferenceField eq "reference"}
                                    {if $refrenceListCount > 1}
                                        {assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
                                        {assign var="REFERENCED_MODULE_STRUCTURE" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
                                        {if !empty($REFERENCED_MODULE_STRUCTURE)}
                                            {assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCTURE->get('name')}
                                        {/if}
                                        <select style="width: 140px;" class="select2 referenceModulesList">
                                            {foreach key=index item=value from=$refrenceList}
                                                <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if}>{vtranslate($value, $value)}</option>
                                            {/foreach}
                                        </select>
                                    {else}
                                        {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                    {/if}
                                {else if $FIELD_MODEL->get('uitype') eq "83"}
                                    {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE}
                                    {if $TAXCLASS_DETAILS}
                                        {assign 'taxCount' count($TAXCLASS_DETAILS)%2}
                                        {if $taxCount eq 0}
                                            {if $COUNTER eq 2}
                                                {assign var=COUNTER value=1}
                                            {else}
                                                {assign var=COUNTER value=2}
                                            {/if}
                                        {/if}
                                    {/if}
                                {else}
                                    {if $MODULE eq 'Documents' && $FIELD_MODEL->get('label') eq 'File Name'}
                                        {assign var=FILE_LOCATION_TYPE_FIELD value=$RECORD_STRUCTURE['LBL_FILE_INFORMATION']['filelocationtype']}
                                        {if $FILE_LOCATION_TYPE_FIELD}
                                            {if $FILE_LOCATION_TYPE_FIELD->get('fieldvalue') eq 'E'}
                                                {vtranslate("LBL_FILE_URL", $MODULE)}&nbsp;<span class="redColor">*</span>
                                            {else}
                                                {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                            {/if}
                                        {else}
                                            {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                        {/if}
                                    {else}
                                        {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                    {/if}
                                {/if}
                                &nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if}
                            </td>
                            {if $FIELD_MODEL->get('uitype') neq '83'}
                                <td class="fieldValue" {if $FIELD_MODEL->getFieldDataType() eq 'boolean'} style="width:25%" {/if}{if $FIELD_MODEL->get('uitype') eq '6'} style="width: 450px"{/if} {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
                                    {if $FIELD_MODEL->get('uitype') eq '13' || $FIELD_MODEL->get('uitype') eq '11'}
                                        {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),'CalendarPopup') BLOCK_FIELDS=$BLOCK_FIELDS}
                                    {else}
                                        {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
                                    {/if}
                                </td>
                            {/if}
                            {/if}
                            {/foreach}
                            {*If their are odd number of fields in edit then border top is missing so adding the check*}
                            {if $COUNTER is odd}
                                <td></td>
                                <td></td>
                            {/if}
                        </tr>
                    </table>
                </div>
        {/foreach}
    </div>
{/strip}