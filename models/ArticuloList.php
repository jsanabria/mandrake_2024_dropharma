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
class ArticuloList extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ArticuloList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "farticulolist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "ArticuloList";

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

    // Audit Trail
    public $AuditTrailOnAdd = true;
    public $AuditTrailOnEdit = true;
    public $AuditTrailOnDelete = true;
    public $AuditTrailOnView = false;
    public $AuditTrailOnViewData = false;
    public $AuditTrailOnSearch = false;

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
        $this->foto->setVisibility();
        $this->codigo->setVisibility();
        $this->nombre_comercial->setVisibility();
        $this->principio_activo->setVisibility();
        $this->presentacion->setVisibility();
        $this->fabricante->setVisibility();
        $this->codigo_ims->Visible = false;
        $this->codigo_de_barra->Visible = false;
        $this->categoria->setVisibility();
        $this->lista_pedido->setVisibility();
        $this->unidad_medida_defecto->Visible = false;
        $this->cantidad_por_unidad_medida->Visible = false;
        $this->cantidad_minima->Visible = false;
        $this->cantidad_maxima->Visible = false;
        $this->cantidad_en_mano->setVisibility();
        $this->cantidad_en_almacenes->setVisibility();
        $this->cantidad_en_pedido->setVisibility();
        $this->cantidad_en_transito->setVisibility();
        $this->ultimo_costo->Visible = false;
        $this->descuento->setVisibility();
        $this->precio->Visible = false;
        $this->alicuota->Visible = false;
        $this->articulo_inventario->Visible = false;
        $this->activo->setVisibility();
        $this->lote->Visible = false;
        $this->fecha_vencimiento->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'articulo';
        $this->TableName = 'articulo';

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
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || $GLOBALS["articulo"]::class == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "ArticuloAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "ArticuloDelete";
        $this->MultiUpdateUrl = "ArticuloUpdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'articulo');
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

        // Export options
        $this->ExportOptions = new ListOptions(TagClassName: "ew-export-option");

        // Import options
        $this->ImportOptions = new ListOptions(TagClassName: "ew-import-option");

        // Other options
        $this->OtherOptions = new ListOptionsArray();

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions(
            TagClassName: "ew-add-edit-option",
            UseDropDownButton: false,
            DropDownButtonPhrase: $Language->phrase("ButtonAddEdit"),
            UseButtonGroup: true
        );

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(TagClassName: "ew-detail-option");
        // Actions
        $this->OtherOptions["action"] = new ListOptions(TagClassName: "ew-action-option");

        // Column visibility
        $this->OtherOptions["column"] = new ListOptions(
            TableVar: $this->TableVar,
            TagClassName: "ew-column-option",
            ButtonGroupClass: "ew-column-dropdown",
            UseDropDownButton: true,
            DropDownButtonPhrase: $Language->phrase("Columns"),
            DropDownAutoClose: "outside",
            UseButtonGroup: false
        );

        // Filter options
        $this->FilterOptions = new ListOptions(TagClassName: "ew-filter-option");

        // List actions
        $this->ListActions = new ListActions();
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
                    $result["view"] = SameString($pageName, "ArticuloView"); // If View page, no primary button
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
                        if ($fld->DataType == DataType::MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
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
    public $TopContentClass = "ew-top";
    public $MiddleContentClass = "ew-middle";
    public $BottomContentClass = "ew-bottom";
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

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportType = $this->Export; // Get export parameter, used in header
        if ($ExportType != "") {
            global $SkipHeaderFooter;
            $SkipHeaderFooter = true;
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup export options
        $this->setupExportOptions();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->categoria);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->unidad_medida_defecto);
        $this->setupLookupOptions($this->cantidad_por_unidad_medida);
        $this->setupLookupOptions($this->alicuota);
        $this->setupLookupOptions($this->articulo_inventario);
        $this->setupLookupOptions($this->activo);

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "farticulogrid";
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

        // Process list action first
        if ($this->processListAction()) { // Ajax request
            $this->terminate();
            return;
        }

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

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

        // Hide options
        if ($this->isExport() || !(EmptyValue($this->CurrentAction) || $this->isSearch())) {
            $this->ExportOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
            $this->ImportOptions->hideAllOptions();
        }

        // Hide other options
        if ($this->isExport()) {
            $this->OtherOptions->hideAllOptions();
        }

        // Get default search criteria
        AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));
        AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

        // Get basic search values
        $this->loadBasicSearchValues();

        // Get and validate search values for advanced search
        if (EmptyValue($this->UserAction)) { // Skip if user action
            $this->loadSearchValues();
        }

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
        }
        if (!$this->validateSearch()) {
            // Nothing to do
        }

        // Restore search parms from Session if not searching / reset / export
        if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
            $this->restoreSearchParms();
        }

        // Call Recordset SearchValidated event
        $this->recordsetSearchValidated();

        // Set up sorting order
        $this->setupSortOrder();

        // Get basic search criteria
        if (!$this->hasInvalidFields()) {
            $srchBasic = $this->basicSearchWhere();
        }

        // Get advanced search criteria
        if (!$this->hasInvalidFields()) {
            $srchAdvanced = $this->advancedSearchWhere();
        }

        // Get query builder criteria
        $query = $DashboardReport ? "" : $this->queryBuilderWhere();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms() && !$query) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere(); // Save to session
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere(); // Save to session
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Build search criteria
        if ($query) {
            AddFilter($this->SearchWhere, $query);
        } else {
            AddFilter($this->SearchWhere, $srchAdvanced);
            AddFilter($this->SearchWhere, $srchBasic);
        }

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json" && !$query) {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        if (!$Security->canList()) {
            $this->Filter = "(0=1)"; // Filter all records
        }
        AddFilter($this->Filter, $this->DbDetailFilter);
        AddFilter($this->Filter, $this->SearchWhere);

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
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
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
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if ((EmptyValue($this->CurrentAction) || $this->isSearch()) && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }

            // Audit trail on search
            if ($this->AuditTrailOnSearch && $this->Command == "search" && !$this->RestoreSearch) {
                $searchParm = ServerVar("QUERY_STRING");
                $searchSql = $this->getSessionWhere();
                $this->writeAuditTrailOnSearch($searchParm, $searchSql);
            }
        }

        // Set up list action columns
        foreach ($this->ListActions as $listAction) {
            if ($listAction->Allowed) {
                if ($listAction->Select == ACTION_MULTIPLE) { // Show checkbox column if multiple action
                    $this->ListOptions["checkbox"]->Visible = true;
                } elseif ($listAction->Select == ACTION_SINGLE) { // Show list action column
                        $this->ListOptions["listactions"]->Visible = true; // Set visible if any list action is allowed
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            if ($query) { // Hide search panel if using QueryBuilder
                RemoveClass($this->SearchPanelClass, "show");
            } else {
                AppendClass($this->SearchPanelClass, "show");
            }
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

    // Get list of filters
    public function getFilterList()
    {
        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->id->AdvancedSearch->toJson(), ","); // Field id
        $filterList = Concat($filterList, $this->foto->AdvancedSearch->toJson(), ","); // Field foto
        $filterList = Concat($filterList, $this->codigo->AdvancedSearch->toJson(), ","); // Field codigo
        $filterList = Concat($filterList, $this->nombre_comercial->AdvancedSearch->toJson(), ","); // Field nombre_comercial
        $filterList = Concat($filterList, $this->principio_activo->AdvancedSearch->toJson(), ","); // Field principio_activo
        $filterList = Concat($filterList, $this->presentacion->AdvancedSearch->toJson(), ","); // Field presentacion
        $filterList = Concat($filterList, $this->fabricante->AdvancedSearch->toJson(), ","); // Field fabricante
        $filterList = Concat($filterList, $this->codigo_ims->AdvancedSearch->toJson(), ","); // Field codigo_ims
        $filterList = Concat($filterList, $this->codigo_de_barra->AdvancedSearch->toJson(), ","); // Field codigo_de_barra
        $filterList = Concat($filterList, $this->categoria->AdvancedSearch->toJson(), ","); // Field categoria
        $filterList = Concat($filterList, $this->lista_pedido->AdvancedSearch->toJson(), ","); // Field lista_pedido
        $filterList = Concat($filterList, $this->unidad_medida_defecto->AdvancedSearch->toJson(), ","); // Field unidad_medida_defecto
        $filterList = Concat($filterList, $this->cantidad_por_unidad_medida->AdvancedSearch->toJson(), ","); // Field cantidad_por_unidad_medida
        $filterList = Concat($filterList, $this->cantidad_minima->AdvancedSearch->toJson(), ","); // Field cantidad_minima
        $filterList = Concat($filterList, $this->cantidad_maxima->AdvancedSearch->toJson(), ","); // Field cantidad_maxima
        $filterList = Concat($filterList, $this->cantidad_en_mano->AdvancedSearch->toJson(), ","); // Field cantidad_en_mano
        $filterList = Concat($filterList, $this->cantidad_en_almacenes->AdvancedSearch->toJson(), ","); // Field cantidad_en_almacenes
        $filterList = Concat($filterList, $this->cantidad_en_pedido->AdvancedSearch->toJson(), ","); // Field cantidad_en_pedido
        $filterList = Concat($filterList, $this->cantidad_en_transito->AdvancedSearch->toJson(), ","); // Field cantidad_en_transito
        $filterList = Concat($filterList, $this->ultimo_costo->AdvancedSearch->toJson(), ","); // Field ultimo_costo
        $filterList = Concat($filterList, $this->descuento->AdvancedSearch->toJson(), ","); // Field descuento
        $filterList = Concat($filterList, $this->precio->AdvancedSearch->toJson(), ","); // Field precio
        $filterList = Concat($filterList, $this->alicuota->AdvancedSearch->toJson(), ","); // Field alicuota
        $filterList = Concat($filterList, $this->articulo_inventario->AdvancedSearch->toJson(), ","); // Field articulo_inventario
        $filterList = Concat($filterList, $this->activo->AdvancedSearch->toJson(), ","); // Field activo
        $filterList = Concat($filterList, $this->lote->AdvancedSearch->toJson(), ","); // Field lote
        $filterList = Concat($filterList, $this->fecha_vencimiento->AdvancedSearch->toJson(), ","); // Field fecha_vencimiento
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            Profile()->setSearchFilters("farticulosrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field id
        $this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
        $this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
        $this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
        $this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
        $this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
        $this->id->AdvancedSearch->save();

        // Field foto
        $this->foto->AdvancedSearch->SearchValue = @$filter["x_foto"];
        $this->foto->AdvancedSearch->SearchOperator = @$filter["z_foto"];
        $this->foto->AdvancedSearch->SearchCondition = @$filter["v_foto"];
        $this->foto->AdvancedSearch->SearchValue2 = @$filter["y_foto"];
        $this->foto->AdvancedSearch->SearchOperator2 = @$filter["w_foto"];
        $this->foto->AdvancedSearch->save();

        // Field codigo
        $this->codigo->AdvancedSearch->SearchValue = @$filter["x_codigo"];
        $this->codigo->AdvancedSearch->SearchOperator = @$filter["z_codigo"];
        $this->codigo->AdvancedSearch->SearchCondition = @$filter["v_codigo"];
        $this->codigo->AdvancedSearch->SearchValue2 = @$filter["y_codigo"];
        $this->codigo->AdvancedSearch->SearchOperator2 = @$filter["w_codigo"];
        $this->codigo->AdvancedSearch->save();

        // Field nombre_comercial
        $this->nombre_comercial->AdvancedSearch->SearchValue = @$filter["x_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchOperator = @$filter["z_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchCondition = @$filter["v_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchValue2 = @$filter["y_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchOperator2 = @$filter["w_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->save();

        // Field principio_activo
        $this->principio_activo->AdvancedSearch->SearchValue = @$filter["x_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchOperator = @$filter["z_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchCondition = @$filter["v_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchValue2 = @$filter["y_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchOperator2 = @$filter["w_principio_activo"];
        $this->principio_activo->AdvancedSearch->save();

        // Field presentacion
        $this->presentacion->AdvancedSearch->SearchValue = @$filter["x_presentacion"];
        $this->presentacion->AdvancedSearch->SearchOperator = @$filter["z_presentacion"];
        $this->presentacion->AdvancedSearch->SearchCondition = @$filter["v_presentacion"];
        $this->presentacion->AdvancedSearch->SearchValue2 = @$filter["y_presentacion"];
        $this->presentacion->AdvancedSearch->SearchOperator2 = @$filter["w_presentacion"];
        $this->presentacion->AdvancedSearch->save();

        // Field fabricante
        $this->fabricante->AdvancedSearch->SearchValue = @$filter["x_fabricante"];
        $this->fabricante->AdvancedSearch->SearchOperator = @$filter["z_fabricante"];
        $this->fabricante->AdvancedSearch->SearchCondition = @$filter["v_fabricante"];
        $this->fabricante->AdvancedSearch->SearchValue2 = @$filter["y_fabricante"];
        $this->fabricante->AdvancedSearch->SearchOperator2 = @$filter["w_fabricante"];
        $this->fabricante->AdvancedSearch->save();

        // Field codigo_ims
        $this->codigo_ims->AdvancedSearch->SearchValue = @$filter["x_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchOperator = @$filter["z_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchCondition = @$filter["v_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchValue2 = @$filter["y_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->save();

        // Field codigo_de_barra
        $this->codigo_de_barra->AdvancedSearch->SearchValue = @$filter["x_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchOperator = @$filter["z_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchCondition = @$filter["v_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchValue2 = @$filter["y_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->save();

        // Field categoria
        $this->categoria->AdvancedSearch->SearchValue = @$filter["x_categoria"];
        $this->categoria->AdvancedSearch->SearchOperator = @$filter["z_categoria"];
        $this->categoria->AdvancedSearch->SearchCondition = @$filter["v_categoria"];
        $this->categoria->AdvancedSearch->SearchValue2 = @$filter["y_categoria"];
        $this->categoria->AdvancedSearch->SearchOperator2 = @$filter["w_categoria"];
        $this->categoria->AdvancedSearch->save();

        // Field lista_pedido
        $this->lista_pedido->AdvancedSearch->SearchValue = @$filter["x_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator = @$filter["z_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchCondition = @$filter["v_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchValue2 = @$filter["y_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->save();

        // Field unidad_medida_defecto
        $this->unidad_medida_defecto->AdvancedSearch->SearchValue = @$filter["x_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchOperator = @$filter["z_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchCondition = @$filter["v_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchValue2 = @$filter["y_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchOperator2 = @$filter["w_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->save();

        // Field cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue = @$filter["x_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchOperator = @$filter["z_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchCondition = @$filter["v_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->save();

        // Field cantidad_minima
        $this->cantidad_minima->AdvancedSearch->SearchValue = @$filter["x_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchOperator = @$filter["z_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchCondition = @$filter["v_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->save();

        // Field cantidad_maxima
        $this->cantidad_maxima->AdvancedSearch->SearchValue = @$filter["x_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchOperator = @$filter["z_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchCondition = @$filter["v_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->save();

        // Field cantidad_en_mano
        $this->cantidad_en_mano->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->save();

        // Field cantidad_en_almacenes
        $this->cantidad_en_almacenes->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_almacenes"];
        $this->cantidad_en_almacenes->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_almacenes"];
        $this->cantidad_en_almacenes->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_almacenes"];
        $this->cantidad_en_almacenes->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_almacenes"];
        $this->cantidad_en_almacenes->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_almacenes"];
        $this->cantidad_en_almacenes->AdvancedSearch->save();

        // Field cantidad_en_pedido
        $this->cantidad_en_pedido->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->save();

        // Field cantidad_en_transito
        $this->cantidad_en_transito->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->save();

        // Field ultimo_costo
        $this->ultimo_costo->AdvancedSearch->SearchValue = @$filter["x_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchOperator = @$filter["z_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchCondition = @$filter["v_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchValue2 = @$filter["y_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchOperator2 = @$filter["w_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->save();

        // Field descuento
        $this->descuento->AdvancedSearch->SearchValue = @$filter["x_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator = @$filter["z_descuento"];
        $this->descuento->AdvancedSearch->SearchCondition = @$filter["v_descuento"];
        $this->descuento->AdvancedSearch->SearchValue2 = @$filter["y_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator2 = @$filter["w_descuento"];
        $this->descuento->AdvancedSearch->save();

        // Field precio
        $this->precio->AdvancedSearch->SearchValue = @$filter["x_precio"];
        $this->precio->AdvancedSearch->SearchOperator = @$filter["z_precio"];
        $this->precio->AdvancedSearch->SearchCondition = @$filter["v_precio"];
        $this->precio->AdvancedSearch->SearchValue2 = @$filter["y_precio"];
        $this->precio->AdvancedSearch->SearchOperator2 = @$filter["w_precio"];
        $this->precio->AdvancedSearch->save();

        // Field alicuota
        $this->alicuota->AdvancedSearch->SearchValue = @$filter["x_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator = @$filter["z_alicuota"];
        $this->alicuota->AdvancedSearch->SearchCondition = @$filter["v_alicuota"];
        $this->alicuota->AdvancedSearch->SearchValue2 = @$filter["y_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator2 = @$filter["w_alicuota"];
        $this->alicuota->AdvancedSearch->save();

        // Field articulo_inventario
        $this->articulo_inventario->AdvancedSearch->SearchValue = @$filter["x_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchOperator = @$filter["z_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchCondition = @$filter["v_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchValue2 = @$filter["y_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchOperator2 = @$filter["w_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->save();

        // Field activo
        $this->activo->AdvancedSearch->SearchValue = @$filter["x_activo"];
        $this->activo->AdvancedSearch->SearchOperator = @$filter["z_activo"];
        $this->activo->AdvancedSearch->SearchCondition = @$filter["v_activo"];
        $this->activo->AdvancedSearch->SearchValue2 = @$filter["y_activo"];
        $this->activo->AdvancedSearch->SearchOperator2 = @$filter["w_activo"];
        $this->activo->AdvancedSearch->save();

        // Field lote
        $this->lote->AdvancedSearch->SearchValue = @$filter["x_lote"];
        $this->lote->AdvancedSearch->SearchOperator = @$filter["z_lote"];
        $this->lote->AdvancedSearch->SearchCondition = @$filter["v_lote"];
        $this->lote->AdvancedSearch->SearchValue2 = @$filter["y_lote"];
        $this->lote->AdvancedSearch->SearchOperator2 = @$filter["w_lote"];
        $this->lote->AdvancedSearch->save();

        // Field fecha_vencimiento
        $this->fecha_vencimiento->AdvancedSearch->SearchValue = @$filter["x_fecha_vencimiento"];
        $this->fecha_vencimiento->AdvancedSearch->SearchOperator = @$filter["z_fecha_vencimiento"];
        $this->fecha_vencimiento->AdvancedSearch->SearchCondition = @$filter["v_fecha_vencimiento"];
        $this->fecha_vencimiento->AdvancedSearch->SearchValue2 = @$filter["y_fecha_vencimiento"];
        $this->fecha_vencimiento->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_vencimiento"];
        $this->fecha_vencimiento->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    public function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $this->buildSearchSql($where, $this->id, $default, false); // id
        $this->buildSearchSql($where, $this->foto, $default, false); // foto
        $this->buildSearchSql($where, $this->codigo, $default, false); // codigo
        $this->buildSearchSql($where, $this->nombre_comercial, $default, false); // nombre_comercial
        $this->buildSearchSql($where, $this->principio_activo, $default, false); // principio_activo
        $this->buildSearchSql($where, $this->presentacion, $default, false); // presentacion
        $this->buildSearchSql($where, $this->fabricante, $default, false); // fabricante
        $this->buildSearchSql($where, $this->codigo_ims, $default, false); // codigo_ims
        $this->buildSearchSql($where, $this->codigo_de_barra, $default, false); // codigo_de_barra
        $this->buildSearchSql($where, $this->categoria, $default, false); // categoria
        $this->buildSearchSql($where, $this->lista_pedido, $default, false); // lista_pedido
        $this->buildSearchSql($where, $this->unidad_medida_defecto, $default, false); // unidad_medida_defecto
        $this->buildSearchSql($where, $this->cantidad_por_unidad_medida, $default, false); // cantidad_por_unidad_medida
        $this->buildSearchSql($where, $this->cantidad_minima, $default, false); // cantidad_minima
        $this->buildSearchSql($where, $this->cantidad_maxima, $default, false); // cantidad_maxima
        $this->buildSearchSql($where, $this->cantidad_en_mano, $default, false); // cantidad_en_mano
        $this->buildSearchSql($where, $this->cantidad_en_almacenes, $default, false); // cantidad_en_almacenes
        $this->buildSearchSql($where, $this->cantidad_en_pedido, $default, false); // cantidad_en_pedido
        $this->buildSearchSql($where, $this->cantidad_en_transito, $default, false); // cantidad_en_transito
        $this->buildSearchSql($where, $this->ultimo_costo, $default, false); // ultimo_costo
        $this->buildSearchSql($where, $this->descuento, $default, false); // descuento
        $this->buildSearchSql($where, $this->precio, $default, false); // precio
        $this->buildSearchSql($where, $this->alicuota, $default, false); // alicuota
        $this->buildSearchSql($where, $this->articulo_inventario, $default, false); // articulo_inventario
        $this->buildSearchSql($where, $this->activo, $default, false); // activo
        $this->buildSearchSql($where, $this->lote, $default, false); // lote
        $this->buildSearchSql($where, $this->fecha_vencimiento, $default, false); // fecha_vencimiento

        // Set up search command
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->foto->AdvancedSearch->save(); // foto
            $this->codigo->AdvancedSearch->save(); // codigo
            $this->nombre_comercial->AdvancedSearch->save(); // nombre_comercial
            $this->principio_activo->AdvancedSearch->save(); // principio_activo
            $this->presentacion->AdvancedSearch->save(); // presentacion
            $this->fabricante->AdvancedSearch->save(); // fabricante
            $this->codigo_ims->AdvancedSearch->save(); // codigo_ims
            $this->codigo_de_barra->AdvancedSearch->save(); // codigo_de_barra
            $this->categoria->AdvancedSearch->save(); // categoria
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->unidad_medida_defecto->AdvancedSearch->save(); // unidad_medida_defecto
            $this->cantidad_por_unidad_medida->AdvancedSearch->save(); // cantidad_por_unidad_medida
            $this->cantidad_minima->AdvancedSearch->save(); // cantidad_minima
            $this->cantidad_maxima->AdvancedSearch->save(); // cantidad_maxima
            $this->cantidad_en_mano->AdvancedSearch->save(); // cantidad_en_mano
            $this->cantidad_en_almacenes->AdvancedSearch->save(); // cantidad_en_almacenes
            $this->cantidad_en_pedido->AdvancedSearch->save(); // cantidad_en_pedido
            $this->cantidad_en_transito->AdvancedSearch->save(); // cantidad_en_transito
            $this->ultimo_costo->AdvancedSearch->save(); // ultimo_costo
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->precio->AdvancedSearch->save(); // precio
            $this->alicuota->AdvancedSearch->save(); // alicuota
            $this->articulo_inventario->AdvancedSearch->save(); // articulo_inventario
            $this->activo->AdvancedSearch->save(); // activo
            $this->lote->AdvancedSearch->save(); // lote
            $this->fecha_vencimiento->AdvancedSearch->save(); // fecha_vencimiento

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $where;
    }

    // Query builder rules
    public function queryBuilderRules()
    {
        return Post("rules") ?? $this->getSessionRules();
    }

    // Quey builder WHERE clause
    public function queryBuilderWhere($fieldName = "")
    {
        global $Security;
        if (!$Security->canSearch()) {
            return "";
        }

        // Get rules by query builder
        $rules = $this->queryBuilderRules();

        // Decode and parse rules
        $where = $rules ? $this->parseRules(json_decode($rules, true), $fieldName) : "";

        // Clear other search and save rules to session
        if ($where && $fieldName == "") { // Skip if get query for specific field
            $this->resetSearchParms();
            $this->id->AdvancedSearch->save(); // id
            $this->foto->AdvancedSearch->save(); // foto
            $this->codigo->AdvancedSearch->save(); // codigo
            $this->nombre_comercial->AdvancedSearch->save(); // nombre_comercial
            $this->principio_activo->AdvancedSearch->save(); // principio_activo
            $this->presentacion->AdvancedSearch->save(); // presentacion
            $this->fabricante->AdvancedSearch->save(); // fabricante
            $this->codigo_ims->AdvancedSearch->save(); // codigo_ims
            $this->codigo_de_barra->AdvancedSearch->save(); // codigo_de_barra
            $this->categoria->AdvancedSearch->save(); // categoria
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->unidad_medida_defecto->AdvancedSearch->save(); // unidad_medida_defecto
            $this->cantidad_por_unidad_medida->AdvancedSearch->save(); // cantidad_por_unidad_medida
            $this->cantidad_minima->AdvancedSearch->save(); // cantidad_minima
            $this->cantidad_maxima->AdvancedSearch->save(); // cantidad_maxima
            $this->cantidad_en_mano->AdvancedSearch->save(); // cantidad_en_mano
            $this->cantidad_en_almacenes->AdvancedSearch->save(); // cantidad_en_almacenes
            $this->cantidad_en_pedido->AdvancedSearch->save(); // cantidad_en_pedido
            $this->cantidad_en_transito->AdvancedSearch->save(); // cantidad_en_transito
            $this->ultimo_costo->AdvancedSearch->save(); // ultimo_costo
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->precio->AdvancedSearch->save(); // precio
            $this->alicuota->AdvancedSearch->save(); // alicuota
            $this->articulo_inventario->AdvancedSearch->save(); // articulo_inventario
            $this->activo->AdvancedSearch->save(); // activo
            $this->lote->AdvancedSearch->save(); // lote
            $this->fecha_vencimiento->AdvancedSearch->save(); // fecha_vencimiento
            $this->setSessionRules($rules);
        }

        // Return query
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, $fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = $default ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = $default ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $fldVal = ConvertSearchValue($fldVal, $fldOpr, $fld);
        $fldVal2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld);
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $fldVal);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $fldVal2);
        $wrk = "";
        $sep = $fld->UseFilter ? Config("FILTER_OPTION_SEPARATOR") : Config("MULTIPLE_OPTION_SEPARATOR");
        if (is_array($fldVal)) {
            $fldVal = implode($sep, $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode($sep, $fldVal2);
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 && !$fld->UseFilter || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = $fldVal2 != "" ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            AddFilter($wrk, $wrk2, $fldCond);
        } else {
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        if ($this->SearchOption == "AUTO" && in_array($this->BasicSearch->getType(), ["AND", "OR"])) {
            $cond = $this->BasicSearch->getType();
        } else {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
        }
        AddFilter($where, $wrk, $cond);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field foto
        $filter = $this->queryBuilderWhere("foto");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->foto, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->foto->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field codigo
        $filter = $this->queryBuilderWhere("codigo");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->codigo, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->codigo->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field nombre_comercial
        $filter = $this->queryBuilderWhere("nombre_comercial");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->nombre_comercial, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->nombre_comercial->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field principio_activo
        $filter = $this->queryBuilderWhere("principio_activo");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->principio_activo, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->principio_activo->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field presentacion
        $filter = $this->queryBuilderWhere("presentacion");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->presentacion, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->presentacion->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field fabricante
        $filter = $this->queryBuilderWhere("fabricante");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->fabricante, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->fabricante->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field categoria
        $filter = $this->queryBuilderWhere("categoria");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->categoria, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->categoria->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field lista_pedido
        $filter = $this->queryBuilderWhere("lista_pedido");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->lista_pedido, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->lista_pedido->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field cantidad_en_mano
        $filter = $this->queryBuilderWhere("cantidad_en_mano");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->cantidad_en_mano, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->cantidad_en_mano->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field cantidad_en_almacenes
        $filter = $this->queryBuilderWhere("cantidad_en_almacenes");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->cantidad_en_almacenes, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->cantidad_en_almacenes->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field cantidad_en_pedido
        $filter = $this->queryBuilderWhere("cantidad_en_pedido");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->cantidad_en_pedido, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->cantidad_en_pedido->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field cantidad_en_transito
        $filter = $this->queryBuilderWhere("cantidad_en_transito");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->cantidad_en_transito, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->cantidad_en_transito->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field descuento
        $filter = $this->queryBuilderWhere("descuento");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->descuento, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->descuento->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field activo
        $filter = $this->queryBuilderWhere("activo");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->activo, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->activo->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }
        if ($this->BasicSearch->Keyword != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $Language->phrase("BasicSearchKeyword") . "</span>" . $captionSuffix . $this->BasicSearch->Keyword . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"callout callout-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $this->messageShowing($message, "");
            Write($message);
        } else { // Output empty tag
            Write("<div id=\"ew-filter-list\"></div>");
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    public function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }

        // Fields to search
        $searchFlds = [];
        $searchFlds[] = &$this->foto;
        $searchFlds[] = &$this->codigo;
        $searchFlds[] = &$this->nombre_comercial;
        $searchFlds[] = &$this->principio_activo;
        $searchFlds[] = &$this->presentacion;
        $searchFlds[] = &$this->codigo_ims;
        $searchFlds[] = &$this->codigo_de_barra;
        $searchFlds[] = &$this->categoria;
        $searchFlds[] = &$this->lista_pedido;
        $searchFlds[] = &$this->unidad_medida_defecto;
        $searchFlds[] = &$this->alicuota;
        $searchFlds[] = &$this->lote;
        $searchKeyword = $default ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = $default ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            $searchStr = GetQuickSearchFilter($searchFlds, $ar, $searchType, Config("BASIC_SEARCH_ANY_FIELDS"), $this->Dbid);
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        if ($this->id->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->foto->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->codigo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nombre_comercial->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->principio_activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->presentacion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fabricante->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->codigo_ims->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->codigo_de_barra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->categoria->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->lista_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->unidad_medida_defecto->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_por_unidad_medida->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_minima->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_maxima->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_mano->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_almacenes->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_transito->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ultimo_costo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->precio->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->alicuota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->articulo_inventario->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->lote->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_vencimiento->AdvancedSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();

        // Clear queryBuilder
        $this->setSessionRules("");
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
        $this->id->AdvancedSearch->unsetSession();
        $this->foto->AdvancedSearch->unsetSession();
        $this->codigo->AdvancedSearch->unsetSession();
        $this->nombre_comercial->AdvancedSearch->unsetSession();
        $this->principio_activo->AdvancedSearch->unsetSession();
        $this->presentacion->AdvancedSearch->unsetSession();
        $this->fabricante->AdvancedSearch->unsetSession();
        $this->codigo_ims->AdvancedSearch->unsetSession();
        $this->codigo_de_barra->AdvancedSearch->unsetSession();
        $this->categoria->AdvancedSearch->unsetSession();
        $this->lista_pedido->AdvancedSearch->unsetSession();
        $this->unidad_medida_defecto->AdvancedSearch->unsetSession();
        $this->cantidad_por_unidad_medida->AdvancedSearch->unsetSession();
        $this->cantidad_minima->AdvancedSearch->unsetSession();
        $this->cantidad_maxima->AdvancedSearch->unsetSession();
        $this->cantidad_en_mano->AdvancedSearch->unsetSession();
        $this->cantidad_en_almacenes->AdvancedSearch->unsetSession();
        $this->cantidad_en_pedido->AdvancedSearch->unsetSession();
        $this->cantidad_en_transito->AdvancedSearch->unsetSession();
        $this->ultimo_costo->AdvancedSearch->unsetSession();
        $this->descuento->AdvancedSearch->unsetSession();
        $this->precio->AdvancedSearch->unsetSession();
        $this->alicuota->AdvancedSearch->unsetSession();
        $this->articulo_inventario->AdvancedSearch->unsetSession();
        $this->activo->AdvancedSearch->unsetSession();
        $this->lote->AdvancedSearch->unsetSession();
        $this->fecha_vencimiento->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->id->AdvancedSearch->load();
        $this->foto->AdvancedSearch->load();
        $this->codigo->AdvancedSearch->load();
        $this->nombre_comercial->AdvancedSearch->load();
        $this->principio_activo->AdvancedSearch->load();
        $this->presentacion->AdvancedSearch->load();
        $this->fabricante->AdvancedSearch->load();
        $this->codigo_ims->AdvancedSearch->load();
        $this->codigo_de_barra->AdvancedSearch->load();
        $this->categoria->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->unidad_medida_defecto->AdvancedSearch->load();
        $this->cantidad_por_unidad_medida->AdvancedSearch->load();
        $this->cantidad_minima->AdvancedSearch->load();
        $this->cantidad_maxima->AdvancedSearch->load();
        $this->cantidad_en_mano->AdvancedSearch->load();
        $this->cantidad_en_almacenes->AdvancedSearch->load();
        $this->cantidad_en_pedido->AdvancedSearch->load();
        $this->cantidad_en_transito->AdvancedSearch->load();
        $this->ultimo_costo->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->precio->AdvancedSearch->load();
        $this->alicuota->AdvancedSearch->load();
        $this->articulo_inventario->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->lote->AdvancedSearch->load();
        $this->fecha_vencimiento->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = $this->principio_activo->Expression . " ASC" . ", " . $this->nombre_comercial->Expression . " ASC"; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->foto); // foto
            $this->updateSort($this->codigo); // codigo
            $this->updateSort($this->nombre_comercial); // nombre_comercial
            $this->updateSort($this->principio_activo); // principio_activo
            $this->updateSort($this->presentacion); // presentacion
            $this->updateSort($this->fabricante); // fabricante
            $this->updateSort($this->categoria); // categoria
            $this->updateSort($this->lista_pedido); // lista_pedido
            $this->updateSort($this->cantidad_en_mano); // cantidad_en_mano
            $this->updateSort($this->cantidad_en_almacenes); // cantidad_en_almacenes
            $this->updateSort($this->cantidad_en_pedido); // cantidad_en_pedido
            $this->updateSort($this->cantidad_en_transito); // cantidad_en_transito
            $this->updateSort($this->descuento); // descuento
            $this->updateSort($this->activo); // activo
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
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->id->setSort("");
                $this->foto->setSort("");
                $this->codigo->setSort("");
                $this->nombre_comercial->setSort("");
                $this->principio_activo->setSort("");
                $this->presentacion->setSort("");
                $this->fabricante->setSort("");
                $this->codigo_ims->setSort("");
                $this->codigo_de_barra->setSort("");
                $this->categoria->setSort("");
                $this->lista_pedido->setSort("");
                $this->unidad_medida_defecto->setSort("");
                $this->cantidad_por_unidad_medida->setSort("");
                $this->cantidad_minima->setSort("");
                $this->cantidad_maxima->setSort("");
                $this->cantidad_en_mano->setSort("");
                $this->cantidad_en_almacenes->setSort("");
                $this->cantidad_en_pedido->setSort("");
                $this->cantidad_en_transito->setSort("");
                $this->ultimo_costo->setSort("");
                $this->descuento->setSort("");
                $this->precio->setSort("");
                $this->alicuota->setSort("");
                $this->articulo_inventario->setSort("");
                $this->activo->setSort("");
                $this->lote->setSort("");
                $this->fecha_vencimiento->setSort("");
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

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = true;

        // "detail_articulo_unidad_medida"
        $item = &$this->ListOptions->add("detail_articulo_unidad_medida");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'articulo_unidad_medida');
        $item->OnLeft = true;
        $item->ShowInButtonGroup = false;

        // "detail_adjunto"
        $item = &$this->ListOptions->add("detail_adjunto");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'adjunto');
        $item->OnLeft = true;
        $item->ShowInButtonGroup = false;

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$this->ListOptions->add("details");
            $item->CssClass = "text-nowrap";
            $item->Visible = $this->ShowMultipleDetails && $this->ListOptions->detailVisible();
            $item->OnLeft = true;
            $item->ShowInButtonGroup = false;
            $this->ListOptions->hideDetailItems();
        }

        // Set up detail pages
        $pages = new SubPages();
        $pages->add("articulo_unidad_medida");
        $pages->add("adjunto");
        $this->DetailPages = $pages;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = true;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = false;
        $item->OnLeft = true;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

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
        $this->setupListOptionsExt();
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
        $pageUrl = $this->pageUrl(false);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"articulo\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                if ($this->ModalEdit && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"articulo\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
                $deleteCaption = $Language->phrase("DeleteLink");
                $deleteTitle = HtmlTitle($deleteCaption);
                if ($this->UseAjaxActions) {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"inline\" data-action=\"delete\" title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" data-key= \"" . HtmlEncode($this->getKey(true)) . "\" data-url=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\"" .
                        ($this->InlineDelete ? " data-ew-action=\"inline-delete\"" : "") .
                        " title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                }
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions as $listAction) {
                $action = $listAction->Action;
                $allowed = $listAction->Allowed;
                $disabled = false;
                if ($listAction->Select == ACTION_SINGLE && $allowed) {
                    $caption = $listAction->Caption;
                    $title = HtmlTitle($caption);
                    if ($action != "") {
                        $icon = ($listAction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listAction->Icon)) . "\" data-caption=\"" . $title . "\"></i> " : "";
                        $link = $disabled
                            ? "<li><div class=\"alert alert-light\">" . $icon . " " . $caption . "</div></li>"
                            : "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . $title . "\" data-ew-action=\"submit\" form=\"farticulolist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listAction->toDataAttributes() . ">" . $icon . " " . $caption . "</button></li>";
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = $disabled
                            ? "<div class=\"alert alert-light\">" . $icon . " " . $caption . "</div>"
                            : "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . $title . "\" data-caption=\"" . $title . "\" data-ew-action=\"submit\" form=\"farticulolist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listAction->toDataAttributes() . ">" . $icon . " " . $caption . "</button>";
                        }
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = implode(array_map(fn($link) => "<li>" . $link . "</li>", $links));
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
            }
        }
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_articulo_unidad_medida"
        $opt = $this->ListOptions["detail_articulo_unidad_medida"];
        if ($Security->allowList(CurrentProjectID() . 'articulo_unidad_medida')) {
            $body = $Language->phrase("DetailLink") . $Language->tablePhrase("articulo_unidad_medida", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("ArticuloUnidadMedidaList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("ArticuloUnidadMedidaGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "articulo_unidad_medida";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "articulo_unidad_medida";
            }
            if ($links != "") {
                $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
                $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
            } else {
                $body = preg_replace('/\b\s+dropdown-toggle\b/', "", $body);
            }
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }

        // "detail_adjunto"
        $opt = $this->ListOptions["detail_adjunto"];
        if ($Security->allowList(CurrentProjectID() . 'adjunto')) {
            $body = $Language->phrase("DetailLink") . $Language->tablePhrase("adjunto", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("AdjuntoList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("AdjuntoGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "adjunto";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "adjunto";
            }
            if ($links != "") {
                $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
                $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
            } else {
                $body = preg_replace('/\b\s+dropdown-toggle\b/', "", $body);
            }
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailViewLink", true)) . "\" href=\"" . HtmlEncode($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar)) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailEditLink", true)) . "\" href=\"" . HtmlEncode($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar)) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailCopyLink", true)) . "\" href=\"" . HtmlEncode($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar)) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlEncode($Language->phrase("MultipleMasterDetails", true)) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-dropdown-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $opt = $this->ListOptions["details"];
            $opt->Body = $body;
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->id->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;

        // Preview extension
        $links = "";
        $detailFilters = [];
        $masterKeys = []; // Reset
        $masterKeys["id"] = strval($this->id->DbValue);

        // Column "detail_articulo_unidad_medida"
        if ($this->DetailPages?->getItem("articulo_unidad_medida")?->Visible && $Security->allowList(CurrentProjectID() . 'articulo_unidad_medida')) {
            $link = "";
            $option = $this->ListOptions["detail_articulo_unidad_medida"];
            $detailTbl = Container("articulo_unidad_medida");
            $detailFilter = $detailTbl->getDetailFilter($this);
            $detailTbl->setCurrentMasterTable($this->TableVar);
            $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
            $url = "ArticuloUnidadMedidaPreview?t=articulo&f=" . Encrypt($detailFilter . "|" . implode("|", array_values($masterKeys)));
            $btngrp = "<div data-table=\"articulo_unidad_medida\" data-url=\"" . $url . "\" class=\"ew-detail-btn-group btn-group btn-group-sm d-none\">";
            if ($Security->allowList(CurrentProjectID() . 'articulo')) {
                $label = $Language->tablePhrase("articulo_unidad_medida", "TblCaption");
                $link = "<button class=\"nav-link\" data-bs-toggle=\"tab\" data-table=\"articulo_unidad_medida\" data-url=\"" . $url . "\" type=\"button\" role=\"tab\" aria-selected=\"false\">" . $label . "</button>";
                $detaillnk = GetUrl("ArticuloUnidadMedidaList?" . Config("TABLE_SHOW_MASTER") . "=articulo");
                foreach ($masterKeys as $key => $value) {
                    $detaillnk .= "&" . GetForeignKeyUrl("fk_$key", $value);
                }
                $title = $Language->tablePhrase("articulo_unidad_medida", "TblCaption");
                $caption = $Language->phrase("MasterDetailListLink");
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . $title . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($detaillnk) . "\">" . $caption . "</button>";
            }
            $detailPageObj = Container("ArticuloUnidadMedidaGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $viewurl = GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida"));
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . HtmlTitle($caption) . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($viewurl) . "\">" . $caption . "</button>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $editurl = GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida"));
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . HtmlTitle($caption) . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($editurl) . "\">" . $caption . "</button>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $link = "<li class=\"nav-item\">" . $btngrp . $link . "</li>";  // Note: Place $btngrp before $link
                $links .= $link;
                $option->Body .= "<div class=\"ew-preview d-none\">" . $link . "</div>";
            }
        }
        $masterKeys = []; // Reset
        $masterKeys["id"] = strval($this->id->DbValue);

        // Column "detail_adjunto"
        if ($this->DetailPages?->getItem("adjunto")?->Visible && $Security->allowList(CurrentProjectID() . 'adjunto')) {
            $link = "";
            $option = $this->ListOptions["detail_adjunto"];
            $detailTbl = Container("adjunto");
            $detailFilter = $detailTbl->getDetailFilter($this);
            $detailTbl->setCurrentMasterTable($this->TableVar);
            $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
            $url = "AdjuntoPreview?t=articulo&f=" . Encrypt($detailFilter . "|" . implode("|", array_values($masterKeys)));
            $btngrp = "<div data-table=\"adjunto\" data-url=\"" . $url . "\" class=\"ew-detail-btn-group btn-group btn-group-sm d-none\">";
            if ($Security->allowList(CurrentProjectID() . 'articulo')) {
                $label = $Language->tablePhrase("adjunto", "TblCaption");
                $link = "<button class=\"nav-link\" data-bs-toggle=\"tab\" data-table=\"adjunto\" data-url=\"" . $url . "\" type=\"button\" role=\"tab\" aria-selected=\"false\">" . $label . "</button>";
                $detaillnk = GetUrl("AdjuntoList?" . Config("TABLE_SHOW_MASTER") . "=articulo");
                foreach ($masterKeys as $key => $value) {
                    $detaillnk .= "&" . GetForeignKeyUrl("fk_$key", $value);
                }
                $title = $Language->tablePhrase("adjunto", "TblCaption");
                $caption = $Language->phrase("MasterDetailListLink");
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . $title . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($detaillnk) . "\">" . $caption . "</button>";
            }
            $detailPageObj = Container("AdjuntoGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $viewurl = GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto"));
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . HtmlTitle($caption) . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($viewurl) . "\">" . $caption . "</button>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $editurl = GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto"));
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . HtmlTitle($caption) . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($editurl) . "\">" . $caption . "</button>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $link = "<li class=\"nav-item\">" . $btngrp . $link . "</li>";  // Note: Place $btngrp before $link
                $links .= $link;
                $option->Body .= "<div class=\"ew-preview d-none\">" . $link . "</div>";
            }
        }

        // Add row attributes for expandable row
        if ($this->RowType == RowType::VIEW) {
            $this->RowAttrs["data-widget"] = "expandable-table";
            $this->RowAttrs["aria-expanded"] = "false";
        }

        // Column "preview"
        $option = $this->ListOptions["preview"];
        if (!$option) { // Add preview column
            $option = &$this->ListOptions->add("preview");
            $option->OnLeft = true;
            $checkboxPos = $this->ListOptions->itemPos("checkbox");
            $pos = $checkboxPos === false
                ? ($option->OnLeft ? 0 : -1)
                : ($option->OnLeft ? $checkboxPos + 1 : $checkboxPos);
            $option->moveTo($pos);
            $option->Visible = !($this->isExport() || $this->isGridAdd() || $this->isGridEdit() || $this->isMultiEdit());
            $option->ShowInDropDown = false;
            $option->ShowInButtonGroup = false;
        }
        if ($option) {
            $icon = "fa-solid fa-caret-right fa-fw"; // Right
            if (property_exists($this, "MultiColumnLayout") && $this->MultiColumnLayout == "table") {
                $option->CssStyle = "width: 1%;";
                if (!$option->OnLeft) {
                    $icon = preg_replace('/\\bright\\b/', "left", $icon);
                }
            }
            if (IsRTL()) { // Reverse
                if (preg_match('/\\bleft\\b/', $icon)) {
                    $icon = preg_replace('/\\bleft\\b/', "right", $icon);
                } elseif (preg_match('/\\bright\\b/', $icon)) {
                    $icon = preg_replace('/\\bright\\b/', "left", $icon);
                }
            }
            $option->Body = "<i role=\"button\" class=\"ew-preview-btn expandable-table-caret ew-icon " . $icon . "\"></i>" .
                "<div class=\"ew-preview d-none\">" . $links . "</div>";
            if ($option->Visible) {
                $option->Visible = $links != "";
            }
        }

        // Column "details" (Multiple details)
        $option = $this->ListOptions["details"];
        if ($option) {
            $option->Body .= "<div class=\"ew-preview d-none\">" . $links . "</div>";
            if ($option->Visible) {
                $option->Visible = $links != "";
            }
        }
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        if ($this->ModalAdd && !IsMobile()) {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"articulo\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["detail"];
        $detailTableLink = "";
        $item = &$option->add("detailadd_articulo_unidad_medida");
        $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
        $detailPage = Container("ArticuloUnidadMedidaGrid");
        $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
        $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
        $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'articulo') && $Security->canAdd());
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "articulo_unidad_medida";
        }
        $item = &$option->add("detailadd_adjunto");
        $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
        $detailPage = Container("AdjuntoGrid");
        $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
        $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
        $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'articulo') && $Security->canAdd());
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "adjunto";
        }

        // Add multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$option->add("detailsadd");
            $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailTableLink);
            $caption = $Language->phrase("AddMasterDetailLink");
            $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
            $item->Visible = $detailTableLink != "" && $Security->canAdd();
            // Hide single master/detail items
            $ar = explode(",", $detailTableLink);
            $cnt = count($ar);
            for ($i = 0; $i < $cnt; $i++) {
                if ($item = $option["detailadd_" . $ar[$i]]) {
                    $item->Visible = false;
                }
            }
        }
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $this->createColumnOption($option, "foto");
            $this->createColumnOption($option, "codigo");
            $this->createColumnOption($option, "nombre_comercial");
            $this->createColumnOption($option, "principio_activo");
            $this->createColumnOption($option, "presentacion");
            $this->createColumnOption($option, "fabricante");
            $this->createColumnOption($option, "categoria");
            $this->createColumnOption($option, "lista_pedido");
            $this->createColumnOption($option, "cantidad_en_mano");
            $this->createColumnOption($option, "cantidad_en_almacenes");
            $this->createColumnOption($option, "cantidad_en_pedido");
            $this->createColumnOption($option, "cantidad_en_transito");
            $this->createColumnOption($option, "descuento");
            $this->createColumnOption($option, "activo");
        }

        // Set up custom actions
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions[$name] = $action;
        }

        // Set up options default
        foreach ($options as $name => $option) {
            if ($name != "column") { // Always use dropdown for column
                $option->UseDropDownButton = false;
                $option->UseButtonGroup = true;
            }
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"farticulosrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"farticulosrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Page header/footer options
        $this->HeaderOptions = new ListOptions(TagClassName: "ew-header-option", UseDropDownButton: false, UseButtonGroup: false);
        $item = &$this->HeaderOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
        $this->FooterOptions = new ListOptions(TagClassName: "ew-footer-option", UseDropDownButton: false, UseButtonGroup: false);
        $item = &$this->FooterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Show active user count from SQL
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
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions as $listAction) {
            if ($listAction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listAction->Action);
                $caption = $listAction->Caption;
                $icon = ($listAction->Icon != "") ? '<i class="' . HtmlEncode($listAction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="farticulolist"' . $listAction->toDataAttributes() . '>' . $icon . '</button>';
                $item->Visible = $listAction->Allowed;
            }
        }

        // Hide multi edit, grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security, $Response;
        $users = [];
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("action", "");
        if ($filter != "" && $userAction != "") {
            $conn = $this->getConnection();
            // Clear current action
            $this->CurrentAction = "";
            // Check permission first
            $actionCaption = $userAction;
            $listAction = $this->ListActions[$userAction] ?? null;
            if ($listAction) {
                $this->UserAction = $userAction;
                $actionCaption = $listAction->Caption ?: $listAction->Action;
                if (!$listAction->Allowed) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            } else {
                // Skip checking, handle by Row_CustomAction
            }
            $rows = $this->loadRs($filter)->fetchAllAssociative();
            $this->SelectedCount = count($rows);
            $this->ActionValue = Post("actionvalue");

            // Call row action event
            if ($this->SelectedCount > 0) {
                if ($this->UseTransaction) {
                    $conn->beginTransaction();
                }
                $this->SelectedIndex = 0;
                foreach ($rows as $row) {
                    $this->SelectedIndex++;
                    if ($listAction) {
                        $processed = $listAction->handle($row, $this);
                        if (!$processed) {
                            break;
                        }
                    }
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                }
                if ($processed) {
                    if ($this->UseTransaction) { // Commit transaction
                        if ($conn->isTransactionActive()) {
                            $conn->commit();
                        }
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($listAction->SuccessMessage);
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage(str_replace("%s", $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        if ($conn->isTransactionActive()) {
                            $conn->rollback();
                        }
                    }
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($listAction->FailureMessage);
                    }

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if (Post("ajax") == $userAction) { // Ajax
                if (WithJsonResponse()) { // List action returns JSON
                    $this->clearSuccessMessage(); // Clear success message
                    $this->clearFailureMessage(); // Clear failure message
                } else {
                    if ($this->getSuccessMessage() != "") {
                        echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                        $this->clearSuccessMessage(); // Clear success message
                    }
                    if ($this->getFailureMessage() != "") {
                        echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                        $this->clearFailureMessage(); // Clear failure message
                    }
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        if ($this->ExportAll && $this->isExport()) {
            $this->StopRecord = $this->TotalRecords;
        } else {
            // Set the last record to display
            if ($this->TotalRecords > $this->StartRecord + $this->DisplayRecords - 1) {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            } else {
                $this->StopRecord = $this->TotalRecords;
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_articulo", "data-rowtype" => RowType::ADD]);
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

        // Set up key count
        $this->KeyCount = $this->RowIndex;

        // Init row class and style
        $this->resetAttributes();
        $this->CssClass = "";
        if ($this->isCopy() && $this->InlineRowCount == 0 && !$this->loadRow()) { // Inline copy
            $this->CurrentAction = "add";
        }
        if ($this->isAdd() && $this->InlineRowCount == 0 || $this->isGridAdd()) {
            $this->loadRowValues(); // Load default values
            $this->OldKey = "";
            $this->setKey($this->OldKey);
        } elseif ($this->isInlineInserted() && $this->UseInfiniteScroll) {
            // Nothing to do, just use current values
        } elseif (!($this->isCopy() && $this->InlineRowCount == 0)) {
            $this->loadRowValues($this->CurrentRow); // Load row values
            if ($this->isGridEdit() || $this->isMultiEdit()) {
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey);
            }
        }
        $this->RowType = RowType::VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = RowType::ADD; // Render add
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
            "id" => "r" . $this->RowCount . "_articulo",
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

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // Load query builder rules
        $rules = Post("rules");
        if ($rules && $this->Command == "") {
            $this->QueryRules = $rules;
            $this->Command = "search";
        }

        // id
        if ($this->id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id->AdvancedSearch->SearchValue != "" || $this->id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // foto
        if ($this->foto->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->foto->AdvancedSearch->SearchValue != "" || $this->foto->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // codigo
        if ($this->codigo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo->AdvancedSearch->SearchValue != "" || $this->codigo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nombre_comercial
        if ($this->nombre_comercial->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nombre_comercial->AdvancedSearch->SearchValue != "" || $this->nombre_comercial->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // principio_activo
        if ($this->principio_activo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->principio_activo->AdvancedSearch->SearchValue != "" || $this->principio_activo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // presentacion
        if ($this->presentacion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->presentacion->AdvancedSearch->SearchValue != "" || $this->presentacion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fabricante
        if ($this->fabricante->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fabricante->AdvancedSearch->SearchValue != "" || $this->fabricante->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // codigo_ims
        if ($this->codigo_ims->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo_ims->AdvancedSearch->SearchValue != "" || $this->codigo_ims->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // codigo_de_barra
        if ($this->codigo_de_barra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo_de_barra->AdvancedSearch->SearchValue != "" || $this->codigo_de_barra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // categoria
        if ($this->categoria->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->categoria->AdvancedSearch->SearchValue != "" || $this->categoria->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // lista_pedido
        if ($this->lista_pedido->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->lista_pedido->AdvancedSearch->SearchValue != "" || $this->lista_pedido->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // unidad_medida_defecto
        if ($this->unidad_medida_defecto->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->unidad_medida_defecto->AdvancedSearch->SearchValue != "" || $this->unidad_medida_defecto->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_por_unidad_medida
        if ($this->cantidad_por_unidad_medida->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue != "" || $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_minima
        if ($this->cantidad_minima->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_minima->AdvancedSearch->SearchValue != "" || $this->cantidad_minima->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_maxima
        if ($this->cantidad_maxima->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_maxima->AdvancedSearch->SearchValue != "" || $this->cantidad_maxima->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_mano
        if ($this->cantidad_en_mano->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_mano->AdvancedSearch->SearchValue != "" || $this->cantidad_en_mano->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_almacenes
        if ($this->cantidad_en_almacenes->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_almacenes->AdvancedSearch->SearchValue != "" || $this->cantidad_en_almacenes->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_pedido
        if ($this->cantidad_en_pedido->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_pedido->AdvancedSearch->SearchValue != "" || $this->cantidad_en_pedido->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_transito
        if ($this->cantidad_en_transito->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_transito->AdvancedSearch->SearchValue != "" || $this->cantidad_en_transito->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ultimo_costo
        if ($this->ultimo_costo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ultimo_costo->AdvancedSearch->SearchValue != "" || $this->ultimo_costo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // descuento
        if ($this->descuento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->descuento->AdvancedSearch->SearchValue != "" || $this->descuento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // precio
        if ($this->precio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->precio->AdvancedSearch->SearchValue != "" || $this->precio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // alicuota
        if ($this->alicuota->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->alicuota->AdvancedSearch->SearchValue != "" || $this->alicuota->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // articulo_inventario
        if ($this->articulo_inventario->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->articulo_inventario->AdvancedSearch->SearchValue != "" || $this->articulo_inventario->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // activo
        if ($this->activo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->activo->AdvancedSearch->SearchValue != "" || $this->activo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // lote
        if ($this->lote->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->lote->AdvancedSearch->SearchValue != "" || $this->lote->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_vencimiento
        if ($this->fecha_vencimiento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_vencimiento->AdvancedSearch->SearchValue != "" || $this->fecha_vencimiento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
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
        $this->foto->Upload->DbValue = $row['foto'];
        $this->foto->setDbValue($this->foto->Upload->DbValue);
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->categoria->setDbValue($row['categoria']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidad_medida_defecto->setDbValue($row['unidad_medida_defecto']);
        $this->cantidad_por_unidad_medida->setDbValue($row['cantidad_por_unidad_medida']);
        $this->cantidad_minima->setDbValue($row['cantidad_minima']);
        $this->cantidad_maxima->setDbValue($row['cantidad_maxima']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->cantidad_en_almacenes->setDbValue($row['cantidad_en_almacenes']);
        $this->cantidad_en_pedido->setDbValue($row['cantidad_en_pedido']);
        $this->cantidad_en_transito->setDbValue($row['cantidad_en_transito']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio->setDbValue($row['precio']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->articulo_inventario->setDbValue($row['articulo_inventario']);
        $this->activo->setDbValue($row['activo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['foto'] = $this->foto->DefaultValue;
        $row['codigo'] = $this->codigo->DefaultValue;
        $row['nombre_comercial'] = $this->nombre_comercial->DefaultValue;
        $row['principio_activo'] = $this->principio_activo->DefaultValue;
        $row['presentacion'] = $this->presentacion->DefaultValue;
        $row['fabricante'] = $this->fabricante->DefaultValue;
        $row['codigo_ims'] = $this->codigo_ims->DefaultValue;
        $row['codigo_de_barra'] = $this->codigo_de_barra->DefaultValue;
        $row['categoria'] = $this->categoria->DefaultValue;
        $row['lista_pedido'] = $this->lista_pedido->DefaultValue;
        $row['unidad_medida_defecto'] = $this->unidad_medida_defecto->DefaultValue;
        $row['cantidad_por_unidad_medida'] = $this->cantidad_por_unidad_medida->DefaultValue;
        $row['cantidad_minima'] = $this->cantidad_minima->DefaultValue;
        $row['cantidad_maxima'] = $this->cantidad_maxima->DefaultValue;
        $row['cantidad_en_mano'] = $this->cantidad_en_mano->DefaultValue;
        $row['cantidad_en_almacenes'] = $this->cantidad_en_almacenes->DefaultValue;
        $row['cantidad_en_pedido'] = $this->cantidad_en_pedido->DefaultValue;
        $row['cantidad_en_transito'] = $this->cantidad_en_transito->DefaultValue;
        $row['ultimo_costo'] = $this->ultimo_costo->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['precio'] = $this->precio->DefaultValue;
        $row['alicuota'] = $this->alicuota->DefaultValue;
        $row['articulo_inventario'] = $this->articulo_inventario->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['lote'] = $this->lote->DefaultValue;
        $row['fecha_vencimiento'] = $this->fecha_vencimiento->DefaultValue;
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
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // foto

        // codigo

        // nombre_comercial

        // principio_activo

        // presentacion

        // fabricante

        // codigo_ims

        // codigo_de_barra

        // categoria

        // lista_pedido

        // unidad_medida_defecto

        // cantidad_por_unidad_medida

        // cantidad_minima

        // cantidad_maxima

        // cantidad_en_mano

        // cantidad_en_almacenes

        // cantidad_en_pedido

        // cantidad_en_transito

        // ultimo_costo

        // descuento

        // precio

        // alicuota

        // articulo_inventario

        // activo

        // lote

        // fecha_vencimiento

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // foto
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->ImageWidth = 120;
                $this->foto->ImageHeight = 120;
                $this->foto->ImageAlt = $this->foto->alt();
                $this->foto->ImageCssClass = "ew-image";
                $this->foto->ViewValue = $this->foto->Upload->DbValue;
            } else {
                $this->foto->ViewValue = "";
            }

            // codigo
            $this->codigo->ViewValue = $this->codigo->CurrentValue;

            // nombre_comercial
            $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;

            // principio_activo
            $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;

            // presentacion
            $this->presentacion->ViewValue = $this->presentacion->CurrentValue;

            // fabricante
            $curVal = strval($this->fabricante->CurrentValue);
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->fabricante->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $curVal, $this->fabricante->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                        $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                    } else {
                        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
                    }
                }
            } else {
                $this->fabricante->ViewValue = null;
            }

            // codigo_de_barra
            $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;

            // categoria
            $curVal = strval($this->categoria->CurrentValue);
            if ($curVal != "") {
                $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
                if ($this->categoria->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->categoria->getSelectFilter($this); // PHP
                    $sqlWrk = $this->categoria->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->categoria->Lookup->renderViewRow($rswrk[0]);
                        $this->categoria->ViewValue = $this->categoria->displayValue($arwrk);
                    } else {
                        $this->categoria->ViewValue = $this->categoria->CurrentValue;
                    }
                }
            } else {
                $this->categoria->ViewValue = null;
            }

            // lista_pedido
            $curVal = strval($this->lista_pedido->CurrentValue);
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
                if ($this->lista_pedido->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->lista_pedido->getSelectFilter($this); // PHP
                    $sqlWrk = $this->lista_pedido->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->lista_pedido->Lookup->renderViewRow($rswrk[0]);
                        $this->lista_pedido->ViewValue = $this->lista_pedido->displayValue($arwrk);
                    } else {
                        $this->lista_pedido->ViewValue = $this->lista_pedido->CurrentValue;
                    }
                }
            } else {
                $this->lista_pedido->ViewValue = null;
            }

            // cantidad_en_mano
            $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
            $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, $this->cantidad_en_mano->formatPattern());

            // cantidad_en_almacenes
            $this->cantidad_en_almacenes->ViewValue = $this->cantidad_en_almacenes->CurrentValue;
            $this->cantidad_en_almacenes->ViewValue = FormatNumber($this->cantidad_en_almacenes->ViewValue, $this->cantidad_en_almacenes->formatPattern());

            // cantidad_en_pedido
            $this->cantidad_en_pedido->ViewValue = $this->cantidad_en_pedido->CurrentValue;
            $this->cantidad_en_pedido->ViewValue = FormatNumber($this->cantidad_en_pedido->ViewValue, $this->cantidad_en_pedido->formatPattern());

            // cantidad_en_transito
            $this->cantidad_en_transito->ViewValue = $this->cantidad_en_transito->CurrentValue;
            $this->cantidad_en_transito->ViewValue = FormatNumber($this->cantidad_en_transito->ViewValue, $this->cantidad_en_transito->formatPattern());

            // ultimo_costo
            $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
            $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, $this->ultimo_costo->formatPattern());

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->formatPattern());

            // alicuota
            $curVal = strval($this->alicuota->CurrentValue);
            if ($curVal != "") {
                $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
                if ($this->alicuota->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->alicuota->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->alicuota->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                    $lookupFilter = $this->alicuota->getSelectFilter($this); // PHP
                    $sqlWrk = $this->alicuota->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->alicuota->Lookup->renderViewRow($rswrk[0]);
                        $this->alicuota->ViewValue = $this->alicuota->displayValue($arwrk);
                    } else {
                        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
                    }
                }
            } else {
                $this->alicuota->ViewValue = null;
            }

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // lote
            $this->lote->ViewValue = $this->lote->CurrentValue;

            // fecha_vencimiento
            $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
            $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

            // foto
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
                $this->foto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
                }
            } else {
                $this->foto->HrefValue = "";
            }
            $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;
            $this->foto->TooltipValue = "";
            if ($this->foto->UseColorbox) {
                if (EmptyValue($this->foto->TooltipValue)) {
                    $this->foto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->foto->LinkAttrs["data-rel"] = "articulo_x" . $this->RowCount . "_foto";
                $this->foto->LinkAttrs->appendClass("ew-lightbox");
            }

            // codigo
            $this->codigo->HrefValue = "";
            $this->codigo->TooltipValue = "";

            // nombre_comercial
            $this->nombre_comercial->HrefValue = "";
            $this->nombre_comercial->TooltipValue = "";

            // principio_activo
            $this->principio_activo->HrefValue = "";
            $this->principio_activo->TooltipValue = "";

            // presentacion
            $this->presentacion->HrefValue = "";
            $this->presentacion->TooltipValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";
            $this->fabricante->TooltipValue = "";

            // categoria
            $this->categoria->HrefValue = "";
            $this->categoria->TooltipValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";
            $this->lista_pedido->TooltipValue = "";

            // cantidad_en_mano
            $this->cantidad_en_mano->HrefValue = "";
            $this->cantidad_en_mano->TooltipValue = "";

            // cantidad_en_almacenes
            $this->cantidad_en_almacenes->HrefValue = "";
            $this->cantidad_en_almacenes->TooltipValue = "";

            // cantidad_en_pedido
            $this->cantidad_en_pedido->HrefValue = "";
            $this->cantidad_en_pedido->TooltipValue = "";

            // cantidad_en_transito
            $this->cantidad_en_transito->HrefValue = "";
            $this->cantidad_en_transito->TooltipValue = "";

            // descuento
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // activo
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";
        } elseif ($this->RowType == RowType::SEARCH) {
            // foto
            $this->foto->setupEditAttributes();
            if (!$this->foto->Raw) {
                $this->foto->AdvancedSearch->SearchValue = HtmlDecode($this->foto->AdvancedSearch->SearchValue);
            }
            $this->foto->EditValue = HtmlEncode($this->foto->AdvancedSearch->SearchValue);
            $this->foto->PlaceHolder = RemoveHtml($this->foto->caption());

            // codigo
            $this->codigo->setupEditAttributes();
            if (!$this->codigo->Raw) {
                $this->codigo->AdvancedSearch->SearchValue = HtmlDecode($this->codigo->AdvancedSearch->SearchValue);
            }
            $this->codigo->EditValue = HtmlEncode($this->codigo->AdvancedSearch->SearchValue);
            $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

            // nombre_comercial
            $this->nombre_comercial->setupEditAttributes();
            if (!$this->nombre_comercial->Raw) {
                $this->nombre_comercial->AdvancedSearch->SearchValue = HtmlDecode($this->nombre_comercial->AdvancedSearch->SearchValue);
            }
            $this->nombre_comercial->EditValue = HtmlEncode($this->nombre_comercial->AdvancedSearch->SearchValue);
            $this->nombre_comercial->PlaceHolder = RemoveHtml($this->nombre_comercial->caption());

            // principio_activo
            $this->principio_activo->setupEditAttributes();
            if (!$this->principio_activo->Raw) {
                $this->principio_activo->AdvancedSearch->SearchValue = HtmlDecode($this->principio_activo->AdvancedSearch->SearchValue);
            }
            $this->principio_activo->EditValue = HtmlEncode($this->principio_activo->AdvancedSearch->SearchValue);
            $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

            // presentacion
            $this->presentacion->setupEditAttributes();
            if (!$this->presentacion->Raw) {
                $this->presentacion->AdvancedSearch->SearchValue = HtmlDecode($this->presentacion->AdvancedSearch->SearchValue);
            }
            $this->presentacion->EditValue = HtmlEncode($this->presentacion->AdvancedSearch->SearchValue);
            $this->presentacion->PlaceHolder = RemoveHtml($this->presentacion->caption());

            // fabricante
            $curVal = trim(strval($this->fabricante->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            } else {
                $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->Lookup !== null && is_array($this->fabricante->lookupOptions()) && count($this->fabricante->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->fabricante->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->fabricante->EditValue = array_values($this->fabricante->lookupOptions());
                if ($this->fabricante->AdvancedSearch->ViewValue == "") {
                    $this->fabricante->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->fabricante->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $this->fabricante->AdvancedSearch->SearchValue, $this->fabricante->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                }
                $sqlWrk = $this->fabricante->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->fabricante->EditValue = $arwrk;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // categoria
            $this->categoria->setupEditAttributes();
            $curVal = trim(strval($this->categoria->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->categoria->AdvancedSearch->ViewValue = $this->categoria->lookupCacheOption($curVal);
            } else {
                $this->categoria->AdvancedSearch->ViewValue = $this->categoria->Lookup !== null && is_array($this->categoria->lookupOptions()) && count($this->categoria->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->categoria->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->categoria->EditValue = array_values($this->categoria->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->categoria->AdvancedSearch->SearchValue, $this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->categoria->getSelectFilter($this); // PHP
                $sqlWrk = $this->categoria->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->categoria->EditValue = $arwrk;
            }
            $this->categoria->PlaceHolder = RemoveHtml($this->categoria->caption());

            // lista_pedido
            $this->lista_pedido->setupEditAttributes();
            $curVal = trim(strval($this->lista_pedido->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->lista_pedido->AdvancedSearch->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            } else {
                $this->lista_pedido->AdvancedSearch->ViewValue = $this->lista_pedido->Lookup !== null && is_array($this->lista_pedido->lookupOptions()) && count($this->lista_pedido->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->lista_pedido->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->lista_pedido->EditValue = array_values($this->lista_pedido->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->lista_pedido->AdvancedSearch->SearchValue, $this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->lista_pedido->getSelectFilter($this); // PHP
                $sqlWrk = $this->lista_pedido->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->lista_pedido->EditValue = $arwrk;
            }
            $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

            // cantidad_en_mano
            $this->cantidad_en_mano->setupEditAttributes();
            $this->cantidad_en_mano->EditValue = $this->cantidad_en_mano->AdvancedSearch->SearchValue;
            $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());
            $this->cantidad_en_mano->setupEditAttributes();
            $this->cantidad_en_mano->EditValue2 = $this->cantidad_en_mano->AdvancedSearch->SearchValue2;
            $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());

            // cantidad_en_almacenes
            $this->cantidad_en_almacenes->setupEditAttributes();
            $this->cantidad_en_almacenes->EditValue = $this->cantidad_en_almacenes->AdvancedSearch->SearchValue;
            $this->cantidad_en_almacenes->PlaceHolder = RemoveHtml($this->cantidad_en_almacenes->caption());

            // cantidad_en_pedido
            $this->cantidad_en_pedido->setupEditAttributes();
            $this->cantidad_en_pedido->EditValue = $this->cantidad_en_pedido->AdvancedSearch->SearchValue;
            $this->cantidad_en_pedido->PlaceHolder = RemoveHtml($this->cantidad_en_pedido->caption());
            $this->cantidad_en_pedido->setupEditAttributes();
            $this->cantidad_en_pedido->EditValue2 = $this->cantidad_en_pedido->AdvancedSearch->SearchValue2;
            $this->cantidad_en_pedido->PlaceHolder = RemoveHtml($this->cantidad_en_pedido->caption());

            // cantidad_en_transito
            $this->cantidad_en_transito->setupEditAttributes();
            $this->cantidad_en_transito->EditValue = $this->cantidad_en_transito->AdvancedSearch->SearchValue;
            $this->cantidad_en_transito->PlaceHolder = RemoveHtml($this->cantidad_en_transito->caption());
            $this->cantidad_en_transito->setupEditAttributes();
            $this->cantidad_en_transito->EditValue2 = $this->cantidad_en_transito->AdvancedSearch->SearchValue2;
            $this->cantidad_en_transito->PlaceHolder = RemoveHtml($this->cantidad_en_transito->caption());

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->AdvancedSearch->SearchValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());

            // activo
            $this->activo->setupEditAttributes();
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckNumber($this->cantidad_en_mano->AdvancedSearch->SearchValue)) {
            $this->cantidad_en_mano->addErrorMessage($this->cantidad_en_mano->getErrorMessage(false));
        }
        if (!CheckNumber($this->cantidad_en_mano->AdvancedSearch->SearchValue2)) {
            $this->cantidad_en_mano->addErrorMessage($this->cantidad_en_mano->getErrorMessage(false));
        }
        if (!CheckNumber($this->cantidad_en_pedido->AdvancedSearch->SearchValue)) {
            $this->cantidad_en_pedido->addErrorMessage($this->cantidad_en_pedido->getErrorMessage(false));
        }
        if (!CheckNumber($this->cantidad_en_pedido->AdvancedSearch->SearchValue2)) {
            $this->cantidad_en_pedido->addErrorMessage($this->cantidad_en_pedido->getErrorMessage(false));
        }
        if (!CheckNumber($this->cantidad_en_transito->AdvancedSearch->SearchValue)) {
            $this->cantidad_en_transito->addErrorMessage($this->cantidad_en_transito->getErrorMessage(false));
        }
        if (!CheckNumber($this->cantidad_en_transito->AdvancedSearch->SearchValue2)) {
            $this->cantidad_en_transito->addErrorMessage($this->cantidad_en_transito->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->id->AdvancedSearch->load();
        $this->foto->AdvancedSearch->load();
        $this->codigo->AdvancedSearch->load();
        $this->nombre_comercial->AdvancedSearch->load();
        $this->principio_activo->AdvancedSearch->load();
        $this->presentacion->AdvancedSearch->load();
        $this->fabricante->AdvancedSearch->load();
        $this->codigo_ims->AdvancedSearch->load();
        $this->codigo_de_barra->AdvancedSearch->load();
        $this->categoria->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->unidad_medida_defecto->AdvancedSearch->load();
        $this->cantidad_por_unidad_medida->AdvancedSearch->load();
        $this->cantidad_minima->AdvancedSearch->load();
        $this->cantidad_maxima->AdvancedSearch->load();
        $this->cantidad_en_mano->AdvancedSearch->load();
        $this->cantidad_en_almacenes->AdvancedSearch->load();
        $this->cantidad_en_pedido->AdvancedSearch->load();
        $this->cantidad_en_transito->AdvancedSearch->load();
        $this->ultimo_costo->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->precio->AdvancedSearch->load();
        $this->alicuota->AdvancedSearch->load();
        $this->articulo_inventario->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->lote->AdvancedSearch->load();
        $this->fecha_vencimiento->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        if ($type == "print" || $custom) { // Printer friendly / custom export
            $pageUrl = $this->pageUrl(false);
            $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        } else { // Export API URL
            $exportUrl = GetApiUrl(Config("API_EXPORT_ACTION") . "/" . $type . "/" . $this->TableVar);
        }
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" form=\"farticulolist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" form=\"farticulolist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" form=\"farticulolist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\">" . $Language->phrase("ExportToPdf") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtml", true)) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXml", true)) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsv", true)) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ' data-url="' . $exportUrl . '"' : '';
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmail", true) . '" data-caption="' . $Language->phrase("ExportToEmail", true) . '" form="farticulolist" data-ew-action="email" data-custom="false" data-hdr="' . $Language->phrase("ExportToEmail", true) . '" data-exported-selected="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendly", true)) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language, $Security;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = false;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = false;

        // Export to HTML
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = false;

        // Export to XML
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = false;

        // Export to CSV
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = true;

        // Export to PDF
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = false;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = false;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
        if (!$Security->canExport()) { // Export not allowed
            $this->ExportOptions->hideAllOptions();
        }
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl(false);
        $this->SearchOptions = new ListOptions(TagClassName: "ew-search-option");

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : "";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"farticulosrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction && $this->CurrentAction != "search") {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return true;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param bool $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($doc)
    {
        global $Language;
        $rs = null;
        $this->TotalRecords = $this->listRecordCount();

        // Export all
        if ($this->ExportAll) {
            if (Config("EXPORT_ALL_TIME_LIMIT") >= 0) {
                @set_time_limit(Config("EXPORT_ALL_TIME_LIMIT"));
            }
            $this->DisplayRecords = $this->TotalRecords;
            $this->StopRecord = $this->TotalRecords;
        } else { // Export one page only
            $this->setupStartRecord(); // Set up start record position
            // Set the last record to display
            if ($this->DisplayRecords <= 0) {
                $this->StopRecord = $this->TotalRecords;
            } else {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            }
        }
        $rs = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords);
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $doc->ExportCustom = !$this->pageExporting($doc);

        // Page header
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "");
        $rs->free();

        // Page footer
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Call Page Exported server event
        $this->pageExported($doc);
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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
                case "x_presentacion":
                    break;
                case "x_fabricante":
                    break;
                case "x_categoria":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_lista_pedido":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_unidad_medida_defecto":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_cantidad_por_unidad_medida":
                    break;
                case "x_alicuota":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_articulo_inventario":
                    break;
                case "x_activo":
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
        $infiniteScroll = ConvertToBool(Param("infinitescroll"));
        if ($pageNo !== null) { // Check for "pageno" parameter first
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                if ($this->StartRecord <= 0) {
                    $this->StartRecord = 1;
                } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                    $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                }
            }
        } elseif ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
            $this->StartRecord = $startRec;
        } elseif (!$infiniteScroll) {
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

    // Parse query builder rule
    protected function parseRules($group, $fieldName = "", $itemName = "") {
        $group["condition"] ??= "AND";
        if (!in_array($group["condition"], ["AND", "OR"])) {
            throw new \Exception("Unable to build SQL query with condition '" . $group["condition"] . "'");
        }
        if (!is_array($group["rules"] ?? null)) {
            return "";
        }
        $parts = [];
        foreach ($group["rules"] as $rule) {
            if (is_array($rule["rules"] ?? null) && count($rule["rules"]) > 0) {
                $part = $this->parseRules($rule, $fieldName, $itemName);
                if ($part) {
                    $parts[] = "(" . " " . $part . " " . ")" . " ";
                }
            } else {
                $field = $rule["field"];
                $fld = $this->fieldByParam($field);
                $dbid = $this->Dbid;
                if ($fld instanceof ReportField && is_array($fld->DashboardSearchSourceFields)) {
                    $item = $fld->DashboardSearchSourceFields[$itemName] ?? null;
                    if ($item) {
                        $tbl = Container($item["table"]);
                        $dbid = $tbl->Dbid;
                        $fld = $tbl->Fields[$item["field"]];
                    } else {
                        $fld = null;
                    }
                }
                if ($fld && ($fieldName == "" || $fld->Name == $fieldName)) { // Field name not specified or matched field name
                    $fldOpr = array_search($rule["operator"], Config("CLIENT_SEARCH_OPERATORS"));
                    $ope = Config("QUERY_BUILDER_OPERATORS")[$rule["operator"]] ?? null;
                    if (!$ope || !$fldOpr) {
                        throw new \Exception("Unknown SQL operation for operator '" . $rule["operator"] . "'");
                    }
                    if ($ope["nb_inputs"] > 0 && isset($rule["value"]) && !EmptyValue($rule["value"]) || IsNullOrEmptyOperator($fldOpr)) {
                        $fldVal = $rule["value"];
                        if (is_array($fldVal)) {
                            $fldVal = $fld->isMultiSelect() ? implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal) : $fldVal[0];
                        }
                        $useFilter = $fld->UseFilter; // Query builder does not use filter
                        try {
                            if ($fld instanceof ReportField) { // Search report fields
                                if ($fld->SearchType == "dropdown") {
                                    if (is_array($fldVal)) {
                                        $sql = "";
                                        foreach ($fldVal as $val) {
                                            AddFilter($sql, DropDownFilter($fld, $val, $fldOpr, $dbid), "OR");
                                        }
                                        $parts[] = $sql;
                                    } else {
                                        $parts[] = DropDownFilter($fld, $fldVal, $fldOpr, $dbid);
                                    }
                                } else {
                                    $fld->AdvancedSearch->SearchOperator = $fldOpr;
                                    $fld->AdvancedSearch->SearchValue = $fldVal;
                                    $parts[] = GetReportFilter($fld, false, $dbid);
                                }
                            } else { // Search normal fields
                                if ($fld->isMultiSelect()) {
                                    $parts[] = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, ConvertSearchValue($fldVal, $fldOpr, $fld), $this->Dbid) : "";
                                } else {
                                    $fldVal2 = ContainsString($fldOpr, "BETWEEN") ? $rule["value"][1] : ""; // BETWEEN
                                    if (is_array($fldVal2)) {
                                        $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
                                    }
                                    $fld->AdvancedSearch->SearchValue = ConvertSearchValue($fldVal, $fldOpr, $fld);
                                    $fld->AdvancedSearch->SearchValue2 = ConvertSearchValue($fldVal2, $fldOpr, $fld);
                                    $parts[] = GetSearchSql(
                                        $fld,
                                        $fld->AdvancedSearch->SearchValue, // SearchValue
                                        $fldOpr,
                                        "", // $fldCond not used
                                        $fld->AdvancedSearch->SearchValue2, // SearchValue2
                                        "", // $fldOpr2 not used
                                        $this->Dbid
                                    );
                                }
                            }
                        } finally {
                            $fld->UseFilter = $useFilter;
                        }
                    }
                }
            }
        }
        $where = "";
        foreach ($parts as $part) {
            AddFilter($where, $part, $group["condition"]);
        }
        if ($where && ($group["not"] ?? false)) {
            $where = "NOT (" . $where . ")";
        }
        return $where;
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
       	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
    	$grupo = trim(ExecuteScalar($sql));
    	if($grupo == "CLIENTE") {
    		$this->activo->Visible = false;
    		$this->codigo_ims->Visible = false;
    		$this->codigo->Visible = false;
    		$this->cantidad_minima->Visible = false;
    		$this->cantidad_maxima->Visible = false;
    		$this->cantidad_en_transito->Visible = false;
    		$this->ultimo_costo->Visible = false;
    	}
        $this->activo->Visible = false;
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
        // Consulto de donde se toman las salida
        $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
        $tipo_documento = 'TDCNET';
        if($row = ExecuteRow($sql)) $tipo_documento = $row["tipo_documento"];
        $codart = intval($this->id->CurrentValue);
        $sql = "SELECT 
                    SUM(x.cantidad_movimiento) AS cantidad 
                FROM 
                    (
                        SELECT 
                            a.cantidad_movimiento 
                        FROM 
                            entradas_salidas AS a 
                            JOIN entradas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                        WHERE 
                            (
                                (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                                (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                            ) AND a.newdata = 'S' 
                        UNION ALL SELECT 
                            a.cantidad_movimiento  
                        FROM 
                            entradas_salidas AS a 
                            JOIN salidas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                        WHERE 
                            (
                                (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                                (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                            ) AND a.newdata = 'S' 
                        UNION ALL SELECT 
                            aa.cantidad_movimiento  
                        FROM 
                            pedidos_detalles_online AS aa 
                            JOIN pedidos_online AS bb ON bb.id = aa.id_documento 
                        WHERE 
                            bb.estatus = 'NUEVO' 
                    ) AS x 
                WHERE 1;";
        $cantidad = 0;
        if($row = ExecuteRow($sql)) $cantidad = intval($row["cantidad"]);
    	$header = '<div class="row">
    				<div class="col-sm-3 col-md-2">
    					<a href="include/articulos_por_lote.php" class="btn btn-primary">Descargar Art&iacute;culos por Lote</a>
    				</div>
    				<div class="col-sm-3 col-md-2">
    					<a href="include/DescargarArticulosCostos.php" class="btn btn-primary">Descargar Art&iacute;culos Costos</a>
    				</div>
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#8ad3d3;" class="alert" role="alert">Sin Existencia</div>
    				</div>
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#ffcccc;" class="alert" role="alert">Desactivado</div>
    				</div>
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#ffcc99;" class="alert" role="alert">Total General Articulos <b>' . number_format($cantidad, 0, ".", ".") . ' Unidades</b></div>
    				</div>
    			</div>';
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

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
    }

    // Page Exporting event
    // $doc = export document object
    public function pageExporting(&$doc)
    {
    	if (IsAdmin()) {
    	    $doc->Text = "\n\"CODIGO\",\"NOMBRE COMERCIAL\",\"NOMBRE PRINCIPAL\",\"PRESENTACION\",\"FABRICANTE\",\"LISTA PEDIDO\",\"EXISTENCIA\",\"COSTO\",\"PRECIO\",\"DESCUENTO\"\n";
        }
        else {
    	    $doc->Text = "\n\"CODIGO\",\"NOMBRE COMERCIAL\",\"NOMBRE PRINCIPAL\",\"PRESENTACION\",\"FABRICANTE\",\"LISTA PEDIDO\",\"EXISTENCIA\",\"PRECIO\",\"DESCUENTO\"\n";
        }
        if (IsAdmin()) return true;
        return false; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $doc = export document object
    public function rowExport($doc, $row)
    {
    	if (IsAdmin()) {
    		$doc->Text .= "\n\"" . $this->codigo->ViewValue . "\",\"" . $this->nombre_comercial->ViewValue . "\",\"" . $this->principio_activo->ViewValue . "\",\"" . $this->presentacion->ViewValue . "\",\"" . $this->fabricante->ViewValue . "\",\"" . $this->lista_pedido->ViewValue . "\",\"" . $this->cantidad_en_mano->ViewValue . "\",\"" . $this->ultimo_costo->ViewValue . "\",\"" . $this->precio->ViewValue . "\",\"" . $this->descuento->ViewValue . "\"";
    	}
    	else {
    		$doc->Text .= "\n\"" . $this->codigo->ViewValue . "\",\"" . $this->nombre_comercial->ViewValue . "\",\"" . $this->principio_activo->ViewValue . "\",\"" . $this->presentacion->ViewValue . "\",\"" . $this->fabricante->ViewValue . "\",\"" . $this->lista_pedido->ViewValue . "\",\"" . $this->cantidad_en_mano->ViewValue . "\",\"" . $this->precio->ViewValue . "\",\"" . $this->descuento->ViewValue . "\"";
    	}
    }

    // Page Exported event
    // $doc = export document object
    public function pageExported($doc)
    {
       	$doc->Text .= "</table>";
        //Log($doc->Text);
    }

    // Page Importing event
    public function pageImporting(&$builder, &$options)
    {
        //var_dump($options); // Show all options for importing
        //$builder = fn($workflow) => $workflow->addStep($myStep);
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($obj, $results)
    {
        //var_dump($obj); // Workflow result object
        //var_dump($results); // Import results
    }
}
