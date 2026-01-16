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
class EntradasAdd extends Entradas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "EntradasAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "EntradasAdd";

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
        $this->tipo_documento->setVisibility();
        $this->nro_documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->proveedor->setVisibility();
        $this->almacen->setVisibility();
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->Visible = false;
        $this->documento->setVisibility();
        $this->nota->setVisibility();
        $this->estatus->Visible = false;
        $this->_username->Visible = false;
        $this->id_documento_padre->Visible = false;
        $this->moneda->setVisibility();
        $this->consignacion->setVisibility();
        $this->consignacion_reportada->Visible = false;
        $this->aplica_retencion->setVisibility();
        $this->ret_iva->Visible = false;
        $this->ref_iva->Visible = false;
        $this->ret_islr->Visible = false;
        $this->ref_islr->Visible = false;
        $this->ret_municipal->Visible = false;
        $this->ref_municipal->setVisibility();
        $this->monto_pagar->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->fecha_registro_retenciones->setVisibility();
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->fecha_libro_compra->Visible = false;
        $this->tipo_municipal->Visible = false;
        $this->cerrado->Visible = false;
        $this->descuento->setVisibility();
        $this->archivo_pedido->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'entradas';
        $this->TableName = 'entradas';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (entradas)
        if (!isset($GLOBALS["entradas"]) || $GLOBALS["entradas"]::class == PROJECT_NAMESPACE . "entradas") {
            $GLOBALS["entradas"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'entradas');
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
                        $result["view"] = SameString($pageName, "EntradasView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->tipo_documento);
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->almacen);
        $this->setupLookupOptions($this->documento);
        $this->setupLookupOptions($this->estatus);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->consignacion);
        $this->setupLookupOptions($this->consignacion_reportada);
        $this->setupLookupOptions($this->aplica_retencion);
        $this->setupLookupOptions($this->comprobante);
        $this->setupLookupOptions($this->cerrado);

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
                    $this->terminate("EntradasList"); // No matching record, return to list
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
                    $returnUrl = $this->getViewUrl();
                    if (GetPageName($returnUrl) == "EntradasList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "EntradasView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "EntradasList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "EntradasList"; // Return list page content
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
        $this->archivo_pedido->Upload->Index = $CurrentForm->Index;
        $this->archivo_pedido->Upload->uploadFile();
        $this->archivo_pedido->CurrentValue = $this->archivo_pedido->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->consignacion_reportada->DefaultValue = $this->consignacion_reportada->getDefault(); // PHP
        $this->consignacion_reportada->OldValue = $this->consignacion_reportada->DefaultValue;
        $this->cerrado->DefaultValue = $this->cerrado->getDefault(); // PHP
        $this->cerrado->OldValue = $this->cerrado->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'tipo_documento' first before field var 'x_tipo_documento'
        $val = $CurrentForm->hasValue("tipo_documento") ? $CurrentForm->getValue("tipo_documento") : $CurrentForm->getValue("x_tipo_documento");
        if (!$this->tipo_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_documento->Visible = false; // Disable update for API request
            } else {
                $this->tipo_documento->setFormValue($val);
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

        // Check field name 'doc_afectado' first before field var 'x_doc_afectado'
        $val = $CurrentForm->hasValue("doc_afectado") ? $CurrentForm->getValue("doc_afectado") : $CurrentForm->getValue("x_doc_afectado");
        if (!$this->doc_afectado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_afectado->Visible = false; // Disable update for API request
            } else {
                $this->doc_afectado->setFormValue($val);
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

        // Check field name 'proveedor' first before field var 'x_proveedor'
        $val = $CurrentForm->hasValue("proveedor") ? $CurrentForm->getValue("proveedor") : $CurrentForm->getValue("x_proveedor");
        if (!$this->proveedor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->proveedor->Visible = false; // Disable update for API request
            } else {
                $this->proveedor->setFormValue($val);
            }
        }

        // Check field name 'almacen' first before field var 'x_almacen'
        $val = $CurrentForm->hasValue("almacen") ? $CurrentForm->getValue("almacen") : $CurrentForm->getValue("x_almacen");
        if (!$this->almacen->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->almacen->Visible = false; // Disable update for API request
            } else {
                $this->almacen->setFormValue($val);
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

        // Check field name 'nota' first before field var 'x_nota'
        $val = $CurrentForm->hasValue("nota") ? $CurrentForm->getValue("nota") : $CurrentForm->getValue("x_nota");
        if (!$this->nota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nota->Visible = false; // Disable update for API request
            } else {
                $this->nota->setFormValue($val);
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

        // Check field name 'consignacion' first before field var 'x_consignacion'
        $val = $CurrentForm->hasValue("consignacion") ? $CurrentForm->getValue("consignacion") : $CurrentForm->getValue("x_consignacion");
        if (!$this->consignacion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->consignacion->Visible = false; // Disable update for API request
            } else {
                $this->consignacion->setFormValue($val);
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

        // Check field name 'ref_municipal' first before field var 'x_ref_municipal'
        $val = $CurrentForm->hasValue("ref_municipal") ? $CurrentForm->getValue("ref_municipal") : $CurrentForm->getValue("x_ref_municipal");
        if (!$this->ref_municipal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_municipal->Visible = false; // Disable update for API request
            } else {
                $this->ref_municipal->setFormValue($val);
            }
        }

        // Check field name 'fecha_registro_retenciones' first before field var 'x_fecha_registro_retenciones'
        $val = $CurrentForm->hasValue("fecha_registro_retenciones") ? $CurrentForm->getValue("fecha_registro_retenciones") : $CurrentForm->getValue("x_fecha_registro_retenciones");
        if (!$this->fecha_registro_retenciones->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha_registro_retenciones->Visible = false; // Disable update for API request
            } else {
                $this->fecha_registro_retenciones->setFormValue($val, true, $validate);
            }
            $this->fecha_registro_retenciones->CurrentValue = UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, $this->fecha_registro_retenciones->formatPattern());
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
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->nro_control->CurrentValue = $this->nro_control->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->proveedor->CurrentValue = $this->proveedor->FormValue;
        $this->almacen->CurrentValue = $this->almacen->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->consignacion->CurrentValue = $this->consignacion->FormValue;
        $this->aplica_retencion->CurrentValue = $this->aplica_retencion->FormValue;
        $this->ref_municipal->CurrentValue = $this->ref_municipal->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = $this->fecha_registro_retenciones->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, $this->fecha_registro_retenciones->formatPattern());
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->proveedor->setDbValue($row['proveedor']);
        $this->almacen->setDbValue($row['almacen']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->documento->setDbValue($row['documento']);
        $this->nota->setDbValue($row['nota']);
        $this->estatus->setDbValue($row['estatus']);
        $this->_username->setDbValue($row['username']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->moneda->setDbValue($row['moneda']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->consignacion_reportada->setDbValue($row['consignacion_reportada']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->fecha_registro_retenciones->setDbValue($row['fecha_registro_retenciones']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->fecha_libro_compra->setDbValue($row['fecha_libro_compra']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->descuento->setDbValue($row['descuento']);
        $this->archivo_pedido->Upload->DbValue = $row['archivo_pedido'];
        $this->archivo_pedido->setDbValue($this->archivo_pedido->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['tipo_documento'] = $this->tipo_documento->DefaultValue;
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['proveedor'] = $this->proveedor->DefaultValue;
        $row['almacen'] = $this->almacen->DefaultValue;
        $row['monto_total'] = $this->monto_total->DefaultValue;
        $row['alicuota_iva'] = $this->alicuota_iva->DefaultValue;
        $row['iva'] = $this->iva->DefaultValue;
        $row['total'] = $this->total->DefaultValue;
        $row['documento'] = $this->documento->DefaultValue;
        $row['nota'] = $this->nota->DefaultValue;
        $row['estatus'] = $this->estatus->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['id_documento_padre'] = $this->id_documento_padre->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['consignacion'] = $this->consignacion->DefaultValue;
        $row['consignacion_reportada'] = $this->consignacion_reportada->DefaultValue;
        $row['aplica_retencion'] = $this->aplica_retencion->DefaultValue;
        $row['ret_iva'] = $this->ret_iva->DefaultValue;
        $row['ref_iva'] = $this->ref_iva->DefaultValue;
        $row['ret_islr'] = $this->ret_islr->DefaultValue;
        $row['ref_islr'] = $this->ref_islr->DefaultValue;
        $row['ret_municipal'] = $this->ret_municipal->DefaultValue;
        $row['ref_municipal'] = $this->ref_municipal->DefaultValue;
        $row['monto_pagar'] = $this->monto_pagar->DefaultValue;
        $row['comprobante'] = $this->comprobante->DefaultValue;
        $row['tipo_iva'] = $this->tipo_iva->DefaultValue;
        $row['tipo_islr'] = $this->tipo_islr->DefaultValue;
        $row['sustraendo'] = $this->sustraendo->DefaultValue;
        $row['fecha_registro_retenciones'] = $this->fecha_registro_retenciones->DefaultValue;
        $row['tasa_dia'] = $this->tasa_dia->DefaultValue;
        $row['monto_usd'] = $this->monto_usd->DefaultValue;
        $row['fecha_libro_compra'] = $this->fecha_libro_compra->DefaultValue;
        $row['tipo_municipal'] = $this->tipo_municipal->DefaultValue;
        $row['cerrado'] = $this->cerrado->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['archivo_pedido'] = $this->archivo_pedido->DefaultValue;
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

        // nro_documento
        $this->nro_documento->RowCssClass = "row";

        // doc_afectado
        $this->doc_afectado->RowCssClass = "row";

        // nro_control
        $this->nro_control->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // proveedor
        $this->proveedor->RowCssClass = "row";

        // almacen
        $this->almacen->RowCssClass = "row";

        // monto_total
        $this->monto_total->RowCssClass = "row";

        // alicuota_iva
        $this->alicuota_iva->RowCssClass = "row";

        // iva
        $this->iva->RowCssClass = "row";

        // total
        $this->total->RowCssClass = "row";

        // documento
        $this->documento->RowCssClass = "row";

        // nota
        $this->nota->RowCssClass = "row";

        // estatus
        $this->estatus->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // id_documento_padre
        $this->id_documento_padre->RowCssClass = "row";

        // moneda
        $this->moneda->RowCssClass = "row";

        // consignacion
        $this->consignacion->RowCssClass = "row";

        // consignacion_reportada
        $this->consignacion_reportada->RowCssClass = "row";

        // aplica_retencion
        $this->aplica_retencion->RowCssClass = "row";

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

        // monto_pagar
        $this->monto_pagar->RowCssClass = "row";

        // comprobante
        $this->comprobante->RowCssClass = "row";

        // tipo_iva
        $this->tipo_iva->RowCssClass = "row";

        // tipo_islr
        $this->tipo_islr->RowCssClass = "row";

        // sustraendo
        $this->sustraendo->RowCssClass = "row";

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->RowCssClass = "row";

        // tasa_dia
        $this->tasa_dia->RowCssClass = "row";

        // monto_usd
        $this->monto_usd->RowCssClass = "row";

        // fecha_libro_compra
        $this->fecha_libro_compra->RowCssClass = "row";

        // tipo_municipal
        $this->tipo_municipal->RowCssClass = "row";

        // cerrado
        $this->cerrado->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

        // archivo_pedido
        $this->archivo_pedido->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // tipo_documento
            $curVal = strval($this->tipo_documento->CurrentValue);
            if ($curVal != "") {
                $this->tipo_documento->ViewValue = $this->tipo_documento->lookupCacheOption($curVal);
                if ($this->tipo_documento->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_documento->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_documento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_documento->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_documento->ViewValue = $this->tipo_documento->displayValue($arwrk);
                    } else {
                        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
                    }
                }
            } else {
                $this->tipo_documento->ViewValue = null;
            }

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

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

            // almacen
            $curVal = strval($this->almacen->CurrentValue);
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
                if ($this->almacen->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->almacen->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->almacen->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                    $sqlWrk = $this->almacen->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->almacen->Lookup->renderViewRow($rswrk[0]);
                        $this->almacen->ViewValue = $this->almacen->displayValue($arwrk);
                    } else {
                        $this->almacen->ViewValue = $this->almacen->CurrentValue;
                    }
                }
            } else {
                $this->almacen->ViewValue = null;
            }

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

            // documento
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->ViewValue = null;
            }

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
            }

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

            // id_documento_padre
            $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;

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

            // consignacion
            if (strval($this->consignacion->CurrentValue) != "") {
                $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
            } else {
                $this->consignacion->ViewValue = null;
            }

            // consignacion_reportada
            if (strval($this->consignacion_reportada->CurrentValue) != "") {
                $this->consignacion_reportada->ViewValue = $this->consignacion_reportada->optionCaption($this->consignacion_reportada->CurrentValue);
            } else {
                $this->consignacion_reportada->ViewValue = null;
            }

            // aplica_retencion
            if (strval($this->aplica_retencion->CurrentValue) != "") {
                $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
            } else {
                $this->aplica_retencion->ViewValue = null;
            }

            // ret_iva
            $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
            $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, $this->ret_iva->formatPattern());

            // ref_iva
            $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;

            // ret_islr
            $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
            $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, $this->ret_islr->formatPattern());

            // ref_islr
            $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;

            // ret_municipal
            $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
            $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, $this->ret_municipal->formatPattern());

            // ref_municipal
            $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;

            // monto_pagar
            $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
            $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, $this->monto_pagar->formatPattern());

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

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->ViewValue = $this->fecha_registro_retenciones->CurrentValue;
            $this->fecha_registro_retenciones->ViewValue = FormatDateTime($this->fecha_registro_retenciones->ViewValue, $this->fecha_registro_retenciones->formatPattern());

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

            // fecha_libro_compra
            $this->fecha_libro_compra->ViewValue = $this->fecha_libro_compra->CurrentValue;
            $this->fecha_libro_compra->ViewValue = FormatDateTime($this->fecha_libro_compra->ViewValue, $this->fecha_libro_compra->formatPattern());

            // tipo_municipal
            $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;

            // cerrado
            if (strval($this->cerrado->CurrentValue) != "") {
                $this->cerrado->ViewValue = $this->cerrado->optionCaption($this->cerrado->CurrentValue);
            } else {
                $this->cerrado->ViewValue = null;
            }

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

            // archivo_pedido
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->ViewValue = $this->archivo_pedido->Upload->DbValue;
            } else {
                $this->archivo_pedido->ViewValue = "";
            }

            // tipo_documento
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // nro_control
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // proveedor
            $this->proveedor->HrefValue = "";
            $this->proveedor->TooltipValue = "";

            // almacen
            $this->almacen->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";

            // nota
            $this->nota->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->HrefValue = "";

            // ref_municipal
            $this->ref_municipal->HrefValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";

            // archivo_pedido
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->HrefValue = GetFileUploadUrl($this->archivo_pedido, $this->archivo_pedido->htmlDecode($this->archivo_pedido->Upload->DbValue)); // Add prefix/suffix
                $this->archivo_pedido->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->archivo_pedido->HrefValue = FullUrl($this->archivo_pedido->HrefValue, "href");
                }
            } else {
                $this->archivo_pedido->HrefValue = "";
            }
            $this->archivo_pedido->ExportHrefValue = $this->archivo_pedido->UploadPath . $this->archivo_pedido->Upload->DbValue;
        } elseif ($this->RowType == RowType::ADD) {
            // tipo_documento
            $this->tipo_documento->setupEditAttributes();
            $curVal = trim(strval($this->tipo_documento->CurrentValue));
            if ($curVal != "") {
                $this->tipo_documento->ViewValue = $this->tipo_documento->lookupCacheOption($curVal);
            } else {
                $this->tipo_documento->ViewValue = $this->tipo_documento->Lookup !== null && is_array($this->tipo_documento->lookupOptions()) && count($this->tipo_documento->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_documento->ViewValue !== null) { // Load from cache
                $this->tipo_documento->EditValue = array_values($this->tipo_documento->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->tipo_documento->CurrentValue, $this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_documento->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_documento->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_documento->EditValue = $arwrk;
            }
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // nro_documento
            $this->nro_documento->setupEditAttributes();
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // doc_afectado
            $this->doc_afectado->setupEditAttributes();
            if (!$this->doc_afectado->Raw) {
                $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->CurrentValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

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

            // almacen
            $this->almacen->setupEditAttributes();
            $curVal = trim(strval($this->almacen->CurrentValue));
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
            } else {
                $this->almacen->ViewValue = $this->almacen->Lookup !== null && is_array($this->almacen->lookupOptions()) && count($this->almacen->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->almacen->ViewValue !== null) { // Load from cache
                $this->almacen->EditValue = array_values($this->almacen->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->almacen->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $this->almacen->CurrentValue, $this->almacen->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                }
                $sqlWrk = $this->almacen->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->almacen->EditValue = $arwrk;
            }
            $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

            // documento
            $this->documento->setupEditAttributes();
            $this->documento->EditValue = $this->documento->options(true);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // nota
            $this->nota->setupEditAttributes();
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

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

            // consignacion
            $this->consignacion->setupEditAttributes();
            $this->consignacion->EditValue = $this->consignacion->options(true);
            $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

            // aplica_retencion
            $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
            $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

            // ref_municipal
            $this->ref_municipal->setupEditAttributes();
            if (!$this->ref_municipal->Raw) {
                $this->ref_municipal->CurrentValue = HtmlDecode($this->ref_municipal->CurrentValue);
            }
            $this->ref_municipal->EditValue = HtmlEncode($this->ref_municipal->CurrentValue);
            $this->ref_municipal->PlaceHolder = RemoveHtml($this->ref_municipal->caption());

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->setupEditAttributes();
            $this->fecha_registro_retenciones->EditValue = HtmlEncode(FormatDateTime($this->fecha_registro_retenciones->CurrentValue, $this->fecha_registro_retenciones->formatPattern()));
            $this->fecha_registro_retenciones->PlaceHolder = RemoveHtml($this->fecha_registro_retenciones->caption());

            // descuento
            $this->descuento->setupEditAttributes();
            $this->descuento->EditValue = $this->descuento->CurrentValue;
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
            }

            // archivo_pedido
            $this->archivo_pedido->setupEditAttributes();
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->EditValue = $this->archivo_pedido->Upload->DbValue;
            } else {
                $this->archivo_pedido->EditValue = "";
            }
            if (!EmptyValue($this->archivo_pedido->CurrentValue)) {
                $this->archivo_pedido->Upload->FileName = $this->archivo_pedido->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->archivo_pedido->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->archivo_pedido);
            }

            // Add refer script

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // nro_documento
            $this->nro_documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // nro_control
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->HrefValue = "";

            // proveedor
            $this->proveedor->HrefValue = "";

            // almacen
            $this->almacen->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";

            // nota
            $this->nota->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->HrefValue = "";

            // ref_municipal
            $this->ref_municipal->HrefValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->HrefValue = "";

            // descuento
            $this->descuento->HrefValue = "";

            // archivo_pedido
            if (!EmptyValue($this->archivo_pedido->Upload->DbValue)) {
                $this->archivo_pedido->HrefValue = GetFileUploadUrl($this->archivo_pedido, $this->archivo_pedido->htmlDecode($this->archivo_pedido->Upload->DbValue)); // Add prefix/suffix
                $this->archivo_pedido->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->archivo_pedido->HrefValue = FullUrl($this->archivo_pedido->HrefValue, "href");
                }
            } else {
                $this->archivo_pedido->HrefValue = "";
            }
            $this->archivo_pedido->ExportHrefValue = $this->archivo_pedido->UploadPath . $this->archivo_pedido->Upload->DbValue;
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
            if ($this->tipo_documento->Visible && $this->tipo_documento->Required) {
                if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                    $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
                }
            }
            if ($this->nro_documento->Visible && $this->nro_documento->Required) {
                if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                    $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
                }
            }
            if ($this->doc_afectado->Visible && $this->doc_afectado->Required) {
                if (!$this->doc_afectado->IsDetailKey && EmptyValue($this->doc_afectado->FormValue)) {
                    $this->doc_afectado->addErrorMessage(str_replace("%s", $this->doc_afectado->caption(), $this->doc_afectado->RequiredErrorMessage));
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
            if ($this->proveedor->Visible && $this->proveedor->Required) {
                if (!$this->proveedor->IsDetailKey && EmptyValue($this->proveedor->FormValue)) {
                    $this->proveedor->addErrorMessage(str_replace("%s", $this->proveedor->caption(), $this->proveedor->RequiredErrorMessage));
                }
            }
            if ($this->almacen->Visible && $this->almacen->Required) {
                if (!$this->almacen->IsDetailKey && EmptyValue($this->almacen->FormValue)) {
                    $this->almacen->addErrorMessage(str_replace("%s", $this->almacen->caption(), $this->almacen->RequiredErrorMessage));
                }
            }
            if ($this->documento->Visible && $this->documento->Required) {
                if (!$this->documento->IsDetailKey && EmptyValue($this->documento->FormValue)) {
                    $this->documento->addErrorMessage(str_replace("%s", $this->documento->caption(), $this->documento->RequiredErrorMessage));
                }
            }
            if ($this->nota->Visible && $this->nota->Required) {
                if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                    $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
                }
            }
            if ($this->moneda->Visible && $this->moneda->Required) {
                if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                    $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
                }
            }
            if ($this->consignacion->Visible && $this->consignacion->Required) {
                if (!$this->consignacion->IsDetailKey && EmptyValue($this->consignacion->FormValue)) {
                    $this->consignacion->addErrorMessage(str_replace("%s", $this->consignacion->caption(), $this->consignacion->RequiredErrorMessage));
                }
            }
            if ($this->aplica_retencion->Visible && $this->aplica_retencion->Required) {
                if ($this->aplica_retencion->FormValue == "") {
                    $this->aplica_retencion->addErrorMessage(str_replace("%s", $this->aplica_retencion->caption(), $this->aplica_retencion->RequiredErrorMessage));
                }
            }
            if ($this->ref_municipal->Visible && $this->ref_municipal->Required) {
                if (!$this->ref_municipal->IsDetailKey && EmptyValue($this->ref_municipal->FormValue)) {
                    $this->ref_municipal->addErrorMessage(str_replace("%s", $this->ref_municipal->caption(), $this->ref_municipal->RequiredErrorMessage));
                }
            }
            if ($this->fecha_registro_retenciones->Visible && $this->fecha_registro_retenciones->Required) {
                if (!$this->fecha_registro_retenciones->IsDetailKey && EmptyValue($this->fecha_registro_retenciones->FormValue)) {
                    $this->fecha_registro_retenciones->addErrorMessage(str_replace("%s", $this->fecha_registro_retenciones->caption(), $this->fecha_registro_retenciones->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->fecha_registro_retenciones->FormValue, $this->fecha_registro_retenciones->formatPattern())) {
                $this->fecha_registro_retenciones->addErrorMessage($this->fecha_registro_retenciones->getErrorMessage(false));
            }
            if ($this->descuento->Visible && $this->descuento->Required) {
                if (!$this->descuento->IsDetailKey && EmptyValue($this->descuento->FormValue)) {
                    $this->descuento->addErrorMessage(str_replace("%s", $this->descuento->caption(), $this->descuento->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->descuento->FormValue)) {
                $this->descuento->addErrorMessage($this->descuento->getErrorMessage(false));
            }
            if ($this->archivo_pedido->Visible && $this->archivo_pedido->Required) {
                if ($this->archivo_pedido->Upload->FileName == "" && !$this->archivo_pedido->Upload->KeepFile) {
                    $this->archivo_pedido->addErrorMessage(str_replace("%s", $this->archivo_pedido->caption(), $this->archivo_pedido->RequiredErrorMessage));
                }
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("EntradasSalidasGrid");
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd) {
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
        if ($this->archivo_pedido->Visible && !$this->archivo_pedido->Upload->KeepFile) {
            if (!EmptyValue($this->archivo_pedido->Upload->FileName)) {
                $this->archivo_pedido->Upload->DbValue = null;
                FixUploadFileNames($this->archivo_pedido);
                $this->archivo_pedido->setDbValueDef($rsnew, $this->archivo_pedido->Upload->FileName, false);
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
                if ($this->archivo_pedido->Visible && !$this->archivo_pedido->Upload->KeepFile) {
                    $this->archivo_pedido->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->archivo_pedido, $rsnew['archivo_pedido'], false)) {
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
            $detailPage = Container("EntradasSalidasGrid");
            if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->tipo_documento->setSessionValue($this->tipo_documento->CurrentValue); // Set master key
                $detailPage->id_documento->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "entradas_salidas"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->tipo_documento->setSessionValue(""); // Clear master key if insert failed
                $detailPage->id_documento->setSessionValue(""); // Clear master key if insert failed
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

        // tipo_documento
        $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, false);

        // nro_documento
        $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, false);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, false);

        // nro_control
        $this->nro_control->setDbValueDef($rsnew, $this->nro_control->CurrentValue, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern()), false);

        // proveedor
        $this->proveedor->setDbValueDef($rsnew, $this->proveedor->CurrentValue, false);

        // almacen
        $this->almacen->setDbValueDef($rsnew, $this->almacen->CurrentValue, false);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, false);

        // consignacion
        $this->consignacion->setDbValueDef($rsnew, $this->consignacion->CurrentValue, strval($this->consignacion->CurrentValue) == "");

        // aplica_retencion
        $this->aplica_retencion->setDbValueDef($rsnew, $this->aplica_retencion->CurrentValue, false);

        // ref_municipal
        $this->ref_municipal->setDbValueDef($rsnew, $this->ref_municipal->CurrentValue, false);

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, $this->fecha_registro_retenciones->formatPattern()), false);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, false);

        // archivo_pedido
        if ($this->archivo_pedido->Visible && !$this->archivo_pedido->Upload->KeepFile) {
            if ($this->archivo_pedido->Upload->FileName == "") {
                $rsnew['archivo_pedido'] = null;
            } else {
                FixUploadTempFileNames($this->archivo_pedido);
                $rsnew['archivo_pedido'] = $this->archivo_pedido->Upload->FileName;
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
        if (isset($row['tipo_documento'])) { // tipo_documento
            $this->tipo_documento->setFormValue($row['tipo_documento']);
        }
        if (isset($row['nro_documento'])) { // nro_documento
            $this->nro_documento->setFormValue($row['nro_documento']);
        }
        if (isset($row['doc_afectado'])) { // doc_afectado
            $this->doc_afectado->setFormValue($row['doc_afectado']);
        }
        if (isset($row['nro_control'])) { // nro_control
            $this->nro_control->setFormValue($row['nro_control']);
        }
        if (isset($row['fecha'])) { // fecha
            $this->fecha->setFormValue($row['fecha']);
        }
        if (isset($row['proveedor'])) { // proveedor
            $this->proveedor->setFormValue($row['proveedor']);
        }
        if (isset($row['almacen'])) { // almacen
            $this->almacen->setFormValue($row['almacen']);
        }
        if (isset($row['documento'])) { // documento
            $this->documento->setFormValue($row['documento']);
        }
        if (isset($row['nota'])) { // nota
            $this->nota->setFormValue($row['nota']);
        }
        if (isset($row['moneda'])) { // moneda
            $this->moneda->setFormValue($row['moneda']);
        }
        if (isset($row['consignacion'])) { // consignacion
            $this->consignacion->setFormValue($row['consignacion']);
        }
        if (isset($row['aplica_retencion'])) { // aplica_retencion
            $this->aplica_retencion->setFormValue($row['aplica_retencion']);
        }
        if (isset($row['ref_municipal'])) { // ref_municipal
            $this->ref_municipal->setFormValue($row['ref_municipal']);
        }
        if (isset($row['fecha_registro_retenciones'])) { // fecha_registro_retenciones
            $this->fecha_registro_retenciones->setFormValue($row['fecha_registro_retenciones']);
        }
        if (isset($row['descuento'])) { // descuento
            $this->descuento->setFormValue($row['descuento']);
        }
        if (isset($row['archivo_pedido'])) { // archivo_pedido
            $this->archivo_pedido->setFormValue($row['archivo_pedido']);
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
            if (in_array("entradas_salidas", $detailTblVar)) {
                $detailPageObj = Container("EntradasSalidasGrid");
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
                    $detailPageObj->tipo_documento->IsDetailKey = true;
                    $detailPageObj->tipo_documento->CurrentValue = $this->tipo_documento->CurrentValue;
                    $detailPageObj->tipo_documento->setSessionValue($detailPageObj->tipo_documento->CurrentValue);
                    $detailPageObj->id_documento->IsDetailKey = true;
                    $detailPageObj->id_documento->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->id_documento->setSessionValue($detailPageObj->id_documento->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EntradasList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
        $pages->add(3);
        $pages->add(4);
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
                case "x_tipo_documento":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_proveedor":
                    break;
                case "x_almacen":
                    break;
                case "x_documento":
                    break;
                case "x_estatus":
                    break;
                case "x__username":
                    break;
                case "x_moneda":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_consignacion":
                    break;
                case "x_consignacion_reportada":
                    break;
                case "x_aplica_retencion":
                    break;
                case "x_comprobante":
                    break;
                case "x_cerrado":
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
    public function pageRender() {
    	//echo "Page Render";
    	$this->fecha->Visible = FALSE;
    	$this->nro_documento->Visible = FALSE;
    	$this->nro_control->Visible = FALSE;
    	$this->documento->Visible = FALSE;
    	$this->ret_municipal->Visible = FALSE;
    	$this->ref_municipal->Visible = FALSE;
    	$this->doc_afectado->Visible = FALSE;
    	$this->aplica_retencion->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
    	$this->archivo_pedido->Visible = FALSE;
    	$this->doc_afectado->Visible = FALSE;
    	$this->almacen->Visible = FALSE;
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	if($tipo == "TDCFCC") {
    		$this->fecha->Visible = TRUE;
    		$this->nro_documento->Visible = TRUE;
    		$this->nro_control->Visible = TRUE;
    		$this->documento->Visible = TRUE;
    		$this->doc_afectado->Visible = TRUE;
    		$this->aplica_retencion->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    	}
    	if($tipo == "TDCNRP" or $tipo == "TDCPDC") {
    		$this->descuento->Visible = TRUE;
    	}
    	if($tipo == "TDCPDC" or $tipo == "TDCAEN") {
    		$this->archivo_pedido->Visible = TRUE;
    		$this->doc_afectado->Visible = TRUE;
    	}
    	if($tipo == "TDCAEN") {
    		$this->almacen->Visible = TRUE;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDC":
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCNRP":
    		$header = '<a href="ViewEntradasList?crear=TDCNRP" class="btn btn-primary">
    				Crear nota de recepci&oacute;n desde Pedido de Compra o Factura
    			</a>';
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCFCC":
    		$header = '<a href="ViewEntradasList?crear=TDCFCC" class="btn btn-primary">
    				Crear Factura desde Nota de Recepci&oacute;n
    			</a>';
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCAEN":
    		break;
    	}
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
