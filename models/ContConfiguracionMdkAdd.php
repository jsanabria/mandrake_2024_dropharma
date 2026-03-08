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
class ContConfiguracionMdkAdd extends ContConfiguracionMdk
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ContConfiguracionMdkAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ContConfiguracionMdkAdd";

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
        $this->clave->setVisibility();
        $this->descripcion->setVisibility();
        $this->cuenta_id->setVisibility();
        $this->valor_texto->setVisibility();
        $this->valor_numero->setVisibility();
        $this->estado->setVisibility();
        $this->created_at->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cont_configuracion_mdk';
        $this->TableName = 'cont_configuracion_mdk';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cont_configuracion_mdk)
        if (!isset($GLOBALS["cont_configuracion_mdk"]) || $GLOBALS["cont_configuracion_mdk"]::class == PROJECT_NAMESPACE . "cont_configuracion_mdk") {
            $GLOBALS["cont_configuracion_mdk"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cont_configuracion_mdk');
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
                        $result["view"] = SameString($pageName, "ContConfiguracionMdkView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->cuenta_id);
        $this->setupLookupOptions($this->estado);

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
                    $this->terminate("ContConfiguracionMdkList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "ContConfiguracionMdkList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ContConfiguracionMdkView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "ContConfiguracionMdkList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ContConfiguracionMdkList"; // Return list page content
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
        $this->estado->DefaultValue = $this->estado->getDefault(); // PHP
        $this->estado->OldValue = $this->estado->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'clave' first before field var 'x_clave'
        $val = $CurrentForm->hasValue("clave") ? $CurrentForm->getValue("clave") : $CurrentForm->getValue("x_clave");
        if (!$this->clave->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->clave->Visible = false; // Disable update for API request
            } else {
                $this->clave->setFormValue($val);
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

        // Check field name 'cuenta_id' first before field var 'x_cuenta_id'
        $val = $CurrentForm->hasValue("cuenta_id") ? $CurrentForm->getValue("cuenta_id") : $CurrentForm->getValue("x_cuenta_id");
        if (!$this->cuenta_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cuenta_id->Visible = false; // Disable update for API request
            } else {
                $this->cuenta_id->setFormValue($val);
            }
        }

        // Check field name 'valor_texto' first before field var 'x_valor_texto'
        $val = $CurrentForm->hasValue("valor_texto") ? $CurrentForm->getValue("valor_texto") : $CurrentForm->getValue("x_valor_texto");
        if (!$this->valor_texto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->valor_texto->Visible = false; // Disable update for API request
            } else {
                $this->valor_texto->setFormValue($val);
            }
        }

        // Check field name 'valor_numero' first before field var 'x_valor_numero'
        $val = $CurrentForm->hasValue("valor_numero") ? $CurrentForm->getValue("valor_numero") : $CurrentForm->getValue("x_valor_numero");
        if (!$this->valor_numero->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->valor_numero->Visible = false; // Disable update for API request
            } else {
                $this->valor_numero->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'estado' first before field var 'x_estado'
        $val = $CurrentForm->hasValue("estado") ? $CurrentForm->getValue("estado") : $CurrentForm->getValue("x_estado");
        if (!$this->estado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estado->Visible = false; // Disable update for API request
            } else {
                $this->estado->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->clave->CurrentValue = $this->clave->FormValue;
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->cuenta_id->CurrentValue = $this->cuenta_id->FormValue;
        $this->valor_texto->CurrentValue = $this->valor_texto->FormValue;
        $this->valor_numero->CurrentValue = $this->valor_numero->FormValue;
        $this->estado->CurrentValue = $this->estado->FormValue;
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
        $this->clave->setDbValue($row['clave']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->cuenta_id->setDbValue($row['cuenta_id']);
        $this->valor_texto->setDbValue($row['valor_texto']);
        $this->valor_numero->setDbValue($row['valor_numero']);
        $this->estado->setDbValue($row['estado']);
        $this->created_at->setDbValue($row['created_at']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['clave'] = $this->clave->DefaultValue;
        $row['descripcion'] = $this->descripcion->DefaultValue;
        $row['cuenta_id'] = $this->cuenta_id->DefaultValue;
        $row['valor_texto'] = $this->valor_texto->DefaultValue;
        $row['valor_numero'] = $this->valor_numero->DefaultValue;
        $row['estado'] = $this->estado->DefaultValue;
        $row['created_at'] = $this->created_at->DefaultValue;
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

        // clave
        $this->clave->RowCssClass = "row";

        // descripcion
        $this->descripcion->RowCssClass = "row";

        // cuenta_id
        $this->cuenta_id->RowCssClass = "row";

        // valor_texto
        $this->valor_texto->RowCssClass = "row";

        // valor_numero
        $this->valor_numero->RowCssClass = "row";

        // estado
        $this->estado->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // clave
            $this->clave->ViewValue = $this->clave->CurrentValue;

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

            // cuenta_id
            $curVal = strval($this->cuenta_id->CurrentValue);
            if ($curVal != "") {
                $this->cuenta_id->ViewValue = $this->cuenta_id->lookupCacheOption($curVal);
                if ($this->cuenta_id->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cuenta_id->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta_id->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->cuenta_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta_id->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta_id->ViewValue = $this->cuenta_id->displayValue($arwrk);
                    } else {
                        $this->cuenta_id->ViewValue = FormatNumber($this->cuenta_id->CurrentValue, $this->cuenta_id->formatPattern());
                    }
                }
            } else {
                $this->cuenta_id->ViewValue = null;
            }

            // valor_texto
            $this->valor_texto->ViewValue = $this->valor_texto->CurrentValue;

            // valor_numero
            $this->valor_numero->ViewValue = $this->valor_numero->CurrentValue;
            $this->valor_numero->ViewValue = FormatNumber($this->valor_numero->ViewValue, $this->valor_numero->formatPattern());

            // estado
            if (ConvertToBool($this->estado->CurrentValue)) {
                $this->estado->ViewValue = $this->estado->tagCaption(1) != "" ? $this->estado->tagCaption(1) : "Yes";
            } else {
                $this->estado->ViewValue = $this->estado->tagCaption(2) != "" ? $this->estado->tagCaption(2) : "No";
            }

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // clave
            $this->clave->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // cuenta_id
            $this->cuenta_id->HrefValue = "";

            // valor_texto
            $this->valor_texto->HrefValue = "";

            // valor_numero
            $this->valor_numero->HrefValue = "";

            // estado
            $this->estado->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // clave
            $this->clave->setupEditAttributes();
            if (!$this->clave->Raw) {
                $this->clave->CurrentValue = HtmlDecode($this->clave->CurrentValue);
            }
            $this->clave->EditValue = HtmlEncode($this->clave->CurrentValue);
            $this->clave->PlaceHolder = RemoveHtml($this->clave->caption());

            // descripcion
            $this->descripcion->setupEditAttributes();
            if (!$this->descripcion->Raw) {
                $this->descripcion->CurrentValue = HtmlDecode($this->descripcion->CurrentValue);
            }
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->CurrentValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // cuenta_id
            $curVal = trim(strval($this->cuenta_id->CurrentValue));
            if ($curVal != "") {
                $this->cuenta_id->ViewValue = $this->cuenta_id->lookupCacheOption($curVal);
            } else {
                $this->cuenta_id->ViewValue = $this->cuenta_id->Lookup !== null && is_array($this->cuenta_id->lookupOptions()) && count($this->cuenta_id->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cuenta_id->ViewValue !== null) { // Load from cache
                $this->cuenta_id->EditValue = array_values($this->cuenta_id->lookupOptions());
                if ($this->cuenta_id->ViewValue == "") {
                    $this->cuenta_id->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cuenta_id->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cuenta_id->CurrentValue, $this->cuenta_id->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $sqlWrk = $this->cuenta_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta_id->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta_id->ViewValue = $this->cuenta_id->displayValue($arwrk);
                } else {
                    $this->cuenta_id->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cuenta_id->EditValue = $arwrk;
            }
            $this->cuenta_id->PlaceHolder = RemoveHtml($this->cuenta_id->caption());

            // valor_texto
            $this->valor_texto->setupEditAttributes();
            if (!$this->valor_texto->Raw) {
                $this->valor_texto->CurrentValue = HtmlDecode($this->valor_texto->CurrentValue);
            }
            $this->valor_texto->EditValue = HtmlEncode($this->valor_texto->CurrentValue);
            $this->valor_texto->PlaceHolder = RemoveHtml($this->valor_texto->caption());

            // valor_numero
            $this->valor_numero->setupEditAttributes();
            $this->valor_numero->EditValue = $this->valor_numero->CurrentValue;
            $this->valor_numero->PlaceHolder = RemoveHtml($this->valor_numero->caption());
            if (strval($this->valor_numero->EditValue) != "" && is_numeric($this->valor_numero->EditValue)) {
                $this->valor_numero->EditValue = FormatNumber($this->valor_numero->EditValue, null);
            }

            // estado
            $this->estado->EditValue = $this->estado->options(false);
            $this->estado->PlaceHolder = RemoveHtml($this->estado->caption());

            // Add refer script

            // clave
            $this->clave->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // cuenta_id
            $this->cuenta_id->HrefValue = "";

            // valor_texto
            $this->valor_texto->HrefValue = "";

            // valor_numero
            $this->valor_numero->HrefValue = "";

            // estado
            $this->estado->HrefValue = "";
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
            if ($this->clave->Visible && $this->clave->Required) {
                if (!$this->clave->IsDetailKey && EmptyValue($this->clave->FormValue)) {
                    $this->clave->addErrorMessage(str_replace("%s", $this->clave->caption(), $this->clave->RequiredErrorMessage));
                }
            }
            if ($this->descripcion->Visible && $this->descripcion->Required) {
                if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                    $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
                }
            }
            if ($this->cuenta_id->Visible && $this->cuenta_id->Required) {
                if (!$this->cuenta_id->IsDetailKey && EmptyValue($this->cuenta_id->FormValue)) {
                    $this->cuenta_id->addErrorMessage(str_replace("%s", $this->cuenta_id->caption(), $this->cuenta_id->RequiredErrorMessage));
                }
            }
            if ($this->valor_texto->Visible && $this->valor_texto->Required) {
                if (!$this->valor_texto->IsDetailKey && EmptyValue($this->valor_texto->FormValue)) {
                    $this->valor_texto->addErrorMessage(str_replace("%s", $this->valor_texto->caption(), $this->valor_texto->RequiredErrorMessage));
                }
            }
            if ($this->valor_numero->Visible && $this->valor_numero->Required) {
                if (!$this->valor_numero->IsDetailKey && EmptyValue($this->valor_numero->FormValue)) {
                    $this->valor_numero->addErrorMessage(str_replace("%s", $this->valor_numero->caption(), $this->valor_numero->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->valor_numero->FormValue)) {
                $this->valor_numero->addErrorMessage($this->valor_numero->getErrorMessage(false));
            }
            if ($this->estado->Visible && $this->estado->Required) {
                if ($this->estado->FormValue == "") {
                    $this->estado->addErrorMessage(str_replace("%s", $this->estado->caption(), $this->estado->RequiredErrorMessage));
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
        if ($this->clave->CurrentValue != "") { // Check field with unique index
            $filter = "(`clave` = '" . AdjustSql($this->clave->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->clave->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->clave->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
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

        // clave
        $this->clave->setDbValueDef($rsnew, $this->clave->CurrentValue, false);

        // descripcion
        $this->descripcion->setDbValueDef($rsnew, $this->descripcion->CurrentValue, false);

        // cuenta_id
        $this->cuenta_id->setDbValueDef($rsnew, $this->cuenta_id->CurrentValue, false);

        // valor_texto
        $this->valor_texto->setDbValueDef($rsnew, $this->valor_texto->CurrentValue, false);

        // valor_numero
        $this->valor_numero->setDbValueDef($rsnew, $this->valor_numero->CurrentValue, false);

        // estado
        $tmpBool = $this->estado->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->estado->setDbValueDef($rsnew, $tmpBool, strval($this->estado->CurrentValue) == "");
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['clave'])) { // clave
            $this->clave->setFormValue($row['clave']);
        }
        if (isset($row['descripcion'])) { // descripcion
            $this->descripcion->setFormValue($row['descripcion']);
        }
        if (isset($row['cuenta_id'])) { // cuenta_id
            $this->cuenta_id->setFormValue($row['cuenta_id']);
        }
        if (isset($row['valor_texto'])) { // valor_texto
            $this->valor_texto->setFormValue($row['valor_texto']);
        }
        if (isset($row['valor_numero'])) { // valor_numero
            $this->valor_numero->setFormValue($row['valor_numero']);
        }
        if (isset($row['estado'])) { // estado
            $this->estado->setFormValue($row['estado']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ContConfiguracionMdkList"), "", $this->TableVar, true);
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
                case "x_cuenta_id":
                    break;
                case "x_estado":
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
