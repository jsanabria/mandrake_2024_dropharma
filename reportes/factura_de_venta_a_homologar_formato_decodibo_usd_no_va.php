<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

function enc_pdf($txt) {
    $texto = html_entity_decode((string)$txt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
}

function sql_val($link, $value) {
    return mysqli_real_escape_string($link, (string)$value);
}

// Moneda default e IGTF dinámico
$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default' LIMIT 1";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"] ?? "Bs.";

$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S' LIMIT 1";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["alicuota_dinamica"] = floatval($row["alicuota"] ?? 0);

// Normalizar cantidad_movimiento si viene NULL
$sql = "SELECT id FROM entradas_salidas WHERE id_documento = {$id_invoice} AND tipo_documento = 'TDCFCV' AND cantidad_movimiento IS NULL LIMIT 1";
$rs = mysqli_query($link, $sql);
if ($row = mysqli_fetch_array($rs)) {
    mysqli_query($link, "UPDATE entradas_salidas SET cantidad_movimiento = (-1) * cantidad_articulo WHERE id_documento = {$id_invoice} AND tipo_documento = 'TDCFCV' AND cantidad_movimiento IS NULL");
}

// Cabecera documento
$sql = "SELECT
            id,
            DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
            DATE_FORMAT(fecha, '%Y/%m/%d') AS fech,
            cliente,
            nro_documento,
            nro_control,
            tipo_documento,
            estatus,
            asesor,
            documento,
            monto_usd,
            IFNULL(tasa_dia, 0) AS tasa_dia,
            moneda,
            asesor_asignado,
            dias_credito,
            DATE_FORMAT(DATE_ADD(fecha, INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc,
            doc_afectado,
            descuento,
            descuento2,
            IFNULL(entregado, 'N') AS formato_usd,
            impreso
        FROM salidas
        WHERE id = '{$id_invoice}'
        LIMIT 1";
$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
$row = mysqli_fetch_array($rs);

if (!$row) {
    die("Documento no encontrado.");
}

$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"] == "ANULADO" ? $row["estatus"] . " - " : "";
$GLOBALS["documento"] = $row["documento"];
$GLOBALS["moneda"] = $row["moneda"] ?: "USD";
$GLOBALS["dias_credito"] = $row["dias_credito"];
$GLOBALS["fec_venc"] = $row["fec_venc"];
$GLOBALS["doc_afectado"] = $row["doc_afectado"];
$GLOBALS["impreso"] = $row["impreso"];

if (trim($GLOBALS["nro_documento"] ?? "") != "") {
    if ($row["impreso"] != "S") {
        mysqli_query($link, "UPDATE salidas SET impreso = 'S' WHERE id = '{$id_invoice}'");
    }
}

if (trim($GLOBALS["nro_documento"] ?? "") == "") {
    $GLOBALS["impreso"] = "S";
}

if (substr((string)$row["nro_documento"], 0, 6) == "1PREFAC") {
    header("Location: factura_de_venta_prefactura.php?id={$id_invoice}&tipo=TDCFCV");
    die();
}

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = $row["asesor"] ?? "";

if (($monto_usd == 0 || $tasa_dia == 0) && strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) {
    $monedaTasa = sql_val($link, $GLOBALS["moneda"] ?: "USD");
    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = '{$monedaTasa}' ORDER BY id DESC LIMIT 1";
    $rs = mysqli_query($link, $sql);
    $rowTasa = mysqli_fetch_array($rs);
    $tasa = floatval($rowTasa["tasa"] ?? 0);

    if ($tasa > 0) {
        mysqli_query($link, "UPDATE salidas SET monto_usd = (total/{$tasa}), tasa_dia = {$tasa} WHERE id = '{$id_invoice}'");
        $tasa_dia = $tasa;
    }
}
if ($tasa_dia <= 0) {
    $tasa_dia = 1;
}
$GLOBALS["tasa_dia"] = $tasa_dia;

$sql = "SELECT a.nombre
        FROM usuario AS u
        JOIN asesor AS a ON a.id = u.asesor
        WHERE u.username = '" . sql_val($link, $asesor) . "'
        LIMIT 1";
$rs = mysqli_query($link, $sql);
if ($rowAsesor = mysqli_fetch_array($rs)) {
    $GLOBALS["asesor"] = substr($rowAsesor["nombre"], 0, 15);
} else {
    $GLOBALS["asesor"] = "";
}

class PDF extends FPDF
{
    function RotatedText($x, $y, $txt, $angle)
    {
        $this->_out(sprintf(
            'q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm CP n',
            cos(deg2rad($angle)),
            sin(deg2rad($angle)),
            -sin(deg2rad($angle)),
            cos(deg2rad($angle)),
            $x,
            $y,
            -$x,
            -$y
        ));
        $this->Text($x, $y, $txt);
        $this->_out('Q');
    }

    function MarcaDeAgua()
    {
        $this->SetFont('Arial', 'B', 34);
        $this->SetTextColor(225, 225, 225);
        $this->RotatedText(75, 115, enc_pdf("SIN DERECHO A CRÉDITO FISCAL"), 25);
        $this->SetTextColor(0, 0, 0);
    }

    function Header()
    {
        require("../include/connect2.php");

        $sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 1";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        $ciaId = $row["id"];

        $sql = "SELECT a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, a.direccion, a.telefono1, a.email1
                FROM compania AS a
                LEFT JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
                WHERE a.id = '{$ciaId}'";
        $rs = mysqli_query($link, $sql);
        $rowCia = mysqli_fetch_array($rs);
        $ciudad = $rowCia["ciudad"] ?? "";

        $sql = "SELECT
                    LPAD(a.id, 8, '0') AS id,
                    a.ci_rif,
                    a.nombre,
                    a.contacto,
                    a.email1,
                    a.direccion,
                    b.campo_descripcion AS ciudad,
                    CONCAT(IFNULL(a.telefono1,''), ' ', IFNULL(a.telefono2,'')) AS telf,
                    a.web,
                    a.email2 AS SICM
                FROM cliente AS a
                LEFT JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
                WHERE a.id = '" . $GLOBALS["cliente"] . "'";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);

        $id = $row["id"] ?? "";
        $rif = $row["ci_rif"] ?? "";
        $razon_social = html_entity_decode($row["nombre"] ?? "", ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $razon_social = str_ireplace(['&AMP;', '&amp;'], '&', $razon_social);
        $direccion_cliente = trim(($row["direccion"] ?? "") . ". " . ($row["ciudad"] ?? ""));
        $telf = $row["telf"] ?? "";

        $this->Ln(25);
        $this->SetFont('Arial', '', 10);

        $this->Cell(125, 6);
        $tdoc = ($GLOBALS["documento"] == "FC" ? "Nro. Factura: " : ($GLOBALS["documento"] == "NC" ? "Nro. Nota de Crédito: " : ($GLOBALS["documento"] == "ND" ? "Nro. Nota de Débito: " : "N/A")));
        $this->Cell(30, 6, enc_pdf($tdoc), 0, 0, 'L');
        $this->Cell(45, 6, $GLOBALS["nro_documento"], 0, 0, 'R');

        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(125, 6);
        $this->Cell(30, 6, 'Fecha: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(45, 6, enc_pdf($ciudad . ", " . $GLOBALS["fecha"]), 0, 0, 'R');

        if (trim($GLOBALS["doc_afectado"] ?? "") != "" && in_array($GLOBALS["documento"], ["NC", "ND"])) {
            $this->Ln(5);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(125, 4);
            $this->Cell(38, 4, 'Documento Afectado:', 0, 0, 'L');
            $this->SetFont('Arial', '', 7);
            $this->Cell(37, 4, $GLOBALS["doc_afectado"], 0, 0, 'R');
        }

        if ($GLOBALS["impreso"] == "S") {
            $this->Ln(5);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(0, 4, enc_pdf("SIN DERECHO A CRÉDITO FISCAL"), 0, 1, 'C');
        } else {
            $this->Ln(6);
        }

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4);
        $this->Cell(30, 4, "CLIENTE: ", 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(110, 4, enc_pdf(substr($razon_social, 0, 70)), 0, 0, 'L');

        $this->Ln();
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4);
        $this->Cell(30, 4, "CUENTA Nº: ", 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(110, 4, $id, 0, 0, 'L');

        $this->Ln();
        $this->Cell(10, 4);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 4, 'DIRECCION: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(160, 4, enc_pdf($direccion_cliente), 0, 'L');

        $this->Ln();
        $this->Cell(10, 4);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 4, 'TELEFONOS:', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(40, 4, $telf, 0, 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4, 'R.I.F.: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 4, str_replace('-', '', $rif), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(35, 4, 'CONDICIONES DE PAGO: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(15, 4, 'Contado', 0, 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(35, 4, 'TASA B.C.V.: ', 0, 0, 'R');
        $this->Cell(15, 4, number_format($GLOBALS["tasa_dia"], 4, ".", ","), 0, 0, 'R');
        $this->SetFont('Arial', '', 7);

        require("../include/desconnect.php");

        $this->Ln();
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4);
        $this->Cell(15, 4, "CANT", "B", 0, 'C');
        $this->Cell(110, 4, "DESCRIPCION", "B", 0, 'C');
        $this->Cell(20, 4, "PRECIO UNIT", "B", 0, 'R');
        $this->Cell(10, 4, "% ALIC", "B", 0, 'R');
        $this->Cell(20, 4, "TOT. Bs.", "B", 0, 'R');
        $this->Cell(15, 4, "TOT. " . $GLOBALS["moneda"], "B", 0, 'R');
        $this->Ln(5);

        if ($GLOBALS["impreso"] == "S") {
            $this->MarcaDeAgua();
        }
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 7);
    }

    function EndReport($id_invoice)
    {
        require("../include/connect2.php");
        $doc = "";
        $asociado = "";

        $igtf_porc = floatval($GLOBALS["alicuota_dinamica"]);
        if ($igtf_porc <= 0) {
            $sql = "SELECT valor1 AS igtf FROM parametro WHERE codigo = '037' LIMIT 1";
            $rs = mysqli_query($link, $sql);
            $rowIgtf = mysqli_fetch_array($rs);
            $igtf_porc = floatval($rowIgtf["igtf"] ?? 0);
        }

        $sql = "SELECT
                    a.alicuota_iva,
                    a.iva,
                    a.monto_total,
                    a.total,
                    IFNULL(a.nota, '') AS nota,
                    a.moneda,
                    a.asesor,
                    a.id_documento_padre,
                    a.monto_usd,
                    IFNULL(a.tasa_dia, 0) AS tasa_dia,
                    a.descuento,
                    a.descuento2,
                    a.monto_sin_descuento,
                    a.unidades,
                    a.igtf,
                    a.monto_base_igtf,
                    a.monto_igtf
                FROM salidas a
                WHERE a.id = '{$id_invoice}'";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);

        $nota = $row["nota"] ?? "";
        $moneda = $row["moneda"] ?: "USD";
        $id_documento_padre = $row["id_documento_padre"];
        $tasa_dia = floatval($row["tasa_dia"] ?: 1);
        $descuento = floatval($row["descuento"]);
        $descuento2 = floatval($row["descuento2"]);
        $igtf = $row["igtf"];
        $monto_base_igtf = floatval($row["monto_base_igtf"]);
        $monto_igtf = floatval($row["monto_igtf"]);
        $alicuota_salidas = floatval($row["alicuota_iva"]);

        $sql = "SELECT
                    SUM(precio) AS precio,
                    SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento,
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
                    MAX(IFNULL(alicuota,0)) AS alicuota
                FROM entradas_salidas
                WHERE tipo_documento = 'TDCFCV'
                  AND id_documento = '{$id_invoice}'";
        $rs = mysqli_query($link, $sql);
        $rowTot = mysqli_fetch_array($rs);

        $sql2 = "SELECT b.descripcion, a.nro_documento
                 FROM salidas AS a
                 JOIN tipo_documento AS b ON b.codigo = a.tipo_documento
                 WHERE a.id = '{$id_documento_padre}'";
        $rs2 = mysqli_query($link, $sql2);
        $sw = false;
        while ($row2 = mysqli_fetch_array($rs2)) {
            $doc .= " #" . $row2["nro_documento"];
            $tdoc = $row2["descripcion"];
            $sw = true;
        }
        if ($sw) {
            $asociado = "Documento(s) Asociado(s): {$tdoc} {$doc} / ";
        }

        $exento_bs_db = floatval($rowTot["exento"]);
        $gravado_bs_db = floatval($rowTot["gravado"]);
        $base_sin_descuento_bs = $exento_bs_db + $gravado_bs_db;

        // Descuento 1 en cascada
        $base_con_descuento1_bs = $base_sin_descuento_bs - ($base_sin_descuento_bs * ($descuento / 100));
        $monto_descuento1_bs_db = $base_sin_descuento_bs - $base_con_descuento1_bs;

        // Descuento 2 en cascada, calculado sobre la base ya afectada por descuento 1
        $monto_descuento2_bs_db = $base_con_descuento1_bs * ($descuento2 / 100);
        $base_con_descuento2_bs = $base_con_descuento1_bs - $monto_descuento2_bs_db;

        // Aplicar descuentos proporcionalmente a exento y gravado para calcular IVA real
        $factor_descuento = ($base_sin_descuento_bs > 0) ? ($base_con_descuento2_bs / $base_sin_descuento_bs) : 1;
        $exento_desc_bs = $exento_bs_db * $factor_descuento;
        $gravado_desc_bs = $gravado_bs_db * $factor_descuento;

        $alicuota_total = floatval($rowTot["alicuota"]);
        if ($alicuota_total <= 0) {
            $alicuota_total = $alicuota_salidas;
        }

        $iva_bs_db = $gravado_desc_bs * ($alicuota_total / 100);
        $total_bs_db = $exento_desc_bs + $gravado_desc_bs + $iva_bs_db;

        // Lógica USD: la columna USD manda, y Bs se recalcula como USD * tasa
        $exento_usd = round($exento_desc_bs / $tasa_dia, 2);
        $gravado_usd = round($gravado_desc_bs / $tasa_dia, 2);
        $base_sin_descuento_usd = round($base_sin_descuento_bs / $tasa_dia, 2);
        $monto_descuento1_usd = round($monto_descuento1_bs_db / $tasa_dia, 2);
        $monto_descuento2_usd = round($monto_descuento2_bs_db / $tasa_dia, 2);
        $iva_usd = round($iva_bs_db / $tasa_dia, 2);
        $total_usd = round($total_bs_db / $tasa_dia, 2);

        $exento_bs_recalculado = $exento_usd * $tasa_dia;
        $gravado_bs_recalculado = $gravado_usd * $tasa_dia;
        $base_sin_descuento_bs_recalculado = $base_sin_descuento_usd * $tasa_dia;
        $monto_descuento1_bs_recalculado = $monto_descuento1_usd * $tasa_dia;
        $monto_descuento2_bs_recalculado = $monto_descuento2_usd * $tasa_dia;
        $iva_bs_recalculado = $iva_usd * $tasa_dia;
        $total_bs_recalculado = $total_usd * $tasa_dia;

        $this->Ln(100 - $this->GetY());

        $this->Cell(10, 3, "", 0, 0, 'R');
        $this->SetFont('Arial','BI',7);
        $this->MultiCell(190, 3, strtoupper(enc_pdf(trim($nota . " " . $asociado))), 0, 'L');

        $this->SetFont('Arial','',6);
        $this->Cell(10, 3, "", 0, 0, 'L');
        $this->Cell(90, 3, enc_pdf("Este documento se expresa en Dólares Americanos con su equivalente en Bolívares al tipo de cambio corriente"), 0, 0, 'L');
        $this->SetFont('Arial','B',7);
        $this->Cell(63, 3, "SUB-TOTAL EXENTO:", 0, 0, 'R');
        $this->SetFont('Arial','',7);
        $this->Cell(22, 3, number_format($exento_bs_recalculado, 2, ",", "."), 0, 0, 'R');
        $this->Cell(15, 3, number_format($exento_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(3);

        $this->SetFont('Arial','',6);
        $this->Cell(10, 3, "", 0, 0, 'L');
        $this->Cell(90, 3, enc_pdf("del mercado a la fecha de su emisión, según lo establecido en el artículo 13 numeral 14 de la Providencia"), 0, 0, 'L');
        $this->SetFont('Arial','B',7);
        $this->Cell(63, 3, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
        $this->SetFont('Arial','',7);
        $this->Cell(22, 3, number_format($base_sin_descuento_bs_recalculado, 2, ",", "."), 0, 0, 'R');
        $this->Cell(15, 3, number_format($base_sin_descuento_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(3);

        $this->SetFont('Arial','',6);
        $this->Cell(10, 3, "", 0, 0, 'L');
        $this->Cell(90, 3, enc_pdf("Administrativa SNAT/2011/0071, el artículo 128 de la Ley del Banco Central de Venezuela, el artículo 25 de la"), 0, 0, 'L');

        if ($descuento > 0) {
            $this->SetFont('Arial','B',7);
            $this->Cell(63, 3, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(22, 3, number_format($monto_descuento1_bs_recalculado, 2, ",", "."), 0, 0, 'R');
            $this->Cell(15, 3, number_format($monto_descuento1_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(3);

            $this->SetFont('Arial','',6);
            $this->Cell(10, 3, "", 0, 0, 'L');
            $this->Cell(90, 3, enc_pdf("Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A."), 0, 0, 'L');
        }

        if ($descuento2 > 0) {
            if ($descuento <= 0) {
                $this->Ln(3);
                $this->SetFont('Arial','',6);
                $this->Cell(10, 3, "", 0, 0, 'L');
                $this->Cell(90, 3, enc_pdf("Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A."), 0, 0, 'L');
            }

            $this->SetFont('Arial','B',7);
            $this->Cell(63, 3, "Descuento2 " . number_format($descuento2, 2, ",", ".") . "%:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(22, 3, number_format($monto_descuento2_bs_recalculado, 2, ",", "."), 0, 0, 'R');
            $this->Cell(15, 3, number_format($monto_descuento2_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(3);
        } else {
            $this->Ln(3);
        }

        if ($descuento <= 0 && $descuento2 <= 0) {
            $this->SetFont('Arial','',6);
            $this->Cell(10, 3, "", 0, 0, 'L');
            $this->Cell(90, 3, enc_pdf("Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A."), 0, 0, 'L');
        } else {
            $this->Cell(100, 3, "", 0, 0, 'L');
        }

        $this->SetFont('Arial','B',7);
        $this->Cell(63, 3, "IVA " . number_format($alicuota_total, 2, ",", ".") . " % SOBRE {$moneda} " . number_format($gravado_bs_recalculado, 2, ",", ".") . ": ", 0, 0, 'R');
        $this->SetFont('Arial','',7);
        $this->Cell(22, 3, number_format($iva_bs_recalculado, 2, ",", "."), 0, 0, 'R');
        $this->Cell(15, 3, number_format($iva_usd, 2, ",", "."), 0, 0, 'R');

        $this->Ln(3);
        $this->SetFont('Arial','',6);
        $this->Cell(100, 3, "", 0, 0, 'L');

        $this->SetFont('Arial','B',7);
        $this->Cell(63, 3, "TOTAL:", 0, 0, 'R');
        $this->SetFont('Arial','',7);
        $this->Cell(22, 3, number_format($total_bs_recalculado, 2, ",", "."), 0, 0, 'R');
        $this->Cell(15, 3, number_format($total_usd, 2, ",", "."), 0, 0, 'R');

        if ($igtf == "S") {
            $totaligtf_bs_db = $total_bs_db + $monto_igtf;
            $monto_igtf_usd = round($monto_igtf / $tasa_dia, 2);
            $totaligtf_usd = round($totaligtf_bs_db / $tasa_dia, 2);
            $monto_igtf_bs_recalculado = $monto_igtf_usd * $tasa_dia;
            $totaligtf_bs_recalculado = $totaligtf_usd * $tasa_dia;

            $this->Ln(3);
            $this->SetFont('Arial','B',7);
            $this->Cell(30, 3, "", 0, 0, 'R');
            $this->Cell(90, 3, "", 0, 0, 'R');
            $this->Cell(43, 3, "I.G.T.F. " . number_format($igtf_porc, 0, "", "") . "% SOBRE " . number_format($monto_base_igtf, 2, ",", ".") . " {$moneda}:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(22, 3, number_format($monto_igtf_bs_recalculado, 2, ",", "."), 0, 0, 'R');
            $this->Cell(15, 3, number_format($monto_igtf_usd, 2, ",", "."), 0, 0, 'R');

            $this->Ln(3);
            $this->SetFont('Arial','B',7);
            $this->Cell(30, 3, "", 0, 0, 'R');
            $this->Cell(90, 3, "", 0, 0, 'R');
            $this->Cell(43, 3, "TOTAL GENERAL:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(22, 3, number_format($totaligtf_bs_recalculado, 2, ",", "."), 0, 0, 'R');
            $this->Cell(15, 3, number_format($totaligtf_usd, 2, ",", "."), 0, 0, 'R');
        }

        require("../include/desconnect.php");
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2, 10, 9);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 7);

$sql = "SELECT
            IFNULL(b.codigo, '') AS codigo,
            LTRIM(RTRIM(CONCAT(IFNULL(b.codigo, ''), ' ', SUBSTRING(IFNULL(b.principio_activo, ''), 1, 65), ' ', IFNULL(a.lote, '')))) AS articulo,
            a.lote,
            DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS vencimiento,
            a.cantidad_articulo AS cantidad,
            (SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida,
            IFNULL(a.alicuota, 0.00) AS alicuota,
            a.precio_unidad,
            a.precio,
            c.nombre AS fabricante,
            a.descuento,
            a.precio_unidad_sin_desc AS precio_ful
        FROM entradas_salidas AS a
        LEFT JOIN articulo AS b ON b.id = a.articulo
        LEFT JOIN fabricante AS c ON c.id = a.fabricante
        WHERE a.id_documento = '{$id_invoice}'
          AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
        ORDER BY b.principio_activo, b.presentacion";

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($rs)) {
    $tasa_dia = $GLOBALS["tasa_dia"];
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(10, 3);
    $pdf->Cell(15, 3, number_format($row["cantidad"], 0, "", ""), "", 0, 'C');

    $articulo = ($row["alicuota"] == 0.00 ? "(E) " : "") . trim($row["articulo"]);
    if (strlen($articulo) < 70) {
        $pdf->Cell(110, 3, enc_pdf($articulo), 0, 0, 'L');
    } else {
        $pdf->Cell(110, 3, enc_pdf(substr($articulo, 0, 70)), 0, 0, 'L');
    }

    $pdf->Cell(20, 3, number_format(floatval($row["precio_unidad"]), 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(10, 3, number_format(floatval($row["alicuota"]), 2, ",", "."), 0, 0, 'R');

    $precio_bs_db = floatval($row["precio"]);
    $precio_usd = round($precio_bs_db / $tasa_dia, 2);
    $precio_bs_recalculado = $precio_usd * $tasa_dia;

    $pdf->Cell(20, 3, number_format($precio_bs_recalculado, 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(15, 3, number_format($precio_usd, 2, ",", "."), 0, 0, 'R');

    if (strlen($articulo) >= 70) {
        $pdf->Ln();
        if (substr(trim($articulo), 70) != "") {
            $pdf->Cell(25, 3);
            $pdf->MultiCell(110, 3, enc_pdf(substr(trim($articulo), 70)), 0, 'L');
        }
    } else {
        $pdf->Ln();
    }

    if ($pdf->GetY() > 250) {
        $pdf->AddPage();
    }
}

$pdf->EndReport($id_invoice);

require("../include/desconnect.php");
$pdf->Output();
?>
