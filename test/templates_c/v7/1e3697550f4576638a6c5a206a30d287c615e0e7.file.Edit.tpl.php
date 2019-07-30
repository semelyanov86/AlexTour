<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:35:59
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20964537185d0be01f270eb7-04347630%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e3697550f4576638a6c5a206a30d287c615e0e7' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Edit.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20964537185d0be01f270eb7-04347630',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'PARENTTAB' => 0,
    'SAVETEMPLATEID' => 0,
    'MODULE' => 0,
    'IS_BLOCK' => 0,
    'FILENAME' => 0,
    'TEMPLATEBLOCKTYPE' => 0,
    'TEMPLATEBLOCKTYPEVAL' => 0,
    'DESCRIPTION' => 0,
    'HEAD_FOOT_VARS' => 0,
    'TEMPLATEID' => 0,
    'SELECTMODULE' => 0,
    'MODULENAMES' => 0,
    'SELECT_MODULE_FIELD' => 0,
    'RELATED_MODULES' => 0,
    'RelMod' => 0,
    'RELATED_BLOCKS' => 0,
    'ACCOUNTINFORMATIONS' => 0,
    'CUI_BLOCKS' => 0,
    'USERINFORMATIONS' => 0,
    'IS_LISTVIEW_CHECKED' => 0,
    'LISTVIEW_BLOCK_TPL' => 0,
    'INVENTORYTERMSANDCONDITIONS' => 0,
    'DATE_VARS' => 0,
    'TYPE' => 0,
    'CUSTOM_FUNCTIONS' => 0,
    'FONTAWESOMEICONS' => 0,
    'SELECTEDFONTAWESOMEICON' => 0,
    'FONTAWESOMEDATA' => 0,
    'GLOBAL_LANG_LABELS' => 0,
    'MODULE_LANG_LABELS' => 0,
    'CUSTOM_LANG_LABELS' => 0,
    'PRODUCT_BLOC_TPL' => 0,
    'ARTICLE_STRINGS' => 0,
    'SELECT_PRODUCT_FIELD' => 0,
    'PRODUCTS_FIELDS' => 0,
    'SERVICES_FIELDS' => 0,
    'BLOCK_TYPES' => 0,
    'BLOCK_TYPE' => 0,
    'BLOCKID' => 0,
    'BLOCK_TYPE_DATA' => 0,
    'DH_ALL' => 0,
    'DH_FIRST' => 0,
    'DH_OTHER' => 0,
    'DF_ALL' => 0,
    'DF_FIRST' => 0,
    'DF_OTHER' => 0,
    'DF_LAST' => 0,
    'FORMATS' => 0,
    'SELECT_FORMAT' => 0,
    'CUSTOM_FORMAT' => 0,
    'ORIENTATIONS' => 0,
    'SELECT_ORIENTATION' => 0,
    'MARGINS' => 0,
    'margin_input_width' => 0,
    'DECIMALS' => 0,
    'WATERMARK' => 0,
    'MAX_UPLOAD_LIMIT_BYTES' => 0,
    'MAX_UPLOAD_LIMIT_MB' => 0,
    'NAME_OF_FILE' => 0,
    'FILENAME_FIELDS' => 0,
    'SELECT_MODULE_FIELD_FILENAME' => 0,
    'PDF_PASSWORD' => 0,
    'IGNORE_PICKLIST_VALUES' => 0,
    'STATUS' => 0,
    'IS_ACTIVE' => 0,
    'IS_DEFAULT_DV_CHECKED' => 0,
    'IS_DEFAULT_LV_CHECKED' => 0,
    'ORDER' => 0,
    'IS_PORTAL_CHECKED' => 0,
    'TEMPLATE_OWNERS' => 0,
    'TEMPLATE_OWNER' => 0,
    'SHARINGTYPES' => 0,
    'SHARINGTYPE' => 0,
    'MEMBER_GROUPS' => 0,
    'GROUP_LABEL' => 0,
    'TRANS_GROUP_LABEL' => 0,
    'ALL_GROUP_MEMBERS' => 0,
    'MEMBER' => 0,
    'SELECTED_MEMBERS_GROUP' => 0,
    'STYLES_CONTENT' => 0,
    'BODY' => 0,
    'HEADER' => 0,
    'FOOTER' => 0,
    'ITS4YOUSTYLE_FILES' => 0,
    'STYLE_DATA' => 0,
    'FONTAWESOMECLASS' => 0,
    'VERSION' => 0,
    'VATBLOCK_TABLE' => 0,
    'COMPANY_HEADER_SIGNATURE' => 0,
    'COMPANY_STAMP_SIGNATURE' => 0,
    'QUALIFIED_MODULE' => 0,
    'HEADER_TITLE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0be01f3eaee',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0be01f3eaee')) {function content_5d0be01f3eaee($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/vtigercrm/libraries/Smarty/libs/plugins/function.html_options.php';
