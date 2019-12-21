<?php

class Potentials_ConvertToInvoice_Action extends Vtiger_Action_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function checkPermission(Vtiger_Request $request)
    {
        return true;
    }
    public function process(Vtiger_Request $request)
    {
        global $adb;
        global $current_user;
        $parentId = $request->get("parentId");
        $parentModel = Vtiger_Record_Model::getInstanceById($parentId, 'Potentials');
        $pagingModel = new Vtiger_Paging_Model();
        $pagingModel->set('page', 1);
        $pagingModel->set('limit', 10);
        $relList = Vtiger_RelationListView_Model::getInstance($parentModel, 'Invoice', 'Invoice');
        $invoices = $relList->getEntries($pagingModel);
        $response = new Vtiger_Response();
        if (count($invoices) > 0) {
            $response->setError(1121, 'You have already created an invoice!');
        } else {
            $contactModel = Vtiger_Record_Model::getInstanceById($parentModel->get('contact_id'));
            $recordModel = Vtiger_Record_Model::getCleanInstance("Invoice");
            $recordModel->set("mode", "");
            $recordModel->set("assigned_user_id", $current_user->id);
            $recordModel->set("subject", $parentModel->getName());
            $recordModel->set("contact_id", $parentModel->get('contact_id'));
            $recordModel->set("cf_tours_id", $parentModel->get('cf_tours_id'));
            $recordModel->set("cf_2020", $parentModel->get('opportunity_type'));
            $recordModel->set("invoicestatus", 'AutoCreated');
            $recordModel->set("description", $parentModel->get('description'));
            $recordModel->set("potential_id", $parentId);
            $recordModel->set("invoicedate", date('Y-m-d'));
            $recordModel->set("duedate", $parentModel->get('cf_2058'));
            $recordModel->set('salescommission', $parentModel->get('probability'));
            $recordModel->set('account_id', $parentModel->get('related_to'));
            $recordModel->set("customerno", $contactModel->get('contact_no'));
            $recordModel->set("bill_street", $contactModel->get('mailingstreet'));
            $recordModel->set("ship_street", $contactModel->get('otherstreet'));
            $recordModel->set("bill_pobox", $contactModel->get('mailingpobox'));
            $recordModel->set("ship_pobox", $contactModel->get('otherpobox'));
            $recordModel->set("bill_city", $contactModel->get('mailingcity'));
            $recordModel->set("ship_city", $contactModel->get('othercity'));
            $recordModel->set("bill_state", $contactModel->get('mailingstate'));
            $recordModel->set("ship_state", $contactModel->get('otherstate'));
            $recordModel->set("bill_code", $contactModel->get('mailingzip'));
            $recordModel->set("ship_code", $contactModel->get('otherzip'));
            $recordModel->set("bill_country", $contactModel->get('mailingcountry'));
            $recordModel->set("ship_country", $contactModel->get('othercountry'));
            $recordModel->set("taxtype", 'individual');
            $recordModel->set("region_id", 0);
            $recordModel->set("spcompany", 'Default');
            $recordModel->set("terms_conditions", 'Vielen Dank für Ihre Buchung. Bei Rückfragen stehen wir Ihnen gerne zur Verfügung.
Bitte beachten Sie, dass für die Einreise in die Russische Föderation ein gültiges Visum erforderlich ist!
Wir empfehlen Ihnen den Abschluss einer Reise-Rücktrittsversicherung.

Das Reisebüro weist darauf hin, dass es auf der Grundlage seiner allgemeinen Geschäftsbedingungen tätig ist.
Diese Geschäftsbedingungen liegen im Reisebüro zur jederzeitigen Einsichtnahme und Mitnahme aus oder werden auf Wunsch
zugesandt.

Gerichtsstand Hamburg');
            $recordModel->save();
            $this->addProductLinesToInvoice($parentModel, $recordModel, $pagingModel);
            $this->attachRelatedEntities($parentModel, $recordModel, 'Contacts', $pagingModel);
            $this->attachFieldEntities($parentModel, $recordModel, 'PackageServices', $pagingModel);
            $this->attachFieldEntities($parentModel, $recordModel, 'Movings', $pagingModel);
            $this->attachRelatedEntities($parentModel, $recordModel, 'Hotels', $pagingModel);
            $this->attachRelatedEntities($parentModel, $recordModel, 'Cities', $pagingModel);
            $invoiceid = $recordModel->getId();
            $result = array("success" => true, "data" => array("invoiceid" => $invoiceid));
            $response->setResult($result);
        }
        $response->emit();
    }

    public function attachRelatedEntities($potentialModel, $invoiceRecordModel, $moduleName, $pagingModel)
    {
        $relModel = Vtiger_Relation_Model::getInstance(Vtiger_Module_Model::getInstance('Invoice'), Vtiger_Module_Model::getInstance($moduleName), $moduleName);
        $relListModel = Vtiger_RelationListView_Model::getInstance($potentialModel, $moduleName, $moduleName);
        $entries = $relListModel->getEntries($pagingModel);
        foreach ($entries as $entry) {
            $relModel->addRelation($invoiceRecordModel->getId(), $entry->getId());
        }
    }
    public function attachFieldEntities($potentialModel, $invoiceRecordModel, $moduleName, $pagingModel)
    {
        $relListModel = Vtiger_RelationListView_Model::getInstance($potentialModel, $moduleName, $moduleName);
        $entries = $relListModel->getEntries($pagingModel);
        foreach ($entries as $entry) {
            $recModel = Vtiger_Record_Model::getInstanceById($entry->getId(), $moduleName);
            $recModel->set('mode', 'edit');
            $recModel->set('cf_invoice_id', $invoiceRecordModel->getId());
            $recModel->save();
        }
    }

    protected function addProductLinesToInvoice($parentModel, $recordModel, $pagingModel)
    {
        global $adb;
        $relList = Vtiger_RelationListView_Model::getInstance($parentModel, 'ServiceDetails', 'ServiceDetails');
        $entries = $relList->getEntries($pagingModel);
        $invid = $recordModel->getId();
        if($invid>0) {
            $i = 1;
            $total = 0;
            foreach ($entries as $entry) {
                $total += $entry->get('service_qty') * $entry->get('service_price');
                $sql= $adb->pquery("INSERT INTO vtiger_inventoryproductrel SET id=?,productid=?,sequence_no=?,quantity=?,listprice=?,comment=?",array($invid,$entry->get('cf_services_id'),$i,$entry->get('service_qty'),$entry->get('service_price'),$entry->get('description')));
                $i++;
            }
            $adjustment = $parentModel->get('amount') - $total;
            $sql= $adb->pquery("UPDATE vtiger_invoice SET total=?,subtotal=?,currency_id=?,taxtype=?,region_id=?,adjustment=? WHERE invoiceid=?",array($parentModel->get('amount'),$parentModel->get('amount'),1,'individual',0,$adjustment,$invid));
        }
    }
}

?>