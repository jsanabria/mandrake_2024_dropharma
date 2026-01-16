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
class TempConsignacionAdd extends TempConsignacion
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "TempConsignacionAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "TempConsignacionAdd";

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
        $this->_username->setVisibility();
        $this->nro_documento->setVisibility();
        $this->id_documento->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->fabricante->setVisibility();
        $this->articulo->setVisibility();
        $this->cantidad_movimiento->setVisibility();
        $this->cantidad_entre_fechas->setVisibility();
        $this->cantidad_acumulada->setVisibility();
        $this->cantidad_ajuste->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'temp_consignacion';
        $this->TableName = 'temp_consignacion';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (temp_consignacion)
        if (!isset($GLOBALS["temp_consignacion"]) || $GLOBALS["temp_consignacion"]::class == PROJECT_NAMESPACE . "temp_consignacion") {
            $GLOBALS["temp_consignacion"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'temp_consignacion');
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
                        $result["view"] = SameString($pageName, "TempConsignacionView"); // If View page, no primary button
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
                    $this->terminate("TempConsignacionList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "TempConsignacionList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "TempConsignacionView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "TempConsignacionList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "TempConsignacionList"; // Return list page content
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

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
            }
        }

        // Check field name 'nro_documento' first before field var 'x_nro_documento'
        $val = $CurrentForm->hasValue("nro_documento") ? $CurrentForm->getValue("nro_documento") : $CurrentForm->getValue("x_nro_documento");
        if (!$this->nro_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_documento->Visible = false; // Disable update for API request
            } else {
                $this->nro_documento->setFormValue($val);
            }
        }

        // Check field name 'id_documento' first before field var 'x_id_documento'
        $val = $CurrentForm->hasValue("id_documento") ? $CurrentForm->getValue("id_documento") : $CurrentForm->getValue("x_id_documento");
        if (!$this->id_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_documento->Visible = false; // Disable update for API request
            } else {
                $this->id_documento->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'tipo_documento' first before field var 'x_tipo_documento'
        $val = $CurrentForm->hasValue("tipo_documento") ? $CurrentForm->getValue("tipo_documento") : $CurrentForm->getValue("x_tipo_documento");
        if (!$this->tipo_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_documento->Visible = false; // Disable update for API request
            } else {
                $this->tipo_documento->setFormValue($val);
            }
        }

        // Check field name 'fabricante' first before field var 'x_fabricante'
        $val = $CurrentForm->hasValue("fabricante") ? $CurrentForm->getValue("fabricante") : $CurrentForm->getValue("x_fabricante");
        if (!$this->fabricante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fabricante->Visible = false; // Disable update for API request
            } else {
                $this->fabricante->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cantidad_movimiento' first before field var 'x_cantidad_movimiento'
        $val = $CurrentForm->hasValue("cantidad_movimiento") ? $CurrentForm->getValue("cantidad_movimiento") : $CurrentForm->getValue("x_cantidad_movimiento");
        if (!$this->cantidad_movimiento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_movimiento->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_movimiento->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cantidad_entre_fechas' first before field var 'x_cantidad_entre_fechas'
        $val = $CurrentForm->hasValue("cantidad_entre_fechas") ? $CurrentForm->getValue("cantidad_entre_fechas") : $CurrentForm->getValue("x_cantidad_entre_fechas");
        if (!$this->cantidad_entre_fechas->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_entre_fechas->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_entre_fechas->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cantidad_acumulada' first before field var 'x_cantidad_acumulada'
        $val = $CurrentForm->hasValue("cantidad_acumulada") ? $CurrentForm->getValue("cantidad_acumulada") : $CurrentForm->getValue("x_cantidad_acumulada");
        if (!$this->cantidad_acumulada->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_acumulada->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_acumulada->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cantidad_ajuste' first before field var 'x_cantidad_ajuste'
        $val = $CurrentForm->hasValue("cantidad_ajuste") ? $CurrentForm->getValue("cantidad_ajuste") : $CurrentForm->getValue("x_cantidad_ajuste");
        if (!$this->cantidad_ajuste->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_ajuste->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_ajuste->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->id_documento->CurrentValue = $this->id_documento->FormValue;
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->cantidad_movimiento->CurrentValue = $this->cantidad_movimiento->FormValue;
        $this->cantidad_entre_fechas->CurrentValue = $this->cantidad_entre_fechas->FormValue;
        $this->cantidad_acumulada->CurrentValue = $this->cantidad_acumulada->FormValue;
        $this->cantidad_ajuste->CurrentValue = $this->cantidad_ajuste->FormValue;
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
        $this->_username->setDbValue($row['username']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->cantidad_entre_fechas->setDbValue($row['cantidad_entre_fechas']);
        $this->cantidad_acumulada->setDbValue($row['cantidad_acumulada']);
        $this->cantidad_ajuste->setDbValue($row['cantidad_ajuste']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['id_documento'] = $this->id_documento->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['fabricante'] = $this->fabricante->DefaultValue;
        $row['articulo'] = $this->articulo->DefaultValue;
        $row['cantidad_movimiento'] = $this->cantidad_movimiento->DefaultValue;
        $row['cantidad_entre_fechas'] = $this->cantidad_entre_fechas->DefaultValue;
        $row['cantidad_acumulada'] = $this->cantidad_acumulada->DefaultValue;
        $row['cantidad_ajuste'] = $this->cantidad_ajuste->DefaultValue;
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

        // username
        $this->_username->RowCssClass = "row";

        // nro_documento
        $this->nro_documento->RowCssClass = "row";

        // id_documento
        $this->id_documento->RowCssClass = "row";

        // tipo_documento
        $this->tipo_documento->RowCssClass = "row";

        // fabricante
        $this->fabricante->RowCssClass = "row";

        // articulo
        $this->articulo->RowCssClass = "row";

        // cantidad_movimiento
        $this->cantidad_movimiento->RowCssClass = "row";

        // cantidad_entre_fechas
        $this->cantidad_entre_fechas->RowCssClass = "row";

        // cantidad_acumulada
        $this->cantidad_acumulada->RowCssClass = "row";

        // cantidad_ajuste
        $this->cantidad_ajuste->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;

            // cantidad_movimiento
            $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->formatPattern());

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->ViewValue = $this->cantidad_entre_fechas->CurrentValue;
            $this->cantidad_entre_fechas->ViewValue = FormatNumber($this->cantidad_entre_fechas->ViewValue, $this->cantidad_entre_fechas->formatPattern());

            // cantidad_acumulada
            $this->cantidad_acumulada->ViewValue = $this->cantidad_acumulada->CurrentValue;
            $this->cantidad_acumulada->ViewValue = FormatNumber($this->cantidad_acumulada->ViewValue, $this->cantidad_acumulada->formatPattern());

            // cantidad_ajuste
            $this->cantidad_ajuste->ViewValue = $this->cantidad_ajuste->CurrentValue;
            $this->cantidad_ajuste->ViewValue = FormatNumber($this->cantidad_ajuste->ViewValue, $this->cantidad_ajuste->formatPattern());

            // username
            $this->_username->HrefValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // id_documento
            $this->id_documento->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";

            // articulo
            $this->articulo->HrefValue = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->HrefValue = "";

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->HrefValue = "";

            // cantidad_acumulada
            $this->cantidad_acumulada->HrefValue = "";

            // cantidad_ajuste
            $this->cantidad_ajuste->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // username
            $this->_username->setupEditAttributes();
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // nro_documento
            $this->nro_documento->setupEditAttributes();
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // id_documento
            $this->id_documento->setupEditAttributes();
            $this->id_documento->EditValue = $this->id_documento->CurrentValue;
            $this->id_documento->PlaceHolder = RemoveHtml($this->id_documento->caption());
            if (strval($this->id_documento->EditValue) != "" && is_numeric($this->id_documento->EditValue)) {
                $this->id_documento->EditValue = $this->id_documento->EditValue;
            }

            // tipo_documento
            $this->tipo_documento->setupEditAttributes();
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = HtmlEncode($this->tipo_documento->CurrentValue);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // fabricante
            $this->fabricante->setupEditAttributes();
            $this->fabricante->EditValue = $this->fabricante->CurrentValue;
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());
            if (strval($this->fabricante->EditValue) != "" && is_numeric($this->fabricante->EditValue)) {
                $this->fabricante->EditValue = $this->fabricante->EditValue;
            }

            // articulo
            $this->articulo->setupEditAttributes();
            $this->articulo->EditValue = $this->articulo->CurrentValue;
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());
            if (strval($this->articulo->EditValue) != "" && is_numeric($this->articulo->EditValue)) {
                $this->articulo->EditValue = $this->articulo->EditValue;
            }

            // cantidad_movimiento
            $this->cantidad_movimiento->setupEditAttributes();
            $this->cantidad_movimiento->EditValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->PlaceHolder = RemoveHtml($this->cantidad_movimiento->caption());
            if (strval($this->cantidad_movimiento->EditValue) != "" && is_numeric($this->cantidad_movimiento->EditValue)) {
                $this->cantidad_movimiento->EditValue = FormatNumber($this->cantidad_movimiento->EditValue, null);
            }

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->setupEditAttributes();
            $this->cantidad_entre_fechas->EditValue = $this->cantidad_entre_fechas->CurrentValue;
            $this->cantidad_entre_fechas->PlaceHolder = RemoveHtml($this->cantidad_entre_fechas->caption());
            if (strval($this->cantidad_entre_fechas->EditValue) != "" && is_numeric($this->cantidad_entre_fechas->EditValue)) {
                $this->cantidad_entre_fechas->EditValue = FormatNumber($this->cantidad_entre_fechas->EditValue, null);
            }

            // cantidad_acumulada
            $this->cantidad_acumulada->setupEditAttributes();
            $this->cantidad_acumulada->EditValue = $this->cantidad_acumulada->CurrentValue;
            $this->cantidad_acumulada->PlaceHolder = RemoveHtml($this->cantidad_acumulada->caption());
            if (strval($this->cantidad_acumulada->EditValue) != "" && is_numeric($this->cantidad_acumulada->EditValue)) {
                $this->cantidad_acumulada->EditValue = FormatNumber($this->cantidad_acumulada->EditValue, null);
            }

            // cantidad_ajuste
            $this->cantidad_ajuste->setupEditAttributes();
            $this->cantidad_ajuste->EditValue = $this->cantidad_ajuste->CurrentValue;
            $this->cantidad_ajuste->PlaceHolder = RemoveHtml($this->cantidad_ajuste->caption());
            if (strval($this->cantidad_ajuste->EditValue) != "" && is_numeric($this->cantidad_ajuste->EditValue)) {
                $this->cantidad_ajuste->EditValue = FormatNumber($this->cantidad_ajuste->EditValue, null);
            }

            // Add refer script

            // username
            $this->_username->HrefValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // id_documento
            $this->id_documento->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";

            // articulo
            $this->articulo->HrefValue = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->HrefValue = "";

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->HrefValue = "";

            // cantidad_acumulada
            $this->cantidad_acumulada->HrefValue = "";

            // cantidad_ajuste
            $this->cantidad_ajuste->HrefValue = "";
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
            if ($this->_username->Visible && $this->_username->Required) {
                if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                    $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
                }
            }
            if ($this->nro_documento->Visible && $this->nro_documento->Required) {
                if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                    $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
                }
            }
            if ($this->id_documento->Visible && $this->id_documento->Required) {
                if (!$this->id_documento->IsDetailKey && EmptyValue($this->id_documento->FormValue)) {
                    $this->id_documento->addErrorMessage(str_replace("%s", $this->id_documento->caption(), $this->id_documento->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->id_documento->FormValue)) {
                $this->id_documento->addErrorMessage($this->id_documento->getErrorMessage(false));
            }
            if ($this->tipo_documento->Visible && $this->tipo_documento->Required) {
                if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                    $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
                }
            }
            if ($this->fabricante->Visible && $this->fabricante->Required) {
                if (!$this->fabricante->IsDetailKey && EmptyValue($this->fabricante->FormValue)) {
                    $this->fabricante->addErrorMessage(str_replace("%s", $this->fabricante->caption(), $this->fabricante->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->fabricante->FormValue)) {
                $this->fabricante->addErrorMessage($this->fabricante->getErrorMessage(false));
            }
            if ($this->articulo->Visible && $this->articulo->Required) {
                if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                    $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->articulo->FormValue)) {
                $this->articulo->addErrorMessage($this->articulo->getErrorMessage(false));
            }
            if ($this->cantidad_movimiento->Visible && $this->cantidad_movimiento->Required) {
                if (!$this->cantidad_movimiento->IsDetailKey && EmptyValue($this->cantidad_movimiento->FormValue)) {
                    $this->cantidad_movimiento->addErrorMessage(str_replace("%s", $this->cantidad_movimiento->caption(), $this->cantidad_movimiento->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_movimiento->FormValue)) {
                $this->cantidad_movimiento->addErrorMessage($this->cantidad_movimiento->getErrorMessage(false));
            }
            if ($this->cantidad_entre_fechas->Visible && $this->cantidad_entre_fechas->Required) {
                if (!$this->cantidad_entre_fechas->IsDetailKey && EmptyValue($this->cantidad_entre_fechas->FormValue)) {
                    $this->cantidad_entre_fechas->addErrorMessage(str_replace("%s", $this->cantidad_entre_fechas->caption(), $this->cantidad_entre_fechas->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_entre_fechas->FormValue)) {
                $this->cantidad_entre_fechas->addErrorMessage($this->cantidad_entre_fechas->getErrorMessage(false));
            }
            if ($this->cantidad_acumulada->Visible && $this->cantidad_acumulada->Required) {
                if (!$this->cantidad_acumulada->IsDetailKey && EmptyValue($this->cantidad_acumulada->FormValue)) {
                    $this->cantidad_acumulada->addErrorMessage(str_replace("%s", $this->cantidad_acumulada->caption(), $this->cantidad_acumulada->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_acumulada->FormValue)) {
                $this->cantidad_acumulada->addErrorMessage($this->cantidad_acumulada->getErrorMessage(false));
            }
            if ($this->cantidad_ajuste->Visible && $this->cantidad_ajuste->Required) {
                if (!$this->cantidad_ajuste->IsDetailKey && EmptyValue($this->cantidad_ajuste->FormValue)) {
                    $this->cantidad_ajuste->addErrorMessage(str_replace("%s", $this->cantidad_ajuste->caption(), $this->cantidad_ajuste->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->cantidad_ajuste->FormValue)) {
                $this->cantidad_ajuste->addErrorMessage($this->cantidad_ajuste->getErrorMessage(false));
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

        // username
        $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, false);

        // nro_documento
        $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, false);

        // id_documento
        $this->id_documento->setDbValueDef($rsnew, $this->id_documento->CurrentValue, false);

        // tipo_documento
        $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, false);

        // fabricante
        $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, false);

        // articulo
        $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, false);

        // cantidad_movimiento
        $this->cantidad_movimiento->setDbValueDef($rsnew, $this->cantidad_movimiento->CurrentValue, false);

        // cantidad_entre_fechas
        $this->cantidad_entre_fechas->setDbValueDef($rsnew, $this->cantidad_entre_fechas->CurrentValue, false);

        // cantidad_acumulada
        $this->cantidad_acumulada->setDbValueDef($rsnew, $this->cantidad_acumulada->CurrentValue, false);

        // cantidad_ajuste
        $this->cantidad_ajuste->setDbValueDef($rsnew, $this->cantidad_ajuste->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['username'])) { // username
            $this->_username->setFormValue($row['username']);
        }
        if (isset($row['nro_documento'])) { // nro_documento
            $this->nro_documento->setFormValue($row['nro_documento']);
        }
        if (isset($row['id_documento'])) { // id_documento
            $this->id_documento->setFormValue($row['id_documento']);
        }
        if (isset($row['tipo_documento'])) { // tipo_documento
            $this->tipo_documento->setFormValue($row['tipo_documento']);
        }
        if (isset($row['fabricante'])) { // fabricante
            $this->fabricante->setFormValue($row['fabricante']);
        }
        if (isset($row['articulo'])) { // articulo
            $this->articulo->setFormValue($row['articulo']);
        }
        if (isset($row['cantidad_movimiento'])) { // cantidad_movimiento
            $this->cantidad_movimiento->setFormValue($row['cantidad_movimiento']);
        }
        if (isset($row['cantidad_entre_fechas'])) { // cantidad_entre_fechas
            $this->cantidad_entre_fechas->setFormValue($row['cantidad_entre_fechas']);
        }
        if (isset($row['cantidad_acumulada'])) { // cantidad_acumulada
            $this->cantidad_acumulada->setFormValue($row['cantidad_acumulada']);
        }
        if (isset($row['cantidad_ajuste'])) { // cantidad_ajuste
            $this->cantidad_ajuste->setFormValue($row['cantidad_ajuste']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("TempConsignacionList"), "", $this->TableVar, true);
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
