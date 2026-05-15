<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

function enc_pdf($txt) {
    $texto = html_entity_decode((string)$txt, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
}

// Moneda default e IGTF dinámico
$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default' LIMIT 1";
$rs = mysqli_query($GLOBALS['link'], $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"] ?? "Bs.";

$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S' LIMIT 1";
$rs = mysqli_query($GLOBALS['link'], $sql);
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
            IFNULL(entregado, 'N') AS formato_usd,
            asesor_asignado,
            dias_credito,
            DATE_FORMAT(DATE_ADD(fecha, INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc,
            doc_afectado,
            descuento,
            descuento2,
            moneda,
            impreso
        FROM salidas
        WHERE id = '{$id_invoice}'
        LIMIT 1";
$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
$row = mysqli_fetch_array($rs);

if (!$row) {
    die("Documento no encontrado.");
}

$formato_usd = $row["formato_usd"];
if ($formato_usd == "S") {
    header("Location: factura_de_venta_formato_en_usd.php?id={$id_invoice}");
    die();
}

$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"] == "ANULADO" ? $row["estatus"] . " - " : "";
$GLOBALS["documento"] = $row["documento"];
$GLOBALS["dias_credito"] = $row["dias_credito"];
$GLOBALS["fec_venc"] = $row["fec_venc"];
$GLOBALS["doc_afectado"] = $row["doc_afectado"];
$GLOBALS["moneda"] = $row["moneda"];
$GLOBALS["impreso"] = $row["impreso"];

if (trim($GLOBALS["nro_documento"] ?? "") != "") {
    if ($row["impreso"] != "S") {
        mysqli_query($link, "UPDATE salidas SET impreso = 'S' WHERE id = '{$id_invoice}'");
    }
}

if (trim($GLOBALS["nro_documento"] ?? "") == "") {
    $GLOBALS["impreso"] = "S";
}

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = $row["asesor"] ?? "";

if (($monto_usd == 0 || $tasa_dia == 0) && strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) {
    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1";
    $rs = mysqli_query($link, $sql);
    $rowTasa = mysqli_fetch_array($rs);
    $tasa = floatval($rowTasa["tasa"] ?? 0);

    if ($tasa > 0) {
        mysqli_query($link, "UPDATE salidas SET monto_usd = (total/{$tasa}), tasa_dia = {$tasa} WHERE id = '{$id_invoice}'");
        $tasa_dia = $tasa;
    }
}

$sql = "SELECT a.nombre
        FROM usuario AS u
        JOIN asesor AS a ON a.id = u.asesor
        WHERE u.username = '" . mysqli_real_escape_string($link, $asesor) . "'
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
        $this->Cell(25, 4, 'TELEFONOS:', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(55, 4, $telf, 0, 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4, 'R.I.F.: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 4, str_replace('-', '', $rif), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(50, 4, 'CONDICIONES DE PAGO: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 4, 'Contado', 0, 0, 'L');

        require("../include/desconnect.php");

        $this->Ln();
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 4);
        $this->Cell(25, 4, "CANT", "B", 0, 'C');
        $this->Cell(85, 4, "DESCRIPCION", "B", 0, 'C');
        $this->Cell(30, 4, "PRECIO UNIT", "B", 0, 'R');
        $this->Cell(20, 4, "% ALIC", "B", 0, 'R');
        $this->Cell(30, 4, "TOTAL", "B", 0, 'R');
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
        $moneda = $row["moneda"] ?? "Bs.";
        $id_documento_padre = $row["id_documento_padre"];
        $tasa_dia = floatval($row["tasa_dia"] ?: 1);
        $descuento = floatval($row["descuento"]);
        $descuento2 = floatval($row["descuento2"]);
        $igtf = $row["igtf"];
        $monto_base_igtf = floatval($row["monto_base_igtf"]);
        $monto_igtf = floatval($row["monto_igtf"]);

        $sql = "SELECT
                    SUM(precio) AS precio,
                    SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento,
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
                    SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuento}/100)), 0)) AS exento_2,
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100)))) AS gravado_2,
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100))) * (IFNULL(alicuota,0)/100)) AS iva,
                    SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuento}/100)), 0)) +
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100)))) +
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100))) * (IFNULL(alicuota,0)/100)) AS total,
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

        // Ubicar resumen en media carta superior, como formato DECODIBO
        $this->Ln(100 - $this->GetY());

        $this->Cell(10, 3, "", 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(130, 3, strtoupper(enc_pdf(trim($nota . " " . $asociado))), 0, 'L');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(168, 3, "SUB-TOTAL EXENTO:", 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 3, number_format(floatval($rowTot["exento"]), 2, ",", "."), 0, 0, 'R');
        $this->Ln(3);

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(168, 3, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 3, number_format(floatval($rowTot["gravado"]), 2, ",", "."), 0, 0, 'R');
        $this->Ln(3);

        $monto_exento = floatval($rowTot["exento"]);

        $base_sin_descuento_exento = $monto_exento;
        $base_con_descuento1_exento = $base_sin_descuento_exento - ($base_sin_descuento_exento * ($descuento / 100));
        $monto_descuento1_exento = $base_sin_descuento_exento - $base_con_descuento1_exento;
        $monto_exento -= $monto_descuento1_exento;

        $monto_gravado = floatval($rowTot["gravado"]);

        $base_sin_descuento_gravado = $monto_gravado;
        $base_con_descuento1_gravado = $base_sin_descuento_gravado - ($base_sin_descuento_gravado * ($descuento / 100));
        $monto_descuento1_gravado = $base_sin_descuento_gravado - $base_con_descuento1_gravado;
        $monto_gravado -= $monto_descuento1_gravado;

        $monto_descuento1 = $monto_descuento1_exento + $monto_descuento1_gravado;
        
        if ($descuento > 0) {
            $this->SetFont('Arial','B',7);
            $this->Cell(168, 3, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(32, 3, "-" . number_format($monto_descuento1, 2, ",", "."), 0, 0, 'R');
            $this->Ln(3);
        }

        if ($descuento2 > 0) {
            $monto_descuento2_exento = $base_con_descuento1_exento * ($descuento2 / 100);
            $monto_descuento2_gravado = $base_con_descuento1_gravado * ($descuento2 / 100);

            $monto_exento -= $monto_descuento2_exento;
            $monto_gravado -= $monto_descuento2_gravado;

            $monto_descuento2 = $monto_descuento2_exento + $monto_descuento2_gravado;

            $this->SetFont('Arial','B',7);
            $this->Cell(168, 3, "Descuento2 " . number_format($descuento2, 2, ",", ".") . "%:", 0, 0, 'R');
            $this->SetFont('Arial','',7);
            $this->Cell(32, 3, "-" . number_format($monto_descuento2, 2, ",", "."), 0, 0, 'R');
            $this->Ln(3);
        }

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(168, 3, "IVA " . number_format(floatval($rowTot["alicuota"]), 2, ",", ".") . " % SOBRE  {$moneda} " . number_format(floatval($monto_gravado), 2, ",", ".") . ": ", 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $alicuota = floatval($rowTot["alicuota"]);
        $monto_iva = $monto_gravado*($alicuota/100);
        $this->Cell(32, 3, number_format(floatval($monto_iva), 2, ",", "."), 0, 0, 'R');
        $this->Ln(3);

        $monto_total = ($monto_exento + $monto_gravado) + $monto_iva;

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(168, 3, "TOTAL {$moneda}:", 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(32, 3, number_format(floatval($monto_total), 2, ",", "."), 0, 0, 'R');

        if ($igtf == "S") {
            $totaligtf = $monto_total + ($moneda == "Bs." ? $monto_igtf : $monto_igtf/$tasa_dia);

            $this->Ln(3);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(168, 3, "I.G.T.F. " . number_format($igtf_porc, 0, "", "") . "% SOBRE " . number_format(($moneda == "Bs." ? $monto_base_igtf : $monto_base_igtf/$tasa_dia), 2, ",", ".") . " {$moneda}:", 0, 0, 'R');
            $this->SetFont('Arial', '', 7);
            $this->Cell(32, 3, number_format(($moneda == "Bs." ? $monto_igtf : $monto_igtf/$tasa_dia), 2, ",", "."), 0, 0, 'R');

            $this->Ln(3);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(168, 3, "TOTAL GENERAL {$moneda}:", 0, 0, 'R');
            $this->SetFont('Arial', '', 7);
            $this->Cell(32, 3, number_format($totaligtf, 2, ",", "."), 0, 0, 'R');
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
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(10, 3);
    $pdf->Cell(25, 3, number_format($row["cantidad"], 0, "", ""), "", 0, 'C');

    $articulo = ($row["alicuota"] == 0.00 ? "(E) " : "") . trim($row["articulo"]);
    if (strlen($articulo) < 63) {
        $pdf->Cell(85, 3, enc_pdf($articulo), 0, 0, 'L');
    } else {
        $pdf->Cell(85, 3, enc_pdf(substr($articulo, 0, 63)), 0, 0, 'L');
    }

    $pdf->Cell(30, 3, number_format(floatval($row["precio_unidad"]), 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(20, 3, number_format(floatval($row["alicuota"]), 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(30, 3, number_format(floatval($row["precio"]), 2, ",", "."), 0, 0, 'R');

    if (strlen($articulo) >= 63) {
        $pdf->Ln();
        $pdf->Cell(35, 3);
        $pdf->MultiCell(85, 3, enc_pdf(substr($articulo, 63)), 0, 'L');
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
