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
class CompraEdit extends Compra
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CompraEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "CompraEdit";

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
        $this->id->setVisibility();
        $this->proveedor->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->descripcion->setVisibility();
        $this->aplica_retencion->setVisibility();
        $this->monto_exento->setVisibility();
        $this->monto_gravado->setVisibility();
        $this->alicuota->setVisibility();
        $this->monto_iva->Visible = false;
        $this->monto_total->Visible = false;
        $this->monto_pagar->Visible = false;
        $this->ret_iva->Visible = false;
        $this->ref_iva->setVisibility();
        $this->ret_islr->Visible = false;
        $this->ref_islr->setVisibility();
        $this->ret_municipal->Visible = false;
        $this->ref_municipal->setVisibility();
        $this->fecha_registro->setVisibility();
        $this->_username->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->tipo_municipal->Visible = false;
        $this->anulado->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'compra';
        $this->TableName = 'compra';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (compra)
        if (!isset($GLOBALS["compra"]) || $GLOBALS["compra"]::class == PROJECT_NAMESPACE . "compra") {
            $GLOBALS["compra"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'compra');
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
                        $result["view"] = SameString($pageName, "CompraView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->tipo_documento);
        $this->setupLookupOptions($this->aplica_retencion);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->comprobante);
        $this->setupLookupOptions($this->anulado);

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
                        $this->terminate("CompraList"); // Return to list page
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
                        $this->terminate("CompraList"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("CompraList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getViewUrl();
                if (GetPageName($returnUrl) == "CompraList") {
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
                        if (GetPageName($returnUrl) != "CompraList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "CompraList"; // Return list page content
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

        // Check field name 'proveedor' first before field var 'x_proveedor'
        $val = $CurrentForm->hasValue("proveedor") ? $CurrentForm->getValue("proveedor") : $CurrentForm->getValue("x_proveedor");
        if (!$this->proveedor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->proveedor->Visible = false; // Disable update for API request
            } else {
                $this->proveedor->setFormValue($val);
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

        // Check field name 'doc_afectado' first before field var 'x_doc_afectado'
        $val = $CurrentForm->hasValue("doc_afectado") ? $CurrentForm->getValue("doc_afectado") : $CurrentForm->getValue("x_doc_afectado");
        if (!$this->doc_afectado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_afectado->Visible = false; // Disable update for API request
            } else {
                $this->doc_afectado->setFormValue($val);
            }
        }

        // Check field name 'documento' first before field var 'x_documento'
        $val = $CurrentForm->hasValue("documento") ? $CurrentForm->getValue("documento") : $CurrentForm->getValue("x_documento");
        if (!$this->documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->documento->Visible = false; // Disable update for API request
            } else {
                $this->documento->setFormValue($val);
            }
        }

        // Check field name 'nro_control' first before field var 'x_nro_control'
        $val = $CurrentForm->hasValue("nro_control") ? $CurrentForm->getValue("nro_control") : $CurrentForm->getValue("x_nro_control");
        if (!$this->nro_control->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_control->Visible = false; // Disable update for API request
            } else {
                $this->nro_control->setFormValue($val);
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

        // Check field name 'descripcion' first before field var 'x_descripcion'
        $val = $CurrentForm->hasValue("descripcion") ? $CurrentForm->getValue("descripcion") : $CurrentForm->getValue("x_descripcion");
        if (!$this->descripcion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->descripcion->Visible = false; // Disable update for API request
            } else {
                $this->descripcion->setFormValue($val);
            }
        }

        // Check field name 'aplica_retencion' first before field var 'x_aplica_retencion'
        $val = $CurrentForm->hasValue("aplica_retencion") ? $CurrentForm->getValue("aplica_retencion") : $CurrentForm->getValue("x_aplica_retencion");
        if (!$this->aplica_retencion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->aplica_retencion->Visible = false; // Disable update for API request
            } else {
                $this->aplica_retencion->setFormValue($val);
            }
        }

        // Check field name 'monto_exento' first before field var 'x_monto_exento'
        $val = $CurrentForm->hasValue("monto_exento") ? $CurrentForm->getValue("monto_exento") : $CurrentForm->getValue("x_monto_exento");
        if (!$this->monto_exento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_exento->Visible = false; // Disable update for API request
            } else {
                $this->monto_exento->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'monto_gravado' first before field var 'x_monto_gravado'
        $val = $CurrentForm->hasValue("monto_gravado") ? $CurrentForm->getValue("monto_gravado") : $CurrentForm->getValue("x_monto_gravado");
        if (!$this->monto_gravado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_gravado->Visible = false; // Disable update for API request
            } else {
                $this->monto_gravado->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'alicuota' first before field var 'x_alicuota'
        $val = $CurrentForm->hasValue("alicuota") ? $CurrentForm->getValue("alicuota") : $CurrentForm->getValue("x_alicuota");
        if (!$this->alicuota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->alicuota->Visible = false; // Disable update for API request
            } else {
                $this->alicuota->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'ref_iva' first before field var 'x_ref_iva'
        $val = $CurrentForm->hasValue("ref_iva") ? $CurrentForm->getValue("ref_iva") : $CurrentForm->getValue("x_ref_iva");
        if (!$this->ref_iva->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_iva->Visible = false; // Disable update for API request
            } else {
                $this->ref_iva->setFormValue($val);
            }
        }

        // Check field name 'ref_islr' first before field var 'x_ref_islr'
        $val = $CurrentForm->hasValue("ref_islr") ? $CurrentForm->getValue("ref_islr") : $CurrentForm->getValue("x_ref_islr");
        if (!$this->ref_islr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_islr->Visible = false; // Disable update for API request
            } else {
                $this->ref_islr->setFormValue($val);
            }
        }

        // Check field name 'ref_municipal' first before field var 'x_ref_municipal'
        $val = $CurrentForm->hasValue("ref_municipal") ? $CurrentForm->getValue("ref_municipal") : $CurrentForm->getValue("x_ref_municipal");
        if (!$this->ref_municipal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_municipal->Visible = false; // Disable update for API request
            } else {
                $this->ref_municipal->setFormValue($val);
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

        // Check field name 'anulado' first before field var 'x_anulado'
        $val = $CurrentForm->hasValue("anulado") ? $CurrentForm->getValue("anulado") : $CurrentForm->getValue("x_anulado");
        if (!$this->anulado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->anulado->Visible = false; // Disable update for API request
            } else {
                $this->anulado->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->proveedor->CurrentValue = $this->proveedor->FormValue;
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->nro_control->CurrentValue = $this->nro_control->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->aplica_retencion->CurrentValue = $this->aplica_retencion->FormValue;
        $this->monto_exento->CurrentValue = $this->monto_exento->FormValue;
        $this->monto_gravado->CurrentValue = $this->monto_gravado->FormValue;
        $this->alicuota->CurrentValue = $this->alicuota->FormValue;
        $this->ref_iva->CurrentValue = $this->ref_iva->FormValue;
        $this->ref_islr->CurrentValue = $this->ref_islr->FormValue;
        $this->ref_municipal->CurrentValue = $this->ref_municipal->FormValue;
        $this->fecha_registro->CurrentValue = $this->fecha_registro->FormValue;
        $this->fecha_registro->CurrentValue = UnFormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern());
        $this->anulado->CurrentValue = $this->anulado->FormValue;
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
        $this->proveedor->setDbValue($row['proveedor']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->documento->setDbValue($row['documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->monto_exento->setDbValue($row['monto_exento']);
        $this->monto_gravado->setDbValue($row['monto_gravado']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->monto_iva->setDbValue($row['monto_iva']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->anulado->setDbValue($row['anulado']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['proveedor'] = $this->proveedor->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['documento'] = $this->documento->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['descripcion'] = $this->descripcion->DefaultValue;
        $row['aplica_retencion'] = $this->aplica_retencion->DefaultValue;
        $row['monto_exento'] = $this->monto_exento->DefaultValue;
        $row['monto_gravado'] = $this->monto_gravado->DefaultValue;
        $row['alicuota'] = $this->alicuota->DefaultValue;
        $row['monto_iva'] = $this->monto_iva->DefaultValue;
        $row['monto_total'] = $this->monto_total->DefaultValue;
        $row['monto_pagar'] = $this->monto_pagar->DefaultValue;
        $row['ret_iva'] = $this->ret_iva->DefaultValue;
        $row['ref_iva'] = $this->ref_iva->DefaultValue;
        $row['ret_islr'] = $this->ret_islr->DefaultValue;
        $row['ref_islr'] = $this->ref_islr->DefaultValue;
        $row['ret_municipal'] = $this->ret_municipal->DefaultValue;
        $row['ref_municipal'] = $this->ref_municipal->DefaultValue;
        $row['fecha_registro'] = $this->fecha_registro->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['comprobante'] = $this->comprobante->DefaultValue;
        $row['tipo_iva'] = $this->tipo_iva->DefaultValue;
        $row['tipo_islr'] = $this->tipo_islr->DefaultValue;
        $row['sustraendo'] = $this->sustraendo->DefaultValue;
        $row['tipo_municipal'] = $this->tipo_municipal->DefaultValue;
        $row['anulado'] = $this->anulado->DefaultValue;
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

        // proveedor
        $this->proveedor->RowCssClass = "row";

        // tipo_documento
        $this->tipo_documento->RowCssClass = "row";

        // doc_afectado
        $this->doc_afectado->RowCssClass = "row";

        // documento
        $this->documento->RowCssClass = "row";

        // nro_control
        $this->nro_control->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // descripcion
        $this->descripcion->RowCssClass = "row";

        // aplica_retencion
        $this->aplica_retencion->RowCssClass = "row";

        // monto_exento
        $this->monto_exento->RowCssClass = "row";

        // monto_gravado
        $this->monto_gravado->RowCssClass = "row";

        // alicuota
        $this->alicuota->RowCssClass = "row";

        // monto_iva
        $this->monto_iva->RowCssClass = "row";

        // monto_total
        $this->monto_total->RowCssClass = "row";

        // monto_pagar
        $this->monto_pagar->RowCssClass = "row";

        // ret_iva
        $this->ret_iva->RowCssClass = "row";

        // ref_iva
        $this->ref_iva->RowCssClass = "row";

        // ret_islr
        $this->ret_islr->RowCssClass = "row";

        // ref_islr
        $this->ref_islr->RowCssClass = "row";

        // ret_municipal
        $this->ret_municipal->RowCssClass = "row";

        // ref_municipal
        $this->ref_municipal->RowCssClass = "row";

        // fecha_registro
        $this->fecha_registro->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // comprobante
        $this->comprobante->RowCssClass = "row";

        // tipo_iva
        $this->tipo_iva->RowCssClass = "row";

        // tipo_islr
        $this->tipo_islr->RowCssClass = "row";

        // sustraendo
        $this->sustraendo->RowCssClass = "row";

        // tipo_municipal
        $this->tipo_municipal->RowCssClass = "row";

        // anulado
        $this->anulado->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // proveedor
            $curVal = strval($this->proveedor->CurrentValue);
            if ($curVal != "") {
                $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
                if ($this->proveedor->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->proveedor->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->proveedor->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                        $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                    } else {
                        $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
                    }
                }
            } else {
                $this->proveedor->ViewValue = null;
            }

            // tipo_documento
            if (strval($this->tipo_documento->CurrentValue) != "") {
                $this->tipo_documento->ViewValue = $this->tipo_documento->optionCaption($this->tipo_documento->CurrentValue);
            } else {
                $this->tipo_documento->ViewValue = null;
            }

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

            // documento
            $this->documento->ViewValue = $this->documento->CurrentValue;

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

            // aplica_retencion
            if (strval($this->aplica_retencion->CurrentValue) != "") {
                $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
            } else {
                $this->aplica_retencion->ViewValue = null;
            }

            // monto_exento
            $this->monto_exento->ViewValue = $this->monto_exento->CurrentValue;
            $this->monto_exento->ViewValue = FormatNumber($this->monto_exento->ViewValue, $this->monto_exento->formatPattern());

            // monto_gravado
            $this->monto_gravado->ViewValue = $this->monto_gravado->CurrentValue;
            $this->monto_gravado->ViewValue = FormatNumber($this->monto_gravado->ViewValue, $this->monto_gravado->formatPattern());

            // alicuota
            $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
            $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->formatPattern());

            // monto_iva
            $this->monto_iva->ViewValue = $this->monto_iva->CurrentValue;
            $this->monto_iva->ViewValue = FormatNumber($this->monto_iva->ViewValue, $this->monto_iva->formatPattern());

            // monto_total
            $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
            $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, $this->monto_total->formatPattern());

            // monto_pagar
            $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
            $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, $this->monto_pagar->formatPattern());
            $this->monto_pagar->CssClass = "fw-bold";

            // ret_iva
            $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
            $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, $this->ret_iva->formatPattern());
            $this->ret_iva->CssClass = "fw-bold";

            // ref_iva
            $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;

            // ret_islr
            $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
            $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, $this->ret_islr->formatPattern());
            $this->ret_islr->CssClass = "fw-bold";

            // ref_islr
            $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;

            // ret_municipal
            $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
            $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, $this->ret_municipal->formatPattern());
            $this->ret_municipal->CssClass = "fw-bold";

            // ref_municipal
            $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;

            // fecha_registro
            $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
            $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, $this->fecha_registro->formatPattern());

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

            // comprobante
            $curVal = strval($this->comprobante->CurrentValue);
            if ($curVal != "") {
                $this->comprobante->ViewValue = $this->comprobante->lookupCacheOption($curVal);
                if ($this->comprobante->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->comprobante->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->comprobante->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->comprobante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->comprobante->Lookup->renderViewRow($rswrk[0]);
                        $this->comprobante->ViewValue = $this->comprobante->displayValue($arwrk);
                    } else {
                        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
                    }
                }
            } else {
                $this->comprobante->ViewValue = null;
            }

            // tipo_iva
            $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;

            // tipo_islr
            $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;

            // sustraendo
            $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
            $this->sustraendo->ViewValue = FormatNumber($this->sustraendo->ViewValue, $this->sustraendo->formatPattern());

            // tipo_municipal
            $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;

            // anulado
            if (strval($this->anulado->CurrentValue) != "") {
                $this->anulado->ViewValue = $this->anulado->optionCaption($this->anulado->CurrentValue);
            } else {
                $this->anulado->ViewValue = null;
            }

            // id
            $this->id->HrefValue = "";

            // proveedor
            $this->proveedor->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";

            // nro_control
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->HrefValue = "";

            // monto_exento
            $this->monto_exento->HrefValue = "";

            // monto_gravado
            $this->monto_gravado->HrefValue = "";

            // alicuota
            $this->alicuota->HrefValue = "";

            // ref_iva
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_iva->HrefValue = $this->ref_iva->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_iva->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_iva->HrefValue = FullUrl($this->ref_iva->HrefValue, "href");
                }
            } else {
                $this->ref_iva->HrefValue = "";
            }

            // ref_islr
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_islr->HrefValue = $this->ref_islr->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_islr->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_islr->HrefValue = FullUrl($this->ref_islr->HrefValue, "href");
                }
            } else {
                $this->ref_islr->HrefValue = "";
            }

            // ref_municipal
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_municipal->HrefValue = $this->ref_municipal->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_municipal->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_municipal->HrefValue = FullUrl($this->ref_municipal->HrefValue, "href");
                }
            } else {
                $this->ref_municipal->HrefValue = "";
            }

            // fecha_registro
            $this->fecha_registro->HrefValue = "";

            // anulado
            $this->anulado->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // id
            $this->id->setupEditAttributes();
            $this->id->EditValue = $this->id->CurrentValue;

            // proveedor
            $curVal = trim(strval($this->proveedor->CurrentValue));
            if ($curVal != "") {
                $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
            } else {
                $this->proveedor->ViewValue = $this->proveedor->Lookup !== null && is_array($this->proveedor->lookupOptions()) && count($this->proveedor->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->proveedor->ViewValue !== null) { // Load from cache
                $this->proveedor->EditValue = array_values($this->proveedor->lookupOptions());
                if ($this->proveedor->ViewValue == "") {
                    $this->proveedor->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->proveedor->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->proveedor->CurrentValue, $this->proveedor->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $sqlWrk = $this->proveedor->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->proveedor->EditValue = $arwrk;
            }
            $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

            // tipo_documento
            $this->tipo_documento->setupEditAttributes();
            $this->tipo_documento->EditValue = $this->tipo_documento->options(true);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // doc_afectado
            $this->doc_afectado->setupEditAttributes();
            if (!$this->doc_afectado->Raw) {
                $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->CurrentValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

            // documento
            $this->documento->setupEditAttributes();
            if (!$this->documento->Raw) {
                $this->documento->CurrentValue = HtmlDecode($this->documento->CurrentValue);
            }
            $this->documento->EditValue = HtmlEncode($this->documento->CurrentValue);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // nro_control
            $this->nro_control->setupEditAttributes();
            if (!$this->nro_control->Raw) {
                $this->nro_control->CurrentValue = HtmlDecode($this->nro_control->CurrentValue);
            }
            $this->nro_control->EditValue = HtmlEncode($this->nro_control->CurrentValue);
            $this->nro_control->PlaceHolder = RemoveHtml($this->nro_control->caption());

            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // descripcion
            $this->descripcion->setupEditAttributes();
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->CurrentValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // aplica_retencion
            $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
            $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

            // monto_exento
            $this->monto_exento->setupEditAttributes();
            $this->monto_exento->EditValue = $this->monto_exento->CurrentValue;
            $this->monto_exento->PlaceHolder = RemoveHtml($this->monto_exento->caption());
            if (strval($this->monto_exento->EditValue) != "" && is_numeric($this->monto_exento->EditValue)) {
                $this->monto_exento->EditValue = FormatNumber($this->monto_exento->EditValue, null);
            }

            // monto_gravado
            $this->monto_gravado->setupEditAttributes();
            $this->monto_gravado->EditValue = $this->monto_gravado->CurrentValue;
            $this->monto_gravado->PlaceHolder = RemoveHtml($this->monto_gravado->caption());
            if (strval($this->monto_gravado->EditValue) != "" && is_numeric($this->monto_gravado->EditValue)) {
                $this->monto_gravado->EditValue = FormatNumber($this->monto_gravado->EditValue, null);
            }

            // alicuota
            $this->alicuota->setupEditAttributes();
            $this->alicuota->EditValue = $this->alicuota->CurrentValue;
            $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
            if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
                $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, null);
            }

            // ref_iva
            $this->ref_iva->setupEditAttributes();
            if (!$this->ref_iva->Raw) {
                $this->ref_iva->CurrentValue = HtmlDecode($this->ref_iva->CurrentValue);
            }
            $this->ref_iva->EditValue = HtmlEncode($this->ref_iva->CurrentValue);
            $this->ref_iva->PlaceHolder = RemoveHtml($this->ref_iva->caption());

            // ref_islr
            $this->ref_islr->setupEditAttributes();
            if (!$this->ref_islr->Raw) {
                $this->ref_islr->CurrentValue = HtmlDecode($this->ref_islr->CurrentValue);
            }
            $this->ref_islr->EditValue = HtmlEncode($this->ref_islr->CurrentValue);
            $this->ref_islr->PlaceHolder = RemoveHtml($this->ref_islr->caption());

            // ref_municipal
            $this->ref_municipal->setupEditAttributes();
            if (!$this->ref_municipal->Raw) {
                $this->ref_municipal->CurrentValue = HtmlDecode($this->ref_municipal->CurrentValue);
            }
            $this->ref_municipal->EditValue = HtmlEncode($this->ref_municipal->CurrentValue);
            $this->ref_municipal->PlaceHolder = RemoveHtml($this->ref_municipal->caption());

            // fecha_registro
            $this->fecha_registro->setupEditAttributes();
            $this->fecha_registro->EditValue = HtmlEncode(FormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern()));
            $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

            // anulado
            $this->anulado->EditValue = $this->anulado->options(false);
            $this->anulado->PlaceHolder = RemoveHtml($this->anulado->caption());

            // Edit refer script

            // id
            $this->id->HrefValue = "";

            // proveedor
            $this->proveedor->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";

            // nro_control
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // descripcion
            $this->descripcion->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->HrefValue = "";

            // monto_exento
            $this->monto_exento->HrefValue = "";

            // monto_gravado
            $this->monto_gravado->HrefValue = "";

            // alicuota
            $this->alicuota->HrefValue = "";

            // ref_iva
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_iva->HrefValue = $this->ref_iva->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_iva->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_iva->HrefValue = FullUrl($this->ref_iva->HrefValue, "href");
                }
            } else {
                $this->ref_iva->HrefValue = "";
            }

            // ref_islr
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_islr->HrefValue = $this->ref_islr->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_islr->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_islr->HrefValue = FullUrl($this->ref_islr->HrefValue, "href");
                }
            } else {
                $this->ref_islr->HrefValue = "";
            }

            // ref_municipal
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_municipal->HrefValue = $this->ref_municipal->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_municipal->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_municipal->HrefValue = FullUrl($this->ref_municipal->HrefValue, "href");
                }
            } else {
                $this->ref_municipal->HrefValue = "";
            }

            // fecha_registro
            $this->fecha_registro->HrefValue = "";

            // anulado
            $this->anulado->HrefValue = "";
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
            if ($this->proveedor->Visible && $this->proveedor->Required) {
                if (!$this->proveedor->IsDetailKey && EmptyValue($this->proveedor->FormValue)) {
                    $this->proveedor->addErrorMessage(str_replace("%s", $this->proveedor->caption(), $this->proveedor->RequiredErrorMessage));
                }
            }
            if ($this->tipo_documento->Visible && $this->tipo_documento->Required) {
                if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                    $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
                }
            }
            if ($this->doc_afectado->Visible && $this->doc_afectado->Required) {
                if (!$this->doc_afectado->IsDetailKey && EmptyValue($this->doc_afectado->FormValue)) {
                    $this->doc_afectado->addErrorMessage(str_replace("%s", $this->doc_afectado->caption(), $this->doc_afectado->RequiredErrorMessage));
                }
            }
            if ($this->documento->Visible && $this->documento->Required) {
                if (!$this->documento->IsDetailKey && EmptyValue($this->documento->FormValue)) {
                    $this->documento->addErrorMessage(str_replace("%s", $this->documento->caption(), $this->documento->RequiredErrorMessage));
                }
            }
            if ($this->nro_control->Visible && $this->nro_control->Required) {
                if (!$this->nro_control->IsDetailKey && EmptyValue($this->nro_control->FormValue)) {
                    $this->nro_control->addErrorMessage(str_replace("%s", $this->nro_control->caption(), $this->nro_control->RequiredErrorMessage));
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
            if ($this->descripcion->Visible && $this->descripcion->Required) {
                if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                    $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
                }
            }
            if ($this->aplica_retencion->Visible && $this->aplica_retencion->Required) {
                if ($this->aplica_retencion->FormValue == "") {
                    $this->aplica_retencion->addErrorMessage(str_replace("%s", $this->aplica_retencion->caption(), $this->aplica_retencion->RequiredErrorMessage));
                }
            }
            if ($this->monto_exento->Visible && $this->monto_exento->Required) {
                if (!$this->monto_exento->IsDetailKey && EmptyValue($this->monto_exento->FormValue)) {
                    $this->monto_exento->addErrorMessage(str_replace("%s", $this->monto_exento->caption(), $this->monto_exento->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_exento->FormValue)) {
                $this->monto_exento->addErrorMessage($this->monto_exento->getErrorMessage(false));
            }
            if ($this->monto_gravado->Visible && $this->monto_gravado->Required) {
                if (!$this->monto_gravado->IsDetailKey && EmptyValue($this->monto_gravado->FormValue)) {
                    $this->monto_gravado->addErrorMessage(str_replace("%s", $this->monto_gravado->caption(), $this->monto_gravado->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->monto_gravado->FormValue)) {
                $this->monto_gravado->addErrorMessage($this->monto_gravado->getErrorMessage(false));
            }
            if ($this->alicuota->Visible && $this->alicuota->Required) {
                if (!$this->alicuota->IsDetailKey && EmptyValue($this->alicuota->FormValue)) {
                    $this->alicuota->addErrorMessage(str_replace("%s", $this->alicuota->caption(), $this->alicuota->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->alicuota->FormValue)) {
                $this->alicuota->addErrorMessage($this->alicuota->getErrorMessage(false));
            }
            if ($this->ref_iva->Visible && $this->ref_iva->Required) {
                if (!$this->ref_iva->IsDetailKey && EmptyValue($this->ref_iva->FormValue)) {
                    $this->ref_iva->addErrorMessage(str_replace("%s", $this->ref_iva->caption(), $this->ref_iva->RequiredErrorMessage));
                }
            }
            if ($this->ref_islr->Visible && $this->ref_islr->Required) {
                if (!$this->ref_islr->IsDetailKey && EmptyValue($this->ref_islr->FormValue)) {
                    $this->ref_islr->addErrorMessage(str_replace("%s", $this->ref_islr->caption(), $this->ref_islr->RequiredErrorMessage));
                }
            }
            if ($this->ref_municipal->Visible && $this->ref_municipal->Required) {
                if (!$this->ref_municipal->IsDetailKey && EmptyValue($this->ref_municipal->FormValue)) {
                    $this->ref_municipal->addErrorMessage(str_replace("%s", $this->ref_municipal->caption(), $this->ref_municipal->RequiredErrorMessage));
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
            if ($this->anulado->Visible && $this->anulado->Required) {
                if ($this->anulado->FormValue == "") {
                    $this->anulado->addErrorMessage(str_replace("%s", $this->anulado->caption(), $this->anulado->RequiredErrorMessage));
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

        // proveedor
        $this->proveedor->setDbValueDef($rsnew, $this->proveedor->CurrentValue, $this->proveedor->ReadOnly);

        // tipo_documento
        $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, $this->tipo_documento->ReadOnly);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, $this->doc_afectado->ReadOnly);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, $this->documento->ReadOnly);

        // nro_control
        $this->nro_control->setDbValueDef($rsnew, $this->nro_control->CurrentValue, $this->nro_control->ReadOnly);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()), $this->fecha->ReadOnly);

        // descripcion
        $this->descripcion->setDbValueDef($rsnew, $this->descripcion->CurrentValue, $this->descripcion->ReadOnly);

        // aplica_retencion
        $this->aplica_retencion->setDbValueDef($rsnew, $this->aplica_retencion->CurrentValue, $this->aplica_retencion->ReadOnly);

        // monto_exento
        $this->monto_exento->setDbValueDef($rsnew, $this->monto_exento->CurrentValue, $this->monto_exento->ReadOnly);

        // monto_gravado
        $this->monto_gravado->setDbValueDef($rsnew, $this->monto_gravado->CurrentValue, $this->monto_gravado->ReadOnly);

        // alicuota
        $this->alicuota->setDbValueDef($rsnew, $this->alicuota->CurrentValue, $this->alicuota->ReadOnly);

        // ref_iva
        $this->ref_iva->setDbValueDef($rsnew, $this->ref_iva->CurrentValue, $this->ref_iva->ReadOnly);

        // ref_islr
        $this->ref_islr->setDbValueDef($rsnew, $this->ref_islr->CurrentValue, $this->ref_islr->ReadOnly);

        // ref_municipal
        $this->ref_municipal->setDbValueDef($rsnew, $this->ref_municipal->CurrentValue, $this->ref_municipal->ReadOnly);

        // fecha_registro
        $this->fecha_registro->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern()), $this->fecha_registro->ReadOnly);

        // anulado
        $this->anulado->setDbValueDef($rsnew, $this->anulado->CurrentValue, $this->anulado->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['proveedor'])) { // proveedor
            $this->proveedor->CurrentValue = $row['proveedor'];
        }
        if (isset($row['tipo_documento'])) { // tipo_documento
            $this->tipo_documento->CurrentValue = $row['tipo_documento'];
        }
        if (isset($row['doc_afectado'])) { // doc_afectado
            $this->doc_afectado->CurrentValue = $row['doc_afectado'];
        }
        if (isset($row['documento'])) { // documento
            $this->documento->CurrentValue = $row['documento'];
        }
        if (isset($row['nro_control'])) { // nro_control
            $this->nro_control->CurrentValue = $row['nro_control'];
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->CurrentValue = $row['fecha'];
        }
        if (isset($row['descripcion'])) { // descripcion
            $this->descripcion->CurrentValue = $row['descripcion'];
        }
        if (isset($row['aplica_retencion'])) { // aplica_retencion
            $this->aplica_retencion->CurrentValue = $row['aplica_retencion'];
        }
        if (isset($row['monto_exento'])) { // monto_exento
            $this->monto_exento->CurrentValue = $row['monto_exento'];
        }
        if (isset($row['monto_gravado'])) { // monto_gravado
            $this->monto_gravado->CurrentValue = $row['monto_gravado'];
        }
        if (isset($row['alicuota'])) { // alicuota
            $this->alicuota->CurrentValue = $row['alicuota'];
        }
        if (isset($row['ref_iva'])) { // ref_iva
            $this->ref_iva->CurrentValue = $row['ref_iva'];
        }
        if (isset($row['ref_islr'])) { // ref_islr
            $this->ref_islr->CurrentValue = $row['ref_islr'];
        }
        if (isset($row['ref_municipal'])) { // ref_municipal
            $this->ref_municipal->CurrentValue = $row['ref_municipal'];
        }
        if (isset($row['fecha_registro'])) { // fecha_registro
            $this->fecha_registro->CurrentValue = $row['fecha_registro'];
        }
        if (isset($row['anulado'])) { // anulado
            $this->anulado->CurrentValue = $row['anulado'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CompraList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_proveedor":
                    break;
                case "x_tipo_documento":
                    break;
                case "x_aplica_retencion":
                    break;
                case "x__username":
                    break;
                case "x_comprobante":
                    break;
                case "x_anulado":
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
