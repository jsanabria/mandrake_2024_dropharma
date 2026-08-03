<?php

namespace PHPMaker2024\mandrake;

// Page object
$RegistrarPagosProveedores = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$id_compra = intval(Param("id_compra") ?? 0);
$accion = trim((string)(Param("accion") ?? ""));
$origen = strtoupper(trim((string)(Param("origen") ?? "TDCFCC")));
if (in_array($origen, ["ENTRADAS", "TDCFCC"], true)) {
    $origen = "TDCFCC";
} elseif (in_array($origen, ["COMPRA", "GASTOS"], true)) {
    $origen = "GASTOS";
} else {
    $origen = "TDCFCC";
}

function pagosProveedorJsonResponse(array $respuesta): void
{
    if (ob_get_length()) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function pagosProveedorDocumento(int $idCompra, string $origen): array
{
    if ($idCompra <= 0) {
        return [];
    }

    if ($origen === "GASTOS") {
        $sql = "
            SELECT
                c.id,
                c.proveedor,
                p.nombre AS nombre_proveedor,
                p.ci_rif,
                c.documento AS nro_documento,
                'GASTOS' AS tipo_documento_pago,
                c.moneda,
                c.tasa_dia,
                COALESCE(NULLIF(c.monto_pagar, 0), c.monto_total, 0) AS total_documento,
                CASE
                    WHEN c.moneda <> 'Bs.' THEN COALESCE(NULLIF(c.monto_pagar, 0), c.monto_total, 0) * c.tasa_dia
                    ELSE COALESCE(NULLIF(c.monto_pagar, 0), c.monto_total, 0)
                END AS total_bs,
                CASE
                    WHEN c.moneda <> 'Bs.' THEN COALESCE(NULLIF(c.monto_pagar, 0), c.monto_total, 0)
                    ELSE COALESCE(NULLIF(c.monto_pagar, 0), c.monto_total, 0) / NULLIF(c.tasa_dia, 0)
                END AS total_usd
            FROM compra AS c
            JOIN proveedor AS p ON p.id = c.proveedor
            WHERE c.id = " . intval($idCompra) . "
              AND c.anulado = 'N'
            LIMIT 1
        ";
    } else {
        $sql = "
            SELECT
                e.id,
                e.proveedor,
                p.nombre AS nombre_proveedor,
                p.ci_rif,
                e.nro_documento,
                'TDCFCC' AS tipo_documento_pago,
                e.moneda,
                e.tasa_dia,
                COALESCE(NULLIF(e.monto_pagar, 0), e.total, 0) AS total_documento,
                CASE
                    WHEN e.moneda <> 'Bs.' THEN COALESCE(NULLIF(e.monto_pagar, 0), e.total, 0) * e.tasa_dia
                    ELSE COALESCE(NULLIF(e.monto_pagar, 0), e.total, 0)
                END AS total_bs,
                CASE
                    WHEN e.moneda <> 'Bs.' THEN COALESCE(NULLIF(e.monto_pagar, 0), e.total, 0)
                    ELSE COALESCE(NULLIF(e.monto_pagar, 0), e.total, 0) / NULLIF(e.tasa_dia, 0)
                END AS total_usd
            FROM entradas AS e
            JOIN proveedor AS p ON p.id = e.proveedor
            WHERE e.id = " . intval($idCompra) . "
            LIMIT 1
        ";
    }

    return ExecuteRow($sql) ?: [];
}

function pagosProveedorPagadoBs(int $idCompra, string $tipoDocumento): float
{
    if ($idCompra <= 0 || $tipoDocumento === "") {
        return 0;
    }

    $sql = "
        SELECT COALESCE(SUM(pago), 0)
        FROM pagos_compras
        WHERE id_documento = " . intval($idCompra) . "
          AND tipo_documento = '" . AdjustSql($tipoDocumento) . "'
    ";

    return round(floatval(ExecuteScalar($sql) ?: 0), 2);
}

// -----------------------------------------------------------------------------
// AJAX: refrescar formulario y resumen
// -----------------------------------------------------------------------------
if ($accion === "refrescar") {
    if (ob_get_length()) {
        ob_end_clean();
    }

    $pagosJson = (string)(Param("pagos") ?? "[]");
    $tipoPagoActual = trim((string)(Param("tipo_pago") ?? ""));
    $listaPagos = json_decode($pagosJson, true);

    if (!is_array($listaPagos)) {
        $listaPagos = [];
    }

    $compra = pagosProveedorDocumento($id_compra, $origen);
    if (!$compra) {
        echo '<div class="alert alert-danger">No fue posible localizar el documento de compra.</div>';
        exit;
    }

    $tipoDocumento = trim((string)($compra["tipo_documento_pago"] ?? $origen));
    $monedaDocumento = trim((string)($compra["moneda"] ?? "Bs."));
    $tasaDia = floatval($compra["tasa_dia"] ?? 1);
    if ($tasaDia <= 0) {
        $tasaDia = 1;
    }

    $totalBs = round(floatval($compra["total_bs"] ?? 0), 2);
    $pagadoAnteriorBs = pagosProveedorPagadoBs($id_compra, $tipoDocumento);

    $pagadoCarritoBs = 0;
    foreach ($listaPagos as $pago) {
        $monto = floatval($pago["monto"] ?? 0);
        $moneda = trim((string)($pago["moneda"] ?? "Bs."));
        $tasaMoneda = floatval($pago["tasa_moneda"] ?? 0);

        if ($monto <= 0) {
            continue;
        }

        if ($moneda === "Bs.") {
            $pagadoCarritoBs += $monto;
        } else {
            if ($tasaMoneda <= 0) {
                $tasaMoneda = $tasaDia;
            }
            $pagadoCarritoBs += $monto * $tasaMoneda;
        }
    }

    $saldoBs = round($totalBs - $pagadoAnteriorBs - $pagadoCarritoBs, 2);
    if (abs($saldoBs) < 0.01) {
        $saldoBs = 0;
    }

    $saldoUsd = ($tasaDia > 0) ? round($saldoBs / $tasaDia, 2) : 0;

    $metodos = ExecuteRows("
        SELECT valor1, valor2
        FROM parametro
        WHERE codigo = '009'
          AND valor1 NOT IN ('PC','PF','DV','NC','ND','IG','AN','RC','RD','RI','RJ','RR','RS')
        ORDER BY valor2
    ");

    $cuentas = ExecuteRows("
        SELECT
            cc.id,
            cc.banco,
            COALESCE(t.campo_descripcion, cc.banco) AS banco_descripcion,
            cc.titular,
            cc.tipo,
            cc.numero
        FROM compania_cuenta AS cc
        LEFT JOIN tabla AS t
               ON t.tabla = 'BANCO'
              AND t.campo_codigo = cc.banco
        WHERE cc.compania = 1
          AND cc.mostrar = 'S'
          AND cc.activo = 'S'
          AND cc.pago_electronico = 'S'
        ORDER BY banco_descripcion, cc.numero
    ");

    $monedas = ExecuteRows("
        SELECT valor1
        FROM parametro
        WHERE codigo = '006'
          AND valor1 IN ('Bs.', 'USD', 'EUR', 'EURO')
        ORDER BY CASE WHEN valor1 = 'Bs.' THEN 0 ELSE 1 END, valor1
    ");

    if (!$monedas) {
        $monedas = [
            ["valor1" => "Bs."],
            ["valor1" => "USD"]
        ];
    }

    $montoSugerido = $saldoBs;
    if ($tipoPagoActual !== "" && $monedaDocumento !== "Bs.") {
        $montoSugerido = $saldoUsd;
    }
    ?>

    <div class="card shadow-sm border-0 mb-3 bg-light">
        <div class="card-body p-3 text-center">
            <div class="row g-0">
                <div class="col-md-4 border-end">
                    <small class="text-muted d-block">PROVEEDOR</small>
                    <b><?= HtmlEncode($compra["nombre_proveedor"] ?? "") ?></b>
                    <div class="small text-muted mt-1">
                        <?= HtmlEncode($compra["ci_rif"] ?? "") ?>
                    </div>
                </div>

                <div class="col-md-4 border-end">
                    <small class="text-muted d-block">DOCUMENTO</small>
                    <b><?= HtmlEncode($compra["nro_documento"] ?? "") ?></b>
                    <div class="small text-muted mt-1">
                        <?= HtmlEncode($tipoDocumento) ?> · <?= HtmlEncode($monedaDocumento) ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">SALDO PENDIENTE</small>
                    <div class="<?= ($saldoBs <= 0.01) ? 'text-success' : 'text-danger' ?>">
                        <b>Bs. <?= number_format($saldoBs, 2, ".", ",") ?></b>
                    </div>
                    <div class="small text-muted">
                        USD <?= number_format($saldoUsd, 2, ".", ",") ?>
                    </div>
                </div>
            </div>

            <input type="hidden" id="pendiente_bs" value="<?= number_format($saldoBs, 2, '.', '') ?>">
            <input type="hidden" id="pendiente_usd" value="<?= number_format($saldoUsd, 2, '.', '') ?>">
            <input type="hidden" id="tasa_dia_doc" value="<?= number_format($tasaDia, 6, '.', '') ?>">
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 border-top border-primary border-3">
        <div class="card-body">
            <!-- FILA 1: datos del método -->
            <div class="row g-3 align-items-end pago-proveedor-fila">
                <div class="col-xl-4 col-lg-5 col-md-12">
                    <label class="small fw-bold">MÉTODO DE PAGO</label>
                    <select id="tipo_pago" class="form-select form-select-sm w-100" onchange="cambiarMetodoProveedor(this.value)">
                        <option value="">Seleccione...</option>
                        <?php foreach ($metodos as $metodo): ?>
                            <option value="<?= HtmlEncode($metodo['valor1']) ?>"
                                <?= ($tipoPagoActual === trim((string)$metodo['valor1'])) ? 'selected' : '' ?>>
                                <?= HtmlEncode($metodo['valor2']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <label class="small fw-bold">MONTO</label>
                    <input type="number" id="monto_input"
                           class="form-control form-control-sm fw-bold border-primary w-100"
                           min="0.01" step="0.01"
                           value="<?= ($saldoBs > 0) ? number_format($montoSugerido, 2, '.', '') : '' ?>">
                </div>

                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                    <label class="small fw-bold">MONEDA</label>
                    <select id="moneda_input" class="form-select form-select-sm w-100">
                        <?php foreach ($monedas as $moneda):
                            $codigoMoneda = trim((string)$moneda['valor1']);
                            $seleccionada = ($codigoMoneda === "Bs.") ? "selected" : "";
                        ?>
                            <option value="<?= HtmlEncode($codigoMoneda) ?>" <?= $seleccionada ?>>
                                <?= HtmlEncode($codigoMoneda) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-3 col-lg-2 col-md-4 col-sm-12">
                    <label class="small fw-bold">TASA</label>
                    <input type="number" id="tasa_moneda_input"
                           class="form-control form-control-sm w-100"
                           min="0.000001" step="0.000001" value="1.000000">
                </div>
            </div>

            <!-- FILA 2: cuenta, referencia y acción -->
            <div class="row g-3 align-items-end mt-1 pago-proveedor-fila">
                <div class="col-xl-5 col-lg-5 col-md-12">
                    <label class="small fw-bold">CUENTA DE LA COMPAÑÍA</label>
                    <select id="banco_input" class="form-select form-select-sm w-100">
                        <option value="">Seleccione...</option>
                        <?php foreach ($cuentas as $cuenta): ?>
                            <option value="<?= intval($cuenta['id']) ?>">
                                <?= HtmlEncode(trim(
                                    ($cuenta['banco_descripcion'] ?? '') . ' · ' .
                                    ($cuenta['tipo'] ?? '') . ' · ' .
                                    ($cuenta['numero'] ?? '')
                                )) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-8">
                    <label class="small fw-bold">REFERENCIA</label>
                    <input type="text" id="ref_input"
                           class="form-control form-control-sm w-100"
                           maxlength="50"
                           placeholder="Número de transferencia, cheque o referencia...">
                </div>

                <div class="col-xl-2 col-lg-2 col-md-4">
                    <button type="button"
                            class="btn btn-primary btn-sm w-100 fw-bold"
                            onclick="return agregarPagoProveedor(event)">
                        AGREGAR
                    </button>
                </div>
            </div>

            <small class="text-muted d-block mt-2" id="help_pago_proveedor"></small>
        </div>
    </div>

    <div class="list-group shadow-sm">
        <div class="list-group-item bg-dark text-white py-1 small fw-bold">
            PAGOS AGREGADOS (<?= count($listaPagos) ?>)
        </div>

        <?php foreach ($listaPagos as $indice => $pago): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                <div>
                    <span class="fw-bold d-block small">
                        <?= HtmlEncode($pago["metodo_nom"] ?? "") ?>
                    </span>
                    <small class="text-muted">
                        Ref: <?= HtmlEncode($pago["ref"] ?? "") ?>
                        <?php if (!empty($pago["banco_nom"])): ?>
                            · Cuenta: <?= HtmlEncode($pago["banco_nom"]) ?>
                        <?php endif; ?>
                    </small>
                </div>

                <div class="d-flex align-items-center">
                    <span class="fw-bold text-primary me-3">
                        <?= HtmlEncode($pago["moneda"] ?? "") ?>
                        <?= number_format(floatval($pago["monto"] ?? 0), 2, ".", ",") ?>
                    </span>
                    <button type="button"
                            class="btn btn-sm text-danger"
                            onclick="eliminarPagoProveedor(<?= intval($indice) ?>)">
                        X
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    exit;
}

// -----------------------------------------------------------------------------
// AJAX: finalizar y registrar pago
// -----------------------------------------------------------------------------
if ($accion === "finalizar") {
    $pagosJson = (string)(Param("pagos") ?? "[]");
    $nota = trim((string)(Param("nota") ?? ""));
    $lista = json_decode($pagosJson, true);

    if (!is_array($lista) || count($lista) === 0) {
        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "No hay pagos para registrar."
        ]);
    }

    $compra = pagosProveedorDocumento($id_compra, $origen);
    if (!$compra) {
        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "No fue posible localizar el documento de compra."
        ]);
    }

    $proveedorId = intval($compra["proveedor"] ?? 0);
    $tipoDocumento = trim((string)($compra["tipo_documento_pago"] ?? $origen));
    $tasaDia = floatval($compra["tasa_dia"] ?? 1);
    if ($tasaDia <= 0) {
        $tasaDia = 1;
    }

    $totalBs = round(floatval($compra["total_bs"] ?? 0), 2);
    $pagadoAnteriorBs = pagosProveedorPagadoBs($id_compra, $tipoDocumento);
    $saldoAntesBs = round($totalBs - $pagadoAnteriorBs, 2);

    if ($saldoAntesBs <= 0.01) {
        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "Este documento ya se encuentra totalmente pagado."
        ]);
    }

    $pagadoActualBs = 0;
    $detalleNormalizado = [];

    foreach ($lista as $indice => $pago) {
        $metodo = trim((string)($pago["tipo"] ?? ""));
        $referencia = trim((string)($pago["ref"] ?? ""));
        $moneda = trim((string)($pago["moneda"] ?? "Bs."));
        $montoMoneda = round(floatval($pago["monto"] ?? 0), 2);
        $banco = intval($pago["banco_id"] ?? 0);
        $tasaMoneda = floatval($pago["tasa_moneda"] ?? 0);

        if ($metodo === "") {
            pagosProveedorJsonResponse([
                "success" => false,
                "message" => "El pago #" . ($indice + 1) . " no tiene método de pago."
            ]);
        }

        if ($montoMoneda <= 0) {
            pagosProveedorJsonResponse([
                "success" => false,
                "message" => "El pago #" . ($indice + 1) . " tiene un monto inválido."
            ]);
        }

        $esEfectivo = ($metodo === "EF");
        if (!$esEfectivo && $referencia === "") {
            pagosProveedorJsonResponse([
                "success" => false,
                "message" => "Debe indicar la referencia del pago #" . ($indice + 1) . "."
            ]);
        }

        if (!$esEfectivo && $banco <= 0) {
            pagosProveedorJsonResponse([
                "success" => false,
                "message" => "Debe seleccionar la cuenta bancaria de la compañía para el pago #" . ($indice + 1) . "."
            ]);
        }

        if ($banco > 0) {
            $cuentaValida = intval(ExecuteScalar("
                SELECT COUNT(*)
                FROM compania_cuenta
                WHERE id = " . intval($banco) . "
                  AND compania = 1
                  AND activo = 'S'
                  AND mostrar = 'S'
                LIMIT 1
            ") ?: 0);

            if ($cuentaValida <= 0) {
                pagosProveedorJsonResponse([
                    "success" => false,
                    "message" => "La cuenta seleccionada en el pago #" . ($indice + 1) . " no es válida para la compañía 1."
                ]);
            }
        }

        if ($moneda === "Bs.") {
            $tasaMoneda = 1;
            $montoBs = $montoMoneda;
        } else {
            if ($tasaMoneda <= 0) {
                $tasaMoneda = $tasaDia;
            }
            $montoBs = round($montoMoneda * $tasaMoneda, 2);
        }

        $montoUsd = ($tasaDia > 0) ? round($montoBs / $tasaDia, 2) : 0;
        $pagadoActualBs += $montoBs;

        $detalleNormalizado[] = [
            "metodo" => $metodo,
            "referencia" => $referencia,
            "monto_moneda" => $montoMoneda,
            "moneda" => $moneda,
            "tasa_moneda" => $tasaMoneda,
            "monto_bs" => $montoBs,
            "tasa_usd" => $tasaDia,
            "monto_usd" => $montoUsd,
            "banco" => $banco
        ];
    }

    $pagadoActualBs = round($pagadoActualBs, 2);
    $diferencia = round($saldoAntesBs - $pagadoActualBs, 2);

    // Se permiten abonos parciales. Solo se rechaza un pago que exceda el saldo.
    if ($pagadoActualBs <= 0) {
        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "El monto total del pago debe ser mayor que cero."
        ]);
    }

    if ($diferencia < -0.01) {
        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "El pago excede el saldo del documento en Bs. " .
                         number_format(abs($diferencia), 2, ".", ",") . ". Ajuste los montos antes de registrar."
        ]);
    }

    $username = CurrentUserName();
    $fecha = CurrentDate();
    $conn = Conn();

    try {
        $conn->beginTransaction();

        $metodoCabecera = (count($detalleNormalizado) === 1)
            ? $detalleNormalizado[0]["metodo"]
            : "MP";

        $sqlCabecera = "
            INSERT INTO pagos_compras
            (
                proveedor,
                id_documento,
                tipo_documento,
                fecha,
                pago,
                moneda,
                nota,
                fecha_registro,
                username,
                comprobante,
                tipo_pago,
                pivote,
                pivote2,
                anexos,
                tasa_cambio
            )
            VALUES
            (
                " . intval($proveedorId) . ",
                " . intval($id_compra) . ",
                '" . AdjustSql($tipoDocumento) . "',
                '" . AdjustSql($fecha) . "',
                " . floatval($pagadoActualBs) . ",
                'Bs.',
                " . ($nota !== "" ? "'" . AdjustSql($nota) . "'" : "NULL") . ",
                '" . AdjustSql($fecha) . "',
                '" . AdjustSql($username) . "',
                'N',
                '" . AdjustSql($metodoCabecera) . "',
                'N',
                'N',
                NULL,
                " . floatval($tasaDia) . "
            )
        ";
        Execute($sqlCabecera);

        $pagoCompraId = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));
        if ($pagoCompraId <= 0) {
            throw new \RuntimeException("No fue posible obtener el identificador de pagos_compras.");
        }

        foreach ($detalleNormalizado as $detalle) {
            $sqlDetalle = "
                INSERT INTO pagos_compras_detalle
                (
                    pagos_compras,
                    metodo_pago,
                    referencia,
                    monto_moneda,
                    moneda,
                    tasa_moneda,
                    monto_bs,
                    tasa_usd,
                    monto_usd,
                    banco
                )
                VALUES
                (
                    " . intval($pagoCompraId) . ",
                    '" . AdjustSql($detalle["metodo"]) . "',
                    " . ($detalle["referencia"] !== ""
                        ? "'" . AdjustSql($detalle["referencia"]) . "'"
                        : "NULL") . ",
                    " . floatval($detalle["monto_moneda"]) . ",
                    '" . AdjustSql($detalle["moneda"]) . "',
                    " . floatval($detalle["tasa_moneda"]) . ",
                    " . floatval($detalle["monto_bs"]) . ",
                    " . floatval($detalle["tasa_usd"]) . ",
                    " . floatval($detalle["monto_usd"]) . ",
                    " . ($detalle["banco"] > 0 ? intval($detalle["banco"]) : "NULL") . "
                )
            ";
            Execute($sqlDetalle);
        }

        $saldoDespuesBs = round($saldoAntesBs - $pagadoActualBs, 2);
        if ($saldoDespuesBs < 0.01) {
            $saldoDespuesBs = 0;
        }

        // La tabla compra sí posee el indicador pagado. Entradas no lo posee.
        if ($origen === "GASTOS") {
            $estadoPagado = ($saldoDespuesBs <= 0.01) ? "S" : "N";
            Execute("
                UPDATE compra
                SET pagado = '" . AdjustSql($estadoPagado) . "'
                WHERE id = " . intval($id_compra) . "
            ");
        }

        $conn->commit();

        pagosProveedorJsonResponse([
            "success" => true,
            "message" => ($saldoDespuesBs > 0.01)
                ? "Pago parcial registrado correctamente. Saldo pendiente: Bs. " . number_format($saldoDespuesBs, 2, ".", ",") . "."
                : "Pago registrado correctamente. El documento quedó totalmente pagado.",
            "pago_compra_id" => $pagoCompraId,
            "saldo_pendiente_bs" => $saldoDespuesBs,
            "pago_parcial" => ($saldoDespuesBs > 0.01)
        ]);
    } catch (\Throwable $ex) {
        try {
            $conn->rollBack();
        } catch (\Throwable $ignorado) {
        }

        pagosProveedorJsonResponse([
            "success" => false,
            "message" => "Error guardando el pago al proveedor: " . $ex->getMessage()
        ]);
    }
}

