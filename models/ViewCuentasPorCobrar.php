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
 * Table class for view_cuentas_por_cobrar
 */
class ViewCuentasPorCobrar extends DbTable
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
    public $cliente_rif;
    public $cliente_nombre;
    public $tipo_documento_fiscal;
    public $nro_documento;
    public $nro_control;
    public $fecha;
    public $fecha_documento;
    public $fecha_vencimiento;
    public $moneda;
    public $tasa_dia;
    public $dias_credito;
    public $entregado;
    public $pagado;
    public $doc_afectado;
    public $doc_afe;
    public $igtf;
    public $monto_igtf_bs;
    public $signo_documento;
    public $monto_documento_moneda;
    public $monto_documento_bs;
    public $monto_documento_usd;
    public $monto_aplicado_bs;
    public $monto_aplicado_usd;
    public $total_cobrado_bs;
    public $total_cobrado_usd;
    public $cantidad_cobros;
    public $fecha_ultimo_cobro;
    public $saldo_bs;
    public $saldo_usd;
    public $estado_cuenta;
    public $dias_vencido;
    public $antiguedad;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "view_cuentas_por_cobrar";
        $this->TableName = 'view_cuentas_por_cobrar';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_cuentas_por_cobrar";
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
        $this->cliente->InputTextType = "text";
        $this->cliente->Raw = true;
        $this->cliente->IsForeignKey = true; // Foreign key field
        $this->cliente->setSelectMultiple(false); // Select one
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup($this->cliente, 'cliente', false, 'id', ["nombre","ci_rif","",""], '', '', [], [], [], [], [], [], false, '', '', "CONCAT(COALESCE(`nombre`, ''),'" . ValueSeparator(1, $this->cliente) . "',COALESCE(`ci_rif`,''))");
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
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

        // tipo_documento_fiscal
        $this->tipo_documento_fiscal = new DbField(
            $this, // Table
            'x_tipo_documento_fiscal', // Variable name
            'tipo_documento_fiscal', // Name
            '`tipo_documento_fiscal`', // Expression
            '`tipo_documento_fiscal`', // Basic search expression
            129, // Type
            2, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`tipo_documento_fiscal`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->tipo_documento_fiscal->InputTextType = "text";
        $this->tipo_documento_fiscal->setSelectMultiple(false); // Select one
        $this->tipo_documento_fiscal->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento_fiscal->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento_fiscal->Lookup = new Lookup($this->tipo_documento_fiscal, 'view_cuentas_por_cobrar', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->tipo_documento_fiscal->OptionCount = 4;
        $this->tipo_documento_fiscal->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_documento_fiscal'] = &$this->tipo_documento_fiscal;

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
            76, // Size
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
        $this->fecha->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha'] = &$this->fecha;

        // fecha_documento
        $this->fecha_documento = new DbField(
            $this, // Table
            'x_fecha_documento', // Variable name
            'fecha_documento', // Name
            '`fecha_documento`', // Expression
            CastDateFieldForLike("`fecha_documento`", 7, "DB"), // Basic search expression
            133, // Type
            40, // Size
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

        // fecha_vencimiento
        $this->fecha_vencimiento = new DbField(
            $this, // Table
            'x_fecha_vencimiento', // Variable name
            'fecha_vencimiento', // Name
            '`fecha_vencimiento`', // Expression
            CastDateFieldForLike("`fecha_vencimiento`", 7, "DB"), // Basic search expression
            133, // Type
            40, // Size
            7, // Date/Time format
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
        $this->fecha_vencimiento->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_vencimiento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_vencimiento'] = &$this->fecha_vencimiento;

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
        $this->tasa_dia->addMethod("getDefault", fn() => 0.00);
        $this->tasa_dia->InputTextType = "text";
        $this->tasa_dia->Raw = true;
        $this->tasa_dia->Nullable = false; // NOT NULL field
        $this->tasa_dia->FormatPattern = "#,##0.00"; // Format pattern
        $this->tasa_dia->DefaultNumberFormat = $this->tasa_dia->FormatPattern;
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // dias_credito
        $this->dias_credito = new DbField(
            $this, // Table
            'x_dias_credito', // Variable name
            'dias_credito', // Name
            '`dias_credito`', // Expression
            '`dias_credito`', // Basic search expression
            3, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`dias_credito`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->dias_credito->addMethod("getDefault", fn() => 0);
        $this->dias_credito->InputTextType = "text";
        $this->dias_credito->Raw = true;
        $this->dias_credito->Nullable = false; // NOT NULL field
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
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
            'RADIO' // Edit Tag
        );
        $this->entregado->addMethod("getDefault", fn() => "N");
        $this->entregado->InputTextType = "text";
        $this->entregado->Raw = true;
        $this->entregado->Lookup = new Lookup($this->entregado, 'view_cuentas_por_cobrar', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->entregado->OptionCount = 2;
        $this->entregado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['entregado'] = &$this->entregado;

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
            'RADIO' // Edit Tag
        );
        $this->pagado->addMethod("getDefault", fn() => "N");
        $this->pagado->InputTextType = "text";
        $this->pagado->Raw = true;
        $this->pagado->Lookup = new Lookup($this->pagado, 'view_cuentas_por_cobrar', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->pagado->OptionCount = 2;
        $this->pagado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['pagado'] = &$this->pagado;

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

        // doc_afe
        $this->doc_afe = new DbField(
            $this, // Table
            'x_doc_afe', // Variable name
            'doc_afe', // Name
            '`doc_afe`', // Expression
            '`doc_afe`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`doc_afe`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->doc_afe->InputTextType = "text";
        $this->doc_afe->Raw = true;
        $this->doc_afe->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->doc_afe->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['doc_afe'] = &$this->doc_afe;

        // igtf
        $this->igtf = new DbField(
            $this, // Table
            'x_igtf', // Variable name
            'igtf', // Name
            '`igtf`', // Expression
            '`igtf`', // Basic search expression
            200, // Type
            1, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`igtf`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->igtf->InputTextType = "text";
        $this->igtf->Raw = true;
        $this->igtf->Lookup = new Lookup($this->igtf, 'view_cuentas_por_cobrar', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->igtf->OptionCount = 2;
        $this->igtf->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['igtf'] = &$this->igtf;

        // monto_igtf_bs
        $this->monto_igtf_bs = new DbField(
            $this, // Table
            'x_monto_igtf_bs', // Variable name
            'monto_igtf_bs', // Name
            '`monto_igtf_bs`', // Expression
            '`monto_igtf_bs`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_igtf_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_igtf_bs->addMethod("getDefault", fn() => 0.00);
        $this->monto_igtf_bs->InputTextType = "text";
        $this->monto_igtf_bs->Raw = true;
        $this->monto_igtf_bs->Nullable = false; // NOT NULL field
        $this->monto_igtf_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_igtf_bs->DefaultNumberFormat = $this->monto_igtf_bs->FormatPattern;
        $this->monto_igtf_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_igtf_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['monto_igtf_bs'] = &$this->monto_igtf_bs;

        // signo_documento
        $this->signo_documento = new DbField(
            $this, // Table
            'x_signo_documento', // Variable name
            'signo_documento', // Name
            '`signo_documento`', // Expression
            '`signo_documento`', // Basic search expression
            3, // Type
            2, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`signo_documento`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->signo_documento->addMethod("getDefault", fn() => 0);
        $this->signo_documento->InputTextType = "text";
        $this->signo_documento->Raw = true;
        $this->signo_documento->Nullable = false; // NOT NULL field
        $this->signo_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->signo_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['signo_documento'] = &$this->signo_documento;

        // monto_documento_moneda
        $this->monto_documento_moneda = new DbField(
            $this, // Table
            'x_monto_documento_moneda', // Variable name
            'monto_documento_moneda', // Name
            '`monto_documento_moneda`', // Expression
            '`monto_documento_moneda`', // Basic search expression
            131, // Type
            23, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_documento_moneda`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_documento_moneda->InputTextType = "text";
        $this->monto_documento_moneda->Raw = true;
        $this->monto_documento_moneda->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_documento_moneda->DefaultNumberFormat = $this->monto_documento_moneda->FormatPattern;
        $this->monto_documento_moneda->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_documento_moneda->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_documento_moneda'] = &$this->monto_documento_moneda;

        // monto_documento_bs
        $this->monto_documento_bs = new DbField(
            $this, // Table
            'x_monto_documento_bs', // Variable name
            'monto_documento_bs', // Name
            '`monto_documento_bs`', // Expression
            '`monto_documento_bs`', // Basic search expression
            131, // Type
            31, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_documento_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_documento_bs->addMethod("getDefault", fn() => 0.0000);
        $this->monto_documento_bs->InputTextType = "text";
        $this->monto_documento_bs->Raw = true;
        $this->monto_documento_bs->Nullable = false; // NOT NULL field
        $this->monto_documento_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_documento_bs->DefaultNumberFormat = $this->monto_documento_bs->FormatPattern;
        $this->monto_documento_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_documento_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['monto_documento_bs'] = &$this->monto_documento_bs;

        // monto_documento_usd
        $this->monto_documento_usd = new DbField(
            $this, // Table
            'x_monto_documento_usd', // Variable name
            'monto_documento_usd', // Name
            '`monto_documento_usd`', // Expression
            '`monto_documento_usd`', // Basic search expression
            131, // Type
            23, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_documento_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_documento_usd->InputTextType = "text";
        $this->monto_documento_usd->Raw = true;
        $this->monto_documento_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_documento_usd->DefaultNumberFormat = $this->monto_documento_usd->FormatPattern;
        $this->monto_documento_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_documento_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_documento_usd'] = &$this->monto_documento_usd;

        // monto_aplicado_bs
        $this->monto_aplicado_bs = new DbField(
            $this, // Table
            'x_monto_aplicado_bs', // Variable name
            'monto_aplicado_bs', // Name
            '`monto_aplicado_bs`', // Expression
            '`monto_aplicado_bs`', // Basic search expression
            131, // Type
            31, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_aplicado_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_aplicado_bs->addMethod("getDefault", fn() => 0.00);
        $this->monto_aplicado_bs->InputTextType = "text";
        $this->monto_aplicado_bs->Raw = true;
        $this->monto_aplicado_bs->Nullable = false; // NOT NULL field
        $this->monto_aplicado_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_aplicado_bs->DefaultNumberFormat = $this->monto_aplicado_bs->FormatPattern;
        $this->monto_aplicado_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_aplicado_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['monto_aplicado_bs'] = &$this->monto_aplicado_bs;

        // monto_aplicado_usd
        $this->monto_aplicado_usd = new DbField(
            $this, // Table
            'x_monto_aplicado_usd', // Variable name
            'monto_aplicado_usd', // Name
            '`monto_aplicado_usd`', // Expression
            '`monto_aplicado_usd`', // Basic search expression
            131, // Type
            21, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_aplicado_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_aplicado_usd->InputTextType = "text";
        $this->monto_aplicado_usd->Raw = true;
        $this->monto_aplicado_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->monto_aplicado_usd->DefaultNumberFormat = $this->monto_aplicado_usd->FormatPattern;
        $this->monto_aplicado_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_aplicado_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_aplicado_usd'] = &$this->monto_aplicado_usd;

        // total_cobrado_bs
        $this->total_cobrado_bs = new DbField(
            $this, // Table
            'x_total_cobrado_bs', // Variable name
            'total_cobrado_bs', // Name
            '`total_cobrado_bs`', // Expression
            '`total_cobrado_bs`', // Basic search expression
            131, // Type
            61, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_cobrado_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_cobrado_bs->addMethod("getDefault", fn() => 0.00);
        $this->total_cobrado_bs->InputTextType = "text";
        $this->total_cobrado_bs->Raw = true;
        $this->total_cobrado_bs->Nullable = false; // NOT NULL field
        $this->total_cobrado_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->total_cobrado_bs->DefaultNumberFormat = $this->total_cobrado_bs->FormatPattern;
        $this->total_cobrado_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_cobrado_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['total_cobrado_bs'] = &$this->total_cobrado_bs;

        // total_cobrado_usd
        $this->total_cobrado_usd = new DbField(
            $this, // Table
            'x_total_cobrado_usd', // Variable name
            'total_cobrado_usd', // Name
            '`total_cobrado_usd`', // Expression
            '`total_cobrado_usd`', // Basic search expression
            131, // Type
            63, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`total_cobrado_usd`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->total_cobrado_usd->addMethod("getDefault", fn() => 0.00);
        $this->total_cobrado_usd->InputTextType = "text";
        $this->total_cobrado_usd->Raw = true;
        $this->total_cobrado_usd->Nullable = false; // NOT NULL field
        $this->total_cobrado_usd->FormatPattern = "#,##0.00"; // Format pattern
        $this->total_cobrado_usd->DefaultNumberFormat = $this->total_cobrado_usd->FormatPattern;
        $this->total_cobrado_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total_cobrado_usd->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['total_cobrado_usd'] = &$this->total_cobrado_usd;

        // cantidad_cobros
        $this->cantidad_cobros = new DbField(
            $this, // Table
            'x_cantidad_cobros', // Variable name
            'cantidad_cobros', // Name
            '`cantidad_cobros`', // Expression
            '`cantidad_cobros`', // Basic search expression
            20, // Type
            21, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cantidad_cobros`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cantidad_cobros->addMethod("getDefault", fn() => 0);
        $this->cantidad_cobros->InputTextType = "text";
        $this->cantidad_cobros->Raw = true;
        $this->cantidad_cobros->Nullable = false; // NOT NULL field
        $this->cantidad_cobros->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cantidad_cobros->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['cantidad_cobros'] = &$this->cantidad_cobros;

        // fecha_ultimo_cobro
        $this->fecha_ultimo_cobro = new DbField(
            $this, // Table
            'x_fecha_ultimo_cobro', // Variable name
            'fecha_ultimo_cobro', // Name
            '`fecha_ultimo_cobro`', // Expression
            CastDateFieldForLike("`fecha_ultimo_cobro`", 7, "DB"), // Basic search expression
            133, // Type
            40, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_ultimo_cobro`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_ultimo_cobro->InputTextType = "text";
        $this->fecha_ultimo_cobro->Raw = true;
        $this->fecha_ultimo_cobro->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_ultimo_cobro->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_ultimo_cobro'] = &$this->fecha_ultimo_cobro;

        // saldo_bs
        $this->saldo_bs = new DbField(
            $this, // Table
            'x_saldo_bs', // Variable name
            'saldo_bs', // Name
            '`saldo_bs`', // Expression
            '`saldo_bs`', // Basic search expression
            131, // Type
            63, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`saldo_bs`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->saldo_bs->addMethod("getDefault", fn() => 0.00);
        $this->saldo_bs->InputTextType = "text";
        $this->saldo_bs->Raw = true;
        $this->saldo_bs->Nullable = false; // NOT NULL field
        $this->saldo_bs->FormatPattern = "#,##0.00"; // Format pattern
        $this->saldo_bs->DefaultNumberFormat = $this->saldo_bs->FormatPattern;
        $this->saldo_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->saldo_bs->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['saldo_bs'] = &$this->saldo_bs;

        // saldo_usd
        $this->saldo_usd = new DbField(
            $this, // Table
            'x_saldo_usd', // Variable name
            'saldo_usd', // Name
            '`saldo_usd`', // Expression
            '`saldo_usd`', // Basic search expression
            131, // Type
            64, // Size
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

        // estado_cuenta
        $this->estado_cuenta = new DbField(
            $this, // Table
            'x_estado_cuenta', // Variable name
            'estado_cuenta', // Name
            '`estado_cuenta`', // Expression
            '`estado_cuenta`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`estado_cuenta`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->estado_cuenta->InputTextType = "text";
        $this->estado_cuenta->Nullable = false; // NOT NULL field
        $this->estado_cuenta->Required = true; // Required field
        $this->estado_cuenta->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['estado_cuenta'] = &$this->estado_cuenta;

        // dias_vencido
        $this->dias_vencido = new DbField(
            $this, // Table
            'x_dias_vencido', // Variable name
            'dias_vencido', // Name
            '`dias_vencido`', // Expression
            '`dias_vencido`', // Basic search expression
            3, // Type
            9, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`dias_vencido`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->dias_vencido->InputTextType = "text";
        $this->dias_vencido->Raw = true;
        $this->dias_vencido->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_vencido->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_vencido'] = &$this->dias_vencido;

        // antiguedad
        $this->antiguedad = new DbField(
            $this, // Table
            'x_antiguedad', // Variable name
            'antiguedad', // Name
            '`antiguedad`', // Expression
            '`antiguedad`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`antiguedad`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->antiguedad->InputTextType = "text";
        $this->antiguedad->Nullable = false; // NOT NULL field
        $this->antiguedad->Required = true; // Required field
        $this->antiguedad->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['antiguedad'] = &$this->antiguedad;

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
        if ($this->getCurrentMasterTable() == "view_cuentas_por_cobrar_resumen") {
            $masterTable = Container("view_cuentas_por_cobrar_resumen");
            if ($this->cliente->getSessionValue() != "") {
                $masterFilter .= "" . GetKeyFilter($masterTable->cliente, $this->cliente->getSessionValue(), $masterTable->cliente->DataType, $masterTable->Dbid);
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
        if ($this->getCurrentMasterTable() == "view_cuentas_por_cobrar_resumen") {
            $masterTable = Container("view_cuentas_por_cobrar_resumen");
            if ($this->cliente->getSessionValue() != "") {
                $detailFilter .= "" . GetKeyFilter($this->cliente, $this->cliente->getSessionValue(), $masterTable->cliente->DataType, $this->Dbid);
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
            case "view_cuentas_por_cobrar_resumen":
                $key = $keys["cliente"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->cliente->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return GetKeyFilter($masterTable->cliente, $keys["cliente"], $this->cliente->DataType, $this->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "view_cuentas_por_cobrar_resumen":
                return GetKeyFilter($this->cliente, $masterTable->cliente->DbValue, $masterTable->cliente->DataType, $masterTable->Dbid);
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_cuentas_por_cobrar";
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
        $this->cliente->DbValue = $row['cliente'];
        $this->cliente_rif->DbValue = $row['cliente_rif'];
        $this->cliente_nombre->DbValue = $row['cliente_nombre'];
        $this->tipo_documento_fiscal->DbValue = $row['tipo_documento_fiscal'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->fecha_documento->DbValue = $row['fecha_documento'];
        $this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
        $this->moneda->DbValue = $row['moneda'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->entregado->DbValue = $row['entregado'];
        $this->pagado->DbValue = $row['pagado'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->doc_afe->DbValue = $row['doc_afe'];
        $this->igtf->DbValue = $row['igtf'];
        $this->monto_igtf_bs->DbValue = $row['monto_igtf_bs'];
        $this->signo_documento->DbValue = $row['signo_documento'];
        $this->monto_documento_moneda->DbValue = $row['monto_documento_moneda'];
        $this->monto_documento_bs->DbValue = $row['monto_documento_bs'];
        $this->monto_documento_usd->DbValue = $row['monto_documento_usd'];
        $this->monto_aplicado_bs->DbValue = $row['monto_aplicado_bs'];
        $this->monto_aplicado_usd->DbValue = $row['monto_aplicado_usd'];
        $this->total_cobrado_bs->DbValue = $row['total_cobrado_bs'];
        $this->total_cobrado_usd->DbValue = $row['total_cobrado_usd'];
        $this->cantidad_cobros->DbValue = $row['cantidad_cobros'];
        $this->fecha_ultimo_cobro->DbValue = $row['fecha_ultimo_cobro'];
        $this->saldo_bs->DbValue = $row['saldo_bs'];
        $this->saldo_usd->DbValue = $row['saldo_usd'];
        $this->estado_cuenta->DbValue = $row['estado_cuenta'];
        $this->dias_vencido->DbValue = $row['dias_vencido'];
        $this->antiguedad->DbValue = $row['antiguedad'];
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
        return $_SESSION[$name] ?? GetUrl("ViewCuentasPorCobrarList");
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
            "ViewCuentasPorCobrarView" => $Language->phrase("View"),
            "ViewCuentasPorCobrarEdit" => $Language->phrase("Edit"),
            "ViewCuentasPorCobrarAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewCuentasPorCobrarList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewCuentasPorCobrarView",
            Config("API_ADD_ACTION") => "ViewCuentasPorCobrarAdd",
            Config("API_EDIT_ACTION") => "ViewCuentasPorCobrarEdit",
            Config("API_DELETE_ACTION") => "ViewCuentasPorCobrarDelete",
            Config("API_LIST_ACTION") => "ViewCuentasPorCobrarList",
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
        return "ViewCuentasPorCobrarList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewCuentasPorCobrarView", $parm);
        } else {
            $url = $this->keyUrl("ViewCuentasPorCobrarView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewCuentasPorCobrarAdd?" . $parm;
        } else {
            $url = "ViewCuentasPorCobrarAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarEdit", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarAdd", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewCuentasPorCobrarList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewCuentasPorCobrarDelete", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "view_cuentas_por_cobrar_resumen" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_cliente", $this->cliente->getSessionValue()); // Use Session Value
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
        $this->cliente->setDbValue($row['cliente']);
        $this->cliente_rif->setDbValue($row['cliente_rif']);
        $this->cliente_nombre->setDbValue($row['cliente_nombre']);
        $this->tipo_documento_fiscal->setDbValue($row['tipo_documento_fiscal']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->fecha_documento->setDbValue($row['fecha_documento']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->pagado->setDbValue($row['pagado']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->doc_afe->setDbValue($row['doc_afe']);
        $this->igtf->setDbValue($row['igtf']);
        $this->monto_igtf_bs->setDbValue($row['monto_igtf_bs']);
        $this->signo_documento->setDbValue($row['signo_documento']);
        $this->monto_documento_moneda->setDbValue($row['monto_documento_moneda']);
        $this->monto_documento_bs->setDbValue($row['monto_documento_bs']);
        $this->monto_documento_usd->setDbValue($row['monto_documento_usd']);
        $this->monto_aplicado_bs->setDbValue($row['monto_aplicado_bs']);
        $this->monto_aplicado_usd->setDbValue($row['monto_aplicado_usd']);
        $this->total_cobrado_bs->setDbValue($row['total_cobrado_bs']);
        $this->total_cobrado_usd->setDbValue($row['total_cobrado_usd']);
        $this->cantidad_cobros->setDbValue($row['cantidad_cobros']);
        $this->fecha_ultimo_cobro->setDbValue($row['fecha_ultimo_cobro']);
        $this->saldo_bs->setDbValue($row['saldo_bs']);
        $this->saldo_usd->setDbValue($row['saldo_usd']);
        $this->estado_cuenta->setDbValue($row['estado_cuenta']);
        $this->dias_vencido->setDbValue($row['dias_vencido']);
        $this->antiguedad->setDbValue($row['antiguedad']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewCuentasPorCobrarList";
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

        // cliente_rif

        // cliente_nombre

        // tipo_documento_fiscal

        // nro_documento

        // nro_control

        // fecha

        // fecha_documento

        // fecha_vencimiento

        // moneda

        // tasa_dia

        // dias_credito

        // entregado

        // pagado

        // doc_afectado

        // doc_afe

        // igtf

        // monto_igtf_bs

        // signo_documento

        // monto_documento_moneda

        // monto_documento_bs

        // monto_documento_usd

        // monto_aplicado_bs

        // monto_aplicado_usd

        // total_cobrado_bs

        // total_cobrado_usd

        // cantidad_cobros

        // fecha_ultimo_cobro

        // saldo_bs

        // saldo_usd

        // estado_cuenta

        // dias_vencido

        // antiguedad

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

        // cliente_rif
        $this->cliente_rif->ViewValue = $this->cliente_rif->CurrentValue;

        // cliente_nombre
        $this->cliente_nombre->ViewValue = $this->cliente_nombre->CurrentValue;

        // tipo_documento_fiscal
        if (strval($this->tipo_documento_fiscal->CurrentValue) != "") {
            $this->tipo_documento_fiscal->ViewValue = $this->tipo_documento_fiscal->optionCaption($this->tipo_documento_fiscal->CurrentValue);
        } else {
            $this->tipo_documento_fiscal->ViewValue = null;
        }

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

        // nro_control
        $this->nro_control->ViewValue = $this->nro_control->CurrentValue;

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

        // fecha_documento
        $this->fecha_documento->ViewValue = $this->fecha_documento->CurrentValue;
        $this->fecha_documento->ViewValue = FormatDateTime($this->fecha_documento->ViewValue, $this->fecha_documento->formatPattern());

        // fecha_vencimiento
        $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
        $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, $this->fecha_vencimiento->formatPattern());

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;

        // tasa_dia
        $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());
        $this->tasa_dia->CssClass = "fw-bold";
        $this->tasa_dia->CellCssStyle .= "text-align: right;";

        // dias_credito
        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->ViewValue, $this->dias_credito->formatPattern());

        // entregado
        if (strval($this->entregado->CurrentValue) != "") {
            $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
        } else {
            $this->entregado->ViewValue = null;
        }

        // pagado
        if (strval($this->pagado->CurrentValue) != "") {
            $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
        } else {
            $this->pagado->ViewValue = null;
        }

        // doc_afectado
        $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;

        // doc_afe
        $this->doc_afe->ViewValue = $this->doc_afe->CurrentValue;
        $this->doc_afe->ViewValue = FormatNumber($this->doc_afe->ViewValue, $this->doc_afe->formatPattern());

        // igtf
        if (strval($this->igtf->CurrentValue) != "") {
            $this->igtf->ViewValue = $this->igtf->optionCaption($this->igtf->CurrentValue);
        } else {
            $this->igtf->ViewValue = null;
        }

        // monto_igtf_bs
        $this->monto_igtf_bs->ViewValue = $this->monto_igtf_bs->CurrentValue;
        $this->monto_igtf_bs->ViewValue = FormatNumber($this->monto_igtf_bs->ViewValue, $this->monto_igtf_bs->formatPattern());
        $this->monto_igtf_bs->CssClass = "fw-bold";
        $this->monto_igtf_bs->CellCssStyle .= "text-align: right;";

        // signo_documento
        $this->signo_documento->ViewValue = $this->signo_documento->CurrentValue;
        $this->signo_documento->ViewValue = FormatNumber($this->signo_documento->ViewValue, $this->signo_documento->formatPattern());

        // monto_documento_moneda
        $this->monto_documento_moneda->ViewValue = $this->monto_documento_moneda->CurrentValue;
        $this->monto_documento_moneda->ViewValue = FormatNumber($this->monto_documento_moneda->ViewValue, $this->monto_documento_moneda->formatPattern());
        $this->monto_documento_moneda->CssClass = "fw-bold";
        $this->monto_documento_moneda->CellCssStyle .= "text-align: right;";

        // monto_documento_bs
        $this->monto_documento_bs->ViewValue = $this->monto_documento_bs->CurrentValue;
        $this->monto_documento_bs->ViewValue = FormatNumber($this->monto_documento_bs->ViewValue, $this->monto_documento_bs->formatPattern());
        $this->monto_documento_bs->CssClass = "fw-bold";
        $this->monto_documento_bs->CellCssStyle .= "text-align: right;";

        // monto_documento_usd
        $this->monto_documento_usd->ViewValue = $this->monto_documento_usd->CurrentValue;
        $this->monto_documento_usd->ViewValue = FormatNumber($this->monto_documento_usd->ViewValue, $this->monto_documento_usd->formatPattern());
        $this->monto_documento_usd->CssClass = "fw-bold";
        $this->monto_documento_usd->CellCssStyle .= "text-align: right;";

        // monto_aplicado_bs
        $this->monto_aplicado_bs->ViewValue = $this->monto_aplicado_bs->CurrentValue;
        $this->monto_aplicado_bs->ViewValue = FormatNumber($this->monto_aplicado_bs->ViewValue, $this->monto_aplicado_bs->formatPattern());
        $this->monto_aplicado_bs->CssClass = "fw-bold";
        $this->monto_aplicado_bs->CellCssStyle .= "text-align: right;";

        // monto_aplicado_usd
        $this->monto_aplicado_usd->ViewValue = $this->monto_aplicado_usd->CurrentValue;
        $this->monto_aplicado_usd->ViewValue = FormatNumber($this->monto_aplicado_usd->ViewValue, $this->monto_aplicado_usd->formatPattern());
        $this->monto_aplicado_usd->CssClass = "fw-bold";
        $this->monto_aplicado_usd->CellCssStyle .= "text-align: right;";

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

        // cantidad_cobros
        $this->cantidad_cobros->ViewValue = $this->cantidad_cobros->CurrentValue;
        $this->cantidad_cobros->ViewValue = FormatNumber($this->cantidad_cobros->ViewValue, $this->cantidad_cobros->formatPattern());

        // fecha_ultimo_cobro
        $this->fecha_ultimo_cobro->ViewValue = $this->fecha_ultimo_cobro->CurrentValue;
        $this->fecha_ultimo_cobro->ViewValue = FormatDateTime($this->fecha_ultimo_cobro->ViewValue, $this->fecha_ultimo_cobro->formatPattern());

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

        // estado_cuenta
        $this->estado_cuenta->ViewValue = $this->estado_cuenta->CurrentValue;

        // dias_vencido
        $this->dias_vencido->ViewValue = $this->dias_vencido->CurrentValue;
        $this->dias_vencido->ViewValue = FormatNumber($this->dias_vencido->ViewValue, $this->dias_vencido->formatPattern());

        // antiguedad
        $this->antiguedad->ViewValue = $this->antiguedad->CurrentValue;

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // cliente_rif
        $this->cliente_rif->HrefValue = "";
        $this->cliente_rif->TooltipValue = "";

        // cliente_nombre
        $this->cliente_nombre->HrefValue = "";
        $this->cliente_nombre->TooltipValue = "";

        // tipo_documento_fiscal
        $this->tipo_documento_fiscal->HrefValue = "";
        $this->tipo_documento_fiscal->TooltipValue = "";

        // nro_documento
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // nro_control
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // fecha_documento
        $this->fecha_documento->HrefValue = "";
        $this->fecha_documento->TooltipValue = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->HrefValue = "";
        $this->fecha_vencimiento->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // tasa_dia
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // dias_credito
        $this->dias_credito->HrefValue = "";
        $this->dias_credito->TooltipValue = "";

        // entregado
        $this->entregado->HrefValue = "";
        $this->entregado->TooltipValue = "";

        // pagado
        $this->pagado->HrefValue = "";
        $this->pagado->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // doc_afe
        $this->doc_afe->HrefValue = "";
        $this->doc_afe->TooltipValue = "";

        // igtf
        $this->igtf->HrefValue = "";
        $this->igtf->TooltipValue = "";

        // monto_igtf_bs
        $this->monto_igtf_bs->HrefValue = "";
        $this->monto_igtf_bs->TooltipValue = "";

        // signo_documento
        $this->signo_documento->HrefValue = "";
        $this->signo_documento->TooltipValue = "";

        // monto_documento_moneda
        $this->monto_documento_moneda->HrefValue = "";
        $this->monto_documento_moneda->TooltipValue = "";

        // monto_documento_bs
        $this->monto_documento_bs->HrefValue = "";
        $this->monto_documento_bs->TooltipValue = "";

        // monto_documento_usd
        $this->monto_documento_usd->HrefValue = "";
        $this->monto_documento_usd->TooltipValue = "";

        // monto_aplicado_bs
        $this->monto_aplicado_bs->HrefValue = "";
        $this->monto_aplicado_bs->TooltipValue = "";

        // monto_aplicado_usd
        $this->monto_aplicado_usd->HrefValue = "";
        $this->monto_aplicado_usd->TooltipValue = "";

        // total_cobrado_bs
        $this->total_cobrado_bs->HrefValue = "";
        $this->total_cobrado_bs->TooltipValue = "";

        // total_cobrado_usd
        $this->total_cobrado_usd->HrefValue = "";
        $this->total_cobrado_usd->TooltipValue = "";

        // cantidad_cobros
        $this->cantidad_cobros->HrefValue = "";
        $this->cantidad_cobros->TooltipValue = "";

        // fecha_ultimo_cobro
        $this->fecha_ultimo_cobro->HrefValue = "";
        $this->fecha_ultimo_cobro->TooltipValue = "";

        // saldo_bs
        $this->saldo_bs->HrefValue = "";
        $this->saldo_bs->TooltipValue = "";

        // saldo_usd
        $this->saldo_usd->HrefValue = "";
        $this->saldo_usd->TooltipValue = "";

        // estado_cuenta
        $this->estado_cuenta->HrefValue = "";
        $this->estado_cuenta->TooltipValue = "";

        // dias_vencido
        $this->dias_vencido->HrefValue = "";
        $this->dias_vencido->TooltipValue = "";

        // antiguedad
        $this->antiguedad->HrefValue = "";
        $this->antiguedad->TooltipValue = "";

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

        // cliente
        $this->cliente->setupEditAttributes();
        if ($this->cliente->getSessionValue() != "") {
            $this->cliente->CurrentValue = GetForeignKeyValue($this->cliente->getSessionValue());
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
        } else {
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());
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

        // tipo_documento_fiscal
        $this->tipo_documento_fiscal->setupEditAttributes();
        $this->tipo_documento_fiscal->EditValue = $this->tipo_documento_fiscal->options(true);
        $this->tipo_documento_fiscal->PlaceHolder = RemoveHtml($this->tipo_documento_fiscal->caption());

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

        // fecha_documento
        $this->fecha_documento->setupEditAttributes();
        $this->fecha_documento->EditValue = FormatDateTime($this->fecha_documento->CurrentValue, $this->fecha_documento->formatPattern());
        $this->fecha_documento->PlaceHolder = RemoveHtml($this->fecha_documento->caption());

        // fecha_vencimiento
        $this->fecha_vencimiento->setupEditAttributes();
        $this->fecha_vencimiento->EditValue = FormatDateTime($this->fecha_vencimiento->CurrentValue, $this->fecha_vencimiento->formatPattern());
        $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

        // moneda
        $this->moneda->setupEditAttributes();
        if (!$this->moneda->Raw) {
            $this->moneda->CurrentValue = HtmlDecode($this->moneda->CurrentValue);
        }
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // tasa_dia
        $this->tasa_dia->setupEditAttributes();
        $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->PlaceHolder = RemoveHtml($this->tasa_dia->caption());
        if (strval($this->tasa_dia->EditValue) != "" && is_numeric($this->tasa_dia->EditValue)) {
            $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, null);
        }

        // dias_credito
        $this->dias_credito->setupEditAttributes();
        $this->dias_credito->EditValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());
        if (strval($this->dias_credito->EditValue) != "" && is_numeric($this->dias_credito->EditValue)) {
            $this->dias_credito->EditValue = FormatNumber($this->dias_credito->EditValue, null);
        }

        // entregado
        $this->entregado->EditValue = $this->entregado->options(false);
        $this->entregado->PlaceHolder = RemoveHtml($this->entregado->caption());

        // pagado
        $this->pagado->EditValue = $this->pagado->options(false);
        $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

        // doc_afectado
        $this->doc_afectado->setupEditAttributes();
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // doc_afe
        $this->doc_afe->setupEditAttributes();
        $this->doc_afe->EditValue = $this->doc_afe->CurrentValue;
        $this->doc_afe->PlaceHolder = RemoveHtml($this->doc_afe->caption());
        if (strval($this->doc_afe->EditValue) != "" && is_numeric($this->doc_afe->EditValue)) {
            $this->doc_afe->EditValue = FormatNumber($this->doc_afe->EditValue, null);
        }

        // igtf
        $this->igtf->EditValue = $this->igtf->options(false);
        $this->igtf->PlaceHolder = RemoveHtml($this->igtf->caption());

        // monto_igtf_bs
        $this->monto_igtf_bs->setupEditAttributes();
        $this->monto_igtf_bs->EditValue = $this->monto_igtf_bs->CurrentValue;
        $this->monto_igtf_bs->PlaceHolder = RemoveHtml($this->monto_igtf_bs->caption());
        if (strval($this->monto_igtf_bs->EditValue) != "" && is_numeric($this->monto_igtf_bs->EditValue)) {
            $this->monto_igtf_bs->EditValue = FormatNumber($this->monto_igtf_bs->EditValue, null);
        }

        // signo_documento
        $this->signo_documento->setupEditAttributes();
        $this->signo_documento->EditValue = $this->signo_documento->CurrentValue;
        $this->signo_documento->PlaceHolder = RemoveHtml($this->signo_documento->caption());
        if (strval($this->signo_documento->EditValue) != "" && is_numeric($this->signo_documento->EditValue)) {
            $this->signo_documento->EditValue = FormatNumber($this->signo_documento->EditValue, null);
        }

        // monto_documento_moneda
        $this->monto_documento_moneda->setupEditAttributes();
        $this->monto_documento_moneda->EditValue = $this->monto_documento_moneda->CurrentValue;
        $this->monto_documento_moneda->PlaceHolder = RemoveHtml($this->monto_documento_moneda->caption());
        if (strval($this->monto_documento_moneda->EditValue) != "" && is_numeric($this->monto_documento_moneda->EditValue)) {
            $this->monto_documento_moneda->EditValue = FormatNumber($this->monto_documento_moneda->EditValue, null);
        }

        // monto_documento_bs
        $this->monto_documento_bs->setupEditAttributes();
        $this->monto_documento_bs->EditValue = $this->monto_documento_bs->CurrentValue;
        $this->monto_documento_bs->PlaceHolder = RemoveHtml($this->monto_documento_bs->caption());
        if (strval($this->monto_documento_bs->EditValue) != "" && is_numeric($this->monto_documento_bs->EditValue)) {
            $this->monto_documento_bs->EditValue = FormatNumber($this->monto_documento_bs->EditValue, null);
        }

        // monto_documento_usd
        $this->monto_documento_usd->setupEditAttributes();
        $this->monto_documento_usd->EditValue = $this->monto_documento_usd->CurrentValue;
        $this->monto_documento_usd->PlaceHolder = RemoveHtml($this->monto_documento_usd->caption());
        if (strval($this->monto_documento_usd->EditValue) != "" && is_numeric($this->monto_documento_usd->EditValue)) {
            $this->monto_documento_usd->EditValue = FormatNumber($this->monto_documento_usd->EditValue, null);
        }

        // monto_aplicado_bs
        $this->monto_aplicado_bs->setupEditAttributes();
        $this->monto_aplicado_bs->EditValue = $this->monto_aplicado_bs->CurrentValue;
        $this->monto_aplicado_bs->PlaceHolder = RemoveHtml($this->monto_aplicado_bs->caption());
        if (strval($this->monto_aplicado_bs->EditValue) != "" && is_numeric($this->monto_aplicado_bs->EditValue)) {
            $this->monto_aplicado_bs->EditValue = FormatNumber($this->monto_aplicado_bs->EditValue, null);
        }

        // monto_aplicado_usd
        $this->monto_aplicado_usd->setupEditAttributes();
        $this->monto_aplicado_usd->EditValue = $this->monto_aplicado_usd->CurrentValue;
        $this->monto_aplicado_usd->PlaceHolder = RemoveHtml($this->monto_aplicado_usd->caption());
        if (strval($this->monto_aplicado_usd->EditValue) != "" && is_numeric($this->monto_aplicado_usd->EditValue)) {
            $this->monto_aplicado_usd->EditValue = FormatNumber($this->monto_aplicado_usd->EditValue, null);
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

        // cantidad_cobros
        $this->cantidad_cobros->setupEditAttributes();
        $this->cantidad_cobros->EditValue = $this->cantidad_cobros->CurrentValue;
        $this->cantidad_cobros->PlaceHolder = RemoveHtml($this->cantidad_cobros->caption());
        if (strval($this->cantidad_cobros->EditValue) != "" && is_numeric($this->cantidad_cobros->EditValue)) {
            $this->cantidad_cobros->EditValue = FormatNumber($this->cantidad_cobros->EditValue, null);
        }

        // fecha_ultimo_cobro
        $this->fecha_ultimo_cobro->setupEditAttributes();
        $this->fecha_ultimo_cobro->EditValue = FormatDateTime($this->fecha_ultimo_cobro->CurrentValue, $this->fecha_ultimo_cobro->formatPattern());
        $this->fecha_ultimo_cobro->PlaceHolder = RemoveHtml($this->fecha_ultimo_cobro->caption());

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

        // estado_cuenta
        $this->estado_cuenta->setupEditAttributes();
        if (!$this->estado_cuenta->Raw) {
            $this->estado_cuenta->CurrentValue = HtmlDecode($this->estado_cuenta->CurrentValue);
        }
        $this->estado_cuenta->EditValue = $this->estado_cuenta->CurrentValue;
        $this->estado_cuenta->PlaceHolder = RemoveHtml($this->estado_cuenta->caption());

        // dias_vencido
        $this->dias_vencido->setupEditAttributes();
        $this->dias_vencido->EditValue = $this->dias_vencido->CurrentValue;
        $this->dias_vencido->PlaceHolder = RemoveHtml($this->dias_vencido->caption());
        if (strval($this->dias_vencido->EditValue) != "" && is_numeric($this->dias_vencido->EditValue)) {
            $this->dias_vencido->EditValue = FormatNumber($this->dias_vencido->EditValue, null);
        }

        // antiguedad
        $this->antiguedad->setupEditAttributes();
        if (!$this->antiguedad->Raw) {
            $this->antiguedad->CurrentValue = HtmlDecode($this->antiguedad->CurrentValue);
        }
        $this->antiguedad->EditValue = $this->antiguedad->CurrentValue;
        $this->antiguedad->PlaceHolder = RemoveHtml($this->antiguedad->caption());

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
                    $doc->exportCaption($this->tipo_documento_fiscal);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->fecha_documento);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->doc_afe);
                    $doc->exportCaption($this->igtf);
                    $doc->exportCaption($this->monto_igtf_bs);
                    $doc->exportCaption($this->signo_documento);
                    $doc->exportCaption($this->monto_documento_moneda);
                    $doc->exportCaption($this->monto_documento_bs);
                    $doc->exportCaption($this->monto_documento_usd);
                    $doc->exportCaption($this->monto_aplicado_bs);
                    $doc->exportCaption($this->monto_aplicado_usd);
                    $doc->exportCaption($this->total_cobrado_bs);
                    $doc->exportCaption($this->total_cobrado_usd);
                    $doc->exportCaption($this->cantidad_cobros);
                    $doc->exportCaption($this->fecha_ultimo_cobro);
                    $doc->exportCaption($this->saldo_bs);
                    $doc->exportCaption($this->saldo_usd);
                    $doc->exportCaption($this->estado_cuenta);
                    $doc->exportCaption($this->dias_vencido);
                    $doc->exportCaption($this->antiguedad);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->cliente_rif);
                    $doc->exportCaption($this->cliente_nombre);
                    $doc->exportCaption($this->tipo_documento_fiscal);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->fecha_documento);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->doc_afe);
                    $doc->exportCaption($this->igtf);
                    $doc->exportCaption($this->monto_igtf_bs);
                    $doc->exportCaption($this->signo_documento);
                    $doc->exportCaption($this->monto_documento_moneda);
                    $doc->exportCaption($this->monto_documento_bs);
                    $doc->exportCaption($this->monto_documento_usd);
                    $doc->exportCaption($this->monto_aplicado_bs);
                    $doc->exportCaption($this->monto_aplicado_usd);
                    $doc->exportCaption($this->total_cobrado_bs);
                    $doc->exportCaption($this->total_cobrado_usd);
                    $doc->exportCaption($this->cantidad_cobros);
                    $doc->exportCaption($this->fecha_ultimo_cobro);
                    $doc->exportCaption($this->saldo_bs);
                    $doc->exportCaption($this->saldo_usd);
                    $doc->exportCaption($this->estado_cuenta);
                    $doc->exportCaption($this->dias_vencido);
                    $doc->exportCaption($this->antiguedad);
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
                        $doc->exportField($this->tipo_documento_fiscal);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->fecha_documento);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->doc_afe);
                        $doc->exportField($this->igtf);
                        $doc->exportField($this->monto_igtf_bs);
                        $doc->exportField($this->signo_documento);
                        $doc->exportField($this->monto_documento_moneda);
                        $doc->exportField($this->monto_documento_bs);
                        $doc->exportField($this->monto_documento_usd);
                        $doc->exportField($this->monto_aplicado_bs);
                        $doc->exportField($this->monto_aplicado_usd);
                        $doc->exportField($this->total_cobrado_bs);
                        $doc->exportField($this->total_cobrado_usd);
                        $doc->exportField($this->cantidad_cobros);
                        $doc->exportField($this->fecha_ultimo_cobro);
                        $doc->exportField($this->saldo_bs);
                        $doc->exportField($this->saldo_usd);
                        $doc->exportField($this->estado_cuenta);
                        $doc->exportField($this->dias_vencido);
                        $doc->exportField($this->antiguedad);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->cliente_rif);
                        $doc->exportField($this->cliente_nombre);
                        $doc->exportField($this->tipo_documento_fiscal);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->fecha_documento);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->doc_afe);
                        $doc->exportField($this->igtf);
                        $doc->exportField($this->monto_igtf_bs);
                        $doc->exportField($this->signo_documento);
                        $doc->exportField($this->monto_documento_moneda);
                        $doc->exportField($this->monto_documento_bs);
                        $doc->exportField($this->monto_documento_usd);
                        $doc->exportField($this->monto_aplicado_bs);
                        $doc->exportField($this->monto_aplicado_usd);
                        $doc->exportField($this->total_cobrado_bs);
                        $doc->exportField($this->total_cobrado_usd);
                        $doc->exportField($this->cantidad_cobros);
                        $doc->exportField($this->fecha_ultimo_cobro);
                        $doc->exportField($this->saldo_bs);
                        $doc->exportField($this->saldo_usd);
                        $doc->exportField($this->estado_cuenta);
                        $doc->exportField($this->dias_vencido);
                        $doc->exportField($this->antiguedad);
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
        $antiguedad = strtoupper(trim(
            (string)$this->antiguedad->CurrentValue
        ));
        switch ($antiguedad) {
            case "NO VENCIDO":
                $this->antiguedad->CellAttrs["class"] =
                    "bg-success text-white fw-bold text-center";
                break;
            case "1-30 DÍAS":
                $this->antiguedad->CellAttrs["class"] =
                    "bg-warning text-dark fw-bold text-center";
                break;
            case "31-60 DÍAS":
                $this->antiguedad->CellAttrs["class"] =
                    "bg-orange text-dark fw-bold text-center";
                break;
            case "61-90 DÍAS":
            case "MÁS DE 90 DÍAS":
                $this->antiguedad->CellAttrs["class"] =
                    "bg-danger text-white fw-bold text-center";
                break;
        }
        $estado = strtoupper(trim(
            (string)$this->estado_cuenta->CurrentValue
        ));
        switch ($estado) {
            case "PAGADO":
                $this->estado_cuenta->CellAttrs["class"] =
                    "bg-success text-white fw-bold";
                break;
            case "PAGO PARCIAL":
                $this->estado_cuenta->CellAttrs["class"] =
                    "bg-warning text-dark fw-bold";
                break;
            case "PENDIENTE":
                $this->estado_cuenta->CellAttrs["class"] =
                    "bg-danger text-white fw-bold";
                break;
            case "ANULADO":
                $this->estado_cuenta->CellAttrs["class"] =
                    "bg-secondary text-white fw-bold";
                break;
        }    
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
