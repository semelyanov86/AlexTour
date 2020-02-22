<?php /* Smarty version Smarty-3.1.19, created on 2019-11-18 06:47:25
         compiled from "/var/www/html/crm.avelacom/portal/layouts/default/templates/Portal/partials/DetailContentBefore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11738024495dd23e7d18e4e1-94754174%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fcd8ea8c22fc80bae0a89fd9d30a2976a7861e09' => 
    array (
      0 => '/var/www/html/crm.avelacom/portal/layouts/default/templates/Portal/partials/DetailContentBefore.tpl',
      1 => 1570520844,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11738024495dd23e7d18e4e1-94754174',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dd23e7d18ff78_62488982',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dd23e7d18ff78_62488982')) {function content_5dd23e7d18ff78_62488982($_smarty_tpl) {?>


    <div class="col-lg-12 col-md-12 col-sm-7 col-xs-7 detail-header detail-header-row">
      <h3 class="fsmall">
        <detail-navigator>
          <span>
            <a ng-click="navigateBack(module)" style="font-size:small;">{{ptitle}}
            </a>
            </span>
        </detail-navigator>{{record[header]}}
        <button ng-if="isEditable" class="btn btn-primary attach-files-ticket" ng-click="editRecord(module,id)">{{'Edit'|translate}} {{ptitle}}</button>
      </h3>
    </div>
</div>

<hr class="hrHeader">
<div class="container-fluid">

<?php }} ?>
