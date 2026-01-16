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
 * Table class for view_facturas_cobranza
 */
class ViewFacturasCobranza extends DbTable
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
    public $tipo_documento;
    public $documento;
    public $codcli;
    public $fecha;
    public $nro_documento;
    public $nro_nota;
    public $ciudad;
    public $moneda;
    public $base_imponible;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $monto_pagado;
    public $pendiente;
    public $pendiente2;
    public $pendiente3;
    public $tasa_dia;
    public $monto_usd;
    public $fecha_despacho;
    public $fecha_entrega;
    public $dias_credito;
    public $dias_transcurridos;
    public $dias_vencidos;
    public $pagado;
    public $bultos;
    public $asesor_asignado;
    public $id_documento_padre;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "view_facturas_cobranza";
        $this->TableName = 'view_facturas_cobranza';
        $this->TableType = "VIEW";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "view_facturas_cobranza";
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
            'TEXT' // Edit Tag
        );
        $this->id->InputTextType = "text";
        $this->id->Raw = true;
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
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
        $this->tipo_documento->Lookup = new Lookup($this->tipo_documento, 'tipo_documento', false, 'codigo', ["descripcion","","",""], '', '', [], [], [], [], [], [], false, '', '', "`descripcion`");
        $this->tipo_documento->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // documento
        $this->documento = new DbField(
            $this, // Table
            'x_documento', // Variable name
            'documento', // Name
            '`documento`', // Expression
            '`documento`', // Basic search expression
            129, // Type
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
        $this->documento->Lookup = new Lookup($this->documento, 'view_facturas_cobranza', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->documento->OptionCount = 3;
        $this->documento->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['documento'] = &$this->documento;

        // codcli
        $this->codcli = new DbField(
            $this, // Table
            'x_codcli', // Variable name
            'codcli', // Name
            '`codcli`', // Expression
            '`codcli`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`codcli`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->codcli->InputTextType = "text";
        $this->codcli->Raw = true;
        $this->codcli->setSelectMultiple(false); // Select one
        $this->codcli->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->codcli->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->codcli->Lookup = new Lookup($this->codcli, 'cliente', false, 'id', ["nombre","ci_rif","",""], '', '', [], [], [], [], [], [], false, '`nombre`', '', "CONCAT(COALESCE(`nombre`, ''),'" . ValueSeparator(1, $this->codcli) . "',COALESCE(`ci_rif`,''))");
        $this->codcli->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->codcli->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['codcli'] = &$this->codcli;

        // fecha
        $this->fecha = new DbField(
            $this, // Table
            'x_fecha', // Variable name
            'fecha', // Name
            '`fecha`', // Expression
            CastDateFieldForLike("`fecha`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
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

        // nro_nota
        $this->nro_nota = new DbField(
            $this, // Table
            'x_nro_nota', // Variable name
            'nro_nota', // Name
            '`nro_nota`', // Expression
            '`nro_nota`', // Basic search expression
            200, // Type
            20, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`nro_nota`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->nro_nota->InputTextType = "text";
        $this->nro_nota->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['nro_nota'] = &$this->nro_nota;

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
        $this->ciudad->InputTextType = "text";
        $this->ciudad->Lookup = new Lookup($this->ciudad, 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], '', '', [], [], [], [], [], [], false, '`campo_descripcion`', '', "`campo_descripcion`");
        $this->ciudad->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['ciudad'] = &$this->ciudad;

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

        // alicuota_iva
        $this->alicuota_iva = new DbField(
            $this, // Table
            'x_alicuota_iva', // Variable name
            'alicuota_iva', // Name
            '`alicuota_iva`', // Expression
            '`alicuota_iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`alicuota_iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->alicuota_iva->InputTextType = "text";
        $this->alicuota_iva->Raw = true;
        $this->alicuota_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota_iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['alicuota_iva'] = &$this->alicuota_iva;

        // iva
        $this->iva = new DbField(
            $this, // Table
            'x_iva', // Variable name
            'iva', // Name
            '`iva`', // Expression
            '`iva`', // Basic search expression
            131, // Type
            16, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`iva`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->iva->InputTextType = "text";
        $this->iva->Raw = true;
        $this->iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->iva->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['iva'] = &$this->iva;

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

        // monto_pagado
        $this->monto_pagado = new DbField(
            $this, // Table
            'x_monto_pagado', // Variable name
            'monto_pagado', // Name
            '`monto_pagado`', // Expression
            '`monto_pagado`', // Basic search expression
            131, // Type
            52, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`monto_pagado`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->monto_pagado->InputTextType = "text";
        $this->monto_pagado->Raw = true;
        $this->monto_pagado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagado->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['monto_pagado'] = &$this->monto_pagado;

        // pendiente
        $this->pendiente = new DbField(
            $this, // Table
            'x_pendiente', // Variable name
            'pendiente', // Name
            '`pendiente`', // Expression
            '`pendiente`', // Basic search expression
            131, // Type
            53, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`pendiente`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->pendiente->InputTextType = "text";
        $this->pendiente->Raw = true;
        $this->pendiente->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pendiente->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['pendiente'] = &$this->pendiente;

        // pendiente2
        $this->pendiente2 = new DbField(
            $this, // Table
            'x_pendiente2', // Variable name
            'pendiente2', // Name
            '`pendiente2`', // Expression
            '`pendiente2`', // Basic search expression
            131, // Type
            59, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`pendiente2`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->pendiente2->InputTextType = "text";
        $this->pendiente2->Raw = true;
        $this->pendiente2->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pendiente2->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['pendiente2'] = &$this->pendiente2;

        // pendiente3
        $this->pendiente3 = new DbField(
            $this, // Table
            'x_pendiente3', // Variable name
            'pendiente3', // Name
            '`pendiente3`', // Expression
            '`pendiente3`', // Basic search expression
            131, // Type
            67, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`pendiente3`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->pendiente3->InputTextType = "text";
        $this->pendiente3->Raw = true;
        $this->pendiente3->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pendiente3->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['pendiente3'] = &$this->pendiente3;

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
        $this->tasa_dia->InputTextType = "text";
        $this->tasa_dia->Raw = true;
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // monto_usd
        $this->monto_usd = new DbField(
            $this, // Table
            'x_monto_usd', // Variable name
            'monto_usd', // Name
            '`monto_usd`', // Expression
            '`monto_usd`', // Basic search expression
            131, // Type
            16, // Size
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

        // fecha_despacho
        $this->fecha_despacho = new DbField(
            $this, // Table
            'x_fecha_despacho', // Variable name
            'fecha_despacho', // Name
            '`fecha_despacho`', // Expression
            CastDateFieldForLike("`fecha_despacho`", 7, "DB"), // Basic search expression
            135, // Type
            19, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_despacho`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_despacho->InputTextType = "text";
        $this->fecha_despacho->Raw = true;
        $this->fecha_despacho->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_despacho->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_despacho'] = &$this->fecha_despacho;

        // fecha_entrega
        $this->fecha_entrega = new DbField(
            $this, // Table
            'x_fecha_entrega', // Variable name
            'fecha_entrega', // Name
            '`fecha_entrega`', // Expression
            CastDateFieldForLike("`fecha_entrega`", 7, "DB"), // Basic search expression
            133, // Type
            10, // Size
            7, // Date/Time format
            false, // Is upload field
            '`fecha_entrega`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->fecha_entrega->InputTextType = "text";
        $this->fecha_entrega->Raw = true;
        $this->fecha_entrega->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->fecha_entrega->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['fecha_entrega'] = &$this->fecha_entrega;

        // dias_credito
        $this->dias_credito = new DbField(
            $this, // Table
            'x_dias_credito', // Variable name
            'dias_credito', // Name
            '`dias_credito`', // Expression
            '`dias_credito`', // Basic search expression
            16, // Type
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
        $this->dias_credito->InputTextType = "text";
        $this->dias_credito->Raw = true;
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_credito'] = &$this->dias_credito;

        // dias_transcurridos
        $this->dias_transcurridos = new DbField(
            $this, // Table
            'x_dias_transcurridos', // Variable name
            'dias_transcurridos', // Name
            '`dias_transcurridos`', // Expression
            '`dias_transcurridos`', // Basic search expression
            20, // Type
            21, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`dias_transcurridos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->dias_transcurridos->InputTextType = "text";
        $this->dias_transcurridos->Raw = true;
        $this->dias_transcurridos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_transcurridos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_transcurridos'] = &$this->dias_transcurridos;

        // dias_vencidos
        $this->dias_vencidos = new DbField(
            $this, // Table
            'x_dias_vencidos', // Variable name
            'dias_vencidos', // Name
            '`dias_vencidos`', // Expression
            '`dias_vencidos`', // Basic search expression
            20, // Type
            22, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`dias_vencidos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->dias_vencidos->InputTextType = "text";
        $this->dias_vencidos->Raw = true;
        $this->dias_vencidos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_vencidos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['dias_vencidos'] = &$this->dias_vencidos;

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
        $this->pagado->Lookup = new Lookup($this->pagado, 'view_facturas_cobranza', false, '', ["","","",""], '', '', [], [], [], [], [], [], false, '', '', "");
        $this->pagado->OptionCount = 2;
        $this->pagado->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['pagado'] = &$this->pagado;

        // bultos
        $this->bultos = new DbField(
            $this, // Table
            'x_bultos', // Variable name
            'bultos', // Name
            '`bultos`', // Expression
            '`bultos`', // Basic search expression
            3, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`bultos`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->bultos->InputTextType = "text";
        $this->bultos->Raw = true;
        $this->bultos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->bultos->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['bultos'] = &$this->bultos;

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
        $this->asesor_asignado->Lookup = new Lookup($this->asesor_asignado, 'usuario', false, 'username', ["nombre","","",""], '', '', [], [], [], [], [], [], false, '`nombre` ASC', '', "`nombre`");
        $this->asesor_asignado->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['asesor_asignado'] = &$this->asesor_asignado;

        // id_documento_padre
        $this->id_documento_padre = new DbField(
            $this, // Table
            'x_id_documento_padre', // Variable name
            'id_documento_padre', // Name
            '`id_documento_padre`', // Expression
            '`id_documento_padre`', // Basic search expression
            19, // Type
            10, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id_documento_padre`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->id_documento_padre->InputTextType = "text";
        $this->id_documento_padre->Raw = true;
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

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
        if ($this->getCurrentDetailTable() == "pagos") {
            $detailUrl = Container("pagos")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ViewFacturasCobranzaList";
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "view_facturas_cobranza";
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
        $this->DefaultFilter = "`documento` <> 'NC'";
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

        // Cascade delete detail table 'pagos'
        $dtlrows = Container("pagos")->loadRs("`id_documento` = " . QuotedValue($rs['id'], DataType::NUMBER, "DB") . " AND " . "`tipo_documento` = " . QuotedValue($rs['tipo_documento'], DataType::STRING, "DB"))->fetchAllAssociative();
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("pagos")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("pagos")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("pagos")->rowDeleted($dtlrow);
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
        $this->documento->DbValue = $row['documento'];
        $this->codcli->DbValue = $row['codcli'];
        $this->fecha->DbValue = $row['fecha'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->nro_nota->DbValue = $row['nro_nota'];
        $this->ciudad->DbValue = $row['ciudad'];
        $this->moneda->DbValue = $row['moneda'];
        $this->base_imponible->DbValue = $row['base_imponible'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->monto_pagado->DbValue = $row['monto_pagado'];
        $this->pendiente->DbValue = $row['pendiente'];
        $this->pendiente2->DbValue = $row['pendiente2'];
        $this->pendiente3->DbValue = $row['pendiente3'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->fecha_despacho->DbValue = $row['fecha_despacho'];
        $this->fecha_entrega->DbValue = $row['fecha_entrega'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->dias_transcurridos->DbValue = $row['dias_transcurridos'];
        $this->dias_vencidos->DbValue = $row['dias_vencidos'];
        $this->pagado->DbValue = $row['pagado'];
        $this->bultos->DbValue = $row['bultos'];
        $this->asesor_asignado->DbValue = $row['asesor_asignado'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
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
        return $_SESSION[$name] ?? GetUrl("ViewFacturasCobranzaList");
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
            "ViewFacturasCobranzaView" => $Language->phrase("View"),
            "ViewFacturasCobranzaEdit" => $Language->phrase("Edit"),
            "ViewFacturasCobranzaAdd" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "ViewFacturasCobranzaList";
    }

    // API page name
    public function getApiPageName($action)
    {
        return match (strtolower($action)) {
            Config("API_VIEW_ACTION") => "ViewFacturasCobranzaView",
            Config("API_ADD_ACTION") => "ViewFacturasCobranzaAdd",
            Config("API_EDIT_ACTION") => "ViewFacturasCobranzaEdit",
            Config("API_DELETE_ACTION") => "ViewFacturasCobranzaDelete",
            Config("API_LIST_ACTION") => "ViewFacturasCobranzaList",
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
        return "ViewFacturasCobranzaList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewFacturasCobranzaView", $parm);
        } else {
            $url = $this->keyUrl("ViewFacturasCobranzaView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewFacturasCobranzaAdd?" . $parm;
        } else {
            $url = "ViewFacturasCobranzaAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewFacturasCobranzaEdit", $parm);
        } else {
            $url = $this->keyUrl("ViewFacturasCobranzaEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("ViewFacturasCobranzaList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewFacturasCobranzaAdd", $parm);
        } else {
            $url = $this->keyUrl("ViewFacturasCobranzaAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("ViewFacturasCobranzaList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("ViewFacturasCobranzaDelete", $parm);
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->documento->setDbValue($row['documento']);
        $this->codcli->setDbValue($row['codcli']);
        $this->fecha->setDbValue($row['fecha']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_nota->setDbValue($row['nro_nota']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->moneda->setDbValue($row['moneda']);
        $this->base_imponible->setDbValue($row['base_imponible']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->monto_pagado->setDbValue($row['monto_pagado']);
        $this->pendiente->setDbValue($row['pendiente']);
        $this->pendiente2->setDbValue($row['pendiente2']);
        $this->pendiente3->setDbValue($row['pendiente3']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->fecha_despacho->setDbValue($row['fecha_despacho']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->dias_transcurridos->setDbValue($row['dias_transcurridos']);
        $this->dias_vencidos->setDbValue($row['dias_vencidos']);
        $this->pagado->setDbValue($row['pagado']);
        $this->bultos->setDbValue($row['bultos']);
        $this->asesor_asignado->setDbValue($row['asesor_asignado']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
    }

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "ViewFacturasCobranzaList";
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

        // documento

        // codcli

        // fecha

        // nro_documento

        // nro_nota

        // ciudad

        // moneda

        // base_imponible

        // alicuota_iva

        // iva

        // total

        // monto_pagado

        // pendiente

        // pendiente2

        // pendiente3

        // tasa_dia

        // monto_usd

        // fecha_despacho

        // fecha_entrega

        // dias_credito

        // dias_transcurridos

        // dias_vencidos

        // pagado

        // bultos

        // asesor_asignado

        // id_documento_padre

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $curVal = strval($this->tipo_documento->CurrentValue);
        if ($curVal != "") {
            $this->tipo_documento->ViewValue = $this->tipo_documento->lookupCacheOption($curVal);
            if ($this->tipo_documento->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchExpression(), "=", $curVal, $this->tipo_documento->Lookup->getTable()->Fields["codigo"]->searchDataType(), "");
                $sqlWrk = $this->tipo_documento->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_documento->ViewValue = $this->tipo_documento->displayValue($arwrk);
                } else {
                    $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
                }
            }
        } else {
            $this->tipo_documento->ViewValue = null;
        }

        // documento
        if (strval($this->documento->CurrentValue) != "") {
            $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
        } else {
            $this->documento->ViewValue = null;
        }

        // codcli
        $curVal = strval($this->codcli->CurrentValue);
        if ($curVal != "") {
            $this->codcli->ViewValue = $this->codcli->lookupCacheOption($curVal);
            if ($this->codcli->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->codcli->Lookup->getTable()->Fields["id"]->searchExpression(), "=", $curVal, $this->codcli->Lookup->getTable()->Fields["id"]->searchDataType(), "");
                $sqlWrk = $this->codcli->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->codcli->Lookup->renderViewRow($rswrk[0]);
                    $this->codcli->ViewValue = $this->codcli->displayValue($arwrk);
                } else {
                    $this->codcli->ViewValue = FormatNumber($this->codcli->CurrentValue, $this->codcli->formatPattern());
                }
            }
        } else {
            $this->codcli->ViewValue = null;
        }

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, $this->fecha->formatPattern());

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;

        // nro_nota
        $this->nro_nota->ViewValue = $this->nro_nota->CurrentValue;

        // ciudad
        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
        $curVal = strval($this->ciudad->CurrentValue);
        if ($curVal != "") {
            $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
            if ($this->ciudad->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchExpression(), "=", $curVal, $this->ciudad->Lookup->getTable()->Fields["campo_codigo"]->searchDataType(), "");
                $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, '', $this, true, true);
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

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;

        // base_imponible
        $this->base_imponible->ViewValue = $this->base_imponible->CurrentValue;
        $this->base_imponible->ViewValue = FormatNumber($this->base_imponible->ViewValue, $this->base_imponible->formatPattern());

        // alicuota_iva
        $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, $this->alicuota_iva->formatPattern());

        // iva
        $this->iva->ViewValue = $this->iva->CurrentValue;
        $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, $this->iva->formatPattern());

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->formatPattern());

        // monto_pagado
        $this->monto_pagado->ViewValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->ViewValue = FormatNumber($this->monto_pagado->ViewValue, $this->monto_pagado->formatPattern());

        // pendiente
        $this->pendiente->ViewValue = $this->pendiente->CurrentValue;
        $this->pendiente->ViewValue = FormatNumber($this->pendiente->ViewValue, $this->pendiente->formatPattern());

        // pendiente2
        $this->pendiente2->ViewValue = $this->pendiente2->CurrentValue;
        $this->pendiente2->ViewValue = FormatNumber($this->pendiente2->ViewValue, $this->pendiente2->formatPattern());

        // pendiente3
        $this->pendiente3->ViewValue = $this->pendiente3->CurrentValue;
        $this->pendiente3->ViewValue = FormatNumber($this->pendiente3->ViewValue, $this->pendiente3->formatPattern());

        // tasa_dia
        $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->formatPattern());

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->formatPattern());

        // fecha_despacho
        $this->fecha_despacho->ViewValue = $this->fecha_despacho->CurrentValue;
        $this->fecha_despacho->ViewValue = FormatDateTime($this->fecha_despacho->ViewValue, $this->fecha_despacho->formatPattern());

        // fecha_entrega
        $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
        $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, $this->fecha_entrega->formatPattern());

        // dias_credito
        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->ViewValue = FormatNumber($this->dias_credito->ViewValue, $this->dias_credito->formatPattern());

        // dias_transcurridos
        $this->dias_transcurridos->ViewValue = $this->dias_transcurridos->CurrentValue;
        $this->dias_transcurridos->ViewValue = FormatNumber($this->dias_transcurridos->ViewValue, $this->dias_transcurridos->formatPattern());

        // dias_vencidos
        $this->dias_vencidos->ViewValue = $this->dias_vencidos->CurrentValue;
        $this->dias_vencidos->ViewValue = FormatNumber($this->dias_vencidos->ViewValue, $this->dias_vencidos->formatPattern());

        // pagado
        if (strval($this->pagado->CurrentValue) != "") {
            $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
        } else {
            $this->pagado->ViewValue = null;
        }

        // bultos
        $this->bultos->ViewValue = $this->bultos->CurrentValue;
        $this->bultos->ViewValue = FormatNumber($this->bultos->ViewValue, $this->bultos->formatPattern());

        // asesor_asignado
        $this->asesor_asignado->ViewValue = $this->asesor_asignado->CurrentValue;
        $curVal = strval($this->asesor_asignado->CurrentValue);
        if ($curVal != "") {
            $this->asesor_asignado->ViewValue = $this->asesor_asignado->lookupCacheOption($curVal);
            if ($this->asesor_asignado->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter($this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchExpression(), "=", $curVal, $this->asesor_asignado->Lookup->getTable()->Fields["username"]->searchDataType(), "");
                $sqlWrk = $this->asesor_asignado->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->asesor_asignado->Lookup->renderViewRow($rswrk[0]);
                    $this->asesor_asignado->ViewValue = $this->asesor_asignado->displayValue($arwrk);
                } else {
                    $this->asesor_asignado->ViewValue = $this->asesor_asignado->CurrentValue;
                }
            }
        } else {
            $this->asesor_asignado->ViewValue = null;
        }

        // id_documento_padre
        $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->ViewValue = FormatNumber($this->id_documento_padre->ViewValue, $this->id_documento_padre->formatPattern());

        // id
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // documento
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // codcli
        $this->codcli->HrefValue = "";
        $this->codcli->TooltipValue = "";

        // fecha
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // nro_documento
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // nro_nota
        $this->nro_nota->HrefValue = "";
        $this->nro_nota->TooltipValue = "";

        // ciudad
        $this->ciudad->HrefValue = "";
        $this->ciudad->TooltipValue = "";

        // moneda
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // base_imponible
        $this->base_imponible->HrefValue = "";
        $this->base_imponible->TooltipValue = "";

        // alicuota_iva
        $this->alicuota_iva->HrefValue = "";
        $this->alicuota_iva->TooltipValue = "";

        // iva
        $this->iva->HrefValue = "";
        $this->iva->TooltipValue = "";

        // total
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

        // monto_pagado
        $this->monto_pagado->HrefValue = "";
        $this->monto_pagado->TooltipValue = "";

        // pendiente
        $this->pendiente->HrefValue = "";
        $this->pendiente->TooltipValue = "";

        // pendiente2
        $this->pendiente2->HrefValue = "";
        $this->pendiente2->TooltipValue = "";

        // pendiente3
        $this->pendiente3->HrefValue = "";
        $this->pendiente3->TooltipValue = "";

        // tasa_dia
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // fecha_despacho
        $this->fecha_despacho->HrefValue = "";
        $this->fecha_despacho->TooltipValue = "";

        // fecha_entrega
        $this->fecha_entrega->HrefValue = "";
        $this->fecha_entrega->TooltipValue = "";

        // dias_credito
        $this->dias_credito->HrefValue = "";
        $this->dias_credito->TooltipValue = "";

        // dias_transcurridos
        $this->dias_transcurridos->HrefValue = "";
        $this->dias_transcurridos->TooltipValue = "";

        // dias_vencidos
        $this->dias_vencidos->HrefValue = "";
        $this->dias_vencidos->TooltipValue = "";

        // pagado
        $this->pagado->HrefValue = "";
        $this->pagado->TooltipValue = "";

        // bultos
        $this->bultos->HrefValue = "";
        $this->bultos->TooltipValue = "";

        // asesor_asignado
        $this->asesor_asignado->HrefValue = "";
        $this->asesor_asignado->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

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

        // tipo_documento
        $this->tipo_documento->setupEditAttributes();
        if (!$this->tipo_documento->Raw) {
            $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
        }
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // documento
        $this->documento->setupEditAttributes();
        $this->documento->EditValue = $this->documento->options(true);
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

        // codcli
        $this->codcli->setupEditAttributes();
        $this->codcli->PlaceHolder = RemoveHtml($this->codcli->caption());

        // fecha
        $this->fecha->setupEditAttributes();
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, $this->fecha->formatPattern());
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // nro_documento
        $this->nro_documento->setupEditAttributes();
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // nro_nota
        $this->nro_nota->setupEditAttributes();
        if (!$this->nro_nota->Raw) {
            $this->nro_nota->CurrentValue = HtmlDecode($this->nro_nota->CurrentValue);
        }
        $this->nro_nota->EditValue = $this->nro_nota->CurrentValue;
        $this->nro_nota->PlaceHolder = RemoveHtml($this->nro_nota->caption());

        // ciudad
        $this->ciudad->setupEditAttributes();
        if (!$this->ciudad->Raw) {
            $this->ciudad->CurrentValue = HtmlDecode($this->ciudad->CurrentValue);
        }
        $this->ciudad->EditValue = $this->ciudad->CurrentValue;
        $this->ciudad->PlaceHolder = RemoveHtml($this->ciudad->caption());

        // moneda
        $this->moneda->setupEditAttributes();
        if (!$this->moneda->Raw) {
            $this->moneda->CurrentValue = HtmlDecode($this->moneda->CurrentValue);
        }
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // base_imponible
        $this->base_imponible->setupEditAttributes();
        $this->base_imponible->EditValue = $this->base_imponible->CurrentValue;
        $this->base_imponible->PlaceHolder = RemoveHtml($this->base_imponible->caption());
        if (strval($this->base_imponible->EditValue) != "" && is_numeric($this->base_imponible->EditValue)) {
            $this->base_imponible->EditValue = FormatNumber($this->base_imponible->EditValue, null);
        }

        // alicuota_iva
        $this->alicuota_iva->setupEditAttributes();
        $this->alicuota_iva->EditValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->PlaceHolder = RemoveHtml($this->alicuota_iva->caption());
        if (strval($this->alicuota_iva->EditValue) != "" && is_numeric($this->alicuota_iva->EditValue)) {
            $this->alicuota_iva->EditValue = FormatNumber($this->alicuota_iva->EditValue, null);
        }

        // iva
        $this->iva->setupEditAttributes();
        $this->iva->EditValue = $this->iva->CurrentValue;
        $this->iva->PlaceHolder = RemoveHtml($this->iva->caption());
        if (strval($this->iva->EditValue) != "" && is_numeric($this->iva->EditValue)) {
            $this->iva->EditValue = FormatNumber($this->iva->EditValue, null);
        }

        // total
        $this->total->setupEditAttributes();
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->PlaceHolder = RemoveHtml($this->total->caption());
        if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
            $this->total->EditValue = FormatNumber($this->total->EditValue, null);
        }

        // monto_pagado
        $this->monto_pagado->setupEditAttributes();
        $this->monto_pagado->EditValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->PlaceHolder = RemoveHtml($this->monto_pagado->caption());
        if (strval($this->monto_pagado->EditValue) != "" && is_numeric($this->monto_pagado->EditValue)) {
            $this->monto_pagado->EditValue = FormatNumber($this->monto_pagado->EditValue, null);
        }

        // pendiente
        $this->pendiente->setupEditAttributes();
        $this->pendiente->EditValue = $this->pendiente->CurrentValue;
        $this->pendiente->PlaceHolder = RemoveHtml($this->pendiente->caption());
        if (strval($this->pendiente->EditValue) != "" && is_numeric($this->pendiente->EditValue)) {
            $this->pendiente->EditValue = FormatNumber($this->pendiente->EditValue, null);
        }

        // pendiente2
        $this->pendiente2->setupEditAttributes();
        $this->pendiente2->EditValue = $this->pendiente2->CurrentValue;
        $this->pendiente2->PlaceHolder = RemoveHtml($this->pendiente2->caption());
        if (strval($this->pendiente2->EditValue) != "" && is_numeric($this->pendiente2->EditValue)) {
            $this->pendiente2->EditValue = FormatNumber($this->pendiente2->EditValue, null);
        }

        // pendiente3
        $this->pendiente3->setupEditAttributes();
        $this->pendiente3->EditValue = $this->pendiente3->CurrentValue;
        $this->pendiente3->PlaceHolder = RemoveHtml($this->pendiente3->caption());
        if (strval($this->pendiente3->EditValue) != "" && is_numeric($this->pendiente3->EditValue)) {
            $this->pendiente3->EditValue = FormatNumber($this->pendiente3->EditValue, null);
        }

        // tasa_dia
        $this->tasa_dia->setupEditAttributes();
        $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->PlaceHolder = RemoveHtml($this->tasa_dia->caption());
        if (strval($this->tasa_dia->EditValue) != "" && is_numeric($this->tasa_dia->EditValue)) {
            $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, null);
        }

        // monto_usd
        $this->monto_usd->setupEditAttributes();
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, null);
        }

        // fecha_despacho
        $this->fecha_despacho->setupEditAttributes();
        $this->fecha_despacho->EditValue = FormatDateTime($this->fecha_despacho->CurrentValue, $this->fecha_despacho->formatPattern());
        $this->fecha_despacho->PlaceHolder = RemoveHtml($this->fecha_despacho->caption());

        // fecha_entrega
        $this->fecha_entrega->setupEditAttributes();
        $this->fecha_entrega->EditValue = FormatDateTime($this->fecha_entrega->CurrentValue, $this->fecha_entrega->formatPattern());
        $this->fecha_entrega->PlaceHolder = RemoveHtml($this->fecha_entrega->caption());

        // dias_credito
        $this->dias_credito->setupEditAttributes();
        $this->dias_credito->EditValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());
        if (strval($this->dias_credito->EditValue) != "" && is_numeric($this->dias_credito->EditValue)) {
            $this->dias_credito->EditValue = FormatNumber($this->dias_credito->EditValue, null);
        }

        // dias_transcurridos
        $this->dias_transcurridos->setupEditAttributes();
        $this->dias_transcurridos->EditValue = $this->dias_transcurridos->CurrentValue;
        $this->dias_transcurridos->PlaceHolder = RemoveHtml($this->dias_transcurridos->caption());
        if (strval($this->dias_transcurridos->EditValue) != "" && is_numeric($this->dias_transcurridos->EditValue)) {
            $this->dias_transcurridos->EditValue = FormatNumber($this->dias_transcurridos->EditValue, null);
        }

        // dias_vencidos
        $this->dias_vencidos->setupEditAttributes();
        $this->dias_vencidos->EditValue = $this->dias_vencidos->CurrentValue;
        $this->dias_vencidos->PlaceHolder = RemoveHtml($this->dias_vencidos->caption());
        if (strval($this->dias_vencidos->EditValue) != "" && is_numeric($this->dias_vencidos->EditValue)) {
            $this->dias_vencidos->EditValue = FormatNumber($this->dias_vencidos->EditValue, null);
        }

        // pagado
        $this->pagado->EditValue = $this->pagado->options(false);
        $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

        // bultos
        $this->bultos->setupEditAttributes();
        $this->bultos->EditValue = $this->bultos->CurrentValue;
        $this->bultos->PlaceHolder = RemoveHtml($this->bultos->caption());
        if (strval($this->bultos->EditValue) != "" && is_numeric($this->bultos->EditValue)) {
            $this->bultos->EditValue = FormatNumber($this->bultos->EditValue, null);
        }

        // asesor_asignado
        $this->asesor_asignado->setupEditAttributes();
        if (!$this->asesor_asignado->Raw) {
            $this->asesor_asignado->CurrentValue = HtmlDecode($this->asesor_asignado->CurrentValue);
        }
        $this->asesor_asignado->EditValue = $this->asesor_asignado->CurrentValue;
        $this->asesor_asignado->PlaceHolder = RemoveHtml($this->asesor_asignado->caption());

        // id_documento_padre
        $this->id_documento_padre->setupEditAttributes();
        $this->id_documento_padre->EditValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->PlaceHolder = RemoveHtml($this->id_documento_padre->caption());
        if (strval($this->id_documento_padre->EditValue) != "" && is_numeric($this->id_documento_padre->EditValue)) {
            $this->id_documento_padre->EditValue = FormatNumber($this->id_documento_padre->EditValue, null);
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
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->codcli);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_nota);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->base_imponible);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->pendiente);
                    $doc->exportCaption($this->pendiente2);
                    $doc->exportCaption($this->pendiente3);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->fecha_despacho);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->dias_transcurridos);
                    $doc->exportCaption($this->dias_vencidos);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->bultos);
                    $doc->exportCaption($this->asesor_asignado);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->codcli);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_nota);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->base_imponible);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->pendiente);
                    $doc->exportCaption($this->pendiente2);
                    $doc->exportCaption($this->pendiente3);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->fecha_despacho);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->dias_transcurridos);
                    $doc->exportCaption($this->dias_vencidos);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->bultos);
                    $doc->exportCaption($this->asesor_asignado);
                    $doc->exportCaption($this->id_documento_padre);
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
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->codcli);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_nota);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->base_imponible);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->pendiente);
                        $doc->exportField($this->pendiente2);
                        $doc->exportField($this->pendiente3);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->fecha_despacho);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->dias_transcurridos);
                        $doc->exportField($this->dias_vencidos);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->bultos);
                        $doc->exportField($this->asesor_asignado);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->codcli);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_nota);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->base_imponible);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->pendiente);
                        $doc->exportField($this->pendiente2);
                        $doc->exportField($this->pendiente3);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->fecha_despacho);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->dias_transcurridos);
                        $doc->exportField($this->dias_vencidos);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->bultos);
                        $doc->exportField($this->asesor_asignado);
                        $doc->exportField($this->id_documento_padre);
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
       	if ($this->PageID == "list" || $this->PageID == "view") {
       		if($this->pagado->CurrentValue == "S") {
       			$this->codcli->CellAttrs["style"] = "background-color: #5B8266; color: #ffffff;";
       			$this->fecha->CellAttrs["style"] = "background-color: #5B8266; color: #ffffff;";
       			$this->nro_documento->CellAttrs["style"] = "background-color: #5B8266; color: #ffffff;";
       		}
       		if(floatval($this->monto_pagado->CurrentValue) > 0) {
       			$this->total->CellAttrs["style"] = "background-color: red; color: #ffffff;";
       			$this->monto_pagado->CellAttrs["style"] = "background-color: red; color: #ffffff;";
       			if(floatval($this->monto_pagado->CurrentValue) >= floatval($this->total->CurrentValue)) {
       				$this->total->CellAttrs["style"] = "background-color: #5B8266;; color: #ffffff;";
       				$this->monto_pagado->CellAttrs["style"] = "background-color: #5B8266;; color: #ffffff;";
       			} 
       		}
       		if(intval($this->dias_vencidos->CurrentValue) > 0) {
       			$this->dias_vencidos->CellAttrs["style"] = "background-color: red; color: #ffffff;";
       		}
       		if(floatval($this->monto_pagado->CurrentValue) > floatval($this->total->CurrentValue)) {
       			$this->monto_pagado->CellAttrs["style"] = "background-color: yellow; color: #000000;";
       		}
       	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
