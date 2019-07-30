<?php /* Smarty version Smarty-3.1.7, created on 2019-03-24 12:42:53
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/ModuleSummaryView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4273635045c97511d4bf665-44823639%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a830a6d865626b174860896defc807927eaa5303' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Visa/ModuleSummaryView.tpl',
      1 => 1553184215,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4273635045c97511d4bf665-44823639',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c97511d4c17f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c97511d4c17f')) {function content_5c97511d4c17f($_smarty_tpl) {?>
<div class="recordDetails"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SummaryViewContents.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>