<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class RecargaAdd extends Recarga
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'recarga';

    // Page object name
    public $PageObjName = "RecargaAdd";

    // Rendering View
    public $RenderingView = false;

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
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (recarga)
        if (!isset($GLOBALS["recarga"]) || get_class($GLOBALS["recarga"]) == PROJECT_NAMESPACE . "recarga") {
            $GLOBALS["recarga"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'recarga');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
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
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("recarga"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
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
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "RecargaView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
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
                            if ($fld->DataType == DATATYPE_BLOB) {
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
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
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
        $lookup->toJson($this); // Use settings from current page
    }
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->Visible = false;
        $this->cliente->setVisibility();
        $this->fecha->Visible = false;
        $this->monto_moneda->setVisibility();
        $this->moneda->setVisibility();
        $this->tasa_moneda->Visible = false;
        $this->monto_bs->Visible = false;
        $this->tasa_usd->Visible = false;
        $this->monto_usd->Visible = false;
        $this->saldo->Visible = false;
        $this->nota->setVisibility();
        $this->_username->Visible = false;
        $this->metodo_pago->setVisibility();
        $this->referencia->setVisibility();
        $this->reverso->setVisibility();
        $this->cobro_cliente_reverso->setVisibility();
        $this->nro_recibo->setVisibility();
        $this->nota_recepcion->setVisibility();
        $this->abono->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->_username);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form ew-horizontal";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
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
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Set up master/detail parameters
        // NOTE: must be after loadOldRecord to prevent master key values overwritten
        $this->setupMasterParms();

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
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("RecargaList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "RecargaList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "RecargaView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
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
        if ($this->isConfirm()) { // Confirm page
            $this->RowType = ROWTYPE_VIEW; // Render view type
        } else {
            $this->RowType = ROWTYPE_ADD; // Render add type
        }

        // Render row
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Visible", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
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
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->cliente->CurrentValue = null;
        $this->cliente->OldValue = $this->cliente->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->monto_moneda->CurrentValue = null;
        $this->monto_moneda->OldValue = $this->monto_moneda->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->tasa_moneda->CurrentValue = null;
        $this->tasa_moneda->OldValue = $this->tasa_moneda->CurrentValue;
        $this->monto_bs->CurrentValue = null;
        $this->monto_bs->OldValue = $this->monto_bs->CurrentValue;
        $this->tasa_usd->CurrentValue = null;
        $this->tasa_usd->OldValue = $this->tasa_usd->CurrentValue;
        $this->monto_usd->CurrentValue = null;
        $this->monto_usd->OldValue = $this->monto_usd->CurrentValue;
        $this->saldo->CurrentValue = null;
        $this->saldo->OldValue = $this->saldo->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->metodo_pago->CurrentValue = null;
        $this->metodo_pago->OldValue = $this->metodo_pago->CurrentValue;
        $this->referencia->CurrentValue = null;
        $this->referencia->OldValue = $this->referencia->CurrentValue;
        $this->reverso->CurrentValue = "N";
        $this->cobro_cliente_reverso->CurrentValue = 0;
        $this->nro_recibo->CurrentValue = 0;
        $this->nota_recepcion->CurrentValue = null;
        $this->nota_recepcion->OldValue = $this->nota_recepcion->CurrentValue;
        $this->abono->CurrentValue = 0;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
            }
        }

        // Check field name 'monto_moneda' first before field var 'x_monto_moneda'
        $val = $CurrentForm->hasValue("monto_moneda") ? $CurrentForm->getValue("monto_moneda") : $CurrentForm->getValue("x_monto_moneda");
        if (!$this->monto_moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_moneda->Visible = false; // Disable update for API request
            } else {
                $this->monto_moneda->setFormValue($val);
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

        // Check field name 'nota' first before field var 'x_nota'
        $val = $CurrentForm->hasValue("nota") ? $CurrentForm->getValue("nota") : $CurrentForm->getValue("x_nota");
        if (!$this->nota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nota->Visible = false; // Disable update for API request
            } else {
                $this->nota->setFormValue($val);
            }
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

        // Check field name 'reverso' first before field var 'x_reverso'
        $val = $CurrentForm->hasValue("reverso") ? $CurrentForm->getValue("reverso") : $CurrentForm->getValue("x_reverso");
        if (!$this->reverso->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reverso->Visible = false; // Disable update for API request
            } else {
                $this->reverso->setFormValue($val);
            }
        }

        // Check field name 'cobro_cliente_reverso' first before field var 'x_cobro_cliente_reverso'
        $val = $CurrentForm->hasValue("cobro_cliente_reverso") ? $CurrentForm->getValue("cobro_cliente_reverso") : $CurrentForm->getValue("x_cobro_cliente_reverso");
        if (!$this->cobro_cliente_reverso->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cobro_cliente_reverso->Visible = false; // Disable update for API request
            } else {
                $this->cobro_cliente_reverso->setFormValue($val);
            }
        }

        // Check field name 'nro_recibo' first before field var 'x_nro_recibo'
        $val = $CurrentForm->hasValue("nro_recibo") ? $CurrentForm->getValue("nro_recibo") : $CurrentForm->getValue("x_nro_recibo");
        if (!$this->nro_recibo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_recibo->Visible = false; // Disable update for API request
            } else {
                $this->nro_recibo->setFormValue($val);
            }
        }

        // Check field name 'nota_recepcion' first before field var 'x_nota_recepcion'
        $val = $CurrentForm->hasValue("nota_recepcion") ? $CurrentForm->getValue("nota_recepcion") : $CurrentForm->getValue("x_nota_recepcion");
        if (!$this->nota_recepcion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nota_recepcion->Visible = false; // Disable update for API request
            } else {
                $this->nota_recepcion->setFormValue($val);
            }
        }

        // Check field name 'abono' first before field var 'x_abono'
        $val = $CurrentForm->hasValue("abono") ? $CurrentForm->getValue("abono") : $CurrentForm->getValue("x_abono");
        if (!$this->abono->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->abono->Visible = false; // Disable update for API request
            } else {
                $this->abono->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->monto_moneda->CurrentValue = $this->monto_moneda->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->metodo_pago->CurrentValue = $this->metodo_pago->FormValue;
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->reverso->CurrentValue = $this->reverso->FormValue;
        $this->cobro_cliente_reverso->CurrentValue = $this->cobro_cliente_reverso->FormValue;
        $this->nro_recibo->CurrentValue = $this->nro_recibo->FormValue;
        $this->nota_recepcion->CurrentValue = $this->nota_recepcion->FormValue;
        $this->abono->CurrentValue = $this->abono->FormValue;
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
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->id->setDbValue($row['id']);
        $this->cliente->setDbValue($row['cliente']);
        $this->fecha->setDbValue($row['fecha']);
        $this->monto_moneda->setDbValue($row['monto_moneda']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_moneda->setDbValue($row['tasa_moneda']);
        $this->monto_bs->setDbValue($row['monto_bs']);
        $this->tasa_usd->setDbValue($row['tasa_usd']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->saldo->setDbValue($row['saldo']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->metodo_pago->setDbValue($row['metodo_pago']);
        $this->referencia->setDbValue($row['referencia']);
        $this->reverso->setDbValue($row['reverso']);
        $this->cobro_cliente_reverso->setDbValue($row['cobro_cliente_reverso']);
        $this->nro_recibo->setDbValue($row['nro_recibo']);
        $this->nota_recepcion->setDbValue($row['nota_recepcion']);
        $this->abono->setDbValue($row['abono']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['cliente'] = $this->cliente->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['monto_moneda'] = $this->monto_moneda->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['tasa_moneda'] = $this->tasa_moneda->CurrentValue;
        $row['monto_bs'] = $this->monto_bs->CurrentValue;
        $row['tasa_usd'] = $this->tasa_usd->CurrentValue;
        $row['monto_usd'] = $this->monto_usd->CurrentValue;
        $row['saldo'] = $this->saldo->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['metodo_pago'] = $this->metodo_pago->CurrentValue;
        $row['referencia'] = $this->referencia->CurrentValue;
        $row['reverso'] = $this->reverso->CurrentValue;
        $row['cobro_cliente_reverso'] = $this->cobro_cliente_reverso->CurrentValue;
        $row['nro_recibo'] = $this->nro_recibo->CurrentValue;
        $row['nota_recepcion'] = $this->nota_recepcion->CurrentValue;
        $row['abono'] = $this->abono->CurrentValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Convert decimal values if posted back
        if ($this->monto_moneda->FormValue == $this->monto_moneda->CurrentValue && is_numeric(ConvertToFloatString($this->monto_moneda->CurrentValue))) {
            $this->monto_moneda->CurrentValue = ConvertToFloatString($this->monto_moneda->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // cliente

        // fecha

        // monto_moneda

        // moneda

        // tasa_moneda

        // monto_bs

        // tasa_usd

        // monto_usd

        // saldo

        // nota

        // username

        // metodo_pago

        // referencia

        // reverso

        // cobro_cliente_reverso

        // nro_recibo

        // nota_recepcion

        // abono
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // cliente
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
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
            $this->cliente->ViewCustomAttributes = "";

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // monto_moneda
            $this->monto_moneda->ViewValue = $this->monto_moneda->CurrentValue;
            $this->monto_moneda->ViewValue = FormatNumber($this->monto_moneda->ViewValue, 2, -1, 0, -1);
            $this->monto_moneda->ViewCustomAttributes = "";

            // moneda
            $curVal = trim(strval($this->moneda->CurrentValue));
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
                if ($this->moneda->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->moneda->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
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
            $this->moneda->ViewCustomAttributes = "";

            // tasa_moneda
            $this->tasa_moneda->ViewValue = $this->tasa_moneda->CurrentValue;
            $this->tasa_moneda->ViewValue = FormatNumber($this->tasa_moneda->ViewValue, 2, -2, -2, -2);
            $this->tasa_moneda->ViewCustomAttributes = "";

            // monto_bs
            $this->monto_bs->ViewValue = $this->monto_bs->CurrentValue;
            $this->monto_bs->ViewValue = FormatNumber($this->monto_bs->ViewValue, 2, -2, -2, -2);
            $this->monto_bs->ViewCustomAttributes = "";

            // tasa_usd
            $this->tasa_usd->ViewValue = $this->tasa_usd->CurrentValue;
            $this->tasa_usd->ViewValue = FormatNumber($this->tasa_usd->ViewValue, 2, -2, -2, -2);
            $this->tasa_usd->ViewCustomAttributes = "";

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, 2, -1, 0, -1);
            $this->monto_usd->CssClass = "font-weight-bold";
            $this->monto_usd->ViewCustomAttributes = "";

            // saldo
            $this->saldo->ViewValue = $this->saldo->CurrentValue;
            $this->saldo->ViewValue = FormatNumber($this->saldo->ViewValue, 2, -1, -1, -1);
            $this->saldo->CssClass = "font-weight-bold font-italic";
            $this->saldo->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;
            $curVal = trim(strval($this->_username->CurrentValue));
            if ($curVal != "") {
                $this->_username->ViewValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->ViewValue === null) { // Lookup from database
                    $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
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
            $this->_username->ViewCustomAttributes = "";

            // metodo_pago
            $this->metodo_pago->ViewValue = $this->metodo_pago->CurrentValue;
            $this->metodo_pago->ViewCustomAttributes = "";

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;
            $this->referencia->ViewCustomAttributes = "";

            // reverso
            if (strval($this->reverso->CurrentValue) != "") {
                $this->reverso->ViewValue = $this->reverso->optionCaption($this->reverso->CurrentValue);
            } else {
                $this->reverso->ViewValue = null;
            }
            $this->reverso->ViewCustomAttributes = "";

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->ViewValue = $this->cobro_cliente_reverso->CurrentValue;
            $this->cobro_cliente_reverso->ViewValue = FormatNumber($this->cobro_cliente_reverso->ViewValue, 0, -2, -2, -2);
            $this->cobro_cliente_reverso->ViewCustomAttributes = "";

            // nro_recibo
            $this->nro_recibo->ViewValue = $this->nro_recibo->CurrentValue;
            $this->nro_recibo->ViewValue = FormatNumber($this->nro_recibo->ViewValue, 0, -2, -2, -2);
            $this->nro_recibo->ViewCustomAttributes = "";

            // nota_recepcion
            $this->nota_recepcion->ViewValue = $this->nota_recepcion->CurrentValue;
            $this->nota_recepcion->ViewValue = FormatNumber($this->nota_recepcion->ViewValue, 0, -2, -2, -2);
            $this->nota_recepcion->ViewCustomAttributes = "";

            // abono
            $this->abono->ViewValue = $this->abono->CurrentValue;
            $this->abono->ViewValue = FormatNumber($this->abono->ViewValue, 0, -2, -2, -2);
            $this->abono->ViewCustomAttributes = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // monto_moneda
            $this->monto_moneda->LinkCustomAttributes = "";
            $this->monto_moneda->HrefValue = "";
            $this->monto_moneda->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // metodo_pago
            $this->metodo_pago->LinkCustomAttributes = "";
            $this->metodo_pago->HrefValue = "";
            $this->metodo_pago->TooltipValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";
            $this->referencia->TooltipValue = "";

            // reverso
            $this->reverso->LinkCustomAttributes = "";
            $this->reverso->HrefValue = "";
            $this->reverso->TooltipValue = "";

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->LinkCustomAttributes = "";
            $this->cobro_cliente_reverso->HrefValue = "";
            $this->cobro_cliente_reverso->TooltipValue = "";

            // nro_recibo
            $this->nro_recibo->LinkCustomAttributes = "";
            $this->nro_recibo->HrefValue = "";
            $this->nro_recibo->TooltipValue = "";

            // nota_recepcion
            $this->nota_recepcion->LinkCustomAttributes = "";
            $this->nota_recepcion->HrefValue = "";
            $this->nota_recepcion->TooltipValue = "";

            // abono
            $this->abono->LinkCustomAttributes = "";
            $this->abono->HrefValue = "";
            $this->abono->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // cliente
            $this->cliente->EditCustomAttributes = "";
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            } else {
                $this->cliente->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->Lookup->Options) ? $curVal : null;
            }
            if ($this->cliente->ViewValue !== null) { // Load from cache
                $this->cliente->EditValue = array_values($this->cliente->Lookup->Options);
                if ($this->cliente->ViewValue == "") {
                    $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id`" . SearchString("=", $this->cliente->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
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

            // monto_moneda
            $this->monto_moneda->EditAttrs["class"] = "form-control";
            $this->monto_moneda->EditCustomAttributes = "";
            $this->monto_moneda->EditValue = HtmlEncode($this->monto_moneda->CurrentValue);
            $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());
            if (strval($this->monto_moneda->EditValue) != "" && is_numeric($this->monto_moneda->EditValue)) {
                $this->monto_moneda->EditValue = FormatNumber($this->monto_moneda->EditValue, -2, -1, -2, -1);
            }

            // moneda
            $this->moneda->EditAttrs["class"] = "form-control";
            $this->moneda->EditCustomAttributes = "";
            $curVal = trim(strval($this->moneda->CurrentValue));
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
            } else {
                $this->moneda->ViewValue = $this->moneda->Lookup !== null && is_array($this->moneda->Lookup->Options) ? $curVal : null;
            }
            if ($this->moneda->ViewValue !== null) { // Load from cache
                $this->moneda->EditValue = array_values($this->moneda->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->moneda->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`codigo` = '006'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->moneda->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->moneda->EditValue = $arwrk;
            }
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // metodo_pago
            $this->metodo_pago->EditAttrs["class"] = "form-control";
            $this->metodo_pago->EditCustomAttributes = "";
            if (!$this->metodo_pago->Raw) {
                $this->metodo_pago->CurrentValue = HtmlDecode($this->metodo_pago->CurrentValue);
            }
            $this->metodo_pago->EditValue = HtmlEncode($this->metodo_pago->CurrentValue);
            $this->metodo_pago->PlaceHolder = RemoveHtml($this->metodo_pago->caption());

            // referencia
            $this->referencia->EditAttrs["class"] = "form-control";
            $this->referencia->EditCustomAttributes = "";
            if (!$this->referencia->Raw) {
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // reverso
            $this->reverso->EditCustomAttributes = "";
            $this->reverso->EditValue = $this->reverso->options(false);
            $this->reverso->PlaceHolder = RemoveHtml($this->reverso->caption());

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->EditAttrs["class"] = "form-control";
            $this->cobro_cliente_reverso->EditCustomAttributes = "";
            $this->cobro_cliente_reverso->EditValue = HtmlEncode($this->cobro_cliente_reverso->CurrentValue);
            $this->cobro_cliente_reverso->PlaceHolder = RemoveHtml($this->cobro_cliente_reverso->caption());

            // nro_recibo
            $this->nro_recibo->EditAttrs["class"] = "form-control";
            $this->nro_recibo->EditCustomAttributes = "";
            $this->nro_recibo->EditValue = HtmlEncode($this->nro_recibo->CurrentValue);
            $this->nro_recibo->PlaceHolder = RemoveHtml($this->nro_recibo->caption());

            // nota_recepcion
            $this->nota_recepcion->EditAttrs["class"] = "form-control";
            $this->nota_recepcion->EditCustomAttributes = "";
            $this->nota_recepcion->EditValue = HtmlEncode($this->nota_recepcion->CurrentValue);
            $this->nota_recepcion->PlaceHolder = RemoveHtml($this->nota_recepcion->caption());

            // abono
            $this->abono->EditAttrs["class"] = "form-control";
            $this->abono->EditCustomAttributes = "";
            if ($this->abono->getSessionValue() != "") {
                $this->abono->CurrentValue = GetForeignKeyValue($this->abono->getSessionValue());
                $this->abono->ViewValue = $this->abono->CurrentValue;
                $this->abono->ViewValue = FormatNumber($this->abono->ViewValue, 0, -2, -2, -2);
                $this->abono->ViewCustomAttributes = "";
            } else {
                $this->abono->EditValue = HtmlEncode($this->abono->CurrentValue);
                $this->abono->PlaceHolder = RemoveHtml($this->abono->caption());
            }

            // Add refer script

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // monto_moneda
            $this->monto_moneda->LinkCustomAttributes = "";
            $this->monto_moneda->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // metodo_pago
            $this->metodo_pago->LinkCustomAttributes = "";
            $this->metodo_pago->HrefValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";

            // reverso
            $this->reverso->LinkCustomAttributes = "";
            $this->reverso->HrefValue = "";

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->LinkCustomAttributes = "";
            $this->cobro_cliente_reverso->HrefValue = "";

            // nro_recibo
            $this->nro_recibo->LinkCustomAttributes = "";
            $this->nro_recibo->HrefValue = "";

            // nota_recepcion
            $this->nota_recepcion->LinkCustomAttributes = "";
            $this->nota_recepcion->HrefValue = "";

            // abono
            $this->abono->LinkCustomAttributes = "";
            $this->abono->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->cliente->Required) {
            if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
            }
        }
        if ($this->monto_moneda->Required) {
            if (!$this->monto_moneda->IsDetailKey && EmptyValue($this->monto_moneda->FormValue)) {
                $this->monto_moneda->addErrorMessage(str_replace("%s", $this->monto_moneda->caption(), $this->monto_moneda->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_moneda->FormValue)) {
            $this->monto_moneda->addErrorMessage($this->monto_moneda->getErrorMessage(false));
        }
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->metodo_pago->Required) {
            if (!$this->metodo_pago->IsDetailKey && EmptyValue($this->metodo_pago->FormValue)) {
                $this->metodo_pago->addErrorMessage(str_replace("%s", $this->metodo_pago->caption(), $this->metodo_pago->RequiredErrorMessage));
            }
        }
        if ($this->referencia->Required) {
            if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
            }
        }
        if ($this->reverso->Required) {
            if ($this->reverso->FormValue == "") {
                $this->reverso->addErrorMessage(str_replace("%s", $this->reverso->caption(), $this->reverso->RequiredErrorMessage));
            }
        }
        if ($this->cobro_cliente_reverso->Required) {
            if (!$this->cobro_cliente_reverso->IsDetailKey && EmptyValue($this->cobro_cliente_reverso->FormValue)) {
                $this->cobro_cliente_reverso->addErrorMessage(str_replace("%s", $this->cobro_cliente_reverso->caption(), $this->cobro_cliente_reverso->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->cobro_cliente_reverso->FormValue)) {
            $this->cobro_cliente_reverso->addErrorMessage($this->cobro_cliente_reverso->getErrorMessage(false));
        }
        if ($this->nro_recibo->Required) {
            if (!$this->nro_recibo->IsDetailKey && EmptyValue($this->nro_recibo->FormValue)) {
                $this->nro_recibo->addErrorMessage(str_replace("%s", $this->nro_recibo->caption(), $this->nro_recibo->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->nro_recibo->FormValue)) {
            $this->nro_recibo->addErrorMessage($this->nro_recibo->getErrorMessage(false));
        }
        if ($this->nota_recepcion->Required) {
            if (!$this->nota_recepcion->IsDetailKey && EmptyValue($this->nota_recepcion->FormValue)) {
                $this->nota_recepcion->addErrorMessage(str_replace("%s", $this->nota_recepcion->caption(), $this->nota_recepcion->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->nota_recepcion->FormValue)) {
            $this->nota_recepcion->addErrorMessage($this->nota_recepcion->getErrorMessage(false));
        }
        if ($this->abono->Required) {
            if (!$this->abono->IsDetailKey && EmptyValue($this->abono->FormValue)) {
                $this->abono->addErrorMessage(str_replace("%s", $this->abono->caption(), $this->abono->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->abono->FormValue)) {
            $this->abono->addErrorMessage($this->abono->getErrorMessage(false));
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

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
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, false);

        // monto_moneda
        $this->monto_moneda->setDbValueDef($rsnew, $this->monto_moneda->CurrentValue, null, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // metodo_pago
        $this->metodo_pago->setDbValueDef($rsnew, $this->metodo_pago->CurrentValue, null, false);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, null, false);

        // reverso
        $this->reverso->setDbValueDef($rsnew, $this->reverso->CurrentValue, null, strval($this->reverso->CurrentValue) == "");

        // cobro_cliente_reverso
        $this->cobro_cliente_reverso->setDbValueDef($rsnew, $this->cobro_cliente_reverso->CurrentValue, 0, strval($this->cobro_cliente_reverso->CurrentValue) == "");

        // nro_recibo
        $this->nro_recibo->setDbValueDef($rsnew, $this->nro_recibo->CurrentValue, 0, strval($this->nro_recibo->CurrentValue) == "");

        // nota_recepcion
        $this->nota_recepcion->setDbValueDef($rsnew, $this->nota_recepcion->CurrentValue, null, false);

        // abono
        $this->abono->setDbValueDef($rsnew, $this->abono->CurrentValue, 0, strval($this->abono->CurrentValue) == "");

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        $addRow = false;
        if ($insertRow) {
            try {
                $addRow = $this->insert($rsnew);
            } catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());
            }
            if ($addRow) {
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

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "abono") {
                $validMaster = true;
                $masterTbl = Container("abono");
                if (($parm = Get("fk_id", Get("abono"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->abono->setQueryStringValue($masterTbl->id->QueryStringValue);
                    $this->abono->setSessionValue($this->abono->QueryStringValue);
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "abono") {
                $validMaster = true;
                $masterTbl = Container("abono");
                if (($parm = Post("fk_id", Post("abono"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->abono->setFormValue($masterTbl->id->FormValue);
                    $this->abono->setSessionValue($this->abono->FormValue);
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "abono") {
                if ($this->abono->CurrentValue == "") {
                    $this->abono->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("RecargaList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_cliente":
                    break;
                case "x_moneda":
                    $lookupFilter = function () {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x__username":
                    break;
                case "x_reverso":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
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
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
