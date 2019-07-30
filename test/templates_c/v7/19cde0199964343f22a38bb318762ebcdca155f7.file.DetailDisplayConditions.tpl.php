<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:37:34
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/DetailDisplayConditions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20417371045d0be07ecb3c61-48553617%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19cde0199964343f22a38bb318762ebcdca155f7' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/DetailDisplayConditions.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20417371045d0be07ecb3c61-48553617',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PDFMAKER_RECORD_MODEL' => 0,
    'DISPLAY_CONDITION' => 0,
    'ALL_CONDITIONS' => 0,
    'ANY_CONDITIONS' => 0,
    'MODULE' => 0,
    'ALL_CONDITION' => 0,
    'ANY_CONDITION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0be07ecd1f6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0be07ecd1f6')) {function content_5d0be07ecd1f6($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['DISPLAY_CONDITION'] = new Smarty_variable($_smarty_tpl->tpl_vars['PDFMAKER_RECORD_MODEL']->value->getConditonDisplayValue(), null, 0);?><?php $_smarty_tpl->tpl_vars['ALL_CONDITIONS'] = new Smarty_variable($_smarty_tpl->tpl_vars['DISPLAY_CONDITION']->value['All'], null, 0);?><?php $_smarty_tpl->tpl_vars['ANY_CONDITIONS'] = new Smarty_variable($_smarty_tpl->tpl_vars['DISPLAY_CONDITION']->value['Any'], null, 0);?><?php if (count($_smarty_tpl->tpl_vars['ALL_CONDITIONS']->value)=="0"&&count($_smarty_tpl->tpl_vars['ANY_CONDITIONS']->value)=="0"){?><?php echo vtranslate('LBL_NO_DISPLAY_CONDITIONS_DEFINED',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['DISPLAY_CONDITION']->value['displayed']=="0"){?><?php echo vtranslate('LBL_DISPLAY_CONDITIONS_YES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }else{ ?><?php echo vtranslate('LBL_DISPLAY_CONDITIONS_NO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?>:<br><br><span><strong><?php echo vtranslate('All');?>
&nbsp;:&nbsp;&nbsp;&nbsp;</strong></span><?php if (is_array($_smarty_tpl->tpl_vars['ALL_CONDITIONS']->value)&&!empty($_smarty_tpl->tpl_vars['ALL_CONDITIONS']->value)){?><?php  $_smarty_tpl->tpl_vars['ALL_CONDITION'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ALL_CONDITION']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ALL_CONDITIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['allCounter']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['ALL_CONDITION']->key => $_smarty_tpl->tpl_vars['ALL_CONDITION']->value){
$_smarty_tpl->tpl_vars['ALL_CONDITION']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['allCounter']['iteration']++;
?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['allCounter']['iteration']!=1){?><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><?php }?><span><?php echo $_smarty_tpl->tpl_vars['ALL_CONDITION']->value;?>
</span><br><?php } ?><?php }else{ ?><?php echo vtranslate('LBL_NA');?>
<?php }?><br><span><strong><?php echo vtranslate('Any');?>
&nbsp;:&nbsp;</strong></span><?php if (is_array($_smarty_tpl->tpl_vars['ANY_CONDITIONS']->value)&&!empty($_smarty_tpl->tpl_vars['ANY_CONDITIONS']->value)){?><?php  $_smarty_tpl->tpl_vars['ANY_CONDITION'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ANY_CONDITION']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ANY_CONDITIONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['anyCounter']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['ANY_CONDITION']->key => $_smarty_tpl->tpl_vars['ANY_CONDITION']->value){
$_smarty_tpl->tpl_vars['ANY_CONDITION']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['anyCounter']['iteration']++;
?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['anyCounter']['iteration']!=1){?><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><?php }?><span><?php echo $_smarty_tpl->tpl_vars['ANY_CONDITION']->value;?>
</span><br><?php } ?><?php }else{ ?><?php echo vtranslate('LBL_NA');?>
<?php }?><?php }?><?php }} ?>