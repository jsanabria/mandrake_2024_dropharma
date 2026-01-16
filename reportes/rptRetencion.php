<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$Nretencion = $_GET["Nretencion"];

$sql = "SELECT 
			a.ref_iva AS comprobante, DATE_FORMAT(a.fecha_registro, '%d/%m/%Y') AS fecha_emision, 
			DATE_FORMAT(a.fecha_registro, '%Y') AS anho, DATE_FORMAT(a.fecha_registro, '%m') AS mes, 
			b.nombre AS proveedor, replace(b.ci_rif, '-', '') AS RIF 
		FROM 
			compra a 
			JOIN proveedor AS b ON b.id = a.proveedor 
		WHERE a.id = $Nretencion"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);


$GLOBALS["comprobante"] = $row["comprobante"];
$GLOBALS["fecha_emision"] = $row["fecha_emision"];
$GLOBALS["anho"] = $row["anho"];
$GLOBALS["mes"] = $row["mes"];
$GLOBALS["proveedor"] = $row["proveedor"];
$GLOBALS["RIF"] = substr($row["RIF"],0,1)."-".substr($row["RIF"],1,strlen($row["RIF"])-2)."-".substr($row["RIF"],strlen($row["RIF"])-1,1);

class PDF extends FPDF
{
	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compañía 
		require("../include/connect.php");
		// $date = new DateTime(trim($GLOBALS["anho"]) . "-" . trim($GLOBALS["mes"]) . "-01");
		$date = new DateTime("2073-09-29");
		$fecha_solictud = date_format($date, 'Y-m-d');

		$codigo_cia = 1;
		$sql = "SELECT a.nombre AS razon_social, a.ci_rif AS rif, a.direccion AS direccion, 
					CONCAT(IFNULL(a.telefono1, ''), '/', IFNULL(a.telefono2, '')) AS telefono 
				FROM compania AS a WHERE a.id = $codigo_cia;"; 
		$rs = mysqli_query($link, $sql);
		$row_datos = mysqli_fetch_array($rs);
		
		$razon_social = $row_datos["razon_social"];
		$rif = $row_datos["rif"];
		$direccion = $row_datos["direccion"];
		$telefono = $row_datos["telefono"];

		
		$this->Image('../images/Logo.png',10,10,-170);

		//$this->Image('../Images/logo.png',10,10,-150);
		$this->SetFont('Courier','',6);
		//Linea 1
		$this->Cell(0,3,$GLOBALS["fecha_emision"] . " - COMPROBANTE DE RETENCION DEL IMPUESTO AL VALOR AGREGADO",0,0,'C');
		$this->Ln();
		//Linea 2
		$this->Cell(56);
		$this->Cell(0,3,"LEY IVA – ART.11: “La Administración Tributaria podrá designar como responsable del pago del impuesto, en calidad",0,0,'L');
		$this->Ln();
		//Linea 3
		$this->Cell(56);
		$this->Cell(149,3,"de agente de retención, a quienes por sus funciones públicas o por razón de sus actividades privadas intervengan",0,0,'L');
		$this->Cell(25,3,"0.NRO.COMPROBANTE",0,0,'L');
		$this->Cell(5,3,"PAG.",0,0,'L');
		$this->Cell(20,3,"1.FECHA",0,0,'R');
		$this->Ln();
		//Linea 4
		$this->Cell(56);
		$this->Cell(149,3,"en operaciones gravadas con el impuesto establecido en este decreto con Rango, Valor y Fuerza de Ley”",0,0,'L'); 
		$this->Cell(24,3,"-----------------",0,0,'L');		
		$this->Cell(1,3,"",0,0,'L');		
		$this->Cell(5,3,"----",0,0,'L');		
		$this->Cell(7,3,"",0,0,'L');		
		$this->Cell(20,3,"---------",0,0,'L');	
		$this->Ln();	
		//Linea 5
		$this->Cell(205,3,"",0,0,'L');
		$this->Cell(25,3,$GLOBALS["comprobante"],0,0,'L');
		$this->Cell(2,3,"",0,0,'L');
		//$this->Cell(4,3,str_pad("0",2,$this->PageNo()),0,0,'L');
		$this->Cell(4,3,str_pad("0",2,1),0,0,'L');
		$this->Cell(19,3,$GLOBALS["fecha_emision"],0,0,'R');
		$this->Ln(2);

		//////
		$this->SetFont('Courier','b',10);
		$this->Cell(80,3,"R.I.F. Nro: $rif",0,0,'L');
		$this->SetFont('Courier','',6);
		$this->Ln(10);
		//////
		
