<?php /* Smarty version Smarty-3.1.7, created on 2019-03-26 09:46:33
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/ModuleLinkCreator/ListViewContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16122603865c99cac94b2263-12896577%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a9b09409da1524b677b44f97ed5c5b8022f7168' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/ModuleLinkCreator/ListViewContents.tpl',
      1 => 1553183943,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16122603865c99cac94b2263-12896577',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'CURRENT_USER_MODEL' => 0,
    'LISTVIEW_HEADERS' => 0,
    'COLUMNNAME' => 0,
    'LISTVIEW_HEADER' => 0,
    'RECORDS' => 0,
    'LISTVIEW_ENTRY' => 0,
    'WIDTHTYPE' => 0,
    'LISTVIEW_ENTRIES_COUNT' => 0,
    'IS_MODULE_EDITABLE' => 0,
    'SINGLE_MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c99cac95a132',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c99cac95a132')) {function content_5c99cac95a132($_smarty_tpl) {?>
<div class="col-sm-12 col-xs-12 "><div class="row" style="margin-bottom: 20px"><h3 style="text-align: center;margin-top: 0; margin-bottom: 20px"><?php echo vtranslate('Welcome To Module & Link Creator',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h3><div class="listViewActionsDiv row"><table style="margin: 0 auto;"><tr><td><a id="Contacts_listView_basicAction_LBL_ADD_RECORD" target="_blank" href="index.php?module=ModuleLinkCreator&view=Edit" class="btn btn-default btn-warning">Custom Module</a></td><td><a  href="index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&parent=Settings&view=RelationshipOneOne" target="_blank" class="btn btn-default btn-warning">1:1 Relationship</a></td><td><a href="index.php?module=ModuleLinkCreator&parent=Settings&view=IndexRelatedFields" target="_blank" class="btn btn-default btn-warning">1:M Relationship</a></td><td><a href="index.php?module=ModuleLinkCreator&parent=Settings&view=RelationshipMM" target="_blank" class="btn btn-default btn-warning">M:M Relationship</a></td><td><a href="index.php?module=ModuleLinkCreator&parent=Settings&view=RelationshipOneNone" target="_blank" class="btn btn-default btn-warning">One Way Relationship</a></td></tr></table></div></div><div class="listViewContentDiv" id="listViewContents" style="position: relative; clear:both;"><div class="listViewEntriesDiv contents-bottomscroll"><div class="bottomscroll-div"><?php $_smarty_tpl->tpl_vars['WIDTHTYPE'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('rowheight'), null, 0);?><table id="module-link-creator-list-table" class="table table-bordered listview-table" style="border-top: 1px solid #ddd"><thead><tr class="listViewContentHeader"><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMNNAME'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMNNAME']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration++;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration === $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total;
?><th nowrap <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last){?> colspan="2" <?php }?>><?php if ($_smarty_tpl->tpl_vars['COLUMNNAME']->value!='description'){?><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?></th><?php } ?></tr></thead><tbody class="overflow-y"><?php  $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RECORDS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->key => $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['listview']['index']++;
?><tr class="listViewEntries" data-id='<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('id');?>
'id="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
_listView_row_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['listview']['index']+1;?>
"><?php  $_smarty_tpl->tpl_vars['LISTVIEW_HEADER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = false;
 $_smarty_tpl->tpl_vars['COLUMNNAME'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key => $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->value){
$_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->_loop = true;
 $_smarty_tpl->tpl_vars['COLUMNNAME']->value = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->key;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration++;
 $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last = $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->iteration === $_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->total;
?><?php if ($_smarty_tpl->tpl_vars['COLUMNNAME']->value!='description'){?><td class="<?php if ($_smarty_tpl->tpl_vars['COLUMNNAME']->value=='filename'){?>listViewEntryValue<?php }?> <?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
" nowrap data-column="<?php echo $_smarty_tpl->tpl_vars['COLUMNNAME']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['COLUMNNAME']->value=='filename'){?><a href='index.php?module=ModuleLinkCreator&view=Edit&record=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('id');?>
'><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['COLUMNNAME']->value),$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['COLUMNNAME']->value));?>
</a><?php }elseif($_smarty_tpl->tpl_vars['COLUMNNAME']->value=='module'){?><?php echo vtranslate($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['COLUMNNAME']->value),$_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['COLUMNNAME']->value));?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get($_smarty_tpl->tpl_vars['COLUMNNAME']->value);?>
<?php }?></td><?php }?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_HEADER']->last){?><td nowrap class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><div class="actions pull-right"><span class="actionImages"><a class="downloadRecordButton" href="index.php?module=ModuleManager&parent=Settings&action=ModuleExport&mode=exportModule&forModule=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('module_name');?>
"><i title="<?php echo vtranslate('LBL_DOWNLOAD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" class="fa fa-download alignMiddle"></i></a>&nbsp;&nbsp;<a data-link ="index.php?module=ModuleLinkCreator&action=ActionAjax&mode=delete&record=<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('id');?>
" class="deleteRecordModuleLinkCreator"><i title="<?php echo vtranslate('LBL_DELETE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" class="fa fa-trash alignMiddle"></i></a></span></div></td><?php }?><?php } ?></tr><?php } ?><?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value=='0'){?><tr class="emptyRecordsDiv"><td colspan="5"><div class="emptyRecordsContent"><?php $_smarty_tpl->tpl_vars['SINGLE_MODULE'] = new Smarty_variable("SINGLE_".($_smarty_tpl->tpl_vars['MODULE']->value), null, 0);?><?php echo vtranslate('LBL_NO');?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate('LBL_FOUND');?>
.<?php if ($_smarty_tpl->tpl_vars['IS_MODULE_EDITABLE']->value){?> <?php echo vtranslate('LBL_CREATE');?>
 <ahref="index.php?module=ModuleLinkCreator&view=Edit"><?php echo vtranslate($_smarty_tpl->tpl_vars['SINGLE_MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a><?php }?></div></td></tr><?php }?></tbody></table><!--added this div for Temporarily --></div></div></div></div>
<?php }} ?>