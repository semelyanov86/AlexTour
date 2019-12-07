{*<!--
/* ********************************************************************************
 * The content of this file is subject to the Calendar Popup ("License");
 * You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is VTExperts.com
 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.
 * All Rights Reserved.
 * ****************************************************************************** */
-->*}

<div class="container-fluid">
    <div class="widget_header row-fluid">
        <h3>{vtranslate($MODULE_NAME, $MODULE_NAME)}</h3>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="summaryWidgetContainer">
        <form action="index.php" method="post" id="EditView" class="form-horizontal">
            <input type="hidden" name="module" value="CalendarPopup">
            <input type="hidden" name="action" value="SaveSettings">
            <div class="form-group">
                <label for="modulesList">{vtranslate('LBL_SELECT_MODULE', $MODULE_NAME)}</label>
                <select class="select2" multiple="true" id="modulesList" name="modules[]" data-placeholder="Select modules" style="width: 800px">
                    {foreach from=$ALL_MODULE item=MODULE}
                        <option value="{$MODULE}" {if in_array($MODULE, $SELECTED_MODULE)} selected="true"{/if}>{vtranslate($MODULE, $MODULE)}</option>
                    {/foreach}
                </select>
            </div>
            <br />
            <div class="row-fluid">
                <button class="btn btn-success" type="submit">{vtranslate('LBL_SAVE', $MODULE_NAME)}</button>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
    <div>
        <div style="padding: 10px; text-align: justify; font-size: 14px; border: 1px solid #ececec; border-left: 5px solid #2a9bbc; border-radius: 5px; overflow: hidden;">
            <h4 style="color: #2a9bbc; margin: 0px -15px 10px -15px; padding: 0px 15px 8px 15px; border-bottom: 1px solid #ececec;"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;{vtranslate('LBL_INFO_BLOCK', $QUALIFIED_MODULE)}</h4>
            {vtranslate('LBL_INFO_BLOCK_ON_SETTING_PAGE', $QUALIFIED_MODULE)}
        </div>
    </div>
</div>