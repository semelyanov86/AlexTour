<?php /* Smarty version Smarty-3.1.19, created on 2019-11-08 02:15:15
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Faq/Index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19845683325dc4b393f26d12-42065925%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dcebf9a67c2e2a8380e6e4cba546b26e472337e1' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Faq/Index.tpl',
      1 => 1570520743,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19845683325dc4b393f26d12-42065925',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dc4b394154ff0_59027801',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dc4b394154ff0_59027801')) {function content_5dc4b394154ff0_59027801($_smarty_tpl) {?>

<div class="container-fluid"  ng-controller="<?php echo portal_componentjs_class($_smarty_tpl->tpl_vars['MODULE']->value,'IndexView_Component');?>
">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/IndexContent.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
</div>
<?php }} ?>
