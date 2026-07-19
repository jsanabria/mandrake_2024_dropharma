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
class ProveedorAddopt extends Proveedor
{
    use MessagesTrait;

    // Page ID
    public $PageID = "addopt";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ProveedorAddopt";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "ProveedorAddopt";

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
        $this->ciudad->Visible = false;
        $this->direccion->setVisibility();
        $this->telefono1->setVisibility();
        $this->telefono2->Visible = false;
        $this->email1->Visible = false;
        $this->email2->Visible = false;
        $this->fabricante->Visible = false;
        $this->cuenta_auxiliar->Visible = false;
        $this->cuenta_gasto->Visible = false;
        $this->tipo_ret_iva->setVisibility();
        $this->tipo_ret_islr_concepto->setVisibility();
        $this->tipo_ret_islr->setVisibility();
        $this->tipo_ret_mun->setVisibility();
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->tipo_impmun->Visible = false;
        $this->cta_bco->setVisibility();
        $this->activo->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'proveedor';
        $this->TableName = 'proveedor';

        // Table CSS class
        $this->TableClass = "table table-striped table-sm ew-view-table";

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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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
    public $IsModal = false;
    public $IsMobileOrModal = true; // Add option page is always modal

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm;

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
        $this->setupLookupOptions($this->tipo_ret_iva);
        $this->setupLookupOptions($this->tipo_ret_islr_concepto);
        $this->setupLookupOptions($this->tipo_ret_islr);
        $this->setupLookupOptions($this->tipo_ret_mun);
        $this->setupLookupOptions($this->tipo_iva);
        $this->setupLookupOptions($this->tipo_islr);
        $this->setupLookupOptions($this->sustraendo);
        $this->setupLookupOptions($this->tipo_impmun);
        $this->setupLookupOptions($this->activo);

        // Load default values for add
        $this->loadDefaultValues();

        // Set up Breadcrumb
        // $this->setupBreadcrumb(); // Not used
        $this->loadRowValues(); // Load default values

