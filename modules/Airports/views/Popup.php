<?php

class Airports_Popup_View extends Vtiger_Popup_View
{
    protected $listViewEntries = false;
    protected $listViewHeaders = false;
    public function checkPermission(Vtiger_Request $request)
    {
        $moduleName = $request->get("module");
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
        if (!$currentUserPrivilegesModel->hasModulePermission($moduleModel->getId())) {
            throw new AppException(vtranslate($moduleName, $moduleName) . " " . vtranslate("LBL_NOT_ACCESSIBLE"));
        }
    }
    /**
     * Function returns the module name for which the popup should be initialized
     * @param Vtiger_request $request
     * @return <String>
     */
    public function getModule(Vtiger_request $request)
    {
        $moduleName = $request->get("parent");
        return $moduleName;
    }
    public function process(Vtiger_Request $request)
    {
        $viewer = $this->getViewer($request);
        $VDmoduleName = $request->get("module");
        $companyDetails = Vtiger_CompanyDetails_Model::getInstanceById();
        $companyLogo = $companyDetails->getLogo();
        $this->initializeListViewContents($request, $viewer);
        $viewer->assign("COMPANY_LOGO", $companyLogo);
        $viewer->assign("VD_MODULE", $VDmoduleName);
        if ($request->get('multiple')) {
            $viewer->view("MPopup.tpl", $VDmoduleName);
        } else {
            $viewer->view("Popup.tpl", $VDmoduleName);
        }
    }
    public function postProcess(Vtiger_Request $request)
    {
        $viewer = $this->getViewer($request);
        $VDmoduleName = $request->get("module");
        $viewer->view("PopupFooter.tpl", $VDmoduleName);
    }

