<div id="globalmodal">
    <div id="massEditContainer" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header contentsBackground">
                <button aria-hidden="true" class="close " data-dismiss="modal" type="button"><span aria-hidden="true" class='fa fa-close'></span></button>
                <h4>{vtranslate('LBL_FIND_ERROR', $MODULE_NAME)}</h4>
            </div>
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: auto;">
                <div name="massEditContent" style="overflow: hidden; width: auto; height: auto;">
                    <div class="modal-body tabbable">
                        <div >
                            {vtranslate('Recommended update module before fix error.',$MODULE_NAME)}
                        </div>
                        <br><br>
                        <div class="summaryWidgetContainer" style="border:1px solid #ccc;">
                            <div style="text-align: center;width: 100%;">
                                <span style="font-size: 15px;"><strong>{vtranslate('Errors', $MODULE_NAME)}</strong></span>
                            </div>
                            <div class="summaryWidgetContainer" style="width:100%; height:180px;border:1px solid #ccc; float: left; font-size: 11px;">
                                <strong>{vtranslate('LBL_ERROR_DASHBOARD', $MODULE_NAME)}:</strong>
                                <br><br>
                                {vtranslate('Dashboard Reports missing vtiger_links', $MODULE_NAME)} :
                                {if !empty($DASHBOARD_MISSING_LINK)}
                                    <font color="red">{$COUNT_DASHBOARD_MISSING_LINK} </font>
                                    <a class="fixError" data-fix="findMissingLink" style="text-decoration: underline!important;">
                                        {vtranslate('Click here to fix', $MODULE_NAME)}
                                    </a> &nbsp;&nbsp;
                                    <a class="showError" data-fix="findMissingLink" style="text-decoration: underline!important;">
                                        {vtranslate('Click here to expand', $MODULE_NAME)}
                                    </a>
                                    <ul class="findMissingLink hide">
                                        {foreach from=$RAW_VALUE_DASHBOARD_MISSING_LINK item=raw}
                                            <li>linkId : {$raw.linkid} &nbsp;&nbsp;&nbsp; linklabel : {$raw.linklabel}</li>
                                        {/foreach}
                                    </ul>
                                {else}
                                    <font color="green">0</font>
                                {/if}
                                <br>
                                {vtranslate('Widgets missing', $MODULE_NAME)} :
                                {if !empty($DASHBOARD_ERROR_WIDGET)}
                                    <font color="red">{$COUNT_DASHBOARD_ERROR_WIDGET} </font>
                                    <a class="fixError" data-fix="findErrorWidget" style="text-decoration: underline!important;">
                                        {vtranslate('Click here to fix', $MODULE_NAME)}
                                    </a>
                                {else}
                                    <font color="green">0</font>
                                {/if}
                                <br>
                                {vtranslate('Empty vtiger_link on dashboard', $MODULE_NAME)} :
                                {if !empty($DASHBOARD_EMPTY_LINK)}
                                    <font color="red">{$COUNT_DASHBOARD_EMPTY_LINK} </font>
                                    <a class="fixError" data-fix="findEmptyLink" style="text-decoration: underline!important;">
                                        {vtranslate('Click here to fix', $MODULE_NAME)}
                                    </a>
                                {else}
                                    <font color="green">0</font>
                                {/if}
                                <br>
                                {vtranslate('Dashboard Missing Default tab', $MODULE_NAME)} :
                                {if $DASHBOARD_MISSING_DEFAULT_TAB == false}
                                    <font color="red">Yes </font>
                                    <a class="fixError" data-fix="findDefaultTab" style="text-decoration: underline!important;">
                                        {vtranslate('Click here to fix', $MODULE_NAME)}
                                    </a>
                                {else}
                                    <font color="green">No</font>
                                {/if}

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div style="float: left;padding: 20px 0;text-align: center;width: 100%; line-height: 24px;">
                            Need help? Contact us - the support is free.<br>
                            Email: help@vtexperts.com<br>
                            Phone: +1 (818) 495-5557<br>
                            <a href="javascript:void(0);" onclick="window.open('https://v2.zopim.com/widget/livechat.html?&amp;key=1P1qFzYLykyIVMZJPNrXdyBilLpj662a=en', '_blank', 'location=yes,height=600,width=500,scrollbars=yes,status=yes');"> <img src="layouts/vlayout/modules/VTEStore/resources/images/livechat.png" style="height: 28px"></a><br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right cancelLinkContainer" style="margin-top: 0px;"><a class="cancelLink" type="reset" data-dismiss="modal"><strong>{vtranslate('LBL_CLOSE', $MODULE)}</strong></a></div>
            </div>
        </div>
    </div>
</div>
