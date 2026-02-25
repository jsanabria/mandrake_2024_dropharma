<?php
session_start();
header('Content-Type: application/json');
include "connect.php";

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID de compra no válido"]);
    exit;
}

// 1. Obtener datos del cliente (Sentencia Preparada)
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

// 2. Construir HTML del Cliente (Diseño de Card de Resumen)
$html_cliente = '
<div class="card shadow-sm border-0 bg-light mb-3">
    <div class="card-body py-2 px-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
            <div class="col">
                <h6 class="text-muted small mb-0 uppercase fw-bold" style="font-size: 0.7rem;">CLIENTE</h6>
                <p class="mb-0 fw-bold text-dark">' . htmlspecialchars($nomcli, ENT_QUOTES, 'UTF-8') . '</p>
                <input type="hidden" name="x_cliente" id="x_cliente" value="' . $codcli . '">
            </div>
            <div class="col-auto text-end">
                <span class="badge rounded-pill bg-secondary">ID: ' . $codcli . '</span>
            </div>
        </div>
    </div>
</div>';

// 3. Consultar la factura específica (Sentencia Preparada)
$sqlFact = "SELECT 
                a.id AS id_documento, b.descripcion AS tipo_documento_desc, a.nro_documento, 
                a.total AS monto_pagar, 0 AS monto_pagado, 0 AS retivamonto, 0 AS retislrmonto, a.tipo_documento
            FROM 
                salidas AS a 
                JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
            WHERE a.id = ?;";
$stmtFact = mysqli_prepare($link, $sqlFact);
mysqli_stmt_bind_param($stmtFact, "i", $id);
mysqli_stmt_execute($stmtFact);
$rsFact = mysqli_stmt_get_result($stmtFact);

$html_tabla = '<div class="row g-3">';

$i = 0;
$monto_final = 0;
while ($row = mysqli_fetch_assoc($rsFact)) {
    $monto_pagar = floatval($row["monto_pagar"]);
    $monto_pagado = floatval($row["monto_pagado"]);
    $deducciones = $monto_pagado + floatval($row["retivamonto"]) + floatval($row["retislrmonto"]);
    $saldo_real = $monto_pagar - $deducciones;
    $monto_final = $monto_pagar;

    $html_tabla .= '
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-info-subtle text-info-emphasis px-2">VENTAS</span>
                    <div class="form-check m-0">
                        <input class="form-check-input" type="radio" id="x_id_'.$i.'" name="x_id_0" value="'.$row["id_documento"].'-'.$row["tipo_documento_desc"].'" checked>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-1 fw-bold">'.$row["tipo_documento_desc"].'</h5>
                        <p class="text-muted small mb-0">Número de Control: <span class="fw-bold text-dark">'.$row["nro_documento"].'</span></p>
                    </div>
                    <div class="col-auto text-end border-start ps-4">
                        <p class="text-muted small mb-0 fw-bold" style="font-size: 0.65rem;">SALDO PENDIENTE</p>
                        <h4 class="mb-0 fw-bold text-danger">$ '.number_format($saldo_real, 2, ".", ",").'</h4>
                    </div>
                </div>
            </div>
            
            <input type="hidden" id="x_pagar_'.$i.'" value="'.number_format($monto_pagar, 2, ".", "").'">
            <input type="hidden" id="x_saldo_'.$i.'" value="'.number_format($saldo_real, 2, ".", "").'">
        </div>
    </div>';
    $i++;
}

$html_tabla .= '
    <input type="hidden" id="xCantidad" value="'.$i.'">
    <input type="hidden" id="monto" name="monto" value="'.number_format($monto_final, 2, ".", "").'">
    <input type="hidden" id="saldo" name="saldo" value="'.number_format($monto_final, 2, ".", "").'">
    <input type="hidden" id="xctrl" value="x_saldo_0">
</div>';

// 4. Retorno del objeto JSON unificado
echo json_encode([
    "status" => "success",
    "cliente_html" => $html_cliente,
    "facturas_html" => $html_tabla,
    "monto_total" => $monto_final,
    "ctrl_foco" => "x_saldo_0"
]);