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
 * Table class for articulo
 */
class Articulo extends DbTable
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
    public $foto;
    public $codigo;
    public $nombre_comercial;
    public $principio_activo;
    public $presentacion;
    public $fabricante;
    public $codigo_ims;
    public $codigo_de_barra;
    public $categoria;
    public $lista_pedido;
    public $unidad_medida_defecto;
    public $cantidad_por_unidad_medida;
    public $cantidad_minima;
    public $cantidad_maxima;
    public $cantidad_en_mano;
    public $cantidad_en_almacenes;
    public $cantidad_en_pedido;
    public $cantidad_en_transito;
    public $ultimo_costo;
    public $descuento;
    public $precio;
    public $alicuota;
    public $articulo_inventario;
    public $activo;
    public $lote;
    public $fecha_vencimiento;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "articulo";
        $this->TableName = 'articulo';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "articulo";
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

        // foto
        $this->foto = new DbField(
            $this, // Table
            'x_foto', // Variable name
            'foto', // Name
            '`foto`', // Expression
            '`foto`', // Basic search expression
            200, // Type
            250, // Size
            -1, // Date/Time format
            true, // Is upload field
            '`foto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'IMAGE', // View Tag
            'FILE' // Edit Tag
        );
        $this->foto->InputTextType = "text";
        $this->foto->SearchOperators = ["=", "<>", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['foto'] = &$this->foto;

        // codigo
        $this->codigo = new DbField(
            $this, // Table
            'x_codigo', // Variable name
            'codigo', // Name
            '`codigo`', // Expression
            '`codigo`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codigo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codigo->InputTextType = "text";
        $this->codigo->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['codigo'] = &$this->codigo;

        // nombre_comercial
        $this->nombre_comercial = new DbField(
            $this, // Table
            'x_nombre_comercial', // Variable name
            'nombre_comercial', // Name
            '`nombre_comercial`', // Expression
            '`nombre_comercial`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nombre_comercial`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nombre_comercial->InputTextType = "text";
        $this->nombre_comercial->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nombre_comercial'] = &$this->nombre_comercial;

        // principio_activo
        $this->principio_activo = new DbField(
            $this, // Table
            'x_principio_activo', // Variable name
            'principio_activo', // Name
            '`principio_activo`', // Expression
            '`principio_activo`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`principio_activo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->principio_activo->InputTextType = "text";
        $this->principio_activo->Required = true; // Required field
        $this->principio_activo->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['principio_activo'] = &$this->principio_activo;

        // presentacion
        $this->presentacion = new DbField(
            $this, // Table
            'x_presentacion', // Variable name
            'presentacion', // Name
            '`presentacion`', // Expression
            '`presentacion`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`presentacion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->presentacion->InputTextType = "text";
        $this->presentacion->Required = true; // Required field
        $this->presentacion->Lookup = new Lookup($this->presentacion, 'articulo', true, 'presentacion', ["presentacion","","",""], '', '', [], [], [], [], [], [], false, '`presentacion`', '', "`presentacion`");
        $this->presentacion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['presentacion'] = &$this->presentacion;

        // fabricante
        $this->fabricante = new DbField(
            $this, // Table
            'x_fabricante', // Variable name
            'fabricante', // Name
            '`fabricante`', // Expression
            '`fabricante`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`fabricante`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->fabricante->InputTextType = "text";
        $this->fabricante->Raw = true;
        $this->fabricante->Required = true; // Required field
        $this->fabricante->setSelectMultiple(false); // Select one
        $this->fabricante->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->fabricante->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->fabricante->Lookup = new Lookup($this->fabricante, 'fabricante', false, 'Id', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '', '', "`nombre`");
        $this->fabricante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->fabricante->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fabricante'] = &$this->fabricante;

        // codigo_ims
        $this->codigo_ims = new DbField(
            $this, // Table
            'x_codigo_ims', // Variable name
            'codigo_ims', // Name
            '`codigo_ims`', // Expression
            '`codigo_ims`', // Basic search expression
            200, // Type
            50, // Size
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

        // codigo_de_barra
        $this->codigo_de_barra = new DbField(
            $this, // Table
            'x_codigo_de_barra', // Variable name
            'codigo_de_barra', // Name
            '`codigo_de_barra`', // Expression
            '`codigo_de_barra`', // Basic search expression
            200, // Type
            50, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codigo_de_barra`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codigo_de_barra->InputTextType = "text";
        $this->codigo_de_barra->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['codigo_de_barra'] = &$this->codigo_de_barra;

        // categoria
        $this->categoria = new DbField(
            $this, // Table
            'x_categoria', // Variable name
            'categoria', // Name
            '`categoria`', // Expression
            '`categoria`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`categoria`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->categoria->addMethod("getSelectFilter", fn() => "`tabla` = 'CATEGORIA'");
        $this->categoria->InputTextType = "text";
        $this->categoria->setSelectMultiple(false); // Select one
        $this->categoria->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->categoria->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->categoria->Lookup = new Lookup($this->categoria, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion`', '', "`campo_descripcion`");
        $this->categoria->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['categoria'] = &$this->categoria;

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

        // unidad_medida_defecto
        $this->unidad_medida_defecto = new DbField(
            $this, // Table
            'x_unidad_medida_defecto', // Variable name
            'unidad_medida_defecto', // Name
            '`unidad_medida_defecto`', // Expression
            '`unidad_medida_defecto`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`unidad_medida_defecto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->unidad_medida_defecto->addMethod("getSelectFilter", fn() => "`codigo` = 'UDM001'");
        $this->unidad_medida_defecto->InputTextType = "text";
        $this->unidad_medida_defecto->Required = true; // Required field
        $this->unidad_medida_defecto->setSelectMultiple(false); // Select one
        $this->unidad_medida_defecto->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->unidad_medida_defecto->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->unidad_medida_defecto->Lookup = new Lookup($this->unidad_medida_defecto, 'unidad_medida', false, 'codigo', ["descripcion","","",""], '', '', [], ["x_cantidad_por_unidad_medida"], [], [], [], [], false, '`descripcion`', '', "`descripcion`");
        $this->unidad_medida_defecto->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['unidad_medida_defecto'] = &$this->unidad_medida_defecto;

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida = new DbField(
            $this, // Table
            'x_cantidad_por_unidad_medida', // Variable name
            'cantidad_por_unidad_medida', // Name
            '`cantidad_por_unidad_medida`', // Expression
            '`cantidad_por_unidad_medida`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_por_unidad_medida`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->cantidad_por_unidad_medida->InputTextType = "text";
        $this->cantidad_por_unidad_medida->Raw = true;
        $this->cantidad_por_unidad_medida->Required = true; // Required field
        $this->cantidad_por_unidad_medida->setSelectMultiple(false); // Select one
        $this->cantidad_por_unidad_medida->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cantidad_por_unidad_medida->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cantidad_por_unidad_medida->Lookup = new Lookup($this->cantidad_por_unidad_medida, 'unidad_medida', false, 'cantidad', ["cantidad","","",""], '', '', ["x_unidad_medida_defecto"], [], ["codigo"], ["x_codigo"], [], [], false, '', '', "`cantidad`");
        $this->cantidad_por_unidad_medida->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_por_unidad_medida->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cantidad_por_unidad_medida'] = &$this->cantidad_por_unidad_medida;

        // cantidad_minima
        $this->cantidad_minima = new DbField(
            $this, // Table
            'x_cantidad_minima', // Variable name
            'cantidad_minima', // Name
            '`cantidad_minima`', // Expression
            '`cantidad_minima`', // Basic search expression
            131, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_minima`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_minima->addMethod("getDefault", fn() => 0);
        $this->cantidad_minima->InputTextType = "text";
        $this->cantidad_minima->Raw = true;
        $this->cantidad_minima->Nullable = false; // NOT NULL field
        $this->cantidad_minima->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_minima->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_minima'] = &$this->cantidad_minima;

        // cantidad_maxima
        $this->cantidad_maxima = new DbField(
            $this, // Table
            'x_cantidad_maxima', // Variable name
            'cantidad_maxima', // Name
            '`cantidad_maxima`', // Expression
            '`cantidad_maxima`', // Basic search expression
            131, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_maxima`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_maxima->addMethod("getDefault", fn() => 0);
        $this->cantidad_maxima->InputTextType = "text";
        $this->cantidad_maxima->Raw = true;
        $this->cantidad_maxima->Nullable = false; // NOT NULL field
        $this->cantidad_maxima->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_maxima->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_maxima'] = &$this->cantidad_maxima;

        // cantidad_en_mano
        $this->cantidad_en_mano = new DbField(
            $this, // Table
            'x_cantidad_en_mano', // Variable name
            'cantidad_en_mano', // Name
            '`cantidad_en_mano`', // Expression
            '`cantidad_en_mano`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_en_mano`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_en_mano->addMethod("getDefault", fn() => 0.00);
        $this->cantidad_en_mano->InputTextType = "text";
        $this->cantidad_en_mano->Raw = true;
        $this->cantidad_en_mano->Nullable = false; // NOT NULL field
        $this->cantidad_en_mano->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_mano->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_en_mano'] = &$this->cantidad_en_mano;

        // cantidad_en_almacenes
        $this->cantidad_en_almacenes = new DbField(
            $this, // Table
            'x_cantidad_en_almacenes', // Variable name
            'cantidad_en_almacenes', // Name
            '`cantidad_en_almacenes`', // Expression
            '`cantidad_en_almacenes`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_en_almacenes`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_en_almacenes->addMethod("getDefault", fn() => 0.00);
        $this->cantidad_en_almacenes->InputTextType = "text";
        $this->cantidad_en_almacenes->Raw = true;
        $this->cantidad_en_almacenes->Nullable = false; // NOT NULL field
        $this->cantidad_en_almacenes->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_almacenes->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_en_almacenes'] = &$this->cantidad_en_almacenes;

        // cantidad_en_pedido
        $this->cantidad_en_pedido = new DbField(
            $this, // Table
            'x_cantidad_en_pedido', // Variable name
            'cantidad_en_pedido', // Name
            '`cantidad_en_pedido`', // Expression
            '`cantidad_en_pedido`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_en_pedido`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_en_pedido->addMethod("getDefault", fn() => 0.00);
        $this->cantidad_en_pedido->InputTextType = "text";
        $this->cantidad_en_pedido->Raw = true;
        $this->cantidad_en_pedido->Nullable = false; // NOT NULL field
        $this->cantidad_en_pedido->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_pedido->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_en_pedido'] = &$this->cantidad_en_pedido;

        // cantidad_en_transito
        $this->cantidad_en_transito = new DbField(
            $this, // Table
            'x_cantidad_en_transito', // Variable name
            'cantidad_en_transito', // Name
            '`cantidad_en_transito`', // Expression
            '`cantidad_en_transito`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_en_transito`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_en_transito->addMethod("getDefault", fn() => 0.00);
        $this->cantidad_en_transito->InputTextType = "text";
        $this->cantidad_en_transito->Raw = true;
        $this->cantidad_en_transito->Nullable = false; // NOT NULL field
        $this->cantidad_en_transito->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_transito->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_en_transito'] = &$this->cantidad_en_transito;

        // ultimo_costo
        $this->ultimo_costo = new DbField(
            $this, // Table
            'x_ultimo_costo', // Variable name
            'ultimo_costo', // Name
            '`ultimo_costo`', // Expression
            '`ultimo_costo`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ultimo_costo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ultimo_costo->addMethod("getDefault", fn() => 0.00);
        $this->ultimo_costo->InputTextType = "text";
        $this->ultimo_costo->Raw = true;
        $this->ultimo_costo->Nullable = false; // NOT NULL field
        $this->ultimo_costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ultimo_costo->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['ultimo_costo'] = &$this->ultimo_costo;

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
        $this->descuento->addMethod("getDefault", fn() => 0.00);
        $this->descuento->InputTextType = "text";
        $this->descuento->Raw = true;
        $this->descuento->Nullable = false; // NOT NULL field
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->descuento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['descuento'] = &$this->descuento;

        // precio
        $this->precio = new DbField(
            $this, // Table
            'x_precio', // Variable name
            'precio', // Name
            '`precio`', // Expression
            '`precio`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`precio`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->precio->addMethod("getDefault", fn() => 0.00);
        $this->precio->InputTextType = "text";
        $this->precio->Raw = true;
        $this->precio->Nullable = false; // NOT NULL field
        $this->precio->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['precio'] = &$this->precio;

        // alicuota
        $this->alicuota = new DbField(
            $this, // Table
            'x_alicuota', // Variable name
            'alicuota', // Name
            '`alicuota`', // Expression
            '`alicuota`', // Basic search expression
            200, // Type
            3, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`alicuota`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->alicuota->addMethod("getSelectFilter", fn() => "`activo` = 'S'");
        $this->alicuota->addMethod("getDefault", fn() => "0");
        $this->alicuota->InputTextType = "text";
        $this->alicuota->Required = true; // Required field
        $this->alicuota->setSelectMultiple(false); // Select one
        $this->alicuota->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->alicuota->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->alicuota->Lookup = new Lookup($this->alicuota, 'alicuota', false, 'codigo', ["nombre","alicuota","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`nombre`, ''),'" . ValueSeparator(1, $this->alicuota) . "',COALESCE(`alicuota`,''))");
        $this->alicuota->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['alicuota'] = &$this->alicuota;

        // articulo_inventario
        $this->articulo_inventario = new DbField(
            $this, // Table
            'x_articulo_inventario', // Variable name
            'articulo_inventario', // Name
            '`articulo_inventario`', // Expression
            '`articulo_inventario`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`articulo_inventario`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->articulo_inventario->addMethod("getDefault", fn() => "S");
        $this->articulo_inventario->InputTextType = "text";
        $this->articulo_inventario->Raw = true;
        $this->articulo_inventario->Required = true; // Required field
        $this->articulo_inventario->setSelectMultiple(false); // Select one
        $this->articulo_inventario->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo_inventario->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo_inventario->Lookup = new Lookup($this->articulo_inventario, 'articulo', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->articulo_inventario->OptionCount = 2;
        $this->articulo_inventario->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['articulo_inventario'] = &$this->articulo_inventario;

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
        $this->activo->InputTextType = "text";
        $this->activo->Raw = true;
        $this->activo->Required = true; // Required field
        $this->activo->setSelectMultiple(false); // Select one
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup($this->activo, 'articulo', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->activo->OptionCount = 2;
        $this->activo->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['activo'] = &$this->activo;

        // lote
        $this->lote = new DbField(
            $this, // Table
            'x_lote', // Variable name
            'lote', // Name
            '`lote`', // Expression
            '`lote`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`lote`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->lote->InputTextType = "text";
        $this->lote->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['lote'] = &$this->lote;

        // fecha_vencimiento
        $this->fecha_vencimiento = new DbField(
            $this, // Table
            'x_fecha_vencimiento', // Variable name
            'fecha_vencimiento', // Name
            '`fecha_vencimiento`', // Expression
            CastDateFieldForLike("`fecha_vencimiento`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
            false, // Is upload field
            '`fecha_vencimiento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_vencimiento->InputTextType = "text";
        $this->fecha_vencimiento->Raw = true;
        $this->fecha_vencimiento->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_vencimiento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_vencimiento'] = &$this->fecha_vencimiento;

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
        if ($this->getCurrentDetailTable() == "articulo_unidad_medida") {
            $detailUrl = Container("articulo_unidad_medida")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "adjunto") {
            $detailUrl = Container("adjunto")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ArticuloList";
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "articulo";
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
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'articulo'
            $cascadeUpdate = true;
            $rscascade['articulo'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("adjunto")->loadRs("`articulo` = " . QuotedValue($rsold['id'], DataType::NUMBER, 'DB'))->fetchAllAssociative();
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
        $dtlrows = Container("adjunto")->loadRs("`articulo` = " . QuotedValue($rs['id'], DataType::NUMBER, "DB"))->fetchAllAssociative();
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
        $this->foto->Upload->DbValue = $row['foto'];
        $this->codigo->DbValue = $row['codigo'];
        $this->nombre_comercial->DbValue = $row['nombre_comercial'];
        $this->principio_activo->DbValue = $row['principio_activo'];
        $this->presentacion->DbValue = $row['presentacion'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->codigo_de_barra->DbValue = $row['codigo_de_barra'];
        $this->categoria->DbValue = $row['categoria'];
        $this->lista_pedido->DbValue = $row['lista_pedido'];
        $this->unidad_medida_defecto->DbValue = $row['unidad_medida_defecto'];
        $this->cantidad_por_unidad_medida->DbValue = $row['cantidad_por_unidad_medida'];
        $this->cantidad_minima->DbValue = $row['cantidad_minima'];
        $this->cantidad_maxima->DbValue = $row['cantidad_maxima'];
        $this->cantidad_en_mano->DbValue = $row['cantidad_en_mano'];
        $this->cantidad_en_almacenes->DbValue = $row['cantidad_en_almacenes'];
        $this->cantidad_en_pedido->DbValue = $row['cantidad_en_pedido'];
        $this->cantidad_en_transito->DbValue = $row['cantidad_en_transito'];
        $this->ultimo_costo->DbValue = $row['ultimo_costo'];
        $this->descuento->DbValue = $row['descuento'];
        $this->precio->DbValue = $row['precio'];
        $this->alicuota->DbValue = $row['alicuota'];
        $this->articulo_inventario->DbValue = $row['articulo_inventario'];
        $this->activo->DbValue = $row['activo'];
        $this->lote->DbValue = $row['lote'];
        $this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['foto']) ? [] : [$row['foto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->foto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->foto->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("ArticuloList");
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
            "ArticuloView" => $Language->phrase("View"),
            "ArticuloEdit" => $Language->phrase("Edit"),
            "ArticuloAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ArticuloList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ArticuloView",
            Config("API_ADD_ACTION") => "ArticuloAdd",
            Config("API_EDIT_ACTION") => "ArticuloEdit",
            Config("API_DELETE_ACTION") => "ArticuloDelete",
            Config("API_LIST_ACTION") => "ArticuloList",
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
        return "ArticuloList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloView", $parm);
        } else {
            $url = $this->keyUrl("ArticuloView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ArticuloAdd?" . $parm;
        } else {
            $url = "ArticuloAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloEdit", $parm);
        } else {
            $url = $this->keyUrl("ArticuloEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ArticuloList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloAdd", $parm);
        } else {
            $url = $this->keyUrl("ArticuloAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ArticuloList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ArticuloDelete", $parm);
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
        $this->foto->Upload->DbValue = $row['foto'];
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->categoria->setDbValue($row['categoria']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidad_medida_defecto->setDbValue($row['unidad_medida_defecto']);
        $this->cantidad_por_unidad_medida->setDbValue($row['cantidad_por_unidad_medida']);
        $this->cantidad_minima->setDbValue($row['cantidad_minima']);
        $this->cantidad_maxima->setDbValue($row['cantidad_maxima']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->cantidad_en_almacenes->setDbValue($row['cantidad_en_almacenes']);
        $this->cantidad_en_pedido->setDbValue($row['cantidad_en_pedido']);
        $this->cantidad_en_transito->setDbValue($row['cantidad_en_transito']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio->setDbValue($row['precio']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->articulo_inventario->setDbValue($row['articulo_inventario']);
        $this->activo->setDbValue($row['activo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ArticuloList";
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

        // foto

        // codigo

        // nombre_comercial

        // principio_activo

        // presentacion

        // fabricante

        // codigo_ims

        // codigo_de_barra

        // categoria

        // lista_pedido

        // unidad_medida_defecto

        // cantidad_por_unidad_medida

        // cantidad_minima

        // cantidad_maxima

        // cantidad_en_mano

        // cantidad_en_almacenes

        // cantidad_en_pedido

        // cantidad_en_transito

        // ultimo_costo

        // descuento

        // precio

        // alicuota

        // articulo_inventario

        // activo

        // lote

        // fecha_vencimiento

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // foto
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->ImageWidth = 120;
            $this->foto->ImageHeight = 120;
            $this->foto->ImageAlt = $this->foto->alt();
            $this->foto->ImageCssClass = "ew-image";
            $this->foto->ViewValue = $this->foto->Upload->DbValue;
        } else {
            $this->foto->ViewValue = "";
        }

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;

        // nombre_comercial
        $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;

        // principio_activo
        $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;

        // presentacion
        $this->presentacion->ViewValue = $this->presentacion->CurrentValue;

        // fabricante
        $curVal = strval($this->fabricante->CurrentValue);
        if ($curVal != "") {
            $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            if ($this->fabricante->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->fabricante->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $curVal, $this->fabricante->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
                }
            }
        } else {
            $this->fabricante->ViewValue = null;
        }

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->CssClass = "fw-bold fst-italic";

        // codigo_de_barra
        $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;

        // categoria
        $curVal = strval($this->categoria->CurrentValue);
        if ($curVal != "") {
            $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
            if ($this->categoria->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->categoria->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $lookupFilter = $this->categoria->getSelectFilter($this); // PHP
                $sqlWrk = $this->categoria->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->categoria->Lookup->renderViewRow($rswrk[0]);
                    $this->categoria->ViewValue = $this->categoria->displayValue($arwrk);
                } else {
                    $this->categoria->ViewValue = $this->categoria->CurrentValue;
                }
            }
        } else {
            $this->categoria->ViewValue = null;
        }

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

        // unidad_medida_defecto
        $curVal = strval($this->unidad_medida_defecto->CurrentValue);
        if ($curVal != "") {
            $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
            if ($this->unidad_medida_defecto->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->unidad_medida_defecto->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                $lookupFilter = $this->unidad_medida_defecto->getSelectFilter($this); // PHP
                $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->unidad_medida_defecto->Lookup->renderViewRow($rswrk[0]);
                    $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->displayValue($arwrk);
                } else {
                    $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->CurrentValue;
                }
            }
        } else {
            $this->unidad_medida_defecto->ViewValue = null;
        }

        // cantidad_por_unidad_medida
        $curVal = strval($this->cantidad_por_unidad_medida->CurrentValue);
        if ($curVal != "") {
            $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
            if ($this->cantidad_por_unidad_medida->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchExpression(), "=", $curVal, $this->cantidad_por_unidad_medida->Lookup->getTable()->Fields["cantidad"]->searchDataType(), "");
                $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                    $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->displayValue($arwrk);
                } else {
                    $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->CurrentValue;
                }
            }
        } else {
            $this->cantidad_por_unidad_medida->ViewValue = null;
        }

        // cantidad_minima
        $this->cantidad_minima->ViewValue = $this->cantidad_minima->CurrentValue;
        $this->cantidad_minima->ViewValue = FormatNumber($this->cantidad_minima->ViewValue, $this->cantidad_minima->formatPattern());

        // cantidad_maxima
        $this->cantidad_maxima->ViewValue = $this->cantidad_maxima->CurrentValue;
        $this->cantidad_maxima->ViewValue = FormatNumber($this->cantidad_maxima->ViewValue, $this->cantidad_maxima->formatPattern());

        // cantidad_en_mano
        $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, $this->cantidad_en_mano->formatPattern());

        // cantidad_en_almacenes
        $this->cantidad_en_almacenes->ViewValue = $this->cantidad_en_almacenes->CurrentValue;
        $this->cantidad_en_almacenes->ViewValue = FormatNumber($this->cantidad_en_almacenes->ViewValue, $this->cantidad_en_almacenes->formatPattern());

        // cantidad_en_pedido
        $this->cantidad_en_pedido->ViewValue = $this->cantidad_en_pedido->CurrentValue;
        $this->cantidad_en_pedido->ViewValue = FormatNumber($this->cantidad_en_pedido->ViewValue, $this->cantidad_en_pedido->formatPattern());

        // cantidad_en_transito
        $this->cantidad_en_transito->ViewValue = $this->cantidad_en_transito->CurrentValue;
        $this->cantidad_en_transito->ViewValue = FormatNumber($this->cantidad_en_transito->ViewValue, $this->cantidad_en_transito->formatPattern());

        // ultimo_costo
        $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, $this->ultimo_costo->formatPattern());

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->formatPattern());

        // precio
        $this->precio->ViewValue = $this->precio->CurrentValue;
        $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->formatPattern());

        // alicuota
        $curVal = strval($this->alicuota->CurrentValue);
        if ($curVal != "") {
            $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
            if ($this->alicuota->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->alicuota->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->alicuota->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                $lookupFilter = $this->alicuota->getSelectFilter($this); // PHP
                $sqlWrk = $this->alicuota->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->alicuota->Lookup->renderViewRow($rswrk[0]);
                    $this->alicuota->ViewValue = $this->alicuota->displayValue($arwrk);
                } else {
                    $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
                }
            }
        } else {
            $this->alicuota->ViewValue = null;
        }

        // articulo_inventario
        if (strval($this->articulo_inventario->CurrentValue) != "") {
            $this->articulo_inventario->ViewValue = $this->articulo_inventario->optionCaption($this->articulo_inventario->CurrentValue);
        } else {
            $this->articulo_inventario->ViewValue = null;
        }

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }

        // lote
        $this->lote->ViewValue = $this->lote->CurrentValue;

        // fecha_vencimiento
        $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
        $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // foto
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
            $this->foto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
            }
        } else {
            $this->foto->HrefValue = "";
        }
        $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;
        $this->foto->TooltipValue = "";
        if ($this->foto->UseColorbox) {
            if (EmptyValue($this->foto->TooltipValue)) {
                $this->foto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->foto->LinkAttrs["data-rel"] = "articulo_x_foto";
            $this->foto->LinkAttrs->appendClass("ew-lightbox");
        }

        // codigo
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // nombre_comercial
        $this->nombre_comercial->HrefValue = "";
        $this->nombre_comercial->TooltipValue = "";

        // principio_activo
        $this->principio_activo->HrefValue = "";
        $this->principio_activo->TooltipValue = "";

        // presentacion
        $this->presentacion->HrefValue = "";
        $this->presentacion->TooltipValue = "";

        // fabricante
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // codigo_ims
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // codigo_de_barra
        $this->codigo_de_barra->HrefValue = "";
        $this->codigo_de_barra->TooltipValue = "";

        // categoria
        $this->categoria->HrefValue = "";
        $this->categoria->TooltipValue = "";

        // lista_pedido
        $this->lista_pedido->HrefValue = "";
        $this->lista_pedido->TooltipValue = "";

        // unidad_medida_defecto
        $this->unidad_medida_defecto->HrefValue = "";
        $this->unidad_medida_defecto->TooltipValue = "";

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->HrefValue = "";
        $this->cantidad_por_unidad_medida->TooltipValue = "";

        // cantidad_minima
        $this->cantidad_minima->HrefValue = "";
        $this->cantidad_minima->TooltipValue = "";

        // cantidad_maxima
        $this->cantidad_maxima->HrefValue = "";
        $this->cantidad_maxima->TooltipValue = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->HrefValue = "";
        $this->cantidad_en_mano->TooltipValue = "";

        // cantidad_en_almacenes
        $this->cantidad_en_almacenes->HrefValue = "";
        $this->cantidad_en_almacenes->TooltipValue = "";

        // cantidad_en_pedido
        $this->cantidad_en_pedido->HrefValue = "";
        $this->cantidad_en_pedido->TooltipValue = "";

        // cantidad_en_transito
        $this->cantidad_en_transito->HrefValue = "";
        $this->cantidad_en_transito->TooltipValue = "";

        // ultimo_costo
        $this->ultimo_costo->HrefValue = "";
        $this->ultimo_costo->TooltipValue = "";

        // descuento
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

        // precio
        $this->precio->HrefValue = "";
        $this->precio->TooltipValue = "";

        // alicuota
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

        // articulo_inventario
        $this->articulo_inventario->HrefValue = "";
        $this->articulo_inventario->TooltipValue = "";

        // activo
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // lote
        $this->lote->HrefValue = "";
        $this->lote->TooltipValue = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->HrefValue = "";
        $this->fecha_vencimiento->TooltipValue = "";

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

        // foto
        $this->foto->setupEditAttributes();
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->ImageWidth = 120;
            $this->foto->ImageHeight = 120;
            $this->foto->ImageAlt = $this->foto->alt();
            $this->foto->ImageCssClass = "ew-image";
            $this->foto->EditValue = $this->foto->Upload->DbValue;
        } else {
            $this->foto->EditValue = "";
        }
        if (!EmptyValue($this->foto->CurrentValue)) {
            $this->foto->Upload->FileName = $this->foto->CurrentValue;
        }

        // codigo
        $this->codigo->setupEditAttributes();
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

        // nombre_comercial
        $this->nombre_comercial->setupEditAttributes();
        if (!$this->nombre_comercial->Raw) {
            $this->nombre_comercial->CurrentValue = HtmlDecode($this->nombre_comercial->CurrentValue);
        }
        $this->nombre_comercial->EditValue = $this->nombre_comercial->CurrentValue;
        $this->nombre_comercial->PlaceHolder = RemoveHtml($this->nombre_comercial->caption());

        // principio_activo
        $this->principio_activo->setupEditAttributes();
        if (!$this->principio_activo->Raw) {
            $this->principio_activo->CurrentValue = HtmlDecode($this->principio_activo->CurrentValue);
        }
        $this->principio_activo->EditValue = $this->principio_activo->CurrentValue;
        $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

        // presentacion
        $this->presentacion->setupEditAttributes();
        if (!$this->presentacion->Raw) {
            $this->presentacion->CurrentValue = HtmlDecode($this->presentacion->CurrentValue);
        }
        $this->presentacion->EditValue = $this->presentacion->CurrentValue;
        $this->presentacion->PlaceHolder = RemoveHtml($this->presentacion->caption());

        // fabricante
        $this->fabricante->setupEditAttributes();
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // codigo_ims
        $this->codigo_ims->setupEditAttributes();
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // codigo_de_barra
        $this->codigo_de_barra->setupEditAttributes();
        if (!$this->codigo_de_barra->Raw) {
            $this->codigo_de_barra->CurrentValue = HtmlDecode($this->codigo_de_barra->CurrentValue);
        }
        $this->codigo_de_barra->EditValue = $this->codigo_de_barra->CurrentValue;
        $this->codigo_de_barra->PlaceHolder = RemoveHtml($this->codigo_de_barra->caption());

        // categoria
        $this->categoria->setupEditAttributes();
        $this->categoria->PlaceHolder = RemoveHtml($this->categoria->caption());

        // lista_pedido
        $this->lista_pedido->setupEditAttributes();
        $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

        // unidad_medida_defecto
        $this->unidad_medida_defecto->setupEditAttributes();
        $this->unidad_medida_defecto->PlaceHolder = RemoveHtml($this->unidad_medida_defecto->caption());

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->setupEditAttributes();
        $this->cantidad_por_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_por_unidad_medida->caption());

        // cantidad_minima
        $this->cantidad_minima->setupEditAttributes();
        $this->cantidad_minima->EditValue = $this->cantidad_minima->CurrentValue;
        $this->cantidad_minima->PlaceHolder = RemoveHtml($this->cantidad_minima->caption());
        if (strval($this->cantidad_minima->EditValue) != "" && is_numeric($this->cantidad_minima->EditValue)) {
            $this->cantidad_minima->EditValue = FormatNumber($this->cantidad_minima->EditValue, null);
        }

        // cantidad_maxima
        $this->cantidad_maxima->setupEditAttributes();
        $this->cantidad_maxima->EditValue = $this->cantidad_maxima->CurrentValue;
        $this->cantidad_maxima->PlaceHolder = RemoveHtml($this->cantidad_maxima->caption());
        if (strval($this->cantidad_maxima->EditValue) != "" && is_numeric($this->cantidad_maxima->EditValue)) {
            $this->cantidad_maxima->EditValue = FormatNumber($this->cantidad_maxima->EditValue, null);
        }

        // cantidad_en_mano
        $this->cantidad_en_mano->setupEditAttributes();
        $this->cantidad_en_mano->EditValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());
        if (strval($this->cantidad_en_mano->EditValue) != "" && is_numeric($this->cantidad_en_mano->EditValue)) {
            $this->cantidad_en_mano->EditValue = FormatNumber($this->cantidad_en_mano->EditValue, null);
        }

        // cantidad_en_almacenes
        $this->cantidad_en_almacenes->setupEditAttributes();
        $this->cantidad_en_almacenes->EditValue = $this->cantidad_en_almacenes->CurrentValue;
        $this->cantidad_en_almacenes->PlaceHolder = RemoveHtml($this->cantidad_en_almacenes->caption());
        if (strval($this->cantidad_en_almacenes->EditValue) != "" && is_numeric($this->cantidad_en_almacenes->EditValue)) {
            $this->cantidad_en_almacenes->EditValue = FormatNumber($this->cantidad_en_almacenes->EditValue, null);
        }

        // cantidad_en_pedido
        $this->cantidad_en_pedido->setupEditAttributes();
        $this->cantidad_en_pedido->EditValue = $this->cantidad_en_pedido->CurrentValue;
        $this->cantidad_en_pedido->PlaceHolder = RemoveHtml($this->cantidad_en_pedido->caption());
        if (strval($this->cantidad_en_pedido->EditValue) != "" && is_numeric($this->cantidad_en_pedido->EditValue)) {
            $this->cantidad_en_pedido->EditValue = FormatNumber($this->cantidad_en_pedido->EditValue, null);
        }

        // cantidad_en_transito
        $this->cantidad_en_transito->setupEditAttributes();
        $this->cantidad_en_transito->EditValue = $this->cantidad_en_transito->CurrentValue;
        $this->cantidad_en_transito->PlaceHolder = RemoveHtml($this->cantidad_en_transito->caption());
        if (strval($this->cantidad_en_transito->EditValue) != "" && is_numeric($this->cantidad_en_transito->EditValue)) {
            $this->cantidad_en_transito->EditValue = FormatNumber($this->cantidad_en_transito->EditValue, null);
        }

        // ultimo_costo
        $this->ultimo_costo->setupEditAttributes();
        $this->ultimo_costo->EditValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->PlaceHolder = RemoveHtml($this->ultimo_costo->caption());
        if (strval($this->ultimo_costo->EditValue) != "" && is_numeric($this->ultimo_costo->EditValue)) {
            $this->ultimo_costo->EditValue = FormatNumber($this->ultimo_costo->EditValue, null);
        }

        // descuento
        $this->descuento->setupEditAttributes();
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
        if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
            $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, null);
        }

        // precio
        $this->precio->setupEditAttributes();
        $this->precio->EditValue = $this->precio->CurrentValue;
        $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
        if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
            $this->precio->EditValue = FormatNumber($this->precio->EditValue, null);
        }

        // alicuota
        $this->alicuota->setupEditAttributes();
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());

        // articulo_inventario
        $this->articulo_inventario->setupEditAttributes();
        $this->articulo_inventario->EditValue = $this->articulo_inventario->options(true);
        $this->articulo_inventario->PlaceHolder = RemoveHtml($this->articulo_inventario->caption());

        // activo
        $this->activo->setupEditAttributes();
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // lote
        $this->lote->setupEditAttributes();
        if (!$this->lote->Raw) {
            $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
        }
        $this->lote->EditValue = $this->lote->CurrentValue;
        $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

        // fecha_vencimiento
        $this->fecha_vencimiento->setupEditAttributes();
        $this->fecha_vencimiento->EditValue = FormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern());
        $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

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
                    $doc->exportCaption($this->foto);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->codigo_de_barra);
                    $doc->exportCaption($this->categoria);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->unidad_medida_defecto);
                    $doc->exportCaption($this->cantidad_por_unidad_medida);
                    $doc->exportCaption($this->cantidad_minima);
                    $doc->exportCaption($this->cantidad_maxima);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->cantidad_en_almacenes);
                    $doc->exportCaption($this->cantidad_en_pedido);
                    $doc->exportCaption($this->cantidad_en_transito);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->articulo_inventario);
                    $doc->exportCaption($this->activo);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->codigo_de_barra);
                    $doc->exportCaption($this->categoria);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->cantidad_en_almacenes);
                    $doc->exportCaption($this->cantidad_en_pedido);
                    $doc->exportCaption($this->cantidad_en_transito);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->lote);
                    $doc->exportCaption($this->fecha_vencimiento);
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
                        $doc->exportField($this->foto);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->codigo_de_barra);
                        $doc->exportField($this->categoria);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->unidad_medida_defecto);
                        $doc->exportField($this->cantidad_por_unidad_medida);
                        $doc->exportField($this->cantidad_minima);
                        $doc->exportField($this->cantidad_maxima);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->cantidad_en_almacenes);
                        $doc->exportField($this->cantidad_en_pedido);
                        $doc->exportField($this->cantidad_en_transito);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->articulo_inventario);
                        $doc->exportField($this->activo);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->codigo_de_barra);
                        $doc->exportField($this->categoria);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->cantidad_en_almacenes);
                        $doc->exportField($this->cantidad_en_pedido);
                        $doc->exportField($this->cantidad_en_transito);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->lote);
                        $doc->exportField($this->fecha_vencimiento);
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
        if ($fldparm == 'foto') {
            $fldName = "foto";
            $fileNameFld = "foto";
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
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'articulo');
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
                WriteAuditLog($usr, "A", 'articulo', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'articulo', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'articulo', $fldname, $key, $oldvalue);
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
       	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
    	$grupo = trim(ExecuteScalar($sql));
    	if($grupo == "CLIENTE") {
    		AddFilter($filter, "activo = 'S' AND cantidad_en_mano > 0"); 
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
    	if(trim($rsnew["codigo"]) != "") {
    		$sql = "SELECT COUNT(codigo) AS cantidad FROM articulo WHERE codigo = '" . $rsnew["codigo"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo \"" . $rsnew["codigo"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_de_barra"]) != "") {
    		$sql = "SELECT COUNT(codigo_de_barra) AS cantidad FROM articulo WHERE codigo_de_barra = '" . $rsnew["codigo_de_barra"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo de barra \"" . $rsnew["codigo_de_barra"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew) {
    	//echo "Row Inserted"
    	$sql = "INSERT INTO articulo_unidad_medida
    				(id, articulo, unidad_medida)
    			SELECT
    				NULL, id, unidad_medida_defecto 
    			FROM articulo WHERE id = '" . $rsnew["id"] . "';";
    	Execute($sql);
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if(trim($rsnew["codigo"]) != "" and $rsold["codigo"] <> $rsnew["codigo"]) {
    		$sql = "SELECT COUNT(codigo) AS cantidad FROM articulo WHERE codigo = '" . $rsnew["codigo"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo \"" . $rsnew["codigo"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_de_barra"]) != "" and $rsold["codigo_de_barra"] <> $rsnew["codigo_de_barra"]) {
    		$sql = "SELECT COUNT(codigo_de_barra) AS cantidad FROM articulo WHERE codigo_de_barra = '" . $rsnew["codigo_de_barra"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo de barra \"" . $rsnew["codigo_de_barra"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	return TRUE;
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
    public function rowDeleting(&$rs) {
    	// Enter your code here
    	// To cancel, set return value to False
    	$sql = "SELECT COUNT(id) AS cantidad FROM entradas_salidas WHERE articulo = '" . $rs["id"] . "';"; 
    	$cantidad = intval(ExecuteScalar($sql));
    	if($cantidad > 0) {
    		$this->CancelMessage = "Este art&iacute;culo no se puede eliminar porque tiene movimientos asociados.";
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
    public function rowRendered() {
    	// To view properties of field class, use:
    	//var_dump($this-><FieldName>);
        if($this->id->CurrentValue != "") { 
            /*
            $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
            $tipo_documento = 'TDCNET';
            if($row = ExecuteRow($sql)) $tipo_documento = $row["tipo_documento"];
            $sql = "SELECT 
                a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
            FROM 
                entradas_salidas AS a 
                JOIN entradas AS b ON
                    b.tipo_documento = a.tipo_documento
                    AND b.id = a.id_documento 
                JOIN almacen AS c ON
                    c.codigo = a.almacen AND c.movimiento = 'S'
            WHERE 
                    (
                        (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                            (a.tipo_documento IN ('TDCAEN', '$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') OR 
                            (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                        ) AND a.articulo = " . $this->id->CurrentValue . " AND a.newdata = 'S' 
            GROUP BY a.articulo;"; 
            $onhand = 0;
            if($row = ExecuteRow($sql)) $onhand = intval($row["cantidad"]);
            if($onhand != intval($this->cantidad_en_mano->CurrentValue)) {
                $sql = "UPDATE articulo SET cantidad_en_mano = $onhand WHERE id = " . $this->id->CurrentValue;
                Execute($sql);
                $this->cantidad_en_mano->CellAttrs["style"] = "color:#000000; background-color:yellow;";
            }
            */
            $onhand = intval($this->cantidad_en_mano->CurrentValue);
            if ($onhand <= 0) { // List page only
                $this->nombre_comercial->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
                $this->principio_activo->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
                $this->presentacion->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
                $this->fabricante->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
            }
            if ($this->activo->CurrentValue == "N") { // List page only
                $this->nombre_comercial->CellAttrs["style"] = "background-color: #ffcccc";
                $this->principio_activo->CellAttrs["style"] = "background-color: #ffcccc";
                $this->presentacion->CellAttrs["style"] = "background-color: #ffcccc";
                $this->fabricante->CellAttrs["style"] = "background-color: #ffcccc";
            }
        }
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
