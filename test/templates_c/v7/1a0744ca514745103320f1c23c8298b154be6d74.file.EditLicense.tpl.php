<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:34:34
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/EditLicense.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10997749065d0bdfcabe59b9-38831656%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a0744ca514745103320f1c23c8298b154be6d74' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/EditLicense.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10997749065d0bdfcabe59b9-38831656',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TYPE' => 0,
    'MODULE' => 0,
    'HEADER_TITLE' => 0,
    'LICENSEKEY' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0bdfcabf71a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0bdfcabf71a')) {function content_5d0bdfcabf71a($_smarty_tpl) {?>
<div class="modal-dialog"><div class="modal-content"><?php if ($_smarty_tpl->tpl_vars['TYPE']->value=="reactivate"){?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable(vtranslate('LBL_REACTIVATE','PDFMaker'), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable(vtranslate('LBL_ACTIVATE_KEY','PDFMaker'), null, 0);?><?php }?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<form id="editLicense" class="form-horizontal contentsBackground"><input type="hidden" name="module" value="PDFMaker"><input type="hidden" name="action" value="License"><input type="hidden" name="mode" value="editLicense"><input type="hidden" name="type" value="<?php echo $_smarty_tpl->tpl_vars['TYPE']->value;?>
"><div class="modal-body"><table class="massEditTable table no-border"><tr><td class="fieldLabel col-lg-2"><label class="muted pull-right"><?php echo vtranslate('LBL_LICENSE_KEY','PDFMaker');?>
<span class="redColor">*</span></label></td><td class="fieldValue col-lg-4" colspan="3"><input type="text" class="inputElement" name="licensekey" value="<?php echo $_smarty_tpl->tpl_vars['LICENSEKEY']->value;?>
" data-validation-engine='validate[required]' /></td></tr></table></div><?php $_smarty_tpl->tpl_vars['BUTTON_ID'] = new Smarty_variable("js-edit-license", null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ModalFooter.tpl','Vtiger'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form></div></div><?php }} ?>