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
class PagosPreview extends Pagos
{
    use MessagesTrait;

    // Page ID
    public $PageID = "preview";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "PagosPreview";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "PagosPreview";

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
        $this->id_documento->Visible = false;
        $this->tipo_documento->Visible = false;
        $this->tipo_pago->setVisibility();
        $this->fecha->setVisibility();
        $this->banco->setVisibility();
        $this->banco_destino->setVisibility();
        $this->referencia->setVisibility();
        $this->moneda->setVisibility();
        $this->monto->setVisibility();
        $this->nota->Visible = false;
        $this->comprobante_pago->setVisibility();
        $this->comprobante->Visible = false;
        $this->_username->Visible = false;
        $this->fecha_resgistro->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'pagos';
        $this->TableName = 'pagos';

        // Table CSS class
        $this->TableClass = "table table-sm ew-table ew-preview-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (pagos)
        if (!isset($GLOBALS["pagos"]) || $GLOBALS["pagos"]::class == PROJECT_NAMESPACE . "pagos") {
            $GLOBALS["pagos"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pagos');
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
    public $Recordset;
    public $CurrentRow; // Current row // PHP
    public $TotalRecords = 0;
    public $RecordCount = 0;
    public $ListOptions; // List options
    public $OtherOptions; // Other options
    public $StartRecord = 1;
    public $DisplayRecords = 0;
    public $UseModalLinks = false;
    public $IsModal = true;
    public $DetailCounts = [];

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }
        $this->CurrentAction = Param("action"); // Set up current action

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

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->tipo_pago);
        $this->setupLookupOptions($this->banco);
        $this->setupLookupOptions($this->banco_destino);
        $this->setupLookupOptions($this->moneda);

        // Load filter
        $masterKeys = explode("|", Decrypt(Get("f", "")));
        $filter = array_shift($masterKeys);
        if ($filter == "") {
            $filter = "0=1";
        }
        $detailFilters = JsonDecode(Decrypt(Get("detailfilters", ""))) ?: [];
        foreach ($detailFilters as $detailTblVar => $detailFilter) {
            $this->DetailCounts[$detailTblVar] = Container($detailTblVar)?->loadRecordCount($detailFilter);
        }

        // Set up Sort Order
        $this->setupSortOrder();

        // Set up foreign keys
        $this->setupForeignKeys($masterKeys);

        // Call Recordset Selecting event
        $this->recordsetSelecting($filter);

        // Load result set
        $filter = $this->applyUserIDFilters($filter);
        $this->TotalRecords = $this->loadRecordCount($filter);
        if ($this->DisplayRecords <= 0) { // Show all records if page size not specified
            $this->DisplayRecords = $this->TotalRecords > 0 ? $this->TotalRecords : 10;
        }
        $sql = $this->previewSql($filter);
        if ($this->DisplayRecords > 0) {
            $sql->setFirstResult(max($this->StartRecord - 1, 0))->setMaxResults($this->DisplayRecords);
        }
        $this->Recordset = $sql->executeQuery();

        // Call Recordset Selected event
        $this->recordsetSelected($this->Recordset);
        $this->renderOtherOptions();

        // Set up pager (always use PrevNextPager for preview page)
        $url = CurrentPageName() . "?t=" . Get("t") . "&f=" . Get("f"); // Add table/filter parameters only
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", 10, $this->AutoHidePager, null, null, true, $url);

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

    /**
     * Set up sort order
     *
     * @return void
     */
    protected function setupSortOrder()
    {
        $defaultSort = ""; // Set up default sort
        if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
            $this->setSessionOrderBy($defaultSort);
        }
        if (SameText(Get("cmd"), "resetsort")) {
            $this->StartRecord = 1;
            $this->CurrentOrder = "";
            $this->CurrentOrderType = "";
            $this->id->setSort("");
            $this->id_documento->setSort("");
            $this->tipo_documento->setSort("");
            $this->tipo_pago->setSort("");
            $this->fecha->setSort("");
            $this->banco->setSort("");
            $this->banco_destino->setSort("");
            $this->referencia->setSort("");
            $this->moneda->setSort("");
            $this->monto->setSort("");
            $this->nota->setSort("");
            $this->comprobante_pago->setSort("");
            $this->comprobante->setSort("");
            $this->_username->setSort("");
            $this->fecha_resgistro->setSort("");

            // Save sort to session
            $this->setSessionOrderBy("");
        } else {
            $this->StartRecord = 1;
            $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
            $startRec = Get(Config("TABLE_START_REC"));
            if (is_numeric($pageNo)) { // Check for page parameter
                $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
            } elseif (is_numeric($startRec)) { // Check for "start" parameter
                $this->StartRecord = $startRec;
            }
            $this->CurrentOrder = Get("sort", "");
            $this->CurrentOrderType = Get("sortorder", "");
        }

