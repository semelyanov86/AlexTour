<?php /* Smarty version Smarty-3.1.19, created on 2019-11-18 06:47:16
         compiled from "/var/www/html/crm.avelacom/portal/layouts/default/templates/Documents/partials/IndexContentBefore.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14604641055dd23e74bf1c77-36557336%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77ef7e0b3a2dca351dff791444b5e2b30a0bd726' => 
    array (
      0 => '/var/www/html/crm.avelacom/portal/layouts/default/templates/Documents/partials/IndexContentBefore.tpl',
      1 => 1570520857,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14604641055dd23e74bf1c77-36557336',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dd23e74bf9a68_88138629',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dd23e74bf9a68_88138629')) {function content_5dd23e74bf9a68_88138629($_smarty_tpl) {?>


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
