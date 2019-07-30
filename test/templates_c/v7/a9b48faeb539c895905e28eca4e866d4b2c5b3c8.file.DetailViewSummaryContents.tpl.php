<?php /* Smarty version Smarty-3.1.7, created on 2019-03-24 12:42:53
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/DetailViewSummaryContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13555431495c97511d4f9c79-25837959%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9b48faeb539c895905e28eca4e866d4b2c5b3c8' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/DetailViewSummaryContents.tpl',
      1 => 1553184215,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13555431495c97511d4f9c79-25837959',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c97511d4fc54',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c97511d4fc54')) {function content_5c97511d4fc54($_smarty_tpl) {?>
<form id="detailView" method="POST"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SummaryViewWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</form><?php }} ?>