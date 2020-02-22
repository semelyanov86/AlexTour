<?php /* Smarty version Smarty-3.1.19, created on 2019-11-18 07:02:37
         compiled from "/var/www/html/crm.avelacom/portal/layouts/default/templates/HelpDesk/partials/DetailContentBefore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14740258385dd2420d794be2-63110614%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b230b64c7ac6a6e91618754ff9c79520b6459479' => 
    array (
      0 => '/var/www/html/crm.avelacom/portal/layouts/default/templates/HelpDesk/partials/DetailContentBefore.tpl',
      1 => 1570520859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14740258385dd2420d794be2-63110614',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dd2420d7a00f7_92845419',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dd2420d7a00f7_92845419')) {function content_5dd2420d7a00f7_92845419($_smarty_tpl) {?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ticket-detail-header-row ">
  <h3 class="fsmall">
    <detail-navigator>
      <span>
        <a ng-click="navigateBack(module)" style="font-size:small;">{{ptitle}}</a>
      </span>
    </detail-navigator>
      {{record[header]}}
    <button ng-if="(closeButtonDisabled && HelpDeskIsStatusEditable && isEditable)" translate="Mark as closed" class="btn btn-success close-ticket" ng-click="close();"></button>
    <button ng-if="closeButtonDisabled && documentsEnabled" translate="Attach document to this ticket" class="btn btn-primary attach-files-ticket" ng-click="attachDocument('Documents','LBL_ADD_DOCUMENT')"></button>
    <button translate="Edit Ticket" class="btn btn-primary attach-files-ticket" ng-if="isEditable" ng-click="edit(module,id)"></button>
  </h3>
</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  
  <script type="text/javascript" src="<?php echo portal_componentjs_file('Documents');?>
"></script>
  <?php echo $_smarty_tpl->getSubTemplate (portal_template_resolve('Documents',"partials/IndexContentAfter.tpl"), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
