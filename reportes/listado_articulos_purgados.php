<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id = $_REQUEST["id"];

$GLOBALS["titulo"] = "TOMA FISICA DE INVENTARIO CANTIDAD DE ARTICULOS AJUSTADA";

$sql = "SELECT 
	        DATE_FORMAT(z.fecha, '%d/%m/%Y') AS fecha, z.username, z.username_procesa 
	      FROM purga AS z WHERE z.id = '$id';"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error());
$row = mysqli_fetch_array($rs);
$GLOBALS["fecha"] = "Usuario: " . $row["username"] . " Usuario Aprueba:  " . $row["username_procesa"] . "  FECHA: " . $row["fecha"];

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../include/connect2.php");
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
		
		$this->Ln(8);
		$this->SetFont('Arial','',8);
		$this->Cell(270, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(270, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(10);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(270, 6, mb_convert_encoding($GLOBALS["titulo"], "ISO-8859-1") ,0,0,'C');
		$this->Ln();
		$this->SetFont('Arial','B',12);

		$this->Cell(270, 6, mb_convert_encoding($GLOBALS["fecha"], "ISO-8859-1") ,0,0,'C');
		$this->SetFont('Arial','B',8);

		$this->Ln(5);
		

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(5, 6);
		$this->Cell(35, 6, "LAB.", 1, 0, 'L');
		$this->Cell(80, 6, "MEDICAMENTO", 1, 0, 'L');
		$this->Cell(75, 6, "PRESENTACION", 1, 0, 'L');
		$this->Cell(20, 6, "CODIGO", 1, 0, 'L');
		$this->Cell(20, 6, "LOTE", 1, 0, 'C');
		$this->Cell(20, 6, "FECHA V.", 1, 0, 'C');
		$this->Cell(15, 6, "CANT", 1, 0, 'C');
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
	
	function EndReport($items)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(270, 5, "TOTAL ARTICULOS: "  . $items, 0, 0, 'R');

		$this->Ln(20);
		$this->Cell(270, 5, "FECHA FIRMA PROCESO: ____________________________________", 0, 0, 'C');
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$sql = "SELECT 
	        a.id, 
	        a.foto, IFNULL(a.nombre_comercial, '') AS nombre_comercial, IFNULL(b.nombre, '') AS fabricante, 
	        IFNULL(a.principio_activo, '') AS principio_activo, IFNULL(a.presentacion, '') AS presentacion, 
	        x.cantidad_articulo AS cantidad_articulo, 
	        a.descuento, a.codigo, x.lote, x.fecha AS fecha_vencimiento, DATE_FORMAT(z.fecha, '%d/%m/%Y') AS fecha
	      FROM 
	        purga AS z 
	        JOIN purga_detalle AS x ON x.purga = z.id 
	        JOIN articulo AS a ON a.fabricante = x.fabricante AND a.id = x.articulo
	        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
	      WHERE 
	        z.id = '$id' 
	      ORDER BY b.nombre, a.nombre_comercial, a.principio_activo, a.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);

	$pdf->Cell(5, 5);
	$pdf->Cell(35, 5, $row["fabricante"], 1, 0, 'L');
	$art = (trim($row["nombre_comercial"]) == "" ? "" : trim($row["nombre_comercial"]) . " ") . trim($row["principio_activo"]);
	$art = mb_convert_encoding($art, "ISO-8859-1");
	$pdf->Cell(80, 5, substr($art, 0, 45), 1, 0, 'L');
	$pre = trim($row["presentacion"]);
	$pre = mb_convert_encoding($pre, "ISO-8859-1");
	$pdf->Cell(75, 5, substr($pre, 0, 45), 1, 0, 'L');
	$pdf->Cell(20, 5, trim($row["codigo"]), 1, 0, 'L');
	$pdf->Cell(20, 5, $row["lote"], 1, 0, 'c');
	$pdf->Cell(20, 5, $row["fecha_vencimiento"], 1, 0, 'C');
	$pdf->Cell(15, 5, $row["cantidad_articulo"], 1, 0, 'C');
	$pdf->Ln();
	$items++;

	if(strlen($art) > 45 or strlen($pre) > 45) {
		$pdf->Cell(5, 5);
		$pdf->Cell(35, 5, "", 1, 0, 'C');
		$pdf->Cell(80, 5, substr($art, 45, 45), 1, 0, 'L');
		$pdf->Cell(75, 5, substr($pre, 45, 45), 1, 0, 'L');
		$pdf->Cell(75, 5, "", 1, 0, 'C');
		$pdf->Ln();
	}
}

$pdf->EndReport($items);

	
require("../include/desconnect.php");

$pdf->Output();
?>