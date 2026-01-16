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
 * Table class for pagos
 */
class Pagos extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Audit trail
    public $AuditTrailOnAdd = true;
    public $AuditTrailOnEdit = true;
    public $AuditTrailOnDelete = true;
    public $AuditTrailOnView = false;
    public $AuditTrailOnViewData = false;
    public $AuditTrailOnSearch = false;

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $id;
    public $id_documento;
    public $tipo_documento;
    public $tipo_pago;
    public $fecha;
    public $banco;
    public $banco_destino;
    public $referencia;
    public $moneda;
    public $monto;
    public $nota;
    public $comprobante_pago;
    public $comprobante;
    public $_username;
    public $fecha_resgistro;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "pagos";
        $this->TableName = 'pagos';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "pagos";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "null"; // Page orientation (PDF only)
        $this->ExportPageSize = ""; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = true; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // id
        $this->id = new DbField(
            $this, // Table
            'x_id', // Variable name
            'id', // Name
            '`id`', // Expression
            '`id`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->id->InputTextType = "text";
        $this->id->Raw = true;
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Nullable = false; // NOT NULL field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id'] = &$this->id;

        // id_documento
        $this->id_documento = new DbField(
            $this, // Table
            'x_id_documento', // Variable name
            'id_documento', // Name
            '`id_documento`', // Expression
            '`id_documento`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id_documento->InputTextType = "text";
        $this->id_documento->Raw = true;
        $this->id_documento->IsForeignKey = true; // Foreign key field
        $this->id_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_documento'] = &$this->id_documento;

        // tipo_documento
        $this->tipo_documento = new DbField(
            $this, // Table
            'x_tipo_documento', // Variable name
            'tipo_documento', // Name
            '`tipo_documento`', // Expression
            '`tipo_documento`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tipo_documento->InputTextType = "text";
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // tipo_pago
        $this->tipo_pago = new DbField(
            $this, // Table
            'x_tipo_pago', // Variable name
            'tipo_pago', // Name
            '`tipo_pago`', // Expression
            '`tipo_pago`', // Basic search expression
            129, // Type
            2, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_pago`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tipo_pago->addMethod("getSelectFilter", fn() => "`codigo` = '009'");
        $this->tipo_pago->InputTextType = "text";
        $this->tipo_pago->Required = true; // Required field
        $this->tipo_pago->setSelectMultiple(false); // Select one
        $this->tipo_pago->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_pago->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_pago->Lookup = new Lookup($this->tipo_pago, 'parametro', false, 'valor1', ["valor2","","",""], '', '', [], [], [], [], [], [], false, '', '', "`valor2`");
        $this->tipo_pago->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_pago'] = &$this->tipo_pago;

        // fecha
        $this->fecha = new DbField(
            $this, // Table
            'x_fecha', // Variable name
            'fecha', // Name
            '`fecha`', // Expression
            CastDateFieldForLike("`fecha`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha->InputTextType = "text";
        $this->fecha->Raw = true;
        $this->fecha->Required = true; // Required field
        $this->fecha->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha'] = &$this->fecha;

        // banco
        $this->banco = new DbField(
            $this, // Table
            'x_banco', // Variable name
            'banco', // Name
            '`banco`', // Expression
            '`banco`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`banco`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->banco->addMethod("getSelectFilter", fn() => "`tabla` = 'BANCO'");
        $this->banco->InputTextType = "text";
        $this->banco->setSelectMultiple(false); // Select one
        $this->banco->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->banco->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->banco->Lookup = new Lookup($this->banco, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion`', '', "`campo_descripcion`");
        $this->banco->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['banco'] = &$this->banco;

        // banco_destino
        $this->banco_destino = new DbField(
            $this, // Table
            'x_banco_destino', // Variable name
            'banco_destino', // Name
            '`banco_destino`', // Expression
            '`banco_destino`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`banco_destino`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->banco_destino->InputTextType = "text";
        $this->banco_destino->Raw = true;
        $this->banco_destino->setSelectMultiple(false); // Select one
        $this->banco_destino->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->banco_destino->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->banco_destino->Lookup = new Lookup($this->banco_destino, 'view_banco', false, 'id', ["banco","numero","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`banco`, ''),'" . ValueSeparator(1, $this->banco_destino) . "',COALESCE(`numero`,''))");
        $this->banco_destino->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->banco_destino->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['banco_destino'] = &$this->banco_destino;

        // referencia
        $this->referencia = new DbField(
            $this, // Table
            'x_referencia', // Variable name
            'referencia', // Name
            '`referencia`', // Expression
            '`referencia`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`referencia`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->referencia->InputTextType = "text";
        $this->referencia->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['referencia'] = &$this->referencia;

        // moneda
        $this->moneda = new DbField(
            $this, // Table
            'x_moneda', // Variable name
            'moneda', // Name
            '`moneda`', // Expression
            '`moneda`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`moneda`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->moneda->addMethod("getSelectFilter", fn() => "`codigo` = '006' AND valor1 <> 'EURO'");
        $this->moneda->InputTextType = "text";
        $this->moneda->Required = true; // Required field
        $this->moneda->setSelectMultiple(false); // Select one
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup($this->moneda, 'parametro', false, 'valor1', ["valor1","","",""], '', '', [], [], [], [], [], [], false, '`valor1` ASC', '', "`valor1`");
        $this->moneda->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['moneda'] = &$this->moneda;

        // monto
        $this->monto = new DbField(
            $this, // Table
            'x_monto', // Variable name
            'monto', // Name
            '`monto`', // Expression
            '`monto`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto->InputTextType = "text";
        $this->monto->Raw = true;
        $this->monto->Required = true; // Required field
        $this->monto->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto'] = &$this->monto;

        // nota
        $this->nota = new DbField(
            $this, // Table
            'x_nota', // Variable name
            'nota', // Name
            '`nota`', // Expression
            '`nota`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nota`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nota->InputTextType = "text";
        $this->nota->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nota'] = &$this->nota;

        // comprobante_pago
        $this->comprobante_pago = new DbField(
            $this, // Table
            'x_comprobante_pago', // Variable name
            'comprobante_pago', // Name
            '`comprobante_pago`', // Expression
            '`comprobante_pago`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`comprobante_pago`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->comprobante_pago->InputTextType = "text";
        $this->comprobante_pago->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['comprobante_pago'] = &$this->comprobante_pago;

        // comprobante
        $this->comprobante = new DbField(
            $this, // Table
            'x_comprobante', // Variable name
            'comprobante', // Name
            '`comprobante`', // Expression
            '`comprobante`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`comprobante`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->comprobante->InputTextType = "text";
        $this->comprobante->Raw = true;
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['comprobante'] = &$this->comprobante;

        // username
        $this->_username = new DbField(
            $this, // Table
            'x__username', // Variable name
            'username', // Name
            '`username`', // Expression
            '`username`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`username`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->_username->InputTextType = "text";
        $this->_username->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['username'] = &$this->_username;

        // fecha_resgistro
        $this->fecha_resgistro = new DbField(
            $this, // Table
            'x_fecha_resgistro', // Variable name
            'fecha_resgistro', // Name
            '`fecha_resgistro`', // Expression
            CastDateFieldForLike("`fecha_resgistro`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
            false, // Is upload field
            '`fecha_resgistro`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_resgistro->InputTextType = "text";
        $this->fecha_resgistro->Raw = true;
        $this->fecha_resgistro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_resgistro->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_resgistro'] = &$this->fecha_resgistro;

        // Add Doctrine Cache
        $this->Cache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Get master WHERE clause from session values
    public function getMasterFilterFromSession()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "salidas") {
            $masterTable = Container("salidas");
            if ($this->id_documento->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->id, $this->id_documento->getSessionValue(), $masterTable->id->DataType, $masterTable->Dbid);
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $masterFilter .= " AND " . GetKeyFilter($masterTable->tipo_documento, $this->tipo_documento->getSessionValue(), $masterTable->tipo_documento->DataType, $masterTable->Dbid);
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "view_facturas_cobranza") {
            $masterTable = Container("view_facturas_cobranza");
            if ($this->id_documento->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->id, $this->id_documento->getSessionValue(), $masterTable->id->DataType, $masterTable->Dbid);
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $masterFilter .= " AND " . GetKeyFilter($masterTable->tipo_documento, $this->tipo_documento->getSessionValue(), $masterTable->tipo_documento->DataType, $masterTable->Dbid);
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Get detail WHERE clause from session values
    public function getDetailFilterFromSession()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "salidas") {
            $masterTable = Container("salidas");
            if ($this->id_documento->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->id_documento, $this->id_documento->getSessionValue(), $masterTable->id->DataType, $this->Dbid);
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $detailFilter .= " AND " . GetKeyFilter($this->tipo_documento, $this->tipo_documento->getSessionValue(), $masterTable->tipo_documento->DataType, $this->Dbid);
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "view_facturas_cobranza") {
            $masterTable = Container("view_facturas_cobranza");
            if ($this->id_documento->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->id_documento, $this->id_documento->getSessionValue(), $masterTable->id->DataType, $this->Dbid);
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $detailFilter .= " AND " . GetKeyFilter($this->tipo_documento, $this->tipo_documento->getSessionValue(), $masterTable->tipo_documento->DataType, $this->Dbid);
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    /**
     * Get master filter
     *
     * @param object $masterTable Master Table
     * @param array $keys Detail Keys
     * @return mixed NULL is returned if all keys are empty, Empty string is returned if some keys are empty and is required
     */
    public function getMasterFilter($masterTable, $keys)
    {
        $validKeys = true;
        switch ($masterTable->TableVar) {
            case "salidas":
                $key = $keys["id_documento"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->id->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                $key = $keys["tipo_documento"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->tipo_documento->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->id, $keys["id_documento"], $this->id_documento->DataType, $this->Dbid) . " AND " . GetKeyFilter($masterTable->tipo_documento, $keys["tipo_documento"], $this->tipo_documento->DataType, $this->Dbid);
                }
                break;
            case "view_facturas_cobranza":
                $key = $keys["id_documento"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->id->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                $key = $keys["tipo_documento"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->tipo_documento->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->id, $keys["id_documento"], $this->id_documento->DataType, $this->Dbid) . " AND " . GetKeyFilter($masterTable->tipo_documento, $keys["tipo_documento"], $this->tipo_documento->DataType, $this->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "salidas":
                return GetKeyFilter($this->id_documento, $masterTable->id->DbValue, $masterTable->id->DataType, $masterTable->Dbid) . " AND " . GetKeyFilter($this->tipo_documento, $masterTable->tipo_documento->DbValue, $masterTable->tipo_documento->DataType, $masterTable->Dbid);
            case "view_facturas_cobranza":
                return GetKeyFilter($this->id_documento, $masterTable->id->DbValue, $masterTable->id->DataType, $masterTable->Dbid) . " AND " . GetKeyFilter($this->tipo_documento, $masterTable->tipo_documento->DbValue, $masterTable->tipo_documento->DataType, $masterTable->Dbid);
        }
        return "";
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "pagos";
    }

    // Get FROM clause (for backward compatibility)
    public function sqlFrom()
    {
        return $this->getSqlFrom();
    }

    // Set FROM clause
    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    // Get SELECT clause
    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select($this->sqlSelectFields());
    }

    // Get list of fields
    private function sqlSelectFields()
    {
        $useFieldNames = false;
        $fieldNames = [];
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($this->Fields as $field) {
            $expr = $field->Expression;
            $customExpr = $field->CustomDataType?->convertToPHPValueSQL($expr, $platform) ?? $expr;
            if ($customExpr != $expr) {
                $fieldNames[] = $customExpr . " AS " . QuotedName($field->Name, $this->Dbid);
                $useFieldNames = true;
            } else {
                $fieldNames[] = $expr;
            }
        }
        return $useFieldNames ? implode(", ", $fieldNames) : "*";
    }

    // Get SELECT clause (for backward compatibility)
    public function sqlSelect()
    {
        return $this->getSqlSelect();
    }

    // Set SELECT clause
    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    // Get WHERE clause
    public function getSqlWhere()
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    // Get WHERE clause (for backward compatibility)
    public function sqlWhere()
    {
        return $this->getSqlWhere();
    }

    // Set WHERE clause
    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    // Get GROUP BY clause
    public function getSqlGroupBy()
    {
        return $this->SqlGroupBy != "" ? $this->SqlGroupBy : "";
    }

    // Get GROUP BY clause (for backward compatibility)
    public function sqlGroupBy()
    {
        return $this->getSqlGroupBy();
    }

    // set GROUP BY clause
    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    // Get HAVING clause
    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    // Get HAVING clause (for backward compatibility)
    public function sqlHaving()
    {
        return $this->getSqlHaving();
    }

    // Set HAVING clause
    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    // Get ORDER BY clause
    public function getSqlOrderBy()
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    // Get ORDER BY clause (for backward compatibility)
    public function sqlOrderBy()
    {
        return $this->getSqlOrderBy();
    }

    // set ORDER BY clause
    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return ($allow & Allow::ADD->value) == Allow::ADD->value;
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return ($allow & Allow::EDIT->value) == Allow::EDIT->value;
            case "delete":
                return ($allow & Allow::DELETE->value) == Allow::DELETE->value;
            case "view":
                return ($allow & Allow::VIEW->value) == Allow::VIEW->value;
            case "search":
                return ($allow & Allow::SEARCH->value) == Allow::SEARCH->value;
            case "lookup":
                return ($allow & Allow::LOOKUP->value) == Allow::LOOKUP->value;
            default:
                return ($allow & Allow::LIST->value) == Allow::LIST->value;
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $sqlwrk = $sql instanceof QueryBuilder // Query builder
            ? (clone $sql)->resetQueryPart("orderBy")->getSQL()
            : $sql;
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            in_array($this->TableType, ["TABLE", "VIEW", "LINKTABLE"]) &&
            preg_match($pattern, $sqlwrk) &&
            !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*SELECT\s+DISTINCT\s+/i', $sqlwrk) &&
            !preg_match('/\s+ORDER\s+BY\s+/i', $sqlwrk)
        ) {
            $sqlcnt = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlcnt = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlcnt);
        if ($cnt !== false) {
            return (int)$cnt;
        }
        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        $result = $conn->executeQuery($sqlwrk);
        $cnt = $result->rowCount();
        if ($cnt == 0) { // Unable to get record count, count directly
            while ($result->fetch()) {
                $cnt++;
            }
        }
        return $cnt;
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $isCustomView = $this->TableType == "CUSTOMVIEW";
        $select = $isCustomView ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $isCustomView ? $this->getSqlGroupBy() : "";
        $having = $isCustomView ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->setValue($field->Expression, $parm);
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $queryBuilder = $this->insertSql($rs);
            $result = $queryBuilder->executeStatement();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $result = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($result) {
            $this->id->setDbValue($conn->lastInsertId());
            $rs['id'] = $this->id->DbValue;
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailOnAdd($rs);
            }
        }
        return $result;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        $platform = $this->getConnection()->getDatabasePlatform();
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $field = $this->Fields[$name];
            $parm = $queryBuilder->createPositionalParameter($value, $field->getParameterType());
            $parm = $field->CustomDataType?->convertToDatabaseValueSQL($parm, $platform) ?? $parm; // Convert database SQL
            $queryBuilder->set($field->Expression, $parm);
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->executeStatement();
            $success = $success > 0 ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['id']) && !EmptyValue($this->id->CurrentValue)) {
                $rs['id'] = $this->id->CurrentValue;
            }
        }
        if ($success && $this->AuditTrailOnEdit && $rsold) {
            $rsaudit = $rs;
            $fldname = 'id';
            if (!array_key_exists($fldname, $rsaudit)) {
                $rsaudit[$fldname] = $rsold[$fldname];
            }
            $this->writeAuditTrailOnEdit($rsold, $rsaudit);
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('id', $rs)) {
                AddFilter($where, QuotedName('id', $this->Dbid) . '=' . QuotedValue($rs['id'], $this->id->DataType, $this->Dbid));
            }
        }
        $filter = $curfilter ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->executeStatement();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        if ($success && $this->AuditTrailOnDelete) {
            $this->writeAuditTrailOnDelete($rs);
        }
        return $success;
    }

    // Load DbValue from result set or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->id->DbValue = $row['id'];
        $this->id_documento->DbValue = $row['id_documento'];
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->tipo_pago->DbValue = $row['tipo_pago'];
        $this->fecha->DbValue = $row['fecha'];
        $this->banco->DbValue = $row['banco'];
        $this->banco_destino->DbValue = $row['banco_destino'];
        $this->referencia->DbValue = $row['referencia'];
        $this->moneda->DbValue = $row['moneda'];
        $this->monto->DbValue = $row['monto'];
        $this->nota->DbValue = $row['nota'];
        $this->comprobante_pago->Upload->DbValue = $row['comprobante_pago'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->_username->DbValue = $row['username'];
        $this->fecha_resgistro->DbValue = $row['fecha_resgistro'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['comprobante_pago']) ? [] : [$row['comprobante_pago']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->comprobante_pago->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->comprobante_pago->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id` = @id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->id->CurrentValue : $this->id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->id->CurrentValue = $keys[0];
            } else {
                $this->id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id', $row) ? $row['id'] : null;
        } else {
            $val = !EmptyValue($this->id->OldValue) && !$current ? $this->id->OldValue : $this->id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("PagosList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        return match ($pageName) {
            "PagosView" => $Language->phrase("View"),
            "PagosEdit" => $Language->phrase("Edit"),
            "PagosAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "PagosList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "PagosView",
            Config("API_ADD_ACTION") => "PagosAdd",
            Config("API_EDIT_ACTION") => "PagosEdit",
            Config("API_DELETE_ACTION") => "PagosDelete",
            Config("API_LIST_ACTION") => "PagosList",
            default => ""
        };
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "PagosList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PagosView", $parm);
        } else {
            $url = $this->keyUrl("PagosView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PagosAdd?" . $parm;
        } else {
            $url = "PagosAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PagosEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("PagosList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("PagosAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("PagosList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("PagosDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "salidas" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->id_documento->getSessionValue()); // Use Session Value
            $url .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->getSessionValue()); // Use Session Value
        }
        if ($this->getCurrentMasterTable() == "view_facturas_cobranza" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->id_documento->getSessionValue()); // Use Session Value
            $url .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->getSessionValue()); // Use Session Value
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"id\":" . VarToJson($this->id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($this->PageID != "grid" && $fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($this->PageID != "grid" && !$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") .
                (is_array($fld->EditValue) ? str_replace("%c", count($fld->EditValue), $Language->phrase("FilterCount")) : '') .
                '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport;
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, 0))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, 0))) // Other API
                : []; // Non-API
            if (($keyValue = Param("id") ?? Route("id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif ($isApi && (($keyValue = Key(0) ?? $keyValues[0] ?? null) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        return implode(" OR ", array_map(fn($row) => "(" . $this->getRecordFilter($row) . ")", $rows));
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->id->CurrentValue = $key;
            } else {
                $this->id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load result set based on filter/sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->id->setDbValue($row['id']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->tipo_pago->setDbValue($row['tipo_pago']);
        $this->fecha->setDbValue($row['fecha']);
        $this->banco->setDbValue($row['banco']);
        $this->banco_destino->setDbValue($row['banco_destino']);
        $this->referencia->setDbValue($row['referencia']);
        $this->moneda->setDbValue($row['moneda']);
        $this->monto->setDbValue($row['monto']);
        $this->nota->setDbValue($row['nota']);
        $this->comprobante_pago->Upload->DbValue = $row['comprobante_pago'];
        $this->comprobante->setDbValue($row['comprobante']);
        $this->_username->setDbValue($row['username']);
        $this->fecha_resgistro->setDbValue($row['fecha_resgistro']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "PagosList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("app.view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // id_documento

        // tipo_documento

        // tipo_pago

        // fecha

        // banco

        // banco_destino

        // referencia

        // moneda

        // monto

        // nota

        // comprobante_pago

        // comprobante

        // username

        // fecha_resgistro

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // id_documento
        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

        // tipo_pago
        $curVal = strval($this->tipo_pago->CurrentValue);
        if ($curVal != "") {
            $this->tipo_pago->ViewValue = $this->tipo_pago->lookupCacheOption($curVal);
            if ($this->tipo_pago->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tipo_pago->Lookup->getTable()->Fields["valor1"]->searchExpression(), "=", $curVal, $this->tipo_pago->Lookup->getTable()->Fields["valor1"]->searchDataType(), "");
                $lookupFilter = $this->tipo_pago->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_pago->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_pago->ViewValue = $this->tipo_pago->displayValue($arwrk);
                } else {
                    $this->tipo_pago->ViewValue = $this->tipo_pago->CurrentValue;
                }
            }
        } else {
            $this->tipo_pago->ViewValue = null;
        }

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

        // banco
        $curVal = strval($this->banco->CurrentValue);
        if ($curVal != "") {
            $this->banco->ViewValue = $this->banco->lookupCacheOption($curVal);
            if ($this->banco->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->banco->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->banco->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $lookupFilter = $this->banco->getSelectFilter($this); // PHP
                $sqlWrk = $this->banco->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->banco->Lookup->renderViewRow($rswrk[0]);
                    $this->banco->ViewValue = $this->banco->displayValue($arwrk);
                } else {
                    $this->banco->ViewValue = $this->banco->CurrentValue;
                }
            }
        } else {
            $this->banco->ViewValue = null;
        }

        // banco_destino
        $curVal = strval($this->banco_destino->CurrentValue);
        if ($curVal != "") {
            $this->banco_destino->ViewValue = $this->banco_destino->lookupCacheOption($curVal);
            if ($this->banco_destino->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->banco_destino->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->banco_destino->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->banco_destino->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->banco_destino->Lookup->renderViewRow($rswrk[0]);
                    $this->banco_destino->ViewValue = $this->banco_destino->displayValue($arwrk);
                } else {
                    $this->banco_destino->ViewValue = FormatNumber($this->banco_destino->CurrentValue, $this->banco_destino->formatPattern());
                }
            }
        } else {
            $this->banco_destino->ViewValue = null;
        }

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;

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

        // monto
        $this->monto->ViewValue = $this->monto->CurrentValue;
        $this->monto->ViewValue = FormatNumber($this->monto->ViewValue, $this->monto->formatPattern());

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;

        // comprobante_pago
        if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
            $this->comprobante_pago->ImageWidth = 120;
            $this->comprobante_pago->ImageHeight = 120;
            $this->comprobante_pago->ImageAlt = $this->comprobante_pago->alt();
            $this->comprobante_pago->ImageCssClass = "ew-image";
            $this->comprobante_pago->ViewValue = $this->comprobante_pago->Upload->DbValue;
        } else {
            $this->comprobante_pago->ViewValue = "";
        }

        // comprobante
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;

        // username
        $this->_username->ViewValue = $this->_username->CurrentValue;

        // fecha_resgistro
        $this->fecha_resgistro->ViewValue = $this->fecha_resgistro->CurrentValue;
        $this->fecha_resgistro->ViewValue = FormatDateTime($this->fecha_resgistro->ViewValue, $this->fecha_resgistro->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // id_documento
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // tipo_pago
        $this->tipo_pago->HrefValue = "";
        $this->tipo_pago->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // banco
        $this->banco->HrefValue = "";
        $this->banco->TooltipValue = "";

        // banco_destino
        $this->banco_destino->HrefValue = "";
        $this->banco_destino->TooltipValue = "";

        // referencia
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // monto
        $this->monto->HrefValue = "";
        $this->monto->TooltipValue = "";

        // nota
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // comprobante_pago
        if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
            $this->comprobante_pago->HrefValue = GetFileUploadUrl($this->comprobante_pago, $this->comprobante_pago->htmlDecode($this->comprobante_pago->Upload->DbValue)); // Add prefix/suffix
            $this->comprobante_pago->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->comprobante_pago->HrefValue = FullUrl($this->comprobante_pago->HrefValue, "href");
            }
        } else {
            $this->comprobante_pago->HrefValue = "";
        }
        $this->comprobante_pago->ExportHrefValue = $this->comprobante_pago->UploadPath . $this->comprobante_pago->Upload->DbValue;
        $this->comprobante_pago->TooltipValue = "";
        if ($this->comprobante_pago->UseColorbox) {
            if (EmptyValue($this->comprobante_pago->TooltipValue)) {
                $this->comprobante_pago->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->comprobante_pago->LinkAttrs["data-rel"] = "pagos_x_comprobante_pago";
            $this->comprobante_pago->LinkAttrs->appendClass("ew-lightbox");
        }

        // comprobante
        $this->comprobante->HrefValue = "";
        $this->comprobante->TooltipValue = "";

        // username
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // fecha_resgistro
        $this->fecha_resgistro->HrefValue = "";
        $this->fecha_resgistro->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // id
        $this->id->setupEditAttributes();
        $this->id->EditValue = $this->id->CurrentValue;

        // id_documento
        $this->id_documento->setupEditAttributes();
        if ($this->id_documento->getSessionValue() != "") {
            $this->id_documento->CurrentValue = GetForeignKeyValue($this->id_documento->getSessionValue());
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
        } else {
            $this->id_documento->EditValue = $this->id_documento->CurrentValue;
            $this->id_documento->PlaceHolder = RemoveHtml($this->id_documento->caption());
            if (strval($this->id_documento->EditValue) != "" && is_numeric($this->id_documento->EditValue)) {
                $this->id_documento->EditValue = $this->id_documento->EditValue;
            }
        }

        // tipo_documento
        $this->tipo_documento->setupEditAttributes();
        if ($this->tipo_documento->getSessionValue() != "") {
            $this->tipo_documento->CurrentValue = GetForeignKeyValue($this->tipo_documento->getSessionValue());
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        } else {
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());
        }

        // tipo_pago
        $this->tipo_pago->setupEditAttributes();
        $this->tipo_pago->PlaceHolder = RemoveHtml($this->tipo_pago->caption());

        // fecha
        $this->fecha->setupEditAttributes();
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // banco
        $this->banco->setupEditAttributes();
        $this->banco->PlaceHolder = RemoveHtml($this->banco->caption());

        // banco_destino
        $this->banco_destino->setupEditAttributes();
        $this->banco_destino->PlaceHolder = RemoveHtml($this->banco_destino->caption());

        // referencia
        $this->referencia->setupEditAttributes();
        if (!$this->referencia->Raw) {
            $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
        }
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

        // moneda
        $this->moneda->setupEditAttributes();
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // monto
        $this->monto->setupEditAttributes();
        $this->monto->EditValue = $this->monto->CurrentValue;
        $this->monto->PlaceHolder = RemoveHtml($this->monto->caption());
        if (strval($this->monto->EditValue) != "" && is_numeric($this->monto->EditValue)) {
            $this->monto->EditValue = FormatNumber($this->monto->EditValue, null);
        }

        // nota
        $this->nota->setupEditAttributes();
        if (!$this->nota->Raw) {
            $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
        }
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // comprobante_pago
        $this->comprobante_pago->setupEditAttributes();
        if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
            $this->comprobante_pago->ImageWidth = 120;
            $this->comprobante_pago->ImageHeight = 120;
            $this->comprobante_pago->ImageAlt = $this->comprobante_pago->alt();
            $this->comprobante_pago->ImageCssClass = "ew-image";
            $this->comprobante_pago->EditValue = $this->comprobante_pago->Upload->DbValue;
        } else {
            $this->comprobante_pago->EditValue = "";
        }
        if (!EmptyValue($this->comprobante_pago->CurrentValue)) {
            $this->comprobante_pago->Upload->FileName = $this->comprobante_pago->CurrentValue;
        }

        // comprobante
        $this->comprobante->setupEditAttributes();
        $this->comprobante->EditValue = $this->comprobante->CurrentValue;
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());
        if (strval($this->comprobante->EditValue) != "" && is_numeric($this->comprobante->EditValue)) {
            $this->comprobante->EditValue = $this->comprobante->EditValue;
        }

        // username
        $this->_username->setupEditAttributes();
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // fecha_resgistro
        $this->fecha_resgistro->setupEditAttributes();
        $this->fecha_resgistro->EditValue = FormatDateTime($this->fecha_resgistro->CurrentValue, $this->fecha_resgistro->formatPattern());
        $this->fecha_resgistro->PlaceHolder = RemoveHtml($this->fecha_resgistro->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $result, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$result || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->tipo_pago);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->banco_destino);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->nota);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->tipo_pago);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->banco_destino);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->comprobante_pago);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->fecha_resgistro);
                }
                $doc->endExportRow();
            }
        }
        $recCnt = $startRec - 1;
        $stopRec = $stopRec > 0 ? $stopRec : PHP_INT_MAX;
        while (($row = $result->fetch()) && $recCnt < $stopRec) {
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = RowType::VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->tipo_pago);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->banco_destino);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->nota);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->tipo_pago);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->banco_destino);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->comprobante_pago);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->fecha_resgistro);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'comprobante_pago') {
            $fldName = "comprobante_pago";
            $fileNameFld = "comprobante_pago";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->id->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssociative($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DataType::BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower($pathinfo["extension"] ?? "");
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment" . ($DownloadFileName ? "; filename=\"" . $DownloadFileName . "\"" : ""));
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    if ($fld->hasMethod("getUploadPath")) { // Check field level upload path
                        $fld->UploadPath = $fld->getUploadPath();
                    }
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Write audit trail start/end for grid update
    public function writeAuditTrailDummy($typ)
    {
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'pagos');
    }

    // Write audit trail (add page)
    public function writeAuditTrailOnAdd(&$rs)
    {
        global $Language;
        if (!$this->AuditTrailOnAdd) {
            return;
        }

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['id'];

        // Write audit trail
        $usr = CurrentUserIdentifier();
        foreach (array_keys($rs) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && $this->Fields[$fldname]->DataType != DataType::BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") { // Password Field
                    $newvalue = $Language->phrase("PasswordMask");
                } elseif ($this->Fields[$fldname]->DataType == DataType::MEMO) { // Memo Field
                    $newvalue = Config("AUDIT_TRAIL_TO_DATABASE") ? $rs[$fldname] : "[MEMO]";
                } elseif ($this->Fields[$fldname]->DataType == DataType::XML) { // XML Field
                    $newvalue = "[XML]";
                } else {
                    $newvalue = $rs[$fldname];
                }
                WriteAuditLog($usr, "A", 'pagos', $fldname, $key, "", $newvalue);
            }
        }
    }

    // Write audit trail (edit page)
    public function writeAuditTrailOnEdit(&$rsold, &$rsnew)
    {
        global $Language;
        if (!$this->AuditTrailOnEdit) {
            return;
        }

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rsold['id'];

        // Write audit trail
        $usr = CurrentUserIdentifier();
        foreach (array_keys($rsnew) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && array_key_exists($fldname, $rsold) && $this->Fields[$fldname]->DataType != DataType::BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->DataType == DataType::DATE) { // DateTime field
                    $modified = (FormatDateTime($rsold[$fldname], 0) != FormatDateTime($rsnew[$fldname], 0));
                } else {
                    $modified = !CompareValue($rsold[$fldname], $rsnew[$fldname]);
                }
                if ($modified) {
                    if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") { // Password Field
                        $oldvalue = $Language->phrase("PasswordMask");
                        $newvalue = $Language->phrase("PasswordMask");
                    } elseif ($this->Fields[$fldname]->DataType == DataType::MEMO) { // Memo field
                        $oldvalue = Config("AUDIT_TRAIL_TO_DATABASE") ? $rsold[$fldname] : "[MEMO]";
                        $newvalue = Config("AUDIT_TRAIL_TO_DATABASE") ? $rsnew[$fldname] : "[MEMO]";
                    } elseif ($this->Fields[$fldname]->DataType == DataType::XML) { // XML field
                        $oldvalue = "[XML]";
                        $newvalue = "[XML]";
                    } else {
                        $oldvalue = $rsold[$fldname];
                        $newvalue = $rsnew[$fldname];
                    }
                    WriteAuditLog($usr, "U", 'pagos', $fldname, $key, $oldvalue, $newvalue);
                }
            }
        }
    }

    // Write audit trail (delete page)
    public function writeAuditTrailOnDelete(&$rs)
    {
        global $Language;
        if (!$this->AuditTrailOnDelete) {
            return;
        }

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['id'];

        // Write audit trail
        $usr = CurrentUserIdentifier();
        foreach (array_keys($rs) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && $this->Fields[$fldname]->DataType != DataType::BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") { // Password Field
                    $oldvalue = $Language->phrase("PasswordMask");
                } elseif ($this->Fields[$fldname]->DataType == DataType::MEMO) { // Memo field
                    $oldvalue = Config("AUDIT_TRAIL_TO_DATABASE") ? $rs[$fldname] : "[MEMO]";
                } elseif ($this->Fields[$fldname]->DataType == DataType::XML) { // XML field
                    $oldvalue = "[XML]";
                } else {
                    $oldvalue = $rs[$fldname];
                }
                WriteAuditLog($usr, "D", 'pagos', $fldname, $key, $oldvalue);
            }
        }
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected($rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        $sql = "SELECT cliente
        		FROM salidas
        		WHERE
        			id = " . $rsnew["id_documento"] . "
        			AND tipo_documento = '" . $rsnew["tipo_documento"] . "'";
        $cliente = ExecuteScalar($sql);
        if($rsnew["tipo_pago"] == "RC") {
    		if(substr($rsnew["moneda"], 0, 3) != "Bs.") {
    			$this->CancelMessage = "El monto a tomar de los abonos debe ser en Bs..";
    			return FALSE;
    		}
        	$saldo = 0;
        	$sql = "SELECT saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
        	if($row = ExecuteRow($sql))
        		$saldo = floatval($row["saldo"]);
        	$monto = floatval($rsnew["monto"]);
        	if($monto > $saldo) {
    			$this->CancelMessage = "El monto a pagar es mayor al saldo disponible en los abonos.";
    			return FALSE;
        	}
        }
        $rsnew["username"] = CurrentUserName();
        $rsnew["fecha_resgistro"] = date("Y-m-d");
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
        $sql = "SELECT tasa_dia, cliente, nro_documento FROM salidas WHERE id = " . $rsnew["id_documento"] . "";
        $row = ExecuteRow($sql);
        $tasa_dia = floatval($row["tasa_dia"]);
        $cliente = $row["cliente"];
        $documentos = $row["nro_documento"];

        /////////////////
        $monto_igtf_a_pagar = 0;
        $sql = "SELECT
        			SUM(IF(moneda = 'USD',
        				ROUND(monto, 2)*ROUND($tasa_dia, 2),
        				ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rsnew["id_documento"] . "
        			AND tipo_documento = '" . $rsnew["tipo_documento"] . "'
        			AND tipo_pago <> 'IG' AND moneda = 'USD'";
        $monto_igtf_a_pagar = floatval(ExecuteScalar($sql))*(3/100);
        /////////////////
        $sql = "SELECT
        			SUM(IF(moneda = 'USD', ROUND(monto, 2)*ROUND($tasa_dia, 2), ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rsnew["id_documento"] . "
        			AND tipo_documento = '" . $rsnew["tipo_documento"] . "'
        			AND tipo_pago <> 'IG'";
        $monto = floatval(ExecuteScalar($sql));
        $sql = "SELECT
        			IF(moneda = 'USD', ROUND(total, 2)*ROUND(tasa_dia, 2), ROUND(total, 2)) AS total
        		FROM
        			salidas 
        		WHERE id = " . $rsnew["id_documento"] . "";
        $monto_factura = floatval(ExecuteScalar($sql));

        /////////////////
        $monto_igtf_pagado = 0;
        $sql = "SELECT
        			SUM(IF(moneda = 'USD', ROUND(monto, 2)*ROUND($tasa_dia, 2), ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rsnew["id_documento"] . "
        			AND tipo_documento = '" . $rsnew["tipo_documento"] . "'
        			AND tipo_pago = 'IG'";
        $monto_igtf_pagado = floatval(ExecuteScalar($sql));
        /////////////////
        if(($monto+$monto_igtf_pagado) >= ($monto_factura+$monto_igtf_a_pagar)) 
        	$sql = "UPDATE salidas SET pagado = 'S' WHERE id = " . $rsnew["id_documento"] . "";
        else
        	$sql = "UPDATE salidas SET pagado = 'N' WHERE id = " . $rsnew["id_documento"] . "";
       	Execute($sql);

       	// Rebajo en abonos
    		  		if($rsnew["tipo_pago"] == "RC") {
    		  			$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
    		  			$nro_recibo = 0; //ExecuteScalar($sql);
    		  			$sql = "INSERT INTO
    		  						abono 
    		  					SET 	
    		  						id = NULL,
    		  						cliente = " . $cliente . ",
    		  						fecha = '" . date("Y-m-d H:i:s") . "',
    		  						metodo_pago = NULL,
    		  						nro_recibo = $nro_recibo,
    		  						nota = 'REBAJA EN COBROS Documento: $documentos',
    		  						username = '" . CurrentUserName() . "';";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$Abono = ExecuteScalar($sql);
    					$monto_moneda = $rsnew["monto"];
    					$tasa = 1;
    					$monto_bs = $monto_moneda;
    					$tasa_usd = 1;
    					$monto_usd = $monto_bs;
    					$sql = "INSERT INTO recarga(
    								id,
    								cliente,
    								fecha,
    								metodo_pago,
    								monto_moneda,
    								moneda,
    								tasa_moneda,
    								monto_bs,
    								tasa_usd,
    								monto_usd,
    								saldo,
    								nota,
    								username, reverso, abono)
    							VALUES (
    								NULL,
    								" . $cliente . ",
    								'" . date("Y-m-d H:i:s") . "',
    								'" . $rsnew["tipo_pago"] . "',
    								$monto_moneda,
    								'" . $rsnew["moneda"] . "',
    								$tasa,
    								$monto_bs,
    								$tasa_usd,
    								(-1)*$monto_usd,
    								0,
    								'Pago Documento(s): $documentos',
    								'$username', 'N', $Abono)";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$id = ExecuteScalar($sql);
    					$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
    			    			WHERE cliente = " . $cliente . ";";
    			    	$saldo = ExecuteScalar($sql);
    			    	$sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
    			    	Execute($sql);
    			    	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
    			    	$monto_abono = ExecuteScalar($sql);
    			    	$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
    			    	Execute($sql);
    		  		}

       	//
    }	

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted($rs)
    {
        //Log("Row Deleted");
        $sql = "SELECT tasa_dia, cliente, nro_documento FROM salidas WHERE id = " . $rs["id_documento"] . "";
        $row = ExecuteRow($sql);
        $tasa_dia = floatval($row["tasa_dia"]);
        $cliente = $row["cliente"];
        $documentos = $row["nro_documento"];

        /////////////////
        $monto_igtf_a_pagar = 0;
        $sql = "SELECT
        			SUM(IF(moneda = 'USD', ROUND(monto, 2)*ROUND($tasa_dia, 2), ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rs["id_documento"] . "
        			AND tipo_documento = '" . $rs["tipo_documento"] . "'
        			AND tipo_pago <> 'IG' AND moneda = 'USD'";
        $monto_igtf_a_pagar = floatval(ExecuteScalar($sql))*(3/100);
        /////////////////
        $sql = "SELECT
        			SUM(IF(moneda = 'USD', ROUND(monto, 2)*ROUND($tasa_dia, 2), ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rs["id_documento"] . "
        			AND tipo_documento = '" . $rs["tipo_documento"] . "'
        			AND tipo_pago <> 'IG'";
        $monto = floatval(ExecuteScalar($sql));
        $sql = "SELECT
        			IF(moneda = 'USD', ROUND(total, 2)*ROUND(tasa_dia, 2), ROUND(total, 2)) AS total
        		FROM
        			salidas 
        		WHERE id = " . $rs["id_documento"] . "";
        $monto_factura = floatval(ExecuteScalar($sql));

        /////////////////
        $monto_igtf_pagado = 0;
        $sql = "SELECT
        			SUM(IF(moneda = 'USD', ROUND(monto, 2)*ROUND($tasa_dia, 2), ROUND(monto, 2))) AS monto
        		FROM
        			pagos
        		WHERE
        			id_documento = " . $rs["id_documento"] . "
        			AND tipo_documento = '" . $rs["tipo_documento"] . "'
        			AND tipo_pago = 'IG'";
        $monto_igtf_pagado = floatval(ExecuteScalar($sql));
        /////////////////
        if(($monto+$monto_igtf_pagado) >= ($monto_factura+$monto_igtf_a_pagar)) 
        	$sql = "UPDATE salidas SET pagado = 'S' WHERE id = " . $rs["id_documento"] . "";
        else
        	$sql = "UPDATE salidas SET pagado = 'N' WHERE id = " . $rs["id_documento"] . "";
       	Execute($sql);

       	// Revertir en abonos
    		  		if($rs["tipo_pago"] == "RC") {
    		  			$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
    		  			$nro_recibo = 0; //ExecuteScalar($sql);
    		  			$sql = "INSERT INTO
    		  						abono 
    		  					SET 	
    		  						id = NULL,
    		  						cliente = " . $cliente . ",
    		  						fecha = NOW(),
    		  						metodo_pago = NULL,
    		  						nro_recibo = $nro_recibo,
    		  						nota = 'POR ELIMINACION PAGO A Documento: $documentos',
    		  						username = '" . CurrentUserName() . "';";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$Abono = ExecuteScalar($sql);
    					$monto_moneda = $rs["monto"];
    					$tasa = 1;
    					$monto_bs = $monto_moneda;
    					$tasa_usd = 1;
    					$monto_usd = $monto_bs;
    					$sql = "INSERT INTO recarga(
    								id,
    								cliente,
    								fecha,
    								metodo_pago,
    								monto_moneda,
    								moneda,
    								tasa_moneda,
    								monto_bs,
    								tasa_usd,
    								monto_usd,
    								saldo,
    								nota,
    								username, reverso, abono)
    							VALUES (
    								NULL,
    								" . $cliente . ",
    								NOW(),
    								'" . $rs["tipo_pago"] . "',
    								$monto_moneda,
    								'" . $rs["moneda"] . "',
    								$tasa,
    								$monto_bs,
    								$tasa_usd,
    								$monto_usd,
    								0,
    								'Pago Documento(s): $documentos',
    								'$username', 'N', $Abono)";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$id = ExecuteScalar($sql);
    					$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
    			    			WHERE cliente = " . $cliente . ";";
    			    	$saldo = ExecuteScalar($sql);
    			    	$sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
    			    	Execute($sql);
    			    	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
    			    	$monto_abono = ExecuteScalar($sql);
    			    	$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
    			    	Execute($sql);
    		  		}

       	//
    }

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
        //$this->fecha->CellAttrs["style"] = "font-size: 80px";
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
