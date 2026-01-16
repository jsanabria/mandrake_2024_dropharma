<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["CurrentUserName"] = isset($_REQUEST["CurrentUserName"])?$_REQUEST["CurrentUserName"]:"";

$sql = "SELECT 
			id, date_format(fecha, '%d-%m-%Y') as fecha, date_format(fecha, '%H:%i:%s') as hora, 
			cliente, nro_documento, tipo_documento, estatus, cliente, username, tasa_dia, descuento  
		FROM salidas where id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["hora"] = $row["hora"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];
$GLOBALS["direccion_cia"] = "";
$GLOBALS["username"] = $row["username"];
$GLOBALS["descuento"] = floatval($row["descuento"]);

$tasa_dia = floatval($row["tasa_dia"]);

$cliente = $row["cliente"];

class PDF extends FPDF
{
	// Cabecera de pαgina
	function Header()
	{
		// Consulto datos de la compaρνa 
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
					a.direccion, a.telefono1, a.email1, logo  
				FROM 
					compania AS a 
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad = $row["ciudad"];
		$direccion = $row["direccion"]; 
		$GLOBALS["direccion_cia"] = $direccion;
		$cia =  $row["nombre"];
		$logo =  $row["logo"];
		$ci_rif = $row["ci_rif"];

		
		
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf 
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

		$this->Image("../carpetacarga/$logo", 18, 5, 30);
		
		$this->Ln(15);
		$this->SetFont('Helvetica','B',8);
		$this->Cell(65, 3, "TICKET",0,0,'C');
		$this->Ln();
		$this->Cell(65, 3, "RIF $ci_rif", 0, 0, "C");
		$this->Ln();
		$this->SetFont('Helvetica','',8);
		$this->Cell(65, 3, utf8_decode($cia),0,0,'C');
		$this->Ln();
		$this->MultiCell(65, 3, "$direccion", '0', 'C');
		


		$this->Ln(4);
		

		$this->Cell(65,5,'RIF/C.I.: ' . $rif,'0',0,'L');
		$this->Ln();
		$this->Cell(65, 3,"RAZON SOCIAL: " . $razon_social,'0','0','L');
		$this->Ln();
		$this->Cell(65, 3,'DIRECCION: ' . $direccion_cliente,'0','0','L');
		$this->Ln();
		$this->Cell(65,5,'Telf:'.  $telf,'0','0','L');
		$this->Ln();
	

		$this->SetFont('Helvetica','B',8);
		$this->Cell(65, 3, "N/E",0,0,'C');
		$this->SetFont('Helvetica','',8);
		$this->Ln(6);

		$this->Cell(35, 3, "N/E",0,0,'L');
		$this->Cell(30, 3, $GLOBALS["invoice"],0,0,'R');
		$this->Ln();

		$this->Cell(35, 3, "FECHA: " . $GLOBALS["fecha"],0,0,'L');
		$this->Cell(30, 3, "HORA: " . $GLOBALS["hora"],0,0,'R');
		$this->Ln();

		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln(4);

		require("../include/desconnect.php");
	}
	
