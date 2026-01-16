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
class ViewOutTdcnetEdit extends ViewOutTdcnet
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ViewOutTdcnetEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ViewOutTdcnetEdit";

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
        $this->tipo_documento->Visible = false;
        $this->_username->Visible = false;
        $this->fecha->setVisibility();
        $this->cliente->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->Visible = false;
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->Visible = false;
        $this->lista_pedido->Visible = false;
        $this->nota->setVisibility();
        $this->unidades->Visible = false;
        $this->estatus->setVisibility();
        $this->id_documento_padre->Visible = false;
        $this->moneda->Visible = false;
        $this->asesor->Visible = false;
        $this->documento->Visible = false;
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->dias_credito->Visible = false;
        $this->entregado->Visible = false;
        $this->fecha_entrega->Visible = false;
        $this->pagado->Visible = false;
        $this->bultos->setVisibility();
        $this->fecha_bultos->Visible = false;
        $this->user_bultos->Visible = false;
        $this->fecha_despacho->Visible = false;
        $this->user_despacho->Visible = false;
        $this->consignacion->setVisibility();
        $this->descuento->Visible = false;
        $this->descuento2->Visible = false;
        $this->monto_sin_descuento->Visible = false;
        $this->factura->setVisibility();
        $this->ci_rif->setVisibility();
        $this->nombre->setVisibility();
        $this->direccion->setVisibility();
        $this->telefono->setVisibility();
        $this->_email->Visible = false;
        $this->activo->Visible = false;
        $this->comprobante->Visible = false;
        $this->doc_afectado->Visible = false;
        $this->nro_despacho->Visible = false;
        $this->cerrado->Visible = false;
        $this->asesor_asignado->Visible = false;
        $this->tasa_indexada->Visible = false;
        $this->id_documento_padre_nd->Visible = false;
        $this->archivo_pedido->Visible = false;
        $this->checker->Visible = false;
        $this->checker_date->Visible = false;
        $this->packer->Visible = false;
        $this->packer_date->Visible = false;
        $this->fotos->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'view_out_tdcnet';
        $this->TableName = 'view_out_tdcnet';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (view_out_tdcnet)
        if (!isset($GLOBALS["view_out_tdcnet"]) || $GLOBALS["view_out_tdcnet"]::class == PROJECT_NAMESPACE . "view_out_tdcnet") {
            $GLOBALS["view_out_tdcnet"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'view_out_tdcnet');
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
                        $result["view"] = SameString($pageName, "ViewOutTdcnetView"); // If View page, no primary button
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
        $this->cliente->Required = false;
        $this->nro_documento->Required = false;

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
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->estatus);
        $this->setupLookupOptions($this->id_documento_padre);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->asesor);
        $this->setupLookupOptions($this->documento);
        $this->setupLookupOptions($this->dias_credito);
        $this->setupLookupOptions($this->entregado);
        $this->setupLookupOptions($this->pagado);
        $this->setupLookupOptions($this->user_bultos);
        $this->setupLookupOptions($this->user_despacho);
        $this->setupLookupOptions($this->consignacion);
        $this->setupLookupOptions($this->factura);
        $this->setupLookupOptions($this->activo);
        $this->setupLookupOptions($this->cerrado);
        $this->setupLookupOptions($this->asesor_asignado);

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
                        $this->terminate("ViewOutTdcnetList"); // Return to list page
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
                        $this->terminate("ViewOutTdcnetList"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("ViewOutTdcnetList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                $returnUrl = $this->getViewUrl();
                if (GetPageName($returnUrl) == "ViewOutTdcnetList") {
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
                        if (GetPageName($returnUrl) != "ViewOutTdcnetList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "ViewOutTdcnetList"; // Return list page content
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

        // Check field name 'fecha' first before field var 'x_fecha'
        $val = $CurrentForm->hasValue("fecha") ? $CurrentForm->getValue("fecha") : $CurrentForm->getValue("x_fecha");
        if (!$this->fecha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha->Visible = false; // Disable update for API request
            } else {
                $this->fecha->setFormValue($val);
            }
            $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        }

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
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

        // Check field name 'nota' first before field var 'x_nota'
        $val = $CurrentForm->hasValue("nota") ? $CurrentForm->getValue("nota") : $CurrentForm->getValue("x_nota");
        if (!$this->nota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nota->Visible = false; // Disable update for API request
            } else {
                $this->nota->setFormValue($val);
            }
        }

        // Check field name 'estatus' first before field var 'x_estatus'
        $val = $CurrentForm->hasValue("estatus") ? $CurrentForm->getValue("estatus") : $CurrentForm->getValue("x_estatus");
        if (!$this->estatus->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estatus->Visible = false; // Disable update for API request
            } else {
                $this->estatus->setFormValue($val);
            }
        }

        // Check field name 'bultos' first before field var 'x_bultos'
        $val = $CurrentForm->hasValue("bultos") ? $CurrentForm->getValue("bultos") : $CurrentForm->getValue("x_bultos");
        if (!$this->bultos->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bultos->Visible = false; // Disable update for API request
            } else {
                $this->bultos->setFormValue($val, true, $validate);
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

        // Check field name 'factura' first before field var 'x_factura'
        $val = $CurrentForm->hasValue("factura") ? $CurrentForm->getValue("factura") : $CurrentForm->getValue("x_factura");
        if (!$this->factura->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->factura->Visible = false; // Disable update for API request
            } else {
                $this->factura->setFormValue($val);
            }
        }

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

        // Check field name 'direccion' first before field var 'x_direccion'
        $val = $CurrentForm->hasValue("direccion") ? $CurrentForm->getValue("direccion") : $CurrentForm->getValue("x_direccion");
        if (!$this->direccion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->direccion->Visible = false; // Disable update for API request
            } else {
                $this->direccion->setFormValue($val);
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
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->estatus->CurrentValue = $this->estatus->FormValue;
        $this->bultos->CurrentValue = $this->bultos->FormValue;
        $this->consignacion->CurrentValue = $this->consignacion->FormValue;
        $this->factura->CurrentValue = $this->factura->FormValue;
        $this->ci_rif->CurrentValue = $this->ci_rif->FormValue;
        $this->nombre->CurrentValue = $this->nombre->FormValue;
        $this->direccion->CurrentValue = $this->direccion->FormValue;
        $this->telefono->CurrentValue = $this->telefono->FormValue;
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->_username->setDbValue($row['username']);
        $this->fecha->setDbValue($row['fecha']);
        $this->cliente->setDbValue($row['cliente']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->nota->setDbValue($row['nota']);
        $this->unidades->setDbValue($row['unidades']);
        $this->estatus->setDbValue($row['estatus']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->moneda->setDbValue($row['moneda']);
        $this->asesor->setDbValue($row['asesor']);
        $this->documento->setDbValue($row['documento']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->pagado->setDbValue($row['pagado']);
        $this->bultos->setDbValue($row['bultos']);
        $this->fecha_bultos->setDbValue($row['fecha_bultos']);
        $this->user_bultos->setDbValue($row['user_bultos']);
        $this->fecha_despacho->setDbValue($row['fecha_despacho']);
        $this->user_despacho->setDbValue($row['user_despacho']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->descuento->setDbValue($row['descuento']);
        $this->descuento2->setDbValue($row['descuento2']);
        $this->monto_sin_descuento->setDbValue($row['monto_sin_descuento']);
        $this->factura->setDbValue($row['factura']);
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono->setDbValue($row['telefono']);
        $this->_email->setDbValue($row['email']);
        $this->activo->setDbValue($row['activo']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->nro_despacho->setDbValue($row['nro_despacho']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->asesor_asignado->setDbValue($row['asesor_asignado']);
        $this->tasa_indexada->setDbValue($row['tasa_indexada']);
        $this->id_documento_padre_nd->setDbValue($row['id_documento_padre_nd']);
        $this->archivo_pedido->Upload->DbValue = $row['archivo_pedido'];
        $this->archivo_pedido->setDbValue($this->archivo_pedido->Upload->DbValue);
        $this->checker->setDbValue($row['checker']);
        $this->checker_date->setDbValue($row['checker_date']);
        $this->packer->setDbValue($row['packer']);
        $this->packer_date->setDbValue($row['packer_date']);
        $this->fotos->Upload->DbValue = $row['fotos'];
        $this->fotos->setDbValue($this->fotos->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['cliente'] = $this->cliente->DefaultValue;
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['monto_total'] = $this->monto_total->DefaultValue;
        $row['alicuota_iva'] = $this->alicuota_iva->DefaultValue;
        $row['iva'] = $this->iva->DefaultValue;
        $row['total'] = $this->total->DefaultValue;
        $row['lista_pedido'] = $this->lista_pedido->DefaultValue;
        $row['nota'] = $this->nota->DefaultValue;
        $row['unidades'] = $this->unidades->DefaultValue;
        $row['estatus'] = $this->estatus->DefaultValue;
        $row['id_documento_padre'] = $this->id_documento_padre->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['asesor'] = $this->asesor->DefaultValue;
        $row['documento'] = $this->documento->DefaultValue;
        $row['tasa_dia'] = $this->tasa_dia->DefaultValue;
        $row['monto_usd'] = $this->monto_usd->DefaultValue;
        $row['dias_credito'] = $this->dias_credito->DefaultValue;
        $row['entregado'] = $this->entregado->DefaultValue;
        $row['fecha_entrega'] = $this->fecha_entrega->DefaultValue;
        $row['pagado'] = $this->pagado->DefaultValue;
        $row['bultos'] = $this->bultos->DefaultValue;
        $row['fecha_bultos'] = $this->fecha_bultos->DefaultValue;
        $row['user_bultos'] = $this->user_bultos->DefaultValue;
        $row['fecha_despacho'] = $this->fecha_despacho->DefaultValue;
        $row['user_despacho'] = $this->user_despacho->DefaultValue;
        $row['consignacion'] = $this->consignacion->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['descuento2'] = $this->descuento2->DefaultValue;
        $row['monto_sin_descuento'] = $this->monto_sin_descuento->DefaultValue;
        $row['factura'] = $this->factura->DefaultValue;
        $row['ci_rif'] = $this->ci_rif->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['direccion'] = $this->direccion->DefaultValue;
        $row['telefono'] = $this->telefono->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['comprobante'] = $this->comprobante->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['nro_despacho'] = $this->nro_despacho->DefaultValue;
        $row['cerrado'] = $this->cerrado->DefaultValue;
        $row['asesor_asignado'] = $this->asesor_asignado->DefaultValue;
        $row['tasa_indexada'] = $this->tasa_indexada->DefaultValue;
        $row['id_documento_padre_nd'] = $this->id_documento_padre_nd->DefaultValue;
        $row['archivo_pedido'] = $this->archivo_pedido->DefaultValue;
        $row['checker'] = $this->checker->DefaultValue;
        $row['checker_date'] = $this->checker_date->DefaultValue;
        $row['packer'] = $this->packer->DefaultValue;
        $row['packer_date'] = $this->packer_date->DefaultValue;
        $row['fotos'] = $this->fotos->DefaultValue;
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

        // tipo_documento
        $this->tipo_documento->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // cliente
        $this->cliente->RowCssClass = "row";

        // nro_documento
        $this->nro_documento->RowCssClass = "row";

        // nro_control
        $this->nro_control->RowCssClass = "row";

        // monto_total
        $this->monto_total->RowCssClass = "row";

        // alicuota_iva
        $this->alicuota_iva->RowCssClass = "row";

        // iva
        $this->iva->RowCssClass = "row";

        // total
        $this->total->RowCssClass = "row";

        // lista_pedido
        $this->lista_pedido->RowCssClass = "row";

        // nota
        $this->nota->RowCssClass = "row";

        // unidades
        $this->unidades->RowCssClass = "row";

        // estatus
        $this->estatus->RowCssClass = "row";

        // id_documento_padre
        $this->id_documento_padre->RowCssClass = "row";

        // moneda
        $this->moneda->RowCssClass = "row";

        // asesor
        $this->asesor->RowCssClass = "row";

        // documento
        $this->documento->RowCssClass = "row";

        // tasa_dia
        $this->tasa_dia->RowCssClass = "row";

        // monto_usd
        $this->monto_usd->RowCssClass = "row";

        // dias_credito
        $this->dias_credito->RowCssClass = "row";

        // entregado
        $this->entregado->RowCssClass = "row";

        // fecha_entrega
        $this->fecha_entrega->RowCssClass = "row";

        // pagado
        $this->pagado->RowCssClass = "row";

        // bultos
        $this->bultos->RowCssClass = "row";

        // fecha_bultos
        $this->fecha_bultos->RowCssClass = "row";

        // user_bultos
        $this->user_bultos->RowCssClass = "row";

        // fecha_despacho
        $this->fecha_despacho->RowCssClass = "row";

        // user_despacho
        $this->user_despacho->RowCssClass = "row";

        // consignacion
        $this->consignacion->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

        // descuento2
        $this->descuento2->RowCssClass = "row";

        // monto_sin_descuento
        $this->monto_sin_descuento->RowCssClass = "row";

        // factura
        $this->factura->RowCssClass = "row";

        // ci_rif
        $this->ci_rif->RowCssClass = "row";

        // nombre
        $this->nombre->RowCssClass = "row";

        // direccion
        $this->direccion->RowCssClass = "row";

        // telefono
        $this->telefono->RowCssClass = "row";

        // email
        $this->_email->RowCssClass = "row";

        // activo
        $this->activo->RowCssClass = "row";

        // comprobante
        $this->comprobante->RowCssClass = "row";

        // doc_afectado
        $this->doc_afectado->RowCssClass = "row";

        // nro_despacho
        $this->nro_despacho->RowCssClass = "row";

        // cerrado
        $this->cerrado->RowCssClass = "row";

        // asesor_asignado
        $this->asesor_asignado->RowCssClass = "row";

        // tasa_indexada
        $this->tasa_indexada->RowCssClass = "row";

        // id_documento_padre_nd
        $this->id_documento_padre_nd->RowCssClass = "row";

        // archivo_pedido
        $this->archivo_pedido->RowCssClass = "row";

        // checker
        $this->checker->RowCssClass = "row";

        // checker_date
        $this->checker_date->RowCssClass = "row";

        // packer
        $this->packer->RowCssClass = "row";

        // packer_date
        $this->packer_date->RowCssClass = "row";

        // fotos
        $this->fotos->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

            // username
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

            // cliente
            $curVal = strval($this->cliente->CurrentValue);
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->ViewValue = $this->cliente->CurrentValue;
                    }
                }
            } else {
                $this->cliente->ViewValue = null;
            }

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

            // monto_total
            $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
            $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, $this->monto_total->formatPattern());

            // alicuota_iva
            $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
            $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, $this->alicuota_iva->formatPattern());

            // iva
            $this->iva->ViewValue = $this->iva->CurrentValue;
            $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, $this->iva->formatPattern());

            // total
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->formatPattern());

            // lista_pedido
            $curVal = strval($this->lista_pedido->CurrentValue);
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
                if ($this->lista_pedido->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->lista_pedido->getSelectFilter($this); // PHP
                    $sqlWrk = $this->lista_pedido->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->lista_pedido->Lookup->renderViewRow($rswrk[0]);
                        $this->lista_pedido->ViewValue = $this->lista_pedido->displayValue($arwrk);
                    } else {
                        $this->lista_pedido->ViewValue = $this->lista_pedido->CurrentValue;
                    }
                }
            } else {
                $this->lista_pedido->ViewValue = null;
            }

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;

            // unidades
            $this->unidades->ViewValue = $this->unidades->CurrentValue;

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
            }

            // id_documento_padre
            $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
            $curVal = strval($this->id_documento_padre->CurrentValue);
            if ($curVal != "") {
                $this->id_documento_padre->ViewValue = $this->id_documento_padre->lookupCacheOption($curVal);
                if ($this->id_documento_padre->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->id_documento_padre->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->id_documento_padre->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->id_documento_padre->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->id_documento_padre->Lookup->renderViewRow($rswrk[0]);
                        $this->id_documento_padre->ViewValue = $this->id_documento_padre->displayValue($arwrk);
                    } else {
                        $this->id_documento_padre->ViewValue = FormatNumber($this->id_documento_padre->CurrentValue, $this->id_documento_padre->formatPattern());
                    }
                }
            } else {
                $this->id_documento_padre->ViewValue = null;
            }

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

            // asesor
            $curVal = strval($this->asesor->CurrentValue);
            if ($curVal != "") {
                $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
                if ($this->asesor->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->asesor->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->asesor->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->asesor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->asesor->Lookup->renderViewRow($rswrk[0]);
                        $this->asesor->ViewValue = $this->asesor->displayValue($arwrk);
                    } else {
                        $this->asesor->ViewValue = $this->asesor->CurrentValue;
                    }
                }
            } else {
                $this->asesor->ViewValue = null;
            }

            // documento
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->ViewValue = null;
            }

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

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
                        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
                    }
                }
            } else {
                $this->dias_credito->ViewValue = null;
            }

            // entregado
            if (strval($this->entregado->CurrentValue) != "") {
                $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
            } else {
                $this->entregado->ViewValue = null;
            }
            $this->entregado->CssClass = "fw-bold fst-italic";

            // fecha_entrega
            $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
            $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, $this->fecha_entrega->formatPattern());

            // pagado
            if (strval($this->pagado->CurrentValue) != "") {
                $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
            } else {
                $this->pagado->ViewValue = null;
            }
            $this->pagado->CssClass = "fw-bold fst-italic";

            // bultos
            $this->bultos->ViewValue = $this->bultos->CurrentValue;

            // fecha_bultos
            $this->fecha_bultos->ViewValue = $this->fecha_bultos->CurrentValue;
            $this->fecha_bultos->ViewValue = FormatDateTime($this->fecha_bultos->ViewValue, $this->fecha_bultos->formatPattern());

            // user_bultos
            $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
            $curVal = strval($this->user_bultos->CurrentValue);
            if ($curVal != "") {
                $this->user_bultos->ViewValue = $this->user_bultos->lookupCacheOption($curVal);
                if ($this->user_bultos->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->user_bultos->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->user_bultos->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->user_bultos->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->user_bultos->Lookup->renderViewRow($rswrk[0]);
                        $this->user_bultos->ViewValue = $this->user_bultos->displayValue($arwrk);
                    } else {
                        $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
                    }
                }
            } else {
                $this->user_bultos->ViewValue = null;
            }

            // fecha_despacho
            $this->fecha_despacho->ViewValue = $this->fecha_despacho->CurrentValue;
            $this->fecha_despacho->ViewValue = FormatDateTime($this->fecha_despacho->ViewValue, $this->fecha_despacho->formatPattern());

            // user_despacho
            $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
            $curVal = strval($this->user_despacho->CurrentValue);
            if ($curVal != "") {
                $this->user_despacho->ViewValue = $this->user_despacho->lookupCacheOption($curVal);
                if ($this->user_despacho->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->user_despacho->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->user_despacho->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $sqlWrk = $this->user_despacho->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->user_despacho->Lookup->renderViewRow($rswrk[0]);
                        $this->user_despacho->ViewValue = $this->user_despacho->displayValue($arwrk);
                    } else {
                        $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
                    }
                }
            } else {
                $this->user_despacho->ViewValue = null;
            }

            // consignacion
            if (strval($this->consignacion->CurrentValue) != "") {
                $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
            } else {
                $this->consignacion->ViewValue = null;
            }

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // descuento2
            $this->descuento2->ViewValue = $this->descuento2->CurrentValue;
            $this->descuento2->ViewValue = FormatNumber($this->descuento2->ViewValue, $this->descuento2->formatPattern());

            // monto_sin_descuento
            $this->monto_sin_descuento->ViewValue = $this->monto_sin_descuento->CurrentValue;
            $this->monto_sin_descuento->ViewValue = FormatNumber($this->monto_sin_descuento->ViewValue, $this->monto_sin_descuento->formatPattern());

            // factura
            if (strval($this->factura->CurrentValue) != "") {
                $this->factura->ViewValue = $this->factura->optionCaption($this->factura->CurrentValue);
            } else {
                $this->factura->ViewValue = null;
            }

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;

            // direccion
            $this->direccion->ViewValue = $this->direccion->CurrentValue;

            // telefono
            $this->telefono->ViewValue = $this->telefono->CurrentValue;

            // email
            $this->_email->ViewValue = $this->_email->CurrentValue;

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }

            // comprobante
            $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
            $this->comprobante->ViewValue = FormatNumber($this->comprobante->ViewValue, $this->comprobante->formatPattern());

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

            // nro_despacho
            $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;

            // cerrado
            if (strval($this->cerrado->CurrentValue) != "") {
                $this->cerrado->ViewValue = $this->cerrado->optionCaption($this->cerrado->CurrentValue);
            } else {
                $this->cerrado->ViewValue = null;
            }

            // asesor_asignado
            $curVal = strval($this->asesor_asignado->CurrentValue);
            if ($curVal != "") {
                $this->asesor_asignado->ViewValue = $this->asesor_asignado->lookupCacheOption($curVal);
                if ($this->asesor_asignado->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                    $lookupFilter = $this->asesor_asignado->getSelectFilter($this); // PHP
                    $sqlWrk = $this->asesor_asignado->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->asesor_asignado->Lookup->renderViewRow($rswrk[0]);
                        $this->asesor_asignado->ViewValue = $this->asesor_asignado->displayValue($arwrk);
                    } else {
                        $this->asesor_asignado->ViewValue = $this->asesor_asignado->CurrentValue;
                    }
                }
            } else {
                $this->asesor_asignado->ViewValue = null;
            }

            // tasa_indexada
            $this->tasa_indexada->ViewValue = $this->tasa_indexada->CurrentValue;
            $this->tasa_indexada->ViewValue = FormatNumber($this->tasa_indexada->ViewValue, $this->tasa_indexada->formatPattern());

            // id_documento_padre_nd
            $this->id_documento_padre_nd->ViewValue = $this->id_documento_padre_nd->CurrentValue;
            $this->id_documento_padre_nd->ViewValue = FormatNumber($this->id_documento_padre_nd->ViewValue, $this->id_documento_padre_nd->formatPattern());

            // archivo_pedido
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->ViewValue = $this->archivo_pedido->Upload->DbValue;
            } else {
                $this->archivo_pedido->ViewValue = "";
            }

            // checker
            $this->checker->ViewValue = $this->checker->CurrentValue;

            // checker_date
            $this->checker_date->ViewValue = $this->checker_date->CurrentValue;
            $this->checker_date->ViewValue = FormatDateTime($this->checker_date->ViewValue, $this->checker_date->formatPattern());

            // packer
            $this->packer->ViewValue = $this->packer->CurrentValue;

            // packer_date
            $this->packer_date->ViewValue = $this->packer_date->CurrentValue;
            $this->packer_date->ViewValue = FormatDateTime($this->packer_date->ViewValue, $this->packer_date->formatPattern());

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // cliente
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // nota
            $this->nota->HrefValue = "";

            // estatus
            $this->estatus->HrefValue = "";

            // bultos
            $this->bultos->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // factura
            $this->factura->HrefValue = "";

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // direccion
            $this->direccion->HrefValue = "";

            // telefono
            $this->telefono->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // fecha
            $this->fecha->setupEditAttributes();
            $this->fecha->EditValue = $this->fecha->CurrentValue;
            $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, $this->fecha->formatPattern());

            // cliente
            $this->cliente->setupEditAttributes();
            $curVal = strval($this->cliente->CurrentValue);
            if ($curVal != "") {
                $this->cliente->EditValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->EditValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->EditValue = $this->cliente->CurrentValue;
                    }
                }
            } else {
                $this->cliente->EditValue = null;
            }

            // nro_documento
            $this->nro_documento->setupEditAttributes();
            $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;

            // nota
            $this->nota->setupEditAttributes();
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // estatus
            $this->estatus->setupEditAttributes();
            $this->estatus->EditValue = $this->estatus->options(true);
            $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

            // bultos
            $this->bultos->setupEditAttributes();
            $this->bultos->EditValue = $this->bultos->CurrentValue;
            $this->bultos->PlaceHolder = RemoveHtml($this->bultos->caption());
            if (strval($this->bultos->EditValue) != "" && is_numeric($this->bultos->EditValue)) {
                $this->bultos->EditValue = $this->bultos->EditValue;
            }

            // consignacion
            $this->consignacion->EditValue = $this->consignacion->options(false);
            $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

            // factura
            $this->factura->setupEditAttributes();
            $this->factura->EditValue = $this->factura->options(true);
            $this->factura->PlaceHolder = RemoveHtml($this->factura->caption());

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

            // direccion
            $this->direccion->setupEditAttributes();
            if (!$this->direccion->Raw) {
                $this->direccion->CurrentValue = HtmlDecode($this->direccion->CurrentValue);
            }
            $this->direccion->EditValue = HtmlEncode($this->direccion->CurrentValue);
            $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

            // telefono
            $this->telefono->setupEditAttributes();
            if (!$this->telefono->Raw) {
                $this->telefono->CurrentValue = HtmlDecode($this->telefono->CurrentValue);
            }
            $this->telefono->EditValue = HtmlEncode($this->telefono->CurrentValue);
            $this->telefono->PlaceHolder = RemoveHtml($this->telefono->caption());

            // Edit refer script

            // fecha
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // cliente
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // nota
            $this->nota->HrefValue = "";

            // estatus
            $this->estatus->HrefValue = "";

            // bultos
            $this->bultos->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // factura
            $this->factura->HrefValue = "";

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // direccion
            $this->direccion->HrefValue = "";

            // telefono
            $this->telefono->HrefValue = "";
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
            if ($this->fecha->Visible && $this->fecha->Required) {
                if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                    $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
                }
            }
            if ($this->cliente->Visible && $this->cliente->Required) {
                if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                    $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
                }
            }
            if ($this->nro_documento->Visible && $this->nro_documento->Required) {
                if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                    $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
                }
            }
            if ($this->nota->Visible && $this->nota->Required) {
                if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                    $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
                }
            }
            if ($this->estatus->Visible && $this->estatus->Required) {
                if (!$this->estatus->IsDetailKey && EmptyValue($this->estatus->FormValue)) {
                    $this->estatus->addErrorMessage(str_replace("%s", $this->estatus->caption(), $this->estatus->RequiredErrorMessage));
                }
            }
            if ($this->bultos->Visible && $this->bultos->Required) {
                if (!$this->bultos->IsDetailKey && EmptyValue($this->bultos->FormValue)) {
                    $this->bultos->addErrorMessage(str_replace("%s", $this->bultos->caption(), $this->bultos->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->bultos->FormValue)) {
                $this->bultos->addErrorMessage($this->bultos->getErrorMessage(false));
            }
            if ($this->consignacion->Visible && $this->consignacion->Required) {
                if ($this->consignacion->FormValue == "") {
                    $this->consignacion->addErrorMessage(str_replace("%s", $this->consignacion->caption(), $this->consignacion->RequiredErrorMessage));
                }
            }
            if ($this->factura->Visible && $this->factura->Required) {
                if (!$this->factura->IsDetailKey && EmptyValue($this->factura->FormValue)) {
                    $this->factura->addErrorMessage(str_replace("%s", $this->factura->caption(), $this->factura->RequiredErrorMessage));
                }
            }
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
            if ($this->direccion->Visible && $this->direccion->Required) {
                if (!$this->direccion->IsDetailKey && EmptyValue($this->direccion->FormValue)) {
                    $this->direccion->addErrorMessage(str_replace("%s", $this->direccion->caption(), $this->direccion->RequiredErrorMessage));
                }
            }
            if ($this->telefono->Visible && $this->telefono->Required) {
                if (!$this->telefono->IsDetailKey && EmptyValue($this->telefono->FormValue)) {
                    $this->telefono->addErrorMessage(str_replace("%s", $this->telefono->caption(), $this->telefono->RequiredErrorMessage));
                }
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ViewOutGrid");
        if (in_array("view_out", $detailTblVar) && $detailPage->DetailEdit) {
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
            $detailPage = Container("ViewOutGrid");
            if (in_array("view_out", $detailTblVar) && $detailPage->DetailEdit && $editRow) {
                $Security->loadCurrentUserLevel($this->ProjectID . "view_out"); // Load user level of detail table
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

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, $this->nota->ReadOnly);

        // estatus
        $this->estatus->setDbValueDef($rsnew, $this->estatus->CurrentValue, $this->estatus->ReadOnly);

        // bultos
        $this->bultos->setDbValueDef($rsnew, $this->bultos->CurrentValue, $this->bultos->ReadOnly);

        // consignacion
        $this->consignacion->setDbValueDef($rsnew, $this->consignacion->CurrentValue, $this->consignacion->ReadOnly);

        // factura
        $this->factura->setDbValueDef($rsnew, $this->factura->CurrentValue, $this->factura->ReadOnly);

        // ci_rif
        $this->ci_rif->setDbValueDef($rsnew, $this->ci_rif->CurrentValue, $this->ci_rif->ReadOnly);

        // nombre
        $this->nombre->setDbValueDef($rsnew, $this->nombre->CurrentValue, $this->nombre->ReadOnly);

        // direccion
        $this->direccion->setDbValueDef($rsnew, $this->direccion->CurrentValue, $this->direccion->ReadOnly);

        // telefono
        $this->telefono->setDbValueDef($rsnew, $this->telefono->CurrentValue, $this->telefono->ReadOnly);
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['nota'])) { // nota
            $this->nota->CurrentValue = $row['nota'];
        }
        if (isset($row['estatus'])) { // estatus
            $this->estatus->CurrentValue = $row['estatus'];
        }
        if (isset($row['bultos'])) { // bultos
            $this->bultos->CurrentValue = $row['bultos'];
        }
        if (isset($row['consignacion'])) { // consignacion
            $this->consignacion->CurrentValue = $row['consignacion'];
        }
        if (isset($row['factura'])) { // factura
            $this->factura->CurrentValue = $row['factura'];
        }
        if (isset($row['ci_rif'])) { // ci_rif
            $this->ci_rif->CurrentValue = $row['ci_rif'];
        }
        if (isset($row['nombre'])) { // nombre
            $this->nombre->CurrentValue = $row['nombre'];
        }
        if (isset($row['direccion'])) { // direccion
            $this->direccion->CurrentValue = $row['direccion'];
        }
        if (isset($row['telefono'])) { // telefono
            $this->telefono->CurrentValue = $row['telefono'];
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
            if (in_array("view_out", $detailTblVar)) {
                $detailPageObj = Container("ViewOutGrid");
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->id_documento->IsDetailKey = true;
                    $detailPageObj->id_documento->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->id_documento->setSessionValue($detailPageObj->id_documento->CurrentValue);
                    $detailPageObj->tipo_documento->IsDetailKey = true;
                    $detailPageObj->tipo_documento->CurrentValue = $this->tipo_documento->CurrentValue;
                    $detailPageObj->tipo_documento->setSessionValue($detailPageObj->tipo_documento->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ViewOutTdcnetList"), "", $this->TableVar, true);
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
                case "x__username":
                    break;
                case "x_cliente":
                    break;
                case "x_lista_pedido":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_estatus":
                    break;
                case "x_id_documento_padre":
                    break;
                case "x_moneda":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_asesor":
                    break;
                case "x_documento":
                    break;
                case "x_dias_credito":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_entregado":
                    break;
                case "x_pagado":
                    break;
                case "x_user_bultos":
                    break;
                case "x_user_despacho":
                    break;
                case "x_consignacion":
                    break;
                case "x_factura":
                    break;
                case "x_activo":
                    break;
                case "x_cerrado":
                    break;
                case "x_asesor_asignado":
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
