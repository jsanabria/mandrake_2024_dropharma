<?php
session_start();
header('Content-Type: application/json');
include "connect.php";

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID de compra no válido"]);
    exit;
}

// 1. Obtener datos del cliente mediante el ID de la salida (Sentencia Preparada)
$sqlCli = "SELECT a.cliente AS id, b.nombre AS nombre_cliente 
           FROM salidas AS a 
           JOIN cliente AS b ON b.id = a.cliente 
           WHERE a.id = ?";
$stmtCli = mysqli_prepare($link, $sqlCli);
mysqli_stmt_bind_param($stmtCli, "i", $id);
mysqli_stmt_execute($stmtCli);
$rsCli = mysqli_stmt_get_result($stmtCli);
$rowCli = mysqli_fetch_array($rsCli);

if (!$rowCli) {
    echo json_encode(["status" => "error", "message" => "No se encontró el cliente"]);
    exit;
}

$codcli = $rowCli["id"];
$nomcli = $rowCli["nombre_cliente"];

// 2. Construir HTML del Cliente
$html_cliente = '
    <label class="col-sm-2 col-form-label ew-label">Cliente</label>
    <div class="col-sm-10">
        <span id="el_cobros_cliente_cliente">
            <input type="hidden" name="x_cliente" id="x_cliente" value="' . $codcli . '">
            <input type="text" name="x_Nomcliente" id="x_Nomcliente" value="' . htmlspecialchars($nomcli, ENT_QUOTES, 'UTF-8') . '" class="form-control" readonly>
        </span>
    </div>';

// 3. Consultar la factura específica (Sentencia Preparada)
$sqlFact = "SELECT 
                a.id AS id_documento, b.descripcion AS tipo_documento_desc, a.nro_documento , 
                a.total AS monto_pagar, 0 AS monto_pagado,  0 AS retivamonto, 0 AS retislrmonto, a.tipo_documento
            FROM 
                salidas AS a 
                JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
            WHERE a.id = ?;";
$stmtFact = mysqli_prepare($link, $sqlFact);
mysqli_stmt_bind_param($stmtFact, "i", $id);
mysqli_stmt_execute($stmtFact);
$rsFact = mysqli_stmt_get_result($stmtFact);

$html_tabla = '
    <div class="container-fluid">
        <div class="col-md-12">
            <table class="table table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>&nbsp;</th>
                        <th>Documento</th>
                        <th>Tipo</th>
                        <th>Nro.</th>
                        <th class="text-right">A Pagar</th>
                        <th class="text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>';

$i = 0;
$monto_final = 0;
while ($row = mysqli_fetch_assoc($rsFact)) {
    $monto_pagar = floatval($row["monto_pagar"]);
    $monto_pagado = floatval($row["monto_pagado"]);
    $deducciones = $monto_pagado + floatval($row["retivamonto"]) + floatval($row["retislrmonto"]);
    $saldo_real = $monto_pagar - $deducciones;
    
    // Guardamos el último monto para el hidden global
    $monto_final = $monto_pagar;

    $html_tabla .= '
        <tr>
            <td class="text-center">
                <input type="radio" id="x_id_'.$i.'" name="x_id_0" value="'.$row["id_documento"].'-'.$row["tipo_documento_desc"].'" checked="checked">
            </td>
            <td><small>VENTAS</small></td>
            <td>'.$row["tipo_documento_desc"].'</td>
            <td><strong>'.$row["nro_documento"].'</strong></td>
            <td class="text-right">
                <input type="text" id="x_pagar_'.$i.'" class="form-control form-control-sm text-right" value="'.number_format($monto_pagar, 2, ".", "").'" readonly>
            </td>
            <td class="text-right">
                <input type="text" id="x_saldo_'.$i.'" class="form-control form-control-sm text-right" value="'.number_format($saldo_real, 2, ".", "").'" readonly>
            </td>
        </tr>';
    $i++;
}

$html_tabla .= '
                <input type="hidden" id="xCantidad" value="'.$i.'">
                <input type="hidden" id="monto" name="monto" value="'.number_format($monto_final, 2, ".", "").'">
                <input type="hidden" id="saldo" name="saldo" value="'.number_format($monto_final, 2, ".", "").'">
                <input type="hidden" id="xctrl" value="x_saldo_0">
            </tbody>
            </table>
        </div>
    </div>';

// 4. Retorno del objeto JSON unificado
echo json_encode([
    "status" => "success",
    "cliente_html" => $html_cliente,
    "facturas_html" => $html_tabla,
    "monto_total" => $monto_final,
    "ctrl_foco" => "x_saldo_0"
]);