        // Render row
        $this->RowType = RowType::ADD; // Render add type
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
        $this->activo->DefaultValue = $this->activo->getDefault(); // PHP
        $this->activo->OldValue = $this->activo->DefaultValue;
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
            $this->ci_rif->setFormValue($val);
        }

        // Check field name 'nombre' first before field var 'x_nombre'
        $val = $CurrentForm->hasValue("nombre") ? $CurrentForm->getValue("nombre") : $CurrentForm->getValue("x_nombre");
        if (!$this->nombre->IsDetailKey) {
            $this->nombre->setFormValue($val);
        }

        // Check field name 'direccion' first before field var 'x_direccion'
        $val = $CurrentForm->hasValue("direccion") ? $CurrentForm->getValue("direccion") : $CurrentForm->getValue("x_direccion");
        if (!$this->direccion->IsDetailKey) {
            $this->direccion->setFormValue($val);
        }

        // Check field name 'telefono1' first before field var 'x_telefono1'
        $val = $CurrentForm->hasValue("telefono1") ? $CurrentForm->getValue("telefono1") : $CurrentForm->getValue("x_telefono1");
        if (!$this->telefono1->IsDetailKey) {
            $this->telefono1->setFormValue($val);
        }

        // Check field name 'tipo_ret_iva' first before field var 'x_tipo_ret_iva'
        $val = $CurrentForm->hasValue("tipo_ret_iva") ? $CurrentForm->getValue("tipo_ret_iva") : $CurrentForm->getValue("x_tipo_ret_iva");
        if (!$this->tipo_ret_iva->IsDetailKey) {
            $this->tipo_ret_iva->setFormValue($val);
        }

        // Check field name 'tipo_ret_islr_concepto' first before field var 'x_tipo_ret_islr_concepto'
        $val = $CurrentForm->hasValue("tipo_ret_islr_concepto") ? $CurrentForm->getValue("tipo_ret_islr_concepto") : $CurrentForm->getValue("x_tipo_ret_islr_concepto");
        if (!$this->tipo_ret_islr_concepto->IsDetailKey) {
            $this->tipo_ret_islr_concepto->setFormValue($val);
        }

        // Check field name 'tipo_ret_islr' first before field var 'x_tipo_ret_islr'
        $val = $CurrentForm->hasValue("tipo_ret_islr") ? $CurrentForm->getValue("tipo_ret_islr") : $CurrentForm->getValue("x_tipo_ret_islr");
        if (!$this->tipo_ret_islr->IsDetailKey) {
            $this->tipo_ret_islr->setFormValue($val);
        }

        // Check field name 'tipo_ret_mun' first before field var 'x_tipo_ret_mun'
        $val = $CurrentForm->hasValue("tipo_ret_mun") ? $CurrentForm->getValue("tipo_ret_mun") : $CurrentForm->getValue("x_tipo_ret_mun");
        if (!$this->tipo_ret_mun->IsDetailKey) {
            $this->tipo_ret_mun->setFormValue($val);
        }

        // Check field name 'cta_bco' first before field var 'x_cta_bco'
        $val = $CurrentForm->hasValue("cta_bco") ? $CurrentForm->getValue("cta_bco") : $CurrentForm->getValue("x_cta_bco");
        if (!$this->cta_bco->IsDetailKey) {
            $this->cta_bco->setFormValue($val);
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ci_rif->CurrentValue = ConvertToUtf8($this->ci_rif->FormValue);
        $this->nombre->CurrentValue = ConvertToUtf8($this->nombre->FormValue);
        $this->direccion->CurrentValue = ConvertToUtf8($this->direccion->FormValue);
        $this->telefono1->CurrentValue = ConvertToUtf8($this->telefono1->FormValue);
        $this->tipo_ret_iva->CurrentValue = ConvertToUtf8($this->tipo_ret_iva->FormValue);
        $this->tipo_ret_islr_concepto->CurrentValue = ConvertToUtf8($this->tipo_ret_islr_concepto->FormValue);
        $this->tipo_ret_islr->CurrentValue = ConvertToUtf8($this->tipo_ret_islr->FormValue);
        $this->tipo_ret_mun->CurrentValue = ConvertToUtf8($this->tipo_ret_mun->FormValue);
        $this->cta_bco->CurrentValue = ConvertToUtf8($this->cta_bco->FormValue);
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
        $this->fabricante->setDbValue($row['fabricante']);
        $this->cuenta_auxiliar->setDbValue($row['cuenta_auxiliar']);
        $this->cuenta_gasto->setDbValue($row['cuenta_gasto']);
        $this->tipo_ret_iva->setDbValue($row['tipo_ret_iva']);
        $this->tipo_ret_islr_concepto->setDbValue($row['tipo_ret_islr_concepto']);
        $this->tipo_ret_islr->setDbValue($row['tipo_ret_islr']);
        $this->tipo_ret_mun->setDbValue($row['tipo_ret_mun']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_impmun->setDbValue($row['tipo_impmun']);
        $this->cta_bco->setDbValue($row['cta_bco']);
        $this->activo->setDbValue($row['activo']);
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
        $row['fabricante'] = $this->fabricante->DefaultValue;
        $row['cuenta_auxiliar'] = $this->cuenta_auxiliar->DefaultValue;
        $row['cuenta_gasto'] = $this->cuenta_gasto->DefaultValue;
        $row['tipo_ret_iva'] = $this->tipo_ret_iva->DefaultValue;
        $row['tipo_ret_islr_concepto'] = $this->tipo_ret_islr_concepto->DefaultValue;
        $row['tipo_ret_islr'] = $this->tipo_ret_islr->DefaultValue;
        $row['tipo_ret_mun'] = $this->tipo_ret_mun->DefaultValue;
        $row['tipo_iva'] = $this->tipo_iva->DefaultValue;
        $row['tipo_islr'] = $this->tipo_islr->DefaultValue;
        $row['sustraendo'] = $this->sustraendo->DefaultValue;
        $row['tipo_impmun'] = $this->tipo_impmun->DefaultValue;
        $row['cta_bco'] = $this->cta_bco->DefaultValue;
        $row['activo'] = $this->activo->DefaultValue;
        return $row;
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

        // fabricante
        $this->fabricante->RowCssClass = "row";

        // cuenta_auxiliar
        $this->cuenta_auxiliar->RowCssClass = "row";

        // cuenta_gasto
        $this->cuenta_gasto->RowCssClass = "row";

        // tipo_ret_iva
        $this->tipo_ret_iva->RowCssClass = "row";

        // tipo_ret_islr_concepto
        $this->tipo_ret_islr_concepto->RowCssClass = "row";

        // tipo_ret_islr
        $this->tipo_ret_islr->RowCssClass = "row";

        // tipo_ret_mun
        $this->tipo_ret_mun->RowCssClass = "row";

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

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
            $this->fabricante->ViewValue = FormatNumber($this->fabricante->ViewValue, $this->fabricante->formatPattern());

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

            // tipo_ret_iva
            $curVal = strval($this->tipo_ret_iva->CurrentValue);
            if ($curVal != "") {
                $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->lookupCacheOption($curVal);
                if ($this->tipo_ret_iva->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_ret_iva->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_ret_iva->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_ret_iva->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_ret_iva->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_iva->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_iva->ViewValue = null;
            }

            // tipo_ret_islr_concepto
            $curVal = strval($this->tipo_ret_islr_concepto->CurrentValue);
            if ($curVal != "") {
                $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->lookupCacheOption($curVal);
                if ($this->tipo_ret_islr_concepto->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_ret_islr_concepto->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_ret_islr_concepto->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_ret_islr_concepto->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_ret_islr_concepto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_islr_concepto->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_islr_concepto->ViewValue = null;
            }

            // tipo_ret_islr
            $curVal = strval($this->tipo_ret_islr->CurrentValue);
            if ($curVal != "") {
                $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->lookupCacheOption($curVal);
                if ($this->tipo_ret_islr->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_ret_islr->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->tipo_ret_islr->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                    $sqlWrk = $this->tipo_ret_islr->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_islr->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_islr->ViewValue = FormatNumber($this->tipo_ret_islr->CurrentValue, $this->tipo_ret_islr->formatPattern());
                    }
                }
            } else {
                $this->tipo_ret_islr->ViewValue = null;
            }

            // tipo_ret_mun
            $curVal = strval($this->tipo_ret_mun->CurrentValue);
            if ($curVal != "") {
                $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->lookupCacheOption($curVal);
                if ($this->tipo_ret_mun->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->tipo_ret_mun->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_ret_mun->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                    $lookupFilter = $this->tipo_ret_mun->getSelectFilter($this); // PHP
                    $sqlWrk = $this->tipo_ret_mun->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_mun->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_mun->ViewValue = null;
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

            // ci_rif
            $this->ci_rif->HrefValue = "";
            $this->ci_rif->TooltipValue = "";

            // nombre
            $this->nombre->HrefValue = "";
            $this->nombre->TooltipValue = "";

            // direccion
            $this->direccion->HrefValue = "";
            $this->direccion->TooltipValue = "";

            // telefono1
            $this->telefono1->HrefValue = "";
            $this->telefono1->TooltipValue = "";

            // tipo_ret_iva
            $this->tipo_ret_iva->HrefValue = "";
            $this->tipo_ret_iva->TooltipValue = "";

            // tipo_ret_islr_concepto
            $this->tipo_ret_islr_concepto->HrefValue = "";
            $this->tipo_ret_islr_concepto->TooltipValue = "";

            // tipo_ret_islr
            $this->tipo_ret_islr->HrefValue = "";
            $this->tipo_ret_islr->TooltipValue = "";

            // tipo_ret_mun
            $this->tipo_ret_mun->HrefValue = "";
            $this->tipo_ret_mun->TooltipValue = "";

            // cta_bco
            $this->cta_bco->HrefValue = "";
            $this->cta_bco->TooltipValue = "";
        } elseif ($this->RowType == RowType::ADD) {
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
            $this->direccion->EditValue = HtmlEncode($this->direccion->CurrentValue);
            $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

            // telefono1
            $this->telefono1->setupEditAttributes();
            if (!$this->telefono1->Raw) {
                $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
            }
            $this->telefono1->EditValue = HtmlEncode($this->telefono1->CurrentValue);
            $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

            // tipo_ret_iva
            $this->tipo_ret_iva->setupEditAttributes();
            $curVal = trim(strval($this->tipo_ret_iva->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->lookupCacheOption($curVal);
            } else {
                $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->Lookup !== null && is_array($this->tipo_ret_iva->lookupOptions()) && count($this->tipo_ret_iva->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_ret_iva->ViewValue !== null) { // Load from cache
                $this->tipo_ret_iva->EditValue = array_values($this->tipo_ret_iva->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_ret_iva->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->tipo_ret_iva->CurrentValue, $this->tipo_ret_iva->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_ret_iva->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_ret_iva->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_ret_iva->EditValue = $arwrk;
            }
            $this->tipo_ret_iva->PlaceHolder = RemoveHtml($this->tipo_ret_iva->caption());

            // tipo_ret_islr_concepto
            $curVal = trim(strval($this->tipo_ret_islr_concepto->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->lookupCacheOption($curVal);
            } else {
                $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->Lookup !== null && is_array($this->tipo_ret_islr_concepto->lookupOptions()) && count($this->tipo_ret_islr_concepto->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_ret_islr_concepto->ViewValue !== null) { // Load from cache
                $this->tipo_ret_islr_concepto->EditValue = array_values($this->tipo_ret_islr_concepto->lookupOptions());
                if ($this->tipo_ret_islr_concepto->ViewValue == "") {
                    $this->tipo_ret_islr_concepto->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_ret_islr_concepto->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->tipo_ret_islr_concepto->CurrentValue, $this->tipo_ret_islr_concepto->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_ret_islr_concepto->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_ret_islr_concepto->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_ret_islr_concepto->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->displayValue($arwrk);
                } else {
                    $this->tipo_ret_islr_concepto->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->tipo_ret_islr_concepto->EditValue = $arwrk;
            }
            $this->tipo_ret_islr_concepto->PlaceHolder = RemoveHtml($this->tipo_ret_islr_concepto->caption());

            // tipo_ret_islr
            $this->tipo_ret_islr->setupEditAttributes();
            $curVal = trim(strval($this->tipo_ret_islr->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->lookupCacheOption($curVal);
            } else {
                $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->Lookup !== null && is_array($this->tipo_ret_islr->lookupOptions()) && count($this->tipo_ret_islr->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_ret_islr->ViewValue !== null) { // Load from cache
                $this->tipo_ret_islr->EditValue = array_values($this->tipo_ret_islr->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_ret_islr->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $this->tipo_ret_islr->CurrentValue, $this->tipo_ret_islr->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                }
                $sqlWrk = $this->tipo_ret_islr->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row) {
                    $row = $this->tipo_ret_islr->Lookup->renderViewRow($row);
                }
                $this->tipo_ret_islr->EditValue = $arwrk;
            }
            $this->tipo_ret_islr->PlaceHolder = RemoveHtml($this->tipo_ret_islr->caption());

            // tipo_ret_mun
            $this->tipo_ret_mun->setupEditAttributes();
            $curVal = trim(strval($this->tipo_ret_mun->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->lookupCacheOption($curVal);
            } else {
                $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->Lookup !== null && is_array($this->tipo_ret_mun->lookupOptions()) && count($this->tipo_ret_mun->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->tipo_ret_mun->ViewValue !== null) { // Load from cache
                $this->tipo_ret_mun->EditValue = array_values($this->tipo_ret_mun->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->tipo_ret_mun->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $this->tipo_ret_mun->CurrentValue, $this->tipo_ret_mun->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                }
                $lookupFilter = $this->tipo_ret_mun->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_ret_mun->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_ret_mun->EditValue = $arwrk;
            }
            $this->tipo_ret_mun->PlaceHolder = RemoveHtml($this->tipo_ret_mun->caption());

            // cta_bco
            $this->cta_bco->setupEditAttributes();
            if (!$this->cta_bco->Raw) {
                $this->cta_bco->CurrentValue = HtmlDecode($this->cta_bco->CurrentValue);
            }
            $this->cta_bco->EditValue = HtmlEncode($this->cta_bco->CurrentValue);
            $this->cta_bco->PlaceHolder = RemoveHtml($this->cta_bco->caption());

            // Add refer script

            // ci_rif
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->HrefValue = "";

            // direccion
            $this->direccion->HrefValue = "";

            // telefono1
            $this->telefono1->HrefValue = "";

            // tipo_ret_iva
            $this->tipo_ret_iva->HrefValue = "";

            // tipo_ret_islr_concepto
            $this->tipo_ret_islr_concepto->HrefValue = "";

            // tipo_ret_islr
            $this->tipo_ret_islr->HrefValue = "";

            // tipo_ret_mun
            $this->tipo_ret_mun->HrefValue = "";

            // cta_bco
            $this->cta_bco->HrefValue = "";
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
            if ($this->tipo_ret_iva->Visible && $this->tipo_ret_iva->Required) {
                if (!$this->tipo_ret_iva->IsDetailKey && EmptyValue($this->tipo_ret_iva->FormValue)) {
                    $this->tipo_ret_iva->addErrorMessage(str_replace("%s", $this->tipo_ret_iva->caption(), $this->tipo_ret_iva->RequiredErrorMessage));
                }
            }
            if ($this->tipo_ret_islr_concepto->Visible && $this->tipo_ret_islr_concepto->Required) {
                if (!$this->tipo_ret_islr_concepto->IsDetailKey && EmptyValue($this->tipo_ret_islr_concepto->FormValue)) {
                    $this->tipo_ret_islr_concepto->addErrorMessage(str_replace("%s", $this->tipo_ret_islr_concepto->caption(), $this->tipo_ret_islr_concepto->RequiredErrorMessage));
                }
            }
            if ($this->tipo_ret_islr->Visible && $this->tipo_ret_islr->Required) {
                if (!$this->tipo_ret_islr->IsDetailKey && EmptyValue($this->tipo_ret_islr->FormValue)) {
                    $this->tipo_ret_islr->addErrorMessage(str_replace("%s", $this->tipo_ret_islr->caption(), $this->tipo_ret_islr->RequiredErrorMessage));
                }
            }
            if ($this->tipo_ret_mun->Visible && $this->tipo_ret_mun->Required) {
                if (!$this->tipo_ret_mun->IsDetailKey && EmptyValue($this->tipo_ret_mun->FormValue)) {
                    $this->tipo_ret_mun->addErrorMessage(str_replace("%s", $this->tipo_ret_mun->caption(), $this->tipo_ret_mun->RequiredErrorMessage));
                }
            }
            if ($this->cta_bco->Visible && $this->cta_bco->Required) {
                if (!$this->cta_bco->IsDetailKey && EmptyValue($this->cta_bco->FormValue)) {
                    $this->cta_bco->addErrorMessage(str_replace("%s", $this->cta_bco->caption(), $this->cta_bco->RequiredErrorMessage));
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ProveedorList"), "", $this->TableVar, true);
        $pageId = "addopt";
        $Breadcrumb->add("addopt", $pageId, $url);
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
                case "x_tipo_ret_iva":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_ret_islr_concepto":
                    $lookupFilter = $fld->getSelectFilter(); // PHP
                    break;
                case "x_tipo_ret_islr":
                    break;
                case "x_tipo_ret_mun":
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
}
