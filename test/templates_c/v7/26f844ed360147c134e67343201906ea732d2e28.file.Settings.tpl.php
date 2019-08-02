<?php /* Smarty version Smarty-3.1.7, created on 2019-08-01 11:26:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/Settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4054995285c9f7f28793223-69693919%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '26f844ed360147c134e67343201906ea732d2e28' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTELabelEditor/Settings.tpl',
      1 => 1564645310,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4054995285c9f7f28793223-69693919',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c9f7f288213a',
  'variables' => 
  array (
    'QUALIFIED_MODULE' => 0,
    'LANGUAGES' => 0,
    'KEY' => 0,
    'CURRENT_LANGUAGE' => 0,
    'LANGUAGE_LABEL' => 0,
    'CURRENT_LANGUAGE_DIR' => 0,
    'CURRENT_LANGUAGE_DIR_PERMISSIONS' => 0,
    'MODULES_FILES_LIST' => 0,
    'FILE_NAME' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9f7f288213a')) {function content_5c9f7f288213a($_smarty_tpl) {?>
<style>.label-editor-info{border: 1px solid rgb(217, 217, 217);border-left: #52a9cd solid 4px;max-height: 245px;height: 245px;}.label-editor-info > .label-info{color: #52a9cd;background-color: white !important;}.label-editor-info > .content-info{resize: none;border: none;width: 100%;color: #9b9997;max-height: 140px;height: 140px;}</style><div class="editViewPageDiv"><div class="col-sm-12 col-xs-12" id="EditView"><div class="editViewHeader"><div class="row"><div class="col-lg-6 col-md-6 col-lg-pull-0"><h4><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp1=ob_get_clean();?><?php echo vtranslate('MODULE_LBL',$_tmp1);?>
</h4></div><div class="col-lg-6 col-md-6 col-lg-pull-0"><button id="restore_from_backup" class="pull-right btn btn-default"><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp2=ob_get_clean();?><?php echo vtranslate('Restore from Backup',$_tmp2);?>
</button></div></div></div><hr style="margin-top: 0px !important;"><div class="editViewBody"><div class="editViewContents"><div class="row"><div class="col-sm-12 col-xs-12"><div class="row"><div class="col-sm-7 col-xs-7 form-horizontal"><div class="form-group"><label for="module_lang" class="col-sm-4"><span><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp3=ob_get_clean();?><?php echo vtranslate('Select Language',$_tmp3);?>
</span></label><div class="setting-field col-sm-8"><select class="inputElement select2" id="module_lang" name="module_lang"><?php  $_smarty_tpl->tpl_vars['LANGUAGE_LABEL'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->_loop = false;
 $_smarty_tpl->tpl_vars['KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['LANGUAGES']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->key => $_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->value){
$_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->_loop = true;
 $_smarty_tpl->tpl_vars['KEY']->value = $_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['CURRENT_LANGUAGE']->value==$_smarty_tpl->tpl_vars['KEY']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['LANGUAGE_LABEL']->value;?>
</option><?php } ?></select><div style="margin-top: 10px;" id="lang_dir"><?php echo $_smarty_tpl->tpl_vars['CURRENT_LANGUAGE_DIR']->value;?>
&nbsp;&nbsp;&nbsp;&nbsp;<b style="color: <?php if ($_smarty_tpl->tpl_vars['CURRENT_LANGUAGE_DIR_PERMISSIONS']->value=='OK'){?>green<?php }else{ ?>red<?php }?>">(Permissions - <?php echo $_smarty_tpl->tpl_vars['CURRENT_LANGUAGE_DIR_PERMISSIONS']->value;?>
)</b></div></div></div><div class="form-group"><label for="lang_files" class="col-sm-4"><span><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp4=ob_get_clean();?><?php echo vtranslate('Select Module/File',$_tmp4);?>
</span></label><div class="setting-field col-sm-8"><select class="inputElement select2" id="lang_files" name="lang_files"><option value=""><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp5=ob_get_clean();?><?php echo vtranslate('LBL_SELECT_OPTION',$_tmp5);?>
</option><?php  $_smarty_tpl->tpl_vars['FILE_NAME'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['FILE_NAME']->_loop = false;
 $_smarty_tpl->tpl_vars['KEY'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MODULES_FILES_LIST']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['FILE_NAME']->key => $_smarty_tpl->tpl_vars['FILE_NAME']->value){
$_smarty_tpl->tpl_vars['FILE_NAME']->_loop = true;
 $_smarty_tpl->tpl_vars['KEY']->value = $_smarty_tpl->tpl_vars['FILE_NAME']->key;
?><option value="<?php echo $_smarty_tpl->tpl_vars['FILE_NAME']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['FILE_NAME']->value;?>
</option><?php } ?></select><div style="margin-top: 10px;" id="file_info"></div></div></div><div class="form-group"><label for="module_lang" class="col-sm-4"><span><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp6=ob_get_clean();?><?php echo vtranslate('Search',$_tmp6);?>
</span></label><div class="setting-field col-sm-8"><div class="row"><div class="setting-field col-sm-10"><input type="text" class="inputElement" name="search_lang_value" id="search_lang_value" required/></div><div class="setting-field col-sm-2"><button class="btn btn-default" id="search_lang"><i class="fa fa-search"></i></button></div></div></div></div></div><div class="col-sm-5 col-xs-5 label-editor-info"><div class="label-info"><h5><span class="glyphicon glyphicon-info-sign"></span> Info</h5></div><span><p>To edit the label, select language, module/file and the available labels will show up. You can also search for certain word e.g "Add Contact" and it will show all files that consist that label - you can then select the file and change the label.</p><p>Most module file names as straight forward e.g Contacts.php is Contacts module, however there are some that not as clear. Please see the list below:</p><p>Accounts.php > Organizations<br>HelpDesk.php > Tickets<br>Potentials.php > Opportunities<br>Events.php > Calendar</p></span></div></div></div></div></div></div><div class="editViewHeader"><div class="row"><div class="col-lg-12 col-md-12 col-lg-pull-0"><h4><?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value;?>
<?php $_tmp7=ob_get_clean();?><?php echo vtranslate('Available Labels',$_tmp7);?>
</h4></div></div></div><hr style="margin-top: 0px !important;"><div id="fields_result" style="margin-bottom: 15px;"></div></div></div><?php }} ?>