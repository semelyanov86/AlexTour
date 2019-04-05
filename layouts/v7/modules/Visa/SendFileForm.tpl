{*<!--
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: SalesPlatform Ltd
 * The Initial Developer of the Original Code is SalesPlatform Ltd.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@salesplatform.ru
 ************************************************************************************/
-->*}
{strip}
    <div id="sendMessageContainer" class='modal-dialog modal-lg'>
        <div class="modal-content">
            <form class="form-horizontal" id="massSave" method="POST" action="index.php" enctype="multipart/form-data">
                {assign var=HEADER_TITLE value={vtranslate('Send PDF File', $MODULE)}|cat:" "|cat:{vtranslate($SINGLE_MODULE, $MODULE)}}
                {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
                <div class="modal-body">

                    <input type="hidden" name="module" value="{$MODULE}" />
                    <input type="hidden" name="view" value="MassActionAjax" />
                    <input type="hidden" name="mode" value="saveAjax" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                    <div class="modal-body tabbable">

                        <hr>
                        <div>
                            <span><strong>{vtranslate('Send File',$MODULE)}</strong></span>
                            &nbsp;:&nbsp;
                            {vtranslate('PDF',$MODULE)}
                        </div>
                        <div class="form-group">
                            {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
                        </div>
                        <div class="form-group">
                            <input name="userfile" type="file" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {if $BUTTON_NAME neq null}
                        {assign var=BUTTON_LABEL value=$BUTTON_NAME}
                    {else}
                        {assign var=BUTTON_LABEL value={vtranslate('LBL_SAVE', $MODULE)}}
                    {/if}
                    <input type="submit" class="btn btn-success" value="Send File" />
                    <a href="#" class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                </div>
            </form>
        </div>
    </div>
{/strip}