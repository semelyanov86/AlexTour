<?php /* Smarty version Smarty-3.1.7, created on 2019-03-21 19:03:39
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/ModuleLinkCreator/IndexRelatedFields.tpl" */ ?>
<?php /*%%SmartyHeaderCode:416205325c93b5db2937f9-25091717%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '649288c539af6f3699a9581d9ab94e1b81133100' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/ModuleLinkCreator/IndexRelatedFields.tpl',
      1 => 1553183943,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '416205325c93b5db2937f9-25091717',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'ENTITY_MODULES' => 0,
    'MODULE1' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c93b5db2aef2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c93b5db2aef2')) {function content_5c93b5db2aef2($_smarty_tpl) {?>

<div class="viewContent"><div class="col-sm-12 col-xs-12 content-area"><form class="form-horizontal fieldBlockContainer" method="post" action="index.php" onsubmit="return false;"><div class="contentHeader row"><h3 class="col-sm-8 col-xs-8 textOverflowEllipsis"title="<?php echo vtranslate('add_new_related_field_explain',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><?php echo vtranslate('LBL_CREATEING_1M',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h3><span class="col-sm-4 col-xs-4 text-right"><button class="btn btn-success" type="submit"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><a class="cancelLink"href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&view=List"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></span></div><div class="contentHeader"><div class="alert alert-warning"><?php echo vtranslate('notice1M',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></div><table class="table table-bordered listview-table" style="border-top: 1px solid #ddd;"><thead><tr><th colspan="4"><?php echo vtranslate('add_new_related_field_1M',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th></tr></thead><tbody><tr><td class="fieldLabel medium" style="width: 20%"><label class="muted pull-right marginRight10px"><?php echo vtranslate('module2',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue medium"><div class="row-fluid"><select name="module2" id="module2" class="select2 span10" style="width: 200px"><option value="-"><?php echo vtranslate('LBL_SELECT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php  $_smarty_tpl->tpl_vars['MODULE1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MODULE1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ENTITY_MODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MODULE1']->key => $_smarty_tpl->tpl_vars['MODULE1']->value){
$_smarty_tpl->tpl_vars['MODULE1']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['MODULE1']->value;?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE1']->value);?>
</option><?php } ?></select></div></td></tr><tr><td class="fieldLabel medium"><label class="muted pull-right marginRight10px"><?php echo vtranslate('module1',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue medium"><div class="row-fluid" ><select name="module1" id="module1" class="select2 span10" style="width: 200px"><option value="-"><?php echo vtranslate('LBL_SELECT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php  $_smarty_tpl->tpl_vars['MODULE1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MODULE1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ENTITY_MODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MODULE1']->key => $_smarty_tpl->tpl_vars['MODULE1']->value){
$_smarty_tpl->tpl_vars['MODULE1']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['MODULE1']->value;?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE1']->value);?>
</option><?php } ?></select></div></td></tr><tr><td class="fieldLabel medium"><label class="muted pull-right marginRight10px"><?php echo vtranslate('fields_label',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue medium"><div class="row-fluid"><input type="text" id="field_label" class="inputElement"></div></td></tr><tr><td class="fieldLabel medium"><label class="muted pull-right marginRight10px"><?php echo vtranslate('block',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <span class="redColor">*</span></label></td><td class="fieldValue medium"><div class="row-fluid"><select name="block" id="block" class="select2 span10 required" style="width: 200px"><option value="-"><?php echo vtranslate('LBL_SELECT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div></td></tr><tr><td class="fieldLabel medium"><label class="muted pull-right marginRight10px"><?php echo vtranslate('related_list_label',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue medium"><div class="row-fluid"><input type="text" id="related_list_label" class="inputElement"></div></td></tr><tr><td class="fieldLabel medium"><label class="muted pull-right marginRight10px"><?php echo vtranslate('add_related_list',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></td><td class="fieldValue medium"><div class="row-fluid"><span><input type="checkbox" checked id ="action_add" name="action_add"> Allow to Add New</span></div></td></tr></tbody></table><br><br><div id="error_notice" class="alert alert-error notices related-field-creator-notices"style="display:none;"><?php echo vtranslate('fail',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div id="success_message" class="alert alert-success notices related-field-creator-notices"style="display:none;"><?php echo vtranslate('works',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div id="duplicate_error" class="alert alert-error notices related-field-creator-notices"style="display:none;"><?php echo vtranslate('duplicated-error',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div id="field-already-there" class="alert alert-error notices related-field-creator-notices"style="display:none;"><?php echo vtranslate('field-already-there',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><div class="row-fluid"><div class="pull-right"><button id="add_related_field" class="btn btn-success" type="submit"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><a class="cancelLink"href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&view=List"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div></div></form><br><div class="row-fluid"><table id="table-relations" class="table table-bordered listViewEntriesTable"><caption style="font-weight: bold; font-size: 18px; padding: 10px; text-align: left;"><?php echo vtranslate('All 1:M Relations');?>
</caption><thead><tr class="listViewHeaders"><th>#</th><th><?php echo vtranslate('Module 1',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th><th colspan="2"><?php echo vtranslate('Module 2',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th></tr></thead><tbody></tbody></table></div><br></div></div><?php }} ?>