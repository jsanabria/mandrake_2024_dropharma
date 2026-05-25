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
		// Ajustado el ancho de fecha/hora al espacio Legal útil (~330mm)
		$this->Cell(330, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(330, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(2);
		
		$this->SetFont('Arial','B',14);
		$titulo_convertido = function_exists('iconv') ? iconv('UTF-8', 'ISO-8859-1', $GLOBALS["titulo"]) : $GLOBALS["titulo"];
		$this->Cell(330, 6, $titulo_convertido, 0, 0, 'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(330, 6, $GLOBALS["subtitulo"],0,0,'C');
		$this->SetFont('Arial','',5);		


		$this->Ln(8);
		

		require("../include/desconnect.php");

		// Columnas superiores cabecera (Ancho Total recalculado para Legal)
		$this->Cell(5, 5);
		$this->Cell(10, 5, "", "LTR", 0, 'L'); // Fecha
		$this->Cell(12, 5, "", "LTR", 0, 'C'); // Factura
		$this->Cell(12, 5, "NOTA", "LTR", 0, 'C'); // Crédito
		$this->Cell(12, 5, "NOTA", "LTR", 0, 'C'); // Débito (¡Añadida!)
		$this->Cell(12, 5, "NRO", "LTR", 0, 'C'); // Doc Afectado
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C'); // Control
		$this->Cell(45, 5, "", "LTR", 0, 'L'); // Nombre Razón Soc (Ampliado a 45)
		$this->Cell(15, 5, "", "LTR", 0, 'L'); // RIF (Ampliado a 15)
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(44, 5, "DEBITO FISCAL", 1, 0, 'C');
		$this->Cell(55, 5, "RETENCION IVA", 1, 0, 'C'); // Ampliado a 55mm totales para dar 30mm a N. COMP.
		$this->Cell(55, 5, "RETENCION ISLR", 1, 0, 'C'); // Ampliado a 55mm totales para dar 30mm a N. COMP.
		$this->Ln(5);

		// Columnas inferiores cabecera
		$this->Cell(5, 5);
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'L');
		$this->Cell(12, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(12, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(12, 5, "DEBITO", "LBR", 0, 'C'); // ¡Añadida!
		$this->Cell(12, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(15, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(45, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(15, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(18, 5, "COMPRAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(18, 5, "IMPUESTO", "LBR", 0, 'R');
		
		// RETENCION IVA (Total 55mm)
		$this->Cell(30, 5, "N. COMP.", "LBR", 0, 'R'); // Ampliado de 18 a 30mm
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'R');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'R');
		
		// RETENCION ISLR (Total 55mm)
		$this->Cell(30, 5, "N. COMP.", "LBR", 0, 'R'); // Ampliado de 18 a 30mm
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'R');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de página
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',6);
		$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr)
	{
		$this->SetFont('Arial','B',5);
		$this->Ln();
		// Alineación recalculada con la nueva estructura de columnas (136mm de desfase antes de montos numéricos)
		$this->Cell(136, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(8, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		
		// Bloque Retenciones alineados (30mm Comp + 10mm Fecha)
		$this->Cell(40, 4, "", 0, 0, 'R');
		$this->Cell(14, 4, number_format($_retiva, 2, ",", "."), 0, 0, 'R');
		
		$this->Cell(40, 4, "", 0, 0, 'R');
		$this->Cell(14, 4, number_format($_retislr, 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		$this->Cell(230, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');

		$this->Ln(5);
		if ($this->GetY() > 151) { 
			$this->AddPage();
		}

		// Cuadro de Resumen Final con la columna adicional de ISLR RETENIDO
		$this->SetFont('Arial','',5);
		$this->Cell(5, 5);
		$this->Cell(100, 5, "RESUMEN EN CUADRO: COMPRAS", 1, 0, 'C');
		$this->Cell(20, 5, "BASE", 1, 0, 'C');
		$this->Cell(20, 5, "CREDITO", 1, 0, 'C');
		$this->Cell(20, 5, "", 0, 0, 'C');
		$this->Cell(20, 5, "IVA RETENIDO", 1, 0, 'C');
		$this->Cell(20, 5, "ISLR RETENIDO", 1, 0, 'C'); // ¡Añadida!
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Exentas y/o sin derecho a credito fiscal"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_exenta, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R'); // ¡Añadida!
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota General 16%"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_gravable, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format($_retiva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_retislr, 2, ",", "."), 1, 0, 'R'); // ¡Añadida!
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Adicional"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R'); // ¡Añadida!
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Reducida"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R'); // ¡Añadida!
		$this->Ln();
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(120, 5, function_exists('iconv') ? iconv('UTF-8', 'ISO-8859-1//TRANSLIT', strtoupper("Total Créditos Fiscales del Mes:")) : strtoupper("Total Creditos Fiscales del Mes:"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Ln();

		require("../include/desconnect.php");
	}
}

// CAMBIO CLAVE: Cambiado de 'A4' a 'Legal' (Oficio)
$pdf = new PDF('L', 'mm', 'Legal');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',6);

$sql = "SELECT 
			IFNULL(a.fecha_registro_retenciones, a.fecha)  AS fecfac, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			IF(a.documento = 'NC' OR a.documento = 'ND', '', a.nro_documento) AS nro_documento, 
			IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
			IF(a.documento = 'ND', a.nro_documento, '') AS nota_debito, 
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
			a.estatus, ret_islr, 
			0 AS ret_iva, 0 AS ref_iva, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_retiva, 
			0 AS ret_islr, 0 AS ref_islr, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_retislr 
		FROM 
			entradas AS a 
			LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE 
			a.tipo_documento = 'TDCFCC' AND 
			a.fecha_libro_compra BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' AND a.estatus = 'PROCESADO' 
		UNION ALL 	
		SELECT 
			IFNULL(a.fecha_registro, a.fecha) AS fecfac, 
			DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
			IF(a.tipo_documento = 'NC' OR a.tipo_documento = 'ND', '', a.documento) AS nro_documento, 
			IF(a.tipo_documento = 'NC', a.documento, '')  AS nota_credito, 
			IF(a.tipo_documento = 'ND', a.documento, '')  AS nota_debito, 
			a.doc_afectado AS doc_afectado, 
			a.nro_control,  
			b.nombre AS proveedor, 
			b.ci_rif, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS monto_total, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
			a.alicuota AS alicuota_iva, '' AS estatus, ret_islr, 
			a.ret_iva, a.ref_iva, IF(LTRIM(IFNULL(a.ref_iva, '')) = '', '', DATE_FORMAT(a.fecha_registro, '%d/%m/%Y')) AS fecha_retiva, 
			a.ret_islr, a.ref_islr, IF(LTRIM(IFNULL(a.ref_islr, '')) = '', '', DATE_FORMAT(a.fecha_registro, '%d/%m/%Y')) AS fecha_retislr    
		FROM 
			compra AS a
			LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE 
			a.fecha_registro BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' 
		ORDER BY fecfac, ref_iva;";
$rs = mysqli_query($link, $sql) or die(mysqli_error($link));

$items = 0;

$_total = 0.00;
$_exenta = 0.00;
$_gravable = 0.00;
$_iva = 0.00;
$_retiva = 0.00;
$_retislr = 0.00;

while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 5);

	$pdf->Cell(5, 4);
	$pdf->Cell(10, 4, $row["fecha"], 0, 0, 'L');
	$pdf->Cell(12, 4, $row["nro_documento"], 0, 0, 'C');
	$pdf->Cell(12, 4, $row["nota_credito"], 0, 0, 'C');
	$pdf->Cell(12, 4, $row["nota_debito"] ?? '', 0, 0, 'C'); // Celda de Nota de Débito añadida
	$pdf->Cell(12, 4, $row["doc_afectado"], 0, 0, 'C');
	$pdf->Cell(15, 4, $row["nro_control"], 0, 0, 'C');
	
	// Ampliado el substring a 40 caracteres aprovechando el espacio Legal
	$pdf->Cell(45, 4, substr($row["proveedor"] ?? '', 0, 40), 0, 0, 'L');
	$pdf->Cell(15, 4, $row["ci_rif"], 0, 0, 'L');
	
	$pdf->Cell(18, 4, number_format($row["total"] ?? 0, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(18, 4, number_format($row["exenta"] ?? 0, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(18, 4, number_format($row["gravable"] ?? 0, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(8, 4, number_format($row["alicuota_iva"] ?? 0, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["iva"] ?? 0, 2, ",", "."), 0, 0, 'R');
	
	/**** RETENCION IVA ***/
	$pdf->Cell(30, 4, $row["ref_iva"] ?? 0, 0, 0, 'R'); // Ampliado de 18 a 30mm en bucle
	$pdf->Cell(10, 4, $row["fecha_retiva"] ?? 0, 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["ret_iva"] ?? 0, 2, ",", "."), 0, 0, 'R');
	
	/**** RETENCION ISLR ***/
	$pdf->Cell(30, 4, $row["ref_islr"] ?? 0, 0, 0, 'R'); // Ampliado de 18 a 30mm en bucle
	$pdf->Cell(10, 4, $row["fecha_retislr"] ?? 0, 0, 0, 'R');
	$pdf->Cell(15, 4, number_format($row["ret_islr"] ?? 0, 2, ",", "."), 0, 0, 'R');
	
	$pdf->Ln();
	$items++;

	$_total += floatval($row["total"]);
	$_exenta += floatval($row["exenta"]);
	$_gravable += floatval($row["gravable"]);
	$_iva += floatval($row["iva"]);
	$_retiva += floatval($row["ret_iva"]);
	$_retislr += floatval($row["ret_islr"]);
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr);

	
require("../include/desconnect.php");

$pdf->Output();
?>