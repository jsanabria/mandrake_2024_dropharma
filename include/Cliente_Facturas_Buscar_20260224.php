<?php
session_start();
header('Content-Type: application/json');
include "connect.php"; 

$cliente = isset($_REQUEST["cliente"]) ? intval($_REQUEST["cliente"]) : 0;

if ($cliente <= 0) {
    echo json_encode(["status" => "error", "count" => 0, "facturas_html" => ""]);
    exit;
}

// 1. Consulta con Sentencia Preparada para evitar Inyección SQL
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
    echo json_encode(["status" => "success", "count" => 0, "facturas_html" => "0"]);
    exit;
}

ob_start(); // Iniciamos buffer para el HTML
?>
<div class="container-fluid">
    <div class="col-md-12">
        <table class="table table-hover table-sm">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">#</th>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Nro.</th>
                    <th class="text-right">A Pagar</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 0;
                while($row = mysqli_fetch_assoc($rs)): 
                    $monto_pagar = floatval($row["monto_pagar"]);
                    $deducciones = floatval($row["monto_pagado"]) + floatval($row["retivamonto"]) + floatval($row["retislrmonto"]);
                    $saldo_real  = $monto_pagar - $deducciones;
                ?>
                <tr>
                    <td class="text-center">
                        <input type="radio" name="factura_seleccionada" id="x_id_<?php echo $i; ?>" 
                               onclick="validar_check(<?php echo $i; ?>);" 
                               value="<?php echo $row['id_documento'].'-'.$row['tipo_documento_desc']; ?>">
                    </td>
                    <td><small>VENTAS</small></td>
                    <td><?php echo $row["tipo_documento_desc"]; ?></td>
                    <td><strong><?php echo $row["nro_documento"]; ?></strong></td>
                    <td class="text-right">
                        <input type="text" id="x_pagar_<?php echo $i; ?>" 
                               class="form-control text-right form-control-sm" 
                               value="<?php echo number_format($monto_pagar, 2, ".", ""); ?>" 
                               data-valor="<?php echo $monto_pagar; ?>" readonly>
                    </td>
                    <td class="text-right">
                        <input type="text" id="x_saldo_<?php echo $i; ?>" 
                               class="form-control text-right form-control-sm" 
                               value="<?php echo number_format($saldo_real, 2, ".", ""); ?>" 
                               data-valor="<?php echo $saldo_real; ?>" readonly>
                    </td>
                </tr>
                <?php $i++; endwhile; ?>
            </tbody>
        </table>

        <input type="hidden" id="xCantidad" value="<?php echo $i; ?>">
        <input type="hidden" id="pagos" name="pagos" value="">
        <input type="hidden" id="monto" name="monto" value="">
        <input type="hidden" id="abono" name="abono" value="">
        <input type="hidden" id="abono2" name="abono2" value="">
        <input type="hidden" id="saldo" name="saldo" value="">
        <input type="hidden" id="xctrl" name="xctrl" value="">
    </div>
</div>
<?php
$html = ob_get_clean();

echo json_encode([
    "status" => "success",
    "count" => $total_filas,
    "facturas_html" => $html
]);
?>