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
 * Table class for cliente
 */
class Cliente extends DbTable
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
    public $ci_rif;
    public $nombre;
    public $sucursal;
    public $contacto;
    public $ciudad;
    public $zona;
    public $direccion;
    public $telefono1;
    public $telefono2;
    public $email1;
    public $email2;
    public $codigo_ims;
    public $web;
    public $tipo_cliente;
    public $tarifa;
    public $consignacion;
    public $limite_credito;
    public $condicion;
    public $cuenta;
    public $activo;
    public $foto1;
    public $foto2;
    public $dias_credito;
    public $descuento;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "cliente";
        $this->TableName = 'cliente';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "cliente";
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
            11, // Size
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
        $this->ci_rif->Required = true; // Required field
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
        $this->nombre->Required = true; // Required field
        $this->nombre->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nombre'] = &$this->nombre;

        // sucursal
        $this->sucursal = new DbField(
            $this, // Table
            'x_sucursal', // Variable name
            'sucursal', // Name
            '`sucursal`', // Expression
            '`sucursal`', // Basic search expression
            200, // Type
            80, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`sucursal`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->sucursal->InputTextType = "text";
        $this->sucursal->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['sucursal'] = &$this->sucursal;

        // contacto
        $this->contacto = new DbField(
            $this, // Table
            'x_contacto', // Variable name
            'contacto', // Name
            '`contacto`', // Expression
            '`contacto`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`contacto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->contacto->InputTextType = "text";
        $this->contacto->Required = true; // Required field
        $this->contacto->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['contacto'] = &$this->contacto;

        // ciudad
        $this->ciudad = new DbField(
            $this, // Table
            'x_ciudad', // Variable name
            'ciudad', // Name
            '`ciudad`', // Expression
            '`ciudad`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ciudad`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ciudad->addMethod("getSelectFilter", fn() => "`tabla` = 'CIUDAD'");
        $this->ciudad->InputTextType = "text";
        $this->ciudad->Required = true; // Required field
        $this->ciudad->Lookup = new Lookup($this->ciudad, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion`', '', "`campo_descripcion`");
        $this->ciudad->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ciudad'] = &$this->ciudad;

        // zona
        $this->zona = new DbField(
            $this, // Table
            'x_zona', // Variable name
            'zona', // Name
            '`zona`', // Expression
            '`zona`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`zona`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->zona->InputTextType = "text";
        $this->zona->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['zona'] = &$this->zona;

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
            'TEXTAREA' // Edit Tag
        );
        $this->direccion->InputTextType = "text";
        $this->direccion->Required = true; // Required field
        $this->direccion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['direccion'] = &$this->direccion;

        // telefono1
        $this->telefono1 = new DbField(
            $this, // Table
            'x_telefono1', // Variable name
            'telefono1', // Name
            '`telefono1`', // Expression
            '`telefono1`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`telefono1`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->telefono1->InputTextType = "text";
        $this->telefono1->Required = true; // Required field
        $this->telefono1->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['telefono1'] = &$this->telefono1;

        // telefono2
        $this->telefono2 = new DbField(
            $this, // Table
            'x_telefono2', // Variable name
            'telefono2', // Name
            '`telefono2`', // Expression
            '`telefono2`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`telefono2`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->telefono2->InputTextType = "text";
        $this->telefono2->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['telefono2'] = &$this->telefono2;

        // email1
        $this->email1 = new DbField(
            $this, // Table
            'x_email1', // Variable name
            'email1', // Name
            '`email1`', // Expression
            '`email1`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`email1`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->email1->InputTextType = "text";
        $this->email1->Required = true; // Required field
        $this->email1->DefaultErrorMessage = $Language->phrase("IncorrectEmail");
        $this->email1->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['email1'] = &$this->email1;

        // email2
        $this->email2 = new DbField(
            $this, // Table
            'x_email2', // Variable name
            'email2', // Name
            '`email2`', // Expression
            '`email2`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`email2`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->email2->InputTextType = "text";
        $this->email2->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['email2'] = &$this->email2;

        // codigo_ims
        $this->codigo_ims = new DbField(
            $this, // Table
            'x_codigo_ims', // Variable name
            'codigo_ims', // Name
            '`codigo_ims`', // Expression
            '`codigo_ims`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codigo_ims`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codigo_ims->InputTextType = "text";
        $this->codigo_ims->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['codigo_ims'] = &$this->codigo_ims;

        // web
        $this->web = new DbField(
            $this, // Table
            'x_web', // Variable name
            'web', // Name
            '`web`', // Expression
            '`web`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`web`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->web->InputTextType = "text";
        $this->web->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['web'] = &$this->web;

        // tipo_cliente
        $this->tipo_cliente = new DbField(
            $this, // Table
            'x_tipo_cliente', // Variable name
            'tipo_cliente', // Name
            '`tipo_cliente`', // Expression
            '`tipo_cliente`', // Basic search expression
            200, // Type
            25, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_cliente`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tipo_cliente->addMethod("getSelectFilter", fn() => "`tabla` = 'TIPO_CLIENTE'");
        $this->tipo_cliente->InputTextType = "text";
        $this->tipo_cliente->Required = true; // Required field
        $this->tipo_cliente->setSelectMultiple(false); // Select one
        $this->tipo_cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_cliente->Lookup = new Lookup($this->tipo_cliente, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion`', '', "`campo_descripcion`");
        $this->tipo_cliente->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_cliente'] = &$this->tipo_cliente;

        // tarifa
        $this->tarifa = new DbField(
            $this, // Table
            'x_tarifa', // Variable name
            'tarifa', // Name
            '`tarifa`', // Expression
            '`tarifa`', // Basic search expression
            19, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tarifa`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tarifa->addMethod("getSelectFilter", fn() => "activo = 'S'");
        $this->tarifa->InputTextType = "text";
        $this->tarifa->Raw = true;
        $this->tarifa->Required = true; // Required field
        $this->tarifa->setSelectMultiple(false); // Select one
        $this->tarifa->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tarifa->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tarifa->Lookup = new Lookup($this->tarifa, 'tarifa', false, 'id', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`nombre`', '', "`nombre`");
        $this->tarifa->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->tarifa->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tarifa'] = &$this->tarifa;

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
        $this->consignacion->Lookup = new Lookup($this->consignacion, 'cliente', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->consignacion->OptionCount = 2;
        $this->consignacion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['consignacion'] = &$this->consignacion;

        // limite_credito
        $this->limite_credito = new DbField(
            $this, // Table
            'x_limite_credito', // Variable name
            'limite_credito', // Name
            '`limite_credito`', // Expression
            '`limite_credito`', // Basic search expression
            131, // Type
            17, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`limite_credito`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->limite_credito->InputTextType = "text";
        $this->limite_credito->Raw = true;
        $this->limite_credito->Required = true; // Required field
        $this->limite_credito->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->limite_credito->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['limite_credito'] = &$this->limite_credito;

        // condicion
        $this->condicion = new DbField(
            $this, // Table
            'x_condicion', // Variable name
            'condicion', // Name
            '`condicion`', // Expression
            '`condicion`', // Basic search expression
            200, // Type
            7, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`condicion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->condicion->InputTextType = "text";
        $this->condicion->Raw = true;
        $this->condicion->Required = true; // Required field
        $this->condicion->Lookup = new Lookup($this->condicion, 'cliente', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->condicion->OptionCount = 3;
        $this->condicion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['condicion'] = &$this->condicion;

        // cuenta
        $this->cuenta = new DbField(
            $this, // Table
            'x_cuenta', // Variable name
            'cuenta', // Name
            '`cuenta`', // Expression
            '`cuenta`', // Basic search expression
            19, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cuenta`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->cuenta->addMethod("getSelectFilter", fn() => "codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Clientes'), '%')");
        $this->cuenta->InputTextType = "text";
        $this->cuenta->Raw = true;
        $this->cuenta->setSelectMultiple(false); // Select one
        $this->cuenta->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cuenta->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cuenta->Lookup = new Lookup($this->cuenta, 'view_plancta', false, 'id', ["codigo","descripcion","",""], '', '', [], [], [], [], [], [], false, '`codigo`', '', "CONCAT(COALESCE(`codigo`, ''),'" . ValueSeparator(1, $this->cuenta) . "',COALESCE(`descripcion`,''))");
        $this->cuenta->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cuenta->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cuenta'] = &$this->cuenta;

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
            'SELECT' // Edit Tag
        );
        $this->activo->addMethod("getDefault", fn() => "S");
        $this->activo->InputTextType = "text";
        $this->activo->Raw = true;
        $this->activo->Required = true; // Required field
        $this->activo->setSelectMultiple(false); // Select one
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup($this->activo, 'cliente', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->activo->OptionCount = 2;
        $this->activo->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['activo'] = &$this->activo;

        // foto1
        $this->foto1 = new DbField(
            $this, // Table
            'x_foto1', // Variable name
            'foto1', // Name
            '`foto1`', // Expression
            '`foto1`', // Basic search expression
            200, // Type
            254, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`foto1`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->foto1->InputTextType = "text";
        $this->foto1->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['foto1'] = &$this->foto1;

        // foto2
        $this->foto2 = new DbField(
            $this, // Table
            'x_foto2', // Variable name
            'foto2', // Name
            '`foto2`', // Expression
            '`foto2`', // Basic search expression
            200, // Type
            254, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`foto2`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->foto2->InputTextType = "text";
        $this->foto2->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['foto2'] = &$this->foto2;

        // dias_credito
        $this->dias_credito = new DbField(
            $this, // Table
            'x_dias_credito', // Variable name
            'dias_credito', // Name
            '`dias_credito`', // Expression
            '`dias_credito`', // Basic search expression
            19, // Type
            10, // Size
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
        $this->dias_credito->setSelectMultiple(false); // Select one
        $this->dias_credito->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->dias_credito->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->dias_credito->Lookup = new Lookup($this->dias_credito, 'parametro', false, 'valor1', ["valor1","","",""], '', '', [], [], [], [], [], [], false, '`valor1` ASC', '', "`valor1`");
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_credito'] = &$this->dias_credito;

        // descuento
        $this->descuento = new DbField(
            $this, // Table
            'x_descuento', // Variable name
            'descuento', // Name
            '`descuento`', // Expression
            '`descuento`', // Basic search expression
            19, // Type
            10, // Size
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
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->descuento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['descuento'] = &$this->descuento;

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
        if ($this->getCurrentDetailTable() == "adjunto") {
            $detailUrl = Container("adjunto")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ClienteList";
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "cliente";
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
        // Cascade Update detail table 'adjunto'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'cliente'
            $cascadeUpdate = true;
            $rscascade['cliente'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("adjunto")->loadRs("`cliente` = " . QuotedValue($rsold['id'], DataType::NUMBER, 'DB'))->fetchAllAssociative();
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("adjunto")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("adjunto")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("adjunto")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

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

        // Cascade delete detail table 'adjunto'
        $dtlrows = Container("adjunto")->loadRs("`cliente` = " . QuotedValue($rs['id'], DataType::NUMBER, "DB"))->fetchAllAssociative();
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("adjunto")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("adjunto")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("adjunto")->rowDeleted($dtlrow);
            }
        }
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
        $this->ci_rif->DbValue = $row['ci_rif'];
        $this->nombre->DbValue = $row['nombre'];
        $this->sucursal->DbValue = $row['sucursal'];
        $this->contacto->DbValue = $row['contacto'];
        $this->ciudad->DbValue = $row['ciudad'];
        $this->zona->DbValue = $row['zona'];
        $this->direccion->DbValue = $row['direccion'];
        $this->telefono1->DbValue = $row['telefono1'];
        $this->telefono2->DbValue = $row['telefono2'];
        $this->email1->DbValue = $row['email1'];
        $this->email2->DbValue = $row['email2'];
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->web->DbValue = $row['web'];
        $this->tipo_cliente->DbValue = $row['tipo_cliente'];
        $this->tarifa->DbValue = $row['tarifa'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->limite_credito->DbValue = $row['limite_credito'];
        $this->condicion->DbValue = $row['condicion'];
        $this->cuenta->DbValue = $row['cuenta'];
        $this->activo->DbValue = $row['activo'];
        $this->foto1->Upload->DbValue = $row['foto1'];
        $this->foto2->Upload->DbValue = $row['foto2'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->descuento->DbValue = $row['descuento'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['foto1']) ? [] : [$row['foto1']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->foto1->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->foto1->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['foto2']) ? [] : [$row['foto2']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->foto2->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->foto2->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("ClienteList");
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
            "ClienteView" => $Language->phrase("View"),
            "ClienteEdit" => $Language->phrase("Edit"),
            "ClienteAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ClienteList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ClienteView",
            Config("API_ADD_ACTION") => "ClienteAdd",
            Config("API_EDIT_ACTION") => "ClienteEdit",
            Config("API_DELETE_ACTION") => "ClienteDelete",
            Config("API_LIST_ACTION") => "ClienteList",
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
        return "ClienteList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ClienteView", $parm);
        } else {
            $url = $this->keyUrl("ClienteView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ClienteAdd?" . $parm;
        } else {
            $url = "ClienteAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ClienteEdit", $parm);
        } else {
            $url = $this->keyUrl("ClienteEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ClienteList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ClienteAdd", $parm);
        } else {
            $url = $this->keyUrl("ClienteAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ClienteList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ClienteDelete", $parm);
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->sucursal->setDbValue($row['sucursal']);
        $this->contacto->setDbValue($row['contacto']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->zona->setDbValue($row['zona']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->web->setDbValue($row['web']);
        $this->tipo_cliente->setDbValue($row['tipo_cliente']);
        $this->tarifa->setDbValue($row['tarifa']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->limite_credito->setDbValue($row['limite_credito']);
        $this->condicion->setDbValue($row['condicion']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->activo->setDbValue($row['activo']);
        $this->foto1->Upload->DbValue = $row['foto1'];
        $this->foto2->Upload->DbValue = $row['foto2'];
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->descuento->setDbValue($row['descuento']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ClienteList";
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

        // ci_rif

        // nombre

        // sucursal

        // contacto

        // ciudad

        // zona

        // direccion

        // telefono1

        // telefono2

        // email1

        // email2

        // codigo_ims

        // web

        // tipo_cliente

        // tarifa

        // consignacion

        // limite_credito

        // condicion

        // cuenta

        // activo

        // foto1

        // foto2

        // dias_credito

        // descuento

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // ci_rif
        $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;

        // sucursal
        $this->sucursal->ViewValue = $this->sucursal->CurrentValue;

        // contacto
        $this->contacto->ViewValue = $this->contacto->CurrentValue;

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

        // zona
        $this->zona->ViewValue = $this->zona->CurrentValue;

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

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;

        // web
        $this->web->ViewValue = $this->web->CurrentValue;

        // tipo_cliente
        $curVal = strval($this->tipo_cliente->CurrentValue);
        if ($curVal != "") {
            $this->tipo_cliente->ViewValue = $this->tipo_cliente->lookupCacheOption($curVal);
            if ($this->tipo_cliente->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->tipo_cliente->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $lookupFilter = $this->tipo_cliente->getSelectFilter($this); // PHP
                $sqlWrk = $this->tipo_cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->displayValue($arwrk);
                } else {
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->CurrentValue;
                }
            }
        } else {
            $this->tipo_cliente->ViewValue = null;
        }

        // tarifa
        $curVal = strval($this->tarifa->CurrentValue);
        if ($curVal != "") {
            $this->tarifa->ViewValue = $this->tarifa->lookupCacheOption($curVal);
            if ($this->tarifa->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tarifa->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->tarifa->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $lookupFilter = $this->tarifa->getSelectFilter($this); // PHP
                $sqlWrk = $this->tarifa->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tarifa->Lookup->renderViewRow($rswrk[0]);
                    $this->tarifa->ViewValue = $this->tarifa->displayValue($arwrk);
                } else {
                    $this->tarifa->ViewValue = $this->tarifa->CurrentValue;
                }
            }
        } else {
            $this->tarifa->ViewValue = null;
        }

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }

        // limite_credito
        $this->limite_credito->ViewValue = $this->limite_credito->CurrentValue;
        $this->limite_credito->ViewValue = FormatNumber($this->limite_credito->ViewValue, $this->limite_credito->formatPattern());

        // condicion
        if (strval($this->condicion->CurrentValue) != "") {
            $this->condicion->ViewValue = $this->condicion->optionCaption($this->condicion->CurrentValue);
        } else {
            $this->condicion->ViewValue = null;
        }

        // cuenta
        $curVal = strval($this->cuenta->CurrentValue);
        if ($curVal != "") {
            $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
            if ($this->cuenta->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->cuenta->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $lookupFilter = $this->cuenta->getSelectFilter($this); // PHP
                $sqlWrk = $this->cuenta->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                } else {
                    $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
                }
            }
        } else {
            $this->cuenta->ViewValue = null;
        }

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }

        // foto1
        if (!EmptyValue($this->foto1->Upload->DbValue)) {
            $this->foto1->ImageWidth = 120;
            $this->foto1->ImageHeight = 120;
            $this->foto1->ImageAlt = $this->foto1->alt();
            $this->foto1->ImageCssClass = "ew-image";
            $this->foto1->ViewValue = $this->foto1->Upload->DbValue;
        } else {
            $this->foto1->ViewValue = "";
        }

        // foto2
        if (!EmptyValue($this->foto2->Upload->DbValue)) {
            $this->foto2->ImageWidth = 120;
            $this->foto2->ImageHeight = 120;
            $this->foto2->ImageAlt = $this->foto2->alt();
            $this->foto2->ImageCssClass = "ew-image";
            $this->foto2->ViewValue = $this->foto2->Upload->DbValue;
        } else {
            $this->foto2->ViewValue = "";
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
                    $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->CurrentValue, $this->dias_credito->formatPattern());
                }
            }
        } else {
            $this->dias_credito->ViewValue = null;
        }

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // ci_rif
        $this->ci_rif->HrefValue = "";
        $this->ci_rif->TooltipValue = "";

        // nombre
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // sucursal
        $this->sucursal->HrefValue = "";
        $this->sucursal->TooltipValue = "";

        // contacto
        $this->contacto->HrefValue = "";
        $this->contacto->TooltipValue = "";

        // ciudad
        $this->ciudad->HrefValue = "";
        $this->ciudad->TooltipValue = "";

        // zona
        $this->zona->HrefValue = "";
        $this->zona->TooltipValue = "";

        // direccion
        $this->direccion->HrefValue = "";
        $this->direccion->TooltipValue = "";

        // telefono1
        $this->telefono1->HrefValue = "";
        $this->telefono1->TooltipValue = "";

        // telefono2
        $this->telefono2->HrefValue = "";
        $this->telefono2->TooltipValue = "";

        // email1
        $this->email1->HrefValue = "";
        $this->email1->TooltipValue = "";

        // email2
        $this->email2->HrefValue = "";
        $this->email2->TooltipValue = "";

        // codigo_ims
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // web
        $this->web->HrefValue = "";
        $this->web->TooltipValue = "";

        // tipo_cliente
        $this->tipo_cliente->HrefValue = "";
        $this->tipo_cliente->TooltipValue = "";

        // tarifa
        $this->tarifa->HrefValue = "";
        $this->tarifa->TooltipValue = "";

        // consignacion
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // limite_credito
        $this->limite_credito->HrefValue = "";
        $this->limite_credito->TooltipValue = "";

        // condicion
        $this->condicion->HrefValue = "";
        $this->condicion->TooltipValue = "";

        // cuenta
        $this->cuenta->HrefValue = "";
        $this->cuenta->TooltipValue = "";

        // activo
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // foto1
        if (!EmptyValue($this->foto1->Upload->DbValue)) {
            $this->foto1->HrefValue = GetFileUploadUrl($this->foto1, $this->foto1->htmlDecode($this->foto1->Upload->DbValue)); // Add prefix/suffix
            $this->foto1->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->foto1->HrefValue = FullUrl($this->foto1->HrefValue, "href");
            }
        } else {
            $this->foto1->HrefValue = "";
        }
        $this->foto1->ExportHrefValue = $this->foto1->UploadPath . $this->foto1->Upload->DbValue;
        $this->foto1->TooltipValue = "";
        if ($this->foto1->UseColorbox) {
            if (EmptyValue($this->foto1->TooltipValue)) {
                $this->foto1->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->foto1->LinkAttrs["data-rel"] = "cliente_x_foto1";
            $this->foto1->LinkAttrs->appendClass("ew-lightbox");
        }

        // foto2
        if (!EmptyValue($this->foto2->Upload->DbValue)) {
            $this->foto2->HrefValue = GetFileUploadUrl($this->foto2, $this->foto2->htmlDecode($this->foto2->Upload->DbValue)); // Add prefix/suffix
            $this->foto2->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->foto2->HrefValue = FullUrl($this->foto2->HrefValue, "href");
            }
        } else {
            $this->foto2->HrefValue = "";
        }
        $this->foto2->ExportHrefValue = $this->foto2->UploadPath . $this->foto2->Upload->DbValue;
        $this->foto2->TooltipValue = "";
        if ($this->foto2->UseColorbox) {
            if (EmptyValue($this->foto2->TooltipValue)) {
                $this->foto2->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->foto2->LinkAttrs["data-rel"] = "cliente_x_foto2";
            $this->foto2->LinkAttrs->appendClass("ew-lightbox");
        }

        // dias_credito
        $this->dias_credito->HrefValue = "";
        $this->dias_credito->TooltipValue = "";

        // descuento
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

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

        // sucursal
        $this->sucursal->setupEditAttributes();
        if (!$this->sucursal->Raw) {
            $this->sucursal->CurrentValue = HtmlDecode($this->sucursal->CurrentValue);
        }
        $this->sucursal->EditValue = $this->sucursal->CurrentValue;
        $this->sucursal->PlaceHolder = RemoveHtml($this->sucursal->caption());

        // contacto
        $this->contacto->setupEditAttributes();
        if (!$this->contacto->Raw) {
            $this->contacto->CurrentValue = HtmlDecode($this->contacto->CurrentValue);
        }
        $this->contacto->EditValue = $this->contacto->CurrentValue;
        $this->contacto->PlaceHolder = RemoveHtml($this->contacto->caption());

        // ciudad
        $this->ciudad->setupEditAttributes();
        if (!$this->ciudad->Raw) {
            $this->ciudad->CurrentValue = HtmlDecode($this->ciudad->CurrentValue);
        }
        $this->ciudad->EditValue = $this->ciudad->CurrentValue;
        $this->ciudad->PlaceHolder = RemoveHtml($this->ciudad->caption());

        // zona
        $this->zona->setupEditAttributes();
        if (!$this->zona->Raw) {
            $this->zona->CurrentValue = HtmlDecode($this->zona->CurrentValue);
        }
        $this->zona->EditValue = $this->zona->CurrentValue;
        $this->zona->PlaceHolder = RemoveHtml($this->zona->caption());

        // direccion
        $this->direccion->setupEditAttributes();
        $this->direccion->EditValue = $this->direccion->CurrentValue;
        $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

        // telefono1
        $this->telefono1->setupEditAttributes();
        if (!$this->telefono1->Raw) {
            $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
        }
        $this->telefono1->EditValue = $this->telefono1->CurrentValue;
        $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

        // telefono2
        $this->telefono2->setupEditAttributes();
        if (!$this->telefono2->Raw) {
            $this->telefono2->CurrentValue = HtmlDecode($this->telefono2->CurrentValue);
        }
        $this->telefono2->EditValue = $this->telefono2->CurrentValue;
        $this->telefono2->PlaceHolder = RemoveHtml($this->telefono2->caption());

        // email1
        $this->email1->setupEditAttributes();
        if (!$this->email1->Raw) {
            $this->email1->CurrentValue = HtmlDecode($this->email1->CurrentValue);
        }
        $this->email1->EditValue = $this->email1->CurrentValue;
        $this->email1->PlaceHolder = RemoveHtml($this->email1->caption());

        // email2
        $this->email2->setupEditAttributes();
        if (!$this->email2->Raw) {
            $this->email2->CurrentValue = HtmlDecode($this->email2->CurrentValue);
        }
        $this->email2->EditValue = $this->email2->CurrentValue;
        $this->email2->PlaceHolder = RemoveHtml($this->email2->caption());

        // codigo_ims
        $this->codigo_ims->setupEditAttributes();
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // web
        $this->web->setupEditAttributes();
        if (!$this->web->Raw) {
            $this->web->CurrentValue = HtmlDecode($this->web->CurrentValue);
        }
        $this->web->EditValue = $this->web->CurrentValue;
        $this->web->PlaceHolder = RemoveHtml($this->web->caption());

        // tipo_cliente
        $this->tipo_cliente->setupEditAttributes();
        $this->tipo_cliente->PlaceHolder = RemoveHtml($this->tipo_cliente->caption());

        // tarifa
        $this->tarifa->setupEditAttributes();
        $this->tarifa->PlaceHolder = RemoveHtml($this->tarifa->caption());

        // consignacion
        $this->consignacion->setupEditAttributes();
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // limite_credito
        $this->limite_credito->setupEditAttributes();
        $this->limite_credito->EditValue = $this->limite_credito->CurrentValue;
        $this->limite_credito->PlaceHolder = RemoveHtml($this->limite_credito->caption());
        if (strval($this->limite_credito->EditValue) != "" && is_numeric($this->limite_credito->EditValue)) {
            $this->limite_credito->EditValue = FormatNumber($this->limite_credito->EditValue, null);
        }

        // condicion
        $this->condicion->EditValue = $this->condicion->options(false);
        $this->condicion->PlaceHolder = RemoveHtml($this->condicion->caption());

        // cuenta
        $this->cuenta->setupEditAttributes();
        $this->cuenta->PlaceHolder = RemoveHtml($this->cuenta->caption());

        // activo
        $this->activo->setupEditAttributes();
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // foto1
        $this->foto1->setupEditAttributes();
        if (!EmptyValue($this->foto1->Upload->DbValue)) {
            $this->foto1->ImageWidth = 120;
            $this->foto1->ImageHeight = 120;
            $this->foto1->ImageAlt = $this->foto1->alt();
            $this->foto1->ImageCssClass = "ew-image";
            $this->foto1->EditValue = $this->foto1->Upload->DbValue;
        } else {
            $this->foto1->EditValue = "";
        }
        if (!EmptyValue($this->foto1->CurrentValue)) {
            $this->foto1->Upload->FileName = $this->foto1->CurrentValue;
        }

        // foto2
        $this->foto2->setupEditAttributes();
        if (!EmptyValue($this->foto2->Upload->DbValue)) {
            $this->foto2->ImageWidth = 120;
            $this->foto2->ImageHeight = 120;
            $this->foto2->ImageAlt = $this->foto2->alt();
            $this->foto2->ImageCssClass = "ew-image";
            $this->foto2->EditValue = $this->foto2->Upload->DbValue;
        } else {
            $this->foto2->EditValue = "";
        }
        if (!EmptyValue($this->foto2->CurrentValue)) {
            $this->foto2->Upload->FileName = $this->foto2->CurrentValue;
        }

        // dias_credito
        $this->dias_credito->setupEditAttributes();
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

        // descuento
        $this->descuento->setupEditAttributes();
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
        if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
            $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
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
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->sucursal);
                    $doc->exportCaption($this->contacto);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->zona);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->web);
                    $doc->exportCaption($this->tipo_cliente);
                    $doc->exportCaption($this->tarifa);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->limite_credito);
                    $doc->exportCaption($this->condicion);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->foto1);
                    $doc->exportCaption($this->foto2);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->descuento);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->sucursal);
                    $doc->exportCaption($this->contacto);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->zona);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->web);
                    $doc->exportCaption($this->tipo_cliente);
                    $doc->exportCaption($this->tarifa);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->limite_credito);
                    $doc->exportCaption($this->condicion);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->descuento);
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
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->sucursal);
                        $doc->exportField($this->contacto);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->zona);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->web);
                        $doc->exportField($this->tipo_cliente);
                        $doc->exportField($this->tarifa);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->limite_credito);
                        $doc->exportField($this->condicion);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->foto1);
                        $doc->exportField($this->foto2);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->descuento);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->sucursal);
                        $doc->exportField($this->contacto);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->zona);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->web);
                        $doc->exportField($this->tipo_cliente);
                        $doc->exportField($this->tarifa);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->limite_credito);
                        $doc->exportField($this->condicion);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->descuento);
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
        if ($fldparm == 'foto1') {
            $fldName = "foto1";
            $fileNameFld = "foto1";
        } elseif ($fldparm == 'foto2') {
            $fldName = "foto2";
            $fileNameFld = "foto2";
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
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'cliente');
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
                WriteAuditLog($usr, "A", 'cliente', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'cliente', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'cliente', $fldname, $key, $oldvalue);
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
    public function rowInserting($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$rsnew["ci_rif"] = trim(strtoupper($rsnew["ci_rif"]));
    	$rsnew["email2"] = trim($rsnew["email2"]);
    	$rsnew["web"] = trim(strtoupper($rsnew["web"]));
    	if(trim($rsnew["ci_rif"]) != "") {
    		$sql = "SELECT COUNT(ci_rif) AS cantidad FROM cliente WHERE ci_rif = '" . $rsnew["ci_rif"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "RIF / CI \"" . $rsnew["ci_rif"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew) {
    	//echo "Row Inserted"
    	$sql = "SELECT valor2 AS cuenta FROM parametro WHERE codigo = '018' AND valor1 = 'Clientes';";
    	$cuenta = explode("-", ExecuteScalar($sql));
    	if(count($cuenta) > 2) {
    		$clase = trim($cuenta["0"]);
    		$grupo = trim($cuenta["1"]);
    		$cuenta = trim($cuenta["2"]);
    		$subcuenta = str_pad(intval($rsnew["id"]), 4, '0', STR_PAD_LEFT);
    		$where = "LTRIM(RTRIM(clase)) = '$clase' ";
    		$where .= "AND LTRIM(RTRIM(grupo)) = '$grupo' ";
    		$where .= "AND LTRIM(RTRIM(cuenta)) = '$cuenta' ";
    		$where .= "AND LTRIM(RTRIM(subcuenta)) = '$subcuenta' ";
    		$sql = "SELECT 
    					COUNT(descripcion) AS cantidad 
    				FROM 
    					cont_plancta 
    				WHERE 
    					$where;";
    		$cantidad = intval(ExecuteScalar($sql));
    		if($cantidad == 0) {
    			$sql = "INSERT INTO cont_plancta
    					(clase, 
    					grupo, 
    					cuenta, 
    					subcuenta, 
    					descripcion, 
    					clasificacion, 
    					activa)
    				VALUES
    					(
    					'$clase', 
    					'$grupo', 
    					'$cuenta', 
    					'$subcuenta', 
    					'" . $rsnew["nombre"] . "',
    					'ACTIVO', 
    					'S'
    					)";
    			Execute($sql);
    			$sql = "SELECT LAST_INSERT_ID();";
    			$idCTA = ExecuteScalar($sql);
    			$sql = "UPDATE cliente SET cuenta = $idCTA WHERE id = '" . $rsnew["id"] . "'";
    			Execute($sql);
    		}
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$rsnew["ci_rif"] = trim(strtoupper($rsnew["ci_rif"]));
    	$rsnew["email2"] = trim($rsnew["email2"]);
    	$rsnew["web"] = trim(strtoupper($rsnew["web"]));
    	if(trim($rsnew["ci_rif"]) != "" and $rsold["ci_rif"] <> $rsnew["ci_rif"]) {
    		$sql = "SELECT COUNT(ci_rif) AS cantidad FROM cliente WHERE ci_rif = '" . $rsnew["ci_rif"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "RF /CI \"" . $rsnew["ci_rif"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["cuenta"]) != "" and $rsnew["cuenta"] != $rsold["cuenta"]) {
    		$sql = "SELECT COUNT(id) AS cantidad FROM cliente WHERE cuenta = " . $rsnew["cuenta"] . "";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "La cuenta seleccionda ya se est&aacute; usando con otro Cliente; verifique";
    			return FALSE;
    		}
    	}
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, $rsnew)
    {
        //Log("Row Updated");
        if($rsold["cuenta"] == "" and $rsnew["cuenta"] == "") {
    		$sql = "SELECT valor2 AS cuenta FROM parametro WHERE codigo = '018' AND valor1 = 'Clientes';";
    		$cuenta = explode("-", ExecuteScalar($sql));
    		if(count($cuenta) > 2) {
    			$clase = trim($cuenta["0"]);
    			$grupo = trim($cuenta["1"]);
    			$cuenta = trim($cuenta["2"]);
    			$subcuenta = str_pad(intval($rsold["id"]), 4, '0', STR_PAD_LEFT);
    			$where = "LTRIM(RTRIM(clase)) = '$clase' ";
    			$where .= "AND LTRIM(RTRIM(grupo)) = '$grupo' ";
    			$where .= "AND LTRIM(RTRIM(cuenta)) = '$cuenta' ";
    			$where .= "AND LTRIM(RTRIM(subcuenta)) = '$subcuenta' ";
    			$sql = "SELECT 
    						COUNT(descripcion) AS cantidad 
    					FROM 
    						cont_plancta 
    					WHERE 
    						$where;";
    			$cantidad = intval(ExecuteScalar($sql));
    			if($cantidad == 0) {
    				$sql = "INSERT INTO cont_plancta
    						(clase, 
    						grupo, 
    						cuenta, 
    						subcuenta, 
    						descripcion, 
    						clasificacion, 
    						activa)
    					VALUES
    						(
    						'$clase', 
    						'$grupo', 
    						'$cuenta', 
    						'$subcuenta', 
    						'" . $rsnew["nombre"] . "',
    						'ACTIVO', 
    						'S'
    						)";
    				Execute($sql);
    				$sql = "SELECT LAST_INSERT_ID();";
    				$idCTA = ExecuteScalar($sql);
    			}
    			else {
    				$sql = "SELECT 
    						id  
    					FROM 
    						cont_plancta 
    					WHERE 
    						$where;";
    				$idCTA = intval(ExecuteScalar($sql));
    			}
    			$sql = "UPDATE cliente SET cuenta = $idCTA WHERE id = '" . $rsold["id"] . "'";
    			Execute($sql);
    		}
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
    	$sql = "SELECT COUNT(id) AS cantidad FROM salidas WHERE cliente = '" . $rs["id"] . "';"; 
    	$cantidad = intval(ExecuteScalar($sql));
    	if($cantidad > 0) {
    		$this->CancelMessage = "Este cliente no se puede eliminar porque tiene movimientos asociados.";
    		return FALSE;
    	}
    	$sql = "SELECT id FROM cont_plancta WHERE id = " . ($rs["cuenta"] == "" ? 0 : $rs["cuenta"]) . ";";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El cliente no se puede eliminar porque tiene un auxiliar contable asociado; !Verifique!";
    		return FALSE;
    	}
    	return TRUE;
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
        if ($this->PageID == "list" || $this->PageID == "view") { 
            if(isset($this->tipo_cliente->CurrentValue)) {
                if(trim($this->tipo_cliente->CurrentValue) != "") {
                    $sql = "SELECT campo_dato AS color FROM tabla WHERE tabla = 'tipo_cliente' AND campo_codigo = '" . $this->tipo_cliente->CurrentValue . "';"; 
                    $valor = trim(ExecuteScalar($sql)); 
                    if ($valor != "") {
                        $color = explode(";", $valor);
                        $valor = 'background-color: ' . $color[0] . '; color: ' . $color[1] . ';';
                        $this->tipo_cliente->CellAttrs["style"] = $valor;
                    } 
                }
            }
        }
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