	// Pie de pαgina
	function Footer()
	{
		// Posiciσn: a 1,5 cm del final
		//////$this->SetY(-15);
		// Helvetica italic 8
		//////$this->SetFont('Helvetica','I',8);
		// Nϊmero de pαgina
		//////$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($id_invoice)
	{
		//$this->AddPage();
		//require("../connect.php");
		//////$this->Ln();
		//////$this->Cell(200, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');
		//require("../include/desconnect.php");

		require("../include/connect.php");


		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.nota, a.doc_afectado,  
					a.moneda, 
					a.username, a.id_documento_padre, 
					a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades, 
					a.nro_despacho, estatus  
				FROM salidas a where a.id = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = $row["alicuota_iva"];
		$nota = utf8_decode($row["nota"]);
		$moneda = utf8_decode($row["moneda"]);
		$username = utf8_decode($row["username"]);
		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];
		$estatus = $row["estatus"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];
		if($tasa_dia == 0) $tasa_dia = 1;

		$descuento = floatval($row["descuento"]);

		$unidades = $row["unidades"];
		$nro_despacho = $row["nro_despacho"];

		$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'GEN' AND activo = 'S';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota = floatval($row["alicuota"]);

		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
				FROM entradas_salidas
				WHERE tipo_documento = '" . $GLOBALS["tipo_documento"] . "' AND 
					id_documento = '$id_invoice'"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		// $this->Ln(125-$this->GetY());
		$this->SetFont('Helvetica','',8);

		$this->Cell(35, 3, "SUBSTL:", 0, 0, 'L');
		$this->Cell(30, 3, number_format(((floatval($row["exento"])+floatval($row["gravado"]))/(1+$alicuota/100)) * floatval($tasa_dia), 2, ",", "."), 0, 0, 'R');

		if($GLOBALS["descuento"] > 0) {
			$TotDesc = ((floatval($row["exento"])+floatval($row["gravado"]))/(1+$alicuota/100)) * floatval($tasa_dia);
			$this->Ln(4);
			$this->Cell(35, 3, "DESCUENTO " . number_format($GLOBALS["descuento"] , 2, ",", ".") . "%:", 0, 0, 'L');
			$this->Cell(30, 3, number_format($TotDesc*($descuento/100), 2, ",", "."), 0, 0, 'R');

			$this->Ln(4);
			$this->Cell(35, 3, "SUBSTL:", 0, 0, 'L');
			$this->Cell(30, 3, number_format($TotDesc-($TotDesc*($descuento/100)), 2, ",", "."), 0, 0, 'R');
		}

		$this->Ln(4);
		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln(4);

		/*
		$this->Cell(30, 3, "EXENTO:", 0, 0, 'L');
		$this->Cell(35, 3, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
		$this->Ln();
		*/

		if($GLOBALS["descuento"] > 0) {
			$this->Cell(15,4, "BI G" . number_format(floatval($alicuota), 2, ",", "") . ":", 0, 0, 'L');
			$this->Cell(20, 3, number_format($TotDesc-($TotDesc*($descuento/100)), 2, ",", "."), 0, 0, 'R');
			$this->Cell(30, 3, number_format(($TotDesc-($TotDesc*($descuento/100)))*($alicuota/100), 2, ",", "."), 0, 0, 'R');
		} 
		else {
			$this->Cell(15,4, "BI G" . number_format(floatval($alicuota), 2, ",", "") . ":", 0, 0, 'L');
			$this->Cell(20, 3, number_format(((floatval($row["exento"])+floatval($row["gravado"]))/(1+$alicuota/100)) * floatval($tasa_dia), 2, ",", "."), 0, 0, 'R');
			$this->Cell(30, 3, number_format(((floatval($row["exento"])+floatval($row["gravado"]))-(floatval($row["exento"])+floatval($row["gravado"]))/(1+$alicuota/100)) * floatval($tasa_dia), 2, ",", "."), 0, 0, 'R');
		}

		/*
		if($descuento > 0) {
			$this->Ln();
			$this->Cell(30,4, "DESCUENTO " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->Cell(35, 3, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
		}
		*/

		$this->Ln(4);
		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln(4);

		$this->SetFont('Helvetica','',8);
		$this->Cell(40, 3, "TOTAL", 0, 0, 'L');
		$this->Cell(25, 3, "Bs. " . number_format((floatval($row["total"])) * floatval($tasa_dia), 2, ",", "."), 0, 0, 'R');
		$this->SetFont('Helvetica','',8);
		$total = floatval($row["total"]);

		$this->Ln(4);
		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln(4);

		
		$sql = "SELECT 
					b.metodo_pago, IF(b.metodo_pago = 'RC', '', b.referencia) AS referencia, b.monto_moneda, b.moneda, 
					b.monto_usd, c.nro_recibo, c.monto_usd  
				FROM 
					cobros_cliente AS a 
					JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
					LEFT OUTER JOIN recarga AS c ON c.cobro_cliente_reverso = a.id 
				WHERE a.id_documento = $id_invoice;"; 
		$rs = mysqli_query($link, $sql) or die(mysqli_error());

		while($row = mysqli_fetch_array($rs))
		{
			$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
			$rs2 = mysqli_query($link, $sql) or die(mysqli_error());
			$row2 = mysqli_fetch_array($rs2);

			$this->Cell(40, 3, substr($row2["valor2"], 0, 11), 0, 0, 'L');
			// $this->Cell(20, 3, $row["referencia"], 0, 0, 'L');

			if($row["moneda"] != "Bs.")
				$this->Cell(25, 3, "Bs. " . (floatval($row["monto_moneda"])) * $tasa_dia, 0, 0, 'R');
			else
				$this->Cell(25, 3, $row["moneda"] . " " . $row["monto_moneda"], 0, 0, 'R');

			/*
			if(trim($row["nro_recibo"]) != "") {
				$this->SetFont('Helvetica','B',7);
				$recibo = str_pad($row["nro_recibo"], 7, "0", STR_PAD_LEFT) . " / USD " . number_format($row["monto_usd"], 2, ".", ",");
				$this->Cell(55, 4);
				$this->Cell(70, 3, "Nro. RECIBO: " . $recibo, 0, 0, 'C');
				$this->SetFont('Helvetica','',7);
			}
			*/

			$this->Ln();
		}


		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln();

		$this->Cell(35, 3, "TASA DIA Bs. " . number_format(floatval($tasa_dia), 2, ",", "."), 0, 0, 'L');
		$this->Cell(30, 3, "USD " . number_format($total, 2, ",", "."), 0, 0, 'R');
		$this->Ln();


		$this->Cell(65, 3, "Atendido por: " . $GLOBALS['username'], 0, 0, 'L');
		$this->Ln();
		/*$this->SetFont('Helvetica','I',8);
		$this->Cell(30, 3, "MH", 0, 0, 'L');
		$this->Cell(25, 3, "Z1F00" . $GLOBALS["invoice"], 0, 0, 'R');
		$this->SetFont('Helvetica','',7);*/
		$this->Ln(3);
		$this->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
		$this->Ln();

		require("../include/desconnect.php");		
	}
}

