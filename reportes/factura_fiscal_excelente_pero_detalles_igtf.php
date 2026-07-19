<?php
session_start();
require('rcs/fpdf.php');
require("../include/connect.php");

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
$username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : "";
$test_fiscal = isset($_REQUEST["test_fiscal"]) ? intval($_REQUEST["test_fiscal"]) : 0;
$auto_return = isset($_REQUEST["auto_return"]) ? intval($_REQUEST["auto_return"]) : 0;
$generar_ne = strtoupper(trim($_REQUEST["generar_ne"] ?? "N"));

// =======================================================================
// PANTALLA PREVIA: REGISTRO DE PAGOS DE LA FACTURA
// -----------------------------------------------------------------------
// Antes de ejecutar la impresión fiscal, la factura debe tener su pago
// registrado (salidas.pagado = 'S'). Este bloque reimplementa, usando
// mysqli (este script no corre dentro del framework PHPMaker), el mismo
// proceso que ya funciona bien en RegistrarPagosClientes.php.
// =======================================================================

function h_pago($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function jsonResponsePago($arr) {
    if (ob_get_length()) { @ob_end_clean(); }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

function rows_pago($link, $sql) {
    $out = array();
    $rs = mysqli_query($link, $sql);
    if ($rs) {
        while ($r = mysqli_fetch_assoc($rs)) $out[] = $r;
    }
    return $out;
}

function row1_pago($link, $sql) {
    $rs = mysqli_query($link, $sql);
    if ($rs) {
        $r = mysqli_fetch_assoc($rs);
        if ($r) return $r;
    }
    return array();
}

function scalar_pago($link, $sql) {
    $rs = mysqli_query($link, $sql);
    if (!$rs) return null;
    $row = mysqli_fetch_row($rs);
    return $row ? $row[0] : null;
}

function factura_ya_pagada($link, $id) {
    $id = intval($id);
    if ($id <= 0) return false;
    $v = scalar_pago($link, "SELECT pagado FROM salidas WHERE id = $id LIMIT 1");
    return (trim((string)$v) === 'S');
}

// -----------------------------------------------------------------------
// Fragmento HTML del formulario/lista de pagos (equivalente al bloque
// "refrescar" de RegistrarPagosClientes.php)
// -----------------------------------------------------------------------
function construir_html_pagos($link, $id_compra, $pagos_json, $tipo_pago_actual) {
    $id_compra = intval($id_compra);
    $lista_pagos = json_decode($pagos_json, true);
    if (!is_array($lista_pagos)) $lista_pagos = array();

    ob_start();

    $sqlFact = "SELECT a.cliente, b.nombre AS nombre_cliente, a.nro_documento,
                        a.moneda,
                        CASE WHEN a.moneda <> 'Bs.' THEN (a.total * a.tasa_dia) ELSE a.total END AS total,
                        CASE WHEN a.moneda <> 'Bs.' THEN a.total ELSE (a.total / NULLIF(a.tasa_dia, 0)) END AS totalDivisa,
                        a.tasa_dia, a.total AS total_factura
                FROM salidas AS a
                JOIN cliente AS b ON b.id = a.cliente
                WHERE a.id = $id_compra";
    $factura = row1_pago($link, $sqlFact);

    if (!$factura) {
        echo "<div class='alert alert-danger'>No se encontró la factura #$id_compra.</div>";
        return ob_get_clean();
    }

    $compania_id = 1;

    $reglas = rows_pago($link, "
        SELECT compania, metodo, IFNULL(moneda,'') AS moneda, cuenta_destino_id, prioridad
        FROM pago_destino_regla
        WHERE compania = " . intval($compania_id) . " AND activo = 'S'
        ORDER BY prioridad DESC, id DESC
    ");
    $reglas_json = json_encode($reglas);

    $cuentas_destino = rows_pago($link, "
        SELECT a.id, b.campo_descripcion AS banco, a.tipo, a.numero
        FROM compania_cuenta a
        JOIN tabla b ON b.campo_codigo = a.banco AND b.tabla = 'BANCO'
        WHERE a.compania = " . intval($compania_id) . "
        AND a.mostrar = 'S' AND a.activo = 'S'
        ORDER BY b.campo_descripcion, a.numero
    ");

    $anticipos = array();
    if ($tipo_pago_actual === "AN") {
        $cliente_id = intval($factura["cliente"] ?? 0);

        $sqlAnt = "
            SELECT
            cc.id AS anticipo_id,
            cc.fecha,
            ccd.moneda,
            SUM(ccd.monto_moneda) AS monto_anticipo,
            COALESCE((
                SELECT SUM(a.monto_moneda) FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id AND a.moneda = ccd.moneda
            ), 0) AS monto_aplicado,
            (SUM(ccd.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda) FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id AND a.moneda = ccd.moneda
            ), 0)) AS saldo_disponible
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle ccd ON ccd.cobros_cliente = cc.id
            WHERE cc.cliente = " . intval($cliente_id) . "
            AND cc.id_documento = 0
            GROUP BY cc.id, cc.fecha, ccd.moneda
            HAVING saldo_disponible > 0.01
            ORDER BY cc.fecha DESC
        ";
        $anticipos = rows_pago($link, $sqlAnt);

        $aplicado_local = array();
        foreach ($lista_pagos as $pp) {
            if (trim($pp["tipo"] ?? "") !== "AN") continue;
            $aid = intval($pp["anticipo_id"] ?? 0);
            $m   = floatval($pp["monto"] ?? 0);
            $mo  = trim($pp["moneda"] ?? "");
            if ($aid > 0 && $m > 0 && $mo !== "") {
                if (!isset($aplicado_local[$aid])) $aplicado_local[$aid] = array();
                if (!isset($aplicado_local[$aid][$mo])) $aplicado_local[$aid][$mo] = 0;
                $aplicado_local[$aid][$mo] += $m;
            }
        }

        $tmp = array();
        foreach ($anticipos as $a) {
            $aid = intval($a["anticipo_id"] ?? 0);
            $mo  = trim($a["moneda"] ?? "");
            $saldo = floatval($a["saldo_disponible"] ?? 0);
            $rebaja = floatval($aplicado_local[$aid][$mo] ?? 0);
            $saldo2 = $saldo - $rebaja;
            if ($saldo2 > 0.01) {
                $a["saldo_disponible"] = $saldo2;
                $tmp[] = $a;
            }
        }
        $anticipos = $tmp;
    }

    $igtf_pct = floatval(scalar_pago($link, "SELECT alicuota AS IGTF FROM alicuota WHERE codigo = 'IGT' AND activo = 'S'") ?: 0);
    if ($igtf_pct > 0 && $igtf_pct < 1) {
        $igtf_pct = $igtf_pct * 100;
    }

    $total_factura  = trim($factura["total_factura"] ?? "Bs.");
    $moneda_doc = trim($factura["moneda"] ?? "Bs.");
    $moneda_doc_sel = $moneda_doc == "Bs." ? "USD" : $moneda_doc;
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);

    $total_bs  = floatval($factura["total"] ?? 0);
    $total_div = floatval($factura["totalDivisa"] ?? 0);

    $total_bs_pagado  = 0;

    foreach ($lista_pagos as $p) {
        $tipo_reg = trim($p["tipo"] ?? "");
        if ($tipo_reg === "IG") {
            $total_bs += floatval($p["monto"] ?? 0);
            continue;
        }
        $monto_p = floatval($p["monto"] ?? 0);
        $mon_p   = trim($p["moneda"] ?? "Bs.");
        if ($monto_p <= 0) continue;

        if ($mon_p === "Bs.") {
            $total_bs_pagado += $monto_p;
        } else {
            $total_bs_pagado += round($monto_p * $tasa_dia, 2);
        }
    }

    $saldo_bs  = $total_bs - $total_bs_pagado;
    $saldo_div = ($tasa_dia > 0) ? ($saldo_bs / $tasa_dia) : 0;

    if ($saldo_bs < 0.01) {
        $saldo_bs = 0;
        $saldo_div = 0;
    }

    $saldo_restante = $saldo_div;
    ?>

    <div class="card shadow-sm border-0 mb-3 bg-light">
        <div class="card-body p-3 text-center">
            <div class="row g-0">
                <div class="col-4 border-end">
                    <small class="text-muted d-block">CLIENTE</small>
                    <b><?= h_pago($factura['nombre_cliente']) ?></b>
                    <div class="small text-muted mt-1">Total Factura <?= h_pago($moneda_doc) ?>: <b> <?= number_format($total_factura, 2, ".", ",") ?></b></div>
                </div>
                <div class="col-4 border-end">
                    <small class="text-muted d-block">FACTURA</small>
                    <b><?= h_pago($factura['nro_documento']) ?></b>
                    <div class="small text-muted mt-1">Tasa día Bs.: <b> <?= number_format($tasa_dia, 2, ".", ",") ?></b></div>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block">PENDIENTE</small>
                    <div class="<?= ($saldo_bs <= 0.01) ? 'text-success' : 'text-danger' ?>">
                        <b>Bs. <?= number_format($saldo_bs, 2, ".", ",") ?></b>
                    </div>
                    <div class="small text-muted">
                        USD <?= number_format($saldo_div, 2, ".", ",") ?>
                    </div>
                </div>

                <input type="hidden" id="pendiente_bs" value="<?= $saldo_bs ?>">
                <input type="hidden" id="pendiente_div" value="<?= $saldo_div ?>">
                <input type="hidden" id="tasa_dia_doc" value="<?= $tasa_dia ?>">
                <input type="hidden" id="moneda_doc" value="<?= h_pago($moneda_doc) ?>">
                <input type="hidden" id="igtf_pct" value="<?= $igtf_pct ?>">
                <input type="hidden" id="reglas_destino_json" value='<?= h_pago($reglas_json) ?>'>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 border-top border-primary border-3">
    <div class="card-body">

        <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="small fw-bold">MÉTODO</label>
            <select id="tipo_pago" class="form-select form-select-sm" onchange="cambiarMetodo(this.value)">
            <option value="">Seleccione...</option>
            <?php
            $metodos = rows_pago($link, "SELECT valor1, valor2 FROM parametro WHERE codigo = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
            foreach ($metodos as $m) {
                $sel = ($tipo_pago_actual == $m['valor1']) ? "selected" : "";
                echo "<option value='" . h_pago($m['valor1']) . "' $sel>" . h_pago($m['valor2']) . "</option>";
            }
            ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="small fw-bold">MONTO</label>
            <input type="number" id="monto_input" class="form-control form-control-sm fw-bold border-primary"
                step="0.01" value="<?= ($saldo_bs > 0) ? round($saldo_bs, 2) : '' ?>">
        </div>

        <div class="col-md-2">
            <label class="small fw-bold">MONEDA</label>
            <select id="moneda_input" class="form-select form-select-sm">
            <?php
            $lista = array("Bs.", $moneda_doc_sel);
            $lista = array_values(array_unique($lista));
            $lista_sql = array();
            foreach ($lista as $v) $lista_sql[] = "'" . mysqli_real_escape_string($link, $v) . "'";
            $sqlMon = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor1 IN (" . implode(",", $lista_sql) . ")";
            $monedas = rows_pago($link, $sqlMon);
            foreach ($monedas as $mon) {
                echo "<option value='" . h_pago($mon['valor1']) . "'>" . h_pago($mon['valor1']) . "</option>";
            }
            ?>
            </select>
        </div>
        </div>

        <div class="row g-2 align-items-end mt-1">

        <div class="col-md-4">
            <label class="small fw-bold">BANCO</label>
            <?php
            $bancos = rows_pago($link, "SELECT campo_codigo AS codigo, campo_descripcion AS descripcion
                                FROM tabla WHERE tabla = 'BANCO'
                                ORDER BY campo_descripcion");
            ?>
            <select id="banco_input" class="form-select form-select-sm" style="max-width: 240px;">
                <option value="">Banco...</option>
                <?php foreach ($bancos as $b): ?>
                    <option value="<?= h_pago($b["codigo"]) ?>" data-code="<?= h_pago($b["codigo"]) ?>">
                        <?= h_pago($b["descripcion"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="small fw-bold">REFERENCIA</label>
            <?php if (in_array($tipo_pago_actual, array("RC", "RD"))):
                $tabla_recarga = ($tipo_pago_actual == "RC") ? "recarga" : "recarga2";
                $recarga = row1_pago($link, "SELECT id, saldo FROM $tabla_recarga WHERE cliente = " . intval($factura['cliente']) . " ORDER BY id DESC LIMIT 1");
            ?>
                <input type="hidden" id="ref_input" value="<?= h_pago($recarga['id'] ?? '') ?>">
                <input type="text" class="form-control form-control-sm bg-white fw-bold"
                    value="<?= number_format($recarga['saldo'] ?? 0, 2, ".", "") ?>" readonly>
            <?php else: ?>
                <input type="text" id="ref_input" class="form-control form-control-sm" placeholder="Referencia...">
            <?php endif; ?>
        </div>

            <div class="col-md-4 d-flex flex-column justify-content-end">
                <button type="button" class="btn btn-primary btn-sm w-100 fw-bold" onclick="return agregarPago(event)">
                    ADD
                </button>
                <small class="mt-2 text-muted" id="help_ref_banco"></small>
            </div>
        </div>

        <div class="row g-2 align-items-end mt-1">
            <div class="col-md-4">
            <div class="d-flex align-items-center justify-content-between">
                <label class="small fw-bold mb-1">CUENTA DESTINO</label>
                <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" id="destino_auto" checked>
                <label class="form-check-label small" for="destino_auto">Auto</label>
                </div>
            </div>

            <select id="destino_input" class="form-select form-select-sm" style="max-width: 260px;">
                <option value="">Seleccione...</option>
                <?php foreach ($cuentas_destino as $c): ?>
                <option value="<?= intval($c["id"]) ?>">
                    <?= h_pago(trim($c["banco"])) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <small class="text-muted d-block mt-1" id="help_destino"></small>
            </div>
        </div>

        <div class="row g-2 align-items-end mt-1">
            <?php if ($tipo_pago_actual === "AN"): ?>
            <div class="col-md-12">
                <label class="small fw-bold">ANTICIPO DISPONIBLE</label>
                <select id="anticipo_input" class="form-select form-select-sm">
                    <option value="">Seleccione...</option>
                    <?php foreach ($anticipos as $a):
                        $idA = intval($a["anticipo_id"]);
                        $monA = trim($a["moneda"] ?? "");
                        $saldoA = floatval($a["saldo_disponible"] ?? 0);
                        $txt = "#{$idA} | {$monA} " . number_format($saldoA, 2, ".", ",") . " | " . (string)($a["fecha"] ?? "");
                    ?>
                        <option value="<?= $idA ?>" data-moneda="<?= h_pago($monA) ?>" data-saldo="<?= h_pago($saldoA) ?>">
                            <?= h_pago($txt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1">El monto se tomará del saldo del anticipo seleccionado.</small>
            </div>
            <?php endif; ?>
        </div>

    </div>
    </div>

    <div class="list-group shadow-sm">
        <div class="list-group-item bg-dark text-white py-1 small fw-bold">PAGOS REGISTRADOS (<?= count($lista_pagos) ?>)</div>
        <?php foreach ($lista_pagos as $idx => $p): ?>
            <?php $esIG = (trim($p["tipo"] ?? "") === "IG"); ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                <div>
                    <span class="fw-bold d-block small <?= $esIG ? "text-danger" : "" ?>">
                        <?= h_pago($p['metodo_nom'] ?? '') ?><?= $esIG ? " (IGTF)" : "" ?>
                    </span>
                    <small class="text-muted">
                        Ref: <?= h_pago($p['ref'] ?? '') ?>
                        <?php if (!empty($p["banco_nom"])): ?> · Banco: <?= h_pago($p["banco_nom"]) ?><?php endif; ?>
                        <?php if (!empty($p["destino_nom"])): ?> · Destino: <?= h_pago($p["destino_nom"]) ?><?php endif; ?>
                    </small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold text-primary me-3">
                        <?= h_pago($p["moneda"] ?? "") ?> <?= number_format(floatval($p["monto"] ?? 0), 2, ".", ",") ?>
                    </span>
                    <?php if (!$esIG): ?>
                        <button type="button" class="btn btn-sm text-danger" onclick="eliminarPago(<?= $idx ?>)">X</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm text-danger" disabled style="visibility:hidden;">X</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $("#btn-registrar-final").prop('disabled', <?= ($saldo_restante > 0.01) ? 'true' : 'false' ?>);
        (function(){
            const tipo = ($("#tipo_pago").val() || "").trim();
            const requiereBanco = !["EF","RI","RC","IG","RS","AN"].includes(tipo);
            const requiereRef   = !["EF","RC","IG","AN"].includes(tipo);
            $("#banco_input").prop("disabled", !requiereBanco);
            $("#help_ref_banco").text(
                (["RS","RI"].includes(tipo)) ? "Requiere: referencia." :
                (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
                (requiereRef) ? "Requiere: referencia." : ""
            );
        })();
    </script>
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------
// Guarda el pago (equivalente al bloque "finalizar" de
// RegistrarPagosClientes.php) y responde JSON.
// -----------------------------------------------------------------------
function finalizar_pago_factura($link, $id_compra, $username, $pagos_json, $confirmar_exceso = "0") {
    $id_compra = intval($id_compra);
    $lista = json_decode($pagos_json, true);

    if (!is_array($lista) || count($lista) == 0) {
        jsonResponsePago(array("success" => false, "message" => "No hay pagos para registrar."));
    }

    $sqlFact = "SELECT a.id, a.cliente, a.nro_documento, a.moneda,
                       CASE WHEN a.moneda <> 'Bs.' THEN (a.total * a.tasa_dia) ELSE a.total END AS total_bs,
                       CASE WHEN a.moneda <> 'Bs.' THEN a.total ELSE (a.total / NULLIF(a.tasa_dia, 0)) END AS total_div,
                       a.tasa_dia
                FROM salidas a
                WHERE a.id = $id_compra";
    $factura = row1_pago($link, $sqlFact);
    if (!$factura) {
        jsonResponsePago(array("success" => false, "message" => "Factura no encontrada."));
    }

    $cliente_id = intval($factura["cliente"] ?? 0);
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);
    $total_bs   = floatval($factura["total_bs"] ?? 0);

    foreach ($lista as $pp) {
        if (trim($pp["tipo"] ?? "") === "IG")
            $total_bs += floatval($pp["monto"] ?? 0);
    }

    $pagado_bs = 0;
    foreach ($lista as $p) {
        $tipo = trim($p["tipo"] ?? "");
        if ($tipo === "IG") continue;
        $monto = floatval($p["monto"] ?? 0);
        $mon   = trim($p["moneda"] ?? "Bs.");
        if ($monto <= 0) continue;
        $bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);
        $pagado_bs += $bs;
    }

    $vuelto_bs = $pagado_bs - $total_bs;
    $vuelto_usd_info = 0;

    // Si el pago excede el total, no se bloquea de una vez: se le pide
    // confirmación al usuario mostrando el vuelto (Bs. y USD). Solo si ya
    // confirmó (confirmar_exceso=1) se continúa, pero RECORTANDO los montos
    // a registrar para que nunca se sobregire la cuenta del cliente: el
    // excedente (vuelto) no se guarda como abono/pago.
    if ($vuelto_bs > 0.02) {
        if ($confirmar_exceso !== "1") {
            $vuelto_usd = ($tasa_dia > 0) ? ($vuelto_bs / $tasa_dia) : 0;
            jsonResponsePago(array(
                "success" => false,
                "requiere_confirmacion" => true,
                "message" => "El total pagado excede el total de la factura.",
                "total_bs"   => round($total_bs, 2),
                "pagado_bs"  => round($pagado_bs, 2),
                "vuelto_bs"  => round($vuelto_bs, 2),
                "vuelto_usd" => round($vuelto_usd, 2)
            ));
        }

        // ---- Recorte de montos (capping) ----
        // Se guarda el vuelto informativo para el mensaje final.
        $vuelto_usd_info = ($tasa_dia > 0) ? ($vuelto_bs / $tasa_dia) : 0;

        $igtf_pct_cap = floatval(scalar_pago($link, "SELECT alicuota AS IGTF FROM alicuota WHERE codigo = 'IGT' AND activo = 'S'") ?: 0);
        if ($igtf_pct_cap > 0 && $igtf_pct_cap < 1) {
            $igtf_pct_cap = $igtf_pct_cap * 100;
        }

        $remaining = $vuelto_bs;
        $gids_removidos = array();   // gid de pagos eliminados por completo
        $gids_ajustados = array();   // gid => nuevo monto_bs del pago (en Bs.)

        // Se recorta empezando por el último pago agregado (normalmente el
        // efectivo que generó el vuelto), sin tocar anticipos (AN), que no
        // se pueden "devolver como vuelto".
        for ($i = count($lista) - 1; $i >= 0 && $remaining > 0.02; $i--) {
            $p = $lista[$i];
            $tipo = trim($p["tipo"] ?? "");
            if ($tipo === "IG" || $tipo === "AN") continue;

            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0) continue;

            $item_bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);
            $gid = (string)($p["gid"] ?? "");

            if ($item_bs <= $remaining + 0.0001) {
                // Se absorbe completo: este pago desaparece del registro.
                $remaining -= $item_bs;
                if ($gid !== "") $gids_removidos[] = $gid;
                $lista[$i]["monto"] = 0; // marcado para descartar más abajo
            } else {
                $nuevo_item_bs = $item_bs - $remaining;
                $nuevo_monto = ($mon === "Bs.") ? $nuevo_item_bs : (($tasa_dia > 0) ? ($nuevo_item_bs / $tasa_dia) : 0);
                $lista[$i]["monto"] = round($nuevo_monto, 2);
                if ($gid !== "") $gids_ajustados[$gid] = round($nuevo_item_bs, 2);
                $remaining = 0;
            }
        }

        // Recalcular/eliminar las líneas de IGTF (tipo IG) asociadas a los
        // pagos recortados o eliminados, para que el IGTF no quede inflado
        // ni referenciando un pago que ya no existe.
        foreach ($lista as $i => $p) {
            $tipo = trim($p["tipo"] ?? "");
            if ($tipo !== "IG") continue;
            $gid = (string)($p["gid"] ?? "");
            if ($gid === "") continue;

            if (in_array($gid, $gids_removidos, true)) {
                $lista[$i]["monto"] = 0; // se elimina junto con su pago
            } elseif (isset($gids_ajustados[$gid])) {
                $nuevo_igtf = ($gids_ajustados[$gid] * $igtf_pct_cap) / 100;
                $lista[$i]["monto"] = round($nuevo_igtf, 2);
            }
        }

        // Descartar del arreglo cualquier línea que haya quedado en 0.
        $lista = array_values(array_filter($lista, function ($p) {
            return floatval($p["monto"] ?? 0) > 0;
        }));

        // Recalcular pagado_bs y total_bs (con IGTF ya recortado) desde la
        // lista ajustada, para dejar todo consistente antes de insertar.
        $total_bs = floatval($factura["total_bs"] ?? 0);
        foreach ($lista as $pp) {
            if (trim($pp["tipo"] ?? "") === "IG") {
                $total_bs += floatval($pp["monto"] ?? 0);
            }
        }
        $pagado_bs = 0;
        foreach ($lista as $p) {
            $tipo = trim($p["tipo"] ?? "");
            if ($tipo === "IG") continue;
            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0) continue;
            $bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);
            $pagado_bs += $bs;
        }

        // Salvaguarda: si tras recortar los pagos "recortables" (todo menos
        // anticipos) el pago sigue excediendo el total, es porque el
        // excedente proviene de un anticipo (AN), que no se puede devolver
        // como vuelto. En ese caso se corta el proceso con un mensaje claro
        // en vez de dejar pasar un sobregiro silencioso.
        if (($pagado_bs - $total_bs) > 0.02) {
            jsonResponsePago(array(
                "success" => false,
                "message" => "No se pudo ajustar el pago al total de la factura porque el excedente proviene " .
                             "de un anticipo aplicado, y un anticipo no puede devolverse como vuelto. " .
                             "Ajuste manualmente el monto del anticipo o de los demás pagos."
            ));
        }
    }

    foreach ($lista as $p) {
        $tipo = trim($p["tipo"] ?? "");
        if ($tipo !== "AN") continue;

        $anticipo_id = intval($p["anticipo_id"] ?? 0);
        $monto = floatval($p["monto"] ?? 0);
        $mon   = trim($p["moneda"] ?? "");

        if ($anticipo_id <= 0) jsonResponsePago(array("success" => false, "message" => "Anticipo inválido (anticipo_id)."));
        if ($monto <= 0) jsonResponsePago(array("success" => false, "message" => "Monto inválido para anticipo."));
        if ($mon === "") jsonResponsePago(array("success" => false, "message" => "Moneda inválida para anticipo."));

        $mon_sql = mysqli_real_escape_string($link, $mon);
        $sqlSaldoAnt = "
            SELECT
              (SUM(ccd.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda) FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id AND a.moneda = ccd.moneda
              ), 0)) AS saldo
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle ccd ON ccd.cobros_cliente = cc.id
            WHERE cc.id = " . intval($anticipo_id) . "
              AND cc.cliente = " . intval($cliente_id) . "
              AND cc.id_documento = 0
              AND ccd.moneda = '$mon_sql'
            GROUP BY cc.id, ccd.moneda
        ";
        $saldoAnt = floatval(scalar_pago($link, $sqlSaldoAnt) ?: 0);
        if ($saldoAnt + 0.0001 < $monto) {
            jsonResponsePago(array(
                "success" => false,
                "message" => "El anticipo #$anticipo_id no tiene saldo suficiente en $mon. Disponible: " .
                             number_format($saldoAnt, 2, ".", ",") .
                             " / Requerido: " . number_format($monto, 2, ".", ",")
            ));
        }
    }

    // ----------------- INSERTS (TRANSACCIÓN) -----------------
    $username_sql = mysqli_real_escape_string($link, trim($username) != "" ? trim($username) : "NA.NA");
    $fecha_sql = mysqli_real_escape_string($link, date("Y-m-d H:i:s"));

    mysqli_begin_transaction($link);
    try {
        $sqlInsCab = "
            INSERT INTO cobros_cliente
            (cliente, id_documento, fecha, fecha_registro, username, moneda, tasa_cambio, monto)
            VALUES
            (" . intval($cliente_id) . ", " . intval($id_compra) . ",
            '$fecha_sql', '$fecha_sql', '$username_sql', 'Bs.',
            " . floatval($tasa_dia) . ", " . floatval($pagado_bs) . ")
        ";
        if (!mysqli_query($link, $sqlInsCab)) {
            throw new \Exception("No se pudo insertar cabecera de cobro: " . mysqli_error($link));
        }

        $cobro_id = intval(mysqli_insert_id($link));
        if ($cobro_id <= 0) {
            throw new \Exception("No se pudo obtener ID de cobro (cabecera).");
        }

        $x_igtf = "N";
        $x_monto_base_igtf = 0;
        $x_monto_igtf = 0;

        foreach ($lista as $p) {
            $tipo  = trim($p["tipo"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0 || $tipo === "") continue;

            $monto_bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);

            if ($tipo !== "IG" && $mon !== "Bs.") {
                $x_monto_base_igtf += $monto_bs;
            }
            if ($tipo === "IG") {
                $x_igtf = "S";
                $mon = "Bs.";
                $monto_bs = $monto;
                $x_monto_igtf += $monto_bs;
            }

            $tasa_moneda = ($mon === "Bs.") ? 1 : $tasa_dia;

            $ref = trim($p["ref"] ?? "");
            $banco_origen = trim($p["banco_cod"] ?? "");
            $destino_id = intval($p["destino_id"] ?? 0);
            $anticipo_id = ($tipo === "AN") ? intval($p["anticipo_id"] ?? 0) : 0;

            $sqlBancoDestino = ($destino_id > 0) ? (string)$destino_id : "NULL";
            $sqlBancoOrigen  = ($banco_origen !== "") ? ("'" . mysqli_real_escape_string($link, $banco_origen) . "'") : "NULL";
            $sqlRef          = ($ref !== "") ? ("'" . mysqli_real_escape_string($link, $ref) . "'") : "NULL";
            $sqlAnticipo     = ($tipo === "AN") ? (string)$anticipo_id : "NULL";

            $sqlInsDet = "
                INSERT INTO cobros_cliente_detalle
                (cobros_cliente, metodo_pago, referencia,
                monto_moneda, moneda, tasa_moneda, monto_bs,
                banco_origen, banco, anticipo_id)
                VALUES
                (" . intval($cobro_id) . ",
                '" . mysqli_real_escape_string($link, $tipo) . "',
                $sqlRef,
                " . floatval($monto) . ",
                '" . mysqli_real_escape_string($link, $mon) . "',
                " . floatval($tasa_moneda) . ",
                " . floatval($monto_bs) . ",
                $sqlBancoOrigen, $sqlBancoDestino, $sqlAnticipo)
            ";
            if (!mysqli_query($link, $sqlInsDet)) {
                throw new \Exception("No se pudo insertar detalle de cobro: " . mysqli_error($link));
            }

            if ($tipo === "AN") {
                if ($anticipo_id <= 0) {
                    throw new \Exception("Anticipo inválido (anticipo_id).");
                }

                $okAnt = scalar_pago($link, "
                    SELECT COUNT(*) FROM cobros_cliente
                    WHERE id = " . intval($anticipo_id) . "
                    AND cliente = " . intval($cliente_id) . "
                    AND id_documento = 0
                    LIMIT 1
                ");
                if (intval($okAnt) <= 0) {
                    throw new \Exception("El anticipo #$anticipo_id no pertenece a este cliente o no es un anticipo válido.");
                }

                $mon_sql2 = mysqli_real_escape_string($link, $mon);
                $sqlInsApp = "
                    INSERT INTO anticipos_aplicaciones
                    (anticipo_cobro_id, cobro_factura_id, salida_id, fecha, username,
                    monto_moneda, moneda, tasa_factura)
                    VALUES
                    (" . intval($anticipo_id) . ", " . intval($cobro_id) . ", " . intval($id_compra) . ",
                    NOW(), '$username_sql', " . floatval($monto) . ", '$mon_sql2', " . floatval($tasa_dia) . ")
                ";
                if (!mysqli_query($link, $sqlInsApp)) {
                    throw new \Exception("No se pudo insertar aplicación de anticipo: " . mysqli_error($link));
                }
            }
        }

        if (!mysqli_query($link, "UPDATE salidas SET pagado = 'S' WHERE id = " . intval($id_compra))) {
            throw new \Exception("No se pudo marcar la factura como pagada: " . mysqli_error($link));
        }

        if ($x_igtf === "S") {
            mysqli_query($link, "UPDATE salidas SET igtf = 'S', monto_base_igtf = $x_monto_base_igtf, monto_igtf = $x_monto_igtf WHERE id = " . intval($id_compra));
        }

        mysqli_commit($link);

        $msg_ok = "Cobro registrado correctamente.";
        if ($vuelto_bs > 0.02) {
            $msg_ok .= " Vuelto entregado: Bs. " . number_format($vuelto_bs, 2, ".", ",") .
                       " / USD " . number_format($vuelto_usd_info, 2, ".", ",") .
                       " (no se registró como abono).";
        }

        jsonResponsePago(array(
            "success" => true,
            "message" => $msg_ok,
            "cobro_id" => $cobro_id,
            "vuelto_bs" => round($vuelto_bs, 2),
            "vuelto_usd" => round($vuelto_usd_info, 2)
        ));

    } catch (\Throwable $ex) {
        mysqli_rollback($link);
        jsonResponsePago(array(
            "success" => false,
            "message" => "Error guardando cobro: " . $ex->getMessage()
        ));
    }
}

// -----------------------------------------------------------------------
// Página completa de registro de pago (se muestra cuando la factura no
// está pagada). Contiene su propio bloque AJAX.
// -----------------------------------------------------------------------
function render_pagina_pago($link, $id, $username, $auto_return, $generar_ne) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrar pago de factura</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    body { background: #f4f6f9; }
    .input-group .form-control, .input-group .form-select { border-radius: 4px !important; }
</style>
</head>
<body>

<div class="container-fluid py-3" style="max-width: 900px;">
    <h4 class="mb-3">Registrar pago de la factura #<?= intval($id) ?></h4>
    <div id="div-ajax">
        <div class="text-center p-5"><div class="spinner-border text-primary"></div></div>
    </div>
    <input type="hidden" id="json_pagos" value="[]">
    <div class="mt-4 text-center">
        <button type="button" id="btn-registrar-final" class="btn btn-success btn-lg px-5 shadow fw-bold" disabled onclick="finalizar()">
            REGISTRAR PAGO Y EMITIR FACTURA
        </button>
        <button type="button" id="btn-cancelar" class="btn btn-outline-secondary btn-lg px-5 shadow-sm fw-bold" onclick="return cancelar(event)">
            CANCELAR
        </button>
    </div>

    <div class="modal fade" id="mdlCancelar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar cancelación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">Se perderán los pagos agregados.<br><br>¿Desea continuar?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarCancelar">Sí, cancelar</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdlVuelto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">El pago excede el total de la factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-6 text-muted">Total Factura Bs:</div>
                    <div class="col-6 text-end fw-bold" id="vuelto_total_bs">-</div>

                    <div class="col-6 text-muted">Pagado Bs:</div>
                    <div class="col-6 text-end fw-bold" id="vuelto_pagado_bs">-</div>

                    <div class="col-12"><hr class="my-1"></div>

                    <div class="col-6 text-success">Vuelto Bs:</div>
                    <div class="col-6 text-end fw-bold text-success" id="vuelto_bs">-</div>

                    <div class="col-6 text-success">Vuelto USD:</div>
                    <div class="col-6 text-end fw-bold text-success" id="vuelto_usd">-</div>
                </div>
                <div class="small text-muted mt-3">
                    El pago registrado supera el total de la factura. Verifique el vuelto a entregar al cliente antes de continuar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success fw-bold" id="btnContinuarVuelto">Continuar</button>
            </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const ID_COMPRA = <?= intval($id) ?>;
let ajaxInFlight = false;

function refrescar(metodo = "") {
    const data = {
        accion: "pago_refrescar",
        id_compra: ID_COMPRA,
        pagos: $("#json_pagos").val(),
        tipo_pago: metodo
    };
    $.ajax({
        url: window.location.pathname + window.location.search,
        type: "POST",
        data: data,
        success: function (r) {
            $("#div-ajax").html(r);
            recalcularMontoSegunMoneda();
            initBancoSelect2();
            aplicarDestinoAuto(true);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            $("#div-ajax").html("<div class='alert alert-danger'>AJAX falló (revisa Network/Console).</div>");
        }
    });
}

function cambiarMetodo(v) { refrescar(v); }

function agregarPago(e) {
    e = e || window.event;
    if (e) { e.preventDefault(); e.stopPropagation(); }

    const ref = ($("#ref_input").val() || "").trim();
    let bancoCod = ($("#banco_input").val() || "").toString().trim();
    if (!bancoCod && $("#banco_input").hasClass("select2-hidden-accessible")) {
        const d = $("#banco_input").select2("data") || [];
        if (d.length && d[0].id) bancoCod = (d[0].id || "").toString().trim();
    }
    const bancoNom = ($("#banco_input option:selected").text() || "").trim();

    const tipo = ($("#tipo_pago").val() || "").trim();
    const m = parseFloat($("#monto_input").val());
    const monSel = ($("#moneda_input").val() || "Bs.").trim();

    if (!tipo) { alert("Seleccione un método de pago."); return false; }
    if (isNaN(m) || m <= 0) { alert("Indique un monto válido."); return false; }

    const requiereRef = !["EF", "RC", "IG", "AN"].includes(tipo);
    const requiereBanco = !["EF", "RI", "RC", "IG", "RS", "AN"].includes(tipo);
    const destinoId = ($("#destino_input").val() || "").toString().trim();
    const requiereDestino = ["EF","TD","TC","TR","CH","DP","PM"].includes(tipo);

    if (requiereDestino && !destinoId) { alert("Debe seleccionar la cuenta destino."); return false; }

    if (["RS", "RI"].includes(tipo)) {
        if (!ref) { alert("Debe indicar el número de referencia."); return false; }
    } else {
        if (requiereRef && !ref) { alert("Debe indicar el número de referencia."); return false; }
        if (requiereBanco && !bancoCod) { alert("Debe seleccionar un banco."); return false; }
    }

    if (ajaxInFlight) return false;

    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (err) { lista = []; }

    const gid = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);

    let anticipoId = null;
    if (tipo === "AN") {
        anticipoId = ($("#anticipo_input").val() || "").trim();
        if (!anticipoId) { alert("Debe seleccionar un anticipo."); return false; }

        const monAnt = ($("#anticipo_input option:selected").data("moneda") || "").toString().trim();
        if (monAnt && monSel && monAnt !== monSel) {
            alert("La moneda del pago debe coincidir con la moneda del anticipo.");
            return false;
        }

        const saldoAnt = parseFloat($("#anticipo_input option:selected").data("saldo") || "0");
        if (!isNaN(saldoAnt) && m > saldoAnt + 0.0001) {
            alert("El monto excede el saldo disponible del anticipo.");
            return false;
        }
    }

    const item = {
        gid: gid,
        tipo: tipo,
        metodo_nom: $("#tipo_pago option:selected").text(),
        ref: ref || "N/A",
        banco_cod: bancoCod || "",
        banco_nom: (bancoCod ? bancoNom : ""),
        monto: m,
        moneda: monSel
    };

    if (tipo === "AN") {
        item.anticipo_id = parseInt(anticipoId, 10) || 0;
        item.ref = "ANTICIPO #" + item.anticipo_id;
        item.banco_cod = "";
        item.banco_nom = "";
    }

    item.destino_id = parseInt(destinoId, 10) || 0;
    item.destino_nom = ($("#destino_input option:selected").text() || "").trim();

    lista.push(item);

    const igtfPct = parseFloat($("#igtf_pct").val() || "0");
    const tasaDiaDoc = parseFloat($("#tasa_dia_doc").val() || "1");

    if (monSel !== "Bs." && igtfPct > 0) {
        const montoPagoBs = (tasaDiaDoc > 0) ? (m * tasaDiaDoc) : 0;
        const montoIgtfBs = (montoPagoBs * igtfPct) / 100;

        lista = lista.filter(x => !((x.gid || "") === gid && (x.tipo || "") === "IG"));

        lista.push({
            gid: gid, tipo: "IG", metodo_nom: "IGTF", ref: "AUTO",
            banco_cod: "", banco_nom: "",
            monto: parseFloat(montoIgtfBs.toFixed(2)), moneda: "Bs.", es_igtf: 1,
            destino_id: parseInt(destinoId, 10) || 0,
            destino_nom: ($("#destino_input option:selected").text() || "").trim()
        });
    }

    $("#json_pagos").val(JSON.stringify(lista));
    refrescar(tipo);
    return false;
}

function eliminarPago(i) {
    if (ajaxInFlight) return false;

    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (err) { lista = []; }

    const item = lista[i];
    if (!item) return false;
    if ((item.tipo || "").trim() === "IG") return false;

    const gid = (item.gid || "").toString();
    lista.splice(i, 1);

    if (gid) {
        lista = lista.filter(x => !(((x.gid || "").toString() === gid) && ((x.tipo || "").trim() === "IG")));
    }

    $("#json_pagos").val(JSON.stringify(lista));
    refrescar($("#tipo_pago").val() || "");
    return false;
}

function fmtMoneda(n) {
    n = parseFloat(n || 0);
    return n.toLocaleString("es-VE", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function mostrarModalVuelto(resp) {
    $("#vuelto_total_bs").text(fmtMoneda(resp.total_bs));
    $("#vuelto_pagado_bs").text(fmtMoneda(resp.pagado_bs));
    $("#vuelto_bs").text(fmtMoneda(resp.vuelto_bs));
    $("#vuelto_usd").text(fmtMoneda(resp.vuelto_usd));

    const el = document.getElementById("mdlVuelto");
    const modal = bootstrap.Modal.getOrCreateInstance(el);

    $("#btnContinuarVuelto").off("click").on("click", function () {
        modal.hide();
        finalizar(true);
    });

    modal.show();
}

function finalizar(forzarConExceso) {
    const pagos = $("#json_pagos").val() || "[]";
    const data = {
        accion: "pago_finalizar",
        id_compra: ID_COMPRA,
        pagos: pagos,
        confirmar_exceso: forzarConExceso ? "1" : "0"
    };

    ajaxInFlight = true;
    $.ajax({
        url: window.location.pathname + window.location.search,
        type: "POST",
        dataType: "text",
        data: data,
        success: function (txt) {
            ajaxInFlight = false;
            let resp = null;
            try { resp = JSON.parse(txt); } catch (e) {}

            if (resp && resp.success) {
                // Pago registrado: se recarga esta misma página, que ahora
                // encontrará la factura pagada y continuará con la
                // impresión fiscal automáticamente.
                window.location.href = window.location.pathname
                    + "?id=<?= intval($id) ?>&username=<?= urlencode($username) ?>"
                    + "&auto_return=<?= intval($auto_return) ?>&generar_ne=<?= urlencode($generar_ne) ?>";
            } else if (resp && resp.requiere_confirmacion) {
                mostrarModalVuelto(resp);
            } else {
                alert((resp && resp.message) ? resp.message : (txt || "Respuesta vacía del servidor."));
            }
        },
        error: function (xhr, status, err) {
            ajaxInFlight = false;
            alert("Error servidor: " + status + " / " + err + "\n\n" + (xhr.responseText || ""));
        }
    });
    return false;
}

function cancelar(e) {
    e = e || window.event;
    if (e) { e.preventDefault(); e.stopPropagation(); }

    let lista = [];
    const raw = ($("#json_pagos").val() || "").trim();
    if (raw && raw !== "[]") {
        try {
            const tmp = JSON.parse(raw);
            if (Array.isArray(tmp)) lista = tmp;
        } catch (err) { lista = ["__json_invalido__"]; }
    }

    const itemsDom = $("#div-ajax .list-group .list-group-item").length;
    const pagosDom = Math.max(0, itemsDom - 1);
    const hayPagos = (lista.length > 0) || (pagosDom > 0);
    const url = "../ViewOutTdcfcvView/<?= intval($id) ?>?showdetail=";

    if (!hayPagos) { window.location.href = url; return false; }

    try {
        const el = document.getElementById("mdlCancelar");
        if (el && window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            modal.show();
            $("#btnConfirmarCancelar").off("click").on("click", function () {
                window.location.href = url;
            });
            return false;
        }
    } catch (err) {}

    if (confirm("¿Está seguro que desea cancelar?\n\nSe perderán los pagos agregados.")) {
        window.location.href = url;
    }
    return false;
}

function recalcularMontoSegunMoneda() {
    const saldoBs = parseFloat($("#pendiente_bs").val() || "0");
    const tasaDia = parseFloat($("#tasa_dia_doc").val() || "1");
    const monSel  = ($("#moneda_input").val() || "Bs.").trim();
    if (saldoBs <= 0) return;
    if (monSel === "Bs.") {
        $("#monto_input").val(saldoBs.toFixed(2));
    } else {
        const v = (tasaDia > 0) ? (saldoBs / tasaDia) : 0;
        $("#monto_input").val(v.toFixed(2));
    }
}

function initBancoSelect2() {
    const $sel = $("#banco_input");
    if ($sel.length === 0) return;
    if ($sel.hasClass("select2-hidden-accessible")) $sel.select2("destroy");
    if (!$.fn.select2) return;

    $sel.select2({
        width: "resolve",
        placeholder: "Banco...",
        allowClear: true,
        dropdownParent: $("#div-ajax"),
        matcher: function (params, data) {
            const term = $.trim(params.term || "").toLowerCase();
            if (term === "") return data;
            const text = (data.text || "").toLowerCase();
            const code = ($(data.element).data("code") || "").toString().toLowerCase();
            if (text.indexOf(term) > -1 || code.indexOf(term) > -1) return data;
            return null;
        },
        templateResult: function (data) { return data.text; },
        templateSelection: function (data) { return data.text; }
    });
}

function parseReglasDestino() {
    const raw = ($("#reglas_destino_json").val() || "").trim();
    if (!raw) return [];
    try {
        const arr = JSON.parse(raw);
        return Array.isArray(arr) ? arr : [];
    } catch (e) { return []; }
}

function sugerirDestinoId(metodo, moneda) {
    metodo = (metodo || "").trim();
    moneda = (moneda || "").trim();
    const reglas = parseReglasDestino();

    let r = reglas.find(x => String(x.metodo || "").trim() === metodo && String(x.moneda || "").trim() === moneda);
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    r = reglas.find(x => String(x.metodo || "").trim() === metodo && String(x.moneda || "").trim() === "");
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    return "";
}

function aplicarDestinoAuto(force) {
    const auto = $("#destino_auto").is(":checked");
    if (!auto && !force) return;

    const metodoSel = ($("#tipo_pago").val() || "").trim();
    const monedaSel = ($("#moneda_input").val() || "").trim();

    if (["IG","RC","RI","RS","NC","AN"].includes(metodoSel)) {
        $("#destino_input").val("");
        $("#help_destino").text("No aplica cuenta destino.");
        return;
    }

    const sugerido = sugerirDestinoId(metodoSel, monedaSel);
    if (sugerido) {
        $("#destino_input").val(sugerido);
        $("#help_destino").text("Auto: cuenta sugerida según configuración.");
    } else {
        $("#help_destino").text("No hay regla configurada para este caso.");
    }
}

$(document).on("change", "#tipo_pago", function () {
    const tipo = ($(this).val() || "").trim();
    const requiereBanco = !["EF","RI","RC","IG","RS","AN"].includes(tipo);
    const requiereRef   = !["EF","RC","IG","AN"].includes(tipo);
    $("#banco_input").prop("disabled", !requiereBanco);
    if ($("#banco_input").hasClass("select2-hidden-accessible")) $("#banco_input").trigger("change.select2");

    $("#help_ref_banco").text(
        (["RS","RI"].includes(tipo)) ? "Requiere: referencia." :
        (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
        (requiereRef) ? "Requiere: referencia." : ""
    );
    aplicarDestinoAuto(true);
});

$(document).on("change", "#anticipo_input", function () {
    const mon = ($(this).find("option:selected").data("moneda") || "").toString().trim();
    if (mon) $("#moneda_input").val(mon).trigger("change");
});

$(document).on("change", "#moneda_input", function () {
    recalcularMontoSegunMoneda();
    aplicarDestinoAuto(true);
});

$(document).on("change", "#destino_auto", function () {
    if ($(this).is(":checked")) aplicarDestinoAuto(true);
});

$(document).ready(function () {
    refrescar("");
});
</script>
</body>
</html>
<?php
    exit;
}

// -----------------------------------------------------------------------
// Dispatch: AJAX de la pantalla de pago
// -----------------------------------------------------------------------
$accion_pago = trim($_REQUEST["accion"] ?? "");

if ($accion_pago === "pago_refrescar") {
    $id_compra_pago = intval($_REQUEST["id_compra"] ?? 0);
    echo construir_html_pagos($link, $id_compra_pago, $_REQUEST["pagos"] ?? "[]", $_REQUEST["tipo_pago"] ?? "");
    exit;
}

if ($accion_pago === "pago_finalizar") {
    $id_compra_pago = intval($_REQUEST["id_compra"] ?? 0);
    $confirmar_exceso = trim($_REQUEST["confirmar_exceso"] ?? "0");
    finalizar_pago_factura($link, $id_compra_pago, $username, $_REQUEST["pagos"] ?? "[]", $confirmar_exceso);
    exit; // finalizar_pago_factura ya responde y termina, esto es solo por seguridad
}

// -----------------------------------------------------------------------
// Configuración del Motor Fiscal
// -----------------------------------------------------------------------
$FISCAL_DIR = "C:\\laragon\\www\\mandrake_2024_dropharma\\MandrakeFiscal";
$FISCAL_EXE = $FISCAL_DIR . "\\FiscalPrinterV2.exe";
$FISCAL_WORK_DIR = $FISCAL_DIR . "\\Temp";
$FISCAL_LOG_DIR = $FISCAL_DIR . "\\Logs";

// Modo seguro para pruebas: genera .dat, llama al motor, muestra respuesta,
// pero NO actualiza salidas como fiscal real. Cámbialo a false al pasar a producción.
$MODO_PRUEBA_FISCAL = true;

// Debug visual del comando ejecutado
$DEBUG_FISCAL = true;
$debug_cmd = "";
$debug_cwd = "";
$debug_raw = "";
$debug_json_error = "";
$debug_param040 = "";

if (!file_exists($FISCAL_EXE)) {
    die("No se encontró el Motor Fiscal en: " . $FISCAL_EXE);
}

if (!is_dir($FISCAL_WORK_DIR)) {
    mkdir($FISCAL_WORK_DIR, 0777, true);
}

if (!is_dir($FISCAL_LOG_DIR)) {
    mkdir($FISCAL_LOG_DIR, 0777, true);
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function safe_utf8($str) {
    $str = (string)$str;
    if ($str === "") return $str;

    // Si ya es UTF-8 válido, no tocar nada.
    if (mb_check_encoding($str, 'UTF-8')) {
        return $str;
    }

    // El .exe probablemente escribe acentos en el codepage de consola de
    // Windows (CP1252 / CP850 / CP437), no en UTF-8. Probamos en ese orden.
    foreach (array('Windows-1252', 'CP850', 'CP437', 'ISO-8859-1') as $enc) {
        $try = @iconv($enc, 'UTF-8//IGNORE', $str);
        if ($try !== false && $try !== "" && mb_check_encoding($try, 'UTF-8')) {
            return $try;
        }
    }

    // Último recurso: forzar limpieza de bytes inválidos para que al menos
    // se pueda mostrar algo en pantalla sin que h() se trague todo.
    return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
}

function dat_value($v) {
    $v = trim((string)$v);
    $v = str_replace(array("\r", "\n", "|"), array(" ", " ", "/"), $v);
    return $v;
}

function money_dot($v, $dec = 2) {
    return number_format((float)$v, $dec, ".", "");
}

function execute_scalar($link, $sql) {
    $rs = mysqli_query($link, $sql);
    if (!$rs) return "";
    $row = mysqli_fetch_row($rs);
    return $row ? $row[0] : "";
}

function run_fiscal_cmd($cmd, $cwd = "") {
    // Mantenida por compatibilidad. Usa ejecución simple tipo script original.
    return run_cmd_capture($cmd);
}

function run_cmd_capture($cmd) {
    global $debug_cmd, $debug_cwd, $debug_raw, $FISCAL_DIR;

    $debug_cmd = $cmd;
    $debug_cwd = $FISCAL_DIR;

    $descriptors = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w"),
    );

    // OJO: forzamos el cwd real del motor fiscal, aquí estaba el problema.
    $proc = proc_open($cmd, $descriptors, $pipes, $FISCAL_DIR);

    $salida = "";
    $err = "";
    $ret = -1;

    if (is_resource($proc)) {
        fclose($pipes[0]);
        $salida = trim(stream_get_contents($pipes[1]));
        $err = trim(stream_get_contents($pipes[2]));
        fclose($pipes[1]);
        fclose($pipes[2]);
        $ret = proc_close($proc);

        // Si el comando ya tenía "2>&1", stderr suele venir vacío o mezclado en stdout.
        // Igual lo dejamos disponible para depurar por separado.
        if ($salida === "" && $err !== "") {
            $salida = $err;
        }
    }

    // DEBUG CRUDO: longitud real en bytes y hexdump de los primeros bytes.
    // Esto nos permite ver BOM, UTF-16, o basura invisible que rompe json_decode
    // aunque en pantalla "se vea" como JSON normal.
    $len = strlen($salida);
    $hex_preview = bin2hex(substr($salida, 0, 80));

    // Normalizamos a UTF-8 válido SOLO para mostrar en pantalla; de lo
    // contrario htmlspecialchars() se traga el bloque completo si hay
    // bytes inválidos (por eso antes no veías esta sección).
    $salida_debug = safe_utf8($salida);
    $err_debug = safe_utf8($err);

    $debug_raw =
        "EXIT CODE: " . $ret . "\n" .
        "BYTES TOTALES: " . $len . "\n" .
        "HEX (primeros 80 bytes): " . $hex_preview . "\n" .
        "STDERR:\n" . $err_debug . "\n" .
        "OUTPUT:\n" . $salida_debug;

    return $salida;
}

function parse_json_response($text) {
    global $debug_json_error;
    $debug_json_error = "";

    // 1) Quitar BOM UTF-8 (EF BB BF) si viene
    if (substr($text, 0, 3) === "\xEF\xBB\xBF") {
        $text = substr($text, 3);
    }

    // 2) Detectar UTF-16LE/BE (típico en exe de consola .NET/Windows) y convertir
    if (substr($text, 0, 2) === "\xFF\xFE") {
        $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16LE');
    } elseif (substr($text, 0, 2) === "\xFE\xFF") {
        $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16BE');
    } elseif (preg_match('/\x00/', substr($text, 0, 20))) {
        // No hay BOM pero hay bytes nulos intercalados: casi seguro es UTF-16LE sin BOM
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-16LE');
    }

    // 3) Si sigue sin ser UTF-8 válido, probablemente viene en el codepage
    // de consola de Windows (CP1252/CP850) por los acentos. Normalizamos.
    $text = safe_utf8($text);

    $text = trim($text);
    $json = json_decode($text, true);
    if (is_array($json)) return $json;
    $debug_json_error = "json_decode falló en texto completo: " . json_last_error_msg();

    $lines = preg_split('/\r\n|\r|\n/', $text);
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $line = trim($lines[$i]);
        if ($line === "") continue;
        $json = json_decode($line, true);
        if (is_array($json)) return $json;
    }

    return null;
}

function get_parametro_040($link) {
    $sql = "SELECT valor1, valor2 FROM parametro WHERE codigo = '040' LIMIT 1";
    $rs = mysqli_query($link, $sql);

    if (!$rs) return array("", "");

    $row = mysqli_fetch_assoc($rs);
    if (!$row) return array("", "");

    return array(trim($row["valor1"]), trim($row["valor2"]));
}

function get_serial_fiscal_param($link) {
    list($puerto, $serial) = get_parametro_040($link);
    return $serial;
}

function guardar_parametro_040($link, $puerto, $serial = "") {
    global $debug_param040;
    $debug_param040 = "";

    $puerto = mysqli_real_escape_string($link, trim($puerto));
    $serial = mysqli_real_escape_string($link, trim($serial));

    $existe = execute_scalar($link, "SELECT COUNT(*) FROM parametro WHERE codigo = '040'");

    if ((int)$existe > 0) {
        $res = mysqli_query($link, "
            UPDATE parametro
               SET valor1 = '$puerto',
                   valor2 = '$serial',
                   descripcion = 'PUERTO COM Y SERIAL IMPRESORA FISCAL'
             WHERE codigo = '040'
        ");
        if ($res === false) {
            $debug_param040 = "UPDATE falló: " . mysqli_error($link);
        } else {
            $debug_param040 = "UPDATE ejecutado. Filas afectadas: " . mysqli_affected_rows($link)
                . " (valor1='$puerto', valor2='$serial')";
        }
    } else {
        $res = mysqli_query($link, "
            INSERT INTO parametro (codigo, descripcion, valor1, valor2)
            VALUES (
                '040',
                'PUERTO COM Y SERIAL IMPRESORA FISCAL',
                '$puerto',
                '$serial'
            )
        ");
        if ($res === false) {
            $debug_param040 = "INSERT falló: " . mysqli_error($link);
        } else {
            $debug_param040 = "INSERT ejecutado. Filas afectadas: " . mysqli_affected_rows($link)
                . " (valor1='$puerto', valor2='$serial')";
        }
    }
}

function obtener_info_impresora_json($exe, $com) {
    // Ejecución simple, igual al script original:
    // C:\...\FiscalPrinterV2.exe INFOJSON [COMx]
    if (trim($com) != "") {
        $cmd = $exe . ' INFOJSON "' . trim($com) . '" 2>&1';
    } else {
        $cmd = $exe . ' INFOJSON 2>&1';
    }

    $raw = run_cmd_capture($cmd);
    $json = parse_json_response($raw);

    if (!is_array($json)) {
        return array(null, $raw);
    }

    return array($json, $raw);
}

function detectar_puerto_fiscal($link, $exe) {
    list($puerto_guardado, $serial_guardado) = get_parametro_040($link);

    // 1) Si existe puerto guardado, probar SOLO ese puerto.
    if ($puerto_guardado != "") {
        list($json, $raw) = obtener_info_impresora_json($exe, $puerto_guardado);

        if (is_array($json) && !empty($json["success"])) {
            $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : $puerto_guardado;
            $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : $serial_guardado;

            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw);
        }
    }

    // 2) Si falló o no existe, pedir autodetección al motor fiscal. NO escanear COM1-COM20 en PHP.
    list($json, $raw) = obtener_info_impresora_json($exe, "");

    if (is_array($json) && !empty($json["success"])) {
        $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : "";
        $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : "";

        if ($puerto != "") {
            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw);
        }
    }

    return array("", "", $json, $raw);
}

function map_tipo_fiscal($documento, $tipo_documento) {
    $documento = strtoupper(trim($documento));
    $tipo_documento = strtoupper(trim($tipo_documento));

    if (in_array($documento, array("FC", "NC", "ND"))) return $documento;

    if ($tipo_documento == "TDCFCV") return "FC";
    if ($tipo_documento == "TDCNCR" || $tipo_documento == "TDCNCV" || $tipo_documento == "TDCNCC") return "NC";
    if ($tipo_documento == "TDCNDB" || $tipo_documento == "TDCNDV") return "ND";

    return $documento;
}

function generar_dat_fiscal($link, $id, $username, $puerto, $work_dir, $serial_fiscal = "") {
    global $MODO_PRUEBA_FISCAL;
    $id = intval($id);

    $sql = "
        SELECT
            s.id,
            DATE_FORMAT(s.fecha, '%d/%m/%Y') AS fecha_afectada,
            s.fecha,
            s.cliente,
            s.nro_documento,
            s.nro_control,
            s.tipo_documento,
            s.estatus,
            s.username,
            s.documento,
            IFNULL(s.moneda, 'Bs.') AS moneda,
            IFNULL(s.tasa_dia, 1) AS tasa_dia,
            IFNULL(s.monto_base_igtf, 0) AS monto_base_igtf,
            IFNULL(s.igtf_alicuota, 0) AS igtf_alicuota,
            IFNULL(s.doc_afectado, '') AS doc_afectado,
            c.ci_rif,
            c.nombre,
            c.direccion,
            c.telefono1
        FROM salidas s
            LEFT JOIN cliente c ON c.id = s.cliente
        WHERE s.id = $id
        LIMIT 1";

    $rs = mysqli_query($link, $sql);
    if (!$rs) return array(false, "", "Error consultando cabecera: " . mysqli_error($link));

    $cab = mysqli_fetch_assoc($rs);
    if (!$cab) return array(false, "", "No existe el documento ID $id.");

    $tipo = map_tipo_fiscal($cab["documento"], $cab["tipo_documento"]);

    $lines = array();
    $lines[] = "COM=" . dat_value($puerto);
    $lines[] = "TIPO=" . dat_value($tipo);
    $lines[] = "ID=" . $id;
    $lines[] = "USUARIO=" . dat_value($username != "" ? $username : $cab["username"]);
    $lines[] = "";

    if ($tipo == "NC" || $tipo == "ND") {
        $doc_afectado = trim($cab["doc_afectado"]);
        $fecha_afectada = "";

        if ($doc_afectado != "") {
            $doc_afectado_sql = mysqli_real_escape_string($link, $doc_afectado);
            $fecha_afectada = execute_scalar($link, "
                SELECT DATE_FORMAT(fecha, '%d/%m/%Y')
                FROM salidas
                WHERE nro_documento = '$doc_afectado_sql'
                LIMIT 1
            ");
        }

        $serial = trim($serial_fiscal);
        if ($serial == "") $serial = get_serial_fiscal_param($link);

        $lines[] = "FACTURA_AFECTADA=" . dat_value(str_pad($doc_afectado, 8, "0", STR_PAD_LEFT));
        $lines[] = "FECHA_AFECTADA=" . dat_value($fecha_afectada);
        $lines[] = "SERIAL_AFECTADA=" . dat_value($serial);
        $lines[] = "";
    }

    $lines[] = "RIF=" . dat_value($cab["ci_rif"]);
    $lines[] = "CLIENTE=" . dat_value($cab["nombre"]);
    $lines[] = "DIRECCION=" . dat_value($cab["direccion"]);
    $lines[] = "TELEFONO=" . dat_value($cab["telefono1"]);
    $lines[] = "";

    $moneda = trim($cab["moneda"]);
    if ($moneda == "") $moneda = "Bs.";

    $lines[] = "MONEDA=" . dat_value($moneda);
    $lines[] = "TASA=" . money_dot($cab["tasa_dia"], 4);
    $lines[] = "";

    // NOTA: el .exe ya NO usa este flag para decidir si cierra con el comando
    // 199 — ahora decide mirando la moneda de cada PAGO en el .dat (más
    // confiable que un flag calculado aparte). Como más abajo todavía armamos
    // un único pago fijo en Bs. (ver bloque "PAGOS="), este flag se deja en
    // "N" para reflejar la realidad de lo que se envía a imprimir, y no
    // confundir con lo que diga monto_base_igtf/igtf_alicuota en salidas.
    // El día que se conecten pagos reales multi-moneda, esto debe volver a
    // calcularse (o mejor, eliminarse del todo y dejar que decida el .exe).
    $igtf_aplica = "N";
    $lines[] = "IGTF_APLICA=" . $igtf_aplica;
    $lines[] = "IGTF_ALICUOTA=" . money_dot($cab["igtf_alicuota"], 2);
    // Le indicamos al motor fiscal (.exe) si debe imprimir como documento
    // NO fiscal (PrintTest) o como documento fiscal real (PrintFiscal),
    // usando el mismo flag que ya controla el resto del flujo en PHP.
    $lines[] = "MODO_PRUEBA=" . ($MODO_PRUEBA_FISCAL ? "S" : "N");
    $lines[] = "";

    $sql_det = "
        SELECT
            IFNULL(
                NULLIF(
                    CONCAT_WS(' ',
                        NULLIF(TRIM(a.nombre_comercial), ''),
                        NULLIF(TRIM(a.principio_activo), ''),
                        NULLIF(TRIM(a.presentacion), '')
                    ),
                    ''
                ),
                CONCAT('ARTICULO ', es.articulo)
            ) AS descripcion,
            IFNULL(es.cantidad_articulo, 0) AS cantidad,
            IFNULL(es.precio_unidad, 0) AS precio,
            IFNULL(es.alicuota, 0) AS alicuota
        FROM entradas_salidas es
            LEFT JOIN articulo a ON a.id = es.articulo
        WHERE es.id_documento = $id
            AND es.tipo_documento = '" . mysqli_real_escape_string($link, $cab["tipo_documento"]) . "'
        ORDER BY es.id";

    $rs_det = mysqli_query($link, $sql_det);
    if (!$rs_det) return array(false, "", "Error consultando detalle: " . mysqli_error($link));

    $items = array();
    while ($d = mysqli_fetch_assoc($rs_det)) {
        $items[] = dat_value($d["descripcion"]) . "|" .
                   money_dot($d["cantidad"], 3) . "|" .
                   money_dot($d["precio"], 2) . "|" .
                   money_dot($d["alicuota"], 2);
    }

    if (count($items) == 0) return array(false, "", "El documento no tiene detalle.");

    $lines[] = "ITEMS=" . count($items);
    foreach ($items as $i => $line) {
        $lines[] = "ITEM" . ($i + 1) . "=" . $line;
    }
    $lines[] = "";

    // Pagos reales del documento. Cualquier medio de pago que no esté en la
    // lista conocida (EF, TD, TC, TR, PM, CH) se manda tal cual al .dat con
    // su código original — el .exe (GetPaymentCode) ya sabe tratar cualquier
    // código desconocido como Efectivo en la moneda que traiga cada pago
    // (nacional -> "01", divisa -> "20"), así que RC/CS/RD (abonos, CASHEA,
    // etc.) quedan cubiertos sin necesidad de mapearlos aquí uno por uno.
    $sql_pagos = "
        SELECT
            b.metodo_pago,
            b.moneda,
            b.monto_moneda
        FROM cobros_cliente AS a
            JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id
        WHERE a.id_documento = $id
        ORDER BY b.id";

    $rs_pagos = mysqli_query($link, $sql_pagos);
    if (!$rs_pagos) return array(false, "", "Error consultando pagos: " . mysqli_error($link));

    $descripciones_pago = array(
        "EF" => "EFECTIVO",
        "TD" => "TARJETA DEBITO",
        "TC" => "TARJETA CREDITO",
        "TR" => "TRANSFERENCIA",
        "PM" => "PAGO MOVIL",
        "CH" => "CHEQUE",
    );

    $pagos = array();
    while ($p = mysqli_fetch_assoc($rs_pagos)) {
        $codigo = trim($p["metodo_pago"]);
        $moneda_pago = trim($p["moneda"]);
        if ($moneda_pago == "") $moneda_pago = "Bs.";

        // Medios de pago conocidos usan su nombre real; cualquier otro
        // (RC = abono/anticipo, CS = CASHEA, etc.) se manda con su propio
        // código como descripción — el .exe igual lo va a tratar como
        // Efectivo en la moneda que traiga, así que el nombre exacto aquí
        // es solo informativo para lo que se imprime en el ticket.
        $descripcion = isset($descripciones_pago[$codigo]) ? $descripciones_pago[$codigo] : $codigo;

        $pagos[] = dat_value($codigo) . "|" .
                   dat_value($descripcion) . "|" .
                   money_dot($p["monto_moneda"], 2) . "|" .
                   dat_value($moneda_pago);
    }

    // Si por algún motivo el documento no tiene pagos registrados en
    // cobros_cliente_detalle (dato faltante, no que el cliente no haya
    // pagado), caemos al total en efectivo/Bs. como red de seguridad para
    // no bloquear la impresión.
    if (count($pagos) == 0) {
        $total_bs = execute_scalar($link, "SELECT IFNULL(total, 0) FROM salidas WHERE id = $id");
        $pagos[] = "EF|EFECTIVO|" . money_dot($total_bs, 2) . "|Bs.";
    }

    $lines[] = "PAGOS=" . count($pagos);
    foreach ($pagos as $i => $line) {
        $lines[] = "PAGO" . ($i + 1) . "=" . $line;
    }

    $file = rtrim($work_dir, "\\/") . "\\doc_" . $id . "_" . $tipo . ".dat";
    $content = implode("\r\n", $lines) . "\r\n";

    if (file_put_contents($file, $content) === false) {
        return array(false, "", "No se pudo crear el archivo DAT: $file");
    }

    return array(true, $file, "");
}


function solo_numero_fiscal($valor) {
    $valor = preg_replace('/\D+/', '', (string)$valor);
    return intval($valor);
}

function sincronizar_documento_consecutivo_fiscal($link, $tipo, $numero_doc, $numero_ctrl) {
    $tipo = strtoupper(trim($tipo));
    if (!in_array($tipo, array("FC", "NC", "ND"))) {
        return true;
    }

    $tipo_documento = mysqli_real_escape_string($link, "TDCFCV");
    $serie_doc = mysqli_real_escape_string($link, $tipo . "_DOC");
    $serie_ctrl = mysqli_real_escape_string($link, $tipo . "_CTRL");

    $num_doc = solo_numero_fiscal($numero_doc);
    $num_ctrl = solo_numero_fiscal($numero_ctrl);

    if ($num_doc > 0) {
        mysqli_query($link, "
            INSERT IGNORE INTO documento_consecutivo
                (tipo_documento, serie, ultimo_numero, updated_at)
            VALUES
                ('$tipo_documento', '$serie_doc', 0, NOW())
        ");

        mysqli_query($link, "
            UPDATE documento_consecutivo
               SET ultimo_numero = GREATEST(IFNULL(ultimo_numero, 0), $num_doc),
                   updated_at = NOW()
             WHERE tipo_documento = '$tipo_documento'
               AND serie = '$serie_doc'
        ");
    }

    if ($num_ctrl > 0) {
        mysqli_query($link, "
            INSERT IGNORE INTO documento_consecutivo
                (tipo_documento, serie, ultimo_numero, updated_at)
            VALUES
                ('$tipo_documento', '$serie_ctrl', 0, NOW())
        ");

        mysqli_query($link, "
            UPDATE documento_consecutivo
               SET ultimo_numero = GREATEST(IFNULL(ultimo_numero, 0), $num_ctrl),
                   updated_at = NOW()
             WHERE tipo_documento = '$tipo_documento'
               AND serie = '$serie_ctrl'
        ");
    }

    return true;
}

function registrar_auditoria_fiscal($link, $id, $tipo, $numero, $control, $username) {
    $id = intval($id);
    $tipo = strtoupper(trim($tipo));

    $nombre = "Documento";
    if ($tipo == "FC") $nombre = "Factura";
    elseif ($tipo == "NC") $nombre = "Nota de Crédito";
    elseif ($tipo == "ND") $nombre = "Nota de Débito";

    $usuario = mysqli_real_escape_string($link, trim($username) != "" ? trim($username) : "NA.NA");
    $numero_sql = mysqli_real_escape_string($link, $numero);
    $control_sql = mysqli_real_escape_string($link, $control);
    $fecha_txt = date("d/m/Y H:i:s");
    $script = mysqli_real_escape_string($link, "Emitió documento $nombre # $numero con # de control $control de fecha $fecha_txt (IMPRESORA FISCAL)");

    mysqli_query($link, "
        INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (NULL, '" . date("Y-m-d H:i:s") . "',
             '$script', '$usuario', 'SENIAT: U', 'view_out_tdcfcv', 'id', '$id', '', '$numero_sql')
    ");
}

function actualizar_salida_post_fiscal($link, $id, $json) {
    global $MODO_PRUEBA_FISCAL, $username;

    if ($MODO_PRUEBA_FISCAL) {
        return true;
    }

    $id = intval($id);
    $tipo = isset($json["tipo"]) ? strtoupper(trim($json["tipo"])) : "";

    $numero = "";
    if ($tipo == "FC") {
        $numero = isset($json["numeroFactura"]) ? $json["numeroFactura"] : "";
    } elseif ($tipo == "NC") {
        $numero = isset($json["numeroNotaCredito"]) ? $json["numeroNotaCredito"] : "";
    } elseif ($tipo == "ND") {
        $numero = isset($json["numeroNotaDebito"]) ? $json["numeroNotaDebito"] : "";
    }

    $control = isset($json["numeroControl"]) && trim($json["numeroControl"]) != ""
        ? $json["numeroControl"]
        : $numero;

    $numero_sql = mysqli_real_escape_string($link, $numero);
    $control_sql = mysqli_real_escape_string($link, $control);
    $usuario_sql = mysqli_real_escape_string($link, trim($username) != "" ? trim($username) : "NA.NA");

    $sql = "
        UPDATE salidas
        SET
            fecha = '" . date("Y-m-d H:i:s") . "',
            nro_documento = '$numero_sql',
            nro_control = '$control_sql',
            estatus = 'PROCESADO',
            impreso = 'S',
            username = '$usuario_sql'
        WHERE id = $id
          AND (nro_documento IS NULL OR nro_documento = '')";

    $ok_update = mysqli_query($link, $sql);
    if (!$ok_update) {
        return false;
    }

    sincronizar_documento_consecutivo_fiscal($link, $tipo, $numero, $control);
    registrar_auditoria_fiscal($link, $id, $tipo, $numero, $control, $username);

    return true;
}

// -----------------------------------------------------------------------
// GATE: si la factura todavía no tiene su pago registrado, se muestra la
// pantalla previa de registro de pagos y NO se ejecuta nada del motor
// fiscal. Solo cuando salidas.pagado = 'S' se continúa con el proceso de
// impresión fiscal que ya funciona bien más abajo.
// -----------------------------------------------------------------------
if ($test_fiscal != 1 && $id > 0 && !factura_ya_pagada($link, $id)) {
    render_pagina_pago($link, $id, $username, $auto_return, $generar_ne);
    exit;
}

$ok = false;
$ya_impreso = false;
$title = "Impresora Fiscal";
$detail = "";
$dat_file = "";
$raw_response = "";
$json = null;
$puerto = "";
$serialFiscal = "";
$info_json = null;

if ($test_fiscal == 1) {
    list($puerto, $serialFiscal, $json, $raw_response) = detectar_puerto_fiscal($link, $FISCAL_EXE);

    if ($puerto != "" && is_array($json) && !empty($json["success"])) {
        $ok = true;
        $title = "Comunicación fiscal correcta";
        $detail = "Impresora fiscal detectada correctamente.";
    } else {
        $title = "No hay comunicación con la impresora fiscal";
        $detail = "No se pudo comunicar con la impresora fiscal usando el puerto guardado ni autodetección.";
    }
} elseif ($id <= 0) {
    $detail = "Debe indicar un ID de documento válido.";
} elseif (!file_exists($FISCAL_EXE)) {
    $detail = "No se encontró FiscalPrinterV2.exe en: " . $FISCAL_EXE;
} else {
    // -----------------------------------------------------------------
    // Guardia anti-doble-impresión: si el documento YA quedó marcado
    // como impreso fiscalmente (fuera de modo prueba), no se vuelve a
    // ejecutar el motor fiscal aunque recarguen (F5) o vuelvan atrás.
    // -----------------------------------------------------------------
    $doc_previo = null;
    $rs_doc_previo = mysqli_query($link, "SELECT nro_documento, nro_control, impreso FROM salidas WHERE id = $id LIMIT 1");
    if ($rs_doc_previo) $doc_previo = mysqli_fetch_assoc($rs_doc_previo);

    if (!$MODO_PRUEBA_FISCAL && $doc_previo && $doc_previo["impreso"] == "S") {
        $ok = true;
        $ya_impreso = true;
        $title = "Documento fiscal ya emitido";
        $detail = "Este documento ya fue procesado fiscalmente anteriormente (Nro. Documento: "
            . $doc_previo["nro_documento"] . ", Nro. Control: " . $doc_previo["nro_control"]
            . "). No se puede volver a imprimir desde aquí.";
    } else {
    list($puerto, $serialFiscal, $info_json, $info_raw) = detectar_puerto_fiscal($link, $FISCAL_EXE);

    if ($puerto == "") {
        $detail = "No se pudo detectar el puerto COM de la impresora fiscal. Verifique conexión y driver USB/Serial.";
        $raw_response = $info_raw;
    } else {
        list($gen_ok, $dat_file, $gen_error) = generar_dat_fiscal($link, $id, $username, $puerto, $FISCAL_WORK_DIR, $serialFiscal);

        if (!$gen_ok) {
            $detail = $gen_error;
        } else {
            // DEBUG TEMPORAL: comparar cwd real de PHP vs carpeta del motor fiscal
            error_log("CWD antes de exec (esperado: $FISCAL_DIR) => " . getcwd());

            // Ejecución simple, igual al script original.
            $cmd = $FISCAL_EXE . ' "' . $dat_file . '" 2>&1';
            $raw_response = run_cmd_capture($cmd);
            $json = parse_json_response($raw_response);

            if (is_array($json) && !empty($json["success"])) {
                if (actualizar_salida_post_fiscal($link, $id, $json)) {
                    $ok = true;
                    $title = $MODO_PRUEBA_FISCAL ? "Prueba fiscal ejecutada correctamente" : "Documento fiscal emitido correctamente";
                    $detail = isset($json["message"]) ? $json["message"] : "Proceso exitoso.";

                    if ($MODO_PRUEBA_FISCAL) {
                        $detail .= " MODO PRUEBA: no se actualizó salidas como fiscal real.";
                    } else {
                        // Impresión real exitosa: ya no se debe poder reimprimir.
                        $ya_impreso = true;
                    }
                } else {
                    $detail = "La impresora respondió OK, pero falló el UPDATE de salidas: " . mysqli_error($link);
                }
            } else {
                $title = "No se pudo emitir el documento fiscal";
                $detail = is_array($json) && isset($json["message"]) ? $json["message"] : $raw_response;
            }
        }
    }
    }
}

if ($auto_return == 1 && $ok && !$MODO_PRUEBA_FISCAL) {
    if ($generar_ne == "S") {
        header("Location: CrearNotaEntregaAutomaticaWait?id=$id&return=ViewOutTdcfcvList");
        die();
    }

    header("Location: ../ViewOutTdcfcvView/" . $id . "?showdetail=");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Impresora Fiscal</title>
<style>
    body { margin: 0; padding: 25px; font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #263238; }
    .fiscal-card { max-width: 920px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,.12); overflow: hidden; border: 1px solid #e6e9ef; }
    .fiscal-header { padding: 18px 24px; color: #fff; background: <?php echo $ok ? "#198754" : "#dc3545"; ?>; }
    .fiscal-header h2 { margin: 0; font-size: 22px; }
    .fiscal-body { padding: 22px 24px; }
    .badge { display: inline-block; padding: 6px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; color: #fff; background: <?php echo $ok ? "#198754" : "#dc3545"; ?>; margin-bottom: 12px; }
    .badge-test { background: #fd7e14; }
    .grid { display: grid; grid-template-columns: 190px 1fr; gap: 8px 14px; margin-top: 12px; }
    .label { color: #607d8b; font-weight: bold; }
    pre { background: #101820; color: #e8f5e9; padding: 14px; border-radius: 8px; overflow: auto; font-size: 13px; }
    .actions { margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap; }
    .btn { display: inline-block; text-decoration: none; background: #0d6efd; color: #fff; padding: 9px 14px; border-radius: 6px; font-weight: bold; }
    .btn-secondary { background: #6c757d; }
    .btn-warning { background: #fd7e14; color: #111; }
</style>
</head>
<body>
<div class="fiscal-card">
    <div class="fiscal-header">
        <h2><?php echo h($title); ?></h2>
    </div>
    <div class="fiscal-body">
        <span class="badge"><?php echo $ok ? "OK" : "ERROR"; ?></span>
        <?php if ($MODO_PRUEBA_FISCAL) { ?>
            <span class="badge badge-test">MODO PRUEBA</span>
        <?php } ?>

        <div class="grid">
            <div class="label">Documento ID</div><div><?php echo h($id); ?></div>
            <div class="label">Puerto COM</div><div><?php echo h($puerto); ?></div>
            <div class="label">Serial consultado</div><div><?php echo h($serialFiscal); ?></div>
            <div class="label">Archivo DAT</div><div><?php echo h($dat_file); ?></div>
            <div class="label">Mensaje</div><div><?php echo h($detail); ?></div>

            <?php if (is_array($json)) { ?>
                <div class="label">Tipo</div><div><?php echo h(isset($json["tipo"]) ? $json["tipo"] : ""); ?></div>
                <div class="label">Factura</div><div><?php echo h(isset($json["numeroFactura"]) ? $json["numeroFactura"] : ""); ?></div>
                <div class="label">Nota Crédito</div><div><?php echo h(isset($json["numeroNotaCredito"]) ? $json["numeroNotaCredito"] : ""); ?></div>
                <div class="label">Nota Débito</div><div><?php echo h(isset($json["numeroNotaDebito"]) ? $json["numeroNotaDebito"] : ""); ?></div>
                <div class="label">Serial fiscal</div><div><?php echo h(isset($json["serialFiscal"]) ? $json["serialFiscal"] : ""); ?></div>
                <div class="label">Estado impresora</div><div><?php echo h(isset($json["estadoImpresora"]) ? $json["estadoImpresora"] : ""); ?></div>
                <div class="label">Error impresora</div><div><?php echo h(isset($json["errorImpresora"]) ? $json["errorImpresora"] : ""); ?></div>
            <?php } ?>
        </div>

        <?php if ($raw_response != "") { ?>
            <h3>Respuesta del motor fiscal</h3>
            <pre><?php echo h($raw_response); ?></pre>
        <?php } ?>

        <?php if (!empty($DEBUG_FISCAL)) { ?>
            <h3>Debug del comando fiscal</h3>
            <pre><?php
                echo h("CMD:\n" . $debug_cmd . "\n\n");
                echo h("CWD:\n" . $debug_cwd . "\n\n");
                echo h("RAW:\n" . $debug_raw . "\n\n");
                echo h("JSON DECODE:\n" . ($debug_json_error !== "" ? $debug_json_error : "OK, se interpretó correctamente") . "\n\n");
                echo h("PARAMETRO 040 (guardar puerto/serial):\n" . ($debug_param040 !== "" ? $debug_param040 : "(no se intentó guardar en esta ejecución)"));
            ?></pre>
        <?php } ?>

        <div class="actions">
            <?php if ($ya_impreso) { ?>
                <a class="btn btn-secondary" href="javascript:void(0);" onclick="window.close(); return false;">Cerrar</a>
            <?php } else { ?>
                <a class="btn btn-warning" href="?test_fiscal=1&id=<?php echo intval($id); ?>&username=<?php echo urlencode($username); ?>">Probar comunicación fiscal</a>
                <a class="btn btn-secondary" href="../ViewOutTdcfcvList">Listar Facturas</a>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>