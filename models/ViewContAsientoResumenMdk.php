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
 * Table class for view_cont_asiento_resumen_mdk
 */
class ViewContAsientoResumenMdk extends DbTable
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
    public $fecha;
    public $referencia;
    public $modulo_origen;
    public $origen_id;
    public $descripcion;
    public $anulado;
    public $created_at;
    public $total_debe_bs;
    public $total_haber_bs;
    public $diferencia_bs;
    public $estado_cuadre;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "view_cont_asiento_resumen_mdk";
        $this->TableName = 'view_cont_asiento_resumen_mdk';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_cont_asiento_resumen_mdk";
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
        $this->id->addMethod("getDefault", fn() => 0);
        $this->id->InputTextType = "text";
        $this->id->Raw = true;
        $this->id->Nullable = false; // NOT NULL field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id'] = &$this->id;

        // fecha
        $this->fecha = new DbField(
            $this, // Table
            'x_fecha', // Variable name
            'fecha', // Name
            '`fecha`', // Expression
            CastDateFieldForLike("`fecha`", 0, "DB"), // Basic search expression
            133, // Type
            10, // Size
            0, // Date/Time format
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
        $this->fecha->Nullable = false; // NOT NULL field
        $this->fecha->Required = true; // Required field
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['fecha'] = &$this->fecha;

        // referencia
        $this->referencia = new DbField(
            $this, // Table
            'x_referencia', // Variable name
            'referencia', // Name
            '`referencia`', // Expression
            '`referencia`', // Basic search expression
            200, // Type
            60, // Size
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

        // modulo_origen
        $this->modulo_origen = new DbField(
            $this, // Table
            'x_modulo_origen', // Variable name
            'modulo_origen', // Name
            '`modulo_origen`', // Expression
            '`modulo_origen`', // Basic search expression
            200, // Type
            13, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`modulo_origen`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->modulo_origen->InputTextType = "text";
        $this->modulo_origen->Raw = true;
        $this->modulo_origen->Nullable = false; // NOT NULL field
        $this->modulo_origen->Required = true; // Required field
        $this->modulo_origen->Lookup = new Lookup($this->modulo_origen, 'view_cont_asiento_resumen_mdk', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->modulo_origen->OptionCount = 11;
        $this->modulo_origen->SearchOperators = ["=", "<>"];
        $this->Fields['modulo_origen'] = &$this->modulo_origen;

        // origen_id
        $this->origen_id = new DbField(
            $this, // Table
            'x_origen_id', // Variable name
            'origen_id', // Name
            '`origen_id`', // Expression
            '`origen_id`', // Basic search expression
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`origen_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->origen_id->InputTextType = "text";
        $this->origen_id->Raw = true;
        $this->origen_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->origen_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['origen_id'] = &$this->origen_id;

        // descripcion
        $this->descripcion = new DbField(
            $this, // Table
            'x_descripcion', // Variable name
            'descripcion', // Name
            '`descripcion`', // Expression
            '`descripcion`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`descripcion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->descripcion->InputTextType = "text";
        $this->descripcion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['descripcion'] = &$this->descripcion;

        // anulado
        $this->anulado = new DbField(
            $this, // Table
            'x_anulado', // Variable name
            'anulado', // Name
            '`anulado`', // Expression
            '`anulado`', // Basic search expression
            16, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`anulado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->anulado->addMethod("getDefault", fn() => 0);
        $this->anulado->InputTextType = "text";
        $this->anulado->Raw = true;
        $this->anulado->Nullable = false; // NOT NULL field
        $this->anulado->setDataType(DataType::BOOLEAN);
        $this->anulado->Lookup = new Lookup($this->anulado, 'view_cont_asiento_resumen_mdk', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->anulado->OptionCount = 2;
        $this->anulado->DefaultErrorMessage = $Language->phrase("IncorrectField");
        $this->anulado->SearchOperators = ["=", "<>"];
        $this->Fields['anulado'] = &$this->anulado;

        // created_at
        $this->created_at = new DbField(
            $this, // Table
            'x_created_at', // Variable name
            'created_at', // Name
            '`created_at`', // Expression
            CastDateFieldForLike("`created_at`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`created_at`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->created_at->InputTextType = "text";
        $this->created_at->Raw = true;
        $this->created_at->Nullable = false; // NOT NULL field
        $this->created_at->Required = true; // Required field
        $this->created_at->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->created_at->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['created_at'] = &$this->created_at;

        // total_debe_bs
        $this->total_debe_bs = new DbField(
            $this, // Table
            'x_total_debe_bs', // Variable name
            'total_debe_bs', // Name
            '`total_debe_bs`', // Expression
            '`total_debe_bs`', // Basic search expression
            131, // Type
            42, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_debe_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_debe_bs->addMethod("getDefault", fn() => 0.00);
        $this->total_debe_bs->InputTextType = "text";
        $this->total_debe_bs->Raw = true;
        $this->total_debe_bs->Nullable = false; // NOT NULL field
        $this->total_debe_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_debe_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['total_debe_bs'] = &$this->total_debe_bs;

        // total_haber_bs
        $this->total_haber_bs = new DbField(
            $this, // Table
            'x_total_haber_bs', // Variable name
            'total_haber_bs', // Name
            '`total_haber_bs`', // Expression
            '`total_haber_bs`', // Basic search expression
            131, // Type
            42, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_haber_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_haber_bs->addMethod("getDefault", fn() => 0.00);
        $this->total_haber_bs->InputTextType = "text";
        $this->total_haber_bs->Raw = true;
        $this->total_haber_bs->Nullable = false; // NOT NULL field
        $this->total_haber_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_haber_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['total_haber_bs'] = &$this->total_haber_bs;

        // diferencia_bs
        $this->diferencia_bs = new DbField(
            $this, // Table
            'x_diferencia_bs', // Variable name
            'diferencia_bs', // Name
            '`diferencia_bs`', // Expression
            '`diferencia_bs`', // Basic search expression
            131, // Type
            43, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`diferencia_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->diferencia_bs->addMethod("getDefault", fn() => 0.00);
        $this->diferencia_bs->InputTextType = "text";
        $this->diferencia_bs->Raw = true;
        $this->diferencia_bs->Nullable = false; // NOT NULL field
        $this->diferencia_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->diferencia_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['diferencia_bs'] = &$this->diferencia_bs;

        // estado_cuadre
        $this->estado_cuadre = new DbField(
            $this, // Table
            'x_estado_cuadre', // Variable name
            'estado_cuadre', // Name
            '`estado_cuadre`', // Expression
            '`estado_cuadre`', // Basic search expression
            200, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`estado_cuadre`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->estado_cuadre->InputTextType = "text";
        $this->estado_cuadre->Nullable = false; // NOT NULL field
        $this->estado_cuadre->Required = true; // Required field
        $this->estado_cuadre->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['estado_cuadre'] = &$this->estado_cuadre;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_cont_asiento_resumen_mdk";
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
        $this->id->DbValue = $row['id'];
        $this->fecha->DbValue = $row['fecha'];
        $this->referencia->DbValue = $row['referencia'];
        $this->modulo_origen->DbValue = $row['modulo_origen'];
        $this->origen_id->DbValue = $row['origen_id'];
        $this->descripcion->DbValue = $row['descripcion'];
        $this->anulado->DbValue = $row['anulado'];
        $this->created_at->DbValue = $row['created_at'];
        $this->total_debe_bs->DbValue = $row['total_debe_bs'];
        $this->total_haber_bs->DbValue = $row['total_haber_bs'];
        $this->diferencia_bs->DbValue = $row['diferencia_bs'];
        $this->estado_cuadre->DbValue = $row['estado_cuadre'];
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
        return $_SESSION[$name] ?? GetUrl("ViewContAsientoResumenMdkList");
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
            "ViewContAsientoResumenMdkView" => $Language->phrase("View"),
            "ViewContAsientoResumenMdkEdit" => $Language->phrase("Edit"),
            "ViewContAsientoResumenMdkAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewContAsientoResumenMdkList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewContAsientoResumenMdkView",
            Config("API_ADD_ACTION") => "ViewContAsientoResumenMdkAdd",
            Config("API_EDIT_ACTION") => "ViewContAsientoResumenMdkEdit",
            Config("API_DELETE_ACTION") => "ViewContAsientoResumenMdkDelete",
            Config("API_LIST_ACTION") => "ViewContAsientoResumenMdkList",
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
        return "ViewContAsientoResumenMdkList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewContAsientoResumenMdkView", $parm);
        } else {
            $url = $this->keyUrl("ViewContAsientoResumenMdkView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewContAsientoResumenMdkAdd?" . $parm;
        } else {
            $url = "ViewContAsientoResumenMdkAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewContAsientoResumenMdkEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewContAsientoResumenMdkList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ViewContAsientoResumenMdkAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewContAsientoResumenMdkList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewContAsientoResumenMdkDelete", $parm);
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
        $this->id->setDbValue($row['id']);
        $this->fecha->setDbValue($row['fecha']);
        $this->referencia->setDbValue($row['referencia']);
        $this->modulo_origen->setDbValue($row['modulo_origen']);
        $this->origen_id->setDbValue($row['origen_id']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->anulado->setDbValue($row['anulado']);
        $this->created_at->setDbValue($row['created_at']);
        $this->total_debe_bs->setDbValue($row['total_debe_bs']);
        $this->total_haber_bs->setDbValue($row['total_haber_bs']);
        $this->diferencia_bs->setDbValue($row['diferencia_bs']);
        $this->estado_cuadre->setDbValue($row['estado_cuadre']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewContAsientoResumenMdkList";
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

        // fecha

        // referencia

        // modulo_origen

        // origen_id

        // descripcion

        // anulado

        // created_at

        // total_debe_bs

        // total_haber_bs

        // diferencia_bs

        // estado_cuadre

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewValue = FormatNumber($this->id->ViewValue, $this->id->formatPattern());

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;

        // modulo_origen
        if (strval($this->modulo_origen->CurrentValue) != "") {
            $this->modulo_origen->ViewValue = $this->modulo_origen->optionCaption($this->modulo_origen->CurrentValue);
        } else {
            $this->modulo_origen->ViewValue = null;
        }

        // origen_id
        $this->origen_id->ViewValue = $this->origen_id->CurrentValue;
        $this->origen_id->ViewValue = FormatNumber($this->origen_id->ViewValue, $this->origen_id->formatPattern());

        // descripcion
        $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

        // anulado
        if (ConvertToBool($this->anulado->CurrentValue)) {
            $this->anulado->ViewValue = $this->anulado->tagCaption(1) != "" ? $this->anulado->tagCaption(1) : "Yes";
        } else {
            $this->anulado->ViewValue = $this->anulado->tagCaption(2) != "" ? $this->anulado->tagCaption(2) : "No";
        }

        // created_at
        $this->created_at->ViewValue = $this->created_at->CurrentValue;
        $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

        // total_debe_bs
        $this->total_debe_bs->ViewValue = $this->total_debe_bs->CurrentValue;
        $this->total_debe_bs->ViewValue = FormatNumber($this->total_debe_bs->ViewValue, $this->total_debe_bs->formatPattern());

        // total_haber_bs
        $this->total_haber_bs->ViewValue = $this->total_haber_bs->CurrentValue;
        $this->total_haber_bs->ViewValue = FormatNumber($this->total_haber_bs->ViewValue, $this->total_haber_bs->formatPattern());

        // diferencia_bs
        $this->diferencia_bs->ViewValue = $this->diferencia_bs->CurrentValue;
        $this->diferencia_bs->ViewValue = FormatNumber($this->diferencia_bs->ViewValue, $this->diferencia_bs->formatPattern());

        // estado_cuadre
        $this->estado_cuadre->ViewValue = $this->estado_cuadre->CurrentValue;

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // referencia
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // modulo_origen
        $this->modulo_origen->HrefValue = "";
        $this->modulo_origen->TooltipValue = "";

        // origen_id
        $this->origen_id->HrefValue = "";
        $this->origen_id->TooltipValue = "";

        // descripcion
        $this->descripcion->HrefValue = "";
        $this->descripcion->TooltipValue = "";

        // anulado
        $this->anulado->HrefValue = "";
        $this->anulado->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

        // total_debe_bs
        $this->total_debe_bs->HrefValue = "";
        $this->total_debe_bs->TooltipValue = "";

        // total_haber_bs
        $this->total_haber_bs->HrefValue = "";
        $this->total_haber_bs->TooltipValue = "";

        // diferencia_bs
        $this->diferencia_bs->HrefValue = "";
        $this->diferencia_bs->TooltipValue = "";

        // estado_cuadre
        $this->estado_cuadre->HrefValue = "";
        $this->estado_cuadre->TooltipValue = "";

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
        if (strval($this->id->EditValue) != "" && is_numeric($this->id->EditValue)) {
            $this->id->EditValue = FormatNumber($this->id->EditValue, null);
        }

        // fecha
        $this->fecha->setupEditAttributes();
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // referencia
        $this->referencia->setupEditAttributes();
        if (!$this->referencia->Raw) {
            $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
        }
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

        // modulo_origen
        $this->modulo_origen->EditValue = $this->modulo_origen->options(false);
        $this->modulo_origen->PlaceHolder = RemoveHtml($this->modulo_origen->caption());

        // origen_id
        $this->origen_id->setupEditAttributes();
        $this->origen_id->EditValue = $this->origen_id->CurrentValue;
        $this->origen_id->PlaceHolder = RemoveHtml($this->origen_id->caption());
        if (strval($this->origen_id->EditValue) != "" && is_numeric($this->origen_id->EditValue)) {
            $this->origen_id->EditValue = FormatNumber($this->origen_id->EditValue, null);
        }

        // descripcion
        $this->descripcion->setupEditAttributes();
        if (!$this->descripcion->Raw) {
            $this->descripcion->CurrentValue = HtmlDecode($this->descripcion->CurrentValue);
        }
        $this->descripcion->EditValue = $this->descripcion->CurrentValue;
        $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

        // anulado
        $this->anulado->EditValue = $this->anulado->options(false);
        $this->anulado->PlaceHolder = RemoveHtml($this->anulado->caption());

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // total_debe_bs
        $this->total_debe_bs->setupEditAttributes();
        $this->total_debe_bs->EditValue = $this->total_debe_bs->CurrentValue;
        $this->total_debe_bs->PlaceHolder = RemoveHtml($this->total_debe_bs->caption());
        if (strval($this->total_debe_bs->EditValue) != "" && is_numeric($this->total_debe_bs->EditValue)) {
            $this->total_debe_bs->EditValue = FormatNumber($this->total_debe_bs->EditValue, null);
        }

        // total_haber_bs
        $this->total_haber_bs->setupEditAttributes();
        $this->total_haber_bs->EditValue = $this->total_haber_bs->CurrentValue;
        $this->total_haber_bs->PlaceHolder = RemoveHtml($this->total_haber_bs->caption());
        if (strval($this->total_haber_bs->EditValue) != "" && is_numeric($this->total_haber_bs->EditValue)) {
            $this->total_haber_bs->EditValue = FormatNumber($this->total_haber_bs->EditValue, null);
        }

        // diferencia_bs
        $this->diferencia_bs->setupEditAttributes();
        $this->diferencia_bs->EditValue = $this->diferencia_bs->CurrentValue;
        $this->diferencia_bs->PlaceHolder = RemoveHtml($this->diferencia_bs->caption());
        if (strval($this->diferencia_bs->EditValue) != "" && is_numeric($this->diferencia_bs->EditValue)) {
            $this->diferencia_bs->EditValue = FormatNumber($this->diferencia_bs->EditValue, null);
        }

        // estado_cuadre
        $this->estado_cuadre->setupEditAttributes();
        if (!$this->estado_cuadre->Raw) {
            $this->estado_cuadre->CurrentValue = HtmlDecode($this->estado_cuadre->CurrentValue);
        }
        $this->estado_cuadre->EditValue = $this->estado_cuadre->CurrentValue;
        $this->estado_cuadre->PlaceHolder = RemoveHtml($this->estado_cuadre->caption());

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
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->modulo_origen);
                    $doc->exportCaption($this->origen_id);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->anulado);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->total_debe_bs);
                    $doc->exportCaption($this->total_haber_bs);
                    $doc->exportCaption($this->diferencia_bs);
                    $doc->exportCaption($this->estado_cuadre);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->modulo_origen);
                    $doc->exportCaption($this->origen_id);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->anulado);
                    $doc->exportCaption($this->created_at);
                    $doc->exportCaption($this->total_debe_bs);
                    $doc->exportCaption($this->total_haber_bs);
                    $doc->exportCaption($this->diferencia_bs);
                    $doc->exportCaption($this->estado_cuadre);
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
                        $doc->exportField($this->id);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->modulo_origen);
                        $doc->exportField($this->origen_id);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->anulado);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->total_debe_bs);
                        $doc->exportField($this->total_haber_bs);
                        $doc->exportField($this->diferencia_bs);
                        $doc->exportField($this->estado_cuadre);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->modulo_origen);
                        $doc->exportField($this->origen_id);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->anulado);
                        $doc->exportField($this->created_at);
                        $doc->exportField($this->total_debe_bs);
                        $doc->exportField($this->total_haber_bs);
                        $doc->exportField($this->diferencia_bs);
                        $doc->exportField($this->estado_cuadre);
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
