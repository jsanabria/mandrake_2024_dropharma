<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$xfecha = isset($_REQUEST["xfecha"])?$_REQUEST["xfecha"]:"0";
$yfecha = isset($_REQUEST["yfecha"])?$_REQUEST["yfecha"]:"0";

$f = explode("-", $xfecha);
$fecdesde = $f["2"] . "/" . $f["1"] . "/" . $f["0"];
$f = explode("-", $yfecha);
$fechasta = $f["2"] . "/" . $f["1"] . "/" . $f["0"];

$GLOBALS["titulo"] = "Libro de Ventas";
$GLOBALS["subtitulo"] = "Desde $fecdesde Hasta $fechasta";


class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../include/connect.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia =  $row["id"];


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

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(5);
		$this->SetFont('Arial','',6);
		$this->Cell(270, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(270, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(2);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(270, 6, mb_convert_encoding($GLOBALS["titulo"], 'ISO-8859-1', 'UTF-8'),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(270, 6, $GLOBALS["subtitulo"],0,0,'C');
		$this->SetFont('Arial','',6);		


		$this->Ln(8);
		

		require("../include/desconnect.php");

		$this->Cell(5, 4);
		$this->Cell(5, 4, "", "LTR", 0, 'R');
		$this->Cell(12, 4, "", "LTR", 0, 'L');
		$this->Cell(15, 4, "", "LTR", 0, 'L');
		$this->Cell(47, 4, "", "LTR", 0, 'L');
		$this->Cell(15, 4, "", "LTR", 0, 'C');
		$this->Cell(13, 4, "NRO", "LTR", 0, 'C');
		$this->Cell(13, 4, "NRO", "LTR", 0, 'C');
		$this->Cell(13, 4, "NOTA", "LTR", 0, 'C');
		$this->Cell(13, 4, "NOTA", "LTR", 0, 'C');
		$this->Cell(13, 4, "NRO", "LTR", 0, 'C');
		$this->Cell(17, 4, "TOTAL", "LTR", 0, 'R');
		$this->Cell(17, 4, "TOTAL", "LTR", 0, 'R');
		$this->Cell(17, 4, "TOTAL", "LTR", 0, 'R');
		$this->Cell(75, 4, "", 1, 0, 'C');
		$this->Ln(4);

		$this->Cell(5, 4);
		$this->Cell(5, 4, "ID", "LBR", 0, 'L');
		$this->Cell(12, 4, "FECHA", "LBR", 0, 'L');
		$this->Cell(15, 4, "RIF", "LBR", 0, 'L');
		$this->Cell(47, 4, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(15, 4, "COMPROBAN", "LBR", 0, 'C');
		$this->Cell(13, 4, "FACT", "LBR", 0, 'C');
		$this->Cell(13, 4, "CTRL", "LBR", 0, 'C');
		$this->Cell(13, 4, "DEBITO", "LBR", 0, 'C');
		$this->Cell(13, 4, "CREDITO", "LBR", 0, 'C');
		$this->Cell(13, 4, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(17, 4, "VENTAS", "LBR", 0, 'R');
		$this->Cell(17, 4, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(17, 4, "BASE", "LBR", 0, 'R');
		$this->Cell(10, 4, "ALIC", "LBR", 0, 'R');
		$this->Cell(17, 4, "IVA", "LBR", 0, 'R');
		$this->Cell(17, 4, "IVA RET", "LBR", 0, 'R');
		$this->Cell(15, 4, "ASESOR", "LBR", 0, 'R');
		$this->Cell(8, 4, "DES 1", "LBR", 0, 'R');
		$this->Cell(8, 4, "DES 2", "LBR", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',6);
		// Nϊmero de pαgina
		$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_ivaret)
	{
		//$this->AddPage();
		//require("../include/connect.php");
		$this->SetFont('Arial','B',6);
		$this->Ln();
		$this->Cell(164, 4, "", 0, 0, 'R');
		$this->Cell(17, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(17, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(17, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(10, 4, "", 0, 0, 'R');
		$this->Cell(17, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		$this->Cell(17, 4, number_format($_ivaret, 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		$this->Cell(250, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');
		$this->Ln();
		require("../include/desconnect.php");
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'A4');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',6);

$sql = "SELECT 
				a.id, 
				date_format(a.fecha, '%d/%m/%Y') AS fecha, 
				b.`ci_rif`, 
				b.`nombre` AS cliente, 
				'' AS comprobante, 
				a.`documento`, 
				IF(a.documento = 'FC', REPLACE(a.nro_documento, 'FACT-', ''), '') AS nro_factura, 
				a.`nro_control`, 
				IF(a.documento = 'ND', REPLACE(a.nro_documento, 'ND-', ''), '') AS nota_debito, 
				IF(a.documento = 'NC', REPLACE(a.nro_documento, 'NC-', ''), '') AS nota_credito, 
				REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 
				a.`total` AS total_ventas, 
				ROUND((SELECT
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * (IFNULL(a.descuento, 0)/100)), 0)) AS exento_2 
				FROM entradas_salidas
				WHERE tipo_documento = a.tipo_documento AND 
					id_documento = a.id), 2) AS no_gravadas, 
				ROUND((SELECT
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (IFNULL(a.descuento, 0)/100)))) AS gravado_2 
				FROM entradas_salidas
				WHERE tipo_documento = a.tipo_documento AND 
					id_documento = a.id), 2) AS base, 
				(SELECT
					alicuota  
				FROM entradas_salidas
				WHERE tipo_documento = a.tipo_documento AND 
					id_documento = a.id ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
				a.`iva` AS iva, 
				0 AS iva_ret, 'N' AS orden, a.asesor_asignado AS asesor, a.descuento, a.tipo_documento, a.estatus, a.descuento2   
			FROM 
				salidas AS a 
				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			WHERE 
				a.tipo_documento = 'TDCFCV' AND 
				a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' AND 
				a.estatus = 'PROCESADO' 
			UNION ALL 
			SELECT 
				a.id_documento AS id, 
				date_format(a.fecha, '%d/%m/%Y') AS fecha, 
				c.ci_rif, c.nombre AS cliente, 
				a.referencia AS comprobante, 
				a.tipo_pago AS documento, 
				'' AS nro_factura, '' AS nro_control, '' AS nota_debito, '' AS nota_credito, 
				b.nro_documento AS afectado, 0 AS total_ventas, 0 AS no_gravadas, 0 AS base, 0 AS iva, 
				(SELECT
					alicuota  
				FROM entradas_salidas
				WHERE tipo_documento = a.tipo_documento AND 
					id_documento = a.id_documento ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
				a.monto AS iva_ret, 'S' AS orden, b.asesor_asignado AS asesor, 0 AS descuento, a.tipo_documento, b.estatus, 
				0 AS descuento2  
			FROM 
				pagos AS a 
				JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				LEFT OUTER JOIN cliente AS c ON c.id = b.cliente 
			WHERE 
				a.tipo_documento = 'TDCFCV' AND 
				a.tipo_pago IN ('RI','RR') AND 
				a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' AND 
				b.estatus = 'PROCESADO' 
			ORDER BY fecha, orden, nro_control;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());

$items = 0;

$_total = 0.00;
$_exenta = 0.00;
$_gravable = 0.00;
$_iva = 0.00;
$_ivaret = 0.00;

while($row = mysqli_fetch_array($rs))
{
	$total = trim($row["estatus"])=="ANULADO" ? "" : ($row["total_ventas"]==0 ? "" : ($row["documento"]=="NC" ? -1 : 1)*$row["total_ventas"]);
	$exentas = trim($row["estatus"])=="ANULADO" ? "" : ($row["no_gravadas"]==0 ? "" : ($row["documento"]=="NC" ? -1 : 1)*$row["no_gravadas"]);
	$base = trim($row["estatus"])=="ANULADO" ? "" : ($row["base"]==0 ? "" : ($row["documento"]=="NC" ? -1 : 1)*$row["base"]);

	$pdf->SetFont('Arial', '', 6);

	$pdf->Cell(5, 4);
	$pdf->Cell(5, 4, $items+1, 0, 0, 'R');
	$pdf->Cell(12, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(15, 4, (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]), 0, 0, 'L');
	$pdf->Cell(47, 4, (trim($row["estatus"])=="ANULADO" ? "ANULADA" : substr($row["cliente"], 0, 32)), 0, 0, 'L');
	$pdf->Cell(15, 4, $row["comprobante"], 0, 0, 'C');
	$pdf->Cell(13, 4, $row["nro_factura"], 0, 0, 'C');
	$pdf->Cell(13, 4, $row["nro_control"], 0, 0, 'C');
	$pdf->Cell(13, 4, $row["nota_debito"], 0, 0, 'C');
	$pdf->Cell(13, 4, $row["nota_credito"], 0, 0, 'C');
	$pdf->Cell(13, 4, $row["afectado"], 0, 0, 'C');
	$pdf->Cell(17, 4, number_format(floatval($total), 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(17, 4, number_format(floatval($exentas), 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(17, 4, number_format(floatval($base), 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(10, 4, number_format($row["alic"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(17, 4, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(17, 4, number_format($row["iva_ret"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(15, 4, substr(strtolower($row["asesor"]), 0, 12), 0, 0, 'R');
	$pdf->Cell(8, 4, number_format($row["descuento"] ?? 0, 0, ",", ".") . "%", 0, 0, 'C');
	$pdf->Cell(8, 4, number_format($row["descuento2"] ?? 0, 0, ",", ".") . "%", 0, 0, 'C');

	$pdf->Ln();
	$items++;
	$_total += floatval($total); 
	$_exenta += floatval($exentas); 
	$_gravable += floatval($base); 
	$_iva += floatval($row["iva"]);
	$_ivaret += floatval($row["iva_ret"]);
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_ivaret); 

	
require("../include/desconnect.php");

$pdf->Output();
?>