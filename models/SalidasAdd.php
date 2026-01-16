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
class SalidasAdd extends Salidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "SalidasAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "SalidasAdd";

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
        $this->nro_documento->Visible = false;
        $this->nro_control->Visible = false;
        $this->fecha->Visible = false;
        $this->cliente->setVisibility();
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->moneda->setVisibility();
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->Visible = false;
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->lista_pedido->setVisibility();
        $this->nota->setVisibility();
        $this->_username->Visible = false;
        $this->estatus->Visible = false;
        $this->asesor->Visible = false;
        $this->dias_credito->setVisibility();
        $this->entregado->Visible = false;
        $this->fecha_entrega->Visible = false;
        $this->pagado->Visible = false;
        $this->bultos->Visible = false;
        $this->fecha_bultos->Visible = false;
        $this->user_bultos->Visible = false;
        $this->fecha_despacho->Visible = false;
        $this->user_despacho->Visible = false;
        $this->consignacion->setVisibility();
        $this->unidades->Visible = false;
        $this->descuento->Visible = false;
        $this->monto_sin_descuento->Visible = false;
        $this->factura->setVisibility();
        $this->ci_rif->Visible = false;
        $this->nombre->Visible = false;
        $this->direccion->Visible = false;
        $this->telefono->Visible = false;
        $this->_email->Visible = false;
        $this->activo->Visible = false;
        $this->comprobante->Visible = false;
        $this->nro_despacho->setVisibility();
        $this->cerrado->Visible = false;
        $this->asesor_asignado->setVisibility();
        $this->tasa_indexada->Visible = false;
        $this->id_documento_padre_nd->Visible = false;
        $this->id_documento_padre->Visible = false;
        $this->archivo_pedido->setVisibility();
        $this->checker->setVisibility();
        $this->checker_date->setVisibility();
        $this->packer->setVisibility();
        $this->packer_date->setVisibility();
        $this->fotos->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'salidas';
        $this->TableName = 'salidas';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (salidas)
        if (!isset($GLOBALS["salidas"]) || $GLOBALS["salidas"]::class == PROJECT_NAMESPACE . "salidas") {
            $GLOBALS["salidas"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'salidas');
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
                        $result["view"] = SameString($pageName, "SalidasView"); // If View page, no primary button
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
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->documento);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->estatus);
        $this->setupLookupOptions($this->asesor);
        $this->setupLookupOptions($this->dias_credito);
        $this->setupLookupOptions($this->entregado);
        $this->setupLookupOptions($this->pagado);
        $this->setupLookupOptions($this->user_bultos);
        $this->setupLookupOptions($this->user_despacho);
        $this->setupLookupOptions($this->consignacion);
        $this->setupLookupOptions($this->factura);
        $this->setupLookupOptions($this->activo);
        $this->setupLookupOptions($this->comprobante);
        $this->setupLookupOptions($this->cerrado);
        $this->setupLookupOptions($this->asesor_asignado);
        $this->setupLookupOptions($this->id_documento_padre);

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
                    $this->terminate("SalidasList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "SalidasList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "SalidasView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "SalidasList") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "SalidasList"; // Return list page content
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
        $this->fotos->Upload->Index = $CurrentForm->Index;
        $this->fotos->Upload->uploadFile();
        $this->fotos->CurrentValue = $this->fotos->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->entregado->DefaultValue = $this->entregado->getDefault(); // PHP
        $this->entregado->OldValue = $this->entregado->DefaultValue;
        $this->pagado->DefaultValue = $this->pagado->getDefault(); // PHP
        $this->pagado->OldValue = $this->pagado->DefaultValue;
        $this->bultos->DefaultValue = $this->bultos->getDefault(); // PHP
        $this->bultos->OldValue = $this->bultos->DefaultValue;
        $this->fecha_bultos->DefaultValue = $this->fecha_bultos->getDefault(); // PHP
        $this->fecha_bultos->OldValue = $this->fecha_bultos->DefaultValue;
        $this->fecha_despacho->DefaultValue = $this->fecha_despacho->getDefault(); // PHP
        $this->fecha_despacho->OldValue = $this->fecha_despacho->DefaultValue;
        $this->consignacion->DefaultValue = $this->consignacion->getDefault(); // PHP
        $this->consignacion->OldValue = $this->consignacion->DefaultValue;
        $this->factura->DefaultValue = $this->factura->getDefault(); // PHP
        $this->factura->OldValue = $this->factura->DefaultValue;
        $this->activo->DefaultValue = $this->activo->getDefault(); // PHP
        $this->activo->OldValue = $this->activo->DefaultValue;
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

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
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

        // Check field name 'doc_afectado' first before field var 'x_doc_afectado'
        $val = $CurrentForm->hasValue("doc_afectado") ? $CurrentForm->getValue("doc_afectado") : $CurrentForm->getValue("x_doc_afectado");
        if (!$this->doc_afectado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_afectado->Visible = false; // Disable update for API request
            } else {
                $this->doc_afectado->setFormValue($val);
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

        // Check field name 'lista_pedido' first before field var 'x_lista_pedido'
        $val = $CurrentForm->hasValue("lista_pedido") ? $CurrentForm->getValue("lista_pedido") : $CurrentForm->getValue("x_lista_pedido");
        if (!$this->lista_pedido->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lista_pedido->Visible = false; // Disable update for API request
            } else {
                $this->lista_pedido->setFormValue($val);
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

        // Check field name 'dias_credito' first before field var 'x_dias_credito'
        $val = $CurrentForm->hasValue("dias_credito") ? $CurrentForm->getValue("dias_credito") : $CurrentForm->getValue("x_dias_credito");
        if (!$this->dias_credito->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->dias_credito->Visible = false; // Disable update for API request
            } else {
                $this->dias_credito->setFormValue($val);
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

        // Check field name 'nro_despacho' first before field var 'x_nro_despacho'
        $val = $CurrentForm->hasValue("nro_despacho") ? $CurrentForm->getValue("nro_despacho") : $CurrentForm->getValue("x_nro_despacho");
        if (!$this->nro_despacho->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_despacho->Visible = false; // Disable update for API request
            } else {
                $this->nro_despacho->setFormValue($val);
            }
        }

        // Check field name 'asesor_asignado' first before field var 'x_asesor_asignado'
        $val = $CurrentForm->hasValue("asesor_asignado") ? $CurrentForm->getValue("asesor_asignado") : $CurrentForm->getValue("x_asesor_asignado");
        if (!$this->asesor_asignado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asesor_asignado->Visible = false; // Disable update for API request
            } else {
                $this->asesor_asignado->setFormValue($val);
            }
        }

        // Check field name 'checker' first before field var 'x_checker'
        $val = $CurrentForm->hasValue("checker") ? $CurrentForm->getValue("checker") : $CurrentForm->getValue("x_checker");
        if (!$this->checker->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->checker->Visible = false; // Disable update for API request
            } else {
                $this->checker->setFormValue($val);
            }
        }

        // Check field name 'checker_date' first before field var 'x_checker_date'
        $val = $CurrentForm->hasValue("checker_date") ? $CurrentForm->getValue("checker_date") : $CurrentForm->getValue("x_checker_date");
        if (!$this->checker_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->checker_date->Visible = false; // Disable update for API request
            } else {
                $this->checker_date->setFormValue($val, true, $validate);
            }
            $this->checker_date->CurrentValue = UnFormatDateTime($this->checker_date->CurrentValue, $this->checker_date->formatPattern());
        }

        // Check field name 'packer' first before field var 'x_packer'
        $val = $CurrentForm->hasValue("packer") ? $CurrentForm->getValue("packer") : $CurrentForm->getValue("x_packer");
        if (!$this->packer->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->packer->Visible = false; // Disable update for API request
            } else {
                $this->packer->setFormValue($val);
            }
        }

        // Check field name 'packer_date' first before field var 'x_packer_date'
        $val = $CurrentForm->hasValue("packer_date") ? $CurrentForm->getValue("packer_date") : $CurrentForm->getValue("x_packer_date");
        if (!$this->packer_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->packer_date->Visible = false; // Disable update for API request
            } else {
                $this->packer_date->setFormValue($val, true, $validate);
            }
            $this->packer_date->CurrentValue = UnFormatDateTime($this->packer_date->CurrentValue, $this->packer_date->formatPattern());
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
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->lista_pedido->CurrentValue = $this->lista_pedido->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->dias_credito->CurrentValue = $this->dias_credito->FormValue;
        $this->consignacion->CurrentValue = $this->consignacion->FormValue;
        $this->factura->CurrentValue = $this->factura->FormValue;
        $this->nro_despacho->CurrentValue = $this->nro_despacho->FormValue;
        $this->asesor_asignado->CurrentValue = $this->asesor_asignado->FormValue;
        $this->checker->CurrentValue = $this->checker->FormValue;
        $this->checker_date->CurrentValue = $this->checker_date->FormValue;
        $this->checker_date->CurrentValue = UnFormatDateTime($this->checker_date->CurrentValue, $this->checker_date->formatPattern());
        $this->packer->CurrentValue = $this->packer->FormValue;
        $this->packer_date->CurrentValue = $this->packer_date->FormValue;
        $this->packer_date->CurrentValue = UnFormatDateTime($this->packer_date->CurrentValue, $this->packer_date->formatPattern());
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
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->cliente->setDbValue($row['cliente']);
        $this->documento->setDbValue($row['documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->moneda->setDbValue($row['moneda']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->estatus->setDbValue($row['estatus']);
        $this->asesor->setDbValue($row['asesor']);
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
        $this->unidades->setDbValue($row['unidades']);
        $this->descuento->setDbValue($row['descuento']);
        $this->monto_sin_descuento->setDbValue($row['monto_sin_descuento']);
        $this->factura->setDbValue($row['factura']);
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono->setDbValue($row['telefono']);
        $this->_email->setDbValue($row['email']);
        $this->activo->setDbValue($row['activo']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->nro_despacho->setDbValue($row['nro_despacho']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->asesor_asignado->setDbValue($row['asesor_asignado']);
        $this->tasa_indexada->setDbValue($row['tasa_indexada']);
        $this->id_documento_padre_nd->setDbValue($row['id_documento_padre_nd']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
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
        $row['nro_documento'] = $this->nro_documento->DefaultValue;
        $row['nro_control'] = $this->nro_control->DefaultValue;
        $row['fecha'] = $this->fecha->DefaultValue;
        $row['cliente'] = $this->cliente->DefaultValue;
        $row['documento'] = $this->documento->DefaultValue;
        $row['doc_afectado'] = $this->doc_afectado->DefaultValue;
        $row['moneda'] = $this->moneda->DefaultValue;
        $row['monto_total'] = $this->monto_total->DefaultValue;
        $row['alicuota_iva'] = $this->alicuota_iva->DefaultValue;
        $row['iva'] = $this->iva->DefaultValue;
        $row['total'] = $this->total->DefaultValue;
        $row['tasa_dia'] = $this->tasa_dia->DefaultValue;
        $row['monto_usd'] = $this->monto_usd->DefaultValue;
        $row['lista_pedido'] = $this->lista_pedido->DefaultValue;
        $row['nota'] = $this->nota->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['estatus'] = $this->estatus->DefaultValue;
        $row['asesor'] = $this->asesor->DefaultValue;
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
        $row['unidades'] = $this->unidades->DefaultValue;
        $row['descuento'] = $this->descuento->DefaultValue;
        $row['monto_sin_descuento'] = $this->monto_sin_descuento->DefaultValue;
        $row['factura'] = $this->factura->DefaultValue;
        $row['ci_rif'] = $this->ci_rif->DefaultValue;
        $row['nombre'] = $this->nombre->DefaultValue;
        $row['direccion'] = $this->direccion->DefaultValue;
        $row['telefono'] = $this->telefono->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        $row['comprobante'] = $this->comprobante->DefaultValue;
        $row['nro_despacho'] = $this->nro_despacho->DefaultValue;
        $row['cerrado'] = $this->cerrado->DefaultValue;
        $row['asesor_asignado'] = $this->asesor_asignado->DefaultValue;
        $row['tasa_indexada'] = $this->tasa_indexada->DefaultValue;
        $row['id_documento_padre_nd'] = $this->id_documento_padre_nd->DefaultValue;
        $row['id_documento_padre'] = $this->id_documento_padre->DefaultValue;
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

        // nro_documento
        $this->nro_documento->RowCssClass = "row";

        // nro_control
        $this->nro_control->RowCssClass = "row";

        // fecha
        $this->fecha->RowCssClass = "row";

        // cliente
        $this->cliente->RowCssClass = "row";

        // documento
        $this->documento->RowCssClass = "row";

        // doc_afectado
        $this->doc_afectado->RowCssClass = "row";

        // moneda
        $this->moneda->RowCssClass = "row";

        // monto_total
        $this->monto_total->RowCssClass = "row";

        // alicuota_iva
        $this->alicuota_iva->RowCssClass = "row";

        // iva
        $this->iva->RowCssClass = "row";

        // total
        $this->total->RowCssClass = "row";

        // tasa_dia
        $this->tasa_dia->RowCssClass = "row";

        // monto_usd
        $this->monto_usd->RowCssClass = "row";

        // lista_pedido
        $this->lista_pedido->RowCssClass = "row";

        // nota
        $this->nota->RowCssClass = "row";

        // username
        $this->_username->RowCssClass = "row";

        // estatus
        $this->estatus->RowCssClass = "row";

        // asesor
        $this->asesor->RowCssClass = "row";

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

        // unidades
        $this->unidades->RowCssClass = "row";

        // descuento
        $this->descuento->RowCssClass = "row";

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

        // id_documento_padre
        $this->id_documento_padre->RowCssClass = "row";

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

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

            // cliente
            $curVal = strval($this->cliente->CurrentValue);
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $lookupFilter = $this->cliente->getSelectFilter($this); // PHP
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
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

            // documento
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->ViewValue = null;
            }

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

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

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

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

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
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

            // unidades
            $this->unidades->ViewValue = $this->unidades->CurrentValue;

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());
            $this->descuento->CssClass = "fw-bold";

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
                        $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
                    }
                }
            } else {
                $this->id_documento_padre->ViewValue = null;
            }

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

            // fotos
            if (!EmptyValue($this->fotos->Upload->DbValue)) {
                $this->fotos->ImageWidth = 120;
                $this->fotos->ImageHeight = 120;
                $this->fotos->ImageAlt = $this->fotos->alt();
                $this->fotos->ImageCssClass = "ew-image";
                $this->fotos->ViewValue = $this->fotos->Upload->DbValue;
            } else {
                $this->fotos->ViewValue = "";
            }

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // cliente
            $this->cliente->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";

            // nota
            $this->nota->HrefValue = "";

            // dias_credito
            $this->dias_credito->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // factura
            $this->factura->HrefValue = "";

            // nro_despacho
            $this->nro_despacho->HrefValue = "";

            // asesor_asignado
            $this->asesor_asignado->HrefValue = "";

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

            // checker
            $this->checker->HrefValue = "";
            $this->checker->TooltipValue = "";

            // checker_date
            $this->checker_date->HrefValue = "";
            $this->checker_date->TooltipValue = "";

            // packer
            $this->packer->HrefValue = "";
            $this->packer->TooltipValue = "";

            // packer_date
            $this->packer_date->HrefValue = "";
            $this->packer_date->TooltipValue = "";

            // fotos
            if (!EmptyValue($this->fotos->Upload->DbValue)) {
                $this->fotos->HrefValue = "%u"; // Add prefix/suffix
                $this->fotos->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->fotos->HrefValue = FullUrl($this->fotos->HrefValue, "href");
                }
            } else {
                $this->fotos->HrefValue = "";
            }
            $this->fotos->ExportHrefValue = $this->fotos->UploadPath . $this->fotos->Upload->DbValue;
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

            // cliente
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            } else {
                $this->cliente->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->lookupOptions()) && count($this->cliente->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->cliente->ViewValue !== null) { // Load from cache
                $this->cliente->EditValue = array_values($this->cliente->lookupOptions());
                if ($this->cliente->ViewValue == "") {
                    $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->cliente->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->cliente->CurrentValue, $this->cliente->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $lookupFilter = $this->cliente->getSelectFilter($this); // PHP
                $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                } else {
                    $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cliente->EditValue = $arwrk;
            }
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

            // documento
            $this->documento->setupEditAttributes();
            $this->documento->EditValue = $this->documento->options(true);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // doc_afectado
            $this->doc_afectado->setupEditAttributes();
            if (!$this->doc_afectado->Raw) {
                $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->CurrentValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

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

            // lista_pedido
            $this->lista_pedido->setupEditAttributes();
            $curVal = trim(strval($this->lista_pedido->CurrentValue));
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            } else {
                $this->lista_pedido->ViewValue = $this->lista_pedido->Lookup !== null && is_array($this->lista_pedido->lookupOptions()) && count($this->lista_pedido->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->lista_pedido->ViewValue !== null) { // Load from cache
                $this->lista_pedido->EditValue = array_values($this->lista_pedido->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->lista_pedido->CurrentValue, $this->lista_pedido->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->lista_pedido->getSelectFilter($this); // PHP
                $sqlWrk = $this->lista_pedido->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->lista_pedido->EditValue = $arwrk;
            }
            $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

            // nota
            $this->nota->setupEditAttributes();
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

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

            // consignacion
            $this->consignacion->setupEditAttributes();
            $this->consignacion->EditValue = $this->consignacion->options(true);
            $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

            // factura
            $this->factura->setupEditAttributes();
            $this->factura->EditValue = $this->factura->options(true);
            $this->factura->PlaceHolder = RemoveHtml($this->factura->caption());

            // nro_despacho
            $this->nro_despacho->setupEditAttributes();
            if (!$this->nro_despacho->Raw) {
                $this->nro_despacho->CurrentValue = HtmlDecode($this->nro_despacho->CurrentValue);
            }
            $this->nro_despacho->EditValue = HtmlEncode($this->nro_despacho->CurrentValue);
            $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

            // asesor_asignado
            $curVal = trim(strval($this->asesor_asignado->CurrentValue));
            if ($curVal != "") {
                $this->asesor_asignado->ViewValue = $this->asesor_asignado->lookupCacheOption($curVal);
            } else {
                $this->asesor_asignado->ViewValue = $this->asesor_asignado->Lookup !== null && is_array($this->asesor_asignado->lookupOptions()) && count($this->asesor_asignado->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->asesor_asignado->ViewValue !== null) { // Load from cache
                $this->asesor_asignado->EditValue = array_values($this->asesor_asignado->lookupOptions());
                if ($this->asesor_asignado->ViewValue == "") {
                    $this->asesor_asignado->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $this->asesor_asignado->CurrentValue, $this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                }
                $lookupFilter = $this->asesor_asignado->getSelectFilter($this); // PHP
                $sqlWrk = $this->asesor_asignado->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->asesor_asignado->Lookup->renderViewRow($rswrk[0]);
                    $this->asesor_asignado->ViewValue = $this->asesor_asignado->displayValue($arwrk);
                } else {
                    $this->asesor_asignado->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->asesor_asignado->EditValue = $arwrk;
            }
            $this->asesor_asignado->PlaceHolder = RemoveHtml($this->asesor_asignado->caption());

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

            // checker
            $this->checker->setupEditAttributes();
            if (!$this->checker->Raw) {
                $this->checker->CurrentValue = HtmlDecode($this->checker->CurrentValue);
            }
            $this->checker->EditValue = HtmlEncode($this->checker->CurrentValue);
            $this->checker->PlaceHolder = RemoveHtml($this->checker->caption());

            // checker_date
            $this->checker_date->setupEditAttributes();
            $this->checker_date->EditValue = HtmlEncode(FormatDateTime($this->checker_date->CurrentValue, $this->checker_date->formatPattern()));
            $this->checker_date->PlaceHolder = RemoveHtml($this->checker_date->caption());

            // packer
            $this->packer->setupEditAttributes();
            if (!$this->packer->Raw) {
                $this->packer->CurrentValue = HtmlDecode($this->packer->CurrentValue);
            }
            $this->packer->EditValue = HtmlEncode($this->packer->CurrentValue);
            $this->packer->PlaceHolder = RemoveHtml($this->packer->caption());

            // packer_date
            $this->packer_date->setupEditAttributes();
            $this->packer_date->EditValue = HtmlEncode(FormatDateTime($this->packer_date->CurrentValue, $this->packer_date->formatPattern()));
            $this->packer_date->PlaceHolder = RemoveHtml($this->packer_date->caption());

            // fotos
            $this->fotos->setupEditAttributes();
            if (!EmptyValue($this->fotos->Upload->DbValue)) {
                $this->fotos->ImageWidth = 120;
                $this->fotos->ImageHeight = 120;
                $this->fotos->ImageAlt = $this->fotos->alt();
                $this->fotos->ImageCssClass = "ew-image";
                $this->fotos->EditValue = $this->fotos->Upload->DbValue;
            } else {
                $this->fotos->EditValue = "";
            }
            if (!EmptyValue($this->fotos->CurrentValue)) {
                $this->fotos->Upload->FileName = $this->fotos->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->fotos->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->fotos);
            }

            // Add refer script

            // tipo_documento
            $this->tipo_documento->HrefValue = "";

            // cliente
            $this->cliente->HrefValue = "";

            // documento
            $this->documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->HrefValue = "";

            // moneda
            $this->moneda->HrefValue = "";

            // lista_pedido
            $this->lista_pedido->HrefValue = "";

            // nota
            $this->nota->HrefValue = "";

            // dias_credito
            $this->dias_credito->HrefValue = "";

            // consignacion
            $this->consignacion->HrefValue = "";

            // factura
            $this->factura->HrefValue = "";

            // nro_despacho
            $this->nro_despacho->HrefValue = "";

            // asesor_asignado
            $this->asesor_asignado->HrefValue = "";

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

            // checker
            $this->checker->HrefValue = "";

            // checker_date
            $this->checker_date->HrefValue = "";

            // packer
            $this->packer->HrefValue = "";

            // packer_date
            $this->packer_date->HrefValue = "";

            // fotos
            if (!EmptyValue($this->fotos->Upload->DbValue)) {
                $this->fotos->HrefValue = "%u"; // Add prefix/suffix
                $this->fotos->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->fotos->HrefValue = FullUrl($this->fotos->HrefValue, "href");
                }
            } else {
                $this->fotos->HrefValue = "";
            }
            $this->fotos->ExportHrefValue = $this->fotos->UploadPath . $this->fotos->Upload->DbValue;
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
            if ($this->cliente->Visible && $this->cliente->Required) {
                if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                    $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
                }
            }
            if ($this->documento->Visible && $this->documento->Required) {
                if (!$this->documento->IsDetailKey && EmptyValue($this->documento->FormValue)) {
                    $this->documento->addErrorMessage(str_replace("%s", $this->documento->caption(), $this->documento->RequiredErrorMessage));
                }
            }
            if ($this->doc_afectado->Visible && $this->doc_afectado->Required) {
                if (!$this->doc_afectado->IsDetailKey && EmptyValue($this->doc_afectado->FormValue)) {
                    $this->doc_afectado->addErrorMessage(str_replace("%s", $this->doc_afectado->caption(), $this->doc_afectado->RequiredErrorMessage));
                }
            }
            if ($this->moneda->Visible && $this->moneda->Required) {
                if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                    $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
                }
            }
            if ($this->lista_pedido->Visible && $this->lista_pedido->Required) {
                if (!$this->lista_pedido->IsDetailKey && EmptyValue($this->lista_pedido->FormValue)) {
                    $this->lista_pedido->addErrorMessage(str_replace("%s", $this->lista_pedido->caption(), $this->lista_pedido->RequiredErrorMessage));
                }
            }
            if ($this->nota->Visible && $this->nota->Required) {
                if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                    $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
                }
            }
            if ($this->dias_credito->Visible && $this->dias_credito->Required) {
                if (!$this->dias_credito->IsDetailKey && EmptyValue($this->dias_credito->FormValue)) {
                    $this->dias_credito->addErrorMessage(str_replace("%s", $this->dias_credito->caption(), $this->dias_credito->RequiredErrorMessage));
                }
            }
            if ($this->consignacion->Visible && $this->consignacion->Required) {
                if (!$this->consignacion->IsDetailKey && EmptyValue($this->consignacion->FormValue)) {
                    $this->consignacion->addErrorMessage(str_replace("%s", $this->consignacion->caption(), $this->consignacion->RequiredErrorMessage));
                }
            }
            if ($this->factura->Visible && $this->factura->Required) {
                if (!$this->factura->IsDetailKey && EmptyValue($this->factura->FormValue)) {
                    $this->factura->addErrorMessage(str_replace("%s", $this->factura->caption(), $this->factura->RequiredErrorMessage));
                }
            }
            if ($this->nro_despacho->Visible && $this->nro_despacho->Required) {
                if (!$this->nro_despacho->IsDetailKey && EmptyValue($this->nro_despacho->FormValue)) {
                    $this->nro_despacho->addErrorMessage(str_replace("%s", $this->nro_despacho->caption(), $this->nro_despacho->RequiredErrorMessage));
                }
            }
            if ($this->asesor_asignado->Visible && $this->asesor_asignado->Required) {
                if (!$this->asesor_asignado->IsDetailKey && EmptyValue($this->asesor_asignado->FormValue)) {
                    $this->asesor_asignado->addErrorMessage(str_replace("%s", $this->asesor_asignado->caption(), $this->asesor_asignado->RequiredErrorMessage));
                }
            }
            if ($this->archivo_pedido->Visible && $this->archivo_pedido->Required) {
                if ($this->archivo_pedido->Upload->FileName == "" && !$this->archivo_pedido->Upload->KeepFile) {
                    $this->archivo_pedido->addErrorMessage(str_replace("%s", $this->archivo_pedido->caption(), $this->archivo_pedido->RequiredErrorMessage));
                }
            }
            if ($this->checker->Visible && $this->checker->Required) {
                if (!$this->checker->IsDetailKey && EmptyValue($this->checker->FormValue)) {
                    $this->checker->addErrorMessage(str_replace("%s", $this->checker->caption(), $this->checker->RequiredErrorMessage));
                }
            }
            if ($this->checker_date->Visible && $this->checker_date->Required) {
                if (!$this->checker_date->IsDetailKey && EmptyValue($this->checker_date->FormValue)) {
                    $this->checker_date->addErrorMessage(str_replace("%s", $this->checker_date->caption(), $this->checker_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->checker_date->FormValue, $this->checker_date->formatPattern())) {
                $this->checker_date->addErrorMessage($this->checker_date->getErrorMessage(false));
            }
            if ($this->packer->Visible && $this->packer->Required) {
                if (!$this->packer->IsDetailKey && EmptyValue($this->packer->FormValue)) {
                    $this->packer->addErrorMessage(str_replace("%s", $this->packer->caption(), $this->packer->RequiredErrorMessage));
                }
            }
            if ($this->packer_date->Visible && $this->packer_date->Required) {
                if (!$this->packer_date->IsDetailKey && EmptyValue($this->packer_date->FormValue)) {
                    $this->packer_date->addErrorMessage(str_replace("%s", $this->packer_date->caption(), $this->packer_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->packer_date->FormValue, $this->packer_date->formatPattern())) {
                $this->packer_date->addErrorMessage($this->packer_date->getErrorMessage(false));
            }
            if ($this->fotos->Visible && $this->fotos->Required) {
                if ($this->fotos->Upload->FileName == "" && !$this->fotos->Upload->KeepFile) {
                    $this->fotos->addErrorMessage(str_replace("%s", $this->fotos->caption(), $this->fotos->RequiredErrorMessage));
                }
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("EntradasSalidasGrid");
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("PagosGrid");
        if (in_array("pagos", $detailTblVar) && $detailPage->DetailAdd) {
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
        if ($this->fotos->Visible && !$this->fotos->Upload->KeepFile) {
            if (!EmptyValue($this->fotos->Upload->FileName)) {
                $this->fotos->Upload->DbValue = null;
                FixUploadFileNames($this->fotos);
                $this->fotos->setDbValueDef($rsnew, $this->fotos->Upload->FileName, false);
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
                if ($this->fotos->Visible && !$this->fotos->Upload->KeepFile) {
                    $this->fotos->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->fotos, $rsnew['fotos'], false)) {
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
            $detailPage = Container("PagosGrid");
            if (in_array("pagos", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->id_documento->setSessionValue($this->id->CurrentValue); // Set master key
                $detailPage->tipo_documento->setSessionValue($this->tipo_documento->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "pagos"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->id_documento->setSessionValue(""); // Clear master key if insert failed
                $detailPage->tipo_documento->setSessionValue(""); // Clear master key if insert failed
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

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, false);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, false);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, false);

        // lista_pedido
        $this->lista_pedido->setDbValueDef($rsnew, $this->lista_pedido->CurrentValue, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, false);

        // dias_credito
        $this->dias_credito->setDbValueDef($rsnew, $this->dias_credito->CurrentValue, false);

        // consignacion
        $this->consignacion->setDbValueDef($rsnew, $this->consignacion->CurrentValue, strval($this->consignacion->CurrentValue) == "");

        // factura
        $this->factura->setDbValueDef($rsnew, $this->factura->CurrentValue, strval($this->factura->CurrentValue) == "");

        // nro_despacho
        $this->nro_despacho->setDbValueDef($rsnew, $this->nro_despacho->CurrentValue, false);

        // asesor_asignado
        $this->asesor_asignado->setDbValueDef($rsnew, $this->asesor_asignado->CurrentValue, false);

        // archivo_pedido
        if ($this->archivo_pedido->Visible && !$this->archivo_pedido->Upload->KeepFile) {
            if ($this->archivo_pedido->Upload->FileName == "") {
                $rsnew['archivo_pedido'] = null;
            } else {
                FixUploadTempFileNames($this->archivo_pedido);
                $rsnew['archivo_pedido'] = $this->archivo_pedido->Upload->FileName;
            }
        }

        // checker
        $this->checker->setDbValueDef($rsnew, $this->checker->CurrentValue, false);

        // checker_date
        $this->checker_date->setDbValueDef($rsnew, UnFormatDateTime($this->checker_date->CurrentValue, $this->checker_date->formatPattern()), false);

        // packer
        $this->packer->setDbValueDef($rsnew, $this->packer->CurrentValue, false);

        // packer_date
        $this->packer_date->setDbValueDef($rsnew, UnFormatDateTime($this->packer_date->CurrentValue, $this->packer_date->formatPattern()), false);

        // fotos
        if ($this->fotos->Visible && !$this->fotos->Upload->KeepFile) {
            if ($this->fotos->Upload->FileName == "") {
                $rsnew['fotos'] = null;
            } else {
                FixUploadTempFileNames($this->fotos);
                $rsnew['fotos'] = $this->fotos->Upload->FileName;
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
        if (isset($row['cliente'])) { // cliente
            $this->cliente->setFormValue($row['cliente']);
        }
        if (isset($row['documento'])) { // documento
            $this->documento->setFormValue($row['documento']);
        }
        if (isset($row['doc_afectado'])) { // doc_afectado
            $this->doc_afectado->setFormValue($row['doc_afectado']);
        }
        if (isset($row['moneda'])) { // moneda
            $this->moneda->setFormValue($row['moneda']);
        }
        if (isset($row['lista_pedido'])) { // lista_pedido
            $this->lista_pedido->setFormValue($row['lista_pedido']);
        }
        if (isset($row['nota'])) { // nota
            $this->nota->setFormValue($row['nota']);
        }
        if (isset($row['dias_credito'])) { // dias_credito
            $this->dias_credito->setFormValue($row['dias_credito']);
        }
        if (isset($row['consignacion'])) { // consignacion
            $this->consignacion->setFormValue($row['consignacion']);
        }
        if (isset($row['factura'])) { // factura
            $this->factura->setFormValue($row['factura']);
        }
        if (isset($row['nro_despacho'])) { // nro_despacho
            $this->nro_despacho->setFormValue($row['nro_despacho']);
        }
        if (isset($row['asesor_asignado'])) { // asesor_asignado
            $this->asesor_asignado->setFormValue($row['asesor_asignado']);
        }
        if (isset($row['archivo_pedido'])) { // archivo_pedido
            $this->archivo_pedido->setFormValue($row['archivo_pedido']);
        }
        if (isset($row['checker'])) { // checker
            $this->checker->setFormValue($row['checker']);
        }
        if (isset($row['checker_date'])) { // checker_date
            $this->checker_date->setFormValue($row['checker_date']);
        }
        if (isset($row['packer'])) { // packer
            $this->packer->setFormValue($row['packer']);
        }
        if (isset($row['packer_date'])) { // packer_date
            $this->packer_date->setFormValue($row['packer_date']);
        }
        if (isset($row['fotos'])) { // fotos
            $this->fotos->setFormValue($row['fotos']);
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
            if (in_array("pagos", $detailTblVar)) {
                $detailPageObj = Container("PagosGrid");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SalidasList"), "", $this->TableVar, true);
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
        $pages->add(5);
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
                case "x_cliente":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_documento":
                    break;
                case "x_moneda":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_lista_pedido":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x__username":
                    break;
                case "x_estatus":
                    break;
                case "x_asesor":
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
                case "x_comprobante":
                    break;
                case "x_cerrado":
                    break;
                case "x_asesor_asignado":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_id_documento_padre":
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
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
        if($tipo == "TDCFCV") {    
            if(VerificaFuncion("021")) {
            	$url = "SalidasList?tipo=TDCFCV";
            }
        }
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
    	$this->descuento->Visible = FALSE;
    	$this->factura->Visible = FALSE;
    	$this->nombre->Visible = FALSE;
    	$this->nro_despacho->Visible = FALSE;
    	$this->dias_credito->Visible = FALSE;
    	$this->archivo_pedido->Visible = FALSE;
    	//$this->asesor_asignado->Visible = FALSE;
    	$this->fotos->Visible = FALSE;
    	$this->checker->Visible = FALSE;
    	$this->checker_date->Visible = FALSE;
    	$this->packer->Visible = FALSE;
    	$this->packer_date->Visible = FALSE;
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDV":
    		/////////////////
    		$sql = "SELECT IFNULL(asesor, 0) AS asesor FROM usuario WHERE username = '" . CurrentUserName() . "';";
    		$asesor = intval(ExecuteScalar($sql));
    		if($asesor > 0) $this->asesor_asignado->Visible = FALSE;
    		/////////////////
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = CurrentUserLevel() == -1 ? TRUE : FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = TRUE;
    		$this->dias_credito->Visible = TRUE;
    		$this->archivo_pedido->Visible = TRUE;
    		break;
    	case "TDCNET":
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->fotos->Visible = TRUE;
    		break;
    	case "TDCFCV":
    		$this->descuento->Visible = TRUE;
    		$this->consignacion->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->nro_despacho->Visible = TRUE;
    		$this->dias_credito->Visible = TRUE;
    		$this->archivo_pedido->Visible = TRUE;
    		break;
    	case "TDCASA":
    		$this->factura->Visible = TRUE;
    		$this->nombre->Visible = TRUE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		break;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
    	//$header = "your header";
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCNET":
    		$this->setTableCaption("Crear nota de entrega desde"); 
    		$header = '<a href="ViewSalidasList?crear=TDCNET" class="btn btn-primary">
    				Pedido de Venta o Factura
    			</a>';
    		break;
    	case "TDCFCV":
    		$this->setTableCaption("Crear factura desde"); 
    		$header = '<a href="ViewSalidasList?crear=TDCFCV&consig=0" class="btn btn-primary">
    				 Nota de Entrega
    			</a>&nbsp;&nbsp;';
    		$header .= '<a href="ViewSalidasList?crear=TDCFCV&consig=1" class="btn btn-primary">
    				Nota de Entrega a Consignaci&oacute;n
    			</a>&nbsp;&nbsp;';
    		$header .= '<a href="ClienteConsignacionLista" class="btn btn-primary">
    				Clientes a Consignaci&oacute;n
    			</a>';
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
