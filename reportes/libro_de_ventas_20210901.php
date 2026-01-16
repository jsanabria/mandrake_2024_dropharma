<?php
require('rcs/fpdf.php');
require("../connect.php");

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
		require("../connect.php");
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
		

		require("../desconnect.php");

		$this->Cell(5, 5);
		$this->Cell(15, 5, "", "LTR", 0, 'L');
		$this->Cell(15, 5, "", "LTR", 0, 'C');
		$this->Cell(15, 5, "NOTA", "LTR", 0, 'C');
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(20, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(55, 5, "", "LTR", 0, 'L');
		$this->Cell(20, 5, "", "LTR", 0, 'L');
		$this->Cell(27, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(27, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(58, 5, "DEBITO FISCAL", 1, 0, 'C');
		$this->Ln(5);

		$this->Cell(5, 5);
		$this->Cell(15, 5, "FECHA", "LBR", 0, 'L');
		$this->Cell(15, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(15, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(15, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(20, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(55, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(20, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(27, 5, "VENTAS", "LBR", 0, 'R');
		$this->Cell(27, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(27, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(23, 5, "IMPUESTO", "LBR", 0, 'R');
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
		//require("../connect.php");
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(160, 4, "", 0, 0, 'R');
		$this->Cell(27, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(27, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(27, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(8, 4, "", 0, 0, 'R');
		$this->Cell(23, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		$this->Cell(240, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');
		$this->Ln();
		require("../desconnect.php");
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
			a.id, 
			a.`tipo_documento`, 
			a.`nro_documento`, 
			a.`documento`, 
			IF(a.documento='NC', REPLACE(REPLACE(REPLACE(SUBSTRING_INDEX(a.nota, ':', -1), 'FACT-', ''), 'NC-', ''), 'ND-', ''), '') AS afectado, 
			a.`nro_control`, 
			b.`nombre` AS cliente, 
			b.`ci_rif`, 
			date_format(a.fecha, '%Y/%m/%d') AS fecha, 
			a.`total`, 
			a.`iva`, 
			a.`estatus`, a.descuento  
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.tipo_documento = 'TDCFCV' AND 
			a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' ORDER BY a.nro_control;"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());

$items = 0;

$_total = 0.00;
$_exenta = 0.00;
$_gravable = 0.00;
$_iva = 0.00;

while($row = mysqli_fetch_array($rs))
{
	$desc = floatval($row["descuento"]);
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(5, 4);
	$pdf->Cell(15, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(15, 4, ($row["documento"]=="FC" ? str_replace("FACT-", "", $row["nro_documento"]) : ""), 0, 0, 'C');
	$pdf->Cell(15, 4, ($row["documento"]=="NC" ? str_replace("NC-", "", $row["nro_documento"]) : ""), 0, 0, 'C');
	$pdf->Cell(15, 4, $row["afectado"], 0, 0, 'C');
	$pdf->Cell(20, 4, $row["nro_control"], 0, 0, 'C');
	$pdf->Cell(55, 4, substr((trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["cliente"]), 0, 30), 0, 0, 'L');
	$pdf->Cell(20, 4, (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]), 0, 0, 'L');
	$pdf->Cell(27, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row["total"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total"], 2, ",", ".")), 0, 0, 'R');
	$_total += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*$row["total"]);

	$sql = "SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) AS exenta FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'"; 
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
	$row2 = mysqli_fetch_array($rs3);
	$pdf->Cell(27, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row2["exenta"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($row2["exenta"] - ($row2["exenta"]*($desc/100))), 2, ",", ".")), 0, 0, 'R');
	$_exenta += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*($row2["exenta"] - ($row2["exenta"]*($desc/100))));

	$sql = "SELECT SUM(IF(alicuota>0, precio, 0)) AS gravable FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
	$row2 = mysqli_fetch_array($rs3);
	$pdf->Cell(27, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row2["gravable"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($row2["gravable"] - ($row2["gravable"]*($desc/100))), 2, ",", ".")), 0, 0, 'R');
	$_gravable += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*($row2["gravable"] - ($row2["gravable"]*($desc/100))));

	$sql = "SELECT MAX(alicuota) AS alicuota_iva FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error());
	$row2 = mysqli_fetch_array($rs3);
	$pdf->Cell(8, 4, $row2["alicuota_iva"]==0 ? "" : number_format($row2["alicuota_iva"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(23, 4, trim($row["estatus"])=="ANULADO" ? "" : ($row["iva"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["iva"], 2, ",", ".")), 0, 0, 'R');
	$_iva += trim($row["estatus"])=="ANULADO" ? 0 : floatval(($row["documento"]=="NC" ? -1 : 1)*$row["iva"]);

	//$pdf->Cell(20, 4, "", 0, 0, 'R');
	$pdf->Ln();
	$items++;

	
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva); 

	
require("../desconnect.php");

$pdf->Output();
?>