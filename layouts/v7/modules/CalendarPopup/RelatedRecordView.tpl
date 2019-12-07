{if $TABNO eq 0}
    {assign var="TABNO" value=''}
{else}
    {assign var="TABNO" value=$TABNO+1}
{/if}
<div class="tab-pane" id="module_{$MODULE_LABEL}{$TABNO}" data-tabno="{$TABNO}">
    <script>
        selected_module.push('{$MODULE_LABEL}{$TABNO}');
    </script>
    <div class="row referenceField" style="margin-bottom: 5px">
        {assign var="displayId" value=$RECORD_STRUCTURE_MODEL->getRecord()->getId()}
        <div style="margin-top: 5px;" class="col-md-4">&nbsp;
            {if $MODULE_LABEL eq 'Leads'}
                <button onclick="javascript:CalendarPopup_Edit_Js.convertLead('index.php?module=CalendarPopup&view=ConvertLead&eventid={$EVENTID}',this);" id="Leads_detailView_basicAction_LBL_CONVERT_LEAD" class="btn btn-default">{vtranslate('LBL_CONVERT_LEAD', $MODULE_LABEL)}</button>
            {/if}
        </div>
        <div style="margin-top: 5px;" class="col-md-4">
            {assign var="FIELD_NAME" value=$ENTITY_FIELD['fieldname']}

            {assign var="FIELD_INFO" value=$ENTITY_FIELD['field_info']}
            <input name="popupReferenceModule" type="hidden" value="{$MODULE_LABEL}" />
            <input name="{$FIELD_NAME}" type="hidden" value="{$displayId}" class="sourceField" data-displayvalue='{$RECORD_STRUCTURE_MODEL->getRecordName()}' data-fieldinfo='{$FIELD_INFO}' />

            <div class="row-fluid input-prepend input-append referencefield-wrapper ">
                <input name="popupReferenceModule" type="hidden" value="{$MODULE_LABEL}" />
                {*<span class="add-on clearReferenceSelectionC cursorPointer">*}
                    {*<i id="{$MODULE_LABEL}_editView_fieldName_{$FIELD_NAME}_clear" class='icon-remove-sign' title="{vtranslate('LBL_CLEAR')}"></i>*}
                {*</span>*}
                <div class="input-group">
                    <input id="{$FIELD_NAME}_display" name="{$FIELD_NAME}_display" data-fieldname="{$FIELD_NAME}" data-fieldtype="reference" type="text"
                           class="marginLeftZero autoComplete inputElement"
                           value="{$RECORD_STRUCTURE_MODEL->getRecordName()}"
                           placeholder="{vtranslate('LBL_TYPE_SEARCH',$MODULE)}"
                           {if !empty($displayId)}readonly="true"{/if}
                           data-validation-engine="validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"
                           data-fieldinfo='{$FIELD_INFO}' placeholder="{vtranslate('LBL_TYPE_SEARCH',$MODULE_LABEL)}"
                    />
                    <a href="#" id="{$MODULE_LABEL}_editView_fieldName_{$FIELD_NAME}_clear" class="clearReferenceSelectionC icon-remove-sign"> x </a>
                    <span class="input-group-addon relatedPopup cursorPointer" title="{vtranslate('LBL_SELECT', $MODULE)}">
                        <i id="{$MODULE}_editView_fieldName_{$FIELD_NAME}_select" class="fa fa-search relatedPopupC"></i>
                    </span>
                </div>
                 <span class="createReferenceRecordC cursorPointer clearfix add-on" title="{vtranslate('LBL_CREATE', $MODULE)}">
                    <i id="{$MODULE}_editView_fieldName_{$FIELD_NAME}_create" class="fa fa-plus"></i>
                </span>

                {*<input id="{$FIELD_NAME}_display" name="{$FIELD_NAME}_display" type="text" class="span6 marginLeftZero autoComplete" {if !empty($displayId)}readonly="true"{/if}*}
                       {*value="{$RECORD_STRUCTURE_MODEL->getRecordName()}" data-validation-engine="validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"*}
                       {*data-fieldinfo='{$FIELD_INFO}' placeholder="{vtranslate('LBL_TYPE_SEARCH',$MODULE_LABEL)}"/>*}
                {*<span class="add-on relatedPopup cursorPointer">*}
                    {*<i id="{$MODULE_LABEL}_editView_fieldName_{$FIELD_NAME}_select" class="icon-search relatedPopupC" title="{vtranslate('LBL_SELECT')}" ></i>*}
                {*</span>*}
                {*<span class="add-on cursorPointer createReferenceRecordC">*}
                    {*<i id="{$MODULE_LABEL}_editView_fieldName_{$FIELD_NAME}_create" class='icon-plus' title="{vtranslate('LBL_CREATE')}"></i>*}
                {*</span>*}
            </div>
        </div>
        <div style="margin-top: 5px;" class="col-md-4">&nbsp;
            {if $MODULE_LABEL eq 'Contacts' || $MODULE_LABEL eq 'Potentials'}
                <button class="btn btn-default module-buttons btnCreateAnother pull-right" data-module="{$MODULE_LABEL}" data-module-label="{vtranslate('SINGLE_'|cat:$MODULE_LABEL, $MODULE_LABEL)}"><div style="margin-right: 5px" class="fa fa-plus" aria-hidden="true"></div><strong>{vtranslate('LBL_CREATE_ANOTHER', 'CalendarPopup')} {vtranslate('SINGLE_'|cat:$MODULE_LABEL, $MODULE_LABEL)}</strong></button>
            {/if}
        </div>
    </div>
    {*<form id="module_{$MODULE_LABEL}_Fields">*}
    <form class="form-horizontal recordEditView" id="module_{$MODULE_LABEL}{$TABNO}_Fields" name="module_{$MODULE_LABEL}{$TABNO}_Fields" method="post" action="index.php">
        {include file="modules/CalendarPopup/EditViewBlocks.tpl" RECORD_STRUCTURE=$RECORD_STRUCTURE_MODEL->getStructure() MODULE=$MODULE_LABEL RECORD_STRUCTURE_MODEL=$RECORD_STRUCTURE_MODEL}
    </form>
</div>