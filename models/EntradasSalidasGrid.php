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
class EntradasSalidasGrid extends EntradasSalidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EntradasSalidasGrid";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fentradas_salidasgrid";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "EntradasSalidasGrid";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

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
        $this->tipo_documento->Visible = false;
        $this->id_documento->Visible = false;
        $this->fabricante->Visible = false;
        $this->articulo->setVisibility();
        $this->lote->setVisibility();
        $this->fecha_vencimiento->setVisibility();
        $this->almacen->setVisibility();
        $this->id_compra->setVisibility();
        $this->cantidad_articulo->setVisibility();
        $this->articulo_unidad_medida->Visible = false;
        $this->cantidad_unidad_medida->Visible = false;
        $this->cantidad_movimiento->Visible = false;
        $this->precio_unidad_sin_desc->setVisibility();
        $this->descuento->setVisibility();
        $this->costo_unidad->setVisibility();
        $this->costo->setVisibility();
        $this->precio_unidad->setVisibility();
        $this->precio->setVisibility();
        $this->alicuota->Visible = false;
        $this->cantidad_movimiento_consignacion->Visible = false;
        $this->id_consignacion->Visible = false;
        $this->check_ne->setVisibility();
        $this->packer_cantidad->Visible = false;
        $this->newdata->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'entradas_salidas';
        $this->TableName = 'entradas_salidas';

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

        // Table object (entradas_salidas)
        if (!isset($GLOBALS["entradas_salidas"]) || $GLOBALS["entradas_salidas"]::class == PROJECT_NAMESPACE . "entradas_salidas") {
            $GLOBALS["entradas_salidas"] = &$this;
        }
        $this->AddUrl = "EntradasSalidasAdd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'entradas_salidas');
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->almacen);
        $this->setupLookupOptions($this->articulo_unidad_medida);
        $this->setupLookupOptions($this->cantidad_unidad_medida);
        $this->setupLookupOptions($this->check_ne);
        $this->setupLookupOptions($this->newdata);

        // Load default values for add
        $this->loadDefaultValues();

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fentradas_salidasgrid";
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
                    $item->Visible = $Security->allowDelete(CurrentProjectID() . $this->TableName);
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
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "entradas") {
            $masterTbl = Container("entradas");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("EntradasList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = RowType::MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "salidas") {
            $masterTbl = Container("salidas");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("SalidasList"); // Return to master page
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
        $this->cantidad_articulo->FormValue = ""; // Clear form value
        $this->precio_unidad_sin_desc->FormValue = ""; // Clear form value
        $this->descuento->FormValue = ""; // Clear form value
        $this->costo_unidad->FormValue = ""; // Clear form value
        $this->costo->FormValue = ""; // Clear form value
        $this->precio_unidad->FormValue = ""; // Clear form value
        $this->precio->FormValue = ""; // Clear form value
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
        if ($this->AuditTrailOnEdit) {
            $this->writeAuditTrailDummy($Language->phrase("BatchUpdateBegin")); // Batch update begin
        }
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
            if ($this->AuditTrailOnEdit) {
                $this->writeAuditTrailDummy($Language->phrase("BatchUpdateSuccess")); // Batch update success
            }
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->AuditTrailOnEdit) {
                $this->writeAuditTrailDummy($Language->phrase("BatchUpdateRollback")); // Batch update rollback
            }
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
        if ($this->AuditTrailOnAdd) {
            $this->writeAuditTrailDummy($Language->phrase("BatchInsertBegin")); // Batch insert begin
        }
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
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailDummy($Language->phrase("BatchInsertSuccess")); // Batch insert success
            }
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailDummy($Language->phrase("BatchInsertRollback")); // Batch insert rollback
            }
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
            $CurrentForm->hasValue("x_articulo") &&
            $CurrentForm->hasValue("o_articulo") &&
            $this->articulo->CurrentValue != $this->articulo->DefaultValue &&
            !($this->articulo->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->articulo->CurrentValue == $this->articulo->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_lote") &&
            $CurrentForm->hasValue("o_lote") &&
            $this->lote->CurrentValue != $this->lote->DefaultValue &&
            !($this->lote->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->lote->CurrentValue == $this->lote->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_fecha_vencimiento") &&
            $CurrentForm->hasValue("o_fecha_vencimiento") &&
            $this->fecha_vencimiento->CurrentValue != $this->fecha_vencimiento->DefaultValue &&
            !($this->fecha_vencimiento->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->fecha_vencimiento->CurrentValue == $this->fecha_vencimiento->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_almacen") &&
            $CurrentForm->hasValue("o_almacen") &&
            $this->almacen->CurrentValue != $this->almacen->DefaultValue &&
            !($this->almacen->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->almacen->CurrentValue == $this->almacen->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_id_compra") &&
            $CurrentForm->hasValue("o_id_compra") &&
            $this->id_compra->CurrentValue != $this->id_compra->DefaultValue &&
            !($this->id_compra->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->id_compra->CurrentValue == $this->id_compra->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_cantidad_articulo") &&
            $CurrentForm->hasValue("o_cantidad_articulo") &&
            $this->cantidad_articulo->CurrentValue != $this->cantidad_articulo->DefaultValue &&
            !($this->cantidad_articulo->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->cantidad_articulo->CurrentValue == $this->cantidad_articulo->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_precio_unidad_sin_desc") &&
            $CurrentForm->hasValue("o_precio_unidad_sin_desc") &&
            $this->precio_unidad_sin_desc->CurrentValue != $this->precio_unidad_sin_desc->DefaultValue &&
            !($this->precio_unidad_sin_desc->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->precio_unidad_sin_desc->CurrentValue == $this->precio_unidad_sin_desc->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_descuento") &&
            $CurrentForm->hasValue("o_descuento") &&
            $this->descuento->CurrentValue != $this->descuento->DefaultValue &&
            !($this->descuento->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->descuento->CurrentValue == $this->descuento->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_costo_unidad") &&
            $CurrentForm->hasValue("o_costo_unidad") &&
            $this->costo_unidad->CurrentValue != $this->costo_unidad->DefaultValue &&
            !($this->costo_unidad->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->costo_unidad->CurrentValue == $this->costo_unidad->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_costo") &&
            $CurrentForm->hasValue("o_costo") &&
            $this->costo->CurrentValue != $this->costo->DefaultValue &&
            !($this->costo->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->costo->CurrentValue == $this->costo->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_precio_unidad") &&
            $CurrentForm->hasValue("o_precio_unidad") &&
            $this->precio_unidad->CurrentValue != $this->precio_unidad->DefaultValue &&
            !($this->precio_unidad->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->precio_unidad->CurrentValue == $this->precio_unidad->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_precio") &&
            $CurrentForm->hasValue("o_precio") &&
            $this->precio->CurrentValue != $this->precio->DefaultValue &&
            !($this->precio->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->precio->CurrentValue == $this->precio->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_check_ne") &&
            $CurrentForm->hasValue("o_check_ne") &&
            $this->check_ne->CurrentValue != $this->check_ne->DefaultValue &&
            !($this->check_ne->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->check_ne->CurrentValue == $this->check_ne->getSessionValue())
        ) {
            return false;
        }
        if (
            $CurrentForm->hasValue("x_newdata") &&
            $CurrentForm->hasValue("o_newdata") &&
            $this->newdata->CurrentValue != $this->newdata->DefaultValue &&
            !($this->newdata->IsForeignKey && $this->getCurrentMasterTable() != "" && $this->newdata->CurrentValue == $this->newdata->getSessionValue())
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
            $defaultSort = $this->articulo->Expression . " DESC"; // Set up default sort
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
                        $this->tipo_documento->setSessionValue("");
                        $this->id_documento->setSessionValue("");
                        $this->tipo_documento->setSessionValue("");
                        $this->id_documento->setSessionValue("");
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

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
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
                if (!$Security->allowDelete(CurrentProjectID() . $this->TableName) && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
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
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"entradas_salidas\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
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
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"entradas_salidas\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
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

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            if ($this->ModalAdd && !IsMobile()) {
                $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"entradas_salidas\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
            } else {
                $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            }
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
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
                    $item->Visible = $Security->canAdd();
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_entradas_salidas", "data-rowtype" => RowType::ADD]);
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
            "id" => "r" . $this->RowCount . "_entradas_salidas",
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
        $this->check_ne->DefaultValue = $this->check_ne->getDefault(); // PHP
        $this->check_ne->OldValue = $this->check_ne->DefaultValue;
        $this->newdata->DefaultValue = $this->newdata->getDefault(); // PHP
        $this->newdata->OldValue = $this->newdata->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_articulo")) {
            $this->articulo->setOldValue($CurrentForm->getValue("o_articulo"));
        }

        // Check field name 'lote' first before field var 'x_lote'
        $val = $CurrentForm->hasValue("lote") ? $CurrentForm->getValue("lote") : $CurrentForm->getValue("x_lote");
        if (!$this->lote->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lote->Visible = false; // Disable update for API request
            } else {
                $this->lote->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_lote")) {
            $this->lote->setOldValue($CurrentForm->getValue("o_lote"));
        }

        // Check field name 'fecha_vencimiento' first before field var 'x_fecha_vencimiento'
        $val = $CurrentForm->hasValue("fecha_vencimiento") ? $CurrentForm->getValue("fecha_vencimiento") : $CurrentForm->getValue("x_fecha_vencimiento");
        if (!$this->fecha_vencimiento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha_vencimiento->Visible = false; // Disable update for API request
            } else {
                $this->fecha_vencimiento->setFormValue($val, true, $validate);
            }
            $this->fecha_vencimiento->CurrentValue = UnFormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern());
        }
        if ($CurrentForm->hasValue("o_fecha_vencimiento")) {
            $this->fecha_vencimiento->setOldValue($CurrentForm->getValue("o_fecha_vencimiento"));
        }

        // Check field name 'almacen' first before field var 'x_almacen'
        $val = $CurrentForm->hasValue("almacen") ? $CurrentForm->getValue("almacen") : $CurrentForm->getValue("x_almacen");
        if (!$this->almacen->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->almacen->Visible = false; // Disable update for API request
            } else {
                $this->almacen->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_almacen")) {
            $this->almacen->setOldValue($CurrentForm->getValue("o_almacen"));
        }

        // Check field name 'id_compra' first before field var 'x_id_compra'
        $val = $CurrentForm->hasValue("id_compra") ? $CurrentForm->getValue("id_compra") : $CurrentForm->getValue("x_id_compra");
        if (!$this->id_compra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_compra->Visible = false; // Disable update for API request
            } else {
                $this->id_compra->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_id_compra")) {
            $this->id_compra->setOldValue($CurrentForm->getValue("o_id_compra"));
        }

        // Check field name 'cantidad_articulo' first before field var 'x_cantidad_articulo'
        $val = $CurrentForm->hasValue("cantidad_articulo") ? $CurrentForm->getValue("cantidad_articulo") : $CurrentForm->getValue("x_cantidad_articulo");
        if (!$this->cantidad_articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_articulo->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_articulo->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_cantidad_articulo")) {
            $this->cantidad_articulo->setOldValue($CurrentForm->getValue("o_cantidad_articulo"));
        }

        // Check field name 'precio_unidad_sin_desc' first before field var 'x_precio_unidad_sin_desc'
        $val = $CurrentForm->hasValue("precio_unidad_sin_desc") ? $CurrentForm->getValue("precio_unidad_sin_desc") : $CurrentForm->getValue("x_precio_unidad_sin_desc");
        if (!$this->precio_unidad_sin_desc->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio_unidad_sin_desc->Visible = false; // Disable update for API request
            } else {
                $this->precio_unidad_sin_desc->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_precio_unidad_sin_desc")) {
            $this->precio_unidad_sin_desc->setOldValue($CurrentForm->getValue("o_precio_unidad_sin_desc"));
        }

        // Check field name 'descuento' first before field var 'x_descuento'
        $val = $CurrentForm->hasValue("descuento") ? $CurrentForm->getValue("descuento") : $CurrentForm->getValue("x_descuento");
        if (!$this->descuento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->descuento->Visible = false; // Disable update for API request
            } else {
                $this->descuento->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_descuento")) {
            $this->descuento->setOldValue($CurrentForm->getValue("o_descuento"));
        }

        // Check field name 'costo_unidad' first before field var 'x_costo_unidad'
        $val = $CurrentForm->hasValue("costo_unidad") ? $CurrentForm->getValue("costo_unidad") : $CurrentForm->getValue("x_costo_unidad");
        if (!$this->costo_unidad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->costo_unidad->Visible = false; // Disable update for API request
            } else {
                $this->costo_unidad->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_costo_unidad")) {
            $this->costo_unidad->setOldValue($CurrentForm->getValue("o_costo_unidad"));
        }

        // Check field name 'costo' first before field var 'x_costo'
        $val = $CurrentForm->hasValue("costo") ? $CurrentForm->getValue("costo") : $CurrentForm->getValue("x_costo");
        if (!$this->costo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->costo->Visible = false; // Disable update for API request
            } else {
                $this->costo->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_costo")) {
            $this->costo->setOldValue($CurrentForm->getValue("o_costo"));
        }

        // Check field name 'precio_unidad' first before field var 'x_precio_unidad'
        $val = $CurrentForm->hasValue("precio_unidad") ? $CurrentForm->getValue("precio_unidad") : $CurrentForm->getValue("x_precio_unidad");
        if (!$this->precio_unidad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio_unidad->Visible = false; // Disable update for API request
            } else {
                $this->precio_unidad->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_precio_unidad")) {
            $this->precio_unidad->setOldValue($CurrentForm->getValue("o_precio_unidad"));
        }

        // Check field name 'precio' first before field var 'x_precio'
        $val = $CurrentForm->hasValue("precio") ? $CurrentForm->getValue("precio") : $CurrentForm->getValue("x_precio");
        if (!$this->precio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio->Visible = false; // Disable update for API request
            } else {
                $this->precio->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_precio")) {
            $this->precio->setOldValue($CurrentForm->getValue("o_precio"));
        }

        // Check field name 'check_ne' first before field var 'x_check_ne'
        $val = $CurrentForm->hasValue("check_ne") ? $CurrentForm->getValue("check_ne") : $CurrentForm->getValue("x_check_ne");
        if (!$this->check_ne->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->check_ne->Visible = false; // Disable update for API request
            } else {
                $this->check_ne->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_check_ne")) {
            $this->check_ne->setOldValue($CurrentForm->getValue("o_check_ne"));
        }

        // Check field name 'newdata' first before field var 'x_newdata'
        $val = $CurrentForm->hasValue("newdata") ? $CurrentForm->getValue("newdata") : $CurrentForm->getValue("x_newdata");
        if (!$this->newdata->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->newdata->Visible = false; // Disable update for API request
            } else {
                $this->newdata->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_newdata")) {
            $this->newdata->setOldValue($CurrentForm->getValue("o_newdata"));
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
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->lote->CurrentValue = $this->lote->FormValue;
        $this->fecha_vencimiento->CurrentValue = $this->fecha_vencimiento->FormValue;
        $this->fecha_vencimiento->CurrentValue = UnFormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern());
        $this->almacen->CurrentValue = $this->almacen->FormValue;
        $this->id_compra->CurrentValue = $this->id_compra->FormValue;
        $this->cantidad_articulo->CurrentValue = $this->cantidad_articulo->FormValue;
        $this->precio_unidad_sin_desc->CurrentValue = $this->precio_unidad_sin_desc->FormValue;
        $this->descuento->CurrentValue = $this->descuento->FormValue;
        $this->costo_unidad->CurrentValue = $this->costo_unidad->FormValue;
        $this->costo->CurrentValue = $this->costo->FormValue;
        $this->precio_unidad->CurrentValue = $this->precio_unidad->FormValue;
        $this->precio->CurrentValue = $this->precio->FormValue;
        $this->check_ne->CurrentValue = $this->check_ne->FormValue;
        $this->newdata->CurrentValue = $this->newdata->FormValue;
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->almacen->setDbValue($row['almacen']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->cantidad_articulo->setDbValue($row['cantidad_articulo']);
        $this->articulo_unidad_medida->setDbValue($row['articulo_unidad_medida']);
        $this->cantidad_unidad_medida->setDbValue($row['cantidad_unidad_medida']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->precio_unidad_sin_desc->setDbValue($row['precio_unidad_sin_desc']);
        $this->descuento->setDbValue($row['descuento']);
        $this->costo_unidad->setDbValue($row['costo_unidad']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_unidad->setDbValue($row['precio_unidad']);
        $this->precio->setDbValue($row['precio']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->cantidad_movimiento_consignacion->setDbValue($row['cantidad_movimiento_consignacion']);
        $this->id_consignacion->setDbValue($row['id_consignacion']);
        $this->check_ne->setDbValue($row['check_ne']);
        $this->packer_cantidad->setDbValue($row['packer_cantidad']);
        $this->newdata->setDbValue($row['newdata']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['id_documento'] = $this->id_documento->DefaultValue;
        $row['fabricante'] = $this->fabricante->DefaultValue;
        $row['articulo'] = $this->articulo->DefaultValue;
        $row['lote'] = $this->lote->DefaultValue;
        $row['fecha_vencimiento'] = $this->fecha_vencimiento->DefaultValue;
        $row['almacen'] = $this->almacen->DefaultValue;
        $row['id_compra'] = $this->id_compra->DefaultValue;
        $row['cantidad_articulo'] = $this->cantidad_articulo->DefaultValue;
        $row['articulo_unidad_medida'] = $this->articulo_unidad_medida->DefaultValue;
        $row['cantidad_unidad_medida'] = $this->cantidad_unidad_medida->DefaultValue;
        $row['cantidad_movimiento'] = $this->cantidad_movimiento->DefaultValue;
        $row['precio_unidad_sin_desc'] = $this->precio_unidad_sin_desc->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['costo_unidad'] = $this->costo_unidad->DefaultValue;
        $row['costo'] = $this->costo->DefaultValue;
        $row['precio_unidad'] = $this->precio_unidad->DefaultValue;
        $row['precio'] = $this->precio->DefaultValue;
        $row['alicuota'] = $this->alicuota->DefaultValue;
        $row['cantidad_movimiento_consignacion'] = $this->cantidad_movimiento_consignacion->DefaultValue;
        $row['id_consignacion'] = $this->id_consignacion->DefaultValue;
        $row['check_ne'] = $this->check_ne->DefaultValue;
        $row['packer_cantidad'] = $this->packer_cantidad->DefaultValue;
        $row['newdata'] = $this->newdata->DefaultValue;
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

        // tipo_documento

        // id_documento

        // fabricante

        // articulo

        // lote

        // fecha_vencimiento

        // almacen

        // id_compra

        // cantidad_articulo

        // articulo_unidad_medida

        // cantidad_unidad_medida

        // cantidad_movimiento

        // precio_unidad_sin_desc

        // descuento

        // costo_unidad

        // costo

        // precio_unidad

        // precio

        // alicuota

        // cantidad_movimiento_consignacion

        // id_consignacion

        // check_ne

        // packer_cantidad

        // newdata

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
            $curVal = strval($this->fabricante->CurrentValue);
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->fabricante->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $curVal, $this->fabricante->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                    $lookupFilter = $this->fabricante->getSelectFilter($this); // PHP
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
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

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;

            // lote
            $this->lote->ViewValue = $this->lote->CurrentValue;

            // fecha_vencimiento
            $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
            $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

            // almacen
            $curVal = strval($this->almacen->CurrentValue);
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
                if ($this->almacen->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->almacen->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->almacen->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                    $sqlWrk = $this->almacen->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->almacen->Lookup->renderViewRow($rswrk[0]);
                        $this->almacen->ViewValue = $this->almacen->displayValue($arwrk);
                    } else {
                        $this->almacen->ViewValue = $this->almacen->CurrentValue;
                    }
                }
            } else {
                $this->almacen->ViewValue = null;
            }

            // id_compra
            $this->id_compra->ViewValue = $this->id_compra->CurrentValue;

            // cantidad_articulo
            $this->cantidad_articulo->ViewValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->ViewValue = FormatNumber($this->cantidad_articulo->ViewValue, $this->cantidad_articulo->formatPattern());

            // articulo_unidad_medida
            if (strval($this->articulo_unidad_medida->CurrentValue) != "") {
                $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->optionCaption($this->articulo_unidad_medida->CurrentValue);
            } else {
                $this->articulo_unidad_medida->ViewValue = null;
            }

            // cantidad_unidad_medida
            $this->cantidad_unidad_medida->ViewValue = $this->cantidad_unidad_medida->CurrentValue;

            // cantidad_movimiento
            $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->formatPattern());

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->ViewValue = $this->precio_unidad_sin_desc->CurrentValue;
            $this->precio_unidad_sin_desc->ViewValue = FormatNumber($this->precio_unidad_sin_desc->ViewValue, $this->precio_unidad_sin_desc->formatPattern());

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // costo_unidad
            $this->costo_unidad->ViewValue = $this->costo_unidad->CurrentValue;
            $this->costo_unidad->ViewValue = FormatNumber($this->costo_unidad->ViewValue, $this->costo_unidad->formatPattern());

            // costo
            $this->costo->ViewValue = $this->costo->CurrentValue;
            $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, $this->costo->formatPattern());

            // precio_unidad
            $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
            $this->precio_unidad->ViewValue = FormatNumber($this->precio_unidad->ViewValue, $this->precio_unidad->formatPattern());

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->formatPattern());

            // alicuota
            $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
            $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->formatPattern());

            // cantidad_movimiento_consignacion
            $this->cantidad_movimiento_consignacion->ViewValue = $this->cantidad_movimiento_consignacion->CurrentValue;
            $this->cantidad_movimiento_consignacion->ViewValue = FormatNumber($this->cantidad_movimiento_consignacion->ViewValue, $this->cantidad_movimiento_consignacion->formatPattern());

            // id_consignacion
            $this->id_consignacion->ViewValue = $this->id_consignacion->CurrentValue;

            // check_ne
            if (strval($this->check_ne->CurrentValue) != "") {
                $this->check_ne->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->check_ne->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->check_ne->ViewValue->add($this->check_ne->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->check_ne->ViewValue = null;
            }
            $this->check_ne->CssClass = "fw-bold fst-italic";

            // packer_cantidad
            $this->packer_cantidad->ViewValue = $this->packer_cantidad->CurrentValue;
            $this->packer_cantidad->ViewValue = FormatNumber($this->packer_cantidad->ViewValue, $this->packer_cantidad->formatPattern());

            // newdata
            if (strval($this->newdata->CurrentValue) != "") {
                $this->newdata->ViewValue = $this->newdata->optionCaption($this->newdata->CurrentValue);
            } else {
                $this->newdata->ViewValue = null;
            }

            // articulo
            $this->articulo->HrefValue = "";
            $this->articulo->TooltipValue = "";

            // lote
            $this->lote->HrefValue = "";
            $this->lote->TooltipValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->HrefValue = "";
            $this->fecha_vencimiento->TooltipValue = "";

            // almacen
            $this->almacen->HrefValue = "";
            $this->almacen->TooltipValue = "";

            // id_compra
            $this->id_compra->HrefValue = "";
            $this->id_compra->TooltipValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->HrefValue = "";
            $this->cantidad_articulo->TooltipValue = "";

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->HrefValue = "";
            $this->precio_unidad_sin_desc->TooltipValue = "";

            // descuento
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // costo_unidad
            $this->costo_unidad->HrefValue = "";
            $this->costo_unidad->TooltipValue = "";

            // costo
            $this->costo->HrefValue = "";
            $this->costo->TooltipValue = "";

            // precio_unidad
            $this->precio_unidad->HrefValue = "";
            $this->precio_unidad->TooltipValue = "";

            // precio
            $this->precio->HrefValue = "";
            $this->precio->TooltipValue = "";

            // check_ne
            $this->check_ne->HrefValue = "";
            $this->check_ne->TooltipValue = "";

            // newdata
            $this->newdata->HrefValue = "";
            $this->newdata->TooltipValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // articulo
            $this->articulo->setupEditAttributes();
            $this->articulo->EditValue = $this->articulo->CurrentValue;
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());
            if (strval($this->articulo->EditValue) != "" && is_numeric($this->articulo->EditValue)) {
                $this->articulo->EditValue = $this->articulo->EditValue;
            }

            // lote
            $this->lote->setupEditAttributes();
            if (!$this->lote->Raw) {
                $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
            }
            $this->lote->EditValue = HtmlEncode($this->lote->CurrentValue);
            $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

            // fecha_vencimiento
            $this->fecha_vencimiento->setupEditAttributes();
            $this->fecha_vencimiento->EditValue = HtmlEncode(FormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern()));
            $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

            // almacen
            $this->almacen->setupEditAttributes();
            $curVal = trim(strval($this->almacen->CurrentValue));
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
            } else {
                $this->almacen->ViewValue = $this->almacen->Lookup !== null && is_array($this->almacen->lookupOptions()) && count($this->almacen->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->almacen->ViewValue !== null) { // Load from cache
                $this->almacen->EditValue = array_values($this->almacen->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->almacen->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->almacen->CurrentValue, $this->almacen->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $sqlWrk = $this->almacen->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->almacen->EditValue = $arwrk;
            }
            $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

            // id_compra
            $this->id_compra->setupEditAttributes();
            $this->id_compra->EditValue = $this->id_compra->CurrentValue;
            $this->id_compra->PlaceHolder = RemoveHtml($this->id_compra->caption());
            if (strval($this->id_compra->EditValue) != "" && is_numeric($this->id_compra->EditValue)) {
                $this->id_compra->EditValue = $this->id_compra->EditValue;
            }

            // cantidad_articulo
            $this->cantidad_articulo->setupEditAttributes();
            $this->cantidad_articulo->EditValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, null);
            }

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->setupEditAttributes();
            $this->precio_unidad_sin_desc->EditValue = $this->precio_unidad_sin_desc->CurrentValue;
            $this->precio_unidad_sin_desc->PlaceHolder = RemoveHtml($this->precio_unidad_sin_desc->caption());
            if (strval($this->precio_unidad_sin_desc->EditValue) != "" && is_numeric($this->precio_unidad_sin_desc->EditValue)) {
                $this->precio_unidad_sin_desc->EditValue = FormatNumber($this->precio_unidad_sin_desc->EditValue, null);
            }

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->CurrentValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
            }

            // costo_unidad
            $this->costo_unidad->setupEditAttributes();
            $this->costo_unidad->EditValue = $this->costo_unidad->CurrentValue;
            $this->costo_unidad->PlaceHolder = RemoveHtml($this->costo_unidad->caption());
            if (strval($this->costo_unidad->EditValue) != "" && is_numeric($this->costo_unidad->EditValue)) {
                $this->costo_unidad->EditValue = FormatNumber($this->costo_unidad->EditValue, null);
            }

            // costo
            $this->costo->setupEditAttributes();
            $this->costo->EditValue = $this->costo->CurrentValue;
            $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
            if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
                $this->costo->EditValue = FormatNumber($this->costo->EditValue, null);
            }

            // precio_unidad
            $this->precio_unidad->setupEditAttributes();
            $this->precio_unidad->EditValue = $this->precio_unidad->CurrentValue;
            $this->precio_unidad->PlaceHolder = RemoveHtml($this->precio_unidad->caption());
            if (strval($this->precio_unidad->EditValue) != "" && is_numeric($this->precio_unidad->EditValue)) {
                $this->precio_unidad->EditValue = FormatNumber($this->precio_unidad->EditValue, null);
            }

            // precio
            $this->precio->setupEditAttributes();
            $this->precio->EditValue = $this->precio->CurrentValue;
            $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
            if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
                $this->precio->EditValue = FormatNumber($this->precio->EditValue, null);
            }

            // check_ne
            $this->check_ne->EditValue = $this->check_ne->options(false);
            $this->check_ne->PlaceHolder = RemoveHtml($this->check_ne->caption());

            // newdata
            $this->newdata->EditValue = $this->newdata->options(false);
            $this->newdata->PlaceHolder = RemoveHtml($this->newdata->caption());

            // Add refer script

            // articulo
            $this->articulo->HrefValue = "";

            // lote
            $this->lote->HrefValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->HrefValue = "";

            // almacen
            $this->almacen->HrefValue = "";

            // id_compra
            $this->id_compra->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->HrefValue = "";

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";

            // costo_unidad
            $this->costo_unidad->HrefValue = "";

            // costo
            $this->costo->HrefValue = "";

            // precio_unidad
            $this->precio_unidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

            // check_ne
            $this->check_ne->HrefValue = "";

            // newdata
            $this->newdata->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // articulo
            $this->articulo->setupEditAttributes();
            $this->articulo->EditValue = $this->articulo->CurrentValue;
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());
            if (strval($this->articulo->EditValue) != "" && is_numeric($this->articulo->EditValue)) {
                $this->articulo->EditValue = $this->articulo->EditValue;
            }

            // lote
            $this->lote->setupEditAttributes();
            if (!$this->lote->Raw) {
                $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
            }
            $this->lote->EditValue = HtmlEncode($this->lote->CurrentValue);
            $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

            // fecha_vencimiento
            $this->fecha_vencimiento->setupEditAttributes();
            $this->fecha_vencimiento->EditValue = HtmlEncode(FormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern()));
            $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

            // almacen
            $this->almacen->setupEditAttributes();
            $curVal = trim(strval($this->almacen->CurrentValue));
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
            } else {
                $this->almacen->ViewValue = $this->almacen->Lookup !== null && is_array($this->almacen->lookupOptions()) && count($this->almacen->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->almacen->ViewValue !== null) { // Load from cache
                $this->almacen->EditValue = array_values($this->almacen->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->almacen->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->almacen->CurrentValue, $this->almacen->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $sqlWrk = $this->almacen->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->almacen->EditValue = $arwrk;
            }
            $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

            // id_compra
            $this->id_compra->setupEditAttributes();
            $this->id_compra->EditValue = $this->id_compra->CurrentValue;
            $this->id_compra->PlaceHolder = RemoveHtml($this->id_compra->caption());
            if (strval($this->id_compra->EditValue) != "" && is_numeric($this->id_compra->EditValue)) {
                $this->id_compra->EditValue = $this->id_compra->EditValue;
            }

            // cantidad_articulo
            $this->cantidad_articulo->setupEditAttributes();
            $this->cantidad_articulo->EditValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, null);
            }

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->setupEditAttributes();
            $this->precio_unidad_sin_desc->EditValue = $this->precio_unidad_sin_desc->CurrentValue;
            $this->precio_unidad_sin_desc->PlaceHolder = RemoveHtml($this->precio_unidad_sin_desc->caption());
            if (strval($this->precio_unidad_sin_desc->EditValue) != "" && is_numeric($this->precio_unidad_sin_desc->EditValue)) {
                $this->precio_unidad_sin_desc->EditValue = FormatNumber($this->precio_unidad_sin_desc->EditValue, null);
            }

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->CurrentValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
            }

            // costo_unidad
            $this->costo_unidad->setupEditAttributes();
            $this->costo_unidad->EditValue = $this->costo_unidad->CurrentValue;
            $this->costo_unidad->PlaceHolder = RemoveHtml($this->costo_unidad->caption());
            if (strval($this->costo_unidad->EditValue) != "" && is_numeric($this->costo_unidad->EditValue)) {
                $this->costo_unidad->EditValue = FormatNumber($this->costo_unidad->EditValue, null);
            }

            // costo
            $this->costo->setupEditAttributes();
            $this->costo->EditValue = $this->costo->CurrentValue;
            $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
            if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
                $this->costo->EditValue = FormatNumber($this->costo->EditValue, null);
            }

            // precio_unidad
            $this->precio_unidad->setupEditAttributes();
            $this->precio_unidad->EditValue = $this->precio_unidad->CurrentValue;
            $this->precio_unidad->PlaceHolder = RemoveHtml($this->precio_unidad->caption());
            if (strval($this->precio_unidad->EditValue) != "" && is_numeric($this->precio_unidad->EditValue)) {
                $this->precio_unidad->EditValue = FormatNumber($this->precio_unidad->EditValue, null);
            }

            // precio
            $this->precio->setupEditAttributes();
            $this->precio->EditValue = $this->precio->CurrentValue;
            $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
            if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
                $this->precio->EditValue = FormatNumber($this->precio->EditValue, null);
            }

            // check_ne
            $this->check_ne->setupEditAttributes();
            if (strval($this->check_ne->CurrentValue) != "") {
                $this->check_ne->EditValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->check_ne->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->check_ne->EditValue->add($this->check_ne->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->check_ne->EditValue = null;
            }
            $this->check_ne->CssClass = "fw-bold fst-italic";

            // newdata
            $this->newdata->EditValue = $this->newdata->options(false);
            $this->newdata->PlaceHolder = RemoveHtml($this->newdata->caption());

            // Edit refer script

            // articulo
            $this->articulo->HrefValue = "";

            // lote
            $this->lote->HrefValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->HrefValue = "";

            // almacen
            $this->almacen->HrefValue = "";

            // id_compra
            $this->id_compra->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->HrefValue = "";

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";

            // costo_unidad
            $this->costo_unidad->HrefValue = "";

            // costo
            $this->costo->HrefValue = "";

            // precio_unidad
            $this->precio_unidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

            // check_ne
            $this->check_ne->HrefValue = "";
            $this->check_ne->TooltipValue = "";

            // newdata
            $this->newdata->HrefValue = "";
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
            if ($this->articulo->Visible && $this->articulo->Required) {
                if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                    $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->articulo->FormValue)) {
                $this->articulo->addErrorMessage($this->articulo->getErrorMessage(false));
            }
            if ($this->lote->Visible && $this->lote->Required) {
                if (!$this->lote->IsDetailKey && EmptyValue($this->lote->FormValue)) {
                    $this->lote->addErrorMessage(str_replace("%s", $this->lote->caption(), $this->lote->RequiredErrorMessage));
                }
            }
            if ($this->fecha_vencimiento->Visible && $this->fecha_vencimiento->Required) {
                if (!$this->fecha_vencimiento->IsDetailKey && EmptyValue($this->fecha_vencimiento->FormValue)) {
                    $this->fecha_vencimiento->addErrorMessage(str_replace("%s", $this->fecha_vencimiento->caption(), $this->fecha_vencimiento->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecha_vencimiento->FormValue, $this->fecha_vencimiento->formatPattern())) {
                $this->fecha_vencimiento->addErrorMessage($this->fecha_vencimiento->getErrorMessage(false));
            }
            if ($this->almacen->Visible && $this->almacen->Required) {
                if (!$this->almacen->IsDetailKey && EmptyValue($this->almacen->FormValue)) {
                    $this->almacen->addErrorMessage(str_replace("%s", $this->almacen->caption(), $this->almacen->RequiredErrorMessage));
                }
            }
            if ($this->id_compra->Visible && $this->id_compra->Required) {
                if (!$this->id_compra->IsDetailKey && EmptyValue($this->id_compra->FormValue)) {
                    $this->id_compra->addErrorMessage(str_replace("%s", $this->id_compra->caption(), $this->id_compra->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->id_compra->FormValue)) {
                $this->id_compra->addErrorMessage($this->id_compra->getErrorMessage(false));
            }
            if ($this->cantidad_articulo->Visible && $this->cantidad_articulo->Required) {
                if (!$this->cantidad_articulo->IsDetailKey && EmptyValue($this->cantidad_articulo->FormValue)) {
                    $this->cantidad_articulo->addErrorMessage(str_replace("%s", $this->cantidad_articulo->caption(), $this->cantidad_articulo->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_articulo->FormValue)) {
                $this->cantidad_articulo->addErrorMessage($this->cantidad_articulo->getErrorMessage(false));
            }
            if ($this->precio_unidad_sin_desc->Visible && $this->precio_unidad_sin_desc->Required) {
                if (!$this->precio_unidad_sin_desc->IsDetailKey && EmptyValue($this->precio_unidad_sin_desc->FormValue)) {
                    $this->precio_unidad_sin_desc->addErrorMessage(str_replace("%s", $this->precio_unidad_sin_desc->caption(), $this->precio_unidad_sin_desc->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->precio_unidad_sin_desc->FormValue)) {
                $this->precio_unidad_sin_desc->addErrorMessage($this->precio_unidad_sin_desc->getErrorMessage(false));
            }
            if ($this->descuento->Visible && $this->descuento->Required) {
                if (!$this->descuento->IsDetailKey && EmptyValue($this->descuento->FormValue)) {
                    $this->descuento->addErrorMessage(str_replace("%s", $this->descuento->caption(), $this->descuento->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->descuento->FormValue)) {
                $this->descuento->addErrorMessage($this->descuento->getErrorMessage(false));
            }
            if ($this->costo_unidad->Visible && $this->costo_unidad->Required) {
                if (!$this->costo_unidad->IsDetailKey && EmptyValue($this->costo_unidad->FormValue)) {
                    $this->costo_unidad->addErrorMessage(str_replace("%s", $this->costo_unidad->caption(), $this->costo_unidad->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->costo_unidad->FormValue)) {
                $this->costo_unidad->addErrorMessage($this->costo_unidad->getErrorMessage(false));
            }
            if ($this->costo->Visible && $this->costo->Required) {
                if (!$this->costo->IsDetailKey && EmptyValue($this->costo->FormValue)) {
                    $this->costo->addErrorMessage(str_replace("%s", $this->costo->caption(), $this->costo->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->costo->FormValue)) {
                $this->costo->addErrorMessage($this->costo->getErrorMessage(false));
            }
            if ($this->precio_unidad->Visible && $this->precio_unidad->Required) {
                if (!$this->precio_unidad->IsDetailKey && EmptyValue($this->precio_unidad->FormValue)) {
                    $this->precio_unidad->addErrorMessage(str_replace("%s", $this->precio_unidad->caption(), $this->precio_unidad->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->precio_unidad->FormValue)) {
                $this->precio_unidad->addErrorMessage($this->precio_unidad->getErrorMessage(false));
            }
            if ($this->precio->Visible && $this->precio->Required) {
                if (!$this->precio->IsDetailKey && EmptyValue($this->precio->FormValue)) {
                    $this->precio->addErrorMessage(str_replace("%s", $this->precio->caption(), $this->precio->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->precio->FormValue)) {
                $this->precio->addErrorMessage($this->precio->getErrorMessage(false));
            }
            if ($this->check_ne->Visible && $this->check_ne->Required) {
                if ($this->check_ne->FormValue == "") {
                    $this->check_ne->addErrorMessage(str_replace("%s", $this->check_ne->caption(), $this->check_ne->RequiredErrorMessage));
                }
            }
            if ($this->newdata->Visible && $this->newdata->Required) {
                if ($this->newdata->FormValue == "") {
                    $this->newdata->addErrorMessage(str_replace("%s", $this->newdata->caption(), $this->newdata->RequiredErrorMessage));
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
        if ($this->AuditTrailOnDelete) {
            $this->writeAuditTrailDummy($Language->phrase("BatchDeleteBegin")); // Batch delete begin
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

        // articulo
        $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, $this->articulo->ReadOnly);

        // lote
        $this->lote->setDbValueDef($rsnew, $this->lote->CurrentValue, $this->lote->ReadOnly);

        // fecha_vencimiento
        $this->fecha_vencimiento->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern()), $this->fecha_vencimiento->ReadOnly);

        // almacen
        $this->almacen->setDbValueDef($rsnew, $this->almacen->CurrentValue, $this->almacen->ReadOnly);

        // id_compra
        $this->id_compra->setDbValueDef($rsnew, $this->id_compra->CurrentValue, $this->id_compra->ReadOnly);

        // cantidad_articulo
        $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, $this->cantidad_articulo->ReadOnly);

        // precio_unidad_sin_desc
        $this->precio_unidad_sin_desc->setDbValueDef($rsnew, $this->precio_unidad_sin_desc->CurrentValue, $this->precio_unidad_sin_desc->ReadOnly);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, $this->descuento->ReadOnly);

        // costo_unidad
        $this->costo_unidad->setDbValueDef($rsnew, $this->costo_unidad->CurrentValue, $this->costo_unidad->ReadOnly);

        // costo
        $this->costo->setDbValueDef($rsnew, $this->costo->CurrentValue, $this->costo->ReadOnly);

        // precio_unidad
        $this->precio_unidad->setDbValueDef($rsnew, $this->precio_unidad->CurrentValue, $this->precio_unidad->ReadOnly);

        // precio
        $this->precio->setDbValueDef($rsnew, $this->precio->CurrentValue, $this->precio->ReadOnly);

        // newdata
        $this->newdata->setDbValueDef($rsnew, $this->newdata->CurrentValue, $this->newdata->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['articulo'])) { // articulo
            $this->articulo->CurrentValue = $row['articulo'];
        }
        if (isset($row['lote'])) { // lote
            $this->lote->CurrentValue = $row['lote'];
        }
        if (isset($row['fecha_vencimiento'])) { // fecha_vencimiento
            $this->fecha_vencimiento->CurrentValue = $row['fecha_vencimiento'];
        }
        if (isset($row['almacen'])) { // almacen
            $this->almacen->CurrentValue = $row['almacen'];
        }
        if (isset($row['id_compra'])) { // id_compra
            $this->id_compra->CurrentValue = $row['id_compra'];
        }
        if (isset($row['cantidad_articulo'])) { // cantidad_articulo
            $this->cantidad_articulo->CurrentValue = $row['cantidad_articulo'];
        }
        if (isset($row['precio_unidad_sin_desc'])) { // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->CurrentValue = $row['precio_unidad_sin_desc'];
        }
        if (isset($row['descuento'])) { // descuento
            $this->descuento->CurrentValue = $row['descuento'];
        }
        if (isset($row['costo_unidad'])) { // costo_unidad
            $this->costo_unidad->CurrentValue = $row['costo_unidad'];
        }
        if (isset($row['costo'])) { // costo
            $this->costo->CurrentValue = $row['costo'];
        }
        if (isset($row['precio_unidad'])) { // precio_unidad
            $this->precio_unidad->CurrentValue = $row['precio_unidad'];
        }
        if (isset($row['precio'])) { // precio
            $this->precio->CurrentValue = $row['precio'];
        }
        if (isset($row['newdata'])) { // newdata
            $this->newdata->CurrentValue = $row['newdata'];
        }
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "entradas") {
            $this->tipo_documento->Visible = true; // Need to insert foreign key
            $this->tipo_documento->CurrentValue = $this->tipo_documento->getSessionValue();
            $this->id_documento->Visible = true; // Need to insert foreign key
            $this->id_documento->CurrentValue = $this->id_documento->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "salidas") {
            $this->tipo_documento->Visible = true; // Need to insert foreign key
            $this->tipo_documento->CurrentValue = $this->tipo_documento->getSessionValue();
            $this->id_documento->Visible = true; // Need to insert foreign key
            $this->id_documento->CurrentValue = $this->id_documento->getSessionValue();
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

        // articulo
        $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, false);

        // lote
        $this->lote->setDbValueDef($rsnew, $this->lote->CurrentValue, false);

        // fecha_vencimiento
        $this->fecha_vencimiento->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern()), false);

        // almacen
        $this->almacen->setDbValueDef($rsnew, $this->almacen->CurrentValue, false);

        // id_compra
        $this->id_compra->setDbValueDef($rsnew, $this->id_compra->CurrentValue, false);

        // cantidad_articulo
        $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, false);

        // precio_unidad_sin_desc
        $this->precio_unidad_sin_desc->setDbValueDef($rsnew, $this->precio_unidad_sin_desc->CurrentValue, false);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, false);

        // costo_unidad
        $this->costo_unidad->setDbValueDef($rsnew, $this->costo_unidad->CurrentValue, false);

        // costo
        $this->costo->setDbValueDef($rsnew, $this->costo->CurrentValue, false);

        // precio_unidad
        $this->precio_unidad->setDbValueDef($rsnew, $this->precio_unidad->CurrentValue, false);

        // precio
        $this->precio->setDbValueDef($rsnew, $this->precio->CurrentValue, false);

        // check_ne
        $this->check_ne->setDbValueDef($rsnew, $this->check_ne->CurrentValue, strval($this->check_ne->CurrentValue) == "");

        // newdata
        $this->newdata->setDbValueDef($rsnew, $this->newdata->CurrentValue, strval($this->newdata->CurrentValue) == "");

        // tipo_documento
        if ($this->tipo_documento->getSessionValue() != "") {
            $rsnew['tipo_documento'] = $this->tipo_documento->getSessionValue();
        }

        // id_documento
        if ($this->id_documento->getSessionValue() != "") {
            $rsnew['id_documento'] = $this->id_documento->getSessionValue();
        }
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['articulo'])) { // articulo
            $this->articulo->setFormValue($row['articulo']);
        }
        if (isset($row['lote'])) { // lote
            $this->lote->setFormValue($row['lote']);
        }
        if (isset($row['fecha_vencimiento'])) { // fecha_vencimiento
            $this->fecha_vencimiento->setFormValue($row['fecha_vencimiento']);
        }
        if (isset($row['almacen'])) { // almacen
            $this->almacen->setFormValue($row['almacen']);
        }
        if (isset($row['id_compra'])) { // id_compra
            $this->id_compra->setFormValue($row['id_compra']);
        }
        if (isset($row['cantidad_articulo'])) { // cantidad_articulo
            $this->cantidad_articulo->setFormValue($row['cantidad_articulo']);
        }
        if (isset($row['precio_unidad_sin_desc'])) { // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->setFormValue($row['precio_unidad_sin_desc']);
        }
        if (isset($row['descuento'])) { // descuento
            $this->descuento->setFormValue($row['descuento']);
        }
        if (isset($row['costo_unidad'])) { // costo_unidad
            $this->costo_unidad->setFormValue($row['costo_unidad']);
        }
        if (isset($row['costo'])) { // costo
            $this->costo->setFormValue($row['costo']);
        }
        if (isset($row['precio_unidad'])) { // precio_unidad
            $this->precio_unidad->setFormValue($row['precio_unidad']);
        }
        if (isset($row['precio'])) { // precio
            $this->precio->setFormValue($row['precio']);
        }
        if (isset($row['check_ne'])) { // check_ne
            $this->check_ne->setFormValue($row['check_ne']);
        }
        if (isset($row['newdata'])) { // newdata
            $this->newdata->setFormValue($row['newdata']);
        }
        if (isset($row['tipo_documento'])) { // tipo_documento
            $this->tipo_documento->setFormValue($row['tipo_documento']);
        }
        if (isset($row['id_documento'])) { // id_documento
            $this->id_documento->setFormValue($row['id_documento']);
        }
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "entradas") {
            $masterTbl = Container("entradas");
            $this->tipo_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
            $this->id_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "salidas") {
            $masterTbl = Container("salidas");
            $this->tipo_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
            $this->id_documento->Visible = false;
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
                case "x_fabricante":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_almacen":
                    break;
                case "x_articulo_unidad_medida":
                    break;
                case "x_cantidad_unidad_medida":
                    break;
                case "x_check_ne":
                    break;
                case "x_newdata":
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
    public function pageRender() {
    	//echo "Page Render";
    	$this->check_ne->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
    	$this->precio_unidad_sin_desc->Visible = FALSE;
    	$this->almacen->Visible = FALSE;
    	$this->packer_cantidad->Visible = FALSE;
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDC":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCNRP":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCFCC":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCAEN":
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		$this->almacen->Visible = TRUE;
    		break;
    	case "TDCPDV":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCNET":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		$this->check_ne->Visible = TRUE;
    		$this->packer_cantidad->Visible = TRUE;
    		break;
    	case "TDCFCV":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		$this->descuento->Visible = TRUE;
    		break;
    	case "TDCASA":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = TRUE;
    		$this->almacen->Visible = FALSE;
    	}
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
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    	if($this->tipo_documento->CurrentValue == "TDCNET") {
    		if(isset($_SESSION["packer"])) {
    			$sql = "SELECT packer, packer_date FROM salidas WHERE id = '" . $this->id_documento->CurrentValue . "'";
    			$row = ExecuteRow($sql);
    			if($_SESSION["packer"] == $row["packer"]) {
    				if($row["packer_date"] == "") {
    					$opt = &$this->ListOptions->Add("packer");
    					$opt->Header = "VF";
    					$opt->OnLeft = FALSE; // Link on left
    					$opt->MoveTo(0); // Move to first column
    				}
    			}
    		}
    	}
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
    	if($this->tipo_documento->CurrentValue == "TDCNET") {
    		if(isset($_SESSION["packer"])) {
    			$sql = "SELECT packer, packer_date FROM salidas WHERE id = '" . $this->id_documento->CurrentValue . "'";
    			$row = ExecuteRow($sql);
    			if($_SESSION["packer"] == $row["packer"]) {
    				if($row["packer_date"] == "") {
    					if(isset($this->id->CurrentValue)) {
    						$xctrl = 'x' . $this->id->CurrentValue . '_packer';
    						$xctrl = '<div class="input-group input-group-sm mb-1"><input type="text" id="' . $xctrl . '" name="' . $xctrl . '" value="' . intval($this->cantidad_articulo->CurrentValue) . '" onchange="js: CambiarCantidad(' . $this->id->CurrentValue . ', this.name, this.value);" size="2" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"></div>';
    						$this->ListOptions->Items["packer"]->Body = $xctrl;
    					}
    				}
    			}
    		}
    	}
    }
}
