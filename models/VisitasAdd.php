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
class VisitasAdd extends Visitas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "VisitasAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "VisitasAdd";

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
        $this->nombre->setVisibility();
        $this->apellido->setVisibility();
        $this->correo->setVisibility();
        $this->telefono->setVisibility();
        $this->producto->setVisibility();
        $this->referencia->setVisibility();
        $this->comentario->setVisibility();
        $this->seguimiento->setVisibility();
        $this->fecha->setVisibility();
        $this->fecha_registro->setVisibility();
        $this->usuario->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'visitas';
        $this->TableName = 'visitas';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (visitas)
        if (!isset($GLOBALS["visitas"]) || $GLOBALS["visitas"]::class == PROJECT_NAMESPACE . "visitas") {
            $GLOBALS["visitas"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'visitas');
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
                        $result["view"] = SameString($pageName, "VisitasView"); // If View page, no primary button
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
                    $this->terminate("VisitasList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "VisitasList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "VisitasView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "VisitasList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "VisitasList"; // Return list page content
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

        // Check field name 'nombre' first before field var 'x_nombre'
        $val = $CurrentForm->hasValue("nombre") ? $CurrentForm->getValue("nombre") : $CurrentForm->getValue("x_nombre");
        if (!$this->nombre->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nombre->Visible = false; // Disable update for API request
            } else {
                $this->nombre->setFormValue($val);
            }
        }

        // Check field name 'apellido' first before field var 'x_apellido'
        $val = $CurrentForm->hasValue("apellido") ? $CurrentForm->getValue("apellido") : $CurrentForm->getValue("x_apellido");
        if (!$this->apellido->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->apellido->Visible = false; // Disable update for API request
            } else {
                $this->apellido->setFormValue($val);
            }
        }

        // Check field name 'correo' first before field var 'x_correo'
        $val = $CurrentForm->hasValue("correo") ? $CurrentForm->getValue("correo") : $CurrentForm->getValue("x_correo");
        if (!$this->correo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->correo->Visible = false; // Disable update for API request
            } else {
                $this->correo->setFormValue($val);
            }
        }

        // Check field name 'telefono' first before field var 'x_telefono'
        $val = $CurrentForm->hasValue("telefono") ? $CurrentForm->getValue("telefono") : $CurrentForm->getValue("x_telefono");
        if (!$this->telefono->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->telefono->Visible = false; // Disable update for API request
            } else {
                $this->telefono->setFormValue($val);
            }
        }

        // Check field name 'producto' first before field var 'x_producto'
        $val = $CurrentForm->hasValue("producto") ? $CurrentForm->getValue("producto") : $CurrentForm->getValue("x_producto");
        if (!$this->producto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->producto->Visible = false; // Disable update for API request
            } else {
                $this->producto->setFormValue($val);
            }
        }

        // Check field name 'referencia' first before field var 'x_referencia'
        $val = $CurrentForm->hasValue("referencia") ? $CurrentForm->getValue("referencia") : $CurrentForm->getValue("x_referencia");
        if (!$this->referencia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->referencia->Visible = false; // Disable update for API request
            } else {
                $this->referencia->setFormValue($val);
            }
        }

        // Check field name 'comentario' first before field var 'x_comentario'
        $val = $CurrentForm->hasValue("comentario") ? $CurrentForm->getValue("comentario") : $CurrentForm->getValue("x_comentario");
        if (!$this->comentario->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->comentario->Visible = false; // Disable update for API request
            } else {
                $this->comentario->setFormValue($val);
            }
        }

        // Check field name 'seguimiento' first before field var 'x_seguimiento'
        $val = $CurrentForm->hasValue("seguimiento") ? $CurrentForm->getValue("seguimiento") : $CurrentForm->getValue("x_seguimiento");
        if (!$this->seguimiento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->seguimiento->Visible = false; // Disable update for API request
            } else {
                $this->seguimiento->setFormValue($val);
            }
        }

        // Check field name 'fecha' first before field var 'x_fecha'
        $val = $CurrentForm->hasValue("fecha") ? $CurrentForm->getValue("fecha") : $CurrentForm->getValue("x_fecha");
        if (!$this->fecha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha->Visible = false; // Disable update for API request
            } else {
                $this->fecha->setFormValue($val);
            }
        }

        // Check field name 'fecha_registro' first before field var 'x_fecha_registro'
        $val = $CurrentForm->hasValue("fecha_registro") ? $CurrentForm->getValue("fecha_registro") : $CurrentForm->getValue("x_fecha_registro");
        if (!$this->fecha_registro->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha_registro->Visible = false; // Disable update for API request
            } else {
                $this->fecha_registro->setFormValue($val, true, $validate);
            }
            $this->fecha_registro->CurrentValue = UnFormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern());
        }

        // Check field name 'usuario' first before field var 'x_usuario'
        $val = $CurrentForm->hasValue("usuario") ? $CurrentForm->getValue("usuario") : $CurrentForm->getValue("x_usuario");
        if (!$this->usuario->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->usuario->Visible = false; // Disable update for API request
            } else {
                $this->usuario->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->nombre->CurrentValue = $this->nombre->FormValue;
        $this->apellido->CurrentValue = $this->apellido->FormValue;
        $this->correo->CurrentValue = $this->correo->FormValue;
        $this->telefono->CurrentValue = $this->telefono->FormValue;
        $this->producto->CurrentValue = $this->producto->FormValue;
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->comentario->CurrentValue = $this->comentario->FormValue;
        $this->seguimiento->CurrentValue = $this->seguimiento->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha_registro->CurrentValue = $this->fecha_registro->FormValue;
        $this->fecha_registro->CurrentValue = UnFormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern());
        $this->usuario->CurrentValue = $this->usuario->FormValue;
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
        $this->nombre->setDbValue($row['nombre']);
        $this->apellido->setDbValue($row['apellido']);
        $this->correo->setDbValue($row['correo']);
        $this->telefono->setDbValue($row['telefono']);
        $this->producto->setDbValue($row['producto']);
        $this->referencia->setDbValue($row['referencia']);
        $this->comentario->setDbValue($row['comentario']);
        $this->seguimiento->setDbValue($row['seguimiento']);
        $this->fecha->setDbValue($row['fecha']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->usuario->setDbValue($row['usuario']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['apellido'] = $this->apellido->DefaultValue;
        $row['correo'] = $this->correo->DefaultValue;
        $row['telefono'] = $this->telefono->DefaultValue;
        $row['producto'] = $this->producto->DefaultValue;
        $row['referencia'] = $this->referencia->DefaultValue;
        $row['comentario'] = $this->comentario->DefaultValue;
        $row['seguimiento'] = $this->seguimiento->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['fecha_registro'] = $this->fecha_registro->DefaultValue;
        $row['usuario'] = $this->usuario->DefaultValue;
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

        // nombre
        $this->nombre->RowCssClass = "row";

        // apellido
        $this->apellido->RowCssClass = "row";

        // correo
        $this->correo->RowCssClass = "row";

        // telefono
        $this->telefono->RowCssClass = "row";

        // producto
        $this->producto->RowCssClass = "row";

        // referencia
        $this->referencia->RowCssClass = "row";

        // comentario
        $this->comentario->RowCssClass = "row";

        // seguimiento
        $this->seguimiento->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // fecha_registro
        $this->fecha_registro->RowCssClass = "row";

        // usuario
        $this->usuario->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;

            // apellido
            $this->apellido->ViewValue = $this->apellido->CurrentValue;

            // correo
            $this->correo->ViewValue = $this->correo->CurrentValue;

            // telefono
            $this->telefono->ViewValue = $this->telefono->CurrentValue;

            // producto
            $this->producto->ViewValue = $this->producto->CurrentValue;

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;

            // comentario
            $this->comentario->ViewValue = $this->comentario->CurrentValue;

            // seguimiento
            $this->seguimiento->ViewValue = $this->seguimiento->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;

            // fecha_registro
            $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
            $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, $this->fecha_registro->formatPattern());

            // usuario
            $this->usuario->ViewValue = $this->usuario->CurrentValue;

            // nombre
            $this->nombre->HrefValue = "";

            // apellido
            $this->apellido->HrefValue = "";

            // correo
            $this->correo->HrefValue = "";

            // telefono
            $this->telefono->HrefValue = "";

            // producto
            $this->producto->HrefValue = "";

            // referencia
            $this->referencia->HrefValue = "";

            // comentario
            $this->comentario->HrefValue = "";

            // seguimiento
            $this->seguimiento->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // fecha_registro
            $this->fecha_registro->HrefValue = "";

            // usuario
            $this->usuario->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // nombre
            $this->nombre->setupEditAttributes();
            if (!$this->nombre->Raw) {
                $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
            }
            $this->nombre->EditValue = HtmlEncode($this->nombre->CurrentValue);
            $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

            // apellido
            $this->apellido->setupEditAttributes();
            if (!$this->apellido->Raw) {
                $this->apellido->CurrentValue = HtmlDecode($this->apellido->CurrentValue);
            }
            $this->apellido->EditValue = HtmlEncode($this->apellido->CurrentValue);
            $this->apellido->PlaceHolder = RemoveHtml($this->apellido->caption());

            // correo
            $this->correo->setupEditAttributes();
            if (!$this->correo->Raw) {
                $this->correo->CurrentValue = HtmlDecode($this->correo->CurrentValue);
            }
            $this->correo->EditValue = HtmlEncode($this->correo->CurrentValue);
            $this->correo->PlaceHolder = RemoveHtml($this->correo->caption());

            // telefono
            $this->telefono->setupEditAttributes();
            if (!$this->telefono->Raw) {
                $this->telefono->CurrentValue = HtmlDecode($this->telefono->CurrentValue);
            }
            $this->telefono->EditValue = HtmlEncode($this->telefono->CurrentValue);
            $this->telefono->PlaceHolder = RemoveHtml($this->telefono->caption());

            // producto
            $this->producto->setupEditAttributes();
            if (!$this->producto->Raw) {
                $this->producto->CurrentValue = HtmlDecode($this->producto->CurrentValue);
            }
            $this->producto->EditValue = HtmlEncode($this->producto->CurrentValue);
            $this->producto->PlaceHolder = RemoveHtml($this->producto->caption());

            // referencia
            $this->referencia->setupEditAttributes();
            if (!$this->referencia->Raw) {
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // comentario
            $this->comentario->setupEditAttributes();
            if (!$this->comentario->Raw) {
                $this->comentario->CurrentValue = HtmlDecode($this->comentario->CurrentValue);
            }
            $this->comentario->EditValue = HtmlEncode($this->comentario->CurrentValue);
            $this->comentario->PlaceHolder = RemoveHtml($this->comentario->caption());

            // seguimiento
            $this->seguimiento->setupEditAttributes();
            if (!$this->seguimiento->Raw) {
                $this->seguimiento->CurrentValue = HtmlDecode($this->seguimiento->CurrentValue);
            }
            $this->seguimiento->EditValue = HtmlEncode($this->seguimiento->CurrentValue);
            $this->seguimiento->PlaceHolder = RemoveHtml($this->seguimiento->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            if (!$this->fecha->Raw) {
                $this->fecha->CurrentValue = HtmlDecode($this->fecha->CurrentValue);
            }
            $this->fecha->EditValue = HtmlEncode($this->fecha->CurrentValue);
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // fecha_registro
            $this->fecha_registro->setupEditAttributes();
            $this->fecha_registro->EditValue = HtmlEncode(FormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern()));
            $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

            // usuario
            $this->usuario->setupEditAttributes();
            if (!$this->usuario->Raw) {
                $this->usuario->CurrentValue = HtmlDecode($this->usuario->CurrentValue);
            }
            $this->usuario->EditValue = HtmlEncode($this->usuario->CurrentValue);
            $this->usuario->PlaceHolder = RemoveHtml($this->usuario->caption());

            // Add refer script

            // nombre
            $this->nombre->HrefValue = "";

            // apellido
            $this->apellido->HrefValue = "";

            // correo
            $this->correo->HrefValue = "";

            // telefono
            $this->telefono->HrefValue = "";

            // producto
            $this->producto->HrefValue = "";

            // referencia
            $this->referencia->HrefValue = "";

            // comentario
            $this->comentario->HrefValue = "";

            // seguimiento
            $this->seguimiento->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // fecha_registro
            $this->fecha_registro->HrefValue = "";

            // usuario
            $this->usuario->HrefValue = "";
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
            if ($this->nombre->Visible && $this->nombre->Required) {
                if (!$this->nombre->IsDetailKey && EmptyValue($this->nombre->FormValue)) {
                    $this->nombre->addErrorMessage(str_replace("%s", $this->nombre->caption(), $this->nombre->RequiredErrorMessage));
                }
            }
            if ($this->apellido->Visible && $this->apellido->Required) {
                if (!$this->apellido->IsDetailKey && EmptyValue($this->apellido->FormValue)) {
                    $this->apellido->addErrorMessage(str_replace("%s", $this->apellido->caption(), $this->apellido->RequiredErrorMessage));
                }
            }
            if ($this->correo->Visible && $this->correo->Required) {
                if (!$this->correo->IsDetailKey && EmptyValue($this->correo->FormValue)) {
                    $this->correo->addErrorMessage(str_replace("%s", $this->correo->caption(), $this->correo->RequiredErrorMessage));
                }
            }
            if ($this->telefono->Visible && $this->telefono->Required) {
                if (!$this->telefono->IsDetailKey && EmptyValue($this->telefono->FormValue)) {
                    $this->telefono->addErrorMessage(str_replace("%s", $this->telefono->caption(), $this->telefono->RequiredErrorMessage));
                }
            }
            if ($this->producto->Visible && $this->producto->Required) {
                if (!$this->producto->IsDetailKey && EmptyValue($this->producto->FormValue)) {
                    $this->producto->addErrorMessage(str_replace("%s", $this->producto->caption(), $this->producto->RequiredErrorMessage));
                }
            }
            if ($this->referencia->Visible && $this->referencia->Required) {
                if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                    $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
                }
            }
            if ($this->comentario->Visible && $this->comentario->Required) {
                if (!$this->comentario->IsDetailKey && EmptyValue($this->comentario->FormValue)) {
                    $this->comentario->addErrorMessage(str_replace("%s", $this->comentario->caption(), $this->comentario->RequiredErrorMessage));
                }
            }
            if ($this->seguimiento->Visible && $this->seguimiento->Required) {
                if (!$this->seguimiento->IsDetailKey && EmptyValue($this->seguimiento->FormValue)) {
                    $this->seguimiento->addErrorMessage(str_replace("%s", $this->seguimiento->caption(), $this->seguimiento->RequiredErrorMessage));
                }
            }
            if ($this->fecha->Visible && $this->fecha->Required) {
                if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                    $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
                }
            }
            if ($this->fecha_registro->Visible && $this->fecha_registro->Required) {
                if (!$this->fecha_registro->IsDetailKey && EmptyValue($this->fecha_registro->FormValue)) {
                    $this->fecha_registro->addErrorMessage(str_replace("%s", $this->fecha_registro->caption(), $this->fecha_registro->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecha_registro->FormValue, $this->fecha_registro->formatPattern())) {
                $this->fecha_registro->addErrorMessage($this->fecha_registro->getErrorMessage(false));
            }
            if ($this->usuario->Visible && $this->usuario->Required) {
                if (!$this->usuario->IsDetailKey && EmptyValue($this->usuario->FormValue)) {
                    $this->usuario->addErrorMessage(str_replace("%s", $this->usuario->caption(), $this->usuario->RequiredErrorMessage));
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

        // nombre
        $this->nombre->setDbValueDef($rsnew, $this->nombre->CurrentValue, false);

        // apellido
        $this->apellido->setDbValueDef($rsnew, $this->apellido->CurrentValue, false);

        // correo
        $this->correo->setDbValueDef($rsnew, $this->correo->CurrentValue, false);

        // telefono
        $this->telefono->setDbValueDef($rsnew, $this->telefono->CurrentValue, false);

        // producto
        $this->producto->setDbValueDef($rsnew, $this->producto->CurrentValue, false);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, false);

        // comentario
        $this->comentario->setDbValueDef($rsnew, $this->comentario->CurrentValue, false);

        // seguimiento
        $this->seguimiento->setDbValueDef($rsnew, $this->seguimiento->CurrentValue, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, $this->fecha->CurrentValue, false);

        // fecha_registro
        $this->fecha_registro->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern()), false);

        // usuario
        $this->usuario->setDbValueDef($rsnew, $this->usuario->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['nombre'])) { // nombre
            $this->nombre->setFormValue($row['nombre']);
        }
        if (isset($row['apellido'])) { // apellido
            $this->apellido->setFormValue($row['apellido']);
        }
        if (isset($row['correo'])) { // correo
            $this->correo->setFormValue($row['correo']);
        }
        if (isset($row['telefono'])) { // telefono
            $this->telefono->setFormValue($row['telefono']);
        }
        if (isset($row['producto'])) { // producto
            $this->producto->setFormValue($row['producto']);
        }
        if (isset($row['referencia'])) { // referencia
            $this->referencia->setFormValue($row['referencia']);
        }
        if (isset($row['comentario'])) { // comentario
            $this->comentario->setFormValue($row['comentario']);
        }
        if (isset($row['seguimiento'])) { // seguimiento
            $this->seguimiento->setFormValue($row['seguimiento']);
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->setFormValue($row['fecha']);
        }
        if (isset($row['fecha_registro'])) { // fecha_registro
            $this->fecha_registro->setFormValue($row['fecha_registro']);
        }
        if (isset($row['usuario'])) { // usuario
            $this->usuario->setFormValue($row['usuario']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("VisitasList"), "", $this->TableVar, true);
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
