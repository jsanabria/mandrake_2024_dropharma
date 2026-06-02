<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aseguramos la conexión de forma autónoma
if (!isset($link)) {
    require_once __DIR__ . "/connect.php";
}

// Parámetros mínimos que sí deben venir por AJAX
$id_documento = isset($_REQUEST["id_documento"]) ? intval($_REQUEST["id_documento"]) : (isset($id_documento) ? intval($id_documento) : 0);
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? trim($_REQUEST["tipo_documento"]) : (isset($tipo_documento) ? trim($tipo_documento) : "TDCNET");

// Sincronizamos la variable $id para el resto del script heredado
$id = $id_documento;

// Protección básica para usar en SQL
$tipo_documento_sql = mysqli_real_escape_string($link, $tipo_documento);

// Moneda por defecto del sistema
$sqlMoneda = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rsMoneda = mysqli_query($link, $sqlMoneda);
$rowMoneda = $rsMoneda ? mysqli_fetch_array($rsMoneda) : null;
$moneda_default = trim($rowMoneda["moneda"] ?? "Bs.");

// Valores por defecto
$tasa_dia = 0;
$descuento = 0;
$descuento2 = 0;
$monto_sin_descuento_bd = 0;
$monto_total_bd = 0;
$alicuota_iva_bd = 0;
$iva_bd = 0;
$total_bd = 0;
$monto_usd_bd = 0;

// Consultamos SIEMPRE los valores financieros desde salidas
if ($id > 0) {
    $sql = "SELECT 
                a.monto_total,
                a.alicuota_iva,
                a.iva,
                a.total,
                IFNULL(a.tasa_dia, 0) AS tasa_dia,
                IFNULL(a.monto_usd, 0) AS monto_usd,
                IFNULL(a.descuento, 0) AS descuento,
                IFNULL(a.descuento2, 0) AS descuento2,
                IFNULL(a.monto_sin_descuento, 0) AS monto_sin_descuento
            FROM salidas AS a 
            WHERE a.id = $id;";

    $rs = mysqli_query($link, $sql);
    $row_totales = $rs ? mysqli_fetch_array($rs) : null;

    if ($row_totales) {
        $tasa_dia = floatval($row_totales["tasa_dia"] ?? 0);
        $descuento = floatval($row_totales["descuento"] ?? 0);
        $descuento2 = floatval($row_totales["descuento2"] ?? 0);
        $monto_sin_descuento_bd = floatval($row_totales["monto_sin_descuento"] ?? 0);
        $monto_total_bd = floatval($row_totales["monto_total"] ?? 0);
        $alicuota_iva_bd = floatval($row_totales["alicuota_iva"] ?? 0);
        $iva_bd = floatval($row_totales["iva"] ?? 0);
        $total_bd = floatval($row_totales["total"] ?? 0);
        $monto_usd_bd = floatval($row_totales["monto_usd"] ?? 0);
    }
}

// Validación defensiva para mostrar valores sanos
if ($descuento < 0) $descuento = 0;
if ($descuento >= 100) $descuento = 99;

if ($descuento2 < 0) $descuento2 = 0;
if ($descuento2 >= 100) $descuento2 = 99;

// Calculamos la vista con los descuentos almacenados en salidas
$exento_bruto = 0;
$gravado_bruto = 0;
$alicuota_iva = $alicuota_iva_bd;

if ($id > 0) {
    $sql = "SELECT
                IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio, 0)), 0) AS exento_bruto,
                IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio)), 0) AS gravado_bruto,
                IFNULL(MAX(IFNULL(alicuota,0)), 0) AS alicuota_iva
            FROM entradas_salidas
            WHERE tipo_documento = '$tipo_documento_sql'
              AND id_documento = '$id'";

    $rs = mysqli_query($link, $sql);
    $row_calc = $rs ? mysqli_fetch_array($rs) : null;

    $exento_bruto = floatval($row_calc["exento_bruto"] ?? 0);
    $gravado_bruto = floatval($row_calc["gravado_bruto"] ?? 0);
    $alicuota_iva = floatval($row_calc["alicuota_iva"] ?? $alicuota_iva_bd);
}

// Subtotal sin descuentos: exento + gravado bruto
$subtotal_sin_descuento = $exento_bruto + $gravado_bruto;

// Descuento sobre descuento
$monto_descuento1 = $subtotal_sin_descuento * ($descuento / 100);
$base_descuento2 = $subtotal_sin_descuento - $monto_descuento1;

$monto_descuento2 = $base_descuento2 * ($descuento2 / 100);
$subtotal_neto = $base_descuento2 - $monto_descuento2;

