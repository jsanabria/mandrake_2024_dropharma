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
        $cliente_direccion = TdcfcvSqlValue($row["cliente_direccion"] ?? "");
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

$pedido = $_REQUEST["pedido"];

$sql = "SELECT documento, nro_documento, estatus FROM salidas WHERE id = $pedido;";
$row = ExecuteRow($sql);
$documento = $row["documento"];
$nro_documento = $row["nro_documento"];
$estatus =  $row["estatus"];

if(trim($nro_documento) == "") {
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

    $sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
    if(ExecuteScalar($sql) == "S") {
        $crtl = "030";
    }
    else {
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
    }

    $sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$docu';";
    $row = ExecuteRow($sql);
    $numero = intval($row["valor1"]) + 1;
    $prefijo = trim($row["valor2"]);
    $padeo = intval($row["valor3"]);
    $factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    $sql = "UPDATE parametro SET valor1='$numero' 
        WHERE codigo = '$docu';";
    Execute($sql);

    //// Nro Ctrol ////
    // Tomo el siguiente número de control de factura
    $sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$crtl';";
    $row = ExecuteRow($sql);
    $numero = intval($row["valor1"]) + 1;
    $prefijo = trim($row["valor2"]);
    $padeo = intval($row["valor3"]);
    $facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    $sql = "UPDATE parametro SET valor1='$numero' 
            WHERE codigo = '$crtl';";
            Execute($sql);
    ///////////////////

    // $sql = "SELECT IF(a.dias_credito IS NULL OR a.asesor_asignado IS NULL, 'S', 'N') AS faltan_datos FROM salidas AS a WHERE id = $pedido;";
    // $faltan_datos = ExecuteScalar($sql);
    // if($faltan_datos == "N") $estatus = "PROCESADO";
    $estatus = "PROCESADO";

    $sql = "UPDATE salidas SET fecha = '" . date("Y-m-d H:i:s") . "', nro_documento = '$factura', nro_control = '$facturaCTRL', estatus = '$estatus', username = '" . CurrentUserName() . "' WHERE id = $pedido;";
    Execute($sql);

    // Congela la foto fiscal del cliente y de los articulos al momento de emitir.
    CongelarSnapshotFiscalTdcfcv($pedido);

    /**********************************************************************************
     * NUEVO: REGISTRO DE AUDITORÍA DE EMISIÓN DE DOCUMENTO
     **********************************************************************************/
    // 1. Identificamos textualmente el tipo de documento para el log
    $nombreDocumentoTxt = "Documento";
    switch ($documento) {
        case "FC":
            $nombreDocumentoTxt = "Factura";
            break;
        case "NC":
            $nombreDocumentoTxt = "Nota de Crédito";
            break;
        case "ND":
            $nombreDocumentoTxt = "Nota de Débito";
            break;
    }

    $usuarioActual = CurrentUserName();
    $fechaActualTxt = date("d/m/Y H:i:s");
    
    // 2. Construimos el texto informativo del log
    $scriptLog = "Emitió documento {$nombreDocumentoTxt} # {$factura} con # de control {$facturaCTRL} de fecha {$fechaActualTxt}";

    // 3. Insertamos el rastro en audittrail usando Execute() propio de tu entorno PHPMaker
    $sqlAudit = "INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (NULL, '" . date("Y-m-d H:i:s") . "',
             '{$scriptLog}',
             '{$usuarioActual}', 'A', 'view_out_tdcfcv', 'id', '{$pedido}', '', '{$factura}');";
    Execute($sqlAudit);
    /**********************************************************************************/
} 
else {
    $sql = "SELECT IF(a.dias_credito IS NULL OR a.asesor_asignado IS NULL, 'S', 'N') AS faltan_datos FROM salidas AS a WHERE id = $pedido;";
    $faltan_datos = ExecuteScalar($sql);
    if($faltan_datos == "N") $estatus = "PROCESADO";
}

if($estatus == "PROCESADO")
    header("Location: ViewOutTdcfcvView/" . $pedido . "?showdetail=");
else 
    header("Location: ViewOutTdcfcvEdit/" . $pedido . "?showdetail=&my_estatus=PROCESADO");
die();
?>
<?= GetDebugMessage() ?>
