<?php /* Smarty version Smarty-3.1.7, created on 2019-04-24 20:07:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:634703355c93bf6a6731a2-97089478%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c2fcfabf85abdb198710192d7877a4d9c70b9c51' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/Vtiger/Footer.tpl',
      1 => 1556124960,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '634703355c93bf6a6731a2-97089478',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c93bf6a67bad',
  'variables' => 
  array (
    'VTIGER_VERSION' => 0,
    'LANGUAGE_STRINGS' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c93bf6a67bad')) {function content_5c93bf6a67bad($_smarty_tpl) {?>

<footer class="app-footer">
        
        <div class="pull-right footer-icons">
            <!-- SalesPlatform begin #5822 -->
            <!-- <a href="http://community.salesplatform.ru/"><img src="layouts/vlayout/skins/images/forum.png"></a>
            <!-- SalesPlatform end -->
        </div>
        
	<p>
		
                
                
                

            <?php echo vtranslate('POWEREDBY');?>
 <?php echo $_smarty_tpl->tpl_vars['VTIGER_VERSION']->value;?>
 &nbsp;
            &copy; 2004 - <?php echo date('Y');?>
&nbsp&nbsp;
            <a href="//www.vtiger.com" target="_blank">vtiger.com</a>
            &nbsp;|&nbsp;
            
            &copy; 2011 - <?php echo date('Y');?>
&nbsp&nbsp;
            
	</p>
</footer>
</div>
<div id='overlayPage'>
	<!-- arrow is added to point arrow to the clicked element (Ex:- TaskManagement),
	any one can use this by adding "show" class to it -->
	<div class='arrow'></div>
	<div class='data'>
	</div>
</div>
<div id='helpPageOverlay'></div>
<div id="js_strings" class="hide noprint"><?php echo Zend_Json::encode($_smarty_tpl->tpl_vars['LANGUAGE_STRINGS']->value);?>
</div>
<div class="modal myModal fade"></div>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('JSResources.tpl'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>

</html>
<?php }} ?>