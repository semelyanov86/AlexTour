{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Calendar Popup ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}

<script type="text/javascript">
    var selected_module=['Calendar'];
</script>
{strip}
<style>
    .nav.massEditTabs li.active a {
        border: none;
        border-bottom: 3px solid #555;
    }
    .clearReferenceSelectionC {
        display: table-cell;
        width: 1%;
        white-space: nowrap;
        vertical-align: middle;
        padding: 5px 8px;
        border: 1px solid #ddd;
        border-left: 0;
    }
    .createReferenceRecordC {
        float: left;
        margin-left: 5px;
        margin-top: 3px;
        border: 1px solid #DDDDDD;
        padding: 3px 7px;
        text-align: center;
        color: #666;
        background: #F3F3F3;
    }
    .fieldBlockContainer{
        position: relative;
        border: 1px solid #F3F3F3;
        padding: 15px;
        margin-bottom: 10px;
        background: #FFFFFF;
    }

</style>
{foreach key=index item=jsModel from=$SCRIPTS}
	<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
{/foreach}

<div id="massEditContainer" class=' modal-dialog modelContainer' style="width: 90%; border: 1px solid #cccccc;">
    <div id="massEdit">
            <div name='massEditContent'>
                <div style="height: 20px; background: white">
                    <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: #000; margin: 0px 10px">
                        <span aria-hidden="true" class="fa fa-close"></span>
                    </button>
                </div>
                <div class="modal-body tabbable"  style="font-size: 12px; font-family:'OpenSans-Regular', sans-serif">
                    <ul class="nav nav-tabs massEditTabs">
                        <li class="active"><a href="#module_Events" data-toggle="tab"><strong>{vtranslate('SINGLE_Events', 'Calendar')}</strong></a></li>
                        {foreach item=MODULE_RECORDS from=$SELECTED_MODULES key=MODULE_NAME name=selectedModule}
                            {foreach item=RECORDID key=TABNO from=$MODULE_RECORDS}
                                {if $TABNO eq 0}
                                    {assign var="TABNO" value=''}
                                {else}
                                    {assign var="TABNO" value=$TABNO+1}
                                {/if}
                                <li>
                                    <a href="#module_{$MODULE_NAME}{$TABNO}" class="module_{$MODULE_NAME}" data-toggle="tab">
                                        {if $LINKED_MODULE_RECORDS[$MODULE_NAME]}
                                            <strong>{vtranslate('SINGLE_'|cat:$MODULE_NAME,$MODULE_NAME)} {$TABNO}</strong>
                                        {else}
                                            {vtranslate('SINGLE_'|cat:$MODULE_NAME,$MODULE_NAME)} {$TABNO}
                                        {/if}

                                    </a>
                                </li>
                            {/foreach}
                        {/foreach}
                    </ul>

                    <div class="tab-content massEditContent" >
                        <div class="tab-pane active" id="module_Events">
                            <form class="form-horizontal recordEditView" id="module_Events_Fields" name="module_Events_Fields" method="post" action="index.php">
                                {include file="modules/CalendarPopup/EditViewBlocks.tpl" RECORD_STRUCTURE=$LINKED_RECORD_STRUCTURES['Events']->getStructure() MODULE="Events" RECORD_STRUCTURE_MODEL=$LINKED_RECORD_STRUCTURES['Events']}
                            </form>
                        </div>
                        {foreach item=MODULE_RECORDS key=MODULE_NAME from=$SELECTED_MODULES name=selectedModule}
                            {foreach item=RECORD_STRUCTURE_MODEL key=TABNO from=$LINKED_RECORD_STRUCTURES[$MODULE_NAME]}
                                {include file="modules/CalendarPopup/RelatedRecordView.tpl" EVENTID=$LINKED_RECORD_STRUCTURES['Events']->getRecord()->getId() RECORD_STRUCTURE_MODEL=$RECORD_STRUCTURE_MODEL MODULE_LABEL=$MODULE_NAME ENTITY_FIELD = $ENTITY_FIELDS[$MODULE_NAME]}
                            {/foreach}
                        {/foreach}
                    </div>
                </div>
            </div>
        <div class='modal-overlay-footer clearfix' style="border-left: 0px">
            <div class="row clearfix">
                <div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                    <button class="btn btn-success" type="button" name="saveButton"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>&nbsp;&nbsp;
                    <a class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                </div>
            </div>
        </div>
    </div>
</div>
{/strip}