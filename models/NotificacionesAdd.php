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
class NotificacionesAdd extends Notificaciones
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "NotificacionesAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "NotificacionesAdd";

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
        $this->Nnotificaciones->Visible = false;
        $this->tipo->Visible = false;
        $this->notificar->setVisibility();
        $this->asunto->setVisibility();
        $this->notificacion->setVisibility();
        $this->notificados->setVisibility();
        $this->notificados_efectivos->setVisibility();
        $this->_username->setVisibility();
        $this->fecha->setVisibility();
        $this->enviado->setVisibility();
        $this->adjunto->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'notificaciones';
        $this->TableName = 'notificaciones';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (notificaciones)
        if (!isset($GLOBALS["notificaciones"]) || $GLOBALS["notificaciones"]::class == PROJECT_NAMESPACE . "notificaciones") {
            $GLOBALS["notificaciones"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'notificaciones');
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
                        $result["view"] = SameString($pageName, "NotificacionesView"); // If View page, no primary button
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
            $key .= @$ar['Nnotificaciones'];
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
            $this->Nnotificaciones->Visible = false;
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
        $this->setupLookupOptions($this->tipo);
        $this->setupLookupOptions($this->notificar);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->enviado);

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
            if (($keyValue = Get("Nnotificaciones") ?? Route("Nnotificaciones")) !== null) {
                $this->Nnotificaciones->setQueryStringValue($keyValue);
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
                    $this->terminate("NotificacionesList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "NotificacionesList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "NotificacionesView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "NotificacionesList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "NotificacionesList"; // Return list page content
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
        $this->adjunto->Upload->Index = $CurrentForm->Index;
        $this->adjunto->Upload->uploadFile();
        $this->adjunto->CurrentValue = $this->adjunto->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->enviado->DefaultValue = $this->enviado->getDefault(); // PHP
        $this->enviado->OldValue = $this->enviado->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'notificar' first before field var 'x_notificar'
        $val = $CurrentForm->hasValue("notificar") ? $CurrentForm->getValue("notificar") : $CurrentForm->getValue("x_notificar");
        if (!$this->notificar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificar->Visible = false; // Disable update for API request
            } else {
                $this->notificar->setFormValue($val);
            }
        }

        // Check field name 'asunto' first before field var 'x_asunto'
        $val = $CurrentForm->hasValue("asunto") ? $CurrentForm->getValue("asunto") : $CurrentForm->getValue("x_asunto");
        if (!$this->asunto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asunto->Visible = false; // Disable update for API request
            } else {
                $this->asunto->setFormValue($val);
            }
        }

        // Check field name 'notificacion' first before field var 'x_notificacion'
        $val = $CurrentForm->hasValue("notificacion") ? $CurrentForm->getValue("notificacion") : $CurrentForm->getValue("x_notificacion");
        if (!$this->notificacion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificacion->Visible = false; // Disable update for API request
            } else {
                $this->notificacion->setFormValue($val);
            }
        }

        // Check field name 'notificados' first before field var 'x_notificados'
        $val = $CurrentForm->hasValue("notificados") ? $CurrentForm->getValue("notificados") : $CurrentForm->getValue("x_notificados");
        if (!$this->notificados->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificados->Visible = false; // Disable update for API request
            } else {
                $this->notificados->setFormValue($val);
            }
        }

        // Check field name 'notificados_efectivos' first before field var 'x_notificados_efectivos'
        $val = $CurrentForm->hasValue("notificados_efectivos") ? $CurrentForm->getValue("notificados_efectivos") : $CurrentForm->getValue("x_notificados_efectivos");
        if (!$this->notificados_efectivos->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificados_efectivos->Visible = false; // Disable update for API request
            } else {
                $this->notificados_efectivos->setFormValue($val);
            }
        }

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
            }
        }

        // Check field name 'fecha' first before field var 'x_fecha'
        $val = $CurrentForm->hasValue("fecha") ? $CurrentForm->getValue("fecha") : $CurrentForm->getValue("x_fecha");
        if (!$this->fecha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha->Visible = false; // Disable update for API request
            } else {
                $this->fecha->setFormValue($val, true, $validate);
            }
            $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        }

        // Check field name 'enviado' first before field var 'x_enviado'
        $val = $CurrentForm->hasValue("enviado") ? $CurrentForm->getValue("enviado") : $CurrentForm->getValue("x_enviado");
        if (!$this->enviado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->enviado->Visible = false; // Disable update for API request
            } else {
                $this->enviado->setFormValue($val);
            }
        }

        // Check field name 'Nnotificaciones' first before field var 'x_Nnotificaciones'
        $val = $CurrentForm->hasValue("Nnotificaciones") ? $CurrentForm->getValue("Nnotificaciones") : $CurrentForm->getValue("x_Nnotificaciones");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->notificar->CurrentValue = $this->notificar->FormValue;
        $this->asunto->CurrentValue = $this->asunto->FormValue;
        $this->notificacion->CurrentValue = $this->notificacion->FormValue;
        $this->notificados->CurrentValue = $this->notificados->FormValue;
        $this->notificados_efectivos->CurrentValue = $this->notificados_efectivos->FormValue;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->enviado->CurrentValue = $this->enviado->FormValue;
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
        $this->Nnotificaciones->setDbValue($row['Nnotificaciones']);
        $this->tipo->setDbValue($row['tipo']);
        $this->notificar->setDbValue($row['notificar']);
        $this->asunto->setDbValue($row['asunto']);
        $this->notificacion->setDbValue($row['notificacion']);
        $this->notificados->setDbValue($row['notificados']);
        $this->notificados_efectivos->setDbValue($row['notificados_efectivos']);
        $this->_username->setDbValue($row['username']);
        $this->fecha->setDbValue($row['fecha']);
        $this->enviado->setDbValue($row['enviado']);
        $this->adjunto->Upload->DbValue = $row['adjunto'];
        $this->adjunto->setDbValue($this->adjunto->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Nnotificaciones'] = $this->Nnotificaciones->DefaultValue;
        $row['tipo'] = $this->tipo->DefaultValue;
        $row['notificar'] = $this->notificar->DefaultValue;
        $row['asunto'] = $this->asunto->DefaultValue;
        $row['notificacion'] = $this->notificacion->DefaultValue;
        $row['notificados'] = $this->notificados->DefaultValue;
        $row['notificados_efectivos'] = $this->notificados_efectivos->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['enviado'] = $this->enviado->DefaultValue;
        $row['adjunto'] = $this->adjunto->DefaultValue;
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

        // Nnotificaciones
        $this->Nnotificaciones->RowCssClass = "row";

        // tipo
        $this->tipo->RowCssClass = "row";

        // notificar
        $this->notificar->RowCssClass = "row";

        // asunto
        $this->asunto->RowCssClass = "row";

        // notificacion
        $this->notificacion->RowCssClass = "row";

        // notificados
        $this->notificados->RowCssClass = "row";

        // notificados_efectivos
        $this->notificados_efectivos->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // enviado
        $this->enviado->RowCssClass = "row";

        // adjunto
        $this->adjunto->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // Nnotificaciones
            $this->Nnotificaciones->ViewValue = $this->Nnotificaciones->CurrentValue;

            // tipo
            $curVal = strval($this->tipo->CurrentValue);
            if ($curVal != "") {
                $this->tipo->ViewValue = $this->tipo->lookupCacheOption($curVal);
                if ($this->tipo->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->tipo->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->tipo->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo->ViewValue = $this->tipo->displayValue($arwrk);
                    } else {
                        $this->tipo->ViewValue = $this->tipo->CurrentValue;
                    }
                }
            } else {
                $this->tipo->ViewValue = null;
            }

            // notificar
            $curVal = strval($this->notificar->CurrentValue);
            if ($curVal != "") {
                $this->notificar->ViewValue = $this->notificar->lookupCacheOption($curVal);
                if ($this->notificar->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->notificar->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->notificar->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->notificar->getSelectFilter($this); // PHP
                    $sqlWrk = $this->notificar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->notificar->Lookup->renderViewRow($rswrk[0]);
                        $this->notificar->ViewValue = $this->notificar->displayValue($arwrk);
                    } else {
                        $this->notificar->ViewValue = $this->notificar->CurrentValue;
                    }
                }
            } else {
                $this->notificar->ViewValue = null;
            }

            // asunto
            $this->asunto->ViewValue = $this->asunto->CurrentValue;

            // notificacion
            $this->notificacion->ViewValue = $this->notificacion->CurrentValue;

            // notificados
            $this->notificados->ViewValue = $this->notificados->CurrentValue;

            // notificados_efectivos
            $this->notificados_efectivos->ViewValue = $this->notificados_efectivos->CurrentValue;

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;
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

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

            // enviado
            if (strval($this->enviado->CurrentValue) != "") {
                $this->enviado->ViewValue = $this->enviado->optionCaption($this->enviado->CurrentValue);
            } else {
                $this->enviado->ViewValue = null;
            }

            // adjunto
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->ImageWidth = 120;
                $this->adjunto->ImageHeight = 120;
                $this->adjunto->ImageAlt = $this->adjunto->alt();
                $this->adjunto->ImageCssClass = "ew-image";
                $this->adjunto->ViewValue = $this->adjunto->Upload->DbValue;
            } else {
                $this->adjunto->ViewValue = "";
            }

            // notificar
            $this->notificar->HrefValue = "";
            $this->notificar->TooltipValue = "";

            // asunto
            $this->asunto->HrefValue = "";

            // notificacion
            $this->notificacion->HrefValue = "";

            // notificados
            $this->notificados->HrefValue = "";

            // notificados_efectivos
            $this->notificados_efectivos->HrefValue = "";

            // username
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // enviado
            $this->enviado->HrefValue = "";
            $this->enviado->TooltipValue = "";

            // adjunto
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->HrefValue = GetFileUploadUrl($this->adjunto, $this->adjunto->htmlDecode($this->adjunto->Upload->DbValue)); // Add prefix/suffix
                $this->adjunto->LinkAttrs["target"] = "_self"; // Add target
                if ($this->isExport()) {
                    $this->adjunto->HrefValue = FullUrl($this->adjunto->HrefValue, "href");
                }
            } else {
                $this->adjunto->HrefValue = "";
            }
            $this->adjunto->ExportHrefValue = $this->adjunto->UploadPath . $this->adjunto->Upload->DbValue;
            $this->adjunto->TooltipValue = "";
            if ($this->adjunto->UseColorbox) {
                if (EmptyValue($this->adjunto->TooltipValue)) {
                    $this->adjunto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->adjunto->LinkAttrs["data-rel"] = "notificaciones_x_adjunto";
                $this->adjunto->LinkAttrs->appendClass("ew-lightbox");
            }
        } elseif ($this->RowType == RowType::ADD) {
            // notificar
            $this->notificar->setupEditAttributes();
            $curVal = trim(strval($this->notificar->CurrentValue));
            if ($curVal != "") {
                $this->notificar->ViewValue = $this->notificar->lookupCacheOption($curVal);
            } else {
                $this->notificar->ViewValue = $this->notificar->Lookup !== null && is_array($this->notificar->lookupOptions()) && count($this->notificar->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->notificar->ViewValue !== null) { // Load from cache
                $this->notificar->EditValue = array_values($this->notificar->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->notificar->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $this->notificar->CurrentValue, $this->notificar->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                }
                $lookupFilter = $this->notificar->getSelectFilter($this); // PHP
                $sqlWrk = $this->notificar->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->notificar->EditValue = $arwrk;
            }
            $this->notificar->PlaceHolder = RemoveHtml($this->notificar->caption());

            // asunto
            $this->asunto->setupEditAttributes();
            if (!$this->asunto->Raw) {
                $this->asunto->CurrentValue = HtmlDecode($this->asunto->CurrentValue);
            }
            $this->asunto->EditValue = HtmlEncode($this->asunto->CurrentValue);
            $this->asunto->PlaceHolder = RemoveHtml($this->asunto->caption());

            // notificacion
            $this->notificacion->setupEditAttributes();
            $this->notificacion->EditValue = HtmlEncode($this->notificacion->CurrentValue);
            $this->notificacion->PlaceHolder = RemoveHtml($this->notificacion->caption());

            // notificados
            $this->notificados->setupEditAttributes();
            $this->notificados->EditValue = HtmlEncode($this->notificados->CurrentValue);
            $this->notificados->PlaceHolder = RemoveHtml($this->notificados->caption());

            // notificados_efectivos
            $this->notificados_efectivos->setupEditAttributes();
            $this->notificados_efectivos->EditValue = HtmlEncode($this->notificados_efectivos->CurrentValue);
            $this->notificados_efectivos->PlaceHolder = RemoveHtml($this->notificados_efectivos->caption());

            // username
            $this->_username->setupEditAttributes();
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $curVal = strval($this->_username->CurrentValue);
            if ($curVal != "") {
                $this->_username->EditValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->_username->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->_username->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                        $this->_username->EditValue = $this->_username->displayValue($arwrk);
                    } else {
                        $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
                    }
                }
            } else {
                $this->_username->EditValue = null;
            }
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // enviado
            $this->enviado->setupEditAttributes();
            $this->enviado->EditValue = $this->enviado->options(true);
            $this->enviado->PlaceHolder = RemoveHtml($this->enviado->caption());

            // adjunto
            $this->adjunto->setupEditAttributes();
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->ImageWidth = 120;
                $this->adjunto->ImageHeight = 120;
                $this->adjunto->ImageAlt = $this->adjunto->alt();
                $this->adjunto->ImageCssClass = "ew-image";
                $this->adjunto->EditValue = $this->adjunto->Upload->DbValue;
            } else {
                $this->adjunto->EditValue = "";
            }
            if (!EmptyValue($this->adjunto->CurrentValue)) {
                $this->adjunto->Upload->FileName = $this->adjunto->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->adjunto->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->adjunto);
            }

            // Add refer script

            // notificar
            $this->notificar->HrefValue = "";

            // asunto
            $this->asunto->HrefValue = "";

            // notificacion
            $this->notificacion->HrefValue = "";

            // notificados
            $this->notificados->HrefValue = "";

            // notificados_efectivos
            $this->notificados_efectivos->HrefValue = "";

            // username
            $this->_username->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // enviado
            $this->enviado->HrefValue = "";

            // adjunto
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->HrefValue = GetFileUploadUrl($this->adjunto, $this->adjunto->htmlDecode($this->adjunto->Upload->DbValue)); // Add prefix/suffix
                $this->adjunto->LinkAttrs["target"] = "_self"; // Add target
                if ($this->isExport()) {
                    $this->adjunto->HrefValue = FullUrl($this->adjunto->HrefValue, "href");
                }
            } else {
                $this->adjunto->HrefValue = "";
            }
            $this->adjunto->ExportHrefValue = $this->adjunto->UploadPath . $this->adjunto->Upload->DbValue;
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
            if ($this->notificar->Visible && $this->notificar->Required) {
                if (!$this->notificar->IsDetailKey && EmptyValue($this->notificar->FormValue)) {
                    $this->notificar->addErrorMessage(str_replace("%s", $this->notificar->caption(), $this->notificar->RequiredErrorMessage));
                }
            }
            if ($this->asunto->Visible && $this->asunto->Required) {
                if (!$this->asunto->IsDetailKey && EmptyValue($this->asunto->FormValue)) {
                    $this->asunto->addErrorMessage(str_replace("%s", $this->asunto->caption(), $this->asunto->RequiredErrorMessage));
                }
            }
            if ($this->notificacion->Visible && $this->notificacion->Required) {
                if (!$this->notificacion->IsDetailKey && EmptyValue($this->notificacion->FormValue)) {
                    $this->notificacion->addErrorMessage(str_replace("%s", $this->notificacion->caption(), $this->notificacion->RequiredErrorMessage));
                }
            }
            if ($this->notificados->Visible && $this->notificados->Required) {
                if (!$this->notificados->IsDetailKey && EmptyValue($this->notificados->FormValue)) {
                    $this->notificados->addErrorMessage(str_replace("%s", $this->notificados->caption(), $this->notificados->RequiredErrorMessage));
                }
            }
            if ($this->notificados_efectivos->Visible && $this->notificados_efectivos->Required) {
                if (!$this->notificados_efectivos->IsDetailKey && EmptyValue($this->notificados_efectivos->FormValue)) {
                    $this->notificados_efectivos->addErrorMessage(str_replace("%s", $this->notificados_efectivos->caption(), $this->notificados_efectivos->RequiredErrorMessage));
                }
            }
            if ($this->_username->Visible && $this->_username->Required) {
                if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                    $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
                }
            }
            if ($this->fecha->Visible && $this->fecha->Required) {
                if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                    $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecha->FormValue, $this->fecha->formatPattern())) {
                $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
            }
            if ($this->enviado->Visible && $this->enviado->Required) {
                if (!$this->enviado->IsDetailKey && EmptyValue($this->enviado->FormValue)) {
                    $this->enviado->addErrorMessage(str_replace("%s", $this->enviado->caption(), $this->enviado->RequiredErrorMessage));
                }
            }
            if ($this->adjunto->Visible && $this->adjunto->Required) {
                if ($this->adjunto->Upload->FileName == "" && !$this->adjunto->Upload->KeepFile) {
                    $this->adjunto->addErrorMessage(str_replace("%s", $this->adjunto->caption(), $this->adjunto->RequiredErrorMessage));
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
        if ($this->adjunto->Visible && !$this->adjunto->Upload->KeepFile) {
            if (!EmptyValue($this->adjunto->Upload->FileName)) {
                $this->adjunto->Upload->DbValue = null;
                FixUploadFileNames($this->adjunto);
                $this->adjunto->setDbValueDef($rsnew, $this->adjunto->Upload->FileName, false);
            }
        }

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
                if ($this->adjunto->Visible && !$this->adjunto->Upload->KeepFile) {
                    $this->adjunto->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->adjunto, $rsnew['adjunto'], false)) {
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

        // notificar
        $this->notificar->setDbValueDef($rsnew, $this->notificar->CurrentValue, false);

        // asunto
        $this->asunto->setDbValueDef($rsnew, $this->asunto->CurrentValue, false);

        // notificacion
        $this->notificacion->setDbValueDef($rsnew, $this->notificacion->CurrentValue, false);

        // notificados
        $this->notificados->setDbValueDef($rsnew, $this->notificados->CurrentValue, false);

        // notificados_efectivos
        $this->notificados_efectivos->setDbValueDef($rsnew, $this->notificados_efectivos->CurrentValue, false);

        // username
        $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()), false);

        // enviado
        $this->enviado->setDbValueDef($rsnew, $this->enviado->CurrentValue, strval($this->enviado->CurrentValue) == "");

        // adjunto
        if ($this->adjunto->Visible && !$this->adjunto->Upload->KeepFile) {
            if ($this->adjunto->Upload->FileName == "") {
                $rsnew['adjunto'] = null;
            } else {
                FixUploadTempFileNames($this->adjunto);
                $rsnew['adjunto'] = $this->adjunto->Upload->FileName;
            }
        }
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['notificar'])) { // notificar
            $this->notificar->setFormValue($row['notificar']);
        }
        if (isset($row['asunto'])) { // asunto
            $this->asunto->setFormValue($row['asunto']);
        }
        if (isset($row['notificacion'])) { // notificacion
            $this->notificacion->setFormValue($row['notificacion']);
        }
        if (isset($row['notificados'])) { // notificados
            $this->notificados->setFormValue($row['notificados']);
        }
        if (isset($row['notificados_efectivos'])) { // notificados_efectivos
            $this->notificados_efectivos->setFormValue($row['notificados_efectivos']);
        }
        if (isset($row['username'])) { // username
            $this->_username->setFormValue($row['username']);
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->setFormValue($row['fecha']);
        }
        if (isset($row['enviado'])) { // enviado
            $this->enviado->setFormValue($row['enviado']);
        }
        if (isset($row['adjunto'])) { // adjunto
            $this->adjunto->setFormValue($row['adjunto']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("NotificacionesList"), "", $this->TableVar, true);
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
                case "x_tipo":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_notificar":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x__username":
                    break;
                case "x_enviado":
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
    public function pageLoad() {
    	//echo "Page Load";
    	header("Location: dashboard/emails_masivos/new_email.php?username=".CurrentUserName());
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
