<?php

require_once 'modules/Visa/views/MassActionAjax.php';
require_once 'modules/Visa/vendor/autoload.php';
require_once 'modules/Visa/models/DocumentNamespace.php';
include_once 'include/Webservices/Utils.php';
include_once 'include/Webservices/Create.php';

class MassImport
{

    public $files = array();
    public $dir = 'import/';
    public $isInvoices;

    public function __construct($files, $dir, $isInvoices = false)
    {
        $this->files = $files;
        $this->dir = $dir;
        $this->isInvoices = $isInvoices;
    }

    public function process()
    {
        global $adb;
        $massActionObj = new Visa_MassActionAjax_View();
        foreach ($this->files as $file) {
            $path = $this->saveImportFile($file);
            $arr_file_name = $massActionObj->getFileName($path);
            $recordModel = $this->saveRecordFromFile($path);
            if (!$recordModel) {
                unlink($this->dir . $file);
                continue;
            } elseif ($recordModel == 'No result') {
                continue;
            }
            $fileName = $arr_file_name['name'];
            $path = $arr_file_name['path'];
            $crmid = $recordModel->getId();
            $this->linkFileToEntity($file, $crmid, $arr_file_name, $recordModel->getModuleName());
            unlink($this->dir . $file);
        }
    }

    public function saveImportFile($file)
    {
        $adb = PearDatabase::getInstance();
        $attachid = $adb->getUniqueId('vtiger_crmentity');
        $uploadPath = decideFilePath();
        $fileName = $file;
        $binFile = sanitizeUploadFileName($fileName, vglobal('upload_badext'));
        $fileName = ltrim(basename(' ' . $binFile));
        $path = $uploadPath . $attachid . '_' . $fileName;

        try {
            $tmp_name = $this->dir . $file;
            copy($tmp_name, $path);
            return $path;
        }
        catch (Exception $e) {
            return $e->getCode();
        }
    }

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
        $massActionObj->namespaces = require('modules/Visa/namespaces.php');
        $massActionObj->textArray = $textArr;
        $result = $massActionObj->doParsing();
        $result['Visa']['cf_1159'] = date('Y-m-d');
        $visaModel = Visa_Module_Model::isRecordExist($result['Visa']['name']);
        if ($visaModel) {
            return false;
        }
        $recordModel = $this->saveRecordWithRequest($result);
        return $recordModel;
    }

    public function saveRecordWithRequest($result)
    {
        $current_user = CRMEntity::getInstance('Users');
        $current_user->retrieveCurrentUserInfoFromFile(1);
        $moduleModel = Vtiger_Module_Model::getInstance('Contacts');
        $contactRecordModel = Visa_Module_Model::isContactExist($result['Contacts']['firstname'], $result['Contacts']['lasttname']);
        if (!$contactRecordModel) {
            $tmpArr = array();
            $tmpArr['Contacts'] = $result['Contacts'];
            $contactRecordModel = Visa_Module_Model::createRecordFromArray($tmpArr);
        }
        $tmpArr = array();
        $tmpArr['Visa'] = $result['Visa'];
        $visaRecordModel = Visa_Module_Model::createRecordFromArray($tmpArr, $contactRecordModel);
        return $visaRecordModel;
    }

    public function linkFileToEntity($file, $crmid, $arr_file_name, $moduleName)
    {
        global $adb;
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        $document = CRMEntity::getInstance('Documents');
        $document->column_fields['notes_title'] = $file;
        $document->column_fields['filename'] = $file;
        $document->column_fields['filetype'] = 'application/pdf';
        $document->column_fields['filesize'] = filesize($this->dir . $file);
        $document->column_fields['filestatus'] = 1;
        $document->column_fields['filelocationtype'] = 'I';
        $document->column_fields['folderid'] = 1;
        if ($moduleName == 'Visa') {
            $document->column_fields['cf_for_field'] = 'cf_acf_ulf_1153';
        }
        $document->column_fields['assigned_user_id'] =  Users_Record_Model::getCurrentUserModel()->getId();
        $document->saveentity('Documents');
        $documentId = $document->id;
        $adb->pquery('INSERT INTO vtiger_senotesrel(crmid, notesid) VALUES(?,?)', array($crmid, $documentId));
        if ($moduleName == 'Visa') {
            $adb->pquery('UPDATE vtiger_notescf SET cf_for_field = ? WHERE notesid = ?', array('cf_acf_ulf_1153', $documentId));
        }
        $attachid = $arr_file_name['id'];
        $res = $adb->pquery('SELECT crmid FROM vtiger_crmentity WHERE crmid = ?', array($attachid));

        if ($adb->num_rows($res) == 0) {
            $description = $arr_file_name['name'];
            $date_var = $adb->formatDate(date('YmdHis'), true);
            $usetime = $adb->formatDate($date_var, true);
            $adb->pquery('INSERT INTO vtiger_crmentity(crmid, smcreatorid, smownerid,modifiedby, setype, description, createdtime, modifiedtime, presence, deleted)' . "\r\n" . '                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($attachid, $currentUserModel->getId(), $currentUserModel->getId(), $currentUserModel->getId(), 'Documents Attachment', $description, $usetime, $usetime, 1, 0));
            $mimetype = 'application/pdf';
            $adb->pquery('INSERT INTO vtiger_attachments SET attachmentsid=?, name=?, description=?, type=?, path=?', array($attachid, $arr_file_name['name'], $description, $mimetype, $arr_file_name['path']));
        }

        $adb->pquery('INSERT INTO vtiger_seattachmentsrel(crmid, attachmentsid) VALUES(?,?)', array($documentId, $attachid));
    }
}