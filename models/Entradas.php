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
 * Table class for entradas
 */
class Entradas extends DbTable
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
    public $tipo_documento;
    public $nro_documento;
    public $doc_afectado;
    public $nro_control;
    public $fecha;
    public $proveedor;
    public $almacen;
    public $monto_total;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $documento;
    public $nota;
    public $estatus;
    public $_username;
    public $id_documento_padre;
    public $moneda;
    public $consignacion;
    public $consignacion_reportada;
    public $aplica_retencion;
    public $ret_iva;
    public $ref_iva;
    public $ret_islr;
    public $ref_islr;
    public $ret_municipal;
    public $ref_municipal;
    public $monto_pagar;
    public $comprobante;
    public $tipo_iva;
    public $tipo_islr;
    public $sustraendo;
    public $fecha_registro_retenciones;
    public $tasa_dia;
    public $monto_usd;
    public $fecha_libro_compra;
    public $tipo_municipal;
    public $cerrado;
    public $descuento;
    public $archivo_pedido;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "entradas";
        $this->TableName = 'entradas';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "entradas";
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
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
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
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Nullable = false; // NOT NULL field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id'] = &$this->id;

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
            'SELECT' // Edit Tag
        );
        $this->tipo_documento->addMethod("getSelectFilter", fn() => "`tipo` = 'PROVEEDOR' AND codigo IN (SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "')");
        $this->tipo_documento->InputTextType = "text";
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->setSelectMultiple(false); // Select one
        $this->tipo_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento->Lookup = new Lookup($this->tipo_documento, 'tipo_documento', false, 'codigo', ["descripcion","","",""], '', '', [], [], [], [], [], [], false, '', '', "`descripcion`");
        $this->tipo_documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // nro_documento
        $this->nro_documento = new DbField(
            $this, // Table
            'x_nro_documento', // Variable name
            'nro_documento', // Name
            '`nro_documento`', // Expression
            '`nro_documento`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nro_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nro_documento->InputTextType = "text";
        $this->nro_documento->Required = true; // Required field
        $this->nro_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // doc_afectado
        $this->doc_afectado = new DbField(
            $this, // Table
            'x_doc_afectado', // Variable name
            'doc_afectado', // Name
            '`doc_afectado`', // Expression
            '`doc_afectado`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`doc_afectado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->doc_afectado->InputTextType = "text";
        $this->doc_afectado->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['doc_afectado'] = &$this->doc_afectado;

        // nro_control
        $this->nro_control = new DbField(
            $this, // Table
            'x_nro_control', // Variable name
            'nro_control', // Name
            '`nro_control`', // Expression
            '`nro_control`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nro_control`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nro_control->InputTextType = "text";
        $this->nro_control->Required = true; // Required field
        $this->nro_control->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nro_control'] = &$this->nro_control;

        // fecha
        $this->fecha = new DbField(
            $this, // Table
            'x_fecha', // Variable name
            'fecha', // Name
            '`fecha`', // Expression
            CastDateFieldForLike("`fecha`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
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

        // proveedor
        $this->proveedor = new DbField(
            $this, // Table
            'x_proveedor', // Variable name
            'proveedor', // Name
            '`proveedor`', // Expression
            '`proveedor`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`proveedor`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->proveedor->InputTextType = "text";
        $this->proveedor->Raw = true;
        $this->proveedor->Required = true; // Required field
        $this->proveedor->setSelectMultiple(false); // Select one
        $this->proveedor->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->proveedor->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->proveedor->Lookup = new Lookup($this->proveedor, 'proveedor', false, 'id', ["nombre","","",""], '', '', [], ["x_id_documento_padre"], [], [], [], [], false, '`nombre`', '', "`nombre`");
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['proveedor'] = &$this->proveedor;

        // almacen
        $this->almacen = new DbField(
            $this, // Table
            'x_almacen', // Variable name
            'almacen', // Name
            '`almacen`', // Expression
            '`almacen`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`almacen`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->almacen->InputTextType = "text";
        $this->almacen->Required = true; // Required field
        $this->almacen->setSelectMultiple(false); // Select one
        $this->almacen->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->almacen->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->almacen->Lookup = new Lookup($this->almacen, 'almacen', false, 'codigo', ["descripcion","","",""], '', '', [], [], [], [], [], [], false, '`descripcion`', '', "`descripcion`");
        $this->almacen->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['almacen'] = &$this->almacen;

        // monto_total
        $this->monto_total = new DbField(
            $this, // Table
            'x_monto_total', // Variable name
            'monto_total', // Name
            '`monto_total`', // Expression
            '`monto_total`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_total`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_total->InputTextType = "text";
        $this->monto_total->Raw = true;
        $this->monto_total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_total->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_total'] = &$this->monto_total;

        // alicuota_iva
        $this->alicuota_iva = new DbField(
            $this, // Table
            'x_alicuota_iva', // Variable name
            'alicuota_iva', // Name
            '`alicuota_iva`', // Expression
            '`alicuota_iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`alicuota_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->alicuota_iva->InputTextType = "text";
        $this->alicuota_iva->Raw = true;
        $this->alicuota_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['alicuota_iva'] = &$this->alicuota_iva;

        // iva
        $this->iva = new DbField(
            $this, // Table
            'x_iva', // Variable name
            'iva', // Name
            '`iva`', // Expression
            '`iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->iva->InputTextType = "text";
        $this->iva->Raw = true;
        $this->iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['iva'] = &$this->iva;

        // total
        $this->total = new DbField(
            $this, // Table
            'x_total', // Variable name
            'total', // Name
            '`total`', // Expression
            '`total`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total->InputTextType = "text";
        $this->total->Raw = true;
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['total'] = &$this->total;

        // documento
        $this->documento = new DbField(
            $this, // Table
            'x_documento', // Variable name
            'documento', // Name
            '`documento`', // Expression
            '`documento`', // Basic search expression
            129, // Type
            2, // Size
            7, // Date/Time format
            false, // Is upload field
            '`documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->documento->InputTextType = "text";
        $this->documento->Required = true; // Required field
        $this->documento->setSelectMultiple(false); // Select one
        $this->documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->documento->Lookup = new Lookup($this->documento, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->documento->OptionCount = 3;
        $this->documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['documento'] = &$this->documento;

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
            'TEXTAREA' // Edit Tag
        );
        $this->nota->InputTextType = "text";
        $this->nota->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nota'] = &$this->nota;

        // estatus
        $this->estatus = new DbField(
            $this, // Table
            'x_estatus', // Variable name
            'estatus', // Name
            '`estatus`', // Expression
            '`estatus`', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`estatus`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->estatus->InputTextType = "text";
        $this->estatus->Required = true; // Required field
        $this->estatus->setSelectMultiple(false); // Select one
        $this->estatus->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->estatus->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->estatus->Lookup = new Lookup($this->estatus, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->estatus->OptionCount = 3;
        $this->estatus->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['estatus'] = &$this->estatus;

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
        $this->_username->Lookup = new Lookup($this->_username, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nombre`");
        $this->_username->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['username'] = &$this->_username;

        // id_documento_padre
        $this->id_documento_padre = new DbField(
            $this, // Table
            'x_id_documento_padre', // Variable name
            'id_documento_padre', // Name
            '`id_documento_padre`', // Expression
            '`id_documento_padre`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id_documento_padre`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id_documento_padre->addMethod("getSelectFilter", fn() => "`tipo_documento` IN ('TDCPDC','TDCFCC') AND estatus = 'NUEVO'");
        $this->id_documento_padre->InputTextType = "text";
        $this->id_documento_padre->Raw = true;
        $this->id_documento_padre->Required = true; // Required field
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

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
        $this->moneda->addMethod("getSelectFilter", fn() => "`codigo` = '006'");
        $this->moneda->InputTextType = "text";
        $this->moneda->Required = true; // Required field
        $this->moneda->setSelectMultiple(false); // Select one
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup($this->moneda, 'parametro', false, 'valor1', ["valor1","","",""], '', '', [], [], [], [], [], [], false, '`valor2` DESC', '', "`valor1`");
        $this->moneda->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['moneda'] = &$this->moneda;

        // consignacion
        $this->consignacion = new DbField(
            $this, // Table
            'x_consignacion', // Variable name
            'consignacion', // Name
            '`consignacion`', // Expression
            '`consignacion`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`consignacion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->consignacion->InputTextType = "text";
        $this->consignacion->Raw = true;
        $this->consignacion->setSelectMultiple(false); // Select one
        $this->consignacion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion->Lookup = new Lookup($this->consignacion, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->consignacion->OptionCount = 2;
        $this->consignacion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['consignacion'] = &$this->consignacion;

        // consignacion_reportada
        $this->consignacion_reportada = new DbField(
            $this, // Table
            'x_consignacion_reportada', // Variable name
            'consignacion_reportada', // Name
            '`consignacion_reportada`', // Expression
            '`consignacion_reportada`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`consignacion_reportada`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->consignacion_reportada->addMethod("getDefault", fn() => "N");
        $this->consignacion_reportada->InputTextType = "text";
        $this->consignacion_reportada->Raw = true;
        $this->consignacion_reportada->setSelectMultiple(false); // Select one
        $this->consignacion_reportada->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion_reportada->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion_reportada->Lookup = new Lookup($this->consignacion_reportada, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->consignacion_reportada->OptionCount = 2;
        $this->consignacion_reportada->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['consignacion_reportada'] = &$this->consignacion_reportada;

        // aplica_retencion
        $this->aplica_retencion = new DbField(
            $this, // Table
            'x_aplica_retencion', // Variable name
            'aplica_retencion', // Name
            '`aplica_retencion`', // Expression
            '`aplica_retencion`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`aplica_retencion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->aplica_retencion->InputTextType = "text";
        $this->aplica_retencion->Raw = true;
        $this->aplica_retencion->Required = true; // Required field
        $this->aplica_retencion->Lookup = new Lookup($this->aplica_retencion, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->aplica_retencion->OptionCount = 2;
        $this->aplica_retencion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['aplica_retencion'] = &$this->aplica_retencion;

        // ret_iva
        $this->ret_iva = new DbField(
            $this, // Table
            'x_ret_iva', // Variable name
            'ret_iva', // Name
            '`ret_iva`', // Expression
            '`ret_iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ret_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ret_iva->InputTextType = "text";
        $this->ret_iva->Raw = true;
        $this->ret_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ret_iva'] = &$this->ret_iva;

        // ref_iva
        $this->ref_iva = new DbField(
            $this, // Table
            'x_ref_iva', // Variable name
            'ref_iva', // Name
            '`ref_iva`', // Expression
            '`ref_iva`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ref_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ref_iva->addMethod("getLinkPrefix", fn() => "reportes/rptRetencionE.php?Nretencion=");
        $this->ref_iva->InputTextType = "text";
        $this->ref_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ref_iva'] = &$this->ref_iva;

        // ret_islr
        $this->ret_islr = new DbField(
            $this, // Table
            'x_ret_islr', // Variable name
            'ret_islr', // Name
            '`ret_islr`', // Expression
            '`ret_islr`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ret_islr`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ret_islr->InputTextType = "text";
        $this->ret_islr->Raw = true;
        $this->ret_islr->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_islr->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ret_islr'] = &$this->ret_islr;

        // ref_islr
        $this->ref_islr = new DbField(
            $this, // Table
            'x_ref_islr', // Variable name
            'ref_islr', // Name
            '`ref_islr`', // Expression
            '`ref_islr`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ref_islr`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ref_islr->addMethod("getLinkPrefix", fn() => "reportes/rptRetencionE2.php?Nretencion=");
        $this->ref_islr->InputTextType = "text";
        $this->ref_islr->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ref_islr'] = &$this->ref_islr;

        // ret_municipal
        $this->ret_municipal = new DbField(
            $this, // Table
            'x_ret_municipal', // Variable name
            'ret_municipal', // Name
            '`ret_municipal`', // Expression
            '`ret_municipal`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ret_municipal`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ret_municipal->InputTextType = "text";
        $this->ret_municipal->Raw = true;
        $this->ret_municipal->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_municipal->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['ret_municipal'] = &$this->ret_municipal;

        // ref_municipal
        $this->ref_municipal = new DbField(
            $this, // Table
            'x_ref_municipal', // Variable name
            'ref_municipal', // Name
            '`ref_municipal`', // Expression
            '`ref_municipal`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ref_municipal`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ref_municipal->InputTextType = "text";
        $this->ref_municipal->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ref_municipal'] = &$this->ref_municipal;

        // monto_pagar
        $this->monto_pagar = new DbField(
            $this, // Table
            'x_monto_pagar', // Variable name
            'monto_pagar', // Name
            '`monto_pagar`', // Expression
            '`monto_pagar`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_pagar`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_pagar->InputTextType = "text";
        $this->monto_pagar->Raw = true;
        $this->monto_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagar->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_pagar'] = &$this->monto_pagar;

        // comprobante
        $this->comprobante = new DbField(
            $this, // Table
            'x_comprobante', // Variable name
            'comprobante', // Name
            '`comprobante`', // Expression
            '`comprobante`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`comprobante`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->comprobante->addMethod("getLinkPrefix", fn() => "ContAsientoList?showmaster=cont_comprobante&fk_id=");
        $this->comprobante->InputTextType = "text";
        $this->comprobante->Raw = true;
        $this->comprobante->setSelectMultiple(false); // Select one
        $this->comprobante->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->comprobante->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->comprobante->Lookup = new Lookup($this->comprobante, 'cont_comprobante', false, 'id', ["id","descripcion","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`id`, ''),'" . ValueSeparator(1, $this->comprobante) . "',COALESCE(`descripcion`,''))");
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipo_iva
        $this->tipo_iva = new DbField(
            $this, // Table
            'x_tipo_iva', // Variable name
            'tipo_iva', // Name
            '`tipo_iva`', // Expression
            '`tipo_iva`', // Basic search expression
            200, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tipo_iva->InputTextType = "text";
        $this->tipo_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_iva'] = &$this->tipo_iva;

        // tipo_islr
        $this->tipo_islr = new DbField(
            $this, // Table
            'x_tipo_islr', // Variable name
            'tipo_islr', // Name
            '`tipo_islr`', // Expression
            '`tipo_islr`', // Basic search expression
            200, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_islr`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tipo_islr->InputTextType = "text";
        $this->tipo_islr->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_islr'] = &$this->tipo_islr;

        // sustraendo
        $this->sustraendo = new DbField(
            $this, // Table
            'x_sustraendo', // Variable name
            'sustraendo', // Name
            '`sustraendo`', // Expression
            '`sustraendo`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`sustraendo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->sustraendo->InputTextType = "text";
        $this->sustraendo->Raw = true;
        $this->sustraendo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->sustraendo->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['sustraendo'] = &$this->sustraendo;

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones = new DbField(
            $this, // Table
            'x_fecha_registro_retenciones', // Variable name
            'fecha_registro_retenciones', // Name
            '`fecha_registro_retenciones`', // Expression
            CastDateFieldForLike("`fecha_registro_retenciones`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
            false, // Is upload field
            '`fecha_registro_retenciones`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_registro_retenciones->InputTextType = "text";
        $this->fecha_registro_retenciones->Raw = true;
        $this->fecha_registro_retenciones->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_registro_retenciones->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_registro_retenciones'] = &$this->fecha_registro_retenciones;

        // tasa_dia
        $this->tasa_dia = new DbField(
            $this, // Table
            'x_tasa_dia', // Variable name
            'tasa_dia', // Name
            '`tasa_dia`', // Expression
            '`tasa_dia`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tasa_dia`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tasa_dia->InputTextType = "text";
        $this->tasa_dia->Raw = true;
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // monto_usd
        $this->monto_usd = new DbField(
            $this, // Table
            'x_monto_usd', // Variable name
            'monto_usd', // Name
            '`monto_usd`', // Expression
            '`monto_usd`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_usd->InputTextType = "text";
        $this->monto_usd->Raw = true;
        $this->monto_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_usd'] = &$this->monto_usd;

        // fecha_libro_compra
        $this->fecha_libro_compra = new DbField(
            $this, // Table
            'x_fecha_libro_compra', // Variable name
            'fecha_libro_compra', // Name
            '`fecha_libro_compra`', // Expression
            CastDateFieldForLike("`fecha_libro_compra`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_libro_compra`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_libro_compra->InputTextType = "text";
        $this->fecha_libro_compra->Raw = true;
        $this->fecha_libro_compra->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_libro_compra->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_libro_compra'] = &$this->fecha_libro_compra;

        // tipo_municipal
        $this->tipo_municipal = new DbField(
            $this, // Table
            'x_tipo_municipal', // Variable name
            'tipo_municipal', // Name
            '`tipo_municipal`', // Expression
            '`tipo_municipal`', // Basic search expression
            200, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_municipal`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tipo_municipal->InputTextType = "text";
        $this->tipo_municipal->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_municipal'] = &$this->tipo_municipal;

        // cerrado
        $this->cerrado = new DbField(
            $this, // Table
            'x_cerrado', // Variable name
            'cerrado', // Name
            '`cerrado`', // Expression
            '`cerrado`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cerrado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->cerrado->addMethod("getDefault", fn() => "N");
        $this->cerrado->InputTextType = "text";
        $this->cerrado->Raw = true;
        $this->cerrado->Lookup = new Lookup($this->cerrado, 'entradas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->cerrado->OptionCount = 2;
        $this->cerrado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['cerrado'] = &$this->cerrado;

        // descuento
        $this->descuento = new DbField(
            $this, // Table
            'x_descuento', // Variable name
            'descuento', // Name
            '`descuento`', // Expression
            '`descuento`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`descuento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->descuento->InputTextType = "text";
        $this->descuento->Raw = true;
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->descuento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['descuento'] = &$this->descuento;

        // archivo_pedido
        $this->archivo_pedido = new DbField(
            $this, // Table
            'x_archivo_pedido', // Variable name
            'archivo_pedido', // Name
            '`archivo_pedido`', // Expression
            '`archivo_pedido`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`archivo_pedido`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'FILE' // Edit Tag
        );
        $this->archivo_pedido->InputTextType = "text";
        $this->archivo_pedido->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['archivo_pedido'] = &$this->archivo_pedido;

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

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")) ?? "";
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "entradas_salidas") {
            $detailUrl = Container("entradas_salidas")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "EntradasList";
        }
        return $detailUrl;
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "entradas";
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
        $pattern = '/^SELECT\s([\s\S]+?)\sFROM\s/i';
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
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->proveedor->DbValue = $row['proveedor'];
        $this->almacen->DbValue = $row['almacen'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->documento->DbValue = $row['documento'];
        $this->nota->DbValue = $row['nota'];
        $this->estatus->DbValue = $row['estatus'];
        $this->_username->DbValue = $row['username'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
        $this->moneda->DbValue = $row['moneda'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->consignacion_reportada->DbValue = $row['consignacion_reportada'];
        $this->aplica_retencion->DbValue = $row['aplica_retencion'];
        $this->ret_iva->DbValue = $row['ret_iva'];
        $this->ref_iva->DbValue = $row['ref_iva'];
        $this->ret_islr->DbValue = $row['ret_islr'];
        $this->ref_islr->DbValue = $row['ref_islr'];
        $this->ret_municipal->DbValue = $row['ret_municipal'];
        $this->ref_municipal->DbValue = $row['ref_municipal'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipo_iva->DbValue = $row['tipo_iva'];
        $this->tipo_islr->DbValue = $row['tipo_islr'];
        $this->sustraendo->DbValue = $row['sustraendo'];
        $this->fecha_registro_retenciones->DbValue = $row['fecha_registro_retenciones'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->fecha_libro_compra->DbValue = $row['fecha_libro_compra'];
        $this->tipo_municipal->DbValue = $row['tipo_municipal'];
        $this->cerrado->DbValue = $row['cerrado'];
        $this->descuento->DbValue = $row['descuento'];
        $this->archivo_pedido->Upload->DbValue = $row['archivo_pedido'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['archivo_pedido']) ? [] : [$row['archivo_pedido']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->archivo_pedido->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->archivo_pedido->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("EntradasList");
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
            "EntradasView" => $Language->phrase("View"),
            "EntradasEdit" => $Language->phrase("Edit"),
            "EntradasAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "EntradasList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "EntradasView",
            Config("API_ADD_ACTION") => "EntradasAdd",
            Config("API_EDIT_ACTION") => "EntradasEdit",
            Config("API_DELETE_ACTION") => "EntradasDelete",
            Config("API_LIST_ACTION") => "EntradasList",
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
        return "EntradasList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EntradasView", $parm);
        } else {
            $url = $this->keyUrl("EntradasView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "EntradasAdd?" . $parm;
        } else {
            $url = "EntradasAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EntradasEdit", $parm);
        } else {
            $url = $this->keyUrl("EntradasEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("EntradasList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EntradasAdd", $parm);
        } else {
            $url = $this->keyUrl("EntradasAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("EntradasList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("EntradasDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
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
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "EntradasList";
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

        // tipo_documento

        // nro_documento

        // doc_afectado

        // nro_control

        // fecha

        // proveedor

        // almacen

        // monto_total

        // alicuota_iva

        // iva

        // total

        // documento

        // nota

        // estatus

        // username

        // id_documento_padre

        // moneda

        // consignacion

        // consignacion_reportada

        // aplica_retencion

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // monto_pagar

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // fecha_registro_retenciones

        // tasa_dia

        // monto_usd

        // fecha_libro_compra

        // tipo_municipal

        // cerrado

        // descuento

        // archivo_pedido

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

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // nro_documento
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // nro_control
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // proveedor
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

        // almacen
        $this->almacen->HrefValue = "";
        $this->almacen->TooltipValue = "";

        // monto_total
        $this->monto_total->HrefValue = "";
        $this->monto_total->TooltipValue = "";

        // alicuota_iva
        $this->alicuota_iva->HrefValue = "";
        $this->alicuota_iva->TooltipValue = "";

        // iva
        $this->iva->HrefValue = "";
        $this->iva->TooltipValue = "";

        // total
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

        // documento
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // nota
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // estatus
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // username
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // consignacion
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // consignacion_reportada
        $this->consignacion_reportada->HrefValue = "";
        $this->consignacion_reportada->TooltipValue = "";

        // aplica_retencion
        $this->aplica_retencion->HrefValue = "";
        $this->aplica_retencion->TooltipValue = "";

        // ret_iva
        $this->ret_iva->HrefValue = "";
        $this->ret_iva->TooltipValue = "";

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
        $this->ref_iva->TooltipValue = "";

        // ret_islr
        $this->ret_islr->HrefValue = "";
        $this->ret_islr->TooltipValue = "";

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
        $this->ref_islr->TooltipValue = "";

        // ret_municipal
        $this->ret_municipal->HrefValue = "";
        $this->ret_municipal->TooltipValue = "";

        // ref_municipal
        $this->ref_municipal->HrefValue = "";
        $this->ref_municipal->TooltipValue = "";

        // monto_pagar
        $this->monto_pagar->HrefValue = "";
        $this->monto_pagar->TooltipValue = "";

        // comprobante
        if (!EmptyValue($this->comprobante->CurrentValue)) {
            $this->comprobante->HrefValue = $this->comprobante->getLinkPrefix() . $this->comprobante->CurrentValue; // Add prefix/suffix
            $this->comprobante->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->comprobante->HrefValue = FullUrl($this->comprobante->HrefValue, "href");
            }
        } else {
            $this->comprobante->HrefValue = "";
        }
        $this->comprobante->TooltipValue = "";

        // tipo_iva
        $this->tipo_iva->HrefValue = "";
        $this->tipo_iva->TooltipValue = "";

        // tipo_islr
        $this->tipo_islr->HrefValue = "";
        $this->tipo_islr->TooltipValue = "";

        // sustraendo
        $this->sustraendo->HrefValue = "";
        $this->sustraendo->TooltipValue = "";

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->HrefValue = "";
        $this->fecha_registro_retenciones->TooltipValue = "";

        // tasa_dia
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // fecha_libro_compra
        $this->fecha_libro_compra->HrefValue = "";
        $this->fecha_libro_compra->TooltipValue = "";

        // tipo_municipal
        $this->tipo_municipal->HrefValue = "";
        $this->tipo_municipal->TooltipValue = "";

        // cerrado
        $this->cerrado->HrefValue = "";
        $this->cerrado->TooltipValue = "";

        // descuento
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

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
        $this->archivo_pedido->TooltipValue = "";

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

        // tipo_documento
        $this->tipo_documento->setupEditAttributes();
        $curVal = strval($this->tipo_documento->CurrentValue);
        if ($curVal != "") {
            $this->tipo_documento->EditValue = $this->tipo_documento->lookupCacheOption($curVal);
            if ($this->tipo_documento->EditValue === null) { // Lookup from database
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
                    $this->tipo_documento->EditValue = $this->tipo_documento->displayValue($arwrk);
                } else {
                    $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
                }
            }
        } else {
            $this->tipo_documento->EditValue = null;
        }

        // nro_documento
        $this->nro_documento->setupEditAttributes();
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // doc_afectado
        $this->doc_afectado->setupEditAttributes();
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // nro_control
        $this->nro_control->setupEditAttributes();
        if (!$this->nro_control->Raw) {
            $this->nro_control->CurrentValue = HtmlDecode($this->nro_control->CurrentValue);
        }
        $this->nro_control->EditValue = $this->nro_control->CurrentValue;
        $this->nro_control->PlaceHolder = RemoveHtml($this->nro_control->caption());

        // fecha
        $this->fecha->setupEditAttributes();
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // proveedor
        $this->proveedor->setupEditAttributes();
        $curVal = strval($this->proveedor->CurrentValue);
        if ($curVal != "") {
            $this->proveedor->EditValue = $this->proveedor->lookupCacheOption($curVal);
            if ($this->proveedor->EditValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->proveedor->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->proveedor->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->EditValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->EditValue = $this->proveedor->CurrentValue;
                }
            }
        } else {
            $this->proveedor->EditValue = null;
        }

        // almacen
        $this->almacen->setupEditAttributes();
        $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

        // monto_total
        $this->monto_total->setupEditAttributes();
        $this->monto_total->EditValue = $this->monto_total->CurrentValue;
        $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, $this->monto_total->formatPattern());

        // alicuota_iva
        $this->alicuota_iva->setupEditAttributes();
        $this->alicuota_iva->EditValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->EditValue = FormatNumber($this->alicuota_iva->EditValue, $this->alicuota_iva->formatPattern());

        // iva
        $this->iva->setupEditAttributes();
        $this->iva->EditValue = $this->iva->CurrentValue;
        $this->iva->EditValue = FormatNumber($this->iva->EditValue, $this->iva->formatPattern());

        // total
        $this->total->setupEditAttributes();
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->EditValue = FormatNumber($this->total->EditValue, $this->total->formatPattern());

        // documento
        $this->documento->setupEditAttributes();
        $this->documento->EditValue = $this->documento->options(true);
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

        // nota
        $this->nota->setupEditAttributes();
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // estatus
        $this->estatus->setupEditAttributes();
        $this->estatus->EditValue = $this->estatus->options(true);
        $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

        // username
        $this->_username->setupEditAttributes();
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // id_documento_padre
        $this->id_documento_padre->setupEditAttributes();
        $this->id_documento_padre->EditValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->PlaceHolder = RemoveHtml($this->id_documento_padre->caption());
        if (strval($this->id_documento_padre->EditValue) != "" && is_numeric($this->id_documento_padre->EditValue)) {
            $this->id_documento_padre->EditValue = $this->id_documento_padre->EditValue;
        }

        // moneda
        $this->moneda->setupEditAttributes();
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // consignacion
        $this->consignacion->setupEditAttributes();
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // consignacion_reportada
        $this->consignacion_reportada->setupEditAttributes();
        $this->consignacion_reportada->EditValue = $this->consignacion_reportada->options(true);
        $this->consignacion_reportada->PlaceHolder = RemoveHtml($this->consignacion_reportada->caption());

        // aplica_retencion
        $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
        $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

        // ret_iva
        $this->ret_iva->setupEditAttributes();
        $this->ret_iva->EditValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->PlaceHolder = RemoveHtml($this->ret_iva->caption());
        if (strval($this->ret_iva->EditValue) != "" && is_numeric($this->ret_iva->EditValue)) {
            $this->ret_iva->EditValue = FormatNumber($this->ret_iva->EditValue, null);
        }

        // ref_iva
        $this->ref_iva->setupEditAttributes();
        if (!$this->ref_iva->Raw) {
            $this->ref_iva->CurrentValue = HtmlDecode($this->ref_iva->CurrentValue);
        }
        $this->ref_iva->EditValue = $this->ref_iva->CurrentValue;
        $this->ref_iva->PlaceHolder = RemoveHtml($this->ref_iva->caption());

        // ret_islr
        $this->ret_islr->setupEditAttributes();
        $this->ret_islr->EditValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->PlaceHolder = RemoveHtml($this->ret_islr->caption());
        if (strval($this->ret_islr->EditValue) != "" && is_numeric($this->ret_islr->EditValue)) {
            $this->ret_islr->EditValue = FormatNumber($this->ret_islr->EditValue, null);
        }

        // ref_islr
        $this->ref_islr->setupEditAttributes();
        if (!$this->ref_islr->Raw) {
            $this->ref_islr->CurrentValue = HtmlDecode($this->ref_islr->CurrentValue);
        }
        $this->ref_islr->EditValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->PlaceHolder = RemoveHtml($this->ref_islr->caption());

        // ret_municipal
        $this->ret_municipal->setupEditAttributes();
        $this->ret_municipal->EditValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->PlaceHolder = RemoveHtml($this->ret_municipal->caption());
        if (strval($this->ret_municipal->EditValue) != "" && is_numeric($this->ret_municipal->EditValue)) {
            $this->ret_municipal->EditValue = FormatNumber($this->ret_municipal->EditValue, null);
        }

        // ref_municipal
        $this->ref_municipal->setupEditAttributes();
        if (!$this->ref_municipal->Raw) {
            $this->ref_municipal->CurrentValue = HtmlDecode($this->ref_municipal->CurrentValue);
        }
        $this->ref_municipal->EditValue = $this->ref_municipal->CurrentValue;
        $this->ref_municipal->PlaceHolder = RemoveHtml($this->ref_municipal->caption());

        // monto_pagar
        $this->monto_pagar->setupEditAttributes();
        $this->monto_pagar->EditValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->PlaceHolder = RemoveHtml($this->monto_pagar->caption());
        if (strval($this->monto_pagar->EditValue) != "" && is_numeric($this->monto_pagar->EditValue)) {
            $this->monto_pagar->EditValue = FormatNumber($this->monto_pagar->EditValue, null);
        }

        // comprobante
        $this->comprobante->setupEditAttributes();
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

        // tipo_iva
        $this->tipo_iva->setupEditAttributes();
        if (!$this->tipo_iva->Raw) {
            $this->tipo_iva->CurrentValue = HtmlDecode($this->tipo_iva->CurrentValue);
        }
        $this->tipo_iva->EditValue = $this->tipo_iva->CurrentValue;
        $this->tipo_iva->PlaceHolder = RemoveHtml($this->tipo_iva->caption());

        // tipo_islr
        $this->tipo_islr->setupEditAttributes();
        if (!$this->tipo_islr->Raw) {
            $this->tipo_islr->CurrentValue = HtmlDecode($this->tipo_islr->CurrentValue);
        }
        $this->tipo_islr->EditValue = $this->tipo_islr->CurrentValue;
        $this->tipo_islr->PlaceHolder = RemoveHtml($this->tipo_islr->caption());

        // sustraendo
        $this->sustraendo->setupEditAttributes();
        $this->sustraendo->EditValue = $this->sustraendo->CurrentValue;
        $this->sustraendo->PlaceHolder = RemoveHtml($this->sustraendo->caption());
        if (strval($this->sustraendo->EditValue) != "" && is_numeric($this->sustraendo->EditValue)) {
            $this->sustraendo->EditValue = FormatNumber($this->sustraendo->EditValue, null);
        }

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->setupEditAttributes();
        $this->fecha_registro_retenciones->EditValue = FormatDateTime($this->fecha_registro_retenciones->CurrentValue, $this->fecha_registro_retenciones->formatPattern());
        $this->fecha_registro_retenciones->PlaceHolder = RemoveHtml($this->fecha_registro_retenciones->caption());

        // tasa_dia
        $this->tasa_dia->setupEditAttributes();
        $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->PlaceHolder = RemoveHtml($this->tasa_dia->caption());
        if (strval($this->tasa_dia->EditValue) != "" && is_numeric($this->tasa_dia->EditValue)) {
            $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, null);
        }

        // monto_usd
        $this->monto_usd->setupEditAttributes();
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, null);
        }

        // fecha_libro_compra
        $this->fecha_libro_compra->setupEditAttributes();
        $this->fecha_libro_compra->EditValue = FormatDateTime($this->fecha_libro_compra->CurrentValue, $this->fecha_libro_compra->formatPattern());
        $this->fecha_libro_compra->PlaceHolder = RemoveHtml($this->fecha_libro_compra->caption());

        // tipo_municipal
        $this->tipo_municipal->setupEditAttributes();
        if (!$this->tipo_municipal->Raw) {
            $this->tipo_municipal->CurrentValue = HtmlDecode($this->tipo_municipal->CurrentValue);
        }
        $this->tipo_municipal->EditValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->PlaceHolder = RemoveHtml($this->tipo_municipal->caption());

        // cerrado
        $this->cerrado->EditValue = $this->cerrado->options(false);
        $this->cerrado->PlaceHolder = RemoveHtml($this->cerrado->caption());

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
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->consignacion_reportada);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->fecha_libro_compra);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->archivo_pedido);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->almacen);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->id_documento_padre);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->consignacion_reportada);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->fecha_registro_retenciones);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->fecha_libro_compra);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->cerrado);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->archivo_pedido);
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
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->consignacion_reportada);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->fecha_libro_compra);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->archivo_pedido);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->almacen);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->id_documento_padre);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->consignacion_reportada);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->fecha_registro_retenciones);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->fecha_libro_compra);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->cerrado);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->archivo_pedido);
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
        if ($fldparm == 'archivo_pedido') {
            $fldName = "archivo_pedido";
            $fileNameFld = "archivo_pedido";
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
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'entradas');
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
                WriteAuditLog($usr, "A", 'entradas', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'entradas', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'entradas', $fldname, $key, $oldvalue);
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
    public function recordsetSelecting(&$filter) {
    	// Enter your code here
    	AddFilter($filter, "IFNULL(documento, '') <> 'TR'");
    	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
    	$grupo = trim(ExecuteScalar($sql));
    	$proveedor = 0;
    	if($grupo == "PROVEEDOR") {
    		$sql = "SELECT proveedor FROM usuario WHERE username = '" . CurrentUserName() . "';";
    		$proveedor = trim(ExecuteScalar($sql));
    		AddFilter($filter, "proveedor = $proveedor AND tipo_documento = 'TDCFCC'");
    	}
    	else {
    		if(!isset($_REQUEST["tipo"]) or trim($_REQUEST["tipo"]) == "") {
    			$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    			$tipo = ExecuteScalar($sql);
    		}
    		else $tipo = $_REQUEST["tipo"];
    		AddFilter($filter, "tipo_documento = '$tipo'");
    		$sql = "DELETE FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    		Execute($sql);
    		$sql = "INSERT INTO username_tipo_documento
    				(id, username, tipo_documento)
    				VALUES (NULL, '" . CurrentUserName() . "', '$tipo');";
    		Execute($sql);
    		if(trim($tipo) == "") {
    			header("Location: error_page.php");
    			die();
    		}
    	}
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
    public function rowInserting($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);

    	// Se obtiene el consecutivo del tipo de documento
    	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM entradas WHERE tipo_documento = '$tipo';";
    	$consecutivo = intval(ExecuteScalar($sql)) + 1;
    	switch($tipo) {
    	case "TDCPDC":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			if(strpos(strtoupper($rsnew["archivo_pedido"]), ".CSV") == false) {
    				$this->CancelMessage = "El archivo a subir con los art&iacute;culo debe ser .csv. Verifique!";
    				return FALSE;
    			}
    		}	
    		break;
    	case "TDCNRP":
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	case "TDCFCC":
    		// Valido cierre de facturación
    		$sql = "SELECT DISTINCT cerrado AS cerrado FROM entradas WHERE YEAR(fecha) = " . intval(substr($rsnew["fecha"], 0, 4)) . " AND MONTH(fecha) = " . intval(substr($rsnew["fecha"], 5, 2)) . ";";
    		if($row = ExecuteRow($sql)) {
    			if($row["cerrado"] == "S") {
    				$this->CancelMessage = "El mes en el que se va a crear la factura est&aacute; cerrado. Verifique!";
    				return FALSE;
    			}
    		}

    		// Valido que ya no se haya registrado el número de factura 
    		$proveedor = $rsnew["proveedor"];
    		$rsnew["nro_documento"] = trim($rsnew["nro_documento"]);
    		$documento = $rsnew["nro_documento"];
    		$sql = "SELECT documento FROM entradas WHERE tipo_documento = 'TDCFCC' AND proveedor = $proveedor AND nro_documento = '$documento';";
    		if($row = ExecuteRow($sql)){
    			$this->CancelMessage = "El n&uacute;mero de factura ya est&aacute; registrado para el proveedor; verifique.";
    			return FALSE;
    		}
    		break;
    	case "TDCAEN":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			if(strpos(strtoupper($rsnew["archivo_pedido"]), ".CSV") == false) {
    				$this->CancelMessage = "El archivo a subir con los art&iacute;culo debe ser .csv. Verifique!";
    				return FALSE;
    			}
    		}	
    		break;
    	case "TDCASA":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	}

    	// Se establecen valores por defecto a varibles bitácora y estatus
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["estatus"] = "NUEVO";
    	$rsnew["fecha_libro_compra"] = $rsnew["fecha"];
    	if($rsnew["documento"] == "FC" or trim($rsnew["documento"]) == "") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	if(isset($rsnew["consignacion"])) {
    		if(trim($rsnew["consignacion"]) != "S") $rsnew["consignacion"] = "N";
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew) {
    	//echo "Row Inserted"
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDC":
    		/**** Almacen por defecto ****/
    		$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
    		$almacen = ExecuteScalar($sql);

    		// Asigna a la compra detalles proveniente de los articulos que por defecto vende el proveedor
    		$sql = "SELECT COUNT(id) AS cantidad
    				FROM entradas_salidas
    				WHERE id_documento = '" . $rsnew["id"] . "'
    					AND tipo_documento = '" . $rsnew["tipo_documento"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad == 0) {
    			$sql = "INSERT INTO entradas_salidas
    				(id, tipo_documento, id_documento, fabricante, 
    				articulo, articulo_unidad_medida, 
    				cantidad_unidad_medida, almacen, cantidad_articulo)
    			SELECT 
    				NULL, 'TDCPDC', '" . $rsnew["id"] . "', a.fabricante,
    				a.articulo, b.unidad_medida_defecto, 
    				b.cantidad_por_unidad_medida, '$almacen', 0 
    			FROM 
    				proveedor_articulo AS a 
    				JOIN articulo AS b ON b.id = a.articulo 
    			WHERE 
    				a.proveedor = '" . $rsnew["proveedor"] . "';"; 
    			Execute($sql);
    		}

    		//// **** Proceso para agregar detalle desde el archivo de texto ////
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			$linea = 0;
    			//Abrimos nuestro archivo
    			$file01 = "carpetacarga/" . $rsnew["archivo_pedido"];
    			$archivo = fopen($file01, "r");
    			//Lo recorremos
    			$codigo = "";
    			$nombre = "";
    			$precio = 0.00;
    			// include_once("include/pdv_linea_guardar.class.php");
    			$insart = new PdcLineaGuardar();
    			while (($datos = fgetcsv($archivo, 0, ";")) == true) {
    				$num = count($datos);
    				//Recorremos las columnas de esa linea
    				if($num == 3) {
    					for($columna = 0; $columna < $num; $columna++) {
    						switch($columna) {
    						case 0:	
    							$cod_articulo = $datos[$columna];
    							break;
    						case 1:	
    							$nombre = $datos[$columna];
    							break;
    						case 2:	
    							$cantidad = intval($datos[$columna]);
    							break;
    						}
    					}
    					$insart->insertar_articulo("TDCPDC", $rsnew["id"], $rsnew["proveedor"], $cod_articulo, $cantidad);
    				}
    				$codigo = "";
    				$nombre = "";
    				$precio = 0.00;
    			}
    			//Cerramos el archivo
    			fclose($archivo);
    		}
    		//// **** **** **** **** **** **** **** **** **** **** **** **** ////
    		break;
    	case "TDCNRP":
    		/*$sql = "INSERT INTO entradas_salidas
    					(id, tipo_documento, id_documento, 
    					fabricante, articulo, alicuota)
    				SELECT 
    					NULL, '" . $rsnew["tipo_documento"] . "', '" . $rsnew["id"] . "', 
    					fabricante, articulo, alicuota 
    				FROM 
    					entradas_salidas 
    				WHERE 
    					id_documento = '" . $rsnew["id"] . "'
    					AND tipo_documento = '" . $rsnew["tipo_documento"] . "';";
    		Execute($sql);*/
    		break;
    	case "TDCFCC":
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '026';";
    		$CmpbIVAAuto = ExecuteScalar($sql);
    		if($CmpbIVAAuto == "S") {
    			$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '023';";
    			$row = ExecuteRow($sql);
        		$numero = intval($row["valor1"]) + 1;
    			$prefijo = trim($row["valor2"]);
    			$prefijo .= substr($rsnew["fecha"], 0, 4) . substr($rsnew["fecha"], 5, 2);
    			$padeo = intval($row["valor3"]);
    			$comprobante = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT);
    			$sql = "UPDATE parametro SET valor1='$numero' 
    					WHERE codigo = '023';";
    			Execute($sql);
    			$sql = "UPDATE entradas SET ref_iva = '$comprobante' 
    					WHERE id = '" . $rsnew["id"] . "';";
    			Execute($sql);
    		}
    		break;
    	case "TDCAEN":
    		//// **** Proceso para agregar detalle desde el archivo de texto ////
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			$linea = 0;
    			//Abrimos nuestro archivo
    			$file01 = "carpetacarga/" . $rsnew["archivo_pedido"];
    			$archivo = fopen($file01, "r");
    			//Lo recorremos
    			$codigo = "";
    			$nombre = "";
    			$precio = 0.00;
    			// include_once("include/pdv_linea_guardar.class.php");
    			$insart = new PdcLineaGuardar();
    			while (($datos = fgetcsv($archivo, 0, ";")) == true) {
    				$num = count($datos);
    				//Recorremos las columnas de esa linea
    				if($num == 3) {
    					for($columna = 0; $columna < $num; $columna++) {
    						switch($columna) {
    						case 0:	
    							$cod_articulo = $datos[$columna];
    							break;
    						case 1:	
    							$nombre = $datos[$columna];
    							break;
    						case 2:	
    							$cantidad = intval($datos[$columna]);
    							break;
    						}
    					}
    					$insart->insertar_articulo("TDCAEN", $rsnew["id"], $rsnew["proveedor"], $cod_articulo, $cantidad);
    				}
    				$codigo = "";
    				$nombre = "";
    				$precio = 0.00;
    			}
    			//Cerramos el archivo
    			fclose($archivo);
    		}
    		if(trim($rsnew["almacen"]) != "") {
    			$sql = "UPDATE entradas_salidas
    					SET almacen = '" . $rsnew["almacen"] . "'
    					WHERE id_documento = '" . $rsnew["id"] . "' AND tipo_documento = '" . $rsnew["tipo_documento"] . "';";
    			Execute($sql);
    		}
    		//// **** **** **** **** **** **** **** **** **** **** **** **** ////
    		break;
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if(CurrentUserLevel() != -1) {
    		if($rsold["estatus"] != "NUEVO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulado; no se puede modificar.";
    			return FALSE;
    		}
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		// Valido cierre de facturación
    		if($rsold["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    	}
    	if(($rsnew["fecha_registro_retenciones"] == "" or $rsnew["fecha_registro_retenciones"] == "0000-00-00") and (trim($rsold["ref_iva"]) == "" or trim($rsold["ref_islr"] == ""))) {
    		$rsnew["fecha_registro_retenciones"] = date("Y-m-d");
    	}
        if(isset($rsnew["documento"])) {
            if($rsnew["documento"] == "FC" or trim($rsnew["documento"]) == "") {
                $rsnew["doc_afectado"] == ""; 
            } 
            else {
                if($rsnew["doc_afectado"] == "") {
                    $this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
                    return FALSE;
                }
            }
        }
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		if($rsold["ref_iva"] != "") {
    			if($rsold["ref_iva"] != $rsnew["ref_iva"]) {
    				if(!VerificaFuncion('016')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de IVA; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["ref_islr"] != "") {
    			if($rsold["ref_islr"] != $rsnew["ref_islr"]) {
    				if(!VerificaFuncion('017')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de ISLR; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["ref_municipal"] != "") {
    			if($rsold["ref_municipal"] != $rsnew["ref_municipal"]) {
    				if(!VerificaFuncion('018')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de Impuesto Municipal; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede modificar.";
    			return FALSE;
    		}
    	}
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew) {
    	//echo "Row Updated";
    	if($rsold["consignacion"] != $rsnew["consignacion"]) {
    		if($rsnew["consignacion"] == "S") {
    			$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '014';";
    			$almacen = ExecuteScalar($sql);
    		}
    		else {
    			$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
    			$almacen = ExecuteScalar($sql);
    		}
    		$sql = "UPDATE entradas_salidas
    				SET
    					almacen='$almacen' 
    				WHERE
    					tipo_documento = '" . $rsold["tipo_documento"] . "' AND id_documento = " . $rsold["id"] . "";
    		Execute($sql);
    	}

    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE tipo_documento = '" . $rsold["tipo_documento"] . "'
    				AND id_documento = " . $rsold["id"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '" . $rsold["tipo_documento"] . "'
    					AND id_documento = " . $rsold["id"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		CalcularRetenciones($rsold["id"], $rsold["tipo_documento"]);
    	}
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
    public function rowDeleting(&$rs) {
    	// Enter your code here
    	// To cancel, set return value to False
    	if(CurrentUserLevel() != -1) {
    		if($rs["estatus"] != "NUEVO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulado; no se puede eliminar.";
    			return FALSE;
    		}
    	}
    	if($rs["tipo_documento"] == "TDCFCC") {
    		// Valido cierre de facturación
    		if($rs["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    		if($rs["ref_iva"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de IVA asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["ref_islr"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de ISLR asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["ref_municipal"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de Impuesto Municipal asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede eliminar.";
    			return FALSE;
    		}
    	}
    	$sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '" . $rs["tipo_documento"] . "' AND id_documento = '" . $rs["id"] . "';";
    	Execute($sql);
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted($rs) {
    	//echo "Row Deleted";
    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	/*$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE
    				tipo_documento = '" . $rs["tipo_documento"] . "'
    				AND id_documento = " . $rs["id"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '" . $rs["tipo_documento"] . "'
    					AND id_documento = " . $rs["id"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}*/
    	ActualizarExitencia();
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
    public function rowRendered() {
    	// To view properties of field class, use:
    	//var_dump($this-><FieldName>); 

    	/*switch($_SESSION["tipo_documento"]) {
    	case "TDCPDC":
    		break;
    	case "TDCNRP":
    		break;
    	case "TDCFCC":
    		break;
    	case "TDCAEN":
    		break;
    	}*/
    	$color = "";
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->estatus->ViewValue == "NUEVO") { 
    			$color = "background-color: #eda135; color: #ffffff;";
    		} elseif ($this->estatus->ViewValue == "PROCESADO") {
    			$color = "background-color: #51aa51; color: #ffffff;";
    		} elseif ($this->estatus->ViewValue == "ANULADO") {
    			$color = "background-color: #cc3f3b; color: #ffffff;";
    		}
    		$this->tipo_documento->CellAttrs["style"] = $color;
    		$this->nro_documento->CellAttrs["style"] = $color;
    		$this->estatus->CellAttrs["style"] = $color;
    		$this->nro_control->CellAttrs["style"] = $color;
    		$this->fecha->CellAttrs["style"] = $color;
    		$this->total->CellAttrs["style"] = $color;
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
