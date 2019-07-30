<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:38:00
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/ModalPDFTemplatesSelectContent.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17957482745d0be098efced1-04809499%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cfb3defbec3f4e38c89878f5d84aecc3c2289e16' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/ModalPDFTemplatesSelectContent.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17957482745d0be098efced1-04809499',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'HEADER_TITLE' => 0,
    'ATTR_PATH' => 0,
    'SOURCE_MODULE' => 0,
    'idslist' => 0,
    'ATTRIBUTES' => 0,
    'ATTR_NAME' => 0,
    'ATTR_VAL' => 0,
    'templateid' => 0,
    'itemArr' => 0,
    'TEMPLATE_LANGUAGES' => 0,
    'CURRENT_LANGUAGE' => 0,
    'lang_key' => 0,
    'PDF_PREVIEW_ACTION' => 0,
    'SEND_EMAIL_PDF_ACTION' => 0,
    'SEND_EMAIL_PDF_ACTION_TYPE' => 0,
    'EDIT_AND_EXPORT_ACTION' => 0,
    'SAVE_AS_DOC_ACTION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0be098f2c7b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0be098f2c7b')) {function content_5d0be098f2c7b($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/vtigercrm/libraries/Smarty/libs/plugins/function.html_options.php';
?>
<div class="PDFMakerContainer modal-dialog modelContainer"><div class="modal-content" style="width:675px;"><?php ob_start();?><?php echo vtranslate('LBL_PDF_ACTIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['HEADER_TITLE'] = new Smarty_variable($_tmp1, null, 0);?><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0);?>
<div class="modal-body"><div class="container-fluid"><div><form class="form-horizontal contentsBackground" id="exportSelectDFMakerForm" method="post" action="index.php<?php if ($_smarty_tpl->tpl_vars['ATTR_PATH']->value!=''){?>?<?php echo $_smarty_tpl->tpl_vars['ATTR_PATH']->value;?>
<?php }?>" novalidate="novalidate"><input type="hidden" name="module" value="PDFMaker" /><input type="hidden" name="source_module" value="<?php echo $_smarty_tpl->tpl_vars['SOURCE_MODULE']->value;?>
" /><input type="hidden" name="relmodule" value="<?php echo $_smarty_tpl->tpl_vars['SOURCE_MODULE']->value;?>
" /><input type="hidden" name="action" value="CreatePDFFromTemplate" /><input type="hidden" name="idslist" value="<?php echo $_smarty_tpl->tpl_vars['idslist']->value;?>
"><input type="hidden" name="commontemplateid" value=""><input type="hidden" name="language" value=""><?php  $_smarty_tpl->tpl_vars["ATTR_VAL"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["ATTR_VAL"]->_loop = false;
 $_smarty_tpl->tpl_vars["ATTR_NAME"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ATTRIBUTES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["ATTR_VAL"]->key => $_smarty_tpl->tpl_vars["ATTR_VAL"]->value){
$_smarty_tpl->tpl_vars["ATTR_VAL"]->_loop = true;
 $_smarty_tpl->tpl_vars["ATTR_NAME"]->value = $_smarty_tpl->tpl_vars["ATTR_VAL"]->key;
?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['ATTR_NAME']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['ATTR_VAL']->value;?>
"/><?php } ?><div class="modal-body tabbable"><div class="row"><h5><?php echo vtranslate('LBL_PDF_TEMPLATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h5></div><div class="row"><select class="form-control" data-rule-required="true" name="use_common_template" id="use_common_template" multiple><?php  $_smarty_tpl->tpl_vars["itemArr"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["itemArr"]->_loop = false;
 $_smarty_tpl->tpl_vars["templateid"] = new Smarty_Variable;
 $_from = ($_smarty_tpl->tpl_vars['CRM_TEMPLATES']->value); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["itemArr"]->key => $_smarty_tpl->tpl_vars["itemArr"]->value){
$_smarty_tpl->tpl_vars["itemArr"]->_loop = true;
 $_smarty_tpl->tpl_vars["templateid"]->value = $_smarty_tpl->tpl_vars["itemArr"]->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['templateid']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['itemArr']->value['title']!=''){?>title="<?php echo $_smarty_tpl->tpl_vars['itemArr']->value['title'];?>
"<?php }?> <?php if ($_smarty_tpl->tpl_vars['itemArr']->value['is_default']=='1'||$_smarty_tpl->tpl_vars['itemArr']->value['is_default']=='3'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['itemArr']->value['templatename'];?>
</option><?php } ?></select></div><?php if (sizeof($_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value)>1){?><br><div class="row"><h5><?php echo vtranslate('LBL_PDF_LANGUAGE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h5></div><div class="row"><select name="template_language" id="template_language" class="col-lg-12"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value,'selected'=>$_smarty_tpl->tpl_vars['CURRENT_LANGUAGE']->value),$_smarty_tpl);?>
</select></div><?php }else{ ?><?php  $_smarty_tpl->tpl_vars["lang"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["lang"]->_loop = false;
 $_smarty_tpl->tpl_vars["lang_key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["lang"]->key => $_smarty_tpl->tpl_vars["lang"]->value){
$_smarty_tpl->tpl_vars["lang"]->_loop = true;
 $_smarty_tpl->tpl_vars["lang_key"]->value = $_smarty_tpl->tpl_vars["lang"]->key;
?><input type="hidden" name="template_language" id="template_language" value="<?php echo $_smarty_tpl->tpl_vars['lang_key']->value;?>
"/><?php } ?><?php }?></div></form></div></div></div><div class="modal-footer"><center><button class="btn btn-success PDFMakerDownloadPDF" type="button" name="generateButton"><strong><?php echo vtranslate('LBL_DOWNLOAD_FILE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php if ($_smarty_tpl->tpl_vars['PDF_PREVIEW_ACTION']->value=="1"){?><button class="btn btn-success PDFModalPreview" type="button" name="PDFModalPreview"><strong><?php echo vtranslate('LBL_PREVIEW');?>
</strong></button><?php }?><?php if ($_smarty_tpl->tpl_vars['SEND_EMAIL_PDF_ACTION']->value=="1"){?><button class="btn btn-success sendEmailWithPDF" data-sendtype="<?php echo $_smarty_tpl->tpl_vars['SEND_EMAIL_PDF_ACTION_TYPE']->value;?>
" type="button" name="sendEmailWithPDF"><strong><?php echo vtranslate('LBL_SEND_EMAIL');?>
</strong></button><?php }?><?php if ($_smarty_tpl->tpl_vars['EDIT_AND_EXPORT_ACTION']->value=="1"){?><button class="btn btn-success editPDF" type="button" name="editPDF"><strong><?php echo vtranslate('LBL_EDIT');?>
</strong></button><?php }?><?php if ($_smarty_tpl->tpl_vars['SAVE_AS_DOC_ACTION']->value=="1"){?><button class="btn btn-success savePDFToDoc" type="button" name="savePDFToDoc"><strong><?php echo vtranslate('LBL_SAVEASDOC',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><?php }?><a href="#" class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></center></div></div></div><?php }} ?>