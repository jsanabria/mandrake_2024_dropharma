<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, 
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus, 
			asesor, documento, monto_usd, tasa_dia, ci_rif, nombre, direccion, telefono 
		FROM salidas where id = '$id_invoice'";
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

$GLOBALS["cliente_ci_rif"] = $row["ci_rif"];
$GLOBALS["cliente_nombre"] = $row["nombre"];
$GLOBALS["cliente_direccion"] = $row["direccion"];
$GLOBALS["cliente_telefono"] = $row["telefono"];

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
if($row = mysqli_fetch_array($rs)) $GLOBALS["asesor"] = $row["nombre"];
else $GLOBALS["asesor"] = "";


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
		$GLOBALS["cta_cia"] = mb_convert_encoding("Realizar depósito y/o transferencia a nombre de " . $row["titular"] . " en cta " . $row["tipo"] . " # " . $row["numero"] . " Banco " . $row["banco"], "ISO-8859-1");


		$sql = "SELECT 
					a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
					a.direccion, a.telefono1, a.email1, logo  
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];
		$logo =  $row["logo"];


		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, a.contacto, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf, a.web 
				FROM cliente AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$rif = $row["ci_rif"]; 
		$razon_social = $row["nombre"];
		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 
		$web = $row["web"]; 

		$this->Ln(20);
		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}

		$this->SetFont('Courier','',8);
		$this->Cell(10, 5);
		$this->Cell(50, 5);
		//$this->Cell(100, 6, $GLOBALS["estatus"] . "CIUDAD: $ciudad,",0,0,'R');
		/*$this->Cell(10, 6, substr(($GLOBALS["fecha"]),0,2),0,0,'L');
		$this->Cell(10, 6, substr(($GLOBALS["fecha"]),3,2),0,0,'C');
		$this->Cell(20, 6, substr(($GLOBALS["fecha"]),6,4),0,0,'C');*/
		$this->Ln(18);

		$this->Cell(10, 3);
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 3,"RAZON SOCIAL: ",'0','0','L');
		$this->SetFont('Courier','',8);
		$this->Cell(110, 3, mb_convert_encoding(substr($razon_social . " - " . $GLOBALS["cliente_nombre"], 0, 55), "ISO-8859-1"),'0','0','L');
		$this->SetFont('Courier','B',8);
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Crédito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de Débito: ":"N/A")));
		$this->Cell(20, 3, $tdoc,'0','0','R');
		$this->SetFont('Courier','',8);
		$this->Cell(30, 3, $GLOBALS["nro_documento"],'0','0','L');
	

		$this->Ln();
		$this->SetFont('Courier','',8);
		$this->Cell(40, 3);
		$this->Cell(110, 3, mb_convert_encoding(substr($razon_social, 55, strlen($razon_social)), "ISO-8859-1"),'0','0','L');
		$this->Ln();

		$this->Cell(10, 4);
		$this->SetFont('Courier','B',8);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->SetFont('Courier','',8);
		$direccion_cliente = $GLOBALS["cliente_direccion"];
		$this->Cell(110, 4, substr((isset($direccion_cliente) ? $direccion_cliente : ""), 0, 60), '0', '0', 'L');
		$this->SetFont('Courier','B',8);
		$this->Cell(20, 4,'Fecha: ','0','0','R');
		$this->SetFont('Courier','',8);
		$this->Cell(30, 4, $GLOBALS["fecha"], 0, 0, 'L');

		$this->Ln();		
		$this->Cell(10, 5);
		$this->Cell(190, 5, substr((isset($direccion_cliente) ? $direccion_cliente : ""), 60, strlen((isset($direccion_cliente) ? $direccion_cliente : ""))), '0', '0', 'L');

		$this->Ln(6);
		$this->Cell(10, 4);
		$this->SetFont('Courier','B',8);
		$this->Cell(15,4,'R.I.F.: ','0',0,'L');
		$this->SetFont('Courier','',8);
		$this->Cell(25,4,$GLOBALS["cliente_ci_rif"],'0',0,'L');
		$this->SetFont('Courier','B',8);
		$this->Cell(10,4,'Telf:','0','0','L');
		$this->SetFont('Courier','',8);
		$this->Cell(55,4,$GLOBALS["cliente_telefono"],'0','0','L');

		/*
		$this->SetFont('Courier','B',8);
		$this->Cell(10,4,'WEB:','0','0','L');
		$this->SetFont('Courier','',8);
		$this->Cell(20,4,$web,'0','0','L');

		$this->SetFont('Courier','B',8);
		$this->Cell(15,4,'Asesor:','0','0','L');
		$this->SetFont('Courier','',8);
		$this->Cell(45,4,$GLOBALS["asesor"],'0','0','L');
		*/

		require("../include/desconnect.php");
		$this->Ln();

		$this->SetFont('Courier','B',8);
		$this->Cell(10, 5);
		$this->Cell(18, 5, "LAB", 1, 0, 'L');
		$this->Cell(60, 5, "ARTICULO", 1, 0, 'L');
		$this->Cell(18, 5, "LOTE", 1, 0, 'C');
		$this->Cell(18, 5, "VENC", 1, 0, 'C');
		//$this->Cell(20, 5, "MED./CAN.", 1, 0, 'L');
		$this->Cell(10, 5, "CAN.", 1, 0, 'R');
		$this->Cell(10, 5, "IVA %", 1, 0, 'R');
		$this->Cell(25, 5, "PRECIO", 1, 0, 'R');
		$this->Cell(29, 5, "TOTAL", 1, 0, 'R');
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
		$this->Ln();
		$this->SetFont('Courier','B',8);
		$this->Cell(50, 4);
		$this->MultiCell(120, 4, mb_convert_encoding($GLOBALS["cta_cia"], "ISO-8859-1"), 0, 'C');
		$this->SetFont('Courier','',8);
		

		$asociado = "";
		require("../include/connect2.php");
		$doc = "";

		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, 
					a.moneda, 
					a.asesor, a.id_documento_padre, 
					a.monto_sin_descuento AS monto_usd, a.tasa_dia, a.descuento, a.monto_sin_descuento, a.tipo_documento  
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = mb_convert_encoding((isset($row["nota"]) ? $row["nota"] : ""), "ISO-8859-1");
		$moneda = mb_convert_encoding((isset($row["moneda"]) ? $row["moneda"] : ""), "ISO-8859-1");
		$asesor = mb_convert_encoding((isset($row["asesor"]) ? $row["asesor"] : ""), "ISO-8859-1");
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];
		$tipo_documento = $row["tipo_documento"];

		$id_documento_padre = $row["id_documento_padre"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];

		$descuento = floatval($row["descuento"]);

		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
				FROM entradas_salidas
				WHERE tipo_documento = '$tipo_documento' AND 
					id_documento = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

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

		$this->Ln(230-$this->GetY());

		$this->SetFont('Courier','B',8);
		$this->Cell(160, 4, $asociado . " " . "SUB-TOTAL EXENTO:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format((isset($row["exento"]) ? $row["exento"] : 0), 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		$this->SetFont('Courier','BI',10);
		$this->Cell(20,4, "", 0, 0, 'R');
		$this->Cell(50,4, "TOTAL USD: " . number_format((isset($monto_usd) ? $monto_usd : 0), 2, ",", "."), 0, 0, 'L');
		$this->Cell(50,4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'L');
		$this->SetFont('Courier','B',8);
		$this->Cell(40,4, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format((isset($row["gravado"]) ? $row["gravado"] : 0), 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		// Se imprime el descuento si aplica
		if($descuento > 0) {
			$this->SetFont('Courier','BI',10);
			$this->Cell(120,4, "", 0, 0, 'R');
			$this->SetFont('Courier','B',8);
			//$this->Cell(40,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%  (" . number_format($monto_total-$monto_sin_descuento, 2, ",", ".") . "):", 0, 0, 'R');
			$this->Cell(40,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			//$this->Cell(40, 4, number_format($monto_total, 2, ",", "."), 0, 0, 'R');
			$this->Cell(40, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
			$this->Ln(5);
		}
		//

		if(floatval($alicuota) > 0) {
			$this->SetFont('Courier','B',8);
			$this->Cell(80, 4, "% IVA:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
			$this->Cell(40, 4, number_format($alicuota, 0, ",", "."), 0, 0, 'L');
			$this->Cell(40,4, number_format((floatval($row["gravado"]) - (floatval($row["gravado"]) * ($descuento/100))), 2, ",", "."), 0, 0, 'R');
		}
		else { 
			$this->SetFont('Courier','B',8);
			$this->Cell(160,4, "IVA:", 0, 0, 'R');
			$this->SetFont('Courier','',8);
		}
		$this->Cell(40, 4, number_format((isset($row["iva"]) ? $row["iva"] : 0), 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);
		$this->SetFont('Courier','B',8);
		$this->Cell(120, 4, strtoupper($nota), 0, 0, 'R');
		$this->Cell(40, 4, "TOTAL $moneda:", 0, 0, 'R');
		$this->SetFont('Courier','',8);
		$this->Cell(40, 4, number_format((isset($row["total"]) ? $row["total"] : 0), 2, ",", "."), 0, 0, 'R');
		
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
			LTRIM(RTRIM(CONCAT(IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, '')))) AS articulo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') as vencimiento, 
			a.cantidad_articulo AS cantidad, 
			(SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			a.alicuota, 
			a.precio_unidad, 
			a.precio, 
			c.nombre AS fabricante 
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
	$pdf->SetFont('Courier', '', 8);
	$pdf->Cell(10, 3);
	$pdf->Cell(18, 3, $row["fabricante"], 0, 0, 'L');
	if(strlen($row["articulo"]) < 34) 
		$pdf->Cell(60, 3, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(60, 3, substr(trim($row["articulo"]), 0, 34), 0, 0, 'L');

	$pdf->Cell(18, 3, $row["lote"], 0, 0, 'R');
	$pdf->Cell(18, 3, ($row["vencimiento"] == "01/01/1990" ? "" : $row["vencimiento"]), 0, 0, 'R');
	$pdf->Cell(10, 3, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
	//$pdf->Cell(20, 4, $row["unidad_medida"] . " " . $row["cantidad"], 0, 0, 'L');
	$pdf->Cell(10, 3, number_format($row["alicuota"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(25, 3, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(29, 3, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 34) {
		$pdf->Ln();
		$pdf->Cell(30, 4);
		$pdf->MultiCell(60, 3, substr(trim($row["articulo"]), 34, strlen($row["articulo"])), 0, 'L');
	}
	else $pdf->Ln();

	if($pdf->GetY() > 200) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>