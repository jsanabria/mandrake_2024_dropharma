<?php
/**
 * findme_actualizar_descuentos.php
 * Actualiza Desc 1 y Desc 2 en salidas, recalcula los totales del documento
 * aplicando descuento sobre descuento, y devuelve respuesta JSON.
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";

$response = [
    "success" => false,
    "error" => ""
];

try {
    $id = intval($_POST["id_documento"] ?? 0);
    $tipo_documento = trim($_POST["tipo_documento"] ?? "TDCNET");
    $descuento = floatval($_POST["descuento"] ?? 0);
    $descuento2 = floatval($_POST["descuento2"] ?? 0);

    if ($id <= 0) {
        throw new Exception("Documento inválido.");
    }

    if ($descuento < 0 || $descuento >= 100) {
        throw new Exception("Desc 1 debe estar entre 0 y 99.");
    }

    if ($descuento2 < 0 || $descuento2 >= 100) {
        throw new Exception("Desc 2 debe estar entre 0 y 99.");
    }

    // Tomamos la tasa guardada en la cabecera del documento
    $stmtDoc = $link->prepare("SELECT IFNULL(tasa_dia, 0) AS tasa_dia FROM salidas WHERE id = ?;");
    if (!$stmtDoc) {
        throw new Exception("Error preparando consulta de documento: " . $link->error);
    }
    $stmtDoc->bind_param("i", $id);
    $stmtDoc->execute();
    $rowDoc = $stmtDoc->get_result()->fetch_assoc();
    $stmtDoc->close();

    if (!$rowDoc) {
        throw new Exception("El documento no existe en salidas.");
    }

    $tasa_dia = floatval($rowDoc["tasa_dia"] ?? 0);

    /**
     * Base bruta sin descuentos:
     * salidas.monto_sin_descuento debe quedar como Exento bruto + Gravado bruto,
     * es decir, subtotal sin aplicar Desc 1 ni Desc 2.
     */
    $stmtCalc = $link->prepare("SELECT
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio, 0)), 0) AS exento_bruto,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio)), 0) AS gravado_bruto,
            IFNULL(MAX(IFNULL(alicuota,0)), 0) AS alicuota_iva
        FROM entradas_salidas
        WHERE tipo_documento = ?
          AND id_documento = ?;");

    if (!$stmtCalc) {
        throw new Exception("Error preparando cálculo de descuentos: " . $link->error);
    }

    $stmtCalc->bind_param("si", $tipo_documento, $id);
    $stmtCalc->execute();
    $row = $stmtCalc->get_result()->fetch_assoc();
    $stmtCalc->close();

    $exento_bruto = floatval($row["exento_bruto"] ?? 0);
    $gravado_bruto = floatval($row["gravado_bruto"] ?? 0);
    $alicuota_iva = floatval($row["alicuota_iva"] ?? 0);

    $monto_sin_descuento = $exento_bruto + $gravado_bruto;

    // Desc 1 sobre el subtotal bruto
    $monto_descuento1 = $monto_sin_descuento * ($descuento / 100);
    $base_descuento2 = $monto_sin_descuento - $monto_descuento1;

    // Desc 2 sobre la base ya rebajada por Desc 1
    $monto_descuento2 = $base_descuento2 * ($descuento2 / 100);
    $monto_total = $base_descuento2 - $monto_descuento2;

    // Distribuir proporcionalmente el neto entre exento y gravado para calcular IVA correctamente
    $factor_neto = ($monto_sin_descuento > 0) ? ($monto_total / $monto_sin_descuento) : 0;
    $exento = $exento_bruto * $factor_neto;
    $gravado = $gravado_bruto * $factor_neto;

    $iva = $gravado * ($alicuota_iva / 100);
    $total = $monto_total + $iva;

    // Moneda por defecto del sistema
    $moneda = "Bs.";
    $rsMoneda = mysqli_query($link, "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';");
    if ($rsMoneda && $rowMoneda = mysqli_fetch_array($rsMoneda)) {
        $moneda = trim($rowMoneda["moneda"] ?? "Bs.");
    }

    /**
     * Si moneda base es Bs., monto_usd es referencia USD.
     * Si moneda base es distinta de Bs., monto_usd queda como total en divisa.
     * La referencia Bs. se calcula visualmente en findme_financiero.php multiplicando total * tasa_dia.
     */
    if ($moneda == "Bs.") {
        $monto_usd = ($tasa_dia > 0) ? ($total / $tasa_dia) : 0;
    } else {
        $monto_usd = $total;
    }

    $stmtUpd = $link->prepare("UPDATE salidas
        SET 
            descuento = ?,
            descuento2 = ?,
            monto_sin_descuento = ?,
            monto_total = ?,
            alicuota_iva = ?,
            iva = ?,
            total = ?,
            monto_usd = ?
        WHERE id = ?;");

    if (!$stmtUpd) {
        throw new Exception("Error preparando actualización: " . $link->error);
    }

    $stmtUpd->bind_param(
        "ddddddddi",
        $descuento,
        $descuento2,
        $monto_sin_descuento,
        $monto_total,
        $alicuota_iva,
        $iva,
        $total,
        $monto_usd,
        $id
    );

    if (!$stmtUpd->execute()) {
        throw new Exception("Error actualizando salidas: " . $stmtUpd->error);
    }

    $stmtUpd->close();

    $response["success"] = true;
    $response["data"] = [
        "descuento" => $descuento,
        "descuento2" => $descuento2,
        "monto_sin_descuento" => $monto_sin_descuento,
        "monto_descuento1" => $monto_descuento1,
        "monto_descuento2" => $monto_descuento2,
        "monto_total" => $monto_total,
        "exento" => $exento,
        "gravado" => $gravado,
        "alicuota_iva" => $alicuota_iva,
        "iva" => $iva,
        "total" => $total,
        "monto_usd" => $monto_usd,
        "moneda" => $moneda,
        "tasa_dia" => $tasa_dia
    ];

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
