<?php
require_once 'modules/Import/readers/FileReader.php';
require_once 'modules/Import/readers/parsecsv.lib.php';

/**
 * Class VTEPayments_Commerzbank_Model
 */
class VTEPayments_Commerzbank_Model
{

    /**
     * @var parseCSV
     */
    protected $csvReader;

    /**
     * @var string
     */
    public $delimeter = ';';

    public $dateFormat = 'd-m-y';

    protected $parsedData = array();

    /**
     * Variable for mapping fields
     * @var array
     */
    protected $mapping = array(
        'amount_paid' => 'Betrag',
        'reference' => 'Verwendungszweck',
        'date' => 'Valutadatum',
        'description' => 'Verwendungszweck',
        'cf_2070' => 'Beguenstigter/Zahlungspflichtiger'
    );

    /**
     * Array of contacts or organisation for which we will not create payments
     * @var array
     */
    protected $blackListContacts = array('Deutsche Post AG');

    /**
     * List of dates fields for which we will parse date to DB format
     * @var array
     */
    protected $datesFields = array('date');

    /**
     * Array of static fields for which we will fill static values for Payment model
     * @var array
     */
    protected $staticFields = array(
        'payment_type' => 'Bank Account',
        'payment_status' => 'Completed',
        'cf_2068' => 'Commerzbank'
    );

    /**
     * VTEPayments_Commerzbank_Model constructor.
     */
    public function __construct()
    {
        $this->csvReader = new parseCSV();
        $this->csvReader->delimiter = $this->delimeter;
    }

    /**
     * Getter for mapping variable
     * @return array
     */
    public function getMapping() : array
    {
        return $this->mapping;
    }

    public function getStaticFields() : array
    {
        return $this->staticFields;
    }

    public function getDatesFields() : array
    {
        return $this->datesFields;
    }

    /**
     * Method to generate assigned_user_id field which is required for Record model
     * @return int
     */
    public function getAssignedUser() : int
    {
        $staticFields = $this->getStaticFields();
        if (isset($staticFields['assigned_user_id'])) {
            return (int) $staticFields['assigned_user_id'];
        } else {
            return (int) Users_Record_Model::getCurrentUserModel()->getId();
        }
    }

    /**
     * Method only make parsing of CSV file and receive array
     * @return array
     */
    public function doParsing() : array
    {
        $filePath = $this->getFilePath();
        $this->csvReader->parse($filePath);
        $data = $this->csvReader->data;
        $this->parsedData = $this->doGermanEncoding($data);
        return $this->parsedData;
    }

    /**
     * Method loop a parsed array from csv file and run createPaymentModel function for each element of array. When finished it redirects to Payment list view page
     */
    public function createPayments()
    {
        $payments = $this->parsedData;
        foreach($payments as $payment) {
            if ($this->shouldExclude($payment)) {
                continue;
            }
            if ($this->existsInDatabase($payment)) {
                continue;
            } else {
                $paymentModel = $this->createPaymentModel($payment);
            }
        }
        $this->doRedirect();
    }

    /**
     * Method which doing redirect to payment list view page
     * @param bool $error
     */
    public function doRedirect($error = false)
    {
        $paymentModule = Vtiger_Module_Model::getInstance('VTEPayments');
        $url = $paymentModule->getListViewUrl();
        if ($error) {
            echo $error;
            sleep(3);
        }
        header( "Location: $url" );
        exit;
    }

    /**
     * This method check if we should create payment model for current row of csv file
     * @param array $payment
     * @return bool
     */
    private function shouldExclude(array $payment) : bool
    {
        if (in_array($payment['Beguenstigter/Zahlungspflichtiger'], $this->blackListContacts) || $payment['Betrag'] < 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method checks if payment already exists in database. We are doing check via reference field
     * @param array $payment
     * @return bool
     */
    private function existsInDatabase(array $payment) : bool
    {
        global $adb;
        $uniqueId = $this->getUniqId($payment);
        $query="SELECT paymentid FROM vtiger_payments WHERE vtiger_payments.reference = ?";
        $result = $adb->pquery($query, array($uniqueId));

        if($adb->num_rows($result) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for converting date to Database format
     * @param string $date
     * @return string
     */
    private function getDBDateFormat(string $date) : string
    {
        $arr = explode('.', $date);
        $yearVal = $arr[2];
        if (strlen($yearVal) < 4) {
            $yearVal = '20' . $arr[2];
        }
        return $yearVal . '-' . $arr[1] . '-' . $arr[0];
    }

    protected function createPaymentModel(array $payment)
    {
        $paymentModel = Vtiger_Record_Model::getCleanInstance('VTEPayments');
        $paymentModel->set('mode', 'create');
        $paymentModel->set('assigned_user_id', $this->getAssignedUser());
        foreach ($this->getMapping() as $field=>$map) {
            $curValue = $payment[$map];
            if(in_array($field, $this->getDatesFields())) {
                $paymentModel->set($field, $this->getDBDateFormat($curValue));
            } elseif ($field == 'reference') {
                $paymentModel->set($field, $this->getUniqId($payment));
            } elseif($field == 'cf_2070') {
                $paymentModel->set($field, $this->getFormatNameValue($curValue));
            } else {
                $paymentModel->set($field, $curValue);
            }
        }
        foreach ($this->getStaticFields() as $field=>$value) {
            $paymentModel->set($field, $value);
        }
        $paymentModel->save();
        return $paymentModel;
    }

    public function getFormatNameValue(string $value): string
    {
        $lowString = strtolower($value);
        return ucwords($lowString);
    }

    protected function getUniqueIdFieldName()
    {
        return $this->mapping['reference'];
    }

    protected function getUniqId($payment) : string
    {
        return md5($payment[$this->getUniqueIdFieldName()]);
    }

    public function doGermanEncoding(array $data) : array
    {
        $result = array();
        foreach($data as $key=>$value) {
            $result[$key] = array_map("utf8_encode", $value);
        }
        return $result;
    }

    protected function getFilePath() : string
    {
        $path = $this->saveUploadFile($_FILES);
        /*$arr_file_name = $this->getFileName($path);
        $fileName = $arr_file_name['name'];
        $path = $arr_file_name['path'];*/
        return $path;
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
            $moved = move_uploaded_file($tmp_name, $path);
            return $path;
        }
        catch (Exception $e) {
            $this->doRedirect($e->getMessage());
            return $e->getCode();
        }
    }

    public function getFileName($file) : array
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
}