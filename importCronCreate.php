<?php
include_once 'vtlib/Vtiger/Module.php';
require_once 'include/utils/utils.php';
require_once 'includes/Loader.php';
vimport('~~vtlib/Vtiger/Cron.php');
Vtiger_Cron::register('VisaImport', 'modules/Visa/cron/importVisa.service', 86400, 'Visa', 1, 11, 'Рекомендуемая частота обновления - 24 часа.');
echo 'done';