<?php
session_start();
header('Content-Type: application/json');
include "connect.php"; 

// 1. Validación y Limpieza (Evitamos el error Undefined Index)
$cliente   = isset($_REQUEST["cliente"]) ? intval($_REQUEST["cliente"]) : 0;
$tipo_pago = $_REQUEST["tipo_pago"] ?? "";
$moneda    = !empty($_REQUEST["moneda"]) ? $_REQUEST["moneda"] : "USD";
$dsc       = isset($_REQUEST["dsc"]) ? intval($_REQUEST["dsc"]) : 0;
$puede     = ($tipo_pago == "RD") ? ($_REQUEST["puede"] ?? "S") : "S";

// 2. Manejo de Pagos (Decodificamos el JSON que viene del JS)
$pagos_json = $_REQUEST["pagos"] ?? "[]";
$lista_pagos = json_decode($pagos_json, true); 
if (!is_array($lista_pagos)) $lista_pagos = [];

$total_usd = 0.00;
$abono_rc = 0.00;
$abono_rd = 0.00;

// Obtener tasa del día
$sqlTasa = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1"; 
$rsTasa = mysqli_query($link, $sqlTasa);
$rowTasa = mysqli_fetch_array($rsTasa);
$tasa_cambio = $rowTasa["tasa"] ?? 1;

ob_start(); 
?>

<div class="container-fluid">
    <div class="col-md-12">
        <table class="table table-sm table-bordered">
            <thead class="thead-light">
                <?php if($tipo_pago != ""): 
                    $sqlT = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '$tipo_pago'"; 
                    $rowT = mysqli_fetch_array(mysqli_query($link, $sqlT));
                    $tipo_nombre = $rowT["valor2"] ?? "N/A";
                ?>
                <tr>
                    <th>Tipo</th>
                    <th><?= ($tipo_pago=="RC" || $tipo_pago=="RD") ? "Disponible" : ($tipo_pago=="EF" ? "" : "Referencia") ?></th>
                    <th class="text-right">Monto</th>
                    <th>Moneda</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" id="tipo_pago" value="<?= $tipo_pago ?>">
                        <input type="text" value="<?= $tipo_nombre ?>" class="form-control form-control-sm" readonly>
                    </td>
                    <td>
                        <?php 
                        if($tipo_pago == "RC" || $tipo_pago == "RD"){
                            $tabla_rec = ($tipo_pago == "RC") ? "recarga" : "recarga2";
                            $sqlR = "SELECT id, saldo FROM $tabla_rec WHERE cliente = $cliente ORDER BY id DESC LIMIT 1";
                            $rowR = mysqli_fetch_array(mysqli_query($link, $sqlR));
                            echo '<input type="hidden" id="referencia" value="'.($rowR["id"] ?? "").'">';
                            echo '<input type="text" id="disponible" value="'.number_format($rowR["saldo"] ?? 0, 2, ".", "").'" class="form-control form-control-sm text-right" readonly>';
                        } elseif($tipo_pago == "EF") {
                            echo '<input type="hidden" id="referencia" value="">';
                        } else {
                            echo '<input type="text" id="referencia" class="form-control form-control-sm">';
                        }
                        ?>
                    </td>
                    <td><input type="number" id="pagar_input" class="form-control form-control-sm text-right" step="0.01"></td>
                    <td>
                        <select id="moneda_input" class="form-control form-control-sm">
                            <?php 
                            $filtro = ($dsc >= 25) ? "AND SUBSTRING(valor1, 1, 3) <> 'Bs.'" : "";
                            $rsM = mysqli_query($link, "SELECT valor1 FROM parametro WHERE codigo = '006' $filtro");
                            while($rowM = mysqli_fetch_array($rsM)){
                                $sel = ($moneda == $rowM["valor1"]) ? "selected" : "";
                                echo "<option value='{$rowM["valor1"]}' $sel>{$rowM["valor1"]}</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-success btn-sm" id="btn_agregar_pago">Add</button></td>
                </tr>
                <?php endif; ?>
            </thead>
            <tbody>
                <?php foreach ($lista_pagos as $index => $pago): 
                    $sqlN = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '{$pago['tipo']}'";
                    $rowN = mysqli_fetch_array(mysqli_query($link, $sqlN));
                ?>
                    <tr class="table-info">
                        <td><?= $rowN["valor2"] ?></td>
                        <td><?= $pago['ref'] ?></td>
                        <td class="text-right"><?= number_format($pago['monto'], 2) ?></td>
                        <td class="text-center"><?= $pago['moneda'] ?></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="EliminarPago(<?= $index ?>)">X</button></td>
                    </tr>
                    <?php 
                    // Conversión monetaria
                    $monto_item = floatval($pago['monto']);
                    if($pago['moneda'] == "USD"){
                        $total_usd += $monto_item;
                    } else {
                        $sqlC = "SELECT tasa FROM tasa_usd WHERE moneda = '{$pago['moneda']}' ORDER BY id DESC LIMIT 1";
                        $rowC = mysqli_fetch_array(mysqli_query($link, $sqlC));
                        $tasa_item = $rowC["tasa"] ?? 1;
                        $total_usd += ($monto_item * $tasa_item) / $tasa_cambio;
                    }
                    if($pago['tipo'] == "RC") $abono_rc += $monto_item;
                    if($pago['tipo'] == "RD") $abono_rd += $monto_item;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$html = ob_get_clean();
echo json_encode([
    "status" => "success",
    "html" => $html,
    "totales" => [
        "total_usd" => round($total_usd, 2),
        "abono_rc" => $abono_rc,
        "abono_rd" => $abono_rd,
        "tasa_bcv" => $tasa_cambio
    ]
]);