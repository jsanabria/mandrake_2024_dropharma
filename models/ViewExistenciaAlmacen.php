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
 * Table class for view_existencia_almacen
 */
class ViewExistenciaAlmacen extends DbTable
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
    public $codalm;
    public $codfab;
    public $codart;
    public $nombre_comercial;
    public $principio_activo;
    public $presentacion;
    public $lote;
    public $fecha_vencimiento;
    public $cantidad;
    public $fabricante;
    public $id_compra;
    public $nom_almacen;
    public $codigo;
    public $codigo_de_barra;
    public $articulo;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "view_existencia_almacen";
        $this->TableName = 'view_existencia_almacen';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_existencia_almacen";
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

        // codalm
        $this->codalm = new DbField(
            $this, // Table
            'x_codalm', // Variable name
            'codalm', // Name
            '`codalm`', // Expression
            '`codalm`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codalm`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->codalm->addMethod("getSelectFilter", fn() => "`movimiento` = 'S'");
        $this->codalm->InputTextType = "text";
        $this->codalm->setSelectMultiple(false); // Select one
        $this->codalm->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->codalm->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->codalm->Lookup = new Lookup($this->codalm, 'almacen', false, 'codigo', ["descripcion","","",""], '', '', [], [], [], [], [], [], false, '`descripcion` ASC', '', "`descripcion`");
        $this->codalm->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['codalm'] = &$this->codalm;

        // codfab
        $this->codfab = new DbField(
            $this, // Table
            'x_codfab', // Variable name
            'codfab', // Name
            '`codfab`', // Expression
            '`codfab`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codfab`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->codfab->InputTextType = "text";
        $this->codfab->Raw = true;
        $this->codfab->setSelectMultiple(false); // Select one
        $this->codfab->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->codfab->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->codfab->Lookup = new Lookup($this->codfab, 'fabricante', false, 'Id', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`nombre` ASC', '', "`nombre`");
        $this->codfab->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codfab->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codfab'] = &$this->codfab;

        // codart
        $this->codart = new DbField(
            $this, // Table
            'x_codart', // Variable name
            'codart', // Name
            '`codart`', // Expression
            '`codart`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codart`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->codart->InputTextType = "text";
        $this->codart->Raw = true;
        $this->codart->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codart->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codart'] = &$this->codart;

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
        $this->presentacion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['presentacion'] = &$this->presentacion;

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
            '`fecha_vencimiento`', // Basic search expression
            200, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`fecha_vencimiento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_vencimiento->InputTextType = "text";
        $this->fecha_vencimiento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_vencimiento'] = &$this->fecha_vencimiento;

        // cantidad
        $this->cantidad = new DbField(
            $this, // Table
            'x_cantidad', // Variable name
            'cantidad', // Name
            '`cantidad`', // Expression
            '`cantidad`', // Basic search expression
            131, // Type
            35, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad->addMethod("getDefault", fn() => 0.00);
        $this->cantidad->InputTextType = "text";
        $this->cantidad->Raw = true;
        $this->cantidad->Nullable = false; // NOT NULL field
        $this->cantidad->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad'] = &$this->cantidad;

        // fabricante
        $this->fabricante = new DbField(
            $this, // Table
            'x_fabricante', // Variable name
            'fabricante', // Name
            '`fabricante`', // Expression
            '`fabricante`', // Basic search expression
            200, // Type
            80, // Size
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
        $this->fabricante->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['fabricante'] = &$this->fabricante;

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
        $this->id_compra->addMethod("getDefault", fn() => 0);
        $this->id_compra->InputTextType = "text";
        $this->id_compra->Raw = true;
        $this->id_compra->Nullable = false; // NOT NULL field
        $this->id_compra->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_compra->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id_compra'] = &$this->id_compra;

        // nom_almacen
        $this->nom_almacen = new DbField(
            $this, // Table
            'x_nom_almacen', // Variable name
            'nom_almacen', // Name
            '`nom_almacen`', // Expression
            '`nom_almacen`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nom_almacen`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nom_almacen->InputTextType = "text";
        $this->nom_almacen->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nom_almacen'] = &$this->nom_almacen;

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

        // articulo
        $this->articulo = new DbField(
            $this, // Table
            'x_articulo', // Variable name
            'articulo', // Name
            '`articulo`', // Expression
            '`articulo`', // Basic search expression
            200, // Type
            100, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`articulo`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->articulo->InputTextType = "text";
        $this->articulo->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['articulo'] = &$this->articulo;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_existencia_almacen";
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
        $this->codalm->DbValue = $row['codalm'];
        $this->codfab->DbValue = $row['codfab'];
        $this->codart->DbValue = $row['codart'];
        $this->nombre_comercial->DbValue = $row['nombre_comercial'];
        $this->principio_activo->DbValue = $row['principio_activo'];
        $this->presentacion->DbValue = $row['presentacion'];
        $this->lote->DbValue = $row['lote'];
        $this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
        $this->cantidad->DbValue = $row['cantidad'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->id_compra->DbValue = $row['id_compra'];
        $this->nom_almacen->DbValue = $row['nom_almacen'];
        $this->codigo->DbValue = $row['codigo'];
        $this->codigo_de_barra->DbValue = $row['codigo_de_barra'];
        $this->articulo->DbValue = $row['articulo'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
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
        return $_SESSION[$name] ?? GetUrl("ViewExistenciaAlmacenList");
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
            "ViewExistenciaAlmacenView" => $Language->phrase("View"),
            "ViewExistenciaAlmacenEdit" => $Language->phrase("Edit"),
            "ViewExistenciaAlmacenAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewExistenciaAlmacenList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewExistenciaAlmacenView",
            Config("API_ADD_ACTION") => "ViewExistenciaAlmacenAdd",
            Config("API_EDIT_ACTION") => "ViewExistenciaAlmacenEdit",
            Config("API_DELETE_ACTION") => "ViewExistenciaAlmacenDelete",
            Config("API_LIST_ACTION") => "ViewExistenciaAlmacenList",
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
        return "ViewExistenciaAlmacenList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewExistenciaAlmacenView", $parm);
        } else {
            $url = $this->keyUrl("ViewExistenciaAlmacenView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewExistenciaAlmacenAdd?" . $parm;
        } else {
            $url = "ViewExistenciaAlmacenAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewExistenciaAlmacenEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewExistenciaAlmacenList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ViewExistenciaAlmacenAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewExistenciaAlmacenList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewExistenciaAlmacenDelete", $parm);
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
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
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
                    ? array_map(fn ($i) => Route($i + 3), range(0, -1))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, -1))) // Other API
                : []; // Non-API
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
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
        $this->codalm->setDbValue($row['codalm']);
        $this->codfab->setDbValue($row['codfab']);
        $this->codart->setDbValue($row['codart']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->cantidad->setDbValue($row['cantidad']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->nom_almacen->setDbValue($row['nom_almacen']);
        $this->codigo->setDbValue($row['codigo']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->articulo->setDbValue($row['articulo']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewExistenciaAlmacenList";
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

        // codalm

        // codfab

        // codart

        // nombre_comercial

        // principio_activo

        // presentacion

        // lote

        // fecha_vencimiento

        // cantidad

        // fabricante

        // id_compra

        // nom_almacen

        // codigo

        // codigo_de_barra

        // articulo

        // codalm
        $curVal = strval($this->codalm->CurrentValue);
        if ($curVal != "") {
            $this->codalm->ViewValue = $this->codalm->lookupCacheOption($curVal);
            if ($this->codalm->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->codalm->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->codalm->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                $lookupFilter = $this->codalm->getSelectFilter($this); // PHP
                $sqlWrk = $this->codalm->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->codalm->Lookup->renderViewRow($rswrk[0]);
                    $this->codalm->ViewValue = $this->codalm->displayValue($arwrk);
                } else {
                    $this->codalm->ViewValue = $this->codalm->CurrentValue;
                }
            }
        } else {
            $this->codalm->ViewValue = null;
        }

        // codfab
        $curVal = strval($this->codfab->CurrentValue);
        if ($curVal != "") {
            $this->codfab->ViewValue = $this->codfab->lookupCacheOption($curVal);
            if ($this->codfab->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->codfab->Lookup->getTable()->Fields["Id"]->searchExpression(), "=", $curVal, $this->codfab->Lookup->getTable()->Fields["Id"]->searchDataType(), "");
                $sqlWrk = $this->codfab->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->codfab->Lookup->renderViewRow($rswrk[0]);
                    $this->codfab->ViewValue = $this->codfab->displayValue($arwrk);
                } else {
                    $this->codfab->ViewValue = FormatNumber($this->codfab->CurrentValue, $this->codfab->formatPattern());
                }
            }
        } else {
            $this->codfab->ViewValue = null;
        }

        // codart
        $this->codart->ViewValue = $this->codart->CurrentValue;
        $this->codart->ViewValue = FormatNumber($this->codart->ViewValue, $this->codart->formatPattern());

        // nombre_comercial
        $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;

        // principio_activo
        $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;

        // presentacion
        $this->presentacion->ViewValue = $this->presentacion->CurrentValue;

        // lote
        $this->lote->ViewValue = $this->lote->CurrentValue;

        // fecha_vencimiento
        $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;

        // cantidad
        $this->cantidad->ViewValue = $this->cantidad->CurrentValue;
        $this->cantidad->ViewValue = FormatNumber($this->cantidad->ViewValue, $this->cantidad->formatPattern());

        // fabricante
        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;

        // id_compra
        $this->id_compra->ViewValue = $this->id_compra->CurrentValue;
        $this->id_compra->ViewValue = FormatNumber($this->id_compra->ViewValue, $this->id_compra->formatPattern());

        // nom_almacen
        $this->nom_almacen->ViewValue = $this->nom_almacen->CurrentValue;

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;

        // codigo_de_barra
        $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;

        // articulo
        $this->articulo->ViewValue = $this->articulo->CurrentValue;

        // codalm
        $this->codalm->HrefValue = "";
        $this->codalm->TooltipValue = "";

        // codfab
        $this->codfab->HrefValue = "";
        $this->codfab->TooltipValue = "";

        // codart
        $this->codart->HrefValue = "";
        $this->codart->TooltipValue = "";

        // nombre_comercial
        $this->nombre_comercial->HrefValue = "";
        $this->nombre_comercial->TooltipValue = "";

        // principio_activo
        $this->principio_activo->HrefValue = "";
        $this->principio_activo->TooltipValue = "";

        // presentacion
        $this->presentacion->HrefValue = "";
        $this->presentacion->TooltipValue = "";

        // lote
        $this->lote->HrefValue = "";
        $this->lote->TooltipValue = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->HrefValue = "";
        $this->fecha_vencimiento->TooltipValue = "";

        // cantidad
        $this->cantidad->HrefValue = "";
        $this->cantidad->TooltipValue = "";

        // fabricante
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // id_compra
        $this->id_compra->HrefValue = "";
        $this->id_compra->TooltipValue = "";

        // nom_almacen
        $this->nom_almacen->HrefValue = "";
        $this->nom_almacen->TooltipValue = "";

        // codigo
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // codigo_de_barra
        $this->codigo_de_barra->HrefValue = "";
        $this->codigo_de_barra->TooltipValue = "";

        // articulo
        $this->articulo->HrefValue = "";
        $this->articulo->TooltipValue = "";

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

        // codalm
        $this->codalm->setupEditAttributes();
        $this->codalm->PlaceHolder = RemoveHtml($this->codalm->caption());

        // codfab
        $this->codfab->setupEditAttributes();
        $this->codfab->PlaceHolder = RemoveHtml($this->codfab->caption());

        // codart
        $this->codart->setupEditAttributes();
        $this->codart->EditValue = $this->codart->CurrentValue;
        $this->codart->PlaceHolder = RemoveHtml($this->codart->caption());
        if (strval($this->codart->EditValue) != "" && is_numeric($this->codart->EditValue)) {
            $this->codart->EditValue = FormatNumber($this->codart->EditValue, null);
        }

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

        // lote
        $this->lote->setupEditAttributes();
        if (!$this->lote->Raw) {
            $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
        }
        $this->lote->EditValue = $this->lote->CurrentValue;
        $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

        // fecha_vencimiento
        $this->fecha_vencimiento->setupEditAttributes();
        if (!$this->fecha_vencimiento->Raw) {
            $this->fecha_vencimiento->CurrentValue = HtmlDecode($this->fecha_vencimiento->CurrentValue);
        }
        $this->fecha_vencimiento->EditValue = $this->fecha_vencimiento->CurrentValue;
        $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

        // cantidad
        $this->cantidad->setupEditAttributes();
        $this->cantidad->EditValue = $this->cantidad->CurrentValue;
        $this->cantidad->PlaceHolder = RemoveHtml($this->cantidad->caption());
        if (strval($this->cantidad->EditValue) != "" && is_numeric($this->cantidad->EditValue)) {
            $this->cantidad->EditValue = FormatNumber($this->cantidad->EditValue, null);
        }

        // fabricante
        $this->fabricante->setupEditAttributes();
        if (!$this->fabricante->Raw) {
            $this->fabricante->CurrentValue = HtmlDecode($this->fabricante->CurrentValue);
        }
        $this->fabricante->EditValue = $this->fabricante->CurrentValue;
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // id_compra
        $this->id_compra->setupEditAttributes();
        $this->id_compra->EditValue = $this->id_compra->CurrentValue;
        $this->id_compra->PlaceHolder = RemoveHtml($this->id_compra->caption());
        if (strval($this->id_compra->EditValue) != "" && is_numeric($this->id_compra->EditValue)) {
            $this->id_compra->EditValue = FormatNumber($this->id_compra->EditValue, null);
        }

        // nom_almacen
        $this->nom_almacen->setupEditAttributes();
        if (!$this->nom_almacen->Raw) {
            $this->nom_almacen->CurrentValue = HtmlDecode($this->nom_almacen->CurrentValue);
        }
        $this->nom_almacen->EditValue = $this->nom_almacen->CurrentValue;
        $this->nom_almacen->PlaceHolder = RemoveHtml($this->nom_almacen->caption());

        // codigo
        $this->codigo->setupEditAttributes();
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

        // codigo_de_barra
        $this->codigo_de_barra->setupEditAttributes();
        if (!$this->codigo_de_barra->Raw) {
            $this->codigo_de_barra->CurrentValue = HtmlDecode($this->codigo_de_barra->CurrentValue);
        }
        $this->codigo_de_barra->EditValue = $this->codigo_de_barra->CurrentValue;
        $this->codigo_de_barra->PlaceHolder = RemoveHtml($this->codigo_de_barra->caption());

        // articulo
        $this->articulo->setupEditAttributes();
        if (!$this->articulo->Raw) {
            $this->articulo->CurrentValue = HtmlDecode($this->articulo->CurrentValue);
        }
        $this->articulo->EditValue = $this->articulo->CurrentValue;
        $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

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
                    $doc->exportCaption($this->codalm);
                    $doc->exportCaption($this->codfab);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->lote);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->cantidad);
                } else {
                    $doc->exportCaption($this->codalm);
                    $doc->exportCaption($this->codfab);
                    $doc->exportCaption($this->codart);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->lote);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->cantidad);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->id_compra);
                    $doc->exportCaption($this->nom_almacen);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->codigo_de_barra);
                    $doc->exportCaption($this->articulo);
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
                        $doc->exportField($this->codalm);
                        $doc->exportField($this->codfab);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->lote);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->cantidad);
                    } else {
                        $doc->exportField($this->codalm);
                        $doc->exportField($this->codfab);
                        $doc->exportField($this->codart);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->lote);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->cantidad);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->id_compra);
                        $doc->exportField($this->nom_almacen);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->codigo_de_barra);
                        $doc->exportField($this->articulo);
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
