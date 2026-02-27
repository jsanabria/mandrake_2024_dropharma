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
                        a.tasa_dia  
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
    }

    $igtf_pct = floatval(ExecuteScalar("SELECT alicuota AS IGTF FROM alicuota WHERE codigo = 'IGT' AND activo = 'S'") ?: 0);

    // Si viene como 0.03 (3%), conviértelo a 3
    if ($igtf_pct > 0 && $igtf_pct < 1) {
        $igtf_pct = $igtf_pct * 100;
    }

    /////
    $moneda_doc = trim($factura["moneda"] ?? "Bs.");
    $moneda_doc_sel = $moneda_doc == "Bs." ? "USD" : $moneda_doc;
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);

    // Totales del documento:
    // - total     = Bs (por tu CASE)
    // - totalDivisa = Divisa del doc (por tu CASE)
    $total_bs  = floatval($factura["total"] ?? 0);
    $total_div = floatval($factura["totalDivisa"] ?? 0);

    $total_bs_pagado  = 0;
    $total_div_pagado = 0;

    foreach ($lista_pagos as $p) {
        $tipo_reg = trim($p["tipo"] ?? "");
        if ($tipo_reg === "IG") continue;

        $monto_p = floatval($p["monto"] ?? 0);
        $mon_p   = trim($p["moneda"] ?? "Bs.");
        if ($monto_p <= 0) continue;

        if ($mon_p === "Bs.") {
            $bs = $monto_p;
            $div = ($tasa_dia > 0) ? ($monto_p / $tasa_dia) : 0;
        } else { 
            // aquí mon_p debe ser = moneda_doc (USD o EURO)
            $div = $monto_p;
            $bs  = $monto_p * $tasa_dia;
        }

        $total_bs_pagado  += $bs;
        $total_div_pagado += $div;
    }

    $saldo_bs  = max(0, $total_bs - $total_bs_pagado);
    $saldo_div = max(0, $total_div - $total_div_pagado);

    $saldo_restante = $saldo_bs;
    ?>

    <div class="card shadow-sm border-0 mb-3 bg-light">
        <div class="card-body p-3 text-center">
            <div class="row g-0">
                <div class="col-4 border-end"><small class="text-muted d-block">CLIENTE</small><b><?= $factura['nombre_cliente'] ?></b></div>
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
                        <?= HtmlEncode($moneda_doc) ?> <?= number_format($saldo_div, 2, ".", ",") ?>
                    </div>
                </div>

                <input type="hidden" id="pendiente_bs" value="<?= $saldo_bs ?>">
                <input type="hidden" id="pendiente_div" value="<?= $saldo_div ?>">
                <input type="hidden" id="tasa_dia_doc" value="<?= $tasa_dia ?>">
                <input type="hidden" id="moneda_doc" value="<?= HtmlEncode($moneda_doc) ?>">
                <input type="hidden" id="igtf_pct" value="<?= $igtf_pct ?>">
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
                step="0.01" value="<?= ($saldo_bs > 0) ? round($saldo_bs, 2) : '' ?>">
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
            <label class="small fw-bold">REFERENCIA</label>

            <?php if (in_array($tipo_pago_actual, ["RC", "RD"])): 
                $tabla = ($tipo_pago_actual == "RC") ? "recarga" : "recarga2";
                $recarga = ExecuteRow("SELECT id, saldo FROM $tabla WHERE cliente = " . intval($factura['cliente']) . " ORDER BY id DESC LIMIT 1");
            ?>
                <input type="hidden" id="ref_input" value="<?= $recarga['id'] ?? '' ?>">
                <input type="text" class="form-control form-control-sm bg-white fw-bold"
                    value="<?= number_format($recarga['saldo'] ?? 0, 2, ".", "") ?>" readonly>
            <?php else: ?>
                <input type="text" id="ref_input" class="form-control form-control-sm" placeholder="Referencia...">
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
    exit;  
}

