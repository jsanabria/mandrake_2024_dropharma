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
 * Table class for view_pagos
 */
class ViewPagos extends DbTable
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
    public $cliente;
    public $documento;
    public $nro_documento;
    public $fecha_documento;
    public $base_imponible;
    public $total;
    public $tipo_pago;
    public $fecha_pago;
    public $referencia;
    public $banco;
    public $banco_destino;
    public $moneda;
    public $monto;
    public $monto_bs;
    public $monto_usd;
    public $tasa_usd;
    public $asesor_asignado;
    public $nombre;
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
        $this->TableVar = "view_pagos";
        $this->TableName = 'view_pagos';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_pagos";
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
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id->InputTextType = "text";
        $this->id->Raw = true;
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Nullable = false; // NOT NULL field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id'] = &$this->id;

        // cliente
        $this->cliente = new DbField(
            $this, // Table
            'x_cliente', // Variable name
            'cliente', // Name
            '`cliente`', // Expression
            '`cliente`', // Basic search expression
            20, // Type
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
        $this->cliente->InputTextType = "text";
        $this->cliente->Raw = true;
        $this->cliente->setSelectMultiple(false); // Select one
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup($this->cliente, 'cliente', false, 'id', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`id` ASC', '', "`nombre`");
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
            200, // Type
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
        $this->documento->setSelectMultiple(false); // Select one
        $this->documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->documento->Lookup = new Lookup($this->documento, 'view_pagos', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->documento->OptionCount = 4;
        $this->documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['documento'] = &$this->documento;

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

        // fecha_documento
        $this->fecha_documento = new DbField(
            $this, // Table
            'x_fecha_documento', // Variable name
            'fecha_documento', // Name
            '`fecha_documento`', // Expression
            CastDateFieldForLike("`fecha_documento`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_documento->InputTextType = "text";
        $this->fecha_documento->Raw = true;
        $this->fecha_documento->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_documento'] = &$this->fecha_documento;

        // base_imponible
        $this->base_imponible = new DbField(
            $this, // Table
            'x_base_imponible', // Variable name
            'base_imponible', // Name
            '`base_imponible`', // Expression
            '`base_imponible`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`base_imponible`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->base_imponible->InputTextType = "text";
        $this->base_imponible->Raw = true;
        $this->base_imponible->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->base_imponible->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['base_imponible'] = &$this->base_imponible;

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

        // tipo_pago
        $this->tipo_pago = new DbField(
            $this, // Table
            'x_tipo_pago', // Variable name
            'tipo_pago', // Name
            '`tipo_pago`', // Expression
            '`tipo_pago`', // Basic search expression
            200, // Type
            10, // Size
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
        $this->tipo_pago->setSelectMultiple(false); // Select one
        $this->tipo_pago->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_pago->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_pago->Lookup = new Lookup($this->tipo_pago, 'parametro', false, 'valor1', ["valor2","","",""], '', '', [], [], [], [], [], [], false, '`valor2` ASC', '', "`valor2`");
        $this->tipo_pago->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_pago'] = &$this->tipo_pago;

        // fecha_pago
        $this->fecha_pago = new DbField(
            $this, // Table
            'x_fecha_pago', // Variable name
            'fecha_pago', // Name
            '`fecha_pago`', // Expression
            CastDateFieldForLike("`fecha_pago`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_pago`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_pago->InputTextType = "text";
        $this->fecha_pago->Raw = true;
        $this->fecha_pago->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_pago->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_pago'] = &$this->fecha_pago;

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
        $this->banco->Lookup = new Lookup($this->banco, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion` ASC', '', "`campo_descripcion`");
        $this->banco->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['banco'] = &$this->banco;

        // banco_destino
        $this->banco_destino = new DbField(
            $this, // Table
            'x_banco_destino', // Variable name
            'banco_destino', // Name
            '`banco_destino`', // Expression
            '`banco_destino`', // Basic search expression
            20, // Type
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
        $this->banco_destino->Lookup = new Lookup($this->banco_destino, 'view_banco', false, 'id', ["banco","numero","",""], '', '', [], [], [], [], [], [], false, '`banco` ASC', '', "CONCAT(COALESCE(`banco`, ''),'" . ValueSeparator(1, $this->banco_destino) . "',COALESCE(`numero`,''))");
        $this->banco_destino->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->banco_destino->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['banco_destino'] = &$this->banco_destino;

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
            'TEXT' // Edit Tag
        );
        $this->moneda->InputTextType = "text";
        $this->moneda->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
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
        $this->monto->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto'] = &$this->monto;

        // monto_bs
        $this->monto_bs = new DbField(
            $this, // Table
            'x_monto_bs', // Variable name
            'monto_bs', // Name
            '`monto_bs`', // Expression
            '`monto_bs`', // Basic search expression
            131, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_bs->InputTextType = "text";
        $this->monto_bs->Raw = true;
        $this->monto_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_bs'] = &$this->monto_bs;

        // monto_usd
        $this->monto_usd = new DbField(
            $this, // Table
            'x_monto_usd', // Variable name
            'monto_usd', // Name
            '`monto_usd`', // Expression
            '`monto_usd`', // Basic search expression
            131, // Type
            22, // Size
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

        // tasa_usd
        $this->tasa_usd = new DbField(
            $this, // Table
            'x_tasa_usd', // Variable name
            'tasa_usd', // Name
            '`tasa_usd`', // Expression
            '`tasa_usd`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tasa_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tasa_usd->InputTextType = "text";
        $this->tasa_usd->Raw = true;
        $this->tasa_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tasa_usd'] = &$this->tasa_usd;

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
            'TEXT' // Edit Tag
        );
        $this->asesor_asignado->InputTextType = "text";
        $this->asesor_asignado->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['asesor_asignado'] = &$this->asesor_asignado;

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

        // fecha_resgistro
        $this->fecha_resgistro = new DbField(
            $this, // Table
            'x_fecha_resgistro', // Variable name
            'fecha_resgistro', // Name
            '`fecha_resgistro`', // Expression
            CastDateFieldForLike("`fecha_resgistro`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
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
        $this->fecha_resgistro->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
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

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_pagos";
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
        return $success;
    }

    // Load DbValue from result set or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->id->DbValue = $row['id'];
        $this->cliente->DbValue = $row['cliente'];
        $this->documento->DbValue = $row['documento'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->fecha_documento->DbValue = $row['fecha_documento'];
        $this->base_imponible->DbValue = $row['base_imponible'];
        $this->total->DbValue = $row['total'];
        $this->tipo_pago->DbValue = $row['tipo_pago'];
        $this->fecha_pago->DbValue = $row['fecha_pago'];
        $this->referencia->DbValue = $row['referencia'];
        $this->banco->DbValue = $row['banco'];
        $this->banco_destino->DbValue = $row['banco_destino'];
        $this->moneda->DbValue = $row['moneda'];
        $this->monto->DbValue = $row['monto'];
        $this->monto_bs->DbValue = $row['monto_bs'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->tasa_usd->DbValue = $row['tasa_usd'];
        $this->asesor_asignado->DbValue = $row['asesor_asignado'];
        $this->nombre->DbValue = $row['nombre'];
        $this->_username->DbValue = $row['username'];
        $this->fecha_resgistro->DbValue = $row['fecha_resgistro'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
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
        return $_SESSION[$name] ?? GetUrl("ViewPagosList");
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
            "ViewPagosView" => $Language->phrase("View"),
            "ViewPagosEdit" => $Language->phrase("Edit"),
            "ViewPagosAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewPagosList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewPagosView",
            Config("API_ADD_ACTION") => "ViewPagosAdd",
            Config("API_EDIT_ACTION") => "ViewPagosEdit",
            Config("API_DELETE_ACTION") => "ViewPagosDelete",
            Config("API_LIST_ACTION") => "ViewPagosList",
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
        return "ViewPagosList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewPagosView", $parm);
        } else {
            $url = $this->keyUrl("ViewPagosView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewPagosAdd?" . $parm;
        } else {
            $url = "ViewPagosAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewPagosEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewPagosList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ViewPagosAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewPagosList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewPagosDelete", $parm);
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
        $this->cliente->setDbValue($row['cliente']);
        $this->documento->setDbValue($row['documento']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->fecha_documento->setDbValue($row['fecha_documento']);
        $this->base_imponible->setDbValue($row['base_imponible']);
        $this->total->setDbValue($row['total']);
        $this->tipo_pago->setDbValue($row['tipo_pago']);
        $this->fecha_pago->setDbValue($row['fecha_pago']);
        $this->referencia->setDbValue($row['referencia']);
        $this->banco->setDbValue($row['banco']);
        $this->banco_destino->setDbValue($row['banco_destino']);
        $this->moneda->setDbValue($row['moneda']);
        $this->monto->setDbValue($row['monto']);
        $this->monto_bs->setDbValue($row['monto_bs']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->tasa_usd->setDbValue($row['tasa_usd']);
        $this->asesor_asignado->setDbValue($row['asesor_asignado']);
        $this->nombre->setDbValue($row['nombre']);
        $this->_username->setDbValue($row['username']);
        $this->fecha_resgistro->setDbValue($row['fecha_resgistro']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewPagosList";
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

        // cliente

        // documento

        // nro_documento

        // fecha_documento

        // base_imponible

        // total

        // tipo_pago

        // fecha_pago

        // referencia

        // banco

        // banco_destino

        // moneda

        // monto

        // monto_bs

        // monto_usd

        // tasa_usd

        // asesor_asignado

        // nombre

        // username

        // fecha_resgistro

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

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
                    $this->cliente->ViewValue = FormatNumber($this->cliente->CurrentValue, $this->cliente->formatPattern());
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

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

        // fecha_documento
        $this->fecha_documento->ViewValue = $this->fecha_documento->CurrentValue;
        $this->fecha_documento->ViewValue = FormatDateTime($this->fecha_documento->ViewValue, $this->fecha_documento->formatPattern());

        // base_imponible
        $this->base_imponible->ViewValue = $this->base_imponible->CurrentValue;
        $this->base_imponible->ViewValue = FormatNumber($this->base_imponible->ViewValue, $this->base_imponible->formatPattern());

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->formatPattern());

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

        // fecha_pago
        $this->fecha_pago->ViewValue = $this->fecha_pago->CurrentValue;
        $this->fecha_pago->ViewValue = FormatDateTime($this->fecha_pago->ViewValue, $this->fecha_pago->formatPattern());

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;

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

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;

        // monto
        $this->monto->ViewValue = $this->monto->CurrentValue;
        $this->monto->ViewValue = FormatNumber($this->monto->ViewValue, $this->monto->formatPattern());

        // monto_bs
        $this->monto_bs->ViewValue = $this->monto_bs->CurrentValue;
        $this->monto_bs->ViewValue = FormatNumber($this->monto_bs->ViewValue, $this->monto_bs->formatPattern());

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

        // tasa_usd
        $this->tasa_usd->ViewValue = $this->tasa_usd->CurrentValue;
        $this->tasa_usd->ViewValue = FormatNumber($this->tasa_usd->ViewValue, $this->tasa_usd->formatPattern());

        // asesor_asignado
        $this->asesor_asignado->ViewValue = $this->asesor_asignado->CurrentValue;

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;

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

        // fecha_resgistro
        $this->fecha_resgistro->ViewValue = $this->fecha_resgistro->CurrentValue;
        $this->fecha_resgistro->ViewValue = FormatDateTime($this->fecha_resgistro->ViewValue, $this->fecha_resgistro->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // documento
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // nro_documento
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // fecha_documento
        $this->fecha_documento->HrefValue = "";
        $this->fecha_documento->TooltipValue = "";

        // base_imponible
        $this->base_imponible->HrefValue = "";
        $this->base_imponible->TooltipValue = "";

        // total
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

        // tipo_pago
        $this->tipo_pago->HrefValue = "";
        $this->tipo_pago->TooltipValue = "";

        // fecha_pago
        $this->fecha_pago->HrefValue = "";
        $this->fecha_pago->TooltipValue = "";

        // referencia
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // banco
        $this->banco->HrefValue = "";
        $this->banco->TooltipValue = "";

        // banco_destino
        $this->banco_destino->HrefValue = "";
        $this->banco_destino->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // monto
        $this->monto->HrefValue = "";
        $this->monto->TooltipValue = "";

        // monto_bs
        $this->monto_bs->HrefValue = "";
        $this->monto_bs->TooltipValue = "";

        // monto_usd
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // tasa_usd
        $this->tasa_usd->HrefValue = "";
        $this->tasa_usd->TooltipValue = "";

        // asesor_asignado
        $this->asesor_asignado->HrefValue = "";
        $this->asesor_asignado->TooltipValue = "";

        // nombre
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

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
        $this->id->PlaceHolder = RemoveHtml($this->id->caption());

        // cliente
        $this->cliente->setupEditAttributes();
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // documento
        $this->documento->setupEditAttributes();
        $this->documento->EditValue = $this->documento->options(true);
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

        // nro_documento
        $this->nro_documento->setupEditAttributes();
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // fecha_documento
        $this->fecha_documento->setupEditAttributes();
        $this->fecha_documento->EditValue = FormatDateTime($this->fecha_documento->CurrentValue, $this->fecha_documento->formatPattern());
        $this->fecha_documento->PlaceHolder = RemoveHtml($this->fecha_documento->caption());

        // base_imponible
        $this->base_imponible->setupEditAttributes();
        $this->base_imponible->EditValue = $this->base_imponible->CurrentValue;
        $this->base_imponible->PlaceHolder = RemoveHtml($this->base_imponible->caption());
        if (strval($this->base_imponible->EditValue) != "" && is_numeric($this->base_imponible->EditValue)) {
            $this->base_imponible->EditValue = FormatNumber($this->base_imponible->EditValue, null);
        }

        // total
        $this->total->setupEditAttributes();
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->PlaceHolder = RemoveHtml($this->total->caption());
        if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
            $this->total->EditValue = FormatNumber($this->total->EditValue, null);
        }

        // tipo_pago
        $this->tipo_pago->setupEditAttributes();
        $this->tipo_pago->PlaceHolder = RemoveHtml($this->tipo_pago->caption());

        // fecha_pago
        $this->fecha_pago->setupEditAttributes();
        $this->fecha_pago->EditValue = FormatDateTime($this->fecha_pago->CurrentValue, $this->fecha_pago->formatPattern());
        $this->fecha_pago->PlaceHolder = RemoveHtml($this->fecha_pago->caption());

        // referencia
        $this->referencia->setupEditAttributes();
        if (!$this->referencia->Raw) {
            $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
        }
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

        // banco
        $this->banco->setupEditAttributes();
        $this->banco->PlaceHolder = RemoveHtml($this->banco->caption());

        // banco_destino
        $this->banco_destino->setupEditAttributes();
        $this->banco_destino->PlaceHolder = RemoveHtml($this->banco_destino->caption());

        // moneda
        $this->moneda->setupEditAttributes();
        if (!$this->moneda->Raw) {
            $this->moneda->CurrentValue = HtmlDecode($this->moneda->CurrentValue);
        }
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // monto
        $this->monto->setupEditAttributes();
        $this->monto->EditValue = $this->monto->CurrentValue;
        $this->monto->PlaceHolder = RemoveHtml($this->monto->caption());
        if (strval($this->monto->EditValue) != "" && is_numeric($this->monto->EditValue)) {
            $this->monto->EditValue = FormatNumber($this->monto->EditValue, null);
        }

        // monto_bs
        $this->monto_bs->setupEditAttributes();
        $this->monto_bs->EditValue = $this->monto_bs->CurrentValue;
        $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());
        if (strval($this->monto_bs->EditValue) != "" && is_numeric($this->monto_bs->EditValue)) {
            $this->monto_bs->EditValue = FormatNumber($this->monto_bs->EditValue, null);
        }

        // monto_usd
        $this->monto_usd->setupEditAttributes();
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, null);
        }

        // tasa_usd
        $this->tasa_usd->setupEditAttributes();
        $this->tasa_usd->EditValue = $this->tasa_usd->CurrentValue;
        $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());
        if (strval($this->tasa_usd->EditValue) != "" && is_numeric($this->tasa_usd->EditValue)) {
            $this->tasa_usd->EditValue = FormatNumber($this->tasa_usd->EditValue, null);
        }

        // asesor_asignado
        $this->asesor_asignado->setupEditAttributes();
        if (!$this->asesor_asignado->Raw) {
            $this->asesor_asignado->CurrentValue = HtmlDecode($this->asesor_asignado->CurrentValue);
        }
        $this->asesor_asignado->EditValue = $this->asesor_asignado->CurrentValue;
        $this->asesor_asignado->PlaceHolder = RemoveHtml($this->asesor_asignado->caption());

        // nombre
        $this->nombre->setupEditAttributes();
        if (!$this->nombre->Raw) {
            $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
        }
        $this->nombre->EditValue = $this->nombre->CurrentValue;
        $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->fecha_documento);
                    $doc->exportCaption($this->base_imponible);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tipo_pago);
                    $doc->exportCaption($this->fecha_pago);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->banco_destino);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->monto_bs);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->tasa_usd);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->fecha_resgistro);
                } else {
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->fecha_documento);
                    $doc->exportCaption($this->base_imponible);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tipo_pago);
                    $doc->exportCaption($this->fecha_pago);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->banco_destino);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->monto_bs);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->tasa_usd);
                    $doc->exportCaption($this->asesor_asignado);
                    $doc->exportCaption($this->nombre);
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
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->fecha_documento);
                        $doc->exportField($this->base_imponible);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tipo_pago);
                        $doc->exportField($this->fecha_pago);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->banco_destino);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->monto_bs);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->tasa_usd);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->fecha_resgistro);
                    } else {
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->fecha_documento);
                        $doc->exportField($this->base_imponible);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tipo_pago);
                        $doc->exportField($this->fecha_pago);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->banco_destino);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->monto_bs);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->tasa_usd);
                        $doc->exportField($this->asesor_asignado);
                        $doc->exportField($this->nombre);
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

        // No binary fields
        return false;
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
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
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
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
