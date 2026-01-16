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
 * Table class for pedidio_detalle_online
 */
class PedidioDetalleOnline extends DbTable
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
    public $id_documento;
    public $fabricante;
    public $articulo;
    public $lote;
    public $fecha_vencimiento;
    public $almacen;
    public $cantidad_articulo;
    public $articulo_unidad_medida;
    public $cantidad_unidad_medida;
    public $cantidad_movimiento;
    public $costo_unidad;
    public $costo;
    public $precio_unidad;
    public $precio;
    public $id_compra;
    public $alicuota;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "pedidio_detalle_online";
        $this->TableName = 'pedidio_detalle_online';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "pedidio_detalle_online";
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
            'TEXT' // Edit Tag
        );
        $this->fabricante->InputTextType = "text";
        $this->fabricante->Raw = true;
        $this->fabricante->Lookup = new Lookup($this->fabricante, 'fabricante', false, 'Id', ["nombre","","",""], '', '', [], ["x_articulo"], [], [], [], [], false, '', '', "`nombre`");
        $this->fabricante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->fabricante->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fabricante'] = &$this->fabricante;

        // articulo
        $this->articulo = new DbField(
            $this, // Table
            'x_articulo', // Variable name
            'articulo', // Name
            '`articulo`', // Expression
            '`articulo`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`articulo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->articulo->InputTextType = "text";
        $this->articulo->Raw = true;
        $this->articulo->setSelectMultiple(false); // Select one
        $this->articulo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo->Lookup = new Lookup($this->articulo, 'articulo', false, 'id', ["principio_activo","presentacion","nombre_comercial",""], '', '', ["x_fabricante"], ["x_articulo_unidad_medida"], ["fabricante"], ["x_fabricante"], [], [], false, '', '', "CONCAT(COALESCE(`principio_activo`, ''),'" . ValueSeparator(1, $this->articulo) . "',COALESCE(`presentacion`,''),'" . ValueSeparator(2, $this->articulo) . "',COALESCE(`nombre_comercial`,''))");
        $this->articulo->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->articulo->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['articulo'] = &$this->articulo;

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
            'TEXT' // Edit Tag
        );
        $this->almacen->InputTextType = "text";
        $this->almacen->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['almacen'] = &$this->almacen;

        // cantidad_articulo
        $this->cantidad_articulo = new DbField(
            $this, // Table
            'x_cantidad_articulo', // Variable name
            'cantidad_articulo', // Name
            '`cantidad_articulo`', // Expression
            '`cantidad_articulo`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_articulo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_articulo->InputTextType = "text";
        $this->cantidad_articulo->Raw = true;
        $this->cantidad_articulo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_articulo->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cantidad_articulo'] = &$this->cantidad_articulo;

        // articulo_unidad_medida
        $this->articulo_unidad_medida = new DbField(
            $this, // Table
            'x_articulo_unidad_medida', // Variable name
            'articulo_unidad_medida', // Name
            '`articulo_unidad_medida`', // Expression
            '`articulo_unidad_medida`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`articulo_unidad_medida`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->articulo_unidad_medida->InputTextType = "text";
        $this->articulo_unidad_medida->setSelectMultiple(false); // Select one
        $this->articulo_unidad_medida->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo_unidad_medida->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo_unidad_medida->Lookup = new Lookup($this->articulo_unidad_medida, 'view_unidad_medida', false, 'unidad_medida', ["descripcion_unidad_medida","cantidad_por_unidad_medida","",""], '', '', ["x_articulo"], [], ["articulo"], ["x_articulo"], [], [], false, '', '', "CONCAT(COALESCE(`descripcion_unidad_medida`, ''),'" . ValueSeparator(1, $this->articulo_unidad_medida) . "',COALESCE(`cantidad_por_unidad_medida`,''))");
        $this->articulo_unidad_medida->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['articulo_unidad_medida'] = &$this->articulo_unidad_medida;

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida = new DbField(
            $this, // Table
            'x_cantidad_unidad_medida', // Variable name
            'cantidad_unidad_medida', // Name
            '`cantidad_unidad_medida`', // Expression
            '`cantidad_unidad_medida`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_unidad_medida`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_unidad_medida->InputTextType = "text";
        $this->cantidad_unidad_medida->Raw = true;
        $this->cantidad_unidad_medida->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_unidad_medida->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cantidad_unidad_medida'] = &$this->cantidad_unidad_medida;

        // cantidad_movimiento
        $this->cantidad_movimiento = new DbField(
            $this, // Table
            'x_cantidad_movimiento', // Variable name
            'cantidad_movimiento', // Name
            '`cantidad_movimiento`', // Expression
            '`cantidad_movimiento`', // Basic search expression
            131, // Type
            12, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_movimiento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_movimiento->InputTextType = "text";
        $this->cantidad_movimiento->Raw = true;
        $this->cantidad_movimiento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_movimiento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cantidad_movimiento'] = &$this->cantidad_movimiento;

        // costo_unidad
        $this->costo_unidad = new DbField(
            $this, // Table
            'x_costo_unidad', // Variable name
            'costo_unidad', // Name
            '`costo_unidad`', // Expression
            '`costo_unidad`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`costo_unidad`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->costo_unidad->InputTextType = "text";
        $this->costo_unidad->Raw = true;
        $this->costo_unidad->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->costo_unidad->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['costo_unidad'] = &$this->costo_unidad;

        // costo
        $this->costo = new DbField(
            $this, // Table
            'x_costo', // Variable name
            'costo', // Name
            '`costo`', // Expression
            '`costo`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`costo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->costo->InputTextType = "text";
        $this->costo->Raw = true;
        $this->costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->costo->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['costo'] = &$this->costo;

        // precio_unidad
        $this->precio_unidad = new DbField(
            $this, // Table
            'x_precio_unidad', // Variable name
            'precio_unidad', // Name
            '`precio_unidad`', // Expression
            '`precio_unidad`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`precio_unidad`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->precio_unidad->InputTextType = "text";
        $this->precio_unidad->Raw = true;
        $this->precio_unidad->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio_unidad->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['precio_unidad'] = &$this->precio_unidad;

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
        $this->precio->InputTextType = "text";
        $this->precio->Raw = true;
        $this->precio->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['precio'] = &$this->precio;

        // id_compra
        $this->id_compra = new DbField(
            $this, // Table
            'x_id_compra', // Variable name
            'id_compra', // Name
            '`id_compra`', // Expression
            '`id_compra`', // Basic search expression
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id_compra`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id_compra->InputTextType = "text";
        $this->id_compra->Raw = true;
        $this->id_compra->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_compra->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_compra'] = &$this->id_compra;

        // alicuota
        $this->alicuota = new DbField(
            $this, // Table
            'x_alicuota', // Variable name
            'alicuota', // Name
            '`alicuota`', // Expression
            '`alicuota`', // Basic search expression
            131, // Type
            7, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`alicuota`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->alicuota->InputTextType = "text";
        $this->alicuota->Raw = true;
        $this->alicuota->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['alicuota'] = &$this->alicuota;

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
        if ($this->getCurrentMasterTable() == "pedido_online") {
            $masterTable = Container("pedido_online");
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
        if ($this->getCurrentMasterTable() == "pedido_online") {
            $masterTable = Container("pedido_online");
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
            case "pedido_online":
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
            case "pedido_online":
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "pedidio_detalle_online";
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
        $this->id_documento->DbValue = $row['id_documento'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->articulo->DbValue = $row['articulo'];
        $this->lote->DbValue = $row['lote'];
        $this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
        $this->almacen->DbValue = $row['almacen'];
        $this->cantidad_articulo->DbValue = $row['cantidad_articulo'];
        $this->articulo_unidad_medida->DbValue = $row['articulo_unidad_medida'];
        $this->cantidad_unidad_medida->DbValue = $row['cantidad_unidad_medida'];
        $this->cantidad_movimiento->DbValue = $row['cantidad_movimiento'];
        $this->costo_unidad->DbValue = $row['costo_unidad'];
        $this->costo->DbValue = $row['costo'];
        $this->precio_unidad->DbValue = $row['precio_unidad'];
        $this->precio->DbValue = $row['precio'];
        $this->id_compra->DbValue = $row['id_compra'];
        $this->alicuota->DbValue = $row['alicuota'];
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
        return $_SESSION[$name] ?? GetUrl("PedidioDetalleOnlineList");
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
            "PedidioDetalleOnlineView" => $Language->phrase("View"),
            "PedidioDetalleOnlineEdit" => $Language->phrase("Edit"),
            "PedidioDetalleOnlineAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "PedidioDetalleOnlineList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "PedidioDetalleOnlineView",
            Config("API_ADD_ACTION") => "PedidioDetalleOnlineAdd",
            Config("API_EDIT_ACTION") => "PedidioDetalleOnlineEdit",
            Config("API_DELETE_ACTION") => "PedidioDetalleOnlineDelete",
            Config("API_LIST_ACTION") => "PedidioDetalleOnlineList",
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
        return "PedidioDetalleOnlineList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PedidioDetalleOnlineView", $parm);
        } else {
            $url = $this->keyUrl("PedidioDetalleOnlineView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PedidioDetalleOnlineAdd?" . $parm;
        } else {
            $url = "PedidioDetalleOnlineAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PedidioDetalleOnlineEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("PedidioDetalleOnlineList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("PedidioDetalleOnlineAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("PedidioDetalleOnlineList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("PedidioDetalleOnlineDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "pedido_online" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->almacen->setDbValue($row['almacen']);
        $this->cantidad_articulo->setDbValue($row['cantidad_articulo']);
        $this->articulo_unidad_medida->setDbValue($row['articulo_unidad_medida']);
        $this->cantidad_unidad_medida->setDbValue($row['cantidad_unidad_medida']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->costo_unidad->setDbValue($row['costo_unidad']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_unidad->setDbValue($row['precio_unidad']);
        $this->precio->setDbValue($row['precio']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->alicuota->setDbValue($row['alicuota']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "PedidioDetalleOnlineList";
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

        // id_documento

        // fabricante

        // articulo

        // lote

        // fecha_vencimiento

        // almacen

        // cantidad_articulo

        // articulo_unidad_medida

        // cantidad_unidad_medida

        // cantidad_movimiento

        // costo_unidad

        // costo

        // precio_unidad

        // precio

        // id_compra

        // alicuota

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;

        // id_documento
        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;

        // fabricante
        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
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

        // articulo
        $curVal = strval($this->articulo->CurrentValue);
        if ($curVal != "") {
            $this->articulo->ViewValue = $this->articulo->lookupCacheOption($curVal);
            if ($this->articulo->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->articulo->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->articulo->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                    $this->articulo->ViewValue = $this->articulo->displayValue($arwrk);
                } else {
                    $this->articulo->ViewValue = $this->articulo->CurrentValue;
                }
            }
        } else {
            $this->articulo->ViewValue = null;
        }

        // lote
        $this->lote->ViewValue = $this->lote->CurrentValue;

        // fecha_vencimiento
        $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
        $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

        // almacen
        $this->almacen->ViewValue = $this->almacen->CurrentValue;

        // cantidad_articulo
        $this->cantidad_articulo->ViewValue = $this->cantidad_articulo->CurrentValue;
        $this->cantidad_articulo->ViewValue = FormatNumber($this->cantidad_articulo->ViewValue, $this->cantidad_articulo->formatPattern());

        // articulo_unidad_medida
        $curVal = strval($this->articulo_unidad_medida->CurrentValue);
        if ($curVal != "") {
            $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
            if ($this->articulo_unidad_medida->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->articulo_unidad_medida->Lookup->getTable()->Fields["unidad_medida"]->searchExpression(), "=", $curVal, $this->articulo_unidad_medida->Lookup->getTable()->Fields["unidad_medida"]->searchDataType(), "");
                $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                    $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->displayValue($arwrk);
                } else {
                    $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->CurrentValue;
                }
            }
        } else {
            $this->articulo_unidad_medida->ViewValue = null;
        }

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->ViewValue = $this->cantidad_unidad_medida->CurrentValue;
        $this->cantidad_unidad_medida->ViewValue = FormatNumber($this->cantidad_unidad_medida->ViewValue, $this->cantidad_unidad_medida->formatPattern());

        // cantidad_movimiento
        $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
        $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->formatPattern());

        // costo_unidad
        $this->costo_unidad->ViewValue = $this->costo_unidad->CurrentValue;
        $this->costo_unidad->ViewValue = FormatNumber($this->costo_unidad->ViewValue, $this->costo_unidad->formatPattern());

        // costo
        $this->costo->ViewValue = $this->costo->CurrentValue;
        $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, $this->costo->formatPattern());

        // precio_unidad
        $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
        $this->precio_unidad->ViewValue = FormatNumber($this->precio_unidad->ViewValue, $this->precio_unidad->formatPattern());

        // precio
        $this->precio->ViewValue = $this->precio->CurrentValue;
        $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->formatPattern());

        // id_compra
        $this->id_compra->ViewValue = $this->id_compra->CurrentValue;

        // alicuota
        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
        $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // id_documento
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // fabricante
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // articulo
        $this->articulo->HrefValue = "";
        $this->articulo->TooltipValue = "";

        // lote
        $this->lote->HrefValue = "";
        $this->lote->TooltipValue = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->HrefValue = "";
        $this->fecha_vencimiento->TooltipValue = "";

        // almacen
        $this->almacen->HrefValue = "";
        $this->almacen->TooltipValue = "";

        // cantidad_articulo
        $this->cantidad_articulo->HrefValue = "";
        $this->cantidad_articulo->TooltipValue = "";

        // articulo_unidad_medida
        $this->articulo_unidad_medida->HrefValue = "";
        $this->articulo_unidad_medida->TooltipValue = "";

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->HrefValue = "";
        $this->cantidad_unidad_medida->TooltipValue = "";

        // cantidad_movimiento
        $this->cantidad_movimiento->HrefValue = "";
        $this->cantidad_movimiento->TooltipValue = "";

        // costo_unidad
        $this->costo_unidad->HrefValue = "";
        $this->costo_unidad->TooltipValue = "";

        // costo
        $this->costo->HrefValue = "";
        $this->costo->TooltipValue = "";

        // precio_unidad
        $this->precio_unidad->HrefValue = "";
        $this->precio_unidad->TooltipValue = "";

        // precio
        $this->precio->HrefValue = "";
        $this->precio->TooltipValue = "";

        // id_compra
        $this->id_compra->HrefValue = "";
        $this->id_compra->TooltipValue = "";

        // alicuota
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

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

        // fabricante
        $this->fabricante->setupEditAttributes();
        $this->fabricante->EditValue = $this->fabricante->CurrentValue;
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // articulo
        $this->articulo->setupEditAttributes();
        $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

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

        // almacen
        $this->almacen->setupEditAttributes();
        if (!$this->almacen->Raw) {
            $this->almacen->CurrentValue = HtmlDecode($this->almacen->CurrentValue);
        }
        $this->almacen->EditValue = $this->almacen->CurrentValue;
        $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

        // cantidad_articulo
        $this->cantidad_articulo->setupEditAttributes();
        $this->cantidad_articulo->EditValue = $this->cantidad_articulo->CurrentValue;
        $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
        if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
            $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, null);
        }

        // articulo_unidad_medida
        $this->articulo_unidad_medida->setupEditAttributes();
        $this->articulo_unidad_medida->PlaceHolder = RemoveHtml($this->articulo_unidad_medida->caption());

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->setupEditAttributes();
        $this->cantidad_unidad_medida->EditValue = $this->cantidad_unidad_medida->CurrentValue;
        $this->cantidad_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_unidad_medida->caption());
        if (strval($this->cantidad_unidad_medida->EditValue) != "" && is_numeric($this->cantidad_unidad_medida->EditValue)) {
            $this->cantidad_unidad_medida->EditValue = FormatNumber($this->cantidad_unidad_medida->EditValue, null);
        }

        // cantidad_movimiento
        $this->cantidad_movimiento->setupEditAttributes();
        $this->cantidad_movimiento->EditValue = $this->cantidad_movimiento->CurrentValue;
        $this->cantidad_movimiento->PlaceHolder = RemoveHtml($this->cantidad_movimiento->caption());
        if (strval($this->cantidad_movimiento->EditValue) != "" && is_numeric($this->cantidad_movimiento->EditValue)) {
            $this->cantidad_movimiento->EditValue = FormatNumber($this->cantidad_movimiento->EditValue, null);
        }

        // costo_unidad
        $this->costo_unidad->setupEditAttributes();
        $this->costo_unidad->EditValue = $this->costo_unidad->CurrentValue;
        $this->costo_unidad->PlaceHolder = RemoveHtml($this->costo_unidad->caption());
        if (strval($this->costo_unidad->EditValue) != "" && is_numeric($this->costo_unidad->EditValue)) {
            $this->costo_unidad->EditValue = FormatNumber($this->costo_unidad->EditValue, null);
        }

        // costo
        $this->costo->setupEditAttributes();
        $this->costo->EditValue = $this->costo->CurrentValue;
        $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
        if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
            $this->costo->EditValue = FormatNumber($this->costo->EditValue, null);
        }

        // precio_unidad
        $this->precio_unidad->setupEditAttributes();
        $this->precio_unidad->EditValue = $this->precio_unidad->CurrentValue;
        $this->precio_unidad->PlaceHolder = RemoveHtml($this->precio_unidad->caption());
        if (strval($this->precio_unidad->EditValue) != "" && is_numeric($this->precio_unidad->EditValue)) {
            $this->precio_unidad->EditValue = FormatNumber($this->precio_unidad->EditValue, null);
        }

        // precio
        $this->precio->setupEditAttributes();
        $this->precio->EditValue = $this->precio->CurrentValue;
        $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
        if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
            $this->precio->EditValue = FormatNumber($this->precio->EditValue, null);
        }

        // id_compra
        $this->id_compra->setupEditAttributes();
        $this->id_compra->EditValue = $this->id_compra->CurrentValue;
        $this->id_compra->PlaceHolder = RemoveHtml($this->id_compra->caption());
        if (strval($this->id_compra->EditValue) != "" && is_numeric($this->id_compra->EditValue)) {
            $this->id_compra->EditValue = $this->id_compra->EditValue;
        }

        // alicuota
        $this->alicuota->setupEditAttributes();
        $this->alicuota->EditValue = $this->alicuota->CurrentValue;
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
        if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
            $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, null);
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
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->articulo);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->articulo);
                    $doc->exportCaption($this->lote);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->almacen);
                    $doc->exportCaption($this->cantidad_articulo);
                    $doc->exportCaption($this->articulo_unidad_medida);
                    $doc->exportCaption($this->cantidad_unidad_medida);
                    $doc->exportCaption($this->cantidad_movimiento);
                    $doc->exportCaption($this->costo_unidad);
                    $doc->exportCaption($this->costo);
                    $doc->exportCaption($this->precio_unidad);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->id_compra);
                    $doc->exportCaption($this->alicuota);
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
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->articulo);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->articulo);
                        $doc->exportField($this->lote);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->almacen);
                        $doc->exportField($this->cantidad_articulo);
                        $doc->exportField($this->articulo_unidad_medida);
                        $doc->exportField($this->cantidad_unidad_medida);
                        $doc->exportField($this->cantidad_movimiento);
                        $doc->exportField($this->costo_unidad);
                        $doc->exportField($this->costo);
                        $doc->exportField($this->precio_unidad);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->id_compra);
                        $doc->exportField($this->alicuota);
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

    // Write audit trail start/end for grid update
    public function writeAuditTrailDummy($typ)
    {
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'pedidio_detalle_online');
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
                WriteAuditLog($usr, "A", 'pedidio_detalle_online', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'pedidio_detalle_online', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'pedidio_detalle_online', $fldname, $key, $oldvalue);
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
