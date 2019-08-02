<?php /* Smarty version Smarty-3.1.7, created on 2019-07-22 12:17:42
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Settings/Vtiger/OutgoingServerEdit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15183026215d357f36d06594-55141359%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40136c035be878a72287f941e669f7f0df5cec8c' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Settings/Vtiger/OutgoingServerEdit.tpl',
      1 => 1556124974,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15183026215d357f36d06594-55141359',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'CURRENT_USER_MODEL' => 0,
    'MODEL' => 0,
    'WIDTHTYPE' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d357f36d62fe',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d357f36d62fe')) {function content_5d357f36d62fe($_smarty_tpl) {?>


<div class="editViewPageDiv editViewContainer" id="EditViewOutgoing" style="padding-top:0px;"><div class="col-lg-12 col-md-12 col-sm-12"><div><h3 style="margin-top: 0px;"><?php echo vtranslate('LBL_OUTGOING_SERVER',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h3>&nbsp;<?php echo vtranslate('LBL_OUTGOING_SERVER_DESC',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><?php $_smarty_tpl->tpl_vars['WIDTHTYPE'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('rowheight'), null, 0);?><form id="OutgoingServerForm" data-detail-url="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->getDetailViewUrl();?>
" method="POST"><input type="hidden" name="default" value="false" /><input type="hidden" name="server_port" value="0" /><input type="hidden" name="server_type" value="email"/><input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('id');?>
"/><div class="blockData"><br><div class="hide errorMessage"><div class="alert alert-danger"><?php echo vtranslate('LBL_TESTMAILSTATUS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<strong><?php echo vtranslate('LBL_MAILSENDERROR',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></div></div><div class="block"><div><div class="btn-group pull-right"><button class="btn t-btn resetButton" type="button" title="<?php echo vtranslate('LBL_RESET_TO_DEFAULT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"><strong><?php echo vtranslate('LBL_RESET_TO_DEFAULT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></button></div><h4><?php echo vtranslate('LBL_MAIL_SERVER_SMTP',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</h4></div><hr><table class="table editview-table no-border"><tbody><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel" style="width: 25%;"><label><?php echo vtranslate('LBL_SENDMAIL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="checkbox" name="use_sendmail" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->isUseSendMailEnabled()!='false'){?>checked<?php }?>/></div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_USE_MAIL_ACCOUNT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="checkbox" name="use_mail_account" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->isUseMailAccountEnabled()!='false'){?>checked<?php }?>/></div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><span class="redColor">*</span><?php echo vtranslate('LBL_SERVER_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="text" name="server" data-validation-engine='validate[required]' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('server');?>
" /></div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_SERVER_PORT',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="text" name="server_port" data-validation-engine='validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('server_port');?>
"</div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_USER_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="text" name="server_username" data-validation-engine='validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('server_username');?>
"</div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_PASSWORD',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="password" name="server_password" data-validation-engine='validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('server_password');?>
"</div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_FROM_EMAIL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="text" name="from_email_field" data-validation-engine="validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" data-validator='<?php echo Zend_Json::encode(array(array('name'=>'Email')));?>
' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('from_email_field');?>
"</div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_FROM_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input class="inputElement" type="text" name="from_name" data-validation-engine='validate[funcCall[Vtiger_Base_Validator_Js.invokeValidation]' value="<?php echo $_smarty_tpl->tpl_vars['MODEL']->value->get('from_name');?>
"</div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_REQUIRES_AUTHENTICATION',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="checkbox" name="smtp_auth" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->isSmtpAuthEnabled()!='false'){?>checked<?php }?>/></div></td></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldLabel"><label><?php echo vtranslate('LBL_CONNECTION_SECURITY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
 fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="radio" name="server_tls" value="no" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->get('server_tls')=='no'){?>checked=true<?php }elseif($_smarty_tpl->tpl_vars['MODEL']->value->get('server_tls')!='tls'&&$_smarty_tpl->tpl_vars['MODEL']->value->get('server_tls')!='ssl'){?>checked=true<?php }?>>&nbsp; <?php echo vtranslate('LBL_NO_TLS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<input type="radio" name="server_tls" value="tls" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->get('server_tls')=='tls'){?>checked=true<?php }?>>&nbsp; <?php echo vtranslate('LBL_TLS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<input type="radio" name="server_tls" value="ssl" <?php if ($_smarty_tpl->tpl_vars['MODEL']->value->get('server_tls')=='ssl'){?>checked=true<?php }?>>&nbsp; <?php echo vtranslate('LBL_SSL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div></td></tr></tbody></table><br><div class="alert alert-info"><?php echo vtranslate('LBL_OUTGOING_SERVER_NOTE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><div class="alert alert-info"><?php echo vtranslate('LBL_OUTGOING_SERVER_NOTE_2',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div></div><br><div class='modal-overlay-footer clearfix'><div class="row clearfix"><div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '><button type='submit' class='btn btn-success saveButton' ><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button>&nbsp;&nbsp;<a class='cancelLink' data-dismiss="modal" href="#"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div></div></div></div></form></div></div>
<?php }} ?>