        // Check for sort field
        if ($this->CurrentOrder !== "") {
            $this->updateSort($this->tipo_pago); // tipo_pago
            $this->updateSort($this->fecha); // fecha
            $this->updateSort($this->banco); // banco
            $this->updateSort($this->banco_destino); // banco_destino
            $this->updateSort($this->referencia); // referencia
            $this->updateSort($this->moneda); // moneda
            $this->updateSort($this->monto); // monto
            $this->updateSort($this->comprobante_pago); // comprobante_pago
        }

        // Update field sort
        $this->updateFieldSort();
    }

    /**
     * Get preview SQL
     *
     * @param string $filter
     * @return QueryBuilder
     */
    protected function previewSql($filter)
    {
        $sort = $this->getSessionOrderBy();
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
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
        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();
        $masterKeyUrl = $this->masterKeyUrl();

        // "view"
        $opt = $this->ListOptions["view"];
        if ($Security->canView()) {
            $viewCaption = $Language->phrase("ViewLink");
            $viewTitle = HtmlTitle($viewCaption);
            $viewUrl = GetUrl($this->getViewUrl($masterKeyUrl));
            if ($this->UseModalLinks && !IsMobile()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewTitle . "\" data-caption=\"" . $viewTitle . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode($viewUrl) . "\" data-btn=\"null\">" . $viewCaption . "</a>";
            } else {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewTitle . "\" data-caption=\"" . $viewTitle . "\" href=\"" . HtmlEncode($viewUrl) . "\">" . $viewCaption . "</a>";
            }
        } else {
            $opt->Body = "";
        }

        // "delete"
        $opt = $this->ListOptions["delete"];
        if ($Security->canDelete()) {
            $deleteCaption = $Language->phrase("DeleteLink");
            $deleteTitle = HtmlTitle($deleteCaption);
            $deleteUrl = GetUrl($this->getDeleteUrl($masterKeyUrl));
            $opt->Body = "<a class=\"ew-row-link ew-delete\"" .
                (($this->InlineDelete || $this->UseModalLinks) && !IsMobile() ? " data-json=\"true\" data-ew-action=\"inline-delete\"" : "") .
                " title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" href=\"" . HtmlEncode($deleteUrl) . "\">" . $deleteCaption . "</a>";
        } else {
            $opt->Body = "";
        }

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];
        $option->UseButtonGroup = false;

        // Add group option item
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // Add
        $item = &$option->add("add");
        $item->Visible = $Security->canAdd();
    }

    // Render other options
    protected function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = $option["add"];
        if ($Security->canAdd()) {
            $addCaption = $Language->phrase("AddLink");
            $addTitle = HtmlTitle($addCaption);
            $addUrl = GetUrl($this->getAddUrl($this->masterKeyUrl()));
            if ($this->UseModalLinks && !IsMobile()) {
                $item->Body = "<a class=\"btn btn-default btn-sm ew-add-edit ew-add\" title=\"" . $addTitle . "\" data-caption=\"" . $addTitle . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode($addUrl) . "\" data-btn=\"AddBtn\">" . $addCaption . "</a>";
            } else {
                $item->Body = "<a class=\"btn btn-default btn-sm ew-add-edit ew-add\" title=\"" . $addTitle . "\" data-caption=\"" . $addTitle . "\" href=\"" . HtmlEncode($addUrl) . "\">" . $addCaption . "</a>";
            }
        } else {
            $item->Body = "";
        }
    }

    // Get master foreign key URL
    protected function masterKeyUrl()
    {
        $masterTblVar = Get("t", "");
        $url = "";
        if ($masterTblVar == "salidas") {
            $url = "" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_id", $this->id_documento->QueryStringValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->QueryStringValue) . "";
        }
        if ($masterTblVar == "view_facturas_cobranza") {
            $url = "" . Config("TABLE_SHOW_MASTER") . "=view_facturas_cobranza&" . GetForeignKeyUrl("fk_id", $this->id_documento->QueryStringValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->QueryStringValue) . "";
        }
        return $url;
    }

    // Set up foreign keys
    protected function setupForeignKeys($keys)
    {
        $masterTblVar = Get("t", "");
        if ($masterTblVar == "salidas") {
            $this->id_documento->setQueryStringValue($keys[0] ?? "");
            $this->tipo_documento->setQueryStringValue($keys[1] ?? "");
        }
        if ($masterTblVar == "view_facturas_cobranza") {
            $this->id_documento->setQueryStringValue($keys[0] ?? "");
            $this->tipo_documento->setQueryStringValue($keys[1] ?? "");
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PagosList"), "", $this->TableVar, true);
        $pageId = "preview";
        $Breadcrumb->add("preview", $pageId, $url);
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
                case "x_tipo_pago":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_banco":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_banco_destino":
                    break;
                case "x_moneda":
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
          $header = pagosCobranzaInfo($this->id_documento->CurrentValue, $this->tipo_documento->CurrentValue, "S");

          /*
            $sql = "SELECT total, moneda, IF(IFNULL(tasa_dia, 0) = 0, 1, tasa_dia) AS tasa_dia, nro_documento FROM salidas WHERE id = " . $this->id_documento->CurrentValue;
            $row = ExecuteRow($sql);
            $monto_factura = $row["total"];
            $moneda_factura = $row["moneda"];
            $tasa = floatval($row["tasa_dia"]);
            $nro_documento = $row["nro_documento"];
            $header .= '<div class="col-9"><div class="table-responsive-sm  table-hover">
                        <table class="table table-sm">
                          <thead>
                            <tr class="table-active">
                              <th scope="col">Tipo</th>
                              <th scope="col">Fecha</th>
                              <th scope="col">Banco</th>
                              <th scope="col">Ref.</th>
                              <th scope="col" class="text-right">Monto$</th>
                              <th scope="col" class="text-right">MontoBs.</th>
                              <th scope="col" class="text-right">Tasa</th>
                              <th scope="col" class="text-right">Equiv. $</th>
                              <th scope="col" class="text-right">Equiv. Bs.</th>
                            </tr>
                          </thead>
                          <tbody>';
            $sql = "SELECT 
                        a.tipo_pago, 
                        (SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.tipo_pago) AS tipo, 
                        date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                        a.banco, 
                        a.referencia, 
                        IF(a.moneda = 'USD', a.monto, 0) AS monto_usd, 
                        IF(a.moneda = 'USD', 0, a.monto) AS monto_bss, 
                        a.moneda  
                    FROM 
                        pagos AS a 
                    WHERE 
                        a.id_documento = " . $this->id_documento->CurrentValue . " AND a.tipo_documento = '" . $this->tipo_documento->CurrentValue . "'
                    ORDER BY a.tipo_pago"; 
            $rows = ExecuteRows($sql);
            $monto_usd = 0;
            $monto_bss = 0;
            $moneda = ""; 
            $mont_usd = 0;
            $mont_bss = 0;
            $mont_usd_igtf = 0;
            $mont_bss_igtf = 0;
            $tot_usd = 0;
            $tot_bss = 0;
            $tot_usd_igtf = 0;
            $tot_bss_igtf = 0;
            foreach ($rows as $key => $value) {
                $monto_usd = floatval($value["monto_usd"]);
                $monto_bss = floatval($value["monto_bss"]);
                $moneda = $value["moneda"];
                if($value["tipo_pago"] == "IG") { 
                    $mont_usd_igtf += ($moneda == 'USD' ? $monto_usd : 0);
                    $mont_bss_igtf += ($moneda == 'USD' ? 0 : $monto_bss);
                    $tot_usd_igtf += ($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa);
                    $tot_bss_igtf += ($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss);
                } 
                else {
                    $mont_usd += ($moneda == 'USD' ? $monto_usd : 0);
                    $mont_bss += ($moneda == 'USD' ? 0 : $monto_bss);
                    $tot_usd += ($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa);
                    $tot_bss += ($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss);
                }
                $header .= '<tr>';
                  $header .= '<th scope="row">' . $value["tipo"] . '</th>';
                  $header .= '<td>' . $value["fecha"] . '</td>';
                  $header .= '<td>' . $value["banco"] . ' ...</td>';
                  $header .= '<td>' . $value["referencia"] . '</td>';
                  $header .= '<td align="right">' . number_format($monto_usd, 2, ".", ",") . '</td>';
                  $header .= '<td align="right">' . number_format($monto_bss, 2, ".", ",") . '</td>';
                  $header .= '<td align="right">' . number_format($tasa, 2, ".", ",") . '</td>';
                  $header .= '<td align="right">' . number_format(($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa), 2, ".", ",") . '</td>';
                  $header .= '<td align="right">' . number_format(($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss), 2, ".", ",") . '</td>';
               $header .= ' </tr>';
            }
            $header .= '<tr class="table-success">';
              $header .= '<th scope="row" colspan="4" class="text-right">Total Cancelado Sin IGTF</th>';
              $header .= '<td align="right">' . number_format($mont_usd, 2, ".", ",") . '</td>';
              $header .= '<td align="right">' . number_format($mont_bss, 2, ".", ",") . '</td>';
              $header .= '<td align="right"> -- </td>';
              $header .= '<td align="right">' . number_format($tot_usd, 2, ".", ",") . '</td>';
              $header .= '<td align="right">' . number_format($tot_bss, 2, ".", ",") . '</td>';
           $header .= ' </tr>';
            $header .= '<tr class="table-primary">';
              $header .= '<th scope="row" colspan="4" class="text-right">Total IGTF</th>';
              $header .= '<td align="right">' . number_format($mont_usd_igtf, 2, ".", ",") . '</td>';
              $header .= '<td align="right">' . number_format($mont_bss_igtf, 2, ".", ",") . '</td>';
              $header .= '<td align="right"> -- </td>';
              $header .= '<td align="right">' . number_format($tot_usd_igtf, 2, ".", ",") . '</td>';
              $header .= '<td align="right">' . number_format($tot_bss_igtf, 2, ".", ",") . '</td>';
           $header .= ' </tr>';
            $header .= '</tbody>
                    </table>
                   </div></div>';
           $header .= '<div class="col-6"><table class="table table-sm">
                          <tbody>
                            <tr>
                              <th scope="row" class="text-right">Monto Factura Bs</th>
                              <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura*$tasa : $monto_factura), 2, ".", ",") . '</td>
                              <th class="text-right">Monto Pendiente Bs</th>
                              <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura*$tasa : $monto_factura)-$tot_bss, 2, ".", ",") . '</td>
                            </tr>
                            <tr>
                              <th scope="row" class="text-right">Monto Factura $ Sin IGTF</th>
                              <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa), 2, ".", ",") . '</td>
                              <th class="text-right">Monto Pendiente $</th>
                              <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa)-$tot_usd, 2, ".", ",") . '</td>
                            </tr>
                            <tr>
                              <th scope="row" class="text-right">IGTF Bs</th>
                              <td align="right">' . number_format(($mont_usd*$tasa)*(3/100), 2, ".", ",") . '</td>
                              <th class="text-right">Monto Pendiente IGTF Bs</th>
                              <td align="right">' . number_format((($mont_usd*$tasa)*(3/100))-$tot_bss_igtf, 2, ".", ",") . '</td>
                            </tr>
                            <tr>
                              <th scope="row" class="text-right">IGTF $</th>
                              <td align="right">' . number_format($mont_usd*(3/100), 2, ".", ",") . '</td>
                              <th class="text-right">Monto Pendiente IGTF $</th>
                              <td align="right">' . number_format(($mont_usd*(3/100))-$tot_usd_igtf, 2, ".", ",") . '</td>
                            </tr>
                          </tbody>
                        </table></div>';
    ////////////////////////////////////////                    
           $sql = "SELECT 
           				IF(TIMESTAMPDIFF(DAY, b.fecha_entrega, CURDATE()) > IFNULL(a.dias_credito, 0), 
           				TIMESTAMPDIFF(DAY, b.fecha_entrega, CURDATE()) - a.dias_credito, 
           				0) AS dias_vencidos 
           			FROM 
           				salidas AS a 
           				LEFT OUTER JOIN salidas AS b ON b.id = a.id_documento_padre  
           			WHERE a.id = '" . $this->id_documento->CurrentValue . "' AND a.tipo_documento = '" . $this->tipo_documento->CurrentValue . "';";
           if(intval(ExecuteScalar($sql)) > 0) {
       		$sql = "SELECT nro_documento FROM salidas WHERE id_documento_padre_nd = '" . $this->id_documento->CurrentValue . "';";
       		if($row = ExecuteRow($sql)) {
           		$header .= '<div class="col-6"><table class="table table-sm">
           						<tbody>
           							<tr>
           								<th scope="row" class="text-right">Nota de D&eacute;dito: ' . $row["nro_documento"] . '</th>
           							</tr>
           						</tbody>
           					</table></div>';
           	}
           	else {
           		$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
           		$tasa_indexada = floatval(ExecuteScalar($sql));
           		$sql = "UPDATE salidas SET tasa_indexada = $tasa WHERE id = '" . $this->id->CurrentValue . "' AND tipo_documento = '" . $this->tipo_documento->CurrentValue . "'";
           		Execute($sql);
           		$header .= '<div class="col-6"><table class="table table-sm">
           						<tbody>
           							<tr>
           								<th scope="row" class="text-right">Tasa USD para N/D</th>
           								<td align="right"><input class="form-control" type="text" id="tasa_indexada" value="' . $tasa_indexada . '"></td>
           								<th class="text-right">Sobre Monto Pendiente $</th>
           								<td align="right"><input class="form-control" type="text" id="monto_pendiente" value="' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa)-$tot_usd, 2, ".", ",") . '" readonly="yes"></td>
           							</tr>
           							<tr>
           								<th class="text-center" colspan="4"><a class="btn btn-primary" id="CrearND">Crear Nota de D&eacute;bito</a></th>
           							</tr>
           						</tbody>
           					</table></div>';
           	}
           }
           $sql = "SELECT
           				nro_documento, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
           				total, moneda, tasa_dia, monto_usd
           			FROM
           				salidas
           			WHERE
           				tipo_documento = 'TDCFCV' AND documento = 'NC'
           				AND doc_afectado = '$nro_documento' ";
           if($row = ExecuteRow($sql)) {
           		$header .= '<div class="alert alert-warning">
           						<strong>N.C.: ' .$row["nro_documento"] . ' | Fecha: ' . $row["fecha"] . '</strong> | Monto Bs.: ' . number_format($row["total"], 2, ",", ".") . ' | Monto USD.: ' . number_format($row["monto_usd"], 2, ",", ".") . '
           					</div>';
           }
           $header .= '<script>
           				$("#CrearND").click(function() {
           					var id = ' . $this->id_documento->CurrentValue . ';
           					var username = "' . CurrentUserName() . '";
           					var tasa = $("#tasa_indexada").val();
           					var monto = $("#monto_pendiente").val()
           					var url = "include/crear_nota_debito.php?id=" + id + "&tasa=" + tasa + "&monto=" + monto + "&username=" + username;
           					window.location.href = url; 
           				});
           				</script>';
        */
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
       	$opt = &$this->ListOptions->Add("print");
       	$opt->Header = "";
       	$opt->OnLeft = true; // Link on left
       	$opt->MoveTo(3); // Move to first column
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
        if($this->tipo_pago->CurrentValue == "EF") {
        	$this->ListOptions["print"]->Body = '<a href="PagosView/' . $this->id->CurrentValue . '?export=print" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>';
        }
        else {
        	$this->ListOptions["print"]->Body = '';
        }
    }
}
