<?php /* Smarty version Smarty-3.1.7, created on 2019-07-22 12:33:59
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/GetPDFActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14161152465d358307472a82-42170298%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d9f8122bc082d8138ad9b0f72bb14d622c077c8' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/GetPDFActions.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14161152465d358307472a82-42170298',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ENABLE_PDFMAKER' => 0,
    'SEND_EMAIL_PDF_ACTION' => 0,
    'SEND_EMAIL_PDF_ACTION_TYPE' => 0,
    'EDIT_AND_EXPORT_ACTION' => 0,
    'SAVE_AS_DOC_ACTION' => 0,
    'EXPORT_TO_RTF_ACTION' => 0,
    'MODULE' => 0,
    'TEMPLATE_LANGUAGES' => 0,
    'CURRENT_LANGUAGE' => 0,
    'lang_key' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d3583074c302',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d3583074c302')) {function content_5d3583074c302($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/vtigercrm/libraries/Smarty/libs/plugins/function.html_options.php';
?>
<?php if ($_smarty_tpl->tpl_vars['ENABLE_PDFMAKER']->value=='true'){?>
    
    <li>
        <a href="javascript:;" class="PDFMakerDownloadPDF PDFMakerTemplateAction"><i title="<?php echo vtranslate('LBL_EXPORT','PDFMaker');?>
" class="fa fa-download"></i>&nbsp;<?php echo vtranslate('LBL_EXPORT','PDFMaker');?>
</a>
    </li>
    
    <li>
        <a href="javascript:;" class="PDFModalPreview PDFMakerTemplateAction"><i title="<?php echo vtranslate('LBL_EXPORT','PDFMaker');?>
" class="fa fa-file-pdf-o"></i>&nbsp;<?php echo vtranslate('LBL_PREVIEW','PDFMaker');?>
</a>
    </li>
    
    <?php if ($_smarty_tpl->tpl_vars['SEND_EMAIL_PDF_ACTION']->value=="1"){?>
        <li>
            <a href="javascript:;" class="sendEmailWithPDF PDFMakerTemplateAction" data-sendtype="<?php echo $_smarty_tpl->tpl_vars['SEND_EMAIL_PDF_ACTION_TYPE']->value;?>
"><i class="fa fa-send" aria-hidden="true"></i>&nbsp;<?php echo vtranslate('LBL_SEND_EMAIL');?>
</a>
        </li>
    <?php }?>
    
    <?php if ($_smarty_tpl->tpl_vars['EDIT_AND_EXPORT_ACTION']->value=="1"){?>
        <li>
            <a href="javascript:;" class="editPDF PDFMakerTemplateAction"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;<?php echo vtranslate('LBL_EDIT');?>
 <?php echo vtranslate('LBL_AND');?>
 <?php echo vtranslate('LBL_EXPORT','PDFMaker');?>
</a>
        </li>
    <?php }?>
    
    <?php if ($_smarty_tpl->tpl_vars['SAVE_AS_DOC_ACTION']->value=="1"){?>
        <li>
            <a href="javascript:;" class="savePDFToDoc PDFMakerTemplateAction"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;<?php echo vtranslate('LBL_SAVEASDOC','PDFMaker');?>
</a>
        </li>
    <?php }?>
    
    <?php if ($_smarty_tpl->tpl_vars['EXPORT_TO_RTF_ACTION']->value=="1"){?>
        <li>
            <a href="javascript:;" class="PDFMakerTemplateAction"><?php echo vtranslate('LBL_EXPORT_TO_RTF','PDFMaker');?>
</a>
        </li>
    <?php }?>
    <li class="dropdown-header">
        <span class="fa fa-wrench" aria-hidden="true" title="<?php echo vtranslate('LBL_SETTINGS','PDFMaker');?>
"></span> <?php echo vtranslate('LBL_SETTINGS','PDFMaker');?>

    </li>
    
    <?php if ($_smarty_tpl->tpl_vars['MODULE']->value=='Invoice'||$_smarty_tpl->tpl_vars['MODULE']->value=='SalesOrder'||$_smarty_tpl->tpl_vars['MODULE']->value=='PurchaseOrder'||$_smarty_tpl->tpl_vars['MODULE']->value=='Quotes'||$_smarty_tpl->tpl_vars['MODULE']->value=='Receiptcards'||$_smarty_tpl->tpl_vars['MODULE']->value=='Issuecards'){?>
        <li>
            <a href="javascript:;" class="showPDFBreakline"><?php echo vtranslate('LBL_PRODUCT_BREAKLINE','PDFMaker');?>
</a>
        </li>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['MODULE']->value=='Invoice'||$_smarty_tpl->tpl_vars['MODULE']->value=='SalesOrder'||$_smarty_tpl->tpl_vars['MODULE']->value=='PurchaseOrder'||$_smarty_tpl->tpl_vars['MODULE']->value=='Quotes'||$_smarty_tpl->tpl_vars['MODULE']->value=='Receiptcards'||$_smarty_tpl->tpl_vars['MODULE']->value=='Issuecards'||$_smarty_tpl->tpl_vars['MODULE']->value=='Products'){?>
    <li>
        <a href="javascript:;" class="showProductImages"><?php echo vtranslate('LBL_PRODUCT_IMAGE','PDFMaker');?>
</a>
    </li>
    <?php }?>

    <?php if (sizeof($_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value)>1){?>
        <li class="dropdown-header">
            <i class="fa fa-language" title="<?php echo vtranslate('LBL_PDF_LANGUAGE','PDFMaker');?>
"></i> <?php echo vtranslate('LBL_PDF_LANGUAGE','PDFMaker');?>

        </li>
        <li>
            <select name="template_language" id="template_language" class="col-lg-12">
                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value,'selected'=>$_smarty_tpl->tpl_vars['CURRENT_LANGUAGE']->value),$_smarty_tpl);?>

            </select>
        </li>
    <?php }else{ ?>
        <?php  $_smarty_tpl->tpl_vars["lang"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["lang"]->_loop = false;
 $_smarty_tpl->tpl_vars["lang_key"] = new Smarty_Variable;
 $_from = ($_smarty_tpl->tpl_vars['TEMPLATE_LANGUAGES']->value); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["lang"]->key => $_smarty_tpl->tpl_vars["lang"]->value){
$_smarty_tpl->tpl_vars["lang"]->_loop = true;
 $_smarty_tpl->tpl_vars["lang_key"]->value = $_smarty_tpl->tpl_vars["lang"]->key;
?>
            <input type="hidden" name="template_language" id="template_language" value="<?php echo $_smarty_tpl->tpl_vars['lang_key']->value;?>
"/>
        <?php } ?>
    <?php }?>
<?php }else{ ?>
    <div class="row-fluid">
        <div class="span10">
            <ul class="nav nav-list">
                <li><a href="index.php?module=PDFMaker&view=List"><?php echo vtranslate('LBL_PLEASE_FINISH_INSTALLATION','PDFMaker');?>
</a></li>
            </ul>
        </div>
    </div>
<?php }?><?php }} ?>