		//Linea 6
		$this->Cell(50,3,"2.NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION",0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,"3.RIF DEL AGENTE DE RETECION",0,0,'L');
		$this->Cell(113,3,"",0,0,'L');
		$this->Cell(20,3,"4.PERIODO FISCAL",0,0,'R');
		$this->Ln();
		//Linea 7
		$this->Cell(50,3,str_repeat("-",46),0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,str_repeat("-",28),0,0,'L');
		$this->Cell(113,3,"",0,0,'L');
		$this->Cell(20,3,str_repeat("-",16),0,0,'R');
		$this->Ln();
		//Linea 8
		$this->Cell(50,3,$razon_social,0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,$rif,0,0,'C');
		$this->Cell(113,3,"",0,0,'L');
		$this->Cell(20,3,"AÑO:".$GLOBALS["anho"]."/"."MES:".$GLOBALS["mes"],0,0,'R');
		$this->Ln(7);
		
		//Linea 9
		$this->Cell(0,3,"5.DIRECCION FISCAL DEL AGENTE DE RETENCION",0,0,'L');
		$this->Ln();
		//Linea 10
		$this->Cell(0,3,str_repeat("-",55),0,0,'L');
		$this->Ln();
		//Linea 11
		$this->Cell(0,3,$direccion,0,0,'L');
		$this->Ln(7);


		//Linea 12
		$this->Cell(50,3,"6.NOMBRE O RAZON SOCIAL DEL SUJETO A RETENCION",0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,"7.RIF DEL CONTRIBUYENTE",0,0,'L');
		$this->Ln();
		//Linea 13
		$this->Cell(50,3,str_repeat("-",46),0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,str_repeat("-",28),0,0,'L');
		$this->Ln();
		//Linea 14
		$this->Cell(50,3,$GLOBALS["proveedor"],0,0,'L');
		$this->Cell(22,3,"",0,0,'L');
		$this->Cell(50,3,$GLOBALS["RIF"],0,0,'C');
		$this->Ln();
		
		//Linea 13
		$this->Cell(0,3,str_repeat("=",200),0,0,'L');
		$this->Ln();

		//Linea 14
		$this->Cell(18,3,'FECHA','0','0','C');
		$this->Cell(18,3,'FECHA','0','0','C');
		$this->Cell(18,3,'NUMERO','0','0','C');
		$this->Cell(18,3,'NUMERO','0','0','C');
		$this->Cell(18,3,'NUMERO','0','0','C');
		$this->Cell(18,3,'NUMERO','0','0','C');
		$this->Cell(8,3,'TIPO','0','0','C');
		$this->Cell(18,3,'NUMERO','0','0','C');
		$this->Cell(20,3,'TOTAL COMPRAS','0','0','C');
		$this->Cell(19,3,'MONTO','0','0','C');
		$this->Cell(23,3,'BASE','0','0','C');
		$this->Cell(148,3,'%','0','0','C');
		$this->Cell(19,3,'IMPUESTO','0','0','C');
		$this->Cell(19,3,'IVA','0','0','C');
		$this->Ln();

		//Linea 15
		$this->Cell(18,3,'REGISTRO','0','0','C');
		$this->Cell(18,3,'DOCUMENTO','0','0','C');
		$this->Cell(18,3,'FACTURA','0','0','C');
		$this->Cell(18,3,'CONTROL','0','0','C');
		$this->Cell(18,3,'NOTA DEBITO','0','0','C');
		$this->Cell(18,3,'NOTA CREDITO','0','0','C');
		$this->Cell(8,3,'TRANS.','0','0','C');
		$this->Cell(18,3,'FACTURA AFECTA.','0','0','C');
		$this->Cell(20,3,'INCLUYENDO IVA','0','0','C');
		$this->Cell(22,3,'EXENTO','0','0','C');
		$this->Cell(24,3,'IMPONIBLE','0','0','C');
		$this->Cell(14,3,'ALICUOTA','0','0','C');
		$this->Cell(22,3,'IVA','0','0','C');
		$this->Cell(22,3,'RETENIDO','0','0','C');
		$this->Ln();
		
