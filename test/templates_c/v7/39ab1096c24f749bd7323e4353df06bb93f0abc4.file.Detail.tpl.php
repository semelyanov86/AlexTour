<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:37:34
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15454686715d0be07ec764d2-30622649%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39ab1096c24f749bd7323e4353df06bb93f0abc4' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Detail.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15454686715d0be07ec764d2-30622649',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TEMPLATEID' => 0,
    'PARENTTAB' => 0,
    'IS_BLOCK' => 0,
    'FILENAME' => 0,
    'DESCRIPTION' => 0,
    'MODULENAME' => 0,
    'IS_ACTIVE' => 0,
    'IS_DEFAULT' => 0,
    'WATERMARK' => 0,
    'MODULE' => 0,
    'ISSTYLESACTIVE' => 0,
    'STYLES_LIST' => 0,
    'style_data' => 0,
    'BODY' => 0,
    'HEADER' => 0,
    'FOOTER' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0be07ecb13a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0be07ecb13a')) {function content_5d0be07ecb13a($_smarty_tpl) {?>
<div class="detailview-content container-fluid"><div class="details row"><form id="detailView" method="post" action="index.php" name="etemplatedetailview" onsubmit="VtigerJS_DialogBox.block();"><input type="hidden" name="action" value=""><input type="hidden" name="view" value=""><input type="hidden" name="module" value="PDFMaker"><input type="hidden" name="retur_module" value="PDFMaker"><input type="hidden" name="return_action" value="PDFMaker"><input type="hidden" name="return_view" value="Detail"><input type="hidden" name="templateid" value="<?php echo $_smarty_tpl->tpl_vars['TEMPLATEID']->value;?>
"><input type="hidden" name="parenttab" value="<?php echo $_smarty_tpl->tpl_vars['PARENTTAB']->value;?>
"><input type="hidden" name="isDuplicate" value="false"><input type="hidden" name="subjectChanged" value=""><input id="recordId" value="<?php echo $_smarty_tpl->tpl_vars['TEMPLATEID']->value;?>
" type="hidden"><div class="col-lg-12"><div class="left-block col-lg-4"><div class="summaryView"><div class="summaryViewHeader"><h4 class="display-inline-block"><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value==true){?><?php echo vtranslate('LBL_HEADER_INFORMATIONS','PDFMaker');?>
<?php }else{ ?><?php echo vtranslate('LBL_TEMPLATE_INFORMATIONS','PDFMaker');?>
<?php }?></h4></div><div class="summaryViewFields"><div class="recordDetails"><table class="summary-table no-border"><tbody><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('LBL_PDF_NAME','PDFMaker');?>
</label></td><td class="fieldValue"><?php echo $_smarty_tpl->tpl_vars['FILENAME']->value;?>
</td></tr><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('LBL_DESCRIPTION','PDFMaker');?>
</label></td><td class="fieldValue" valign=top><?php echo $_smarty_tpl->tpl_vars['DESCRIPTION']->value;?>
</td></tr><?php if ($_smarty_tpl->tpl_vars['MODULENAME']->value!=''){?><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('LBL_MODULENAMES','PDFMaker');?>
</label></td><td class="fieldValue" valign=top><?php echo $_smarty_tpl->tpl_vars['MODULENAME']->value;?>
</td></tr><?php }?><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('Status');?>
</label></td><td class="fieldValue" valign=top><?php echo $_smarty_tpl->tpl_vars['IS_ACTIVE']->value;?>
</td></tr><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('LBL_SETASDEFAULT','PDFMaker');?>
</label></td><td class="fieldValue" valign=top><?php echo $_smarty_tpl->tpl_vars['IS_DEFAULT']->value;?>
</td></tr><?php }?><?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['type']!="none"){?><tr class="summaryViewEntries"><td class="fieldLabel"><label class="muted textOverflowEllipsis"><?php echo vtranslate('Watermark','PDFMaker');?>
 (<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['type_label'];?>
)</label></td><td class="fieldValue" valign=top><?php if ($_smarty_tpl->tpl_vars['WATERMARK']->value['type']=="image"){?><a href="<?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['image_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['image_name'];?>
</a><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['WATERMARK']->value['text'];?>
<?php }?></td></tr><?php }?></tbody></table></div></div></div><br><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="summaryView"><div class="summaryViewHeader"><h4 class="display-inline-block"><?php echo vtranslate('LBL_DISPLAY_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h4><div class="pull-right"><button type="button" class="btn btn-default editDisplayConditions" data-url="index.php?module=PDFMaker&view=EditDisplayConditions&templateid=<?php echo $_smarty_tpl->tpl_vars['TEMPLATEID']->value;?>
">&nbsp;<?php echo vtranslate('LBL_EDIT',$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;<?php echo vtranslate('LBL_CONDITIONS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button></div></div><div class="summaryViewFields"><div class="recordDetails"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('DetailDisplayConditions.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div></div></div><br><?php if ($_smarty_tpl->tpl_vars['ISSTYLESACTIVE']->value=="yes"){?><div class="summaryView"><div class="summaryViewHeader"><h4 class="display-inline-block"><?php echo vtranslate('LBL_CSS_STYLE_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h4><div class="pull-right"><button type="button" class="btn btn-default addButton addStyleContentBtn" data-modulename="ITS4YouStyles"><?php echo vtranslate('LBL_ADD');?>
&nbsp;<?php echo vtranslate('SINGLE_ITS4YouStyles','ITS4YouStyles');?>
</button>&nbsp;&nbsp;<button type="button" class="btn btn-default addButton selectRelationStyle" data-modulename="ITS4YouStyles">&nbsp;<?php echo vtranslate('LBL_SELECT');?>
&nbsp;<?php echo vtranslate('SINGLE_ITS4YouStyles','ITS4YouStyles');?>
</button></div></div><br><div class="summaryWidgetContainer noContent"><?php if ($_smarty_tpl->tpl_vars['STYLES_LIST']->value){?><div id="table-content" class="table-container"><table id="listview-table" class="table listview-table"><thead><tr class="listViewContentHeader"><th style="width:55px;"></th><th nowrap><?php echo vtranslate('Name','ITS4YouStyles');?>
</th><th nowrap><?php echo vtranslate('Priority','ITS4YouStyles');?>
</th></tr></thead><tbody class="overflow-y"><?php  $_smarty_tpl->tpl_vars['style_data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['style_data']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['STYLES_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['style_data']->key => $_smarty_tpl->tpl_vars['style_data']->value){
$_smarty_tpl->tpl_vars['style_data']->_loop = true;
?><tr class="" data-id="<?php echo $_smarty_tpl->tpl_vars['style_data']->value['id'];?>
"><td style="width:55px"><?php if ($_smarty_tpl->tpl_vars['style_data']->value['iseditable']=="yes"){?><span class="actionImages">&nbsp;&nbsp;&nbsp;<a name="styleEdit" data-url="index.php?module=ITS4YouStyles&view=Edit&record=<?php echo $_smarty_tpl->tpl_vars['style_data']->value['id'];?>
"><i title="Edit" class="fa fa-pencil"></i></a> &nbsp;&nbsp;<a class="relationDelete"><i title="Unlink" class="vicon-linkopen"></i></a></span><?php }?></td><td class="listViewEntryValue textOverflowEllipsis " width="%" nowrap><a name="styleEdit" data-url="index.php?module=ITS4YouStyles&view=Detail&record=<?php echo $_smarty_tpl->tpl_vars['style_data']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['style_data']->value['name'];?>
</a></td><td class="listViewEntryValue textOverflowEllipsis " width="%" nowrap><?php echo $_smarty_tpl->tpl_vars['style_data']->value['priority'];?>
</td></tr><?php } ?></tbody></table></div><?php }else{ ?><p class="textAlignCenter"><?php echo vtranslate('LBL_NO_RELATED',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate('LBL_STYLES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</p><?php }?></div></div><br><?php }?><?php }?></div><div class="middle-block col-lg-8"><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div id="ContentEditorTabs"><ul class="nav nav-pills"><li class="active" data-type="body"><a href="#body_div2" aria-expanded="false" style="margin-right: 5px" data-toggle="tab"><?php echo vtranslate('LBL_BODY',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li data-type="header"><a href="#header_div2" aria-expanded="false" style="margin-right: 5px" data-toggle="tab"><?php echo vtranslate('LBL_HEADER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><li data-type="footer"><a href="#footer_div2" aria-expanded="false" data-toggle="tab"><?php echo vtranslate('LBL_FOOTER_TAB',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li></ul></div><?php }?><div class="tab-content"><div class="tab-pane active" id="body_div2"><div id="previewcontent_body" class="hide"><?php echo $_smarty_tpl->tpl_vars['BODY']->value;?>
</div><iframe id="preview_body" class="col-lg-12" style="height:1200px;"></iframe></div><?php if ($_smarty_tpl->tpl_vars['IS_BLOCK']->value!=true){?><div class="tab-pane" id="header_div2"><div id="previewcontent_header" class="hide"><?php echo $_smarty_tpl->tpl_vars['HEADER']->value;?>
</div><iframe id="preview_header" class="col-lg-12" style="height:500px;"></iframe></div><div class="tab-pane" id="footer_div2"><div id="previewcontent_footer" class="hide"><?php echo $_smarty_tpl->tpl_vars['FOOTER']->value;?>
</div><iframe id="preview_footer" class="col-lg-12" style="height:500px;"></iframe></div><?php }?></div></div></div><div class="textAlignCenter" style="color: rgb(153, 153, 153);"><?php echo vtranslate('PDF_MAKER','PDFMaker');?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate('COPYRIGHT','PDFMaker');?>
</div></form></div></div><script type="text/javascript">jQuery(document).ready(function() {PDFMaker_Detail_Js.setPreviewContent('body');PDFMaker_Detail_Js.setPreviewContent('header');PDFMaker_Detail_Js.setPreviewContent('footer');});</script><?php }} ?>