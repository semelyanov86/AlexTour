<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:35:00
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/ListPDFTemplatesContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17595009585d0bdfe4a6b3c9-32776922%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99926f3bac876f7912a561aaac6014d373064bf5' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/ListPDFTemplatesContents.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17595009585d0bdfe4a6b3c9-32776922',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'DIR' => 0,
    'ORDERBY' => 0,
    'SEARCH_DETAILS' => 0,
    'LISTVIEW_ENTRIES_COUNT' => 0,
    'MODULE' => 0,
    'name_dir' => 0,
    'MODE' => 0,
    'dir_img' => 0,
    'customsort_img' => 0,
    'module_dir' => 0,
    'description_dir' => 0,
    'sharingtype_dir' => 0,
    'VERSION_TYPE' => 0,
    'SEARCH_FILENAMEVAL' => 0,
    'SEARCHSELECTBOXDATA' => 0,
    'SEARCH_FORMODULEVAL' => 0,
    'SEARCH_DESCRIPTIONVAL' => 0,
    'SHARINGTYPES' => 0,
    'SEARCH_SHARINGTYPEVAL' => 0,
    'SEARCH_OWNERVAL' => 0,
    'STATUSOPTIONS' => 0,
    'SEARCH_STATUSVAL' => 0,
    'PDFTEMPLATES' => 0,
    'template' => 0,
    'SELECTED_MENU_CATEGORY' => 0,
    'LABEL' => 0,
    'ADDTOURL' => 0,
    'VERSION' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0bdfe4b5932',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0bdfe4b5932')) {function content_5d0bdfe4b5932($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/vtigercrm/libraries/Smarty/libs/plugins/function.html_options.php';
?>
<?php if ($_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["dir_img"] = new Smarty_variable('<i class="fa fa-sort fa-sort-asc"></i>', null, 0);?>
<?php }else{ ?>
    <?php $_smarty_tpl->tpl_vars["dir_img"] = new Smarty_variable('<i class="fa fa-sort fa-sort-desc"></i>', null, 0);?>
<?php }?>
<?php $_smarty_tpl->tpl_vars["customsort_img"] = new Smarty_variable('<i class="fa fa-sort customsort"></i>', null, 0);?>
<?php $_smarty_tpl->tpl_vars["name_dir"] = new Smarty_variable("ASC", null, 0);?>
<?php $_smarty_tpl->tpl_vars["module_dir"] = new Smarty_variable("ASC", null, 0);?>
<?php $_smarty_tpl->tpl_vars["description_dir"] = new Smarty_variable("ASC", null, 0);?>
<?php $_smarty_tpl->tpl_vars["order_dir"] = new Smarty_variable("ASC", null, 0);?>
<?php $_smarty_tpl->tpl_vars["sharingtype_dir"] = new Smarty_variable("ASC", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='filename'&&$_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["name_dir"] = new Smarty_variable("DESC", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='module'&&$_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["module_dir"] = new Smarty_variable("DESC", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='description'&&$_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["description_dir"] = new Smarty_variable("DESC", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='order'&&$_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["order_dir"] = new Smarty_variable("DESC", null, 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['ORDERBY']->value=='sharingtype'&&$_smarty_tpl->tpl_vars['DIR']->value=='ASC'){?>
    <?php $_smarty_tpl->tpl_vars["sharingtype_dir"] = new Smarty_variable("DESC", null, 0);?>
<?php }?>
<div class="col-sm-12 col-xs-12 ">
    <input type="hidden" name="idlist" >
    <input type="hidden" name="module" value="PDFMaker">
    <input type="hidden" name="parenttab" value="Tools">
    <input type="hidden" name="view" value="List">
    <input type="hidden" name="cvid" value="1" />
    <input type="hidden" name="action" value="">
    <input type="hidden" name="orderBy" id="orderBy" value="<?php echo $_smarty_tpl->tpl_vars['ORDERBY']->value;?>
">
    <input type="hidden" name="sortOrder" id="sortOrder" value="<?php echo $_smarty_tpl->tpl_vars['DIR']->value;?>
">
    	<input type="hidden" name="currentSearchParams" value="<?php echo Vtiger_Util_Helper::toSafeHTML(Zend_JSON::encode($_smarty_tpl->tpl_vars['SEARCH_DETAILS']->value));?>
" id="currentSearchParams" />
    <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('ListPDFActions.tpl','PDFMaker'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    <div id="table-content" class="table-container">
        <form name='list' id='listedit' action='' onsubmit="return false;">
            <table id="listview-table" class="table <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRIES_COUNT']->value=='0'){?>listview-table-norecords <?php }?> listview-table">
                <thead>
                <tr class="listViewContentHeader">
                    <th>
                        <div class="table-actions">
                            <div class="dropdown" style="float:left;">
                                <span class="input dropdown-toggle" data-toggle="dropdown" title="<?php echo vtranslate('LBL_CLICK_HERE_TO_SELECT_ALL_RECORDS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
">
                                    <input class="listViewEntriesMainCheckBox" type="checkbox">
                                </span>
                            </div>
                        </div>
                    </th>
                    <th nowrap="nowrap"><a href="#" data-columnname="name" data-nextsortorderval="<?php echo $_smarty_tpl->tpl_vars['name_dir']->value;?>
" class="listViewContentHeaderValues"><?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?><?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='filename'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['customsort_img']->value;?>
<?php }?><?php }?>&nbsp;&nbsp;<?php echo vtranslate("LBL_PDF_NAME",$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</a></th>
                    <th nowrap="nowrap"><a href="#" data-columnname="module" data-nextsortorderval="<?php echo $_smarty_tpl->tpl_vars['module_dir']->value;?>
" class="listViewContentHeaderValues"><?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?><?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='module'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['customsort_img']->value;?>
<?php }?><?php }?>&nbsp;&nbsp;<?php echo vtranslate("LBL_MODULENAMES",$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</a></th>
                    <th nowrap="nowrap"><a href="#" data-columnname="description" data-nextsortorderval="<?php echo $_smarty_tpl->tpl_vars['description_dir']->value;?>
" class="listViewContentHeaderValues"><?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?><?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='description'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['customsort_img']->value;?>
<?php }?><?php }?>&nbsp;&nbsp;<?php echo vtranslate("LBL_DESCRIPTION",$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</a></th>
                    <?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?>
                        <th nowrap="nowrap"><a href="#" data-columnname="sharingtype" data-nextsortorderval="<?php echo $_smarty_tpl->tpl_vars['sharingtype_dir']->value;?>
" class="listViewContentHeaderValues"><?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?><?php if ($_smarty_tpl->tpl_vars['ORDERBY']->value=='sharingtype'){?><?php echo $_smarty_tpl->tpl_vars['dir_img']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['customsort_img']->value;?>
<?php }?><?php }?>&nbsp;&nbsp;<?php echo vtranslate("LBL_SHARING_TAB",$_smarty_tpl->tpl_vars['MODULE']->value);?>
&nbsp;</a></th>
                        <th nowrap="nowrap"><?php echo vtranslate("LBL_TEMPLATE_OWNER",$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th>
                        <?php if ($_smarty_tpl->tpl_vars['VERSION_TYPE']->value!='deactivate'){?><th><?php echo vtranslate("Status");?>
</th><?php }?>
                    <?php }else{ ?>
                        <th nowrap="nowrap"><?php echo vtranslate("LBL_BLOCK",$_smarty_tpl->tpl_vars['MODULE']->value);?>
</th>
                    <?php }?>
                </tr>
                <?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?>
                    <tr class="searchRow">
                        <th inline-search-btn>
                            <div class="table-actions">
                                <button class="btn btn-success btn-sm" data-trigger="listSearch"><?php echo vtranslate("LBL_SEARCH",$_smarty_tpl->tpl_vars['MODULE']->value);?>
</button>
                            </div>
                        </th>
                        <th>
                            <input type="text" class="listSearchContributor inputElement" data-field-type="string" name="filename" data-fieldinfo='{"column":"filename","type":"string","name":"filename","label":"<?php echo vtranslate("LBL_PDF_NAME",$_smarty_tpl->tpl_vars['MODULE']->value);?>
"}' value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_FILENAMEVAL']->value;?>
">
                        </th>
                        <th>
                            <div class="select2_search_div">
                                <input type="text" class="listSearchContributor inputElement select2_input_element"/>
                                <select class="select2 listSearchContributor" name="formodule" data-fieldinfo='{"column":"formodule","type":"picklist","name":"formodule","label":"<?php echo vtranslate("LBL_MODULENAMES",$_smarty_tpl->tpl_vars['MODULE']->value);?>
"}' style="display: none" >
                                    <option value=""></option>
                                    <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SEARCHSELECTBOXDATA']->value['modules'],'selected'=>$_smarty_tpl->tpl_vars['SEARCH_FORMODULEVAL']->value),$_smarty_tpl);?>

                                </select>
                            </div>
                        </th>
                        <th>
                            <div>
                                <input type="text" class="listSearchContributor inputElement" name="description" data-fieldinfo='' value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_DESCRIPTIONVAL']->value;?>
">
                            </div>
                        </th>
                        <th>
                            <div class="select2_search_div">
                                <input type="text" class="listSearchContributor inputElement select2_input_element"/>
                                <select class="select2 listSearchContributor" name="sharingtype" data-fieldinfo='{"column":"sharingtype","type":"picklist","name":"sharingtype","label":"<?php echo vtranslate("LBL_SHARING_TAB",$_smarty_tpl->tpl_vars['MODULE']->value);?>
"}' style="display: none">
                                    <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SHARINGTYPES']->value,'selected'=>$_smarty_tpl->tpl_vars['SEARCH_SHARINGTYPEVAL']->value),$_smarty_tpl);?>

                                </select>
                            </div>
                        </th>
                        <th>
                            <div class="select2_search_div">
                                <input type="text" class="listSearchContributor inputElement select2_input_element"/>
                                <select class="select2 listSearchContributor" name="owner" data-fieldinfo='{"column":"owner","type":"owner","name":"owner","label":"<?php echo vtranslate("LBL_TEMPLATE_OWNER",$_smarty_tpl->tpl_vars['MODULE']->value);?>
"}' style="display: none">
                                    <option value=""></option>
                                    <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['SEARCHSELECTBOXDATA']->value['owners'],'selected'=>$_smarty_tpl->tpl_vars['SEARCH_OWNERVAL']->value),$_smarty_tpl);?>

                                </select>
                            </div>
                        </th>
                        <th>
                            <div class="select2_search_div">
                                <input type="text" class="listSearchContributor inputElement select2_input_element"/>
                                <select class="select2 listSearchContributor" name="status" data-fieldinfo='{"column":"status","type":"picklist","name":"status","label":"<?php echo vtranslate("Status",$_smarty_tpl->tpl_vars['MODULE']->value);?>