?>

<div class="container-fluid py-3 pago-proveedor-contenedor" style="max-width: 1180px;">
    <div id="div-ajax">
        <div class="text-center p-5">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>

    <input type="hidden" id="json_pagos" value="[]">

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <label for="nota_pago" class="form-label small fw-bold">NOTA DEL PAGO</label>
            <textarea id="nota_pago"
                      class="form-control"
                      rows="2"
                      maxlength="255"
                      placeholder="Observación opcional..."></textarea>
        </div>
    </div>

    <div class="mt-4 text-center">
        <button type="button"
                id="btn-registrar-final"
                class="btn btn-success btn-lg px-5 shadow fw-bold"
                disabled
                onclick="return finalizarPagoProveedor()">
            REGISTRAR PAGO
        </button>

        <button type="button"
                id="btn-cancelar"
                class="btn btn-outline-secondary btn-lg px-5 shadow-sm fw-bold"
                onclick="return cancelarPagoProveedor(event)">
            CANCELAR
        </button>
    </div>
</div>

<script>
(function iniciarModuloPagosProveedor() {
    if (!window.jQuery) {
        window.setTimeout(iniciarModuloPagosProveedor, 50);
        return;
    }

    const $ = window.jQuery;


let pagoProveedorAjaxEnCurso = false;

function datosCsrfProveedor(data) {
    if (!window.ew) return data;

    if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) {
        data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
    }

    if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) {
        data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    return data;
}

function refrescarPagoProveedor(metodo = "") {
    const data = datosCsrfProveedor({
        accion: "refrescar",
        id_compra: "<?= $id_compra ?>",
        origen: "<?= HtmlEncode($origen) ?>",
        pagos: $("#json_pagos").val() || "[]",
        tipo_pago: metodo
    });

    $.ajax({
        url: window.location.href,
        type: "POST",
        data: data,
        success: function (html) {
            $("#div-ajax").html(html);
            configurarMetodoProveedor();
            recalcularMontoProveedor();
            actualizarBotonRegistrarProveedor();
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            $("#div-ajax").html(
                "<div class='alert alert-danger'>No fue posible actualizar el formulario de pago.</div>"
            );
        }
    });
}

function cambiarMetodoProveedor(metodo) {
    refrescarPagoProveedor(metodo || "");
}

function actualizarBotonRegistrarProveedor() {
    let lista = [];

    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (error) {
        lista = [];
    }

    let totalPagosBs = 0;
    lista.forEach(function (pago) {
        const monto = parseFloat(pago.monto || 0);
        const tasa = parseFloat(pago.tasa_moneda || 1);

        if (Number.isFinite(monto) && Number.isFinite(tasa)) {
            totalPagosBs += monto * tasa;
        }
    });

    const saldoDocumentoBs = parseFloat($("#saldo_documento_bs").val() || $("#pendiente_bs").val() || "0");
    const quedaSaldo = saldoDocumentoBs - totalPagosBs > 0.01;

    $("#btn-registrar-final")
        .prop("disabled", lista.length === 0)
        .text(lista.length > 0 && quedaSaldo
            ? "REGISTRAR PAGO PARCIAL"
            : "REGISTRAR PAGO");
}

function configurarMetodoProveedor() {
    const metodo = ($("#tipo_pago").val() || "").trim();
    const esEfectivo = metodo === "EF";

    $("#banco_input").prop("disabled", esEfectivo);
    $("#ref_input").prop("disabled", esEfectivo);

    $("#help_pago_proveedor").text(
        esEfectivo
            ? "El efectivo no requiere cuenta bancaria ni referencia."
            : "Seleccione la cuenta de la compañía desde donde saldrá el dinero e indique la referencia."
    );
}

function recalcularMontoProveedor() {
    const moneda = ($("#moneda_input").val() || "Bs.").trim();
    const tasaDocumento = parseFloat($("#tasa_dia_doc").val() || "1");
    const saldoBs = parseFloat($("#pendiente_bs").val() || "0");

    if (moneda === "Bs.") {
        $("#tasa_moneda_input").val("1.000000").prop("readonly", true);
        if (saldoBs > 0) {
            $("#monto_input").val(saldoBs.toFixed(2));
        }
    } else {
        $("#tasa_moneda_input")
            .val((tasaDocumento > 0 ? tasaDocumento : 1).toFixed(6))
            .prop("readonly", false);

        const tasa = parseFloat($("#tasa_moneda_input").val() || "1");
        if (saldoBs > 0 && tasa > 0) {
            $("#monto_input").val((saldoBs / tasa).toFixed(2));
        }
    }
}

function agregarPagoProveedor(evento) {
    if (evento) {
        evento.preventDefault();
        evento.stopPropagation();
    }

    if (pagoProveedorAjaxEnCurso) return false;

    const metodo = ($("#tipo_pago").val() || "").trim();
    const metodoNombre = ($("#tipo_pago option:selected").text() || "").trim();
    const monto = parseFloat($("#monto_input").val() || "0");
    const moneda = ($("#moneda_input").val() || "Bs.").trim();
    const tasaMoneda = parseFloat($("#tasa_moneda_input").val() || "0");
    const bancoId = parseInt($("#banco_input").val() || "0", 10);
    const bancoNombre = ($("#banco_input option:selected").text() || "").trim();
    const referencia = ($("#ref_input").val() || "").trim();
    const esEfectivo = metodo === "EF";

    if (!metodo) {
        ew.alert("Seleccione un método de pago.");
        return false;
    }

    if (!Number.isFinite(monto) || monto <= 0) {
        ew.alert("Indique un monto válido.");
        return false;
    }

    if (!Number.isFinite(tasaMoneda) || tasaMoneda <= 0) {
        ew.alert("Indique una tasa válida.");
        return false;
    }

    if (!esEfectivo && bancoId <= 0) {
        ew.alert("Seleccione la cuenta bancaria de la compañía.");
        return false;
    }

    if (!esEfectivo && !referencia) {
        ew.alert("Indique la referencia del pago.");
        return false;
    }

    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (error) {
        lista = [];
    }

    lista.push({
        tipo: metodo,
        metodo_nom: metodoNombre,
        monto: monto,
        moneda: moneda,
        tasa_moneda: tasaMoneda,
        banco_id: esEfectivo ? 0 : bancoId,
        banco_nom: esEfectivo ? "" : bancoNombre,
        ref: esEfectivo ? "N/A" : referencia
    });

    $("#json_pagos").val(JSON.stringify(lista));
    refrescarPagoProveedor(metodo);
    return false;
}

function eliminarPagoProveedor(indice) {
    if (pagoProveedorAjaxEnCurso) return false;

    let lista = [];
    try {
        lista = JSON.parse($("#json_pagos").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch (error) {
        lista = [];
    }

    if (!lista[indice]) return false;

    lista.splice(indice, 1);
    $("#json_pagos").val(JSON.stringify(lista));
    refrescarPagoProveedor($("#tipo_pago").val() || "");
    return false;
}

function finalizarPagoProveedor() {
    if (pagoProveedorAjaxEnCurso) return false;

    const data = datosCsrfProveedor({
        accion: "finalizar",
        id_compra: "<?= $id_compra ?>",
        origen: "<?= HtmlEncode($origen) ?>",
        pagos: $("#json_pagos").val() || "[]",
        nota: ($("#nota_pago").val() || "").trim()
    });

    pagoProveedorAjaxEnCurso = true;
    $("#btn-registrar-final").prop("disabled", true);

    $.ajax({
        url: window.location.href,
        type: "POST",
        dataType: "text",
        data: data,
        success: function (texto) {
            pagoProveedorAjaxEnCurso = false;

            let respuesta = null;
            try {
                respuesta = JSON.parse(texto);
            } catch (error) {
            }

            if (respuesta && respuesta.success) {
                ew.alert(respuesta.message || "Pago registrado correctamente.", function () {
                    window.history.back();
                });
                return;
            }

            $("#btn-registrar-final").prop("disabled", false);
            ew.alert(
                respuesta && respuesta.message
                    ? respuesta.message
                    : (texto || "Respuesta vacía del servidor.")
            );
        },
        error: function (xhr, estado, error) {
            pagoProveedorAjaxEnCurso = false;
            $("#btn-registrar-final").prop("disabled", false);

            ew.alert(
                "Error del servidor: " + estado + " / " + error +
                "\n\n" + (xhr.responseText || "")
            );
        }
    });

    return false;
}

function cancelarPagoProveedor(evento) {
    if (evento) {
        evento.preventDefault();
        evento.stopPropagation();
    }

    const raw = ($("#json_pagos").val() || "[]").trim();
    let tienePagos = raw !== "" && raw !== "[]";

    if (!tienePagos || confirm("Se perderán los pagos agregados. ¿Desea continuar?")) {
        window.history.back();
    }

    return false;
}

    window.agregarPagoProveedor = agregarPagoProveedor;
    window.eliminarPagoProveedor = eliminarPagoProveedor;
    window.finalizarPagoProveedor = finalizarPagoProveedor;
    window.cancelarPagoProveedor = cancelarPagoProveedor;
    window.cambiarMetodoProveedor = cambiarMetodoProveedor;

    configurarMetodoProveedor();
    actualizarBotonRegistrarProveedor();

    $(document)
        .off("change.pagoProveedorMoneda", "#moneda_input")
        .on("change.pagoProveedorMoneda", "#moneda_input", function () {
            recalcularMontoProveedor();
        });

    $(document)
        .off("change.pagoProveedorTasa", "#tasa_moneda_input")
        .on("change.pagoProveedorTasa", "#tasa_moneda_input", function () {
            const moneda = ($("#moneda_input").val() || "Bs.").trim();
            if (moneda !== "Bs.") {
                const saldoBs = parseFloat($("#pendiente_bs").val() || "0");
                const tasa = parseFloat($(this).val() || "0");
                if (saldoBs > 0 && tasa > 0) {
                    $("#monto_input").val((saldoBs / tasa).toFixed(2));
                }
            }
        });

    refrescarPagoProveedor("");
})();
</script>

<style>
.pago-proveedor-contenedor {
    width: 100%;
}

.pago-proveedor-fila > [class*="col-"] {
    min-width: 0;
}

.pago-proveedor-fila .form-control,
.pago-proveedor-fila .form-select,
.pago-proveedor-fila .btn {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    box-sizing: border-box;
}

#banco_input,
#ref_input {
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 991.98px) {
    .pago-proveedor-fila {
        row-gap: .75rem;
    }
}
</style>
<?= GetDebugMessage() ?>
