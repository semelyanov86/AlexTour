<?php /* Smarty version Smarty-3.1.7, created on 2019-03-21 19:05:09
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Vtiger/ListViewPreProcess.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14036846525c93b63514fcf0-58250793%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a3755348b169b5b3f6a68dc7999d675d7316890' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Vtiger/ListViewPreProcess.tpl',
      1 => 1508495595,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14036846525c93b63514fcf0-58250793',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'CURRENT_USER_MODEL' => 0,
    'LEFTPANELHIDE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c93b635164d7',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c93b635164d7')) {function content_5c93b635164d7($_smarty_tpl) {?>

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
<div class="main-container main-container-<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
">
		<?php $_smarty_tpl->tpl_vars['LEFTPANELHIDE'] = new Smarty_variable($_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('leftpanelhide'), null, 0);?>
		<div id="modnavigator" class="module-nav">
			<div class="hidden-xs hidden-sm mod-switcher-container">
				<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("partials/Menubar.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			</div>
		</div>
		<div id="sidebar-essentials" class="sidebar-essentials <?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value=='1'){?> hide <?php }?>">
			<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path("partials/SidebarEssentials.tpl",$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		</div>
		<div class="listViewPageDiv content-area <?php if ($_smarty_tpl->tpl_vars['LEFTPANELHIDE']->value=='1'){?> full-width <?php }?>" id="listViewContent">

<?php }} ?>