    public function initializeListViewContents(Vtiger_Request $request, Vtiger_Viewer $viewer)
    {
        $moduleName = $request->get("parent");
        $VDmoduleName = $request->get("module");
        $pageNumber = $request->get("page");
        $orderBy = $request->get("orderby");
        $sortOrder = $request->get("sortorder");
        $parent_id = $request->get("parent_id");
        $multiple = $request->get('multiple');
        $searchParams = $request->get('search_params');
        if ($parent_id && $parent_id != 'false') {
            $parentModule = Vtiger_Record_Model::getInstanceById($parent_id, $moduleName);
            if ($multiple && $parentModule) {
                $pagingModel = new Vtiger_Paging_Model();
                $pagingModel->set("page", 0);
                $pagingModel->set("limit", 100);
                $relListModel = Vtiger_RelationListView_Model::getInstance($parentModule, 'Airports', 'Airports');
                $entries = $relListModel->getEntries($pagingModel);
                $airportIds = array_keys($entries);
            }
        } else {
            $parentModule = false;
        }

        $moduleModel = Vtiger_Module_Model::getInstance($moduleName); //Potentials Model
        $VDmoduleModel = Vtiger_Module_Model::getInstance($VDmoduleName); // Contacts model
        if (empty($pageNumber)) {
            $pageNumber = "1";
        }
        $pagingModel = new Vtiger_Paging_Model();
        $pagingModel->set("page", $pageNumber);
        $pagingModel->set('multiple', $multiple);
        $pagingModel->set('selectedIds', $airportIds);
        $PageLimit = $pagingModel->getPageLimit();
        if (30 < $PageLimit) {
            $pagingModel->set("limit", 30);
        }

        $listViewModel = Vtiger_ListView_Model::getInstanceForPopup($VDmoduleName);
        $searchModuleModel = $listViewModel->getModule();
        if (!empty($orderBy)) {
            $listViewModel->set("orderby", $orderBy);
            $listViewModel->set("sortorder", $sortOrder);
        }
        if (!empty($searchParams)) {
            $transformedSearchParams = $this->transferListSearchParamsToFilterCondition($searchParams, $searchModuleModel);
            $listViewModel->set("search_params", $transformedSearchParams);
        }
        $this->listViewHeaders = $listViewModel->getListViewHeaders();
        /*$searchData = Array(0 => Array(
            'columns' => Array(
                0 => Array(
                    'columnname' => 'vtiger_vdportscf:cf_2745:cf_2745:VDPorts_Оборудование:V',
                    'comparator' => 'c',
                    'value' => $parentModule->getName(),
                    'column_condition' => ''
                )
            )
        )
        );
        $listViewModel->set('search_params', $searchData);*/
        $this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
//        $this->listViewEntries = $this->getListViewEntries($listViewModel, $pagingModel, $request);
        if (empty($searchParams)) {
            $searchParams = array();
        }
        foreach ($searchParams as $fieldListGroup) {
            foreach ($fieldListGroup as $fieldSearchInfo) {
                $fieldSearchInfo["searchValue"] = $fieldSearchInfo[2];
                $fieldSearchInfo["fieldName"] = $fieldName = $fieldSearchInfo[0];
                $fieldSearchInfo["comparator"] = $fieldSearchInfo[1];
                $searchParams[$fieldName] = $fieldSearchInfo;
            }
        }
        $noOfEntries = count($this->listViewEntries);
        if (empty($sortOrder)) {
            $sortOrder = "ASC";
        }
        if ($sortOrder == "ASC") {
            $nextSortOrder = "DESC";
            $sortImage = "icon-chevron-down";
            $faSortImage = "fa-sort-desc";
        } else {
            $nextSortOrder = "ASC";
            $sortImage = "icon-chevron-up";
            $faSortImage = "fa-sort-asc";
        }
        $multiSelectMode = $request->get('multi_select');
        $viewer->assign("MODULE", $VDmoduleName);
        $viewer->assign("RELATED_MODULE", $moduleName);
        $viewer->assign("MODULE_NAME", $moduleName);
        $viewer->assign("ORDER_BY", $orderBy);
        $viewer->assign("SORT_ORDER", $sortOrder);
        $viewer->assign("NEXT_SORT_ORDER", $nextSortOrder);
        $viewer->assign("SORT_IMAGE", $sortImage);
        $viewer->assign("FASORT_IMAGE", $faSortImage);
        $viewer->assign("PAGING_MODEL", $pagingModel);
        $viewer->assign("PAGE_NUMBER", $pageNumber);
        $viewer->assign("LISTVIEW_ENTRIES_COUNT", $noOfEntries);
        $viewer->assign("LISTVIEW_HEADERS", $this->listViewHeaders);
        $viewer->assign("LISTVIEW_ENTRIES", $this->listViewEntries);
        $viewer->assign("SEARCH_DETAILS", $searchParams);
        $viewer->assign("MODULE_MODEL", $moduleModel);
        $viewer->assign("VIEW", $request->get("view"));
        $viewer->assign("MULTI_SELECT", $multiSelectMode);
        $viewer->assign("VD_MODULE", $VDmoduleName);
        $viewer->assign("ELEMENT_ID", $request->get('element_id'));
        $all_filter_record_id = $this->getAllIds();
        $viewer->assign("ALL_FILTER_RECORD_ID", implode(",", $all_filter_record_id));

        if (PerformancePrefs::getBoolean("LISTVIEW_COMPUTE_PAGE_COUNT", false)) {
            if (!$this->listViewCount) {
                $this->listViewCount = $listViewModel->getListViewCount();
            }
            $totalCount = $this->listViewCount;
            $pageLimit = '100';
            $pageCount = ceil((int) $totalCount / (int) $pageLimit);
            if ($pageCount == 0) {
                $pageCount = 1;
            }
            $viewer->assign("PAGE_COUNT", $pageCount);
            $viewer->assign("LISTVIEW_COUNT", $totalCount);
        }

        $viewer->assign("CURRENT_USER_MODEL", Users_Record_Model::getCurrentUserModel());

        $decimal_separator = Users_Record_Model::getCurrentUserModel()->get("currency_decimal_separator");
        $digit_grouping_separator = Users_Record_Model::getCurrentUserModel()->get("currency_grouping_separator");
        $viewer->assign("DECIMAL_SEPARATOR", $decimal_separator);
        $viewer->assign("DIGIT_GROUPING_SEPARATOR", $digit_grouping_separator);
    }
    private function getAllIds()
    {
        $result = array();
        foreach ($this->listViewEntries as $entry)
        {
            $result[] = $entry->getId();
        }
        return $result;
    }
    public function getListViewEntries($listViewModel, $pagingModel, $request, $option = false)
    {
        $db = PearDatabase::getInstance();
        $anphabetFilter = $request->get("anphabetFilter");
        $product_show_instock_filter = $request->get("product_show_instock_filter");
        $product_show_bundles_filter = $request->get("product_show_bundles_filter");
        $product_bundles = $request->get("product_bundles");
        $products_get_bundles_id = $request->get("products_get_bundles_id");
        if (!empty($products_get_bundles_id)) {
            $products_get_bundles_id = explode(",", $products_get_bundles_id);
            $sub_products_ids = $this->get_sub_products_id($products_get_bundles_id);
        }
        $moduleName = $listViewModel->getModule()->get("name");
        $moduleFocus = CRMEntity::getInstance($moduleName);
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);
        $queryGenerator = $listViewModel->get("query_generator");
        $listViewContoller = $listViewModel->get("listview_controller");
        $searchParams = $listViewModel->get("search_params");
        if (empty($searchParams)) {
            $searchParams = array();
        }
        $glue = "";
        if (0 < count($queryGenerator->getWhereFields()) && 0 < count($searchParams)) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);
        $searchKey = $listViewModel->get("search_key");
        $searchValue = $listViewModel->get("search_value");
        $operator = $listViewModel->get("operator");
        if (!empty($searchKey)) {
            $queryGenerator->addUserSearchConditions(array("search_field" => $searchKey, "search_text" => $searchValue, "operator" => $operator));
        }
        $orderBy = $listViewModel->get("orderby");
        $sortOrder = $listViewModel->get("sortorder");
        if (!empty($orderBy)) {
            $queryGenerator = $listViewModel->get("query_generator");
            $fieldModels = $queryGenerator->getModuleFields();
            $orderByFieldModel = $fieldModels[$orderBy];
            if ($orderByFieldModel && ($orderByFieldModel->getFieldDataType() == Vtiger_Field_Model::REFERENCE_TYPE || $orderByFieldModel->getFieldDataType() == Vtiger_Field_Model::OWNER_TYPE)) {
                $queryGenerator->addWhereField($orderBy);
            }
        }
        $listQuery = $listViewModel->getQuery();
        $sourceModule = $listViewModel->get("src_module");
        if (!empty($sourceModule) && method_exists($moduleModel, "getQueryByModuleField")) {
            $overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $listViewModel->get("src_field"), $listViewModel->get("src_record"), $listQuery, $listViewModel->get("relationId"));
            if (!empty($overrideQuery)) {
                $listQuery = $overrideQuery;
            }
        }
        if (!empty($anphabetFilter) && $anphabetFilter != "all") {
            if ($moduleName == "Products") {
                $listQuery .= " AND productname LIKE '" . $anphabetFilter . "%' ";
            } else {
                if ($moduleName == "Services") {
                    $listQuery .= " AND servicename LIKE '" . $anphabetFilter . "%' ";
                }
            }
        }
        if (!empty($product_show_instock_filter)) {
            if ($moduleName == "Products") {
                $listQuery .= " AND qtyinstock > 0";
            } else {
                if ($moduleName == "Services") {
                    $listQuery .= " AND qtyinstock > 0 ";
                }
            }
        }
        if (!empty($product_show_bundles_filter) && $product_show_bundles_filter == 1 && $moduleName == "Products") {
            $listQuery .= " AND vtiger_products.productid IN (SELECT\r\n                    productid\r\n                FROM\r\n                    `vtiger_seproductsrel`\r\n                INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_seproductsrel.productid\r\n                WHERE vtiger_crmentity.deleted = 0\r\n                GROUP BY\r\n                    productid)";
        }
        if (!empty($sub_products_ids)) {
            if ($moduleName == "Products") {
                $listQuery .= " AND vtiger_products.productid IN (" . implode(",", $sub_products_ids) . ") ";
            }
        } else {
            if ($product_bundles == 1 && $moduleName == "Products") {
                $listQuery .= " AND vtiger_products.productid = 0";
            }
        }
        if ($moduleName == "Products") {
            $listQuery .= " AND vtiger_products.discontinued = 1";
        }
        $startIndex = $pagingModel->getStartIndex();
        $pageLimit = $pagingModel->getPageLimit();
        if (!empty($orderBy) && $orderByFieldModel) {
            if ($orderBy == "roleid" && $moduleName == "Users") {
                $listQuery .= " ORDER BY vtiger_role.rolename " . " " . $sortOrder;
            } else {
                $listQuery .= " ORDER BY " . $queryGenerator->getOrderByColumn($orderBy) . " " . $sortOrder;
            }
            if ($orderBy == "first_name" && $moduleName == "Users") {
                $listQuery .= " , last_name " . " " . $sortOrder . " ,  email1 " . " " . $sortOrder;
            }
        } else {
            if (empty($orderBy) && empty($sortOrder) && $moduleName != "Users") {
                $listQuery .= " ORDER BY vtiger_crmentity.modifiedtime DESC";
            }
        }
        $viewid = ListViewSession::getCurrentView($moduleName);
        if (empty($viewid)) {
            $viewid = $pagingModel->get("viewid");
        }
        $_SESSION["lvs"][$moduleName][$viewid]["start"] = $pagingModel->get("page");
        ListViewSession::setSessionQuery($moduleName, $listQuery, $viewid);
        if ($option && $option == "all") {
            $listResult = $db->pquery($listQuery, array());
            $listViewEntries = $listViewContoller->getListViewRecords($moduleFocus, $moduleName, $listResult);
            return array_keys($listViewEntries);
        }
        $listQuery .= " LIMIT " . $startIndex . "," . ($pageLimit + 1);
        $listResult = $db->pquery($listQuery, array());
        $listViewRecordModels = array();
        $listViewEntries = $listViewContoller->getListViewRecords($moduleFocus, $moduleName, $listResult);
        $pagingModel->calculatePageRange($listViewEntries);
        if ($pageLimit < $db->num_rows($listResult)) {
            array_pop($listViewEntries);
            $pagingModel->set("nextPageExists", true);
        } else {
            $pagingModel->set("nextPageExists", false);
        }
        $index = 0;
        foreach ($listViewEntries as $recordId => $record) {
            $rawData = $db->query_result_rowdata($listResult, $index++);
            $record["id"] = $recordId;
            $listViewRecordModels[$recordId] = $moduleModel->getRecordFromArray($record, $rawData);
            if (!empty($sub_products_ids) && $moduleName == "Products") {
                $sql = "SELECT quantity FROM `vtiger_seproductsrel` WHERE setype = 'Products' AND crmid = " . $recordId . ";";
                $re = $db->pquery($sql, array());
                $quantity = $db->query_result($re, 0, "quantity");
            }
            $listViewRecordModels[$recordId]->set("qty_per_unit", $quantity);
        }
        return $listViewRecordModels;
    }
    /**
     * Function to get listView count
     * @param Vtiger_Request $request
     */
    public function getListViewCount(Vtiger_Request $request)
    {
        $moduleName = $request->get("current_selected_item_modlue");
        $sourceModule = $request->get("src_module");
        $sourceField = $request->get("src_field");
        $sourceRecord = $request->get("src_record");
        $orderBy = $request->get("orderby");
        $sortOrder = $request->get("sortorder");
        $currencyId = $request->get("currency_id");
        $searchKey = $request->get("search_key");
        $searchValue = $request->get("search_value");
        $searchParams = $request->get("search_params");
        $relatedParentModule = $request->get("related_parent_module");
        $relatedParentId = $request->get("related_parent_id");
        if (!empty($relatedParentModule) && !empty($relatedParentId)) {
            $parentRecordModel = Vtiger_Record_Model::getInstanceById($relatedParentId, $relatedParentModule);
            $listViewModel = Vtiger_RelationListView_Model::getInstance($parentRecordModel, $moduleName, $label);
        } else {
            $listViewModel = Vtiger_ListView_Model::getInstanceForPopup($moduleName);
        }
        if (!empty($sourceModule)) {
            $listViewModel->set("src_module", $sourceModule);
            $listViewModel->set("src_field", $sourceField);
            $listViewModel->set("src_record", $sourceRecord);
            $listViewModel->set("currency_id", $currencyId);
        }
        if (!empty($orderBy)) {
            $listViewModel->set("orderby", $orderBy);
            $listViewModel->set("sortorder", $sortOrder);
        }
        if (!empty($searchKey) && !empty($searchValue)) {
            $listViewModel->set("search_key", $searchKey);
            $listViewModel->set("search_value", $searchValue);
        }
        if (!empty($searchParams)) {
            $transformedSearchParams = $this->transferListSearchParamsToFilterCondition($searchParams, $listViewModel->getModule());
            $listViewModel->set("search_params", $transformedSearchParams);
        }
        if (!empty($relatedParentModule) && !empty($relatedParentId)) {
            $count = $listViewModel->getRelatedEntriesCount();
        } else {
            $count = $listViewModel->getListViewCount();
        }
        return $count;
    }
    /**
     * Function to get the page count for list
     * @return total number of pages
     */
    public function getPageCount(Vtiger_Request $request)
    {
        $listViewCount = $this->getListViewCount($request);
        $pagingModel = new Vtiger_Paging_Model();
        $pageLimit = $pagingModel->getPageLimit();
        $pageCount = ceil((int) $listViewCount / (int) $pageLimit);
        if ($pageCount == 0) {
            $pageCount = 1;
        }
        $result = array();
        $result["page"] = $pageCount;
        $result["numberOfRecords"] = $listViewCount;
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    public function transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel)
    {
        return Vtiger_Util_Helper::transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel);
    }
}

?>