// Creaciσn del objeto de la clase heredada
// $pdf = new PDF('L', 'mm', array(155,105));
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(0,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica','',8);


// CONCAT(IFNULL(c.nombre, ''), ' - ', IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, '')) AS articulo, 
$sql = "SELECT 
			b.codigo,  
			CONCAT(IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, '')) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.costo_unidad, 
			a.costo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
			a.precio_unidad, a.precio, b.codigo_ims 
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY a.articulo DESC;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Helvetica', '',8);
	$pdf->Cell(25, 3, "COD: " . $row["codigo_ims"] . ". " . round($row["cantidad"], 0) . " x Bs. " . number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'L');
	$pdf->Ln();
	$pdf->Cell(40, 3, substr(strtoupper($row["articulo"]), 0, 27), 0, 0, 'L');
	$pdf->Cell(25, 3, number_format((floatval($row["precio"])/(1+floatval($row["alicuota"])/100)) * floatval($tasa_dia), 2, ",", "."), 0, 0, 'R');
	$pdf->Ln();

	$items++;
}
$pdf->Cell(65, 3, "--------------------------------------------------------------------------",0,0,'C');
$pdf->Ln();

/*

$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";

$rs = mysqli_query($link, $sql);
$saldo_actual = "";
if($row = mysqli_fetch_array($rs)) $saldo_actual = "\n(Saldo actual abonos: *** USD " . number_format($row["saldo"], 2, ".", ",") . " ***)";

*/

$pdf->EndReport($id);

	
require("../include/desconnect.php");

$pdf->Output();
?>