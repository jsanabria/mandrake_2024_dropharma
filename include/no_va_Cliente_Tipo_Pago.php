<?php
session_start();
header('Content-Type: application/json');
include "connect.php"; 

// 1. Captura de parámetros básicos
$id_compra = isset($_REQUEST["id_compra"]) ? intval($_REQUEST["id_compra"]) : 0;
$cliente   = isset($_REQUEST["cliente"]) ? intval($_REQUEST["cliente"]) : 0;
$tipo_pago = $_REQUEST["tipo_pago"] ?? ""; 
$moneda    = $_REQUEST["moneda"] ?? "USD";

// 2. Manejo ROBUSTO del JSON de pagos
$pagos_json = $_REQUEST["pagos"] ?? "[]";
$pagos_json = stripslashes($pagos_json); 
$lista_pagos = json_decode($pagos_json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    $lista_pagos = json_decode(html_entity_decode($pagos_json), true);
}

if (!is_array($lista_pagos)) {
    $lista_pagos = [];
}

// 3. Datos de la factura (prioridad a id_compra si existe)
$nro_fact = $_REQUEST["nro_factura"] ?? "S/N";
$monto_total_fact = floatval($_REQUEST["monto_total"] ?? 0);

if ($id_compra > 0) {
    $sqlFact = "SELECT a.cliente, b.nombre AS nombre_cliente, a.nro_documento, a.total 
                FROM salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
                WHERE a.id = $id_compra";
    $rsFact = mysqli_query($link, $sqlFact);
    if($rowF = mysqli_fetch_assoc($rsFact)){
        $cliente = $rowF['cliente'];
        $nro_fact = $rowF['nro_documento'];
        $monto_total_fact = floatval($rowF['total']);
    }
}

// 3. CÁLCULO DE TOTALES
$total_usd = 0.00;
$abono_rc = 0.00; 
$abono_rd = 0.00;

$rsTasa = mysqli_query($link, "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1");
$tasa_bcv = ($rowTasa = mysqli_fetch_array($rsTasa)) ? floatval($rowTasa["tasa"]) : 1;

foreach ($lista_pagos as $p) {
    $monto_p = floatval($p['monto']);
    if($p['moneda'] == "USD"){
        $total_usd += $monto_p;
    } else {
        $rsC = mysqli_query($link, "SELECT tasa FROM tasa_usd WHERE moneda = '{$p['moneda']}' ORDER BY id DESC LIMIT 1");
        $rowC = mysqli_fetch_array($rsC);
        $tasa_p = ($rowC) ? floatval($rowC["tasa"]) : 1;
        $total_usd += ($monto_p * $tasa_p) / $tasa_bcv;
    }
    if($p['tipo'] == "RC") $abono_rc += $monto_p;
    if($p['tipo'] == "RD") $abono_rd += $monto_p;
}

$saldo_final = $monto_total_fact - $total_usd;

ob_start(); 
?>
<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm border rounded bg-white">
            <div class="card-body p-2">
                <table class="table table-sm table-borderless mb-0 text-center">
                    <thead>
                        <tr class="text-muted small fw-bold">
                            <th>DOCUMENTO</th><th>NRO. CONTROL</th><th>MONTO TOTAL</th><th>SALDO RESTANTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td class="fw-bold text-primary small">VENTAS</td>
                            <td class="fw-bold"><?= $nro_fact ?></td>
                            <td class="fw-bold">$ <?= number_format($monto_total_fact, 2) ?></td>
                            <td>
                                <span class="badge <?= ($saldo_final <= 0.01) ? 'bg-success' : 'bg-danger' ?> fs-6 shadow-sm">
                                    $ <?= number_format(max(0, $saldo_final), 2) ?>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm border rounded bg-white">
            <div class="card-body p-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Método</label>
                        <select id="tipo_pago" class="form-select form-select-sm fw-bold border-primary-subtle">
                            <option value="">Seleccione...</option>
                            <?php 
                            $rsMetodos = mysqli_query($link, "SELECT valor1, valor2 FROM parametro WHERE codigo = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
                            while($reg = mysqli_fetch_array($rsMetodos)){
                                $sel = ($tipo_pago == $reg['valor1']) ? "selected" : "";
                                echo "<option value='{$reg['valor1']}' $sel>{$reg['valor2']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Referencia / Saldo</label>
                        <?php if(in_array($tipo_pago, ["RC", "RD"])): 
                            $tabla = ($tipo_pago == "RC") ? "recarga" : "recarga2";
                            $rowR = mysqli_fetch_array(mysqli_query($link, "SELECT id, saldo FROM $tabla WHERE cliente = $cliente ORDER BY id DESC LIMIT 1"));
                        ?>
                            <input type="hidden" id="referencia" value="<?= $rowR["id"] ?? "" ?>">
                            <input type="text" id="disponible" value="<?= number_format($rowR["saldo"] ?? 0, 2, ".", "") ?>" class="form-control form-control-sm text-end fw-bold bg-light" readonly>
                        <?php else: ?>
                            <input type="text" id="referencia" class="form-control form-control-sm" <?= (in_array($tipo_pago, ["EF",""])) ? 'readonly placeholder="N/A"' : 'placeholder="Número..."' ?>>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Monto a Entregar</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="pagar_input" class="form-control fw-bold border-primary" step="0.01" value="<?= ($saldo_final > 0) ? round($saldo_final, 2) : '' ?>">
                            <select id="moneda_input" class="form-select bg-light" style="max-width: 90px;">
                                <?php 
                                $rsM = mysqli_query($link, "SELECT valor1 FROM parametro WHERE codigo = '006'");
                                while($rowM = mysqli_fetch_array($rsM)){
                                    $selM = ($moneda == $rowM["valor1"]) ? "selected" : "";
                                    echo "<option value='{$rowM["valor1"]}' $selM>{$rowM["valor1"]}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" id="btn_agregar_pago">ADD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="list-group shadow-sm border rounded">
            <div class="list-group-item bg-dark text-white py-2 small fw-bold">PAGOS REGISTRADOS (<?= count($lista_pagos) ?>)</div>
            <?php if(empty($lista_pagos)): ?>
                <div class="list-group-item text-center py-3 text-muted small">Aún no hay pagos registrados</div>
            <?php else: 
                foreach ($lista_pagos as $idx => $p): 
                    $rsN = mysqli_query($link, "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '{$p['tipo']}'");
                    $metodo_nom = ($rowN = mysqli_fetch_array($rsN)) ? $rowN["valor2"] : $p['tipo'];
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                        <span class="fw-bold d-block mb-0 small"><?= $metodo_nom ?></span>
                        <small class="text-muted" style="font-size: 0.7rem;">Ref: <?= $p['ref'] ?: 'N/A' ?></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-primary me-3 small"><?= $p['moneda'] ?> <?= number_format($p['monto'], 2) ?></span>
                        <!-- <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="EliminarPago(<?= $idx ?>)">X</button> -->
                        <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="EliminarPago(<?= $idx ?>)">X</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$html_out = ob_get_clean();
echo json_encode([
    "status" => "success",
    "html" => $html_out,
    "cliente_id" => $cliente,
    "cliente_nombre" => $nombre_cliente ?? "",
    "nro_factura" => $nro_fact,
    "monto_total" => $monto_total_fact,
    "totales" => [
        "total_usd" => round($total_usd, 2),
        "abono_rc" => $abono_rc,
        "abono_rd" => $abono_rd
    ]
]);