		//Linea 16
		$this->Cell(0,3,str_repeat("=",200),0,0,'L');
		$this->Ln();
		require("../include/desconnect.php"); 
	}
	
	// Pie de página
	function Footer()
	{
		$date = date_create(trim($GLOBALS["anho"]) . "-" . trim($GLOBALS["mes"]) . "-01");
		$fecha_solictud = date_format($date, 'Y-m-d');
		$fecha_entrega = date('d/m/Y');
		$nota = "Este comprobante se emite en función a lo establecido en el artículo 16 de la Providencia Administrativa Nº SNAT/2025/000054 de fecha 02/07/2025";
		$this->Image('../images/firma_reterncion.jpg',10,170,50);

		$this->SetY(-60);
		$this->Ln();
		$this->Cell(37,6,"FECHA DE ENTREGA: $fecha_entrega. $nota","B",0,'L');
	}
	
	function EndReport($tot1,$tot2,$tot3,$tot4,$tot5)
	{
		$this->Ln(10);
		$this->Cell(100);
		$this->Cell(34,6,'TOTALES...',0,0,'C');
		$this->Cell(20,3,number_format($tot1,2,",","."),'0','0','R');
		$this->Cell(22,3,number_format($tot2,2,",","."),'0','0','R');
		$this->Cell(24,3,number_format($tot3,2,",","."),'0','0','R');
		$this->Cell(14,3,"",'0','0','C');
		$this->Cell(22,3,number_format($tot4,2,",","."),'0','0','R');
		$this->Cell(22,3,number_format($tot5,2,",","."),'0','0','R');
		$this->Ln();
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->SetMargins(10,15,10);
$pdf->AliasNbPages();
$pdf->AddPage("L","Letter");
//$pdf->SetFont("Courier");
//$pdf->SetFontSize(8);

/*
$sql = "SELECT 
			date_format(a.fecha_registro,'%d/%m/%Y') as fecha_emision, 
			date_format(a.fecha,'%d/%m/%Y') as fecha_factura, 
			a.nro_control AS nro_control, 
			IF(a.tipo_documento = 'FC', a.documento, '') AS nro_factura, 
			IF(a.tipo_documento = 'NC', a.documento, '') AS nota_credito, 
			IF(a.tipo_documento = 'ND', a.documento, '') AS nota_debito,
			'' AS tipo_trans, 
			a.doc_afectado AS factura_afectada, 
			a.monto_total AS total_monto, a.monto_exento AS monto_sin_iva, a.monto_gravado AS base_imponible, 
			a.alicuota AS alicuota, a.monto_iva AS impuesto_iva, a.ret_iva AS iva_retenido 
		FROM compra a 
		WHERE a.id = $Nretencion;"; 
*/

$sql = "SELECT 
			date_format(a.fecha_registro,'%d/%m/%Y') as fecha_emision, 
			date_format(a.fecha,'%d/%m/%Y') as fecha_factura, 
			a.nro_control AS nro_control, 
			IF(a.tipo_documento = 'FC', a.documento, '') AS nro_factura, 
			IF(a.tipo_documento = 'NC', a.documento, '') AS nota_credito, 
			IF(a.tipo_documento = 'ND', a.documento, '') AS nota_debito,
			'' AS tipo_trans, 
			a.doc_afectado AS factura_afectada, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total_monto, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS monto_sin_iva, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS base_imponible, 
			a.alicuota AS alicuota, IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS impuesto_iva, 
			IF(a.tipo_documento = 'NC', -1, 1) * a.ret_iva AS iva_retenido 
		FROM compra a 
		WHERE a.ref_iva = " . $GLOBALS["comprobante"] . ";";  


$rs = mysqli_query($link, $sql);

$count = 0;
$tot1 = 0;
$tot2 = 0;
$tot3 = 0;
$tot4 = 0;
$tot5 = 0;
while($row = mysqli_fetch_array($rs))
{
	//Linea 15
	$pdf->Cell(18,3,$row["fecha_emision"],'0','0','C');
	$pdf->Cell(18,3,$row["fecha_factura"],'0','0','C');
	$pdf->Cell(18,3,$row["nro_factura"],'0','0','C');
	$pdf->Cell(18,3,$row["nro_control"],'0','0','C');
	$pdf->Cell(18,3,$row["nota_debito"],'0','0','C');
	$pdf->Cell(18,3,$row["nota_credito"],'0','0','C');
	$pdf->Cell(8,3,$row["tipo_trans"],'0','0','C');
	$pdf->Cell(18,3,$row["factura_afectada"],'0','0','C');
	$pdf->Cell(20,3,number_format($row["total_monto"],2,",","."),'0','0','R');
	$pdf->Cell(22,3,number_format($row["monto_sin_iva"],2,",","."),'0','0','R');
	$pdf->Cell(24,3,number_format($row["base_imponible"],2,",","."),'0','0','R');
	$pdf->Cell(14,3,$row["alicuota"]."%",'0','0','R');
	$pdf->Cell(22,3,number_format($row["impuesto_iva"],2,",","."),'0','0','R');
	$pdf->Cell(22,3,number_format($row["iva_retenido"],2,",","."),'0','0','R');
	$pdf->Ln();

	$count++;
	$tot1 += $row["total_monto"];
	$tot2 += $row["monto_sin_iva"];
	$tot3 += $row["base_imponible"];
	$tot4 += $row["impuesto_iva"];
	$tot5 += $row["iva_retenido"];
}

$pdf->EndReport($tot1,$tot2,$tot3,$tot4,$tot5);

/*$sql = "update despacho set estatus = 1 where Ndespacho = '$Ndespacho'";
$resultado = mysql_query($sql,$enlace) or die(mysql_error());*/   

	
require("../include/desconnect.php");

$pdf->Output();
?>