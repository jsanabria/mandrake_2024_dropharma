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
 * Table class for view_cuentas_por_cobrar_resumen
 */
class ViewCuentasPorCobrarResumen extends DbTable
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
    public $cliente;
    public $cliente_rif;
    public $cliente_nombre;
    public $cantidad_documentos;
    public $documentos_pendientes;
    public $documentos_parciales;
    public $monto_documentos_bs;
    public $monto_documentos_usd;
    public $total_cobrado_bs;
    public $total_cobrado_usd;
    public $saldo_bs;
    public $saldo_usd;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "view_cuentas_por_cobrar_resumen";
        $this->TableName = 'view_cuentas_por_cobrar_resumen';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_cuentas_por_cobrar_resumen";
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
            'TEXT' // Edit Tag
        );
        $this->cliente->InputTextType = "text";
        $this->cliente->Raw = true;
        $this->cliente->IsForeignKey = true; // Foreign key field
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['cliente'] = &$this->cliente;

        // cliente_rif
        $this->cliente_rif = new DbField(
            $this, // Table
            'x_cliente_rif', // Variable name
            'cliente_rif', // Name
            '`cliente_rif`', // Expression
            '`cliente_rif`', // Basic search expression
            200, // Type
            30, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cliente_rif`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cliente_rif->InputTextType = "text";
        $this->cliente_rif->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['cliente_rif'] = &$this->cliente_rif;

        // cliente_nombre
        $this->cliente_nombre = new DbField(
            $this, // Table
            'x_cliente_nombre', // Variable name
            'cliente_nombre', // Name
            '`cliente_nombre`', // Expression
            '`cliente_nombre`', // Basic search expression
            200, // Type
            80, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cliente_nombre`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cliente_nombre->InputTextType = "text";
        $this->cliente_nombre->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['cliente_nombre'] = &$this->cliente_nombre;

        // cantidad_documentos
        $this->cantidad_documentos = new DbField(
            $this, // Table
            'x_cantidad_documentos', // Variable name
            'cantidad_documentos', // Name
            '`cantidad_documentos`', // Expression
            '`cantidad_documentos`', // Basic search expression
            20, // Type
            21, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_documentos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_documentos->addMethod("getDefault", fn() => 0);
        $this->cantidad_documentos->InputTextType = "text";
        $this->cantidad_documentos->Raw = true;
        $this->cantidad_documentos->Nullable = false; // NOT NULL field
        $this->cantidad_documentos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cantidad_documentos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_documentos'] = &$this->cantidad_documentos;

        // documentos_pendientes
        $this->documentos_pendientes = new DbField(
            $this, // Table
            'x_documentos_pendientes', // Variable name
            'documentos_pendientes', // Name
            '`documentos_pendientes`', // Expression
            '`documentos_pendientes`', // Basic search expression
            131, // Type
            24, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`documentos_pendientes`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->documentos_pendientes->InputTextType = "text";
        $this->documentos_pendientes->Raw = true;
        $this->documentos_pendientes->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->documentos_pendientes->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['documentos_pendientes'] = &$this->documentos_pendientes;

        // documentos_parciales
        $this->documentos_parciales = new DbField(
            $this, // Table
            'x_documentos_parciales', // Variable name
            'documentos_parciales', // Name
            '`documentos_parciales`', // Expression
            '`documentos_parciales`', // Basic search expression
            131, // Type
            24, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`documentos_parciales`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->documentos_parciales->InputTextType = "text";
        $this->documentos_parciales->Raw = true;
        $this->documentos_parciales->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->documentos_parciales->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['documentos_parciales'] = &$this->documentos_parciales;

        // monto_documentos_bs
        $this->monto_documentos_bs = new DbField(
            $this, // Table
            'x_monto_documentos_bs', // Variable name
            'monto_documentos_bs', // Name
            '`monto_documentos_bs`', // Expression
            '`monto_documentos_bs`', // Basic search expression
            131, // Type
            53, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_documentos_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_documentos_bs->InputTextType = "text";
        $this->monto_documentos_bs->Raw = true;
        $this->monto_documentos_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_documentos_bs->DefaultNumberFormat = $this->monto_documentos_bs->FormatPattern;
        $this->monto_documentos_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_documentos_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_documentos_bs'] = &$this->monto_documentos_bs;

        // monto_documentos_usd
        $this->monto_documentos_usd = new DbField(
            $this, // Table
            'x_monto_documentos_usd', // Variable name
            'monto_documentos_usd', // Name
            '`monto_documentos_usd`', // Expression
            '`monto_documentos_usd`', // Basic search expression
            131, // Type
            43, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_documentos_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_documentos_usd->InputTextType = "text";
        $this->monto_documentos_usd->Raw = true;
        $this->monto_documentos_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_documentos_usd->DefaultNumberFormat = $this->monto_documentos_usd->FormatPattern;
        $this->monto_documentos_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_documentos_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_documentos_usd'] = &$this->monto_documentos_usd;

        // total_cobrado_bs
        $this->total_cobrado_bs = new DbField(
            $this, // Table
            'x_total_cobrado_bs', // Variable name
            'total_cobrado_bs', // Name
            '`total_cobrado_bs`', // Expression
            '`total_cobrado_bs`', // Basic search expression
            131, // Type
            67, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_cobrado_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_cobrado_bs->InputTextType = "text";
        $this->total_cobrado_bs->Raw = true;
        $this->total_cobrado_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->total_cobrado_bs->DefaultNumberFormat = $this->total_cobrado_bs->FormatPattern;
        $this->total_cobrado_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_cobrado_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['total_cobrado_bs'] = &$this->total_cobrado_bs;

        // total_cobrado_usd
        $this->total_cobrado_usd = new DbField(
            $this, // Table
            'x_total_cobrado_usd', // Variable name
            'total_cobrado_usd', // Name
            '`total_cobrado_usd`', // Expression
            '`total_cobrado_usd`', // Basic search expression
            131, // Type
            67, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_cobrado_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_cobrado_usd->InputTextType = "text";
        $this->total_cobrado_usd->Raw = true;
        $this->total_cobrado_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->total_cobrado_usd->DefaultNumberFormat = $this->total_cobrado_usd->FormatPattern;
        $this->total_cobrado_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_cobrado_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['total_cobrado_usd'] = &$this->total_cobrado_usd;

        // saldo_bs
        $this->saldo_bs = new DbField(
            $this, // Table
            'x_saldo_bs', // Variable name
            'saldo_bs', // Name
            '`saldo_bs`', // Expression
            '`saldo_bs`', // Basic search expression
            131, // Type
            67, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`saldo_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->saldo_bs->InputTextType = "text";
        $this->saldo_bs->Raw = true;
        $this->saldo_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->saldo_bs->DefaultNumberFormat = $this->saldo_bs->FormatPattern;
        $this->saldo_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->saldo_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['saldo_bs'] = &$this->saldo_bs;

        // saldo_usd
        $this->saldo_usd = new DbField(
            $this, // Table
            'x_saldo_usd', // Variable name
            'saldo_usd', // Name
            '`saldo_usd`', // Expression
            '`saldo_usd`', // Basic search expression
            131, // Type
            67, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`saldo_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->saldo_usd->InputTextType = "text";
        $this->saldo_usd->Raw = true;
        $this->saldo_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->saldo_usd->DefaultNumberFormat = $this->saldo_usd->FormatPattern;
        $this->saldo_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->saldo_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['saldo_usd'] = &$this->saldo_usd;

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
        if ($this->getCurrentDetailTable() == "view_cuentas_por_cobrar") {
            $detailUrl = Container("view_cuentas_por_cobrar")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_cliente", $this->cliente->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ViewCuentasPorCobrarResumenList";
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_cuentas_por_cobrar_resumen";
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
        $this->cliente->DbValue = $row['cliente'];
        $this->cliente_rif->DbValue = $row['cliente_rif'];
        $this->cliente_nombre->DbValue = $row['cliente_nombre'];
        $this->cantidad_documentos->DbValue = $row['cantidad_documentos'];
        $this->documentos_pendientes->DbValue = $row['documentos_pendientes'];
        $this->documentos_parciales->DbValue = $row['documentos_parciales'];
        $this->monto_documentos_bs->DbValue = $row['monto_documentos_bs'];
        $this->monto_documentos_usd->DbValue = $row['monto_documentos_usd'];
        $this->total_cobrado_bs->DbValue = $row['total_cobrado_bs'];
        $this->total_cobrado_usd->DbValue = $row['total_cobrado_usd'];
        $this->saldo_bs->DbValue = $row['saldo_bs'];
        $this->saldo_usd->DbValue = $row['saldo_usd'];
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
        return $_SESSION[$name] ?? GetUrl("ViewCuentasPorCobrarResumenList");
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
            "ViewCuentasPorCobrarResumenView" => $Language->phrase("View"),
            "ViewCuentasPorCobrarResumenEdit" => $Language->phrase("Edit"),
            "ViewCuentasPorCobrarResumenAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewCuentasPorCobrarResumenList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewCuentasPorCobrarResumenView",
            Config("API_ADD_ACTION") => "ViewCuentasPorCobrarResumenAdd",
            Config("API_EDIT_ACTION") => "ViewCuentasPorCobrarResumenEdit",
            Config("API_DELETE_ACTION") => "ViewCuentasPorCobrarResumenDelete",
            Config("API_LIST_ACTION") => "ViewCuentasPorCobrarResumenList",
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
        return "ViewCuentasPorCobrarResumenList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewCuentasPorCobrarResumenView", $parm);
        } else {
            $url = $this->keyUrl("ViewCuentasPorCobrarResumenView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewCuentasPorCobrarResumenAdd?" . $parm;
        } else {
            $url = "ViewCuentasPorCobrarResumenAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarResumenEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarResumenList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarResumenAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarResumenList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewCuentasPorCobrarResumenDelete", $parm);
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
        $this->cliente->setDbValue($row['cliente']);
        $this->cliente_rif->setDbValue($row['cliente_rif']);
        $this->cliente_nombre->setDbValue($row['cliente_nombre']);
        $this->cantidad_documentos->setDbValue($row['cantidad_documentos']);
        $this->documentos_pendientes->setDbValue($row['documentos_pendientes']);
        $this->documentos_parciales->setDbValue($row['documentos_parciales']);
        $this->monto_documentos_bs->setDbValue($row['monto_documentos_bs']);
        $this->monto_documentos_usd->setDbValue($row['monto_documentos_usd']);
        $this->total_cobrado_bs->setDbValue($row['total_cobrado_bs']);
        $this->total_cobrado_usd->setDbValue($row['total_cobrado_usd']);
        $this->saldo_bs->setDbValue($row['saldo_bs']);
        $this->saldo_usd->setDbValue($row['saldo_usd']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewCuentasPorCobrarResumenList";
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

        // cliente

        // cliente_rif

        // cliente_nombre

        // cantidad_documentos

        // documentos_pendientes

        // documentos_parciales

        // monto_documentos_bs

        // monto_documentos_usd

        // total_cobrado_bs

        // total_cobrado_usd

        // saldo_bs

        // saldo_usd

        // cliente
        $this->cliente->ViewValue = $this->cliente->CurrentValue;
        $this->cliente->ViewValue = FormatNumber($this->cliente->ViewValue, $this->cliente->formatPattern());

        // cliente_rif
        $this->cliente_rif->ViewValue = $this->cliente_rif->CurrentValue;

        // cliente_nombre
        $this->cliente_nombre->ViewValue = $this->cliente_nombre->CurrentValue;

        // cantidad_documentos
        $this->cantidad_documentos->ViewValue = $this->cantidad_documentos->CurrentValue;
        $this->cantidad_documentos->ViewValue = FormatNumber($this->cantidad_documentos->ViewValue, $this->cantidad_documentos->formatPattern());

        // documentos_pendientes
        $this->documentos_pendientes->ViewValue = $this->documentos_pendientes->CurrentValue;
        $this->documentos_pendientes->ViewValue = FormatNumber($this->documentos_pendientes->ViewValue, $this->documentos_pendientes->formatPattern());

        // documentos_parciales
        $this->documentos_parciales->ViewValue = $this->documentos_parciales->CurrentValue;
        $this->documentos_parciales->ViewValue = FormatNumber($this->documentos_parciales->ViewValue, $this->documentos_parciales->formatPattern());

        // monto_documentos_bs
        $this->monto_documentos_bs->ViewValue = $this->monto_documentos_bs->CurrentValue;
        $this->monto_documentos_bs->ViewValue = FormatNumber($this->monto_documentos_bs->ViewValue, $this->monto_documentos_bs->formatPattern());
        $this->monto_documentos_bs->CssClass = "fw-bold";
        $this->monto_documentos_bs->CellCssStyle .= "text-align: right;";

        // monto_documentos_usd
        $this->monto_documentos_usd->ViewValue = $this->monto_documentos_usd->CurrentValue;
        $this->monto_documentos_usd->ViewValue = FormatNumber($this->monto_documentos_usd->ViewValue, $this->monto_documentos_usd->formatPattern());
        $this->monto_documentos_usd->CssClass = "fw-bold";
        $this->monto_documentos_usd->CellCssStyle .= "text-align: right;";

        // total_cobrado_bs
        $this->total_cobrado_bs->ViewValue = $this->total_cobrado_bs->CurrentValue;
        $this->total_cobrado_bs->ViewValue = FormatNumber($this->total_cobrado_bs->ViewValue, $this->total_cobrado_bs->formatPattern());
        $this->total_cobrado_bs->CssClass = "fw-bold";
        $this->total_cobrado_bs->CellCssStyle .= "text-align: right;";

        // total_cobrado_usd
        $this->total_cobrado_usd->ViewValue = $this->total_cobrado_usd->CurrentValue;
        $this->total_cobrado_usd->ViewValue = FormatNumber($this->total_cobrado_usd->ViewValue, $this->total_cobrado_usd->formatPattern());
        $this->total_cobrado_usd->CssClass = "fw-bold";
        $this->total_cobrado_usd->CellCssStyle .= "text-align: right;";

        // saldo_bs
        $this->saldo_bs->ViewValue = $this->saldo_bs->CurrentValue;
        $this->saldo_bs->ViewValue = FormatNumber($this->saldo_bs->ViewValue, $this->saldo_bs->formatPattern());
        $this->saldo_bs->CssClass = "fw-bold";
        $this->saldo_bs->CellCssStyle .= "text-align: right;";

        // saldo_usd
        $this->saldo_usd->ViewValue = $this->saldo_usd->CurrentValue;
        $this->saldo_usd->ViewValue = FormatNumber($this->saldo_usd->ViewValue, $this->saldo_usd->formatPattern());
        $this->saldo_usd->CssClass = "fw-bold";
        $this->saldo_usd->CellCssStyle .= "text-align: right;";

        // cliente
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // cliente_rif
        $this->cliente_rif->HrefValue = "";
        $this->cliente_rif->TooltipValue = "";

        // cliente_nombre
        $this->cliente_nombre->HrefValue = "";
        $this->cliente_nombre->TooltipValue = "";

        // cantidad_documentos
        $this->cantidad_documentos->HrefValue = "";
        $this->cantidad_documentos->TooltipValue = "";

        // documentos_pendientes
        $this->documentos_pendientes->HrefValue = "";
        $this->documentos_pendientes->TooltipValue = "";

        // documentos_parciales
        $this->documentos_parciales->HrefValue = "";
        $this->documentos_parciales->TooltipValue = "";

        // monto_documentos_bs
        $this->monto_documentos_bs->HrefValue = "";
        $this->monto_documentos_bs->TooltipValue = "";

        // monto_documentos_usd
        $this->monto_documentos_usd->HrefValue = "";
        $this->monto_documentos_usd->TooltipValue = "";

        // total_cobrado_bs
        $this->total_cobrado_bs->HrefValue = "";
        $this->total_cobrado_bs->TooltipValue = "";

        // total_cobrado_usd
        $this->total_cobrado_usd->HrefValue = "";
        $this->total_cobrado_usd->TooltipValue = "";

        // saldo_bs
        $this->saldo_bs->HrefValue = "";
        $this->saldo_bs->TooltipValue = "";

        // saldo_usd
        $this->saldo_usd->HrefValue = "";
        $this->saldo_usd->TooltipValue = "";

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

        // cliente
        $this->cliente->setupEditAttributes();
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());
        if (strval($this->cliente->EditValue) != "" && is_numeric($this->cliente->EditValue)) {
            $this->cliente->EditValue = FormatNumber($this->cliente->EditValue, null);
        }

        // cliente_rif
        $this->cliente_rif->setupEditAttributes();
        if (!$this->cliente_rif->Raw) {
            $this->cliente_rif->CurrentValue = HtmlDecode($this->cliente_rif->CurrentValue);
        }
        $this->cliente_rif->EditValue = $this->cliente_rif->CurrentValue;
        $this->cliente_rif->PlaceHolder = RemoveHtml($this->cliente_rif->caption());

        // cliente_nombre
        $this->cliente_nombre->setupEditAttributes();
        if (!$this->cliente_nombre->Raw) {
            $this->cliente_nombre->CurrentValue = HtmlDecode($this->cliente_nombre->CurrentValue);
        }
        $this->cliente_nombre->EditValue = $this->cliente_nombre->CurrentValue;
        $this->cliente_nombre->PlaceHolder = RemoveHtml($this->cliente_nombre->caption());

        // cantidad_documentos
        $this->cantidad_documentos->setupEditAttributes();
        $this->cantidad_documentos->EditValue = $this->cantidad_documentos->CurrentValue;
        $this->cantidad_documentos->PlaceHolder = RemoveHtml($this->cantidad_documentos->caption());
        if (strval($this->cantidad_documentos->EditValue) != "" && is_numeric($this->cantidad_documentos->EditValue)) {
            $this->cantidad_documentos->EditValue = FormatNumber($this->cantidad_documentos->EditValue, null);
        }

        // documentos_pendientes
        $this->documentos_pendientes->setupEditAttributes();
        $this->documentos_pendientes->EditValue = $this->documentos_pendientes->CurrentValue;
        $this->documentos_pendientes->PlaceHolder = RemoveHtml($this->documentos_pendientes->caption());
        if (strval($this->documentos_pendientes->EditValue) != "" && is_numeric($this->documentos_pendientes->EditValue)) {
            $this->documentos_pendientes->EditValue = FormatNumber($this->documentos_pendientes->EditValue, null);
        }

        // documentos_parciales
        $this->documentos_parciales->setupEditAttributes();
        $this->documentos_parciales->EditValue = $this->documentos_parciales->CurrentValue;
        $this->documentos_parciales->PlaceHolder = RemoveHtml($this->documentos_parciales->caption());
        if (strval($this->documentos_parciales->EditValue) != "" && is_numeric($this->documentos_parciales->EditValue)) {
            $this->documentos_parciales->EditValue = FormatNumber($this->documentos_parciales->EditValue, null);
        }

        // monto_documentos_bs
        $this->monto_documentos_bs->setupEditAttributes();
        $this->monto_documentos_bs->EditValue = $this->monto_documentos_bs->CurrentValue;
        $this->monto_documentos_bs->PlaceHolder = RemoveHtml($this->monto_documentos_bs->caption());
        if (strval($this->monto_documentos_bs->EditValue) != "" && is_numeric($this->monto_documentos_bs->EditValue)) {
            $this->monto_documentos_bs->EditValue = FormatNumber($this->monto_documentos_bs->EditValue, null);
        }

        // monto_documentos_usd
        $this->monto_documentos_usd->setupEditAttributes();
        $this->monto_documentos_usd->EditValue = $this->monto_documentos_usd->CurrentValue;
        $this->monto_documentos_usd->PlaceHolder = RemoveHtml($this->monto_documentos_usd->caption());
        if (strval($this->monto_documentos_usd->EditValue) != "" && is_numeric($this->monto_documentos_usd->EditValue)) {
            $this->monto_documentos_usd->EditValue = FormatNumber($this->monto_documentos_usd->EditValue, null);
        }

        // total_cobrado_bs
        $this->total_cobrado_bs->setupEditAttributes();
        $this->total_cobrado_bs->EditValue = $this->total_cobrado_bs->CurrentValue;
        $this->total_cobrado_bs->PlaceHolder = RemoveHtml($this->total_cobrado_bs->caption());
        if (strval($this->total_cobrado_bs->EditValue) != "" && is_numeric($this->total_cobrado_bs->EditValue)) {
            $this->total_cobrado_bs->EditValue = FormatNumber($this->total_cobrado_bs->EditValue, null);
        }

        // total_cobrado_usd
        $this->total_cobrado_usd->setupEditAttributes();
        $this->total_cobrado_usd->EditValue = $this->total_cobrado_usd->CurrentValue;
        $this->total_cobrado_usd->PlaceHolder = RemoveHtml($this->total_cobrado_usd->caption());
        if (strval($this->total_cobrado_usd->EditValue) != "" && is_numeric($this->total_cobrado_usd->EditValue)) {
            $this->total_cobrado_usd->EditValue = FormatNumber($this->total_cobrado_usd->EditValue, null);
        }

        // saldo_bs
        $this->saldo_bs->setupEditAttributes();
        $this->saldo_bs->EditValue = $this->saldo_bs->CurrentValue;
        $this->saldo_bs->PlaceHolder = RemoveHtml($this->saldo_bs->caption());
        if (strval($this->saldo_bs->EditValue) != "" && is_numeric($this->saldo_bs->EditValue)) {
            $this->saldo_bs->EditValue = FormatNumber($this->saldo_bs->EditValue, null);
        }

        // saldo_usd
        $this->saldo_usd->setupEditAttributes();
        $this->saldo_usd->EditValue = $this->saldo_usd->CurrentValue;
        $this->saldo_usd->PlaceHolder = RemoveHtml($this->saldo_usd->caption());
        if (strval($this->saldo_usd->EditValue) != "" && is_numeric($this->saldo_usd->EditValue)) {
            $this->saldo_usd->EditValue = FormatNumber($this->saldo_usd->EditValue, null);
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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->cliente_rif);
                    $doc->exportCaption($this->cliente_nombre);
                    $doc->exportCaption($this->cantidad_documentos);
                    $doc->exportCaption($this->documentos_pendientes);
                    $doc->exportCaption($this->documentos_parciales);
                    $doc->exportCaption($this->monto_documentos_bs);
                    $doc->exportCaption($this->monto_documentos_usd);
                    $doc->exportCaption($this->total_cobrado_bs);
                    $doc->exportCaption($this->total_cobrado_usd);
                    $doc->exportCaption($this->saldo_bs);
                    $doc->exportCaption($this->saldo_usd);
                } else {
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->cliente_rif);
                    $doc->exportCaption($this->cliente_nombre);
                    $doc->exportCaption($this->cantidad_documentos);
                    $doc->exportCaption($this->documentos_pendientes);
                    $doc->exportCaption($this->documentos_parciales);
                    $doc->exportCaption($this->monto_documentos_bs);
                    $doc->exportCaption($this->monto_documentos_usd);
                    $doc->exportCaption($this->total_cobrado_bs);
                    $doc->exportCaption($this->total_cobrado_usd);
                    $doc->exportCaption($this->saldo_bs);
                    $doc->exportCaption($this->saldo_usd);
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
                        $doc->exportField($this->cliente_rif);
                        $doc->exportField($this->cliente_nombre);
                        $doc->exportField($this->cantidad_documentos);
                        $doc->exportField($this->documentos_pendientes);
                        $doc->exportField($this->documentos_parciales);
                        $doc->exportField($this->monto_documentos_bs);
                        $doc->exportField($this->monto_documentos_usd);
                        $doc->exportField($this->total_cobrado_bs);
                        $doc->exportField($this->total_cobrado_usd);
                        $doc->exportField($this->saldo_bs);
                        $doc->exportField($this->saldo_usd);
                    } else {
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->cliente_rif);
                        $doc->exportField($this->cliente_nombre);
                        $doc->exportField($this->cantidad_documentos);
                        $doc->exportField($this->documentos_pendientes);
                        $doc->exportField($this->documentos_parciales);
                        $doc->exportField($this->monto_documentos_bs);
                        $doc->exportField($this->monto_documentos_usd);
                        $doc->exportField($this->total_cobrado_bs);
                        $doc->exportField($this->total_cobrado_usd);
                        $doc->exportField($this->saldo_bs);
                        $doc->exportField($this->saldo_usd);
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
