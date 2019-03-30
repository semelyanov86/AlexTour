<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ***********************************************************************************/

require_once 'modules/Visa/vendor/autoload.php';
require_once 'modules/Visa/models/DocumentNamespace.php';

class Visa_MassActionAjax_View extends Project_MassActionAjax_View {

    const START_LINE_INDEX = 4;

    public $textArray;
    public $mapping;
    public $namespaces;
    public $resultingArray = array(
        'Contacts' => array(),
        'Visa' => array()
    );
    protected $currentNamespace;

    function __construct() {
        parent::__construct();
        $this->exposeMethod('showSendFile');
        $this->exposeMethod('saveAjax');

    }

    function showSendFile(Vtiger_Request $request) {
        global $site_URL;
        $sourceModule = $request->getModule();
        $moduleName = 'Visa';

        $user = Users_Record_Model::getCurrentUserModel();
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $viewer = $this->getViewer($request);
        $viewer->assign('MODULE', $moduleName);
        $viewer->assign('USER_MODEL', $user);
        echo $viewer->view('SendFileForm.tpl', $moduleName, true);
    }

    function saveAjax(Vtiger_Request $request)
    {
        global $adb;
        $currentUserModel = Users_Record_Model::getCurrentUserModel();
        $path = $this->saveUploadFile($_FILES);
        $arr_file_name = $this->getFileName($path);
        $recordModel = $this->saveRecordFromFile($request, $path);
        $fileName = $arr_file_name['name'];
        $path = $arr_file_name['path'];
        if ($recordModel) {
            $crmid = $recordModel->getId();
            $fileData = $_FILES['userfile'];
            $document = CRMEntity::getInstance('Documents');
            $document->column_fields['notes_title'] = $fileData['name'];
            $document->column_fields['filename'] = $fileData['name'];
            $document->column_fields['filetype'] = $fileData['type'];
            $document->column_fields['filesize'] = $fileData['size'];
            $document->column_fields['filestatus'] = 1;
            $document->column_fields['filelocationtype'] = 'I';
            $document->column_fields['folderid'] = 1;
            $document->column_fields['cf_for_field'] = 'cf_acf_ulf_1153';
            $document->column_fields['assigned_user_id'] =  Users_Record_Model::getCurrentUserModel()->getId();
            $document->saveentity('Documents');
            $documentId = $document->id;
            $adb->pquery('INSERT INTO vtiger_senotesrel(crmid, notesid) VALUES(?,?)', array($crmid, $documentId));
            $adb->pquery('UPDATE vtiger_notescf SET cf_for_field = ? WHERE notesid = ?', array('cf_acf_ulf_1153', $documentId));
            $attachid = $arr_file_name['id'];
            $res = $adb->pquery('SELECT crmid FROM vtiger_crmentity WHERE crmid = ?', array($attachid));

            if ($adb->num_rows($res) == 0) {
                $description = $fileName;
                $date_var = $adb->formatDate(date('YmdHis'), true);
                $usetime = $adb->formatDate($date_var, true);
                $adb->pquery('INSERT INTO vtiger_crmentity(crmid, smcreatorid, smownerid,modifiedby, setype, description, createdtime, modifiedtime, presence, deleted)' . "\r\n" . '                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($attachid, $currentUserModel->getId(), $currentUserModel->getId(), $currentUserModel->getId(), 'Documents Attachment', $description, $usetime, $usetime, 1, 0));
                $mimetype = $fileData['type'];
                $adb->pquery('INSERT INTO vtiger_attachments SET attachmentsid=?, name=?, description=?, type=?, path=?', array($attachid, $fileName, $description, $mimetype, $path));
            }

            $adb->pquery('INSERT INTO vtiger_seattachmentsrel(crmid, attachmentsid) VALUES(?,?)', array($documentId, $attachid));

            $url = $recordModel->getDetailViewUrl();
            header("Location: $url");
            die();
        } else {
            $url = Vtiger_Module_Model::getInstance('Visa')->getListViewUrl();
            header("Location: $url");
            die();
        }
    }

    public function saveUploadFile($file)
    {
        $adb = PearDatabase::getInstance();
        $attachid = $adb->getUniqueId('vtiger_crmentity');
        $uploadPath = decideFilePath();
        $fileName = $file['userfile']['name'];
        $binFile = sanitizeUploadFileName($fileName, vglobal('upload_badext'));
        $fileName = ltrim(basename(' ' . $binFile));
        $path = $uploadPath . $attachid . '_' . $fileName;

        try {
            $tmp_name = $file['userfile']['tmp_name'];
            move_uploaded_file($tmp_name, $path);
            return $path;
        }
        catch (Exception $e) {
            return $e->getCode();
        }
    }

    public function saveRecordFromFile($request, $path)
    {
        $parser = new \Wrseward\PdfParser\Pdf\PdfToTextParser('/usr/bin/pdftotext');
        $text = $parser->parse($path);
        $textArr = $this->splitDocument($parser->text());
        $this->namespaces = require('modules/Visa/namespaces.php');
        $this->textArray = $textArr;
        $result = $this->doParsing();
        $visaModel = Visa_Module_Model::isRecordExist($result['Visa']['name']);
        if ($visaModel) {
            $url = $visaModel->getDetailViewUrl();
            header("Location: $url");
            die();
        }
        $recordModel = $this->saveRecordWithRequest($result);
        return $recordModel;
    }

    protected function splitDocument($text){
        $lines = [];
        foreach(preg_split('~[\r\n]+~', $text) as $line){
            if(empty($line) or ctype_space($line)) continue; // skip only spaces
            // if(!strlen($line = trim($line))) continue; // or trim by force and skip empty
            // $line is trimmed and nice here so use it
            $line = $this->clearLine($line);
            $lines[] = $line;
        }
        return $lines;
    }

    protected function clearLine($line){
        $line = str_replace('\f','',$line);
        $pos = mb_strpos($line,'');
        if($pos !== false){
            $line = mb_substr($line,0,$pos);
        }
        return $line;
    }

    protected function saveRecordWithRequest($result)
    {
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
    public function getFileName($file)
    {
        $arr_file_name = explode('/', $file);
        $name = $arr_file_name[count($arr_file_name) - 1];
        $path = str_replace($name, '', $file);
        $array_name = explode('_', $name);
        $id = $array_name[0];
        $sid = $id . '_';
        $c = strlen($sid);
        $name = substr($name, $c);
        return array('id' => $array_name[0], 'name' => $name, 'path' => $path);
    }
    public function doParsing()
    {
        $namespaces = $this->namespaces;
        $lineIndex = self::START_LINE_INDEX;
        $this->currentNamespace = false;
        $this->resultingArray['Visa']['name'] = $this->textArray[1];

        while(isset($this->textArray[$lineIndex])){
            // update namespace according to specific rule. see namespaces property
            $this->updateNamespace($lineIndex);

            if($this->currentNamespace && !$this->checkLine($lineIndex)){
                $item = $this->getItem($lineIndex);
            } elseif($this->currentNamespace && $this->checkLine($lineIndex)) {
                $item = $this->textArray[$lineIndex+1];
            }
            if($item) {
                if ($this->currentNamespace->subfield) {
                    $itemArr = $this->createItemArr($item);
                    $item = array($this->currentNamespace->name => $itemArr[0]);

                    foreach ($this->currentNamespace->subfield as $key => $value) {
                        $item[$value] = $itemArr[$key+1];
                    }
                }
                if (!is_array($item)) {
                    $item = array($this->currentNamespace->name => $item);
                }

                foreach ($item as $field=>$curItem) {
                    if ($this->currentNamespace->fieldType && $this->currentNamespace->fieldType === 'date') {
                        $curItem = $this->changeDateFormat($curItem);
                    }
                    $this->resultingArray[$this->currentNamespace->module][$field] = $curItem;
                }
            }
            $lineIndex++;
        }
        return $this->resultingArray;

    }

    public function checkLine($lineIndex)
    {
        $res = substr($this->textArray[$lineIndex], -1);
        if ($res === $this->currentNamespace->subspace) {
            return true;
        } else {
            return false;
        }
        // $line = $this->lines[$lineIndex];
        // return mb_strpos($line, $this->currentNamespace->subspace);
    }

    public function updateNamespace($lineIndex)
    {
        $line = $this->textArray[$lineIndex];
        $namespaces = $this->namespaces;
        $this->currentNamespace = false;
        $maxSimilarity = 0;
        foreach ($namespaces as $namespace){
            $keyWord = $namespace->keyword;
            if(mb_strpos($line, $keyWord) !== false){
                $similarity = similar_text($line, $keyWord);
                if($similarity > $maxSimilarity){
                    $maxSimilarity = $similarity;
                    $this->currentNamespace = $namespace;
                }

            }
        }

    }

    /**
     * get item from text. In case if some value stored on multiple lined it will put it in item variable
     * break words used for remove unused data
     *
     * @param $lineIndex
     * @param $pointIndex
     * @return mixed|string
     */
    protected function getItem($lineIndex){
        $tmpIndex = $lineIndex+1;
        $line = $this->textArray[$tmpIndex];
        // check next lines before next item
        while (!$this->checkLine($tmpIndex)){
            $tmpIndex++;
            $line = $this->textArray[$tmpIndex];
        }
        return $this->textArray[$tmpIndex+1];
    }

    public function changeDateFormat($date)
    {
        $timeArr = explode('/', $date);
        return trim($timeArr[2]) . '-' . trim($timeArr[1]) . '-' . trim($timeArr[0]);
    }

    public function createItemArr($item)
    {
        if ($this->currentNamespace->fieldType === 'address') {
            $result = array();
            $tmpArr = explode($this->currentNamespace->subdivide, $item);
            $result[] = $tmpArr[0];
            $temp = explode(' ', trim($tmpArr[1]));
            $result[] = $temp[0];
            $result[] = $temp[1];
            return $result;

        } else {
            return explode($this->currentNamespace->subdivide, $item);
        }
    }

}