?>
<div class="contents tabbable ui-sortable"><form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="index.php" enctype="multipart/form-data"><input type="hidden" name="module" value="PDFMaker"><input type="hidden" name="parenttab" value="<?php echo $_smarty_tpl->tpl_vars['PARENTTAB']->value;?>
"><input type="hidden" name="templateid" id="templateid" value="<?php echo $_smarty_tpl->tpl_vars['SAVETEMPLATEID']->value;?>
"><input type="hidden" name="action" value="SavePDFTemplate"><input type="hidden" name="redirect" value="true"><input type="hidden" name="return_module" value="<?php echo $_REQUEST['return_module'];?>
"><input type="hidden" name="return_view" value="<?php echo $_REQUEST['return_view'];?>
"><input type="hidden" name="selectedTab" id="selectedTab" value="properties"><input type="hidden" name="selectedTab2" id="selectedTab2" value="body"><ul class="nav nav-tabs layoutTabs massEditTabs"><li class="detailviewTab active"><a data-toggle="tab" href="#pdfContentEdit" aria-expanded="true"><strong><?php echo vtranslate('LBL_BASIC_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#pdfContentOther" aria-expanded="false"><strong><?php echo vtranslate('LBL_OTHER_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#pdfContentLabels" aria-expanded="false"><strong><?php echo vtranslate('LBL_LABELS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><li class="detailviewTab"><a data-toggle="tab" href="#pdfContentProducts" aria-expanded="false"><strong><?php echo vtranslate('LBL_ARTICLE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#pdfContentHeaderFooter" aria-expanded="false"><strong><?php echo vtranslate('LBL_HEADER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 / <?php echo vtranslate('LBL_FOOTER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#editTabProperties" aria-expanded="false"><strong><?php echo vtranslate('LBL_PROPERTIES_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#editTabSettings" aria-expanded="false"><strong><?php echo vtranslate('LBL_SETTINGS_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><li class="detailviewTab"><a data-toggle="tab" href="#editTabSharing" aria-expanded="false"><strong><?php echo vtranslate('LBL_SHARING_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a></li><?php }?></ul><div ><div><div class="row" ><div class="left-block col-xs-4"><div><div class="tab-content layoutContent themeTableColor overflowVisible"><div class="tab-pane active" id="pdfContentEdit"><div class="edit-template-content col-lg-4" style="position:fixed;z-index:1000;"><br /><div class="properties_div"><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_PDF_NAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:&nbsp;<span class="redColor">*</span></label><div class="controls col-sm-9"><input name="filename" id="filename" type="text" value="<?php echo $_smarty_tpl->tpl_vars['FILENAME']->value;?>
" data-rule-required="true" class="inputElement nameField" tabindex="1"></div></div><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value==true){?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_TYPE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><?php if ($_smarty_tpl->tpl_vars['SAVETEMPLATEID']->value!=''&&$_smarty_tpl->tpl_vars['TEMPLATEBLOCKTYPE']->value!=''){?><?php echo $_smarty_tpl->tpl_vars['TEMPLATEBLOCKTYPEVAL']->value;?>
<input type="hidden" name="blocktype" id="blocktype" value="<?php echo $_smarty_tpl->tpl_vars['TEMPLATEBLOCKTYPE']->value;?>
"><?php }else{ ?><select name="blocktype" id="blocktype" class="select2 form-control" data-rule-required="true"><option value="header" <?php if ($_smarty_tpl->tpl_vars['TEMPLATEBLOCKTYPE']->value=='header'){?>selected<?php }?>><?php echo vtranslate('Header',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="footer" <?php if ($_smarty_tpl->tpl_vars['TEMPLATEBLOCKTYPE']->value=='footer'){?>selected<?php }?>><?php echo vtranslate('Footer',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select><?php }?></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_DESCRIPTION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input name="description" type="text" value="<?php echo $_smarty_tpl->tpl_vars['DESCRIPTION']->value;?>
" class="inputElement" tabindex="2"></div></div><div class="form-group" id="header_variables"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_HEADER_FOOTER_VARIABLES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="header_var" id="header_var" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['HEAD_FOOT_VARS']->value,'selected'=>''),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="header_var" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div><?php }?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_MODULENAMES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:<?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value==''&&$_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?>&nbsp;<span class="redColor">*</span>&nbsp;<?php }?></label><div class="controls col-sm-9"><select name="modulename" id="modulename" class="select2 form-control" <?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?>data-rule-required="true"<?php }?>><?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value!=''||$_smarty_tpl->tpl_vars['SELECTMODULE']->value!=''){?><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULENAMES']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECTMODULE']->value),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULENAMES']->value),$_smarty_tpl);?>
<?php }?></select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"></label><div class="controls col-sm-9"><div class="input-group"><select name="modulefields" id="modulefields" class="select2 form-control"><?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value==''&&$_smarty_tpl->tpl_vars['SELECTMODULE']->value==''){?><option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php }else{ ?><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_MODULE_FIELD']->value),$_smarty_tpl);?>
<?php }?></select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="modulefields" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="modulefields" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_RELATED_MODULES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="relatedmodulesorce" id="relatedmodulesorce" class="select2 form-control"><option value=""><?php echo vtranslate('LBL_SELECT_MODULE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php  $_smarty_tpl->tpl_vars['RelMod'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['RelMod']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['RELATED_MODULES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['RelMod']->key => $_smarty_tpl->tpl_vars['RelMod']->value){
$_smarty_tpl->tpl_vars['RelMod']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['RelMod']->value[3];?>
|<?php echo $_smarty_tpl->tpl_vars['RelMod']->value[0];?>
" data-module="<?php echo $_smarty_tpl->tpl_vars['RelMod']->value[3];?>
"><?php echo $_smarty_tpl->tpl_vars['RelMod']->value[1];?>
 (<?php echo $_smarty_tpl->tpl_vars['RelMod']->value[2];?>
)</option><?php } ?></select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"></label><div class="controls col-sm-9"><div class="input-group"><select name="relatedmodulefields" id="relatedmodulefields" class="select2 form-control"><option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="relatedmodulefields" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="relatedmodulefields" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div></div><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="form-group" id="related_block_tpl_row"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_RELATED_BLOCK_TPL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="related_block" id="related_block" class="select2 form-control" ><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['RELATED_BLOCKS']->value),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success marginLeftZero" onclick="PDFMaker_EditJs.InsertRelatedBlock();" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn addButton marginLeftZero" onclick="PDFMaker_EditJs.CreateRelatedBlock();" title="<?php echo vtranslate('LBL_CREATE');?>
"><i class="fa fa-plus"></i></button><button type="button" class="btn marginLeftZero" onclick="PDFMaker_EditJs.EditRelatedBlock();" title="<?php echo vtranslate('LBL_EDIT');?>
"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-danger marginLeftZero" class="crmButton small delete" onclick="PDFMaker_EditJs.DeleteRelatedBlock();" title="<?php echo vtranslate('LBL_DELETE');?>
"><i class="fa fa-trash"></i></button></div></div></div></div><?php }?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_COMPANY_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="acc_info" id="acc_info" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ACCOUNTINFORMATIONS']->value),$_smarty_tpl);?>
</select><div id="acc_info_div" class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="acc_info" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="acc_info" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_SELECT_USER_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="acc_info_type" id="acc_info_type" class="select2 form-control" onChange="PDFMaker_EditJs.change_acc_info(this)"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUI_BLOCKS']->value),$_smarty_tpl);?>
</select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"></label><div class="controls col-sm-9"><div id="user_info_div" class="au_info_div"><div class="input-group"><select name="user_info" id="user_info" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['USERINFORMATIONS']->value['a']),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="user_info" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="user_info" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div><div id="logged_user_info_div" class="au_info_div" style="display:none;"><div class="input-group"><select name="logged_user_info" id="logged_user_info" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['USERINFORMATIONS']->value['l']),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="logged_user_info" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="logged_user_info" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div><div id="modifiedby_user_info_div" class="au_info_div" style="display:none;"><div class="input-group"><select name="modifiedby_user_info" id="modifiedby_user_info" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['USERINFORMATIONS']->value['m']),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="modifiedby_user_info" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="modifiedby_user_info" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div><div id="smcreator_user_info_div" class="au_info_div" style="display:none;"><div class="input-group"><select name="smcreator_user_info" id="smcreator_user_info" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['USERINFORMATIONS']->value['c']),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="smcreator_user_info" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button><button type="button" class="btn btn-warning InsertLIntoTemplate" data-type="smcreator_user_info" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></div></div></div></div></div></div></div></div><div class="tab-pane" id="pdfContentOther"><div class="edit-template-content col-lg-4" style="position:fixed;z-index:1000;"><br /><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="form-group" id="listview_block_tpl_row"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><input type="checkbox" name="is_listview" id="isListViewTmpl" <?php if ($_smarty_tpl->tpl_vars['IS_LISTVIEW_CHECKED']->value=="yes"){?>checked="checked"<?php }?> onclick="PDFMaker_EditJs.isLvTmplClicked();" title="<?php echo vtranslate('LBL_LISTVIEW_TEMPLATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" />&nbsp;<?php echo vtranslate('LBL_LISTVIEWBLOCK',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="listviewblocktpl" id="listviewblocktpl" class="select2 form-control" <?php if ($_smarty_tpl->tpl_vars['IS_LISTVIEW_CHECKED']->value!="yes"){?>disabled<?php }?>><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['LISTVIEW_BLOCK_TPL']->value),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" id="listviewblocktpl_butt" class="btn btn-success InsertIntoTemplate" data-type="listviewblocktpl" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['IS_LISTVIEW_CHECKED']->value!="yes"){?>disabled<?php }?>><i class="fa fa-usd"></i></button></div></div></div></div><?php }?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('TERMS_AND_CONDITIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="invterandcon" id="invterandcon" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['INVENTORYTERMSANDCONDITIONS']->value),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="invterandcon" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_CURRENT_DATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="dateval" id="dateval" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['DATE_VARS']->value),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="dateval" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_BARCODES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="barcodeval" id="barcodeval" class="select2 form-control"><optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE1',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="EAN13">EAN13</option><option value="ISBN">ISBN</option><option value="ISSN">ISSN</option></optgroup><optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE2',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="UPCA">UPCA</option><option value="UPCE">UPCE</option><option value="EAN8">EAN8</option></optgroup><optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE3',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="EAN2">EAN2</option><option value="EAN5">EAN5</option><option value="EAN13P2">EAN13P2</option><option value="ISBNP2">ISBNP2</option><option value="ISSNP2">ISSNP2</option><option value="UPCAP2">UPCAP2</option><option value="UPCEP2">UPCEP2</option><option value="EAN8P2">EAN8P2</option><option value="EAN13P5">EAN13P5</option><option value="ISBNP5">ISBNP5</option><option value="ISSNP5">ISSNP5</option><option value="UPCAP5">UPCAP5</option><option value="UPCEP5">UPCEP5</option><option value="EAN8P5">EAN8P5</option></optgroup><optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE4',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="IMB">IMB</option><option value="RM4SCC">RM4SCC</option><option value="KIX">KIX</option><option value="POSTNET">POSTNET</option><option value="PLANET">PLANET</option></optgroup><optgroup label="<?php echo vtranslate('LBL_BARCODES_TYPE5',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="C128A">C128A</option><option value="C128B">C128B</option><option value="C128C">C128C</option><option value="EAN128C">EAN128C</option><option value="C39">C39</option><option value="C39+">C39+</option><option value="C39E">C39E</option><option value="C39E+">C39E+</option><option value="S25">S25</option><option value="S25+">S25+</option><option value="I25">I25</option><option value="I25+">I25+</option><option value="I25B">I25B</option><option value="I25B+">I25B+</option><option value="C93">C93</option><option value="MSI">MSI</option><option value="MSI+">MSI+</option><option value="CODABAR">CODABAR</option><option value="CODE11">CODE11</option></optgroup><optgroup label="<?php echo vtranslate('LBL_QRCODE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><option value="QR">QR</option></optgroup></select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="barcodeval" title="<?php echo vtranslate('LBL_INSERT_BARCODE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button>&nbsp;&nbsp;<a href="index.php?module=PDFMaker&view=IndexAjax&mode=showBarcodes" target="_new"><button type="button" class="btn"><i class="fa fa-info"></i></button></a></div></div></div></div><?php if ($_smarty_tpl->tpl_vars['TYPE']->value=="professional"){?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('CUSTOM_FUNCTIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="customfunction" id="customfunction" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUSTOM_FUNCTIONS']->value),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="customfunction" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div><?php }?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_FONT_AWESOME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="fontawesomeicons" id="fontawesomeicons" class="select2 form-control"><?php  $_smarty_tpl->tpl_vars['FONTAWESOMEDATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['FONTAWESOMEICONS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->key => $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value){
$_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['SELECTEDFONTAWESOMEICON']->value==''){?><?php $_smarty_tpl->tpl_vars['SELECTEDFONTAWESOMEICON'] = new Smarty_variable($_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value['name'], null, 0);?><?php }?><option value="<?php echo $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value['code'];?>
" data-classname="<?php echo $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value['name'];?>
" <?php if ($_smarty_tpl->tpl_vars['SELECTEDFONTAWESOMEICON']->value==$_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value['name']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['FONTAWESOMEDATA']->value['name'];?>
</option><?php } ?></select><div class="input-group-btn"><button type="button" class="btn btn-warning InsertIconIntoTemplate" data-type="awesomeicon" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i id="fontawesomepreview" class="fa <?php echo $_smarty_tpl->tpl_vars['SELECTEDFONTAWESOMEICON']->value;?>
"></i></button><a href="index.php?module=PDFMaker&view=IndexAjax&mode=getAwesomeInfoPDF" target="_new"><button type="button" class="btn"><i class="fa fa-info"></i></button></a></div></div></div></div></div></div><div class="tab-pane" id="pdfContentLabels"><div class="edit-template-content col-lg-4" style="position:fixed;z-index:1000;"><br /><div class="form-group" id="labels_div"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_GLOBAL_LANG',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="global_lang" id="global_lang" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['GLOBAL_LANG_LABELS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-warning InsertIntoTemplate" data-type="global_lang" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></span></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_MODULE_LANG',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="module_lang" id="module_lang" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['MODULE_LANG_LABELS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-warning InsertIntoTemplate" data-type="module_lang" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></span></div></div></div><?php if ($_smarty_tpl->tpl_vars['TYPE']->value=="professional"){?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_CUSTOM_LABELS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="custom_lang" id="custom_lang" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['CUSTOM_LANG_LABELS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-warning InsertIntoTemplate" data-type="custom_lang" title="<?php echo vtranslate('LBL_INSERT_LABEL_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-text-width"></i></button></span></div></div></div><?php }?></div></div><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="tab-pane" id="pdfContentProducts"><div class="edit-template-content col-lg-4" style="position:fixed;z-index:1000;"><br /><div id="products_div"><div class="form-group"><label class="control-label fieldLabel col-sm-4" style="font-weight: normal"><?php echo vtranslate('LBL_PRODUCT_BLOC_TPL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-8"><div class="input-group"><select name="productbloctpl2" id="productbloctpl2" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['PRODUCT_BLOC_TPL']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="productbloctpl2" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></span></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-4" style="font-weight: normal"><?php echo vtranslate('LBL_ARTICLE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-8"><div class="input-group"><select name="articelvar" id="articelvar" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ARTICLE_STRINGS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="articelvar" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></span></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-4" style="font-weight: normal">*<?php echo vtranslate('LBL_PRODUCTS_AVLBL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-8"><div class="input-group"><select name="psfields" id="psfields" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_PRODUCT_FIELD']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="psfields" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></span></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-4" style="font-weight: normal">*<?php echo vtranslate('LBL_PRODUCTS_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-8"><div class="input-group"><select name="productfields" id="productfields" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['PRODUCTS_FIELDS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="productfields" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></span></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-4" style="font-weight: normal">*<?php echo vtranslate('LBL_SERVICES_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-8"><div class="input-group"><select name="servicesfields" id="servicesfields" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SERVICES_FIELDS']->value),$_smarty_tpl);?>
</select><span class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="servicesfields" title="<?php echo vtranslate('LBL_INSERT_VARIABLE_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></span></div></div></div><div class="form-group"><div class="controls col-sm-12"><label class="muted"><?php echo vtranslate('LBL_PRODUCT_FIELD_INFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label></div></div></div></div></div><div class="tab-pane" id="pdfContentHeaderFooter"><div class="edit-template-content col-lg-4" style="position:fixed;z-index:1000;"><br /><div id="headerfooter_div"><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><?php  $_smarty_tpl->tpl_vars['BLOCK_TYPE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_TYPE']->_loop = false;
 $_smarty_tpl->tpl_vars['BLOCKID'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['BLOCK_TYPES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_TYPE']->key => $_smarty_tpl->tpl_vars['BLOCK_TYPE']->value){
$_smarty_tpl->tpl_vars['BLOCK_TYPE']->_loop = true;
 $_smarty_tpl->tpl_vars['BLOCKID']->value = $_smarty_tpl->tpl_vars['BLOCK_TYPE']->key;
?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo $_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["name"];?>
:</label><div class="controls col-sm-9"><div class="blocktypeselect"><select name="blocktype<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
_val" id="blocktype<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
_val" data-type="<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
" class="select2 col-sm-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["types"],'selected'=>$_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["selected"]),$_smarty_tpl);?>
</select></div><div id="blocktype<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
" class="<?php if ($_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["selected"]=="custom"){?>hide<?php }?>"><select name="blocktype<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
_list" id="blocktype<?php echo $_smarty_tpl->tpl_vars['BLOCKID']->value;?>
_list" class="select2 col-sm-12"><?php  $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["list"]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->key => $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->value){
$_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->value["templateid"];?>
" <?php if ($_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->value["templateid"]==$_smarty_tpl->tpl_vars['BLOCK_TYPE']->value["selectedid"]){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['BLOCK_TYPE_DATA']->value["name"];?>
</option><?php } ?></select></div></div></div><?php } ?><?php }?><div class="form-group" id="header_variables"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_HEADER_FOOTER_VARIABLES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="header_var" id="header_var" class="select2 form-control"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['HEAD_FOOT_VARS']->value,'selected'=>''),$_smarty_tpl);?>
</select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTemplate" data-type="header_var" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_DISPLAY_HEADER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><b><?php echo vtranslate('LBL_ALL_PAGES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b>&nbsp;<input type="checkbox" id="dh_allid" name="dh_all" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_ALL']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_FIRST_PAGE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<input type="checkbox" id="dh_firstid" name="dh_first" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_FIRST']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_OTHER_PAGES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<input type="checkbox" id="dh_otherid" name="dh_other" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'header');" <?php echo $_smarty_tpl->tpl_vars['DH_OTHER']->value;?>
/></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_DISPLAY_FOOTER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><b><?php echo vtranslate('LBL_ALL_PAGES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</b>&nbsp;<input type="checkbox" id="df_allid" name="df_all" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_ALL']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_FIRST_PAGE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<input type="checkbox" id="df_firstid" name="df_first" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_FIRST']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_OTHER_PAGES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<input type="checkbox" id="df_otherid" name="df_other" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_OTHER']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_LAST_PAGE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<input type="checkbox" id="df_lastid" name="df_last" onclick="PDFMaker_EditJs.hf_checkboxes_changed(this, 'footer');" <?php echo $_smarty_tpl->tpl_vars['DF_LAST']->value;?>
/></div></div></div></div></div><div class="tab-pane" id="editTabProperties"><br /><div id="properties_div"><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_PDF_FORMAT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="pdf_format" id="pdf_format" class="select2 col-sm-12" onchange="PDFMaker_EditJs.CustomFormat();"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FORMATS']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECT_FORMAT']->value),$_smarty_tpl);?>
</select><table class="table showInlineTable" id="custom_format_table" <?php if ($_smarty_tpl->tpl_vars['SELECT_FORMAT']->value!='Custom'){?>style="display:none"<?php }?>><tr><td align="right" nowrap><?php echo vtranslate('LBL_WIDTH',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="pdf_format_width" id="pdf_format_width" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOM_FORMAT']->value['width'];?>
" style="width:50px"></td><td align="right" nowrap><?php echo vtranslate('LBL_HEIGHT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="pdf_format_height" id="pdf_format_height" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['CUSTOM_FORMAT']->value['height'];?>
" style="width:50px"></td></tr></table></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_PDF_ORIENTATION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="pdf_orientation" id="pdf_orientation" class="select2 col-sm-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ORIENTATIONS']->value,'selected'=>$_smarty_tpl->tpl_vars['SELECT_ORIENTATION']->value),$_smarty_tpl);?>
</select></div></div><?php $_smarty_tpl->tpl_vars['margin_input_width'] = new Smarty_variable('50px', null, 0);?><?php $_smarty_tpl->tpl_vars['margin_label_width'] = new Smarty_variable('50px', null, 0);?><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_MARGINS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><table class="table table-bordered"><tr><td align="right" nowrap><?php echo vtranslate('LBL_TOP',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="margin_top" id="margin_top" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['top'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_top', false);"></td></tr><tr><td align="right" nowrap><?php echo vtranslate('LBL_BOTTOM',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="margin_bottom" id="margin_bottom" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['bottom'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_bottom', false);"></td></tr><tr><td align="right" nowrap><?php echo vtranslate('LBL_LEFT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="margin_left"  id="margin_left" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['left'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_left', false);"></td></tr><tr><td align="right" nowrap><?php echo vtranslate('LBL_RIGHT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="margin_right" id="margin_right" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['MARGINS']->value['right'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
" onKeyUp="PDFMaker_EditJs.ControlNumber('margin_right', false);"></td></tr></table></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_DECIMALS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><table class="table table-bordered"><tr><td align="right" nowrap><?php echo vtranslate('LBL_DEC_POINT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" maxlength="2" name="dec_point" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['point'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td></tr><tr><td align="right" nowrap><?php echo vtranslate('LBL_DEC_DECIMALS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" maxlength="2" name="dec_decimals" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['decimals'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td></tr><tr><td align="right" nowrap><?php echo vtranslate('LBL_DEC_THOUSANDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" maxlength="2" name="dec_thousands" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['DECIMALS']->value['thousands'];?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['margin_input_width']->value;?>
"/></td></tr></table></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('Watermark',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><table class="table table-bordered"><tr><td align="right" nowrap width="20%"><?php echo vtranslate('Type',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><select name="watermark_type" id="watermark_type" class="select2 col-sm-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['WATERMARK']->value['types'],'selected'=>$_smarty_tpl->tpl_vars['WATERMARK']->value['type']),$_smarty_tpl);?>
</select></td></tr><tr id="watermark_image_tr" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['type']!="image"){?>class="hide"<?php }?>><td align="right" nowrap ><?php echo vtranslate('Image',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="hidden" name="watermark_img_id" class="inputElement" value="<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['image_id'];?>
"/><div id="uploadedWatermarkFileImage" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['image_name']!=''){?>class="hide"<?php }?>><input type="file" name="watermark_image" class="inputElement"/><div class="uploadedFileDetails"><div class="uploadedFileSize"></div><div class="uploadFileSizeLimit redColor"><?php echo vtranslate('LBL_MAX_UPLOAD_SIZE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<span class="maxUploadSize" data-value="<?php echo $_smarty_tpl->tpl_vars['MAX_UPLOAD_LIMIT_BYTES']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['MAX_UPLOAD_LIMIT_MB']->value;?>
<?php echo vtranslate('MB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</span></div></div></div><div id="uploadedWatermarkFileName" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['image_name']==''){?>class="hide"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['image_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['image_name'];?>
</a><span class="deleteWatermarkFile cursorPointer col-lg-1"><i class="alignMiddle fa fa-trash"></i></span></div></td></tr><tr id="watermark_text_tr" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['type']!="text"){?>class="hide"<?php }?>><td align="right" nowrap><?php echo vtranslate('Text',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="watermark_text" class="inputElement getPopupUi" value="<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['text'];?>
"/></td></tr><tr id="watermark_alpha_tr" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['type']=="none"){?>class="hide"<?php }?>><td align="right" nowrap><?php echo vtranslate('Alpha',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</td><td><input type="text" name="watermark_alpha" class="inputElement" <?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['alpha']==''){?>placeholder="0.1"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['alpha'];?>
"/></td></tr></table></div></div></div></div><div class="tab-pane" id="editTabSettings"><br /><div id="settings_div"><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_FILENAME',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input type="text" name="nameOfFile" value="<?php echo $_smarty_tpl->tpl_vars['NAME_OF_FILE']->value;?>
" id="nameOfFile" class="inputElement getPopupUi"></div></div><div class="form-group hide"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"></label><div class="controls col-sm-9"><select name="filename_fields" id="filename_fields" class="select2 form-control" onchange="PDFMaker_EditJs.insertFieldIntoFilename(this.value);"><option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><optgroup label="<?php echo vtranslate('LBL_COMMON_FILEINFO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['FILENAME_FIELDS']->value),$_smarty_tpl);?>
</optgroup><?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value!=''||$_smarty_tpl->tpl_vars['SELECTMODULE']->value!=''){?><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_MODULE_FIELD_FILENAME']->value),$_smarty_tpl);?>
<?php }?></select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_PDF_PASSWORD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input type="text" name="PDFPassword" value="<?php echo $_smarty_tpl->tpl_vars['PDF_PASSWORD']->value;?>
" id="PDFPassword" class="getPopupUi inputElement"></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_DESCRIPTION',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input name="description" type="text" value="<?php echo $_smarty_tpl->tpl_vars['DESCRIPTION']->value;?>
" class="inputElement" tabindex="2"></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_IGNORE_PICKLIST_VALUES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input type="text" name="ignore_picklist_values" value="<?php echo $_smarty_tpl->tpl_vars['IGNORE_PICKLIST_VALUES']->value;?>
" class="inputElement"/></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_STATUS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="is_active" id="is_active" class="select2 col-sm-12" onchange="PDFMaker_EditJs.templateActiveChanged(this);"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['STATUS']->value,'selected'=>$_smarty_tpl->tpl_vars['IS_ACTIVE']->value),$_smarty_tpl);?>
</select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_SETASDEFAULT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><?php echo vtranslate('LBL_FOR_DV',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <input <?php if ($_smarty_tpl->tpl_vars['IS_LISTVIEW_CHECKED']->value=="yes"){?>disabled="true"<?php }?> type="checkbox" id="is_default_dv" name="is_default_dv" <?php echo $_smarty_tpl->tpl_vars['IS_DEFAULT_DV_CHECKED']->value;?>
/>&nbsp;&nbsp;<?php echo vtranslate('LBL_FOR_LV',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;&nbsp;<input type="checkbox" id="is_default_lv" name="is_default_lv" <?php echo $_smarty_tpl->tpl_vars['IS_DEFAULT_LV_CHECKED']->value;?>
/><input type="hidden" name="tmpl_order" value="<?php echo $_smarty_tpl->tpl_vars['ORDER']->value;?>
" /></div></div><div class="form-group" id="is_portal_row"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_SETFORPORTAL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><input type="checkbox" id="is_portal" name="is_portal" <?php echo $_smarty_tpl->tpl_vars['IS_PORTAL_CHECKED']->value;?>
 onclick="return PDFMaker_EditJs.ConfirmIsPortal(this);"/></div></div></div></div><div class="tab-pane" id="editTabSharing"><br><div id="sharing_div"><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_TEMPLATE_OWNER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="template_owner" id="template_owner" class="select2 col-sm-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['TEMPLATE_OWNERS']->value,'selected'=>$_smarty_tpl->tpl_vars['TEMPLATE_OWNER']->value),$_smarty_tpl);?>
</select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_SHARING_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="sharing" id="sharing" data-toogle-members="true" class="select2 col-sm-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SHARINGTYPES']->value,'selected'=>$_smarty_tpl->tpl_vars['SHARINGTYPE']->value),$_smarty_tpl);?>
</select><br><br><select id="memberList" class="select2 col-sm-12 members op0<?php if ($_smarty_tpl->tpl_vars['SHARINGTYPE']->value=="share"){?> fadeInx<?php }?>" multiple="true" name="members[]" data-placeholder="<?php echo vtranslate('LBL_ADD_USERS_ROLES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" style="margin-bottom: 10px;" data-rule-required="<?php if ($_smarty_tpl->tpl_vars['SHARINGTYPE']->value=="share"){?>true<?php }else{ ?>false<?php }?>"><?php  $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->_loop = false;
 $_smarty_tpl->tpl_vars['GROUP_LABEL'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MEMBER_GROUPS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->key => $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->value){
$_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->_loop = true;
 $_smarty_tpl->tpl_vars['GROUP_LABEL']->value = $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->key;
?><?php $_smarty_tpl->tpl_vars['TRANS_GROUP_LABEL'] = new Smarty_variable($_smarty_tpl->tpl_vars['GROUP_LABEL']->value, null, 0);?><?php if ($_smarty_tpl->tpl_vars['GROUP_LABEL']->value=='RoleAndSubordinates'){?><?php $_smarty_tpl->tpl_vars['TRANS_GROUP_LABEL'] = new Smarty_variable('LBL_ROLEANDSUBORDINATE', null, 0);?><?php }?><?php ob_start();?><?php echo vtranslate($_smarty_tpl->tpl_vars['TRANS_GROUP_LABEL']->value);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['TRANS_GROUP_LABEL'] = new Smarty_variable($_tmp1, null, 0);?><optgroup label="<?php echo $_smarty_tpl->tpl_vars['TRANS_GROUP_LABEL']->value;?>
"><?php  $_smarty_tpl->tpl_vars['MEMBER'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MEMBER']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['ALL_GROUP_MEMBERS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MEMBER']->key => $_smarty_tpl->tpl_vars['MEMBER']->value){
$_smarty_tpl->tpl_vars['MEMBER']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['MEMBER']->value->getId();?>
" data-member-type="<?php echo $_smarty_tpl->tpl_vars['GROUP_LABEL']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['SELECTED_MEMBERS_GROUP']->value[$_smarty_tpl->tpl_vars['GROUP_LABEL']->value][$_smarty_tpl->tpl_vars['MEMBER']->value->getId()])){?>selected="true"<?php }?>><?php echo $_smarty_tpl->tpl_vars['MEMBER']->value->getName();?>
</option><?php } ?></optgroup><?php } ?></select></div></div></div></div><?php }?></div></div></div><div class="middle-block col-xs-8"><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div id="ContentEditorTabs"><ul class="nav nav-pills"><li id="bodyDivTab" class="ContentEditorTab active" data-type="body" style="margin-right: 5px"><a href="#body_div2" aria-expanded="false" data-toggle="tab"><?php echo vtranslate('LBL_BODY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li id="headerDivTab" class="ContentEditorTab" data-type="header" style="margin: 0px 5px 0px 5px"><a href="#header_div2" aria-expanded="false" data-toggle="tab"><?php echo vtranslate('LBL_HEADER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li id="footerDivTab" class="ContentEditorTab" data-type="footer" style="margin: 0px 5px 0px 5px"><a href="#footer_div2" aria-expanded="false" data-toggle="tab"><?php echo vtranslate('LBL_FOOTER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><?php if ($_smarty_tpl->tpl_vars['STYLES_CONTENT']->value!=''){?><li data-type="templateCSSStyleTabLayout" class="ContentEditorTab" style="margin: 0px 5px 0px 5px"><a href="#cssstyle_div2" aria-expanded="false" data-toggle="tab"><?php echo vtranslate('LBL_CSS_STYLE_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><?php }?></ul></div><?php }?><div class="tab-content"><div class="tab-pane ContentTabPanel active" id="body_div2"><textarea name="body" id="body" style="width:90%;height:700px" class=small tabindex="5"><?php echo $_smarty_tpl->tpl_vars['BODY']->value;?>
</textarea></div><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="tab-pane ContentTabPanel" id="header_div2"><textarea name="header_body" id="header_body" style="width:90%;height:200px" class="small"><?php echo $_smarty_tpl->tpl_vars['HEADER']->value;?>
</textarea></div><div class="tab-pane ContentTabPanel" id="footer_div2"><textarea name="footer_body" id="footer_body" style="width:90%;height:200px" class="small"><?php echo $_smarty_tpl->tpl_vars['FOOTER']->value;?>
</textarea></div><?php if ($_smarty_tpl->tpl_vars['ITS4YOUSTYLE_FILES']->value!=''){?><div class="tab-pane ContentTabPanel" id="cssstyle_div2"><?php  $_smarty_tpl->tpl_vars['STYLE_DATA'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['STYLE_DATA']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['STYLES_CONTENT']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['STYLE_DATA']->key => $_smarty_tpl->tpl_vars['STYLE_DATA']->value){
$_smarty_tpl->tpl_vars['STYLE_DATA']->_loop = true;
?><div class="hide"><textarea class="CodeMirrorContent" id="CodeMirrorContent<?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['id'];?>
"   style="border: 1px solid black; " class="CodeMirrorTextarea " tabindex="5"><?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['stylecontent'];?>
</textarea></div><table class="table table-bordered"><thead><tr class="listViewHeaders"><th><div class="pull-left"><a href="index.php?module=ITS4YouStyles&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['id'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['name'];?>
</a></div><div class="pull-right actions"><a href="index.php?module=ITS4YouStyles&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['id'];?>
" target="_blank"><i title="<?php echo vtranslate('LBL_SHOW_COMPLETE_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" class="icon-th-list alignMiddle"></i></a>&nbsp;<?php if ($_smarty_tpl->tpl_vars['STYLE_DATA']->value['iseditable']=="yes"){?><a href="index.php?module=ITS4YouStyles&view=Edit&record=<?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['id'];?>
" target="_blank" class="cursorPointer"><i class="icon-pencil alignMiddle" title="<?php echo vtranslate('LBL_EDIT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"></i></a><?php }?></div></th></tr></thead><tbody><tr><td id="CodeMirrorContent<?php echo $_smarty_tpl->tpl_vars['STYLE_DATA']->value['id'];?>
Output" class="cm-s-default"></td></tr></tbody></table><br><?php } ?></div><?php }?><?php }?></div><div class="hide"><textarea id="fontawesomeclass"><?php echo $_smarty_tpl->tpl_vars['FONTAWESOMECLASS']->value;?>
</textarea></div><script type="text/javascript"> jQuery(document).ready(function(){
                                var stylecontent = jQuery("#fontawesomeclass").val();
                                CKEDITOR.addCss(stylecontent);

                                <?php if ($_smarty_tpl->tpl_vars['ITS4YOUSTYLE_FILES']->value!=''){?>
                                    jQuery('.CodeMirrorContent').each(function(index,Element) {
                                        var stylecontent = jQuery(Element).val();
                                        CKEDITOR.addCss(stylecontent);
                                    });
                                    <?php }?>CKEDITOR.replace('body', {height: '1000'});<?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?>
                                    CKEDITOR.replace('header_body', {height: '1000'});
                                    CKEDITOR.replace('footer_body', {height: '1000'});
                                    <?php }?>})</script></div></div></div></div><div class="modal-overlay-footer row-fluid"><div class="textAlignCenter "><button class="btn" type="submit" onclick="document.EditView.redirect.value = 'false';" ><strong><?php echo vtranslate('LBL_APPLY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button>&nbsp;&nbsp;<button class="btn btn-success" type="submit" ><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php if ($_REQUEST['return_view']!=''){?><a class="cancelLink" type="reset" onclick="window.location.href = 'index.php?module=<?php if ($_REQUEST['return_module']!=''){?><?php echo $_REQUEST['return_module'];?>
<?php }else{ ?>PDFMaker<?php }?>&view=<?php echo $_REQUEST['return_view'];?>
<?php if ($_REQUEST['templateid']!=''&&$_REQUEST['return_view']!="List"){?>&templateid=<?php echo $_REQUEST['templateid'];?>
<?php }?>';"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a><?php }else{ ?><a class="cancelLink" type="reset" onclick="javascript:window.history.back();"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a><?php }?></div><div align="center" class="small" style="color: rgb(153, 153, 153);"><?php echo vtranslate('PDF_MAKER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate('COPYRIGHT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div></div></form><div class="hide" style="display: none"><div id="div_vat_block_table"><?php echo $_smarty_tpl->tpl_vars['VATBLOCK_TABLE']->value;?>
</div><div id="div_company_header_signature"><?php echo $_smarty_tpl->tpl_vars['COMPANY_HEADER_SIGNATURE']->value;?>
</div><div id="div_company_stamp_signature"><?php echo $_smarty_tpl->tpl_vars['COMPANY_STAMP_SIGNATURE']->value;?>
</div><div class="popupUi modal-dialog modal-md" data-backdrop="false"><div class="modal-content"><?php ob_start();?><?php echo vtranslate('LBL_SET_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable($_tmp2, null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<div class="modal-body"><div class="row"><div class="col-sm-12" ><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_MODULENAMES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><div class="input-group"><select name="filename_fields2" id="filename_fields2" class="form-control"><?php if ($_smarty_tpl->tpl_vars['TEMPLATEID']->value==''&&$_smarty_tpl->tpl_vars['SELECTMODULE']->value==''){?><option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php }else{ ?><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SELECT_MODULE_FIELD']->value),$_smarty_tpl);?>
<?php }?></select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTextarea" data-type="filename_fields2" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div></div></div><br><div class="row"><div class="col-sm-12" ><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"><?php echo vtranslate('LBL_RELATED_MODULES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
:</label><div class="controls col-sm-9"><select name="relatedmodulesorce2" id="relatedmodulesorce2" class="form-control"></select></div></div></div></div><br><div class="row"><div class="col-sm-12"><div class="form-group"><label class="control-label fieldLabel col-sm-3" style="font-weight: normal"></label><div class="controls col-sm-9"><div class="input-group"><select name="relatedmodulefields2" id="relatedmodulefields2" class="form-control"><option value=""><?php echo vtranslate('LBL_SELECT_MODULE_FIELD',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select><div class="input-group-btn"><button type="button" class="btn btn-success InsertIntoTextarea" data-type="relatedmodulefields2" title="<?php echo vtranslate('LBL_INSERT_TO_TEXT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
"><i class="fa fa-usd"></i></button></div></div></div></div></div></div><br><div class="row fieldValueContainer"><div class="col-sm-12"><textarea data-textarea="true" class="fieldValue inputElement hide" style="height: inherit;"></textarea></div></div><br></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalFooter.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div></div><div class="clonedPopUp"></div></div><script type="text/javascript">var selectedTab = 'properties';var selectedTab2 = 'body';var module_blocks = new Array();var selected_module = '<?php echo $_smarty_tpl->tpl_vars['SELECTMODULE']->value;?>
';var constructedOptionValue;var constructedOptionName;jQuery(document).ready(function() {jQuery.fn.scrollBottom = function() {return jQuery(document).height() - this.scrollTop() - this.height();};var $el = jQuery('.edit-template-content');var $window = jQuery(window);var top = 127;$window.bind("scroll resize", function() {var gap = $window.height() - $el.height() - 20;var scrollTop = $window.scrollTop();if (scrollTop < top - 125) {$el.css({top: (top - scrollTop) + "px",bottom: "auto"});} else {$el.css({top: top  + "px",bottom: "auto"});}}).scroll();});</script><?php }} ?>