<?php

use Wrseward\PdfParser\Exceptions\PdfNotFoundException;

require_once 'modules/Visa/cron/MassImport.php';

class PotentialsImport extends MassImport
{
    const START_LINE_INDEX = 4;

    public $textArray;
    public $namespaces;
    public $resultingArray = array(
        'Contacts' => array(),
        'Potentials' => array()
    );
    protected $currentNamespace;

    public function saveRecordFromFile($path)
    {
        global $log;
        $massActionObj = new Visa_MassActionAjax_View();
        $parser = new \Wrseward\PdfParser\Pdf\PdfToTextParser('/usr/bin/pdftotext');
        try {
            $text = $parser->parse($path);
        } catch (Wrseward\PdfParser\Exceptions\PdfNotFoundException $ex) {
            echo $ex->getMessage();
            $log->error($ex->getMessage());
        }
        $textArr = $massActionObj->splitDocument($parser->text());
        var_dump($textArr);die;
        $this->namespaces = require('modules/Potentials/StringArray.php');
        $this->textArray = $textArr;
        $result = $this->doParsing();
        if (!$result) {
            $log->error('Error in parsing file, with string 38');
            return 'No result';
        }
        $potentialModel = Potentials_Module_Model::isRecordExist($result['Visa']['name']);
        if ($potentialModel) {
            return false;
        }
        $recordModel = $this->saveRecordWithRequest($result);
        return $recordModel;
    }

    public function doParsing()
    {
        switch($this->textArray[38])
        {
            case 'Reise-Krankenversicherung':
                $namespaces = $this->namespaces['insurance'];
                break;
            case 'Rechnung zum Visum':
                $namespaces = $this->namespaces['visum'];
                break;
            case 'Teilnehmer/in:':
                $namespaces = $this->namespaces['gruppen'];
                break;
            default:
                $namespaces = false;
                break;
        }
        if (!$namespaces) {
            return false;
        }
        $lineIndex = self::START_LINE_INDEX;
        $this->currentNamespace = false;
        foreach ($namespaces as $key=>$curField) {
            if (isset($this->textArray[$key])) {
               $this->resultingArray[$curField[1]][$curField[0]] = $this->textArray[$key];
            }
        }
        return $this->resultingArray;
    }

    public function saveRecordWithRequest($result)
    {
        $current_user = CRMEntity::getInstance('Users');
        $current_user->retrieveCurrentUserInfoFromFile(1);
        $moduleModel = Vtiger_Module_Model::getInstance('Contacts');
        $contactRecordModel = Potentials_Module_Model::isContactExist($result['Contacts']['cf_1161']);
        if (!$contactRecordModel) {
            $tmpArr = array();
            $tmpArr['Contacts'] = $result['Contacts'];
            $contactRecordModel = Potentials_Module_Model::createRecordFromArray($tmpArr);
        }
        $tmpArr = array();
        $tmpArr['Potentials'] = $result['Potentials'];
        $potentialsRecordModel = Potentials_Module_Model::createRecordFromArray($tmpArr, $contactRecordModel);
        return $potentialsRecordModel;
    }
}