"}' style="display: none">
                                    <option value=""></option>
                                    <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['STATUSOPTIONS']->value,'selected'=>$_smarty_tpl->tpl_vars['SEARCH_STATUSVAL']->value),$_smarty_tpl);?>

                                </select>
                            </div>
                        </th>
                    </tr>
                <?php }?>
                </thead>
                <tbody>
                <?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['PDFTEMPLATES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value){
$_smarty_tpl->tpl_vars['template']->_loop = true;
?>
                    <tr class="listViewEntries" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="font-style:italic;" <?php }?> data-id="<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
" data-recordurl="index.php?module=PDFMaker&view=Detail&templateid=<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
" id="PDFMaker_listView_row_<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
">
                        <td class="listViewRecordActions">
                            <div class="table-actions">
                                        <span class="input" >
                                            <input type="checkbox" class="listViewEntriesCheckBox" value="<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
">
                                        </span>
                                <span class="more dropdown action">
                                            <span href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v icon"></i></span>
                                                <ul class="dropdown-menu">
                                                    <li><a data-id="<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
" href="index.php?module=PDFMaker&view=Detail&templateid=<?php echo $_smarty_tpl->tpl_vars['template']->value['templateid'];?>
&app=<?php echo $_smarty_tpl->tpl_vars['SELECTED_MENU_CATEGORY']->value;?>
"><?php echo vtranslate('LBL_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li>
                                                    <?php if ($_smarty_tpl->tpl_vars['VERSION_TYPE']->value!='deactivate'){?><?php echo $_smarty_tpl->tpl_vars['template']->value['edit'];?>
<?php }?>
                                                </ul>
                                        </span>
                            </div>
                        </td>
                        <td class="listViewEntryValue" ><?php echo $_smarty_tpl->tpl_vars['template']->value['filename'];?>
