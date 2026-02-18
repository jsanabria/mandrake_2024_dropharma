<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";


$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"];

if(trim($_COOKIE["strcon"]) != "drophqsc_medrika") $GLOBALS["moneda_default"] = "Bs.";


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
			date_format(DATE_ADD(fecha,INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc, doc_afectado, descuento, descuento2 
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
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
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
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Crédito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de Débito: ":"N/A")));
		$this->Cell(30, 3, $tdoc,'0','0','R');
		$this->SetFont('Courier','',8);
		$this->Cell(30, 3, $GLOBALS["nro_documento"],'0','0','L');

		$this->SetFont('Courier','',8);
		$this->Ln();
		
		/*
		if(trim($GLOBALS["doc_afectado"]) != "" and ($GLOBALS["documento"]=="NC" or $GLOBALS["documento"]=="ND")) {
			$this->Cell(150, 3);
			$this->SetFont('Courier','B',8);
			$this->Cell(30, 3,'Documento Afectado: ','0','0','R');
			$this->SetFont('Courier','',8);
			$this->Cell(30, 3, $GLOBALS["doc_afectado"], 0, 0, 'L');
		} 
		*/
		

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

		require("../include/desconnect.php");
		$this->Ln();

		$this->SetFont('Courier','B',8);
		$this->Cell(5, 5);
		$this->Cell(16, 5, "LAB", 1, 0, 'L');
		$this->Cell(45, 5, "ARTICULO", 1, 0, 'L');
		$this->Cell(16, 5, "LOTE", 1, 0, 'C');
		$this->Cell(10, 5, "VENC", 1, 0, 'C');
		//$this->Cell(20, 5, "MED./CAN.", 1, 0, 'L');
		$this->Cell(8, 5, "CAN", 1, 0, 'R');
		$this->Cell(10, 5, "IVA %", 1, 0, 'R');
		$this->Cell(22, 5, "PRECIO Bs.", 1, 0, 'R');
		$this->Cell(11, 5, "PREC $", 1, 0, 'R');
		$this->Cell(8, 5, "DES1", 1, 0, 'R');
		$this->Cell(8, 5, "DES2", 1, 0, 'R');
		$this->Cell(8, 5, "DES3", 1, 0, 'R');
		$this->Cell(23, 5, "TOTAL Bs.", 1, 0, 'R');
		$this->Cell(15, 5, "TOTAL $", 1, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Ln(5);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Courier','I',8);
		// Número de página
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function EndReport($id_invoice)
	{
		//$this->AddPage();
		$asociado = "";
		require("../include/connect2.php");
		$doc = "";

		$sql = "SELECT 
		    DISTINCT alicuota 
		  FROM 
		    entradas_salidas 
		  WHERE 
		    id_documento = '$id_invoice' AND tipo_documento = 'TDCFCV' ORDER BY 1 DESC LIMIT 0, 1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$xalicuota = floatval($row["alicuota"]);


		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					IFNULL(a.nota, '') AS nota, IFNULL(a.doc_afectado, '') AS doc_afectado,  
					a.moneda, 
					IFNULL(a.asesor, '') as asesor, a.id_documento_padre, 
					a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.descuento2, a.monto_sin_descuento, a.unidades, 
					IFNULL(a.nro_despacho, '') as  nro_despacho  
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = mb_convert_encoding($row["nota"], "UTF-8", mb_detect_encoding($row["nota"]));
		if(trim($row["doc_afectado"]) != "" and ($GLOBALS["documento"]=="NC" or $GLOBALS["documento"]=="ND")) $nota = "Doc. Afectado: " . trim($row["doc_afectado"]) . " " . $nota;
		$doc_afectado = mb_convert_encoding($row["doc_afectado"], "UTF-8", mb_detect_encoding($row["doc_afectado"]));
		$moneda = mb_convert_encoding($row["moneda"], "UTF-8", mb_detect_encoding($row["moneda"]));
		$asesor = mb_convert_encoding($row["asesor"], "UTF-8", mb_detect_encoding($row["asesor"]));
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];

		$id_documento_padre = $row["id_documento_padre"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];
		if($tasa_dia == 0) $tasa_dia = 1;

		$descuento = floatval($row["descuento"]);
		$descuento2 = floatval($row["descuento2"]);
		$descuento3 = 0.00;

		$unidades = $row["unidades"];
		$nro_despacho = $row["nro_despacho"];


		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota, 0) = 0, precio_unidad, 0) * cantidad_articulo) AS exento,  
					SUM(IF(IFNULL(alicuota, 0) = 0, 0, precio_unidad) * cantidad_articulo) AS gravado, 
					MAX(IFNULL(alicuota,0)) AS alicuota, 
					SUM(ABS(cantidad_movimiento)) AS cantidad  
				FROM entradas_salidas
				WHERE tipo_documento = 'TDCFCV' AND 
					id_documento = '$id_invoice';"; 

		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$exento = floatval($row["exento"]);
		$gravado = floatval($row["gravado"]);
		$unidades = intval($row["cantidad"]);

		$exento = $exento - ($exento * ($descuento/100));
		$gravado = $gravado - ($gravado * ($descuento/100));

		$exento = $exento - ($exento * ($descuento2/100));
		$gravado = $gravado - ($gravado * ($descuento2/100));

		$alicuota = floatval($row["alicuota"]);
		$iva = $gravado*($alicuota/100);

		$sql2 = "SELECT b.descripcion, a.nro_documento
				FROM salidas AS a JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
				 where a.id = '$id_documento_padre';";
		$rs2 = mysqli_query($link, $sql2);
		$sw = false;
		while($row2 = mysqli_fetch_array($rs2)) {
			$doc .= " #" . $row2["nro_documento"];
			$tdoc = $row2["descripcion"];
			$sw = true;
		}

		if($sw) $asociado = "Documento(s) Asociado(s): $tdoc $doc / ";

		$this->Ln(225-$this->GetY());

		

		$this->SetFont('Courier','B',8);
		if($GLOBALS["documento"]=="ND") { 
			$sql = "SELECT nro_documento, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha, tasa_dia, total FROM salidas WHERE nro_documento = '" . $GLOBALS["doc_afectado"] . "';";
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) 
				$this->Cell(100, 4, "Afect: " . $row["nro_documento"] . " Tasa Emision: " . $row["tasa_dia"] . " Fec: " . $row["fecha"] . " Monto: " . number_format($row["total"], 2, ",", ".") . "", 0, 0, 'L');
			else 
				$this->Cell(100, 4, "", 0, 0, 'L');
			$this->Cell(49, 4, "SUB-TOTAL:", 0, 0, 'R');
		} 
		else 
			$this->Cell(149, 4, "SUB-TOTAL:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? (($exento+$gravado)*$tasa_dia) : ($exento+$gravado), 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? ($exento+$gravado) : (($exento+$gravado)/$tasa_dia), 2, ",", "."), 0, 0, 'R');
		$this->Ln(4);


		// Se imprime el descuento si aplica
		if($descuento3 > 0) { 
			$descuento = $descuento3;
			$this->SetFont('Courier','BI',10);
			$this->Cell(101,4, "", 0, 0, 'R');
			$this->SetFont('Courier','B',8);

			$this->Cell(48,4, "Descuento " . number_format($descuento, 2, ",", ".") . "% Adicional:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			//$this->Cell(40, 4, number_format($monto_total, 2, ",", "."), 0, 0, 'R');
			$monto_descuento = (-1) * (($exento*($descuento/100)) + ($gravado*($descuento/100)));
			$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $monto_descuento*$tasa_dia : $monto_descuento, 2, ",", "."), 0, 0, 'R');
			$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $monto_descuento : $monto_descuento/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);

			$this->SetFont('Courier','B',8);
			$this->Cell(149, 4, $asociado . " " . "TOTAL EXENTO:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? ($exento-($exento*($descuento/100)))*$tasa_dia : $exento-($exento*($descuento/100)), 2, ",", "."), 0, 0, 'R');
			$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $exento-($exento*($descuento/100)) : ($exento-($exento*($descuento/100)))/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);

			$this->SetFont('Courier','BI',10);
			$this->Cell(10,4, "", 0, 0, 'R');
			$this->Cell(51,4, "I.G.T.F. 3%: USD " . number_format($GLOBALS["moneda_default"]=="USD" ? ($monto_usd*$tasa_dia)+(($monto_usd*$tasa_dia)*(3/100)) : $monto_usd+($monto_usd*(3/100)), 2, ",", "."), 0, 0, 'L');
			$this->Cell(40,4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'C');
			$this->SetFont('Courier','B',8);

			$this->Cell(48,4, "TOTAL BASE IMPONIBLE:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? ($gravado-($gravado*($descuento/100)))*$tasa_dia : $gravado-($gravado*($descuento/100)), 2, ",", "."), 0, 0, 'R');
			$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $gravado-($gravado*($descuento/100)) : ($gravado-($gravado*($descuento/100)))/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);
		} 
		else {
			$this->SetFont('Courier','B',8);
			$this->Cell(149, 4, $asociado . " " . "TOTAL EXENTO:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $exento*$tasa_dia : $exento, 2, ",", "."), 0, 0, 'R');
			$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $exento : $exento/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);

			$this->SetFont('Courier','BI',10);
			$this->Cell(10,4, "", 0, 0, 'R');
			$this->Cell(51,4, "I.G.T.F. 3%: USD " . number_format($GLOBALS["moneda_default"]=="USD" ? ($monto_usd*$tasa_dia)+(($monto_usd*$tasa_dia)*(3/100)) : $monto_usd+($monto_usd*(3/100)), 2, ",", "."), 0, 0, 'L');
			$this->Cell(40,4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'C');
			$this->SetFont('Courier','B',8);

			$this->Cell(48,4, "TOTAL BASE IMPONIBLE:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $gravado*$tasa_dia : $gravado, 2, ",", "."), 0, 0, 'R');
			$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $gravado : $gravado/$tasa_dia, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);
		}
		//

		$this->SetFont('Courier','',6);
		$this->Cell(91, 4, "Tasa de cambio Publicada por el B.C.V. según la fecha de emisión de esta factura.", 0, 0, 'L');
		/*if(floatval($alicuota) > 0) {
			$this->SetFont('Courier','B',8);
			$this->Cell(10, 4, "% IVA:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(10, 4, number_format($alicuota, 0, ",", "."), 0, 0, 'L');
			$this->Cell(30,4, number_format((floatval($row["gravado"]) - (floatval($row["gravado"]) * ($descuento/100))), 2, ",", "."), 0, 0, 'R');
		}
		else { */
			$this->SetFont('Courier','B',8);
			$this->Cell(58,4, "IVA:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
		//}
		/*
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["iva"]*$tasa_dia : $row["iva"], 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["iva"] : $row["iva"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		*/
		$xIVA = ($gravado-($gravado*($descuento3/100)))*($xalicuota/100);
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $xIVA*$tasa_dia : $xIVA, 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $xIVA : $xIVA/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		$this->Ln(4);
		$this->SetFont('Courier','B',7);
		$this->Cell(5, 4);
		$this->Cell(110, 4, "IGTF Sujeto a Pago Recibido (Efectivo $) según Art 1 GO 42339 17/03/2022.", 0, 0, 'R');
		$this->SetFont('Courier','B',8);
		//$this->Cell(34, 4, "TOTAL $moneda/USD $:", 0, 0, 'R');
		$this->Cell(34, 4, "TOTAL Bs./USD $:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		/*
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["total"]*$tasa_dia : $row["total"], 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["total"] : $row["total"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		*/
		$xTotal = ($exento-($exento*($descuento3/100))) + ($gravado-($gravado*($descuento3/100))) + $xIVA;
		/*
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["total"]*$tasa_dia : $row["total"], 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $row["total"] : $row["total"]/$tasa_dia, 2, ",", "."), 0, 0, 'R');
		*/
		$this->Cell(40, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $xTotal*$tasa_dia : $xTotal, 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($GLOBALS["moneda_default"]=="USD" ? $xTotal : $xTotal/$tasa_dia, 2, ",", "."), 0, 0, 'R');

		/*
		$IGFT_por_bs = floatval($GLOBALS["moneda_default"]=="USD" ? $row["total"]*$tasa_dia : $row["total"]) * (3/100);
		$IGFT_bs = floatval($GLOBALS["moneda_default"]=="USD" ? $row["total"]*$tasa_dia : $row["total"]) + $IGFT_por_bs;
		$IGFT_por_usd = floatval($GLOBALS["moneda_default"]=="USD" ? $row["total"] : $row["total"]/$tasa_dia) * (3/100);
		$IGFT_usd = floatval($GLOBALS["moneda_default"]=="USD" ? $row["total"] : $row["total"]/$tasa_dia) + $IGFT_por_usd;
		*/
		$IGFT_por_bs = floatval($GLOBALS["moneda_default"]=="USD" ? $xTotal*$tasa_dia : $xTotal) * (3/100);
		$IGFT_bs = floatval($GLOBALS["moneda_default"]=="USD" ? $xTotal*$tasa_dia : $xTotal) + $IGFT_por_bs;
		$IGFT_por_usd = floatval($GLOBALS["moneda_default"]=="USD" ? $xTotal : $xTotal/$tasa_dia) * (3/100);
		$IGFT_usd = floatval($GLOBALS["moneda_default"]=="USD" ? $xTotal : $xTotal/$tasa_dia) + $IGFT_por_usd;
		
/*		
		$this->SetFont('Courier','B',8);
		$this->Cell(149, 4, "I.G.T.F. 3%:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format($IGFT_por_bs, 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($IGFT_por_usd, 2, ",", "."), 0, 0, 'R');

		$this->ln();
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 4, "Unidades:$unidades", 0, 0, 'C');
		$this->Cell(71, 4, strtoupper($nota), 0, 0, 'R');
		$this->Cell(48, 4, "TOTAL $moneda/USD $:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format($IGFT_bs, 2, ",", "."), 0, 0, 'R');
		$this->Cell(19, 4, number_format($IGFT_usd, 2, ",", "."), 0, 0, 'R');
*/
		$this->ln();
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 4, "Unidades:$unidades", 0, 0, 'C');
		$this->Cell(71, 4, strtoupper($nota), 0, 0, 'R');
		if(trim($nro_despacho) != "") { $this->Cell(90, 4, "Nro. Despacho Psicotrópico: " . $nro_despacho, 0, 0, 'C'); } 
		$this->Ln();
		$this->Cell(10, 4);
		$this->Cell(100, 4, "Esta factura será indexada a la tasa de cambio expresada por el B.C.V. al momento de recibir el pago.", 0, 0, 'L');
		
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
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
			a.descuento, a.precio_unidad_sin_desc AS precio_ful 
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
	$pdf->Cell(5, 3);
	$pdf->Cell(16, 3, substr($row["fabricante"], 0, 10), 0, 0, 'L');
	if(strlen($row["articulo"]) < 28) 
		$pdf->Cell(45, 3, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(45, 3, substr(trim($row["articulo"]), 0, 28), 0, 0, 'L');

	$pdf->Cell(16, 3, $row["lote"], 0, 0, 'R');
	$pdf->Cell(10, 3, (($row["vencimiento"]=="01/01/1990" or $row["vencimiento"]=="01/90") ? "" : $row["vencimiento"]), 0, 0, 'R');
	$pdf->Cell(8, 3, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
	//$pdf->Cell(20, 4, $row["unidad_medida"] . " " . $row["cantidad"], 0, 0, 'L');
	$pdf->Cell(10, 3, number_format($row["alicuota"], 0, ",", "."), 0, 0, 'R');
	// $pdf->Cell(22, 3, $printE . number_format(($GLOBALS["moneda_default"]=="USD" ? ($row["precio_ful"]*$tasa_dia): $row["precio_ful"]) , 2, ",", "."), 0, 0, 'R');
	// $precio_full = (floatval($row["precio_ful"])-(floatval($row["precio_ful"])*floatval($row["descuento"])/100));
	$precio_full = floatval($row["precio_unidad"]);
	$x_precio_full = $precio_full/(1-(floatval($row["descuento"])/100));
	// $x_precio_full = floatval($row["precio_ful"]);
	$pdf->Cell(22, 3, $printE . number_format(($GLOBALS["moneda_default"]=="USD" ? $x_precio_full*$tasa_dia : $x_precio_full) , 2, ",", "."), 0, 0, 'R');
	// 
	$pdf->Cell(11, 3, number_format(($GLOBALS["moneda_default"]=="USD" ? $x_precio_full : $x_precio_full/$tasa_dia), 2, ",", "."), 0, 0, 'R');
	// $pdf->Cell(8, 3, floatval($row["descuento"])>0 ? number_format($row["descuento"], 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->Cell(8, 3, floatval($row["descuento"])>0 ? number_format($row["descuento"], 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->SetFillColor(255, 0, 0);
	$pdf->Cell(8, 3, floatval($descuento_comercial)>0 ? number_format($descuento_comercial, 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->SetFillColor(255, 0, 0);
	$pdf->Cell(8, 3, floatval($descuento_comercial2)>0 ? number_format($descuento_comercial2, 0, ",", ".") . "%" : "", 0, 0, 'R');
	$pdf->SetFillColor(255, 0, 0);
	// $precio = (floatval($row["precio"])-(floatval($row["precio"])*floatval($row["descuento"])/100));
	// $precio = ($precio_full-($precio_full*$descuento_comercial/100))*(intval($row["cantidad"]));
	$precio = $precio_full-($precio_full*$descuento_comercial/100);
	$precio = $precio-($precio*$descuento_comercial2/100);
	$precio = $precio*(intval($row["cantidad"])); 
	$pdf->Cell(23, 3, number_format(($GLOBALS["moneda_default"]=="USD" ? $precio * $tasa_dia : $precio), 2, ",", "."), 0, 0, 'R', ($row["precio"] == 0 ? true: false));
	$pdf->Cell(15, 3, number_format(($GLOBALS["moneda_default"]=="USD" ? $precio  : $precio /$tasa_dia), 2, ",", "."), 0, 0, 'R', ($row["precio"] == 0 ? true: false));
	$pdf->SetFillColor(0, 0, 0);	

	if(trim(substr($row["articulo"], 28, 28)) != "") {
		if(strlen($row["articulo"]) >= 28) {
			$pdf->Ln();
			$pdf->Cell(21, 4);
			$pdf->MultiCell(47, 3, substr(trim($row["articulo"]), 28, 28), 0, 'L');
			$sw = true;
		}
	}

	if(trim(substr($row["articulo"], 56, strlen($row["articulo"]))) != "") {
		if(strlen($row["articulo"]) >= 56) {
			//$pdf->Ln();
			$pdf->Cell(21, 4);
			$pdf->MultiCell(47, 3, substr(trim($row["articulo"]), 56, strlen(trim($row["articulo"]))), 0, 'L');
			$sw = true;
		}
	}
	
	if($sw == false) $pdf->Ln();
	$sw = false;

	if($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>