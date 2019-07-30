<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:34:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/SidebarEssentials.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1918619685d0bdfd9d3f512-20264385%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '31db281434bb381c1c7c80ee3f7236793121c361' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/SidebarEssentials.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1918619685d0bdfd9d3f512-20264385',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
    'MODE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0bdfd9d4652',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0bdfd9d4652')) {function content_5d0bdfd9d4652($_smarty_tpl) {?>
<div class="sidebar-menu">
    <div class="module-filters" id="module-filters">
        <div class="sidebar-container lists-menu-container">
            <div class="sidebar-header clearfix">
                <h5 class="pull-left"><?php echo vtranslate('LBL_LISTS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</h5>
            </div>
            <hr>
            <div class="menu-scroller scrollContainer" style="position:relative; top:0; left:0;">
				<div class="list-menu-content">
                    <ul class="lists-menu">
                        <li style="font-size:12px;" class='listViewFilter <?php if ($_smarty_tpl->tpl_vars['MODE']->value!="Blocks"){?>active<?php }?>'>
                             <a class="filterName listViewFilterElipsis" href="index.php?module=PDFMaker&view=List"><?php echo vtranslate('LBL_PDF_TEMPLATES_LIST',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                        </li>
                        <li style="font-size:12px;" class='listViewFilter <?php if ($_smarty_tpl->tpl_vars['MODE']->value=="Blocks"){?>active<?php }?>'>
                            <a class="filterName listViewFilterElipsis" href="index.php?module=PDFMaker&view=List&mode=Blocks"><?php echo vtranslate('LBL_BLOCKS_LIST',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }} ?>