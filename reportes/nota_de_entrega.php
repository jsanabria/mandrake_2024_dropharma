<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('rcs/fpdf.php');
require("../include/connect2.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

/////////////////////////////
$sql = "SELECT 
			cantidad_articulo, cantidad_movimiento 
		FROM 
			entradas_salidas 
		WHERE
			id_documento = $id 
			AND tipo_documento = 'TDCNET' 
			AND cantidad_movimiento IS NULL;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$sql = "UPDATE entradas_salidas
				SET cantidad_movimiento = (-1)*cantidad_articulo 
			WHERE
				id_documento = $id 
				AND tipo_documento = 'TDCNET' 
				AND cantidad_movimiento IS NULL;";
	mysqli_query($link, $sql);
}
/////////////////////////////

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus, 
			asesor, username  
		FROM salidas where id = '$id'"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];
$GLOBALS["username"] = $row["username"];

$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '" . $row["asesor"] . "';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $GLOBALS["asesor"] = $row["nombre"];
else $GLOBALS["asesor"] = "";


$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '" . $GLOBALS["username"] . "';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$GLOBALS["username"] = $row["nombre"];
else 
	$GLOBALS["username"] = "";

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

		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '" . $GLOBALS["tipo_documento"] . "';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(25);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, $row["descripcion"],0,0,'C');
		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		$this->Cell(200, 6, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln(8);
		$this->Cell(10, 6);
		$this->Cell(150, 6);
		$this->SetFont('Arial','B',8);
		$this->Cell(20, 5,'Fecha: ','0','0','R');
		$this->SetFont('Arial','',8);
		$this->Cell(20, 5, $GLOBALS["fecha"], 0, 0, 'R');
		$this->Ln(10);

		$this->Cell(10, 6);
		$this->SetFont('Arial','B',8);
		$this->Cell(30, 6,"RAZON SOCIAL: ",'0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(130, 6, mb_convert_encoding($razon_social, "ISO-8859-1"),'0','0','L');
	

		$this->Ln(10);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(30, 5,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',8);
		$this->MultiCell(160, 5, mb_convert_encoding("$direccion_cliente. $ciudad_cliente", "ISO-8859-1"), '0', 'L');

		$this->Ln(6);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'R.I.F.: ','0',0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,5,$rif,'0',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'Telf:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(55,5,$telf,'0','0','L');

		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'WEB:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(20,5,$web,'0','0','L');

		$this->SetFont('Arial','B',8);
		$this->Cell(15,5,'Asesor:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(45,5,$GLOBALS["asesor"],'0','0','L');

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->SetFont('Arial','B',8);
		$this->Cell(5, 5);
		$this->Cell(20, 5, "CODIGO", 1, 0, 'L');
		//$this->Cell(25, 5, "LABORATORIO", 1, 0, 'L');
		$this->Cell(100, 5, "ARTICULO", 1, 0, 'L');
		$this->Cell(20, 5, "ALMACEN", 1, 0, 'C');
		$this->Cell(15, 5, "LOTE", 1, 0, 'C');
		$this->Cell(15, 5, "VENCIM.", 1, 0, 'L');
		//$this->Cell(15, 5, "U. MED.", 1, 0, 'C');
		$this->Cell(10, 5, "CANT", 1, 0, 'R');
		$this->Cell(10, 5, "", 1, 0, 'R');
		$this->Cell(10, 5, "", 1, 0, 'R');
		$this->SetFont('Arial','',8);
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
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($id, $items, $yy)
	{
		//$this->AddPage();
		require("../include/connect2.php");
		$doc = "";
		$asociado = "";
		$sql = "SELECT 
					a.id_documento_padre  
				FROM salidas a where a.id = '$id';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$sql2 = "SELECT b.descripcion, a.nro_documento 
				FROM salidas AS a JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
				 where a.id = '" . $row["id_documento_padre"] . "';";
		$rs2 = mysqli_query($link, $sql2);
		$sw = false;
		while($row2 = mysqli_fetch_array($rs2)) {
			$doc .= " #" . $row2["nro_documento"];
			$tdoc = $row2["descripcion"];
			$sw = true;
		}

		if($sw) $asociado = "Documento(s) Asociado(s): $tdoc $doc / ";

		$this->Ln();
		$this->Cell(200, 6, "$asociado TOTAL MEDICAMENTOS $yy EN ITEMS: "  . $items, 0, 0, 'R');

		$this->Ln();
		$this->Cell(200, 6, $GLOBALS["username"] . " ________________________", 0, 0, 'C');
		//require("../include/desconnect.php");
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);


$sql = "SELECT 
			b.codigo, 
			IFNULL(c.nombre, '') AS laboratorio, 
			CONCAT(IFNULL(b.nombre_comercial, ''), IF(IFNULL(b.nombre_comercial, '')='', '', ' - '), IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ') AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.costo_unidad, 
			a.costo, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
			d.descripcion AS almacen  
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
			LEFT OUTER JOIN almacen AS d ON d.codigo = a.almacen 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY c.nombre, b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$labt = "";
$sw = false;
$i = 0;
$y = 0;
$yy = 0;
while($row = mysqli_fetch_array($rs))
{
	if($labt != $row["laboratorio"]) {
		if($sw) {
			$pdf->SetFont('Arial', 'BI', 10);
			$pdf->Cell(5, 5, "");
			$pdf->Cell(200, 5, "Total Medicamentos $y en $labt Items: $i", 1, 0, 'R');
			$pdf->Ln();
			$i = 0;
			$sw = false;
		}

		$pdf->SetFont('Arial', 'BI', 10);
		$pdf->Cell(5, 5, "");
		$pdf->Cell(200, 5, $row["laboratorio"], 1, 0, 'L');
		$pdf->Ln();
		$sw = true;
		$y = 0;
	}

	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(5, 5);
	$pdf->Cell(20, 5, $row["codigo"], 1, 0, 'L');
	$pdf->SetFont('Arial','B',8);
	//$pdf->Cell(25, 5, $row["laboratorio"], 1, 0, 'L');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(100, 5, substr($row["articulo"], 0, 60), 1, 0, 'L');
	$abrev = explode(" ", $row["almacen"] ?? '');
	$almacen = "";
	foreach ($abrev as $key => $value) { 
		$almacen .= substr($value, 0, 5) . " ";
	}
	$pdf->Cell(20, 5, $almacen, 1, 0, 'L');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15, 5, $row["lote"], 1, 0, 'C');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(15, 5, $row["fecha_vencimiento"], 1, 0, 'C');
	//$pdf->Cell(15, 5, $row["unidad_medida"], 1, 0, 'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(10, 5, number_format($row["cantidad"], 0, "", ""), 1, 0, 'R');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(10, 5, "", 1, 0, 'R');
	$pdf->Cell(10, 5, "", 1, 0, 'R');

	$pdf->Ln();

	if(trim(substr($row["articulo"], 60, strlen($row["articulo"]))) != "") {
		if(strlen($row["articulo"]) >= 60) {
			//$pdf->Ln();
			$pdf->Cell(25, 4);
			$pdf->MultiCell(100, 5, substr(trim($row["articulo"]), 60, strlen(trim($row["articulo"]))), 0, 'L');
			$sw = true;
		}
	}


	//if($pdf->GetY() > 250) $pdf->AddPage();
	$items++;
	$labt = $row["laboratorio"];
	$i++;
	$y += intval($row["cantidad"]);
	$yy += intval($row["cantidad"]);
}

if($sw) {
	$pdf->SetFont('Arial', 'BI', 10);
	$pdf->Cell(5, 5, "");
	$pdf->Cell(200, 5, "Total Medicamentos $y en $labt Items: $i", 1, 0, 'R');
	$pdf->Ln();
	$i = 0;
	$sw = false;
}

$pdf->EndReport($id, $items, $yy);

	
require("../include/desconnect.php");

$pdf->Output();
?>