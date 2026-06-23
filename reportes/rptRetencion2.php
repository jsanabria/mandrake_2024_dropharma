<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$Nretencion = isset($_GET["Nretencion"]) ? $_GET["Nretencion"] : "";
$GLOBALS["Nretencion"] = $Nretencion;

class PDF extends FPDF
{
	function Txt($txt)
	{
		return strtoupper(trim($txt));
	}

	function FmtRif($rif)
	{
		$rif = trim($rif);
		$rif = str_replace(array('-', '.', ' '), '', $rif);
		if (strlen($rif) >= 3) {
			return substr($rif,0,1)."-".substr($rif,1,strlen($rif)-2)."-".substr($rif,strlen($rif)-1,1);
		}
		return $rif;
	}

	function FmtNum($num)
	{
		return number_format((float)$num, 2, ',', '.');
	}

	function Corta($txt, $len)
	{
		$txt = $this->Txt($txt);
		return strlen($txt) > $len ? substr($txt, 0, $len) : $txt;
	}

	function Header()
	{
		require("../include/connect.php");

		$sql = "SELECT 
					date_format(a.fecha, '%Y-%m-%d') fecha_factura 
				FROM compra a 
				WHERE a.id = ".intval($GLOBALS["Nretencion"]).";";
		$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_datos = mysqli_fetch_array($rs);
		$fecha_factura_db = $row_datos["fecha_factura"];

		$codigo_cia = 1;
		$sql = "SELECT 
					a.nombre AS razon_social, 
					a.ci_rif AS rif, 
					a.direccion AS direccion, 
					CONCAT(IFNULL(a.telefono1, ''), '/', IFNULL(a.telefono2, '')) AS telefono 
				FROM compania AS a 
				WHERE a.id = $codigo_cia;";
		$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row_cia = mysqli_fetch_array($rs);

		$razon_social = $row_cia["razon_social"];
		$rif = $row_cia["rif"];
		$direccion = $row_cia["direccion"];
		$telefono = $row_cia["telefono"];
		$nit = "";

		$sql = "SELECT 
					replace(b.ci_rif, '-', '') AS RIF, 
					b.nombre AS proveedor, 
					a.documento AS nro_factura, 
					a.nro_control AS nro_control, 
					a.monto_gravado AS base_imponible, 
					'' AS tipo_docu, 
					date_format(a.fecha_registro,'%d/%m/%Y') as fecha_emision, 
					date_format(a.fecha,'%d/%m/%Y') as fecha_factura, 
					a.tipo_islr AS porc_apli, 
					a.sustraendo AS sustraendo, 
					a.ret_islr AS monto_ret, 
					'' AS codigo, 
					descripcion AS descripcion, 
					'' AS consecutivo 
				FROM compra a 
					JOIN proveedor AS b ON b.id = a.proveedor 
				WHERE a.id = ".intval($GLOBALS["Nretencion"]).";";
		$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row = mysqli_fetch_array($rs);

		$proveedor = $row["proveedor"];
		$rif_proveedor = $this->FmtRif($row["RIF"]);

		$nro_documento = ltrim($row["nro_factura"], '0');
		if ($nro_documento == "") $nro_documento = $row["nro_factura"];

		$tipo_docu = trim($row["tipo_docu"]);
		if ($tipo_docu == "") $tipo_docu = "FC";

		$codigo = trim($row["codigo"]);
		if ($codigo == "") $codigo = "11A";

		$this->SetFont('Courier','',7);
		$this->SetLineWidth(0.2);

		// Encabezado superior
		$this->ln(10);
		$this->Cell(230, 4, "COMPROBANTE DE RETENCION DEL IMPUESTO SOBRE LA RENTA", 0, 0, 'C');
		$this->SetXY(10, 25);
		$this->Cell(230, 3, str_repeat("=", 56), 0, 0, 'C');

		$this->SetXY(230, 20);
		$this->Cell(20, 4, "PAGINA...:", 0, 0, 'L');
		$this->Cell(12, 4, $this->PageNo(), 0, 0, 'L');
		$this->SetXY(230, 25);
		$this->Cell(20, 4, "FECHA....:", 0, 0, 'L');
		$this->Cell(25, 4, $row["fecha_factura"], 0, 0, 'L');
		$this->SetXY(230, 30);
		$this->Cell(20, 4, "HORA.....:", 0, 0, 'L');
		$this->Cell(25, 4, date("H:i:s"), 0, 0, 'L');

		// Datos del agente
		$this->SetXY(12, 35);
		$this->Cell(95, 4, "NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION", 0, 0, 'L');
		$this->SetXY(12, 38);
		$this->Cell(150, 4, $this->Corta($razon_social, 70), 0, 0, 'L');

		$this->SetXY(12, 45);
		$this->Cell(95, 4, "DIRECCION FISCAL DEL AGENTE DE RETENCION", 0, 0, 'L');
		$this->SetXY(12, 48);
		$this->MultiCell(125, 3, $this->Corta($direccion, 180), 0, 'L');

		$this->SetXY(142, 35);
		$this->Cell(80, 4, "REGISTRO DE INFORMACION FISCAL (R.I.F) DEL AGENTE DE RETENCION", 0, 0, 'L');
		$this->SetXY(142, 38);
		$this->Cell(80, 4, $rif, 0, 0, 'L');

		if(trim($nit) != "") {
			$this->SetXY(142, 46);
			$this->Cell(80, 4, "NIT: ".$nit, 0, 0, 'L');
		}

		// Datos del sujeto retenido
		$this->SetXY(12, 56);
		$this->Cell(100, 4, "NOMBRE O RAZON SOCIAL DEL SUJETO DE RETENCION", 0, 0, 'L');
		$this->SetXY(12, 59);
		$this->Cell(120, 4, $this->Corta($proveedor, 60), 0, 0, 'L');

		$this->SetXY(142, 56);
		$this->Cell(100, 4, "REGISTRO DE INFORMACION FISCAL (R.I.F) DEL SUJETO DE RETENCION", 0, 0, 'L');
		$this->SetXY(142, 59);
		$this->Cell(90, 4, $rif_proveedor, 0, 0, 'L');

		// Línea superior de tabla
		$y = 65;
		$this->SetXY(12, $y);
		$this->Cell(255, 3, str_repeat("=", 170), 0, 0, 'L');

		// Encabezados de tabla
		$y += 3;
		$this->SetXY(12, $y);
		$this->Cell(20, 4, "FECHA", 0, 0, 'C');
		$this->Cell(60, 4, "DOCUMENTO", 0, 0, 'C');
		$this->Cell(60, 4, "R E T E N C I O N", 0, 0, 'L');
		$this->Cell(28, 4, "BASE DE", 0, 0, 'R');
		$this->Cell(22, 4, "TASA", 0, 0, 'R');
		$this->Cell(28, 4, "SUSTRAENDO", 0, 0, 'R');
		$this->Cell(28, 4, "CANTIDAD", 0, 0, 'R');

		$y += 4;
		$this->SetXY(12, $y);
		$this->Cell(20, 4, "DOCUMENTO", 0, 0, 'C');
		$this->Cell(15, 4, "TIPO", 0, 0, 'C');
		$this->Cell(30, 4, "NUMERO", 0, 0, 'C');
		$this->Cell(10, 4, "CODIGO", 0, 0, 'C');
		$this->Cell(65, 4, "DESCRIPCION", 0, 0, 'L');
		$this->Cell(28, 4, "RETENCION", 0, 0, 'R');
		$this->Cell(22, 4, "APLICADA", 0, 0, 'R');
		$this->Cell(28, 4, "", 0, 0, 'R');
		$this->Cell(28, 4, "RETENIDA", 0, 0, 'R');

		$y += 3;
		$this->SetXY(12, $y);
		$this->Cell(255, 3, str_repeat("=", 170), 0, 0, 'L');

		// Detalle
		$y += 4;
		$this->SetXY(12, $y);
		$this->Cell(20, 4, $row["fecha_factura"], 0, 0, 'C');
		$this->Cell(15, 4, $tipo_docu, 0, 0, 'C');
		$this->Cell(30, 4, $nro_documento, 0, 0, 'C');
		$this->Cell(10, 4, $codigo, 0, 0, 'C');
		$this->Cell(65, 4, $this->Corta($row["descripcion"], 31), 0, 0, 'L');
		$this->Cell(28, 4, $this->FmtNum($row["base_imponible"]), 0, 0, 'R');
		$this->Cell(22, 4, $this->FmtNum($row["porc_apli"])."%", 0, 0, 'R');
		$this->Cell(28, 4, $this->FmtNum($row["sustraendo"]), 0, 0, 'R');
		$this->Cell(28, 4, $this->FmtNum($row["monto_ret"]), 0, 0, 'R');

		// Totales
		$yTot = 170;
		$this->SetXY(125, $yTot);
		$this->Cell(46, 4, "T O T A L E S ...:", 0, 0, 'L');
		$this->Line(152, $yTot - 2, 180, $yTot - 2);
		$this->SetXY(152, $yTot);
		$this->Cell(28, 4, $this->FmtNum($row["base_imponible"]), 0, 0, 'R');
		$this->Line(202, $yTot - 2, 230, $yTot - 2);
		$this->SetXY(202, $yTot);
		$this->Cell(28, 4, $this->FmtNum($row["sustraendo"]), 0, 0, 'R');
		$this->Line(230, $yTot - 2, 258, $yTot - 2);
		$this->SetXY(230, $yTot);
		$this->Cell(27, 4, $this->FmtNum($row["monto_ret"]), 0, 0, 'R');

		// Datos legales
		$this->SetXY(30, 183);
		$this->Cell(30, 4, "DECRETO 1808 DE FECHA 23 DE NOVIEMBRE DE 1997", 0, 0, 'L');
		$this->SetXY(30, 187);
		$this->Cell(30, 4, "GACETA DEL: 12/05/1997 NUMERO 36203", 0, 0, 'L');

		// Firma integrada en el cuerpo
		if (file_exists('../images/firma_reterncion.jpg')) {
			$this->Image('../images/firma_reterncion.jpg', 30, 135, 40);
		}
		$this->SetXY(100, 190);
		$this->Cell(75, 4, "FIRMA DEL AGENTE DE RETENCION", 'T', 0, 'C');

		require("../include/desconnect.php");
	}

	function Footer()
	{
		// El formato requerido maneja el cierre dentro del cuerpo del comprobante.
	}

	function EndReport()
	{
		$this->Ln(10);
	}
}

$pdf = new PDF('L', 'mm', 'Letter');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->EndReport();

require("../include/desconnect.php");
$pdf->Output();
?>
