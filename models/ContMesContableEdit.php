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
class ContMesContableEdit extends ContMesContable
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ContMesContableEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ContMesContableEdit";

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
        $this->tipo_comprobante->setVisibility();
        $this->descripcion->setVisibility();
        $this->M01->setVisibility();
        $this->M02->setVisibility();
        $this->M03->setVisibility();
        $this->M04->setVisibility();
        $this->M05->setVisibility();
        $this->M06->setVisibility();
        $this->M07->setVisibility();
        $this->M08->setVisibility();
        $this->M09->setVisibility();
        $this->M10->setVisibility();
        $this->M11->setVisibility();
        $this->M12->setVisibility();
        $this->activo->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cont_mes_contable';
        $this->TableName = 'cont_mes_contable';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cont_mes_contable)
        if (!isset($GLOBALS["cont_mes_contable"]) || $GLOBALS["cont_mes_contable"]::class == PROJECT_NAMESPACE . "cont_mes_contable") {
            $GLOBALS["cont_mes_contable"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cont_mes_contable');
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
                        $result["view"] = SameString($pageName, "ContMesContableView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->M01);
        $this->setupLookupOptions($this->M02);
        $this->setupLookupOptions($this->M03);
        $this->setupLookupOptions($this->M04);
        $this->setupLookupOptions($this->M05);
        $this->setupLookupOptions($this->M06);
        $this->setupLookupOptions($this->M07);
        $this->setupLookupOptions($this->M08);
        $this->setupLookupOptions($this->M09);
        $this->setupLookupOptions($this->M10);
        $this->setupLookupOptions($this->M11);
        $this->setupLookupOptions($this->M12);
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
                        $this->terminate("ContMesContableList"); // Return to list page
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
                        $this->terminate("ContMesContableList"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("ContMesContableList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "ContMesContableList") {
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
                        if (GetPageName($returnUrl) != "ContMesContableList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ContMesContableList"; // Return list page content
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

        // Check field name 'tipo_comprobante' first before field var 'x_tipo_comprobante'
        $val = $CurrentForm->hasValue("tipo_comprobante") ? $CurrentForm->getValue("tipo_comprobante") : $CurrentForm->getValue("x_tipo_comprobante");
        if (!$this->tipo_comprobante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_comprobante->Visible = false; // Disable update for API request
            } else {
                $this->tipo_comprobante->setFormValue($val);
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

        // Check field name 'M01' first before field var 'x_M01'
        $val = $CurrentForm->hasValue("M01") ? $CurrentForm->getValue("M01") : $CurrentForm->getValue("x_M01");
        if (!$this->M01->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M01->Visible = false; // Disable update for API request
            } else {
                $this->M01->setFormValue($val);
            }
        }

        // Check field name 'M02' first before field var 'x_M02'
        $val = $CurrentForm->hasValue("M02") ? $CurrentForm->getValue("M02") : $CurrentForm->getValue("x_M02");
        if (!$this->M02->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M02->Visible = false; // Disable update for API request
            } else {
                $this->M02->setFormValue($val);
            }
        }

        // Check field name 'M03' first before field var 'x_M03'
        $val = $CurrentForm->hasValue("M03") ? $CurrentForm->getValue("M03") : $CurrentForm->getValue("x_M03");
        if (!$this->M03->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M03->Visible = false; // Disable update for API request
            } else {
                $this->M03->setFormValue($val);
            }
        }

        // Check field name 'M04' first before field var 'x_M04'
        $val = $CurrentForm->hasValue("M04") ? $CurrentForm->getValue("M04") : $CurrentForm->getValue("x_M04");
        if (!$this->M04->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M04->Visible = false; // Disable update for API request
            } else {
                $this->M04->setFormValue($val);
            }
        }

        // Check field name 'M05' first before field var 'x_M05'
        $val = $CurrentForm->hasValue("M05") ? $CurrentForm->getValue("M05") : $CurrentForm->getValue("x_M05");
        if (!$this->M05->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M05->Visible = false; // Disable update for API request
            } else {
                $this->M05->setFormValue($val);
            }
        }

        // Check field name 'M06' first before field var 'x_M06'
        $val = $CurrentForm->hasValue("M06") ? $CurrentForm->getValue("M06") : $CurrentForm->getValue("x_M06");
        if (!$this->M06->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M06->Visible = false; // Disable update for API request
            } else {
                $this->M06->setFormValue($val);
            }
        }

        // Check field name 'M07' first before field var 'x_M07'
        $val = $CurrentForm->hasValue("M07") ? $CurrentForm->getValue("M07") : $CurrentForm->getValue("x_M07");
        if (!$this->M07->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M07->Visible = false; // Disable update for API request
            } else {
                $this->M07->setFormValue($val);
            }
        }

        // Check field name 'M08' first before field var 'x_M08'
        $val = $CurrentForm->hasValue("M08") ? $CurrentForm->getValue("M08") : $CurrentForm->getValue("x_M08");
        if (!$this->M08->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M08->Visible = false; // Disable update for API request
            } else {
                $this->M08->setFormValue($val);
            }
        }

        // Check field name 'M09' first before field var 'x_M09'
        $val = $CurrentForm->hasValue("M09") ? $CurrentForm->getValue("M09") : $CurrentForm->getValue("x_M09");
        if (!$this->M09->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M09->Visible = false; // Disable update for API request
            } else {
                $this->M09->setFormValue($val);
            }
        }

        // Check field name 'M10' first before field var 'x_M10'
        $val = $CurrentForm->hasValue("M10") ? $CurrentForm->getValue("M10") : $CurrentForm->getValue("x_M10");
        if (!$this->M10->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M10->Visible = false; // Disable update for API request
            } else {
                $this->M10->setFormValue($val);
            }
        }

        // Check field name 'M11' first before field var 'x_M11'
        $val = $CurrentForm->hasValue("M11") ? $CurrentForm->getValue("M11") : $CurrentForm->getValue("x_M11");
        if (!$this->M11->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M11->Visible = false; // Disable update for API request
            } else {
                $this->M11->setFormValue($val);
            }
        }

        // Check field name 'M12' first before field var 'x_M12'
        $val = $CurrentForm->hasValue("M12") ? $CurrentForm->getValue("M12") : $CurrentForm->getValue("x_M12");
        if (!$this->M12->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M12->Visible = false; // Disable update for API request
            } else {
                $this->M12->setFormValue($val);
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
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->tipo_comprobante->CurrentValue = $this->tipo_comprobante->FormValue;
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->M01->CurrentValue = $this->M01->FormValue;
        $this->M02->CurrentValue = $this->M02->FormValue;
        $this->M03->CurrentValue = $this->M03->FormValue;
        $this->M04->CurrentValue = $this->M04->FormValue;
        $this->M05->CurrentValue = $this->M05->FormValue;
        $this->M06->CurrentValue = $this->M06->FormValue;
        $this->M07->CurrentValue = $this->M07->FormValue;
        $this->M08->CurrentValue = $this->M08->FormValue;
        $this->M09->CurrentValue = $this->M09->FormValue;
        $this->M10->CurrentValue = $this->M10->FormValue;
        $this->M11->CurrentValue = $this->M11->FormValue;
        $this->M12->CurrentValue = $this->M12->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
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
        $this->tipo_comprobante->setDbValue($row['tipo_comprobante']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->M01->setDbValue($row['M01']);
        $this->M02->setDbValue($row['M02']);
        $this->M03->setDbValue($row['M03']);
        $this->M04->setDbValue($row['M04']);
        $this->M05->setDbValue($row['M05']);
        $this->M06->setDbValue($row['M06']);
        $this->M07->setDbValue($row['M07']);
        $this->M08->setDbValue($row['M08']);
        $this->M09->setDbValue($row['M09']);
        $this->M10->setDbValue($row['M10']);
        $this->M11->setDbValue($row['M11']);
        $this->M12->setDbValue($row['M12']);
        $this->activo->setDbValue($row['activo']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['tipo_comprobante'] = $this->tipo_comprobante->DefaultValue;
        $row['descripcion'] = $this->descripcion->DefaultValue;
        $row['M01'] = $this->M01->DefaultValue;
        $row['M02'] = $this->M02->DefaultValue;
        $row['M03'] = $this->M03->DefaultValue;
        $row['M04'] = $this->M04->DefaultValue;
        $row['M05'] = $this->M05->DefaultValue;
        $row['M06'] = $this->M06->DefaultValue;
        $row['M07'] = $this->M07->DefaultValue;
        $row['M08'] = $this->M08->DefaultValue;
        $row['M09'] = $this->M09->DefaultValue;
        $row['M10'] = $this->M10->DefaultValue;
        $row['M11'] = $this->M11->DefaultValue;
        $row['M12'] = $this->M12->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
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

        // tipo_comprobante
        $this->tipo_comprobante->RowCssClass = "row";

        // descripcion
        $this->descripcion->RowCssClass = "row";

        // M01
        $this->M01->RowCssClass = "row";

        // M02
        $this->M02->RowCssClass = "row";

        // M03
        $this->M03->RowCssClass = "row";

        // M04
        $this->M04->RowCssClass = "row";

        // M05
        $this->M05->RowCssClass = "row";

        // M06
        $this->M06->RowCssClass = "row";

        // M07
        $this->M07->RowCssClass = "row";

        // M08
        $this->M08->RowCssClass = "row";

        // M09
        $this->M09->RowCssClass = "row";

        // M10
        $this->M10->RowCssClass = "row";

        // M11
        $this->M11->RowCssClass = "row";

        // M12
        $this->M12->RowCssClass = "row";

        // activo
        $this->activo->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // tipo_comprobante
            $this->tipo_comprobante->ViewValue = $this->tipo_comprobante->CurrentValue;

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

            // M01
            if (strval($this->M01->CurrentValue) != "") {
                $this->M01->ViewValue = $this->M01->optionCaption($this->M01->CurrentValue);
            } else {
                $this->M01->ViewValue = null;
            }

            // M02
            if (strval($this->M02->CurrentValue) != "") {
                $this->M02->ViewValue = $this->M02->optionCaption($this->M02->CurrentValue);
            } else {
                $this->M02->ViewValue = null;
            }

            // M03
            if (strval($this->M03->CurrentValue) != "") {
                $this->M03->ViewValue = $this->M03->optionCaption($this->M03->CurrentValue);
            } else {
                $this->M03->ViewValue = null;
            }

            // M04
            if (strval($this->M04->CurrentValue) != "") {
                $this->M04->ViewValue = $this->M04->optionCaption($this->M04->CurrentValue);
            } else {
                $this->M04->ViewValue = null;
            }

            // M05
            if (strval($this->M05->CurrentValue) != "") {
                $this->M05->ViewValue = $this->M05->optionCaption($this->M05->CurrentValue);
            } else {
                $this->M05->ViewValue = null;
            }

            // M06
            if (strval($this->M06->CurrentValue) != "") {
                $this->M06->ViewValue = $this->M06->optionCaption($this->M06->CurrentValue);
            } else {
                $this->M06->ViewValue = null;
            }

            // M07
            if (strval($this->M07->CurrentValue) != "") {
                $this->M07->ViewValue = $this->M07->optionCaption($this->M07->CurrentValue);
            } else {
                $this->M07->ViewValue = null;
            }

            // M08
            if (strval($this->M08->CurrentValue) != "") {
                $this->M08->ViewValue = $this->M08->optionCaption($this->M08->CurrentValue);
            } else {
                $this->M08->ViewValue = null;
            }

            // M09
            if (strval($this->M09->CurrentValue) != "") {
                $this->M09->ViewValue = $this->M09->optionCaption($this->M09->CurrentValue);
            } else {
                $this->M09->ViewValue = null;
            }

            // M10
            if (strval($this->M10->CurrentValue) != "") {
                $this->M10->ViewValue = $this->M10->optionCaption($this->M10->CurrentValue);
            } else {
                $this->M10->ViewValue = null;
            }

            // M11
            if (strval($this->M11->CurrentValue) != "") {
                $this->M11->ViewValue = $this->M11->optionCaption($this->M11->CurrentValue);
            } else {
                $this->M11->ViewValue = null;
            }

            // M12
            if (strval($this->M12->CurrentValue) != "") {
                $this->M12->ViewValue = $this->M12->optionCaption($this->M12->CurrentValue);
            } else {
                $this->M12->ViewValue = null;
            }

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // tipo_comprobante
            $this->tipo_comprobante->HrefValue = "";
            $this->tipo_comprobante->TooltipValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // M01
            $this->M01->HrefValue = "";

            // M02
            $this->M02->HrefValue = "";

            // M03
            $this->M03->HrefValue = "";

            // M04
            $this->M04->HrefValue = "";

            // M05
            $this->M05->HrefValue = "";

            // M06
            $this->M06->HrefValue = "";

            // M07
            $this->M07->HrefValue = "";

            // M08
            $this->M08->HrefValue = "";

            // M09
            $this->M09->HrefValue = "";

            // M10
            $this->M10->HrefValue = "";

            // M11
            $this->M11->HrefValue = "";

            // M12
            $this->M12->HrefValue = "";

            // activo
            $this->activo->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // tipo_comprobante
            $this->tipo_comprobante->setupEditAttributes();
            $this->tipo_comprobante->EditValue = $this->tipo_comprobante->CurrentValue;

            // descripcion
            $this->descripcion->setupEditAttributes();
            $this->descripcion->EditValue = $this->descripcion->CurrentValue;

            // M01
            $this->M01->EditValue = $this->M01->options(false);
            $this->M01->PlaceHolder = RemoveHtml($this->M01->caption());

            // M02
            $this->M02->EditValue = $this->M02->options(false);
            $this->M02->PlaceHolder = RemoveHtml($this->M02->caption());

            // M03
            $this->M03->EditValue = $this->M03->options(false);
            $this->M03->PlaceHolder = RemoveHtml($this->M03->caption());

            // M04
            $this->M04->EditValue = $this->M04->options(false);
            $this->M04->PlaceHolder = RemoveHtml($this->M04->caption());

            // M05
            $this->M05->EditValue = $this->M05->options(false);
            $this->M05->PlaceHolder = RemoveHtml($this->M05->caption());

            // M06
            $this->M06->EditValue = $this->M06->options(false);
            $this->M06->PlaceHolder = RemoveHtml($this->M06->caption());

            // M07
            $this->M07->EditValue = $this->M07->options(false);
            $this->M07->PlaceHolder = RemoveHtml($this->M07->caption());

            // M08
            $this->M08->EditValue = $this->M08->options(false);
            $this->M08->PlaceHolder = RemoveHtml($this->M08->caption());

            // M09
            $this->M09->EditValue = $this->M09->options(false);
            $this->M09->PlaceHolder = RemoveHtml($this->M09->caption());

            // M10
            $this->M10->EditValue = $this->M10->options(false);
            $this->M10->PlaceHolder = RemoveHtml($this->M10->caption());

            // M11
            $this->M11->EditValue = $this->M11->options(false);
            $this->M11->PlaceHolder = RemoveHtml($this->M11->caption());

            // M12
            $this->M12->EditValue = $this->M12->options(false);
            $this->M12->PlaceHolder = RemoveHtml($this->M12->caption());

            // activo
            $this->activo->EditValue = $this->activo->options(false);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // Edit refer script

            // tipo_comprobante
            $this->tipo_comprobante->HrefValue = "";
            $this->tipo_comprobante->TooltipValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // M01
            $this->M01->HrefValue = "";

            // M02
            $this->M02->HrefValue = "";

            // M03
            $this->M03->HrefValue = "";

            // M04
            $this->M04->HrefValue = "";

            // M05
            $this->M05->HrefValue = "";

            // M06
            $this->M06->HrefValue = "";

            // M07
            $this->M07->HrefValue = "";

            // M08
            $this->M08->HrefValue = "";

            // M09
            $this->M09->HrefValue = "";

            // M10
            $this->M10->HrefValue = "";

            // M11
            $this->M11->HrefValue = "";

            // M12
            $this->M12->HrefValue = "";

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
            if ($this->tipo_comprobante->Visible && $this->tipo_comprobante->Required) {
                if (!$this->tipo_comprobante->IsDetailKey && EmptyValue($this->tipo_comprobante->FormValue)) {
                    $this->tipo_comprobante->addErrorMessage(str_replace("%s", $this->tipo_comprobante->caption(), $this->tipo_comprobante->RequiredErrorMessage));
                }
            }
            if ($this->descripcion->Visible && $this->descripcion->Required) {
                if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                    $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
                }
            }
            if ($this->M01->Visible && $this->M01->Required) {
                if ($this->M01->FormValue == "") {
                    $this->M01->addErrorMessage(str_replace("%s", $this->M01->caption(), $this->M01->RequiredErrorMessage));
                }
            }
            if ($this->M02->Visible && $this->M02->Required) {
                if ($this->M02->FormValue == "") {
                    $this->M02->addErrorMessage(str_replace("%s", $this->M02->caption(), $this->M02->RequiredErrorMessage));
                }
            }
            if ($this->M03->Visible && $this->M03->Required) {
                if ($this->M03->FormValue == "") {
                    $this->M03->addErrorMessage(str_replace("%s", $this->M03->caption(), $this->M03->RequiredErrorMessage));
                }
            }
            if ($this->M04->Visible && $this->M04->Required) {
                if ($this->M04->FormValue == "") {
                    $this->M04->addErrorMessage(str_replace("%s", $this->M04->caption(), $this->M04->RequiredErrorMessage));
                }
            }
            if ($this->M05->Visible && $this->M05->Required) {
                if ($this->M05->FormValue == "") {
                    $this->M05->addErrorMessage(str_replace("%s", $this->M05->caption(), $this->M05->RequiredErrorMessage));
                }
            }
            if ($this->M06->Visible && $this->M06->Required) {
                if ($this->M06->FormValue == "") {
                    $this->M06->addErrorMessage(str_replace("%s", $this->M06->caption(), $this->M06->RequiredErrorMessage));
                }
            }
            if ($this->M07->Visible && $this->M07->Required) {
                if ($this->M07->FormValue == "") {
                    $this->M07->addErrorMessage(str_replace("%s", $this->M07->caption(), $this->M07->RequiredErrorMessage));
                }
            }
            if ($this->M08->Visible && $this->M08->Required) {
                if ($this->M08->FormValue == "") {
                    $this->M08->addErrorMessage(str_replace("%s", $this->M08->caption(), $this->M08->RequiredErrorMessage));
                }
            }
            if ($this->M09->Visible && $this->M09->Required) {
                if ($this->M09->FormValue == "") {
                    $this->M09->addErrorMessage(str_replace("%s", $this->M09->caption(), $this->M09->RequiredErrorMessage));
                }
            }
            if ($this->M10->Visible && $this->M10->Required) {
                if ($this->M10->FormValue == "") {
                    $this->M10->addErrorMessage(str_replace("%s", $this->M10->caption(), $this->M10->RequiredErrorMessage));
                }
            }
            if ($this->M11->Visible && $this->M11->Required) {
                if ($this->M11->FormValue == "") {
                    $this->M11->addErrorMessage(str_replace("%s", $this->M11->caption(), $this->M11->RequiredErrorMessage));
                }
            }
            if ($this->M12->Visible && $this->M12->Required) {
                if ($this->M12->FormValue == "") {
                    $this->M12->addErrorMessage(str_replace("%s", $this->M12->caption(), $this->M12->RequiredErrorMessage));
                }
            }
            if ($this->activo->Visible && $this->activo->Required) {
                if ($this->activo->FormValue == "") {
                    $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
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

        // Check field with unique index (tipo_comprobante)
        if ($this->tipo_comprobante->CurrentValue != "") {
            $filterChk = "(`tipo_comprobante` = '" . AdjustSql($this->tipo_comprobante->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->tipo_comprobante->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->tipo_comprobante->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
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

        // M01
        $this->M01->setDbValueDef($rsnew, $this->M01->CurrentValue, $this->M01->ReadOnly);

        // M02
        $this->M02->setDbValueDef($rsnew, $this->M02->CurrentValue, $this->M02->ReadOnly);

        // M03
        $this->M03->setDbValueDef($rsnew, $this->M03->CurrentValue, $this->M03->ReadOnly);

        // M04
        $this->M04->setDbValueDef($rsnew, $this->M04->CurrentValue, $this->M04->ReadOnly);

        // M05
        $this->M05->setDbValueDef($rsnew, $this->M05->CurrentValue, $this->M05->ReadOnly);

        // M06
        $this->M06->setDbValueDef($rsnew, $this->M06->CurrentValue, $this->M06->ReadOnly);

        // M07
        $this->M07->setDbValueDef($rsnew, $this->M07->CurrentValue, $this->M07->ReadOnly);

        // M08
        $this->M08->setDbValueDef($rsnew, $this->M08->CurrentValue, $this->M08->ReadOnly);

        // M09
        $this->M09->setDbValueDef($rsnew, $this->M09->CurrentValue, $this->M09->ReadOnly);

        // M10
        $this->M10->setDbValueDef($rsnew, $this->M10->CurrentValue, $this->M10->ReadOnly);

        // M11
        $this->M11->setDbValueDef($rsnew, $this->M11->CurrentValue, $this->M11->ReadOnly);

        // M12
        $this->M12->setDbValueDef($rsnew, $this->M12->CurrentValue, $this->M12->ReadOnly);

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, $this->activo->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['M01'])) { // M01
            $this->M01->CurrentValue = $row['M01'];
        }
        if (isset($row['M02'])) { // M02
            $this->M02->CurrentValue = $row['M02'];
        }
        if (isset($row['M03'])) { // M03
            $this->M03->CurrentValue = $row['M03'];
        }
        if (isset($row['M04'])) { // M04
            $this->M04->CurrentValue = $row['M04'];
        }
        if (isset($row['M05'])) { // M05
            $this->M05->CurrentValue = $row['M05'];
        }
        if (isset($row['M06'])) { // M06
            $this->M06->CurrentValue = $row['M06'];
        }
        if (isset($row['M07'])) { // M07
            $this->M07->CurrentValue = $row['M07'];
        }
        if (isset($row['M08'])) { // M08
            $this->M08->CurrentValue = $row['M08'];
        }
        if (isset($row['M09'])) { // M09
            $this->M09->CurrentValue = $row['M09'];
        }
        if (isset($row['M10'])) { // M10
            $this->M10->CurrentValue = $row['M10'];
        }
        if (isset($row['M11'])) { // M11
            $this->M11->CurrentValue = $row['M11'];
        }
        if (isset($row['M12'])) { // M12
            $this->M12->CurrentValue = $row['M12'];
        }
        if (isset($row['activo'])) { // activo
            $this->activo->CurrentValue = $row['activo'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ContMesContableList"), "", $this->TableVar, true);
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
                case "x_M01":
                    break;
                case "x_M02":
                    break;
                case "x_M03":
                    break;
                case "x_M04":
                    break;
                case "x_M05":
                    break;
                case "x_M06":
                    break;
                case "x_M07":
                    break;
                case "x_M08":
                    break;
                case "x_M09":
                    break;
                case "x_M10":
                    break;
                case "x_M11":
                    break;
                case "x_M12":
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
