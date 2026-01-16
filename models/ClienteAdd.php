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
class ClienteAdd extends Cliente
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ClienteAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ClienteAdd";

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
        $this->ci_rif->setVisibility();
        $this->nombre->setVisibility();
        $this->sucursal->setVisibility();
        $this->contacto->setVisibility();
        $this->ciudad->setVisibility();
        $this->zona->setVisibility();
        $this->direccion->setVisibility();
        $this->telefono1->setVisibility();
        $this->telefono2->setVisibility();
        $this->email1->setVisibility();
        $this->email2->setVisibility();
        $this->codigo_ims->Visible = false;
        $this->web->setVisibility();
        $this->tipo_cliente->setVisibility();
        $this->tarifa->setVisibility();
        $this->consignacion->setVisibility();
        $this->limite_credito->setVisibility();
        $this->condicion->setVisibility();
        $this->cuenta->Visible = false;
        $this->activo->setVisibility();
        $this->foto1->setVisibility();
        $this->foto2->setVisibility();
        $this->dias_credito->setVisibility();
        $this->descuento->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cliente';
        $this->TableName = 'cliente';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cliente)
        if (!isset($GLOBALS["cliente"]) || $GLOBALS["cliente"]::class == PROJECT_NAMESPACE . "cliente") {
            $GLOBALS["cliente"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cliente');
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
                        $result["view"] = SameString($pageName, "ClienteView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->ciudad);
        $this->setupLookupOptions($this->tipo_cliente);
        $this->setupLookupOptions($this->tarifa);
        $this->setupLookupOptions($this->consignacion);
        $this->setupLookupOptions($this->condicion);
        $this->setupLookupOptions($this->cuenta);
        $this->setupLookupOptions($this->activo);
        $this->setupLookupOptions($this->dias_credito);

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
                    $this->terminate("ClienteList"); // No matching record, return to list
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
                    if ($this->getCurrentDetailTable() != "") { // Master/detail add
                        $returnUrl = $this->getDetailUrl();
                    } else {
                        $returnUrl = $this->getReturnUrl();
                    }
                    if (GetPageName($returnUrl) == "ClienteList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ClienteView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "ClienteList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ClienteList"; // Return list page content
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
        $this->foto1->Upload->Index = $CurrentForm->Index;
        $this->foto1->Upload->uploadFile();
        $this->foto1->CurrentValue = $this->foto1->Upload->FileName;
        $this->foto2->Upload->Index = $CurrentForm->Index;
        $this->foto2->Upload->uploadFile();
        $this->foto2->CurrentValue = $this->foto2->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->consignacion->DefaultValue = $this->consignacion->getDefault(); // PHP
        $this->consignacion->OldValue = $this->consignacion->DefaultValue;
        $this->activo->DefaultValue = $this->activo->getDefault(); // PHP
        $this->activo->OldValue = $this->activo->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'ci_rif' first before field var 'x_ci_rif'
        $val = $CurrentForm->hasValue("ci_rif") ? $CurrentForm->getValue("ci_rif") : $CurrentForm->getValue("x_ci_rif");
        if (!$this->ci_rif->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ci_rif->Visible = false; // Disable update for API request
            } else {
                $this->ci_rif->setFormValue($val);
            }
        }

        // Check field name 'nombre' first before field var 'x_nombre'
        $val = $CurrentForm->hasValue("nombre") ? $CurrentForm->getValue("nombre") : $CurrentForm->getValue("x_nombre");
        if (!$this->nombre->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nombre->Visible = false; // Disable update for API request
            } else {
                $this->nombre->setFormValue($val);
            }
        }

        // Check field name 'sucursal' first before field var 'x_sucursal'
        $val = $CurrentForm->hasValue("sucursal") ? $CurrentForm->getValue("sucursal") : $CurrentForm->getValue("x_sucursal");
        if (!$this->sucursal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sucursal->Visible = false; // Disable update for API request
            } else {
                $this->sucursal->setFormValue($val);
            }
        }

        // Check field name 'contacto' first before field var 'x_contacto'
        $val = $CurrentForm->hasValue("contacto") ? $CurrentForm->getValue("contacto") : $CurrentForm->getValue("x_contacto");
        if (!$this->contacto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->contacto->Visible = false; // Disable update for API request
            } else {
                $this->contacto->setFormValue($val);
            }
        }

        // Check field name 'ciudad' first before field var 'x_ciudad'
        $val = $CurrentForm->hasValue("ciudad") ? $CurrentForm->getValue("ciudad") : $CurrentForm->getValue("x_ciudad");
        if (!$this->ciudad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ciudad->Visible = false; // Disable update for API request
            } else {
                $this->ciudad->setFormValue($val);
            }
        }

        // Check field name 'zona' first before field var 'x_zona'
        $val = $CurrentForm->hasValue("zona") ? $CurrentForm->getValue("zona") : $CurrentForm->getValue("x_zona");
        if (!$this->zona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->zona->Visible = false; // Disable update for API request
            } else {
                $this->zona->setFormValue($val);
            }
        }

        // Check field name 'direccion' first before field var 'x_direccion'
        $val = $CurrentForm->hasValue("direccion") ? $CurrentForm->getValue("direccion") : $CurrentForm->getValue("x_direccion");
        if (!$this->direccion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->direccion->Visible = false; // Disable update for API request
            } else {
                $this->direccion->setFormValue($val);
            }
        }

        // Check field name 'telefono1' first before field var 'x_telefono1'
        $val = $CurrentForm->hasValue("telefono1") ? $CurrentForm->getValue("telefono1") : $CurrentForm->getValue("x_telefono1");
        if (!$this->telefono1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->telefono1->Visible = false; // Disable update for API request
            } else {
                $this->telefono1->setFormValue($val);
            }
        }

        // Check field name 'telefono2' first before field var 'x_telefono2'
        $val = $CurrentForm->hasValue("telefono2") ? $CurrentForm->getValue("telefono2") : $CurrentForm->getValue("x_telefono2");
        if (!$this->telefono2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->telefono2->Visible = false; // Disable update for API request
            } else {
                $this->telefono2->setFormValue($val);
            }
        }

        // Check field name 'email1' first before field var 'x_email1'
        $val = $CurrentForm->hasValue("email1") ? $CurrentForm->getValue("email1") : $CurrentForm->getValue("x_email1");
        if (!$this->email1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->email1->Visible = false; // Disable update for API request
            } else {
                $this->email1->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'email2' first before field var 'x_email2'
        $val = $CurrentForm->hasValue("email2") ? $CurrentForm->getValue("email2") : $CurrentForm->getValue("x_email2");
        if (!$this->email2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->email2->Visible = false; // Disable update for API request
            } else {
                $this->email2->setFormValue($val);
            }
        }

        // Check field name 'web' first before field var 'x_web'
        $val = $CurrentForm->hasValue("web") ? $CurrentForm->getValue("web") : $CurrentForm->getValue("x_web");
        if (!$this->web->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->web->Visible = false; // Disable update for API request
            } else {
                $this->web->setFormValue($val);
            }
        }

        // Check field name 'tipo_cliente' first before field var 'x_tipo_cliente'
        $val = $CurrentForm->hasValue("tipo_cliente") ? $CurrentForm->getValue("tipo_cliente") : $CurrentForm->getValue("x_tipo_cliente");
        if (!$this->tipo_cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_cliente->Visible = false; // Disable update for API request
            } else {
                $this->tipo_cliente->setFormValue($val);
            }
        }

        // Check field name 'tarifa' first before field var 'x_tarifa'
        $val = $CurrentForm->hasValue("tarifa") ? $CurrentForm->getValue("tarifa") : $CurrentForm->getValue("x_tarifa");
        if (!$this->tarifa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tarifa->Visible = false; // Disable update for API request
            } else {
                $this->tarifa->setFormValue($val);
            }
        }

        // Check field name 'consignacion' first before field var 'x_consignacion'
        $val = $CurrentForm->hasValue("consignacion") ? $CurrentForm->getValue("consignacion") : $CurrentForm->getValue("x_consignacion");
        if (!$this->consignacion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->consignacion->Visible = false; // Disable update for API request
            } else {
                $this->consignacion->setFormValue($val);
            }
        }

        // Check field name 'limite_credito' first before field var 'x_limite_credito'
        $val = $CurrentForm->hasValue("limite_credito") ? $CurrentForm->getValue("limite_credito") : $CurrentForm->getValue("x_limite_credito");
        if (!$this->limite_credito->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->limite_credito->Visible = false; // Disable update for API request
            } else {
                $this->limite_credito->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'condicion' first before field var 'x_condicion'
        $val = $CurrentForm->hasValue("condicion") ? $CurrentForm->getValue("condicion") : $CurrentForm->getValue("x_condicion");
        if (!$this->condicion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->condicion->Visible = false; // Disable update for API request
            } else {
                $this->condicion->setFormValue($val);
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

        // Check field name 'dias_credito' first before field var 'x_dias_credito'
        $val = $CurrentForm->hasValue("dias_credito") ? $CurrentForm->getValue("dias_credito") : $CurrentForm->getValue("x_dias_credito");
        if (!$this->dias_credito->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->dias_credito->Visible = false; // Disable update for API request
            } else {
                $this->dias_credito->setFormValue($val);
            }
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

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ci_rif->CurrentValue = $this->ci_rif->FormValue;
        $this->nombre->CurrentValue = $this->nombre->FormValue;
        $this->sucursal->CurrentValue = $this->sucursal->FormValue;
        $this->contacto->CurrentValue = $this->contacto->FormValue;
        $this->ciudad->CurrentValue = $this->ciudad->FormValue;
        $this->zona->CurrentValue = $this->zona->FormValue;
        $this->direccion->CurrentValue = $this->direccion->FormValue;
        $this->telefono1->CurrentValue = $this->telefono1->FormValue;
        $this->telefono2->CurrentValue = $this->telefono2->FormValue;
        $this->email1->CurrentValue = $this->email1->FormValue;
        $this->email2->CurrentValue = $this->email2->FormValue;
        $this->web->CurrentValue = $this->web->FormValue;
        $this->tipo_cliente->CurrentValue = $this->tipo_cliente->FormValue;
        $this->tarifa->CurrentValue = $this->tarifa->FormValue;
        $this->consignacion->CurrentValue = $this->consignacion->FormValue;
        $this->limite_credito->CurrentValue = $this->limite_credito->FormValue;
        $this->condicion->CurrentValue = $this->condicion->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
        $this->dias_credito->CurrentValue = $this->dias_credito->FormValue;
        $this->descuento->CurrentValue = $this->descuento->FormValue;
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->sucursal->setDbValue($row['sucursal']);
        $this->contacto->setDbValue($row['contacto']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->zona->setDbValue($row['zona']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->web->setDbValue($row['web']);
        $this->tipo_cliente->setDbValue($row['tipo_cliente']);
        $this->tarifa->setDbValue($row['tarifa']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->limite_credito->setDbValue($row['limite_credito']);
        $this->condicion->setDbValue($row['condicion']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->activo->setDbValue($row['activo']);
        $this->foto1->Upload->DbValue = $row['foto1'];
        $this->foto1->setDbValue($this->foto1->Upload->DbValue);
        $this->foto2->Upload->DbValue = $row['foto2'];
        $this->foto2->setDbValue($this->foto2->Upload->DbValue);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->descuento->setDbValue($row['descuento']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['ci_rif'] = $this->ci_rif->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['sucursal'] = $this->sucursal->DefaultValue;
        $row['contacto'] = $this->contacto->DefaultValue;
        $row['ciudad'] = $this->ciudad->DefaultValue;
        $row['zona'] = $this->zona->DefaultValue;
        $row['direccion'] = $this->direccion->DefaultValue;
        $row['telefono1'] = $this->telefono1->DefaultValue;
        $row['telefono2'] = $this->telefono2->DefaultValue;
        $row['email1'] = $this->email1->DefaultValue;
        $row['email2'] = $this->email2->DefaultValue;
        $row['codigo_ims'] = $this->codigo_ims->DefaultValue;
        $row['web'] = $this->web->DefaultValue;
        $row['tipo_cliente'] = $this->tipo_cliente->DefaultValue;
        $row['tarifa'] = $this->tarifa->DefaultValue;
        $row['consignacion'] = $this->consignacion->DefaultValue;
        $row['limite_credito'] = $this->limite_credito->DefaultValue;
        $row['condicion'] = $this->condicion->DefaultValue;
        $row['cuenta'] = $this->cuenta->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['foto1'] = $this->foto1->DefaultValue;
        $row['foto2'] = $this->foto2->DefaultValue;
        $row['dias_credito'] = $this->dias_credito->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
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

        // ci_rif
        $this->ci_rif->RowCssClass = "row";

        // nombre
        $this->nombre->RowCssClass = "row";

        // sucursal
        $this->sucursal->RowCssClass = "row";

        // contacto
        $this->contacto->RowCssClass = "row";

        // ciudad
        $this->ciudad->RowCssClass = "row";

        // zona
        $this->zona->RowCssClass = "row";

        // direccion
        $this->direccion->RowCssClass = "row";

        // telefono1
        $this->telefono1->RowCssClass = "row";

        // telefono2
        $this->telefono2->RowCssClass = "row";

        // email1
        $this->email1->RowCssClass = "row";

        // email2
        $this->email2->RowCssClass = "row";

        // codigo_ims
        $this->codigo_ims->RowCssClass = "row";

        // web
        $this->web->RowCssClass = "row";

        // tipo_cliente
        $this->tipo_cliente->RowCssClass = "row";

        // tarifa
        $this->tarifa->RowCssClass = "row";

        // consignacion
        $this->consignacion->RowCssClass = "row";

        // limite_credito
        $this->limite_credito->RowCssClass = "row";

        // condicion
        $this->condicion->RowCssClass = "row";

        // cuenta
        $this->cuenta->RowCssClass = "row";

        // activo
        $this->activo->RowCssClass = "row";

        // foto1
        $this->foto1->RowCssClass = "row";

        // foto2
        $this->foto2->RowCssClass = "row";

        // dias_credito
        $this->dias_credito->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;

            // sucursal
            $this->sucursal->ViewValue = $this->sucursal->CurrentValue;

            // contacto
            $this->contacto->ViewValue = $this->contacto->CurrentValue;

            // ciudad
            $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
            $curVal = strval($this->ciudad->CurrentValue);
            if ($curVal != "") {
                $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
                if ($this->ciudad->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->ciudad->getSelectFilter($this); // PHP
                    $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                        $this->ciudad->ViewValue = $this->ciudad->displayValue($arwrk);
                    } else {
                        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
                    }
                }
            } else {
                $this->ciudad->ViewValue = null;
            }

            // zona
            $this->zona->ViewValue = $this->zona->CurrentValue;

            // direccion
            $this->direccion->ViewValue = $this->direccion->CurrentValue;

            // telefono1
            $this->telefono1->ViewValue = $this->telefono1->CurrentValue;

            // telefono2
            $this->telefono2->ViewValue = $this->telefono2->CurrentValue;

            // email1
            $this->email1->ViewValue = $this->email1->CurrentValue;

            // email2
            $this->email2->ViewValue = $this->email2->CurrentValue;

            // codigo_ims
            $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;

            // web
            $this->web->ViewValue = $this->web->CurrentValue;

            // tipo_cliente
            $curVal = strval($this->tipo_cliente->CurrentValue);
            if ($curVal != "") {
                $this->tipo_cliente->ViewValue = $this->tipo_cliente->lookupCacheOption($curVal);
                if ($this->tipo_cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_cliente->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_cliente->ViewValue = $this->tipo_cliente->displayValue($arwrk);
                    } else {
                        $this->tipo_cliente->ViewValue = $this->tipo_cliente->CurrentValue;
                    }
                }
            } else {
                $this->tipo_cliente->ViewValue = null;
            }

            // tarifa
            $curVal = strval($this->tarifa->CurrentValue);
            if ($curVal != "") {
                $this->tarifa->ViewValue = $this->tarifa->lookupCacheOption($curVal);
                if ($this->tarifa->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tarifa->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->tarifa->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $lookupFilter = $this->tarifa->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tarifa->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tarifa->Lookup->renderViewRow($rswrk[0]);
                        $this->tarifa->ViewValue = $this->tarifa->displayValue($arwrk);
                    } else {
                        $this->tarifa->ViewValue = $this->tarifa->CurrentValue;
                    }
                }
            } else {
                $this->tarifa->ViewValue = null;
            }

            // consignacion
            if (strval($this->consignacion->CurrentValue) != "") {
                $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
            } else {
                $this->consignacion->ViewValue = null;
            }

            // limite_credito
            $this->limite_credito->ViewValue = $this->limite_credito->CurrentValue;
            $this->limite_credito->ViewValue = FormatNumber($this->limite_credito->ViewValue, $this->limite_credito->formatPattern());

            // condicion
            if (strval($this->condicion->CurrentValue) != "") {
                $this->condicion->ViewValue = $this->condicion->optionCaption($this->condicion->CurrentValue);
            } else {
                $this->condicion->ViewValue = null;
            }

            // cuenta
            $curVal = strval($this->cuenta->CurrentValue);
            if ($curVal != "") {
                $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
                if ($this->cuenta->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cuenta->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $lookupFilter = $this->cuenta->getSelectFilter($this); // PHP
                    $sqlWrk = $this->cuenta->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                    } else {
                        $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
                    }
                }
            } else {
                $this->cuenta->ViewValue = null;
            }

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // foto1
            if (!EmptyValue($this->foto1->Upload->DbValue)) {
                $this->foto1->ImageWidth = 120;
                $this->foto1->ImageHeight = 120;
                $this->foto1->ImageAlt = $this->foto1->alt();
                $this->foto1->ImageCssClass = "ew-image";
                $this->foto1->ViewValue = $this->foto1->Upload->DbValue;
            } else {
                $this->foto1->ViewValue = "";
            }

            // foto2
            if (!EmptyValue($this->foto2->Upload->DbValue)) {
                $this->foto2->ImageWidth = 120;
                $this->foto2->ImageHeight = 120;
                $this->foto2->ImageAlt = $this->foto2->alt();
                $this->foto2->ImageCssClass = "ew-image";
                $this->foto2->ViewValue = $this->foto2->Upload->DbValue;
            } else {
                $this->foto2->ViewValue = "";
            }

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
                        $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->CurrentValue, $this->dias_credito->formatPattern());
                    }
                }
            } else {
                $this->dias_credito->ViewValue = null;
            }

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // sucursal
            $this->sucursal->HrefValue = "";

            // contacto
            $this->contacto->HrefValue = "";

            // ciudad
            $this->ciudad->HrefValue = "";

            // zona
            $this->zona->HrefValue = "";

            // direccion
            $this->direccion->HrefValue = "";

            // telefono1
            $this->telefono1->HrefValue = "";

            // telefono2
            $this->telefono2->HrefValue = "";

            // email1
            $this->email1->HrefValue = "";

            // email2
            $this->email2->HrefValue = "";

            // web
            $this->web->HrefValue = "";

            // tipo_cliente
            $this->tipo_cliente->HrefValue = "";

            // tarifa
            $this->tarifa->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // limite_credito
            $this->limite_credito->HrefValue = "";

            // condicion
            $this->condicion->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";

            // foto1
            if (!EmptyValue($this->foto1->Upload->DbValue)) {
                $this->foto1->HrefValue = GetFileUploadUrl($this->foto1, $this->foto1->htmlDecode($this->foto1->Upload->DbValue)); // Add prefix/suffix
                $this->foto1->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto1->HrefValue = FullUrl($this->foto1->HrefValue, "href");
                }
            } else {
                $this->foto1->HrefValue = "";
            }
            $this->foto1->ExportHrefValue = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;

            // foto2
            if (!EmptyValue($this->foto2->Upload->DbValue)) {
                $this->foto2->HrefValue = GetFileUploadUrl($this->foto2, $this->foto2->htmlDecode($this->foto2->Upload->DbValue)); // Add prefix/suffix
                $this->foto2->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto2->HrefValue = FullUrl($this->foto2->HrefValue, "href");
                }
            } else {
                $this->foto2->HrefValue = "";
            }
            $this->foto2->ExportHrefValue = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;

            // dias_credito
            $this->dias_credito->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // ci_rif
            $this->ci_rif->setupEditAttributes();
            if (!$this->ci_rif->Raw) {
                $this->ci_rif->CurrentValue = HtmlDecode($this->ci_rif->CurrentValue);
            }
            $this->ci_rif->EditValue = HtmlEncode($this->ci_rif->CurrentValue);
            $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

            // nombre
            $this->nombre->setupEditAttributes();
            if (!$this->nombre->Raw) {
                $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
            }
            $this->nombre->EditValue = HtmlEncode($this->nombre->CurrentValue);
            $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

            // sucursal
            $this->sucursal->setupEditAttributes();
            if (!$this->sucursal->Raw) {
                $this->sucursal->CurrentValue = HtmlDecode($this->sucursal->CurrentValue);
            }
            $this->sucursal->EditValue = HtmlEncode($this->sucursal->CurrentValue);
            $this->sucursal->PlaceHolder = RemoveHtml($this->sucursal->caption());

            // contacto
            $this->contacto->setupEditAttributes();
            if (!$this->contacto->Raw) {
                $this->contacto->CurrentValue = HtmlDecode($this->contacto->CurrentValue);
            }
            $this->contacto->EditValue = HtmlEncode($this->contacto->CurrentValue);
            $this->contacto->PlaceHolder = RemoveHtml($this->contacto->caption());

            // ciudad
            $this->ciudad->setupEditAttributes();
            if (!$this->ciudad->Raw) {
                $this->ciudad->CurrentValue = HtmlDecode($this->ciudad->CurrentValue);
            }
            $this->ciudad->EditValue = HtmlEncode($this->ciudad->CurrentValue);
            $curVal = strval($this->ciudad->CurrentValue);
            if ($curVal != "") {
                $this->ciudad->EditValue = $this->ciudad->lookupCacheOption($curVal);
                if ($this->ciudad->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->ciudad->getSelectFilter($this); // PHP
                    $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                        $this->ciudad->EditValue = $this->ciudad->displayValue($arwrk);
                    } else {
                        $this->ciudad->EditValue = HtmlEncode($this->ciudad->CurrentValue);
                    }
                }
            } else {
                $this->ciudad->EditValue = null;
            }
            $this->ciudad->PlaceHolder = RemoveHtml($this->ciudad->caption());

            // zona
            $this->zona->setupEditAttributes();
            if (!$this->zona->Raw) {
                $this->zona->CurrentValue = HtmlDecode($this->zona->CurrentValue);
            }
            $this->zona->EditValue = HtmlEncode($this->zona->CurrentValue);
            $this->zona->PlaceHolder = RemoveHtml($this->zona->caption());

            // direccion
            $this->direccion->setupEditAttributes();
            $this->direccion->EditValue = HtmlEncode($this->direccion->CurrentValue);
            $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

            // telefono1
            $this->telefono1->setupEditAttributes();
            if (!$this->telefono1->Raw) {
                $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
            }
            $this->telefono1->EditValue = HtmlEncode($this->telefono1->CurrentValue);
            $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

            // telefono2
            $this->telefono2->setupEditAttributes();
            if (!$this->telefono2->Raw) {
                $this->telefono2->CurrentValue = HtmlDecode($this->telefono2->CurrentValue);
            }
            $this->telefono2->EditValue = HtmlEncode($this->telefono2->CurrentValue);
            $this->telefono2->PlaceHolder = RemoveHtml($this->telefono2->caption());

            // email1
            $this->email1->setupEditAttributes();
            if (!$this->email1->Raw) {
                $this->email1->CurrentValue = HtmlDecode($this->email1->CurrentValue);
            }
            $this->email1->EditValue = HtmlEncode($this->email1->CurrentValue);
            $this->email1->PlaceHolder = RemoveHtml($this->email1->caption());

            // email2
            $this->email2->setupEditAttributes();
            if (!$this->email2->Raw) {
                $this->email2->CurrentValue = HtmlDecode($this->email2->CurrentValue);
            }
            $this->email2->EditValue = HtmlEncode($this->email2->CurrentValue);
            $this->email2->PlaceHolder = RemoveHtml($this->email2->caption());

            // web
            $this->web->setupEditAttributes();
            if (!$this->web->Raw) {
                $this->web->CurrentValue = HtmlDecode($this->web->CurrentValue);
            }
            $this->web->EditValue = HtmlEncode($this->web->CurrentValue);
            $this->web->PlaceHolder = RemoveHtml($this->web->caption());

            // tipo_cliente
            $this->tipo_cliente->setupEditAttributes();
            $curVal = trim(strval($this->tipo_cliente->CurrentValue));
            if ($curVal != "") {
                $this->tipo_cliente->ViewValue = $this->tipo_cliente->lookupCacheOption($curVal);
            } else {
                $this->tipo_cliente->ViewValue = $this->tipo_cliente->Lookup !== null && is_array($this->tipo_cliente->lookupOptions()) && count($this->tipo_cliente->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_cliente->ViewValue !== null) { // Load from cache
                $this->tipo_cliente->EditValue = array_values($this->tipo_cliente->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->tipo_cliente->CurrentValue, $this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_cliente->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_cliente->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_cliente->EditValue = $arwrk;
            }
            $this->tipo_cliente->PlaceHolder = RemoveHtml($this->tipo_cliente->caption());

            // tarifa
            $this->tarifa->setupEditAttributes();
            $curVal = trim(strval($this->tarifa->CurrentValue));
            if ($curVal != "") {
                $this->tarifa->ViewValue = $this->tarifa->lookupCacheOption($curVal);
            } else {
                $this->tarifa->ViewValue = $this->tarifa->Lookup !== null && is_array($this->tarifa->lookupOptions()) && count($this->tarifa->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tarifa->ViewValue !== null) { // Load from cache
                $this->tarifa->EditValue = array_values($this->tarifa->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tarifa->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->tarifa->CurrentValue, $this->tarifa->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $lookupFilter = $this->tarifa->getSelectFilter($this); // PHP
                $sqlWrk = $this->tarifa->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tarifa->EditValue = $arwrk;
            }
            $this->tarifa->PlaceHolder = RemoveHtml($this->tarifa->caption());

            // consignacion
            $this->consignacion->setupEditAttributes();
            $this->consignacion->EditValue = $this->consignacion->options(true);
            $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

            // limite_credito
            $this->limite_credito->setupEditAttributes();
            $this->limite_credito->EditValue = $this->limite_credito->CurrentValue;
            $this->limite_credito->PlaceHolder = RemoveHtml($this->limite_credito->caption());
            if (strval($this->limite_credito->EditValue) != "" && is_numeric($this->limite_credito->EditValue)) {
                $this->limite_credito->EditValue = FormatNumber($this->limite_credito->EditValue, null);
            }

            // condicion
            $this->condicion->EditValue = $this->condicion->options(false);
            $this->condicion->PlaceHolder = RemoveHtml($this->condicion->caption());

            // activo
            $this->activo->setupEditAttributes();
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // foto1
            $this->foto1->setupEditAttributes();
            if (!EmptyValue($this->foto1->Upload->DbValue)) {
                $this->foto1->ImageWidth = 120;
                $this->foto1->ImageHeight = 120;
                $this->foto1->ImageAlt = $this->foto1->alt();
                $this->foto1->ImageCssClass = "ew-image";
                $this->foto1->EditValue = $this->foto1->Upload->DbValue;
            } else {
                $this->foto1->EditValue = "";
            }
            if (!EmptyValue($this->foto1->CurrentValue)) {
                $this->foto1->Upload->FileName = $this->foto1->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->foto1->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->foto1);
            }

            // foto2
            $this->foto2->setupEditAttributes();
            if (!EmptyValue($this->foto2->Upload->DbValue)) {
                $this->foto2->ImageWidth = 120;
                $this->foto2->ImageHeight = 120;
                $this->foto2->ImageAlt = $this->foto2->alt();
                $this->foto2->ImageCssClass = "ew-image";
                $this->foto2->EditValue = $this->foto2->Upload->DbValue;
            } else {
                $this->foto2->EditValue = "";
            }
            if (!EmptyValue($this->foto2->CurrentValue)) {
                $this->foto2->Upload->FileName = $this->foto2->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->foto2->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->foto2);
            }

            // dias_credito
            $this->dias_credito->setupEditAttributes();
            $curVal = trim(strval($this->dias_credito->CurrentValue));
            if ($curVal != "") {
                $this->dias_credito->ViewValue = $this->dias_credito->lookupCacheOption($curVal);
            } else {
                $this->dias_credito->ViewValue = $this->dias_credito->Lookup !== null && is_array($this->dias_credito->lookupOptions()) && count($this->dias_credito->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->dias_credito->ViewValue !== null) { // Load from cache
                $this->dias_credito->EditValue = array_values($this->dias_credito->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->dias_credito->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $this->dias_credito->CurrentValue, $this->dias_credito->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                }
                $lookupFilter = $this->dias_credito->getSelectFilter($this); // PHP
                $sqlWrk = $this->dias_credito->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->dias_credito->EditValue = $arwrk;
            }
            $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->CurrentValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
            }

            // Add refer script

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // sucursal
            $this->sucursal->HrefValue = "";

            // contacto
            $this->contacto->HrefValue = "";

            // ciudad
            $this->ciudad->HrefValue = "";

            // zona
            $this->zona->HrefValue = "";

            // direccion
            $this->direccion->HrefValue = "";

            // telefono1
            $this->telefono1->HrefValue = "";

            // telefono2
            $this->telefono2->HrefValue = "";

            // email1
            $this->email1->HrefValue = "";

            // email2
            $this->email2->HrefValue = "";

            // web
            $this->web->HrefValue = "";

            // tipo_cliente
            $this->tipo_cliente->HrefValue = "";

            // tarifa
            $this->tarifa->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // limite_credito
            $this->limite_credito->HrefValue = "";

            // condicion
            $this->condicion->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";

            // foto1
            if (!EmptyValue($this->foto1->Upload->DbValue)) {
                $this->foto1->HrefValue = GetFileUploadUrl($this->foto1, $this->foto1->htmlDecode($this->foto1->Upload->DbValue)); // Add prefix/suffix
                $this->foto1->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto1->HrefValue = FullUrl($this->foto1->HrefValue, "href");
                }
            } else {
                $this->foto1->HrefValue = "";
            }
            $this->foto1->ExportHrefValue = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;

            // foto2
            if (!EmptyValue($this->foto2->Upload->DbValue)) {
                $this->foto2->HrefValue = GetFileUploadUrl($this->foto2, $this->foto2->htmlDecode($this->foto2->Upload->DbValue)); // Add prefix/suffix
                $this->foto2->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto2->HrefValue = FullUrl($this->foto2->HrefValue, "href");
                }
            } else {
                $this->foto2->HrefValue = "";
            }
            $this->foto2->ExportHrefValue = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;

            // dias_credito
            $this->dias_credito->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Render add option
    public function renderAddOpt($row)
    {
        global $Security, $Language, $CurrentLanguage;

        // Set up CurrentValue
        $this->ci_rif->CurrentValue = $row["ci_rif"] ?? null;
        $this->nombre->CurrentValue = $row["nombre"] ?? null;
        $this->sucursal->CurrentValue = $row["sucursal"] ?? null;
        $this->contacto->CurrentValue = $row["contacto"] ?? null;
        $this->ciudad->CurrentValue = $row["ciudad"] ?? null;
        $this->zona->CurrentValue = $row["zona"] ?? null;
        $this->direccion->CurrentValue = $row["direccion"] ?? null;
        $this->telefono1->CurrentValue = $row["telefono1"] ?? null;
        $this->telefono2->CurrentValue = $row["telefono2"] ?? null;
        $this->email1->CurrentValue = $row["email1"] ?? null;
        $this->email2->CurrentValue = $row["email2"] ?? null;
        $this->web->CurrentValue = $row["web"] ?? null;
        $this->tipo_cliente->CurrentValue = $row["tipo_cliente"] ?? null;
        $this->tarifa->CurrentValue = $row["tarifa"] ?? null;
        $this->consignacion->CurrentValue = $row["consignacion"] ?? null;
        $this->limite_credito->CurrentValue = $row["limite_credito"] ?? null;
        $this->condicion->CurrentValue = $row["condicion"] ?? null;
        $this->activo->CurrentValue = $row["activo"] ?? null;
        $this->foto1->CurrentValue = $row["foto1"] ?? null;
        $this->foto2->CurrentValue = $row["foto2"] ?? null;
        $this->dias_credito->CurrentValue = $row["dias_credito"] ?? null;
        $this->descuento->CurrentValue = $row["descuento"] ?? null;

        // ci_rif
        $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;

        // sucursal
        $this->sucursal->ViewValue = $this->sucursal->CurrentValue;

        // contacto
        $this->contacto->ViewValue = $this->contacto->CurrentValue;

        // ciudad
        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
        $curVal = strval($this->ciudad->CurrentValue);
        if ($curVal != "") {
            $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
            if ($this->ciudad->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $lookupFilter = $this->ciudad->getSelectFilter($this); // PHP
                $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                    $this->ciudad->ViewValue = $this->ciudad->displayValue($arwrk);
                } else {
                    $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
                }
            }
        } else {
            $this->ciudad->ViewValue = null;
        }

        // zona
        $this->zona->ViewValue = $this->zona->CurrentValue;

        // direccion
        $this->direccion->ViewValue = $this->direccion->CurrentValue;

        // telefono1
        $this->telefono1->ViewValue = $this->telefono1->CurrentValue;

        // telefono2
        $this->telefono2->ViewValue = $this->telefono2->CurrentValue;

        // email1
        $this->email1->ViewValue = $this->email1->CurrentValue;

        // email2
        $this->email2->ViewValue = $this->email2->CurrentValue;

        // web
        $this->web->ViewValue = $this->web->CurrentValue;

        // tipo_cliente
        $curVal = strval($this->tipo_cliente->CurrentValue);
        if ($curVal != "") {
            $this->tipo_cliente->ViewValue = $this->tipo_cliente->lookupCacheOption($curVal);
            if ($this->tipo_cliente->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $lookupFilter = $this->tipo_cliente->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->displayValue($arwrk);
                } else {
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->CurrentValue;
                }
            }
        } else {
            $this->tipo_cliente->ViewValue = null;
        }

        // tarifa
        $curVal = strval($this->tarifa->CurrentValue);
        if ($curVal != "") {
            $this->tarifa->ViewValue = $this->tarifa->lookupCacheOption($curVal);
            if ($this->tarifa->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tarifa->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->tarifa->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $lookupFilter = $this->tarifa->getSelectFilter($this); // PHP
                $sqlWrk = $this->tarifa->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tarifa->Lookup->renderViewRow($rswrk[0]);
                    $this->tarifa->ViewValue = $this->tarifa->displayValue($arwrk);
                } else {
                    $this->tarifa->ViewValue = $this->tarifa->CurrentValue;
                }
            }
        } else {
            $this->tarifa->ViewValue = null;
        }

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }

        // limite_credito
        $this->limite_credito->ViewValue = $this->limite_credito->CurrentValue;
        $this->limite_credito->ViewValue = FormatNumber($this->limite_credito->ViewValue, $this->limite_credito->formatPattern());

        // condicion
        if (strval($this->condicion->CurrentValue) != "") {
            $this->condicion->ViewValue = $this->condicion->optionCaption($this->condicion->CurrentValue);
        } else {
            $this->condicion->ViewValue = null;
        }

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }

        // foto1
        if (!EmptyValue($this->foto1->Upload->DbValue)) {
            $this->foto1->ImageWidth = 120;
            $this->foto1->ImageHeight = 120;
            $this->foto1->ImageAlt = $this->foto1->alt();
            $this->foto1->ImageCssClass = "ew-image";
            $this->foto1->ViewValue = $this->foto1->Upload->DbValue;
        } else {
            $this->foto1->ViewValue = "";
        }

        // foto2
        if (!EmptyValue($this->foto2->Upload->DbValue)) {
            $this->foto2->ImageWidth = 120;
            $this->foto2->ImageHeight = 120;
            $this->foto2->ImageAlt = $this->foto2->alt();
            $this->foto2->ImageCssClass = "ew-image";
            $this->foto2->ViewValue = $this->foto2->Upload->DbValue;
        } else {
            $this->foto2->ViewValue = "";
        }

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
                    $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->CurrentValue, $this->dias_credito->formatPattern());
                }
            }
        } else {
            $this->dias_credito->ViewValue = null;
        }

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

        // Set up $row
        $row["ci_rif"] = $this->ci_rif->ViewValue;
        $row["nombre"] = $this->nombre->ViewValue;
        $row["sucursal"] = $this->sucursal->ViewValue;
        $row["contacto"] = $this->contacto->ViewValue;
        $row["ciudad"] = $this->ciudad->ViewValue;
        $row["zona"] = $this->zona->ViewValue;
        $row["direccion"] = $this->direccion->ViewValue;
        $row["telefono1"] = $this->telefono1->ViewValue;
        $row["telefono2"] = $this->telefono2->ViewValue;
        $row["email1"] = $this->email1->ViewValue;
        $row["email2"] = $this->email2->ViewValue;
        $row["web"] = $this->web->ViewValue;
        $row["tipo_cliente"] = $this->tipo_cliente->ViewValue;
        $row["tarifa"] = $this->tarifa->ViewValue;
        $row["consignacion"] = $this->consignacion->ViewValue;
        $row["limite_credito"] = $this->limite_credito->ViewValue;
        $row["condicion"] = $this->condicion->ViewValue;
        $row["activo"] = $this->activo->ViewValue;
        $row["foto1"] = $this->foto1->ViewValue;
        $row["foto2"] = $this->foto2->ViewValue;
        $row["dias_credito"] = $this->dias_credito->ViewValue;
        $row["descuento"] = $this->descuento->ViewValue;
        return $row;
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
            if ($this->ci_rif->Visible && $this->ci_rif->Required) {
                if (!$this->ci_rif->IsDetailKey && EmptyValue($this->ci_rif->FormValue)) {
                    $this->ci_rif->addErrorMessage(str_replace("%s", $this->ci_rif->caption(), $this->ci_rif->RequiredErrorMessage));
                }
            }
            if ($this->nombre->Visible && $this->nombre->Required) {
                if (!$this->nombre->IsDetailKey && EmptyValue($this->nombre->FormValue)) {
                    $this->nombre->addErrorMessage(str_replace("%s", $this->nombre->caption(), $this->nombre->RequiredErrorMessage));
                }
            }
            if ($this->sucursal->Visible && $this->sucursal->Required) {
                if (!$this->sucursal->IsDetailKey && EmptyValue($this->sucursal->FormValue)) {
                    $this->sucursal->addErrorMessage(str_replace("%s", $this->sucursal->caption(), $this->sucursal->RequiredErrorMessage));
                }
            }
            if ($this->contacto->Visible && $this->contacto->Required) {
                if (!$this->contacto->IsDetailKey && EmptyValue($this->contacto->FormValue)) {
                    $this->contacto->addErrorMessage(str_replace("%s", $this->contacto->caption(), $this->contacto->RequiredErrorMessage));
                }
            }
            if ($this->ciudad->Visible && $this->ciudad->Required) {
                if (!$this->ciudad->IsDetailKey && EmptyValue($this->ciudad->FormValue)) {
                    $this->ciudad->addErrorMessage(str_replace("%s", $this->ciudad->caption(), $this->ciudad->RequiredErrorMessage));
                }
            }
            if ($this->zona->Visible && $this->zona->Required) {
                if (!$this->zona->IsDetailKey && EmptyValue($this->zona->FormValue)) {
                    $this->zona->addErrorMessage(str_replace("%s", $this->zona->caption(), $this->zona->RequiredErrorMessage));
                }
            }
            if ($this->direccion->Visible && $this->direccion->Required) {
                if (!$this->direccion->IsDetailKey && EmptyValue($this->direccion->FormValue)) {
                    $this->direccion->addErrorMessage(str_replace("%s", $this->direccion->caption(), $this->direccion->RequiredErrorMessage));
                }
            }
            if ($this->telefono1->Visible && $this->telefono1->Required) {
                if (!$this->telefono1->IsDetailKey && EmptyValue($this->telefono1->FormValue)) {
                    $this->telefono1->addErrorMessage(str_replace("%s", $this->telefono1->caption(), $this->telefono1->RequiredErrorMessage));
                }
            }
            if ($this->telefono2->Visible && $this->telefono2->Required) {
                if (!$this->telefono2->IsDetailKey && EmptyValue($this->telefono2->FormValue)) {
                    $this->telefono2->addErrorMessage(str_replace("%s", $this->telefono2->caption(), $this->telefono2->RequiredErrorMessage));
                }
            }
            if ($this->email1->Visible && $this->email1->Required) {
                if (!$this->email1->IsDetailKey && EmptyValue($this->email1->FormValue)) {
                    $this->email1->addErrorMessage(str_replace("%s", $this->email1->caption(), $this->email1->RequiredErrorMessage));
                }
            }
            if (!CheckEmail($this->email1->FormValue)) {
                $this->email1->addErrorMessage($this->email1->getErrorMessage(false));
            }
            if ($this->email2->Visible && $this->email2->Required) {
                if (!$this->email2->IsDetailKey && EmptyValue($this->email2->FormValue)) {
                    $this->email2->addErrorMessage(str_replace("%s", $this->email2->caption(), $this->email2->RequiredErrorMessage));
                }
            }
            if ($this->web->Visible && $this->web->Required) {
                if (!$this->web->IsDetailKey && EmptyValue($this->web->FormValue)) {
                    $this->web->addErrorMessage(str_replace("%s", $this->web->caption(), $this->web->RequiredErrorMessage));
                }
            }
            if ($this->tipo_cliente->Visible && $this->tipo_cliente->Required) {
                if (!$this->tipo_cliente->IsDetailKey && EmptyValue($this->tipo_cliente->FormValue)) {
                    $this->tipo_cliente->addErrorMessage(str_replace("%s", $this->tipo_cliente->caption(), $this->tipo_cliente->RequiredErrorMessage));
                }
            }
            if ($this->tarifa->Visible && $this->tarifa->Required) {
                if (!$this->tarifa->IsDetailKey && EmptyValue($this->tarifa->FormValue)) {
                    $this->tarifa->addErrorMessage(str_replace("%s", $this->tarifa->caption(), $this->tarifa->RequiredErrorMessage));
                }
            }
            if ($this->consignacion->Visible && $this->consignacion->Required) {
                if (!$this->consignacion->IsDetailKey && EmptyValue($this->consignacion->FormValue)) {
                    $this->consignacion->addErrorMessage(str_replace("%s", $this->consignacion->caption(), $this->consignacion->RequiredErrorMessage));
                }
            }
            if ($this->limite_credito->Visible && $this->limite_credito->Required) {
                if (!$this->limite_credito->IsDetailKey && EmptyValue($this->limite_credito->FormValue)) {
                    $this->limite_credito->addErrorMessage(str_replace("%s", $this->limite_credito->caption(), $this->limite_credito->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->limite_credito->FormValue)) {
                $this->limite_credito->addErrorMessage($this->limite_credito->getErrorMessage(false));
            }
            if ($this->condicion->Visible && $this->condicion->Required) {
                if ($this->condicion->FormValue == "") {
                    $this->condicion->addErrorMessage(str_replace("%s", $this->condicion->caption(), $this->condicion->RequiredErrorMessage));
                }
            }
            if ($this->activo->Visible && $this->activo->Required) {
                if (!$this->activo->IsDetailKey && EmptyValue($this->activo->FormValue)) {
                    $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
                }
            }
            if ($this->foto1->Visible && $this->foto1->Required) {
                if ($this->foto1->Upload->FileName == "" && !$this->foto1->Upload->KeepFile) {
                    $this->foto1->addErrorMessage(str_replace("%s", $this->foto1->caption(), $this->foto1->RequiredErrorMessage));
                }
            }
            if ($this->foto2->Visible && $this->foto2->Required) {
                if ($this->foto2->Upload->FileName == "" && !$this->foto2->Upload->KeepFile) {
                    $this->foto2->addErrorMessage(str_replace("%s", $this->foto2->caption(), $this->foto2->RequiredErrorMessage));
                }
            }
            if ($this->dias_credito->Visible && $this->dias_credito->Required) {
                if (!$this->dias_credito->IsDetailKey && EmptyValue($this->dias_credito->FormValue)) {
                    $this->dias_credito->addErrorMessage(str_replace("%s", $this->dias_credito->caption(), $this->dias_credito->RequiredErrorMessage));
                }
            }
            if ($this->descuento->Visible && $this->descuento->Required) {
                if (!$this->descuento->IsDetailKey && EmptyValue($this->descuento->FormValue)) {
                    $this->descuento->addErrorMessage(str_replace("%s", $this->descuento->caption(), $this->descuento->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->descuento->FormValue)) {
                $this->descuento->addErrorMessage($this->descuento->getErrorMessage(false));
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
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
        if ($this->foto1->Visible && !$this->foto1->Upload->KeepFile) {
            if (!EmptyValue($this->foto1->Upload->FileName)) {
                $this->foto1->Upload->DbValue = null;
                FixUploadFileNames($this->foto1);
                $this->foto1->setDbValueDef($rsnew, $this->foto1->Upload->FileName, false);
            }
        }
        if ($this->foto2->Visible && !$this->foto2->Upload->KeepFile) {
            if (!EmptyValue($this->foto2->Upload->FileName)) {
                $this->foto2->Upload->DbValue = null;
                FixUploadFileNames($this->foto2);
                $this->foto2->setDbValueDef($rsnew, $this->foto2->Upload->FileName, false);
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
                if ($this->foto1->Visible && !$this->foto1->Upload->KeepFile) {
                    $this->foto1->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->foto1, $rsnew['foto1'], false)) {
                        $this->setFailureMessage($Language->phrase("UploadError7"));
                        return false;
                    }
                }
                if ($this->foto2->Visible && !$this->foto2->Upload->KeepFile) {
                    $this->foto2->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->foto2, $rsnew['foto2'], false)) {
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
            $detailPage = Container("AdjuntoGrid");
            if (in_array("adjunto", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->cliente->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "adjunto"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->cliente->setSessionValue(""); // Clear master key if insert failed
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
            if (Post("addopt") == "1") { // Render for add option response
                $row = $this->renderAddOpt($row);
            }
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

        // ci_rif
        $this->ci_rif->setDbValueDef($rsnew, $this->ci_rif->CurrentValue, false);

        // nombre
        $this->nombre->setDbValueDef($rsnew, $this->nombre->CurrentValue, false);

        // sucursal
        $this->sucursal->setDbValueDef($rsnew, $this->sucursal->CurrentValue, false);

        // contacto
        $this->contacto->setDbValueDef($rsnew, $this->contacto->CurrentValue, false);

        // ciudad
        $this->ciudad->setDbValueDef($rsnew, $this->ciudad->CurrentValue, false);

        // zona
        $this->zona->setDbValueDef($rsnew, $this->zona->CurrentValue, false);

        // direccion
        $this->direccion->setDbValueDef($rsnew, $this->direccion->CurrentValue, false);

        // telefono1
        $this->telefono1->setDbValueDef($rsnew, $this->telefono1->CurrentValue, false);

        // telefono2
        $this->telefono2->setDbValueDef($rsnew, $this->telefono2->CurrentValue, false);

        // email1
        $this->email1->setDbValueDef($rsnew, $this->email1->CurrentValue, false);

        // email2
        $this->email2->setDbValueDef($rsnew, $this->email2->CurrentValue, false);

        // web
        $this->web->setDbValueDef($rsnew, $this->web->CurrentValue, false);

        // tipo_cliente
        $this->tipo_cliente->setDbValueDef($rsnew, $this->tipo_cliente->CurrentValue, false);

        // tarifa
        $this->tarifa->setDbValueDef($rsnew, $this->tarifa->CurrentValue, false);

        // consignacion
        $this->consignacion->setDbValueDef($rsnew, $this->consignacion->CurrentValue, strval($this->consignacion->CurrentValue) == "");

        // limite_credito
        $this->limite_credito->setDbValueDef($rsnew, $this->limite_credito->CurrentValue, false);

        // condicion
        $this->condicion->setDbValueDef($rsnew, $this->condicion->CurrentValue, false);

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, strval($this->activo->CurrentValue) == "");

        // foto1
        if ($this->foto1->Visible && !$this->foto1->Upload->KeepFile) {
            if ($this->foto1->Upload->FileName == "") {
                $rsnew['foto1'] = null;
            } else {
                FixUploadTempFileNames($this->foto1);
                $rsnew['foto1'] = $this->foto1->Upload->FileName;
            }
        }

        // foto2
        if ($this->foto2->Visible && !$this->foto2->Upload->KeepFile) {
            if ($this->foto2->Upload->FileName == "") {
                $rsnew['foto2'] = null;
            } else {
                FixUploadTempFileNames($this->foto2);
                $rsnew['foto2'] = $this->foto2->Upload->FileName;
            }
        }

        // dias_credito
        $this->dias_credito->setDbValueDef($rsnew, $this->dias_credito->CurrentValue, false);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['ci_rif'])) { // ci_rif
            $this->ci_rif->setFormValue($row['ci_rif']);
        }
        if (isset($row['nombre'])) { // nombre
            $this->nombre->setFormValue($row['nombre']);
        }
        if (isset($row['sucursal'])) { // sucursal
            $this->sucursal->setFormValue($row['sucursal']);
        }
        if (isset($row['contacto'])) { // contacto
            $this->contacto->setFormValue($row['contacto']);
        }
        if (isset($row['ciudad'])) { // ciudad
            $this->ciudad->setFormValue($row['ciudad']);
        }
        if (isset($row['zona'])) { // zona
            $this->zona->setFormValue($row['zona']);
        }
        if (isset($row['direccion'])) { // direccion
            $this->direccion->setFormValue($row['direccion']);
        }
        if (isset($row['telefono1'])) { // telefono1
            $this->telefono1->setFormValue($row['telefono1']);
        }
        if (isset($row['telefono2'])) { // telefono2
            $this->telefono2->setFormValue($row['telefono2']);
        }
        if (isset($row['email1'])) { // email1
            $this->email1->setFormValue($row['email1']);
        }
        if (isset($row['email2'])) { // email2
            $this->email2->setFormValue($row['email2']);
        }
        if (isset($row['web'])) { // web
            $this->web->setFormValue($row['web']);
        }
        if (isset($row['tipo_cliente'])) { // tipo_cliente
            $this->tipo_cliente->setFormValue($row['tipo_cliente']);
        }
        if (isset($row['tarifa'])) { // tarifa
            $this->tarifa->setFormValue($row['tarifa']);
        }
        if (isset($row['consignacion'])) { // consignacion
            $this->consignacion->setFormValue($row['consignacion']);
        }
        if (isset($row['limite_credito'])) { // limite_credito
            $this->limite_credito->setFormValue($row['limite_credito']);
        }
        if (isset($row['condicion'])) { // condicion
            $this->condicion->setFormValue($row['condicion']);
        }
        if (isset($row['activo'])) { // activo
            $this->activo->setFormValue($row['activo']);
        }
        if (isset($row['foto1'])) { // foto1
            $this->foto1->setFormValue($row['foto1']);
        }
        if (isset($row['foto2'])) { // foto2
            $this->foto2->setFormValue($row['foto2']);
        }
        if (isset($row['dias_credito'])) { // dias_credito
            $this->dias_credito->setFormValue($row['dias_credito']);
        }
        if (isset($row['descuento'])) { // descuento
            $this->descuento->setFormValue($row['descuento']);
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
                    $detailPageObj->cliente->IsDetailKey = true;
                    $detailPageObj->cliente->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->cliente->setSessionValue($detailPageObj->cliente->CurrentValue);
                    $detailPageObj->articulo->setSessionValue(""); // Clear session key
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ClienteList"), "", $this->TableVar, true);
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
                case "x_ciudad":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_cliente":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tarifa":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_consignacion":
                    break;
                case "x_condicion":
                    break;
                case "x_cuenta":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_activo":
                    break;
                case "x_dias_credito":
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
