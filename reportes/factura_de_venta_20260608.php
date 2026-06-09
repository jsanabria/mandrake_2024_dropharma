<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";


$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"];


$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["alicuota_dinamica"] = $row["alicuota"];

/////////////////////////////
$sql = "SELECT 
			cantidad_articulo, cantidad_movimiento 
		FROM 
			entradas_salidas 
		WHERE
			id_documento = $id_invoice 
			AND tipo_documento = 'TDCFCV' 
			AND cantidad_movimiento IS NULL;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$sql = "UPDATE entradas_salidas
				SET cantidad_movimiento = (-1)*cantidad_articulo 
			WHERE
				id_documento = $id_invoice 
				AND tipo_documento = 'TDCFCV' 
				AND cantidad_movimiento IS NULL;";
	mysqli_query($link, $sql);
}
/////////////////////////////

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, 
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus, 
			asesor, documento, monto_usd, IFNULL(tasa_dia, 0) AS tasa_dia, asesor_asignado, dias_credito, 
			date_format(DATE_ADD(fecha,INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc, doc_afectado, 
			descuento, descuento2, moneda, impreso, IFNULL(doc_afe, 0) AS doc_afe   
		FROM salidas where id = '$id_invoice';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"]=="ANULADO" ? $row["estatus"] .  " - " : "";
$GLOBALS["documento"] = $row["documento"];
$GLOBALS["dias_credito"] = $row["dias_credito"];
$GLOBALS["fec_venc"] = $row["fec_venc"];
$GLOBALS["doc_afectado"] = $row["doc_afectado"];
$GLOBALS["doc_afe"] = $row["doc_afe"];
$GLOBALS["moneda"] = $row["moneda"];
$GLOBALS["impreso"] = $row["impreso"];

if(trim($GLOBALS["nro_documento"] ?? "") != "") {
	if ($row["impreso"] != "S") {
	    $sql_impreso = "UPDATE salidas 
	                    SET impreso = 'S' 
	                    WHERE id = '$id_invoice'";
	    mysqli_query($link, $sql_impreso);
	}	
}

if(trim($GLOBALS["nro_documento"] ?? "") == "") $GLOBALS["impreso"] = "S"; // Esto por si se imprime sin nro de dccumento o control

$descuento_comercial = floatval($row["descuento"]);
$descuento_comercial2 = floatval($row["descuento2"]);

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);

// $asesor = $row["asesor"];
$asesor = isset($row["asesor"]) ? $row["asesor"] : "";
$asesor_asignado = isset($row["asesor_asignado"]) ? $row["asesor_asignado"] : "";


if(($monto_usd==0 or $tasa_dia==0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) { 
	$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs); 
	$tasa = floatval($row["tasa"]);

	if($tasa > 0) {
		$sql = "UPDATE salidas SET monto_usd = (total/$tasa), tasa_dia = $tasa WHERE id = '$id_invoice'"; 
		mysqli_query($link, $sql);
	}
	$tasa_dia = $tasa;
}


$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '$asesor';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$GLOBALS["asesor"] = substr($row["nombre"], 0, 15);
else 
	$GLOBALS["asesor"] = "";

/*
$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '$asesor_asignado';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$GLOBALS["asesor"] .= " / " . substr($row["nombre"], 0, 15);
*/
	

class PDF extends FPDF
{
	function MarcaDeAgua()
	{
	    $this->SetFont('Courier','B',40);
	    $this->SetTextColor(230,230,230);
	    $this->RotatedText(25, 220, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 45);

	    $this->SetTextColor(0, 0, 0);
	}

	function RotatedText($x, $y, $txt, $angle)
	{
	    $this->_out(sprintf(
	        'q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm CP n',
	        cos(deg2rad($angle)),
	        sin(deg2rad($angle)),
	        -sin(deg2rad($angle)),
	        cos(deg2rad($angle)),
	        $x, $y, -$x, -$y
	    ));
	    $this->Text($x, $y, $txt);
	    $this->_out('Q');
	}

	// Cabecera de p?gina
	function Header()
	{
		if ($GLOBALS["impreso"] == "S") {
		    $this->MarcaDeAgua();
		}

		// Consulto datos de la compa??a 
		require("../include/connect2.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia =  $row["id"];


		$sql = "SELECT 
					b.campo_descripcion AS banco, 
					a.titular AS titular, 
					a.tipo, 
					a.numero 
				FROM 
					compania_cuenta AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.banco AND b.tabla = 'BANCO' 
				WHERE 
					a.compania = '$cia' AND a.mostrar = 'S' AND a.activo = 'S';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$GLOBALS["cta_cia"] =  $row["numero"];


		$sql = "SELECT 
					a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
					a.direccion, a.telefono1, a.email1 
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];

		
		
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, a.contacto, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(REPLACE(ifnull(a.telefono1,''), ' ', ''), ' ', REPLACE(ifnull(a.telefono2,''), ' ', '')) as telf, a.web, 
					a.email2 AS SICM 
				FROM cliente AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$rif = $row["ci_rif"];
		$razon_social = html_entity_decode($row["nombre"]);
		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 
		$web = $row["web"]; 
		$SICM = $row["SICM"]; 

		$this->Ln(20);
		
		$this->SetFont('Courier','',8);
		$this->Cell(10, 5);
		$this->Cell(50, 5);
		//$this->Cell(100, 6, $GLOBALS["estatus"] . "CIUDAD: $ciudad,",0,0,'R');
		/*$this->Cell(10, 6, substr(($GLOBALS["fecha"]),0,2),0,0,'L');
		$this->Cell(10, 6, substr(($GLOBALS["fecha"]),3,2),0,0,'C');
		$this->Cell(20, 6, substr(($GLOBALS["fecha"]),6,4),0,0,'C');*/
		$this->Ln(18);

		$this->Cell(5, 3);
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 3,"RAZON SOCIAL: ",'0','0','L');
		$this->SetFont('Courier','',8);
		$this->Cell(120, 3, mb_convert_encoding(substr($razon_social, 0, 55), "UTF-8", mb_detect_encoding($razon_social)),'0','0','L');
		$this->SetFont('Courier','B',8);
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Cr?dito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de D?bito: ":"N/A")));
		$this->Cell(30, 3, $tdoc,'0','0','R');
		$this->SetFont('Courier','',8);
		$this->Cell(30, 3, $GLOBALS["nro_documento"],'0','0','L');

		$this->SetFont('Courier','',8);
		$this->Ln();
		

		$this->Cell(40, 3);
		$this->Cell(110, 3, mb_convert_encoding(substr($razon_social, 55, strlen($razon_social)), "UTF-8", mb_detect_encoding($razon_social)),'0','0','L');
		$this->Ln();

		$this->Cell(5, 4);
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->SetFont('Courier','',8);
		$direccion_cliente = "$direccion_cliente. $ciudad_cliente";
		$this->Cell(120, 4, substr($direccion_cliente, 0, 60), '0', '0', 'L');
		$this->SetFont('Courier','B',8);
		$this->Cell(33, 4,'Fecha: ','0','0','R');
		$this->SetFont('Courier','',8);
		$this->Cell(30, 4, $GLOBALS["fecha"], 0, 0, 'L');

		$this->Ln();		
		$this->Cell(5, 5);
		$this->Cell(190, 5, substr($direccion_cliente, 60, strlen($direccion_cliente)), '0', '0', 'L');

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

		$this->Ln(2);
		if ($GLOBALS["impreso"] == "S") {
		    $this->SetFont('Courier','B',8);
		    $this->Cell(0, 4, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 0, 1, 'C');
		} else $this->Ln(2);


		if($GLOBALS["doc_afe"] != 0 && ($GLOBALS["documento"] == "NC" || $GLOBALS["documento"] == "ND")) {

		    $sql = "SELECT 
		                DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
		                nro_documento,
		                total
		            FROM salidas
		            WHERE id = " . intval($GLOBALS["doc_afe"]) . "
		            LIMIT 1";

		    $rs = mysqli_query($link, $sql);
		    $rowDoc = mysqli_fetch_array($rs);

		    $docAfectado = $rowDoc["nro_documento"] ?? $GLOBALS["doc_afectado"];
		    $fechaAfectado = $rowDoc["fecha"] ?? "";
		    $montoAfectado = floatval($rowDoc["total"] ?? 0);

		    $this->Ln(1);

		    $this->Cell(1, 4);

		    $this->SetFont('Courier', 'B', 8);
		    $this->Cell(32, 4, 'Doc. Afectado:', 0, 0, 'R');

		    $this->SetFont('Courier', '', 8);
		    $this->Cell(28, 4, $docAfectado, 0, 0, 'L');

		    $this->SetFont('Courier', 'B', 8);
		    $this->Cell(18, 4, 'Fecha:', 0, 0, 'R');

		    $this->SetFont('Courier', '', 8);
		    $this->Cell(22, 4, $fechaAfectado, 0, 0, 'L');

		    $this->SetFont('Courier', 'B', 8);
		    $this->Cell(18, 4, 'Monto:', 0, 0, 'R');

		    $this->SetFont('Courier', '', 8);
		    $this->Cell(28, 4, number_format($montoAfectado, 2, ",", "."), 0, 0, 'L');

		    $this->Ln();
		}
		
		require("../include/desconnect.php");

		$this->SetFont('Courier','B',8);
		$this->Cell(5, 5);
		$this->Cell(45, 5, "ARTICULO", 1, 0, 'L');
		$this->Cell(12, 5, "LOTE", 1, 0, 'C');
		$this->Cell(8, 5, "VENC", 1, 0, 'C');
		$this->Cell(8, 5, "CAN", 1, 0, 'R');
		$this->Cell(10, 5, "IVA %", 1, 0, 'R');
		$this->Cell(18, 5, "PRECIO Bs.", 1, 0, 'R');
		$this->Cell(11, 5, "PREC $", 1, 0, 'R');
		$this->Cell(6, 5, "DP", 1, 0, 'R');
		$this->Cell(6, 5, "DL", 1, 0, 'R');
		$this->Cell(6, 5, "DC", 1, 0, 'R');
		$this->Cell(6, 5, "DT", 1, 0, 'R');
		$this->Cell(18, 5, "P.UNI Bs.", 1, 0, 'R');
		$this->Cell(13, 5, "P.UNI $", 1, 0, 'R');
		$this->Cell(21, 5, "TOTAL Bs.", 1, 0, 'R');
		$this->Cell(13, 5, "TOTAL $", 1, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Ln(5);
	}
	
	// Pie de p?gina
	function Footer()
	{
		// Posici?n: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Courier','I',8);
		// N?mero de p?gina
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function EndReport($id_invoice)
	{
	    $asociado = "";
	    require("../include/connect2.php");
	    $doc = "";

	    // 1. Obtención de datos de la factura y moneda
	    $sql = "SELECT a.alicuota_iva, a.total, a.igtf, a.monto_base_igtf, a.monto_igtf, 
	                   IFNULL(a.nota, '') AS nota, a.moneda, a.id_documento_padre, 
	                   a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.descuento2, a.unidades, 
	                   IFNULL(a.nro_despacho, '') as nro_despacho  
	            FROM salidas a where a.id = '$id_invoice'"; 
	    $rs = mysqli_query($link, $sql);
	    $row = mysqli_fetch_array($rs);
	    
	    $moneda = mb_convert_encoding($row["moneda"], "UTF-8", mb_detect_encoding($row["moneda"]));
	    $tasa_dia = ($row["tasa_dia"] == 0) ? 1 : $row["tasa_dia"];
	    $descuento = floatval($row["descuento"]);
	    $descuento2 = floatval($row["descuento2"]);
	    $igtf_status = $row["igtf"];
	    $monto_base_igtf = floatval($row["monto_base_igtf"]);
	    $monto_igtf = floatval($row["monto_igtf"]);
	    $monto_total_bs_db = floatval($row["total"]);
	    $nota = mb_convert_encoding($row["nota"], "UTF-8", mb_detect_encoding($row["nota"]));
	    $nro_despacho = $row["nro_despacho"];

	    // 2. Totales de artículos
	    $sql = "SELECT SUM(IF(IFNULL(alicuota, 0) = 0, precio_unidad, 0) * cantidad_articulo) AS exento,  
	                   SUM(IF(IFNULL(alicuota, 0) = 0, 0, precio_unidad) * cantidad_articulo) AS gravado,
	                   MAX(IFNULL(alicuota,0)) AS alicuota_act  
	            FROM entradas_salidas WHERE tipo_documento = 'TDCFCV' AND id_documento = '$id_invoice';"; 
	    $rs = mysqli_query($link, $sql);
	    $row_tot = mysqli_fetch_array($rs);
	    
	    $exento = floatval($row_tot["exento"]);
	    $gravado = floatval($row_tot["gravado"]);
	    $xalicuota = floatval($row_tot["alicuota_act"]);

	    // Aplicar descuentos en cascada
	    $exento = ($exento - ($exento * ($descuento/100))) - (($exento - ($exento * ($descuento/100))) * ($descuento2/100));
	    $gravado = ($gravado - ($gravado * ($descuento/100))) - (($gravado - ($gravado * ($descuento/100))) * ($descuento2/100));

$this->SetFont('Courier','',7);

$this->Cell(15, 3);
$this->Cell(0, 3, 'DP: Descuento Producto   DL: Descuento Laboratorio   DC: Descuento Comercial   DT: Descuento Transferencista', 0, 1, 'L');

        if($igtf_status == "S") 
            $this->Ln(215 - $this->GetY());
        else 
            $this->Ln(225 - $this->GetY());

	    // --- SUB-TOTAL ---
	    $this->SetFont('Courier','B',8);
	    $this->Cell(149, 4, "SUB-TOTAL:", 0, 0, 'R');
	    $val_subtotal = $exento + $gravado;
	    // $sub_bs = ($moneda != 'Bs.') ? $val_subtotal * $tasa_dia : ($GLOBALS["moneda_default"] != "Bs." ? $val_subtotal * $tasa_dia : $val_subtotal);
	    $sub_bs = ($moneda != 'Bs.') ? $val_subtotal * $tasa_dia : $val_subtotal;
		// $sub_usd = ($moneda != 'Bs.') ? $val_subtotal : ($GLOBALS["moneda_default"] != "Bs." ? $val_subtotal : $val_subtotal / $tasa_dia);
		$sub_usd = ($moneda != 'Bs.') ? $val_subtotal : $val_subtotal / $tasa_dia;
	    $this->SetFont('Courier','',8);
	    $this->Cell(40, 4, number_format($sub_bs, 2, ",", "."), 0, 0, 'R');
	    $this->Cell(19, 4, number_format($sub_usd, 2, ",", "."), 0, 0, 'R');
	    $this->Ln(4);

	    // --- TOTAL EXENTO ---
	    $this->SetFont('Courier','B',8);
	    $this->Cell(149, 4, "TOTAL EXENTO:", 0, 0, 'R');
	    // $exe_bs = ($moneda != 'Bs.') ? $exento * $tasa_dia : ($GLOBALS["moneda_default"] != "Bs." ? $exento * $tasa_dia : $exento);
	    $exe_bs = ($moneda != 'Bs.') ? $exento * $tasa_dia : $exento;
		// $exe_usd = ($moneda != 'Bs.') ? $exento : ($GLOBALS["moneda_default"] != "Bs." ? $exento : $exento / $tasa_dia);
		$exe_usd = ($moneda != 'Bs.') ? $exento : $exento / $tasa_dia;
	    $this->SetFont('Courier','',8);
	    $this->Cell(40, 4, number_format($exe_bs, 2, ",", "."), 0, 0, 'R');
	    $this->Cell(19, 4, number_format($exe_usd, 2, ",", "."), 0, 0, 'R');
	    $this->Ln(4);

	    $xIVA = $gravado * ($xalicuota / 100);
	    $xTotal = $exento + $gravado + $xIVA;

	    // --- LÍNEA DE IGTF (CUADRO ROJO IZQUIERDO) ---
	    $this->SetFont('Courier', 'BI', 10);
// --- INICIO BLOQUE CONDICIONAL IGTF ---

$alicuota_dinamica = $GLOBALS["alicuota_dinamica"];
if ($igtf_status == "S") {
	$total_con_igtf_bs = ($moneda == "Bs.") ? $xTotal + $monto_igtf : $xTotal * $tasa_dia + $monto_igtf;
	$total_indexado_usd = $total_con_igtf_bs / $tasa_dia;
	$this->Cell(65, 4, "", 0, 0, 'L');
} 
else {
    // Lógica original: Si NO es 'S', se muestra el cálculo estándar de referencia
    $this->SetFont('Courier', 'BI', 10);
    // $monto_referencia_igtf = ($xTotal * $tasa_dia) * ($alicuota_dinamica / 100);
    // $monto_total_referencia = ($xTotal * $tasa_dia) + $monto_referencia_igtf;
    $monto_referencia_igtf = $xTotal * ($alicuota_dinamica / 100);
    $monto_total_referencia = $xTotal + $monto_referencia_igtf;
    
    $this->Cell(5, 4);
	if($moneda != "Bs.") {
        $this->Cell(60, 4, "I.G.T.F. ".number_format($alicuota_dinamica, 0)."%: $moneda " . number_format($monto_total_referencia, 2, ",", "."), 0, 0, 'L');
    } else {
        $this->Cell(60, 4, "I.G.T.F. ".number_format($alicuota_dinamica, 0)."%: USD " . number_format($monto_total_referencia / $tasa_dia, 2, ",", "."), 0, 0, 'L');
    }

}


	    // Tasa de Cambio y Base Imponible
	    $this->SetFont('Courier', 'B', 8);
	    $this->Cell(26, 4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'C');
	    $this->Cell(58, 4, "TOTAL BASE IMPONIBLE:", 0, 0, 'R');
	    // $grav_bs = ($moneda == 'USD') ? $gravado * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $gravado * $tasa_dia : $gravado);
	    $grav_bs = ($moneda == 'USD') ? $gravado * $tasa_dia : $gravado;
	    // $grav_usd = ($moneda == 'USD') ? $gravado : ($GLOBALS["moneda_default"] == "USD" ? $gravado : $gravado / $tasa_dia);
	    $grav_usd = ($moneda == 'USD') ? $gravado : $gravado / $tasa_dia;
	    $this->SetFont('Courier','',8);
	    $this->Cell(40, 4, number_format($grav_bs, 2, ",", "."), 0, 0, 'R');
	    $this->Cell(19, 4, number_format($grav_usd, 2, ",", "."), 0, 0, 'R');
	    $this->Ln(4);

	    // --- IVA Y NOTA BCV ---
	    $this->SetFont('Courier','',6);
	    $this->Cell(5, 4);
	    $this->Cell(86, 4, mb_convert_encoding("Tasa de cambio Publicada por el B.C.V. segun la fecha de emision de esta factura.", "UTF-8"), 0, 0, 'L');
	    $this->SetFont('Courier','B',8);
	    $this->Cell(58,4, "IVA:", 0, 0, 'R');
	    // $iva_bs = ($moneda == 'USD') ? $xIVA * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $xIVA * $tasa_dia : $xIVA);
	    $iva_bs = ($moneda == 'USD') ? $xIVA * $tasa_dia : $xIVA;
	    // $iva_usd = ($moneda == 'USD') ? $xIVA : ($GLOBALS["moneda_default"] == "USD" ? $xIVA : $xIVA / $tasa_dia);
	    $iva_usd = ($moneda == 'USD') ? $xIVA : $xIVA / $tasa_dia;
	    $this->SetFont('Courier','',8);
	    $this->Cell(40, 4, number_format($iva_bs, 2, ",", "."), 0, 0, 'R');
	    $this->Cell(19, 4, number_format($iva_usd, 2, ",", "."), 0, 0, 'R');
	    $this->Ln(4);

	    // --- TOTAL FINAL Y GACETA IGTF ---
	    $this->SetFont('Courier','B',7);
	    $this->Cell(5, 4);
	    $this->Cell(110, 4, mb_convert_encoding("IGTF Sujeto a Pago Recibido (Efectivo $) segun Art 1 GO 42339 17/03/2022.", "UTF-8"), 0, 0, 'R');
	    $this->SetFont('Courier','B',8);
	    $this->Cell(34, 4, "TOTAL Bs./USD $:", 0, 0, 'R');
	    // $total_final_bs = ($moneda == 'USD') ? $xTotal * $tasa_dia : ($GLOBALS["moneda_default"] == "USD" ? $xTotal * $tasa_dia : $xTotal);
	    $total_final_bs = ($moneda == 'USD') ? $xTotal * $tasa_dia : $xTotal;
	    // $total_final_usd = ($moneda == 'USD') ? $xTotal : ($GLOBALS["moneda_default"] == "USD" ? $xTotal : $xTotal / $tasa_dia);
	    $total_final_usd = ($moneda == 'USD') ? $xTotal : $xTotal / $tasa_dia;
	    $this->SetFont('Courier','',8);
	    $this->Cell(40, 4, number_format($total_final_bs, 2, ",", "."), 0, 0, 'R');
	    $this->Cell(19, 4, number_format($total_final_usd, 2, ",", "."), 0, 0, 'R');
	    $this->Ln(4);

// --- CONDICIÓN IGTF ---
        if ($igtf_status == "S") {
            // Línea 1: IGTF 3%
            $this->SetFont('Courier', 'B', 8);
            
            if($moneda == "Bs.") {
	            $this->Cell(149, 4, "I.G.T.F. $alicuota_dinamica% s/Base: " . number_format($monto_base_igtf, 2, ",", ".") . " Bs./USD $:", 0, 0, 'R');
	           	$igtf_bs = ($tasa_dia > 0) ? $monto_igtf : 0;
	            $igtf_usd = $monto_igtf / $tasa_dia;
            } 
            else {
	            $this->Cell(149, 4, "I.G.T.F. $alicuota_dinamica% s/Base: " . number_format($monto_base_igtf / $tasa_dia, 2, ",", ".") . " Bs./USD $:", 0, 0, 'R');
	           	$igtf_bs = $monto_igtf;
	            $igtf_usd = ($tasa_dia > 0) ? $monto_igtf / $tasa_dia : 0;
            }
            
            $this->SetFont('Courier', '', 8);
            $this->Cell(40, 4, number_format($igtf_bs, 2, ",", "."), 0, 0, 'R');
            $this->Cell(19, 4, number_format($igtf_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(4);

            // Línea 2: TOTAL FINAL CON IGTF
            $this->SetFont('Courier', 'B', 9);
            $this->Cell(149, 4, "TOTAL CON IGTF Bs./USD $:", 0, 0, 'R');
            
            if($moneda == "Bs.") {
            	$total_con_igtf_usd = $total_indexado_usd; //$total_con_igtf_bs;
            	// $total_con_igtf_bs = $total_indexado_usd; // ($tasa_dia > 0) ? $total_con_igtf_bs * $tasa_dia : 0;
            } 
            else {
            	$total_con_igtf_usd = $total_indexado_usd; // ($tasa_dia > 0) ? $total_con_igtf_bs / $tasa_dia : 0;
            }

            $this->Cell(40, 4, number_format($total_con_igtf_bs, 2, ",", "."), 0, 0, 'R');
            $this->Cell(19, 4, number_format($total_con_igtf_usd, 2, ",", "."), 0, 0, 'R');
            $this->Ln(4);
        }
//

	    // --- UNIDADES Y NOTA DE INDEXACIÓN (CUADRO ROJO INFERIOR) ---
	    $this->SetFont('Courier','B',8);
	    $this->Cell(30, 4, "Unidades: " . intval($row["unidades"]), 0, 0, 'C');
	    $this->Cell(71, 4, strtoupper($nota), 0, 0, 'R');
	    if(trim($nro_despacho) != "") { $this->Cell(90, 4, "Nro. Despacho: " . $nro_despacho, 0, 0, 'C'); } 
	    $this->Ln(4);
	    
	    $this->Cell(10, 4);
	    $this->SetFont('Courier','B',8);
	    $this->Cell(180, 4, mb_convert_encoding("Esta factura sera indexada a la tasa de cambio expresada por el B.C.V. al momento de recibir el pago.", "UTF-8"), 0, 0, 'L');
	    
	    require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
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
			a.alicuota, 
			a.precio_unidad, 
			a.precio, 
			c.nombre AS fabricante, 
			a.descuento, a.descuento2, a.precio_unidad_sin_desc AS precio_ful 
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.id = a.fabricante 
		WHERE 
			a.id_documento = '$id_invoice' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$sw = false;
$printE = "";
while($row = mysqli_fetch_array($rs))
{
	$printE = floatval($row["alicuota"]) == 0.00 ? " (E)" : "";
	$pdf->SetFont('Courier', '', 7);
	// $pdf->Cell(5, 3);

	$articuloCompleto = trim($row["articulo"]) . $printE;

	$pdf->Cell(5, 3);
	$pdf->Cell(45, 3, substr($articuloCompleto, 0, 30), 0, 0, 'L');

	$pdf->Cell(12, 3, $row["lote"], 0, 0, 'R');
	$pdf->Cell(8, 3, (($row["vencimiento"]=="01/01/1990" or $row["vencimiento"]=="01/90") ? "" : $row["vencimiento"]), 0, 0, 'R');
	$pdf->Cell(8, 3, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
	$pdf->Cell(10, 3, number_format($row["alicuota"], 0, ",", "."), 0, 0, 'R');

$precio_unit_db = floatval($row["precio_unidad"]);

$dp = floatval($row["descuento"]);          // entradas_salidas.descuento
$dl = floatval($row["descuento2"]);         // entradas_salidas.descuento2
$dc = floatval($descuento_comercial);       // salidas.descuento
$dt = floatval($descuento_comercial2);      // salidas.descuento2

$factor_dp = 1 - ($dp / 100);
$factor_dl = 1 - ($dl / 100);

$x_precio_full = $precio_unit_db;

if ($factor_dp > 0) {
    $x_precio_full = $x_precio_full / $factor_dp;
}

if ($factor_dl > 0) {
    $x_precio_full = $x_precio_full / $factor_dl;
}

// Lógica para PRECIO Bs.
// Si la moneda de la factura es USD, multiplicamos por la tasa para mostrar el equivalente en Bs.
if ($moneda != 'Bs.') {
    $val_precio_bs = $x_precio_full * $tasa_dia;
    $val_precio_usd = $x_precio_full; 
} else {
    $val_precio_bs = $x_precio_full;
    $val_precio_usd = $x_precio_full / $tasa_dia;
}

	$pdf->Cell(18, 3, number_format($val_precio_bs, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(11, 3, number_format($val_precio_usd, 2, ",", "."), 0, 0, 'R');


	$pdf->Cell(6, 3, $dp > 0 ? number_format($dp, 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->Cell(6, 3, $dl > 0 ? number_format($dl, 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->Cell(6, 3, $dc > 0 ? number_format($dc, 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->Cell(6, 3, $dt > 0 ? number_format($dt, 0, ",", ".") . "%" : "", 0, 0, 'R');

	$precio_unitario_neto = $x_precio_full;

	$precio_unitario_neto = $precio_unitario_neto - ($precio_unitario_neto * $dp / 100);
	$precio_unitario_neto = $precio_unitario_neto - ($precio_unitario_neto * $dl / 100);
	$precio_unitario_neto = $precio_unitario_neto - ($precio_unitario_neto * $dc / 100);
	$precio_unitario_neto = $precio_unitario_neto - ($precio_unitario_neto * $dt / 100);

	if ($moneda != 'Bs.') {
	    $val_unit_bs = $precio_unitario_neto * $tasa_dia;
	    $val_unit_usd = $precio_unitario_neto;
	} else {
	    $val_unit_bs = $precio_unitario_neto;
	    $val_unit_usd = $precio_unitario_neto / $tasa_dia;
	}

	$pdf->Cell(18, 3, number_format($val_unit_bs, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(13, 3, number_format($val_unit_usd, 2, ",", "."), 0, 0, 'R');
	

// Total de la línea luego de aplicar DP, DL, DC y DT
$precio_linea_total = $precio_unitario_neto * intval($row["cantidad"]);

if ($moneda != 'Bs.') {
    $val_total_bs = $precio_linea_total * $tasa_dia;
    $val_total_usd = $precio_linea_total;
} else {
    $val_total_bs = $precio_linea_total;
    $val_total_usd = $precio_linea_total / $tasa_dia;
	/*
    $val_total_bs = ($GLOBALS["moneda_default"] != "Bs." ? $precio_linea_total * $tasa_dia : $precio_linea_total);
    $val_total_usd = ($GLOBALS["moneda_default"] != "Bs." ? $precio_linea_total : $precio_linea_total / $tasa_dia);
    */
}

	$pdf->Cell(21, 3, number_format($val_total_bs, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(13, 3, number_format($val_total_usd, 2, ",", "."), 0, 0, 'R');
	if (strlen($articuloCompleto) > 30) {
	    $pdf->Ln();
	    $pdf->Cell(5, 3);
	    $pdf->MultiCell(132, 3, substr($articuloCompleto, 30), 0, 'L');
	    $sw = true;
	}

	$pdf->SetFillColor(0, 0, 0);	

	
	
	if($sw == false) $pdf->Ln();
	$sw = false;

	if($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>