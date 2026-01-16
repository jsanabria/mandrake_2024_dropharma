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

$GLOBALS["titulo"] = "Libro de Compras";
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
		$this->SetFont('Arial','',8);
		$this->Cell(270, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(270, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(2);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(270, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(270, 6, $GLOBALS["subtitulo"],0,0,'C');
		$this->SetFont('Arial','',8);		


		$this->Ln(8);
		

		require("../include/desconnect.php");

		$this->Cell(5, 5);
		$this->Cell(15, 5, "", "LTR", 0, 'L');
		$this->Cell(22, 5, "", "LTR", 0, 'C');
		$this->Cell(20, 5, "NOTA", "LTR", 0, 'C');
		$this->Cell(20, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(18, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(45, 5, "", "LTR", 0, 'L');
		$this->Cell(18, 5, "", "LTR", 0, 'L');
		$this->Cell(26, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(26, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(56, 5, "DEBITO FISCAL", 1, 0, 'C');
		$this->Ln(5);

		$this->Cell(5, 5);
		$this->Cell(15, 5, "FECHA", "LBR", 0, 'L');
		$this->Cell(22, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(20, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(20, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(18, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(45, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(18, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(26, 5, "VENTAS", "LBR", 0, 'R');
		$this->Cell(26, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(26, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(22, 5, "IMPUESTO", "LBR", 0, 'R');
		//$this->Cell(20, 5, "RET IVA 75%", "LBR", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Nϊmero de pαgina
		$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $_total, $_exenta, $_gravable, $_iva)
	{
		//$this->AddPage();
		//require("../include/connect.php");
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(163, 4, "", 0, 0, 'R');
		$this->Cell(26, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(26, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(26, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(8, 4, "", 0, 0, 'R');
		$this->Cell(22, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		$this->Cell(240, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');
		$this->Ln();
		require("../include/desconnect.php");
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

/*
			(SELECT SUM(IF(alicuota=0, precio, 0)) 
			FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.`tipo_documento`) AS exenta, 
			(SELECT SUM(IF(alicuota>0, precio, 0)) 
			FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.`tipo_documento`) AS gravable, 
			(SELECT MAX(alicuota) 
			FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.`tipo_documento`) AS alicuota_iva, 
*/
$sql = "SELECT 
			a.fecha AS fecfac, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', a.nro_documento), a.nro_documento)) AS nro_documento, 
			IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
			a.doc_afectado AS doc_afectado, 
			a.nro_control,  
			b.nombre AS proveedor, 
			b.ci_rif, 
			IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.monto_total) AS monto_total, 
			IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.iva) AS iva, 
			IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.total) AS total, 
			IF(a.documento = 'NC', -1, 1) * (SELECT 
				SUM(IF(IFNULL(alicuota, 0)=0, costo, 0)) AS exenta 
			FROM entradas_salidas 
			WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
			IF(a.documento = 'NC', -1, 1) * (SELECT 
				SUM(IF(IFNULL(alicuota, 0)=0, 0, costo)) AS gravable 
			FROM entradas_salidas 
			WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
			(SELECT 
				MAX(alicuota) AS alicuota_iva 
			FROM entradas_salidas 
			WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva, 
			a.estatus, ret_islr 
		FROM 
			entradas AS a 
			LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE 
			a.tipo_documento = 'TDCFCC' AND 
			a.fecha_libro_compra BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' AND a.estatus = 'PROCESADO' 
		UNION ALL 	
		SELECT 
			a.fecha AS fecfac, 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			IF(a.tipo_documento = 'NC', '', IF(a.tipo_documento = 'ND', CONCAT('ND-', a.documento), a.documento)) AS nro_documento, 
			IF(a.tipo_documento = 'NC', a.documento, '')  AS nota_credito, 
			a.doc_afectado AS doc_afectado, 
			a.nro_control,  
			b.nombre AS proveedor, 
			b.ci_rif, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS monto_total, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
			a.alicuota AS alicuota_iva, '' AS estatus, ret_islr   
		FROM 
			compra AS a
			LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE 
			a.fecha_registro BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
		ORDER BY fecfac, nro_documento;";  // La fecha de registro la tomo con control de Fecha Libro Compra
$rs = mysqli_query($link, $sql) or die(mysqli_error());

$items = 0;

$_total = 0.00;
$_exenta = 0.00;
$_gravable = 0.00;
$_iva = 0.00;

while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(5, 4);
	$pdf->Cell(15, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(22, 4, $row["nro_documento"], 0, 0, 'C');
	$pdf->Cell(20, 4, $row["nota_credito"], 0, 0, 'C');
	$pdf->Cell(20, 4, $row["doc_afectado"], 0, 0, 'C');
	$pdf->Cell(18, 4, $row["nro_control"], 0, 0, 'C');
	$pdf->Cell(45, 4, substr($row["proveedor"], 0, 24), 0, 0, 'L');
	$pdf->Cell(18, 4, $row["ci_rif"], 0, 0, 'L');
	$pdf->Cell(26, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(26, 4, number_format($row["exenta"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(26, 4, number_format($row["gravable"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(8, 4, number_format($row["alicuota_iva"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(22, 4, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');
	//$pdf->Cell(20, 4, "", 0, 0, 'R');
	$pdf->Ln();
	$items++;

	$_total += floatval($row["total"]);
	$_exenta += floatval($row["exenta"]);
	$_gravable += floatval($row["gravable"]);
	$_iva += floatval($row["iva"]);
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva);

	
require("../include/desconnect.php");

$pdf->Output();
?>