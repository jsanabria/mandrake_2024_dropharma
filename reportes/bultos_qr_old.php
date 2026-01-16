<?php
require('rcs/fpdf.php');

$GLOBALS["id"] = trim(isset($_REQUEST["id"])?$_REQUEST["id"]:"0");

class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
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
		$cia =  $row["nombre"];
		$logo =  $row["logo"];

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 35);
		}

		$id = $GLOBALS["id"];

		$sql = "SELECT 
					b.nombre AS cliente, c.campo_descripcion AS ciudad, 
					b.direccion, b.telefono1, b.telefono2,
					a.nro_documento, a.asesor, a.bultos  
				FROM 
					salidas AS a 
					LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
					LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD' 
				WHERE a.id = '$id';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$cliente = $row["cliente"];
		$nro_documento = $row["nro_documento"];
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"];
		$telefono1 = $row["telefono1"];
		$telefono2 = $row["telefono2"];
		$asesor = $row["asesor"];
		$GLOBALS["bultos"] = $row["bultos"];

		$sql = "SELECT a.nombre  
				FROM 
					usuario AS u 
					JOIN asesor AS a ON a.id = u.asesor 
				WHERE 
					u.username = '$asesor';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$asesor = $row["nombre"];

		$file = "QR_$nro_documento.png";
		$contenido = $nro_documento; 

		$dir = '../codigo_qr/temp/';
		$filename = $dir .  $file;
		if(!file_exists($filename)) 
			include_once("qr.php");

		$qr = $filename;

		$this->Ln(20);
		
		$this->SetFont('Courier', 'B', 14);
		$this->Cell(10, 5);
		$this->Cell(30, 5, $cliente, '0', '0', 'L');
		$this->Ln();
		$this->SetFont('Courier', '', 12);
		$this->Cell(10, 5);
		$this->MultiCell(125, 5, utf8_decode($direccion . ". CIUDAD $ciudad"), '0', 'L');
		$this->Ln();
		$this->Cell(10, 5);
		$this->Cell(30, 5, "ASESOR: " . $asesor, '0', '0', 'L');
		$this->Ln();
		$this->Cell(10, 5);
		$this->Cell(30, 5, "TELF: " . $telefono1 . " / " . $telefono2, '0', '0', 'L');
		$this->Ln();
		$this->Cell(10, 5);
		$this->Cell(30, 5, "DOCUMENTO: " . $nro_documento, '0', '0', 'L');
		$this->Ln(10);
		$this->SetFont('Courier', 'B', 18);
		$this->Cell(10, 5);
		$this->Cell(0,10,'Bulto: '.$this->PageNo().'/{nb}',0,0,'L');
		$this->Ln();

		$this->Image($qr, 110, 65, 30);

		require("../desconnect.php");
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
	
	function EndReport($id)
	{
		//$this->AddPage();
	}
}

require("../connect.php");
$id = $GLOBALS["id"];
$sql = "SELECT bultos FROM salidas WHERE id = '$id';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$bultos = intval($row["bultos"]);
require("../desconnect.php");

// Creación del objeto de la clase heredada
//$pdf = new PDF('P', 'mm', 'Letter');
$pdf = new PDF('P', 'mm', array(155,105));
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();

for($i=0; $i<$bultos; $i++) {
	$pdf->AddPage();
}

$pdf->SetFont('Courier','',8);



$pdf->EndReport($id);

	
$pdf->Output();
?>