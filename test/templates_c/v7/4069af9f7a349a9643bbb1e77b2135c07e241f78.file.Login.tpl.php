<?php /* Smarty version Smarty-3.1.7, created on 2019-03-21 19:01:46
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTEStore/Login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15512730715c93b56a111dc3-46461658%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4069af9f7a349a9643bbb1e77b2135c07e241f78' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTEStore/Login.tpl',
      1 => 1553183782,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15512730715c93b56a111dc3-46461658',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'VTIGER_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c93b56a118d6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c93b56a118d6')) {function content_5c93b56a118d6($_smarty_tpl) {?><div class="modal-content">
    <div class="modal-header contentsBackground">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true" class='fa fa-close'></span></button>
        <h4><?php echo vtranslate('LBL_LOGIN_TO_VTE_STORE','VTEStore');?>
</h4>
    </div>
    <form class="form-horizontal loginForm">
        <input type="hidden" name="module" value="VTEStore"/>
        <input type="hidden" name="parent" value="Settings"/>
        <input type="hidden" name="action" value="ActionAjax"/>
        <input type="hidden" name="userAction" value="login"/>
        <input type="hidden" name="mode" value="registerAccount"/>
        <input type="hidden" name="vtiger_url" value="<?php echo $_smarty_tpl->tpl_vars['VTIGER_URL']->value;?>
"/>

        <div class="modal-body">
            <div class="row">
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_USERNAME','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="text" class="inputElement" style="max-width: 210px;" name="username" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"><span class="redColor">*</span>&nbsp;<?php echo vtranslate('LBL_PASSWORD','VTEStore');?>
</label>
                    <div class="col-md-9"><input type="password" class="inputElement" style="max-width: 210px;" name="password" aria-required="true" data-rule-required="true" /></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-9"><span><input type="checkbox" name="savePassword" value="1" checked> &nbsp; &nbsp;<?php echo vtranslate('LBL_REMEMBER_ME','VTEStore');?>
</span></div>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-9"><a href="javascript:void(0);" style="text-decoration: underline;" id="forgotPassword" name="forgotPassword"><u><?php echo vtranslate('Forgot Password','VTEStore');?>
</u></a></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="row-fluid">
                <div class="col-lg-6"><div class="row-fluid"><a href="javascript: void(0);" name="signUp"><?php echo vtranslate('LBL_CREATE_AN_ACCOUNT','VTEStore');?>
</a></div></div>
                <div class="col-lg-6">
                    <div class="pull-right">
                        <div class="pull-right cancelLinkContainer" style="margin-top:0px;">
                            <a class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL','VTEStore');?>
</a>
                        </div>
                        <button class="btn btn-success" type="submit" name="saveButton"><strong><?php echo vtranslate('LBL_LOGIN','VTEStore');?>
</strong></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div><?php }} ?>