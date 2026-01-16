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
class ViewOutTdcpdvList extends ViewOutTdcpdv
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ViewOutTdcpdvList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fview_out_tdcpdvlist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "ViewOutTdcpdvList";

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
        $this->tipo_documento->Visible = false;
        $this->nro_documento->setVisibility();
        $this->fecha->setVisibility();
        $this->nro_control->Visible = false;
        $this->cliente->setVisibility();
        $this->doc_afectado->Visible = false;
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->setVisibility();
        $this->moneda->setVisibility();
        $this->descuento->setVisibility();
        $this->descuento2->setVisibility();
        $this->asesor->Visible = false;
        $this->lista_pedido->setVisibility();
        $this->unidades->setVisibility();
        $this->estatus->Visible = false;
        $this->nota->Visible = false;
        $this->_username->setVisibility();
        $this->id_documento_padre->Visible = false;
        $this->documento->Visible = false;
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->dias_credito->Visible = false;
        $this->entregado->Visible = false;
        $this->fecha_entrega->Visible = false;
        $this->pagado->Visible = false;
        $this->bultos->Visible = false;
        $this->fecha_bultos->Visible = false;
        $this->user_bultos->Visible = false;
        $this->fecha_despacho->Visible = false;
        $this->user_despacho->Visible = false;
        $this->consignacion->Visible = false;
        $this->monto_sin_descuento->Visible = false;
        $this->factura->Visible = false;
        $this->ci_rif->Visible = false;
        $this->nombre->Visible = false;
        $this->direccion->Visible = false;
        $this->telefono->Visible = false;
        $this->_email->Visible = false;
        $this->activo->Visible = false;
        $this->comprobante->Visible = false;
        $this->nro_despacho->Visible = false;
        $this->cerrado->Visible = false;
        $this->asesor_asignado->setVisibility();
        $this->tasa_indexada->Visible = false;
        $this->id_documento_padre_nd->Visible = false;
        $this->archivo_pedido->Visible = false;
        $this->checker->Visible = false;
        $this->checker_date->Visible = false;
        $this->packer->Visible = false;
        $this->packer_date->Visible = false;
        $this->fotos->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'view_out_tdcpdv';
        $this->TableName = 'view_out_tdcpdv';

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

        // Table object (view_out_tdcpdv)
        if (!isset($GLOBALS["view_out_tdcpdv"]) || $GLOBALS["view_out_tdcpdv"]::class == PROJECT_NAMESPACE . "view_out_tdcpdv") {
            $GLOBALS["view_out_tdcpdv"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "ViewOutTdcpdvAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "ViewOutTdcpdvDelete";
        $this->MultiUpdateUrl = "ViewOutTdcpdvUpdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'view_out_tdcpdv');
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
                    $result["view"] = SameString($pageName, "ViewOutTdcpdvView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->asesor);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->estatus);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->documento);
        $this->setupLookupOptions($this->dias_credito);
        $this->setupLookupOptions($this->entregado);
        $this->setupLookupOptions($this->pagado);
        $this->setupLookupOptions($this->user_bultos);
        $this->setupLookupOptions($this->user_despacho);
        $this->setupLookupOptions($this->consignacion);
        $this->setupLookupOptions($this->factura);
        $this->setupLookupOptions($this->activo);
        $this->setupLookupOptions($this->cerrado);
        $this->setupLookupOptions($this->asesor_asignado);

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "fview_out_tdcpdvgrid";
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
        $filterList = Concat($filterList, $this->tipo_documento->AdvancedSearch->toJson(), ","); // Field tipo_documento
        $filterList = Concat($filterList, $this->nro_documento->AdvancedSearch->toJson(), ","); // Field nro_documento
        $filterList = Concat($filterList, $this->fecha->AdvancedSearch->toJson(), ","); // Field fecha
        $filterList = Concat($filterList, $this->nro_control->AdvancedSearch->toJson(), ","); // Field nro_control
        $filterList = Concat($filterList, $this->cliente->AdvancedSearch->toJson(), ","); // Field cliente
        $filterList = Concat($filterList, $this->doc_afectado->AdvancedSearch->toJson(), ","); // Field doc_afectado
        $filterList = Concat($filterList, $this->monto_total->AdvancedSearch->toJson(), ","); // Field monto_total
        $filterList = Concat($filterList, $this->alicuota_iva->AdvancedSearch->toJson(), ","); // Field alicuota_iva
        $filterList = Concat($filterList, $this->iva->AdvancedSearch->toJson(), ","); // Field iva
        $filterList = Concat($filterList, $this->total->AdvancedSearch->toJson(), ","); // Field total
        $filterList = Concat($filterList, $this->moneda->AdvancedSearch->toJson(), ","); // Field moneda
        $filterList = Concat($filterList, $this->descuento->AdvancedSearch->toJson(), ","); // Field descuento
        $filterList = Concat($filterList, $this->descuento2->AdvancedSearch->toJson(), ","); // Field descuento2
        $filterList = Concat($filterList, $this->asesor->AdvancedSearch->toJson(), ","); // Field asesor
        $filterList = Concat($filterList, $this->lista_pedido->AdvancedSearch->toJson(), ","); // Field lista_pedido
        $filterList = Concat($filterList, $this->unidades->AdvancedSearch->toJson(), ","); // Field unidades
        $filterList = Concat($filterList, $this->estatus->AdvancedSearch->toJson(), ","); // Field estatus
        $filterList = Concat($filterList, $this->nota->AdvancedSearch->toJson(), ","); // Field nota
        $filterList = Concat($filterList, $this->_username->AdvancedSearch->toJson(), ","); // Field username
        $filterList = Concat($filterList, $this->id_documento_padre->AdvancedSearch->toJson(), ","); // Field id_documento_padre
        $filterList = Concat($filterList, $this->documento->AdvancedSearch->toJson(), ","); // Field documento
        $filterList = Concat($filterList, $this->tasa_dia->AdvancedSearch->toJson(), ","); // Field tasa_dia
        $filterList = Concat($filterList, $this->monto_usd->AdvancedSearch->toJson(), ","); // Field monto_usd
        $filterList = Concat($filterList, $this->dias_credito->AdvancedSearch->toJson(), ","); // Field dias_credito
        $filterList = Concat($filterList, $this->entregado->AdvancedSearch->toJson(), ","); // Field entregado
        $filterList = Concat($filterList, $this->fecha_entrega->AdvancedSearch->toJson(), ","); // Field fecha_entrega
        $filterList = Concat($filterList, $this->pagado->AdvancedSearch->toJson(), ","); // Field pagado
        $filterList = Concat($filterList, $this->bultos->AdvancedSearch->toJson(), ","); // Field bultos
        $filterList = Concat($filterList, $this->fecha_bultos->AdvancedSearch->toJson(), ","); // Field fecha_bultos
        $filterList = Concat($filterList, $this->user_bultos->AdvancedSearch->toJson(), ","); // Field user_bultos
        $filterList = Concat($filterList, $this->fecha_despacho->AdvancedSearch->toJson(), ","); // Field fecha_despacho
        $filterList = Concat($filterList, $this->user_despacho->AdvancedSearch->toJson(), ","); // Field user_despacho
        $filterList = Concat($filterList, $this->consignacion->AdvancedSearch->toJson(), ","); // Field consignacion
        $filterList = Concat($filterList, $this->monto_sin_descuento->AdvancedSearch->toJson(), ","); // Field monto_sin_descuento
        $filterList = Concat($filterList, $this->factura->AdvancedSearch->toJson(), ","); // Field factura
        $filterList = Concat($filterList, $this->ci_rif->AdvancedSearch->toJson(), ","); // Field ci_rif
        $filterList = Concat($filterList, $this->nombre->AdvancedSearch->toJson(), ","); // Field nombre
        $filterList = Concat($filterList, $this->direccion->AdvancedSearch->toJson(), ","); // Field direccion
        $filterList = Concat($filterList, $this->telefono->AdvancedSearch->toJson(), ","); // Field telefono
        $filterList = Concat($filterList, $this->_email->AdvancedSearch->toJson(), ","); // Field email
        $filterList = Concat($filterList, $this->activo->AdvancedSearch->toJson(), ","); // Field activo
        $filterList = Concat($filterList, $this->comprobante->AdvancedSearch->toJson(), ","); // Field comprobante
        $filterList = Concat($filterList, $this->nro_despacho->AdvancedSearch->toJson(), ","); // Field nro_despacho
        $filterList = Concat($filterList, $this->cerrado->AdvancedSearch->toJson(), ","); // Field cerrado
        $filterList = Concat($filterList, $this->asesor_asignado->AdvancedSearch->toJson(), ","); // Field asesor_asignado
        $filterList = Concat($filterList, $this->tasa_indexada->AdvancedSearch->toJson(), ","); // Field tasa_indexada
        $filterList = Concat($filterList, $this->id_documento_padre_nd->AdvancedSearch->toJson(), ","); // Field id_documento_padre_nd
        $filterList = Concat($filterList, $this->archivo_pedido->AdvancedSearch->toJson(), ","); // Field archivo_pedido
        $filterList = Concat($filterList, $this->checker->AdvancedSearch->toJson(), ","); // Field checker
        $filterList = Concat($filterList, $this->checker_date->AdvancedSearch->toJson(), ","); // Field checker_date
        $filterList = Concat($filterList, $this->packer->AdvancedSearch->toJson(), ","); // Field packer
        $filterList = Concat($filterList, $this->packer_date->AdvancedSearch->toJson(), ","); // Field packer_date
        $filterList = Concat($filterList, $this->fotos->AdvancedSearch->toJson(), ","); // Field fotos
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
            Profile()->setSearchFilters("fview_out_tdcpdvsrch", $filters);
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

        // Field tipo_documento
        $this->tipo_documento->AdvancedSearch->SearchValue = @$filter["x_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator = @$filter["z_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchCondition = @$filter["v_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchValue2 = @$filter["y_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->save();

        // Field nro_documento
        $this->nro_documento->AdvancedSearch->SearchValue = @$filter["x_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchOperator = @$filter["z_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchCondition = @$filter["v_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchValue2 = @$filter["y_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchOperator2 = @$filter["w_nro_documento"];
        $this->nro_documento->AdvancedSearch->save();

        // Field fecha
        $this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
        $this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
        $this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
        $this->fecha->AdvancedSearch->save();

        // Field nro_control
        $this->nro_control->AdvancedSearch->SearchValue = @$filter["x_nro_control"];
        $this->nro_control->AdvancedSearch->SearchOperator = @$filter["z_nro_control"];
        $this->nro_control->AdvancedSearch->SearchCondition = @$filter["v_nro_control"];
        $this->nro_control->AdvancedSearch->SearchValue2 = @$filter["y_nro_control"];
        $this->nro_control->AdvancedSearch->SearchOperator2 = @$filter["w_nro_control"];
        $this->nro_control->AdvancedSearch->save();

        // Field cliente
        $this->cliente->AdvancedSearch->SearchValue = @$filter["x_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator = @$filter["z_cliente"];
        $this->cliente->AdvancedSearch->SearchCondition = @$filter["v_cliente"];
        $this->cliente->AdvancedSearch->SearchValue2 = @$filter["y_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator2 = @$filter["w_cliente"];
        $this->cliente->AdvancedSearch->save();

        // Field doc_afectado
        $this->doc_afectado->AdvancedSearch->SearchValue = @$filter["x_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator = @$filter["z_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchCondition = @$filter["v_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchValue2 = @$filter["y_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator2 = @$filter["w_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->save();

        // Field monto_total
        $this->monto_total->AdvancedSearch->SearchValue = @$filter["x_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator = @$filter["z_monto_total"];
        $this->monto_total->AdvancedSearch->SearchCondition = @$filter["v_monto_total"];
        $this->monto_total->AdvancedSearch->SearchValue2 = @$filter["y_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator2 = @$filter["w_monto_total"];
        $this->monto_total->AdvancedSearch->save();

        // Field alicuota_iva
        $this->alicuota_iva->AdvancedSearch->SearchValue = @$filter["x_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchOperator = @$filter["z_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchCondition = @$filter["v_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchValue2 = @$filter["y_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchOperator2 = @$filter["w_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->save();

        // Field iva
        $this->iva->AdvancedSearch->SearchValue = @$filter["x_iva"];
        $this->iva->AdvancedSearch->SearchOperator = @$filter["z_iva"];
        $this->iva->AdvancedSearch->SearchCondition = @$filter["v_iva"];
        $this->iva->AdvancedSearch->SearchValue2 = @$filter["y_iva"];
        $this->iva->AdvancedSearch->SearchOperator2 = @$filter["w_iva"];
        $this->iva->AdvancedSearch->save();

        // Field total
        $this->total->AdvancedSearch->SearchValue = @$filter["x_total"];
        $this->total->AdvancedSearch->SearchOperator = @$filter["z_total"];
        $this->total->AdvancedSearch->SearchCondition = @$filter["v_total"];
        $this->total->AdvancedSearch->SearchValue2 = @$filter["y_total"];
        $this->total->AdvancedSearch->SearchOperator2 = @$filter["w_total"];
        $this->total->AdvancedSearch->save();

        // Field moneda
        $this->moneda->AdvancedSearch->SearchValue = @$filter["x_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator = @$filter["z_moneda"];
        $this->moneda->AdvancedSearch->SearchCondition = @$filter["v_moneda"];
        $this->moneda->AdvancedSearch->SearchValue2 = @$filter["y_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator2 = @$filter["w_moneda"];
        $this->moneda->AdvancedSearch->save();

        // Field descuento
        $this->descuento->AdvancedSearch->SearchValue = @$filter["x_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator = @$filter["z_descuento"];
        $this->descuento->AdvancedSearch->SearchCondition = @$filter["v_descuento"];
        $this->descuento->AdvancedSearch->SearchValue2 = @$filter["y_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator2 = @$filter["w_descuento"];
        $this->descuento->AdvancedSearch->save();

        // Field descuento2
        $this->descuento2->AdvancedSearch->SearchValue = @$filter["x_descuento2"];
        $this->descuento2->AdvancedSearch->SearchOperator = @$filter["z_descuento2"];
        $this->descuento2->AdvancedSearch->SearchCondition = @$filter["v_descuento2"];
        $this->descuento2->AdvancedSearch->SearchValue2 = @$filter["y_descuento2"];
        $this->descuento2->AdvancedSearch->SearchOperator2 = @$filter["w_descuento2"];
        $this->descuento2->AdvancedSearch->save();

        // Field asesor
        $this->asesor->AdvancedSearch->SearchValue = @$filter["x_asesor"];
        $this->asesor->AdvancedSearch->SearchOperator = @$filter["z_asesor"];
        $this->asesor->AdvancedSearch->SearchCondition = @$filter["v_asesor"];
        $this->asesor->AdvancedSearch->SearchValue2 = @$filter["y_asesor"];
        $this->asesor->AdvancedSearch->SearchOperator2 = @$filter["w_asesor"];
        $this->asesor->AdvancedSearch->save();

        // Field lista_pedido
        $this->lista_pedido->AdvancedSearch->SearchValue = @$filter["x_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator = @$filter["z_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchCondition = @$filter["v_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchValue2 = @$filter["y_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->save();

        // Field unidades
        $this->unidades->AdvancedSearch->SearchValue = @$filter["x_unidades"];
        $this->unidades->AdvancedSearch->SearchOperator = @$filter["z_unidades"];
        $this->unidades->AdvancedSearch->SearchCondition = @$filter["v_unidades"];
        $this->unidades->AdvancedSearch->SearchValue2 = @$filter["y_unidades"];
        $this->unidades->AdvancedSearch->SearchOperator2 = @$filter["w_unidades"];
        $this->unidades->AdvancedSearch->save();

        // Field estatus
        $this->estatus->AdvancedSearch->SearchValue = @$filter["x_estatus"];
        $this->estatus->AdvancedSearch->SearchOperator = @$filter["z_estatus"];
        $this->estatus->AdvancedSearch->SearchCondition = @$filter["v_estatus"];
        $this->estatus->AdvancedSearch->SearchValue2 = @$filter["y_estatus"];
        $this->estatus->AdvancedSearch->SearchOperator2 = @$filter["w_estatus"];
        $this->estatus->AdvancedSearch->save();

        // Field nota
        $this->nota->AdvancedSearch->SearchValue = @$filter["x_nota"];
        $this->nota->AdvancedSearch->SearchOperator = @$filter["z_nota"];
        $this->nota->AdvancedSearch->SearchCondition = @$filter["v_nota"];
        $this->nota->AdvancedSearch->SearchValue2 = @$filter["y_nota"];
        $this->nota->AdvancedSearch->SearchOperator2 = @$filter["w_nota"];
        $this->nota->AdvancedSearch->save();

        // Field username
        $this->_username->AdvancedSearch->SearchValue = @$filter["x__username"];
        $this->_username->AdvancedSearch->SearchOperator = @$filter["z__username"];
        $this->_username->AdvancedSearch->SearchCondition = @$filter["v__username"];
        $this->_username->AdvancedSearch->SearchValue2 = @$filter["y__username"];
        $this->_username->AdvancedSearch->SearchOperator2 = @$filter["w__username"];
        $this->_username->AdvancedSearch->save();

        // Field id_documento_padre
        $this->id_documento_padre->AdvancedSearch->SearchValue = @$filter["x_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchOperator = @$filter["z_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchCondition = @$filter["v_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchValue2 = @$filter["y_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchOperator2 = @$filter["w_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->save();

        // Field documento
        $this->documento->AdvancedSearch->SearchValue = @$filter["x_documento"];
        $this->documento->AdvancedSearch->SearchOperator = @$filter["z_documento"];
        $this->documento->AdvancedSearch->SearchCondition = @$filter["v_documento"];
        $this->documento->AdvancedSearch->SearchValue2 = @$filter["y_documento"];
        $this->documento->AdvancedSearch->SearchOperator2 = @$filter["w_documento"];
        $this->documento->AdvancedSearch->save();

        // Field tasa_dia
        $this->tasa_dia->AdvancedSearch->SearchValue = @$filter["x_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchOperator = @$filter["z_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchCondition = @$filter["v_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchValue2 = @$filter["y_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchOperator2 = @$filter["w_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->save();

        // Field monto_usd
        $this->monto_usd->AdvancedSearch->SearchValue = @$filter["x_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator = @$filter["z_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchCondition = @$filter["v_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchValue2 = @$filter["y_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator2 = @$filter["w_monto_usd"];
        $this->monto_usd->AdvancedSearch->save();

        // Field dias_credito
        $this->dias_credito->AdvancedSearch->SearchValue = @$filter["x_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchOperator = @$filter["z_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchCondition = @$filter["v_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchValue2 = @$filter["y_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchOperator2 = @$filter["w_dias_credito"];
        $this->dias_credito->AdvancedSearch->save();

        // Field entregado
        $this->entregado->AdvancedSearch->SearchValue = @$filter["x_entregado"];
        $this->entregado->AdvancedSearch->SearchOperator = @$filter["z_entregado"];
        $this->entregado->AdvancedSearch->SearchCondition = @$filter["v_entregado"];
        $this->entregado->AdvancedSearch->SearchValue2 = @$filter["y_entregado"];
        $this->entregado->AdvancedSearch->SearchOperator2 = @$filter["w_entregado"];
        $this->entregado->AdvancedSearch->save();

        // Field fecha_entrega
        $this->fecha_entrega->AdvancedSearch->SearchValue = @$filter["x_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchOperator = @$filter["z_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchCondition = @$filter["v_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchValue2 = @$filter["y_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->save();

        // Field pagado
        $this->pagado->AdvancedSearch->SearchValue = @$filter["x_pagado"];
        $this->pagado->AdvancedSearch->SearchOperator = @$filter["z_pagado"];
        $this->pagado->AdvancedSearch->SearchCondition = @$filter["v_pagado"];
        $this->pagado->AdvancedSearch->SearchValue2 = @$filter["y_pagado"];
        $this->pagado->AdvancedSearch->SearchOperator2 = @$filter["w_pagado"];
        $this->pagado->AdvancedSearch->save();

        // Field bultos
        $this->bultos->AdvancedSearch->SearchValue = @$filter["x_bultos"];
        $this->bultos->AdvancedSearch->SearchOperator = @$filter["z_bultos"];
        $this->bultos->AdvancedSearch->SearchCondition = @$filter["v_bultos"];
        $this->bultos->AdvancedSearch->SearchValue2 = @$filter["y_bultos"];
        $this->bultos->AdvancedSearch->SearchOperator2 = @$filter["w_bultos"];
        $this->bultos->AdvancedSearch->save();

        // Field fecha_bultos
        $this->fecha_bultos->AdvancedSearch->SearchValue = @$filter["x_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchOperator = @$filter["z_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchCondition = @$filter["v_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchValue2 = @$filter["y_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->save();

        // Field user_bultos
        $this->user_bultos->AdvancedSearch->SearchValue = @$filter["x_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchOperator = @$filter["z_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchCondition = @$filter["v_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchValue2 = @$filter["y_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchOperator2 = @$filter["w_user_bultos"];
        $this->user_bultos->AdvancedSearch->save();

        // Field fecha_despacho
        $this->fecha_despacho->AdvancedSearch->SearchValue = @$filter["x_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchOperator = @$filter["z_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchCondition = @$filter["v_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchValue2 = @$filter["y_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->save();

        // Field user_despacho
        $this->user_despacho->AdvancedSearch->SearchValue = @$filter["x_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchOperator = @$filter["z_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchCondition = @$filter["v_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchValue2 = @$filter["y_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_user_despacho"];
        $this->user_despacho->AdvancedSearch->save();

        // Field consignacion
        $this->consignacion->AdvancedSearch->SearchValue = @$filter["x_consignacion"];
        $this->consignacion->AdvancedSearch->SearchOperator = @$filter["z_consignacion"];
        $this->consignacion->AdvancedSearch->SearchCondition = @$filter["v_consignacion"];
        $this->consignacion->AdvancedSearch->SearchValue2 = @$filter["y_consignacion"];
        $this->consignacion->AdvancedSearch->SearchOperator2 = @$filter["w_consignacion"];
        $this->consignacion->AdvancedSearch->save();

        // Field monto_sin_descuento
        $this->monto_sin_descuento->AdvancedSearch->SearchValue = @$filter["x_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchOperator = @$filter["z_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchCondition = @$filter["v_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchValue2 = @$filter["y_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchOperator2 = @$filter["w_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->save();

        // Field factura
        $this->factura->AdvancedSearch->SearchValue = @$filter["x_factura"];
        $this->factura->AdvancedSearch->SearchOperator = @$filter["z_factura"];
        $this->factura->AdvancedSearch->SearchCondition = @$filter["v_factura"];
        $this->factura->AdvancedSearch->SearchValue2 = @$filter["y_factura"];
        $this->factura->AdvancedSearch->SearchOperator2 = @$filter["w_factura"];
        $this->factura->AdvancedSearch->save();

        // Field ci_rif
        $this->ci_rif->AdvancedSearch->SearchValue = @$filter["x_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchOperator = @$filter["z_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchCondition = @$filter["v_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchValue2 = @$filter["y_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchOperator2 = @$filter["w_ci_rif"];
        $this->ci_rif->AdvancedSearch->save();

        // Field nombre
        $this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
        $this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
        $this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
        $this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
        $this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
        $this->nombre->AdvancedSearch->save();

        // Field direccion
        $this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
        $this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
        $this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
        $this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
        $this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
        $this->direccion->AdvancedSearch->save();

        // Field telefono
        $this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
        $this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
        $this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
        $this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
        $this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
        $this->telefono->AdvancedSearch->save();

        // Field email
        $this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
        $this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
        $this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
        $this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
        $this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
        $this->_email->AdvancedSearch->save();

        // Field activo
        $this->activo->AdvancedSearch->SearchValue = @$filter["x_activo"];
        $this->activo->AdvancedSearch->SearchOperator = @$filter["z_activo"];
        $this->activo->AdvancedSearch->SearchCondition = @$filter["v_activo"];
        $this->activo->AdvancedSearch->SearchValue2 = @$filter["y_activo"];
        $this->activo->AdvancedSearch->SearchOperator2 = @$filter["w_activo"];
        $this->activo->AdvancedSearch->save();

        // Field comprobante
        $this->comprobante->AdvancedSearch->SearchValue = @$filter["x_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator = @$filter["z_comprobante"];
        $this->comprobante->AdvancedSearch->SearchCondition = @$filter["v_comprobante"];
        $this->comprobante->AdvancedSearch->SearchValue2 = @$filter["y_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator2 = @$filter["w_comprobante"];
        $this->comprobante->AdvancedSearch->save();

        // Field nro_despacho
        $this->nro_despacho->AdvancedSearch->SearchValue = @$filter["x_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchOperator = @$filter["z_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchCondition = @$filter["v_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchValue2 = @$filter["y_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->save();

        // Field cerrado
        $this->cerrado->AdvancedSearch->SearchValue = @$filter["x_cerrado"];
        $this->cerrado->AdvancedSearch->SearchOperator = @$filter["z_cerrado"];
        $this->cerrado->AdvancedSearch->SearchCondition = @$filter["v_cerrado"];
        $this->cerrado->AdvancedSearch->SearchValue2 = @$filter["y_cerrado"];
        $this->cerrado->AdvancedSearch->SearchOperator2 = @$filter["w_cerrado"];
        $this->cerrado->AdvancedSearch->save();

        // Field asesor_asignado
        $this->asesor_asignado->AdvancedSearch->SearchValue = @$filter["x_asesor_asignado"];
        $this->asesor_asignado->AdvancedSearch->SearchOperator = @$filter["z_asesor_asignado"];
        $this->asesor_asignado->AdvancedSearch->SearchCondition = @$filter["v_asesor_asignado"];
        $this->asesor_asignado->AdvancedSearch->SearchValue2 = @$filter["y_asesor_asignado"];
        $this->asesor_asignado->AdvancedSearch->SearchOperator2 = @$filter["w_asesor_asignado"];
        $this->asesor_asignado->AdvancedSearch->save();

        // Field tasa_indexada
        $this->tasa_indexada->AdvancedSearch->SearchValue = @$filter["x_tasa_indexada"];
        $this->tasa_indexada->AdvancedSearch->SearchOperator = @$filter["z_tasa_indexada"];
        $this->tasa_indexada->AdvancedSearch->SearchCondition = @$filter["v_tasa_indexada"];
        $this->tasa_indexada->AdvancedSearch->SearchValue2 = @$filter["y_tasa_indexada"];
        $this->tasa_indexada->AdvancedSearch->SearchOperator2 = @$filter["w_tasa_indexada"];
        $this->tasa_indexada->AdvancedSearch->save();

        // Field id_documento_padre_nd
        $this->id_documento_padre_nd->AdvancedSearch->SearchValue = @$filter["x_id_documento_padre_nd"];
        $this->id_documento_padre_nd->AdvancedSearch->SearchOperator = @$filter["z_id_documento_padre_nd"];
        $this->id_documento_padre_nd->AdvancedSearch->SearchCondition = @$filter["v_id_documento_padre_nd"];
        $this->id_documento_padre_nd->AdvancedSearch->SearchValue2 = @$filter["y_id_documento_padre_nd"];
        $this->id_documento_padre_nd->AdvancedSearch->SearchOperator2 = @$filter["w_id_documento_padre_nd"];
        $this->id_documento_padre_nd->AdvancedSearch->save();

        // Field archivo_pedido
        $this->archivo_pedido->AdvancedSearch->SearchValue = @$filter["x_archivo_pedido"];
        $this->archivo_pedido->AdvancedSearch->SearchOperator = @$filter["z_archivo_pedido"];
        $this->archivo_pedido->AdvancedSearch->SearchCondition = @$filter["v_archivo_pedido"];
        $this->archivo_pedido->AdvancedSearch->SearchValue2 = @$filter["y_archivo_pedido"];
        $this->archivo_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_archivo_pedido"];
        $this->archivo_pedido->AdvancedSearch->save();

        // Field checker
        $this->checker->AdvancedSearch->SearchValue = @$filter["x_checker"];
        $this->checker->AdvancedSearch->SearchOperator = @$filter["z_checker"];
        $this->checker->AdvancedSearch->SearchCondition = @$filter["v_checker"];
        $this->checker->AdvancedSearch->SearchValue2 = @$filter["y_checker"];
        $this->checker->AdvancedSearch->SearchOperator2 = @$filter["w_checker"];
        $this->checker->AdvancedSearch->save();

        // Field checker_date
        $this->checker_date->AdvancedSearch->SearchValue = @$filter["x_checker_date"];
        $this->checker_date->AdvancedSearch->SearchOperator = @$filter["z_checker_date"];
        $this->checker_date->AdvancedSearch->SearchCondition = @$filter["v_checker_date"];
        $this->checker_date->AdvancedSearch->SearchValue2 = @$filter["y_checker_date"];
        $this->checker_date->AdvancedSearch->SearchOperator2 = @$filter["w_checker_date"];
        $this->checker_date->AdvancedSearch->save();

        // Field packer
        $this->packer->AdvancedSearch->SearchValue = @$filter["x_packer"];
        $this->packer->AdvancedSearch->SearchOperator = @$filter["z_packer"];
        $this->packer->AdvancedSearch->SearchCondition = @$filter["v_packer"];
        $this->packer->AdvancedSearch->SearchValue2 = @$filter["y_packer"];
        $this->packer->AdvancedSearch->SearchOperator2 = @$filter["w_packer"];
        $this->packer->AdvancedSearch->save();

        // Field packer_date
        $this->packer_date->AdvancedSearch->SearchValue = @$filter["x_packer_date"];
        $this->packer_date->AdvancedSearch->SearchOperator = @$filter["z_packer_date"];
        $this->packer_date->AdvancedSearch->SearchCondition = @$filter["v_packer_date"];
        $this->packer_date->AdvancedSearch->SearchValue2 = @$filter["y_packer_date"];
        $this->packer_date->AdvancedSearch->SearchOperator2 = @$filter["w_packer_date"];
        $this->packer_date->AdvancedSearch->save();

        // Field fotos
        $this->fotos->AdvancedSearch->SearchValue = @$filter["x_fotos"];
        $this->fotos->AdvancedSearch->SearchOperator = @$filter["z_fotos"];
        $this->fotos->AdvancedSearch->SearchCondition = @$filter["v_fotos"];
        $this->fotos->AdvancedSearch->SearchValue2 = @$filter["y_fotos"];
        $this->fotos->AdvancedSearch->SearchOperator2 = @$filter["w_fotos"];
        $this->fotos->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->tipo_documento, $default, false); // tipo_documento
        $this->buildSearchSql($where, $this->nro_documento, $default, false); // nro_documento
        $this->buildSearchSql($where, $this->fecha, $default, false); // fecha
        $this->buildSearchSql($where, $this->nro_control, $default, false); // nro_control
        $this->buildSearchSql($where, $this->cliente, $default, false); // cliente
        $this->buildSearchSql($where, $this->doc_afectado, $default, false); // doc_afectado
        $this->buildSearchSql($where, $this->monto_total, $default, false); // monto_total
        $this->buildSearchSql($where, $this->alicuota_iva, $default, false); // alicuota_iva
        $this->buildSearchSql($where, $this->iva, $default, false); // iva
        $this->buildSearchSql($where, $this->total, $default, false); // total
        $this->buildSearchSql($where, $this->moneda, $default, false); // moneda
        $this->buildSearchSql($where, $this->descuento, $default, false); // descuento
        $this->buildSearchSql($where, $this->descuento2, $default, false); // descuento2
        $this->buildSearchSql($where, $this->asesor, $default, false); // asesor
        $this->buildSearchSql($where, $this->lista_pedido, $default, false); // lista_pedido
        $this->buildSearchSql($where, $this->unidades, $default, false); // unidades
        $this->buildSearchSql($where, $this->estatus, $default, false); // estatus
        $this->buildSearchSql($where, $this->nota, $default, false); // nota
        $this->buildSearchSql($where, $this->_username, $default, false); // username
        $this->buildSearchSql($where, $this->id_documento_padre, $default, false); // id_documento_padre
        $this->buildSearchSql($where, $this->documento, $default, false); // documento
        $this->buildSearchSql($where, $this->tasa_dia, $default, false); // tasa_dia
        $this->buildSearchSql($where, $this->monto_usd, $default, false); // monto_usd
        $this->buildSearchSql($where, $this->dias_credito, $default, false); // dias_credito
        $this->buildSearchSql($where, $this->entregado, $default, false); // entregado
        $this->buildSearchSql($where, $this->fecha_entrega, $default, false); // fecha_entrega
        $this->buildSearchSql($where, $this->pagado, $default, false); // pagado
        $this->buildSearchSql($where, $this->bultos, $default, false); // bultos
        $this->buildSearchSql($where, $this->fecha_bultos, $default, false); // fecha_bultos
        $this->buildSearchSql($where, $this->user_bultos, $default, false); // user_bultos
        $this->buildSearchSql($where, $this->fecha_despacho, $default, false); // fecha_despacho
        $this->buildSearchSql($where, $this->user_despacho, $default, false); // user_despacho
        $this->buildSearchSql($where, $this->consignacion, $default, false); // consignacion
        $this->buildSearchSql($where, $this->monto_sin_descuento, $default, false); // monto_sin_descuento
        $this->buildSearchSql($where, $this->factura, $default, false); // factura
        $this->buildSearchSql($where, $this->ci_rif, $default, false); // ci_rif
        $this->buildSearchSql($where, $this->nombre, $default, false); // nombre
        $this->buildSearchSql($where, $this->direccion, $default, false); // direccion
        $this->buildSearchSql($where, $this->telefono, $default, false); // telefono
        $this->buildSearchSql($where, $this->_email, $default, false); // email
        $this->buildSearchSql($where, $this->activo, $default, false); // activo
        $this->buildSearchSql($where, $this->comprobante, $default, false); // comprobante
        $this->buildSearchSql($where, $this->nro_despacho, $default, false); // nro_despacho
        $this->buildSearchSql($where, $this->cerrado, $default, false); // cerrado
        $this->buildSearchSql($where, $this->asesor_asignado, $default, false); // asesor_asignado
        $this->buildSearchSql($where, $this->tasa_indexada, $default, false); // tasa_indexada
        $this->buildSearchSql($where, $this->id_documento_padre_nd, $default, false); // id_documento_padre_nd
        $this->buildSearchSql($where, $this->archivo_pedido, $default, false); // archivo_pedido
        $this->buildSearchSql($where, $this->checker, $default, false); // checker
        $this->buildSearchSql($where, $this->checker_date, $default, false); // checker_date
        $this->buildSearchSql($where, $this->packer, $default, false); // packer
        $this->buildSearchSql($where, $this->packer_date, $default, false); // packer_date
        $this->buildSearchSql($where, $this->fotos, $default, false); // fotos

        // Set up search command
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->tipo_documento->AdvancedSearch->save(); // tipo_documento
            $this->nro_documento->AdvancedSearch->save(); // nro_documento
            $this->fecha->AdvancedSearch->save(); // fecha
            $this->nro_control->AdvancedSearch->save(); // nro_control
            $this->cliente->AdvancedSearch->save(); // cliente
            $this->doc_afectado->AdvancedSearch->save(); // doc_afectado
            $this->monto_total->AdvancedSearch->save(); // monto_total
            $this->alicuota_iva->AdvancedSearch->save(); // alicuota_iva
            $this->iva->AdvancedSearch->save(); // iva
            $this->total->AdvancedSearch->save(); // total
            $this->moneda->AdvancedSearch->save(); // moneda
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->descuento2->AdvancedSearch->save(); // descuento2
            $this->asesor->AdvancedSearch->save(); // asesor
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->unidades->AdvancedSearch->save(); // unidades
            $this->estatus->AdvancedSearch->save(); // estatus
            $this->nota->AdvancedSearch->save(); // nota
            $this->_username->AdvancedSearch->save(); // username
            $this->id_documento_padre->AdvancedSearch->save(); // id_documento_padre
            $this->documento->AdvancedSearch->save(); // documento
            $this->tasa_dia->AdvancedSearch->save(); // tasa_dia
            $this->monto_usd->AdvancedSearch->save(); // monto_usd
            $this->dias_credito->AdvancedSearch->save(); // dias_credito
            $this->entregado->AdvancedSearch->save(); // entregado
            $this->fecha_entrega->AdvancedSearch->save(); // fecha_entrega
            $this->pagado->AdvancedSearch->save(); // pagado
            $this->bultos->AdvancedSearch->save(); // bultos
            $this->fecha_bultos->AdvancedSearch->save(); // fecha_bultos
            $this->user_bultos->AdvancedSearch->save(); // user_bultos
            $this->fecha_despacho->AdvancedSearch->save(); // fecha_despacho
            $this->user_despacho->AdvancedSearch->save(); // user_despacho
            $this->consignacion->AdvancedSearch->save(); // consignacion
            $this->monto_sin_descuento->AdvancedSearch->save(); // monto_sin_descuento
            $this->factura->AdvancedSearch->save(); // factura
            $this->ci_rif->AdvancedSearch->save(); // ci_rif
            $this->nombre->AdvancedSearch->save(); // nombre
            $this->direccion->AdvancedSearch->save(); // direccion
            $this->telefono->AdvancedSearch->save(); // telefono
            $this->_email->AdvancedSearch->save(); // email
            $this->activo->AdvancedSearch->save(); // activo
            $this->comprobante->AdvancedSearch->save(); // comprobante
            $this->nro_despacho->AdvancedSearch->save(); // nro_despacho
            $this->cerrado->AdvancedSearch->save(); // cerrado
            $this->asesor_asignado->AdvancedSearch->save(); // asesor_asignado
            $this->tasa_indexada->AdvancedSearch->save(); // tasa_indexada
            $this->id_documento_padre_nd->AdvancedSearch->save(); // id_documento_padre_nd
            $this->archivo_pedido->AdvancedSearch->save(); // archivo_pedido
            $this->checker->AdvancedSearch->save(); // checker
            $this->checker_date->AdvancedSearch->save(); // checker_date
            $this->packer->AdvancedSearch->save(); // packer
            $this->packer_date->AdvancedSearch->save(); // packer_date
            $this->fotos->AdvancedSearch->save(); // fotos

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
            $this->tipo_documento->AdvancedSearch->save(); // tipo_documento
            $this->nro_documento->AdvancedSearch->save(); // nro_documento
            $this->fecha->AdvancedSearch->save(); // fecha
            $this->nro_control->AdvancedSearch->save(); // nro_control
            $this->cliente->AdvancedSearch->save(); // cliente
            $this->doc_afectado->AdvancedSearch->save(); // doc_afectado
            $this->monto_total->AdvancedSearch->save(); // monto_total
            $this->alicuota_iva->AdvancedSearch->save(); // alicuota_iva
            $this->iva->AdvancedSearch->save(); // iva
            $this->total->AdvancedSearch->save(); // total
            $this->moneda->AdvancedSearch->save(); // moneda
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->descuento2->AdvancedSearch->save(); // descuento2
            $this->asesor->AdvancedSearch->save(); // asesor
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->unidades->AdvancedSearch->save(); // unidades
            $this->estatus->AdvancedSearch->save(); // estatus
            $this->nota->AdvancedSearch->save(); // nota
            $this->_username->AdvancedSearch->save(); // username
            $this->id_documento_padre->AdvancedSearch->save(); // id_documento_padre
            $this->documento->AdvancedSearch->save(); // documento
            $this->tasa_dia->AdvancedSearch->save(); // tasa_dia
            $this->monto_usd->AdvancedSearch->save(); // monto_usd
            $this->dias_credito->AdvancedSearch->save(); // dias_credito
            $this->entregado->AdvancedSearch->save(); // entregado
            $this->fecha_entrega->AdvancedSearch->save(); // fecha_entrega
            $this->pagado->AdvancedSearch->save(); // pagado
            $this->bultos->AdvancedSearch->save(); // bultos
            $this->fecha_bultos->AdvancedSearch->save(); // fecha_bultos
            $this->user_bultos->AdvancedSearch->save(); // user_bultos
            $this->fecha_despacho->AdvancedSearch->save(); // fecha_despacho
            $this->user_despacho->AdvancedSearch->save(); // user_despacho
            $this->consignacion->AdvancedSearch->save(); // consignacion
            $this->monto_sin_descuento->AdvancedSearch->save(); // monto_sin_descuento
            $this->factura->AdvancedSearch->save(); // factura
            $this->ci_rif->AdvancedSearch->save(); // ci_rif
            $this->nombre->AdvancedSearch->save(); // nombre
            $this->direccion->AdvancedSearch->save(); // direccion
            $this->telefono->AdvancedSearch->save(); // telefono
            $this->_email->AdvancedSearch->save(); // email
            $this->activo->AdvancedSearch->save(); // activo
            $this->comprobante->AdvancedSearch->save(); // comprobante
            $this->nro_despacho->AdvancedSearch->save(); // nro_despacho
            $this->cerrado->AdvancedSearch->save(); // cerrado
            $this->asesor_asignado->AdvancedSearch->save(); // asesor_asignado
            $this->tasa_indexada->AdvancedSearch->save(); // tasa_indexada
            $this->id_documento_padre_nd->AdvancedSearch->save(); // id_documento_padre_nd
            $this->archivo_pedido->AdvancedSearch->save(); // archivo_pedido
            $this->checker->AdvancedSearch->save(); // checker
            $this->checker_date->AdvancedSearch->save(); // checker_date
            $this->packer->AdvancedSearch->save(); // packer
            $this->packer_date->AdvancedSearch->save(); // packer_date
            $this->fotos->AdvancedSearch->save(); // fotos
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

        // Field nro_documento
        $filter = $this->queryBuilderWhere("nro_documento");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->nro_documento, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->nro_documento->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field fecha
        $filter = $this->queryBuilderWhere("fecha");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->fecha, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->fecha->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field cliente
        $filter = $this->queryBuilderWhere("cliente");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->cliente, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->cliente->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field total
        $filter = $this->queryBuilderWhere("total");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->total, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->total->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field moneda
        $filter = $this->queryBuilderWhere("moneda");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->moneda, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->moneda->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field descuento
        $filter = $this->queryBuilderWhere("descuento");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->descuento, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->descuento->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field descuento2
        $filter = $this->queryBuilderWhere("descuento2");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->descuento2, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->descuento2->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field lista_pedido
        $filter = $this->queryBuilderWhere("lista_pedido");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->lista_pedido, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->lista_pedido->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field unidades
        $filter = $this->queryBuilderWhere("unidades");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->unidades, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->unidades->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field username
        $filter = $this->queryBuilderWhere("username");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->_username, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->_username->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field asesor_asignado
        $filter = $this->queryBuilderWhere("asesor_asignado");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->asesor_asignado, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->asesor_asignado->caption() . "</span>" . $captionSuffix . $filter . "</div>";
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
        $searchFlds[] = &$this->tipo_documento;
        $searchFlds[] = &$this->nro_documento;
        $searchFlds[] = &$this->nro_control;
        $searchFlds[] = &$this->doc_afectado;
        $searchFlds[] = &$this->moneda;
        $searchFlds[] = &$this->asesor;
        $searchFlds[] = &$this->estatus;
        $searchFlds[] = &$this->nota;
        $searchFlds[] = &$this->_username;
        $searchFlds[] = &$this->documento;
        $searchFlds[] = &$this->user_bultos;
        $searchFlds[] = &$this->user_despacho;
        $searchFlds[] = &$this->ci_rif;
        $searchFlds[] = &$this->nombre;
        $searchFlds[] = &$this->direccion;
        $searchFlds[] = &$this->telefono;
        $searchFlds[] = &$this->_email;
        $searchFlds[] = &$this->nro_despacho;
        $searchFlds[] = &$this->asesor_asignado;
        $searchFlds[] = &$this->archivo_pedido;
        $searchFlds[] = &$this->checker;
        $searchFlds[] = &$this->packer;
        $searchFlds[] = &$this->fotos;
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
        if ($this->tipo_documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_control->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cliente->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->doc_afectado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_total->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->alicuota_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->total->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->moneda->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descuento2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->asesor->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->lista_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->unidades->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->estatus->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_username->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->id_documento_padre->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tasa_dia->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_usd->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->dias_credito->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->entregado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_entrega->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->pagado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->user_bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->user_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->consignacion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_sin_descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->factura->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ci_rif->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nombre->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->direccion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->telefono->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_email->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->comprobante->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cerrado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->asesor_asignado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tasa_indexada->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->id_documento_padre_nd->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->archivo_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->checker->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->checker_date->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->packer->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->packer_date->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fotos->AdvancedSearch->issetSession()) {
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
        $this->tipo_documento->AdvancedSearch->unsetSession();
        $this->nro_documento->AdvancedSearch->unsetSession();
        $this->fecha->AdvancedSearch->unsetSession();
        $this->nro_control->AdvancedSearch->unsetSession();
        $this->cliente->AdvancedSearch->unsetSession();
        $this->doc_afectado->AdvancedSearch->unsetSession();
        $this->monto_total->AdvancedSearch->unsetSession();
        $this->alicuota_iva->AdvancedSearch->unsetSession();
        $this->iva->AdvancedSearch->unsetSession();
        $this->total->AdvancedSearch->unsetSession();
        $this->moneda->AdvancedSearch->unsetSession();
        $this->descuento->AdvancedSearch->unsetSession();
        $this->descuento2->AdvancedSearch->unsetSession();
        $this->asesor->AdvancedSearch->unsetSession();
        $this->lista_pedido->AdvancedSearch->unsetSession();
        $this->unidades->AdvancedSearch->unsetSession();
        $this->estatus->AdvancedSearch->unsetSession();
        $this->nota->AdvancedSearch->unsetSession();
        $this->_username->AdvancedSearch->unsetSession();
        $this->id_documento_padre->AdvancedSearch->unsetSession();
        $this->documento->AdvancedSearch->unsetSession();
        $this->tasa_dia->AdvancedSearch->unsetSession();
        $this->monto_usd->AdvancedSearch->unsetSession();
        $this->dias_credito->AdvancedSearch->unsetSession();
        $this->entregado->AdvancedSearch->unsetSession();
        $this->fecha_entrega->AdvancedSearch->unsetSession();
        $this->pagado->AdvancedSearch->unsetSession();
        $this->bultos->AdvancedSearch->unsetSession();
        $this->fecha_bultos->AdvancedSearch->unsetSession();
        $this->user_bultos->AdvancedSearch->unsetSession();
        $this->fecha_despacho->AdvancedSearch->unsetSession();
        $this->user_despacho->AdvancedSearch->unsetSession();
        $this->consignacion->AdvancedSearch->unsetSession();
        $this->monto_sin_descuento->AdvancedSearch->unsetSession();
        $this->factura->AdvancedSearch->unsetSession();
        $this->ci_rif->AdvancedSearch->unsetSession();
        $this->nombre->AdvancedSearch->unsetSession();
        $this->direccion->AdvancedSearch->unsetSession();
        $this->telefono->AdvancedSearch->unsetSession();
        $this->_email->AdvancedSearch->unsetSession();
        $this->activo->AdvancedSearch->unsetSession();
        $this->comprobante->AdvancedSearch->unsetSession();
        $this->nro_despacho->AdvancedSearch->unsetSession();
        $this->cerrado->AdvancedSearch->unsetSession();
        $this->asesor_asignado->AdvancedSearch->unsetSession();
        $this->tasa_indexada->AdvancedSearch->unsetSession();
        $this->id_documento_padre_nd->AdvancedSearch->unsetSession();
        $this->archivo_pedido->AdvancedSearch->unsetSession();
        $this->checker->AdvancedSearch->unsetSession();
        $this->checker_date->AdvancedSearch->unsetSession();
        $this->packer->AdvancedSearch->unsetSession();
        $this->packer_date->AdvancedSearch->unsetSession();
        $this->fotos->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->id->AdvancedSearch->load();
        $this->tipo_documento->AdvancedSearch->load();
        $this->nro_documento->AdvancedSearch->load();
        $this->fecha->AdvancedSearch->load();
        $this->nro_control->AdvancedSearch->load();
        $this->cliente->AdvancedSearch->load();
        $this->doc_afectado->AdvancedSearch->load();
        $this->monto_total->AdvancedSearch->load();
        $this->alicuota_iva->AdvancedSearch->load();
        $this->iva->AdvancedSearch->load();
        $this->total->AdvancedSearch->load();
        $this->moneda->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->descuento2->AdvancedSearch->load();
        $this->asesor->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->unidades->AdvancedSearch->load();
        $this->estatus->AdvancedSearch->load();
        $this->nota->AdvancedSearch->load();
        $this->_username->AdvancedSearch->load();
        $this->id_documento_padre->AdvancedSearch->load();
        $this->documento->AdvancedSearch->load();
        $this->tasa_dia->AdvancedSearch->load();
        $this->monto_usd->AdvancedSearch->load();
        $this->dias_credito->AdvancedSearch->load();
        $this->entregado->AdvancedSearch->load();
        $this->fecha_entrega->AdvancedSearch->load();
        $this->pagado->AdvancedSearch->load();
        $this->bultos->AdvancedSearch->load();
        $this->fecha_bultos->AdvancedSearch->load();
        $this->user_bultos->AdvancedSearch->load();
        $this->fecha_despacho->AdvancedSearch->load();
        $this->user_despacho->AdvancedSearch->load();
        $this->consignacion->AdvancedSearch->load();
        $this->monto_sin_descuento->AdvancedSearch->load();
        $this->factura->AdvancedSearch->load();
        $this->ci_rif->AdvancedSearch->load();
        $this->nombre->AdvancedSearch->load();
        $this->direccion->AdvancedSearch->load();
        $this->telefono->AdvancedSearch->load();
        $this->_email->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->comprobante->AdvancedSearch->load();
        $this->nro_despacho->AdvancedSearch->load();
        $this->cerrado->AdvancedSearch->load();
        $this->asesor_asignado->AdvancedSearch->load();
        $this->tasa_indexada->AdvancedSearch->load();
        $this->id_documento_padre_nd->AdvancedSearch->load();
        $this->archivo_pedido->AdvancedSearch->load();
        $this->checker->AdvancedSearch->load();
        $this->checker_date->AdvancedSearch->load();
        $this->packer->AdvancedSearch->load();
        $this->packer_date->AdvancedSearch->load();
        $this->fotos->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = $this->id->Expression . " DESC"; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->nro_documento); // nro_documento
            $this->updateSort($this->fecha); // fecha
            $this->updateSort($this->cliente); // cliente
            $this->updateSort($this->total); // total
            $this->updateSort($this->moneda); // moneda
            $this->updateSort($this->descuento); // descuento
            $this->updateSort($this->descuento2); // descuento2
            $this->updateSort($this->lista_pedido); // lista_pedido
            $this->updateSort($this->unidades); // unidades
            $this->updateSort($this->_username); // username
            $this->updateSort($this->asesor_asignado); // asesor_asignado
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
                $this->tipo_documento->setSort("");
                $this->nro_documento->setSort("");
                $this->fecha->setSort("");
                $this->nro_control->setSort("");
                $this->cliente->setSort("");
                $this->doc_afectado->setSort("");
                $this->monto_total->setSort("");
                $this->alicuota_iva->setSort("");
                $this->iva->setSort("");
                $this->total->setSort("");
                $this->moneda->setSort("");
                $this->descuento->setSort("");
                $this->descuento2->setSort("");
                $this->asesor->setSort("");
                $this->lista_pedido->setSort("");
                $this->unidades->setSort("");
                $this->estatus->setSort("");
                $this->nota->setSort("");
                $this->_username->setSort("");
                $this->id_documento_padre->setSort("");
                $this->documento->setSort("");
                $this->tasa_dia->setSort("");
                $this->monto_usd->setSort("");
                $this->dias_credito->setSort("");
                $this->entregado->setSort("");
                $this->fecha_entrega->setSort("");
                $this->pagado->setSort("");
                $this->bultos->setSort("");
                $this->fecha_bultos->setSort("");
                $this->user_bultos->setSort("");
                $this->fecha_despacho->setSort("");
                $this->user_despacho->setSort("");
                $this->consignacion->setSort("");
                $this->monto_sin_descuento->setSort("");
                $this->factura->setSort("");
                $this->ci_rif->setSort("");
                $this->nombre->setSort("");
                $this->direccion->setSort("");
                $this->telefono->setSort("");
                $this->_email->setSort("");
                $this->activo->setSort("");
                $this->comprobante->setSort("");
                $this->nro_despacho->setSort("");
                $this->cerrado->setSort("");
                $this->asesor_asignado->setSort("");
                $this->tasa_indexada->setSort("");
                $this->id_documento_padre_nd->setSort("");
                $this->archivo_pedido->setSort("");
                $this->checker->setSort("");
                $this->checker_date->setSort("");
                $this->packer->setSort("");
                $this->packer_date->setSort("");
                $this->fotos->setSort("");
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

        // "detail_view_out"
        $item = &$this->ListOptions->add("detail_view_out");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'view_out');
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
        $pages->add("view_out");
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
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"view_out_tdcpdv\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
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
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"view_out_tdcpdv\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
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
                            : "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . $title . "\" data-ew-action=\"submit\" form=\"fview_out_tdcpdvlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listAction->toDataAttributes() . ">" . $icon . " " . $caption . "</button></li>";
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = $disabled
                            ? "<div class=\"alert alert-light\">" . $icon . " " . $caption . "</div>"
                            : "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . $title . "\" data-caption=\"" . $title . "\" data-ew-action=\"submit\" form=\"fview_out_tdcpdvlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listAction->toDataAttributes() . ">" . $icon . " " . $caption . "</button>";
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

        // "detail_view_out"
        $opt = $this->ListOptions["detail_view_out"];
        if ($Security->allowList(CurrentProjectID() . 'view_out')) {
            $body = $Language->phrase("DetailLink") . $Language->tablePhrase("view_out", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("ViewOutList?" . Config("TABLE_SHOW_MASTER") . "=view_out_tdcpdv&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("ViewOutGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'view_out_tdcpdv')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=view_out");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "view_out";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'view_out_tdcpdv')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=view_out");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "view_out";
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
        $masterKeys["tipo_documento"] = strval($this->tipo_documento->DbValue);

        // Column "detail_view_out"
        if ($this->DetailPages?->getItem("view_out")?->Visible && $Security->allowList(CurrentProjectID() . 'view_out')) {
            $link = "";
            $option = $this->ListOptions["detail_view_out"];
            $detailTbl = Container("view_out");
            $detailFilter = $detailTbl->getDetailFilter($this);
            $detailTbl->setCurrentMasterTable($this->TableVar);
            $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
            $url = "ViewOutPreview?t=view_out_tdcpdv&f=" . Encrypt($detailFilter . "|" . implode("|", array_values($masterKeys)));
            $btngrp = "<div data-table=\"view_out\" data-url=\"" . $url . "\" class=\"ew-detail-btn-group btn-group btn-group-sm d-none\">";
            if ($Security->allowList(CurrentProjectID() . 'view_out_tdcpdv')) {
                $label = $Language->tablePhrase("view_out", "TblCaption");
                $link = "<button class=\"nav-link\" data-bs-toggle=\"tab\" data-table=\"view_out\" data-url=\"" . $url . "\" type=\"button\" role=\"tab\" aria-selected=\"false\">" . $label . "</button>";
                $detaillnk = GetUrl("ViewOutList?" . Config("TABLE_SHOW_MASTER") . "=view_out_tdcpdv");
                foreach ($masterKeys as $key => $value) {
                    $detaillnk .= "&" . GetForeignKeyUrl("fk_$key", $value);
                }
                $title = $Language->tablePhrase("view_out", "TblCaption");
                $caption = $Language->phrase("MasterDetailListLink");
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . $title . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($detaillnk) . "\">" . $caption . "</button>";
            }
            $detailPageObj = Container("ViewOutGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'view_out_tdcpdv')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $viewurl = GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=view_out"));
                $btngrp .= "<button type=\"button\" class=\"btn btn-default\" title=\"" . HtmlTitle($caption) . "\" data-ew-action=\"redirect\" data-url=\"" . HtmlEncode($viewurl) . "\">" . $caption . "</button>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'view_out_tdcpdv')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $editurl = GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=view_out"));
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
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $this->createColumnOption($option, "nro_documento");
            $this->createColumnOption($option, "fecha");
            $this->createColumnOption($option, "cliente");
            $this->createColumnOption($option, "total");
            $this->createColumnOption($option, "moneda");
            $this->createColumnOption($option, "descuento");
            $this->createColumnOption($option, "descuento2");
            $this->createColumnOption($option, "lista_pedido");
            $this->createColumnOption($option, "unidades");
            $this->createColumnOption($option, "username");
            $this->createColumnOption($option, "asesor_asignado");
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fview_out_tdcpdvsrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fview_out_tdcpdvsrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fview_out_tdcpdvlist"' . $listAction->toDataAttributes() . '>' . $icon . '</button>';
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_view_out_tdcpdv", "data-rowtype" => RowType::ADD]);
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
            "id" => "r" . $this->RowCount . "_view_out_tdcpdv",
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

        // tipo_documento
        if ($this->tipo_documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tipo_documento->AdvancedSearch->SearchValue != "" || $this->tipo_documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_documento
        if ($this->nro_documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_documento->AdvancedSearch->SearchValue != "" || $this->nro_documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha
        if ($this->fecha->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha->AdvancedSearch->SearchValue != "" || $this->fecha->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_control
        if ($this->nro_control->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_control->AdvancedSearch->SearchValue != "" || $this->nro_control->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cliente
        if ($this->cliente->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cliente->AdvancedSearch->SearchValue != "" || $this->cliente->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // doc_afectado
        if ($this->doc_afectado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->doc_afectado->AdvancedSearch->SearchValue != "" || $this->doc_afectado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_total
        if ($this->monto_total->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_total->AdvancedSearch->SearchValue != "" || $this->monto_total->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // alicuota_iva
        if ($this->alicuota_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->alicuota_iva->AdvancedSearch->SearchValue != "" || $this->alicuota_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // iva
        if ($this->iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->iva->AdvancedSearch->SearchValue != "" || $this->iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // total
        if ($this->total->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->total->AdvancedSearch->SearchValue != "" || $this->total->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // moneda
        if ($this->moneda->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->moneda->AdvancedSearch->SearchValue != "" || $this->moneda->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // descuento2
        if ($this->descuento2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->descuento2->AdvancedSearch->SearchValue != "" || $this->descuento2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // asesor
        if ($this->asesor->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->asesor->AdvancedSearch->SearchValue != "" || $this->asesor->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // unidades
        if ($this->unidades->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->unidades->AdvancedSearch->SearchValue != "" || $this->unidades->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // estatus
        if ($this->estatus->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->estatus->AdvancedSearch->SearchValue != "" || $this->estatus->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nota
        if ($this->nota->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nota->AdvancedSearch->SearchValue != "" || $this->nota->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // username
        if ($this->_username->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->_username->AdvancedSearch->SearchValue != "" || $this->_username->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // id_documento_padre
        if ($this->id_documento_padre->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id_documento_padre->AdvancedSearch->SearchValue != "" || $this->id_documento_padre->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // documento
        if ($this->documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->documento->AdvancedSearch->SearchValue != "" || $this->documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tasa_dia
        if ($this->tasa_dia->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tasa_dia->AdvancedSearch->SearchValue != "" || $this->tasa_dia->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_usd
        if ($this->monto_usd->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_usd->AdvancedSearch->SearchValue != "" || $this->monto_usd->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // dias_credito
        if ($this->dias_credito->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->dias_credito->AdvancedSearch->SearchValue != "" || $this->dias_credito->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // entregado
        if ($this->entregado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->entregado->AdvancedSearch->SearchValue != "" || $this->entregado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_entrega
        if ($this->fecha_entrega->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_entrega->AdvancedSearch->SearchValue != "" || $this->fecha_entrega->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // pagado
        if ($this->pagado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->pagado->AdvancedSearch->SearchValue != "" || $this->pagado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // bultos
        if ($this->bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->bultos->AdvancedSearch->SearchValue != "" || $this->bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_bultos
        if ($this->fecha_bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_bultos->AdvancedSearch->SearchValue != "" || $this->fecha_bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // user_bultos
        if ($this->user_bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->user_bultos->AdvancedSearch->SearchValue != "" || $this->user_bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_despacho
        if ($this->fecha_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_despacho->AdvancedSearch->SearchValue != "" || $this->fecha_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // user_despacho
        if ($this->user_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->user_despacho->AdvancedSearch->SearchValue != "" || $this->user_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // consignacion
        if ($this->consignacion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->consignacion->AdvancedSearch->SearchValue != "" || $this->consignacion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_sin_descuento
        if ($this->monto_sin_descuento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_sin_descuento->AdvancedSearch->SearchValue != "" || $this->monto_sin_descuento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // factura
        if ($this->factura->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->factura->AdvancedSearch->SearchValue != "" || $this->factura->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ci_rif
        if ($this->ci_rif->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ci_rif->AdvancedSearch->SearchValue != "" || $this->ci_rif->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nombre
        if ($this->nombre->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nombre->AdvancedSearch->SearchValue != "" || $this->nombre->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // direccion
        if ($this->direccion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->direccion->AdvancedSearch->SearchValue != "" || $this->direccion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // telefono
        if ($this->telefono->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->telefono->AdvancedSearch->SearchValue != "" || $this->telefono->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // email
        if ($this->_email->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->_email->AdvancedSearch->SearchValue != "" || $this->_email->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // comprobante
        if ($this->comprobante->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->comprobante->AdvancedSearch->SearchValue != "" || $this->comprobante->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_despacho
        if ($this->nro_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_despacho->AdvancedSearch->SearchValue != "" || $this->nro_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cerrado
        if ($this->cerrado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cerrado->AdvancedSearch->SearchValue != "" || $this->cerrado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // asesor_asignado
        if ($this->asesor_asignado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->asesor_asignado->AdvancedSearch->SearchValue != "" || $this->asesor_asignado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tasa_indexada
        if ($this->tasa_indexada->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tasa_indexada->AdvancedSearch->SearchValue != "" || $this->tasa_indexada->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // id_documento_padre_nd
        if ($this->id_documento_padre_nd->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id_documento_padre_nd->AdvancedSearch->SearchValue != "" || $this->id_documento_padre_nd->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // archivo_pedido
        if ($this->archivo_pedido->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->archivo_pedido->AdvancedSearch->SearchValue != "" || $this->archivo_pedido->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // checker
        if ($this->checker->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->checker->AdvancedSearch->SearchValue != "" || $this->checker->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // checker_date
        if ($this->checker_date->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->checker_date->AdvancedSearch->SearchValue != "" || $this->checker_date->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // packer
        if ($this->packer->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->packer->AdvancedSearch->SearchValue != "" || $this->packer->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // packer_date
        if ($this->packer_date->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->packer_date->AdvancedSearch->SearchValue != "" || $this->packer_date->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fotos
        if ($this->fotos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fotos->AdvancedSearch->SearchValue != "" || $this->fotos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->fecha->setDbValue($row['fecha']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->cliente->setDbValue($row['cliente']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->moneda->setDbValue($row['moneda']);
        $this->descuento->setDbValue($row['descuento']);
        $this->descuento2->setDbValue($row['descuento2']);
        $this->asesor->setDbValue($row['asesor']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidades->setDbValue($row['unidades']);
        $this->estatus->setDbValue($row['estatus']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->documento->setDbValue($row['documento']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->pagado->setDbValue($row['pagado']);
        $this->bultos->setDbValue($row['bultos']);
        $this->fecha_bultos->setDbValue($row['fecha_bultos']);
        $this->user_bultos->setDbValue($row['user_bultos']);
        $this->fecha_despacho->setDbValue($row['fecha_despacho']);
        $this->user_despacho->setDbValue($row['user_despacho']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->monto_sin_descuento->setDbValue($row['monto_sin_descuento']);
        $this->factura->setDbValue($row['factura']);
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono->setDbValue($row['telefono']);
        $this->_email->setDbValue($row['email']);
        $this->activo->setDbValue($row['activo']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->nro_despacho->setDbValue($row['nro_despacho']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->asesor_asignado->setDbValue($row['asesor_asignado']);
        $this->tasa_indexada->setDbValue($row['tasa_indexada']);
        $this->id_documento_padre_nd->setDbValue($row['id_documento_padre_nd']);
        $this->archivo_pedido->Upload->DbValue = $row['archivo_pedido'];
        $this->archivo_pedido->setDbValue($this->archivo_pedido->Upload->DbValue);
        $this->checker->setDbValue($row['checker']);
        $this->checker_date->setDbValue($row['checker_date']);
        $this->packer->setDbValue($row['packer']);
        $this->packer_date->setDbValue($row['packer_date']);
        $this->fotos->Upload->DbValue = $row['fotos'];
        $this->fotos->setDbValue($this->fotos->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['cliente'] = $this->cliente->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['monto_total'] = $this->monto_total->DefaultValue;
        $row['alicuota_iva'] = $this->alicuota_iva->DefaultValue;
        $row['iva'] = $this->iva->DefaultValue;
        $row['total'] = $this->total->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['descuento2'] = $this->descuento2->DefaultValue;
        $row['asesor'] = $this->asesor->DefaultValue;
        $row['lista_pedido'] = $this->lista_pedido->DefaultValue;
        $row['unidades'] = $this->unidades->DefaultValue;
        $row['estatus'] = $this->estatus->DefaultValue;
        $row['nota'] = $this->nota->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['id_documento_padre'] = $this->id_documento_padre->DefaultValue;
        $row['documento'] = $this->documento->DefaultValue;
        $row['tasa_dia'] = $this->tasa_dia->DefaultValue;
        $row['monto_usd'] = $this->monto_usd->DefaultValue;
        $row['dias_credito'] = $this->dias_credito->DefaultValue;
        $row['entregado'] = $this->entregado->DefaultValue;
        $row['fecha_entrega'] = $this->fecha_entrega->DefaultValue;
        $row['pagado'] = $this->pagado->DefaultValue;
        $row['bultos'] = $this->bultos->DefaultValue;
        $row['fecha_bultos'] = $this->fecha_bultos->DefaultValue;
        $row['user_bultos'] = $this->user_bultos->DefaultValue;
        $row['fecha_despacho'] = $this->fecha_despacho->DefaultValue;
        $row['user_despacho'] = $this->user_despacho->DefaultValue;
        $row['consignacion'] = $this->consignacion->DefaultValue;
        $row['monto_sin_descuento'] = $this->monto_sin_descuento->DefaultValue;
        $row['factura'] = $this->factura->DefaultValue;
        $row['ci_rif'] = $this->ci_rif->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['direccion'] = $this->direccion->DefaultValue;
        $row['telefono'] = $this->telefono->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['comprobante'] = $this->comprobante->DefaultValue;
        $row['nro_despacho'] = $this->nro_despacho->DefaultValue;
        $row['cerrado'] = $this->cerrado->DefaultValue;
        $row['asesor_asignado'] = $this->asesor_asignado->DefaultValue;
        $row['tasa_indexada'] = $this->tasa_indexada->DefaultValue;
        $row['id_documento_padre_nd'] = $this->id_documento_padre_nd->DefaultValue;
        $row['archivo_pedido'] = $this->archivo_pedido->DefaultValue;
        $row['checker'] = $this->checker->DefaultValue;
        $row['checker_date'] = $this->checker_date->DefaultValue;
        $row['packer'] = $this->packer->DefaultValue;
        $row['packer_date'] = $this->packer_date->DefaultValue;
        $row['fotos'] = $this->fotos->DefaultValue;
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

        // tipo_documento

        // nro_documento

        // fecha

        // nro_control

        // cliente

        // doc_afectado

        // monto_total

        // alicuota_iva

        // iva

        // total

        // moneda

        // descuento

        // descuento2

        // asesor

        // lista_pedido

        // unidades

        // estatus

        // nota

        // username

        // id_documento_padre

        // documento

        // tasa_dia

        // monto_usd

        // dias_credito

        // entregado

        // fecha_entrega

        // pagado

        // bultos

        // fecha_bultos

        // user_bultos

        // fecha_despacho

        // user_despacho

        // consignacion

        // monto_sin_descuento

        // factura

        // ci_rif

        // nombre

        // direccion

        // telefono

        // email

        // activo

        // comprobante

        // nro_despacho

        // cerrado

        // asesor_asignado

        // tasa_indexada

        // id_documento_padre_nd

        // archivo_pedido

        // checker

        // checker_date

        // packer

        // packer_date

        // fotos

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

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
                        $this->cliente->ViewValue = $this->cliente->CurrentValue;
                    }
                }
            } else {
                $this->cliente->ViewValue = null;
            }

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

            // monto_total
            $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
            $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, $this->monto_total->formatPattern());

            // alicuota_iva
            $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
            $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, $this->alicuota_iva->formatPattern());

            // iva
            $this->iva->ViewValue = $this->iva->CurrentValue;
            $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, $this->iva->formatPattern());

            // total
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->formatPattern());

            // moneda
            $curVal = strval($this->moneda->CurrentValue);
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
                if ($this->moneda->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->moneda->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->moneda->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->moneda->getSelectFilter($this); // PHP
                    $sqlWrk = $this->moneda->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->moneda->Lookup->renderViewRow($rswrk[0]);
                        $this->moneda->ViewValue = $this->moneda->displayValue($arwrk);
                    } else {
                        $this->moneda->ViewValue = $this->moneda->CurrentValue;
                    }
                }
            } else {
                $this->moneda->ViewValue = null;
            }

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // descuento2
            $this->descuento2->ViewValue = $this->descuento2->CurrentValue;
            $this->descuento2->ViewValue = FormatNumber($this->descuento2->ViewValue, $this->descuento2->formatPattern());

            // asesor
            $curVal = strval($this->asesor->CurrentValue);
            if ($curVal != "") {
                $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
                if ($this->asesor->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->asesor->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->asesor->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->asesor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->asesor->Lookup->renderViewRow($rswrk[0]);
                        $this->asesor->ViewValue = $this->asesor->displayValue($arwrk);
                    } else {
                        $this->asesor->ViewValue = $this->asesor->CurrentValue;
                    }
                }
            } else {
                $this->asesor->ViewValue = null;
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

            // unidades
            $this->unidades->ViewValue = $this->unidades->CurrentValue;

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
            }

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;

            // username
            $curVal = strval($this->_username->CurrentValue);
            if ($curVal != "") {
                $this->_username->ViewValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->_username->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->_username->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                        $this->_username->ViewValue = $this->_username->displayValue($arwrk);
                    } else {
                        $this->_username->ViewValue = $this->_username->CurrentValue;
                    }
                }
            } else {
                $this->_username->ViewValue = null;
            }

            // id_documento_padre
            $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
            $this->id_documento_padre->ViewValue = FormatNumber($this->id_documento_padre->ViewValue, $this->id_documento_padre->formatPattern());

            // documento
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->ViewValue = null;
            }

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

            // dias_credito
            $curVal = strval($this->dias_credito->CurrentValue);
            if ($curVal != "") {
                $this->dias_credito->ViewValue = $this->dias_credito->lookupCacheOption($curVal);
                if ($this->dias_credito->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->dias_credito->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->dias_credito->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->dias_credito->getSelectFilter($this); // PHP
                    $sqlWrk = $this->dias_credito->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->dias_credito->Lookup->renderViewRow($rswrk[0]);
                        $this->dias_credito->ViewValue = $this->dias_credito->displayValue($arwrk);
                    } else {
                        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
                    }
                }
            } else {
                $this->dias_credito->ViewValue = null;
            }

            // entregado
            if (strval($this->entregado->CurrentValue) != "") {
                $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
            } else {
                $this->entregado->ViewValue = null;
            }
            $this->entregado->CssClass = "fw-bold fst-italic";

            // fecha_entrega
            $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
            $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, $this->fecha_entrega->formatPattern());

            // pagado
            if (strval($this->pagado->CurrentValue) != "") {
                $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
            } else {
                $this->pagado->ViewValue = null;
            }
            $this->pagado->CssClass = "fw-bold fst-italic";

            // bultos
            $this->bultos->ViewValue = $this->bultos->CurrentValue;

            // fecha_bultos
            $this->fecha_bultos->ViewValue = $this->fecha_bultos->CurrentValue;
            $this->fecha_bultos->ViewValue = FormatDateTime($this->fecha_bultos->ViewValue, $this->fecha_bultos->formatPattern());

            // user_bultos
            $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
            $curVal = strval($this->user_bultos->CurrentValue);
            if ($curVal != "") {
                $this->user_bultos->ViewValue = $this->user_bultos->lookupCacheOption($curVal);
                if ($this->user_bultos->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->user_bultos->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->user_bultos->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->user_bultos->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->user_bultos->Lookup->renderViewRow($rswrk[0]);
                        $this->user_bultos->ViewValue = $this->user_bultos->displayValue($arwrk);
                    } else {
                        $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
                    }
                }
            } else {
                $this->user_bultos->ViewValue = null;
            }

            // fecha_despacho
            $this->fecha_despacho->ViewValue = $this->fecha_despacho->CurrentValue;
            $this->fecha_despacho->ViewValue = FormatDateTime($this->fecha_despacho->ViewValue, $this->fecha_despacho->formatPattern());

            // user_despacho
            $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
            $curVal = strval($this->user_despacho->CurrentValue);
            if ($curVal != "") {
                $this->user_despacho->ViewValue = $this->user_despacho->lookupCacheOption($curVal);
                if ($this->user_despacho->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->user_despacho->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->user_despacho->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->user_despacho->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->user_despacho->Lookup->renderViewRow($rswrk[0]);
                        $this->user_despacho->ViewValue = $this->user_despacho->displayValue($arwrk);
                    } else {
                        $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
                    }
                }
            } else {
                $this->user_despacho->ViewValue = null;
            }

            // consignacion
            if (strval($this->consignacion->CurrentValue) != "") {
                $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
            } else {
                $this->consignacion->ViewValue = null;
            }

            // monto_sin_descuento
            $this->monto_sin_descuento->ViewValue = $this->monto_sin_descuento->CurrentValue;
            $this->monto_sin_descuento->ViewValue = FormatNumber($this->monto_sin_descuento->ViewValue, $this->monto_sin_descuento->formatPattern());

            // factura
            if (strval($this->factura->CurrentValue) != "") {
                $this->factura->ViewValue = $this->factura->optionCaption($this->factura->CurrentValue);
            } else {
                $this->factura->ViewValue = null;
            }

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;

            // direccion
            $this->direccion->ViewValue = $this->direccion->CurrentValue;

            // telefono
            $this->telefono->ViewValue = $this->telefono->CurrentValue;

            // email
            $this->_email->ViewValue = $this->_email->CurrentValue;

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // comprobante
            $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
            $this->comprobante->ViewValue = FormatNumber($this->comprobante->ViewValue, $this->comprobante->formatPattern());

            // nro_despacho
            $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;

            // cerrado
            if (strval($this->cerrado->CurrentValue) != "") {
                $this->cerrado->ViewValue = $this->cerrado->optionCaption($this->cerrado->CurrentValue);
            } else {
                $this->cerrado->ViewValue = null;
            }

            // asesor_asignado
            $curVal = strval($this->asesor_asignado->CurrentValue);
            if ($curVal != "") {
                $this->asesor_asignado->ViewValue = $this->asesor_asignado->lookupCacheOption($curVal);
                if ($this->asesor_asignado->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $lookupFilter = $this->asesor_asignado->getSelectFilter($this); // PHP
                    $sqlWrk = $this->asesor_asignado->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->asesor_asignado->Lookup->renderViewRow($rswrk[0]);
                        $this->asesor_asignado->ViewValue = $this->asesor_asignado->displayValue($arwrk);
                    } else {
                        $this->asesor_asignado->ViewValue = $this->asesor_asignado->CurrentValue;
                    }
                }
            } else {
                $this->asesor_asignado->ViewValue = null;
            }

            // tasa_indexada
            $this->tasa_indexada->ViewValue = $this->tasa_indexada->CurrentValue;
            $this->tasa_indexada->ViewValue = FormatNumber($this->tasa_indexada->ViewValue, $this->tasa_indexada->formatPattern());

            // id_documento_padre_nd
            $this->id_documento_padre_nd->ViewValue = $this->id_documento_padre_nd->CurrentValue;
            $this->id_documento_padre_nd->ViewValue = FormatNumber($this->id_documento_padre_nd->ViewValue, $this->id_documento_padre_nd->formatPattern());

            // archivo_pedido
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->ViewValue = $this->archivo_pedido->Upload->DbValue;
            } else {
                $this->archivo_pedido->ViewValue = "";
            }

            // checker
            $this->checker->ViewValue = $this->checker->CurrentValue;

            // checker_date
            $this->checker_date->ViewValue = $this->checker_date->CurrentValue;
            $this->checker_date->ViewValue = FormatDateTime($this->checker_date->ViewValue, $this->checker_date->formatPattern());

            // packer
            $this->packer->ViewValue = $this->packer->CurrentValue;

            // packer_date
            $this->packer_date->ViewValue = $this->packer_date->CurrentValue;
            $this->packer_date->ViewValue = FormatDateTime($this->packer_date->ViewValue, $this->packer_date->formatPattern());

            // nro_documento
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // cliente
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // total
            $this->total->HrefValue = "";
            $this->total->TooltipValue = "";

            // moneda
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // descuento
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // descuento2
            $this->descuento2->HrefValue = "";
            $this->descuento2->TooltipValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";
            $this->lista_pedido->TooltipValue = "";

            // unidades
            $this->unidades->HrefValue = "";
            $this->unidades->TooltipValue = "";

            // username
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // asesor_asignado
            $this->asesor_asignado->HrefValue = "";
            $this->asesor_asignado->TooltipValue = "";
        } elseif ($this->RowType == RowType::SEARCH) {
            // nro_documento
            $this->nro_documento->setupEditAttributes();
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->AdvancedSearch->SearchValue = HtmlDecode($this->nro_documento->AdvancedSearch->SearchValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->AdvancedSearch->SearchValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, $this->fecha->formatPattern()), $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, $this->fecha->formatPattern()), $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // cliente
            $curVal = trim(strval($this->cliente->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->cliente->AdvancedSearch->ViewValue = $this->cliente->lookupCacheOption($curVal);
            } else {
                $this->cliente->AdvancedSearch->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->lookupOptions()) && count($this->cliente->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cliente->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->cliente->EditValue = array_values($this->cliente->lookupOptions());
                if ($this->cliente->AdvancedSearch->ViewValue == "") {
                    $this->cliente->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cliente->AdvancedSearch->SearchValue, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->cliente->AdvancedSearch->ViewValue = $this->cliente->displayValue($arwrk);
                } else {
                    $this->cliente->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cliente->EditValue = $arwrk;
            }
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

            // total
            $this->total->setupEditAttributes();
            $this->total->EditValue = $this->total->AdvancedSearch->SearchValue;
            $this->total->PlaceHolder = RemoveHtml($this->total->caption());

            // moneda
            $this->moneda->setupEditAttributes();
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->AdvancedSearch->SearchValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());

            // descuento2
            $this->descuento2->setupEditAttributes();
            $this->descuento2->EditValue = $this->descuento2->AdvancedSearch->SearchValue;
            $this->descuento2->PlaceHolder = RemoveHtml($this->descuento2->caption());

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

            // unidades
            $this->unidades->setupEditAttributes();
            $this->unidades->EditValue = $this->unidades->AdvancedSearch->SearchValue;
            $this->unidades->PlaceHolder = RemoveHtml($this->unidades->caption());

            // username
            $this->_username->setupEditAttributes();
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // asesor_asignado
            $this->asesor_asignado->setupEditAttributes();
            $this->asesor_asignado->PlaceHolder = RemoveHtml($this->asesor_asignado->caption());
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
        if (!CheckDate($this->fecha->AdvancedSearch->SearchValue, $this->fecha->formatPattern())) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
        }
        if (!CheckDate($this->fecha->AdvancedSearch->SearchValue2, $this->fecha->formatPattern())) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
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
        $this->tipo_documento->AdvancedSearch->load();
        $this->nro_documento->AdvancedSearch->load();
        $this->fecha->AdvancedSearch->load();
        $this->nro_control->AdvancedSearch->load();
        $this->cliente->AdvancedSearch->load();
        $this->doc_afectado->AdvancedSearch->load();
        $this->monto_total->AdvancedSearch->load();
        $this->alicuota_iva->AdvancedSearch->load();
        $this->iva->AdvancedSearch->load();
        $this->total->AdvancedSearch->load();
        $this->moneda->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->descuento2->AdvancedSearch->load();
        $this->asesor->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->unidades->AdvancedSearch->load();
        $this->estatus->AdvancedSearch->load();
        $this->nota->AdvancedSearch->load();
        $this->_username->AdvancedSearch->load();
        $this->id_documento_padre->AdvancedSearch->load();
        $this->documento->AdvancedSearch->load();
        $this->tasa_dia->AdvancedSearch->load();
        $this->monto_usd->AdvancedSearch->load();
        $this->dias_credito->AdvancedSearch->load();
        $this->entregado->AdvancedSearch->load();
        $this->fecha_entrega->AdvancedSearch->load();
        $this->pagado->AdvancedSearch->load();
        $this->bultos->AdvancedSearch->load();
        $this->fecha_bultos->AdvancedSearch->load();
        $this->user_bultos->AdvancedSearch->load();
        $this->fecha_despacho->AdvancedSearch->load();
        $this->user_despacho->AdvancedSearch->load();
        $this->consignacion->AdvancedSearch->load();
        $this->monto_sin_descuento->AdvancedSearch->load();
        $this->factura->AdvancedSearch->load();
        $this->ci_rif->AdvancedSearch->load();
        $this->nombre->AdvancedSearch->load();
        $this->direccion->AdvancedSearch->load();
        $this->telefono->AdvancedSearch->load();
        $this->_email->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->comprobante->AdvancedSearch->load();
        $this->nro_despacho->AdvancedSearch->load();
        $this->cerrado->AdvancedSearch->load();
        $this->asesor_asignado->AdvancedSearch->load();
        $this->tasa_indexada->AdvancedSearch->load();
        $this->id_documento_padre_nd->AdvancedSearch->load();
        $this->archivo_pedido->AdvancedSearch->load();
        $this->checker->AdvancedSearch->load();
        $this->checker_date->AdvancedSearch->load();
        $this->packer->AdvancedSearch->load();
        $this->packer_date->AdvancedSearch->load();
        $this->fotos->AdvancedSearch->load();
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
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" form=\"fview_out_tdcpdvlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcel", true)) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" form=\"fview_out_tdcpdvlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWord", true)) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdf", true)) . "\" form=\"fview_out_tdcpdvlist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
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
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmail", true) . '" data-caption="' . $Language->phrase("ExportToEmail", true) . '" form="fview_out_tdcpdvlist" data-ew-action="email" data-custom="false" data-hdr="' . $Language->phrase("ExportToEmail", true) . '" data-exported-selected="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fview_out_tdcpdvsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
                case "x_cliente":
                    break;
                case "x_moneda":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_asesor":
                    break;
                case "x_lista_pedido":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_estatus":
                    break;
                case "x__username":
                    break;
                case "x_documento":
                    break;
                case "x_dias_credito":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_entregado":
                    break;
                case "x_pagado":
                    break;
                case "x_user_bultos":
                    break;
                case "x_user_despacho":
                    break;
                case "x_consignacion":
                    break;
                case "x_factura":
                    break;
                case "x_activo":
                    break;
                case "x_cerrado":
                    break;
                case "x_asesor_asignado":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
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
        $sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
            WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
        $grupo = trim(ExecuteScalar($sql));
        if($grupo == "CLIENTE") {
            $sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
            $bloquea = ExecuteScalar($sql);
            if($bloquea == "SI") { 
                $url = "Home";
            }
        }
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
        $header .= '<a class="btn btn-outline-primary" id="btnNuevo" href="HomeOutAdd?tipo_documento=TDCPDV"><span class="fas fa-plus"></span> Nuevo Documento</a><br><br>';
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
        $opt = &$this->ListOptions->add("unidades");
        $opt->Header = "UNDS";
        $opt->OnLeft = true; // Link on left
        $opt->moveTo(7); // Move to first column
        $opt = &$this->ListOptions->add("print");
        $opt->Header = "";
        $opt->OnLeft = true; // Link on left
        $opt->moveTo(6); // Move to first column
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
        $sql = "SELECT ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas WHERE id_documento = '" . $this->id->CurrentValue . "' AND tipo_documento = '" . $this->tipo_documento->CurrentValue . "';";
        $this->ListOptions["unidades"]->Body = '<a target="_blank" data-toggle="tooltip" title="Imprimir" data-placement="bottom"> <b>' . intval(ExecuteScalar($sql)) . ' UNDS</b></a>'; // intval(ExecuteScalar($sql));
        $url = "reportes/pedido_de_venta.php?id=" . $this->id->CurrentValue . "&tipo=" . $this->tipo_documento->CurrentValue;
        $this->ListOptions->Items["print"]->Body ='<a target="_blank" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"> <i class="fa-solid fa-print"></i> </a>';   
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
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
