<?php /* Smarty version Smarty-3.1.7, created on 2019-04-05 20:05:35
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/SendFileForm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14730285625c9736ceb73c13-88411658%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b855759b394911e5a6b5de4016ea6c7ca66a1abb' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/SendFileForm.tpl',
      1 => 1554483929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14730285625c9736ceb73c13-88411658',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c9736ceb8d8f',
  'variables' => 
  array (
    'MODULE' => 0,
    'SINGLE_MODULE' => 0,
    'HEADER_TITLE' => 0,
    'FIELD_MODEL' => 0,
    'BUTTON_NAME' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9736ceb8d8f')) {function content_5c9736ceb8d8f($_smarty_tpl) {?>
<div id="sendMessageContainer" class='modal-dialog modal-lg'><div class="modal-content"><form class="form-horizontal" id="massSave" method="POST" action="index.php" enctype="multipart/form-data"><?php ob_start();?><?php echo vtranslate('Send PDF File',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable((($_tmp1).(" ")).($_tmp2), null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<div class="modal-body"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" /><input type="hidden" name="view" value="MassActionAjax" /><input type="hidden" name="mode" value="saveAjax" /><input type="hidden" name="MAX_FILE_SIZE" value="30000" /><div class="modal-body tabbable"><hr><div><span><strong><?php echo vtranslate('Send File',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></span>&nbsp;:&nbsp;<?php echo vtranslate('PDF',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div class="form-group"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getUITypeModel()->getTemplateName(),$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><div class="form-group"><input name="userfile" type="file" /></div></div></div><div class="modal-footer"><?php if ($_smarty_tpl->tpl_vars['BUTTON_NAME']->value!=null){?><?php $_smarty_tpl->tpl_vars['BUTTON_LABEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['BUTTON_NAME']->value, null, 0);?><?php }else{ ?><?php ob_start();?><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['BUTTON_LABEL'] = new Smarty_variable($_tmp3, null, 0);?><?php }?><input type="submit" class="btn btn-success" value="Send File" /><a href="#" class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div></form></div></div><?php }} ?>