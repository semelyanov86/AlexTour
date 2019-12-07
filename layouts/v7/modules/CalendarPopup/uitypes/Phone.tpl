{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
{strip}
    {assign var=MODULE value='PBXManager'}
    {assign var=MODULEMODEL value=Vtiger_Module_Model::getInstance($MODULE)}
    {assign var=FIELD_VALUE value=$FIELD_MODEL->get('fieldvalue')}
    {assign var="FIELD_INFO" value=Vtiger_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
    {assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
    <div class="input-append row-fluid">
    <div class="span12 row-fluid">
    <input id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->get('name')}" type="text" class="input-large inputElement" data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" name="{$FIELD_MODEL->getFieldName()}"
     value="{$FIELD_VALUE}" data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} />
    {if $MODULEMODEL and $MODULEMODEL->isActive() and $FIELD_VALUE}
        {assign var=PERMISSION value=PBXManager_Server_Model::checkPermissionForOutgoingCall()}
        {if $PERMISSION}
            {assign var=PHONE_FIELD_VALUE value=$FIELD_VALUE}
            {assign var=PHONE_NUMBER value=$PHONE_FIELD_VALUE|regex_replace:"/[-()\s]/":""}
            <a class="phoneField" data-value="{$PHONE_NUMBER}" record="{$RECORD_STRUCTURE_MODEL->getRecord()->getId()}" onclick="Vtiger_PBXManager_Js.registerPBXOutboundCall('{$PHONE_NUMBER}',{$RECORD_STRUCTURE_MODEL->getRecord()->getId()})"><span class="add-on"><i class="icon-headphones"></i></span></a>
        {/if}
    {/if}
    </div></div>
{/strip}