<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, 
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus, 
			asesor, documento, monto_usd, tasa_dia    
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

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = $row["asesor"];

if(($monto_usd==0 or $tasa_dia==0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) { 
	$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
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
			u.username = '$asesor';";  ;
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);


$GLOBALS["asesor"] = isset($row["nombre"]) ? $row["nombre"] : "";


class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
		require("../include/connect.php");
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
					a.direccion, a.telefono1, a.email1 
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];

		
		
		$sql = "SELECT 
					LPAD(a.id, 8, '0') AS id, a.ci_rif, a.nombre, a.contacto, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf, a.web, 
					a.email2 AS SICM 
				FROM cliente AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '" . $GLOBALS["cliente"] . "';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		
		$id = $row["id"];
		$rif = $row["ci_rif"];
		$razon_social = $row["nombre"];
		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 
		$web = $row["web"]; 
		$SICM = $row["SICM"]; 

		$this->Ln(30);
		$this->SetFont('Arial','',13);
		
		$this->Cell(125, 6);
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Crédito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de Débito: ":"N/A")));
		$this->Cell(30, 6, $tdoc,'0','0','L');
		$this->SetFont('Arial','',13);
		$this->Cell(45, 6, $GLOBALS["nro_documento"],'0','0','R');

		$this->Ln(12);
		$this->SetFont('Arial','B',12);
		$this->Cell(125, 6);
		$this->Cell(30, 6,'Fecha: ','0','0','L');
		$this->SetFont('Arial','',12);
		$this->Cell(45, 6, $ciudad . ", " . $GLOBALS["fecha"], 0, 0, 'R');


		$this->Ln(15);

		$this->SetFont('Arial','B',9);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CLIENTE: ",'0','0','L');
		$this->SetFont('Arial','',9);
		$this->Cell(110, 4, utf8_decode(substr($razon_social, 0, 70)),'0','0','L');

		$this->Ln(4);
		$this->Cell(40, 4);
		$this->Cell(110, 4, utf8_decode(substr($razon_social, 70, strlen($razon_social))),'0','0','L');

		$this->Ln(4);
		$this->SetFont('Arial','B',9);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CUENTA Nº: ",'0','0','L');
		$this->SetFont('Arial','',9);
		$this->Cell(110, 4, utf8_decode(substr($id, 0, 55)),'0','0','L');

		$this->Ln(6);
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',9);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',9);
		$direccion_cliente = "$direccion_cliente. $ciudad_cliente";
		$this->MultiCell(160, 5, $direccion_cliente, '0', 'L');

		
		$this->Ln(6);
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',9);
		$this->Cell(25,4,'TELEFONOS:','0','0','L');
		$this->SetFont('Arial','',9);
		$this->Cell(55,4,$telf,'0','0','L');

		$this->SetFont('Arial','B',9);
		$this->Cell(10,4,'R.I.F.: ','0',0,'L');
		$this->SetFont('Arial','',9);
		$this->Cell(25,4,$rif,'0',0,'L');

		$this->SetFont('Arial','B',9);
		$this->Cell(50,4,'CONDICIONES DE PAGO: ','0',0,'L');
		$this->SetFont('Arial','',9);
		$this->Cell(25,4,"Contado",'0',0,'L');


		require("../include/desconnect.php");
		$this->Ln(12);

		$this->SetFont('Arial','B',9);
		$this->Cell(10, 6);
		$this->Cell(25, 6, "CANT", "B", 0, 'C');
		$this->Cell(85, 6, "DESCRIPCION", "B", 0, 'C');
		$this->Cell(30, 6, "PRECIO UNIT", "B", 0, 'R');
		$this->Cell(20, 6, "% ALIC", "B", 0, 'R');
		$this->Cell(30, 6, "TOTAL", "B", 0, 'R');
		$this->Ln(10);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function EndReport($id_invoice)
	{
		//$this->AddPage();
		$asociado = "";
		require("../include/connect.php");
		$doc = "";

		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, 
					a.moneda, 
					a.asesor, a.id_documento_padre, 
					a.monto_usd, a.tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades 
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = utf8_decode($row["nota"]);
		$moneda = utf8_decode($row["moneda"]);
		$asesor = utf8_decode($row["asesor"]);
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];

		$id_documento_padre = $row["id_documento_padre"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];

		$descuento = floatval($row["descuento"]);

		$unidades = $row["unidades"];

		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
				FROM entradas_salidas
				WHERE tipo_documento = 'TDCFCV' AND 
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

		$this->SetFont('Arial','B',9);
		$this->Cell(168, 4, $asociado . " " . "SUB-TOTAL EXENTO:", 0, 0, 'R');
		$this->SetFont('Arial','',9);
		$this->Cell(35, 4, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		$this->SetFont('Arial','BI',9);
		$this->Cell(20,4, "", 0, 0, 'R');
		//$this->Cell(50,4, "TOTAL USD: " . number_format($monto_usd, 2, ",", "."), 0, 0, 'L');
		$this->Cell(50,4, "", 0, 0, 'L');
		//$this->Cell(50,4, "TC: " . number_format($tasa_dia, 2, ",", "."), 0, 0, 'L');
		$this->Cell(50,4, "", 0, 0, 'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(48,4, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Arial','',9);
		$this->Cell(35, 4, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);

		// Se imprime el descuento si aplica
		if($descuento > 0) {
			$this->SetFont('Arial','BI',9);
			$this->Cell(120,4, "", 0, 0, 'R');
			$this->SetFont('Arial','B',9);
			//$this->Cell(40,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%  (" . number_format($monto_total-$monto_sin_descuento, 2, ",", ".") . "):", 0, 0, 'R');
			$this->Cell(48,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Arial','',9);
			//$this->Cell(40, 4, number_format($monto_total, 2, ",", "."), 0, 0, 'R');
			$this->Cell(35, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
			$this->Ln(5);
		}
		//

		//$this->Cell(110, 4, "Tasa de cambio Publicada por el B.C.V. según la fecha de emisión de esta factura.", 0, 0, 'L');
		$this->Cell(110, 4, "", 0, 0, 'L');
		$this->SetFont('Arial','B',9);
		$this->Cell(58,4, "IVA $alicuota % SOBRE  $moneda " . number_format($row["gravado"], 2, ",", ".") . ": ", 0, 0, 'R');
		$this->SetFont('Arial','',9);

		$this->Cell(35, 4, number_format($row["iva"], 2, ",", "."), 0, 0, 'R');
		$this->Ln(5);
		$this->SetFont('Arial','B',9);
		// $this->Cell(30, 4, "Unidades: $unidades", 0, 0, 'R');
		$this->Cell(30, 4, "", 0, 0, 'R');
		// $this->Cell(90, 4, strtoupper($nota), 0, 0, 'R');
		$this->Cell(90, 4, "", 0, 0, 'R');
		$this->Cell(48, 4, "TOTAL $moneda:", 0, 0, 'R');
		$this->SetFont('Arial','',9);
		$this->Cell(35, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
		
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,9);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
			IFNULL(b.codigo, '') AS codigo, 
			LTRIM(RTRIM(CONCAT(IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, ''), ' ', IFNULL(a.lote, '')))) AS articulo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') as vencimiento, 
			a.cantidad_articulo AS cantidad, 
			(SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			a.alicuota, 
			a.precio_unidad, 
			a.precio, 
			c.nombre AS fabricante, 
			a.descuento, a.precio_unidad_sin_desc AS precio_ful 
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

	$pdf->SetFont('Arial','',9);
	$pdf->Cell(10, 5);
	$pdf->Cell(25, 5, number_format($row["cantidad"], 0, "", ""), "", 0, 'C');

	if(strlen($row["articulo"]) < 42) 
		$pdf->Cell(85, 5, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(85, 5, substr(trim($row["articulo"]), 0, 42), 0, 0, 'L');



	$pdf->Cell(30, 5, number_format($row["precio_ful"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(20, 5, number_format($row["alicuota"], 2, ",", "."), 0, 0, 'R');
	// $pdf->Cell(10, 3, floatval($row["descuento"])>0 ? number_format($row["descuento"], 2, ",", ".") : "", 0, 0, 'R');
	$pdf->Cell(30, 5, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 42) {
		$pdf->Ln();
		$pdf->Cell(35, 5);
		$pdf->MultiCell(85, 5, substr(trim($row["articulo"]), 42, strlen($row["articulo"])), 0, 'L');
	}
	else $pdf->Ln();

	if($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>