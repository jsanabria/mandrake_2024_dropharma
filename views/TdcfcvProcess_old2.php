<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfcvProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

function TdcfcvSqlValue($value)
{
    return str_replace("'", "''", (string)$value);
}

function CongelarSnapshotFiscalTdcfcv($pedido)
{
    $pedido = intval($pedido);

    if ($pedido <= 0) {
        return;
    }

    /*****************************************************************************
     * 1) Congelar datos fiscales del cliente en salidas
     *
     * IMPORTANTE:
     * - El SELECT puede consultar cliente/tabla.
     * - El UPDATE debe ser simple sobre salidas por id, sin JOIN.
     * - Solo rellena campos vacios; nunca pisa datos ya congelados.
     *****************************************************************************/
    $sql = "SELECT
                s.id,
                IFNULL(c.ci_rif, '') AS cliente_ci_rif,
                IFNULL(c.nombre, '') AS cliente_nombre,
                TRIM(CONCAT(IFNULL(c.direccion, ''), IF(IFNULL(t.campo_descripcion, '') <> '', CONCAT('. ', t.campo_descripcion), ''))) AS cliente_direccion,
                TRIM(CONCAT(REPLACE(IFNULL(c.telefono1, ''), ' ', ''), ' ', REPLACE(IFNULL(c.telefono2, ''), ' ', ''))) AS cliente_telefono
            FROM salidas AS s
            LEFT JOIN cliente AS c ON c.id = s.cliente
            LEFT JOIN tabla AS t ON t.campo_codigo = c.ciudad AND t.tabla = 'CIUDAD'
            WHERE s.id = {$pedido}
            LIMIT 1";
    $row = ExecuteRow($sql);

    if ($row) {
        $cliente_ci_rif = TdcfcvSqlValue($row["cliente_ci_rif"] ?? "");
        $cliente_nombre = TdcfcvSqlValue($row["cliente_nombre"] ?? "");
        // $cliente_direccion = TdcfcvSqlValue($row["cliente_direccion"] ?? "");
        $cliente_direccion = TdcfcvSqlValue(mb_substr($row["cliente_direccion"] ?? "", 0, 150, "UTF-8"));
        $cliente_telefono = TdcfcvSqlValue($row["cliente_telefono"] ?? "");

        $sql = "UPDATE salidas
                SET
                    cliente_ci_rif = IF(cliente_ci_rif IS NULL OR cliente_ci_rif = '', '{$cliente_ci_rif}', cliente_ci_rif),
                    cliente_nombre = IF(cliente_nombre IS NULL OR cliente_nombre = '', '{$cliente_nombre}', cliente_nombre),
                    cliente_direccion = IF(cliente_direccion IS NULL OR cliente_direccion = '', '{$cliente_direccion}', cliente_direccion),
                    cliente_telefono = IF(cliente_telefono IS NULL OR cliente_telefono = '', '{$cliente_telefono}', cliente_telefono)
                WHERE id = {$pedido}
                LIMIT 1";
        Execute($sql);
    }

    /*****************************************************************************
     * 2) Congelar datos del articulo en entradas_salidas
     *
     * IMPORTANTE:
     * - El SELECT puede consultar articulo.
     * - Cada UPDATE es simple sobre entradas_salidas por id, sin JOIN.
     * - Solo rellena campos vacios; nunca pisa datos ya congelados.
     *****************************************************************************/
    $sql = "SELECT
                e.id,
                IFNULL(a.codigo, '') AS articulo_codigo,
                LTRIM(RTRIM(CONCAT(
                    IFNULL(a.nombre_comercial, ''),
                    ' ',
                    IFNULL(a.principio_activo, ''),
                    ' ',
                    IFNULL(a.presentacion, '')
                ))) AS articulo_descripcion
            FROM entradas_salidas AS e
            LEFT JOIN articulo AS a ON a.id = e.articulo
            WHERE e.id_documento = {$pedido}
              AND e.tipo_documento = 'TDCFCV'
              AND (
                    e.articulo_codigo IS NULL OR e.articulo_codigo = ''
                 OR e.articulo_descripcion IS NULL OR e.articulo_descripcion = ''
              )";
    $rows = ExecuteRows($sql);

    if ($rows) {
        foreach ($rows as $row) {
            $id_detalle = intval($row["id"] ?? 0);

            if ($id_detalle <= 0) {
                continue;
            }

            $articulo_codigo = TdcfcvSqlValue($row["articulo_codigo"] ?? "");
            $articulo_descripcion = TdcfcvSqlValue($row["articulo_descripcion"] ?? "");

            $sql = "UPDATE entradas_salidas
                    SET
                        articulo_codigo = IF(articulo_codigo IS NULL OR articulo_codigo = '', '{$articulo_codigo}', articulo_codigo),
                        articulo_descripcion = IF(articulo_descripcion IS NULL OR articulo_descripcion = '', '{$articulo_descripcion}', articulo_descripcion)
                    WHERE id = {$id_detalle}
                    LIMIT 1";
            Execute($sql);
        }
    }
}


