<?php
session_start();
header('Content-Type: application/json');
include "connect.php"; 

$cliente = isset($_REQUEST["cliente"]) ? intval($_REQUEST["cliente"]) : 0;

if ($cliente <= 0) {
    echo json_encode(["status" => "error", "count" => 0, "facturas_html" => ""]);
    exit;
}

// 1. Consulta con Sentencia Preparada
$sql = "SELECT 
                a.id AS id_documento, b.descripcion AS tipo_documento_desc, a.nro_documento , 
                a.total AS monto_pagar, 0 AS monto_pagado,  0 AS retivamonto, 0 AS retislrmonto, a.tipo_documento
            FROM 
                salidas AS a 
                JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
            WHERE a.cliente = ?;";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $cliente);
mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);
$total_filas = mysqli_num_rows($rs);

if ($total_filas == 0) {
    echo json_encode(["status" => "success", "count" => 0, "facturas_html" => '<div class="alert alert-warning text-center">No se encontraron facturas pendientes para este cliente.</div>']);
    exit;
}

ob_start(); 
?>
<div class="row g-3"> <?php 
    $i = 0;
    while($row = mysqli_fetch_assoc($rs)): 
        $monto_pagar = floatval($row["monto_pagar"]);
        $deducciones = floatval($row["monto_pagado"]) + floatval($row["retivamonto"]) + floatval($row["retislrmonto"]);
        $saldo_real  = $monto_pagar - $deducciones;
    ?>
    <div class="col-12 col-md-6 col-lg-4"> <label class="card h-100 shadow-sm border-2 btn-outline-primary position-relative cursor-pointer mb-0" for="x_id_<?php echo $i; ?>" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-primary mb-1">VENTAS</span>
                        <h6 class="card-title fw-bold mb-0 text-dark">
                            <?php echo $row["tipo_documento_desc"]; ?>
                        </h6>
                        <small class="text-muted">Nro: <?php echo $row["nro_documento"]; ?></small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input ms-0" type="radio" name="factura_seleccionada" 
                               id="x_id_<?php echo $i; ?>" 
                               onclick="validar_check(<?php echo $i; ?>);" 
                               value="<?php echo $row['id_documento'].'-'.$row['tipo_documento_desc']; ?>">
                    </div>
                </div>

                <hr class="my-2 opacity-25">

                <div class="row text-center">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block small-caps">A Pagar</small>
                        <span class="fw-bold text-dark">
                            $ <?php echo number_format($monto_pagar, 2); ?>
                        </span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block small-caps">Saldo</small>
                        <span class="fw-bold text-danger">
                            $ <?php echo number_format($saldo_real, 2); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <input type="hidden" id="x_pagar_<?php echo $i; ?>" value="<?php echo number_format($monto_pagar, 2, ".", ""); ?>">
            <input type="hidden" id="x_saldo_<?php echo $i; ?>" value="<?php echo number_format($saldo_real, 2, ".", ""); ?>">
        </label>
    </div>
    <?php $i++; endwhile; ?>
</div>

<input type="hidden" id="xCantidad" value="<?php echo $i; ?>">
<input type="hidden" id="pagos" name="pagos" value="">
<input type="hidden" id="monto" name="monto" value="">
<input type="hidden" id="abono" name="abono" value="">
<input type="hidden" id="abono2" name="abono2" value="">
<input type="hidden" id="saldo" name="saldo" value="">
<input type="hidden" id="xctrl" name="xctrl" value="">

<style>
    .small-caps { font-variant: small-caps; font-size: 0.75rem; }
    /* Efecto de selección visual para las cards */
    input[type="radio"]:checked + label.card {
        border-color: #0d6efd;
        background-color: #f8faff;
    }
</style>

<?php
$html = ob_get_clean();

echo json_encode([
    "status" => "success",
    "count" => $total_filas,
    "facturas_html" => $html
]);
?>