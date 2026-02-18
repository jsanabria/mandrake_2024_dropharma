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
class CobrosClienteDetalleEdit extends CobrosClienteDetalle
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CobrosClienteDetalleEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "CobrosClienteDetalleEdit";

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
        $this->id->setVisibility();
        $this->cobros_cliente->Visible = false;
        $this->metodo_pago->setVisibility();
        $this->referencia->setVisibility();
        $this->monto_moneda->setVisibility();
        $this->moneda->setVisibility();
        $this->tasa_moneda->Visible = false;
        $this->monto_bs->setVisibility();
        $this->tasa_usd->setVisibility();
        $this->monto_usd->setVisibility();
        $this->banco->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cobros_cliente_detalle';
        $this->TableName = 'cobros_cliente_detalle';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cobros_cliente_detalle)
        if (!isset($GLOBALS["cobros_cliente_detalle"]) || $GLOBALS["cobros_cliente_detalle"]::class == PROJECT_NAMESPACE . "cobros_cliente_detalle") {
            $GLOBALS["cobros_cliente_detalle"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cobros_cliente_detalle');
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
                        $result["view"] = SameString($pageName, "CobrosClienteDetalleView"); // If View page, no primary button
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->setupLookupOptions($this->metodo_pago);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->banco);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("id") ?? Key(0) ?? Route(2)) !== null) {
                $this->id->setQueryStringValue($keyValue);
                $this->id->setOldValue($this->id->QueryStringValue);
            } elseif (Post("id") !== null) {
                $this->id->setFormValue(Post("id"));
                $this->id->setOldValue($this->id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("id") ?? Route("id")) !== null) {
                    $this->id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->id->CurrentValue = null;
                }
                if (!$loadByQuery || Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) {
                    $loadByPosition = true;
                }
            }

            // Load result set
            if ($this->isShow()) {
                if (!$this->IsModal) { // Normal edit page
                    $this->StartRecord = 1; // Initialize start position
                    $this->Recordset = $this->loadRecordset(); // Load records
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("CobrosClienteDetalleList"); // Return to list page
                        return;
                    } elseif ($loadByPosition) { // Load record by position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $this->fetch($this->StartRecord);
                            // Redirect to correct record
                            $this->loadRowValues($this->CurrentRow);
                            $url = $this->getCurrentUrl();
                            $this->terminate($url);
                            return;
                        }
                    } else { // Match key values
                        if ($this->id->CurrentValue != null) {
                            while ($this->fetch()) {
                                if (SameString($this->id->CurrentValue, $this->CurrentRow['id'])) {
                                    $this->setStartRecordNumber($this->StartRecord); // Save record position
                                    $loaded = true;
                                    break;
                                } else {
                                    $this->StartRecord++;
                                }
                            }
                        }
                    }

                    // Load current row values
                    if ($loaded) {
                        $this->loadRowValues($this->CurrentRow);
                    }
                } else {
                    // Load current record
                    $loaded = $this->loadRow();
                } // End modal checking
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal) { // Normal edit page
                    if (!$loaded) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("CobrosClienteDetalleList"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("CobrosClienteDetalleList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "CobrosClienteDetalleList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "CobrosClienteDetalleList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "CobrosClienteDetalleList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
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
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        if (!$this->IsModal) { // Normal view page
            $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager, false, false);
            $this->Pager->PageNumberName = Config("TABLE_PAGE_NUMBER");
            $this->Pager->PagePhraseId = "Record"; // Show as record
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }

        // Check field name 'metodo_pago' first before field var 'x_metodo_pago'
        $val = $CurrentForm->hasValue("metodo_pago") ? $CurrentForm->getValue("metodo_pago") : $CurrentForm->getValue("x_metodo_pago");
        if (!$this->metodo_pago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->metodo_pago->Visible = false; // Disable update for API request
            } else {
                $this->metodo_pago->setFormValue($val);
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

        // Check field name 'monto_moneda' first before field var 'x_monto_moneda'
        $val = $CurrentForm->hasValue("monto_moneda") ? $CurrentForm->getValue("monto_moneda") : $CurrentForm->getValue("x_monto_moneda");
        if (!$this->monto_moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_moneda->Visible = false; // Disable update for API request
            } else {
                $this->monto_moneda->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'moneda' first before field var 'x_moneda'
        $val = $CurrentForm->hasValue("moneda") ? $CurrentForm->getValue("moneda") : $CurrentForm->getValue("x_moneda");
        if (!$this->moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->moneda->Visible = false; // Disable update for API request
            } else {
                $this->moneda->setFormValue($val);
            }
        }

        // Check field name 'monto_bs' first before field var 'x_monto_bs'
        $val = $CurrentForm->hasValue("monto_bs") ? $CurrentForm->getValue("monto_bs") : $CurrentForm->getValue("x_monto_bs");
        if (!$this->monto_bs->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_bs->Visible = false; // Disable update for API request
            } else {
                $this->monto_bs->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'tasa_usd' first before field var 'x_tasa_usd'
        $val = $CurrentForm->hasValue("tasa_usd") ? $CurrentForm->getValue("tasa_usd") : $CurrentForm->getValue("x_tasa_usd");
        if (!$this->tasa_usd->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tasa_usd->Visible = false; // Disable update for API request
            } else {
                $this->tasa_usd->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'monto_usd' first before field var 'x_monto_usd'
        $val = $CurrentForm->hasValue("monto_usd") ? $CurrentForm->getValue("monto_usd") : $CurrentForm->getValue("x_monto_usd");
        if (!$this->monto_usd->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_usd->Visible = false; // Disable update for API request
            } else {
                $this->monto_usd->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'banco' first before field var 'x_banco'
        $val = $CurrentForm->hasValue("banco") ? $CurrentForm->getValue("banco") : $CurrentForm->getValue("x_banco");
        if (!$this->banco->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->banco->Visible = false; // Disable update for API request
            } else {
                $this->banco->setFormValue($val, true, $validate);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->metodo_pago->CurrentValue = $this->metodo_pago->FormValue;
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->monto_moneda->CurrentValue = $this->monto_moneda->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->monto_bs->CurrentValue = $this->monto_bs->FormValue;
        $this->tasa_usd->CurrentValue = $this->tasa_usd->FormValue;
        $this->monto_usd->CurrentValue = $this->monto_usd->FormValue;
        $this->banco->CurrentValue = $this->banco->FormValue;
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
        $this->cobros_cliente->setDbValue($row['cobros_cliente']);
        $this->metodo_pago->setDbValue($row['metodo_pago']);
        $this->referencia->setDbValue($row['referencia']);
        $this->monto_moneda->setDbValue($row['monto_moneda']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_moneda->setDbValue($row['tasa_moneda']);
        $this->monto_bs->setDbValue($row['monto_bs']);
        $this->tasa_usd->setDbValue($row['tasa_usd']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->banco->setDbValue($row['banco']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['cobros_cliente'] = $this->cobros_cliente->DefaultValue;
        $row['metodo_pago'] = $this->metodo_pago->DefaultValue;
        $row['referencia'] = $this->referencia->DefaultValue;
        $row['monto_moneda'] = $this->monto_moneda->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['tasa_moneda'] = $this->tasa_moneda->DefaultValue;
        $row['monto_bs'] = $this->monto_bs->DefaultValue;
        $row['tasa_usd'] = $this->tasa_usd->DefaultValue;
        $row['monto_usd'] = $this->monto_usd->DefaultValue;
        $row['banco'] = $this->banco->DefaultValue;
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

        // cobros_cliente
        $this->cobros_cliente->RowCssClass = "row";

        // metodo_pago
        $this->metodo_pago->RowCssClass = "row";

        // referencia
        $this->referencia->RowCssClass = "row";

        // monto_moneda
        $this->monto_moneda->RowCssClass = "row";

        // moneda
        $this->moneda->RowCssClass = "row";

        // tasa_moneda
        $this->tasa_moneda->RowCssClass = "row";

        // monto_bs
        $this->monto_bs->RowCssClass = "row";

        // tasa_usd
        $this->tasa_usd->RowCssClass = "row";

        // monto_usd
        $this->monto_usd->RowCssClass = "row";

        // banco
        $this->banco->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // cobros_cliente
            $this->cobros_cliente->ViewValue = $this->cobros_cliente->CurrentValue;
            $this->cobros_cliente->ViewValue = FormatNumber($this->cobros_cliente->ViewValue, $this->cobros_cliente->formatPattern());

            // metodo_pago
            $this->metodo_pago->ViewValue = $this->metodo_pago->CurrentValue;
            $curVal = strval($this->metodo_pago->CurrentValue);
            if ($curVal != "") {
                $this->metodo_pago->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
                if ($this->metodo_pago->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->metodo_pago->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->metodo_pago->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->metodo_pago->getSelectFilter($this); // PHP
                    $sqlWrk = $this->metodo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->metodo_pago->Lookup->renderViewRow($rswrk[0]);
                        $this->metodo_pago->ViewValue = $this->metodo_pago->displayValue($arwrk);
                    } else {
                        $this->metodo_pago->ViewValue = $this->metodo_pago->CurrentValue;
                    }
                }
            } else {
                $this->metodo_pago->ViewValue = null;
            }

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;

            // monto_moneda
            $this->monto_moneda->ViewValue = $this->monto_moneda->CurrentValue;
            $this->monto_moneda->ViewValue = FormatNumber($this->monto_moneda->ViewValue, $this->monto_moneda->formatPattern());

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

            // tasa_moneda
            $this->tasa_moneda->ViewValue = $this->tasa_moneda->CurrentValue;
            $this->tasa_moneda->ViewValue = FormatNumber($this->tasa_moneda->ViewValue, $this->tasa_moneda->formatPattern());

            // monto_bs
            $this->monto_bs->ViewValue = $this->monto_bs->CurrentValue;
            $this->monto_bs->ViewValue = FormatNumber($this->monto_bs->ViewValue, $this->monto_bs->formatPattern());

            // tasa_usd
            $this->tasa_usd->ViewValue = $this->tasa_usd->CurrentValue;
            $this->tasa_usd->ViewValue = FormatNumber($this->tasa_usd->ViewValue, $this->tasa_usd->formatPattern());

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

            // banco
            $this->banco->ViewValue = $this->banco->CurrentValue;
            $curVal = strval($this->banco->CurrentValue);
            if ($curVal != "") {
                $this->banco->ViewValue = $this->banco->lookupCacheOption($curVal);
                if ($this->banco->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->banco->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->banco->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->banco->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->banco->Lookup->renderViewRow($rswrk[0]);
                        $this->banco->ViewValue = $this->banco->displayValue($arwrk);
                    } else {
                        $this->banco->ViewValue = FormatNumber($this->banco->CurrentValue, $this->banco->formatPattern());
                    }
                }
            } else {
                $this->banco->ViewValue = null;
            }

            // id
            $this->id->HrefValue = "";

            // metodo_pago
            $this->metodo_pago->HrefValue = "";

            // referencia
            $this->referencia->HrefValue = "";

            // monto_moneda
            $this->monto_moneda->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // monto_bs
            $this->monto_bs->HrefValue = "";

            // tasa_usd
            $this->tasa_usd->HrefValue = "";

            // monto_usd
            $this->monto_usd->HrefValue = "";

            // banco
            $this->banco->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // id
            $this->id->setupEditAttributes();
            $this->id->EditValue = $this->id->CurrentValue;

            // metodo_pago
            $this->metodo_pago->setupEditAttributes();
            if (!$this->metodo_pago->Raw) {
                $this->metodo_pago->CurrentValue = HtmlDecode($this->metodo_pago->CurrentValue);
            }
            $this->metodo_pago->EditValue = HtmlEncode($this->metodo_pago->CurrentValue);
            $curVal = strval($this->metodo_pago->CurrentValue);
            if ($curVal != "") {
                $this->metodo_pago->EditValue = $this->metodo_pago->lookupCacheOption($curVal);
                if ($this->metodo_pago->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->metodo_pago->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->metodo_pago->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                    $lookupFilter = $this->metodo_pago->getSelectFilter($this); // PHP
                    $sqlWrk = $this->metodo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->metodo_pago->Lookup->renderViewRow($rswrk[0]);
                        $this->metodo_pago->EditValue = $this->metodo_pago->displayValue($arwrk);
                    } else {
                        $this->metodo_pago->EditValue = HtmlEncode($this->metodo_pago->CurrentValue);
                    }
                }
            } else {
                $this->metodo_pago->EditValue = null;
            }
            $this->metodo_pago->PlaceHolder = RemoveHtml($this->metodo_pago->caption());

            // referencia
            $this->referencia->setupEditAttributes();
            if (!$this->referencia->Raw) {
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // monto_moneda
            $this->monto_moneda->setupEditAttributes();
            $this->monto_moneda->EditValue = $this->monto_moneda->CurrentValue;
            $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());
            if (strval($this->monto_moneda->EditValue) != "" && is_numeric($this->monto_moneda->EditValue)) {
                $this->monto_moneda->EditValue = FormatNumber($this->monto_moneda->EditValue, null);
            }

            // moneda
            $this->moneda->setupEditAttributes();
            $curVal = trim(strval($this->moneda->CurrentValue));
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
            } else {
                $this->moneda->ViewValue = $this->moneda->Lookup !== null && is_array($this->moneda->lookupOptions()) && count($this->moneda->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->moneda->ViewValue !== null) { // Load from cache
                $this->moneda->EditValue = array_values($this->moneda->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->moneda->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $this->moneda->CurrentValue, $this->moneda->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                }
                $lookupFilter = $this->moneda->getSelectFilter($this); // PHP
                $sqlWrk = $this->moneda->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->moneda->EditValue = $arwrk;
            }
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

            // monto_bs
            $this->monto_bs->setupEditAttributes();
            $this->monto_bs->EditValue = $this->monto_bs->CurrentValue;
            $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());
            if (strval($this->monto_bs->EditValue) != "" && is_numeric($this->monto_bs->EditValue)) {
                $this->monto_bs->EditValue = FormatNumber($this->monto_bs->EditValue, null);
            }

            // tasa_usd
            $this->tasa_usd->setupEditAttributes();
            $this->tasa_usd->EditValue = $this->tasa_usd->CurrentValue;
            $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());
            if (strval($this->tasa_usd->EditValue) != "" && is_numeric($this->tasa_usd->EditValue)) {
                $this->tasa_usd->EditValue = FormatNumber($this->tasa_usd->EditValue, null);
            }

            // monto_usd
            $this->monto_usd->setupEditAttributes();
            $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
            if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
                $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, null);
            }

            // banco
            $this->banco->setupEditAttributes();
            $this->banco->EditValue = $this->banco->CurrentValue;
            $curVal = strval($this->banco->CurrentValue);
            if ($curVal != "") {
                $this->banco->EditValue = $this->banco->lookupCacheOption($curVal);
                if ($this->banco->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->banco->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->banco->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->banco->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->banco->Lookup->renderViewRow($rswrk[0]);
                        $this->banco->EditValue = $this->banco->displayValue($arwrk);
                    } else {
                        $this->banco->EditValue = HtmlEncode(FormatNumber($this->banco->CurrentValue, $this->banco->formatPattern()));
                    }
                }
            } else {
                $this->banco->EditValue = null;
            }
            $this->banco->PlaceHolder = RemoveHtml($this->banco->caption());

            // Edit refer script

            // id
            $this->id->HrefValue = "";

            // metodo_pago
            $this->metodo_pago->HrefValue = "";

            // referencia
            $this->referencia->HrefValue = "";

            // monto_moneda
            $this->monto_moneda->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // monto_bs
            $this->monto_bs->HrefValue = "";

            // tasa_usd
            $this->tasa_usd->HrefValue = "";

            // monto_usd
            $this->monto_usd->HrefValue = "";

            // banco
            $this->banco->HrefValue = "";
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
            if ($this->id->Visible && $this->id->Required) {
                if (!$this->id->IsDetailKey && EmptyValue($this->id->FormValue)) {
                    $this->id->addErrorMessage(str_replace("%s", $this->id->caption(), $this->id->RequiredErrorMessage));
                }
            }
            if ($this->metodo_pago->Visible && $this->metodo_pago->Required) {
                if (!$this->metodo_pago->IsDetailKey && EmptyValue($this->metodo_pago->FormValue)) {
                    $this->metodo_pago->addErrorMessage(str_replace("%s", $this->metodo_pago->caption(), $this->metodo_pago->RequiredErrorMessage));
                }
            }
            if ($this->referencia->Visible && $this->referencia->Required) {
                if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                    $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
                }
            }
            if ($this->monto_moneda->Visible && $this->monto_moneda->Required) {
                if (!$this->monto_moneda->IsDetailKey && EmptyValue($this->monto_moneda->FormValue)) {
                    $this->monto_moneda->addErrorMessage(str_replace("%s", $this->monto_moneda->caption(), $this->monto_moneda->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_moneda->FormValue)) {
                $this->monto_moneda->addErrorMessage($this->monto_moneda->getErrorMessage(false));
            }
            if ($this->moneda->Visible && $this->moneda->Required) {
                if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                    $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
                }
            }
            if ($this->monto_bs->Visible && $this->monto_bs->Required) {
                if (!$this->monto_bs->IsDetailKey && EmptyValue($this->monto_bs->FormValue)) {
                    $this->monto_bs->addErrorMessage(str_replace("%s", $this->monto_bs->caption(), $this->monto_bs->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_bs->FormValue)) {
                $this->monto_bs->addErrorMessage($this->monto_bs->getErrorMessage(false));
            }
            if ($this->tasa_usd->Visible && $this->tasa_usd->Required) {
                if (!$this->tasa_usd->IsDetailKey && EmptyValue($this->tasa_usd->FormValue)) {
                    $this->tasa_usd->addErrorMessage(str_replace("%s", $this->tasa_usd->caption(), $this->tasa_usd->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->tasa_usd->FormValue)) {
                $this->tasa_usd->addErrorMessage($this->tasa_usd->getErrorMessage(false));
            }
            if ($this->monto_usd->Visible && $this->monto_usd->Required) {
                if (!$this->monto_usd->IsDetailKey && EmptyValue($this->monto_usd->FormValue)) {
                    $this->monto_usd->addErrorMessage(str_replace("%s", $this->monto_usd->caption(), $this->monto_usd->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_usd->FormValue)) {
                $this->monto_usd->addErrorMessage($this->monto_usd->getErrorMessage(false));
            }
            if ($this->banco->Visible && $this->banco->Required) {
                if (!$this->banco->IsDetailKey && EmptyValue($this->banco->FormValue)) {
                    $this->banco->addErrorMessage(str_replace("%s", $this->banco->caption(), $this->banco->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->banco->FormValue)) {
                $this->banco->addErrorMessage($this->banco->getErrorMessage(false));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // metodo_pago
        $this->metodo_pago->setDbValueDef($rsnew, $this->metodo_pago->CurrentValue, $this->metodo_pago->ReadOnly);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, $this->referencia->ReadOnly);

        // monto_moneda
        $this->monto_moneda->setDbValueDef($rsnew, $this->monto_moneda->CurrentValue, $this->monto_moneda->ReadOnly);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, $this->moneda->ReadOnly);

        // monto_bs
        $this->monto_bs->setDbValueDef($rsnew, $this->monto_bs->CurrentValue, $this->monto_bs->ReadOnly);

        // tasa_usd
        $this->tasa_usd->setDbValueDef($rsnew, $this->tasa_usd->CurrentValue, $this->tasa_usd->ReadOnly);

        // monto_usd
        $this->monto_usd->setDbValueDef($rsnew, $this->monto_usd->CurrentValue, $this->monto_usd->ReadOnly);

        // banco
        $this->banco->setDbValueDef($rsnew, $this->banco->CurrentValue, $this->banco->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['metodo_pago'])) { // metodo_pago
            $this->metodo_pago->CurrentValue = $row['metodo_pago'];
        }
        if (isset($row['referencia'])) { // referencia
            $this->referencia->CurrentValue = $row['referencia'];
        }
        if (isset($row['monto_moneda'])) { // monto_moneda
            $this->monto_moneda->CurrentValue = $row['monto_moneda'];
        }
        if (isset($row['moneda'])) { // moneda
            $this->moneda->CurrentValue = $row['moneda'];
        }
        if (isset($row['monto_bs'])) { // monto_bs
            $this->monto_bs->CurrentValue = $row['monto_bs'];
        }
        if (isset($row['tasa_usd'])) { // tasa_usd
            $this->tasa_usd->CurrentValue = $row['tasa_usd'];
        }
        if (isset($row['monto_usd'])) { // monto_usd
            $this->monto_usd->CurrentValue = $row['monto_usd'];
        }
        if (isset($row['banco'])) { // banco
            $this->banco->CurrentValue = $row['banco'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CobrosClienteDetalleList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_metodo_pago":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_moneda":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_banco":
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
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
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
