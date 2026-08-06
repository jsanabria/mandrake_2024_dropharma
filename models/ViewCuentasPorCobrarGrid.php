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
class ViewCuentasPorCobrarGrid extends ViewCuentasPorCobrar
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ViewCuentasPorCobrarGrid";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fview_cuentas_por_cobrargrid";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "ViewCuentasPorCobrarGrid";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

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
        $this->id->Visible = false;
        $this->cliente->setVisibility();
        $this->cliente_rif->setVisibility();
        $this->cliente_nombre->setVisibility();
        $this->tipo_documento_fiscal->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->Visible = false;
        $this->fecha->setVisibility();
        $this->fecha_documento->Visible = false;
        $this->fecha_vencimiento->Visible = false;
        $this->moneda->Visible = false;
        $this->tasa_dia->Visible = false;
        $this->dias_credito->Visible = false;
        $this->entregado->Visible = false;
        $this->pagado->Visible = false;
        $this->doc_afectado->Visible = false;
        $this->doc_afe->Visible = false;
        $this->igtf->Visible = false;
        $this->monto_igtf_bs->Visible = false;
        $this->signo_documento->Visible = false;
        $this->monto_documento_moneda->Visible = false;
        $this->monto_documento_bs->setVisibility();
        $this->monto_documento_usd->Visible = false;
        $this->monto_aplicado_bs->Visible = false;
        $this->monto_aplicado_usd->Visible = false;
        $this->total_cobrado_bs->setVisibility();
        $this->total_cobrado_usd->Visible = false;
        $this->cantidad_cobros->Visible = false;
        $this->fecha_ultimo_cobro->Visible = false;
        $this->saldo_bs->setVisibility();
        $this->saldo_usd->Visible = false;
        $this->estado_cuenta->Visible = false;
        $this->dias_vencido->setVisibility();
        $this->antiguedad->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'view_cuentas_por_cobrar';
        $this->TableName = 'view_cuentas_por_cobrar';

        // Table CSS class
        $this->TableClass = "table table-sm ew-table";

        // CSS class name as context
        $this->ContextClass = CheckClassName($this->TableVar);
        AppendClass($this->TableGridClass, $this->ContextClass);

        // Fixed header table
        if (!$this->UseCustomTemplate) {
            $this->setFixedHeaderTable(Config("USE_FIXED_HEADER_TABLE"), Config("FIXED_HEADER_TABLE_HEIGHT"));
        }

        // Initialize
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (view_cuentas_por_cobrar)
        if (!isset($GLOBALS["view_cuentas_por_cobrar"]) || $GLOBALS["view_cuentas_por_cobrar"]::class == PROJECT_NAMESPACE . "view_cuentas_por_cobrar") {
            $GLOBALS["view_cuentas_por_cobrar"] = &$this;
        }
        $this->AddUrl = "ViewCuentasPorCobrarAdd";

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

        // List options
        $this->ListOptions = new ListOptions(Tag: "td", TableVar: $this->TableVar);

        // Other options
        $this->OtherOptions = new ListOptionsArray();

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions(
            TagClassName: "ew-add-edit-option",
            UseDropDownButton: false,
            DropDownButtonPhrase: $Language->phrase("ButtonAddEdit"),
            UseButtonGroup: true
        );
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $HeaderOptions; // Header options
    public $FooterOptions; // Footer options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $ShowOtherOptions = false;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = ""; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $InlineRowCount = 0;
    public $StartRowCount = 1;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $PageAction;
    public $RecKeys = [];
    public $IsModal = false;
    protected $FilterForModalActions = "";
    private $UseInfiniteScroll = false;

    /**
     * Load result set from filter
     *
     * @return void
     */
    public function loadRecordsetFromFilter($filter)
    {
        // Set up list options
        $this->setupListOptions();

        // Search options
        $this->setupSearchOptions();

        // Other options
        $this->setupOtherOptions();

        // Set visibility
        $this->setVisibility();

        // Load result set
        $this->TotalRecords = $this->loadRecordCount($filter);
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords;
        $this->CurrentFilter = $filter;
        $this->Recordset = $this->loadRecordset();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);
    }

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $DashboardReport;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");
        $DashboardReport ??= Param(Config("PAGE_DASHBOARD"));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }
        if (Param("export") !== null) {
            $this->Export = Param("export");
        }

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
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

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->tipo_documento_fiscal);
        $this->setupLookupOptions($this->entregado);
        $this->setupLookupOptions($this->pagado);
        $this->setupLookupOptions($this->igtf);

        // Load default values for add
        $this->loadDefaultValues();

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fview_cuentas_por_cobrargrid";
        }

        // Set up page action
        $this->PageAction = CurrentPageUrl(false);

        // Set up infinite scroll
        $this->UseInfiniteScroll = ConvertToBool(Param("infinitescroll"));

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $query = ""; // Query builder

        // Set up Dashboard Filter
        if ($DashboardReport) {
            AddFilter($this->Filter, $this->getDashboardFilter($DashboardReport, $this->TableVar));
        }

        // Get command
        $this->Command = strtolower(Get("cmd", ""));

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Hide list options
        if ($this->isExport()) {
            $this->ListOptions->hideAllOptions(["sequence"]);
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        } elseif ($this->isGridAdd() || $this->isGridEdit() || $this->isMultiEdit() || $this->isConfirm()) {
            $this->ListOptions->hideAllOptions();
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        }

        // Hide other options
        if ($this->isExport()) {
            $this->OtherOptions->hideAllOptions();
        }

        // Show grid delete link for grid add / grid edit
        if ($this->AllowAddDeleteRow) {
            if ($this->isGridAdd() || $this->isGridEdit()) {
                $item = $this->ListOptions["griddelete"];
                if ($item) {
                    $item->Visible = false;
                }
            }
        }

        // Set up sorting order
        $this->setupSortOrder();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Build filter
        if (!$Security->canList()) {
            $this->Filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter from session
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Restore master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Restore detail filter from session
        AddFilter($this->Filter, $this->DbDetailFilter);
        AddFilter($this->Filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "view_cuentas_por_cobrar_resumen") {
            $masterTbl = Container("view_cuentas_por_cobrar_resumen");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("ViewCuentasPorCobrarResumenList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = RowType::MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $this->Filter;
        } else {
            $this->setSessionWhere($this->Filter);
            $this->CurrentFilter = "";
        }
        $this->Filter = $this->applyUserIDFilters($this->Filter);
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } elseif (($this->isEdit() || $this->isCopy() || $this->isInlineInserted() || $this->isInlineUpdated()) && $this->UseInfiniteScroll) { // Get current record only
            $this->CurrentFilter = $this->isInlineUpdated() ? $this->getRecordFilter() : $this->getFilterFromRecordKeys();
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } elseif (
            $this->UseInfiniteScroll && $this->isGridInserted() ||
            $this->UseInfiniteScroll && ($this->isGridEdit() || $this->isGridUpdated()) ||
            $this->isMultiEdit() ||
            $this->UseInfiniteScroll && $this->isMultiUpdated()
        ) { // Get current records only
            $this->CurrentFilter = $this->FilterForModalActions; // Restore filter
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
        }

        // API list action
        if (IsApi()) {
            if (Route(0) == Config("API_LIST_ACTION")) {
                if (!$this->isExport()) {
                    $rows = $this->getRecordsFromRecordset($this->Recordset);
                    $this->Recordset?->free();
                    WriteJson([
                        "success" => true,
                        "action" => Config("API_LIST_ACTION"),
                        $this->TableVar => $rows,
                        "totalRecordCount" => $this->TotalRecords
                    ]);
                    $this->terminate(true);
                }
                return;
            } elseif ($this->getFailureMessage() != "") {
                WriteJson(["error" => $this->getFailureMessage()]);
                $this->clearFailureMessage();
                $this->terminate(true);
                return;
            }
        }

        // Render other options
        $this->renderOtherOptions();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set ReturnUrl in header if necessary
        if ($returnUrl = Container("app.flash")->getFirstMessage("Return-Url")) {
            AddHeader("Return-Url", GetUrl($returnUrl));
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

    // Get page number
    public function getPageNumber()
    {
        return ($this->DisplayRecords > 0 && $this->StartRecord > 0) ? ceil($this->StartRecord / $this->DisplayRecords) : 1;
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->monto_documento_bs->FormValue = ""; // Clear form value
        $this->total_cobrado_bs->FormValue = ""; // Clear form value
        $this->saldo_bs->FormValue = ""; // Clear form value
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to grid add mode
    protected function gridAddMode()
    {
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to grid edit mode
    protected function gridEditMode()
    {
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old result set
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAllAssociative();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            $this->EventCancelled = true;
            return false;
        }
        $this->loadDefaultValues();
        $wrkfilter = "";
        $key = "";

        // Update row index and get row key
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete" && $rowaction != "hide") { // Skip insert then deleted rows / hidden rows for grid edit
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                        if ($gridUpdate) { // Get inserted or updated filter
                            AddFilter($wrkfilter, $this->getRecordFilter(), "OR");
                        }
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    $this->EventCancelled = true;
                    break;
                }
            }
        }
        if ($gridUpdate) {
            $this->FilterForModalActions = $wrkfilter;

            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Perform grid add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            $this->EventCancelled = true;
            return false;
        }
        $this->loadDefaultValues();

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            $rsold = null;
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $rsold = $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success
                $gridInsert = $this->addRow($rsold); // Insert row (already validated by validateGridForm())
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->id->CurrentValue;

                    // Add filter for this record
                    AddFilter($wrkfilter, $this->getRecordFilter(), "OR");
                } else {
                    $this->EventCancelled = true;
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $this->FilterForModalActions = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if (
            $CurrentForm->hasValue("x_cliente") &&
            $CurrentForm->hasValue("o_cliente") &&
            $this->cliente->CurrentValue != $this->cliente->DefaultValue &&
            !($this->cliente->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->cliente->CurrentValue == $this->cliente->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_cliente_rif") &&
            $CurrentForm->hasValue("o_cliente_rif") &&
            $this->cliente_rif->CurrentValue != $this->cliente_rif->DefaultValue &&
            !($this->cliente_rif->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->cliente_rif->CurrentValue == $this->cliente_rif->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_cliente_nombre") &&
            $CurrentForm->hasValue("o_cliente_nombre") &&
            $this->cliente_nombre->CurrentValue != $this->cliente_nombre->DefaultValue &&
            !($this->cliente_nombre->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->cliente_nombre->CurrentValue == $this->cliente_nombre->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_tipo_documento_fiscal") &&
            $CurrentForm->hasValue("o_tipo_documento_fiscal") &&
            $this->tipo_documento_fiscal->CurrentValue != $this->tipo_documento_fiscal->DefaultValue &&
            !($this->tipo_documento_fiscal->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->tipo_documento_fiscal->CurrentValue == $this->tipo_documento_fiscal->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_nro_documento") &&
            $CurrentForm->hasValue("o_nro_documento") &&
            $this->nro_documento->CurrentValue != $this->nro_documento->DefaultValue &&
            !($this->nro_documento->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->nro_documento->CurrentValue == $this->nro_documento->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_fecha") &&
            $CurrentForm->hasValue("o_fecha") &&
            $this->fecha->CurrentValue != $this->fecha->DefaultValue &&
            !($this->fecha->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->fecha->CurrentValue == $this->fecha->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_monto_documento_bs") &&
            $CurrentForm->hasValue("o_monto_documento_bs") &&
            $this->monto_documento_bs->CurrentValue != $this->monto_documento_bs->DefaultValue &&
            !($this->monto_documento_bs->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->monto_documento_bs->CurrentValue == $this->monto_documento_bs->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_total_cobrado_bs") &&
            $CurrentForm->hasValue("o_total_cobrado_bs") &&
            $this->total_cobrado_bs->CurrentValue != $this->total_cobrado_bs->DefaultValue &&
            !($this->total_cobrado_bs->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->total_cobrado_bs->CurrentValue == $this->total_cobrado_bs->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_saldo_bs") &&
            $CurrentForm->hasValue("o_saldo_bs") &&
            $this->saldo_bs->CurrentValue != $this->saldo_bs->DefaultValue &&
            !($this->saldo_bs->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->saldo_bs->CurrentValue == $this->saldo_bs->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_dias_vencido") &&
            $CurrentForm->hasValue("o_dias_vencido") &&
            $this->dias_vencido->CurrentValue != $this->dias_vencido->DefaultValue &&
            !($this->dias_vencido->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->dias_vencido->CurrentValue == $this->dias_vencido->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_antiguedad") &&
            $CurrentForm->hasValue("o_antiguedad") &&
            $this->antiguedad->CurrentValue != $this->antiguedad->DefaultValue &&
            !($this->antiguedad->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->antiguedad->CurrentValue == $this->antiguedad->getSessionValue())
        ) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;

        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Load default values for emptyRow checking
        $this->loadDefaultValues();

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete" && $rowaction != "hide") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    $this->ValidationErrors[$rowindex] = $this->getValidationErrors();
                    $this->EventCancelled = true;
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->resetIndex();
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        foreach ($this->Fields as $field) {
            $field->clearErrorMessage();
        }
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = ""; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->setStartRecordNumber(1); // Reset start position
        }

        // Update field sort
        $this->updateFieldSort();
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->cliente->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = true;
            $item->Visible = false; // Default hidden
        }

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = true;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
        // Preview extension
        $this->ListOptions->hideDetailItemsForDropDown(); // Hide detail items for dropdown if necessary
    }

    // Add "hash" parameter to URL
    public function urlAddHash($url, $hash)
    {
        return $this->UseAjaxActions ? $url : UrlAddQuery($url, "hash=" . $hash);
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-ew-action=\"delete-grid-row\" data-rowindex=\"" . $this->RowIndex . "\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"view_cuentas_por_cobrar\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }
        } // End View mode
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Active user filter
    // - Get active users by SQL (SELECT COUNT(*) FROM UserTable WHERE ProfileField LIKE '%"SessionID":%')
    protected function activeUserFilter()
    {
        if (UserProfile::$FORCE_LOGOUT_USER) {
            $userProfileField = $this->Fields[Config("USER_PROFILE_FIELD_NAME")];
            return $userProfileField->Expression . " LIKE '%\"" . UserProfile::$SESSION_ID . "\":%'";
        }
        return "0=1"; // No active users
    }

    // Create new column option
    protected function createColumnOption($option, $name)
    {
        $field = $this->Fields[$name] ?? null;
        if ($field?->Visible) {
            $item = $option->add($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
        }
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
            if (in_array($this->CurrentMode, ["add", "copy", "edit"]) && !$this->isConfirm()) { // Check add/copy/edit mode
                if ($this->AllowAddDeleteRow) {
                    $option = $options["addedit"];
                    $option->UseDropDownButton = false;
                    $item = &$option->add("addblankrow");
                    $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                    $item->Visible = false;
                    $this->ShowOtherOptions = $item->Visible;
                }
            }
            if ($this->CurrentMode == "view") { // Check view mode
                $option = $options["addedit"];
                $item = $option["add"];
                $this->ShowOtherOptions = $item?->Visible ?? false;
            }
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        $this->StartRecord = 1;
        $this->StopRecord = $this->TotalRecords; // Show all records

        // Restore number of post back records
        if ($CurrentForm && ($this->isConfirm() || $this->EventCancelled)) {
            $CurrentForm->resetIndex();
            if ($CurrentForm->hasValue($this->FormKeyCountName) && ($this->isGridAdd() || $this->isGridEdit() || $this->isConfirm())) {
                $this->KeyCount = $CurrentForm->getValue($this->FormKeyCountName);
                $this->StopRecord = $this->StartRecord + $this->KeyCount - 1;
            }
        }
        $this->RecordCount = $this->StartRecord - 1;
        if ($this->CurrentRow !== false) {
            // Nothing to do
        } elseif ($this->isGridAdd() && !$this->AllowAddDeleteRow && $this->StopRecord == 0) { // Grid-Add with no records
            $this->StopRecord = $this->GridAddRowCount;
        } elseif ($this->isAdd() && $this->TotalRecords == 0) { // Inline-Add with no records
            $this->StopRecord = 1;
        }

        // Initialize aggregate
        $this->RowType = RowType::AGGREGATEINIT;
        $this->resetAttributes();
        $this->renderRow();
        if (($this->isGridAdd() || $this->isGridEdit())) { // Render template row first
            $this->RowIndex = '$rowindex$';
        }
    }

    // Set up Row
    public function setupRow()
    {
        global $CurrentForm;
        if ($this->isGridAdd() || $this->isGridEdit()) {
            if ($this->RowIndex === '$rowindex$') { // Render template row first
                $this->loadRowValues();

                // Set row properties
                $this->resetAttributes();
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_view_cuentas_por_cobrar", "data-rowtype" => RowType::ADD]);
                $this->RowAttrs->appendClass("ew-template");
                // Render row
                $this->RowType = RowType::ADD;
                $this->renderRow();

                // Render list options
                $this->renderListOptions();

                // Reset record count for template row
                $this->RecordCount--;
                return;
            }
        }
        if ($this->isGridAdd() || $this->isGridEdit() || $this->isConfirm() || $this->isMultiEdit()) {
            $this->RowIndex++;
            $CurrentForm->Index = $this->RowIndex;
            if ($CurrentForm->hasValue($this->FormActionName) && ($this->isConfirm() || $this->EventCancelled)) {
                $this->RowAction = strval($CurrentForm->getValue($this->FormActionName));
            } elseif ($this->isGridAdd()) {
                $this->RowAction = "insert";
            } else {
                $this->RowAction = "";
            }
        }

        // Set up key count
        $this->KeyCount = $this->RowIndex;

        // Init row class and style
        $this->resetAttributes();
        $this->CssClass = "";
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->loadRowValues($this->CurrentRow); // Load row values
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
            } else {
                $this->loadRowValues(); // Load default values
                $this->OldKey = "";
            }
        } else {
            $this->loadRowValues($this->CurrentRow); // Load row values
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
        }
        $this->setKey($this->OldKey);
        $this->RowType = RowType::VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = RowType::ADD; // Render add
        }
        if ($this->isGridAdd() && $this->EventCancelled && !$CurrentForm->hasValue($this->FormBlankRowName)) { // Insert failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isGridEdit()) { // Grid edit
            if ($this->EventCancelled) {
                $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
            }
            if ($this->RowAction == "insert") {
                $this->RowType = RowType::ADD; // Render add
            } else {
                $this->RowType = RowType::EDIT; // Render edit
            }
        }
        if ($this->isGridEdit() && ($this->RowType == RowType::EDIT || $this->RowType == RowType::ADD) && $this->EventCancelled) { // Update failed
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }
        if ($this->isConfirm()) { // Confirm row
            $this->restoreCurrentRowFormValues($this->RowIndex); // Restore form values
        }

        // Inline Add/Copy row (row 0)
        if ($this->RowType == RowType::ADD && ($this->isAdd() || $this->isCopy())) {
            $this->InlineRowCount++;
            $this->RecordCount--; // Reset record count for inline add/copy row
            if ($this->TotalRecords == 0) { // Reset stop record if no records
                $this->StopRecord = 0;
            }
        } else {
            // Inline Edit row
            if ($this->RowType == RowType::EDIT && $this->isEdit()) {
                $this->InlineRowCount++;
            }
            $this->RowCount++; // Increment row count
        }

        // Set up row attributes
        $this->RowAttrs->merge([
            "data-rowindex" => $this->RowCount,
            "data-key" => $this->getKey(true),
            "id" => "r" . $this->RowCount . "_view_cuentas_por_cobrar",
            "data-rowtype" => $this->RowType,
            "data-inline" => ($this->isAdd() || $this->isCopy() || $this->isEdit()) ? "true" : "false", // Inline-Add/Copy/Edit
            "class" => ($this->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($this->isAdd() && $this->RowType == RowType::ADD || $this->isEdit() && $this->RowType == RowType::EDIT) { // Inline-Add/Edit row
            $this->RowAttrs->appendClass("table-active");
        }

        // Render row
        $this->renderRow();

        // Render list options
        $this->renderListOptions();
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->tasa_dia->DefaultValue = $this->tasa_dia->getDefault(); // PHP
        $this->tasa_dia->OldValue = $this->tasa_dia->DefaultValue;
        $this->dias_credito->DefaultValue = $this->dias_credito->getDefault(); // PHP
        $this->dias_credito->OldValue = $this->dias_credito->DefaultValue;
        $this->entregado->DefaultValue = $this->entregado->getDefault(); // PHP
        $this->entregado->OldValue = $this->entregado->DefaultValue;
        $this->pagado->DefaultValue = $this->pagado->getDefault(); // PHP
        $this->pagado->OldValue = $this->pagado->DefaultValue;
        $this->monto_igtf_bs->DefaultValue = $this->monto_igtf_bs->getDefault(); // PHP
        $this->monto_igtf_bs->OldValue = $this->monto_igtf_bs->DefaultValue;
        $this->signo_documento->DefaultValue = $this->signo_documento->getDefault(); // PHP
        $this->signo_documento->OldValue = $this->signo_documento->DefaultValue;
        $this->monto_documento_bs->DefaultValue = $this->monto_documento_bs->getDefault(); // PHP
        $this->monto_documento_bs->OldValue = $this->monto_documento_bs->DefaultValue;
        $this->monto_aplicado_bs->DefaultValue = $this->monto_aplicado_bs->getDefault(); // PHP
        $this->monto_aplicado_bs->OldValue = $this->monto_aplicado_bs->DefaultValue;
        $this->total_cobrado_bs->DefaultValue = $this->total_cobrado_bs->getDefault(); // PHP
        $this->total_cobrado_bs->OldValue = $this->total_cobrado_bs->DefaultValue;
        $this->total_cobrado_usd->DefaultValue = $this->total_cobrado_usd->getDefault(); // PHP
        $this->total_cobrado_usd->OldValue = $this->total_cobrado_usd->DefaultValue;
        $this->cantidad_cobros->DefaultValue = $this->cantidad_cobros->getDefault(); // PHP
        $this->cantidad_cobros->OldValue = $this->cantidad_cobros->DefaultValue;
        $this->saldo_bs->DefaultValue = $this->saldo_bs->getDefault(); // PHP
        $this->saldo_bs->OldValue = $this->saldo_bs->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_cliente")) {
            $this->cliente->setOldValue($CurrentForm->getValue("o_cliente"));
        }

        // Check field name 'cliente_rif' first before field var 'x_cliente_rif'
        $val = $CurrentForm->hasValue("cliente_rif") ? $CurrentForm->getValue("cliente_rif") : $CurrentForm->getValue("x_cliente_rif");
        if (!$this->cliente_rif->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente_rif->Visible = false; // Disable update for API request
            } else {
                $this->cliente_rif->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_cliente_rif")) {
            $this->cliente_rif->setOldValue($CurrentForm->getValue("o_cliente_rif"));
        }

        // Check field name 'cliente_nombre' first before field var 'x_cliente_nombre'
        $val = $CurrentForm->hasValue("cliente_nombre") ? $CurrentForm->getValue("cliente_nombre") : $CurrentForm->getValue("x_cliente_nombre");
        if (!$this->cliente_nombre->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente_nombre->Visible = false; // Disable update for API request
            } else {
                $this->cliente_nombre->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_cliente_nombre")) {
            $this->cliente_nombre->setOldValue($CurrentForm->getValue("o_cliente_nombre"));
        }

        // Check field name 'tipo_documento_fiscal' first before field var 'x_tipo_documento_fiscal'
        $val = $CurrentForm->hasValue("tipo_documento_fiscal") ? $CurrentForm->getValue("tipo_documento_fiscal") : $CurrentForm->getValue("x_tipo_documento_fiscal");
        if (!$this->tipo_documento_fiscal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_documento_fiscal->Visible = false; // Disable update for API request
            } else {
                $this->tipo_documento_fiscal->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_tipo_documento_fiscal")) {
            $this->tipo_documento_fiscal->setOldValue($CurrentForm->getValue("o_tipo_documento_fiscal"));
        }

        // Check field name 'nro_documento' first before field var 'x_nro_documento'
        $val = $CurrentForm->hasValue("nro_documento") ? $CurrentForm->getValue("nro_documento") : $CurrentForm->getValue("x_nro_documento");
        if (!$this->nro_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_documento->Visible = false; // Disable update for API request
            } else {
                $this->nro_documento->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_nro_documento")) {
            $this->nro_documento->setOldValue($CurrentForm->getValue("o_nro_documento"));
        }

        // Check field name 'fecha' first before field var 'x_fecha'
        $val = $CurrentForm->hasValue("fecha") ? $CurrentForm->getValue("fecha") : $CurrentForm->getValue("x_fecha");
        if (!$this->fecha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha->Visible = false; // Disable update for API request
            } else {
                $this->fecha->setFormValue($val, true, $validate);
            }
            $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        }
        if ($CurrentForm->hasValue("o_fecha")) {
            $this->fecha->setOldValue($CurrentForm->getValue("o_fecha"));
        }

        // Check field name 'monto_documento_bs' first before field var 'x_monto_documento_bs'
        $val = $CurrentForm->hasValue("monto_documento_bs") ? $CurrentForm->getValue("monto_documento_bs") : $CurrentForm->getValue("x_monto_documento_bs");
        if (!$this->monto_documento_bs->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_documento_bs->Visible = false; // Disable update for API request
            } else {
                $this->monto_documento_bs->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_monto_documento_bs")) {
            $this->monto_documento_bs->setOldValue($CurrentForm->getValue("o_monto_documento_bs"));
        }

        // Check field name 'total_cobrado_bs' first before field var 'x_total_cobrado_bs'
        $val = $CurrentForm->hasValue("total_cobrado_bs") ? $CurrentForm->getValue("total_cobrado_bs") : $CurrentForm->getValue("x_total_cobrado_bs");
        if (!$this->total_cobrado_bs->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->total_cobrado_bs->Visible = false; // Disable update for API request
            } else {
                $this->total_cobrado_bs->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_total_cobrado_bs")) {
            $this->total_cobrado_bs->setOldValue($CurrentForm->getValue("o_total_cobrado_bs"));
        }

        // Check field name 'saldo_bs' first before field var 'x_saldo_bs'
        $val = $CurrentForm->hasValue("saldo_bs") ? $CurrentForm->getValue("saldo_bs") : $CurrentForm->getValue("x_saldo_bs");
        if (!$this->saldo_bs->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->saldo_bs->Visible = false; // Disable update for API request
            } else {
                $this->saldo_bs->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_saldo_bs")) {
            $this->saldo_bs->setOldValue($CurrentForm->getValue("o_saldo_bs"));
        }

        // Check field name 'dias_vencido' first before field var 'x_dias_vencido'
        $val = $CurrentForm->hasValue("dias_vencido") ? $CurrentForm->getValue("dias_vencido") : $CurrentForm->getValue("x_dias_vencido");
        if (!$this->dias_vencido->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->dias_vencido->Visible = false; // Disable update for API request
            } else {
                $this->dias_vencido->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_dias_vencido")) {
            $this->dias_vencido->setOldValue($CurrentForm->getValue("o_dias_vencido"));
        }

        // Check field name 'antiguedad' first before field var 'x_antiguedad'
        $val = $CurrentForm->hasValue("antiguedad") ? $CurrentForm->getValue("antiguedad") : $CurrentForm->getValue("x_antiguedad");
        if (!$this->antiguedad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->antiguedad->Visible = false; // Disable update for API request
            } else {
                $this->antiguedad->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_antiguedad")) {
            $this->antiguedad->setOldValue($CurrentForm->getValue("o_antiguedad"));
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->id->CurrentValue = $this->id->FormValue;
        }
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->cliente_rif->CurrentValue = $this->cliente_rif->FormValue;
        $this->cliente_nombre->CurrentValue = $this->cliente_nombre->FormValue;
        $this->tipo_documento_fiscal->CurrentValue = $this->tipo_documento_fiscal->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->monto_documento_bs->CurrentValue = $this->monto_documento_bs->FormValue;
        $this->total_cobrado_bs->CurrentValue = $this->total_cobrado_bs->FormValue;
        $this->saldo_bs->CurrentValue = $this->saldo_bs->FormValue;
        $this->dias_vencido->CurrentValue = $this->dias_vencido->FormValue;
        $this->antiguedad->CurrentValue = $this->antiguedad->FormValue;
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

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = ExecuteQuery($sql, $conn);
            if ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

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

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // monto_documento_bs
            $this->monto_documento_bs->HrefValue = "";
            $this->monto_documento_bs->TooltipValue = "";

            // total_cobrado_bs
            $this->total_cobrado_bs->HrefValue = "";
            $this->total_cobrado_bs->TooltipValue = "";

            // saldo_bs
            $this->saldo_bs->HrefValue = "";
            $this->saldo_bs->TooltipValue = "";

            // dias_vencido
            $this->dias_vencido->HrefValue = "";
            $this->dias_vencido->TooltipValue = "";

            // antiguedad
            $this->antiguedad->HrefValue = "";
            $this->antiguedad->TooltipValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // cliente
            if ($this->cliente->getSessionValue() != "") {
                $this->cliente->CurrentValue = GetForeignKeyValue($this->cliente->getSessionValue());
                $this->cliente->OldValue = $this->cliente->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->cliente->CurrentValue));
                if ($curVal != "") {
                    $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                } else {
                    $this->cliente->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->lookupOptions()) && count($this->cliente->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->cliente->ViewValue !== null) { // Load from cache
                    $this->cliente->EditValue = array_values($this->cliente->lookupOptions());
                    if ($this->cliente->ViewValue == "") {
                        $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                    }
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cliente->CurrentValue, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    }
                    $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                    }
                    $arwrk = $rswrk;
                    $this->cliente->EditValue = $arwrk;
                }
                $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());
            }

            // cliente_rif
            $this->cliente_rif->setupEditAttributes();
            if (!$this->cliente_rif->Raw) {
                $this->cliente_rif->CurrentValue = HtmlDecode($this->cliente_rif->CurrentValue);
            }
            $this->cliente_rif->EditValue = HtmlEncode($this->cliente_rif->CurrentValue);
            $this->cliente_rif->PlaceHolder = RemoveHtml($this->cliente_rif->caption());

            // cliente_nombre
            $this->cliente_nombre->setupEditAttributes();
            if (!$this->cliente_nombre->Raw) {
                $this->cliente_nombre->CurrentValue = HtmlDecode($this->cliente_nombre->CurrentValue);
            }
            $this->cliente_nombre->EditValue = HtmlEncode($this->cliente_nombre->CurrentValue);
            $this->cliente_nombre->PlaceHolder = RemoveHtml($this->cliente_nombre->caption());

            // tipo_documento_fiscal
            $this->tipo_documento_fiscal->setupEditAttributes();
            $this->tipo_documento_fiscal->EditValue = $this->tipo_documento_fiscal->options(true);
            $this->tipo_documento_fiscal->PlaceHolder = RemoveHtml($this->tipo_documento_fiscal->caption());

            // nro_documento
            $this->nro_documento->setupEditAttributes();
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // monto_documento_bs
            $this->monto_documento_bs->setupEditAttributes();
            $this->monto_documento_bs->EditValue = $this->monto_documento_bs->CurrentValue;
            $this->monto_documento_bs->PlaceHolder = RemoveHtml($this->monto_documento_bs->caption());
            if (strval($this->monto_documento_bs->EditValue) != "" && is_numeric($this->monto_documento_bs->EditValue)) {
                $this->monto_documento_bs->EditValue = FormatNumber($this->monto_documento_bs->EditValue, null);
            }

            // total_cobrado_bs
            $this->total_cobrado_bs->setupEditAttributes();
            $this->total_cobrado_bs->EditValue = $this->total_cobrado_bs->CurrentValue;
            $this->total_cobrado_bs->PlaceHolder = RemoveHtml($this->total_cobrado_bs->caption());
            if (strval($this->total_cobrado_bs->EditValue) != "" && is_numeric($this->total_cobrado_bs->EditValue)) {
                $this->total_cobrado_bs->EditValue = FormatNumber($this->total_cobrado_bs->EditValue, null);
            }

            // saldo_bs
            $this->saldo_bs->setupEditAttributes();
            $this->saldo_bs->EditValue = $this->saldo_bs->CurrentValue;
            $this->saldo_bs->PlaceHolder = RemoveHtml($this->saldo_bs->caption());
            if (strval($this->saldo_bs->EditValue) != "" && is_numeric($this->saldo_bs->EditValue)) {
                $this->saldo_bs->EditValue = FormatNumber($this->saldo_bs->EditValue, null);
            }

            // dias_vencido
            $this->dias_vencido->setupEditAttributes();
            $this->dias_vencido->EditValue = $this->dias_vencido->CurrentValue;
            $this->dias_vencido->PlaceHolder = RemoveHtml($this->dias_vencido->caption());
            if (strval($this->dias_vencido->EditValue) != "" && is_numeric($this->dias_vencido->EditValue)) {
                $this->dias_vencido->EditValue = FormatNumber($this->dias_vencido->EditValue, null);
            }

            // antiguedad
            $this->antiguedad->setupEditAttributes();
            if (!$this->antiguedad->Raw) {
                $this->antiguedad->CurrentValue = HtmlDecode($this->antiguedad->CurrentValue);
            }
            $this->antiguedad->EditValue = HtmlEncode($this->antiguedad->CurrentValue);
            $this->antiguedad->PlaceHolder = RemoveHtml($this->antiguedad->caption());

            // Add refer script

            // cliente
            $this->cliente->HrefValue = "";

            // cliente_rif
            $this->cliente_rif->HrefValue = "";

            // cliente_nombre
            $this->cliente_nombre->HrefValue = "";

            // tipo_documento_fiscal
            $this->tipo_documento_fiscal->HrefValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // monto_documento_bs
            $this->monto_documento_bs->HrefValue = "";

            // total_cobrado_bs
            $this->total_cobrado_bs->HrefValue = "";

            // saldo_bs
            $this->saldo_bs->HrefValue = "";

            // dias_vencido
            $this->dias_vencido->HrefValue = "";

            // antiguedad
            $this->antiguedad->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // cliente
            if ($this->cliente->getSessionValue() != "") {
                $this->cliente->CurrentValue = GetForeignKeyValue($this->cliente->getSessionValue());
                $this->cliente->OldValue = $this->cliente->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->cliente->CurrentValue));
                if ($curVal != "") {
                    $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                } else {
                    $this->cliente->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->lookupOptions()) && count($this->cliente->lookupOptions()) > 0 ? $curVal : null;
                }
                if ($this->cliente->ViewValue !== null) { // Load from cache
                    $this->cliente->EditValue = array_values($this->cliente->lookupOptions());
                    if ($this->cliente->ViewValue == "") {
                        $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                    }
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cliente->CurrentValue, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    }
                    $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                    }
                    $arwrk = $rswrk;
                    $this->cliente->EditValue = $arwrk;
                }
                $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());
            }

            // cliente_rif
            $this->cliente_rif->setupEditAttributes();
            if (!$this->cliente_rif->Raw) {
                $this->cliente_rif->CurrentValue = HtmlDecode($this->cliente_rif->CurrentValue);
            }
            $this->cliente_rif->EditValue = HtmlEncode($this->cliente_rif->CurrentValue);
            $this->cliente_rif->PlaceHolder = RemoveHtml($this->cliente_rif->caption());

            // cliente_nombre
            $this->cliente_nombre->setupEditAttributes();
            if (!$this->cliente_nombre->Raw) {
                $this->cliente_nombre->CurrentValue = HtmlDecode($this->cliente_nombre->CurrentValue);
            }
            $this->cliente_nombre->EditValue = HtmlEncode($this->cliente_nombre->CurrentValue);
            $this->cliente_nombre->PlaceHolder = RemoveHtml($this->cliente_nombre->caption());

            // tipo_documento_fiscal
            $this->tipo_documento_fiscal->setupEditAttributes();
            $this->tipo_documento_fiscal->EditValue = $this->tipo_documento_fiscal->options(true);
            $this->tipo_documento_fiscal->PlaceHolder = RemoveHtml($this->tipo_documento_fiscal->caption());

            // nro_documento
            $this->nro_documento->setupEditAttributes();
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // monto_documento_bs
            $this->monto_documento_bs->setupEditAttributes();
            $this->monto_documento_bs->EditValue = $this->monto_documento_bs->CurrentValue;
            $this->monto_documento_bs->PlaceHolder = RemoveHtml($this->monto_documento_bs->caption());
            if (strval($this->monto_documento_bs->EditValue) != "" && is_numeric($this->monto_documento_bs->EditValue)) {
                $this->monto_documento_bs->EditValue = FormatNumber($this->monto_documento_bs->EditValue, null);
            }

            // total_cobrado_bs
            $this->total_cobrado_bs->setupEditAttributes();
            $this->total_cobrado_bs->EditValue = $this->total_cobrado_bs->CurrentValue;
            $this->total_cobrado_bs->PlaceHolder = RemoveHtml($this->total_cobrado_bs->caption());
            if (strval($this->total_cobrado_bs->EditValue) != "" && is_numeric($this->total_cobrado_bs->EditValue)) {
                $this->total_cobrado_bs->EditValue = FormatNumber($this->total_cobrado_bs->EditValue, null);
            }

            // saldo_bs
            $this->saldo_bs->setupEditAttributes();
            $this->saldo_bs->EditValue = $this->saldo_bs->CurrentValue;
            $this->saldo_bs->PlaceHolder = RemoveHtml($this->saldo_bs->caption());
            if (strval($this->saldo_bs->EditValue) != "" && is_numeric($this->saldo_bs->EditValue)) {
                $this->saldo_bs->EditValue = FormatNumber($this->saldo_bs->EditValue, null);
            }

            // dias_vencido
            $this->dias_vencido->setupEditAttributes();
            $this->dias_vencido->EditValue = $this->dias_vencido->CurrentValue;
            $this->dias_vencido->PlaceHolder = RemoveHtml($this->dias_vencido->caption());
            if (strval($this->dias_vencido->EditValue) != "" && is_numeric($this->dias_vencido->EditValue)) {
                $this->dias_vencido->EditValue = FormatNumber($this->dias_vencido->EditValue, null);
            }

            // antiguedad
            $this->antiguedad->setupEditAttributes();
            if (!$this->antiguedad->Raw) {
                $this->antiguedad->CurrentValue = HtmlDecode($this->antiguedad->CurrentValue);
            }
            $this->antiguedad->EditValue = HtmlEncode($this->antiguedad->CurrentValue);
            $this->antiguedad->PlaceHolder = RemoveHtml($this->antiguedad->caption());

            // Edit refer script

            // cliente
            $this->cliente->HrefValue = "";

            // cliente_rif
            $this->cliente_rif->HrefValue = "";

            // cliente_nombre
            $this->cliente_nombre->HrefValue = "";

            // tipo_documento_fiscal
            $this->tipo_documento_fiscal->HrefValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // monto_documento_bs
            $this->monto_documento_bs->HrefValue = "";

            // total_cobrado_bs
            $this->total_cobrado_bs->HrefValue = "";

            // saldo_bs
            $this->saldo_bs->HrefValue = "";

            // dias_vencido
            $this->dias_vencido->HrefValue = "";

            // antiguedad
            $this->antiguedad->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->cliente->Visible && $this->cliente->Required) {
                if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                    $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
                }
            }
            if ($this->cliente_rif->Visible && $this->cliente_rif->Required) {
                if (!$this->cliente_rif->IsDetailKey && EmptyValue($this->cliente_rif->FormValue)) {
                    $this->cliente_rif->addErrorMessage(str_replace("%s", $this->cliente_rif->caption(), $this->cliente_rif->RequiredErrorMessage));
                }
            }
            if ($this->cliente_nombre->Visible && $this->cliente_nombre->Required) {
                if (!$this->cliente_nombre->IsDetailKey && EmptyValue($this->cliente_nombre->FormValue)) {
                    $this->cliente_nombre->addErrorMessage(str_replace("%s", $this->cliente_nombre->caption(), $this->cliente_nombre->RequiredErrorMessage));
                }
            }
            if ($this->tipo_documento_fiscal->Visible && $this->tipo_documento_fiscal->Required) {
                if (!$this->tipo_documento_fiscal->IsDetailKey && EmptyValue($this->tipo_documento_fiscal->FormValue)) {
                    $this->tipo_documento_fiscal->addErrorMessage(str_replace("%s", $this->tipo_documento_fiscal->caption(), $this->tipo_documento_fiscal->RequiredErrorMessage));
                }
            }
            if ($this->nro_documento->Visible && $this->nro_documento->Required) {
                if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                    $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
                }
            }
            if ($this->fecha->Visible && $this->fecha->Required) {
                if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                    $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecha->FormValue, $this->fecha->formatPattern())) {
                $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
            }
            if ($this->monto_documento_bs->Visible && $this->monto_documento_bs->Required) {
                if (!$this->monto_documento_bs->IsDetailKey && EmptyValue($this->monto_documento_bs->FormValue)) {
                    $this->monto_documento_bs->addErrorMessage(str_replace("%s", $this->monto_documento_bs->caption(), $this->monto_documento_bs->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_documento_bs->FormValue)) {
                $this->monto_documento_bs->addErrorMessage($this->monto_documento_bs->getErrorMessage(false));
            }
            if ($this->total_cobrado_bs->Visible && $this->total_cobrado_bs->Required) {
                if (!$this->total_cobrado_bs->IsDetailKey && EmptyValue($this->total_cobrado_bs->FormValue)) {
                    $this->total_cobrado_bs->addErrorMessage(str_replace("%s", $this->total_cobrado_bs->caption(), $this->total_cobrado_bs->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->total_cobrado_bs->FormValue)) {
                $this->total_cobrado_bs->addErrorMessage($this->total_cobrado_bs->getErrorMessage(false));
            }
            if ($this->saldo_bs->Visible && $this->saldo_bs->Required) {
                if (!$this->saldo_bs->IsDetailKey && EmptyValue($this->saldo_bs->FormValue)) {
                    $this->saldo_bs->addErrorMessage(str_replace("%s", $this->saldo_bs->caption(), $this->saldo_bs->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->saldo_bs->FormValue)) {
                $this->saldo_bs->addErrorMessage($this->saldo_bs->getErrorMessage(false));
            }
            if ($this->dias_vencido->Visible && $this->dias_vencido->Required) {
                if (!$this->dias_vencido->IsDetailKey && EmptyValue($this->dias_vencido->FormValue)) {
                    $this->dias_vencido->addErrorMessage(str_replace("%s", $this->dias_vencido->caption(), $this->dias_vencido->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->dias_vencido->FormValue)) {
                $this->dias_vencido->addErrorMessage($this->dias_vencido->getErrorMessage(false));
            }
            if ($this->antiguedad->Visible && $this->antiguedad->Required) {
                if (!$this->antiguedad->IsDetailKey && EmptyValue($this->antiguedad->FormValue)) {
                    $this->antiguedad->addErrorMessage(str_replace("%s", $this->antiguedad->caption(), $this->antiguedad->RequiredErrorMessage));
                }
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['id'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
                if (!$deleteRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        return $deleteRows;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // cliente
        if ($this->cliente->getSessionValue() != "") {
            $this->cliente->ReadOnly = true;
        }
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, $this->cliente->ReadOnly);

        // cliente_rif
        $this->cliente_rif->setDbValueDef($rsnew, $this->cliente_rif->CurrentValue, $this->cliente_rif->ReadOnly);

        // cliente_nombre
        $this->cliente_nombre->setDbValueDef($rsnew, $this->cliente_nombre->CurrentValue, $this->cliente_nombre->ReadOnly);

        // tipo_documento_fiscal
        $this->tipo_documento_fiscal->setDbValueDef($rsnew, $this->tipo_documento_fiscal->CurrentValue, $this->tipo_documento_fiscal->ReadOnly);

        // nro_documento
        $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, $this->nro_documento->ReadOnly);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()), $this->fecha->ReadOnly);

        // monto_documento_bs
        $this->monto_documento_bs->setDbValueDef($rsnew, $this->monto_documento_bs->CurrentValue, $this->monto_documento_bs->ReadOnly);

        // total_cobrado_bs
        $this->total_cobrado_bs->setDbValueDef($rsnew, $this->total_cobrado_bs->CurrentValue, $this->total_cobrado_bs->ReadOnly);

        // saldo_bs
        $this->saldo_bs->setDbValueDef($rsnew, $this->saldo_bs->CurrentValue, $this->saldo_bs->ReadOnly);

        // dias_vencido
        $this->dias_vencido->setDbValueDef($rsnew, $this->dias_vencido->CurrentValue, $this->dias_vencido->ReadOnly);

        // antiguedad
        $this->antiguedad->setDbValueDef($rsnew, $this->antiguedad->CurrentValue, $this->antiguedad->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['cliente'])) { // cliente
            $this->cliente->CurrentValue = $row['cliente'];
        }
        if (isset($row['cliente_rif'])) { // cliente_rif
            $this->cliente_rif->CurrentValue = $row['cliente_rif'];
        }
        if (isset($row['cliente_nombre'])) { // cliente_nombre
            $this->cliente_nombre->CurrentValue = $row['cliente_nombre'];
        }
        if (isset($row['tipo_documento_fiscal'])) { // tipo_documento_fiscal
            $this->tipo_documento_fiscal->CurrentValue = $row['tipo_documento_fiscal'];
        }
        if (isset($row['nro_documento'])) { // nro_documento
            $this->nro_documento->CurrentValue = $row['nro_documento'];
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->CurrentValue = $row['fecha'];
        }
        if (isset($row['monto_documento_bs'])) { // monto_documento_bs
            $this->monto_documento_bs->CurrentValue = $row['monto_documento_bs'];
        }
        if (isset($row['total_cobrado_bs'])) { // total_cobrado_bs
            $this->total_cobrado_bs->CurrentValue = $row['total_cobrado_bs'];
        }
        if (isset($row['saldo_bs'])) { // saldo_bs
            $this->saldo_bs->CurrentValue = $row['saldo_bs'];
        }
        if (isset($row['dias_vencido'])) { // dias_vencido
            $this->dias_vencido->CurrentValue = $row['dias_vencido'];
        }
        if (isset($row['antiguedad'])) { // antiguedad
            $this->antiguedad->CurrentValue = $row['antiguedad'];
        }
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "view_cuentas_por_cobrar_resumen") {
            $this->cliente->Visible = true; // Need to insert foreign key
            $this->cliente->CurrentValue = $this->cliente->getSessionValue();
        }

        // Get new row
        $rsnew = $this->getAddRow();

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }
        return $addRow;
    }

    /**
     * Get add row
     *
     * @return array
     */
    protected function getAddRow()
    {
        global $Security;
        $rsnew = [];

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, false);

        // cliente_rif
        $this->cliente_rif->setDbValueDef($rsnew, $this->cliente_rif->CurrentValue, false);

        // cliente_nombre
        $this->cliente_nombre->setDbValueDef($rsnew, $this->cliente_nombre->CurrentValue, false);

        // tipo_documento_fiscal
        $this->tipo_documento_fiscal->setDbValueDef($rsnew, $this->tipo_documento_fiscal->CurrentValue, false);

        // nro_documento
        $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()), false);

        // monto_documento_bs
        $this->monto_documento_bs->setDbValueDef($rsnew, $this->monto_documento_bs->CurrentValue, strval($this->monto_documento_bs->CurrentValue) == "");

        // total_cobrado_bs
        $this->total_cobrado_bs->setDbValueDef($rsnew, $this->total_cobrado_bs->CurrentValue, strval($this->total_cobrado_bs->CurrentValue) == "");

        // saldo_bs
        $this->saldo_bs->setDbValueDef($rsnew, $this->saldo_bs->CurrentValue, strval($this->saldo_bs->CurrentValue) == "");

        // dias_vencido
        $this->dias_vencido->setDbValueDef($rsnew, $this->dias_vencido->CurrentValue, false);

        // antiguedad
        $this->antiguedad->setDbValueDef($rsnew, $this->antiguedad->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['cliente'])) { // cliente
            $this->cliente->setFormValue($row['cliente']);
        }
        if (isset($row['cliente_rif'])) { // cliente_rif
            $this->cliente_rif->setFormValue($row['cliente_rif']);
        }
        if (isset($row['cliente_nombre'])) { // cliente_nombre
            $this->cliente_nombre->setFormValue($row['cliente_nombre']);
        }
        if (isset($row['tipo_documento_fiscal'])) { // tipo_documento_fiscal
            $this->tipo_documento_fiscal->setFormValue($row['tipo_documento_fiscal']);
        }
        if (isset($row['nro_documento'])) { // nro_documento
            $this->nro_documento->setFormValue($row['nro_documento']);
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->setFormValue($row['fecha']);
        }
        if (isset($row['monto_documento_bs'])) { // monto_documento_bs
            $this->monto_documento_bs->setFormValue($row['monto_documento_bs']);
        }
        if (isset($row['total_cobrado_bs'])) { // total_cobrado_bs
            $this->total_cobrado_bs->setFormValue($row['total_cobrado_bs']);
        }
        if (isset($row['saldo_bs'])) { // saldo_bs
            $this->saldo_bs->setFormValue($row['saldo_bs']);
        }
        if (isset($row['dias_vencido'])) { // dias_vencido
            $this->dias_vencido->setFormValue($row['dias_vencido']);
        }
        if (isset($row['antiguedad'])) { // antiguedad
            $this->antiguedad->setFormValue($row['antiguedad']);
        }
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "view_cuentas_por_cobrar_resumen") {
            $masterTbl = Container("view_cuentas_por_cobrar_resumen");
            $this->cliente->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
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
        echo '
        <style>
            .bg-orange {
                background-color: #fd7e14 !important;
            }
        </style>
        ';
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->moveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }
}
