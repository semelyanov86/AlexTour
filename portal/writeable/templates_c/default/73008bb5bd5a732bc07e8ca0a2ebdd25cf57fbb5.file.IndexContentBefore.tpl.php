<?php /* Smarty version Smarty-3.1.19, created on 2019-11-08 12:00:06
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/IndexContentBefore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10514443885dc53ca6e3d959-08400512%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73008bb5bd5a732bc07e8ca0a2ebdd25cf57fbb5' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/IndexContentBefore.tpl',
      1 => 1570520857,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10514443885dc53ca6e3d959-08400512',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dc53ca6e60d65_83126555',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dc53ca6e60d65_83126555')) {function content_5dc53ca6e60d65_83126555($_smarty_tpl) {?>


<div class="navigation-controls-row">
<div ng-if="checkRecordsVisibility(filterPermissions)" class="panel-title col-md-12 module-title">{{ptitle}}
</div>
</div>
    <div class="row portal-controls-row">
      <div ng-if="!checkRecordsVisibility(filterPermissions)" class="panel-title col-md-12 module-title">{{ptitle}}</div>
        <div class="col-lg-3 col-md-3 col-sm-8 col-xs-8" ng-if="checkRecordsVisibility(filterPermissions)">
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <button type="button"
                            ng-class="{'btn btn-default btn-primary':searchQ.onlymine, 'btn btn-default':!searchQ.onlymine}" ng-click="searchQ.onlymine=true">{{'Mine' | translate}}</button>
                </div>
                <div class="btn-group">
                    <button type="button"
                            ng-class="{'btn btn-default btn-primary':!searchQ.onlymine, 'btn btn-default':searchQ.onlymine}" ng-click="searchQ.onlymine=false">{{'All' | translate}}</button>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
            <div class="btn-group addbtnContainer" ng-if="isCreateable">
                <button type="button" class="btn btn-primary addBtn" ng-click="create()">{{'Add Document' | translate}}</button>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pagination-holder pull-right">
            <div class="pull-right">
                <div class="text-center">
                    <pagination
                        total-items="totalPages" max-size="3" ng-model="currentPage" ng-change="pageChanged(currentPage)" boundary-links="true">
                    </pagination>
                </div>
            </div>
        </div>
    </div>

<?php }} ?>
