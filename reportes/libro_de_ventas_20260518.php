<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$xfecha = isset($_REQUEST["xfecha"])?$_REQUEST["xfecha"]:"0";
$yfecha = isset($_REQUEST["yfecha"])?$_REQUEST["yfecha"]:"0";

$f = explode("-", $xfecha);
$fecdesde = isset($f["2"], $f["1"], $f["0"]) ? $f["2"] . "/" . $f["1"] . "/" . $f["0"] : "00/00/0000";
$f = explode("-", $yfecha);
$fechasta = isset($f["2"], $f["1"], $f["0"]) ? $f["2"] . "/" . $f["1"] . "/" . $f["0"] : "00/00/0000";

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
		$cia =  $row["id"] ?? 0;


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
		$this->Cell(270, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(270, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(2);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(270, 6, mb_convert_encoding($GLOBALS["titulo"], "ISO-8859-1", "UTF-8"),0,0,'C');
		$this->SetFont('Arial','',12);
		$this->Ln();
		$this->Cell(270, 6, $GLOBALS["subtitulo"],0,0,'C');
		$this->SetFont('Arial','',5);		


		$this->Ln(8);
		

		require("../include/desconnect.php");

		$this->Cell(5, 5);
		$this->Cell(10, 5, "", "LTR", 0, 'L');
		$this->Cell(10, 5, "", "LTR", 0, 'C');
		$this->Cell(12, 5, "NOTA", "LTR", 0, 'C');
		$this->Cell(12, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(15, 5, "NRO", "LTR", 0, 'C');
		$this->Cell(35, 5, "", "LTR", 0, 'L');
		$this->Cell(12, 5, "", "LTR", 0, 'L');
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(18, 5, "TOTAL", "LTR", 0, 'R');
		$this->Cell(44, 5, "DEBITO FISCAL", 1, 0, 'C');
		/**** RETENCION IVA ***/
		$this->Cell(43, 5, "RETENCION IVA", 1, 0, 'C');
		/**** RETENCION ISLR ***/
		$this->Cell(43, 5, "RETENCION ISLR", 1, 0, 'C');
		$this->Ln(5);

		$this->Cell(5, 5);
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'L');
		$this->Cell(10, 5, "FACT", "LBR", 0, 'C');
		$this->Cell(12, 5, "CREDITO", "LBR", 0, 'C');
		$this->Cell(12, 5, "DOC. AFEC", "LBR", 0, 'C');
		$this->Cell(15, 5, "CONTROL", "LBR", 0, 'C');
		$this->Cell(35, 5, "NOMBRE O RAZON SOCIAL", "LBR", 0, 'L');
		$this->Cell(12, 5, "RIF", "LBR", 0, 'L');
		$this->Cell(18, 5, "VENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "EXENTAS", "LBR", 0, 'R');
		$this->Cell(18, 5, "BASE", "LBR", 0, 'R');
		$this->Cell(8, 5, "%", "LBR", 0, 'R');
		$this->Cell(18, 5, "IMPUESTO", "LBR", 0, 'R');
		/**** RETENCION IVA ***/
		$this->Cell(18, 5, "N. COMP.", "LBR", 0, 'R');
		$this->Cell(10, 5, "FECHA", "LBR", 0, 'R');
		$this->Cell(15, 5, "RETENCION", "LBR", 0, 'R');
		/**** RETENCION ISLR ***/
		$this->Cell(18, 5, "N. COMP.", "LBR", 0, 'R');
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
		$this->Cell(111, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_total, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_exenta, 2, ",", "."), 0, 0, 'R');
		$this->Cell(18, 4, number_format($_gravable, 2, ",", "."), 0, 0, 'R');
		$this->Cell(8, 4, "", 0, 0, 'R');
		$this->Cell(18, 4, number_format($_iva, 2, ",", "."), 0, 0, 'R');
		$this->Cell(29, 4, "", 0, 0, 'R');
		$this->Cell(14, 4, number_format($_retiva, 2, ",", "."), 0, 0, 'R');
		$this->Cell(29, 4, "", 0, 0, 'R');
		$this->Cell(14, 4, number_format($_retislr, 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		$this->Cell(182, 5, "TOTAL FACTURAS: "  . $items, 0, 0, 'R');


		$this->Ln(5);
		if ($this->GetY() > 151) { 
			$this->AddPage();
		}

		$this->SetFont('Arial','',5);
		$this->Cell(5, 5);
		$this->Cell(100, 5, "RESUMEN EN CUADRO: COMPRAS", 1, 0, 'C');
		$this->Cell(20, 5, "BASE", 1, 0, 'C');
		$this->Cell(20, 5, "CREDITO", 1, 0, 'C');
		$this->Cell(20, 5, "", 0, 0, 'C');
		$this->Cell(20, 5, "IVA RETENIDO", 1, 0, 'C');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Exentas y/o sin derecho a credito fiscal"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_exenta, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota General 16%"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_gravable, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format($_retiva, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Adicional"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(100, 5, strtoupper("Suma de las: Compras Alicuota Reducida"), 1, 0, 'L');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Cell(20, 5, "", 0, 0, 'R');
		$this->Cell(20, 5, number_format(0, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		$this->Ln();
		$this->Cell(5, 5);
		$this->Cell(120, 5, strtoupper("Total Créditos Fiscales del Mes:"), 1, 0, 'L');
		$this->Cell(20, 5, number_format($_iva, 2, ",", "."), 1, 0, 'R');
		$this->Ln();
		require("../include/desconnect.php");
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('L', 'mm', 'A4');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$sql = "SELECT 
			a.id, 
			a.`tipo_documento`, 
			IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', REPLACE(a.nro_documento, 'ND-', '')), a.nro_documento)) AS nro_documento,  
			IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
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
		ORDER BY a.nro_documento;"; 
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
	$desc = floatval($row["descuento"] ?? 0);
	$pdf->SetFont('Arial', '', 5);

	$pdf->Cell(5, 4);
	$pdf->Cell(10, 4, $row["fecha"] ?? '', 0, 0, 'L');
	$pdf->Cell(10, 4, str_replace("FACT-", "", $row["nro_documento"] ?? ''), 0, 0, 'C');
	$pdf->Cell(12, 4, str_replace("NC-", "", $row["nota_credito"] ?? ''), 0, 0, 'C');
	$pdf->Cell(12, 4, $row["affected"] ?? $row["afectado"] ?? '', 0, 0, 'C');
	$pdf->Cell(15, 4, $row["nro_control"] ?? '', 0, 0, 'C');
	$pdf->Cell(35, 4, substr((trim($row["estatus"] ?? '') == "ANULADO" ? "ANULADA" : ($row["cliente"] ?? '')), 0, 30), 0, 0, 'L');
	$pdf->Cell(12, 4, (trim($row["estatus"] ?? '') == "ANULADO" ? "" : ($row["ci_rif"] ?? '')), 0, 0, 'L');
	$pdf->Cell(18, 4, trim($row["estatus"] ?? '') == "ANULADO" ? "" : (($row["total"] ?? 0) == 0 ? "" : number_format((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($row["total"] ?? 0), 2, ",", ".")), 0, 0, 'R');
	$_total += trim($row["estatus"] ?? '') == "ANULADO" ? 0 : floatval((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($row["total"] ?? 0));

	$sql = "SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) AS exenta FROM entradas_salidas 
			WHERE id_documento = " . intval($row["id"]) . " AND tipo_documento = '" . mysqli_real_escape_string($link, $row["tipo_documento"]) . "'"; 
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$val_exenta = floatval($row2["exenta"] ?? 0);
	$pdf->Cell(18, 4, trim($row["estatus"] ?? '') == "ANULADO" ? "" : ($val_exenta == 0 ? "" : number_format((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($val_exenta - ($val_exenta * ($desc / 100))), 2, ",", ".")), 0, 0, 'R');
	$_exenta += trim($row["estatus"] ?? '') == "ANULADO" ? 0 : (($row["documento"] ?? '') == "NC" ? -1 : 1) * ($val_exenta - ($val_exenta * ($desc / 100)));

	$sql = "SELECT SUM(IF(alicuota>0, precio, 0)) AS gravable FROM entradas_salidas 
			WHERE id_documento = " . intval($row["id"]) . " AND tipo_documento = '" . mysqli_real_escape_string($link, $row["tipo_documento"]) . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$val_gravable = floatval($row2["gravable"] ?? 0);
	$pdf->Cell(18, 4, trim($row["estatus"] ?? '') == "ANULADO" ? "" : ($val_gravable == 0 ? "" : number_format((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($val_gravable - ($val_gravable * ($desc / 100))), 2, ",", ".")), 0, 0, 'R');
	$_gravable += trim($row["estatus"] ?? '') == "ANULADO" ? 0 : (($row["documento"] ?? '') == "NC" ? -1 : 1) * ($val_gravable - ($val_gravable * ($desc / 100)));

	$sql = "SELECT MAX(alicuota) AS alicuota_iva FROM entradas_salidas 
			WHERE id_documento = " . intval($row["id"]) . " AND tipo_documento = '" . mysqli_real_escape_string($link, $row["tipo_documento"]) . "'";
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	$alicuota_iva = floatval($row2["alicuota_iva"] ?? 0);
	$pdf->Cell(8, 4, (trim($row["estatus"] ?? '') == "ANULADO" ? " " : $alicuota_iva == 0) ? "" : number_format($alicuota_iva, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(18, 4, (trim($row["estatus"] ?? '') == "ANULADO" ? "" : (($row["iva"] ?? 0) == 0 ? "" : number_format((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($row["iva"] ?? 0), 2, ",", "."))), 0, 0, 'R');
	$_iva += trim($row["estatus"] ?? '') == "ANULADO" ? 0 : floatval((($row["documento"] ?? '') == "NC" ? -1 : 1) * ($row["iva"] ?? 0));

	/**** RETENCION IVA ***/
	$sql = "SELECT 
				b.referencia AS comprobante, a.fecha, b.monto_bs  
			FROM 
				cobros_cliente AS a 
				JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			WHERE a.id_documento = " . intval($row["id"]) . " AND b.metodo_pago = 'RI';";	
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	
	$retiva_comprobante = $row2["comprobante"] ?? '';
	$retiva_fecha = $row2["fecha"] ?? '';
	$retiva_monto = floatval($row2["monto_bs"] ?? 0);

	$pdf->Cell(18, 4, $retiva_comprobante, 0, 0, 'R');
	$pdf->Cell(10, 4, $retiva_fecha, 0, 0, 'R');
	$pdf->Cell(15, 4, $retiva_monto == 0 ? "" : number_format($retiva_monto, 2, ",", "."), 0, 0, 'R');
	$_retiva += $retiva_monto;

	/**** RETENCION ISLR ***/
	$sql = "SELECT 
				b.referencia AS comprobante, a.fecha, b.monto_bs  
			FROM 
				cobros_cliente AS a 
				JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			WHERE a.id_documento = " . intval($row["id"]) . " AND b.metodo_pago = 'RR';";	
	$rs3 = mysqli_query($link, $sql) or die(mysqli_error($link));
	$row2 = mysqli_fetch_array($rs3);
	
	$retislr_comprobante = $row2["comprobante"] ?? '';
	$retislr_fecha = $row2["fecha"] ?? '';
	$retislr_monto = floatval($row2["monto_bs"] ?? 0);

	$pdf->Cell(18, 4, $retislr_comprobante, 0, 0, 'R');
	$pdf->Cell(10, 4, $retislr_fecha, 0, 0, 'R');
	$pdf->Cell(15, 4, $retislr_monto == 0 ? "" : number_format($retislr_monto, 2, ",", "."), 0, 0, 'R');
	$_retislr += $retislr_monto;
	$pdf->Ln();

	$items++;
}

$pdf->EndReport($items, $_total, $_exenta, $_gravable, $_iva, $_retiva, $_retislr); 
	
require("../include/desconnect.php");
$pdf->Output();
?>