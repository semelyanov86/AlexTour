<?php /* Smarty version Smarty-3.1.7, created on 2019-03-27 12:39:33
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Documents/DetailViewPreProcess.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4369398595c9b44d56d01e9-84830038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35b81e457d3ff51cae0ec3fdf922b58a9cdfd0c5' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Documents/DetailViewPreProcess.tpl',
      1 => 1508495595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4369398595c9b44d56d01e9-84830038',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'RECORD' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c9b44d56dd63',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c9b44d56dd63')) {function content_5c9b44d56dd63($_smarty_tpl) {?>

<?php echo $_smarty_tpl->getSubTemplate ("modules/Vtiger/partials/Topbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="container-fluid app-nav">
    <div class="row">
        <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("partials/SidebarHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModuleHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div>
</div>
</nav>    
     <div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class="data">
        </div>
        <div class="modal-dialog">
        </div>
    </div>
<div class="container-fluid main-container">
    <div class="row">
        <div class="detailViewContainer viewContent clearfix">
            <div class="col-sm-12 col-xs-12 content-area" style="padding-left: 15px;">
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("DetailViewHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("DetailViewTagList.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                    </div>
                </div>   
            </div>
                <div class="detailview-content container-fluid">
                    <input id="recordId" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getId();?>
" />
                    <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("ModuleRelatedTabs.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                    <div class="details row" style="margin-top:10px;">
<?php }} ?>