<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "0";

$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"];

$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["alicuota_dinamica"] = $row["alicuota"];

$sql = "SELECT cantidad_articulo, cantidad_movimiento 
        FROM entradas_salidas 
        WHERE id_documento = $id_invoice 
        AND tipo_documento = 'TDCFCV' 
        AND cantidad_movimiento IS NULL;";
$rs = mysqli_query($link, $sql);
if ($row = mysqli_fetch_array($rs)) {
    $sql = "UPDATE entradas_salidas
            SET cantidad_movimiento = (-1)*cantidad_articulo 
            WHERE id_documento = $id_invoice 
            AND tipo_documento = 'TDCFCV' 
            AND cantidad_movimiento IS NULL;";
    mysqli_query($link, $sql);
}

$sql = "SELECT 
            id, date_format(fecha, '%d/%m/%Y') as fecha, 
            date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus, 
            asesor, documento, monto_usd, IFNULL(tasa_dia, 0) AS tasa_dia, asesor_asignado, dias_credito, 
            date_format(DATE_ADD(fecha,INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc, doc_afectado, 
            descuento, descuento2, moneda   
        FROM salidas where id = '$id_invoice';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

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

$descuento_comercial = floatval($row["descuento"]);
$descuento_comercial2 = floatval($row["descuento2"]);
$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = isset($row["asesor"]) ? $row["asesor"] : "";

if (($monto_usd == 0 or $tasa_dia == 0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) { 
    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
    $rs = mysqli_query($link, $sql);
    $row_t = mysqli_fetch_array($rs); 
    $tasa = floatval($row_t["tasa"]);

    if ($tasa > 0) {
        $sql = "UPDATE salidas SET monto_usd = (total/$tasa), tasa_dia = $tasa WHERE id = '$id_invoice'"; 
        mysqli_query($link, $sql);
    }
    $tasa_dia = $tasa;
}

$sql = "SELECT a.nombre   
        FROM usuario AS u 
        JOIN asesor AS a ON a.id = u.asesor 
        WHERE u.username = '$asesor';"; 
$rs = mysqli_query($link, $sql);
if ($row = mysqli_fetch_array($rs)) {
    $GLOBALS["asesor"] = substr($row["nombre"], 0, 15);
} else { 
    $GLOBALS["asesor"] = "";
}

class PDF extends FPDF
{
    function Header()
    {
        require("../include/connect2.php");
        $sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        $cia_id = $row["id"];

        $sql = "SELECT b.campo_descripcion AS banco, a.titular, a.tipo, a.numero 
                FROM compania_cuenta AS a 
                LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.banco AND b.tabla = 'BANCO' 
                WHERE a.compania = '$cia_id' AND a.mostrar = 'S' AND a.activo = 'S';";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        $GLOBALS["cta_cia"] = $row["numero"];

        $sql = "SELECT a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, a.direccion, a.telefono1, a.email1 
                FROM compania AS a 
                LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
                WHERE a.id = '$cia_id';";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        $ciudad = $row["ciudad"];
        $direccion = $row["direccion"]; 
        $cia_nombre = $row["nombre"];

        $sql = "SELECT a.id, a.ci_rif, a.nombre, a.contacto, a.email1, a.direccion, b.campo_descripcion AS ciudad, 
                CONCAT(REPLACE(ifnull(a.telefono1,''), ' ', ''), ' ', REPLACE(ifnull(a.telefono2,''), ' ', '')) as telf, a.web, a.email2 AS SICM 
                FROM cliente AS a 
                LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
                WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        
        $rif = $row["ci_rif"];
        $razon_social = html_entity_decode($row["nombre"]);
        $direccion_cliente = $row["direccion"] . ". " . $row["ciudad"]; 
        $telf = $row["telf"]; 
        $web = $row["web"]; 

        $this->Ln(20);
        $this->SetFont('Courier','',8);
        $this->Ln(18);

        $this->Cell(5, 3);
        $this->SetFont('Courier','B',8);
        $this->Cell(30, 3,"RAZON SOCIAL: ",'0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(120, 3, mb_convert_encoding(substr($razon_social, 0, 55), "UTF-8", "ISO-8859-1"),'0','0','L');
        
        $this->SetFont('Courier','B',8);
        $tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Crédito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de Débito: ":"N/A")));
        $this->Cell(30, 3, $tdoc,'0','0','R');
        $this->SetFont('Courier','',8);
        $this->Cell(30, 3, $GLOBALS["nro_documento"],'0','0','L');
        $this->Ln();

        $this->Cell(40, 3);
        $this->Cell(110, 3, mb_convert_encoding(substr($razon_social, 55), "UTF-8", "ISO-8859-1"),'0','0','L');
        $this->Ln();

        $this->Cell(5, 4);
        $this->SetFont('Courier','B',8);
        $this->Cell(30, 4,'DIRECCION: ','0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(120, 4, substr($direccion_cliente, 0, 60), '0', '0', 'L');
        $this->SetFont('Courier','B',8);
        $this->Cell(33, 4,'Fecha: ','0','0','R');
        $this->SetFont('Courier','',8);
        $this->Cell(30, 4, $GLOBALS["fecha"], 0, 0, 'L');
        $this->Ln();       
        
        $this->Cell(5, 5);
        $this->Cell(190, 5, substr($direccion_cliente, 60), '0', '0', 'L');
        $this->Ln(6);

        $this->Cell(5, 4);
        $this->SetFont('Courier','B',8);
        $this->Cell(12,4,'R.I.F.: ','0',0,'L');
        $this->SetFont('Courier','',8);
        $this->Cell(20,4,str_replace("-", "", $rif),'0',0,'L');
        
        $this->SetFont('Courier','B',8);
        $this->Cell(8,4,'Telf:','0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(50,4,str_replace("-", "", $telf),'0','0','L');

        $this->SetFont('Courier','B',8);
        $this->Cell(10,4,'SICM:','0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(15,4,$web,'0','0','L');

        $this->SetFont('Courier','B',8);
        $this->Cell(12,4,'Asesor:','0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(30,4,$GLOBALS["asesor"],'0','0','L');

        $this->SetFont('Courier','B',8);
        $this->Cell(24,4,'Dias Cred:','0','0','L');
        $this->SetFont('Courier','',8);
        $this->Cell(5,4,$GLOBALS["dias_credito"] . " " . $GLOBALS["fec_venc"],'0','0','L');

        require("../include/desconnect.php");
        $this->Ln();

        $this->SetFont('Courier','B',8);
        $this->Cell(5, 5);
        $this->Cell(16, 5, "LAB", 1, 0, 'L');
        $this->Cell(45, 5, "ARTICULO", 1, 0, 'L');
        $this->Cell(16, 5, "LOTE", 1, 0, 'C');
        $this->Cell(10, 5, "VENC", 1, 0, 'C');
        $this->Cell(8, 5, "CAN", 1, 0, 'R');
        $this->Cell(10, 5, "IVA %", 1, 0, 'R');
        $this->Cell(22, 5, "PRECIO Bs.", 1, 0, 'R');
        $this->Cell(11, 5, "PREC $", 1, 0, 'R');
        $this->Cell(8, 5, "DES1", 1, 0, 'R');
        $this->Cell(8, 5, "DES2", 1, 0, 'R');
        $this->Cell(8, 5, "DES3", 1, 0, 'R');
        $this->Cell(23, 5, "TOTAL Bs.", 1, 0, 'R');
        $this->Cell(15, 5, "TOTAL $", 1, 0, 'R');
        $this->Ln(5);
    }
    
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Courier','I',8);
    }
    
    function EndReport($id_invoice)
    {
        require("../include/connect2.php");
        global $tasa_dia;

        $sql = "SELECT a.alicuota_iva, a.total, a.igtf, a.monto_base_igtf, a.monto_igtf, 
                       IFNULL(a.nota, '') AS nota, a.moneda, a.id_documento_padre, 
                       a.monto_usd, a.descuento, a.descuento2, a.unidades, 
                       IFNULL(a.nro_despacho, '') as nro_despacho  
                FROM salidas a where a.id = '$id_invoice'"; 
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        
        $moneda = $row["moneda"];
        $descuento = floatval($row["descuento"]);
        $descuento2 = floatval($row["descuento2"]);
        $igtf_status = $row["igtf"];
        $monto_base_igtf = floatval($row["monto_base_igtf"]);
        $monto_igtf = floatval($row["monto_igtf"]);
        $nota = $row["nota"];
        $nro_despacho = $row["nro_despacho"];

        $sql = "SELECT SUM(IF(IFNULL(alicuota, 0) = 0, precio_unidad, 0) * cantidad_articulo) AS exento,  
                       SUM(IF(IFNULL(alicuota, 0) = 0, 0, precio_unidad) * cantidad_articulo) AS gravado,
                       MAX(IFNULL(alicuota,0)) AS alicuota_act  
                FROM entradas_salidas WHERE tipo_documento = 'TDCFCV' AND id_documento = '$id_invoice';"; 
        $rs = mysqli_query($link, $sql);
        $row_tot = mysqli_fetch_array($rs);
        
        $exento = floatval($row_tot["exento"]);
        $gravado = floatval($row_tot["gravado"]);
        $xalicuota = floatval($row_tot["alicuota_act"]);

        $exento = ($exento - ($exento * ($descuento/100))) - (($exento - ($exento * ($descuento/100))) * ($descuento2/100));
        $gravado = ($gravado - ($gravado * ($descuento/100))) - (($gravado - ($gravado * ($descuento/100))) * ($descuento2/100));

        if($igtf_status == "S") 
        	$this->Ln(215 - $this->GetY());
        else 
        	$this->Ln(225 - $this->GetY());

        $this->SetFont('Courier','B',8);
        $this->Cell(149, 4, "SUB-TOTAL:", 0, 0, 'R');
        $val_subtotal = $exento + $gravado;
        $sub_bs = ($moneda == 'USD') ? $val_subtotal * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $val_subtotal * $tasa_dia : $val_subtotal);
        $sub_usd = ($moneda == 'USD') ? $val_subtotal : ($GLOBALS["moneda_default"] == "USD" ? $val_subtotal : $val_subtotal / $tasa_dia);
        $this->SetFont('Courier','',8);
        $this->Cell(40, 4, number_format($sub_bs, 2, ",", "."), 0, 0, 'R');
        $this->Cell(19, 4, number_format($sub_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(4);

        $this->SetFont('Courier','B',8);
        $this->Cell(149, 4, "TOTAL EXENTO:", 0, 0, 'R');
        $exe_bs = ($moneda == 'USD') ? $exento * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $exento * $tasa_dia : $exento);
        $exe_usd = ($moneda == 'USD') ? $exento : ($GLOBALS["moneda_default"] == "USD" ? $exento : $exento / $tasa_dia);
        $this->SetFont('Courier','',8);
        $this->Cell(40, 4, number_format($exe_bs, 2, ",", "."), 0, 0, 'R');
        $this->Cell(19, 4, number_format($exe_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(4);

        $xIVA = $gravado * ($xalicuota / 100);
        $xTotal = $exento + $gravado + $xIVA;

        $alicuota_dinamica = $GLOBALS["alicuota_dinamica"];
        $this->SetFont('Courier', 'BI', 10);
        if ($igtf_status != "S") {
            $monto_referencia_igtf = $xTotal + ($xTotal * ($alicuota_dinamica / 100));
            $label_igtf = "I.G.T.F. ".number_format($alicuota_dinamica, 0)."%: USD " . number_format(($moneda == "USD" ? $monto_referencia_igtf : $monto_referencia_igtf / $tasa_dia), 2, ",", ".");
            $this->Cell(65, 4, $label_igtf, 0, 0, 'L');
        } else {
            $this->Cell(65, 4, "", 0, 0, 'L');
        }

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(26, 4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'C');
        $this->Cell(58, 4, "TOTAL BASE IMPONIBLE:", 0, 0, 'R');
        $grav_bs = ($moneda == 'USD') ? $gravado * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $gravado * $tasa_dia : $gravado);
        $grav_usd = ($moneda == 'USD') ? $gravado : ($GLOBALS["moneda_default"] == "USD" ? $gravado : $gravado / $tasa_dia);
        $this->SetFont('Courier','',8);
        $this->Cell(40, 4, number_format($grav_bs, 2, ",", "."), 0, 0, 'R');
        $this->Cell(19, 4, number_format($grav_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(4);

        $this->SetFont('Courier','',6);
        $this->Cell(91, 4, mb_convert_encoding("Tasa de cambio Publicada por el B.C.V. segun la fecha de emision de esta factura.", "UTF-8", "UTF-8"), 0, 0, 'L');
        $this->SetFont('Courier','B',8);
        $this->Cell(58,4, "IVA:", 0, 0, 'R');
        $iva_bs = ($moneda == 'USD') ? $xIVA * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $xIVA * $tasa_dia : $xIVA);
        $iva_usd = ($moneda == 'USD') ? $xIVA : ($GLOBALS["moneda_default"] == "USD" ? $xIVA : $xIVA / $tasa_dia);
        $this->SetFont('Courier','',8);
        $this->Cell(40, 4, number_format($iva_bs, 2, ",", "."), 0, 0, 'R');
        $this->Cell(19, 4, number_format($iva_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(4);

        $this->SetFont('Courier','B',7);
        $this->Cell(5, 4);
        $this->Cell(110, 4, mb_convert_encoding("IGTF Sujeto a Pago Recibido (Efectivo $) segun Art 1 GO 42339 17/03/2022.", "UTF-8", "UTF-8"), 0, 0, 'R');
        $this->SetFont('Courier','B',8);
        $this->Cell(34, 4, "TOTAL Bs./USD $:", 0, 0, 'R');
        $total_final_bs = ($moneda == 'USD') ? $xTotal * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $xTotal * $tasa_dia : $xTotal);
        $total_final_usd = ($moneda == 'USD') ? $xTotal : ($GLOBALS["moneda_default"] == "USD" ? $xTotal : $xTotal / $tasa_dia);
        $this->SetFont('Courier','',8);
        $this->Cell(40, 4, number_format($total_final_bs, 2, ",", "."), 0, 0, 'R');
        $this->Cell(19, 4, number_format($total_final_usd, 2, ",", "."), 0, 0, 'R');
        $this->Ln(4);

        if ($igtf_status == "S") {
            $this->SetFont('Courier', 'B', 8);
            if ($moneda == "USD") {
                $this->Cell(149, 4, "I.G.T.F. 3% s/Base: " . number_format($monto_base_igtf * $tasa_dia, 2, ",", ".") . " Bs./USD $:", 0, 0, 'R');
                $igtf_bs = ($tasa_dia > 0) ? $monto_igtf * $tasa_dia : 0;
                $igtf_usd = $monto_igtf;
            } else {
                $this->Cell(149, 4, "I.G.T.F. 3% s/Base: " . number_format($monto_base_igtf, 2, ",", ".") . " Bs./USD $:", 0, 0, 'R');
                $igtf_bs = $monto_igtf;
                $igtf_usd = ($tasa_dia > 0) ? $monto_igtf / $tasa_dia : 0;
            }
            $this->SetFont('Courier', '', 8);
            $this->Cell(40, 4, number_format($igtf_bs, 2, ",", "."), 0, 0, 'R');
            $this->Cell(19, 4, number_format($igtf_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(4);

            $this->SetFont('Courier', 'B', 9);
            $this->Cell(149, 4, "TOTAL CON IGTF Bs./USD $:", 0, 0, 'R');
            $total_con_igtf_bs = $total_final_bs + $igtf_bs;
            $total_con_igtf_usd = $total_final_usd + $igtf_usd;
            $this->Cell(40, 4, number_format($total_con_igtf_bs, 2, ",", "."), 0, 0, 'R');
            $this->Cell(19, 4, number_format($total_con_igtf_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(4);
        }

        $this->SetFont('Courier','B',8);
        $this->Cell(30, 4, "Unidades: " . intval($row["unidades"]), 0, 0, 'C');
        $this->Cell(71, 4, strtoupper($nota), 0, 0, 'R');
        if (trim($nro_despacho) != "") { 
            $this->Cell(90, 4, "Nro. Despacho: " . $nro_despacho, 0, 0, 'C'); 
        } 
        $this->Ln(4);
        
        $this->Cell(10, 4);
        $this->SetFont('Courier','B',8);
        $this->Cell(180, 4, mb_convert_encoding("Esta factura sera indexada a la tasa de cambio expresada por el B.C.V. al momento de recibir el pago.", "UTF-8", "UTF-8"), 0, 0, 'L');
        
        require("../include/desconnect.php");
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Courier','',8);

$sql = "SELECT 
            IFNULL(b.codigo, '') AS codigo, 
            LTRIM(RTRIM(CONCAT(IFNULL(b.nombre_comercial, ''), ' ', IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, '')))) AS articulo, 
            a.lote, date_format(a.fecha_vencimiento, '%m/%y') as vencimiento, 
            a.cantidad_articulo AS cantidad, 
            (SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
            a.alicuota, a.precio_unidad, a.precio, c.nombre AS fabricante, 
            a.descuento, a.precio_unidad_sin_desc AS precio_ful 
        FROM entradas_salidas AS a 
        LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
        LEFT OUTER JOIN fabricante AS c ON c.id = a.fabricante 
        WHERE a.id_documento = '$id_invoice' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
        ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
while ($row = mysqli_fetch_array($rs)) {
    $printE = floatval($row["alicuota"]) == 0.00 ? " (E)" : "";
    $pdf->SetFont('Courier', '', 7);
    $pdf->Cell(5, 3);
    $pdf->Cell(16, 3, substr($row["fabricante"], 0, 10), 0, 0, 'L');
    
    $nombre_art = trim($row["articulo"]);
    $pdf->Cell(45, 3, substr($nombre_art, 0, 28), 0, 0, 'L');
    $pdf->Cell(16, 3, $row["lote"], 0, 0, 'R');
    $pdf->Cell(10, 3, ($row["vencimiento"] == "01/90" ? "" : $row["vencimiento"]), 0, 0, 'R');
    $pdf->Cell(8, 3, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
    $pdf->Cell(10, 3, number_format($row["alicuota"], 0, ",", "."), 0, 0, 'R');

    $precio_full = floatval($row["precio_unidad"]);
    $x_precio_full = $precio_full / (1 - (floatval($row["descuento"]) / 100));

    if ($GLOBALS["moneda"] == 'USD') {
        $val_precio_bs = $x_precio_full * $tasa_dia;
        $val_precio_usd = $x_precio_full;
    } else {
        $val_precio_bs = ($GLOBALS["moneda_default"] == "USD" ? $x_precio_full * $tasa_dia : $x_precio_full);
        $val_precio_usd = ($GLOBALS["moneda_default"] == "USD" ? $x_precio_full : $x_precio_full / $tasa_dia);
    }

    $pdf->Cell(22, 3, $printE . number_format($val_precio_bs, 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(11, 3, number_format($val_precio_usd, 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(8, 3, floatval($row["descuento"]) > 0 ? number_format($row["descuento"], 0) . "%" : "", 0, 0, 'R');
    $pdf->Cell(8, 3, $descuento_comercial > 0 ? number_format($descuento_comercial, 0) . "%" : "", 0, 0, 'R');
    $pdf->Cell(8, 3, $descuento_comercial2 > 0 ? number_format($descuento_comercial2, 0) . "%" : "", 0, 0, 'R');

    $p_linea = $precio_full - ($precio_full * $descuento_comercial / 100);
    $p_linea = $p_linea - ($p_linea * $descuento_comercial2 / 100);
    $p_linea_total = $p_linea * intval($row["cantidad"]);

    if ($GLOBALS["moneda"] == 'USD') {
        $val_total_bs = $p_linea_total * $tasa_dia;
        $val_total_usd = $p_linea_total;
    } else {
        $val_total_bs = ($GLOBALS["moneda_default"] == "USD" ? $p_linea_total * $tasa_dia : $p_linea_total);
        $val_total_usd = ($GLOBALS["moneda_default"] == "USD" ? $p_linea_total : $p_linea_total / $tasa_dia);
    }

    $pdf->Cell(23, 3, number_format($val_total_bs, 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(15, 3, number_format($val_total_usd, 2, ",", "."), 0, 0, 'R');

    $sw = false;
    if (strlen($nombre_art) > 28) {
        $pdf->Ln();
        $pdf->Cell(21, 4);
        $pdf->MultiCell(47, 3, substr($nombre_art, 28), 0, 'L');
        $sw = true;
    }
    
    if (!$sw) $pdf->Ln();
    if ($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);
require("../include/desconnect.php");
$pdf->Output();
?>