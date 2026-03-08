<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["alicuota_dinamica"] = $row["alicuota"];


$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, 
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus, 
			asesor, documento, monto_usd, tasa_dia, IFNULL(entregado, 'N') AS formato_usd    
		FROM salidas where id = '$id_invoice'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$formato_usd = $row["formato_usd"];
if($formato_usd == "S") {
	header("Location: factura_de_venta_formato_en_usd.php?id=$id_invoice");
	die();
}

$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"]=="ANULADO" ? $row["estatus"] .  " - " : "";
$GLOBALS["documento"] = $row["documento"];

if(substr($row["nro_documento"] ?? "", 0, 6) == "1PREFAC") {
	header("Location: factura_de_venta_prefactura.php?id=$id_invoice&tipo=TDCFCV");
	die();
}

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = $row["asesor"];

if(($monto_usd==0 or $tasa_dia==0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) { 
	$sql = "SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs); 
	$tasa = floatval($row["tasa"]);

	if($tasa > 0) {
		$sql = "UPDATE salidas SET monto_usd = (total/$tasa), tasa_dia = $tasa WHERE id = '$id_invoice'"; 
		mysqli_query($link, $sql);
	}
}


$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '$asesor';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);


// $GLOBALS["asesor"] = $row["nombre"];


class PDF extends FPDF
{
	// Cabecera de p?gina
	function Header()
	{
		// Consulto datos de la compa??a 
		require("../include/connect.php");
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
					LPAD(a.id, 8, '0') AS id, a.ci_rif, a.nombre, a.contacto, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf, a.web, 
					a.email2 AS SICM 
				FROM cliente AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$id = $row["id"];
		$rif = $row["ci_rif"];
		// $razon_social = html_entity_decode($row["nombre"]);
		$razon_social = html_entity_decode($row["nombre"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$razon_social = str_ireplace(['&AMP;', '&amp;'], '&', $razon_social);
		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 
		$web = $row["web"]; 
		$SICM = $row["SICM"]; 

		$this->Ln(25);
		$this->SetFont('Arial','',10);
		
		$this->Cell(125, 6);
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Cr?dito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de D?bito: ":"N/A")));
		$this->Cell(30, 6, $tdoc,'0','0','L');
		$this->SetFont('Arial','',10);
		$this->Cell(45, 6, $GLOBALS["nro_documento"],'0','0','R');

		$this->Ln(5);
		$this->SetFont('Arial','B',10);
		$this->Cell(125, 6);
		$this->Cell(30, 6,'Fecha: ','0','0','L');
		$this->SetFont('Arial','',10);
		$this->Cell(45, 6, $ciudad . ", " . $GLOBALS["fecha"], 0, 0, 'R');


		$this->Ln(6);

		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CLIENTE: ",'0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(110, 4, mb_convert_encoding(substr($razon_social, 0, 70), "UTF-8"),'0','0','L');

		/*
		$this->Ln();
		$this->Cell(40, 4);
		$this->Cell(110, 4, utf8_decode(substr(utf8_decode($razon_social), 70, strlen($razon_social))),'0','0','L');
		*/

		$this->Ln();
		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CUENTA N?: ",'0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(110, 4, mb_convert_encoding(substr($id, 0, 55), "UTF-8"),'0','0','L');

		$this->Ln();
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',7);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',7);
		$direccion_cliente = "$direccion_cliente. $ciudad_cliente";
		$this->MultiCell(160, 4, mb_convert_encoding($direccion_cliente, "UTF-8"), '0', 'L');

		
		$this->Ln();
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',7);
		$this->Cell(25,4,'TELEFONOS:','0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(55,4,$telf,'0','0','L');

		$this->SetFont('Arial','B',7);
		$this->Cell(10,4,'R.I.F.: ','0',0,'L');
		$this->SetFont('Arial','',7);
		$this->Cell(25,4,$rif,'0',0,'L');

		$this->SetFont('Arial','B',7);
		$this->Cell(50,4,'CONDICIONES DE PAGO: ','0',0,'L');
		$this->SetFont('Arial','',7);
		$this->Cell(25,4,"Contado",'0',0,'L');


		require("../include/desconnect.php");
		$this->Ln();

		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(25, 4, "CANT", "B", 0, 'C');
		$this->Cell(85, 4, "DESCRIPCION", "B", 0, 'C');
		$this->Cell(30, 4, "PRECIO UNIT", "B", 0, 'R');
		$this->Cell(20, 4, "% ALIC", "B", 0, 'R');
		$this->Cell(30, 4, "TOTAL", "B", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de p?gina
	function Footer()
	{
		// Posici?n: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',7);
		// N?mero de p?gina
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function EndReport($id_invoice)
	{
		//$this->AddPage();
		$asociado = "";
		require("../include/connect.php");
		$doc = "";

		$igtf_porc = floatval($GLOBALS["alicuota_dinamica"]);

		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, 
					a.moneda, 
					a.asesor, a.id_documento_padre, 
					a.monto_usd, a.tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades, a.igtf, a.monto_base_igtf, a.monto_igtf 
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = mb_convert_encoding($row["nota"] ?? "", "UTF-8");
		$moneda = mb_convert_encoding($row["moneda"], "UTF-8");
		$asesor = mb_convert_encoding($row["asesor"] ?? "", "UTF-8");
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];

		$id_documento_padre = $row["id_documento_padre"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];

		$descuento = floatval($row["descuento"]);

		$unidades = $row["unidades"];
		$igtf = $row["igtf"];
		$monto_base_igtf = floatval($row["monto_base_igtf"]); 
		$monto_igtf = floatval($row["monto_igtf"]);

		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total, 
					MAX(IFNULL(alicuota,0)) AS alicuota
				FROM entradas_salidas
				WHERE tipo_documento = 'TDCFCV' AND 
					id_documento = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota"];

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

		$this->Ln(100-$this->GetY());

		$this->Cell(10, 3, "", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		// $this->Cell(130, 3, strtoupper(utf8_decode($nota . " " . $asociado)), 0, 0, 'L');
		$this->MultiCell(130, 3, mb_convert_encoding(strtoupper($nota . " " . $asociado) ?? "", "UTF-8"), 0, 'L');


		$this->SetFont('Arial','B',7);
		$this->Cell(168, 3, "SUB-TOTAL EXENTO:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		$this->Cell(32, 3, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
		$this->Ln(3);

		$this->SetFont('Arial','BI',7);
		$this->Cell(20,3, "", 0, 0, 'R');
		//$this->Cell(50,4, "TOTAL USD: " . number_format($monto_usd, 2, ",", "."), 0, 0, 'L');
		$this->Cell(50,3, "", 0, 0, 'L');
		//$this->Cell(50,4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'L');
		$this->Cell(50,3, "", 0, 0, 'L');
		$this->SetFont('Arial','B',7);
		$this->Cell(48,3, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		$this->Cell(32, 3, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
		$this->Ln(3);

		// Se imprime el descuento si aplica
		if($descuento > 0) {
			$this->SetFont('Arial','BI',7);
			$this->Cell(120,3, "", 0, 0, 'R');
			$this->SetFont('Arial','B',7);
			//$this->Cell(40,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%  (" . number_format($monto_total-$monto_sin_descuento, 2, ",", ".") . "):", 0, 0, 'R');
			$this->Cell(48,3, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			//$this->Cell(40, 4, number_format($monto_total, 2, ",", "."), 0, 0, 'R');
			$this->Cell(32, 3, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
			$this->Ln(4);
		}
		//

		//$this->Cell(110, 4, "Tasa de cambio Publicada por el B.C.V. seg?n la fecha de emisi?n de esta factura.", 0, 0, 'L');
		$this->Cell(110, 3, "", 0, 0, 'L');
		$this->SetFont('Arial','B',7);
		$this->Cell(58,3, "IVA $alicuota % SOBRE  $moneda " . number_format($row["gravado"], 2, ",", ".") . ": ", 0, 0, 'R');
		$this->SetFont('Arial','',7);

		$this->Cell(32, 3, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');

		$this->Ln(3);
		$this->SetFont('Arial','B',7);
		// $this->Cell(30, 4, "Unidades: $unidades", 0, 0, 'R');
		$this->Cell(10, 3, "", 0, 0, 'R');
		//$this->SetFont('Arial','',7);
		//$this->Cell(110, 4, strtoupper($nota), 0, 0, 'L');
		$this->Cell(110, 3, "", 0, 0, 'R');
		$this->Cell(48, 3, "TOTAL $moneda:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		$this->Cell(32, 3, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
		

//////////////
		if($igtf == "S") {
			$igtf = $monto_igtf;
			$totaligtf = $row["total"]+$monto_igtf;

			$this->Ln(3);
			$this->SetFont('Arial','B',7);
			// $this->Cell(30, 4, "Unidades: $unidades", 0, 0, 'R');
			$this->Cell(30, 3, "", 0, 0, 'R');
			// $this->Cell(90, 4, strtoupper($nota), 0, 0, 'R');
			$this->Cell(90, 3, "", 0, 0, 'R');
			$this->Cell(48, 3, "I.G.T.F. " . number_format($igtf_porc, 0, "", "") . "% SOBRE " . number_format($monto_base_igtf, 2, ",", ".") ." $moneda:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			$this->Cell(32, 3, number_format($igtf, 2, ",", "."), 0, 0, 'R');

			$this->Ln(3);
			$this->SetFont('Arial','B',7);
			// $this->Cell(30, 4, "Unidades: $unidades", 0, 0, 'R');
			$this->Cell(30, 3, "", 0, 0, 'R');
			// $this->Cell(90, 4, strtoupper($nota), 0, 0, 'R');
			$this->Cell(90, 3, "", 0, 0, 'R');
			$this->Cell(48, 3, "TOTAL GENERAL $moneda:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			$this->Cell(32, 3, number_format($totaligtf, 2, ",", "."), 0, 0, 'R');
		}
//////////////

		require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,9);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',7);


$sql = "SELECT 
			IFNULL(b.codigo, '') AS codigo, 
			LTRIM(RTRIM(CONCAT(IFNULL(b.codigo, ''), ' ', SUBSTRING(IFNULL(b.principio_activo, ''), 1, 65), ' ', IFNULL(a.lote, '')))) AS articulo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') as vencimiento, 
			a.cantidad_articulo AS cantidad, 
			(SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			IFNULL(a.alicuota, 0.00) AS alicuota, 
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
while($row = mysqli_fetch_array($rs))
{

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(10, 3);
	$pdf->Cell(25, 3, number_format($row["cantidad"], 0, "", ""), "", 0, 'C');

	if(strlen($row["articulo"]) < 63) 
		$pdf->Cell(85, 3, ($row["alicuota"]==0.00?"(E) ":"") . mb_convert_encoding(trim($row["articulo"]), "UTF-8"), 0, 0, 'L');
	else 
		$pdf->Cell(85, 3, ($row["alicuota"]==0.00?"(E) ":"") . substr(mb_convert_encoding(trim($row["articulo"]), "UTF-8"), 0, 63), 0, 0, 'L');



	$pdf->Cell(30, 3, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(20, 3, number_format($row["alicuota"], 2, ",", "."), 0, 0, 'R');
	// $pdf->Cell(10, 3, floatval($row["descuento"])>0 ? number_format($row["descuento"], 2, ",", ".") : "", 0, 0, 'R');
	$pdf->Cell(30, 3, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 63) {
		$pdf->Ln();
		$pdf->Cell(35, 3);
		$pdf->MultiCell(85, 3, substr(trim($row["articulo"]), 63, strlen($row["articulo"])), 0, 'L');
	}
	else $pdf->Ln();

	if($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>