<?php /* Smarty version Smarty-3.1.19, created on 2019-11-08 12:00:01
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/RelatedDocumentsContent.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17921255695dc53ca15b09e7-20219929%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a1b1c43cbab2326e93954696ebe443d7ed18292' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/RelatedDocumentsContent.tpl',
      1 => 1570520857,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17921255695dc53ca15b09e7-20219929',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5dc53ca15da597_54578014',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dc53ca15da597_54578014')) {function content_5dc53ca15da597_54578014($_smarty_tpl) {?>


    <div class="cp-table-container" ng-show="documentsrecords">
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-detailed dataTable no-footer">
                <thead>
                    <tr class="listViewHeaders">

                        <th ng-hide="documentsheader=='id'" ng-repeat="documentsheader in documentsheaders" nowrap="" class="medium">
                            <a href="javascript:void(0);" class="listViewHeaderValues" translate="{{documentsheader}}">{{documentsheader}}</a>
                        </th>
                        <th ng-hide="header=='id'" class="medium">
                            <a class="listViewHeaderValues">{{'Actions'|translate}}</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="listViewEntries" ng-repeat="documentrecord in documentsrecords">

                        <td ng-hide="documentsheader=='id'" ng-repeat="documentsheader in documentsheaders" class="listViewEntryValue medium" ng-click="ChangeLocation('Documents',documentrecord.id)">
                <ng-switch on="documentrecord[documentsheader].type">
                    <a ng-href="index.php?module=Documents&view=Detail&id={{documentrecord.id}}"></a>
                    <span ng-switch-default>{{documentrecord[documentsheader]}}</span>
                </ng-switch>
                </td>
                <td ng-hide="documentsheader=='id'" class="listViewEntryValue medium" nowrap="" style='cursor: pointer;'>
                    <span ng-if="documentrecord.documentExists" class="btn btn-primary" ng-click="downloadFile('Documents',documentrecord.id,id)">{{'Download'|translate}}</span>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <a ng-if="!documentsLoaded && !noDocuments" ng-click="loadDocumentsPage(documentsPageNo)">{{'more'|translate}}...</a>
    <p ng-if="documentsLoaded" class="text-muted">{{'No more documents'|translate}}</p>
    <p ng-if="!documentsLoaded && noDocuments" class="text-muted">{{'No documents'|translate}}</p>

<?php }} ?>
