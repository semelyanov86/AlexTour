<?php /* Smarty version Smarty-3.1.7, created on 2019-03-21 19:01:46
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTEStore/ManageSubscription.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4606486255c93b56a129d44-53832323%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0437d9bc605839cc93bbfc237b97e497955ea89c' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/VTEStore/ManageSubscription.tpl',
      1 => 1553183782,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4606486255c93b56a129d44-53832323',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CHARGIFY_CUSTOMER_ID' => 0,
    'PORTALURL' => 0,
    'CUSTOMER_DATA' => 0,
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5c93b56a133a5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c93b56a133a5')) {function content_5c93b56a133a5($_smarty_tpl) {?><div class='modal-dialog'>
    <div class='modal-content'>
        <div class="modal-header contentsBackground">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" <?php if ($_smarty_tpl->tpl_vars['CHARGIFY_CUSTOMER_ID']->value==0||$_smarty_tpl->tpl_vars['CHARGIFY_CUSTOMER_ID']->value==''){?>onclick="app.helper.showSuccessNotification({message: app.vtranslate('JS_PLEASE_WAIT')}); reloadCurrentPage();"<?php }?>><span aria-hidden="true" class='fa fa-close'></span></button>
            <h3><?php echo vtranslate('LBL_MY_ACCOUNT','VTEStore');?>
</h3>
            <input type="hidden" id="vtiger_url" name="vtiger_url" value="">
        </div>
        <div align="center">
        <?php if ($_smarty_tpl->tpl_vars['CHARGIFY_CUSTOMER_ID']->value>0){?>
            <div align="left" style="padding: 20px;">
                <br>For security purposes subscription & payment details reside on another secure portal. Subscription portal will allow you to:<br><br>
                <ul>
                    <li>Update payment method.</li>
                    <li>Download invoices & statements.</li>
                    <li>View payment history.</li>
                    <li>Update billing information.</li>
                    <li>Cancel or adjust subscription.</li>
                </ul>
                <br><h3><u><a href="<?php echo $_smarty_tpl->tpl_vars['PORTALURL']->value;?>
" target="_blank">Please click here to manage subscription</a></u></h3>
                <br><br><i> If it's your first time accessing the portal - you will be asked to set a password. Note, this is a password to manage your subscription. </i>
                <br><br>For any questions please call us at +1 (818) 495-5557 or send us an email at support@vtexperts.com
            </div>
        <?php }else{ ?>
            <iframe src="https://vte-sandbox.chargifypay.com/subscribe/zc5436yw28g8/extensions<?php echo $_smarty_tpl->tpl_vars['CUSTOMER_DATA']->value;?>
" width="99%" height="600px" id="ifchargify"></iframe>
        <?php }?>
        </div>
        <div class="modal-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="cancelLink" type="reset" data-dismiss="modal" <?php if ($_smarty_tpl->tpl_vars['CHARGIFY_CUSTOMER_ID']->value==0||$_smarty_tpl->tpl_vars['CHARGIFY_CUSTOMER_ID']->value==''){?>onclick="app.helper.showSuccessNotification({message: app.vtranslate('JS_PLEASE_WAIT')}); reloadCurrentPage();"<?php }?>><strong><?php echo vtranslate('LBL_CLOSE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function reloadCurrentPage(){
        var url = window.location.href;
        if(url.indexOf('getChargifyInfo')!=-1){
            var urlReload=url;
        }else{
            var urlReload=url+'&getChargifyInfo=1';
        }

        window.location.href = urlReload;
    }
</script>

<?php }} ?>