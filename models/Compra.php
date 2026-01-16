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
 * Table class for compra
 */
class Compra extends DbTable
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
    public $proveedor;
    public $tipo_documento;
    public $doc_afectado;
    public $documento;
    public $nro_control;
    public $fecha;
    public $descripcion;
    public $aplica_retencion;
    public $monto_exento;
    public $monto_gravado;
    public $alicuota;
    public $monto_iva;
    public $monto_total;
    public $monto_pagar;
    public $ret_iva;
    public $ref_iva;
    public $ret_islr;
    public $ref_islr;
    public $ret_municipal;
    public $ref_municipal;
    public $fecha_registro;
    public $_username;
    public $comprobante;
    public $tipo_iva;
    public $tipo_islr;
    public $sustraendo;
    public $tipo_municipal;
    public $anulado;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "compra";
        $this->TableName = 'compra';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "compra";
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
        $this->id->Nullable = false; // NOT NULL field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['id'] = &$this->id;

        // proveedor
        $this->proveedor = new DbField(
            $this, // Table
            'x_proveedor', // Variable name
            'proveedor', // Name
            '`proveedor`', // Expression
            '`proveedor`', // Basic search expression
            19, // Type
            10, // Size
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
        $this->proveedor->Lookup = new Lookup($this->proveedor, 'proveedor', false, 'id', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`nombre`', '', "`nombre`");
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['proveedor'] = &$this->proveedor;

        // tipo_documento
        $this->tipo_documento = new DbField(
            $this, // Table
            'x_tipo_documento', // Variable name
            'tipo_documento', // Name
            '`tipo_documento`', // Expression
            '`tipo_documento`', // Basic search expression
            129, // Type
            2, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tipo_documento->InputTextType = "text";
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->setSelectMultiple(false); // Select one
        $this->tipo_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento->Lookup = new Lookup($this->tipo_documento, 'compra', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->tipo_documento->OptionCount = 3;
        $this->tipo_documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

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

        // documento
        $this->documento = new DbField(
            $this, // Table
            'x_documento', // Variable name
            'documento', // Name
            '`documento`', // Expression
            '`documento`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->documento->InputTextType = "text";
        $this->documento->Required = true; // Required field
        $this->documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['documento'] = &$this->documento;

        // nro_control
        $this->nro_control = new DbField(
            $this, // Table
            'x_nro_control', // Variable name
            'nro_control', // Name
            '`nro_control`', // Expression
            '`nro_control`', // Basic search expression
            200, // Type
            20, // Size
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
            133, // Type
            10, // Size
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

        // descripcion
        $this->descripcion = new DbField(
            $this, // Table
            'x_descripcion', // Variable name
            'descripcion', // Name
            '`descripcion`', // Expression
            '`descripcion`', // Basic search expression
            200, // Type
            150, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`descripcion`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->descripcion->InputTextType = "text";
        $this->descripcion->Required = true; // Required field
        $this->descripcion->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['descripcion'] = &$this->descripcion;

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
        $this->aplica_retencion->Lookup = new Lookup($this->aplica_retencion, 'compra', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->aplica_retencion->OptionCount = 2;
        $this->aplica_retencion->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['aplica_retencion'] = &$this->aplica_retencion;

        // monto_exento
        $this->monto_exento = new DbField(
            $this, // Table
            'x_monto_exento', // Variable name
            'monto_exento', // Name
            '`monto_exento`', // Expression
            '`monto_exento`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_exento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_exento->InputTextType = "text";
        $this->monto_exento->Raw = true;
        $this->monto_exento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_exento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_exento'] = &$this->monto_exento;

        // monto_gravado
        $this->monto_gravado = new DbField(
            $this, // Table
            'x_monto_gravado', // Variable name
            'monto_gravado', // Name
            '`monto_gravado`', // Expression
            '`monto_gravado`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_gravado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_gravado->InputTextType = "text";
        $this->monto_gravado->Raw = true;
        $this->monto_gravado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_gravado->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_gravado'] = &$this->monto_gravado;

        // alicuota
        $this->alicuota = new DbField(
            $this, // Table
            'x_alicuota', // Variable name
            'alicuota', // Name
            '`alicuota`', // Expression
            '`alicuota`', // Basic search expression
            131, // Type
            16, // Size
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

        // monto_iva
        $this->monto_iva = new DbField(
            $this, // Table
            'x_monto_iva', // Variable name
            'monto_iva', // Name
            '`monto_iva`', // Expression
            '`monto_iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_iva->InputTextType = "text";
        $this->monto_iva->Raw = true;
        $this->monto_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_iva'] = &$this->monto_iva;

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
        $this->ref_iva->addMethod("getLinkPrefix", fn() => "../reportes/rptRetencion.php?Nretencion=");
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
        $this->ref_islr->addMethod("getLinkPrefix", fn() => "../reportes/rptRetencion2.php?Nretencion=");
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
        $this->ref_municipal->addMethod("getLinkPrefix", fn() => "../reportes/rptRetencion3.php?Nretencion=");
        $this->ref_municipal->InputTextType = "text";
        $this->ref_municipal->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ref_municipal'] = &$this->ref_municipal;

        // fecha_registro
        $this->fecha_registro = new DbField(
            $this, // Table
            'x_fecha_registro', // Variable name
            'fecha_registro', // Name
            '`fecha_registro`', // Expression
            CastDateFieldForLike("`fecha_registro`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_registro`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_registro->InputTextType = "text";
        $this->fecha_registro->Raw = true;
        $this->fecha_registro->Required = true; // Required field
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_registro->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

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
        $this->comprobante->addMethod("getLinkPrefix", fn() => "../ContAsientoList?showmaster=cont_comprobante&fk_id=");
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

        // anulado
        $this->anulado = new DbField(
            $this, // Table
            'x_anulado', // Variable name
            'anulado', // Name
            '`anulado`', // Expression
            '`anulado`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`anulado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->anulado->addMethod("getDefault", fn() => "N");
        $this->anulado->InputTextType = "text";
        $this->anulado->Raw = true;
        $this->anulado->Required = true; // Required field
        $this->anulado->Lookup = new Lookup($this->anulado, 'compra', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->anulado->OptionCount = 2;
        $this->anulado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['anulado'] = &$this->anulado;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "compra";
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
        $this->proveedor->DbValue = $row['proveedor'];
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->documento->DbValue = $row['documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->descripcion->DbValue = $row['descripcion'];
        $this->aplica_retencion->DbValue = $row['aplica_retencion'];
        $this->monto_exento->DbValue = $row['monto_exento'];
        $this->monto_gravado->DbValue = $row['monto_gravado'];
        $this->alicuota->DbValue = $row['alicuota'];
        $this->monto_iva->DbValue = $row['monto_iva'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->ret_iva->DbValue = $row['ret_iva'];
        $this->ref_iva->DbValue = $row['ref_iva'];
        $this->ret_islr->DbValue = $row['ret_islr'];
        $this->ref_islr->DbValue = $row['ref_islr'];
        $this->ret_municipal->DbValue = $row['ret_municipal'];
        $this->ref_municipal->DbValue = $row['ref_municipal'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->_username->DbValue = $row['username'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipo_iva->DbValue = $row['tipo_iva'];
        $this->tipo_islr->DbValue = $row['tipo_islr'];
        $this->sustraendo->DbValue = $row['sustraendo'];
        $this->tipo_municipal->DbValue = $row['tipo_municipal'];
        $this->anulado->DbValue = $row['anulado'];
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
        return $_SESSION[$name] ?? GetUrl("CompraList");
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
            "CompraView" => $Language->phrase("View"),
            "CompraEdit" => $Language->phrase("Edit"),
            "CompraAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "CompraList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "CompraView",
            Config("API_ADD_ACTION") => "CompraAdd",
            Config("API_EDIT_ACTION") => "CompraEdit",
            Config("API_DELETE_ACTION") => "CompraDelete",
            Config("API_LIST_ACTION") => "CompraList",
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
        return "CompraList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CompraView", $parm);
        } else {
            $url = $this->keyUrl("CompraView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CompraAdd?" . $parm;
        } else {
            $url = "CompraAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("CompraEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("CompraList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("CompraAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("CompraList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("CompraDelete", $parm);
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
        $this->proveedor->setDbValue($row['proveedor']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->documento->setDbValue($row['documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->monto_exento->setDbValue($row['monto_exento']);
        $this->monto_gravado->setDbValue($row['monto_gravado']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->monto_iva->setDbValue($row['monto_iva']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->anulado->setDbValue($row['anulado']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "CompraList";
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

        // proveedor

        // tipo_documento

        // doc_afectado

        // documento

        // nro_control

        // fecha

        // descripcion

        // aplica_retencion

        // monto_exento

        // monto_gravado

        // alicuota

        // monto_iva

        // monto_total

        // monto_pagar

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // fecha_registro

        // username

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // tipo_municipal

        // anulado

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

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

        // tipo_documento
        if (strval($this->tipo_documento->CurrentValue) != "") {
            $this->tipo_documento->ViewValue = $this->tipo_documento->optionCaption($this->tipo_documento->CurrentValue);
        } else {
            $this->tipo_documento->ViewValue = null;
        }

        // doc_afectado
        $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

        // documento
        $this->documento->ViewValue = $this->documento->CurrentValue;

        // nro_control
        $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

        // descripcion
        $this->descripcion->ViewValue = $this->descripcion->CurrentValue;

        // aplica_retencion
        if (strval($this->aplica_retencion->CurrentValue) != "") {
            $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
        } else {
            $this->aplica_retencion->ViewValue = null;
        }

        // monto_exento
        $this->monto_exento->ViewValue = $this->monto_exento->CurrentValue;
        $this->monto_exento->ViewValue = FormatNumber($this->monto_exento->ViewValue, $this->monto_exento->formatPattern());

        // monto_gravado
        $this->monto_gravado->ViewValue = $this->monto_gravado->CurrentValue;
        $this->monto_gravado->ViewValue = FormatNumber($this->monto_gravado->ViewValue, $this->monto_gravado->formatPattern());

        // alicuota
        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
        $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->formatPattern());

        // monto_iva
        $this->monto_iva->ViewValue = $this->monto_iva->CurrentValue;
        $this->monto_iva->ViewValue = FormatNumber($this->monto_iva->ViewValue, $this->monto_iva->formatPattern());

        // monto_total
        $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
        $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, $this->monto_total->formatPattern());

        // monto_pagar
        $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, $this->monto_pagar->formatPattern());
        $this->monto_pagar->CssClass = "fw-bold";

        // ret_iva
        $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, $this->ret_iva->formatPattern());
        $this->ret_iva->CssClass = "fw-bold";

        // ref_iva
        $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;

        // ret_islr
        $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, $this->ret_islr->formatPattern());
        $this->ret_islr->CssClass = "fw-bold";

        // ref_islr
        $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;

        // ret_municipal
        $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, $this->ret_municipal->formatPattern());
        $this->ret_municipal->CssClass = "fw-bold";

        // ref_municipal
        $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;

        // fecha_registro
        $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
        $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, $this->fecha_registro->formatPattern());

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

        // tipo_municipal
        $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;

        // anulado
        if (strval($this->anulado->CurrentValue) != "") {
            $this->anulado->ViewValue = $this->anulado->optionCaption($this->anulado->CurrentValue);
        } else {
            $this->anulado->ViewValue = null;
        }

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // proveedor
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // documento
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // nro_control
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // descripcion
        $this->descripcion->HrefValue = "";
        $this->descripcion->TooltipValue = "";

        // aplica_retencion
        $this->aplica_retencion->HrefValue = "";
        $this->aplica_retencion->TooltipValue = "";

        // monto_exento
        $this->monto_exento->HrefValue = "";
        $this->monto_exento->TooltipValue = "";

        // monto_gravado
        $this->monto_gravado->HrefValue = "";
        $this->monto_gravado->TooltipValue = "";

        // alicuota
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

        // monto_iva
        $this->monto_iva->HrefValue = "";
        $this->monto_iva->TooltipValue = "";

        // monto_total
        $this->monto_total->HrefValue = "";
        $this->monto_total->TooltipValue = "";

        // monto_pagar
        $this->monto_pagar->HrefValue = "";
        $this->monto_pagar->TooltipValue = "";

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
        if (!EmptyValue($this->id->CurrentValue)) {
            $this->ref_municipal->HrefValue = $this->ref_municipal->getLinkPrefix() . $this->id->CurrentValue; // Add prefix/suffix
            $this->ref_municipal->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->ref_municipal->HrefValue = FullUrl($this->ref_municipal->HrefValue, "href");
            }
        } else {
            $this->ref_municipal->HrefValue = "";
        }
        $this->ref_municipal->TooltipValue = "";

        // fecha_registro
        $this->fecha_registro->HrefValue = "";
        $this->fecha_registro->TooltipValue = "";

        // username
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

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

        // tipo_municipal
        $this->tipo_municipal->HrefValue = "";
        $this->tipo_municipal->TooltipValue = "";

        // anulado
        $this->anulado->HrefValue = "";
        $this->anulado->TooltipValue = "";

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

        // proveedor
        $this->proveedor->setupEditAttributes();
        $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

        // tipo_documento
        $this->tipo_documento->setupEditAttributes();
        $this->tipo_documento->EditValue = $this->tipo_documento->options(true);
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // doc_afectado
        $this->doc_afectado->setupEditAttributes();
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // documento
        $this->documento->setupEditAttributes();
        if (!$this->documento->Raw) {
            $this->documento->CurrentValue = HtmlDecode($this->documento->CurrentValue);
        }
        $this->documento->EditValue = $this->documento->CurrentValue;
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

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

        // descripcion
        $this->descripcion->setupEditAttributes();
        $this->descripcion->EditValue = $this->descripcion->CurrentValue;
        $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

        // aplica_retencion
        $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
        $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

        // monto_exento
        $this->monto_exento->setupEditAttributes();
        $this->monto_exento->EditValue = $this->monto_exento->CurrentValue;
        $this->monto_exento->PlaceHolder = RemoveHtml($this->monto_exento->caption());
        if (strval($this->monto_exento->EditValue) != "" && is_numeric($this->monto_exento->EditValue)) {
            $this->monto_exento->EditValue = FormatNumber($this->monto_exento->EditValue, null);
        }

        // monto_gravado
        $this->monto_gravado->setupEditAttributes();
        $this->monto_gravado->EditValue = $this->monto_gravado->CurrentValue;
        $this->monto_gravado->PlaceHolder = RemoveHtml($this->monto_gravado->caption());
        if (strval($this->monto_gravado->EditValue) != "" && is_numeric($this->monto_gravado->EditValue)) {
            $this->monto_gravado->EditValue = FormatNumber($this->monto_gravado->EditValue, null);
        }

        // alicuota
        $this->alicuota->setupEditAttributes();
        $this->alicuota->EditValue = $this->alicuota->CurrentValue;
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
        if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
            $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, null);
        }

        // monto_iva
        $this->monto_iva->setupEditAttributes();
        $this->monto_iva->EditValue = $this->monto_iva->CurrentValue;
        $this->monto_iva->PlaceHolder = RemoveHtml($this->monto_iva->caption());
        if (strval($this->monto_iva->EditValue) != "" && is_numeric($this->monto_iva->EditValue)) {
            $this->monto_iva->EditValue = FormatNumber($this->monto_iva->EditValue, null);
        }

        // monto_total
        $this->monto_total->setupEditAttributes();
        $this->monto_total->EditValue = $this->monto_total->CurrentValue;
        $this->monto_total->PlaceHolder = RemoveHtml($this->monto_total->caption());
        if (strval($this->monto_total->EditValue) != "" && is_numeric($this->monto_total->EditValue)) {
            $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, null);
        }

        // monto_pagar
        $this->monto_pagar->setupEditAttributes();
        $this->monto_pagar->EditValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->PlaceHolder = RemoveHtml($this->monto_pagar->caption());
        if (strval($this->monto_pagar->EditValue) != "" && is_numeric($this->monto_pagar->EditValue)) {
            $this->monto_pagar->EditValue = FormatNumber($this->monto_pagar->EditValue, null);
        }

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

        // fecha_registro
        $this->fecha_registro->setupEditAttributes();
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, $this->fecha_registro->formatPattern());
        $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

        // username
        $this->_username->setupEditAttributes();
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

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

        // tipo_municipal
        $this->tipo_municipal->setupEditAttributes();
        if (!$this->tipo_municipal->Raw) {
            $this->tipo_municipal->CurrentValue = HtmlDecode($this->tipo_municipal->CurrentValue);
        }
        $this->tipo_municipal->EditValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->PlaceHolder = RemoveHtml($this->tipo_municipal->caption());

        // anulado
        $this->anulado->EditValue = $this->anulado->options(false);
        $this->anulado->PlaceHolder = RemoveHtml($this->anulado->caption());

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
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->monto_exento);
                    $doc->exportCaption($this->monto_gravado);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->monto_iva);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->anulado);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->monto_exento);
                    $doc->exportCaption($this->monto_gravado);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->monto_iva);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->anulado);
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
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->monto_exento);
                        $doc->exportField($this->monto_gravado);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->monto_iva);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->anulado);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->monto_exento);
                        $doc->exportField($this->monto_gravado);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->monto_iva);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->anulado);
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
        WriteAuditLog(CurrentUserIdentifier(), $typ, 'compra');
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
                WriteAuditLog($usr, "A", 'compra', $fldname, $key, "", $newvalue);
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
                    WriteAuditLog($usr, "U", 'compra', $fldname, $key, $oldvalue, $newvalue);
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
                WriteAuditLog($usr, "D", 'compra', $fldname, $key, $oldvalue);
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
    	// Valido que ya no se haya registrado el número de factura 
    	$proveedor = $rsnew["proveedor"];
    	$rsnew["documento"] = trim($rsnew["documento"]);
    	$documento = $rsnew["documento"];
    	$sql = "SELECT documento FROM compra WHERE proveedor = $proveedor AND documento = '$documento';";
    	if($row = ExecuteRow($sql)){
    		$this->CancelMessage = "El n&uacute;mero de factura ya est&aacute; registrado para el proveedor; verifique.";
    		return FALSE;
    	}
    	$alicuota = floatval($rsnew["alicuota"]);
    	$monto_exento = floatval($rsnew["monto_exento"]);
    	$monto_gravado = floatval($rsnew["monto_gravado"]);
    	$monto_iva = $monto_gravado * ($alicuota/100);
    	$monto_total = $monto_exento + $monto_gravado + $monto_iva;
    	$monto_pagar = $monto_total;
    	if($rsnew["aplica_retencion"] == "S") {
    		$sql = "SELECT ci_rif AS rif, tipo_iva, tipo_islr, sustraendo, tipo_impmun FROM proveedor WHERE id = " . $rsnew["proveedor"] . ";";
    		$row = ExecuteRow($sql);
    		$retIVA = floatval($row["tipo_iva"]);
    		$retISLR = floatval($row["tipo_islr"]);
    		$sustraendo = floatval($row["sustraendo"]);
    		$retMuni = floatval($row["tipo_impmun"]);
    		$rif = trim($row["rif"]);
    		$MretIVA = $monto_iva * ($retIVA/100);
    		$MretSLR = (($monto_gravado) * ($retISLR/100)) - $sustraendo;
    		$MretMUNI = $monto_gravado * ($retMuni/100);
    		$monto_pagar = $monto_total - ($MretIVA+$MretSLR+$MretMUNI);
    		$rsnew["ret_iva"] = $MretIVA;
    		$rsnew["ret_islr"] = $MretSLR;
    		$rsnew["ret_municipal"] = $MretMUNI;
    	}
    	else {
    		$retIVA = 0.00;
    		$retISLR = 0.00;
    		$sustraendo = 0.00;
    		$retMuni = 0.00;
    		$rsnew["ret_iva"] = $retIVA;
    		$rsnew["ret_islr"] = $retISLR;
    		$rsnew["ret_municipal"] = $retMuni;
    	}
    	$rsnew["monto_iva"] = $monto_iva;
    	$rsnew["monto_total"] = $monto_total;
    	$rsnew["monto_pagar"] = $monto_pagar;
    	$rsnew["fecha_registro"] = $rsnew["fecha"];
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["tipo_iva"] = strval($retIVA);
    	$rsnew["tipo_islr"] = strval($retISLR);
    	$rsnew["sustraendo"] = $sustraendo;
    	$rsnew["tipo_municipal"] = strval($retMuni);
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, $rsnew)
    {
        //Log("Row Inserted");
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
    		$sql = "UPDATE compra SET ref_iva = '$comprobante' 
    				WHERE id = '" . $rsnew["id"] . "';";
    		Execute($sql);
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if($rsold["anulado"] == "S" and $rsnew["anulado"] == "N") { 
    		if(!VerificaFuncion('019')) {
    			$this->CancelMessage = "No est&aacute; autorizado para cambiar a estatus activo; verifique.";
    			return FALSE;
    		}
    	}

    ///////////////////
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
    ///////////////////
    	if($rsnew["tipo_documento"] == "FC" or trim($rsnew["tipo_documento"]) == "") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	$alicuota = floatval($rsnew["alicuota"]);
    	$monto_exento = floatval($rsnew["monto_exento"]);
    	$monto_gravado = floatval($rsnew["monto_gravado"]);
    	$monto_iva = $monto_gravado * ($alicuota/100);
    	$monto_total = $monto_exento + $monto_gravado + $monto_iva;
    	$monto_pagar = $monto_total;
    	if($rsnew["aplica_retencion"] == "S") {
    		$sql = "SELECT ci_rif AS rif, tipo_iva, tipo_islr, sustraendo, tipo_impmun FROM proveedor WHERE id = " . $rsnew["proveedor"] . ";";
    		$row = ExecuteRow($sql);
    		$retIVA = floatval($row["tipo_iva"]);
    		$retISLR = floatval($row["tipo_islr"]);
    		$sustraendo = floatval($row["sustraendo"]);
    		$retMuni = floatval($row["tipo_impmun"]);
    		$rif = trim($row["rif"]);
    		$MretIVA = $monto_iva * ($retIVA/100);
    		$MretSLR = (($monto_gravado) * ($retISLR/100)) - $sustraendo;
    		$MretMUNI = $monto_gravado * ($retMuni/100);
    		$monto_pagar = $monto_total - ($MretIVA+$MretSLR+$MretMUNI);
    		$rsnew["ret_iva"] = $MretIVA;
    		$rsnew["ret_islr"] = $MretSLR;
    		$rsnew["ret_municipal"] = $MretMUNI;
    	}
    	else {
    		$retIVA = 0.00;
    		$retISLR = 0.00;
    		$sustraendo = 0.00;
    		$retMuni = 0.00;
    		$rsnew["ret_iva"] = $retIVA;
    		$rsnew["ret_islr"] = $retISLR;
    		$rsnew["ret_municipal"] = $retMuni;
    	}
    	$rsnew["monto_iva"] = $monto_iva;
    	$rsnew["monto_total"] = $monto_total;
    	$rsnew["monto_pagar"] = $monto_pagar;
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["tipo_iva"] = strval($retIVA);
    	$rsnew["tipo_islr"] = strval($retISLR);
    	$rsnew["sustraendo"] = $sustraendo;
    	$rsnew["tipo_municipal"] = strval($retMuni);
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
    	if($rs["ref_iva"] != "") {
    		$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de IVA asociado; no se puede eliminar.";
    		return FALSE;
    	}
    	if($rs["comprobante"] != "") {
    		$this->CancelMessage = "Este movimiento tiene un asiento contable; no se puede eliminar.";
    		return FALSE;
    	}
    	if(!VerificaFuncion('020')) {
    		$this->CancelMessage = "No est&aacute; autorizado para eliminar compras administrativas; verifique.";
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
    	if($this->comprobante->CurrentValue == "")
    		$this->RowAttrs["class"] = ""; 
    	else
    		$this->RowAttrs["class"] = "success";
    	$color = "";
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->anulado->CurrentValue == "S") { 
    			$color = "background-color: #000000; color: #ffffff;";
    			$this->proveedor->CellAttrs["style"] = $color;
    			$this->documento->CellAttrs["style"] = $color;
    			$this->aplica_retencion->CellAttrs["style"] = $color;
    			$this->monto_pagar->CellAttrs["style"] = $color;
    			$this->anulado->CellAttrs["style"] = $color;
    		}
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
