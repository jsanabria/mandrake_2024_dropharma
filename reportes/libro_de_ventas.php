<?php
require('rcs/fpdf.php');
require("../include/connect.php");

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
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
		require("../include/connect.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia =  $row["id"] ?? '';


		$sql = "SELECT 
					a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
					a.direccion, a.telefono1, a.email1, logo  
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad = $row["ciudad"] ?? '';
		$direccion = $row["direccion"] ?? ''; 
		$cia =  $row["nombre"] ?? '';
		$logo =  $row["logo"] ?? '';

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(5);
		$this->SetFont('Arial','',6);
		// Ajustado el ancho de la celda de fecha/hora al nuevo ancho Legal (330mm aprox útiles)
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

		// Columnas superiores cabecera (Ancho Total: 331mm)
		$this->Cell(5, 5);
		$this->Cell(10, 5, "", "LTR", 0, 'L'); 
		$this->Cell(10, 5, "", "LTR", 0, 'C'); 
		$this->Cell(12, 5, "NOTA", "LTR", 0, 'C'); 
		$this->Cell(12, 5, "NOTA", "LTR", 0, 'C'); 
		$this->Cell(12, 5, "NRO", "LTR", 0, 'C'); 
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C'); 
		$this->Cell(45, 5, "", "LTR", 0, 'L'); // ¡Aumentado a 45mm para la Razón Social!
		$this->Cell(15, 5, "", "LTR", 0, 'L'); // Aumentado RIF a 15mm
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(44, 5, "DEBITO FISCAL", 1, 0, 'C');
		$this->Cell(55, 5, "RETENCION IVA", 1, 0, 'C'); // Ampliado a 55mm totales
		$this->Cell(55, 5, "RETENCION ISLR", 1, 0, 'C'); // Ampliado a 55mm totales
		$this->Ln(5);

		// Columnas inferiores cabecera
		$this->Cell(5, 5);
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'L');
		$this->Cell(10, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(12, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(12, 5, "DEBITO", "LBR", 0, 'C'); 
		$this->Cell(12, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(15, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(45, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L'); 
		$this->Cell(15, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(18, 5, "VENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(18, 5, "IMPUESTO", "LBR", 0, 'R');
		
		// RETENCION IVA (Total 55mm)
		$this->Cell(30, 5, "N. COMP.", "LBR", 0, 'R'); // ¡Ahora tiene 30mm limpios!
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'R');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'R');
		
		// RETENCION ISLR (Total 55mm)
		$this->Cell(30, 5, "N. COMP.", "LBR", 0, 'R'); // ¡Ahora tiene 30mm limpios!
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'R');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de página
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr)
	{
		$this->SetFont('Arial','B',5);
		$this->Ln();
		// Alineación exacta con la nueva estructura de columnas ampliada (136mm antes de montos)
		$this->Cell(136, 4, "", 0, 0, 'R'); 
		$this->Cell(18, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(8, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		
		// Secciones de retención alineadas (30 comp + 10 fecha)
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

		$this->SetFont('Arial','',5);
		$this->Cell(5, 5);
		$this->Cell(100, 5, "RESUMEN EN CUADRO: VENTAS", 1, 0, 'C');
		$this->Cell(20, 5, "BASE", 1, 0, 'C');
		$this->Cell(20, 5, "DEBITO", 1, 0, 'C');
		$this->Cell(20, 5, "", 0, 0, 'C');
		$this->Cell(20, 5, "IVA RETENIDO", 1, 0, 'C');
		$this->Cell(20, 5, "ISLR RETENIDO", 1, 0, 'C'); 
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Ventas Exentas y/o sin derecho a credito fiscal"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_exenta, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Ventas Alicuota General 16%"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_gravable, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format($_retiva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_retislr, 2, ",", "."), 1, 0, 'R'); 
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Ventas Alicuota Adicional"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Ventas Alicuota Reducida"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Ln();
		
		$this->Cell(5, 5);
		$this->Cell(120, 5, function_exists('iconv') ? iconv('UTF-8', 'ISO-8859-1//TRANSLIT', strtoupper("Total Débitos Fiscales del Mes:")) : strtoupper("Total Debitos Fiscales del Mes:"), 1, 0, 'L');
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
$pdf->SetFont('Arial','',8);

$sql = "SELECT 
			a.id, 
			a.`tipo_documento`, 
			IF(a.documento = 'ND' OR a.documento = 'NC', '', a.nro_documento) AS nro_documento,  
			IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
			IF(a.documento = 'ND', a.nro_documento, '') AS nota_debito, 
			a.`documento`, 
			REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 
			a.`nro_control`, 
			b.`nombre` AS cliente, 
			b.`ci_rif`, 
			date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			a.`total`, 
			a.`iva`, 
			a.`estatus`, a.descuento,  
			0 AS ret_iva, IFNULL(NULL, '') AS ref_iva, '' AS fecha_retiva, 
			0 AS ret_islr, IFNULL(NULL, '') AS ref_islr, '' AS fecha_retislr 
		FROM 
			salidas AS a 
			LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
			a.tipo_documento = 'TDCFCV' AND 
			a.fecha BETWEEN '$xfecha 00:00:00' AND '$yfecha 23:59:59' AND SUBSTRING(LTRIM(IFNULL(a.nro_documento, '')), 1, 7) <> 'PREFACT' 
			AND a.id <> 1245 
		ORDER BY a.fecha, a.nro_control;"; 
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
	$desc = floatval($row["descuento"]);
	$pdf->SetFont('Arial', '', 5);

	$pdf->Cell(5, 4);
	$pdf->Cell(10, 4, $row["fecha"] ?? '', 0, 0, 'L');
	
	$pdf->Cell(10, 4, str_replace("FACT-", "", $row["nro_documento"] ?? ''), 0, 0, 'C'); 
	$pdf->Cell(12, 4, str_replace("NC-", "", $row["nota_credito"] ?? ''), 0, 0, 'C');   
	$pdf->Cell(12, 4, str_replace("ND-", "", $row["nota_debito"] ?? ''), 0, 0, 'C');    
	$pdf->Cell(12, 4, $row["afectado"] ?? '', 0, 0, 'C');
	$pdf->Cell(15, 4, $row["nro_control"] ?? '', 0, 0, 'C');
	
	$cliente = $row["cliente"] ?? '';
	$estatus = trim($row["estatus"] ?? '');
	// Ampliado el substring a 40 caracteres para aprovechar el ancho físico del Legal
	$pdf->Cell(45, 4, substr(($estatus =="ANULADO" ? "ANULADA" : $cliente), 0, 40), 0, 0, 'L');
	$pdf->Cell(15, 4, ($estatus =="ANULADO" ? "" : ($row["ci_rif"] ?? '')), 0, 0, 'L');
	
	$total_doc = floatval($row["total"] ?? 0);
	$pdf->Cell(18, 4, $estatus =="ANULADO" ? "" : ($total_doc == 0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$total_doc, 2, ",", ".")), 0, 0, 'R');
	$_total += $estatus =="ANULADO" ? 0 : (($row["documento"]=="NC" ? -1 : 1)*$total_doc);

	// Base Exenta
	$sql = "SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) AS exenta FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'"; 
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$exenta_val = floatval($row2["exenta"] ?? 0);
	$pdf->Cell(18, 4, $estatus =="ANULADO" ? "" : ($exenta_val==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($exenta_val - ($exenta_val*($desc/100))), 2, ",", ".")), 0, 0, 'R');
	$_exenta += $estatus =="ANULADO" ? 0 : (($row["documento"]=="NC" ? -1 : 1)*($exenta_val - ($exenta_val*($desc/100))));

	// Base Gravable
	$sql = "SELECT SUM(IF(alicuota>0, precio, 0)) AS gravable FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$gravable_val = floatval($row2["gravable"] ?? 0);
	$pdf->Cell(18, 4, $estatus =="ANULADO" ? "" : ($gravable_val==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*($gravable_val - ($gravable_val*($desc/100))), 2, ",", ".")), 0, 0, 'R');
	$_gravable += $estatus =="ANULADO" ? 0 : (($row["documento"]=="NC" ? -1 : 1)*($gravable_val - ($gravable_val*($desc/100))));

	// Alicuota % e Impuesto IVA
	$sql = "SELECT MAX(alicuota) AS alicuota_iva FROM entradas_salidas 
			WHERE id_documento = " . $row["id"] . " AND tipo_documento = '" . $row["tipo_documento"] . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$alicuota_iva = floatval($row2["alicuota_iva"] ?? 0);
	$pdf->Cell(8, 4, ($estatus =="ANULADO" ? " " : ($alicuota_iva==0 ? "" : number_format($alicuota_iva, 2, ",", "."))), 0, 0, 'R');
	
	$iva_doc = floatval($row["iva"] ?? 0);
	$pdf->Cell(18, 4, ($estatus =="ANULADO" ? "" : ($iva_doc==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$iva_doc, 2, ",", "."))), 0, 0, 'R');
	$_iva += $estatus =="ANULADO" ? 0 : (($row["documento"]=="NC" ? -1 : 1)*$iva_doc);

	/**** RETENCION IVA ***/
	$sql = "SELECT 
				b.referencia AS comprobante, a.fecha, b.monto_bs  
			FROM 
				cobros_cliente AS a 
				JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			WHERE a.id_documento = " . $row["id"] . " AND b.metodo_pago IN ('RI', 'RJ');";	
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	
	$comp_iva = $row2["comprobante"] ?? '';
	$fecha_ret_iva = $row2["fecha"] ?? '';
	$monto_ret_iva = floatval($row2["monto_bs"] ?? 0);
	
	$pdf->Cell(30, 4, $comp_iva, 0, 0, 'R'); // Ampliado a 30mm en bucle
	$pdf->Cell(10, 4, $fecha_ret_iva, 0, 0, 'R');
	$pdf->Cell(15, 4, $monto_ret_iva == 0 ? "" : number_format($monto_ret_iva, 2, ",", "."), 0, 0, 'R');
	$_retiva += $monto_ret_iva;

	/**** RETENCION ISLR ***/
	$sql = "SELECT 
				b.referencia AS comprobante, a.fecha, b.monto_bs  
			FROM 
				cobros_cliente AS a 
				JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			WHERE a.id_documento = " . $row["id"] . " AND b.metodo_pago = 'RR';";	
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	
	$comp_islr = $row2["comprobante"] ?? '';
	$fecha_ret_islr = $row2["fecha"] ?? '';
	$monto_ret_islr = floatval($row2["monto_bs"] ?? 0);
	
	$pdf->Cell(30, 4, $comp_islr, 0, 0, 'R'); // Ampliado a 30mm en bucle
	$pdf->Cell(10, 4, $fecha_ret_islr, 0, 0, 'R');
	$pdf->Cell(15, 4, $monto_ret_islr == 0 ? "" : number_format($monto_ret_islr, 2, ",", "."), 0, 0, 'R');
	$_retislr += $monto_ret_islr;
	
	$pdf->Ln();
	$items++;
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr); 
	
require("../include/desconnect.php");
$pdf->Output();
?>