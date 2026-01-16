<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus, 
			asesor  
		FROM salidas where id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];

$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '" . $row["asesor"] . "';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$GLOBALS["asesor"] = $row["nombre"];
else 
	$GLOBALS["asesor"] = "";


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
		$this->Cell(130, 6, $razon_social,'0','0','L');
	

		$this->Ln(10);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(30, 5,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',8);
		$this->MultiCell(160, 5, "$direccion_cliente. $ciudad_cliente", '0', 'L');

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

		$this->Cell(10, 6);
		$this->Cell(20, 6, "LAB", 1, 0, 'L');
		$this->Cell(80, 6, "ARTICULO", 1, 0, 'L');
		//$this->Cell(20, 6, "MED./CAN.", 1, 0, 'L');
		$this->Cell(10, 6, "CAN.", 1, 0, 'R');
		$this->Cell(15, 6, "IVA %", 1, 0, 'R');
		$this->Cell(25, 6, "PRECIO", 1, 0, 'R');
		$this->Cell(10, 6, "DES %", 1, 0, 'R');
		$this->Cell(30, 6, "TOTAL", 1, 0, 'R');
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
	
	function EndReport($id, $items, $cnt)
	{
		/*$this->Ln();
		$this->Cell(130, 6, "CANTIDAD ARTICULOS: "  . number_format($cnt, 0, "", ","), 0, 0, 'R');
		$this->Cell(70, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');*/
		$asociado = "";

		require("../include/connect2.php");
		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.id_documento_padre, 
					descuento, descuento2, monto_sin_descuento    
				FROM salidas a where a.id = '$id';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$descuento = $row["descuento"]; 
		$descuento2 = $row["descuento2"]; 
		$monto_sin_descuento = $row["monto_sin_descuento"];

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

		$this->Ln(230-$this->GetY());
		$this->SetFont('Arial','',8);
		$this->Cell(10, 6);
		$this->Cell(80, 6, "CANTIDAD ARTICULOS: "  . number_format($cnt, 0, "", ","), 1, 0, 'R');
		$this->Cell(70, 6, "(TOTAL ITEMS: "  . $items . ") SUB-TOTAL:", 1, 0, 'R');
		$this->Cell(40, 6, number_format($monto_sin_descuento, 2, ".", ","), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell(10, 6);
		$this->Cell(80, 6, "DESCUENTO 1: "  . number_format($descuento, 2, ".", ",") . "%", 1, 0, 'R');
		$this->Cell(70, 6, "DESCUENTO 2: "  . number_format($descuento2, 2, ".", ",") . "%", 1, 0, 'R');
		$this->Cell(40, 6, number_format($row["monto_total"], 2, ".", ","), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell(10, 6);
		if(floatval($row["alicuota_iva"]) > 0) {
			$this->Cell(70, 6, "% IVA:", 0, 0, 'R');
			$this->Cell(40, 6, number_format($row["alicuota_iva"], 0, ".", ","), 1, 0, 'L');
			$this->Cell(40,6, number_format($row["monto_total"], 2, ".", ","), 1, 0, 'R');
		}
		else $this->Cell(150,6, "IVA:", 1, 0, 'R');
		$this->Cell(40, 6, number_format($row["iva"], 2, ".", ","), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell(10, 6);
		$this->Cell(150, 6, $asociado . ' TOTAL:', 1, 0, 'R');
		$this->Cell(40, 6, number_format($row["total"], 2, ".", ","), 1, 0, 'R');
		
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
			c.nombre AS codigo,  
			IFNULL(c.nombre, '') AS lab, 
			CONCAT(IFNULL(b.nombre_comercial, ''), ' ', 
			IFNULL(b.principio_activo, ''), ' ', 
			IFNULL(b.presentacion, '')) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.descuento, 
			a.precio_unidad_sin_desc AS precio_unidad, 
			a.precio
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.id = a.fabricante 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$cnt = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(10, 4);
	$pdf->Cell(20, 4, substr($row["codigo"], 0, 10), 0, 0, 'L');
	if(strlen($row["articulo"]) < 47) 
		$pdf->Cell(80, 4, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(80, 4, trim(substr($row["articulo"], 0, 47)), 0, 0, 'L');
	$pdf->Cell(10, 4, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
	//$pdf->Cell(20, 4, substr($row["unidad_medida"], 0, 3) . " " . $row["cantidad"], 0, 0, 'L');
	$pdf->Cell(15, 4, number_format($row["alicuota"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(25, 4, number_format($row["precio_unidad"], 2, ".", ","), 0, 0, 'R');
	$pdf->Cell(10, 4, floatval($row["descuento"])>0 ? number_format($row["descuento"], 2, ",", ".") : "", 0, 0, 'R');
	$pdf->Cell(30, 4, number_format($row["precio"], 2, ".", ","), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 47) {
		$pdf->Ln();
		$pdf->Cell(30, 4);
		$pdf->MultiCell(80, 4, trim(substr($row["articulo"], 47, strlen($row["articulo"]))), 0, 'L');
	}
	else $pdf->Ln();

	if($pdf->GetY() > 250) $pdf->AddPage();
	$items++;
	$cnt += intval($row["cantidad"]);
}

$pdf->EndReport($id, $items, $cnt);

	
require("../include/desconnect.php");

$pdf->Output();
?>