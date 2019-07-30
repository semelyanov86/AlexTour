<?php /* Smarty version Smarty-3.1.7, created on 2019-06-20 22:34:49
         compiled from "/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Install.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9200785955d0bdfd9d48004-31104513%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c15885d7134172c7d9344fee1e80666b653f49a3' => 
    array (
      0 => '/var/www/html/vtigercrm/includes/runtime/../../layouts/v7/modules/PDFMaker/Install.tpl',
      1 => 1561059252,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9200785955d0bdfd9d48004-31104513',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'STEP' => 0,
    'TOTAL_STEPS' => 0,
    'CURRENT_STEP' => 0,
    'QUALIFIED_MODULE' => 0,
    'MB_STRING_EXISTS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5d0bdfd9d65ac',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d0bdfd9d65ac')) {function content_5d0bdfd9d65ac($_smarty_tpl) {?>
<div class="contentsDiv marginLeftZero" >
<div class="padding1per">
<div class="editContainer" style="padding-left: 3%;padding-right: 3%"><h3><?php echo vtranslate('LBL_MODULE_NAME','PDFMaker');?>
 <?php echo vtranslate('LBL_INSTALL','PDFMaker');?>
</h3>    
<hr>
<div id="breadcrumb">             
    <ul class="crumbs marginLeftZero">
        <li class="first step <?php if ($_smarty_tpl->tpl_vars['STEP']->value=="1"){?>active<?php }?>" style="z-index:10;" id="steplabel1"><a><span class="stepNum">1</span><span class="stepText"><?php echo vtranslate('LBL_VALIDATION','PDFMaker');?>
</span></a></li>
        <?php if ($_smarty_tpl->tpl_vars['TOTAL_STEPS']->value=="3"){?>
        <li class="step <?php if ($_smarty_tpl->tpl_vars['STEP']->value=="2"){?>active<?php }?>" style="z-index:9;"  id="steplabel2"><a><span class="stepNum">2</span><span class="stepText"><?php echo vtranslate('LBL_DOWNLOAD','PDFMaker');?>
</span></a></li>    
        <?php }?>    
        <li class="step last <?php if ($_smarty_tpl->tpl_vars['CURRENT_STEP']->value==$_smarty_tpl->tpl_vars['TOTAL_STEPS']->value){?>active<?php }?>" style="z-index:7;"  id="steplabel<?php echo $_smarty_tpl->tpl_vars['TOTAL_STEPS']->value;?>
"><a><span class="stepNum"><?php echo $_smarty_tpl->tpl_vars['TOTAL_STEPS']->value;?>
</span><span class="stepText"><?php echo vtranslate('LBL_FINISH','PDFMaker');?>
</span></a></li>
    </ul>
</div>
<div class="clearfix">
</div>
<form name="install" id="editLicense"  method="POST" action="index.php" class="form-horizontal">
<input type="hidden" name="module" value="PDFMaker"/>
<input type="hidden" name="view" value="List"/>
  
<div id="step1" class="padding1per" style="border:1px solid #ccc; <?php if ($_smarty_tpl->tpl_vars['STEP']->value!="1"){?>display:none;<?php }?>" >     
    <input type="hidden" name="installtype" value="validate"/>                                       
    <div class="controls">
        <div>
            <strong><?php echo vtranslate('LBL_WELCOME','PDFMaker');?>
</strong>
        </div>
        <br>
        <div class="clearfix">
        </div>
    </div>
    <div class="controls">
        <div>
           <?php echo vtranslate('LBL_WELCOME_DESC','PDFMaker');?>
</br>
            <?php echo vtranslate('LBL_WELCOME_FINISH','PDFMaker');?>

        </div>
        <br>
        <div class="clearfix">
        </div>
    </div>
    <div class="controls">
        <div>
            <strong><?php echo vtranslate('LBL_INSERT_KEY','PDFMaker');?>
</strong>
        </div>
        <div>
            <?php echo vtranslate('LBL_ONLINE_ASSURE','PDFMaker');?>

        </div>
        <div class="clearfix">
        </div>
    </div>
     <div class="controls paddingTop20">    
                <?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('LicenseDetails.tpl',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div> 
        <div class="clearfix">
        </div>
    </div>
</div>
<?php if ($_smarty_tpl->tpl_vars['TOTAL_STEPS']->value=="3"){?>        
<div id="step2" class="padding1per" style="border:1px solid #ccc;  <?php if ($_smarty_tpl->tpl_vars['STEP']->value!="2"){?>display:none;<?php }?>">
    <input type="hidden" name="installtype" value="download_src"/>
    <div class="controls">
        <div>
            <strong><?php echo vtranslate('LBL_DOWNLOAD_SRC','PDFMaker');?>
</strong>
        </div>
        <br>
        <div class="clearfix">
        </div>
    </div>
    <div class="controls">
        <div>
            <?php echo vtranslate('LBL_DOWNLOAD_SRC_DESC','PDFMaker');?>

            <?php if ($_smarty_tpl->tpl_vars['MB_STRING_EXISTS']->value=='false'){?>
                <br><?php echo vtranslate('LBL_MB_STRING_ERROR','PDFMaker');?>

            <?php }?>
        </div>
        <br>
        <div class="clearfix">
        </div>
    </div>          
    <div class="controls">
        <button type="button" id="download_button" class="btn btn-success"/><strong><?php echo vtranslate('LBL_DOWNLOAD','PDFMaker');?>
</strong></button>&nbsp;&nbsp;  
    </div>
</div>
<?php }?>                                                        
<div id="step<?php echo $_smarty_tpl->tpl_vars['TOTAL_STEPS']->value;?>
" class="padding1per" style="border:1px solid #ccc; <?php if ($_smarty_tpl->tpl_vars['STEP']->value!="3"){?>display:none;<?php }?>" >
    <input type="hidden" name="installtype" value="redirect_recalculate" />
    <div class="controls">
        <div><?php echo vtranslate('LBL_INSTALL_SUCCESS','PDFMaker');?>
</div>
        <div class="clearfix">
        </div>
    </div> 
    <br>
    <div class="controls">
        <button type="submit" id="next_button" class="btn btn-success"/><strong><?php echo vtranslate('LBL_FINISH','PDFMaker');?>
</strong></button>&nbsp;&nbsp;
    </div>
</div>
</form> 
</div> 
</div>
</div>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function() {
        var thisInstance = PDFMaker_License_Js.getInstance();
        thisInstance.registerInstallEvents();
    });
</script><?php }} ?>