// Prorrateamos exento y gravado según el neto luego de descuentos
$factor_neto = ($subtotal_sin_descuento > 0) ? ($subtotal_neto / $subtotal_sin_descuento) : 0;

$exento = $exento_bruto * $factor_neto;
$gravado = $gravado_bruto * $factor_neto;
$iva = $gravado * ($alicuota_iva / 100);
$total_nota = $subtotal_neto + $iva;

// Si el documento no tiene detalle todavía, respetamos cero
if ($subtotal_sin_descuento <= 0) {
    $subtotal_neto = 0;
    $exento = 0;
    $gravado = 0;
    $iva = 0;
    $total_nota = 0;
}

// Etiquetas y referencia según moneda base
$tasa_actual = $tasa_dia;

if ($moneda_default == "Bs.") {
    $label_total = "Total Bs.:";
    $label_ref = "Ref. USD:";
    $monto_total_mostrar = $total_nota;
    $monto_ref = ($tasa_actual > 0) ? ($total_nota / $tasa_actual) : 0;
    $simbolo_ref = "$ ";
} else {
    $label_total = "Total " . $moneda_default . ":";
    $label_ref = "Ref. Bs.:";
    $monto_total_mostrar = $total_nota;
    $monto_ref = $total_nota * $tasa_actual;
    $simbolo_ref = "Bs. ";
}
?>
<div class="card shadow-sm sticky-top" style="top: 1rem;">
    <div class="card-header bg-dark text-white py-2">
        <span class="small fw-bold">Parámetros Financieros & Totales</span>
    </div>

    <div class="card-body p-3">
        <div class="row g-2 mb-3 pb-3 border-bottom">

            <div class="col-12">
                <label class="form-label small mb-1 fw-bold">Tasa del Día</label>
                <input
                    type="number"
                    step="0.0001"
                    id="tasa"
                    name="tasa"
                    class="form-control form-control-sm text-end bg-light"
                    value="<?= htmlspecialchars(number_format($tasa_dia, 4, '.', '')); ?>"
                    readonly>
            </div>

            <div class="col-6">
                <label class="form-label small mb-1 fw-bold">Desc 1 (%)</label>
                <input
                    type="number"
                    step="1"
                    min="0"
                    max="99"
                    id="descuento"
                    name="descuento"
                    class="form-control form-control-sm text-end"
                    value="<?= htmlspecialchars($descuento); ?>">
            </div>

            <div class="col-6">
                <label class="form-label small mb-1 fw-bold">Desc 2 (%)</label>
                <input
                    type="number"
                    step="1"
                    min="0"
                    max="99"
                    id="descuento2"
                    name="descuento2"
                    class="form-control form-control-sm text-end"
                    value="<?= htmlspecialchars($descuento2); ?>">
            </div>

        </div>

        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Subtotal sin Descuento:</span>
            <span class="fw-bold"><?= number_format($subtotal_sin_descuento, 2, ",", "."); ?></span>
        </div>

        <?php if ($descuento > 0): ?>
        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Desc 1 (<?= number_format($descuento, 0); ?>%):</span>
            <span class="text-danger fw-bold">-<?= number_format($monto_descuento1, 2, ",", "."); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($descuento2 > 0): ?>
        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Desc 2 (<?= number_format($descuento2, 0); ?>%):</span>
            <span class="text-danger fw-bold">-<?= number_format($monto_descuento2, 2, ",", "."); ?></span>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Monto Exento:</span>
            <span class="text-success fw-bold"><?= number_format($exento, 2, ",", "."); ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Monto Gravado:</span>
            <span><?= number_format($gravado, 2, ",", "."); ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Subtotal Neto:</span>
            <span><?= number_format($subtotal_neto, 2, ",", "."); ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">IVA (<?= number_format($alicuota_iva, 0); ?>%):</span>
            <span><?= number_format($iva, 2, ",", "."); ?></span>
        </div>

        <hr class="my-2">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="h6 mb-0 fw-bold text-dark"><?= htmlspecialchars($label_total); ?></span>
            <span class="h5 mb-0 fw-bold text-dark">
                <?= number_format($monto_total_mostrar, 2, ",", "."); ?>
            </span>
        </div>

        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded border border-dashed">
            <span class="small text-secondary fw-bold"><?= htmlspecialchars($label_ref); ?></span>
            <span class="fw-bold text-primary">
                <?= htmlspecialchars($simbolo_ref) . number_format($monto_ref, 2, ",", "."); ?>
            </span>
        </div>
    </div>
</div>
