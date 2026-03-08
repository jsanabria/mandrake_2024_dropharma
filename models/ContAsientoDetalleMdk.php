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
 * Table class for cont_asiento_detalle_mdk
 */
class ContAsientoDetalleMdk extends DbTable
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
    public $asiento_id;
    public $cuenta_id;
    public $centro_costo_id;
    public $concepto;
    public $moneda_trx;
    public $tasa_trx;
    public $monto_moneda_trx;
    public $debe_bs;
    public $haber_bs;
    public $created_at;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "cont_asiento_detalle_mdk";
        $this->TableName = 'cont_asiento_detalle_mdk';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "cont_asiento_detalle_mdk";
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

        // asiento_id
        $this->asiento_id = new DbField(
            $this, // Table
            'x_asiento_id', // Variable name
            'asiento_id', // Name
            '`asiento_id`', // Expression
            '`asiento_id`', // Basic search expression
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`asiento_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->asiento_id->InputTextType = "text";
        $this->asiento_id->Raw = true;
        $this->asiento_id->IsForeignKey = true; // Foreign key field
        $this->asiento_id->Nullable = false; // NOT NULL field
        $this->asiento_id->Required = true; // Required field
        $this->asiento_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->asiento_id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['asiento_id'] = &$this->asiento_id;

        // cuenta_id
        $this->cuenta_id = new DbField(
            $this, // Table
            'x_cuenta_id', // Variable name
            'cuenta_id', // Name
            '`cuenta_id`', // Expression
            '`cuenta_id`', // Basic search expression
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cuenta_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->cuenta_id->InputTextType = "text";
        $this->cuenta_id->Raw = true;
        $this->cuenta_id->Nullable = false; // NOT NULL field
        $this->cuenta_id->Required = true; // Required field
        $this->cuenta_id->setSelectMultiple(false); // Select one
        $this->cuenta_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cuenta_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cuenta_id->Lookup = new Lookup($this->cuenta_id, 'cont_plan_cuentas_mdk', false, 'id', ["codigo","nombre","",""], '', '', [], [], [], [], [], [], false, '`nombre`', '', "CONCAT(COALESCE(`codigo`, ''),'" . ValueSeparator(1, $this->cuenta_id) . "',COALESCE(`nombre`,''))");
        $this->cuenta_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cuenta_id->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cuenta_id'] = &$this->cuenta_id;

        // centro_costo_id
        $this->centro_costo_id = new DbField(
            $this, // Table
            'x_centro_costo_id', // Variable name
            'centro_costo_id', // Name
            '`centro_costo_id`', // Expression
            '`centro_costo_id`', // Basic search expression
            21, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`centro_costo_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->centro_costo_id->InputTextType = "text";
        $this->centro_costo_id->Raw = true;
        $this->centro_costo_id->setSelectMultiple(false); // Select one
        $this->centro_costo_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->centro_costo_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->centro_costo_id->Lookup = new Lookup($this->centro_costo_id, 'cont_centro_costo_mdk', false, 'id', ["codigo","nombre","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`codigo`, ''),'" . ValueSeparator(1, $this->centro_costo_id) . "',COALESCE(`nombre`,''))");
        $this->centro_costo_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->centro_costo_id->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['centro_costo_id'] = &$this->centro_costo_id;

        // concepto
        $this->concepto = new DbField(
            $this, // Table
            'x_concepto', // Variable name
            'concepto', // Name
            '`concepto`', // Expression
            '`concepto`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`concepto`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->concepto->InputTextType = "text";
        $this->concepto->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['concepto'] = &$this->concepto;

        // moneda_trx
        $this->moneda_trx = new DbField(
            $this, // Table
            'x_moneda_trx', // Variable name
            'moneda_trx', // Name
            '`moneda_trx`', // Expression
            '`moneda_trx`', // Basic search expression
            200, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`moneda_trx`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->moneda_trx->addMethod("getDefault", fn() => "Bs");
        $this->moneda_trx->InputTextType = "text";
        $this->moneda_trx->Nullable = false; // NOT NULL field
        $this->moneda_trx->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['moneda_trx'] = &$this->moneda_trx;

        // tasa_trx
        $this->tasa_trx = new DbField(
            $this, // Table
            'x_tasa_trx', // Variable name
            'tasa_trx', // Name
            '`tasa_trx`', // Expression
            '`tasa_trx`', // Basic search expression
            131, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tasa_trx`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->tasa_trx->addMethod("getDefault", fn() => 1.000000);
        $this->tasa_trx->InputTextType = "text";
        $this->tasa_trx->Raw = true;
        $this->tasa_trx->Nullable = false; // NOT NULL field
        $this->tasa_trx->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_trx->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['tasa_trx'] = &$this->tasa_trx;

        // monto_moneda_trx
        $this->monto_moneda_trx = new DbField(
            $this, // Table
            'x_monto_moneda_trx', // Variable name
            'monto_moneda_trx', // Name
            '`monto_moneda_trx`', // Expression
            '`monto_moneda_trx`', // Basic search expression
            131, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_moneda_trx`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_moneda_trx->addMethod("getDefault", fn() => 0.00);
        $this->monto_moneda_trx->InputTextType = "text";
        $this->monto_moneda_trx->Raw = true;
        $this->monto_moneda_trx->Nullable = false; // NOT NULL field
        $this->monto_moneda_trx->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_moneda_trx->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['monto_moneda_trx'] = &$this->monto_moneda_trx;

        // debe_bs
        $this->debe_bs = new DbField(
            $this, // Table
            'x_debe_bs', // Variable name
            'debe_bs', // Name
            '`debe_bs`', // Expression
            '`debe_bs`', // Basic search expression
            131, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`debe_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->debe_bs->addMethod("getDefault", fn() => 0.00);
        $this->debe_bs->InputTextType = "text";
        $this->debe_bs->Raw = true;
        $this->debe_bs->Nullable = false; // NOT NULL field
        $this->debe_bs->Sortable = false; // Allow sort
        $this->debe_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->debe_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['debe_bs'] = &$this->debe_bs;

        // haber_bs
        $this->haber_bs = new DbField(
            $this, // Table
            'x_haber_bs', // Variable name
            'haber_bs', // Name
            '`haber_bs`', // Expression
            '`haber_bs`', // Basic search expression
            131, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`haber_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->haber_bs->addMethod("getDefault", fn() => 0.00);
        $this->haber_bs->InputTextType = "text";
        $this->haber_bs->Raw = true;
        $this->haber_bs->Nullable = false; // NOT NULL field
        $this->haber_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->haber_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['haber_bs'] = &$this->haber_bs;

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
        if ($this->getCurrentMasterTable() == "cont_asiento_mdk") {
            $masterTable = Container("cont_asiento_mdk");
            if ($this->asiento_id->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->id, $this->asiento_id->getSessionValue(), $masterTable->id->DataType, $masterTable->Dbid);
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
        if ($this->getCurrentMasterTable() == "cont_asiento_mdk") {
            $masterTable = Container("cont_asiento_mdk");
            if ($this->asiento_id->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->asiento_id, $this->asiento_id->getSessionValue(), $masterTable->id->DataType, $this->Dbid);
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
            case "cont_asiento_mdk":
                $key = $keys["asiento_id"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->id->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->id, $keys["asiento_id"], $this->asiento_id->DataType, $this->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "cont_asiento_mdk":
                return GetKeyFilter($this->asiento_id, $masterTable->id->DbValue, $masterTable->id->DataType, $masterTable->Dbid);
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "cont_asiento_detalle_mdk";
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
        $this->asiento_id->DbValue = $row['asiento_id'];
        $this->cuenta_id->DbValue = $row['cuenta_id'];
        $this->centro_costo_id->DbValue = $row['centro_costo_id'];
        $this->concepto->DbValue = $row['concepto'];
        $this->moneda_trx->DbValue = $row['moneda_trx'];
        $this->tasa_trx->DbValue = $row['tasa_trx'];
        $this->monto_moneda_trx->DbValue = $row['monto_moneda_trx'];
        $this->debe_bs->DbValue = $row['debe_bs'];
        $this->haber_bs->DbValue = $row['haber_bs'];
        $this->created_at->DbValue = $row['created_at'];
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
        return $_SESSION[$name] ?? GetUrl("ContAsientoDetalleMdkList");
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
            "ContAsientoDetalleMdkView" => $Language->phrase("View"),
            "ContAsientoDetalleMdkEdit" => $Language->phrase("Edit"),
            "ContAsientoDetalleMdkAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ContAsientoDetalleMdkList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ContAsientoDetalleMdkView",
            Config("API_ADD_ACTION") => "ContAsientoDetalleMdkAdd",
            Config("API_EDIT_ACTION") => "ContAsientoDetalleMdkEdit",
            Config("API_DELETE_ACTION") => "ContAsientoDetalleMdkDelete",
            Config("API_LIST_ACTION") => "ContAsientoDetalleMdkList",
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
        return "ContAsientoDetalleMdkList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContAsientoDetalleMdkView", $parm);
        } else {
            $url = $this->keyUrl("ContAsientoDetalleMdkView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ContAsientoDetalleMdkAdd?" . $parm;
        } else {
            $url = "ContAsientoDetalleMdkAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ContAsientoDetalleMdkEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ContAsientoDetalleMdkList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ContAsientoDetalleMdkAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ContAsientoDetalleMdkList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ContAsientoDetalleMdkDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "cont_asiento_mdk" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->asiento_id->getSessionValue()); // Use Session Value
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
        $this->asiento_id->setDbValue($row['asiento_id']);
        $this->cuenta_id->setDbValue($row['cuenta_id']);
        $this->centro_costo_id->setDbValue($row['centro_costo_id']);
        $this->concepto->setDbValue($row['concepto']);
        $this->moneda_trx->setDbValue($row['moneda_trx']);
        $this->tasa_trx->setDbValue($row['tasa_trx']);
        $this->monto_moneda_trx->setDbValue($row['monto_moneda_trx']);
        $this->debe_bs->setDbValue($row['debe_bs']);
        $this->haber_bs->setDbValue($row['haber_bs']);
        $this->created_at->setDbValue($row['created_at']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ContAsientoDetalleMdkList";
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

        // asiento_id

        // cuenta_id

        // centro_costo_id

        // concepto

        // moneda_trx

        // tasa_trx

        // monto_moneda_trx

        // debe_bs

        // haber_bs

        // created_at

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // asiento_id
        $this->asiento_id->ViewValue = $this->asiento_id->CurrentValue;
        $this->asiento_id->ViewValue = FormatNumber($this->asiento_id->ViewValue, $this->asiento_id->formatPattern());

        // cuenta_id
        $curVal = strval($this->cuenta_id->CurrentValue);
        if ($curVal != "") {
            $this->cuenta_id->ViewValue = $this->cuenta_id->lookupCacheOption($curVal);
            if ($this->cuenta_id->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->cuenta_id->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->cuenta_id->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->cuenta_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta_id->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta_id->ViewValue = $this->cuenta_id->displayValue($arwrk);
                } else {
                    $this->cuenta_id->ViewValue = FormatNumber($this->cuenta_id->CurrentValue, $this->cuenta_id->formatPattern());
                }
            }
        } else {
            $this->cuenta_id->ViewValue = null;
        }

        // centro_costo_id
        $curVal = strval($this->centro_costo_id->CurrentValue);
        if ($curVal != "") {
            $this->centro_costo_id->ViewValue = $this->centro_costo_id->lookupCacheOption($curVal);
            if ($this->centro_costo_id->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->centro_costo_id->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->centro_costo_id->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->centro_costo_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->centro_costo_id->Lookup->renderViewRow($rswrk[0]);
                    $this->centro_costo_id->ViewValue = $this->centro_costo_id->displayValue($arwrk);
                } else {
                    $this->centro_costo_id->ViewValue = FormatNumber($this->centro_costo_id->CurrentValue, $this->centro_costo_id->formatPattern());
                }
            }
        } else {
            $this->centro_costo_id->ViewValue = null;
        }

        // concepto
        $this->concepto->ViewValue = $this->concepto->CurrentValue;

        // moneda_trx
        $this->moneda_trx->ViewValue = $this->moneda_trx->CurrentValue;

        // tasa_trx
        $this->tasa_trx->ViewValue = $this->tasa_trx->CurrentValue;
        $this->tasa_trx->ViewValue = FormatNumber($this->tasa_trx->ViewValue, $this->tasa_trx->formatPattern());

        // monto_moneda_trx
        $this->monto_moneda_trx->ViewValue = $this->monto_moneda_trx->CurrentValue;
        $this->monto_moneda_trx->ViewValue = FormatNumber($this->monto_moneda_trx->ViewValue, $this->monto_moneda_trx->formatPattern());

        // debe_bs
        $this->debe_bs->ViewValue = $this->debe_bs->CurrentValue;
        $this->debe_bs->ViewValue = FormatNumber($this->debe_bs->ViewValue, $this->debe_bs->formatPattern());

        // haber_bs
        $this->haber_bs->ViewValue = $this->haber_bs->CurrentValue;
        $this->haber_bs->ViewValue = FormatNumber($this->haber_bs->ViewValue, $this->haber_bs->formatPattern());

        // created_at
        $this->created_at->ViewValue = $this->created_at->CurrentValue;
        $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // asiento_id
        $this->asiento_id->HrefValue = "";
        $this->asiento_id->TooltipValue = "";

        // cuenta_id
        $this->cuenta_id->HrefValue = "";
        $this->cuenta_id->TooltipValue = "";

        // centro_costo_id
        $this->centro_costo_id->HrefValue = "";
        $this->centro_costo_id->TooltipValue = "";

        // concepto
        $this->concepto->HrefValue = "";
        $this->concepto->TooltipValue = "";

        // moneda_trx
        $this->moneda_trx->HrefValue = "";
        $this->moneda_trx->TooltipValue = "";

        // tasa_trx
        $this->tasa_trx->HrefValue = "";
        $this->tasa_trx->TooltipValue = "";

        // monto_moneda_trx
        $this->monto_moneda_trx->HrefValue = "";
        $this->monto_moneda_trx->TooltipValue = "";

        // debe_bs
        $this->debe_bs->HrefValue = "";
        $this->debe_bs->TooltipValue = "";

        // haber_bs
        $this->haber_bs->HrefValue = "";
        $this->haber_bs->TooltipValue = "";

        // created_at
        $this->created_at->HrefValue = "";
        $this->created_at->TooltipValue = "";

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

        // asiento_id
        $this->asiento_id->setupEditAttributes();
        if ($this->asiento_id->getSessionValue() != "") {
            $this->asiento_id->CurrentValue = GetForeignKeyValue($this->asiento_id->getSessionValue());
            $this->asiento_id->ViewValue = $this->asiento_id->CurrentValue;
            $this->asiento_id->ViewValue = FormatNumber($this->asiento_id->ViewValue, $this->asiento_id->formatPattern());
        } else {
            $this->asiento_id->EditValue = $this->asiento_id->CurrentValue;
            $this->asiento_id->PlaceHolder = RemoveHtml($this->asiento_id->caption());
            if (strval($this->asiento_id->EditValue) != "" && is_numeric($this->asiento_id->EditValue)) {
                $this->asiento_id->EditValue = FormatNumber($this->asiento_id->EditValue, null);
            }
        }

        // cuenta_id
        $this->cuenta_id->setupEditAttributes();
        $this->cuenta_id->PlaceHolder = RemoveHtml($this->cuenta_id->caption());

        // centro_costo_id
        $this->centro_costo_id->setupEditAttributes();
        $this->centro_costo_id->PlaceHolder = RemoveHtml($this->centro_costo_id->caption());

        // concepto
        $this->concepto->setupEditAttributes();
        if (!$this->concepto->Raw) {
            $this->concepto->CurrentValue = HtmlDecode($this->concepto->CurrentValue);
        }
        $this->concepto->EditValue = $this->concepto->CurrentValue;
        $this->concepto->PlaceHolder = RemoveHtml($this->concepto->caption());

        // moneda_trx
        $this->moneda_trx->setupEditAttributes();
        if (!$this->moneda_trx->Raw) {
            $this->moneda_trx->CurrentValue = HtmlDecode($this->moneda_trx->CurrentValue);
        }
        $this->moneda_trx->EditValue = $this->moneda_trx->CurrentValue;
        $this->moneda_trx->PlaceHolder = RemoveHtml($this->moneda_trx->caption());

        // tasa_trx
        $this->tasa_trx->setupEditAttributes();
        $this->tasa_trx->EditValue = $this->tasa_trx->CurrentValue;
        $this->tasa_trx->PlaceHolder = RemoveHtml($this->tasa_trx->caption());
        if (strval($this->tasa_trx->EditValue) != "" && is_numeric($this->tasa_trx->EditValue)) {
            $this->tasa_trx->EditValue = FormatNumber($this->tasa_trx->EditValue, null);
        }

        // monto_moneda_trx
        $this->monto_moneda_trx->setupEditAttributes();
        $this->monto_moneda_trx->EditValue = $this->monto_moneda_trx->CurrentValue;
        $this->monto_moneda_trx->PlaceHolder = RemoveHtml($this->monto_moneda_trx->caption());
        if (strval($this->monto_moneda_trx->EditValue) != "" && is_numeric($this->monto_moneda_trx->EditValue)) {
            $this->monto_moneda_trx->EditValue = FormatNumber($this->monto_moneda_trx->EditValue, null);
        }

        // debe_bs
        $this->debe_bs->setupEditAttributes();
        $this->debe_bs->EditValue = $this->debe_bs->CurrentValue;
        $this->debe_bs->PlaceHolder = RemoveHtml($this->debe_bs->caption());
        if (strval($this->debe_bs->EditValue) != "" && is_numeric($this->debe_bs->EditValue)) {
            $this->debe_bs->EditValue = FormatNumber($this->debe_bs->EditValue, null);
        }

        // haber_bs
        $this->haber_bs->setupEditAttributes();
        $this->haber_bs->EditValue = $this->haber_bs->CurrentValue;
        $this->haber_bs->PlaceHolder = RemoveHtml($this->haber_bs->caption());
        if (strval($this->haber_bs->EditValue) != "" && is_numeric($this->haber_bs->EditValue)) {
            $this->haber_bs->EditValue = FormatNumber($this->haber_bs->EditValue, null);
        }

        // created_at
        $this->created_at->setupEditAttributes();
        $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            if (is_numeric($this->debe_bs->CurrentValue)) {
                $this->debe_bs->Total += $this->debe_bs->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->haber_bs->CurrentValue)) {
                $this->haber_bs->Total += $this->haber_bs->CurrentValue; // Accumulate total
            }
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->debe_bs->CurrentValue = $this->debe_bs->Total;
            $this->debe_bs->ViewValue = $this->debe_bs->CurrentValue;
            $this->debe_bs->ViewValue = FormatNumber($this->debe_bs->ViewValue, $this->debe_bs->formatPattern());
            $this->debe_bs->HrefValue = ""; // Clear href value
            $this->haber_bs->CurrentValue = $this->haber_bs->Total;
            $this->haber_bs->ViewValue = $this->haber_bs->CurrentValue;
            $this->haber_bs->ViewValue = FormatNumber($this->haber_bs->ViewValue, $this->haber_bs->formatPattern());
            $this->haber_bs->HrefValue = ""; // Clear href value

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
                    $doc->exportCaption($this->asiento_id);
                    $doc->exportCaption($this->cuenta_id);
                    $doc->exportCaption($this->centro_costo_id);
                    $doc->exportCaption($this->concepto);
                    $doc->exportCaption($this->moneda_trx);
                    $doc->exportCaption($this->tasa_trx);
                    $doc->exportCaption($this->monto_moneda_trx);
                    $doc->exportCaption($this->debe_bs);
                    $doc->exportCaption($this->haber_bs);
                    $doc->exportCaption($this->created_at);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->asiento_id);
                    $doc->exportCaption($this->cuenta_id);
                    $doc->exportCaption($this->centro_costo_id);
                    $doc->exportCaption($this->concepto);
                    $doc->exportCaption($this->moneda_trx);
                    $doc->exportCaption($this->tasa_trx);
                    $doc->exportCaption($this->monto_moneda_trx);
                    $doc->exportCaption($this->debe_bs);
                    $doc->exportCaption($this->haber_bs);
                    $doc->exportCaption($this->created_at);
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
                $this->aggregateListRowValues(); // Aggregate row values

                // Render row
                $this->RowType = RowType::VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->id);
                        $doc->exportField($this->asiento_id);
                        $doc->exportField($this->cuenta_id);
                        $doc->exportField($this->centro_costo_id);
                        $doc->exportField($this->concepto);
                        $doc->exportField($this->moneda_trx);
                        $doc->exportField($this->tasa_trx);
                        $doc->exportField($this->monto_moneda_trx);
                        $doc->exportField($this->debe_bs);
                        $doc->exportField($this->haber_bs);
                        $doc->exportField($this->created_at);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->asiento_id);
                        $doc->exportField($this->cuenta_id);
                        $doc->exportField($this->centro_costo_id);
                        $doc->exportField($this->concepto);
                        $doc->exportField($this->moneda_trx);
                        $doc->exportField($this->tasa_trx);
                        $doc->exportField($this->monto_moneda_trx);
                        $doc->exportField($this->debe_bs);
                        $doc->exportField($this->haber_bs);
                        $doc->exportField($this->created_at);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
        }

        // Export aggregates (horizontal format only)
        if ($doc->Horizontal) {
            $this->RowType = RowType::AGGREGATE;
            $this->resetAttributes();
            $this->aggregateListRow();
            if (!$doc->ExportCustom) {
                $doc->beginExportRow(-1);
                $doc->exportAggregate($this->id, '');
                $doc->exportAggregate($this->asiento_id, '');
                $doc->exportAggregate($this->cuenta_id, '');
                $doc->exportAggregate($this->centro_costo_id, '');
                $doc->exportAggregate($this->concepto, '');
                $doc->exportAggregate($this->moneda_trx, '');
                $doc->exportAggregate($this->tasa_trx, '');
                $doc->exportAggregate($this->monto_moneda_trx, '');
                $doc->exportAggregate($this->debe_bs, 'TOTAL');
                $doc->exportAggregate($this->haber_bs, 'TOTAL');
                $doc->exportAggregate($this->created_at, '');
                $doc->endExportRow();
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
