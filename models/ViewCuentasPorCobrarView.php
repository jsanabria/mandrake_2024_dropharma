<?php

namespace PHPMaker2024\mandrake;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use Closure;

/**
 * Page class
 */
class ViewCuentasPorCobrarView extends ViewCuentasPorCobrar
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ViewCuentasPorCobrarView";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ViewCuentasPorCobrarView";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Set field visibility
    public function setVisibility()
    {
        $this->id->setVisibility();
        $this->cliente->setVisibility();
        $this->cliente_rif->setVisibility();
        $this->cliente_nombre->setVisibility();
        $this->tipo_documento_fiscal->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->fecha_documento->setVisibility();
        $this->fecha_vencimiento->setVisibility();
        $this->moneda->setVisibility();
        $this->tasa_dia->setVisibility();
        $this->dias_credito->setVisibility();
        $this->entregado->setVisibility();
        $this->pagado->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->doc_afe->setVisibility();
        $this->igtf->setVisibility();
        $this->monto_igtf_bs->setVisibility();
        $this->signo_documento->setVisibility();
        $this->monto_documento_moneda->setVisibility();
        $this->monto_documento_bs->setVisibility();
        $this->monto_documento_usd->setVisibility();
        $this->monto_aplicado_bs->setVisibility();
        $this->monto_aplicado_usd->setVisibility();
        $this->total_cobrado_bs->setVisibility();
        $this->total_cobrado_usd->setVisibility();
        $this->cantidad_cobros->setVisibility();
        $this->fecha_ultimo_cobro->setVisibility();
        $this->saldo_bs->setVisibility();
        $this->saldo_usd->setVisibility();
        $this->estado_cuenta->setVisibility();
        $this->dias_vencido->setVisibility();
        $this->antiguedad->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'view_cuentas_por_cobrar';
        $this->TableName = 'view_cuentas_por_cobrar';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-view-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (view_cuentas_por_cobrar)
        if (!isset($GLOBALS["view_cuentas_por_cobrar"]) || $GLOBALS["view_cuentas_por_cobrar"]::class == PROJECT_NAMESPACE . "view_cuentas_por_cobrar") {
            $GLOBALS["view_cuentas_por_cobrar"] = &$this;
        }

        // Set up record key
        if (($keyValue = Get("id") ?? Route("id")) !== null) {
            $this->RecKey["id"] = $keyValue;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'view_cuentas_por_cobrar');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Export options
        $this->ExportOptions = new ListOptions(TagClassName: "ew-export-option");

        // Other options
        $this->OtherOptions = new ListOptionsArray();

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(TagClassName: "ew-detail-option");
        // Actions
        $this->OtherOptions["action"] = new ListOptions(TagClassName: "ew-action-option");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return $Response?->getBody() ?? ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (WithJsonResponse()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = SameString($pageName, "ViewCuentasPorCobrarView"); // If View page, no primary button
                } else { // List page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Result set
            while ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->id->Visible = false;
        }
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->tipo_documento_fiscal);
        $this->setupLookupOptions($this->entregado);
        $this->setupLookupOptions($this->pagado);
        $this->setupLookupOptions($this->igtf);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;

        // Set up master/detail parameters
        $this->setupMasterParms();
        if (Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) {
            $loadCurrentRecord = true;
        }
        if (($keyValue = Get("id") ?? Route("id")) !== null) {
            $this->id->setQueryStringValue($keyValue);
            $this->RecKey["id"] = $this->id->QueryStringValue;
        } elseif (Post("id") !== null) {
            $this->id->setFormValue(Post("id"));
            $this->RecKey["id"] = $this->id->FormValue;
        } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
            $this->id->setQueryStringValue($keyValue);
            $this->RecKey["id"] = $this->id->QueryStringValue;
        } elseif (!$loadCurrentRecord) {
            $returnUrl = "ViewCuentasPorCobrarList"; // Return to list
        }

        // Get action
        $this->CurrentAction = "show"; // Display
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal && !IsApi()) { // Normal view page
                    $this->StartRecord = 1; // Initialize start position
                    $this->Recordset = $this->loadRecordset(); // Load records
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("ViewCuentasPorCobrarList"); // Return to list page
                        return;
                    } elseif ($loadCurrentRecord) { // Load current record position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $matchRecord = true;
                            $this->fetch($this->StartRecord);
                            // Redirect to correct record
                            $this->loadRowValues($this->CurrentRow);
                            $url = $this->getCurrentUrl();
                            $this->terminate($url);
                            return;
                        }
                    } else { // Match key values
                        while ($this->fetch()) {
                            if (SameString($this->id->CurrentValue, $this->CurrentRow['id'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $matchRecord = true;
                                break;
                            } else {
                                $this->StartRecord++;
                            }
                        }
                    }
                    if (!$matchRecord) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "ViewCuentasPorCobrarList"; // No matching record, return to list
                    } else {
                        $this->loadRowValues($this->CurrentRow); // Load row values
                    }
                } else {
                    // Load record based on key
                    if (IsApi()) {
                        $filter = $this->getRecordFilter();
                        $this->CurrentFilter = $filter;
                        $sql = $this->getCurrentSql();
                        $conn = $this->getConnection();
                        $res = ($this->Recordset = ExecuteQuery($sql, $conn));
                    } else {
                        $res = $this->loadRow();
                    }
                    if (!$res) { // Load record based on key
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "ViewCuentasPorCobrarList"; // No matching record, return to list
                    }
                } // End modal checking
                break;
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = RowType::VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Normal return
        if (IsApi()) {
            if (!$this->isExport()) {
                $row = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
                $this->Recordset?->free();
                WriteJson(["success" => true, "action" => Config("API_VIEW_ACTION"), $this->TableVar => $row]);
                $this->terminate(true);
            }
            return;
        }

        // Set up pager
        if (!$this->IsModal) { // Normal view page
            $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager, false, false);
            $this->Pager->PageNumberName = Config("TABLE_PAGE_NUMBER");
            $this->Pager->PagePhraseId = "Record"; // Show as record
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;

        // Disable Add/Edit/Copy/Delete for Modal and UseAjaxActions
        /*
        if ($this->IsModal && $this->UseAjaxActions) {
            $this->AddUrl = "";
            $this->EditUrl = "";
            $this->CopyUrl = "";
            $this->DeleteUrl = "";
        }
        */
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = !IsJsonResponse() && false;
        $option->UseButtonGroup = true;
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    /**
     * Load result set
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return Doctrine\DBAL\Result Result
     */
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        if (property_exists($this, "TotalRecords") && $rowcnt < 0) {
            $this->TotalRecords = $result->rowCount();
            if ($this->TotalRecords <= 0) { // Handle database drivers that does not return rowCount()
                $this->TotalRecords = $this->getRecordCount($this->getListSql());
            }
        }

        // Call Recordset Selected event
        $this->recordsetSelected($result);
        return $result;
    }

    /**
     * Load records as associative array
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return void
     */
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        return $result->fetchAllAssociative();
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array $row Record
     * @return void
     */
    public function loadRowValues($row = null)
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->id->setDbValue($row['id']);
        $this->cliente->setDbValue($row['cliente']);
        $this->cliente_rif->setDbValue($row['cliente_rif']);
        $this->cliente_nombre->setDbValue($row['cliente_nombre']);
        $this->tipo_documento_fiscal->setDbValue($row['tipo_documento_fiscal']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->fecha_documento->setDbValue($row['fecha_documento']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->pagado->setDbValue($row['pagado']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->doc_afe->setDbValue($row['doc_afe']);
        $this->igtf->setDbValue($row['igtf']);
        $this->monto_igtf_bs->setDbValue($row['monto_igtf_bs']);
        $this->signo_documento->setDbValue($row['signo_documento']);
        $this->monto_documento_moneda->setDbValue($row['monto_documento_moneda']);
        $this->monto_documento_bs->setDbValue($row['monto_documento_bs']);
        $this->monto_documento_usd->setDbValue($row['monto_documento_usd']);
        $this->monto_aplicado_bs->setDbValue($row['monto_aplicado_bs']);
        $this->monto_aplicado_usd->setDbValue($row['monto_aplicado_usd']);
        $this->total_cobrado_bs->setDbValue($row['total_cobrado_bs']);
        $this->total_cobrado_usd->setDbValue($row['total_cobrado_usd']);
        $this->cantidad_cobros->setDbValue($row['cantidad_cobros']);
        $this->fecha_ultimo_cobro->setDbValue($row['fecha_ultimo_cobro']);
        $this->saldo_bs->setDbValue($row['saldo_bs']);
        $this->saldo_usd->setDbValue($row['saldo_usd']);
        $this->estado_cuenta->setDbValue($row['estado_cuenta']);
        $this->dias_vencido->setDbValue($row['dias_vencido']);
        $this->antiguedad->setDbValue($row['antiguedad']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['cliente'] = $this->cliente->DefaultValue;
        $row['cliente_rif'] = $this->cliente_rif->DefaultValue;
        $row['cliente_nombre'] = $this->cliente_nombre->DefaultValue;
        $row['tipo_documento_fiscal'] = $this->tipo_documento_fiscal->DefaultValue;
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['fecha_documento'] = $this->fecha_documento->DefaultValue;
        $row['fecha_vencimiento'] = $this->fecha_vencimiento->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['tasa_dia'] = $this->tasa_dia->DefaultValue;
        $row['dias_credito'] = $this->dias_credito->DefaultValue;
        $row['entregado'] = $this->entregado->DefaultValue;
        $row['pagado'] = $this->pagado->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['doc_afe'] = $this->doc_afe->DefaultValue;
        $row['igtf'] = $this->igtf->DefaultValue;
        $row['monto_igtf_bs'] = $this->monto_igtf_bs->DefaultValue;
        $row['signo_documento'] = $this->signo_documento->DefaultValue;
        $row['monto_documento_moneda'] = $this->monto_documento_moneda->DefaultValue;
        $row['monto_documento_bs'] = $this->monto_documento_bs->DefaultValue;
        $row['monto_documento_usd'] = $this->monto_documento_usd->DefaultValue;
        $row['monto_aplicado_bs'] = $this->monto_aplicado_bs->DefaultValue;
        $row['monto_aplicado_usd'] = $this->monto_aplicado_usd->DefaultValue;
        $row['total_cobrado_bs'] = $this->total_cobrado_bs->DefaultValue;
        $row['total_cobrado_usd'] = $this->total_cobrado_usd->DefaultValue;
        $row['cantidad_cobros'] = $this->cantidad_cobros->DefaultValue;
        $row['fecha_ultimo_cobro'] = $this->fecha_ultimo_cobro->DefaultValue;
        $row['saldo_bs'] = $this->saldo_bs->DefaultValue;
        $row['saldo_usd'] = $this->saldo_usd->DefaultValue;
        $row['estado_cuenta'] = $this->estado_cuenta->DefaultValue;
        $row['dias_vencido'] = $this->dias_vencido->DefaultValue;
        $row['antiguedad'] = $this->antiguedad->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // cliente

        // cliente_rif

        // cliente_nombre

        // tipo_documento_fiscal

        // nro_documento

        // nro_control

        // fecha

        // fecha_documento

        // fecha_vencimiento

        // moneda

        // tasa_dia

        // dias_credito

        // entregado

        // pagado

        // doc_afectado

        // doc_afe

        // igtf

        // monto_igtf_bs

        // signo_documento

        // monto_documento_moneda

        // monto_documento_bs

        // monto_documento_usd

        // monto_aplicado_bs

        // monto_aplicado_usd

        // total_cobrado_bs

        // total_cobrado_usd

        // cantidad_cobros

        // fecha_ultimo_cobro

        // saldo_bs

        // saldo_usd

        // estado_cuenta

        // dias_vencido

        // antiguedad

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // cliente
            $curVal = strval($this->cliente->CurrentValue);
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->ViewValue = FormatNumber($this->cliente->CurrentValue, $this->cliente->formatPattern());
                    }
                }
            } else {
                $this->cliente->ViewValue = null;
            }

            // cliente_rif
            $this->cliente_rif->ViewValue = $this->cliente_rif->CurrentValue;

            // cliente_nombre
            $this->cliente_nombre->ViewValue = $this->cliente_nombre->CurrentValue;

            // tipo_documento_fiscal
            if (strval($this->tipo_documento_fiscal->CurrentValue) != "") {
                $this->tipo_documento_fiscal->ViewValue = $this->tipo_documento_fiscal->optionCaption($this->tipo_documento_fiscal->CurrentValue);
            } else {
                $this->tipo_documento_fiscal->ViewValue = null;
            }

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

            // fecha_documento
            $this->fecha_documento->ViewValue = $this->fecha_documento->CurrentValue;
            $this->fecha_documento->ViewValue = FormatDateTime($this->fecha_documento->ViewValue, $this->fecha_documento->formatPattern());

            // fecha_vencimiento
            $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
            $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

            // moneda
            $this->moneda->ViewValue = $this->moneda->CurrentValue;

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());
            $this->tasa_dia->CssClass = "fw-bold";
            $this->tasa_dia->CellCssStyle .= "text-align: right;";

            // dias_credito
            $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
            $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->ViewValue, $this->dias_credito->formatPattern());

            // entregado
            if (strval($this->entregado->CurrentValue) != "") {
                $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
            } else {
                $this->entregado->ViewValue = null;
            }

            // pagado
            if (strval($this->pagado->CurrentValue) != "") {
                $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
            } else {
                $this->pagado->ViewValue = null;
            }

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

            // doc_afe
            $this->doc_afe->ViewValue = $this->doc_afe->CurrentValue;
            $this->doc_afe->ViewValue = FormatNumber($this->doc_afe->ViewValue, $this->doc_afe->formatPattern());

            // igtf
            if (strval($this->igtf->CurrentValue) != "") {
                $this->igtf->ViewValue = $this->igtf->optionCaption($this->igtf->CurrentValue);
            } else {
                $this->igtf->ViewValue = null;
            }

            // monto_igtf_bs
            $this->monto_igtf_bs->ViewValue = $this->monto_igtf_bs->CurrentValue;
            $this->monto_igtf_bs->ViewValue = FormatNumber($this->monto_igtf_bs->ViewValue, $this->monto_igtf_bs->formatPattern());
            $this->monto_igtf_bs->CssClass = "fw-bold";
            $this->monto_igtf_bs->CellCssStyle .= "text-align: right;";

            // signo_documento
            $this->signo_documento->ViewValue = $this->signo_documento->CurrentValue;
            $this->signo_documento->ViewValue = FormatNumber($this->signo_documento->ViewValue, $this->signo_documento->formatPattern());

            // monto_documento_moneda
            $this->monto_documento_moneda->ViewValue = $this->monto_documento_moneda->CurrentValue;
            $this->monto_documento_moneda->ViewValue = FormatNumber($this->monto_documento_moneda->ViewValue, $this->monto_documento_moneda->formatPattern());
            $this->monto_documento_moneda->CssClass = "fw-bold";
            $this->monto_documento_moneda->CellCssStyle .= "text-align: right;";

            // monto_documento_bs
            $this->monto_documento_bs->ViewValue = $this->monto_documento_bs->CurrentValue;
            $this->monto_documento_bs->ViewValue = FormatNumber($this->monto_documento_bs->ViewValue, $this->monto_documento_bs->formatPattern());
            $this->monto_documento_bs->CssClass = "fw-bold";
            $this->monto_documento_bs->CellCssStyle .= "text-align: right;";

            // monto_documento_usd
            $this->monto_documento_usd->ViewValue = $this->monto_documento_usd->CurrentValue;
            $this->monto_documento_usd->ViewValue = FormatNumber($this->monto_documento_usd->ViewValue, $this->monto_documento_usd->formatPattern());
            $this->monto_documento_usd->CssClass = "fw-bold";
            $this->monto_documento_usd->CellCssStyle .= "text-align: right;";

            // monto_aplicado_bs
            $this->monto_aplicado_bs->ViewValue = $this->monto_aplicado_bs->CurrentValue;
            $this->monto_aplicado_bs->ViewValue = FormatNumber($this->monto_aplicado_bs->ViewValue, $this->monto_aplicado_bs->formatPattern());
            $this->monto_aplicado_bs->CssClass = "fw-bold";
            $this->monto_aplicado_bs->CellCssStyle .= "text-align: right;";

            // monto_aplicado_usd
            $this->monto_aplicado_usd->ViewValue = $this->monto_aplicado_usd->CurrentValue;
            $this->monto_aplicado_usd->ViewValue = FormatNumber($this->monto_aplicado_usd->ViewValue, $this->monto_aplicado_usd->formatPattern());
            $this->monto_aplicado_usd->CssClass = "fw-bold";
            $this->monto_aplicado_usd->CellCssStyle .= "text-align: right;";

            // total_cobrado_bs
            $this->total_cobrado_bs->ViewValue = $this->total_cobrado_bs->CurrentValue;
            $this->total_cobrado_bs->ViewValue = FormatNumber($this->total_cobrado_bs->ViewValue, $this->total_cobrado_bs->formatPattern());
            $this->total_cobrado_bs->CssClass = "fw-bold";
            $this->total_cobrado_bs->CellCssStyle .= "text-align: right;";

            // total_cobrado_usd
            $this->total_cobrado_usd->ViewValue = $this->total_cobrado_usd->CurrentValue;
            $this->total_cobrado_usd->ViewValue = FormatNumber($this->total_cobrado_usd->ViewValue, $this->total_cobrado_usd->formatPattern());
            $this->total_cobrado_usd->CssClass = "fw-bold";
            $this->total_cobrado_usd->CellCssStyle .= "text-align: right;";

            // cantidad_cobros
            $this->cantidad_cobros->ViewValue = $this->cantidad_cobros->CurrentValue;
            $this->cantidad_cobros->ViewValue = FormatNumber($this->cantidad_cobros->ViewValue, $this->cantidad_cobros->formatPattern());

            // fecha_ultimo_cobro
            $this->fecha_ultimo_cobro->ViewValue = $this->fecha_ultimo_cobro->CurrentValue;
            $this->fecha_ultimo_cobro->ViewValue = FormatDateTime($this->fecha_ultimo_cobro->ViewValue, $this->fecha_ultimo_cobro->formatPattern());

            // saldo_bs
            $this->saldo_bs->ViewValue = $this->saldo_bs->CurrentValue;
            $this->saldo_bs->ViewValue = FormatNumber($this->saldo_bs->ViewValue, $this->saldo_bs->formatPattern());
            $this->saldo_bs->CssClass = "fw-bold";
            $this->saldo_bs->CellCssStyle .= "text-align: right;";

            // saldo_usd
            $this->saldo_usd->ViewValue = $this->saldo_usd->CurrentValue;
            $this->saldo_usd->ViewValue = FormatNumber($this->saldo_usd->ViewValue, $this->saldo_usd->formatPattern());
            $this->saldo_usd->CssClass = "fw-bold";
            $this->saldo_usd->CellCssStyle .= "text-align: right;";

            // estado_cuenta
            $this->estado_cuenta->ViewValue = $this->estado_cuenta->CurrentValue;

            // dias_vencido
            $this->dias_vencido->ViewValue = $this->dias_vencido->CurrentValue;
            $this->dias_vencido->ViewValue = FormatNumber($this->dias_vencido->ViewValue, $this->dias_vencido->formatPattern());

            // antiguedad
            $this->antiguedad->ViewValue = $this->antiguedad->CurrentValue;

            // cliente
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // cliente_rif
            $this->cliente_rif->HrefValue = "";
            $this->cliente_rif->TooltipValue = "";

            // cliente_nombre
            $this->cliente_nombre->HrefValue = "";
            $this->cliente_nombre->TooltipValue = "";

            // tipo_documento_fiscal
            $this->tipo_documento_fiscal->HrefValue = "";
            $this->tipo_documento_fiscal->TooltipValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // nro_control
            $this->nro_control->HrefValue = "";
            $this->nro_control->TooltipValue = "";

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // fecha_documento
            $this->fecha_documento->HrefValue = "";
            $this->fecha_documento->TooltipValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->HrefValue = "";
            $this->fecha_vencimiento->TooltipValue = "";

            // moneda
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // tasa_dia
            $this->tasa_dia->HrefValue = "";
            $this->tasa_dia->TooltipValue = "";

            // dias_credito
            $this->dias_credito->HrefValue = "";
            $this->dias_credito->TooltipValue = "";

            // entregado
            $this->entregado->HrefValue = "";
            $this->entregado->TooltipValue = "";

            // pagado
            $this->pagado->HrefValue = "";
            $this->pagado->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";
            $this->doc_afectado->TooltipValue = "";

            // doc_afe
            $this->doc_afe->HrefValue = "";
            $this->doc_afe->TooltipValue = "";

            // igtf
            $this->igtf->HrefValue = "";
            $this->igtf->TooltipValue = "";

            // monto_igtf_bs
            $this->monto_igtf_bs->HrefValue = "";
            $this->monto_igtf_bs->TooltipValue = "";

            // signo_documento
            $this->signo_documento->HrefValue = "";
            $this->signo_documento->TooltipValue = "";

            // monto_documento_moneda
            $this->monto_documento_moneda->HrefValue = "";
            $this->monto_documento_moneda->TooltipValue = "";

            // monto_documento_bs
            $this->monto_documento_bs->HrefValue = "";
            $this->monto_documento_bs->TooltipValue = "";

            // monto_documento_usd
            $this->monto_documento_usd->HrefValue = "";
            $this->monto_documento_usd->TooltipValue = "";

            // monto_aplicado_bs
            $this->monto_aplicado_bs->HrefValue = "";
            $this->monto_aplicado_bs->TooltipValue = "";

            // monto_aplicado_usd
            $this->monto_aplicado_usd->HrefValue = "";
            $this->monto_aplicado_usd->TooltipValue = "";

            // total_cobrado_bs
            $this->total_cobrado_bs->HrefValue = "";
            $this->total_cobrado_bs->TooltipValue = "";

            // total_cobrado_usd
            $this->total_cobrado_usd->HrefValue = "";
            $this->total_cobrado_usd->TooltipValue = "";

            // cantidad_cobros
            $this->cantidad_cobros->HrefValue = "";
            $this->cantidad_cobros->TooltipValue = "";

            // fecha_ultimo_cobro
            $this->fecha_ultimo_cobro->HrefValue = "";
            $this->fecha_ultimo_cobro->TooltipValue = "";

            // saldo_bs
            $this->saldo_bs->HrefValue = "";
            $this->saldo_bs->TooltipValue = "";

            // saldo_usd
            $this->saldo_usd->HrefValue = "";
            $this->saldo_usd->TooltipValue = "";

            // estado_cuenta
            $this->estado_cuenta->HrefValue = "";
            $this->estado_cuenta->TooltipValue = "";

            // dias_vencido
            $this->dias_vencido->HrefValue = "";
            $this->dias_vencido->TooltipValue = "";

            // antiguedad
            $this->antiguedad->HrefValue = "";
            $this->antiguedad->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        $foreignKeys = [];
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "view_cuentas_por_cobrar_resumen") {
                $validMaster = true;
                $masterTbl = Container("view_cuentas_por_cobrar_resumen");
                if (($parm = Get("fk_cliente", Get("cliente"))) !== null) {
                    $masterTbl->cliente->setQueryStringValue($parm);
                    $this->cliente->QueryStringValue = $masterTbl->cliente->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->cliente->setSessionValue($this->cliente->QueryStringValue);
                    $foreignKeys["cliente"] = $this->cliente->QueryStringValue;
                    if (!is_numeric($masterTbl->cliente->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "view_cuentas_por_cobrar_resumen") {
                $validMaster = true;
                $masterTbl = Container("view_cuentas_por_cobrar_resumen");
                if (($parm = Post("fk_cliente", Post("cliente"))) !== null) {
                    $masterTbl->cliente->setFormValue($parm);
                    $this->cliente->FormValue = $masterTbl->cliente->FormValue;
                    $this->cliente->setSessionValue($this->cliente->FormValue);
                    $foreignKeys["cliente"] = $this->cliente->FormValue;
                    if (!is_numeric($masterTbl->cliente->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilterFromSession());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit() && !$this->isGridUpdate()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "view_cuentas_por_cobrar_resumen") {
                if (!array_key_exists("cliente", $foreignKeys)) { // Not current foreign key
                    $this->cliente->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ViewCuentasPorCobrarList"), "", $this->TableVar, true);
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_cliente":
                    break;
                case "x_tipo_documento_fiscal":
                    break;
                case "x_entregado":
                    break;
                case "x_pagado":
                    break;
                case "x_igtf":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == "success") {
            //$msg = "your success message";
        } elseif ($type == "failure") {
            //$msg = "your failure message";
        } elseif ($type == "warning") {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Page Exporting event
    // $doc = export object
    public function pageExporting(&$doc)
    {
        //$doc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $doc = export document object
    public function rowExport($doc, $rs)
    {
        //$doc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $doc = export document object
    public function pageExported($doc)
    {
        //$doc->Text .= "my footer"; // Export footer
        //Log($doc->Text);
    }
}
