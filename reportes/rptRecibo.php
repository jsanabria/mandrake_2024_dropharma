<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('rcs/fpdf.php');
require("../include/connect.php");

$recibo = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["titulo_reporte"] = "RECIBO ABONO CLIENTE";

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
	    $this->Image("../carpetacarga/" . $GLOBALS["logo"], 10, 10, 50);
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		//$this->SetY(-40);
		/*
		$this->SetFont('ARIAL','B',10);
		$this->Ln();
		$this->Cell(5,5);
		$this->Cell(160,5,"Total: ",0,0,'R');
		$this->Cell(35,5,number_format($GLOBALS["monto"], 2, '.', ','),1,0,'R');
		$this->Ln(10);
		*/
		$this->SetFont('ARIAL','B',10);
		$this->Cell(5,15);
		//$this->MultiCell(195,5,"Note: " . $GLOBALS["nota"],1,'L');
		$this->Cell(25,15, "");
		$this->Cell(50,15, "_______________________");
		$this->Ln();
		$this->Cell(20,5, "");
		$this->Cell(70,5, "CONFORME", 0, 0, "C");
		$this->MultiCell(110,5,"RECIBO DE RECEPCION DE ABONO DE CLIENTE.",0,'L');

	}
	
	function EndReport($recibo, $representante, $cia, $firma)
	{
	}
}

$sql = "SELECT 
			a.cliente, a.nro_recibo, 
			date_format(a.fecha, '%d-%m-%Y') AS fecha, date_format(a.fecha, '%d/%m/%Y') AS fecha_pago, 
			a.pago, a.nota   
		FROM 
			abono a 
		WHERE a.id = '$recibo';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cliente = $row["cliente"];
$nro_recibo = $row["nro_recibo"];

$fecha = $row["fecha"];

$fecha_pago = $row["fecha_pago"];
$monto = $row["pago"];
$GLOBALS["submonto"] = $monto;
$GLOBALS["tax"] = 0;
$GLOBALS["totaltax"] = 0;
$GLOBALS["monto"] = $monto;
$nota = $row["nota"];
$GLOBALS["nota"] = $nota;



$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";

$rs = mysqli_query($link, $sql);
$saldo_actual = "";
$saldo = 0;
if($row = mysqli_fetch_array($rs)) {
	$saldo_actual = "(Saldo actual: *** Bs. " . number_format($row["saldo"], 2, ".", ",") . " ***)";
	$saldo = $row["saldo"];
}


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
$email = $row["email1"];
$address = $row["direccion"];
$cia =  $row["nombre"];
$GLOBALS["logo"] =  $row["logo"];
$ci_rif = $row["ci_rif"];
$phone1 = $row["telefono1"];
$webpage = "";


// Creaciσn del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('ARIAL','B',10);

$sql = "SELECT 
			c.nombre AS cliente_nombre, 
			'' AS pais, c.ciudad, c.web AS codigo_postal, 
			c.direccion AS address1, '' AS address2, 
			c.telefono1, c.telefono2, 
			c.id AS codigo_cliente 
		FROM 
			cliente c 
		WHERE c.id = '$cliente';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cliente_nombre = $row["cliente_nombre"];
$ciudad = $row["ciudad"];
$pais = $row["pais"];
$address1 = $row["address1"];
$address2 = $row["address2"];
$telefono1 = $row["telefono1"];
$telefono2 = $row["telefono2"];
$codigo_postal = $row["codigo_postal"];
$codigo_cliente = $row["codigo_cliente"];


$pdf->Ln();

$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,10,'',0,0,'R');
$pdf->Cell(80,10,'RECIBO',0,0,'C');

$pdf->Ln(10);
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'FECHA: ',1,0,'R');
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(40,6,$fecha,1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'NRO. RECIBO: ',1,0,'R');
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(40,6,str_pad($nro_recibo, 7, "0", STR_PAD_LEFT),1,0,'L');

$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(120,6);
$pdf->Cell(40,6,'CODIGO CLIENTE: ',1,0,'R');
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(40,6,"$codigo_cliente",1,0,'L');

$pdf->Ln();

// Datos compaρνa
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,6);
$pdf->Cell(95,6,utf8_decode($cia),0,0,'L');
$pdf->Ln();
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,5);

$pdf->Cell(95,6,$ci_rif,0,0,'L');
$pdf->SetFont('ARIAL','B',10);

