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
class EntradasSalidasAdd extends EntradasSalidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EntradasSalidasAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "EntradasSalidasAdd";

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
        $this->almacen->Visible = false;
        $this->id_compra->Visible = false;
        $this->cantidad_articulo->setVisibility();
        $this->articulo_unidad_medida->Visible = false;
        $this->cantidad_unidad_medida->Visible = false;
        $this->cantidad_movimiento->Visible = false;
        $this->precio_unidad_sin_desc->Visible = false;
        $this->descuento->Visible = false;
        $this->costo_unidad->setVisibility();
        $this->costo->setVisibility();
        $this->precio_unidad->setVisibility();
        $this->precio->setVisibility();
        $this->alicuota->Visible = false;
        $this->cantidad_movimiento_consignacion->Visible = false;
        $this->id_consignacion->Visible = false;
        $this->check_ne->Visible = false;
        $this->packer_cantidad->Visible = false;
        $this->newdata->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'entradas_salidas';
        $this->TableName = 'entradas_salidas';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (entradas_salidas)
        if (!isset($GLOBALS["entradas_salidas"]) || $GLOBALS["entradas_salidas"]::class == PROJECT_NAMESPACE . "entradas_salidas") {
            $GLOBALS["entradas_salidas"] = &$this;
        }

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
                if (
                    SameString($pageName, GetPageName($this->getListUrl())) ||
                    SameString($pageName, GetPageName($this->getViewUrl())) ||
                    SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "EntradasSalidasView"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                        $this->clearFailureMessage();
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

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

        // Create form object
        $CurrentForm = new HttpForm();
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->almacen);
        $this->setupLookupOptions($this->articulo_unidad_medida);
        $this->setupLookupOptions($this->cantidad_unidad_medida);
        $this->setupLookupOptions($this->check_ne);
        $this->setupLookupOptions($this->newdata);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("id") ?? Route("id")) !== null) {
                $this->id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Set up master/detail parameters
        // NOTE: Must be after loadOldRecord to prevent master key values being overwritten
        $this->setupMasterParms();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("EntradasSalidasList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "EntradasSalidasList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "EntradasSalidasView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions && !$this->getCurrentMasterTable()) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "EntradasSalidasList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "EntradasSalidasList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = RowType::ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

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

        // Check field name 'lote' first before field var 'x_lote'
        $val = $CurrentForm->hasValue("lote") ? $CurrentForm->getValue("lote") : $CurrentForm->getValue("x_lote");
        if (!$this->lote->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lote->Visible = false; // Disable update for API request
            } else {
                $this->lote->setFormValue($val);
            }
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

        // Check field name 'cantidad_articulo' first before field var 'x_cantidad_articulo'
        $val = $CurrentForm->hasValue("cantidad_articulo") ? $CurrentForm->getValue("cantidad_articulo") : $CurrentForm->getValue("x_cantidad_articulo");
        if (!$this->cantidad_articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_articulo->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_articulo->setFormValue($val, true, $validate);
            }
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

        // Check field name 'costo' first before field var 'x_costo'
        $val = $CurrentForm->hasValue("costo") ? $CurrentForm->getValue("costo") : $CurrentForm->getValue("x_costo");
        if (!$this->costo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->costo->Visible = false; // Disable update for API request
            } else {
                $this->costo->setFormValue($val, true, $validate);
            }
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

        // Check field name 'precio' first before field var 'x_precio'
        $val = $CurrentForm->hasValue("precio") ? $CurrentForm->getValue("precio") : $CurrentForm->getValue("x_precio");
        if (!$this->precio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio->Visible = false; // Disable update for API request
            } else {
                $this->precio->setFormValue($val, true, $validate);
            }
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

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->lote->CurrentValue = $this->lote->FormValue;
        $this->fecha_vencimiento->CurrentValue = $this->fecha_vencimiento->FormValue;
        $this->fecha_vencimiento->CurrentValue = UnFormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern());
        $this->cantidad_articulo->CurrentValue = $this->cantidad_articulo->FormValue;
        $this->costo_unidad->CurrentValue = $this->costo_unidad->FormValue;
        $this->costo->CurrentValue = $this->costo->FormValue;
        $this->precio_unidad->CurrentValue = $this->precio_unidad->FormValue;
        $this->precio->CurrentValue = $this->precio->FormValue;
        $this->newdata->CurrentValue = $this->newdata->FormValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id
        $this->id->RowCssClass = "row";

        // tipo_documento
        $this->tipo_documento->RowCssClass = "row";

        // id_documento
        $this->id_documento->RowCssClass = "row";

        // fabricante
        $this->fabricante->RowCssClass = "row";

        // articulo
        $this->articulo->RowCssClass = "row";

        // lote
        $this->lote->RowCssClass = "row";

        // fecha_vencimiento
        $this->fecha_vencimiento->RowCssClass = "row";

        // almacen
        $this->almacen->RowCssClass = "row";

        // id_compra
        $this->id_compra->RowCssClass = "row";

        // cantidad_articulo
        $this->cantidad_articulo->RowCssClass = "row";

        // articulo_unidad_medida
        $this->articulo_unidad_medida->RowCssClass = "row";

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->RowCssClass = "row";

        // cantidad_movimiento
        $this->cantidad_movimiento->RowCssClass = "row";

        // precio_unidad_sin_desc
        $this->precio_unidad_sin_desc->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

        // costo_unidad
        $this->costo_unidad->RowCssClass = "row";

        // costo
        $this->costo->RowCssClass = "row";

        // precio_unidad
        $this->precio_unidad->RowCssClass = "row";

        // precio
        $this->precio->RowCssClass = "row";

        // alicuota
        $this->alicuota->RowCssClass = "row";

        // cantidad_movimiento_consignacion
        $this->cantidad_movimiento_consignacion->RowCssClass = "row";

        // id_consignacion
        $this->id_consignacion->RowCssClass = "row";

        // check_ne
        $this->check_ne->RowCssClass = "row";

        // packer_cantidad
        $this->packer_cantidad->RowCssClass = "row";

        // newdata
        $this->newdata->RowCssClass = "row";

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

            // lote
            $this->lote->HrefValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->HrefValue = "";

            // costo_unidad
            $this->costo_unidad->HrefValue = "";

            // costo
            $this->costo->HrefValue = "";

            // precio_unidad
            $this->precio_unidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

            // newdata
            $this->newdata->HrefValue = "";
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

            // cantidad_articulo
            $this->cantidad_articulo->setupEditAttributes();
            $this->cantidad_articulo->EditValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, null);
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

            // cantidad_articulo
            $this->cantidad_articulo->HrefValue = "";

            // costo_unidad
            $this->costo_unidad->HrefValue = "";

            // costo
            $this->costo->HrefValue = "";

            // precio_unidad
            $this->precio_unidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

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
            if ($this->cantidad_articulo->Visible && $this->cantidad_articulo->Required) {
                if (!$this->cantidad_articulo->IsDetailKey && EmptyValue($this->cantidad_articulo->FormValue)) {
                    $this->cantidad_articulo->addErrorMessage(str_replace("%s", $this->cantidad_articulo->caption(), $this->cantidad_articulo->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_articulo->FormValue)) {
                $this->cantidad_articulo->addErrorMessage($this->cantidad_articulo->getErrorMessage(false));
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

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

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
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

        // cantidad_articulo
        $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, false);

        // costo_unidad
        $this->costo_unidad->setDbValueDef($rsnew, $this->costo_unidad->CurrentValue, false);

        // costo
        $this->costo->setDbValueDef($rsnew, $this->costo->CurrentValue, false);

        // precio_unidad
        $this->precio_unidad->setDbValueDef($rsnew, $this->precio_unidad->CurrentValue, false);

        // precio
        $this->precio->setDbValueDef($rsnew, $this->precio->CurrentValue, false);

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
        if (isset($row['cantidad_articulo'])) { // cantidad_articulo
            $this->cantidad_articulo->setFormValue($row['cantidad_articulo']);
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
            if ($masterTblVar == "entradas") {
                $validMaster = true;
                $masterTbl = Container("entradas");
                if (($parm = Get("fk_tipo_documento", Get("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setQueryStringValue($parm);
                    $this->tipo_documento->QueryStringValue = $masterTbl->tipo_documento->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->tipo_documento->setSessionValue($this->tipo_documento->QueryStringValue);
                    $foreignKeys["tipo_documento"] = $this->tipo_documento->QueryStringValue;
                } else {
                    $validMaster = false;
                }
                if (($parm = Get("fk_id", Get("id_documento"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->id_documento->QueryStringValue = $masterTbl->id->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->id_documento->setSessionValue($this->id_documento->QueryStringValue);
                    $foreignKeys["id_documento"] = $this->id_documento->QueryStringValue;
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
                if (($parm = Get("fk_tipo_documento", Get("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setQueryStringValue($parm);
                    $this->tipo_documento->QueryStringValue = $masterTbl->tipo_documento->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->tipo_documento->setSessionValue($this->tipo_documento->QueryStringValue);
                    $foreignKeys["tipo_documento"] = $this->tipo_documento->QueryStringValue;
                } else {
                    $validMaster = false;
                }
                if (($parm = Get("fk_id", Get("id_documento"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->id_documento->QueryStringValue = $masterTbl->id->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->id_documento->setSessionValue($this->id_documento->QueryStringValue);
                    $foreignKeys["id_documento"] = $this->id_documento->QueryStringValue;
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
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
            if ($masterTblVar == "entradas") {
                $validMaster = true;
                $masterTbl = Container("entradas");
                if (($parm = Post("fk_tipo_documento", Post("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setFormValue($parm);
                    $this->tipo_documento->FormValue = $masterTbl->tipo_documento->FormValue;
                    $this->tipo_documento->setSessionValue($this->tipo_documento->FormValue);
                    $foreignKeys["tipo_documento"] = $this->tipo_documento->FormValue;
                } else {
                    $validMaster = false;
                }
                if (($parm = Post("fk_id", Post("id_documento"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->id_documento->FormValue = $masterTbl->id->FormValue;
                    $this->id_documento->setSessionValue($this->id_documento->FormValue);
                    $foreignKeys["id_documento"] = $this->id_documento->FormValue;
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
                if (($parm = Post("fk_tipo_documento", Post("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setFormValue($parm);
                    $this->tipo_documento->FormValue = $masterTbl->tipo_documento->FormValue;
                    $this->tipo_documento->setSessionValue($this->tipo_documento->FormValue);
                    $foreignKeys["tipo_documento"] = $this->tipo_documento->FormValue;
                } else {
                    $validMaster = false;
                }
                if (($parm = Post("fk_id", Post("id_documento"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->id_documento->FormValue = $masterTbl->id->FormValue;
                    $this->id_documento->setSessionValue($this->id_documento->FormValue);
                    $foreignKeys["id_documento"] = $this->id_documento->FormValue;
                    if (!is_numeric($masterTbl->id->FormValue)) {
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

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit() && !$this->isGridUpdate()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "entradas") {
                if (!array_key_exists("tipo_documento", $foreignKeys)) { // Not current foreign key
                    $this->tipo_documento->setSessionValue("");
                }
                if (!array_key_exists("id_documento", $foreignKeys)) { // Not current foreign key
                    $this->id_documento->setSessionValue("");
                }
            }
            if ($masterTblVar != "salidas") {
                if (!array_key_exists("tipo_documento", $foreignKeys)) { // Not current foreign key
                    $this->tipo_documento->setSessionValue("");
                }
                if (!array_key_exists("id_documento", $foreignKeys)) { // Not current foreign key
                    $this->id_documento->setSessionValue("");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EntradasSalidasList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
        if($this->tipo_documento->CurrentValue == "TDCFCV") {    
            if(VerificaFuncion("021")) {
            	$url = "SalidasList?tipo=TDCFCV";
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
    public function pageRender() {
    	//echo "Page Render";
    	$this->check_ne->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
    	$this->precio_unidad_sin_desc->Visible = FALSE;
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
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCFCC":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->lote->Visible = TURE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCAEN":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCPDV":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCNET":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCFCV":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCASA":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = TRUE;
    		break;
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
}
