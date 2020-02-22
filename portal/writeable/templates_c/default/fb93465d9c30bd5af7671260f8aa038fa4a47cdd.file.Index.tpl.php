<?php /* Smarty version Smarty-3.1.19, created on 2019-11-18 06:29:49
         compiled from "/var/www/html/crm.avelacom/portal/layouts/default/templates/Faq/Index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15828588635dd23a5d431435-60653483%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb93465d9c30bd5af7671260f8aa038fa4a47cdd' => 
    array (
      0 => '/var/www/html/crm.avelacom/portal/layouts/default/templates/Faq/Index.tpl',
      1 => 1570520743,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15828588635dd23a5d431435-60653483',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dd23a5d43d1a6_06962937',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dd23a5d43d1a6_06962937')) {function content_5dd23a5d43d1a6_06962937($_smarty_tpl) {?>

<div class="container-fluid"  ng-controller="<?php echo portal_componentjs_class($_smarty_tpl->tpl_vars['MODULE']->value,'IndexView_Component');?>
">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve($_smarty_tpl->tpl_vars['MODULE']->value,"partials/IndexContent.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
</div>
<?php }} ?>
