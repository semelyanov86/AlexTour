<?php
$Vtiger_Utils_Log = true;
require_once('vtlib/Vtiger/Module.php');
require_once('vtlib/Vtiger/Block.php');
require_once('vtlib/Vtiger/Field.php');
$module = Vtiger_Module::getInstance('Flights');
if ($module) {
    $block = Vtiger_Block::getInstance('LBL_FLIGHTS_INFORMATION', $module);
    if ($block) {
        $field = Vtiger_Field::getInstance('through', $module);
        if (!$field) {
            $field               = new Vtiger_Field();
            $field->name         = 'through';
            $field->table        = $module->basetable;
            $field->label        = 'Through';
            $field->column       = 'through';
            $field->columntype   = 'VARCHAR(100)';
            $field->uitype       = 10;
            $field->typeofdata   = 'V~O';
            $block->addField($field);
            $field->setRelatedModules(Array('Airports'));
        }
    } else {
        echo "No block";
    }
} else {
    echo "No module";
}

?>