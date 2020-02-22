<?php /* Smarty version Smarty-3.1.19, created on 2019-11-08 12:00:01
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Portal/partials/DetailContentBefore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5013671265dc53ca149b383-67622271%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2075de14f713f692b872e19b6ac1c806bacd7100' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Portal/partials/DetailContentBefore.tpl',
      1 => 1570520844,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5013671265dc53ca149b383-67622271',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dc53ca14b7238_26942390',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dc53ca14b7238_26942390')) {function content_5dc53ca14b7238_26942390($_smarty_tpl) {?>


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