$pdf->Ln(6);
$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,6);
$pdf->Cell(25,6,"CLIENTE: ","LT",0,'L');
$pdf->Cell(170,6,utf8_decode($cliente_nombre),"TR",0,'L');
$pdf->Ln();

$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,6);
$pdf->Cell(25,6,utf8_decode("DIRECCION: "),"L",0,'L');
$pdf->MultiCell(170,6,utf8_decode($address1),"R",'L');

$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,6);
$pdf->Cell(95,6,"","LTB");
$pdf->Cell(30,6,"TELEFONOS","TBR",0,'L');
$pdf->Cell(70,6,utf8_decode($telefono1) .  " " . utf8_decode($telefono2),"LTBR",0,'L');

$pdf->Ln(8);

$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(5,5);
//$pdf->Cell(25,5,"CANTIDAD",1,0,'C');
$pdf->Cell(35,5,"Tipo de Pago",1,0,'L');
$pdf->Cell(30,5,"Ref. #",1,0,'L');
$pdf->Cell(25,5,"Recibido",1,0,'R');
$pdf->Cell(15,5,"Moneda",1,0,'C');
$pdf->Cell(25,5,"Monto Bs.",1,0,'R');
$pdf->Cell(15,5,"Tasa",1,0,'R');
$pdf->Cell(25,5,"Monto Bs.",1,0,'R');
$pdf->Cell(25,5,"Saldo Bs.",1,0,'R');

$pdf->Ln();

$sql = "SELECT 
			a.id AS recibo, 
			a.monto_usd AS monto,  
			a.nota, '' AS anulado, '' AS item, 
			(SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.metodo_pago) AS metodo_pago, 
			a.referencia AS nro, a.monto_moneda, a.moneda, a.tasa_moneda, a.monto_bs, 
			a.tasa_usd, a.monto_usd, a.saldo, a.nro_recibo, IFNULL(a.cobro_cliente_reverso, 0) AS cobro_cliente_reverso, a.saldo    
		FROM 
			recarga a 
		WHERE a.abono = '$recibo' ORDER BY a.id ASC;"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$cobro_cliente_reverso = $row["cobro_cliente_reverso"];

	$amount = $row["moneda"] . " " . number_format($row["monto_moneda"], 2, ".", ",");

	/*$sql2 = "SELECT b.nro_documento FROM cobros_cliente AS a JOIN salidas AS b ON b.id = a.id_documento WHERE a.id = $cobro_cliente_reverso"; die($sql2);
	$rs4 = mysqli_query($link, $sql2);
	$nto_entrega = "";
	if($row4 = mysqli_fetch_array($rs4)) $nto_entrega = "N/E: " . $row4["nro_documento"];*/

	$pdf->Cell(5,5);
	$pdf->Cell(35,5,$row["metodo_pago"],0,0,'L');
	$pdf->Cell(30,5,"" ,0,0,'L');
	$pdf->Cell(25,5,number_format($row["monto_moneda"], 2, ".", ","),0,0,'R');
	$pdf->Cell(15,5,$row["moneda"],0,0,'C');
	$pdf->Cell(25,5,number_format($row["monto_bs"], 2, ".", ","),0,0,'R');
	$pdf->Cell(15,5,number_format($row["tasa_usd"], 2, ".", ","),0,0,'R');
	$pdf->Cell(25,5,number_format($row["monto_usd"], 2, ".", ","),0,0,'R');
	$pdf->Cell(25,5,number_format($row["saldo"], 2, ".", ","),0,0,'R');

	$pdf->Ln();
}


$pdf->SetFont('ARIAL','B',10);
$pdf->Cell(45,5);
$pdf->Cell(45,5);
$pdf->MultiCell(155,5,"$saldo_actual",0,'L');

$pdf->Cell(5,5);
//$pdf->Cell(25,6,"1",0,0,'C');
//$pdf->Cell(135,6,$row["item"],0,0,'L');
$pdf->Cell(145,6,"Total Abonado",1,0,'R');
$pdf->Cell(25,6,number_format($monto, 2, '.', ','),1,0,'R');
$pdf->Cell(25,6,number_format($saldo, 2, '.', ','),1,0,'R');
$pdf->Ln(10);




$pdf->SetFont('ARIAL','B',10);

	
$pdf->Output();
?>