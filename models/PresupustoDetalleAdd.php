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
class PresupustoDetalleAdd extends PresupustoDetalle
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "PresupustoDetalleAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "PresupustoDetalleAdd";

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
        $this->presupuesto->setVisibility();
        $this->grupo1->setVisibility();
        $this->grupo2->setVisibility();
        $this->numero->setVisibility();
        $this->articulo->setVisibility();
        $this->linea->setVisibility();
        $this->imagen->setVisibility();
        $this->descripcion->setVisibility();
        $this->cantidad->setVisibility();
        $this->precio->setVisibility();
        $this->total->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'presupusto_detalle';
        $this->TableName = 'presupusto_detalle';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (presupusto_detalle)
        if (!isset($GLOBALS["presupusto_detalle"]) || $GLOBALS["presupusto_detalle"]::class == PROJECT_NAMESPACE . "presupusto_detalle") {
            $GLOBALS["presupusto_detalle"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'presupusto_detalle');
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
                        $result["view"] = SameString($pageName, "PresupustoDetalleView"); // If View page, no primary button
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
                    $this->terminate("PresupustoDetalleList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "PresupustoDetalleList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "PresupustoDetalleView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "PresupustoDetalleList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "PresupustoDetalleList"; // Return list page content
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'presupuesto' first before field var 'x_presupuesto'
        $val = $CurrentForm->hasValue("presupuesto") ? $CurrentForm->getValue("presupuesto") : $CurrentForm->getValue("x_presupuesto");
        if (!$this->presupuesto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->presupuesto->Visible = false; // Disable update for API request
            } else {
                $this->presupuesto->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'grupo1' first before field var 'x_grupo1'
        $val = $CurrentForm->hasValue("grupo1") ? $CurrentForm->getValue("grupo1") : $CurrentForm->getValue("x_grupo1");
        if (!$this->grupo1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->grupo1->Visible = false; // Disable update for API request
            } else {
                $this->grupo1->setFormValue($val);
            }
        }

        // Check field name 'grupo2' first before field var 'x_grupo2'
        $val = $CurrentForm->hasValue("grupo2") ? $CurrentForm->getValue("grupo2") : $CurrentForm->getValue("x_grupo2");
        if (!$this->grupo2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->grupo2->Visible = false; // Disable update for API request
            } else {
                $this->grupo2->setFormValue($val);
            }
        }

        // Check field name 'numero' first before field var 'x_numero'
        $val = $CurrentForm->hasValue("numero") ? $CurrentForm->getValue("numero") : $CurrentForm->getValue("x_numero");
        if (!$this->numero->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->numero->Visible = false; // Disable update for API request
            } else {
                $this->numero->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val);
            }
        }

        // Check field name 'linea' first before field var 'x_linea'
        $val = $CurrentForm->hasValue("linea") ? $CurrentForm->getValue("linea") : $CurrentForm->getValue("x_linea");
        if (!$this->linea->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->linea->Visible = false; // Disable update for API request
            } else {
                $this->linea->setFormValue($val);
            }
        }

        // Check field name 'imagen' first before field var 'x_imagen'
        $val = $CurrentForm->hasValue("imagen") ? $CurrentForm->getValue("imagen") : $CurrentForm->getValue("x_imagen");
        if (!$this->imagen->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->imagen->Visible = false; // Disable update for API request
            } else {
                $this->imagen->setFormValue($val);
            }
        }

        // Check field name 'descripcion' first before field var 'x_descripcion'
        $val = $CurrentForm->hasValue("descripcion") ? $CurrentForm->getValue("descripcion") : $CurrentForm->getValue("x_descripcion");
        if (!$this->descripcion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->descripcion->Visible = false; // Disable update for API request
            } else {
                $this->descripcion->setFormValue($val);
            }
        }

        // Check field name 'cantidad' first before field var 'x_cantidad'
        $val = $CurrentForm->hasValue("cantidad") ? $CurrentForm->getValue("cantidad") : $CurrentForm->getValue("x_cantidad");
        if (!$this->cantidad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad->Visible = false; // Disable update for API request
            } else {
                $this->cantidad->setFormValue($val, true, $validate);
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

        // Check field name 'total' first before field var 'x_total'
        $val = $CurrentForm->hasValue("total") ? $CurrentForm->getValue("total") : $CurrentForm->getValue("x_total");
        if (!$this->total->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->total->Visible = false; // Disable update for API request
            } else {
                $this->total->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->presupuesto->CurrentValue = $this->presupuesto->FormValue;
        $this->grupo1->CurrentValue = $this->grupo1->FormValue;
        $this->grupo2->CurrentValue = $this->grupo2->FormValue;
        $this->numero->CurrentValue = $this->numero->FormValue;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->linea->CurrentValue = $this->linea->FormValue;
        $this->imagen->CurrentValue = $this->imagen->FormValue;
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->cantidad->CurrentValue = $this->cantidad->FormValue;
        $this->precio->CurrentValue = $this->precio->FormValue;
        $this->total->CurrentValue = $this->total->FormValue;
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
        $this->presupuesto->setDbValue($row['presupuesto']);
        $this->grupo1->setDbValue($row['grupo1']);
        $this->grupo2->setDbValue($row['grupo2']);
        $this->numero->setDbValue($row['numero']);
        $this->articulo->setDbValue($row['articulo']);
        $this->linea->setDbValue($row['linea']);
        $this->imagen->setDbValue($row['imagen']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->cantidad->setDbValue($row['cantidad']);
        $this->precio->setDbValue($row['precio']);
        $this->total->setDbValue($row['total']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['presupuesto'] = $this->presupuesto->DefaultValue;
        $row['grupo1'] = $this->grupo1->DefaultValue;
        $row['grupo2'] = $this->grupo2->DefaultValue;
        $row['numero'] = $this->numero->DefaultValue;
        $row['articulo'] = $this->articulo->DefaultValue;
        $row['linea'] = $this->linea->DefaultValue;
        $row['imagen'] = $this->imagen->DefaultValue;
        $row['descripcion'] = $this->descripcion->DefaultValue;
        $row['cantidad'] = $this->cantidad->DefaultValue;
        $row['precio'] = $this->precio->DefaultValue;
        $row['total'] = $this->total->DefaultValue;
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

        // presupuesto
        $this->presupuesto->RowCssClass = "row";

        // grupo1
        $this->grupo1->RowCssClass = "row";

        // grupo2
        $this->grupo2->RowCssClass = "row";

        // numero
        $this->numero->RowCssClass = "row";

        // articulo
        $this->articulo->RowCssClass = "row";

        // linea
        $this->linea->RowCssClass = "row";

        // imagen
        $this->imagen->RowCssClass = "row";

        // descripcion
        $this->descripcion->RowCssClass = "row";

        // cantidad
        $this->cantidad->RowCssClass = "row";

        // precio
        $this->precio->RowCssClass = "row";

        // total
        $this->total->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // presupuesto
            $this->presupuesto->ViewValue = $this->presupuesto->CurrentValue;
            $this->presupuesto->ViewValue = FormatNumber($this->presupuesto->ViewValue, $this->presupuesto->formatPattern());

            // grupo1
            $this->grupo1->ViewValue = $this->grupo1->CurrentValue;

            // grupo2
            $this->grupo2->ViewValue = $this->grupo2->CurrentValue;

            // numero
            $this->numero->ViewValue = $this->numero->CurrentValue;
            $this->numero->ViewValue = FormatNumber($this->numero->ViewValue, $this->numero->formatPattern());

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;

            // linea
            $this->linea->ViewValue = $this->linea->CurrentValue;

            // imagen
            $this->imagen->ViewValue = $this->imagen->CurrentValue;

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

            // cantidad
            $this->cantidad->ViewValue = $this->cantidad->CurrentValue;
            $this->cantidad->ViewValue = FormatNumber($this->cantidad->ViewValue, $this->cantidad->formatPattern());

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->formatPattern());

            // total
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->formatPattern());

            // presupuesto
            $this->presupuesto->HrefValue = "";

            // grupo1
            $this->grupo1->HrefValue = "";

            // grupo2
            $this->grupo2->HrefValue = "";

            // numero
            $this->numero->HrefValue = "";

            // articulo
            $this->articulo->HrefValue = "";

            // linea
            $this->linea->HrefValue = "";

            // imagen
            $this->imagen->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // cantidad
            $this->cantidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

            // total
            $this->total->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // presupuesto
            $this->presupuesto->setupEditAttributes();
            $this->presupuesto->EditValue = $this->presupuesto->CurrentValue;
            $this->presupuesto->PlaceHolder = RemoveHtml($this->presupuesto->caption());
            if (strval($this->presupuesto->EditValue) != "" && is_numeric($this->presupuesto->EditValue)) {
                $this->presupuesto->EditValue = FormatNumber($this->presupuesto->EditValue, null);
            }

            // grupo1
            $this->grupo1->setupEditAttributes();
            if (!$this->grupo1->Raw) {
                $this->grupo1->CurrentValue = HtmlDecode($this->grupo1->CurrentValue);
            }
            $this->grupo1->EditValue = HtmlEncode($this->grupo1->CurrentValue);
            $this->grupo1->PlaceHolder = RemoveHtml($this->grupo1->caption());

            // grupo2
            $this->grupo2->setupEditAttributes();
            if (!$this->grupo2->Raw) {
                $this->grupo2->CurrentValue = HtmlDecode($this->grupo2->CurrentValue);
            }
            $this->grupo2->EditValue = HtmlEncode($this->grupo2->CurrentValue);
            $this->grupo2->PlaceHolder = RemoveHtml($this->grupo2->caption());

            // numero
            $this->numero->setupEditAttributes();
            $this->numero->EditValue = $this->numero->CurrentValue;
            $this->numero->PlaceHolder = RemoveHtml($this->numero->caption());
            if (strval($this->numero->EditValue) != "" && is_numeric($this->numero->EditValue)) {
                $this->numero->EditValue = FormatNumber($this->numero->EditValue, null);
            }

            // articulo
            $this->articulo->setupEditAttributes();
            if (!$this->articulo->Raw) {
                $this->articulo->CurrentValue = HtmlDecode($this->articulo->CurrentValue);
            }
            $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

            // linea
            $this->linea->setupEditAttributes();
            if (!$this->linea->Raw) {
                $this->linea->CurrentValue = HtmlDecode($this->linea->CurrentValue);
            }
            $this->linea->EditValue = HtmlEncode($this->linea->CurrentValue);
            $this->linea->PlaceHolder = RemoveHtml($this->linea->caption());

            // imagen
            $this->imagen->setupEditAttributes();
            if (!$this->imagen->Raw) {
                $this->imagen->CurrentValue = HtmlDecode($this->imagen->CurrentValue);
            }
            $this->imagen->EditValue = HtmlEncode($this->imagen->CurrentValue);
            $this->imagen->PlaceHolder = RemoveHtml($this->imagen->caption());

            // descripcion
            $this->descripcion->setupEditAttributes();
            if (!$this->descripcion->Raw) {
                $this->descripcion->CurrentValue = HtmlDecode($this->descripcion->CurrentValue);
            }
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->CurrentValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // cantidad
            $this->cantidad->setupEditAttributes();
            $this->cantidad->EditValue = $this->cantidad->CurrentValue;
            $this->cantidad->PlaceHolder = RemoveHtml($this->cantidad->caption());
            if (strval($this->cantidad->EditValue) != "" && is_numeric($this->cantidad->EditValue)) {
                $this->cantidad->EditValue = FormatNumber($this->cantidad->EditValue, null);
            }

            // precio
            $this->precio->setupEditAttributes();
            $this->precio->EditValue = $this->precio->CurrentValue;
            $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
            if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
                $this->precio->EditValue = FormatNumber($this->precio->EditValue, null);
            }

            // total
            $this->total->setupEditAttributes();
            $this->total->EditValue = $this->total->CurrentValue;
            $this->total->PlaceHolder = RemoveHtml($this->total->caption());
            if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
                $this->total->EditValue = FormatNumber($this->total->EditValue, null);
            }

            // Add refer script

            // presupuesto
            $this->presupuesto->HrefValue = "";

            // grupo1
            $this->grupo1->HrefValue = "";

            // grupo2
            $this->grupo2->HrefValue = "";

            // numero
            $this->numero->HrefValue = "";

            // articulo
            $this->articulo->HrefValue = "";

            // linea
            $this->linea->HrefValue = "";

            // imagen
            $this->imagen->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // cantidad
            $this->cantidad->HrefValue = "";

            // precio
            $this->precio->HrefValue = "";

            // total
            $this->total->HrefValue = "";
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
            if ($this->presupuesto->Visible && $this->presupuesto->Required) {
                if (!$this->presupuesto->IsDetailKey && EmptyValue($this->presupuesto->FormValue)) {
                    $this->presupuesto->addErrorMessage(str_replace("%s", $this->presupuesto->caption(), $this->presupuesto->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->presupuesto->FormValue)) {
                $this->presupuesto->addErrorMessage($this->presupuesto->getErrorMessage(false));
            }
            if ($this->grupo1->Visible && $this->grupo1->Required) {
                if (!$this->grupo1->IsDetailKey && EmptyValue($this->grupo1->FormValue)) {
                    $this->grupo1->addErrorMessage(str_replace("%s", $this->grupo1->caption(), $this->grupo1->RequiredErrorMessage));
                }
            }
            if ($this->grupo2->Visible && $this->grupo2->Required) {
                if (!$this->grupo2->IsDetailKey && EmptyValue($this->grupo2->FormValue)) {
                    $this->grupo2->addErrorMessage(str_replace("%s", $this->grupo2->caption(), $this->grupo2->RequiredErrorMessage));
                }
            }
            if ($this->numero->Visible && $this->numero->Required) {
                if (!$this->numero->IsDetailKey && EmptyValue($this->numero->FormValue)) {
                    $this->numero->addErrorMessage(str_replace("%s", $this->numero->caption(), $this->numero->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->numero->FormValue)) {
                $this->numero->addErrorMessage($this->numero->getErrorMessage(false));
            }
            if ($this->articulo->Visible && $this->articulo->Required) {
                if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                    $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
                }
            }
            if ($this->linea->Visible && $this->linea->Required) {
                if (!$this->linea->IsDetailKey && EmptyValue($this->linea->FormValue)) {
                    $this->linea->addErrorMessage(str_replace("%s", $this->linea->caption(), $this->linea->RequiredErrorMessage));
                }
            }
            if ($this->imagen->Visible && $this->imagen->Required) {
                if (!$this->imagen->IsDetailKey && EmptyValue($this->imagen->FormValue)) {
                    $this->imagen->addErrorMessage(str_replace("%s", $this->imagen->caption(), $this->imagen->RequiredErrorMessage));
                }
            }
            if ($this->descripcion->Visible && $this->descripcion->Required) {
                if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                    $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
                }
            }
            if ($this->cantidad->Visible && $this->cantidad->Required) {
                if (!$this->cantidad->IsDetailKey && EmptyValue($this->cantidad->FormValue)) {
                    $this->cantidad->addErrorMessage(str_replace("%s", $this->cantidad->caption(), $this->cantidad->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->cantidad->FormValue)) {
                $this->cantidad->addErrorMessage($this->cantidad->getErrorMessage(false));
            }
            if ($this->precio->Visible && $this->precio->Required) {
                if (!$this->precio->IsDetailKey && EmptyValue($this->precio->FormValue)) {
                    $this->precio->addErrorMessage(str_replace("%s", $this->precio->caption(), $this->precio->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->precio->FormValue)) {
                $this->precio->addErrorMessage($this->precio->getErrorMessage(false));
            }
            if ($this->total->Visible && $this->total->Required) {
                if (!$this->total->IsDetailKey && EmptyValue($this->total->FormValue)) {
                    $this->total->addErrorMessage(str_replace("%s", $this->total->caption(), $this->total->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->total->FormValue)) {
                $this->total->addErrorMessage($this->total->getErrorMessage(false));
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

        // presupuesto
        $this->presupuesto->setDbValueDef($rsnew, $this->presupuesto->CurrentValue, false);

        // grupo1
        $this->grupo1->setDbValueDef($rsnew, $this->grupo1->CurrentValue, false);

        // grupo2
        $this->grupo2->setDbValueDef($rsnew, $this->grupo2->CurrentValue, false);

        // numero
        $this->numero->setDbValueDef($rsnew, $this->numero->CurrentValue, false);

        // articulo
        $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, false);

        // linea
        $this->linea->setDbValueDef($rsnew, $this->linea->CurrentValue, false);

        // imagen
        $this->imagen->setDbValueDef($rsnew, $this->imagen->CurrentValue, false);

        // descripcion
        $this->descripcion->setDbValueDef($rsnew, $this->descripcion->CurrentValue, false);

        // cantidad
        $this->cantidad->setDbValueDef($rsnew, $this->cantidad->CurrentValue, false);

        // precio
        $this->precio->setDbValueDef($rsnew, $this->precio->CurrentValue, false);

        // total
        $this->total->setDbValueDef($rsnew, $this->total->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['presupuesto'])) { // presupuesto
            $this->presupuesto->setFormValue($row['presupuesto']);
        }
        if (isset($row['grupo1'])) { // grupo1
            $this->grupo1->setFormValue($row['grupo1']);
        }
        if (isset($row['grupo2'])) { // grupo2
            $this->grupo2->setFormValue($row['grupo2']);
        }
        if (isset($row['numero'])) { // numero
            $this->numero->setFormValue($row['numero']);
        }
        if (isset($row['articulo'])) { // articulo
            $this->articulo->setFormValue($row['articulo']);
        }
        if (isset($row['linea'])) { // linea
            $this->linea->setFormValue($row['linea']);
        }
        if (isset($row['imagen'])) { // imagen
            $this->imagen->setFormValue($row['imagen']);
        }
        if (isset($row['descripcion'])) { // descripcion
            $this->descripcion->setFormValue($row['descripcion']);
        }
        if (isset($row['cantidad'])) { // cantidad
            $this->cantidad->setFormValue($row['cantidad']);
        }
        if (isset($row['precio'])) { // precio
            $this->precio->setFormValue($row['precio']);
        }
        if (isset($row['total'])) { // total
            $this->total->setFormValue($row['total']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PresupustoDetalleList"), "", $this->TableVar, true);
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
