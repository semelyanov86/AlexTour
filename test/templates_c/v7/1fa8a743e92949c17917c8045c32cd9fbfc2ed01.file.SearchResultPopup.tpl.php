<?php /* Smarty version Smarty-3.1.7, created on 2019-04-05 19:08:32
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/SearchResultPopup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6303522495ca77d80a6e5a1-29727956%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fa8a743e92949c17917c8045c32cd9fbfc2ed01' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/SearchResultPopup.tpl',
      1 => 1553183842,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6303522495ca77d80a6e5a1-29727956',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'SEARCH_RESULT' => 0,
    'FILE_INFO' => 0,
    'VALUES' => 0,
    'LABEL' => 0,
    'VALUE' => 0,
    'INDEX' => 0,
    'COUNT' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5ca77d80a87d5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ca77d80a87d5')) {function content_5ca77d80a87d5($_smarty_tpl) {?>
<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><div class="clearfix"><div class="pull-right " ><button type="button" class="close" aria-label="Close" data-dismiss="modal"><span aria-hidden="true" class='fa fa-close'></span></button></div><h4 class="pull-left">Search Result</h4></div></div><div class="modal-body"><div class="search-content" style="padding: 0px 0px 5px 20px; max-height: 500px; height: 500px;"><?php $_smarty_tpl->tpl_vars['COUNT'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['SEARCH_RESULT']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['INDEX'] = new Smarty_variable(0, null, 0);?><?php  $_smarty_tpl->tpl_vars['VALUES'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['VALUES']->_loop = false;
 $_smarty_tpl->tpl_vars['FILE_INFO'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['SEARCH_RESULT']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['VALUES']->key => $_smarty_tpl->tpl_vars['VALUES']->value){
$_smarty_tpl->tpl_vars['VALUES']->_loop = true;
 $_smarty_tpl->tpl_vars['FILE_INFO']->value = $_smarty_tpl->tpl_vars['VALUES']->key;
?><div class="row"><div class="col-lg-12"><b><?php echo $_smarty_tpl->tpl_vars['FILE_INFO']->value;?>
</b></div></div><?php  $_smarty_tpl->tpl_vars['VALUE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['VALUE']->_loop = false;
 $_smarty_tpl->tpl_vars['LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['VALUES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['VALUE']->key => $_smarty_tpl->tpl_vars['VALUE']->value){
$_smarty_tpl->tpl_vars['VALUE']->_loop = true;
 $_smarty_tpl->tpl_vars['LABEL']->value = $_smarty_tpl->tpl_vars['VALUE']->key;
?><div class="row"><div class="col-lg-12" style="margin-top: 10px; padding-left: 40px;">'<?php echo $_smarty_tpl->tpl_vars['LABEL']->value;?>
' => '<?php echo $_smarty_tpl->tpl_vars['VALUE']->value;?>
'</div></div><?php } ?><?php if ($_smarty_tpl->tpl_vars['INDEX']->value<($_smarty_tpl->tpl_vars['COUNT']->value-1)){?><hr><?php }?><?php $_smarty_tpl->tpl_vars['INDEX'] = new Smarty_variable($_smarty_tpl->tpl_vars['INDEX']->value+1, null, 0);?><?php } ?></div></div></div></div><script type="text/javascript">var popupContainer = jQuery('.search-content');var Options= {axis:"y",scrollInertia: 200,mouseWheel:{ enable: true }};app.helper.showVerticalScroll(popupContainer, Options);</script><?php }} ?>