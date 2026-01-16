<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

/*$sql = "SELECT * FROM entradas_salidas where id_documento = '$id';"; 	die($sql);
$rs = mysqli_query($link, $sql);
if(!$row = mysqli_fetch_array($rs)) die("La Factura no tiene Detalle");*/

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, proveedor, nro_documento, tipo_documento, estatus 
		FROM entradas where id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["proveedor"] = $row["proveedor"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];


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
					a.id, a.ci_rif, a.nombre, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf 
				FROM proveedor AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["proveedor"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$rif = $row["ci_rif"];
		$razon_social = $row["nombre"];
		$rif = $row["ci_rif"];
		$direccion_proveedor = $row["direccion"]; 
		$ciudad_proveedor = $row["ciudad"]; 
		$telf = $row["telf"]; 

		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '" . $GLOBALS["tipo_documento"] . "';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(15);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, $row["descripcion"],0,0,'C');
		


		$this->Ln(5);
		
		$this->SetFont('Arial','',8);
		$this->Cell(200, 6, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln(8);
		$this->Cell(10, 6);
		$this->Cell(100, 6);
		$this->Cell(50, 6, "CIUDAD: $ciudad,");
		$this->Cell(10, 6, substr(($GLOBALS["fecha"]),0,2),0,0,'L');
		$this->Cell(10, 6, substr(($GLOBALS["fecha"]),3,2),0,0,'C');
		$this->Cell(20, 6, substr(($GLOBALS["fecha"]),6,4),0,0,'C');
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(30, 6,"PROVEEDOR: ",'0','0','L');
		$this->Cell(130, 6, $razon_social,'0','0','L');
	

		$this->Ln(6);
		$this->Cell(10, 6);
		$this->Cell(30, 6,'DIRECCION: ','0','0','L');
		$this->MultiCell(160, 6, "$direccion_proveedor. $ciudad_proveedor", '0', 'L');

		$this->Ln(6);
		$this->Cell(10, 6);
		$this->Cell(70,6,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Cell(80,6,'Telf:'.  $telf,'0','0','L');
		$this->Cell(50,6,'CONTADO','0',0,'C');

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 6);
		$this->Cell(20, 6, "CODIGO", 1, 0, 'L');
		$this->Cell(25, 6, "LAB", 1, 0, 'L');
		$this->Cell(55, 6, "ARTICULO", 1, 0, 'L');
		$this->Cell(20, 6, "LOTE", 1, 0, 'L');
		$this->Cell(15, 6, "VENCE", 1, 0, 'L');
		$this->Cell(15, 6, "ALMACEN", 1, 0, 'C');
		$this->Cell(10, 6, "CANT", 1, 0, 'R');
		$this->Cell(17, 6, "COSTO", 1, 0, 'R');
		$this->Cell(18, 6, "TOTAL", 1, 0, 'R');
		$this->Ln(6);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $unidades, $tot)
	{
		//$this->AddPage();
		//require("../include/connect.php");
		$this->Ln();
		$this->Cell(145, 6, "ITEMS: "  . $items, 0, 0, 'R');
		$this->Cell(25, 6, "UNIDADES: "  . $unidades, 0, 0, 'R');
		$this->Cell(35, 6, "TOTAL: "  . number_format($tot, 2, ",", "."), 0, 0, 'R');
		//require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
			b.codigo, 
			IFNULL(c.nombre, '') AS lab, 
			CONCAT(IFNULL(b.nombre_comercial, ''), ' ', 
			IFNULL(b.principio_activo, ''), ' ', 
			IFNULL(b.presentacion, '')) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.costo_unidad, a.costo, a.lote, 
			date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
			d.descripcion AS almacen, a.costo_unidad, a.costo   
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
			LEFT OUTER JOIN almacen AS d ON d.codigo = a.almacen 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$unidades = 0;
$tot = 0.00;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(20, 4, $row["codigo"], 0, 0, 'L');
	$pdf->Cell(25, 4, substr($row["lab"], 0, 10), 0, 0, 'L');
	if(strlen($row["articulo"]) < 30) 
		$pdf->Cell(55, 4, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(55, 4, trim(substr($row["articulo"], 0, 30)), 0, 0, 'L');
	$pdf->Cell(20, 4, $row["lote"], 0, 0, 'C');
	$pdf->Cell(15, 4, $row["fecha_vencimiento"], 0, 0, 'C');
	$abrev = explode(" ", $row["almacen"]);
	$almacen = "";
	foreach ($abrev as $key => $value) { 
		$almacen .= substr($value, 0, 5) . " ";
	}
	$pdf->Cell(15, 4, $almacen, 0, 0, 'L');
	$pdf->Cell(10, 4, number_format($row["cantidad"], 0,'',''), 0, 0, 'R');
	$pdf->Cell(17, 4, number_format($row["costo_unidad"], 2,'.',','), 0, 0, 'R');
	$pdf->Cell(18, 4, number_format($row["costo"], 2,'.',','), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 30) {
		$pdf->Ln();
		$pdf->Cell(55, 4);
		$pdf->MultiCell(55, 4, trim(substr($row["articulo"], 30, strlen($row["articulo"]))), 0, 'L');
	}
	else $pdf->Ln();

	//if($pdf->GetY() > 250) $pdf->AddPage();
	$items++;
	$unidades += (int)$row["cantidad"];
	$tot += (float)$row["costo"];
}

$pdf->EndReport($items, $unidades, $tot);

	
require("../include/desconnect.php");

$pdf->Output();
?>