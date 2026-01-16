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
class ArticuloAdd extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ArticuloAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ArticuloAdd";

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
        $this->codigo_de_barra->setVisibility();
        $this->categoria->setVisibility();
        $this->lista_pedido->setVisibility();
        $this->unidad_medida_defecto->setVisibility();
        $this->cantidad_por_unidad_medida->setVisibility();
        $this->cantidad_minima->setVisibility();
        $this->cantidad_maxima->setVisibility();
        $this->cantidad_en_mano->Visible = false;
        $this->cantidad_en_almacenes->Visible = false;
        $this->cantidad_en_pedido->Visible = false;
        $this->cantidad_en_transito->Visible = false;
        $this->ultimo_costo->Visible = false;
        $this->descuento->Visible = false;
        $this->precio->Visible = false;
        $this->alicuota->setVisibility();
        $this->articulo_inventario->setVisibility();
        $this->activo->setVisibility();
        $this->lote->Visible = false;
        $this->fecha_vencimiento->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'articulo';
        $this->TableName = 'articulo';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || $GLOBALS["articulo"]::class == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
        }

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
                        $result["view"] = SameString($pageName, "ArticuloView"); // If View page, no primary button
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
    public $MultiPages; // Multi pages object

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

        // Set up multi page object
        $this->setupMultiPages();

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
        $this->setupLookupOptions($this->categoria);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->unidad_medida_defecto);
        $this->setupLookupOptions($this->cantidad_por_unidad_medida);
        $this->setupLookupOptions($this->alicuota);
        $this->setupLookupOptions($this->articulo_inventario);
        $this->setupLookupOptions($this->activo);

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

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Set up detail parameters
        $this->setupDetailParms();

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
                    $this->terminate("ArticuloList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getViewUrl();
                    if (GetPageName($returnUrl) == "ArticuloList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ArticuloView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "ArticuloList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ArticuloList"; // Return list page content
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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
        $this->foto->Upload->Index = $CurrentForm->Index;
        $this->foto->Upload->uploadFile();
        $this->foto->CurrentValue = $this->foto->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->cantidad_minima->DefaultValue = $this->cantidad_minima->getDefault(); // PHP
        $this->cantidad_minima->OldValue = $this->cantidad_minima->DefaultValue;
        $this->cantidad_maxima->DefaultValue = $this->cantidad_maxima->getDefault(); // PHP
        $this->cantidad_maxima->OldValue = $this->cantidad_maxima->DefaultValue;
        $this->cantidad_en_mano->DefaultValue = $this->cantidad_en_mano->getDefault(); // PHP
        $this->cantidad_en_mano->OldValue = $this->cantidad_en_mano->DefaultValue;
        $this->cantidad_en_almacenes->DefaultValue = $this->cantidad_en_almacenes->getDefault(); // PHP
        $this->cantidad_en_almacenes->OldValue = $this->cantidad_en_almacenes->DefaultValue;
        $this->cantidad_en_pedido->DefaultValue = $this->cantidad_en_pedido->getDefault(); // PHP
        $this->cantidad_en_pedido->OldValue = $this->cantidad_en_pedido->DefaultValue;
        $this->cantidad_en_transito->DefaultValue = $this->cantidad_en_transito->getDefault(); // PHP
        $this->cantidad_en_transito->OldValue = $this->cantidad_en_transito->DefaultValue;
        $this->ultimo_costo->DefaultValue = $this->ultimo_costo->getDefault(); // PHP
        $this->ultimo_costo->OldValue = $this->ultimo_costo->DefaultValue;
        $this->descuento->DefaultValue = $this->descuento->getDefault(); // PHP
        $this->descuento->OldValue = $this->descuento->DefaultValue;
        $this->precio->DefaultValue = $this->precio->getDefault(); // PHP
        $this->precio->OldValue = $this->precio->DefaultValue;
        $this->alicuota->DefaultValue = $this->alicuota->getDefault(); // PHP
        $this->alicuota->OldValue = $this->alicuota->DefaultValue;
        $this->articulo_inventario->DefaultValue = $this->articulo_inventario->getDefault(); // PHP
        $this->articulo_inventario->OldValue = $this->articulo_inventario->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'codigo' first before field var 'x_codigo'
        $val = $CurrentForm->hasValue("codigo") ? $CurrentForm->getValue("codigo") : $CurrentForm->getValue("x_codigo");
        if (!$this->codigo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo->Visible = false; // Disable update for API request
            } else {
                $this->codigo->setFormValue($val);
            }
        }

        // Check field name 'nombre_comercial' first before field var 'x_nombre_comercial'
        $val = $CurrentForm->hasValue("nombre_comercial") ? $CurrentForm->getValue("nombre_comercial") : $CurrentForm->getValue("x_nombre_comercial");
        if (!$this->nombre_comercial->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nombre_comercial->Visible = false; // Disable update for API request
            } else {
                $this->nombre_comercial->setFormValue($val);
            }
        }

        // Check field name 'principio_activo' first before field var 'x_principio_activo'
        $val = $CurrentForm->hasValue("principio_activo") ? $CurrentForm->getValue("principio_activo") : $CurrentForm->getValue("x_principio_activo");
        if (!$this->principio_activo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->principio_activo->Visible = false; // Disable update for API request
            } else {
                $this->principio_activo->setFormValue($val);
            }
        }

        // Check field name 'presentacion' first before field var 'x_presentacion'
        $val = $CurrentForm->hasValue("presentacion") ? $CurrentForm->getValue("presentacion") : $CurrentForm->getValue("x_presentacion");
        if (!$this->presentacion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->presentacion->Visible = false; // Disable update for API request
            } else {
                $this->presentacion->setFormValue($val);
            }
        }

        // Check field name 'fabricante' first before field var 'x_fabricante'
        $val = $CurrentForm->hasValue("fabricante") ? $CurrentForm->getValue("fabricante") : $CurrentForm->getValue("x_fabricante");
        if (!$this->fabricante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fabricante->Visible = false; // Disable update for API request
            } else {
                $this->fabricante->setFormValue($val);
            }
        }

        // Check field name 'codigo_de_barra' first before field var 'x_codigo_de_barra'
        $val = $CurrentForm->hasValue("codigo_de_barra") ? $CurrentForm->getValue("codigo_de_barra") : $CurrentForm->getValue("x_codigo_de_barra");
        if (!$this->codigo_de_barra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo_de_barra->Visible = false; // Disable update for API request
            } else {
                $this->codigo_de_barra->setFormValue($val);
            }
        }

        // Check field name 'categoria' first before field var 'x_categoria'
        $val = $CurrentForm->hasValue("categoria") ? $CurrentForm->getValue("categoria") : $CurrentForm->getValue("x_categoria");
        if (!$this->categoria->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->categoria->Visible = false; // Disable update for API request
            } else {
                $this->categoria->setFormValue($val);
            }
        }

        // Check field name 'lista_pedido' first before field var 'x_lista_pedido'
        $val = $CurrentForm->hasValue("lista_pedido") ? $CurrentForm->getValue("lista_pedido") : $CurrentForm->getValue("x_lista_pedido");
        if (!$this->lista_pedido->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lista_pedido->Visible = false; // Disable update for API request
            } else {
                $this->lista_pedido->setFormValue($val);
            }
        }

        // Check field name 'unidad_medida_defecto' first before field var 'x_unidad_medida_defecto'
        $val = $CurrentForm->hasValue("unidad_medida_defecto") ? $CurrentForm->getValue("unidad_medida_defecto") : $CurrentForm->getValue("x_unidad_medida_defecto");
        if (!$this->unidad_medida_defecto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->unidad_medida_defecto->Visible = false; // Disable update for API request
            } else {
                $this->unidad_medida_defecto->setFormValue($val);
            }
        }

        // Check field name 'cantidad_por_unidad_medida' first before field var 'x_cantidad_por_unidad_medida'
        $val = $CurrentForm->hasValue("cantidad_por_unidad_medida") ? $CurrentForm->getValue("cantidad_por_unidad_medida") : $CurrentForm->getValue("x_cantidad_por_unidad_medida");
        if (!$this->cantidad_por_unidad_medida->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_por_unidad_medida->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_por_unidad_medida->setFormValue($val);
            }
        }

        // Check field name 'cantidad_minima' first before field var 'x_cantidad_minima'
        $val = $CurrentForm->hasValue("cantidad_minima") ? $CurrentForm->getValue("cantidad_minima") : $CurrentForm->getValue("x_cantidad_minima");
        if (!$this->cantidad_minima->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_minima->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_minima->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cantidad_maxima' first before field var 'x_cantidad_maxima'
        $val = $CurrentForm->hasValue("cantidad_maxima") ? $CurrentForm->getValue("cantidad_maxima") : $CurrentForm->getValue("x_cantidad_maxima");
        if (!$this->cantidad_maxima->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_maxima->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_maxima->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'alicuota' first before field var 'x_alicuota'
        $val = $CurrentForm->hasValue("alicuota") ? $CurrentForm->getValue("alicuota") : $CurrentForm->getValue("x_alicuota");
        if (!$this->alicuota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->alicuota->Visible = false; // Disable update for API request
            } else {
                $this->alicuota->setFormValue($val);
            }
        }

        // Check field name 'articulo_inventario' first before field var 'x_articulo_inventario'
        $val = $CurrentForm->hasValue("articulo_inventario") ? $CurrentForm->getValue("articulo_inventario") : $CurrentForm->getValue("x_articulo_inventario");
        if (!$this->articulo_inventario->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo_inventario->Visible = false; // Disable update for API request
            } else {
                $this->articulo_inventario->setFormValue($val);
            }
        }

        // Check field name 'activo' first before field var 'x_activo'
        $val = $CurrentForm->hasValue("activo") ? $CurrentForm->getValue("activo") : $CurrentForm->getValue("x_activo");
        if (!$this->activo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->activo->Visible = false; // Disable update for API request
            } else {
                $this->activo->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->codigo->CurrentValue = $this->codigo->FormValue;
        $this->nombre_comercial->CurrentValue = $this->nombre_comercial->FormValue;
        $this->principio_activo->CurrentValue = $this->principio_activo->FormValue;
        $this->presentacion->CurrentValue = $this->presentacion->FormValue;
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
        $this->codigo_de_barra->CurrentValue = $this->codigo_de_barra->FormValue;
        $this->categoria->CurrentValue = $this->categoria->FormValue;
        $this->lista_pedido->CurrentValue = $this->lista_pedido->FormValue;
        $this->unidad_medida_defecto->CurrentValue = $this->unidad_medida_defecto->FormValue;
        $this->cantidad_por_unidad_medida->CurrentValue = $this->cantidad_por_unidad_medida->FormValue;
        $this->cantidad_minima->CurrentValue = $this->cantidad_minima->FormValue;
        $this->cantidad_maxima->CurrentValue = $this->cantidad_maxima->FormValue;
        $this->alicuota->CurrentValue = $this->alicuota->FormValue;
        $this->articulo_inventario->CurrentValue = $this->articulo_inventario->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id
        $this->id->RowCssClass = "row";

        // foto
        $this->foto->RowCssClass = "row";

        // codigo
        $this->codigo->RowCssClass = "row";

        // nombre_comercial
        $this->nombre_comercial->RowCssClass = "row";

        // principio_activo
        $this->principio_activo->RowCssClass = "row";

        // presentacion
        $this->presentacion->RowCssClass = "row";

        // fabricante
        $this->fabricante->RowCssClass = "row";

        // codigo_ims
        $this->codigo_ims->RowCssClass = "row";

        // codigo_de_barra
        $this->codigo_de_barra->RowCssClass = "row";

        // categoria
        $this->categoria->RowCssClass = "row";

        // lista_pedido
        $this->lista_pedido->RowCssClass = "row";

        // unidad_medida_defecto
        $this->unidad_medida_defecto->RowCssClass = "row";

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->RowCssClass = "row";

        // cantidad_minima
        $this->cantidad_minima->RowCssClass = "row";

        // cantidad_maxima
        $this->cantidad_maxima->RowCssClass = "row";

        // cantidad_en_mano
        $this->cantidad_en_mano->RowCssClass = "row";

        // cantidad_en_almacenes
        $this->cantidad_en_almacenes->RowCssClass = "row";

        // cantidad_en_pedido
        $this->cantidad_en_pedido->RowCssClass = "row";

        // cantidad_en_transito
        $this->cantidad_en_transito->RowCssClass = "row";

        // ultimo_costo
        $this->ultimo_costo->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

        // precio
        $this->precio->RowCssClass = "row";

        // alicuota
        $this->alicuota->RowCssClass = "row";

        // articulo_inventario
        $this->articulo_inventario->RowCssClass = "row";

        // activo
        $this->activo->RowCssClass = "row";

        // lote
        $this->lote->RowCssClass = "row";

        // fecha_vencimiento
        $this->fecha_vencimiento->RowCssClass = "row";

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

            // unidad_medida_defecto
            $curVal = strval($this->unidad_medida_defecto->CurrentValue);
            if ($curVal != "") {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
                if ($this->unidad_medida_defecto->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                    $lookupFilter = $this->unidad_medida_defecto->getSelectFilter($this); // PHP
                    $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->unidad_medida_defecto->Lookup->renderViewRow($rswrk[0]);
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->displayValue($arwrk);
                    } else {
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->CurrentValue;
                    }
                }
            } else {
                $this->unidad_medida_defecto->ViewValue = null;
            }

            // cantidad_por_unidad_medida
            $curVal = strval($this->cantidad_por_unidad_medida->CurrentValue);
            if ($curVal != "") {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
                if ($this->cantidad_por_unidad_medida->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchExpression(), "=", $curVal, $this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchDataType(), "");
                    $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->CurrentValue;
                    }
                }
            } else {
                $this->cantidad_por_unidad_medida->ViewValue = null;
            }

            // cantidad_minima
            $this->cantidad_minima->ViewValue = $this->cantidad_minima->CurrentValue;
            $this->cantidad_minima->ViewValue = FormatNumber($this->cantidad_minima->ViewValue, $this->cantidad_minima->formatPattern());

            // cantidad_maxima
            $this->cantidad_maxima->ViewValue = $this->cantidad_maxima->CurrentValue;
            $this->cantidad_maxima->ViewValue = FormatNumber($this->cantidad_maxima->ViewValue, $this->cantidad_maxima->formatPattern());

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

            // articulo_inventario
            if (strval($this->articulo_inventario->CurrentValue) != "") {
                $this->articulo_inventario->ViewValue = $this->articulo_inventario->optionCaption($this->articulo_inventario->CurrentValue);
            } else {
                $this->articulo_inventario->ViewValue = null;
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

            // codigo
            $this->codigo->HrefValue = "";

            // nombre_comercial
            $this->nombre_comercial->HrefValue = "";

            // principio_activo
            $this->principio_activo->HrefValue = "";

            // presentacion
            $this->presentacion->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";

            // codigo_de_barra
            $this->codigo_de_barra->HrefValue = "";

            // categoria
            $this->categoria->HrefValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";

            // unidad_medida_defecto
            $this->unidad_medida_defecto->HrefValue = "";

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->HrefValue = "";

            // cantidad_minima
            $this->cantidad_minima->HrefValue = "";

            // cantidad_maxima
            $this->cantidad_maxima->HrefValue = "";

            // alicuota
            $this->alicuota->HrefValue = "";

            // articulo_inventario
            $this->articulo_inventario->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // foto
            $this->foto->setupEditAttributes();
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->ImageWidth = 120;
                $this->foto->ImageHeight = 120;
                $this->foto->ImageAlt = $this->foto->alt();
                $this->foto->ImageCssClass = "ew-image";
                $this->foto->EditValue = $this->foto->Upload->DbValue;
            } else {
                $this->foto->EditValue = "";
            }
            if (!EmptyValue($this->foto->CurrentValue)) {
                $this->foto->Upload->FileName = $this->foto->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->foto->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->foto);
            }

            // codigo
            $this->codigo->setupEditAttributes();
            if (!$this->codigo->Raw) {
                $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
            }
            $this->codigo->EditValue = HtmlEncode($this->codigo->CurrentValue);
            $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

            // nombre_comercial
            $this->nombre_comercial->setupEditAttributes();
            if (!$this->nombre_comercial->Raw) {
                $this->nombre_comercial->CurrentValue = HtmlDecode($this->nombre_comercial->CurrentValue);
            }
            $this->nombre_comercial->EditValue = HtmlEncode($this->nombre_comercial->CurrentValue);
            $this->nombre_comercial->PlaceHolder = RemoveHtml($this->nombre_comercial->caption());

            // principio_activo
            $this->principio_activo->setupEditAttributes();
            if (!$this->principio_activo->Raw) {
                $this->principio_activo->CurrentValue = HtmlDecode($this->principio_activo->CurrentValue);
            }
            $this->principio_activo->EditValue = HtmlEncode($this->principio_activo->CurrentValue);
            $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

            // presentacion
            $this->presentacion->setupEditAttributes();
            if (!$this->presentacion->Raw) {
                $this->presentacion->CurrentValue = HtmlDecode($this->presentacion->CurrentValue);
            }
            $this->presentacion->EditValue = HtmlEncode($this->presentacion->CurrentValue);
            $arwrk = [];
            $arwrk["lf"] = $this->presentacion->CurrentValue;
            $arwrk["df"] = $this->presentacion->CurrentValue;
            $arwrk = $this->presentacion->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->presentacion->displayValue($arwrk);
            if ($dispVal != "") {
                $this->presentacion->EditValue = $dispVal;
            }
            $this->presentacion->PlaceHolder = RemoveHtml($this->presentacion->caption());

            // fabricante
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            } else {
                $this->fabricante->ViewValue = $this->fabricante->Lookup !== null && is_array($this->fabricante->lookupOptions()) && count($this->fabricante->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->fabricante->ViewValue !== null) { // Load from cache
                $this->fabricante->EditValue = array_values($this->fabricante->lookupOptions());
                if ($this->fabricante->ViewValue == "") {
                    $this->fabricante->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->fabricante->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $this->fabricante->CurrentValue, $this->fabricante->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                }
                $sqlWrk = $this->fabricante->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->fabricante->EditValue = $arwrk;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // codigo_de_barra
            $this->codigo_de_barra->setupEditAttributes();
            if (!$this->codigo_de_barra->Raw) {
                $this->codigo_de_barra->CurrentValue = HtmlDecode($this->codigo_de_barra->CurrentValue);
            }
            $this->codigo_de_barra->EditValue = HtmlEncode($this->codigo_de_barra->CurrentValue);
            $this->codigo_de_barra->PlaceHolder = RemoveHtml($this->codigo_de_barra->caption());

            // categoria
            $this->categoria->setupEditAttributes();
            $curVal = trim(strval($this->categoria->CurrentValue));
            if ($curVal != "") {
                $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
            } else {
                $this->categoria->ViewValue = $this->categoria->Lookup !== null && is_array($this->categoria->lookupOptions()) && count($this->categoria->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->categoria->ViewValue !== null) { // Load from cache
                $this->categoria->EditValue = array_values($this->categoria->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->categoria->CurrentValue, $this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
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
            $curVal = trim(strval($this->lista_pedido->CurrentValue));
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            } else {
                $this->lista_pedido->ViewValue = $this->lista_pedido->Lookup !== null && is_array($this->lista_pedido->lookupOptions()) && count($this->lista_pedido->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->lista_pedido->ViewValue !== null) { // Load from cache
                $this->lista_pedido->EditValue = array_values($this->lista_pedido->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->lista_pedido->CurrentValue, $this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
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

            // unidad_medida_defecto
            $this->unidad_medida_defecto->setupEditAttributes();
            $curVal = trim(strval($this->unidad_medida_defecto->CurrentValue));
            if ($curVal != "") {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
            } else {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->Lookup !== null && is_array($this->unidad_medida_defecto->lookupOptions()) && count($this->unidad_medida_defecto->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->unidad_medida_defecto->ViewValue !== null) { // Load from cache
                $this->unidad_medida_defecto->EditValue = array_values($this->unidad_medida_defecto->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->unidad_medida_defecto->CurrentValue, $this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->unidad_medida_defecto->getSelectFilter($this); // PHP
                $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->unidad_medida_defecto->EditValue = $arwrk;
            }
            $this->unidad_medida_defecto->PlaceHolder = RemoveHtml($this->unidad_medida_defecto->caption());

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->setupEditAttributes();
            $curVal = trim(strval($this->cantidad_por_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
            } else {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->Lookup !== null && is_array($this->cantidad_por_unidad_medida->lookupOptions()) && count($this->cantidad_por_unidad_medida->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cantidad_por_unidad_medida->ViewValue !== null) { // Load from cache
                $this->cantidad_por_unidad_medida->EditValue = array_values($this->cantidad_por_unidad_medida->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchExpression(), "=", $this->cantidad_por_unidad_medida->CurrentValue, $this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchDataType(), "");
                }
                $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row) {
                    $row = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($row);
                }
                $this->cantidad_por_unidad_medida->EditValue = $arwrk;
            }
            $this->cantidad_por_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_por_unidad_medida->caption());

            // cantidad_minima
            $this->cantidad_minima->setupEditAttributes();
            $this->cantidad_minima->EditValue = $this->cantidad_minima->CurrentValue;
            $this->cantidad_minima->PlaceHolder = RemoveHtml($this->cantidad_minima->caption());
            if (strval($this->cantidad_minima->EditValue) != "" && is_numeric($this->cantidad_minima->EditValue)) {
                $this->cantidad_minima->EditValue = FormatNumber($this->cantidad_minima->EditValue, null);
            }

            // cantidad_maxima
            $this->cantidad_maxima->setupEditAttributes();
            $this->cantidad_maxima->EditValue = $this->cantidad_maxima->CurrentValue;
            $this->cantidad_maxima->PlaceHolder = RemoveHtml($this->cantidad_maxima->caption());
            if (strval($this->cantidad_maxima->EditValue) != "" && is_numeric($this->cantidad_maxima->EditValue)) {
                $this->cantidad_maxima->EditValue = FormatNumber($this->cantidad_maxima->EditValue, null);
            }

            // alicuota
            $this->alicuota->setupEditAttributes();
            $curVal = trim(strval($this->alicuota->CurrentValue));
            if ($curVal != "") {
                $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
            } else {
                $this->alicuota->ViewValue = $this->alicuota->Lookup !== null && is_array($this->alicuota->lookupOptions()) && count($this->alicuota->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->alicuota->ViewValue !== null) { // Load from cache
                $this->alicuota->EditValue = array_values($this->alicuota->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->alicuota->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->alicuota->CurrentValue, $this->alicuota->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->alicuota->getSelectFilter($this); // PHP
                $sqlWrk = $this->alicuota->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row) {
                    $row = $this->alicuota->Lookup->renderViewRow($row);
                }
                $this->alicuota->EditValue = $arwrk;
            }
            $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());

            // articulo_inventario
            $this->articulo_inventario->setupEditAttributes();
            $this->articulo_inventario->EditValue = $this->articulo_inventario->options(true);
            $this->articulo_inventario->PlaceHolder = RemoveHtml($this->articulo_inventario->caption());

            // activo
            $this->activo->setupEditAttributes();
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // Add refer script

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

            // codigo
            $this->codigo->HrefValue = "";

            // nombre_comercial
            $this->nombre_comercial->HrefValue = "";

            // principio_activo
            $this->principio_activo->HrefValue = "";

            // presentacion
            $this->presentacion->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";

            // codigo_de_barra
            $this->codigo_de_barra->HrefValue = "";

            // categoria
            $this->categoria->HrefValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";

            // unidad_medida_defecto
            $this->unidad_medida_defecto->HrefValue = "";

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->HrefValue = "";

            // cantidad_minima
            $this->cantidad_minima->HrefValue = "";

            // cantidad_maxima
            $this->cantidad_maxima->HrefValue = "";

            // alicuota
            $this->alicuota->HrefValue = "";

            // articulo_inventario
            $this->articulo_inventario->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";
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
            if ($this->foto->Visible && $this->foto->Required) {
                if ($this->foto->Upload->FileName == "" && !$this->foto->Upload->KeepFile) {
                    $this->foto->addErrorMessage(str_replace("%s", $this->foto->caption(), $this->foto->RequiredErrorMessage));
                }
            }
            if ($this->codigo->Visible && $this->codigo->Required) {
                if (!$this->codigo->IsDetailKey && EmptyValue($this->codigo->FormValue)) {
                    $this->codigo->addErrorMessage(str_replace("%s", $this->codigo->caption(), $this->codigo->RequiredErrorMessage));
                }
            }
            if ($this->nombre_comercial->Visible && $this->nombre_comercial->Required) {
                if (!$this->nombre_comercial->IsDetailKey && EmptyValue($this->nombre_comercial->FormValue)) {
                    $this->nombre_comercial->addErrorMessage(str_replace("%s", $this->nombre_comercial->caption(), $this->nombre_comercial->RequiredErrorMessage));
                }
            }
            if ($this->principio_activo->Visible && $this->principio_activo->Required) {
                if (!$this->principio_activo->IsDetailKey && EmptyValue($this->principio_activo->FormValue)) {
                    $this->principio_activo->addErrorMessage(str_replace("%s", $this->principio_activo->caption(), $this->principio_activo->RequiredErrorMessage));
                }
            }
            if ($this->presentacion->Visible && $this->presentacion->Required) {
                if (!$this->presentacion->IsDetailKey && EmptyValue($this->presentacion->FormValue)) {
                    $this->presentacion->addErrorMessage(str_replace("%s", $this->presentacion->caption(), $this->presentacion->RequiredErrorMessage));
                }
            }
            if ($this->fabricante->Visible && $this->fabricante->Required) {
                if (!$this->fabricante->IsDetailKey && EmptyValue($this->fabricante->FormValue)) {
                    $this->fabricante->addErrorMessage(str_replace("%s", $this->fabricante->caption(), $this->fabricante->RequiredErrorMessage));
                }
            }
            if ($this->codigo_de_barra->Visible && $this->codigo_de_barra->Required) {
                if (!$this->codigo_de_barra->IsDetailKey && EmptyValue($this->codigo_de_barra->FormValue)) {
                    $this->codigo_de_barra->addErrorMessage(str_replace("%s", $this->codigo_de_barra->caption(), $this->codigo_de_barra->RequiredErrorMessage));
                }
            }
            if ($this->categoria->Visible && $this->categoria->Required) {
                if (!$this->categoria->IsDetailKey && EmptyValue($this->categoria->FormValue)) {
                    $this->categoria->addErrorMessage(str_replace("%s", $this->categoria->caption(), $this->categoria->RequiredErrorMessage));
                }
            }
            if ($this->lista_pedido->Visible && $this->lista_pedido->Required) {
                if (!$this->lista_pedido->IsDetailKey && EmptyValue($this->lista_pedido->FormValue)) {
                    $this->lista_pedido->addErrorMessage(str_replace("%s", $this->lista_pedido->caption(), $this->lista_pedido->RequiredErrorMessage));
                }
            }
            if ($this->unidad_medida_defecto->Visible && $this->unidad_medida_defecto->Required) {
                if (!$this->unidad_medida_defecto->IsDetailKey && EmptyValue($this->unidad_medida_defecto->FormValue)) {
                    $this->unidad_medida_defecto->addErrorMessage(str_replace("%s", $this->unidad_medida_defecto->caption(), $this->unidad_medida_defecto->RequiredErrorMessage));
                }
            }
            if ($this->cantidad_por_unidad_medida->Visible && $this->cantidad_por_unidad_medida->Required) {
                if (!$this->cantidad_por_unidad_medida->IsDetailKey && EmptyValue($this->cantidad_por_unidad_medida->FormValue)) {
                    $this->cantidad_por_unidad_medida->addErrorMessage(str_replace("%s", $this->cantidad_por_unidad_medida->caption(), $this->cantidad_por_unidad_medida->RequiredErrorMessage));
                }
            }
            if ($this->cantidad_minima->Visible && $this->cantidad_minima->Required) {
                if (!$this->cantidad_minima->IsDetailKey && EmptyValue($this->cantidad_minima->FormValue)) {
                    $this->cantidad_minima->addErrorMessage(str_replace("%s", $this->cantidad_minima->caption(), $this->cantidad_minima->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_minima->FormValue)) {
                $this->cantidad_minima->addErrorMessage($this->cantidad_minima->getErrorMessage(false));
            }
            if ($this->cantidad_maxima->Visible && $this->cantidad_maxima->Required) {
                if (!$this->cantidad_maxima->IsDetailKey && EmptyValue($this->cantidad_maxima->FormValue)) {
                    $this->cantidad_maxima->addErrorMessage(str_replace("%s", $this->cantidad_maxima->caption(), $this->cantidad_maxima->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_maxima->FormValue)) {
                $this->cantidad_maxima->addErrorMessage($this->cantidad_maxima->getErrorMessage(false));
            }
            if ($this->alicuota->Visible && $this->alicuota->Required) {
                if (!$this->alicuota->IsDetailKey && EmptyValue($this->alicuota->FormValue)) {
                    $this->alicuota->addErrorMessage(str_replace("%s", $this->alicuota->caption(), $this->alicuota->RequiredErrorMessage));
                }
            }
            if ($this->articulo_inventario->Visible && $this->articulo_inventario->Required) {
                if (!$this->articulo_inventario->IsDetailKey && EmptyValue($this->articulo_inventario->FormValue)) {
                    $this->articulo_inventario->addErrorMessage(str_replace("%s", $this->articulo_inventario->caption(), $this->articulo_inventario->RequiredErrorMessage));
                }
            }
            if ($this->activo->Visible && $this->activo->Required) {
                if (!$this->activo->IsDetailKey && EmptyValue($this->activo->FormValue)) {
                    $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
                }
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ArticuloUnidadMedidaGrid");
        if (in_array("articulo_unidad_medida", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("AdjuntoGrid");
        if (in_array("adjunto", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
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
        if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
            if (!EmptyValue($this->foto->Upload->FileName)) {
                $this->foto->Upload->DbValue = null;
                FixUploadFileNames($this->foto);
                $this->foto->setDbValueDef($rsnew, $this->foto->Upload->FileName, false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
                    $this->foto->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->foto, $rsnew['foto'], false)) {
                        $this->setFailureMessage($Language->phrase("UploadError7"));
                        return false;
                    }
                }
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

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ArticuloUnidadMedidaGrid");
            if (in_array("articulo_unidad_medida", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->articulo->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "articulo_unidad_medida"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->articulo->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("AdjuntoGrid");
            if (in_array("adjunto", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->articulo->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "adjunto"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->articulo->setSessionValue(""); // Clear master key if insert failed
                }
            }
        }

        // Commit/Rollback transaction
        if ($this->getCurrentDetailTable() != "") {
            if ($addRow) {
                if ($this->UseTransaction) { // Commit transaction
                    if ($conn->isTransactionActive()) {
                        $conn->commit();
                    }
                }
            } else {
                if ($this->UseTransaction) { // Rollback transaction
                    if ($conn->isTransactionActive()) {
                        $conn->rollback();
                    }
                }
            }
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

        // foto
        if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
            if ($this->foto->Upload->FileName == "") {
                $rsnew['foto'] = null;
            } else {
                FixUploadTempFileNames($this->foto);
                $rsnew['foto'] = $this->foto->Upload->FileName;
            }
        }

        // codigo
        $this->codigo->setDbValueDef($rsnew, $this->codigo->CurrentValue, false);

        // nombre_comercial
        $this->nombre_comercial->setDbValueDef($rsnew, $this->nombre_comercial->CurrentValue, false);

        // principio_activo
        $this->principio_activo->setDbValueDef($rsnew, $this->principio_activo->CurrentValue, false);

        // presentacion
        $this->presentacion->setDbValueDef($rsnew, $this->presentacion->CurrentValue, false);

        // fabricante
        $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, false);

        // codigo_de_barra
        $this->codigo_de_barra->setDbValueDef($rsnew, $this->codigo_de_barra->CurrentValue, false);

        // categoria
        $this->categoria->setDbValueDef($rsnew, $this->categoria->CurrentValue, false);

        // lista_pedido
        $this->lista_pedido->setDbValueDef($rsnew, $this->lista_pedido->CurrentValue, false);

        // unidad_medida_defecto
        $this->unidad_medida_defecto->setDbValueDef($rsnew, $this->unidad_medida_defecto->CurrentValue, false);

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->setDbValueDef($rsnew, $this->cantidad_por_unidad_medida->CurrentValue, false);

        // cantidad_minima
        $this->cantidad_minima->setDbValueDef($rsnew, $this->cantidad_minima->CurrentValue, strval($this->cantidad_minima->CurrentValue) == "");

        // cantidad_maxima
        $this->cantidad_maxima->setDbValueDef($rsnew, $this->cantidad_maxima->CurrentValue, strval($this->cantidad_maxima->CurrentValue) == "");

        // alicuota
        $this->alicuota->setDbValueDef($rsnew, $this->alicuota->CurrentValue, false);

        // articulo_inventario
        $this->articulo_inventario->setDbValueDef($rsnew, $this->articulo_inventario->CurrentValue, strval($this->articulo_inventario->CurrentValue) == "");

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, strval($this->activo->CurrentValue) == "");
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['foto'])) { // foto
            $this->foto->setFormValue($row['foto']);
        }
        if (isset($row['codigo'])) { // codigo
            $this->codigo->setFormValue($row['codigo']);
        }
        if (isset($row['nombre_comercial'])) { // nombre_comercial
            $this->nombre_comercial->setFormValue($row['nombre_comercial']);
        }
        if (isset($row['principio_activo'])) { // principio_activo
            $this->principio_activo->setFormValue($row['principio_activo']);
        }
        if (isset($row['presentacion'])) { // presentacion
            $this->presentacion->setFormValue($row['presentacion']);
        }
        if (isset($row['fabricante'])) { // fabricante
            $this->fabricante->setFormValue($row['fabricante']);
        }
        if (isset($row['codigo_de_barra'])) { // codigo_de_barra
            $this->codigo_de_barra->setFormValue($row['codigo_de_barra']);
        }
        if (isset($row['categoria'])) { // categoria
            $this->categoria->setFormValue($row['categoria']);
        }
        if (isset($row['lista_pedido'])) { // lista_pedido
            $this->lista_pedido->setFormValue($row['lista_pedido']);
        }
        if (isset($row['unidad_medida_defecto'])) { // unidad_medida_defecto
            $this->unidad_medida_defecto->setFormValue($row['unidad_medida_defecto']);
        }
        if (isset($row['cantidad_por_unidad_medida'])) { // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->setFormValue($row['cantidad_por_unidad_medida']);
        }
        if (isset($row['cantidad_minima'])) { // cantidad_minima
            $this->cantidad_minima->setFormValue($row['cantidad_minima']);
        }
        if (isset($row['cantidad_maxima'])) { // cantidad_maxima
            $this->cantidad_maxima->setFormValue($row['cantidad_maxima']);
        }
        if (isset($row['alicuota'])) { // alicuota
            $this->alicuota->setFormValue($row['alicuota']);
        }
        if (isset($row['articulo_inventario'])) { // articulo_inventario
            $this->articulo_inventario->setFormValue($row['articulo_inventario']);
        }
        if (isset($row['activo'])) { // activo
            $this->activo->setFormValue($row['activo']);
        }
    }

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("articulo_unidad_medida", $detailTblVar)) {
                $detailPageObj = Container("ArticuloUnidadMedidaGrid");
                if ($detailPageObj->DetailAdd) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                }
            }
            if (in_array("adjunto", $detailTblVar)) {
                $detailPageObj = Container("AdjuntoGrid");
                if ($detailPageObj->DetailAdd) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                    $detailPageObj->cliente->setSessionValue(""); // Clear session key
                    $detailPageObj->proveedor->setSessionValue(""); // Clear session key
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ArticuloList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        if ($pages->isAccordion()) {
            $pages->Parent = "#accordion_" . $this->PageObjName;
        }
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $pages->add(3);
        $this->MultiPages = $pages;
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
