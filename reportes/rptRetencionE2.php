<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$Nretencion = $_GET["Nretencion"];
$GLOBALS["Nretencion"] = $Nretencion;

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
		require("../include/connect.php");
		/*$sql = "SELECT 
					date_format(a.fecha, '%Y-%m-%d') fecha_factura 
				FROM compra a WHERE a.id =  ".$GLOBALS["Nretencion"].";"; 
		$rs = mysqli_query($link, $sql);
		$row_datos = mysqli_fetch_array($rs);
		$fecha_factura = $row_datos["fecha_factura"];

		$date = date_create($fecha_factura);
		$fecha_solictud = date_format($date, 'Y-m-d');*/

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
		$nit = "";
		
		$sql = "SELECT 
					replace(b.ci_rif, '-', '') AS RIF, 
					b.nombre AS proveedor, 
					a.nro_documento AS nro_factura, 
					a.nro_control AS nro_control, 
					(SELECT SUM(IF(IFNULL(alicuota,0)=0, 0, costo)) AS gravado 
					FROM entradas_salidas WHERE tipo_documento = 'TDCFCC' AND id_documento = a.id) AS base_imponible, 
					'' AS tipo_docu, 
					date_format(a.fecha_registro_retenciones,'%d/%m/%Y') as fecha_emision, 
					date_format(a.fecha,'%d/%m/%Y') as fecha_factura, 
					a.tipo_islr AS porc_apli, 
					a.sustraendo AS sustraendo, 
					a.ret_islr AS monto_ret, 
					'' AS codigo, 
					a.nota AS descripcion, 
					'' AS consecutivo 
				FROM 
					entradas a 
					JOIN proveedor AS b ON b.id = a.proveedor 
				WHERE 
					a.id = ".$GLOBALS["Nretencion"].";";  


		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$proveedor = $row["proveedor"];
		$RIF = substr($row["RIF"],0,1)."-".substr($row["RIF"],1,strlen($row["RIF"])-2)."-".substr($row["RIF"],strlen($row["RIF"])-1,1);
		
		$this->Image('../images/Logo.png',10,10,-150);
		
		$this->SetFont('Courier','',6);
		//Linea 1
		$this->Ln(24);
		$this->Cell(75,4);
		$this->Cell(10,4,"FECHA",0,0,'L');
		$this->Cell(15,4,$row["fecha_factura"],0,0,'R');
		$this->Ln();
		$this->Cell(75,4);
		$this->Cell(10,4,"HORA",0,0,'L');
		$this->Cell(15,4,date("H:i:s"),0,0,'R');
		$this->Ln();
		//Linea 2
		$this->Cell(100,4,"COMPROBANTE DE RETENCION DE IMPUESTO",0,0,'C');
		$this->Ln();
		$this->Cell(100,4,str_repeat("=",38),0,0,'C');
		$this->Ln(6);
		//Linea 3
		$this->Cell(30,6,"AGENTE DE RETENCION:",0,0,'L');
		$this->Cell(70,6,$razon_social,0,0,'L');
		$this->Ln();
		//Linea 4
		$this->Cell(30,6,"RIF................:",0,0,'L');
		$this->Cell(70,6,$rif,0,0,'L');
		$this->Ln();
		//Linea 5
		$this->Cell(30,8,"NIT................:",0,0,'L');
		$this->Cell(70,8,$nit,0,0,'L');
		$this->Ln();
		//Linea 6
		$this->Cell(100,3,"DIRECCION DEL AGENTE DE RETENCION:",0,0,'L');
		$this->Ln();
		$this->MultiCell(100,3,$direccion,0,'L');
		$this->Ln();
		//Linea 7
		$this->Cell(30,6,"BENEFICIARIO.......:",0,0,'L');
		$this->Cell(70,6,$proveedor,0,0,'L');
		$this->Ln();
		//Linea 8
		$this->Cell(30,6,"RIF................:",0,0,'L');
		$this->Cell(70,6,$RIF,0,0,'L');
		$this->Ln();
		//Linea 9
		$this->Cell(30,6,"IMPTE. OBJETO DE LA RETENCION:",0,0,'L');
		$this->Cell(70,6,"Bs. ".number_format($row["base_imponible"],2,",","."),0,0,'R');
		$this->Ln();
		//Linea 10
		$this->Cell(30,6,"DOCUMENTO:",0,0,'L');
		$this->Cell(70,6,$row["tipo_docu"]." ".$row["nro_factura"],0,0,'L');
		$this->Ln();
		//Linea 11
		$this->Cell(30,6,"TASA APLICABLE:",0,0,'L');
		$this->Cell(70,6,number_format($row["porc_apli"],2,",",".")."%",0,0,'R');
		$this->Ln();
		//Linea 11
		$this->Cell(30,6,"SUSTRAENDO:",0,0,'L');
		$this->Cell(70,6,"Bs. ".number_format($row["sustraendo"],2,",","."),0,0,'R');
		$this->Ln();
		//Linea 11
		$this->Cell(30,6,"CANTIDAD RETENIDA:",0,0,'L');
		$this->Cell(70,6,"Bs. ".number_format($row["monto_ret"],2,",","."),0,0,'R');
		$this->Ln();
		//Linea 12
		$this->Cell(100,6,"DECRETO   1808 DE FECHA 23 DE NOVIEMBRE DE 1997",0,0,'L');
		$this->Ln();
		//Linea 13
		$this->Cell(100,6,"GACETA DEL: 12/05/1997 NUMERO 36203",0,0,'L');
		$this->Ln();
		//Linea 14
		$this->Cell(30,6,"CONCEPTO...........:",0,0,'L');
		$this->Cell(5,6,$row["codigo"],0,0,'L');
		$this->Cell(65,6,$row["descripcion"],0,0,'L');
		$this->Ln();
		//Linea 15
		$this->Cell(30,6,"FECHA..............:",0,0,'L');
		$this->Cell(70,6,$row["fecha_factura"],0,0,'L');
		$this->Ln();
		require("../include/desconnect.php");
	}
	
	// Pie de pαgina
	function Footer()
	{
		$this->ln(5);
		$this->Cell(40,6,'FIRMA DEL AGENTE DE RETENCION',"T",0,'C');
		$this->SetY(-30);

		$this->Image('../images/firma_reterncion.jpg',80,150,50);
	}
	
	function EndReport()
	{
		$this->Ln(10);
		$this->Cell(100);
		$this->Ln();
	}
}

// Creaciσn del objeto de la clase heredada
$pdf = new PDF();
$pdf->SetMargins(10,15,10);
$pdf->AliasNbPages();
$pdf->AddPage("L","Letter");
//$pdf->SetFont("Courier");
//$pdf->SetFontSize(8);


$pdf->EndReport();

require("../include/desconnect.php");

$pdf->Output();
?>