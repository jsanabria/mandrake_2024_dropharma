<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfcvProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
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
