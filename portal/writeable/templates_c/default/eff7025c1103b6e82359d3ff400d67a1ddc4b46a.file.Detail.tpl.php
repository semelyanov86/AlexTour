<?php /* Smarty version Smarty-3.1.19, created on 2019-11-08 12:00:01
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Portal/Detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8807358415dc53ca13f9943-00388983%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eff7025c1103b6e82359d3ff400d67a1ddc4b46a' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Portal/Detail.tpl',
      1 => 1570520742,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8807358415dc53ca13f9943-00388983',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dc53ca1486b61_55861321',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dc53ca1486b61_55861321')) {function content_5dc53ca1486b61_55861321($_smarty_tpl) {?>

<div class="container-fluid" ng-controller="<?php echo portal_componentjs_class($_smarty_tpl->tpl_vars['MODULE']->value,'DetailView_Component');?>
">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/DetailContentBefore.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/DetailContent.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/DetailRelatedContent.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/DetailContentAfter.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
</div>
<hr>
<?php }} ?>