// 3. Bloque FINALIZAR (server-side)
if ($accion == "finalizar") {
    if (ob_get_length()) ob_end_clean();

    $pagos_json = Param("pagos") ?? "[]";
    $lista = json_decode($pagos_json, true);

    if (!is_array($lista) || count($lista) == 0) {
        jsonResponse(["success" => false, "message" => "No hay pagos para registrar."]);
        exit;
    }

    // Traer factura y tasa_dia
    $sqlFact = "SELECT a.id, a.cliente, a.nro_documento, a.moneda,
                       CASE WHEN a.moneda <> 'Bs.' THEN (a.total * a.tasa_dia) ELSE a.total END AS total_bs,
                       CASE WHEN a.moneda <> 'Bs.' THEN a.total ELSE (a.total / NULLIF(a.tasa_dia, 0)) END AS total_div,
                       a.tasa_dia
                FROM salidas a
                WHERE a.id = $id_compra;";

    $factura = ExecuteRow($sqlFact);
    if (!$factura) {
        jsonResponse(["success" => false, "message" => "Factura no encontrada."]);
        exit;
    }

    $cliente_id = intval($factura["cliente"] ?? 0);
    $tasa_dia   = floatval($factura["tasa_dia"] ?? 1);
    $total_bs   = floatval($factura["total_bs"] ?? 0);

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

    // Validación: no pagar de más (tolerancia 0.02)
    if ($pagado_bs > ($total_bs + 0.02)) {
        jsonResponse([
            "success" => false,
            "message" => "El total pagado excede el total de la factura. Total Bs: " .
                         number_format($total_bs, 2, ".", ",") .
                         " / Pagado Bs: " . number_format($pagado_bs, 2, ".", ",")
        ]);
        exit;
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
        foreach ($lista as $p) {

            $tipo  = trim($p["tipo"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            if ($monto <= 0 || $tipo === "") continue;

            if ($tipo === "IG") {
                $mon = "Bs."; // 👈 blindaje
            }

            $ref = trim($p["ref"] ?? "");

            // ✅ banco_origen (VARCHAR) viene del select2 (código: BCO0xx)
            $banco_origen = trim($p["banco_cod"] ?? "");

            // ✅ banco destino (INT) viene del nuevo select (compania_cuenta.id)
            $destino_id = intval($p["destino_id"] ?? 0);

            // anticipo_id solo si es AN
            $anticipo_id = ($tipo === "AN") ? intval($p["anticipo_id"] ?? 0) : 0;

            // Calcula Bs según tasa del documento
            $monto_bs = ($mon === "Bs.") ? $monto : ($monto * $tasa_dia);

            // NULLs correctos
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
                " . floatval($tasa_dia) . ",
                " . floatval($monto_bs) . ",
                $sqlBancoOrigen,
                $sqlBancoDestino,
                $sqlAnticipo
                )
            ";

            Execute($sqlInsDet);

            // Si es anticipo, registrar aplicación
            if ($tipo === "AN") {
                if ($anticipo_id <= 0) {
                    throw new \Exception("Anticipo inválido al guardar (anticipo_id).");
                }

                $sqlDestinoAnt = "
                    SELECT banco
                    FROM cobros_cliente_detalle
                    WHERE cobros_cliente = " . intval($anticipo_id) . "
                        AND banco IS NOT NULL
                    ORDER BY id DESC
                    LIMIT 1
                    ";
                $destinoAnt = intval(ExecuteScalar($sqlDestinoAnt) ?: 0);
                $destino_id = $destinoAnt;

                $sqlInsApp = "
                    INSERT INTO anticipos_aplicaciones
                    (anticipo_cobro_id, cobro_factura_id, salida_id, fecha, username, monto_moneda, moneda, tasa_factura)
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

        $conn->commit();

        jsonResponse([
            "success" => true,
            "message" => "Cobro registrado correctamente.",
            "cobro_id" => $cobro_id
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

    // Reglas banco/ref:
    // - Si tipo = (RS, RI): obligatorio SOLO referencia.
    // - Si tipo distinto a (EF, RI, RC, IG, RS, AN): obligatorio banco + referencia.
    // - Si tipo = EF, RC, IG, AN: no exigir ref/banco.
    const requiereRef = !["EF", "RC", "IG", "AN"].includes(tipo);
    const requiereBanco = !["EF", "RI", "RC", "IG", "RS", "AN"].includes(tipo);

    const destinoId = ($("#destino_input").val() || "").toString().trim();

    // Requiere destino cuando entra dinero
    const requiereDestino = ["EF","TD","TC","TR","CH","DP","PM"].includes(t);

    if (requiereDestino && !destinoId) {
        ew.alert("Debe seleccionar la cuenta destino.");
        return false;
    }

    if (["RS", "RI"].includes(tipo)) {
        if (!ref) {
            ew.alert("Debe indicar el número de referencia.");
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
        const montoIgtfBs = (montoPagoBs * igtfPct) / 100;

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

function finalizar() {
    const pagos = $("#json_pagos").val() || "[]";

    let data = {
        accion: "finalizar",
        id_compra: "<?= $id_compra ?>",
        pagos: pagos
    };

    // tokens PHPMaker
    if (window.ew) {
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    $.ajax({
        url: window.location.href,
        type: "POST",
        dataType: "text", // 👈 así vemos el error real
        data: data,
        success: function (txt) {
            console.log("RESP:", txt);

            let resp = null;
            try { resp = JSON.parse(txt); } catch (e) {}

            if (resp && resp.success) {
                window.location.href = "ViewOutTdcfcvView/<?= $id_compra ?>?showdetail=";
            } else {
                // Si no es JSON, te muestro el texto devuelto (PHP error/HTML)
                ew.alert((resp && resp.message) ? resp.message : (txt || "Respuesta vacía del servidor."));
            }
        },
        error: function (xhr, status, err) {
            console.log("AJAX ERROR:", status, err);
            console.log("RESPTEXT:", xhr.responseText);
            ew.alert("Error servidor: " + status + " / " + err + "\n\n" + (xhr.responseText || ""));
        }
    });
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


(function(){
    const tipo = ($("#tipo_pago").val() || "").trim();
    const requiereBanco = !["EF","RI","RC","IG","RS","AN"].includes(tipo);
    const requiereRef = !["EF","RC","IG","AN"].includes(tipo);

    const $b = $("#banco_input");
    $b.prop("disabled", !requiereBanco);

    if ($b.hasClass("select2-hidden-accessible")) {
        $b.trigger("change.select2");
    }

    $("#help_ref_banco").text(
        (["RS","RI"].includes(tipo)) ? "Requiere: referencia." :
        (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
        (requiereRef) ? "Requiere: referencia." : ""
    );
})();

$(document).on("change", "#tipo_pago", function () {
    const tipo = ($(this).val() || "").trim();

    const requiereBanco = !["EF","RI","RC","IG","RS","AN"].includes(tipo);
    const requiereRef   = !["EF","RC","IG","AN"].includes(tipo);

    const $b = $("#banco_input");
    $b.prop("disabled", !requiereBanco);

    if ($b.hasClass("select2-hidden-accessible")) {
        $b.trigger("change.select2");
    }

    $("#help_ref_banco").text(
        (["RS","RI"].includes(tipo)) ? "Requiere: referencia." :
        (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
        (requiereRef) ? "Requiere: referencia." : ""
    );

    aplicarDestinoAuto(true);
});

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
/////

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

$(document).ready(function () {
    refrescar("");
});
</script>


<style>
    .input-group .form-control, .input-group .form-select { border-radius: 4px !important; }
</style>
<?= GetDebugMessage() ?>
