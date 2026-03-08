<?php
require('rcs/fpdf.php');
require("../include/connect.php");

$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["alicuota_dinamica"] = $row["alicuota"];


$id_invoice = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT
			id, date_format(fecha, '%d/%m/%Y') as fecha,
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus,
			asesor, documento, monto_usd, tasa_dia, moneda
		FROM salidas where id = '$id_invoice'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"]=="ANULADO" ? $row["estatus"] . " - " : "";
$GLOBALS["documento"] = $row["documento"];
$GLOBALS["moneda"] = $row["moneda"];

if(substr($row["nro_documento"] ?? "", 0, 6) == "1PREFAC") {
	header("Location: factura_de_venta_prefactura.php?id=$id_invoice&tipo=TDCFCV");
	die();
}

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);
$asesor = $row["asesor"];

// Se limpia esta l?nea para asegurar que no haya caracteres invisibles antes de $sql
if(($monto_usd==0 or $tasa_dia==0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) {
	$sql = "SELECT tasa FROM tasa_usd WHERE moneda = '" . $GLOBALS["moneda"] . "' ORDER BY id DESC LIMIT 0, 1;";
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
			u.username = '$asesor';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);


$GLOBALS["tasa_dia"] = $tasa_dia;


class PDF extends FPDF
{
	// Cabecera de p?gina
	function Header()
	{
		// Consulto datos de la compa??a
		require("../include/connect.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia = $row["id"];


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
		$GLOBALS["cta_cia"] = $row["numero"];


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
		$cia = $row["nombre"];


		
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
		// $razon_social = html_entity_decode($row["nombre"]);
		$razon_social = html_entity_decode($row["nombre"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$razon_social = str_ireplace(['&AMP;', '&amp;'], '&', $razon_social);

		$rif = $row["ci_rif"];
		$direccion_cliente = $row["direccion"];
		$ciudad_cliente = $row["ciudad"];
		$telf = $row["telf"];
		$web = $row["web"];
		$SICM = $row["SICM"];

		$this->Ln(25);
		$this->SetFont('Arial','',10);
		
		$this->Cell(125, 6);
		$tdoc = ($GLOBALS["documento"]=="FC" ? "Nro. Factura: " : ($GLOBALS["documento"]=="NC" ? "Nro. Nota de Cr?dito: " : ($GLOBALS["documento"]=="ND" ? "Nro. Nota de D?bito: ":"N/A")));
		$this->Cell(30, 6, $tdoc,'0','0','L');
		$this->SetFont('Arial','',10);
		$this->Cell(45, 6, $GLOBALS["nro_documento"],'0','0','R');

		$this->Ln(5);
		$this->SetFont('Arial','B',10);
		$this->Cell(125, 6);
		$this->Cell(30, 6,'Fecha: ','0','0','L');
		$this->SetFont('Arial','',10);
		$this->Cell(45, 6, $ciudad . ", " . $GLOBALS["fecha"], 0, 0, 'R');


		$this->Ln(6);

		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CLIENTE: ",'0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(110, 4, mb_convert_encoding(substr($razon_social, 0, 70), "UTF-8"),'0','0','L');

		/*
		$this->Ln();
		$this->Cell(40, 4);
		$this->Cell(110, 4, utf8_decode(substr(utf8_decode($razon_social), 70, strlen($razon_social))),'0','0','L');
		*/

		$this->Ln();
		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(30, 4,"CUENTA N?: ",'0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(110, 4, mb_convert_encoding(substr($id, 0, 55), "UTF-8"),'0','0','L');

		$this->Ln();
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',7);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',7);
		$direccion_cliente = "$direccion_cliente. $ciudad_cliente";
		$this->MultiCell(160, 4, mb_convert_encoding($direccion_cliente, "UTF-8"), '0', 'L');

		
		$this->Ln();
		$this->Cell(10, 4);
		$this->SetFont('Arial','B',7);
		$this->Cell(20,4,'TELEFONOS:','0','0','L');
		$this->SetFont('Arial','',7);
		$this->Cell(40,4,$telf,'0','0','L');

		$this->SetFont('Arial','B',7);
		$this->Cell(10,4,'R.I.F.: ','0',0,'L');
		$this->SetFont('Arial','',7);
		$this->Cell(20,4,$rif,'0',0,'L');

		$this->SetFont('Arial','B',7);
		$this->Cell(35,4,'CONDICIONES DE PAGO: ','0',0,'L');
		$this->SetFont('Arial','',7);
		$this->Cell(15,4,"Contado",'0',0,'L');

		$this->SetFont('Arial','B',7);
		$this->Cell(35,4,'TASA B.C.V.: ','0',0,'R');
		$this->Cell(15,4,number_format($GLOBALS["tasa_dia"], 4, ".", ","),'0',0,'R');
		$this->SetFont('Arial','',7);

		require("../include/desconnect.php");
		$this->Ln();

		$this->SetFont('Arial','B',7);
		$this->Cell(10, 4);
		$this->Cell(15, 4, "CANT", "B", 0, 'C');
		$this->Cell(110, 4, "DESCRIPCION", "B", 0, 'C');
		$this->Cell(20, 4, "PRECIO UNIT", "B", 0, 'R');
		$this->Cell(10, 4, "% ALIC", "B", 0, 'R');
		$this->Cell(20, 4, "TOT. Bs.", "B", 0, 'R');
		$this->Cell(15, 4, "TOT. ". $GLOBALS["moneda"], "B", 0, 'R');
		$this->Ln(5);
	}
	
	// Pie de p?gina
	function Footer()
	{
		// Posici?n: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',7);
		// N?mero de p?gina
		//$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function EndReport($id_invoice)
	{
		$asociado = "";
		require("../include/connect.php");
		$doc = "";

		$igtf_porc = floatval($GLOBALS["alicuota_dinamica"]);

		$sql = "SELECT
					a.alicuota_iva,
					a.iva,
					a.monto_total,
					a.total,
					a.nota,
					a.moneda,
					a.asesor, a.id_documento_padre,
					a.monto_usd, a.tasa_dia, a.descuento, a.monto_sin_descuento, a.unidades, a.igtf, a.monto_base_igtf, a.monto_igtf
				FROM salidas a where a.id = '$id_invoice'";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota_salidas = $row["alicuota_iva"];
		$nota = mb_convert_encoding($row["nota"] ?? "", "UTF-8");
		$moneda = mb_convert_encoding($row["moneda"] ?? "", "UTF-8");
		$asesor = mb_convert_encoding($row["asesor"] ?? "", "UTF-8");
		$monto_total = floatval($row["monto_total"]);
		$monto_sin_descuento = floatval($row["monto_sin_descuento"]);

		$id_documento_padre = $row["id_documento_padre"];

		$monto_usd = floatval($row["monto_usd"]);
		$tasa_dia = floatval($row["tasa_dia"]);

		$descuento = floatval($row["descuento"]);

		$unidades = $row["unidades"];
		$igtf = $row["igtf"];
		$monto_base_igtf = floatval($row["monto_base_igtf"]);
		$monto_igtf = floatval($row["monto_igtf"]);

		// Consulta para obtener los totales en Bs por componentes (Exento, Gravado, IVA, Total)
		// NOTA: Estos valores son los que vienen de la DB en Bs, se usar?n como BASE para la CONVERSI?N
		$sql = "SELECT
					SUM(precio) AS precio,
					SUM(IF(IFNULL(alicuota,0)=0, precio, 0)) AS exento,
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio)) AS gravado,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento_2,
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado_2,
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva,
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total,
					MAX(IFNULL(alicuota,0)) AS alicuota
				FROM entradas_salidas
				WHERE tipo_documento = 'TDCFCV' AND
					id_documento = '$id_invoice'";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$alicuota_total = $row["alicuota"];
        
        // =========================================================================
        // L?GICA DE CONSISTENCIA: Monto Bs = Monto USD * Tasa del D?a
        // Se calcula USD (?ltima columna) y luego se usa para recalcular Bs (pen?ltima).
        // =========================================================================
        
        // Montos en Bs de la consulta de totales (base para la conversi?n a USD)
        $exento_bs_db = floatval($row["exento"]);
        $gravado_bs_db = floatval($row["gravado"]);
        $iva_bs_db = floatval($row["iva"]);
        $total_bs_db = floatval($row["total"]);
        $gravado_base_descuento_bs = floatval($row["gravado_2"]);
        
        // 1. CALCULAR VALORES EN USD (?LTIMA COLUMNA) y redondear a 2 decimales
        $exento_usd = round($exento_bs_db / $tasa_dia, 2);
        $gravado_usd = round($gravado_bs_db / $tasa_dia, 2);
        $iva_usd = round($iva_bs_db / $tasa_dia, 2);
        $total_usd = round($total_bs_db / $tasa_dia, 2);
        
        // C?lculo del monto de descuento en Bs (Positivo)
        $total_base_sin_descuento_bs = $exento_bs_db + $gravado_bs_db; 
        $total_base_con_descuento_bs = floatval($row["exento_2"]) + floatval($row["gravado_2"]);
        
        // Descuento en Bs (usando los totales de la DB)
        $monto_descuento_bs_db = $total_base_sin_descuento_bs - $total_base_con_descuento_bs;
        
        // Convertir y recalcular el descuento (Divisa manda)
        $monto_descuento_usd = round($monto_descuento_bs_db / $tasa_dia, 2);
        $monto_descuento_bs_recalculado = $monto_descuento_usd * $tasa_dia;


        // 2. RECALCULAR VALORES EN BS (PEN?LTIMA COLUMNA) = USD * $tasa_dia
        $exento_bs_recalculado = $exento_usd * $tasa_dia;
        $gravado_bs_recalculado = $gravado_usd * $tasa_dia;
        $iva_bs_recalculado = $iva_usd * $tasa_dia;
        $total_bs_recalculado = $total_usd * $tasa_dia;
        
        // =========================================================================

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

		$this->Ln(100-$this->GetY());

		$this->Cell(10, 3, "", 0, 0, 'R');
		$this->SetFont('Arial','BI',7);
		$this->MultiCell(190, 3, strtoupper(mb_convert_encoding($nota . " " . $asociado, "UTF-8")), 0, 'L');


		$this->SetFont('Arial','',6);
		$this->Cell(10, 3, "", 0, 0, 'L');
		$this->Cell(90, 3, "Este documento se expresa en D?lares Americanos con su equivalente en Bol?vares al tipo de cambio corriente", 0, 0, 'L');
		$this->SetFont('Arial','B',7);
		$this->Cell(63, 3, "SUB-TOTAL EXENTO:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		$this->Cell(22, 3, number_format($exento_bs_recalculado, 2, ",", "."), 0, 0, 'R');
		$this->Cell(15, 3, number_format($exento_usd, 2, ",", "."), 0, 0, 'R');
		$this->Ln(3);

		$this->SetFont('Arial','',6);
		$this->Cell(10, 3, "", 0, 0, 'L');
		$this->Cell(90, 3, "del mercado a la fecha de su emisi?n, seg?n lo establecido en el art?culo 13 numeral 14 de la Providencia ", 0, 0, 'L');
		$this->SetFont('Arial','B',7);
		$this->Cell(63,3, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		// $this->Cell(22, 3, number_format($gravado_bs_recalculado, 2, ",", "."), 0, 0, 'R');
		$this->Cell(22, 3, number_format($total_base_sin_descuento_bs, 2, ",", "."), 0, 0, 'R');
		$this->Cell(15, 3, number_format($gravado_usd, 2, ",", "."), 0, 0, 'R');
		$this->Ln(3);

		// Se imprime el descuento si aplica
		if($descuento > 0) {
			$this->SetFont('Arial','',6);
			$this->Cell(10, 3, "", 0, 0, 'L');
			$this->Cell(90, 3, "Administrativa SNAT/2011/0071, el art?culo 128 de la Ley del Banco Central de Venezuela, el art?culo 25 de la", 0, 0, 'L');
			$this->SetFont('Arial','B',7);
			$this->Cell(63,3, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			$this->Cell(22, 3, number_format($monto_descuento_bs_recalculado, 2, ",", "."), 0, 0, 'R');
			$this->Cell(15, 3, number_format($monto_descuento_usd, 2, ",", "."), 0, 0, 'R');

			$this->Ln(3);
			$this->SetFont('Arial','',6);
			$this->Cell(10, 3, "", 0, 0, 'L');
			$this->Cell(90, 3, "Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A.", 0, 0, 'L');
			$this->SetFont('Arial','B',7);
		}
		else {
			$this->SetFont('Arial','',6);
			$this->Cell(10, 3, "", 0, 0, 'L');
			$this->Cell(90, 3, "Administrativa SNAT/2011/0071, el art?culo 128 de la Ley del Banco Central de Venezuela, el art?culo 25 de la", 0, 0, 'L');			
		}
		//

		$this->SetFont('Arial','B',7);
		// El monto base del IVA es el gravado_2 (gravado con descuento aplicado) en Bs. (valor de la DB)
		$this->Cell(63,3, "IVA $alicuota_salidas % SOBRE $moneda " . number_format($gravado_bs_recalculado, 2, ",", ".") . ": ", 0, 0, 'R');
		$this->SetFont('Arial','',7);

		$this->Cell(22, 3, number_format($iva_bs_recalculado, 2, ",", "."), 0, 0, 'R');
		$this->Cell(15, 3, number_format($iva_usd, 2, ",", "."), 0, 0, 'R');

		$this->Ln(3);
		$this->SetFont('Arial','',6);
		if($descuento == 0) {
			$this->Cell(10, 3, "", 0, 0, 'L');
			$this->Cell(90, 3, "Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A.", 0, 0, 'L');
		}
		else {
			$this->Cell(100, 3, "", 0, 0, 'L');
		}
		$this->SetFont('Arial','B',7);
		$this->Cell(63, 3, "TOTAL:", 0, 0, 'R');
		$this->SetFont('Arial','',7);
		$this->Cell(22, 3, number_format($total_bs_recalculado, 2, ",", "."), 0, 0, 'R');
		$this->Cell(15, 3, number_format($total_usd, 2, ",", "."), 0, 0, 'R');
		

        // IGTF Logic
		if($igtf == "S") {
			// Nota: Se utiliza el total Bs de la DB ($total_bs_db) para el c?lculo del total general en Bs antes de la conversi?n/recalculo
			$totaligtf = $total_bs_db + $monto_igtf;
			
			// 1. CALCULAR VALORES IGTF EN USD (?LTIMA COLUMNA)
			$monto_igtf_usd = round($monto_igtf / $tasa_dia, 2);
			$totaligtf_usd = round($totaligtf / $tasa_dia, 2);
			
			// 2. RECALCULAR VALORES IGTF EN BS (PEN?LTIMA COLUMNA)
			$monto_igtf_bs_recalculado = $monto_igtf_usd * $tasa_dia;
			$totaligtf_bs_recalculado = $totaligtf_usd * $tasa_dia;
			

			$this->Ln(3);
			$this->SetFont('Arial','B',7);
			$this->Cell(30, 3, "", 0, 0, 'R');
			$this->Cell(90, 3, "", 0, 0, 'R');
			$this->Cell(43, 3, "I.G.T.F. " . number_format($igtf_porc, 0, "", "") . "% SOBRE EL MONTO A PAGAR:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			
			$this->Cell(22, 3, "", 0, 0, 'R');
			$this->Cell(15, 3, number_format($monto_igtf_usd, 2, ",", "."), 0, 0, 'R');

			$this->Ln(3);
			$this->SetFont('Arial','B',7);
			$this->Cell(30, 3, "", 0, 0, 'R');
			$this->Cell(90, 3, "", 0, 0, 'R');
			$this->Cell(43, 3, "TOTAL GENERAL:", 0, 0, 'R');
			$this->SetFont('Arial','',7);
			
			$this->Cell(22, 3, "", 0, 0, 'R');
			$this->Cell(15, 3, number_format($totaligtf_usd, 2, ",", "."), 0, 0, 'R');
		}


		require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,9);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',7);


$sql = "SELECT
			IFNULL(b.codigo, '') AS codigo,
			LTRIM(RTRIM(CONCAT(IFNULL(b.codigo, ''), ' ', SUBSTRING(IFNULL(b.principio_activo, ''), 1, 65), ' ', IFNULL(a.lote, '')))) AS articulo,
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') as vencimiento,
			a.cantidad_articulo AS cantidad,
			(SELECT SUBSTRING(descripcion,1,3) FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida,
			IFNULL(a.alicuota, 0.00) AS alicuota,
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
    $tasa_dia = $GLOBALS["tasa_dia"]; // Asegurar que la tasa est? disponible

	$pdf->SetFont('Arial','',7);
	$pdf->Cell(10, 3);
	$pdf->Cell(15, 3, number_format($row["cantidad"], 0, "", ""), "", 0, 'C');

	if(strlen(trim($row["articulo"])) < 70)
		$pdf->Cell(110, 3, ($row["alicuota"]==0.00?"(E) ":"") . mb_convert_encoding(trim($row["articulo"]), "UTF-8"), 0, 0, 'L');
	else
		$pdf->Cell(110, 3, ($row["alicuota"]==0.00?"(E) ":"") . mb_convert_encoding(trim(substr($row["articulo"], 0, 70)), "UTF-8"), 0, 0, 'L');


	$pdf->Cell(20, 3, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(10, 3, number_format($row["alicuota"], 2, ",", "."), 0, 0, 'R');
	
    // === L?GICA DE C?LCULO PARA DETALLE (PRIORIDAD USD) ===
    $precio_bs_db = floatval($row["precio"]);
    
    // 1. Calcular el total de la l?nea en USD (?ltima columna) y redondear a 2 decimales
    $precio_usd = round($precio_bs_db / $tasa_dia, 2); 
    
    // 2. Recalcular el total de la l?nea en Bs (pen?ltima columna) = USD * $tasa_dia
    $precio_bs_recalculado = $precio_usd * $tasa_dia; 

    // Pen?ltima columna (TOT. Bs.)
	//$pdf->Cell(20, 3, number_format($precio_bs_recalculado, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(20, 3, number_format($precio_bs_db, 2, ",", "."), 0, 0, 'R');

    // ?ltima columna (TOT. Moneda/USD)
	$pdf->Cell(15, 3, number_format($precio_usd, 2, ",", "."), 0, 0, 'R');
    // === FIN L?GICA DE C?LCULO PARA DETALLE ===


	if(strlen($row["articulo"]) >= 70) {
		$pdf->Ln();
		if(substr(trim(utf8_decode($row["articulo"])), 70, strlen($row["articulo"])) != "") {
			$pdf->Cell(25, 3);
			$pdf->MultiCell(110, 3, substr(trim(utf8_decode($row["articulo"])), 70, strlen($row["articulo"])), 0, 'L');			
		}
	}
	else $pdf->Ln();

	if($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

	
require("../include/desconnect.php");

$pdf->Output();
?>