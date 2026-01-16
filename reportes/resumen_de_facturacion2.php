<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$xtitulo = isset($_REQUEST["xtitulo"])?$_REQUEST["xtitulo"]:"";
$xcliente = isset($_REQUEST["xcliente"])?$_REQUEST["xcliente"]:"0";
$xasesor = isset($_REQUEST["xasesor"])?$_REQUEST["xasesor"]:"0";
$xfecha = isset($_REQUEST["xfecha"])?$_REQUEST["xfecha"]:"0";
$yfecha = isset($_REQUEST["yfecha"])?$_REQUEST["yfecha"]:"0";

$xfecha = substr($xfecha, 0, 10);
$yfecha = substr($yfecha, 0, 10);

$xF = explode("/", $xfecha);
$xfecha = $xF[2] . "-" . $xF[1] . "-" . $xF[0];

$xF = explode("/", $yfecha);
$yfecha = $xF[2] . "-" . $xF[1] . "-" . $xF[0];

$GLOBALS["titulo"] = $xtitulo;
$GLOBALS["xasesor"] = $xasesor;
//die("$xcliente - $xfecha - $yfecha"); 


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


		if(trim($GLOBALS["xasesor"]) != "") {
			$sql = "SELECT b.nombre FROM usuario AS a INNER JOIN asesor AS b ON b.id = a.asesor WHERE a.username = '" . $GLOBALS["xasesor"] . "';";
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$nombre_asesor =  $row["nombre"];
		}

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
		
		$this->Ln(8);
		$this->SetFont('Arial','',8);
		$this->Cell(200, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(200, 5, "Hora: " . date("H:i:s"),0,0,'R');
		if(trim($GLOBALS["xasesor"]) != "") {
			$this->Ln();
			$this->Cell(200, 5, "Asesor: " . $nombre_asesor,0,0,'R');
		}

		$this->Ln(20);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(200, 6, utf8_decode($GLOBALS["titulo"]),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(200, 6, "Desde " . $_REQUEST["xfecha"] . " Hasta " . $_REQUEST["yfecha"],0,0,'C');
		$this->SetFont('Arial','',8);		


		$this->Ln(8);
		

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(20, 6, "ESTATUS", 1, 0, 'L');
		$this->Cell(20, 6, "FECHA", 1, 0, 'L');
		$this->Cell(20, 6, "DOC.", 1, 0, 'L');
		$this->Cell(60, 6, "CLIENTE", 1, 0, 'L');
		$this->Cell(25, 6, "MONTO", 1, 0, 'R');
		$this->Cell(20, 6, "IVA", 1, 0, 'R');
		$this->Cell(25, 6, "TOTAL", 1, 0, 'R');
		$this->Ln(6);
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Nϊmero de pαgina
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($Total, $granTotal, $items, $xfecha, $yfecha)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(200, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');
		$this->Ln();
		//$this->Cell(100, 5, "MONTO TOTAL SIN IVA: "  . number_format($Total, 2, ".", ","), 0, 0, 'R');
		$this->Cell(200, 5, "MONTO TOTAL: "  . number_format($granTotal, 2, ".", ","), 0, 0, 'R');

		$this->SetFont('Arial','',8);
		require("../include/connect.php");
		$sql = "SELECT 
					estatus, 
					SUM(a.monto_total) AS monto_total, 
					SUM(a.iva) AS iva, 
					SUM(a.total) AS total 
				FROM 
					salidas AS a 
				WHERE 
					a.tipo_documento = 'TDCASA' AND a.factura = 'S' AND 
					a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
				GROUP BY estatus;";
		$rs = mysqli_query($link, $sql);
		while($row = mysqli_fetch_array($rs)) {
			$this->Ln();
			$this->Cell(170, 5, "ESTATUS: "  . $row["estatus"], 0, 0, 'R');
			$this->Cell(30, 5, number_format($row["total"], 2, ".", ","), 0, 0, 'R');
		}

		require("../include/desconnect.php");
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

if(intval($xcliente) == 0) $xcliente = " ";
else $xcliente = "a.cliente = '$xcliente' AND ";

if(trim($xasesor) == "") $xasesor = " ";
else $xasesor = "a.asesor = '$xasesor' AND ";

$sql = "SELECT 
			c.nombre AS usuario, date_format(a.fecha, '%d/%m/%Y') AS fecha, b.nombre AS cliente, 
			a.nro_documento, a.monto_total, 
			a.iva, if(a.DOCUMENTO='NC', (-1)*a.total, a.total) total, a.estatus, a.moneda
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
			LEFT OUTER JOIN usuario AS c ON c.username = a.username 
		WHERE 
			a.tipo_documento = 'TDCASA' AND a.factura = 'S' AND $xcliente $xasesor 
			a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' ORDER BY a.id ASC;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$Total = 0;
$granTotal = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(10, 5);
	$pdf->Cell(20, 5, $row["estatus"], 0, 0, 'L');
	$pdf->Cell(20, 5, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(20, 5, $row["nro_documento"], 0, 0, 'L');
	$pdf->Cell(60, 5, substr($row["cliente"], 0, 35), 0, 0, 'L');
	$pdf->Cell(25, 5, number_format($row["monto_total"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(20, 5, number_format($row["iva"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(25, 5, number_format($row["total"], 2, ".", ","), 0, 0, 'R');
	$pdf->Ln();
	$items++;
	$Total += $row["monto_total"];
	$granTotal += $row["total"];
}

$pdf->EndReport($Total, $granTotal, $items, $xfecha, $yfecha);

	
require("../include/desconnect.php");

$pdf->Output();
?>