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
class ProveedorEdit extends Proveedor
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ProveedorEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ProveedorEdit";

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
        $this->ciudad->setVisibility();
        $this->direccion->setVisibility();
        $this->telefono1->setVisibility();
        $this->telefono2->setVisibility();
        $this->email1->setVisibility();
        $this->email2->setVisibility();
        $this->cuenta_auxiliar->setVisibility();
        $this->cuenta_gasto->setVisibility();
        $this->tipo_iva->setVisibility();
        $this->tipo_islr->setVisibility();
        $this->sustraendo->setVisibility();
        $this->tipo_impmun->setVisibility();
        $this->cta_bco->setVisibility();
        $this->activo->setVisibility();
        $this->fabricante->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'proveedor';
        $this->TableName = 'proveedor';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (proveedor)
        if (!isset($GLOBALS["proveedor"]) || $GLOBALS["proveedor"]::class == PROJECT_NAMESPACE . "proveedor") {
            $GLOBALS["proveedor"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'proveedor');
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
                        $result["view"] = SameString($pageName, "ProveedorView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->ciudad);
        $this->setupLookupOptions($this->cuenta_auxiliar);
        $this->setupLookupOptions($this->cuenta_gasto);
        $this->setupLookupOptions($this->tipo_iva);
        $this->setupLookupOptions($this->tipo_islr);
        $this->setupLookupOptions($this->sustraendo);
        $this->setupLookupOptions($this->tipo_impmun);
        $this->setupLookupOptions($this->activo);

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
                        $this->terminate("ProveedorList"); // Return to list page
                        return;
                    } elseif ($loadByPosition) { // Load record by position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $this->fetch($this->StartRecord);
                            // Redirect to correct record
                            $this->loadRowValues($this->CurrentRow);
                            $url = $this->getCurrentUrl(Config("TABLE_SHOW_DETAIL") . "=" . $this->getCurrentDetailTable());
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

            // Set up detail parameters
            $this->setupDetailParms();
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
                        $this->terminate("ProveedorList"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("ProveedorList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                $returnUrl = $this->getViewUrl();
                if (GetPageName($returnUrl) == "ProveedorList") {
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
                        if (GetPageName($returnUrl) != "ProveedorList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ProveedorList"; // Return list page content
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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

        // Check field name 'ciudad' first before field var 'x_ciudad'
        $val = $CurrentForm->hasValue("ciudad") ? $CurrentForm->getValue("ciudad") : $CurrentForm->getValue("x_ciudad");
        if (!$this->ciudad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ciudad->Visible = false; // Disable update for API request
            } else {
                $this->ciudad->setFormValue($val);
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
                $this->email2->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'cuenta_auxiliar' first before field var 'x_cuenta_auxiliar'
        $val = $CurrentForm->hasValue("cuenta_auxiliar") ? $CurrentForm->getValue("cuenta_auxiliar") : $CurrentForm->getValue("x_cuenta_auxiliar");
        if (!$this->cuenta_auxiliar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cuenta_auxiliar->Visible = false; // Disable update for API request
            } else {
                $this->cuenta_auxiliar->setFormValue($val);
            }
        }

        // Check field name 'cuenta_gasto' first before field var 'x_cuenta_gasto'
        $val = $CurrentForm->hasValue("cuenta_gasto") ? $CurrentForm->getValue("cuenta_gasto") : $CurrentForm->getValue("x_cuenta_gasto");
        if (!$this->cuenta_gasto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cuenta_gasto->Visible = false; // Disable update for API request
            } else {
                $this->cuenta_gasto->setFormValue($val);
            }
        }

        // Check field name 'tipo_iva' first before field var 'x_tipo_iva'
        $val = $CurrentForm->hasValue("tipo_iva") ? $CurrentForm->getValue("tipo_iva") : $CurrentForm->getValue("x_tipo_iva");
        if (!$this->tipo_iva->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_iva->Visible = false; // Disable update for API request
            } else {
                $this->tipo_iva->setFormValue($val);
            }
        }

        // Check field name 'tipo_islr' first before field var 'x_tipo_islr'
        $val = $CurrentForm->hasValue("tipo_islr") ? $CurrentForm->getValue("tipo_islr") : $CurrentForm->getValue("x_tipo_islr");
        if (!$this->tipo_islr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_islr->Visible = false; // Disable update for API request
            } else {
                $this->tipo_islr->setFormValue($val);
            }
        }

        // Check field name 'sustraendo' first before field var 'x_sustraendo'
        $val = $CurrentForm->hasValue("sustraendo") ? $CurrentForm->getValue("sustraendo") : $CurrentForm->getValue("x_sustraendo");
        if (!$this->sustraendo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sustraendo->Visible = false; // Disable update for API request
            } else {
                $this->sustraendo->setFormValue($val);
            }
        }

        // Check field name 'tipo_impmun' first before field var 'x_tipo_impmun'
        $val = $CurrentForm->hasValue("tipo_impmun") ? $CurrentForm->getValue("tipo_impmun") : $CurrentForm->getValue("x_tipo_impmun");
        if (!$this->tipo_impmun->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_impmun->Visible = false; // Disable update for API request
            } else {
                $this->tipo_impmun->setFormValue($val);
            }
        }

        // Check field name 'cta_bco' first before field var 'x_cta_bco'
        $val = $CurrentForm->hasValue("cta_bco") ? $CurrentForm->getValue("cta_bco") : $CurrentForm->getValue("x_cta_bco");
        if (!$this->cta_bco->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cta_bco->Visible = false; // Disable update for API request
            } else {
                $this->cta_bco->setFormValue($val);
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

        // Check field name 'fabricante' first before field var 'x_fabricante'
        $val = $CurrentForm->hasValue("fabricante") ? $CurrentForm->getValue("fabricante") : $CurrentForm->getValue("x_fabricante");
        if (!$this->fabricante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fabricante->Visible = false; // Disable update for API request
            } else {
                $this->fabricante->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->ci_rif->CurrentValue = $this->ci_rif->FormValue;
        $this->nombre->CurrentValue = $this->nombre->FormValue;
        $this->ciudad->CurrentValue = $this->ciudad->FormValue;
        $this->direccion->CurrentValue = $this->direccion->FormValue;
        $this->telefono1->CurrentValue = $this->telefono1->FormValue;
        $this->telefono2->CurrentValue = $this->telefono2->FormValue;
        $this->email1->CurrentValue = $this->email1->FormValue;
        $this->email2->CurrentValue = $this->email2->FormValue;
        $this->cuenta_auxiliar->CurrentValue = $this->cuenta_auxiliar->FormValue;
        $this->cuenta_gasto->CurrentValue = $this->cuenta_gasto->FormValue;
        $this->tipo_iva->CurrentValue = $this->tipo_iva->FormValue;
        $this->tipo_islr->CurrentValue = $this->tipo_islr->FormValue;
        $this->sustraendo->CurrentValue = $this->sustraendo->FormValue;
        $this->tipo_impmun->CurrentValue = $this->tipo_impmun->FormValue;
        $this->cta_bco->CurrentValue = $this->cta_bco->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->cuenta_auxiliar->setDbValue($row['cuenta_auxiliar']);
        $this->cuenta_gasto->setDbValue($row['cuenta_gasto']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_impmun->setDbValue($row['tipo_impmun']);
        $this->cta_bco->setDbValue($row['cta_bco']);
        $this->activo->setDbValue($row['activo']);
        $this->fabricante->setDbValue($row['fabricante']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['ci_rif'] = $this->ci_rif->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['ciudad'] = $this->ciudad->DefaultValue;
        $row['direccion'] = $this->direccion->DefaultValue;
        $row['telefono1'] = $this->telefono1->DefaultValue;
        $row['telefono2'] = $this->telefono2->DefaultValue;
        $row['email1'] = $this->email1->DefaultValue;
        $row['email2'] = $this->email2->DefaultValue;
        $row['cuenta_auxiliar'] = $this->cuenta_auxiliar->DefaultValue;
        $row['cuenta_gasto'] = $this->cuenta_gasto->DefaultValue;
        $row['tipo_iva'] = $this->tipo_iva->DefaultValue;
        $row['tipo_islr'] = $this->tipo_islr->DefaultValue;
        $row['sustraendo'] = $this->sustraendo->DefaultValue;
        $row['tipo_impmun'] = $this->tipo_impmun->DefaultValue;
        $row['cta_bco'] = $this->cta_bco->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['fabricante'] = $this->fabricante->DefaultValue;
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

        // ciudad
        $this->ciudad->RowCssClass = "row";

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

        // cuenta_auxiliar
        $this->cuenta_auxiliar->RowCssClass = "row";

        // cuenta_gasto
        $this->cuenta_gasto->RowCssClass = "row";

        // tipo_iva
        $this->tipo_iva->RowCssClass = "row";

        // tipo_islr
        $this->tipo_islr->RowCssClass = "row";

        // sustraendo
        $this->sustraendo->RowCssClass = "row";

        // tipo_impmun
        $this->tipo_impmun->RowCssClass = "row";

        // cta_bco
        $this->cta_bco->RowCssClass = "row";

        // activo
        $this->activo->RowCssClass = "row";

        // fabricante
        $this->fabricante->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;

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

            // cuenta_auxiliar
            $curVal = strval($this->cuenta_auxiliar->CurrentValue);
            if ($curVal != "") {
                $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->lookupCacheOption($curVal);
                if ($this->cuenta_auxiliar->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cuenta_auxiliar->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta_auxiliar->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $lookupFilter = $this->cuenta_auxiliar->getSelectFilter($this); // PHP
                    $sqlWrk = $this->cuenta_auxiliar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta_auxiliar->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->displayValue($arwrk);
                    } else {
                        $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->CurrentValue;
                    }
                }
            } else {
                $this->cuenta_auxiliar->ViewValue = null;
            }

            // cuenta_gasto
            $curVal = strval($this->cuenta_gasto->CurrentValue);
            if ($curVal != "") {
                $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->lookupCacheOption($curVal);
                if ($this->cuenta_gasto->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cuenta_gasto->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta_gasto->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $lookupFilter = $this->cuenta_gasto->getSelectFilter($this); // PHP
                    $sqlWrk = $this->cuenta_gasto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta_gasto->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->displayValue($arwrk);
                    } else {
                        $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->CurrentValue;
                    }
                }
            } else {
                $this->cuenta_gasto->ViewValue = null;
            }

            // tipo_iva
            $curVal = strval($this->tipo_iva->CurrentValue);
            if ($curVal != "") {
                $this->tipo_iva->ViewValue = $this->tipo_iva->lookupCacheOption($curVal);
                if ($this->tipo_iva->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_iva->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $curVal, $this->tipo_iva->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_iva->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_iva->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_iva->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_iva->ViewValue = $this->tipo_iva->displayValue($arwrk);
                    } else {
                        $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;
                    }
                }
            } else {
                $this->tipo_iva->ViewValue = null;
            }

            // tipo_islr
            $curVal = strval($this->tipo_islr->CurrentValue);
            if ($curVal != "") {
                $this->tipo_islr->ViewValue = $this->tipo_islr->lookupCacheOption($curVal);
                if ($this->tipo_islr->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_islr->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $curVal, $this->tipo_islr->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_islr->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_islr->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_islr->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_islr->ViewValue = $this->tipo_islr->displayValue($arwrk);
                    } else {
                        $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;
                    }
                }
            } else {
                $this->tipo_islr->ViewValue = null;
            }

            // sustraendo
            $curVal = strval($this->sustraendo->CurrentValue);
            if ($curVal != "") {
                $this->sustraendo->ViewValue = $this->sustraendo->lookupCacheOption($curVal);
                if ($this->sustraendo->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->sustraendo->Lookup->getTable()->Fields["valor4"]->searchExpression(), "=", $curVal, $this->sustraendo->Lookup->getTable()->Fields["valor4"]->searchDataType(), "");
                    $lookupFilter = $this->sustraendo->getSelectFilter($this); // PHP
                    $sqlWrk = $this->sustraendo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->sustraendo->Lookup->renderViewRow($rswrk[0]);
                        $this->sustraendo->ViewValue = $this->sustraendo->displayValue($arwrk);
                    } else {
                        $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
                    }
                }
            } else {
                $this->sustraendo->ViewValue = null;
            }

            // tipo_impmun
            $curVal = strval($this->tipo_impmun->CurrentValue);
            if ($curVal != "") {
                $this->tipo_impmun->ViewValue = $this->tipo_impmun->lookupCacheOption($curVal);
                if ($this->tipo_impmun->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_impmun->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $curVal, $this->tipo_impmun->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_impmun->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_impmun->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_impmun->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_impmun->ViewValue = $this->tipo_impmun->displayValue($arwrk);
                    } else {
                        $this->tipo_impmun->ViewValue = $this->tipo_impmun->CurrentValue;
                    }
                }
            } else {
                $this->tipo_impmun->ViewValue = null;
            }

            // cta_bco
            $this->cta_bco->ViewValue = $this->cta_bco->CurrentValue;

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
            $this->fabricante->ViewValue = FormatNumber($this->fabricante->ViewValue, $this->fabricante->formatPattern());

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // ciudad
            $this->ciudad->HrefValue = "";

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

            // cuenta_auxiliar
            $this->cuenta_auxiliar->HrefValue = "";

            // cuenta_gasto
            $this->cuenta_gasto->HrefValue = "";

            // tipo_iva
            $this->tipo_iva->HrefValue = "";

            // tipo_islr
            $this->tipo_islr->HrefValue = "";

            // sustraendo
            $this->sustraendo->HrefValue = "";

            // tipo_impmun
            $this->tipo_impmun->HrefValue = "";

            // cta_bco
            $this->cta_bco->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
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

            // cuenta_auxiliar
            $curVal = trim(strval($this->cuenta_auxiliar->CurrentValue));
            if ($curVal != "") {
                $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->lookupCacheOption($curVal);
            } else {
                $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->Lookup !== null && is_array($this->cuenta_auxiliar->lookupOptions()) && count($this->cuenta_auxiliar->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cuenta_auxiliar->ViewValue !== null) { // Load from cache
                $this->cuenta_auxiliar->EditValue = array_values($this->cuenta_auxiliar->lookupOptions());
                if ($this->cuenta_auxiliar->ViewValue == "") {
                    $this->cuenta_auxiliar->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cuenta_auxiliar->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cuenta_auxiliar->CurrentValue, $this->cuenta_auxiliar->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $lookupFilter = $this->cuenta_auxiliar->getSelectFilter($this); // PHP
                $sqlWrk = $this->cuenta_auxiliar->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta_auxiliar->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->displayValue($arwrk);
                } else {
                    $this->cuenta_auxiliar->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cuenta_auxiliar->EditValue = $arwrk;
            }
            $this->cuenta_auxiliar->PlaceHolder = RemoveHtml($this->cuenta_auxiliar->caption());

            // cuenta_gasto
            $curVal = trim(strval($this->cuenta_gasto->CurrentValue));
            if ($curVal != "") {
                $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->lookupCacheOption($curVal);
            } else {
                $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->Lookup !== null && is_array($this->cuenta_gasto->lookupOptions()) && count($this->cuenta_gasto->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cuenta_gasto->ViewValue !== null) { // Load from cache
                $this->cuenta_gasto->EditValue = array_values($this->cuenta_gasto->lookupOptions());
                if ($this->cuenta_gasto->ViewValue == "") {
                    $this->cuenta_gasto->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cuenta_gasto->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cuenta_gasto->CurrentValue, $this->cuenta_gasto->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $lookupFilter = $this->cuenta_gasto->getSelectFilter($this); // PHP
                $sqlWrk = $this->cuenta_gasto->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta_gasto->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->displayValue($arwrk);
                } else {
                    $this->cuenta_gasto->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cuenta_gasto->EditValue = $arwrk;
            }
            $this->cuenta_gasto->PlaceHolder = RemoveHtml($this->cuenta_gasto->caption());

            // tipo_iva
            $this->tipo_iva->setupEditAttributes();
            $curVal = trim(strval($this->tipo_iva->CurrentValue));
            if ($curVal != "") {
                $this->tipo_iva->ViewValue = $this->tipo_iva->lookupCacheOption($curVal);
            } else {
                $this->tipo_iva->ViewValue = $this->tipo_iva->Lookup !== null && is_array($this->tipo_iva->lookupOptions()) && count($this->tipo_iva->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_iva->ViewValue !== null) { // Load from cache
                $this->tipo_iva->EditValue = array_values($this->tipo_iva->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_iva->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $this->tipo_iva->CurrentValue, $this->tipo_iva->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_iva->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_iva->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_iva->EditValue = $arwrk;
            }
            $this->tipo_iva->PlaceHolder = RemoveHtml($this->tipo_iva->caption());

            // tipo_islr
            $this->tipo_islr->setupEditAttributes();
            $curVal = trim(strval($this->tipo_islr->CurrentValue));
            if ($curVal != "") {
                $this->tipo_islr->ViewValue = $this->tipo_islr->lookupCacheOption($curVal);
            } else {
                $this->tipo_islr->ViewValue = $this->tipo_islr->Lookup !== null && is_array($this->tipo_islr->lookupOptions()) && count($this->tipo_islr->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_islr->ViewValue !== null) { // Load from cache
                $this->tipo_islr->EditValue = array_values($this->tipo_islr->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_islr->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $this->tipo_islr->CurrentValue, $this->tipo_islr->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_islr->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_islr->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_islr->EditValue = $arwrk;
            }
            $this->tipo_islr->PlaceHolder = RemoveHtml($this->tipo_islr->caption());

            // sustraendo
            $this->sustraendo->setupEditAttributes();
            $curVal = trim(strval($this->sustraendo->CurrentValue));
            if ($curVal != "") {
                $this->sustraendo->ViewValue = $this->sustraendo->lookupCacheOption($curVal);
            } else {
                $this->sustraendo->ViewValue = $this->sustraendo->Lookup !== null && is_array($this->sustraendo->lookupOptions()) && count($this->sustraendo->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->sustraendo->ViewValue !== null) { // Load from cache
                $this->sustraendo->EditValue = array_values($this->sustraendo->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->sustraendo->Lookup->getTable()->Fields["valor4"]->searchExpression(), "=", $this->sustraendo->CurrentValue, $this->sustraendo->Lookup->getTable()->Fields["valor4"]->searchDataType(), "");
                }
                $lookupFilter = $this->sustraendo->getSelectFilter($this); // PHP
                $sqlWrk = $this->sustraendo->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->sustraendo->EditValue = $arwrk;
            }
            $this->sustraendo->PlaceHolder = RemoveHtml($this->sustraendo->caption());

            // tipo_impmun
            $this->tipo_impmun->setupEditAttributes();
            $curVal = trim(strval($this->tipo_impmun->CurrentValue));
            if ($curVal != "") {
                $this->tipo_impmun->ViewValue = $this->tipo_impmun->lookupCacheOption($curVal);
            } else {
                $this->tipo_impmun->ViewValue = $this->tipo_impmun->Lookup !== null && is_array($this->tipo_impmun->lookupOptions()) && count($this->tipo_impmun->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_impmun->ViewValue !== null) { // Load from cache
                $this->tipo_impmun->EditValue = array_values($this->tipo_impmun->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_impmun->Lookup->getTable()->Fields["valor2"]->searchExpression(), "=", $this->tipo_impmun->CurrentValue, $this->tipo_impmun->Lookup->getTable()->Fields["valor2"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_impmun->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_impmun->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_impmun->EditValue = $arwrk;
            }
            $this->tipo_impmun->PlaceHolder = RemoveHtml($this->tipo_impmun->caption());

            // cta_bco
            $this->cta_bco->setupEditAttributes();
            if (!$this->cta_bco->Raw) {
                $this->cta_bco->CurrentValue = HtmlDecode($this->cta_bco->CurrentValue);
            }
            $this->cta_bco->EditValue = HtmlEncode($this->cta_bco->CurrentValue);
            $this->cta_bco->PlaceHolder = RemoveHtml($this->cta_bco->caption());

            // activo
            $this->activo->setupEditAttributes();
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // fabricante
            $this->fabricante->setupEditAttributes();
            $this->fabricante->EditValue = $this->fabricante->CurrentValue;
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());
            if (strval($this->fabricante->EditValue) != "" && is_numeric($this->fabricante->EditValue)) {
                $this->fabricante->EditValue = FormatNumber($this->fabricante->EditValue, null);
            }

            // Edit refer script

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // ciudad
            $this->ciudad->HrefValue = "";

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

            // cuenta_auxiliar
            $this->cuenta_auxiliar->HrefValue = "";

            // cuenta_gasto
            $this->cuenta_gasto->HrefValue = "";

            // tipo_iva
            $this->tipo_iva->HrefValue = "";

            // tipo_islr
            $this->tipo_islr->HrefValue = "";

            // sustraendo
            $this->sustraendo->HrefValue = "";

            // tipo_impmun
            $this->tipo_impmun->HrefValue = "";

            // cta_bco
            $this->cta_bco->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";

            // fabricante
            $this->fabricante->HrefValue = "";
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
            if ($this->ciudad->Visible && $this->ciudad->Required) {
                if (!$this->ciudad->IsDetailKey && EmptyValue($this->ciudad->FormValue)) {
                    $this->ciudad->addErrorMessage(str_replace("%s", $this->ciudad->caption(), $this->ciudad->RequiredErrorMessage));
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
            if (!CheckEmail($this->email2->FormValue)) {
                $this->email2->addErrorMessage($this->email2->getErrorMessage(false));
            }
            if ($this->cuenta_auxiliar->Visible && $this->cuenta_auxiliar->Required) {
                if (!$this->cuenta_auxiliar->IsDetailKey && EmptyValue($this->cuenta_auxiliar->FormValue)) {
                    $this->cuenta_auxiliar->addErrorMessage(str_replace("%s", $this->cuenta_auxiliar->caption(), $this->cuenta_auxiliar->RequiredErrorMessage));
                }
            }
            if ($this->cuenta_gasto->Visible && $this->cuenta_gasto->Required) {
                if (!$this->cuenta_gasto->IsDetailKey && EmptyValue($this->cuenta_gasto->FormValue)) {
                    $this->cuenta_gasto->addErrorMessage(str_replace("%s", $this->cuenta_gasto->caption(), $this->cuenta_gasto->RequiredErrorMessage));
                }
            }
            if ($this->tipo_iva->Visible && $this->tipo_iva->Required) {
                if (!$this->tipo_iva->IsDetailKey && EmptyValue($this->tipo_iva->FormValue)) {
                    $this->tipo_iva->addErrorMessage(str_replace("%s", $this->tipo_iva->caption(), $this->tipo_iva->RequiredErrorMessage));
                }
            }
            if ($this->tipo_islr->Visible && $this->tipo_islr->Required) {
                if (!$this->tipo_islr->IsDetailKey && EmptyValue($this->tipo_islr->FormValue)) {
                    $this->tipo_islr->addErrorMessage(str_replace("%s", $this->tipo_islr->caption(), $this->tipo_islr->RequiredErrorMessage));
                }
            }
            if ($this->sustraendo->Visible && $this->sustraendo->Required) {
                if (!$this->sustraendo->IsDetailKey && EmptyValue($this->sustraendo->FormValue)) {
                    $this->sustraendo->addErrorMessage(str_replace("%s", $this->sustraendo->caption(), $this->sustraendo->RequiredErrorMessage));
                }
            }
            if ($this->tipo_impmun->Visible && $this->tipo_impmun->Required) {
                if (!$this->tipo_impmun->IsDetailKey && EmptyValue($this->tipo_impmun->FormValue)) {
                    $this->tipo_impmun->addErrorMessage(str_replace("%s", $this->tipo_impmun->caption(), $this->tipo_impmun->RequiredErrorMessage));
                }
            }
            if ($this->cta_bco->Visible && $this->cta_bco->Required) {
                if (!$this->cta_bco->IsDetailKey && EmptyValue($this->cta_bco->FormValue)) {
                    $this->cta_bco->addErrorMessage(str_replace("%s", $this->cta_bco->caption(), $this->cta_bco->RequiredErrorMessage));
                }
            }
            if ($this->activo->Visible && $this->activo->Required) {
                if (!$this->activo->IsDetailKey && EmptyValue($this->activo->FormValue)) {
                    $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
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

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ProveedorArticuloGrid");
        if (in_array("proveedor_articulo", $detailTblVar) && $detailPage->DetailEdit) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("AdjuntoGrid");
        if (in_array("adjunto", $detailTblVar) && $detailPage->DetailEdit) {
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

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

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

            // Update detail records
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ProveedorArticuloGrid");
            if (in_array("proveedor_articulo", $detailTblVar) && $detailPage->DetailEdit && $editRow) {
                $Security->loadCurrentUserLevel($this->ProjectID . "proveedor_articulo"); // Load user level of detail table
                $editRow = $detailPage->gridUpdate();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
            }
            $detailPage = Container("AdjuntoGrid");
            if (in_array("adjunto", $detailTblVar) && $detailPage->DetailEdit && $editRow) {
                $Security->loadCurrentUserLevel($this->ProjectID . "adjunto"); // Load user level of detail table
                $editRow = $detailPage->gridUpdate();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
            }

            // Commit/Rollback transaction
            if ($this->getCurrentDetailTable() != "") {
                if ($editRow) {
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

        // ci_rif
        $this->ci_rif->setDbValueDef($rsnew, $this->ci_rif->CurrentValue, $this->ci_rif->ReadOnly);

        // nombre
        $this->nombre->setDbValueDef($rsnew, $this->nombre->CurrentValue, $this->nombre->ReadOnly);

        // ciudad
        $this->ciudad->setDbValueDef($rsnew, $this->ciudad->CurrentValue, $this->ciudad->ReadOnly);

        // direccion
        $this->direccion->setDbValueDef($rsnew, $this->direccion->CurrentValue, $this->direccion->ReadOnly);

        // telefono1
        $this->telefono1->setDbValueDef($rsnew, $this->telefono1->CurrentValue, $this->telefono1->ReadOnly);

        // telefono2
        $this->telefono2->setDbValueDef($rsnew, $this->telefono2->CurrentValue, $this->telefono2->ReadOnly);

        // email1
        $this->email1->setDbValueDef($rsnew, $this->email1->CurrentValue, $this->email1->ReadOnly);

        // email2
        $this->email2->setDbValueDef($rsnew, $this->email2->CurrentValue, $this->email2->ReadOnly);

        // cuenta_auxiliar
        $this->cuenta_auxiliar->setDbValueDef($rsnew, $this->cuenta_auxiliar->CurrentValue, $this->cuenta_auxiliar->ReadOnly);

        // cuenta_gasto
        $this->cuenta_gasto->setDbValueDef($rsnew, $this->cuenta_gasto->CurrentValue, $this->cuenta_gasto->ReadOnly);

        // tipo_iva
        $this->tipo_iva->setDbValueDef($rsnew, $this->tipo_iva->CurrentValue, $this->tipo_iva->ReadOnly);

        // tipo_islr
        $this->tipo_islr->setDbValueDef($rsnew, $this->tipo_islr->CurrentValue, $this->tipo_islr->ReadOnly);

        // sustraendo
        $this->sustraendo->setDbValueDef($rsnew, $this->sustraendo->CurrentValue, $this->sustraendo->ReadOnly);

        // tipo_impmun
        $this->tipo_impmun->setDbValueDef($rsnew, $this->tipo_impmun->CurrentValue, $this->tipo_impmun->ReadOnly);

        // cta_bco
        $this->cta_bco->setDbValueDef($rsnew, $this->cta_bco->CurrentValue, $this->cta_bco->ReadOnly);

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, $this->activo->ReadOnly);

        // fabricante
        $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, $this->fabricante->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['ci_rif'])) { // ci_rif
            $this->ci_rif->CurrentValue = $row['ci_rif'];
        }
        if (isset($row['nombre'])) { // nombre
            $this->nombre->CurrentValue = $row['nombre'];
        }
        if (isset($row['ciudad'])) { // ciudad
            $this->ciudad->CurrentValue = $row['ciudad'];
        }
        if (isset($row['direccion'])) { // direccion
            $this->direccion->CurrentValue = $row['direccion'];
        }
        if (isset($row['telefono1'])) { // telefono1
            $this->telefono1->CurrentValue = $row['telefono1'];
        }
        if (isset($row['telefono2'])) { // telefono2
            $this->telefono2->CurrentValue = $row['telefono2'];
        }
        if (isset($row['email1'])) { // email1
            $this->email1->CurrentValue = $row['email1'];
        }
        if (isset($row['email2'])) { // email2
            $this->email2->CurrentValue = $row['email2'];
        }
        if (isset($row['cuenta_auxiliar'])) { // cuenta_auxiliar
            $this->cuenta_auxiliar->CurrentValue = $row['cuenta_auxiliar'];
        }
        if (isset($row['cuenta_gasto'])) { // cuenta_gasto
            $this->cuenta_gasto->CurrentValue = $row['cuenta_gasto'];
        }
        if (isset($row['tipo_iva'])) { // tipo_iva
            $this->tipo_iva->CurrentValue = $row['tipo_iva'];
        }
        if (isset($row['tipo_islr'])) { // tipo_islr
            $this->tipo_islr->CurrentValue = $row['tipo_islr'];
        }
        if (isset($row['sustraendo'])) { // sustraendo
            $this->sustraendo->CurrentValue = $row['sustraendo'];
        }
        if (isset($row['tipo_impmun'])) { // tipo_impmun
            $this->tipo_impmun->CurrentValue = $row['tipo_impmun'];
        }
        if (isset($row['cta_bco'])) { // cta_bco
            $this->cta_bco->CurrentValue = $row['cta_bco'];
        }
        if (isset($row['activo'])) { // activo
            $this->activo->CurrentValue = $row['activo'];
        }
        if (isset($row['fabricante'])) { // fabricante
            $this->fabricante->CurrentValue = $row['fabricante'];
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
            if (in_array("proveedor_articulo", $detailTblVar)) {
                $detailPageObj = Container("ProveedorArticuloGrid");
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->proveedor->IsDetailKey = true;
                    $detailPageObj->proveedor->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->proveedor->setSessionValue($detailPageObj->proveedor->CurrentValue);
                }
            }
            if (in_array("adjunto", $detailTblVar)) {
                $detailPageObj = Container("AdjuntoGrid");
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->proveedor->IsDetailKey = true;
                    $detailPageObj->proveedor->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->proveedor->setSessionValue($detailPageObj->proveedor->CurrentValue);
                    $detailPageObj->articulo->setSessionValue(""); // Clear session key
                    $detailPageObj->cliente->setSessionValue(""); // Clear session key
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ProveedorList"), "", $this->TableVar, true);
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
                case "x_ciudad":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_cuenta_auxiliar":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_cuenta_gasto":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_iva":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_islr":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_sustraendo":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_impmun":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
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
