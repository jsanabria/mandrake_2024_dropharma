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
 * Table class for salidas
 */
class Salidas extends DbTable
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
    public $nro_control;
    public $fecha;
    public $cliente;
    public $documento;
    public $doc_afectado;
    public $moneda;
    public $monto_total;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $tasa_dia;
    public $monto_usd;
    public $lista_pedido;
    public $nota;
    public $_username;
    public $estatus;
    public $asesor;
    public $dias_credito;
    public $entregado;
    public $fecha_entrega;
    public $pagado;
    public $bultos;
    public $fecha_bultos;
    public $user_bultos;
    public $fecha_despacho;
    public $user_despacho;
    public $consignacion;
    public $unidades;
    public $descuento;
    public $monto_sin_descuento;
    public $factura;
    public $ci_rif;
    public $nombre;
    public $direccion;
    public $telefono;
    public $_email;
    public $activo;
    public $comprobante;
    public $nro_despacho;
    public $cerrado;
    public $asesor_asignado;
    public $tasa_indexada;
    public $id_documento_padre_nd;
    public $id_documento_padre;
    public $archivo_pedido;
    public $checker;
    public $checker_date;
    public $packer;
    public $packer_date;
    public $fotos;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "salidas";
        $this->TableName = 'salidas';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "salidas";
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
        $this->tipo_documento->addMethod("getSelectFilter", fn() => "`tipo` = 'CLIENTE' AND codigo IN (SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "')");
        $this->tipo_documento->InputTextType = "text";
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->setSelectMultiple(false); // Select one
        $this->tipo_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento->Lookup = new Lookup($this->tipo_documento, 'tipo_documento', false, 'codigo', ["descripcion","","",""], '', '', [], [], [], [], [], [], false, '`descripcion`', '', "`descripcion`");
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
        $this->nro_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // nro_control
        $this->nro_control = new DbField(
            $this, // Table
            'x_nro_control', // Variable name
            'nro_control', // Name
            '`nro_control`', // Expression
            '`nro_control`', // Basic search expression
            200, // Type
            100, // Size
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

        // cliente
        $this->cliente = new DbField(
            $this, // Table
            'x_cliente', // Variable name
            'cliente', // Name
            '`cliente`', // Expression
            '`cliente`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cliente`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->cliente->addMethod("getSelectFilter", fn() => FiltraClientes());
        $this->cliente->InputTextType = "text";
        $this->cliente->Raw = true;
        $this->cliente->Required = true; // Required field
        $this->cliente->setSelectMultiple(false); // Select one
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup($this->cliente, 'cliente', false, 'id', ["nombre","sucursal","",""], '', '', [], [], [], [], [], [], false, '`nombre`', '', "CONCAT(COALESCE(`nombre`, ''),'" . ValueSeparator(1, $this->cliente) . "',COALESCE(`sucursal`,''))");
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cliente'] = &$this->cliente;

        // documento
        $this->documento = new DbField(
            $this, // Table
            'x_documento', // Variable name
            'documento', // Name
            '`documento`', // Expression
            '`documento`', // Basic search expression
            129, // Type
            2, // Size
            -1, // Date/Time format
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
        $this->documento->Lookup = new Lookup($this->documento, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->documento->OptionCount = 3;
        $this->documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['documento'] = &$this->documento;

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
        $this->monto_total->addMethod("getLinkPrefix", fn() => "ImagenesCliente?id=");
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

        // lista_pedido
        $this->lista_pedido = new DbField(
            $this, // Table
            'x_lista_pedido', // Variable name
            'lista_pedido', // Name
            '`lista_pedido`', // Expression
            '`lista_pedido`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`lista_pedido`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->lista_pedido->addMethod("getSelectFilter", fn() => "`tabla` = 'LISTA_PEDIDO'");
        $this->lista_pedido->InputTextType = "text";
        $this->lista_pedido->Required = true; // Required field
        $this->lista_pedido->setSelectMultiple(false); // Select one
        $this->lista_pedido->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->lista_pedido->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->lista_pedido->Lookup = new Lookup($this->lista_pedido, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion` ASC', '', "`campo_descripcion`");
        $this->lista_pedido->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['lista_pedido'] = &$this->lista_pedido;

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
        $this->estatus->Lookup = new Lookup($this->estatus, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->estatus->OptionCount = 3;
        $this->estatus->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['estatus'] = &$this->estatus;

        // asesor
        $this->asesor = new DbField(
            $this, // Table
            'x_asesor', // Variable name
            'asesor', // Name
            '`asesor`', // Expression
            '`asesor`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`asesor`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->asesor->InputTextType = "text";
        $this->asesor->setSelectMultiple(false); // Select one
        $this->asesor->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->asesor->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->asesor->Lookup = new Lookup($this->asesor, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nombre`");
        $this->asesor->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['asesor'] = &$this->asesor;

        // dias_credito
        $this->dias_credito = new DbField(
            $this, // Table
            'x_dias_credito', // Variable name
            'dias_credito', // Name
            '`dias_credito`', // Expression
            '`dias_credito`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`dias_credito`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->dias_credito->addMethod("getSelectFilter", fn() => "`codigo` = '007'");
        $this->dias_credito->InputTextType = "text";
        $this->dias_credito->Raw = true;
        $this->dias_credito->Required = true; // Required field
        $this->dias_credito->setSelectMultiple(false); // Select one
        $this->dias_credito->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->dias_credito->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->dias_credito->Lookup = new Lookup($this->dias_credito, 'parametro', false, 'valor1', ["valor1","","",""], '', '', [], [], [], [], [], [], false, '`valor1` ASC', '', "`valor1`");
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_credito'] = &$this->dias_credito;

        // entregado
        $this->entregado = new DbField(
            $this, // Table
            'x_entregado', // Variable name
            'entregado', // Name
            '`entregado`', // Expression
            '`entregado`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`entregado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->entregado->addMethod("getDefault", fn() => "N");
        $this->entregado->InputTextType = "text";
        $this->entregado->Raw = true;
        $this->entregado->Required = true; // Required field
        $this->entregado->setSelectMultiple(false); // Select one
        $this->entregado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->entregado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->entregado->Lookup = new Lookup($this->entregado, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->entregado->OptionCount = 2;
        $this->entregado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['entregado'] = &$this->entregado;

        // fecha_entrega
        $this->fecha_entrega = new DbField(
            $this, // Table
            'x_fecha_entrega', // Variable name
            'fecha_entrega', // Name
            '`fecha_entrega`', // Expression
            CastDateFieldForLike("`fecha_entrega`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_entrega`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_entrega->InputTextType = "text";
        $this->fecha_entrega->Raw = true;
        $this->fecha_entrega->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_entrega->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_entrega'] = &$this->fecha_entrega;

        // pagado
        $this->pagado = new DbField(
            $this, // Table
            'x_pagado', // Variable name
            'pagado', // Name
            '`pagado`', // Expression
            '`pagado`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`pagado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->pagado->addMethod("getDefault", fn() => "N");
        $this->pagado->InputTextType = "text";
        $this->pagado->Raw = true;
        $this->pagado->Required = true; // Required field
        $this->pagado->setSelectMultiple(false); // Select one
        $this->pagado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->pagado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->pagado->Lookup = new Lookup($this->pagado, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->pagado->OptionCount = 2;
        $this->pagado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['pagado'] = &$this->pagado;

        // bultos
        $this->bultos = new DbField(
            $this, // Table
            'x_bultos', // Variable name
            'bultos', // Name
            '`bultos`', // Expression
            '`bultos`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`bultos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->bultos->addMethod("getDefault", fn() => 0);
        $this->bultos->InputTextType = "text";
        $this->bultos->Raw = true;
        $this->bultos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->bultos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['bultos'] = &$this->bultos;

        // fecha_bultos
        $this->fecha_bultos = new DbField(
            $this, // Table
            'x_fecha_bultos', // Variable name
            'fecha_bultos', // Name
            '`fecha_bultos`', // Expression
            CastDateFieldForLike("`fecha_bultos`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_bultos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_bultos->addMethod("getDefault", fn() => "0000-00-00 00:00:00");
        $this->fecha_bultos->InputTextType = "text";
        $this->fecha_bultos->Raw = true;
        $this->fecha_bultos->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_bultos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_bultos'] = &$this->fecha_bultos;

        // user_bultos
        $this->user_bultos = new DbField(
            $this, // Table
            'x_user_bultos', // Variable name
            'user_bultos', // Name
            '`user_bultos`', // Expression
            '`user_bultos`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`user_bultos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->user_bultos->InputTextType = "text";
        $this->user_bultos->Lookup = new Lookup($this->user_bultos, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nombre`");
        $this->user_bultos->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['user_bultos'] = &$this->user_bultos;

        // fecha_despacho
        $this->fecha_despacho = new DbField(
            $this, // Table
            'x_fecha_despacho', // Variable name
            'fecha_despacho', // Name
            '`fecha_despacho`', // Expression
            CastDateFieldForLike("`fecha_despacho`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_despacho`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_despacho->addMethod("getDefault", fn() => "0000-00-00 00:00:00");
        $this->fecha_despacho->InputTextType = "text";
        $this->fecha_despacho->Raw = true;
        $this->fecha_despacho->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_despacho->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_despacho'] = &$this->fecha_despacho;

        // user_despacho
        $this->user_despacho = new DbField(
            $this, // Table
            'x_user_despacho', // Variable name
            'user_despacho', // Name
            '`user_despacho`', // Expression
            '`user_despacho`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`user_despacho`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->user_despacho->InputTextType = "text";
        $this->user_despacho->Lookup = new Lookup($this->user_despacho, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nombre`");
        $this->user_despacho->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['user_despacho'] = &$this->user_despacho;

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
        $this->consignacion->addMethod("getDefault", fn() => "N");
        $this->consignacion->InputTextType = "text";
        $this->consignacion->Raw = true;
        $this->consignacion->setSelectMultiple(false); // Select one
        $this->consignacion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion->Lookup = new Lookup($this->consignacion, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->consignacion->OptionCount = 2;
        $this->consignacion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['consignacion'] = &$this->consignacion;

        // unidades
        $this->unidades = new DbField(
            $this, // Table
            'x_unidades', // Variable name
            'unidades', // Name
            '`unidades`', // Expression
            '`unidades`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`unidades`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->unidades->InputTextType = "text";
        $this->unidades->Raw = true;
        $this->unidades->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->unidades->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['unidades'] = &$this->unidades;

        // descuento
        $this->descuento = new DbField(
            $this, // Table
            'x_descuento', // Variable name
            'descuento', // Name
            '`descuento`', // Expression
            '`descuento`', // Basic search expression
            131, // Type
            8, // Size
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

        // monto_sin_descuento
        $this->monto_sin_descuento = new DbField(
            $this, // Table
            'x_monto_sin_descuento', // Variable name
            'monto_sin_descuento', // Name
            '`monto_sin_descuento`', // Expression
            '`monto_sin_descuento`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_sin_descuento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_sin_descuento->InputTextType = "text";
        $this->monto_sin_descuento->Raw = true;
        $this->monto_sin_descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_sin_descuento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_sin_descuento'] = &$this->monto_sin_descuento;

        // factura
        $this->factura = new DbField(
            $this, // Table
            'x_factura', // Variable name
            'factura', // Name
            '`factura`', // Expression
            '`factura`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`factura`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->factura->addMethod("getDefault", fn() => "N");
        $this->factura->InputTextType = "text";
        $this->factura->Raw = true;
        $this->factura->Required = true; // Required field
        $this->factura->setSelectMultiple(false); // Select one
        $this->factura->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->factura->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->factura->Lookup = new Lookup($this->factura, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->factura->OptionCount = 2;
        $this->factura->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['factura'] = &$this->factura;

        // ci_rif
        $this->ci_rif = new DbField(
            $this, // Table
            'x_ci_rif', // Variable name
            'ci_rif', // Name
            '`ci_rif`', // Expression
            '`ci_rif`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ci_rif`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ci_rif->InputTextType = "text";
        $this->ci_rif->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ci_rif'] = &$this->ci_rif;

        // nombre
        $this->nombre = new DbField(
            $this, // Table
            'x_nombre', // Variable name
            'nombre', // Name
            '`nombre`', // Expression
            '`nombre`', // Basic search expression
            200, // Type
            80, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nombre`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nombre->InputTextType = "text";
        $this->nombre->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nombre'] = &$this->nombre;

        // direccion
        $this->direccion = new DbField(
            $this, // Table
            'x_direccion', // Variable name
            'direccion', // Name
            '`direccion`', // Expression
            '`direccion`', // Basic search expression
            200, // Type
            150, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`direccion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->direccion->InputTextType = "text";
        $this->direccion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['direccion'] = &$this->direccion;

        // telefono
        $this->telefono = new DbField(
            $this, // Table
            'x_telefono', // Variable name
            'telefono', // Name
            '`telefono`', // Expression
            '`telefono`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`telefono`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->telefono->InputTextType = "text";
        $this->telefono->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['telefono'] = &$this->telefono;

        // email
        $this->_email = new DbField(
            $this, // Table
            'x__email', // Variable name
            'email', // Name
            '`email`', // Expression
            '`email`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`email`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->_email->InputTextType = "text";
        $this->_email->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['email'] = &$this->_email;

        // activo
        $this->activo = new DbField(
            $this, // Table
            'x_activo', // Variable name
            'activo', // Name
            '`activo`', // Expression
            '`activo`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`activo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->activo->addMethod("getDefault", fn() => "S");
        $this->activo->InputTextType = "text";
        $this->activo->Raw = true;
        $this->activo->Lookup = new Lookup($this->activo, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->activo->OptionCount = 2;
        $this->activo->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['activo'] = &$this->activo;

        // comprobante
        $this->comprobante = new DbField(
            $this, // Table
            'x_comprobante', // Variable name
            'comprobante', // Name
            '`comprobante`', // Expression
            '`comprobante`', // Basic search expression
            19, // Type
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
        $this->comprobante->addMethod("getLinkPrefix", fn() => "ContAsientoList?showmaster=cont_comprobante&fk_id=");
        $this->comprobante->InputTextType = "text";
        $this->comprobante->Raw = true;
        $this->comprobante->Lookup = new Lookup($this->comprobante, 'cont_comprobante', false, 'id', ["id","descripcion","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`id`, ''),'" . ValueSeparator(1, $this->comprobante) . "',COALESCE(`descripcion`,''))");
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['comprobante'] = &$this->comprobante;

        // nro_despacho
        $this->nro_despacho = new DbField(
            $this, // Table
            'x_nro_despacho', // Variable name
            'nro_despacho', // Name
            '`nro_despacho`', // Expression
            '`nro_despacho`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nro_despacho`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nro_despacho->InputTextType = "text";
        $this->nro_despacho->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nro_despacho'] = &$this->nro_despacho;

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
        $this->cerrado->Lookup = new Lookup($this->cerrado, 'salidas', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->cerrado->OptionCount = 2;
        $this->cerrado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['cerrado'] = &$this->cerrado;

        // asesor_asignado
        $this->asesor_asignado = new DbField(
            $this, // Table
            'x_asesor_asignado', // Variable name
            'asesor_asignado', // Name
            '`asesor_asignado`', // Expression
            '`asesor_asignado`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`asesor_asignado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->asesor_asignado->addMethod("getSelectFilter", fn() => "IFNULl(asesor, 0) > 0");
        $this->asesor_asignado->InputTextType = "text";
        $this->asesor_asignado->Required = true; // Required field
        $this->asesor_asignado->setSelectMultiple(false); // Select one
        $this->asesor_asignado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->asesor_asignado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->asesor_asignado->Lookup = new Lookup($this->asesor_asignado, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`nombre` ASC', '', "`nombre`");
        $this->asesor_asignado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['asesor_asignado'] = &$this->asesor_asignado;

        // tasa_indexada
        $this->tasa_indexada = new DbField(
            $this, // Table
            'x_tasa_indexada', // Variable name
            'tasa_indexada', // Name
            '`tasa_indexada`', // Expression
            '`tasa_indexada`', // Basic search expression
            131, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tasa_indexada`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tasa_indexada->InputTextType = "text";
        $this->tasa_indexada->Raw = true;
        $this->tasa_indexada->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_indexada->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tasa_indexada'] = &$this->tasa_indexada;

        // id_documento_padre_nd
        $this->id_documento_padre_nd = new DbField(
            $this, // Table
            'x_id_documento_padre_nd', // Variable name
            'id_documento_padre_nd', // Name
            '`id_documento_padre_nd`', // Expression
            '`id_documento_padre_nd`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id_documento_padre_nd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id_documento_padre_nd->InputTextType = "text";
        $this->id_documento_padre_nd->Raw = true;
        $this->id_documento_padre_nd->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre_nd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_documento_padre_nd'] = &$this->id_documento_padre_nd;

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
        $this->id_documento_padre->InputTextType = "text";
        $this->id_documento_padre->Raw = true;
        $this->id_documento_padre->Lookup = new Lookup($this->id_documento_padre, 'salidas', false, 'id', ["nro_documento","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nro_documento`");
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

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

        // checker
        $this->checker = new DbField(
            $this, // Table
            'x_checker', // Variable name
            'checker', // Name
            '`checker`', // Expression
            '`checker`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`checker`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->checker->InputTextType = "text";
        $this->checker->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['checker'] = &$this->checker;

        // checker_date
        $this->checker_date = new DbField(
            $this, // Table
            'x_checker_date', // Variable name
            'checker_date', // Name
            '`checker_date`', // Expression
            CastDateFieldForLike("`checker_date`", 11, "DB"), // Basic search expression
            135, // Type
            19, // Size
            11, // Date/Time format
            false, // Is upload field
            '`checker_date`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->checker_date->InputTextType = "text";
        $this->checker_date->Raw = true;
        $this->checker_date->DefaultErrorMessage = str_replace("%s", DateFormat(11), $Language->phrase("IncorrectDate"));
        $this->checker_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['checker_date'] = &$this->checker_date;

        // packer
        $this->packer = new DbField(
            $this, // Table
            'x_packer', // Variable name
            'packer', // Name
            '`packer`', // Expression
            '`packer`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`packer`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->packer->InputTextType = "text";
        $this->packer->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['packer'] = &$this->packer;

        // packer_date
        $this->packer_date = new DbField(
            $this, // Table
            'x_packer_date', // Variable name
            'packer_date', // Name
            '`packer_date`', // Expression
            CastDateFieldForLike("`packer_date`", 11, "DB"), // Basic search expression
            135, // Type
            19, // Size
            11, // Date/Time format
            false, // Is upload field
            '`packer_date`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->packer_date->InputTextType = "text";
        $this->packer_date->Raw = true;
        $this->packer_date->DefaultErrorMessage = str_replace("%s", DateFormat(11), $Language->phrase("IncorrectDate"));
        $this->packer_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['packer_date'] = &$this->packer_date;

        // fotos
        $this->fotos = new DbField(
            $this, // Table
            'x_fotos', // Variable name
            'fotos', // Name
            '`fotos`', // Expression
            '`fotos`', // Basic search expression
            200, // Type
            300, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`fotos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->fotos->InputTextType = "text";
        $this->fotos->UploadMultiple = true;
        $this->fotos->Upload->UploadMultiple = true;
        $this->fotos->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['fotos'] = &$this->fotos;

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
        if ($this->getCurrentDetailTable() == "pagos") {
            $detailUrl = Container("pagos")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "SalidasList";
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "salidas";
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
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->cliente->DbValue = $row['cliente'];
        $this->documento->DbValue = $row['documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->moneda->DbValue = $row['moneda'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->lista_pedido->DbValue = $row['lista_pedido'];
        $this->nota->DbValue = $row['nota'];
        $this->_username->DbValue = $row['username'];
        $this->estatus->DbValue = $row['estatus'];
        $this->asesor->DbValue = $row['asesor'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->entregado->DbValue = $row['entregado'];
        $this->fecha_entrega->DbValue = $row['fecha_entrega'];
        $this->pagado->DbValue = $row['pagado'];
        $this->bultos->DbValue = $row['bultos'];
        $this->fecha_bultos->DbValue = $row['fecha_bultos'];
        $this->user_bultos->DbValue = $row['user_bultos'];
        $this->fecha_despacho->DbValue = $row['fecha_despacho'];
        $this->user_despacho->DbValue = $row['user_despacho'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->unidades->DbValue = $row['unidades'];
        $this->descuento->DbValue = $row['descuento'];
        $this->monto_sin_descuento->DbValue = $row['monto_sin_descuento'];
        $this->factura->DbValue = $row['factura'];
        $this->ci_rif->DbValue = $row['ci_rif'];
        $this->nombre->DbValue = $row['nombre'];
        $this->direccion->DbValue = $row['direccion'];
        $this->telefono->DbValue = $row['telefono'];
        $this->_email->DbValue = $row['email'];
        $this->activo->DbValue = $row['activo'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->nro_despacho->DbValue = $row['nro_despacho'];
        $this->cerrado->DbValue = $row['cerrado'];
        $this->asesor_asignado->DbValue = $row['asesor_asignado'];
        $this->tasa_indexada->DbValue = $row['tasa_indexada'];
        $this->id_documento_padre_nd->DbValue = $row['id_documento_padre_nd'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
        $this->archivo_pedido->Upload->DbValue = $row['archivo_pedido'];
        $this->checker->DbValue = $row['checker'];
        $this->checker_date->DbValue = $row['checker_date'];
        $this->packer->DbValue = $row['packer'];
        $this->packer_date->DbValue = $row['packer_date'];
        $this->fotos->Upload->DbValue = $row['fotos'];
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
        $oldFiles = EmptyValue($row['fotos']) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $row['fotos']);
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->fotos->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->fotos->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("SalidasList");
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
            "SalidasView" => $Language->phrase("View"),
            "SalidasEdit" => $Language->phrase("Edit"),
            "SalidasAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "SalidasList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "SalidasView",
            Config("API_ADD_ACTION") => "SalidasAdd",
            Config("API_EDIT_ACTION") => "SalidasEdit",
            Config("API_DELETE_ACTION") => "SalidasDelete",
            Config("API_LIST_ACTION") => "SalidasList",
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
        return "SalidasList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SalidasView", $parm);
        } else {
            $url = $this->keyUrl("SalidasView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "SalidasAdd?" . $parm;
        } else {
            $url = "SalidasAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SalidasEdit", $parm);
        } else {
            $url = $this->keyUrl("SalidasEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("SalidasList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SalidasAdd", $parm);
        } else {
            $url = $this->keyUrl("SalidasAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("SalidasList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("SalidasDelete", $parm);
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
        $this->checker->setDbValue($row['checker']);
        $this->checker_date->setDbValue($row['checker_date']);
        $this->packer->setDbValue($row['packer']);
        $this->packer_date->setDbValue($row['packer_date']);
        $this->fotos->Upload->DbValue = $row['fotos'];
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "SalidasList";
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

        // nro_control

        // fecha

        // cliente

        // documento

        // doc_afectado

        // moneda

        // monto_total

        // alicuota_iva

        // iva

        // total

        // tasa_dia

        // monto_usd

        // lista_pedido

        // nota

        // username

        // estatus

        // asesor

        // dias_credito

        // entregado

        // fecha_entrega

        // pagado

        // bultos

        // fecha_bultos

        // user_bultos

        // fecha_despacho

        // user_despacho

        // consignacion

        // unidades

        // descuento

        // monto_sin_descuento

        // factura

        // ci_rif

        // nombre

        // direccion

        // telefono

        // email

        // activo

        // comprobante

        // nro_despacho

        // cerrado

        // asesor_asignado

        // tasa_indexada

        // id_documento_padre_nd

        // id_documento_padre

        // archivo_pedido

        // checker

        // checker_date

        // packer

        // packer_date

        // fotos

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

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // nro_documento
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // nro_control
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // cliente
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // documento
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // monto_total
        if (!EmptyValue($this->cliente->CurrentValue)) {
            $this->monto_total->HrefValue = $this->monto_total->getLinkPrefix() . $this->cliente->CurrentValue; // Add prefix/suffix
            $this->monto_total->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->monto_total->HrefValue = FullUrl($this->monto_total->HrefValue, "href");
            }
        } else {
            $this->monto_total->HrefValue = "";
        }
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

        // tasa_dia
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // lista_pedido
        $this->lista_pedido->HrefValue = "";
        $this->lista_pedido->TooltipValue = "";

        // nota
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // username
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // estatus
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // asesor
        $this->asesor->HrefValue = "";
        $this->asesor->TooltipValue = "";

        // dias_credito
        $this->dias_credito->HrefValue = "";
        $this->dias_credito->TooltipValue = "";

        // entregado
        $this->entregado->HrefValue = "";
        $this->entregado->TooltipValue = "";

        // fecha_entrega
        $this->fecha_entrega->HrefValue = "";
        $this->fecha_entrega->TooltipValue = "";

        // pagado
        $this->pagado->HrefValue = "";
        $this->pagado->TooltipValue = "";

        // bultos
        $this->bultos->HrefValue = "";
        $this->bultos->TooltipValue = "";

        // fecha_bultos
        $this->fecha_bultos->HrefValue = "";
        $this->fecha_bultos->TooltipValue = "";

        // user_bultos
        $this->user_bultos->HrefValue = "";
        $this->user_bultos->TooltipValue = "";

        // fecha_despacho
        $this->fecha_despacho->HrefValue = "";
        $this->fecha_despacho->TooltipValue = "";

        // user_despacho
        $this->user_despacho->HrefValue = "";
        $this->user_despacho->TooltipValue = "";

        // consignacion
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // unidades
        $this->unidades->HrefValue = "";
        $this->unidades->TooltipValue = "";

        // descuento
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

        // monto_sin_descuento
        $this->monto_sin_descuento->HrefValue = "";
        $this->monto_sin_descuento->TooltipValue = "";

        // factura
        $this->factura->HrefValue = "";
        $this->factura->TooltipValue = "";

        // ci_rif
        $this->ci_rif->HrefValue = "";
        $this->ci_rif->TooltipValue = "";

        // nombre
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // direccion
        $this->direccion->HrefValue = "";
        $this->direccion->TooltipValue = "";

        // telefono
        $this->telefono->HrefValue = "";
        $this->telefono->TooltipValue = "";

        // email
        $this->_email->HrefValue = "";
        $this->_email->TooltipValue = "";

        // activo
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

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

        // nro_despacho
        $this->nro_despacho->HrefValue = "";
        $this->nro_despacho->TooltipValue = "";

        // cerrado
        $this->cerrado->HrefValue = "";
        $this->cerrado->TooltipValue = "";

        // asesor_asignado
        $this->asesor_asignado->HrefValue = "";
        $this->asesor_asignado->TooltipValue = "";

        // tasa_indexada
        $this->tasa_indexada->HrefValue = "";
        $this->tasa_indexada->TooltipValue = "";

        // id_documento_padre_nd
        $this->id_documento_padre_nd->HrefValue = "";
        $this->id_documento_padre_nd->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

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
        $this->fotos->TooltipValue = "";
        if ($this->fotos->UseColorbox) {
            if (EmptyValue($this->fotos->TooltipValue)) {
                $this->fotos->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->fotos->LinkAttrs["data-rel"] = "salidas_x_fotos";
            $this->fotos->LinkAttrs->appendClass("ew-lightbox");
        }

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
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // nro_documento
        $this->nro_documento->setupEditAttributes();
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

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

        // cliente
        $this->cliente->setupEditAttributes();
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // documento
        $this->documento->setupEditAttributes();
        if (strval($this->documento->CurrentValue) != "") {
            $this->documento->EditValue = $this->documento->optionCaption($this->documento->CurrentValue);
        } else {
            $this->documento->EditValue = null;
        }

        // doc_afectado
        $this->doc_afectado->setupEditAttributes();
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // moneda
        $this->moneda->setupEditAttributes();
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

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

        // lista_pedido
        $this->lista_pedido->setupEditAttributes();
        $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

        // nota
        $this->nota->setupEditAttributes();
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // username
        $this->_username->setupEditAttributes();
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // estatus
        $this->estatus->setupEditAttributes();
        $this->estatus->EditValue = $this->estatus->options(true);
        $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

        // asesor
        $this->asesor->setupEditAttributes();
        $this->asesor->PlaceHolder = RemoveHtml($this->asesor->caption());

        // dias_credito
        $this->dias_credito->setupEditAttributes();
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

        // entregado
        $this->entregado->setupEditAttributes();
        $this->entregado->EditValue = $this->entregado->options(true);
        $this->entregado->PlaceHolder = RemoveHtml($this->entregado->caption());

        // fecha_entrega
        $this->fecha_entrega->setupEditAttributes();
        $this->fecha_entrega->EditValue = FormatDateTime($this->fecha_entrega->CurrentValue, $this->fecha_entrega->formatPattern());
        $this->fecha_entrega->PlaceHolder = RemoveHtml($this->fecha_entrega->caption());

        // pagado
        $this->pagado->setupEditAttributes();
        $this->pagado->EditValue = $this->pagado->options(true);
        $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

        // bultos
        $this->bultos->setupEditAttributes();
        $this->bultos->EditValue = $this->bultos->CurrentValue;
        $this->bultos->PlaceHolder = RemoveHtml($this->bultos->caption());
        if (strval($this->bultos->EditValue) != "" && is_numeric($this->bultos->EditValue)) {
            $this->bultos->EditValue = $this->bultos->EditValue;
        }

        // fecha_bultos
        $this->fecha_bultos->setupEditAttributes();
        $this->fecha_bultos->EditValue = FormatDateTime($this->fecha_bultos->CurrentValue, $this->fecha_bultos->formatPattern());
        $this->fecha_bultos->PlaceHolder = RemoveHtml($this->fecha_bultos->caption());

        // user_bultos
        $this->user_bultos->setupEditAttributes();
        if (!$this->user_bultos->Raw) {
            $this->user_bultos->CurrentValue = HtmlDecode($this->user_bultos->CurrentValue);
        }
        $this->user_bultos->EditValue = $this->user_bultos->CurrentValue;
        $this->user_bultos->PlaceHolder = RemoveHtml($this->user_bultos->caption());

        // fecha_despacho
        $this->fecha_despacho->setupEditAttributes();
        $this->fecha_despacho->EditValue = FormatDateTime($this->fecha_despacho->CurrentValue, $this->fecha_despacho->formatPattern());
        $this->fecha_despacho->PlaceHolder = RemoveHtml($this->fecha_despacho->caption());

        // user_despacho
        $this->user_despacho->setupEditAttributes();
        if (!$this->user_despacho->Raw) {
            $this->user_despacho->CurrentValue = HtmlDecode($this->user_despacho->CurrentValue);
        }
        $this->user_despacho->EditValue = $this->user_despacho->CurrentValue;
        $this->user_despacho->PlaceHolder = RemoveHtml($this->user_despacho->caption());

        // consignacion
        $this->consignacion->setupEditAttributes();
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // unidades
        $this->unidades->setupEditAttributes();
        $this->unidades->EditValue = $this->unidades->CurrentValue;
        $this->unidades->PlaceHolder = RemoveHtml($this->unidades->caption());
        if (strval($this->unidades->EditValue) != "" && is_numeric($this->unidades->EditValue)) {
            $this->unidades->EditValue = $this->unidades->EditValue;
        }

        // descuento
        $this->descuento->setupEditAttributes();
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
        if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
            $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
        }

        // monto_sin_descuento
        $this->monto_sin_descuento->setupEditAttributes();
        $this->monto_sin_descuento->EditValue = $this->monto_sin_descuento->CurrentValue;
        $this->monto_sin_descuento->PlaceHolder = RemoveHtml($this->monto_sin_descuento->caption());
        if (strval($this->monto_sin_descuento->EditValue) != "" && is_numeric($this->monto_sin_descuento->EditValue)) {
            $this->monto_sin_descuento->EditValue = FormatNumber($this->monto_sin_descuento->EditValue, null);
        }

        // factura
        $this->factura->setupEditAttributes();
        $this->factura->EditValue = $this->factura->options(true);
        $this->factura->PlaceHolder = RemoveHtml($this->factura->caption());

        // ci_rif
        $this->ci_rif->setupEditAttributes();
        if (!$this->ci_rif->Raw) {
            $this->ci_rif->CurrentValue = HtmlDecode($this->ci_rif->CurrentValue);
        }
        $this->ci_rif->EditValue = $this->ci_rif->CurrentValue;
        $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

        // nombre
        $this->nombre->setupEditAttributes();
        if (!$this->nombre->Raw) {
            $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
        }
        $this->nombre->EditValue = $this->nombre->CurrentValue;
        $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

        // direccion
        $this->direccion->setupEditAttributes();
        if (!$this->direccion->Raw) {
            $this->direccion->CurrentValue = HtmlDecode($this->direccion->CurrentValue);
        }
        $this->direccion->EditValue = $this->direccion->CurrentValue;
        $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

        // telefono
        $this->telefono->setupEditAttributes();
        if (!$this->telefono->Raw) {
            $this->telefono->CurrentValue = HtmlDecode($this->telefono->CurrentValue);
        }
        $this->telefono->EditValue = $this->telefono->CurrentValue;
        $this->telefono->PlaceHolder = RemoveHtml($this->telefono->caption());

        // email
        $this->_email->setupEditAttributes();
        if (!$this->_email->Raw) {
            $this->_email->CurrentValue = HtmlDecode($this->_email->CurrentValue);
        }
        $this->_email->EditValue = $this->_email->CurrentValue;
        $this->_email->PlaceHolder = RemoveHtml($this->_email->caption());

        // activo
        $this->activo->EditValue = $this->activo->options(false);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // comprobante
        $this->comprobante->setupEditAttributes();
        $this->comprobante->EditValue = $this->comprobante->CurrentValue;
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

        // nro_despacho
        $this->nro_despacho->setupEditAttributes();
        if (!$this->nro_despacho->Raw) {
            $this->nro_despacho->CurrentValue = HtmlDecode($this->nro_despacho->CurrentValue);
        }
        $this->nro_despacho->EditValue = $this->nro_despacho->CurrentValue;
        $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

        // cerrado
        $this->cerrado->EditValue = $this->cerrado->options(false);
        $this->cerrado->PlaceHolder = RemoveHtml($this->cerrado->caption());

        // asesor_asignado
        $this->asesor_asignado->setupEditAttributes();
        $this->asesor_asignado->PlaceHolder = RemoveHtml($this->asesor_asignado->caption());

        // tasa_indexada
        $this->tasa_indexada->setupEditAttributes();
        $this->tasa_indexada->EditValue = $this->tasa_indexada->CurrentValue;
        $this->tasa_indexada->PlaceHolder = RemoveHtml($this->tasa_indexada->caption());
        if (strval($this->tasa_indexada->EditValue) != "" && is_numeric($this->tasa_indexada->EditValue)) {
            $this->tasa_indexada->EditValue = FormatNumber($this->tasa_indexada->EditValue, null);
        }

        // id_documento_padre_nd
        $this->id_documento_padre_nd->setupEditAttributes();
        $this->id_documento_padre_nd->EditValue = $this->id_documento_padre_nd->CurrentValue;
        $this->id_documento_padre_nd->PlaceHolder = RemoveHtml($this->id_documento_padre_nd->caption());
        if (strval($this->id_documento_padre_nd->EditValue) != "" && is_numeric($this->id_documento_padre_nd->EditValue)) {
            $this->id_documento_padre_nd->EditValue = FormatNumber($this->id_documento_padre_nd->EditValue, null);
        }

        // id_documento_padre
        $this->id_documento_padre->setupEditAttributes();
        $this->id_documento_padre->EditValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->PlaceHolder = RemoveHtml($this->id_documento_padre->caption());

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

        // checker
        $this->checker->setupEditAttributes();
        $this->checker->EditValue = $this->checker->CurrentValue;

        // checker_date
        $this->checker_date->setupEditAttributes();
        $this->checker_date->EditValue = $this->checker_date->CurrentValue;
        $this->checker_date->EditValue = FormatDateTime($this->checker_date->EditValue, $this->checker_date->formatPattern());

        // packer
        $this->packer->setupEditAttributes();
        $this->packer->EditValue = $this->packer->CurrentValue;

        // packer_date
        $this->packer_date->setupEditAttributes();
        $this->packer_date->EditValue = $this->packer_date->CurrentValue;
        $this->packer_date->EditValue = FormatDateTime($this->packer_date->EditValue, $this->packer_date->formatPattern());

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
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->asesor);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->bultos);
                    $doc->exportCaption($this->fecha_bultos);
                    $doc->exportCaption($this->user_bultos);
                    $doc->exportCaption($this->fecha_despacho);
                    $doc->exportCaption($this->user_despacho);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->factura);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->nro_despacho);
                    $doc->exportCaption($this->asesor_asignado);
                    $doc->exportCaption($this->archivo_pedido);
                    $doc->exportCaption($this->checker);
                    $doc->exportCaption($this->checker_date);
                    $doc->exportCaption($this->packer);
                    $doc->exportCaption($this->packer_date);
                    $doc->exportCaption($this->fotos);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->asesor);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->bultos);
                    $doc->exportCaption($this->fecha_bultos);
                    $doc->exportCaption($this->user_bultos);
                    $doc->exportCaption($this->fecha_despacho);
                    $doc->exportCaption($this->user_despacho);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->unidades);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->monto_sin_descuento);
                    $doc->exportCaption($this->factura);
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono);
                    $doc->exportCaption($this->_email);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->nro_despacho);
                    $doc->exportCaption($this->cerrado);
                    $doc->exportCaption($this->asesor_asignado);
                    $doc->exportCaption($this->tasa_indexada);
                    $doc->exportCaption($this->id_documento_padre_nd);
                    $doc->exportCaption($this->id_documento_padre);
                    $doc->exportCaption($this->archivo_pedido);
                    $doc->exportCaption($this->checker);
                    $doc->exportCaption($this->checker_date);
                    $doc->exportCaption($this->packer);
                    $doc->exportCaption($this->packer_date);
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
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->asesor);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->bultos);
                        $doc->exportField($this->fecha_bultos);
                        $doc->exportField($this->user_bultos);
                        $doc->exportField($this->fecha_despacho);
                        $doc->exportField($this->user_despacho);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->factura);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->nro_despacho);
                        $doc->exportField($this->asesor_asignado);
                        $doc->exportField($this->archivo_pedido);
                        $doc->exportField($this->checker);
                        $doc->exportField($this->checker_date);
                        $doc->exportField($this->packer);
                        $doc->exportField($this->packer_date);
                        $doc->exportField($this->fotos);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->asesor);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->bultos);
                        $doc->exportField($this->fecha_bultos);
                        $doc->exportField($this->user_bultos);
                        $doc->exportField($this->fecha_despacho);
                        $doc->exportField($this->user_despacho);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->unidades);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->monto_sin_descuento);
                        $doc->exportField($this->factura);
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono);
                        $doc->exportField($this->_email);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->nro_despacho);
                        $doc->exportField($this->cerrado);
                        $doc->exportField($this->asesor_asignado);
                        $doc->exportField($this->tasa_indexada);
                        $doc->exportField($this->id_documento_padre_nd);
                        $doc->exportField($this->id_documento_padre);
                        $doc->exportField($this->archivo_pedido);
                        $doc->exportField($this->checker);
                        $doc->exportField($this->checker_date);
                        $doc->exportField($this->packer);
                        $doc->exportField($this->packer_date);
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
        } elseif ($fldparm == 'fotos') {
            $fldName = "fotos";
            $fileNameFld = "fotos";
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
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'salidas');
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
                WriteAuditLog($usr, "A", 'salidas', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'salidas', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'salidas', $fldname, $key, $oldvalue);
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
    	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
    			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
    	$grupo = trim(ExecuteScalar($sql));
    	if($grupo == "CLIENTE") {
    		$sql = "SELECT asesor, cliente FROM usuario
    				WHERE username = '" . CurrentUserName() . "';";
    		$row = ExecuteRow($sql);
    		$asesor = intval($row["asesor"]);
    		$cliente = intval($row["cliente"]);
    		if($asesor > 0) {
    			$sql = "SELECT COUNT(cliente) AS cantidad FROM asesor_cliente
    					WHERE asesor = '$asesor';";
    			$cantidad = ExecuteScalar($sql);
    			$clientes = "";
    			for($i=0; $i<$cantidad; $i++) {
    				$sql = "SELECT cliente FROM asesor_cliente
    					WHERE asesor = '$asesor' LIMIT $i, 1;";
    				$clientes .= ExecuteScalar($sql) . ",";
    			}
    			$clientes .= "0"; 
    			AddFilter($filter, "cliente IN ($clientes)");
    		}
    		else if($cliente > 0) {
    			AddFilter($filter, "cliente = '$cliente'");
    		}
    	}
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
    	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = '$tipo';";
    	$consecutivo = intval(ExecuteScalar($sql)) + 1;
    	switch($tipo) {
    	case "TDCPDV":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$sql = "SELECT consignacion FROM cliente WHERE id = " . intval($rsnew["cliente"]) . ";";
    		if(!isset($rsnew["consignacion"])) $rsnew["consignacion"] = ExecuteScalar($sql);
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			if(strpos(strtoupper($rsnew["archivo_pedido"]), ".CSV") == false) {
    				$this->CancelMessage = "El archivo a subir con los art&iacute;culo debe ser .csv. Verifique!";
    				return FALSE;
    			}
    		}
    		break;
    	case "TDCNET":
    		// Se agrega la siguiente línea el 26/12/2020 Junior Sanabria
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		break;
    	case "TDCFCV":
    		// Valido cierre de facturación
    		if(isset($rsnew["fecha"])) {
                $sql = "SELECT DISTINCT cerrado AS cerrado FROM salidas WHERE YEAR(fecha) = " . intval(substr($rsnew["fecha"], 0, 4)) . " AND MONTH(fecha) = " . intval(substr($rsnew["fecha"], 5, 2)) . ";";
                if($row = ExecuteRow($sql)) {
                    if($row["cerrado"] == "S") {
                        $this->CancelMessage = "El mes en el que se va a crear la factura est&aacute; cerrado. Verifique!";
                        return FALSE;
                    }
                }
    		}

    		// Tomo el número de días de crédito por defecto
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
    		$row = ExecuteRow($sql);
    		$nota = "Crédito a " . $row["valor1"] . " " . $row["valor2"];
    		$rsnew["nota"] = $nota;
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '006';";
    		$moneda = ExecuteScalar($sql);
    		$rsnew["moneda"] = $moneda;
    		break;
    	case "TDCASA":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		break;
    	}

    	// Se establecen valores por defecto a varibles bitácora y estatus
    	$rsnew["fecha"] = date("Y-m-d H:i:s");
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["asesor"] = CurrentUserName();
    	$rsnew["estatus"] = "NUEVO";
    	$rsnew["fecha_bultos"] = NULL;
    	$rsnew["fecha_despacho"] = NULL;
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
    	case "TDCPDV":
    		/////////////////
    		$sql = "SELECT username, IFNULL(asesor, 0) AS asesor FROM usuario WHERE username = '" . CurrentUserName() . "';";
    		$asesor = 0;
    		$username = CurrentUserName();
    		if($row = ExecuteRow($sql)) {
    			$asesor = intval($row["asesor"]);
    			$username = $row["username"];
    		}
    		if($asesor > 0) {
    			$sql = "UPDATE salidas SET asesor_asignado='$username' WHERE id = '" . $rsnew["id"] . "';";
    			Execute($sql);
    		}
    		/////////////////

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
    			$insart = new PdvLineaGuardar();
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
    					$sql = "SELECT 
    								b.activo, a.activo AS artact  
    							FROM 
    								articulo AS a 
    								JOIN fabricante AS b ON b.Id = a.fabricante 
    							WHERE 
    								a.codigo = '$cod_articulo';";
    					if($row = ExecuteRow($sql)) {
    						if($row["activo"] == "S" and $row["artact"] == "S") {
    							$insart->insertar_articulo("TDCPDV", $rsnew["id"], $rsnew["cliente"], $cod_articulo, $rsnew["lista_pedido"], $cantidad, 0);
    						}
    					}
    				}
    				$codigo = "";
    				$nombre = "";
    				$precio = 0.00;
    			}
    			//Cerramos el archivo
    			fclose($archivo);
    			$insart->ActualizarCabecera();
    		}
    		//// **** **** **** **** **** **** **** **** **** **** **** **** ////
    		break;
    	case "TDCNET":
    		/*$sql = "INSERT INTO entradas_salidas
    					(id, tipo_documento, id_documento, 
    					fabricante, articulo, cantidad_articulo, 
    					articulo_unidad_medida, alicuota)
    				SELECT 
    					NULL, '" . $rsnew["tipo_documento"] . "', '" . $rsnew["id"] . "', 
    					fabricante, articulo, cantidad_articulo, 
    					articulo_unidad_medida, alicuota
    				FROM 
    					entradas_salidas 
    				WHERE 
    					id_documento = '" . $rsnew["id_documento_padre"] . "';"; die($sql);
    		Execute($sql);*/
    		break;
    	case "TDCFCV":
    		switch($rsnew["documento"]) {
    		case "FC":
    			$codigo = "003";
    			break;
    		case "NC":
    			$codigo = "010";
    			break;
    		case "ND":
    			$codigo = "011";
    			break;
    		}
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigo';";
    		$row = ExecuteRow($sql);
    		$numero = intval($row["valor1"]) + 1;
    		$prefijo = trim($row["valor2"]);
    		$padeo = intval($row["valor3"]);
    		$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    		$sql = "UPDATE parametro SET valor1='$numero' 
    			WHERE codigo = '$codigo';";
    		Execute($sql);

    		//// Nro Ctrol ////
    		// Tomo el siguiente número de control de factura
    		// Pregunto si el consecutivo del Nro de Control de factura es el mismo
    		// Para Notas de Débito y Nota de Crédito
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
    		if(ExecuteScalar($sql) == "S") {
    			$codigoCRTL = "030";
    		}
    		else {
    			switch($codigo) {
    			case "003":
    				$codigoCRTL = "030";
    				break;
    			case "010":
    				$codigoCRTL = "031";
    				break;
    			case "011":
    				$codigoCRTL = "032";
    				break;
    			}
    		}
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigoCRTL';";
    		$row = ExecuteRow($sql);
    		$numero = intval($row["valor1"]) + 1;
    		$prefijo = trim($row["valor2"]);
    		$padeo = intval($row["valor3"]);
    		$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    		$sql = "UPDATE parametro SET valor1='$numero' 
    				WHERE codigo = '$codigoCRTL';";
    				Execute($sql);
    		///////////////////
    		$sql = "UPDATE salidas SET nro_documento='$factura', nro_control = '$facturaCTRL'  
    				WHERE id = '" . $rsnew["id"] . "';";
    		Execute($sql);

    		//// **** Proceso para agregar detalle desde el archivo de texto a la factura ////
    		if(trim($rsnew["archivo_pedido"]) != "") {
    			$linea = 0;
    			//Abrimos nuestro archivo
    			$file01 = "carpetacarga/" . $rsnew["archivo_pedido"];
    			$archivo = fopen($file01, "r");
    			//Lo recorremos
    			$codigo = "";
    			$cantidad = 0;
    			$precio = 0.00;
    			// include_once("include/pdv_linea_guardar.class.php");
    			$insart = new FctLineaGuardar($rsnew["cliente"]);
    			while (($datos = fgetcsv($archivo, 0, ";")) == true) {
    				$num = count($datos);
    				//Recorremos las columnas de esa linea
    				if($num == 3) {
    					for($columna = 0; $columna < $num; $columna++) {
    						switch($columna) {
    						case 0:	
    							$cod_articulo = trim($datos[$columna]);
    							break;
    						case 1:	
    							$precio = floatval($datos[$columna]);
    							break;
    						case 2:	
    							$cantidad = intval($datos[$columna]);
    							break;
    						}
    					}
    					$insart->insertar_articulo("TDCFCV", $rsnew["id"], $cod_articulo, $cantidad, $precio);
    					// $insart->ActualizarCabecera();
    				}
    				$codigo = "";
    				$cantidad = 0;
    				$precio = 0.00;
    			}
    			$sql = "INSERT INTO entradas_salidas
    						(id, tipo_documento, id_documento, fabricante, articulo, 
    						lote, fecha_vencimiento, 
    						almacen, 
    						cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
    						costo_unidad, costo, precio_unidad, precio, 
    						alicuota, 
    						descuento, precio_unidad_sin_desc, 
    						check_ne)
    					SELECT 
    						NULL, tipo_documento, id_documento, fabricante, articulo, 
    						lote, fecha_vencimiento, 
    						almacen, 
    						SUM(cantidad_articulo) AS cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, SUM(cantidad_movimiento) AS cantidad_movimiento, 
    						costo_unidad, (SUM(cantidad_articulo)*costo_unidad) AS costo, precio_unidad, (SUM(cantidad_articulo)*precio_unidad) AS precio, 
    						alicuota, 
    						descuento, precio_unidad_sin_desc, 
    						'N' AS check_ne
    					FROM 
    						entradas_salidas 
    					WHERE 
    						tipo_documento = 'TDCFCV' AND id_documento = " . $rsnew["id"] . " AND check_ne = 'S'
    					GROUP BY 
    						NULL, tipo_documento, id_documento, fabricante, articulo, 
    						lote, fecha_vencimiento, 
    						almacen, articulo_unidad_medida, cantidad_unidad_medida, costo_unidad, precio_unidad, alicuota, descuento, precio_unidad_sin_desc;";
    			Execute($sql);
    			$sql = "DELETE 
    					FROM 
    						entradas_salidas 
    					WHERE 
    						tipo_documento = 'TDCFCV' AND id_documento = " . $rsnew["id"] . " AND check_ne = 'S';";
    			Execute($sql);
    			$insart->ActualizarCabecera();
    			//Cerramos el archivo
    			fclose($archivo);
    		}
    		//// **** **** **** **** **** **** **** **** **** **** **** **** ////
    		break;
    	case "TDCASA":
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
    		if($rsold["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede modificar.";
    			return FALSE;
    		}
    	}
    	if($rsold["entregado"] != $rsnew["entregado"]) {
    		$rsnew["fecha_entrega"] = date("Y-m-d");
    	}
    	if($rsold["bultos"] != $rsnew["bultos"]) {
    		$rsnew["fecha_bultos"] = date("Y-m-d");
    		$rsnew["user_bultos"] = CurrentUserName();
    	}
    	if($rsold["consignacion"] != $rsnew["consignacion"]) {
    		if(trim($rsnew["consignacion"]) != "S") $rsnew["consignacion"] = "N";
    	}

    	/////////////////
    	/*$sql = "SELECT username, IFNULL(asesor, 0) AS asesor FROM usuario WHERE username = '" . CurrentUserName() . "';";
    	$row = ExecuteScalar($sql);
    	$asesor = $row["asesor"];
    	$username = $row["username"];
    	if($asesor > 0) $rsnew["asesor_asignado"] = $username;*/
    	/////////////////
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew) {
    	//echo "Row Updated";
    	if($rsold["tipo_documento"] == "TDCFCV") {
    		/*if($rsold["tasa_dia"] != $rsnew["tasa_dia"])
    			$tasa = floatval($rsnew["tasa_dia"]);
    		else
    			$tasa = floatval($rsold["tasa_dia"]);*/
    		ActualizarTotalFacturaVenta($rsold["id"], $rsold["tipo_documento"]);
    	}

    	// 19/01/2024 otimizar las siguientes lineas
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
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulada; no se puede eliminar.";
    			return FALSE;
    		}
    	}
    	if($rs["tipo_documento"] == "TDCFCV") {
    		if($rs["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede eliminar.";
    			return FALSE;
    		}

    		// Valido cierre de facturación
    		if($rs["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    	}

    	///////// INI - Pare reversar consignaciones /////////////
    	if($rs["consignacion"] == "S") {
    		$sql = "SELECT 
    					cantidad_movimiento, id_consignacion
    				FROM 	
    					entradas_salidas 			
    				WHERE 
    					id_documento = " . $rs["id"] . "
    					AND tipo_documento = '" . $rs["tipo_documento"] . "';";
    		$rows = ExecuteRows($sql);
    		foreach ($rows as $key => $value) {
    			if(intval($value["id_consignacion"]) > 0) {
    				$cantidad = intval($value["cantidad_movimiento"]);
    				$sql = "UPDATE entradas_salidas
    						SET cantidad_movimiento_consignacion = (cantidad_movimiento_consignacion + ($cantidad))
    						WHERE id = " . $value["id_consignacion"] . ";";
    				Execute($sql);
    			}
    		}
    		if($rs["id_documento_padre"] != "") {
    			$sql = "UPDATE salidas
    				SET estatus = 'NUEVO'
    				WHERE id = " . $rs["id_documento_padre"] . ";";
    			Execute($sql);
    		}
    		if(trim($rs["direccion"]) != "") {
    			// caso en que hayan varias notas de entrega en la misma factura. Informacion Guardada en el campo direccion
    			$sql = "UPDATE salidas
    					SET estatus = 'NUEVO'
    					WHERE id IN (" . $rs["direccion"] . ");";
    			Execute($sql);
    		}
    	}
    	///////// FIN - Pare reversar consignaciones /////////////
    	$sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '" . $rs["tipo_documento"] . "' AND id_documento = '" . $rs["id"] . "';";
    	Execute($sql);
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted($rs) {
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
    	if($rs["tipo_documento"] != "TDCFCV") {
    		ActualizarExitencia();
    	}
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
    	$color = "";
    	$color2 = "";
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->estatus->ViewValue == "NUEVO") { 
    			$color = "background-color: #eda135; color: #ffffff;";
    			if($tipo=="TDCPDV") {
                    if(isset($this->nombre->CurrentValue)) {
                        if(trim($this->nombre->CurrentValue) == "") $color2 = "background-color: #f8040f; color: #ffffff;";
                        $this->id->CellAttrs["style"] = $color2;
                    }
    			}
    		}
    		elseif ($this->estatus->ViewValue == "PROCESADO") {
    			if($tipo=="TDCPDV") {
    				// Si está facturado marco el estatus facturado de lo contrario queda como recibido
    				$sql = "SELECT 
    						(SELECT COUNT(id) FROM salidas WHERE id_documento_padre = a.id) AS cantidad 
    					FROM 
    						salidas AS a 
    					WHERE a.id_documento_padre = " . $this->id->CurrentValue . " AND a.estatus <> 'ANULADO';";
    				$cantidad = ExecuteScalar($sql);
    				if($cantidad > 0)
    					$color = "background-color: #51aa51; color: #ffffff;";
    				else
    					$color = "background-color: #66B2FF; color: #ffffff;";
    			}
    			elseif($tipo=="TDCNET") {
    				if(intval($this->bultos->CurrentValue) > 0) {
                        if(isset($this->user_despacho->CurrentValue)) {
                            if(trim($this->user_despacho->CurrentValue) == "")
                                $color = "background-color: #6c757d; color: #ffffff;";
                            else
                                $color = "background-color: #343a40; color: #ffffff;";					
                        }
    				}
    				else {
    					$color = "background-color: #51aa51; color: #ffffff;";
    				}
    			}
    			else $color = "background-color: #51aa51; color: #ffffff;";
    		}
    		elseif ($this->estatus->ViewValue == "ANULADO") {
    			$color = "background-color: #cc3f3b; color: #ffffff;";
    		}
    		$sql = "SELECT COUNT(id) AS cantidad FROM pagos
    				WHERE tipo_documento = '" . $this->tipo_documento->CurrentValue . "' AND id_documento = '" . $this->id->CurrentValue . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->estatus->CellAttrs["style"] = $color;
    			$this->RowAttrs["class"] = "info";
    			//$this->cliente->CellAttrs["style"] = "background-color: #66B2FF; color: #ffffff;";
    			$color = "";
    		}
    		else $this->estatus->CellAttrs["style"] = $color;
    		if($this->pagado->CurrentValue == "S") {
    			$this->cliente->CellAttrs["style"] = "background-color: #66B2FF; color: #ffffff;";
    		}
    		$this->tipo_documento->CellAttrs["style"] = $color;
    		$this->nro_documento->CellAttrs["style"] = $color;
    		$this->nro_control->CellAttrs["style"] = $color;
    		$this->fecha->CellAttrs["style"] = $color;
    		$this->total->CellAttrs["style"] = $color;

    		/* 02-12-2020 Alerta si hay una cantidad distinta de ITEMS que el pedido o documento origen */
    		if($tipo=="TDCNET" or $tipo=="TDCFCV") {
    			$id = $this->id->CurrentValue;
    			$id_documento_padre = $this->id_documento_padre->CurrentValue;
    			$sql = "SELECT COUNT(articulo) 
    					FROM entradas_salidas 
    					WHERE tipo_documento IN (SELECT codigo FROM tipo_documento WHERE tipo = 'CLIENTE') 
    						AND id_documento = '$id';";
    			$cnt1 = ExecuteScalar($sql);
    			$da = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo'");
    			$sql = "SELECT COUNT(articulo) 
    					FROM entradas_salidas 
    					WHERE tipo_documento IN (SELECT codigo FROM tipo_documento WHERE tipo = 'CLIENTE') 
    						AND id_documento = '$id_documento_padre';";
    			$cnt2 = ExecuteScalar($sql);
    			$sql = "SELECT id_documento_padre, tipo_documento  
    					FROM salidas 
    					WHERE id = '$id_documento_padre';";
    			if($row = ExecuteRow($sql)) {
    				$do = $row["tipo_documento"];
    				$do_padre = $row["id_documento_padre"];
    				$do = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$do'");
    			}
    			else {
    				$do = "";
    				$do_padre = "";
    			}
    			$coletilla = "";
    			$cnt3 = 0;
    			if($tipo=="TDCNET") {
    				if($cnt1 != $cnt2) {
    					$this->cliente->ViewValue = $this->cliente->ViewValue . "<br><br><small><b><i>$do: $cnt2 Items<br><br> $da: $cnt1 Items<br>$coletilla</i></b><small>";
    					$color = "background-color: #fcf902; color: #000000;";
    					$this->cliente->CellAttrs["style"] = $color;
    				}
    			}
    		}
    		if($tipo=="TDCPDV" and $this->id->CurrentValue != "") {
            	$levelid = CurrentUserLevel();
            	$sql = "SELECT tipo_acceso FROM userlevels WHERE userlevelid = $levelid";
            	$tipo_acceso = ExecuteScalar($sql);
            	if($tipo_acceso != "CLIENTE") {
            		$sql = "SELECT tipo_cliente FROM cliente WHERE id = " . $this->cliente->CurrentValue . ""; 
            		$tipo_cliente = ExecuteScalar($sql);
            		$sql = "SELECT campo_dato AS color FROM tabla
            				WHERE tabla = 'tipo_cliente' AND campo_codigo = '" . $tipo_cliente . "';";
            		if($row = ExecuteRow($sql)) {
            		  $color = explode(";", $row["color"]);
            		  if(count($color)>=2)
    				  	$this->cliente->CellAttrs["style"] = "background-color: " . $color[0] . "; color: " . $color[1] . ";";
    				  else
    				  	$this->cliente->CellAttrs["style"] = "background-color: " . $color[0] . ";";
                    }
    			}
    		}

    		/* Marca con negro el numero de documento si fue entregada la mercancia */
            if (trim($this->id->CurrentValue) != "") {
                if($this->tipo_documento->CurrentValue == "TDCPDV") {
                    $sql = "SELECT 
                                IFNULL(a.entregado, 'N') AS entregado, a.tipo_documento  
                            FROM 
                                salidas AS a  
                            WHERE a.id_documento_padre = " . $this->id->CurrentValue . ";"; 
                    if($row = ExecuteRow($sql)) {
                        if($row["tipo_documento"] == 'TDCNET' AND $row["entregado"] == "S") {
                            $this->nro_documento->CellAttrs["style"] = "background-color: #343a40; color: #ffffff;";
                        }
                    }
                }
                else {
                    if($this->tipo_documento->CurrentValue == "TDCFCV") {
                        $sql = "SELECT 
                                    IFNULL(a.entregado, 'N') AS entregado, a.tipo_documento  
                                FROM 
                                    salidas AS a  
                                WHERE a.id = '" . $this->id_documento_padre->CurrentValue . "';"; 
                        if($row = ExecuteRow($sql)) {
                            if($row["tipo_documento"] == 'TDCNET' AND $row["entregado"] == "S") {
                                $this->nro_documento->CellAttrs["style"] = "background-color: #343a40; color: #ffffff;";
                            }
                        }
                    }
                }
            }
    		/* -------------- */
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
