<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";

$sql = "SELECT 
			id, date_format(fecha, '%d/%m/%Y') as fecha, cliente, nro_documento, tipo_documento, estatus, 
			asesor, descuento, descuento2, moneda, IFNULL(tasa_dia, 0) AS tasa_dia  
		FROM salidas where id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];

// Descuentos a nivel de documento (comercial / DC y DT) y datos de moneda para expresar todo en USD
$descuento_comercial = floatval($row["descuento"]);
$descuento_comercial2 = floatval($row["descuento2"]);
$moneda = $row["moneda"];
$tasa_dia = floatval($row["tasa_dia"]);

// Si el documento está en Bs. y no tiene tasa registrada, se toma la última tasa USD disponible
if ($moneda == 'Bs.' && $tasa_dia == 0) {
	$sql_tasa = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0,1;";
	$rs_tasa = mysqli_query($link, $sql_tasa);
	$row_tasa = mysqli_fetch_array($rs_tasa);
	$tasa_dia = floatval($row_tasa["tasa"]);
}

$sql = "SELECT a.nombre  
		FROM 
			usuario AS u 
			JOIN asesor AS a ON a.id = u.asesor 
		WHERE 
			u.username = '" . $row["asesor"] . "';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$GLOBALS["asesor"] = $row["nombre"];
else 
	$GLOBALS["asesor"] = "";


class PDF extends FPDF
{
// Función para la marca de agua
    function MarcaDeAgua()
    {
        $this->SetFont('Arial','B',40);
        $this->SetTextColor(230,230,230); // Color gris muy claro
        $this->RotatedText(35, 190, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 45);
        // $this->RotatedText(20, 230, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 45);
    }

    // Función auxiliar para rotar texto
    function RotatedText($x, $y, $txt, $angle)
    {
        // Rotación de texto
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm CP n', cos(deg2rad($angle)), sin(deg2rad($angle)), -sin(deg2rad($angle)), cos(deg2rad($angle)), $x, $y, -$x, -$y));
        $this->Text($x, $y, $txt);
        $this->_out('Q');
    }

