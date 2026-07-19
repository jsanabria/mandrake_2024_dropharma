<?php

namespace PHPMaker2024\mandrake;

// Page object
$RegistrarPagosClientes = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// 1. Captura de parámetros
$id_compra = Param("id_compra") ?? 0;
$id_compra = intval($id_compra);
$accion = Param("accion") ?? "";

function jsonResponse($arr) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Bloque AJAX
if ($accion == "refrescar") {
    if (ob_get_length()) ob_end_clean();

    $pagos_json = Param("pagos") ?? "[]";
    $tipo_pago_actual = Param("tipo_pago") ?? "";
    $lista_pagos = json_decode($pagos_json, true) ?: [];
    
    $sqlFact = "SELECT a.cliente, b.nombre AS nombre_cliente, a.nro_documento, 
                        a.moneda, 
                        CASE 
                            WHEN a.moneda <> 'Bs.' THEN (a.total * a.tasa_dia) 
                            ELSE a.total 
                        END AS total,
                        CASE 
                            WHEN a.moneda <> 'Bs.' THEN a.total 
                            ELSE (a.total / NULLIF(a.tasa_dia, 0)) 
                        END AS totalDivisa, 
                        a.tasa_dia, a.total AS total_factura, a.iva   
                FROM salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
                WHERE a.id = " . QuotedValue($id_compra, 1);
    $factura = ExecuteRow($sqlFact);

    ///////
    $compania_id = 1;

    // reglas
    $reglas = ExecuteRows("
        SELECT compania, metodo, IFNULL(moneda,'') AS moneda, cuenta_destino_id, prioridad
        FROM pago_destino_regla
        WHERE compania = " . intval($compania_id) . " AND activo = 'S'
        ORDER BY prioridad DESC, id DESC
    ");
    $reglas_json = json_encode($reglas);

    // cuentas destino (select simple)
    $cuentas_destino = ExecuteRows("
        SELECT a.id, b.campo_descripcion AS banco, a.tipo, a.numero
        FROM compania_cuenta a
        JOIN tabla b ON b.campo_codigo = a.banco AND b.tabla = 'BANCO'
        WHERE a.compania = " . intval($compania_id) . "
        AND a.mostrar = 'S' AND a.activo = 'S'
        ORDER BY b.campo_descripcion, a.numero
    ");
    ///////

    $anticipos = [];
    if ($tipo_pago_actual === "AN") {
        $cliente_id = intval($factura["cliente"] ?? 0);

        // Anticipos = cobros_cliente.id_documento = 0
        $sqlAnt = "
            SELECT
            cc.id AS anticipo_id,
            cc.fecha,
            ccd.moneda,
            SUM(ccd.monto_moneda) AS monto_anticipo,
            COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                AND a.moneda = ccd.moneda
            ), 0) AS monto_aplicado,
            (SUM(ccd.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                AND a.moneda = ccd.moneda
            ), 0)) AS saldo_disponible
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle ccd ON ccd.cobros_cliente = cc.id
            WHERE cc.cliente = " . intval($cliente_id) . "
            AND cc.id_documento = 0
            GROUP BY cc.id, cc.fecha, ccd.moneda
            HAVING saldo_disponible > 0.01
            ORDER BY cc.fecha DESC
        ";
        $anticipos = ExecuteRows($sqlAnt);

        // ✅ Rebaja anticipos con lo que ya está en el carrito (JSON)
        $aplicado_local = []; // [anticipo_id][moneda] => monto
        foreach ($lista_pagos as $pp) {
            if (trim($pp["tipo"] ?? "") !== "AN") continue;
            $aid = intval($pp["anticipo_id"] ?? 0);
            $m   = floatval($pp["monto"] ?? 0);
            $mo  = trim($pp["moneda"] ?? "");
            if ($aid > 0 && $m > 0 && $mo !== "") {
                if (!isset($aplicado_local[$aid])) $aplicado_local[$aid] = [];
                if (!isset($aplicado_local[$aid][$mo])) $aplicado_local[$aid][$mo] = 0;
                $aplicado_local[$aid][$mo] += $m;
            }
        }

        // Ajusta saldo_disponible y filtra los que queden en 0
        $tmp = [];
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

    $igtf_pct = floatval(ExecuteScalar("SELECT alicuota AS IGTF FROM alicuota WHERE codigo = 'IGT' AND activo = 'S'") ?: 0);

    // Si viene como 0.03 (3%), conviértelo a 3
    if ($igtf_pct > 0 && $igtf_pct < 1) {
        $igtf_pct = $igtf_pct * 100;
    }

    /////
    $total_factura  = trim($factura["total_factura"] ?? "Bs.");

    $moneda_doc = trim($factura["moneda"] ?? "Bs.");
    $moneda_doc_sel = $moneda_doc == "Bs." ? "USD" : $moneda_doc;
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);

    // Totales del documento:
    // - total     = Bs (por tu CASE)
    // - totalDivisa = Divisa del doc (por tu CASE)
    $total_bs_original = floatval($factura["total"] ?? 0);
    $total_bs  = $total_bs_original;
    $total_div = floatval($factura["totalDivisa"] ?? 0);

    // Retenciones RI/RJ/RR: el IVA se maneja en Bs.
    // Si la factura fue emitida en divisas, convertimos salidas.iva
    // usando la tasa del documento antes de calcular o validar la retención.
    $iva_documento = floatval($factura["iva"] ?? 0);
    $iva_factura = round(
        ($moneda_doc !== "Bs.") ? ($iva_documento * $tasa_dia) : $iva_documento,
        2
    );
    $retencion_pct = 0;
    $monto_metodo_default = null;

    if (in_array($tipo_pago_actual, ["RI", "RJ"], true)) {
        $retencion_pct = floatval(ExecuteScalar("
            SELECT valor3
            FROM parametro
            WHERE codigo = '009'
              AND valor1 = '" . AdjustSql($tipo_pago_actual) . "'
            LIMIT 1
        ") ?: 0);

        if ($retencion_pct > 0) {
            $monto_metodo_default = round(($iva_factura * $retencion_pct) / 100, 2);
        } else {
            $monto_metodo_default = 0;
        }
    } elseif ($tipo_pago_actual === "RR") {
        $monto_metodo_default = 0;
    }

// ... dentro del bloque if ($accion == "refrescar")

    $total_bs_pagado  = 0;
    $total_div_pagado = 0;

    foreach ($lista_pagos as $p) {
        $tipo_reg = trim($p["tipo"] ?? "");
        // El IGTF (IG) no debe restar el saldo pendiente de la factura
        if ($tipo_reg === "IG") { // continue;
            $total_bs += floatval($p["monto"] ?? 0);
            continue;
        }
        
        $monto_p = floatval($p["monto"] ?? 0);
        $mon_p   = trim($p["moneda"] ?? "Bs.");
        
        if ($monto_p <= 0) continue;

        if ($mon_p === "Bs.") {
            $total_bs_pagado += $monto_p;
        } else {
            // IMPORTANTE: Usar la misma tasa que muestra la factura en pantalla
            // Redondeamos a 2 decimales para evitar el arrastre de coma flotante
            $total_bs_pagado += round($monto_p * $tasa_dia, 2);
        }
    }

    // Saldo principal de la factura, sin incluir IGTF. Se usa como límite
    // para que un pago en divisas nunca genere IGTF sobre un monto mayor
    // al principal realmente pendiente.
    $saldo_principal_bs = $total_bs_original - $total_bs_pagado;
    if ($saldo_principal_bs < 0.01) {
        $saldo_principal_bs = 0;
    }

    // Cálculo final del total por cobrar, incluyendo las líneas IGTF ya creadas.
    $saldo_bs  = $total_bs - $total_bs_pagado;

    // Para el saldo en divisas, lo ideal es recalcularlo en base al saldo BS 
    // para que la relación con la tasa mostrada sea coherente al 100%
    $saldo_div = ($tasa_dia > 0) ? ($saldo_bs / $tasa_dia) : 0;

    // Proteccion contra decimales basura (ej: 0.000000001)
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
                    <b><?= $factura['nombre_cliente'] ?></b>
                    <div class="small text-muted mt-1">Total Factura <?= HtmlEncode($moneda_doc) ?>: <b> <?= number_format($total_factura, 2, ".", ",") ?></b></div>
                </div>
                <div class="col-4 border-end">
                    <small class="text-muted d-block">FACTURA</small>
                    <b><?= $factura['nro_documento'] ?></b>
                    <div class="small text-muted mt-1">Tasa día Bs.: <b> <?= number_format($tasa_dia, 2, ".", ",") ?></b></div>
                </div>
                <div class="col-4">
                    <small class="text-muted d-block">PENDIENTE</small>

                    <div class="<?= ($saldo_bs <= 0.01) ? 'text-success' : 'text-danger' ?>">
                        <b>Bs. <?= number_format($saldo_bs, 2, ".", ",") ?></b>
                    </div>

                    <div class="small text-muted">
                        <!-- <?= HtmlEncode($moneda_doc) ?> <?= number_format($saldo_div, 2, ".", ",") ?> -->
                        USD <?= number_format($saldo_div, 2, ".", ",") ?>
                    </div>
                </div>

                <input type="hidden" id="pendiente_bs" value="<?= $saldo_bs ?>">
                <input type="hidden" id="pendiente_principal_bs" value="<?= $saldo_principal_bs ?>">
                <input type="hidden" id="pendiente_div" value="<?= $saldo_div ?>">
                <input type="hidden" id="tasa_dia_doc" value="<?= $tasa_dia ?>">
                <input type="hidden" id="moneda_doc" value="<?= HtmlEncode($moneda_doc) ?>">
                <input type="hidden" id="igtf_pct" value="<?= $igtf_pct ?>">
                <input type="hidden" id="iva_factura" value="<?= number_format($iva_factura, 2, ".", "") ?>">
                <input type="hidden" id="retencion_pct" value="<?= number_format($retencion_pct, 6, ".", "") ?>">
                <input type="hidden" id="reglas_destino_json" value='<?= HtmlEncode($reglas_json) ?>'>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 border-top border-primary border-3">
    <div class="card-body">

        <!-- FILA 1 -->
        <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="small fw-bold">MÉTODO</label>
            <select id="tipo_pago" class="form-select form-select-sm" onchange="cambiarMetodo(this.value)">
            <option value="">Seleccione...</option>
            <?php
            $metodos = ExecuteRows("SELECT valor1, valor2 FROM parametro WHERE codigo = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
            foreach ($metodos as $m) {
                $sel = ($tipo_pago_actual == $m['valor1']) ? "selected" : "";
                echo "<option value='{$m['valor1']}' $sel>{$m['valor2']}</option>";
            }
            ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="small fw-bold">MONTO</label>
            <input type="number" id="monto_input" class="form-control form-control-sm fw-bold border-primary"
                step="0.01"
                max="<?= in_array($tipo_pago_actual, ["RI", "RJ", "RR"], true) ? number_format($iva_factura, 2, ".", "") : "" ?>"
                value="<?= ($monto_metodo_default !== null)
                    ? number_format($monto_metodo_default, 2, ".", "")
                    : (($saldo_bs > 0) ? round($saldo_bs, 2) : '') ?>">
        </div>

        <div class="col-md-2">
            <label class="small fw-bold">MONEDA</label>
            <select id="moneda_input" class="form-select form-select-sm">
            <?php
            $lista = ["Bs.", $moneda_doc_sel];
            $lista = array_values(array_unique($lista));

            $lista_sql = [];
            foreach ($lista as $v) $lista_sql[] = "'" . AdjustSql($v) . "'";
            $sqlMon = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor1 IN (" . implode(",", $lista_sql) . ")";
            $monedas = ExecuteRows($sqlMon);

            foreach ($monedas as $mon) {
                echo "<option value='{$mon['valor1']}'>{$mon['valor1']}</option>";
            }
            ?>
            </select>
        </div>
        </div>

        <!-- FILA 2 -->
        <div class="row g-2 align-items-end mt-1">

        <div class="col-md-4">
            <label class="small fw-bold">BANCO</label>
            <?php
            // Bancos
            $bancos = ExecuteRows("SELECT campo_codigo AS codigo, campo_descripcion AS descripcion
                                FROM tabla WHERE tabla = 'BANCO'
                                ORDER BY campo_descripcion");
            ?>
            <select id="banco_input" class="form-select form-select-sm" style="max-width: 240px;">
                <option value="">Banco...</option>
                <?php foreach ($bancos as $b): ?>
                    <option value="<?= HtmlEncode($b["codigo"]) ?>"
                            data-code="<?= HtmlEncode($b["codigo"]) ?>">
                        <?= HtmlEncode($b["descripcion"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="small fw-bold" id="label_referencia">
                <?= in_array($tipo_pago_actual, ["RI", "RJ", "RR"], true) ? "NRO. COMPROBANTE" : "REFERENCIA" ?>
            </label>

            <?php if (in_array($tipo_pago_actual, ["RC", "RD"])): 
                $tabla = ($tipo_pago_actual == "RC") ? "recarga" : "recarga2";
                $recarga = ExecuteRow("SELECT id, saldo FROM $tabla WHERE cliente = " . intval($factura['cliente']) . " ORDER BY id DESC LIMIT 1");
            ?>
                <input type="hidden" id="ref_input" value="<?= $recarga['id'] ?? '' ?>">
                <input type="text" class="form-control form-control-sm bg-white fw-bold"
                    value="<?= number_format($recarga['saldo'] ?? 0, 2, ".", "") ?>" readonly>
            <?php else: ?>
                <input type="text" id="ref_input" class="form-control form-control-sm"
                    placeholder="<?= in_array($tipo_pago_actual, ["RI", "RJ", "RR"], true) ? "Nro. comprobante..." : "Referencia..." ?>"
                    <?= in_array($tipo_pago_actual, ["RI", "RJ", "RR"], true)
                        ? 'maxlength="14" inputmode="numeric" pattern="[0-9]{14}" autocomplete="off"'
                        : '' ?>>
            <?php endif; ?>

        </div>

            <div class="col-md-4 d-flex flex-column justify-content-end">
                <button type="button"
                        class="btn btn-primary btn-sm w-100 fw-bold"
                        onclick="return agregarPago(event)">
                    ADD
                </button>

                <small class="mt-2 text-muted" id="help_ref_banco"></small>
            </div>
        </div>

        <!-- FILA 3 -->
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
                    <?= HtmlEncode(trim($c["banco"])) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <small class="text-muted d-block mt-1" id="help_destino"></small>
            </div>
        </div>

        <!-- FILA 4 -->
        <div class="row g-2 align-items-end mt-1">

            <!-- /////// -->
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
                        <option value="<?= $idA ?>"
                                data-moneda="<?= HtmlEncode($monA) ?>"
                                data-saldo="<?= HtmlEncode($saldoA) ?>">
                            <?= HtmlEncode($txt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted d-block mt-1">El monto se tomará del saldo del anticipo seleccionado.</small>
            </div>
            <?php endif; ?>
            <!-- /////// -->
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
                        <?= HtmlEncode($p['metodo_nom'] ?? '') ?><?= $esIG ? " (IGTF)" : "" ?>
                    </span>
                    <small class="text-muted">
                        Ref: <?= HtmlEncode($p['ref'] ?? '') ?>
                        <?php if (!empty($p["banco_nom"])): ?>
                            · Banco: <?= HtmlEncode($p["banco_nom"]) ?>
                        <?php endif; ?>

                        <?php if (!empty($p["destino_nom"])): ?>
                            · Destino: <?= HtmlEncode($p["destino_nom"]) ?>
                        <?php endif; ?>
                    </small>
                </div>

                <div class="d-flex align-items-center">
                    <span class="fw-bold text-primary me-3">
                        <?= HtmlEncode($p["moneda"] ?? "") ?> <?= number_format(floatval($p["monto"] ?? 0), 2, ".", ",") ?>
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

        // Lógica banco / referencia según tipo
        (function(){
            const tipo = ($("#tipo_pago").val() || "").trim();

            const requiereBanco = !["EF","RI","RJ","RR","RC","IG","RS","AN"].includes(tipo);
            const requiereRef   = !["EF","RC","IG","AN"].includes(tipo);

            $("#banco_input").prop("disabled", !requiereBanco);

            $("#help_ref_banco").text(
                (["RS","RI","RJ","RR"].includes(tipo)) ? "Requiere: número de comprobante." :
                (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
                (requiereRef) ? "Requiere: referencia." : ""
            );
        })();
    </script>
    <?php
    exit;  
}

// 3. Bloque FINALIZAR (server-side)
if ($accion == "finalizar") {
    if (ob_get_length()) ob_end_clean();

    $pagos_json = Param("pagos") ?? "[]";
    $confirmar_exceso = trim((string)(Param("confirmar_exceso") ?? "0"));
    $lista = json_decode($pagos_json, true);

    if (!is_array($lista) || count($lista) == 0) {
        jsonResponse(["success" => false, "message" => "No hay pagos para registrar."]);
        exit;
    }

    // Traer factura y tasa_dia
    $sqlFact = "SELECT a.id, a.cliente, a.nro_documento, a.moneda,
                       CASE WHEN a.moneda <> 'Bs.' THEN (a.total * a.tasa_dia) ELSE a.total END AS total_bs,
                       CASE WHEN a.moneda <> 'Bs.' THEN a.total ELSE (a.total / NULLIF(a.tasa_dia, 0)) END AS total_div,
                       a.tasa_dia, a.iva
                FROM salidas a
                WHERE a.id = $id_compra;";

    $factura = ExecuteRow($sqlFact);
    if (!$factura) {
        jsonResponse(["success" => false, "message" => "Factura no encontrada."]);
        exit;
    }

    $cliente_id = intval($factura["cliente"] ?? 0);
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);
    $total_factura_bs = floatval($factura["total_bs"] ?? 0);

    // El límite de las retenciones se valida siempre contra el IVA en Bs.
    $iva_documento = floatval($factura["iva"] ?? 0);
    $moneda_factura = trim($factura["moneda"] ?? "Bs.");
    $iva_factura = round(
        ($moneda_factura !== "Bs.") ? ($iva_documento * $tasa_dia) : $iva_documento,
        2
    );

    // Blindaje para retenciones: RI, RJ y RR nunca pueden superar el IVA en Bs.
    foreach ($lista as $p) {
        $tipo_ret = trim($p["tipo"] ?? "");
        if (!in_array($tipo_ret, ["RI", "RJ", "RR"], true)) continue;

        $ref_ret = trim((string)($p["ref"] ?? ""));
        if (!preg_match('/^\d{14}$/', $ref_ret)) {
            jsonResponse([
                "success" => false,
                "message" => "El Nro. de Comprobante de " . $tipo_ret .
                             " debe contener exactamente 14 dígitos numéricos."
            ]);
            exit;
        }

        $monto_ret = round(floatval($p["monto"] ?? 0), 2);
        if ($monto_ret > $iva_factura + 0.0001) {
            jsonResponse([
                "success" => false,
                "message" => "El monto de " . $tipo_ret . " no puede ser mayor al IVA de la factura (Bs. " .
                             number_format($iva_factura, 2, ".", ",") . ")."
            ]);
            exit;
        }
    }

    // Blindaje del lado servidor: recalcular cada línea IGTF usando como
    // base máxima el principal pendiente de la factura. Así, aunque el
    // navegador envíe un IGTF inflado, nunca se guarda de más.
    $igtf_pct = floatval(ExecuteScalar(
        "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S'"
    ) ?: 0);
    if ($igtf_pct > 0 && $igtf_pct < 1) {
        $igtf_pct *= 100;
    }

    $saldo_principal_igtf_bs = $total_factura_bs;
    $igtf_esperado_por_gid = [];

    foreach ($lista as $i => $p) {
        $tipo = trim($p["tipo"] ?? "");
        if ($tipo === "IG") continue;

        $monto = floatval($p["monto"] ?? 0);
        $mon   = trim($p["moneda"] ?? "Bs.");
        if ($monto <= 0) continue;

        $monto_bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);

        if ($mon !== "Bs." && $igtf_pct > 0) {
            $base_igtf_bs = min($monto_bs, max(0, $saldo_principal_igtf_bs));
            $gid = (string)($p["gid"] ?? "");

            // Base fiscal real del método de pago. Debe permanecer igual
            // aunque luego el pago sea recortado para excluir el vuelto.
            $lista[$i]["base_igtf_bs"] = round($base_igtf_bs, 2);

            if ($gid !== "") {
                $igtf_esperado_por_gid[$gid] = round(($base_igtf_bs * $igtf_pct) / 100, 2);
            }
        }

        $saldo_principal_igtf_bs = max(0, $saldo_principal_igtf_bs - $monto_bs);
    }

    foreach ($lista as $i => $p) {
        if (trim($p["tipo"] ?? "") !== "IG") continue;

        $gid = (string)($p["gid"] ?? "");
        $lista[$i]["monto"] = ($gid !== "" && isset($igtf_esperado_por_gid[$gid]))
            ? $igtf_esperado_por_gid[$gid]
            : 0;
        $lista[$i]["moneda"] = "Bs.";
    }

    $lista = array_values(array_filter($lista, function ($p) {
        return floatval($p["monto"] ?? 0) > 0;
    }));

    $total_bs = $total_factura_bs;
    foreach ($lista as $pp) {
        if (trim($pp["tipo"] ?? "") === "IG") {
            $total_bs += floatval($pp["monto"] ?? 0);
        }
    }

    // Recalcular pagado BS (ignorando IG)
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

    // Si el pago supera el total a cobrar, primero mostramos al usuario el
    // vuelto en Bs. y USD. Al confirmar, el excedente no se guarda como pago:
    // se recorta desde el último método agregado, sin tocar anticipos.
    if ($vuelto_bs > 0.02) {
        if ($confirmar_exceso !== "1") {
            $vuelto_usd = ($tasa_dia > 0) ? ($vuelto_bs / $tasa_dia) : 0;

            jsonResponse([
                "success" => false,
                "requiere_confirmacion" => true,
                "message" => "El total pagado excede el total de la factura.",
                "total_bs" => round($total_bs, 2),
                "pagado_bs" => round($pagado_bs, 2),
                "vuelto_bs" => round($vuelto_bs, 2),
                "vuelto_usd" => round($vuelto_usd, 2)
            ]);
            exit;
        }

        $vuelto_usd_info = ($tasa_dia > 0) ? ($vuelto_bs / $tasa_dia) : 0;
        $remaining = $vuelto_bs;
        $gids_removidos = [];
        $gids_ajustados = [];

        // Recortar desde el último pago agregado. Los anticipos no pueden
        // convertirse en vuelto y las líneas IGTF se ajustan después.
        for ($i = count($lista) - 1; $i >= 0 && $remaining > 0.02; $i--) {
            $p = $lista[$i];
            $tipo = trim($p["tipo"] ?? "");

            if ($tipo === "IG" || $tipo === "AN") {
                continue;
            }

            $monto = floatval($p["monto"] ?? 0);
            $mon = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0) {
                continue;
            }

            $item_bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);
            $gid = (string)($p["gid"] ?? "");

            if ($item_bs <= $remaining + 0.0001) {
                $remaining -= $item_bs;
                $lista[$i]["monto"] = 0;

                if ($gid !== "") {
                    $gids_removidos[] = $gid;
                }
            } else {
                $nuevo_item_bs = $item_bs - $remaining;
                $nuevo_monto = ($mon === "Bs.")
                    ? $nuevo_item_bs
                    : (($tasa_dia > 0) ? ($nuevo_item_bs / $tasa_dia) : 0);

                $lista[$i]["monto"] = round($nuevo_monto, 2);

                // Conservar el valor exacto en Bs. Aunque la divisa se
                // redondee a 2 decimales, el monto_bs debe cubrir exactamente
                // factura + IGTF.
                $lista[$i]["monto_bs_forzado"] = round($nuevo_item_bs, 2);

                if ($gid !== "") {
                    $gids_ajustados[$gid] = round($nuevo_item_bs, 2);
                }

                $remaining = 0;
            }
        }

        // Recalcular las líneas IGTF vinculadas a pagos recortados.
        foreach ($lista as $i => $p) {
            if (trim($p["tipo"] ?? "") !== "IG") {
                continue;
            }

            $gid = (string)($p["gid"] ?? "");
            if ($gid === "") {
                continue;
            }

            if (in_array($gid, $gids_removidos, true)) {
                $lista[$i]["monto"] = 0;
            } elseif (isset($gids_ajustados[$gid])) {
                // El IGTF ya está calculado sobre la base correcta de la
                // factura. No debe recalcularse sobre el pago recortado.
                $lista[$i]["moneda"] = "Bs.";
            }
        }

        $lista = array_values(array_filter($lista, function ($p) {
            return floatval($p["monto"] ?? 0) > 0;
        }));

        // Recalcular todo luego del recorte.
        $total_bs = $total_factura_bs;
        foreach ($lista as $p) {
            if (trim($p["tipo"] ?? "") === "IG") {
                $total_bs += floatval($p["monto"] ?? 0);
            }
        }

        $pagado_bs = 0;
        foreach ($lista as $p) {
            $tipo = trim($p["tipo"] ?? "");
            if ($tipo === "IG") {
                continue;
            }

            $monto = floatval($p["monto"] ?? 0);
            $mon = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0) {
                continue;
            }

            $monto_bs_forzado = floatval($p["monto_bs_forzado"] ?? 0);
            $pagado_bs += ($monto_bs_forzado > 0)
                ? $monto_bs_forzado
                : (($mon === "Bs.") ? $monto : ($monto * $tasa_dia));
        }

        // Si todavía existe exceso, necesariamente proviene de un anticipo.
        if (($pagado_bs - $total_bs) > 0.02) {
            jsonResponse([
                "success" => false,
                "message" => "No se pudo ajustar el pago al total de la factura porque el excedente proviene " .
                             "de un anticipo aplicado, y un anticipo no puede devolverse como vuelto. " .
                             "Ajuste manualmente el monto del anticipo o de los demás pagos."
            ]);
            exit;
        }
    }

    // Validación: anticipos (si hay AN) contra saldo disponible
    foreach ($lista as $p) {
        $tipo = trim($p["tipo"] ?? "");
        if ($tipo !== "AN") continue;

        $anticipo_id = intval($p["anticipo_id"] ?? 0);
        $monto = floatval($p["monto"] ?? 0);
        $mon   = trim($p["moneda"] ?? "");

        if ($anticipo_id <= 0) {
            jsonResponse(["success" => false, "message" => "Anticipo inválido (anticipo_id)."]);
            exit;
        }
        if ($monto <= 0) {
            jsonResponse(["success" => false, "message" => "Monto inválido para anticipo."]);
            exit;
        }
        if ($mon === "") {
            jsonResponse(["success" => false, "message" => "Moneda inválida para anticipo."]);
            exit;
        }

        // saldo disponible del anticipo (misma lógica que en refrescar)
        $sqlSaldoAnt = "
            SELECT
              (SUM(ccd.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                  AND a.moneda = ccd.moneda
              ), 0)) AS saldo
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle ccd ON ccd.cobros_cliente = cc.id
            WHERE cc.id = " . intval($anticipo_id) . "
              AND cc.cliente = " . intval($cliente_id) . "
              AND cc.id_documento = 0
              AND ccd.moneda = '" . AdjustSql($mon) . "'
            GROUP BY cc.id, ccd.moneda
        ";

        $saldoAnt = floatval(ExecuteScalar($sqlSaldoAnt) ?: 0);
        if ($saldoAnt + 0.0001 < $monto) {
            jsonResponse([
                "success" => false,
                "message" => "El anticipo #$anticipo_id no tiene saldo suficiente en $mon. Disponible: " .
                             number_format($saldoAnt, 2, ".", ",") .
                             " / Requerido: " . number_format($monto, 2, ".", ",")
            ]);
            exit;
        }
    }

    // ----------------- INSERTS (TRANSACCIÓN) -----------------
    $conn = Conn();
    $username = CurrentUserName(); // PHPMaker

    try {
        $conn->beginTransaction();

        // Insert cabecera cobros_cliente (cobro de factura)
        // Nota: fecha y fecha_registro como CurrentDate()
        $sqlInsCab = "
            INSERT INTO cobros_cliente
            (cliente, id_documento, fecha, fecha_registro, username, moneda, tasa_cambio, monto)
            VALUES
            (" . intval($cliente_id) . ",
            " . intval($id_compra) . ",
            '" . AdjustSql(CurrentDate()) . "',
            '" . AdjustSql(CurrentDate()) . "',
            '" . AdjustSql($username) . "',
            '" . AdjustSql("Bs.") . "',
            " . floatval($tasa_dia) . ",
            " . floatval($pagado_bs) . ")
        ";
        Execute($sqlInsCab);

        $cobro_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));
        if ($cobro_id <= 0) {
            throw new \Exception("No se pudo obtener ID de cobro (cabecera).");
        }

        // Insert detalles
        $x_igtf = "N";
        $x_monto_base_igtf = 0; // en Bs.
        $x_monto_igtf = 0;      // en Bs.

        foreach ($lista as $p) {

            $tipo  = trim($p["tipo"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0 || $tipo === "") continue;

            // Calcula Bs según tasa del documento. Si el pago fue
            // recortado por vuelto, conservar el monto exacto en Bs.
            $monto_bs_forzado = floatval($p["monto_bs_forzado"] ?? 0);
            $monto_bs = ($monto_bs_forzado > 0)
                ? $monto_bs_forzado
                : (($mon === "Bs.") ? $monto : ($monto * $tasa_dia));

            // ---- acumuladores IGTF ----
            // 1) Base IGTF: SOLO pagos en divisa que generan IG (o sea, mon != Bs y tipo != IG)
            // (si quieres excluir AN cuando el IGTF lo cobras al aplicar anticipo, dime y lo excluimos aquí)
            if ($tipo !== "IG" && $mon !== "Bs.") {
                $base_igtf_guardada = floatval($p["base_igtf_bs"] ?? 0);
                $x_monto_base_igtf += ($base_igtf_guardada > 0)
                    ? $base_igtf_guardada
                    : $monto_bs;
            }

            // 2) IGTF acumulado: registros IG (siempre Bs)
            if ($tipo === "IG") {
                $x_igtf = "S";
                $mon = "Bs.";        // blindaje visual
                $monto_bs = $monto;  // IG ya viene en Bs.
                $x_monto_igtf += $monto_bs;
            }

            // tasa_moneda correcta
            $tasa_moneda = ($mon === "Bs.") ? 1 : $tasa_dia;

            // ...
            $ref = trim($p["ref"] ?? "");
            $banco_origen = trim($p["banco_cod"] ?? "");
            $destino_id = intval($p["destino_id"] ?? 0);
            $anticipo_id = ($tipo === "AN") ? intval($p["anticipo_id"] ?? 0) : 0;

            $sqlBancoDestino = ($destino_id > 0) ? (string)$destino_id : "NULL";
            $sqlBancoOrigen  = ($banco_origen !== "") ? ("'" . AdjustSql($banco_origen) . "'") : "NULL";
            $sqlRef          = ($ref !== "") ? ("'" . AdjustSql($ref) . "'") : "NULL";
            $sqlAnticipo     = ($tipo === "AN") ? (string)$anticipo_id : "NULL";

            $sqlInsDet = "
                INSERT INTO cobros_cliente_detalle
                (cobros_cliente, metodo_pago, referencia,
                monto_moneda, moneda, tasa_moneda, monto_bs,
                banco_origen, banco, anticipo_id)
                VALUES
                (" . intval($cobro_id) . ",
                '" . AdjustSql($tipo) . "',
                $sqlRef,
                " . floatval($monto) . ",
                '" . AdjustSql($mon) . "',
                " . floatval($tasa_moneda) . ",
                " . floatval($monto_bs) . ",
                $sqlBancoOrigen,
                $sqlBancoDestino,
                $sqlAnticipo
                )
            ";
            Execute($sqlInsDet);

            // Si es anticipo, registrar aplicación (esto ES lo que rebaja el saldo)
            if ($tipo === "AN") {

                if ($anticipo_id <= 0) {
                    throw new \Exception("Anticipo inválido (anticipo_id).");
                }

                // Recomendado: validar que el anticipo pertenece al cliente (blindaje extra)
                $okAnt = ExecuteScalar("
                    SELECT COUNT(*)
                    FROM cobros_cliente
                    WHERE id = " . intval($anticipo_id) . "
                    AND cliente = " . intval($cliente_id) . "
                    AND id_documento = 0
                    LIMIT 1
                ");
                if (intval($okAnt) <= 0) {
                    throw new \Exception("El anticipo #$anticipo_id no pertenece a este cliente o no es un anticipo válido.");
                }

                $sqlInsApp = "
                    INSERT INTO anticipos_aplicaciones
                    (anticipo_cobro_id, cobro_factura_id, salida_id, fecha, username,
                    monto_moneda, moneda, tasa_factura)
                    VALUES
                    (" . intval($anticipo_id) . ",
                    " . intval($cobro_id) . ",
                    " . intval($id_compra) . ",
                    NOW(),
                    '" . AdjustSql($username) . "',
                    " . floatval($monto) . ",
                    '" . AdjustSql($mon) . "',
                    " . floatval($tasa_dia) . ")
                ";
                Execute($sqlInsApp);
            }

        }

        $sql = "UPDATE salidas  
                SET
                    pagado = 'S' 
                WHERE id = " . QuotedValue($id_compra, 1) . ";";
        Execute($sql);

        if($x_igtf === "S") {
            // Actualizo IGTF en la tabla salidas
            $sql = "UPDATE salidas  
                    SET
                        igtf = '$x_igtf',
                        monto_base_igtf = $x_monto_base_igtf,
                        monto_igtf = $x_monto_igtf
                    WHERE id = " . QuotedValue($id_compra, 1) . ";";
            Execute($sql);
        }

        $conn->commit();

        $mensaje_ok = "Cobro registrado correctamente.";
        if ($vuelto_bs > 0.02) {
            $mensaje_ok .= " Vuelto entregado: Bs. " .
                number_format($vuelto_bs, 2, ".", ",") .
                " / USD " . number_format($vuelto_usd_info, 2, ".", ",") .
                " (el excedente no se registró como abono).";
        }

        jsonResponse([
            "success" => true,
            "message" => $mensaje_ok,
            "cobro_id" => $cobro_id,
            "vuelto_bs" => round($vuelto_bs, 2),
            "vuelto_usd" => round($vuelto_usd_info, 2)
        ]);
        exit;

    } catch (\Throwable $ex) {
        try { $conn->rollBack(); } catch (\Throwable $e2) {}
        jsonResponse([
            "success" => false,
            "message" => "Error guardando cobro: " . $ex->getMessage()
        ]);
        exit;
    }
}

?>

<div class="container-fluid py-3" style="max-width: 900px;">
    <div id="div-ajax">
        <div class="text-center p-5"><div class="spinner-border text-primary"></div></div>
    </div>
    <input type="hidden" id="json_pagos" value="[]">
    <div class="mt-4 text-center">
        <button type="button" id="btn-registrar-final" class="btn btn-success btn-lg px-5 shadow fw-bold" disabled onclick="finalizar()">
            REGISTRAR TRANSACCIÓN
        </button>
        <button type="button"
                id="btn-cancelar"
                class="btn btn-outline-secondary btn-lg px-5 shadow-sm fw-bold"
                onclick="return cancelar(event)">
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
            <div class="modal-body">
                Se perderán los pagos agregados.<br><br>
                ¿Desea continuar?
            </div>
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
                        El pago registrado supera el total de la factura.
                        Verifique el vuelto que debe entregar al cliente antes de continuar.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success fw-bold" id="btnContinuarVuelto">
                        Continuar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let ajaxInFlight = false;

function refrescar(metodo = "") {

    // Construye data normal
    const data = {
        accion: "refrescar",
        id_compra: "<?= $id_compra ?>",
        pagos: $("#json_pagos").val(),
        tipo_pago: metodo
    };

    // ✅ CSRF/Anti-forgery PHPMaker (CRÍTICO en Custom File)
    if (window.ew) {
        // Token name
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) {
            data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        }
        // Anti-forgery token
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) {
            data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
        }
    }

    $.ajax({
        url: window.location.href,
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

function cambiarMetodo(v) {
    refrescar(v);
}

function agregarPago(e) {
    e = e || window.event;
    if (e) { e.preventDefault(); e.stopPropagation(); }

    const ref = ($("#ref_input").val() || "").trim();

    // Banco (select normal o select2)
    let bancoCod = ($("#banco_input").val() || "").toString().trim();
    if (!bancoCod && $("#banco_input").hasClass("select2-hidden-accessible")) {
        const d = $("#banco_input").select2("data") || [];
        if (d.length && d[0].id) bancoCod = (d[0].id || "").toString().trim();
    }
    const bancoNom = ($("#banco_input option:selected").text() || "").trim();

    const t = ($("#tipo_pago").val() || "").trim();
    const tipo = t; // 👈 ÚNICA variable "tipo"
    const m = parseFloat($("#monto_input").val());
    const monSel = ($("#moneda_input").val() || "Bs.").trim();

    if (!tipo) {
        ew.alert("Seleccione un método de pago.");
        return false;
    }
    if (isNaN(m) || m <= 0) {
        ew.alert("Indique un monto válido.");
        return false;
    }

    if (["RI", "RJ", "RR"].includes(tipo)) {
        // El comprobante debe contener exactamente 14 dígitos numéricos.
        if (!/^\d{14}$/.test(ref)) {
            ew.alert("El Nro. de Comprobante debe contener exactamente 14 dígitos numéricos.");
            $("#ref_input").focus();
            return false;
        }

        const ivaFactura = parseFloat($("#iva_factura").val() || "0");
        if (m > ivaFactura + 0.0001) {
            ew.alert(
                "El monto no puede ser mayor al IVA de la factura: Bs. " +
                ivaFactura.toLocaleString("es-VE", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })
            );
            $("#monto_input").focus();
            return false;
        }
    }

    // Reglas banco/ref:
    // - Si tipo = (RS, RI, RJ, RR): obligatorio SOLO comprobante/referencia.
    // - Si tipo distinto a (EF, RI, RC, IG, RS, AN): obligatorio banco + referencia.
    // - Si tipo = EF, RC, IG, AN: no exigir ref/banco.
    const requiereRef = !["EF", "RC", "IG", "AN"].includes(tipo);
    const requiereBanco = !["EF", "RI", "RJ", "RR", "RC", "IG", "RS", "AN"].includes(tipo);

    const destinoId = ($("#destino_input").val() || "").toString().trim();

    // Requiere destino cuando entra dinero
    const requiereDestino = ["EF","TD","TC","TR","CH","DP","PM"].includes(t);

    if (requiereDestino && !destinoId) {
        ew.alert("Debe seleccionar la cuenta destino.");
        return false;
    }

    if (["RS", "RI", "RJ", "RR"].includes(tipo)) {
        if (!ref) {
            ew.alert(["RI", "RJ", "RR"].includes(tipo) ? "Debe indicar el número de comprobante." : "Debe indicar el número de referencia.");
            return false;
        }
    } else {
        if (requiereRef && !ref) {
            ew.alert("Debe indicar el número de referencia.");
            return false;
        }
        if (requiereBanco && !bancoCod) {
            ew.alert("Debe seleccionar un banco.");
            return false;
        }
    }

    // Evitar duplicar clicks
    if (ajaxInFlight) return false;

    // JSON actual
    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (err) {
        lista = [];
    }

    const gid = Date.now().toString(36) + Math.random().toString(36).slice(2, 8);

    // --------- AN (Anticipo) ----------
    let anticipoId = null;
    if (tipo === "AN") {
        anticipoId = ($("#anticipo_input").val() || "").trim();
        if (!anticipoId) {
            ew.alert("Debe seleccionar un anticipo.");
            return false;
        }

        // Validar moneda del anticipo vs moneda seleccionada
        const monAnt = ($("#anticipo_input option:selected").data("moneda") || "").toString().trim();
        if (monAnt && monSel && monAnt !== monSel) {
            ew.alert("La moneda del pago debe coincidir con la moneda del anticipo.");
            return false;
        }

        // Limitar monto al saldo del anticipo
        const saldoAnt = parseFloat($("#anticipo_input option:selected").data("saldo") || "0");
        if (!isNaN(saldoAnt) && m > saldoAnt + 0.0001) {
            ew.alert("El monto excede el saldo disponible del anticipo.");
            return false;
        }
    }

    // Item principal
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

    // Solo AN agrega anticipo_id + ajusta campos visuales
    if (tipo === "AN") {
        item.anticipo_id = parseInt(anticipoId, 10) || 0;
        item.ref = "ANTICIPO #" + item.anticipo_id;
        item.banco_cod = "";
        item.banco_nom = "";
    }

    item.destino_id = parseInt(destinoId, 10) || 0;
    item.destino_nom = ($("#destino_input option:selected").text() || "").trim();

    lista.push(item);

    // ---- IGTF automático si es pago en divisa (no Bs.) ----
    // Nota: AN también puede ser en divisa; si NO quieres IGTF para AN, dímelo y lo excluimos.
    // ---- IGTF automático (SIEMPRE en Bs.) cuando el pago NO sea Bs. ----
    const igtfPct = parseFloat($("#igtf_pct").val() || "0");
    const tasaDiaDoc = parseFloat($("#tasa_dia_doc").val() || "1");

    if (monSel !== "Bs." && igtfPct > 0) {

        const montoPagoBs = (tasaDiaDoc > 0) ? (m * tasaDiaDoc) : 0;
        const pendientePrincipalBs = parseFloat($("#pendiente_principal_bs").val() || "0");

        // El IGTF solo se calcula sobre la parte del pago que realmente
        // cubre el principal pendiente. El excedente no genera IGTF.
        const baseIgtfBs = Math.min(
            montoPagoBs,
            Math.max(0, pendientePrincipalBs)
        );
        const montoIgtfBs = (baseIgtfBs * igtfPct) / 100;

        // 🔹 eliminar IG previo del mismo gid si existiera
        lista = lista.filter(x => !(
            (x.gid || "") === gid &&
            (x.tipo || "") === "IG"
        ));

        lista.push({
            gid: gid,
            tipo: "IG",
            metodo_nom: "IGTF",
            ref: "AUTO",
            banco_cod: "",
            banco_nom: "",
            monto: parseFloat(montoIgtfBs.toFixed(2)),
            moneda: "Bs.",
            es_igtf: 1,
            destino_id: parseInt(destinoId, 10) || 0,
            destino_nom: ($("#destino_input option:selected").text() || "").trim()
        });
    }

    $("#json_pagos").val(JSON.stringify(lista));

    // refrescar manteniendo método actual
    refrescar(tipo);

    return false;
}

function eliminarPago(i) {
    if (ajaxInFlight) return false;

    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (err) {
        lista = [];
    }

    const item = lista[i];
    if (!item) return false;

    // Si por alguna razón intentan eliminar IG, lo bloqueamos
    if ((item.tipo || "").trim() === "IG") {
        return false;
    }

    const gid = (item.gid || "").toString();

    // Elimina el pago seleccionado
    lista.splice(i, 1);

    // Si tenía gid, elimina también el IG asociado (tipo IG con mismo gid)
    if (gid) {
        lista = lista.filter(x => !(((x.gid || "").toString() === gid) && ((x.tipo || "").trim() === "IG")));
    }

    $("#json_pagos").val(JSON.stringify(lista));

    refrescar($("#tipo_pago").val() || "");
    return false;
}

function fmtMoneda(n) {
    n = parseFloat(n || 0);
    return n.toLocaleString("es-VE", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
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

function finalizar(forzarConExceso = false) {
    if (ajaxInFlight) {
        return false;
    }

    const pagos = $("#json_pagos").val() || "[]";

    let data = {
        accion: "finalizar",
        id_compra: "<?= $id_compra ?>",
        pagos: pagos,
        confirmar_exceso: forzarConExceso ? "1" : "0"
    };

    // Tokens PHPMaker
    if (window.ew) {
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) {
            data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        }
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) {
            data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
        }
    }

    ajaxInFlight = true;
    $("#btn-registrar-final").prop("disabled", true);

    $.ajax({
        url: window.location.href,
        type: "POST",
        dataType: "text",
        data: data,

        success: function (txt) {
            ajaxInFlight = false;

            let resp = null;
            try {
                resp = JSON.parse(txt);
            } catch (e) {}

            if (resp && resp.success) {
                window.location.href = "ViewOutTdcfcvView/<?= $id_compra ?>?showdetail=";
                return;
            }

            $("#btn-registrar-final").prop("disabled", false);

            if (resp && resp.requiere_confirmacion) {
                mostrarModalVuelto(resp);
                return;
            }

            ew.alert(
                (resp && resp.message)
                    ? resp.message
                    : (txt || "Respuesta vacía del servidor.")
            );
        },

        error: function (xhr, status, err) {
            ajaxInFlight = false;
            $("#btn-registrar-final").prop("disabled", false);

            ew.alert(
                "Error servidor: " + status + " / " + err +
                "\n\n" + (xhr.responseText || "")
            );
        }
    });

    return false;
}

function cancelar(e) {
    e = e || window.event;
    if (e) { e.preventDefault(); e.stopPropagation(); }

    // Detectar pagos: primero por JSON
    let lista = [];
    const raw = ($("#json_pagos").val() || "").trim();
    if (raw && raw !== "[]") {
        try {
            const tmp = JSON.parse(raw);
            if (Array.isArray(tmp)) lista = tmp;
        } catch (err) {
            // Si está dañado, asumimos que sí hay pagos (para preguntar)
            lista = ["__json_invalido__"];
        }
    }

    // Fallback: contar items del DOM (header + items)
    const itemsDom = $("#div-ajax .list-group .list-group-item").length;
    const pagosDom = Math.max(0, itemsDom - 1);

    const hayPagos = (lista.length > 0) || (pagosDom > 0);
    const url = "ViewOutTdcfcvView/<?= $id_compra ?>?showdetail=";

    if (!hayPagos) {
        window.location.href = url;
        return false;
    }

    // Mostrar modal bootstrap si está disponible
    try {
        const el = document.getElementById("mdlCancelar");
        if (el && window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            modal.show();

            // Confirmación
            $("#btnConfirmarCancelar").off("click").on("click", function () {
                window.location.href = url;
            });

            return false;
        }
    } catch (err) {
        // cae al confirm nativo
    }

    // Respaldo: confirm nativo
    if (confirm("¿Está seguro que desea cancelar?\n\nSe perderán los pagos agregados.")) {
        window.location.href = url;
    }
    return false;
}

function recalcularMontoSegunMoneda() {
    const tipo = ($("#tipo_pago").val() || "").trim();
    const ivaFactura = parseFloat($("#iva_factura").val() || "0");

    // RI/RJ: aplicar el porcentaje de retención al IVA ya convertido a Bs.
    // RR: cero por defecto.
    if (["RI", "RJ"].includes(tipo)) {
        const pct = parseFloat($("#retencion_pct").val() || "0");
        const monto = (pct > 0) ? ((ivaFactura * pct) / 100) : 0;
        $("#monto_input").val(monto.toFixed(2)).attr("max", ivaFactura.toFixed(2));
        return;
    }

    if (tipo === "RR") {
        $("#monto_input").val("0.00").attr("max", ivaFactura.toFixed(2));
        return;
    }

    $("#monto_input").removeAttr("max");

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

    if ($sel.hasClass("select2-hidden-accessible")) {
        $sel.select2("destroy");
    }

    if (!$.fn.select2) return;

    $sel.select2({
        width: "resolve",   // respeta el max-width del select
        placeholder: "Banco...",
        allowClear: true,
        dropdownParent: $("#div-ajax"),

        matcher: function (params, data) {
            const term = $.trim(params.term || "").toLowerCase();
            if (term === "") return data;

            const text = (data.text || "").toLowerCase();
            const code = ($(data.element).data("code") || "").toString().toLowerCase();

            // Permite buscar por código o por nombre
            if (text.indexOf(term) > -1 || code.indexOf(term) > -1) {
                return data;
            }
            return null;
        },

        // Mostrar SOLO el nombre del banco
        templateResult: function (data) {
            return data.text;
        },

        templateSelection: function (data) {
            return data.text;
        }
    });
}

/////
function parseReglasDestino() {
    const raw = ($("#reglas_destino_json").val() || "").trim();
    if (!raw) return [];
    try {
        const arr = JSON.parse(raw);
        return Array.isArray(arr) ? arr : [];
    } catch (e) {
        return [];
    }
}

function sugerirDestinoId(metodo, moneda) {
    metodo = (metodo || "").trim();
    moneda = (moneda || "").trim();

    const reglas = parseReglasDestino();

    // 1) match exacto
    let r = reglas.find(x =>
        String(x.metodo || "").trim() === metodo &&
        String(x.moneda || "").trim() === moneda
    );
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    // 2) comodín moneda=''
    r = reglas.find(x =>
        String(x.metodo || "").trim() === metodo &&
        String(x.moneda || "").trim() === ""
    );
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    return "";
}

function aplicarDestinoAuto(force) {
    const auto = $("#destino_auto").is(":checked");
    if (!auto && !force) return;

    const metodoSel = ($("#tipo_pago").val() || "").trim();
    const monedaSel = ($("#moneda_input").val() || "").trim();

    // Tipos que no llevan destino
    if (["IG","RC","RI","RJ","RR","RS","NC","AN"].includes(metodoSel)) {
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
/////


loadjs.ready("head", function () {
    // PHPMaker puede cargar jQuery en modo noConflict. Dejamos el alias
    // disponible para este Custom File y para los scripts AJAX insertados.
    window.$ = window.jQuery;

    (function(){
        const tipo = ($("#tipo_pago").val() || "").trim();
        const requiereBanco = !["EF","RI","RJ","RR","RC","IG","RS","AN"].includes(tipo);
        const requiereRef = !["EF","RC","IG","AN"].includes(tipo);

        const $b = $("#banco_input");
        $b.prop("disabled", !requiereBanco);

        if ($b.hasClass("select2-hidden-accessible")) {
            $b.trigger("change.select2");
        }

        $("#help_ref_banco").text(
            (["RS","RI","RJ","RR"].includes(tipo)) ? "Requiere: número de comprobante." :
            (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
            (requiereRef) ? "Requiere: referencia." : ""
        );
    })();

    $(document).on("change", "#tipo_pago", function () {
        const tipo = ($(this).val() || "").trim();

        const requiereBanco = !["EF","RI","RJ","RR","RC","IG","RS","AN"].includes(tipo);
        const requiereRef   = !["EF","RC","IG","AN"].includes(tipo);

        const $b = $("#banco_input");
        $b.prop("disabled", !requiereBanco);

        if ($b.hasClass("select2-hidden-accessible")) {
            $b.trigger("change.select2");
        }

        $("#help_ref_banco").text(
            (["RS","RI","RJ","RR"].includes(tipo)) ? "Requiere: número de comprobante." :
            (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
            (requiereRef) ? "Requiere: referencia." : ""
        );

        aplicarDestinoAuto(true);
    });

    // RI, RJ y RR: permitir únicamente números y un máximo de 14 dígitos.
    $(document).on("input", "#ref_input", function () {
        const tipo = ($("#tipo_pago").val() || "").trim();

        if (["RI", "RJ", "RR"].includes(tipo)) {
            this.value = this.value.replace(/\D/g, "").substring(0, 14);
        }
    });

    $(document).on("change", "#anticipo_input", function () {
        const mon = ($(this).find("option:selected").data("moneda") || "").toString().trim();
        if (mon) {
            $("#moneda_input").val(mon).trigger("change");
        }
    });

    $(document).on("change", "#moneda_input", function () {
        recalcularMontoSegunMoneda();
        aplicarDestinoAuto(true);
    });

    $(document).on("change", "#destino_auto", function () {
        if ($(this).is(":checked")) aplicarDestinoAuto(true);
    });

    refrescar("");
});
</script>


<style>
    .input-group .form-control, .input-group .form-select { border-radius: 4px !important; }
</style>
<?= GetDebugMessage() ?>
