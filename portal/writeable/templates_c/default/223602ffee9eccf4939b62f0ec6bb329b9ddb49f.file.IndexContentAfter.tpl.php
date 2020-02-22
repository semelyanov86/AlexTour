<?php /* Smarty version Smarty-3.1.19, created on 2019-10-08 11:35:01
         compiled from "/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/IndexContentAfter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7984457545d9c4a35564e09-66320492%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '223602ffee9eccf4939b62f0ec6bb329b9ddb49f' => 
    array (
      0 => '/var/www/www-root/data/www/avelacom.vordoom.ru/portal/layouts/default/templates/Documents/partials/IndexContentAfter.tpl',
      1 => 1570520857,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7984457545d9c4a35564e09-66320492',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5d9c4a3556f180_45774599',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d9c4a3556f180_45774599')) {function content_5d9c4a3556f180_45774599($_smarty_tpl) {?>


    <script type="text/ng-template" id="editRecordModalDocuments.template">
        <form class="form form-vertical" name="docForm" enctype='multipart/form-data' novalidate="novalidate">
        <div class="modal-header">
        <button type="button" class="close" ng-click="cancel()" title="Close">&times;</button>
        <h4 class="modal-title" >{{'Add New Document'|translate}}</h4>
        </div>
        <div class="modal-body">

        <div class="row">
        <div class="input-group col-sm-8 col-sm-offset-2">
        <input type="text" id="upload-file-info" class="form-control" ng-disabled="true">
        <span class="input-group-btn">
        <span class="btn btn-primary btn-file">
        {{'Browse'|translate}}&hellip;<input  type="file" name="file" file-input="editRecord.filename" ng-required="true" onchange='$("#upload-file-info").val($(this).val().replace("C:\\fakepath\\",""));'/></span>
        </span>
        </span>
        </div>
        <div class="col-sm-12 col-sm-offset-2" style="padding-left:0px;">
        <span ng-show="message" class="text-danger">{{'File size uploaded is greater than 25 MB'|translate}}</span>
        </div>
        <div ng-show="!editRecord.filename && !message" class="col-sm-8 col-sm-offset-2" style="padding-left:0px;"><span class="text-danger">{{'Maximum size for file upload is 25 MB'|translate}}</span></div>

        </div>
        </div>
        <div class="modal-footer">
        <a type="button" class="btn  btn-default" ng-click="cancel()" translate="Cancel">Cancel</a>
        <button type="submit" class="btn  btn-success" ng-disabled="message || !editRecord.filename ||saving" ng-click="save()" type="submit" translate="Save">Save</button>
        </div>
        </form>
    </script>

<?php }} ?>