function ParametroImpresoraFiscalActivaTdcfcv()
{
    ExecuteStatement("
        INSERT INTO parametro (codigo, descripcion, valor1)
        SELECT '112', 'USA IMPRESORA FISCAL', 'N'
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1
            FROM parametro
            WHERE codigo = '112'
        )
    ");

    return strtoupper(trim(
        ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '112'")
    ) ?? "") == "S";
}

function NombreDocumentoFiscalTdcfcv($documento)
{
    switch (strtoupper(trim($documento ?? ''))) {
        case "FC": return "Factura";
        case "NC": return "Nota de Crédito";
        case "ND": return "Nota de Débito";
        default: return "Documento";
    }
}

function RegistrarAuditoriaEmisionTdcfcv($pedido, $documento, $factura, $facturaCTRL, $origen = "SISTEMA")
{
    $pedido = intval($pedido);
    $nombreDocumentoTxt = NombreDocumentoFiscalTdcfcv($documento);
    $usuarioActual = TdcfcvSqlValue(CurrentUserName());
    $fechaActualTxt = date("d/m/Y H:i:s");
    $facturaSql = TdcfcvSqlValue($factura);
    $origenSql = TdcfcvSqlValue($origen);

    $scriptLog = TdcfcvSqlValue("Emitió documento {$nombreDocumentoTxt} # {$factura} con # de control {$facturaCTRL} de fecha {$fechaActualTxt} ({$origen})");

    $sqlAudit = "INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (NULL, '" . date("Y-m-d H:i:s") . "',
             '{$scriptLog}',
             '{$usuarioActual}', 'SENIAT: U', 'view_out_tdcfcv', 'id', '{$pedido}', '', '{$facturaSql}');";
    Execute($sqlAudit);
}

$pedido = intval($_REQUEST["pedido"] ?? 0);
$generar_ne = strtoupper(trim($_GET["generar_ne"] ?? $_POST["generar_ne"] ?? "N"));
$generar_nr = strtoupper(trim($_GET["generar_nr"] ?? $_POST["generar_nr"] ?? "N"));

if ($pedido <= 0) {
    die("Documento no válido.");
}

$sql = "SELECT documento, nro_documento, estatus FROM salidas WHERE id = $pedido LIMIT 1;";
$row = ExecuteRow($sql);

if (!$row) {
    die("No se encontró el documento solicitado.");
}

$documento = strtoupper(trim($row["documento"] ?? ""));
$nro_documento = $row["nro_documento"] ?? "";
$estatus = $row["estatus"] ?? "";
$impresoraFiscal = ParametroImpresoraFiscalActivaTdcfcv();