    // Cabecera de p?gina
	function Header()
	{
		// Consulto datos de la compa??a 

		require("../include/connect2.php");
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
		$cia =  $row["nombre"];
		$logo =  $row["logo"];

		
		
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre, 
					a.email1, a.direccion, b.campo_descripcion AS ciudad, 
					CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf, a.web    
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
		$web = $row["web"]; 

		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '" . $GLOBALS["tipo_documento"] . "';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(25);
		
		$this->SetFont('Arial','',12);
		$this->Cell(200, 6, $row["descripcion"],0,0,'C');

		$this->Ln();
		$this->SetFont('Arial','B',10);
		$this->Cell(200, 6, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 0, 0, 'C');		

		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		$this->Cell(200, 6, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln(8);
		$this->Cell(10, 6);
		$this->Cell(150, 6);
		$this->SetFont('Arial','B',8);
		$this->Cell(20, 5,'Fecha: ','0','0','R');
		$this->SetFont('Arial','',8);
		$this->Cell(20, 5, $GLOBALS["fecha"], 0, 0, 'R');
		$this->Ln(10);

		$this->Cell(10, 6);
		$this->SetFont('Arial','B',8);
		$this->Cell(30, 6,"RAZON SOCIAL: ",'0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(130, 6, $razon_social,'0','0','L');
	

		$this->Ln(10);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(30, 5,'DIRECCION: ','0','0','L');
		$this->SetFont('Arial','',8);
		$this->MultiCell(160, 5, "$direccion_cliente. $ciudad_cliente", '0', 'L');

		$this->Ln(6);
		$this->Cell(10, 5);
		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'R.I.F.: ','0',0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(25,5,$rif,'0',0,'L');
		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'Telf:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(55,5,$telf,'0','0','L');

		$this->SetFont('Arial','B',8);
		$this->Cell(10,5,'WEB:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(20,5,$web,'0','0','L');

		$this->SetFont('Arial','B',8);
		$this->Cell(15,5,'Asesor:','0','0','L');
		$this->SetFont('Arial','',8);
		$this->Cell(45,5,$GLOBALS["asesor"],'0','0','L');

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->SetFont('Arial','B',7);
		$this->Cell(10, 6);
		$this->Cell(15, 6, "LAB", 1, 0, 'L');
		$this->Cell(65, 6, "ARTICULO", 1, 0, 'L');
		$this->Cell(8, 6, "CAN.", 1, 0, 'R');
		$this->Cell(10, 6, "IVA %", 1, 0, 'R');
		$this->Cell(18, 6, "PRECIO FULL", 1, 0, 'R');
		$this->Cell(8, 6, "DP%", 1, 0, 'R');
		$this->Cell(8, 6, "DL%", 1, 0, 'R');
		$this->Cell(8, 6, "DC%", 1, 0, 'R');
		$this->Cell(8, 6, "DT%", 1, 0, 'R');
		$this->Cell(18, 6, "PRECIO NETO", 1, 0, 'R');
		$this->Cell(18, 6, "TOTAL USD", 1, 0, 'R');
		$this->Ln(6);

		// LLAMAR A LA MARCA DE AGUA AL EMPEZAR EL HEADER
		$this->MarcaDeAgua();

	}
	
	// Pie de p?gina
	function Footer()
	{
		// Posici?n: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// N?mero de p?gina
		$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($id, $items, $cnt)
	{
		/*$this->Ln();
		$this->Cell(130, 6, "CANTIDAD ARTICULOS: "  . number_format($cnt, 0, "", ","), 0, 0, 'R');
		$this->Cell(70, 6, "TOTAL ITEMS: "  . $items, 0, 0, 'R');*/
		$this->Cell(15, 3);
		$this->Cell(0, 3, 'DP: Descuento Producto   DL: Descuento Laboratorio   DC: Descuento Comercial   DT: Descuento Transferencista', 0, 1, 'L');
		$asociado = "";

		require("../include/connect2.php");
		$sql = "SELECT 
					a.alicuota_iva, 
					a.iva,
					a.monto_total, 
					a.total, 
					a.id_documento_padre, 
					descuento, descuento2, monto_sin_descuento, 
					moneda, IFNULL(tasa_dia, 0) AS tasa_dia    
				FROM salidas a where a.id = '$id';"; 
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$descuento = $row["descuento"]; 
		$descuento2 = $row["descuento2"]; 
		$monto_sin_descuento = $row["monto_sin_descuento"];

		// Moneda/tasa del documento para expresar el resumen final en USD
		$moneda_doc = $row["moneda"];
		$tasa_doc = floatval($row["tasa_dia"]);

		if ($moneda_doc == 'Bs.' && $tasa_doc == 0) {
			$sql_tasa = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0,1;";
			$rs_tasa = mysqli_query($link, $sql_tasa);
			$row_tasa = mysqli_fetch_array($rs_tasa);
			$tasa_doc = floatval($row_tasa["tasa"]);
		}

		// Convierte un monto a USD según la moneda/tasa del documento
		$toUsd = function($monto) use ($moneda_doc, $tasa_doc) {
			$monto = floatval($monto);
			if ($moneda_doc != 'Bs.') return $monto;
			return ($tasa_doc > 0) ? $monto / $tasa_doc : 0;
		};

		$monto_sin_descuento_usd = $toUsd($monto_sin_descuento);
		$monto_total_usd = $toUsd($row["monto_total"]);
		$iva_usd = $toUsd($row["iva"]);
		$total_usd = $toUsd($row["total"]);

		$sql2 = "SELECT b.descripcion, a.nro_documento 
				FROM salidas AS a JOIN tipo_documento AS b ON b.codigo = a.tipo_documento 
				 where a.id = '" . $row["id_documento_padre"] . "';";
		$rs2 = mysqli_query($link, $sql2);
		$sw = false;
		while($row2 = mysqli_fetch_array($rs2)) {
			$doc .= " #" . $row2["nro_documento"];
			$tdoc = $row2["descripcion"];
			$sw = true;
		}

		if($sw) $asociado = "Documento(s) Asociado(s): $tdoc $doc / ";

		$this->Ln(230-$this->GetY());
		$this->SetFont('Arial','',8);
		$this->Cell(10, 6);
		$this->Cell(80, 6, "CANTIDAD ARTICULOS: "  . number_format($cnt, 0, "", ","), 1, 0, 'R');
		$this->Cell(70, 6, "(TOTAL ITEMS: "  . $items . ") SUB-TOTAL USD:", 1, 0, 'R');
		$this->Cell(40, 6, number_format($monto_total_usd, 2, ".", ","), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell(10, 6);
		if(floatval($row["alicuota_iva"]) > 0) {
			$this->Cell(110, 6, "% IVA:", 0, 0, 'R');
			$this->Cell(40, 6, number_format($row["alicuota_iva"], 0, ".", ","), 1, 0, 'L');
		}
		else $this->Cell(150,6, "IVA USD:", 1, 0, 'R');
		$this->Cell(40, 6, number_format($iva_usd, 2, ".", ","), 1, 0, 'R');
		$this->Ln(6);
		$this->Cell(10, 6);
		$this->Cell(150, 6, $asociado . ' TOTAL USD:', 1, 0, 'R');
		$this->Cell(40, 6, number_format($total_usd, 2, ".", ","), 1, 0, 'R');
		
		require("../include/desconnect.php");
	}
}

// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);


$sql = "SELECT 
			c.nombre AS codigo,  
			IFNULL(c.nombre, '') AS lab, 
			CONCAT(IFNULL(b.nombre_comercial, ''), ' ', 
			IFNULL(b.principio_activo, ''), ' ', 
			IFNULL(b.presentacion, '')) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.descuento,
			IFNULL(a.descuento2, 0) AS descuento2,
			a.precio_unidad,
			a.precio
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.id = a.fabricante 
		WHERE 
			a.id_documento = '$id' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
$cnt = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(10, 4);
	$pdf->Cell(15, 4, substr($row["codigo"], 0, 8), 0, 0, 'L');
	if(strlen($row["articulo"]) < 38) 
		$pdf->Cell(65, 4, trim($row["articulo"]), 0, 0, 'L');
	else 
		$pdf->Cell(65, 4, trim(substr($row["articulo"], 0, 38)), 0, 0, 'L');
	$pdf->Cell(8, 4, number_format($row["cantidad"], 0, "", ""), 0, 0, 'R');
	$pdf->Cell(10, 4, number_format($row["alicuota"], 2, ".", ","), 0, 0, 'R');

	// --- Descuentos: DP y DL vienen del detalle (entradas_salidas), DC y DT del documento (salidas) ---
	$dp = floatval($row["descuento"]);       // DP: descuento producto (entradas_salidas.descuento)
	$dl = floatval($row["descuento2"]);      // DL: descuento lote/línea (entradas_salidas.descuento2)
	$dc = $descuento_comercial;              // DC: descuento comercial (salidas.descuento)
	$dt = $descuento_comercial2;             // DT: descuento comercial 2 (salidas.descuento2)

	// El precio guardado en a.precio_unidad ya viene neto de DP y DL, por lo que se "reconstruye"
	// el precio full dividiendo entre los factores de esos descuentos (igual que en factura_de_venta_dropharma.php)
	$factor_dp = 1 - ($dp / 100);
	$factor_dl = 1 - ($dl / 100);

	$precio_unit_db = floatval($row["precio_unidad"]);
	$x_precio_full = $precio_unit_db;

	if ($factor_dp > 0) $x_precio_full = $x_precio_full / $factor_dp;
	if ($factor_dl > 0) $x_precio_full = $x_precio_full / $factor_dl;

	// Todo se expresa en USD: si el documento está en Bs. se divide entre la tasa del día
	$val_precio_full_usd = ($moneda != 'Bs.') ? $x_precio_full : (($tasa_dia > 0) ? $x_precio_full / $tasa_dia : 0);

	$pdf->Cell(18, 4, number_format($val_precio_full_usd, 2, ".", ","), 0, 0, 'R');

	$pdf->Cell(8, 4, $dp > 0 ? number_format($dp, 0, "", "") . "%" : "", 0, 0, 'R');
	$pdf->Cell(8, 4, $dl > 0 ? number_format($dl, 0, "", "") . "%" : "", 0, 0, 'R');
	$pdf->Cell(8, 4, $dc > 0 ? number_format($dc, 0, "", "") . "%" : "", 0, 0, 'R');
	$pdf->Cell(8, 4, $dt > 0 ? number_format($dt, 0, "", "") . "%" : "", 0, 0, 'R');

	// Precio neto: precio full con los 4 descuentos aplicados en cascada (DP, DL, DC, DT)
	$precio_unitario_neto = $x_precio_full;
	$precio_unitario_neto -= $precio_unitario_neto * $dp / 100;
	$precio_unitario_neto -= $precio_unitario_neto * $dl / 100;
	$precio_unitario_neto -= $precio_unitario_neto * $dc / 100;
	$precio_unitario_neto -= $precio_unitario_neto * $dt / 100;

	$val_neto_usd = ($moneda != 'Bs.') ? $precio_unitario_neto : (($tasa_dia > 0) ? $precio_unitario_neto / $tasa_dia : 0);

	$pdf->Cell(18, 4, number_format($val_neto_usd, 2, ".", ","), 0, 0, 'R');

	// Total de línea = precio neto x cantidad, en USD
	$precio_linea_total = $precio_unitario_neto * intval($row["cantidad"]);
	$val_total_usd = ($moneda != 'Bs.') ? $precio_linea_total : (($tasa_dia > 0) ? $precio_linea_total / $tasa_dia : 0);

	$pdf->Cell(18, 4, number_format($val_total_usd, 2, ".", ","), 0, 0, 'R');

	if(strlen($row["articulo"]) >= 38) {
		$pdf->Ln();
		$pdf->Cell(25, 4);
		$pdf->MultiCell(65, 4, trim(substr($row["articulo"], 38, strlen($row["articulo"]))), 0, 'L');
	}
	else $pdf->Ln();

	if($pdf->GetY() > 250) $pdf->AddPage();
	$items++;
	$cnt += intval($row["cantidad"]);
}

$pdf->EndReport($id, $items, $cnt);

	
require("../include/desconnect.php");

$pdf->Output();
?>