</td>
                        <td class="listViewEntryValue" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['module'];?>
</a></td>
                        <td class="listViewEntryValue" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['description'];?>
&nbsp;</td>
                        <?php if ($_smarty_tpl->tpl_vars['MODE']->value!='Blocks'){?>
                            <td class="listViewEntryValue" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['sharing'];?>
&nbsp;</td>
                            <td class="listViewEntryValue" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?> nowrap><?php echo $_smarty_tpl->tpl_vars['template']->value['owner'];?>
&nbsp;</td>
                            <?php if ($_smarty_tpl->tpl_vars['VERSION_TYPE']->value!='deactivate'){?><td class="listViewEntryValue" <?php if ($_smarty_tpl->tpl_vars['template']->value['status']==0){?> style="color:#888;" <?php }?>><?php echo $_smarty_tpl->tpl_vars['template']->value['status_lbl'];?>
&nbsp;</td><?php }?>
                        <?php }else{ ?>
                            <td class="listViewEntryValue" style="color:#888;"><?php echo $_smarty_tpl->tpl_vars['template']->value['type'];?>
&nbsp;</td>
                        <?php }?>
                    </tr>
                <?php }
if (!$_smarty_tpl->tpl_vars['template']->_loop) {
?>
                    <tr>
                        <td style="background-color:#efefef;" align="center" colspan="9">
                            <table class="emptyRecordsDiv">
                                <tbody>
                                <tr>
                                    <td>
                                        <?php if ($_smarty_tpl->tpl_vars['MODE']->value=='Blocks'){?>
                                            <?php $_smarty_tpl->tpl_vars["LABEL"] = new Smarty_variable("LBL_BLOCK", null, 0);?>
                                            <?php $_smarty_tpl->tpl_vars["ADDTOURL"] = new Smarty_variable("&mode=Blocks", null, 0);?>
                                        <?php }else{ ?>
                                            <?php $_smarty_tpl->tpl_vars["LABEL"] = new Smarty_variable("LBL_TEMPLATE", null, 0);?>
                                            <?php $_smarty_tpl->tpl_vars["ADDTOURL"] = new Smarty_variable('', null, 0);?>
                                        <?php }?>
                                        <?php echo vtranslate("LBL_NO");?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['LABEL']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo vtranslate("LBL_FOUND",$_smarty_tpl->tpl_vars['MODULE']->value);?>
<br><br>
                                        <a href="index.php?module=PDFMaker&view=Edit<?php echo $_smarty_tpl->tpl_vars['ADDTOURL']->value;?>
"><?php echo vtranslate("LBL_CREATE_NEW");?>
 <?php echo vtranslate($_smarty_tpl->tpl_vars['LABEL']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<br>
<div align="center" class="small" style="color: rgb(153, 153, 153);"><?php echo vtranslate("PDF_MAKER",$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
 <?php echo vtranslate("COPYRIGHT",$_smarty_tpl->tpl_vars['MODULE']->value);?>
</div><?php }} ?>