if(trim($nro_documento ?? "") == "") {
    if ($impresoraFiscal) {
        // En modo impresora fiscal activa, NO reservamos consecutivos desde el sistema.
        // La impresora fiscal emitirá el documento y factura_fiscal.php actualizará
        // salidas.nro_documento, salidas.nro_control, estatus e impreso con la respuesta real.
        CongelarSnapshotFiscalTdcfcv($pedido);

        $usernameFiscal = urlencode(CurrentUserName());
        header(
            "Location: reportes/factura_fiscal.php?id={$pedido}" .
            "&username={$usernameFiscal}" .
            "&auto_return=1" .
            "&generar_ne={$generar_ne}" .
            "&generar_nr={$generar_nr}"
        );
        die();
    }

    switch($documento) {
    case "FC":
        $docu = "003";
        break;

    case "NC":
        $docu = "010";
        break;

    case "ND":
        $docu = "011";
        break;
    }

    // ¿El número de control es único para FC, NC y ND?
    $controlUnificado = (ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '035'") == "S");

    if ($controlUnificado) {

        // Prefijo y padding del parámetro 030
        $crtl = "030";

        // Serie única para los tres documentos
        $serie_ctrl = "DC_CTRL";

    } else {

        switch($documento) {
        case "FC":
            $crtl = "030";
            break;

        case "NC":
            $crtl = "031";
            break;

        case "ND":
            $crtl = "032";
            break;
        }

        // Cada documento mantiene su propia serie
        $serie_ctrl = $documento . "_CTRL";
    }

    // El consecutivo del documento SIEMPRE es independiente
    $serie_doc = $documento . "_DOC";

    $numeroDoc  = intval(ReservarConsecutivoDocumento("TDCFCV", $serie_doc));
    $numeroCtrl = intval(ReservarConsecutivoDocumento("TDCFCV", $serie_ctrl));

    $sql = "SELECT valor2, valor3 FROM parametro WHERE codigo = '$docu';";
    $row = ExecuteRow($sql);
    $prefijo = trim($row["valor2"] ?? "");
    $padeo   = intval($row["valor3"]);
    $factura = $prefijo . str_pad($numeroDoc, $padeo, "0", STR_PAD_LEFT);

    $sql = "SELECT valor2, valor3 FROM parametro WHERE codigo = '$crtl';";
    $row = ExecuteRow($sql);
    $prefijo      = trim($row["valor2"] ?? "");
    $padeo        = intval($row["valor3"]);
    $facturaCTRL  = $prefijo . str_pad($numeroCtrl, $padeo, "0", STR_PAD_LEFT);

    // $sql = "SELECT IF(a.dias_credito IS NULL OR a.asesor_asignado IS NULL, 'S', 'N') AS faltan_datos FROM salidas AS a WHERE id = $pedido;";
    // $faltan_datos = ExecuteScalar($sql);
    // if($faltan_datos == "N") $estatus = "PROCESADO";
    $estatus = "PROCESADO";

    $sql = "UPDATE salidas 
            SET fecha = '" . date("Y-m-d H:i:s") . "', 
                nro_documento = '$factura', 
                nro_control = '$facturaCTRL', 
                estatus = '$estatus', 
                username = '" . CurrentUserName() . "' 
            WHERE id = $pedido
            AND (nro_documento IS NULL OR nro_documento = '')";
    Execute($sql);
    
    // Congela la foto fiscal del cliente y de los articulos al momento de emitir.
    CongelarSnapshotFiscalTdcfcv($pedido);

    // Registro de auditoría de emisión de documento no fiscal/manual.
    RegistrarAuditoriaEmisionTdcfcv($pedido, $documento, $factura, $facturaCTRL, "SISTEMA");
} 
else {
    $sql = "SELECT IF(a.dias_credito IS NULL OR a.asesor_asignado IS NULL, 'S', 'N') AS faltan_datos FROM salidas AS a WHERE id = $pedido;";
    $faltan_datos = ExecuteScalar($sql);
    if($faltan_datos == "N") $estatus = "PROCESADO";
}

if ($estatus == "PROCESADO") {

    // NC: el movimiento de inventario es una ENTRADA mediante Nota de Recepción.
    if ($documento == "NC" && $generar_nr == "S") {
        header("Location: CrearNotaRecepcionAutomaticaWait?id=$pedido&return=ViewOutTdcfcvList");
        die();
    }

    // FC / ND: se conserva la SALIDA mediante Orden de Entrega.
    if ($documento != "NC" && $generar_ne == "S") {
        header("Location: CrearNotaEntregaAutomaticaWait?id=$pedido&return=ViewOutTdcfcvList");
        die();
    }

    header("Location: ViewOutTdcfcvView/" . $pedido . "?showdetail=");
    die();

} else {

    header("Location: ViewOutTdcfcvEdit/" . $pedido . "?showdetail=&my_estatus=PROCESADO");
    die();

}
?>
<?= GetDebugMessage() ?>
