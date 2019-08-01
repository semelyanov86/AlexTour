<?php /* Smarty version Smarty-3.1.7, created on 2019-08-01 11:27:54
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/Table.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12029963155c9f7f2f9fe932-68098917%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d6bef0ec6f777cfa1cf9a9a0356fac59ffb35c3' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/Table.tpl',
      1 => 1564645310,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12029963155c9f7f2f9fe932-68098917',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c9f7f2fa15af',
  'variables' => 
  array (
    'LANGUAGESTRINGS' => 0,
    'KEY' => 0,
    'VALUE' => 0,
    'FILE_PATCH' => 0,
    'JSLANGUAGESTRINGS' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9f7f2fa15af')) {function content_5c9f7f2fa15af($_smarty_tpl) {?>
<table class="table table-bordered"><thead><tr><th style="width: 40%">Language Variable</th><th style="width: 40%">Current Value</th><th style="width: 20%">Action</th></tr></thead><tbody><?php  $_smarty_tpl->tpl_vars['VALUE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['VALUE']->_loop = false;
 $_smarty_tpl->tpl_vars['KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LANGUAGESTRINGS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['VALUE']->key => $_smarty_tpl->tpl_vars['VALUE']->value){
$_smarty_tpl->tpl_vars['VALUE']->_loop = true;
 $_smarty_tpl->tpl_vars['KEY']->value = $_smarty_tpl->tpl_vars['VALUE']->key;
?><tr class="lang_element"><td><?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
</td><td><span class="current_value"><?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
</span><input type="text" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" class="new_value inputElement hide"/></td><td><a data-type='NOTJS' data-key='<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
' data-value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" href="javascript:void(0)" class="edit_label"><i class="fa fa-pencil"></i> Edit</a><button data-file_patch='<?php echo $_smarty_tpl->tpl_vars['FILE_PATCH']->value;?>
' data-type='NOTJS' data-key='<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
' data-old_value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" class="btn btn-success hide save_new_label">Save</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-warning hide cancel_save_new_label">Cancel</button></td></tr><?php } ?><?php  $_smarty_tpl->tpl_vars['VALUE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['VALUE']->_loop = false;
 $_smarty_tpl->tpl_vars['KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['JSLANGUAGESTRINGS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['VALUE']->key => $_smarty_tpl->tpl_vars['VALUE']->value){
$_smarty_tpl->tpl_vars['VALUE']->_loop = true;
 $_smarty_tpl->tpl_vars['KEY']->value = $_smarty_tpl->tpl_vars['VALUE']->key;
?><tr class="lang_element"><td><?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
</td><td><span class="current_value"><?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
</span><input type="text" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" class="new_value inputElement hide"/></td><td><a data-type='NOTJS' data-key='<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
' data-value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" href="javascript:void(0)" class="edit_label"><i class="fa fa-pencil"></i> Edit</a><button data-file_patch='<?php echo $_smarty_tpl->tpl_vars['FILE_PATCH']->value;?>
' data-type='NOTJS' data-key='<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
' data-old_value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
" class="btn btn-success hide save_new_label">Save</button>&nbsp;&nbsp;&nbsp;<button class="btn btn-warning hide cancel_save_new_label">Cancel</button></td></tr><?php } ?></tbody></table><?php }} ?>