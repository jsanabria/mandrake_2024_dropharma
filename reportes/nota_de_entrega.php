<?php
// Activamos el búfer de salida para prevenir fugas de texto antes de generar el PDF
ob_start();
session_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require('rcs/fpdf.php');
require("../include/connect.php");

$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"0";
$GLOBALS["id"] = $id;

$GLOBALS["ocultar_precios"] = false;

$con_precio = isset($_REQUEST["con_precio"])?$_REQUEST["con_precio"]:"0";
$GLOBALS["con_precio"] = $con_precio;

$GLOBALS["CurrentUserName"] = isset($_REQUEST["CurrentUserName"])?$_REQUEST["CurrentUserName"]:"";

$sql = "SELECT 
            id,
            DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
            cliente,
            nro_documento,
            tipo_documento,
            estatus,
            cliente,
            username,
            IFNULL(ci_rif,'') AS ci_rif_envio,
            IFNULL(nombre,'') AS nombre_envio,
            IFNULL(direccion,'') AS direccion_envio,
            IFNULL(telefono,'') AS telefono_envio
        FROM salidas
        WHERE id = '$id'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["ocultar_precios"] = ($row["tipo_documento"] == "TDCASA");
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus"] = $row["estatus"];
$GLOBALS["direccion_cia"] = "";
$GLOBALS["username"] = $row["username"];
$GLOBALS["ci_rif_envio"] = $row["ci_rif_envio"];
$GLOBALS["nombre_envio"] = $row["nombre_envio"];
$GLOBALS["direccion_envio"] = $row["direccion_envio"];
$GLOBALS["telefono_envio"] = $row["telefono_envio"];

$cliente = $row["cliente"];

if (in_array($row["tipo_documento"], ["TDCASA"])) {
    $GLOBALS["ocultar_precios"] = true;
}

class PDF extends FPDF
{
    function MarcaDeAgua()
    {
	    $this->SetFont('Arial','B',32);
	    $this->SetTextColor(245,245,245);

	    $this->RotatedText(65, 120, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 25);

	    // Restaurar color normal para el resto del PDF
	    $this->SetTextColor(0, 0, 0);
    }

    // Función auxiliar para rotar texto
    function RotatedText($x, $y, $txt, $angle)
    {
        // Rotación de texto
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm CP n', cos(deg2rad($angle)), sin(deg2rad($angle)), -sin(deg2rad($angle)), cos(deg2rad($angle)), $x, $y, -$x, -$y));
        $this->Text($x, $y, $txt);
        $this->_out('Q');
    }

	// Cabecera de página
	function Header()
	{
		// Consulto datos de la compa??a 
		$this->MarcaDeAgua();

		$this->SetTextColor(0, 0, 0);
    	$this->SetDrawColor(0, 0, 0);

		// Consulto datos de la compañía 
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
		$GLOBALS["logo"] = $logo;
		
		
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
		$direccion_cliente = $row["direccion"]; 
		$ciudad_cliente = $row["ciudad"]; 
		$telf = $row["telf"]; 

		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '" . $GLOBALS["tipo_documento"] . "';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 60);
		}
		
		$this->Ln(15);
		
		$this->SetFont('Arial','B',10);
		// Línea 121 corregida: reemplazo de utf8_decode por mb_convert_encoding
		$this->Cell(200, 6, mb_convert_encoding($row["descripcion"], "ISO-8859-1", "UTF-8"),0,0,'C');

		$this->Ln(5);
		$this->SetFont('Arial','B',10);
		$this->Cell(200, 6, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 0, 0, 'C');		


		$this->Ln(8);
		
		$this->SetFont('Arial','',8);
		
		$this->Cell(10, 5);
		// Corregida codificación para el nombre de la compañía
		$this->Cell(50, 5, mb_convert_encoding($cia, "ISO-8859-1", "UTF-8"),0,0,'L');
		$this->SetFont('Arial','',8);
		$this->Cell(140, 5, "ESTATUS: " . $GLOBALS["estatus"] . " / " . "No. Doc.: " . $GLOBALS["nro_documento"],0,0,'R');

		$this->Ln();
		$this->Cell(10, 5);
		$this->SetFont('Arial','',8);
		$this->Cell(100, 5, "R.I.F: $ci_rif", 0, 0, "L");
		$this->SetFont('Arial','',8);
		$this->Cell(50, 5, "CIUDAD: $ciudad",0,0,'R');
		$this->Cell(40, 5, "FECHA: " . $GLOBALS["fecha"],0,0,'R');

		$this->Ln();

		$this->Cell(10, 4);
		$this->Cell(30, 4,"CLIENTE: ",'0','0','L');
		$this->Cell(120, 4, mb_convert_encoding($razon_social, "ISO-8859-1", "UTF-8"),'0','0','L');
		$this->Cell(40, 4, "User: " . $GLOBALS['username'], 0, 0, 'R');
	

		$this->Ln();
		$this->Cell(10, 4);
		$this->Cell(30, 4,'DIRECCION: ','0','0','L');
		$this->MultiCell(160, 4, mb_convert_encoding("$direccion_cliente. $ciudad_cliente", "ISO-8859-1", "UTF-8"), '0', 'L');

		$this->Cell(10, 5);
		$this->Cell(70,5,'R.I.F.: ' . $rif,'0',0,'L');
		$this->Cell(80,5,'Telf:'.  $telf,'0','0','L');
		$this->Cell(50,5,'CONTADO','0',0,'C');

		$mostrar_envio =
		    trim($GLOBALS["ci_rif_envio"]) != "" ||
		    trim($GLOBALS["nombre_envio"]) != "" ||
		    trim($GLOBALS["direccion_envio"]) != "" ||
		    trim($GLOBALS["telefono_envio"]) != "";

		if ($mostrar_envio) {

		    $this->Ln(6);

		    $this->SetFont('Arial','B',8);
		    $this->Cell(10,4);
		    $this->Cell(190,4,
		        mb_convert_encoding("DATOS DE ENVÍO", "ISO-8859-1", "UTF-8"),
		        0,0,'L');

		    $this->Ln(5);
		    $this->SetFont('Arial','',8);

		    if(trim($GLOBALS["nombre_envio"]) != "") {
		        $this->Cell(10,4);
		        $this->Cell(
		            190,
		            4,
		            mb_convert_encoding("Nombre: " . $GLOBALS["nombre_envio"], "ISO-8859-1", "UTF-8"),
		            0,0,'L'
		        );
		        $this->Ln();
		    }

		    if(trim($GLOBALS["ci_rif_envio"]) != "") {
		        $this->Cell(10,4);
		        $this->Cell(190,4,"C.I./R.I.F.: " . $GLOBALS["ci_rif_envio"],0,0,'L');
		        $this->Ln();
		    }

		    if(trim($GLOBALS["telefono_envio"]) != "") {
		        $this->Cell(10,4);
		        $this->Cell(190,4,"Telefono: " . $GLOBALS["telefono_envio"],0,0,'L');
		        $this->Ln();
		    }

		    if(trim($GLOBALS["direccion_envio"]) != "") {
		        $this->Cell(10,4);
		        $this->Cell(30,4,'Direccion: ','0','0','L');
		        $this->MultiCell(
		            160,
		            4,
		            mb_convert_encoding($GLOBALS["direccion_envio"], "ISO-8859-1", "UTF-8"),
		            0,
		            'L'
		        );
		    }
		}

		require("../include/desconnect.php");
		$this->Ln(6);

		$this->Cell(10, 5);
		if($GLOBALS["con_precio"] == "S" && !$GLOBALS["ocultar_precios"]) {
			$this->Cell(20, 5, "CODIGO", 1, 0, 'L');
			$this->Cell(105, 5, "ARTICULO", 1, 0, 'L');
			$this->Cell(15, 5, "CANT", 1, 0, 'C');
			$this->Cell(25, 5, "PRECIO", 1, 0, 'R');
			$this->Cell(25, 5, "TOTAL", 1, 0, 'R');
		} 
		else {
			$this->Cell(40, 5, "CODIGO", 1, 0, 'L');
			$this->Cell(135, 5, "ARTICULO", 1, 0, 'L');
			$this->Cell(15, 5, "CANT", 1, 0, 'C');
		}
		$this->Ln(6);

	}
	
	// Pie de página
	function Footer()
	{
	}
	
	function EndReport($id_invoice)
	{
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

		// Líneas 207, 208, 209 corregidas utilizando el estándar seguro de codificación de caracteres
		$nota = mb_convert_encoding($row["nota"], "ISO-8859-1", "UTF-8");
		$moneda = mb_convert_encoding($row["moneda"], "ISO-8859-1", "UTF-8");
		$username = mb_convert_encoding($row["username"], "ISO-8859-1", "UTF-8");

		$monto_total = $row["monto_total"];
		$monto_sin_descuento = $row["monto_sin_descuento"];
		$estatus = $row["estatus"];

		$monto_usd = $row["monto_usd"];
		$tasa_dia = $row["tasa_dia"];
		if($tasa_dia == 0) $tasa_dia = 1;

		$descuento = floatval($row["descuento"]);
		$unidades = $row["unidades"];
		$nro_despacho = $row["nro_despacho"];

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
		
		$this->SetFont('Arial','',8);

		if(floatval($row["iva"]) > 0.00) {
				if($GLOBALS["con_precio"] == "S" && !$GLOBALS["ocultar_precios"]) {
					$this->SetFont('Arial','',8);
					$this->Cell(175,3, "TOTAL:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 3, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
					$this->Ln(4);

					if($descuento > 0) {
						$this->SetFont('Arial','',8);
						$this->Cell(101,3, "", 0, 0, 'R');
						$this->SetFont('Arial','',8);
						$this->Cell(175,3, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
						$this->SetFont('Arial','',8);
						$this->Cell(25, 3, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
						$this->Ln(4);
					}
				}
				$this->Ln(4);
				$this->SetFont('Arial','',8);
				$this->Cell(90, 3);
				$this->SetFont('Arial','',8);
				$this->Cell(50, 3, "RECIBE CONFORME", "T", 0, 'C');
				$this->SetFont('Arial','',8);
		} 
		else {
				if($descuento > 0) {
					$this->SetFont('Arial','',8);
					$this->Cell(175, 4, "SUB-TOTAL:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($row["exento"], 2, ",", "."), 0, 0, 'R');
					$this->Ln(5);

					$this->Cell(175,4, "Descuento " . number_format($descuento, 2, ",", ".") . "%:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($monto_total-$monto_sin_descuento, 2, ",", "."), 0, 0, 'R');
					$this->Ln(5);
				}
				$this->SetFont('Arial','',8);
				$this->Cell(90, 4);
				$this->SetFont('Arial','',8);
				$this->Cell(50, 4, "RECIBE CONFORME", "T", 0, 'C');
				$this->SetFont('Arial','',8);
				if($GLOBALS["con_precio"] == "S" && !$GLOBALS["ocultar_precios"]) {
					$this->Cell(35, 4, "TOTAL $moneda:", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, number_format($row["total"], 2, ",", "."), 0, 0, 'R');
				} 
				else {
					$this->Cell(35, 4, "", 0, 0, 'R');
					$this->SetFont('Arial','',8);
					$this->Cell(25, 4, "", 0, 0, 'R');					
				}
		}

		if(trim($nota) != "") {
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->Cell(10, 4);
			$nota = substr($nota, 0, 140);
			$this->Cell(190, 4, "Nota: $nota", 0, 0, "L");		
		} 
		else {
			$this->Ln(6);			
		}
		$this->SetFont('Arial','',8);

		require("../include/desconnect.php");		
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
		
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

require("../include/connect.php");
$sql = "SELECT 
			b.codigo,  
			LTRIM(CONCAT(IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(b.nombre_comercial, ''))) AS articulo, 
			a.cantidad_articulo AS cantidad, 
			(SELECT descripcion FROM unidad_medida WHERE codigo = a.articulo_unidad_medida) AS unidad_medida, 
			(SELECT alicuota FROM alicuota WHERE codigo = b.alicuota AND activo = 'S') alicuota, 
			a.costo_unidad, 
			a.costo, 
			a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
			a.precio_unidad, a.precio, b.codigo_ims, IFNULL(b.nombre_comercial, '') AS nombre_comercial 
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
			LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
		WHERE 
			a.id_documento = '" . $GLOBALS["id"] . "' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.codigo_ims, a.cantidad_articulo, a.id;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
$items = 0;
while($row = mysqli_fetch_array($rs))
{
	$pdf->SetFont('Arial', '', 8);
	$desart = mb_convert_encoding(trim($row["articulo"]) . ". " . $row["codigo"], "ISO-8859-1", "UTF-8");
	
	if($GLOBALS["con_precio"] == "S" && !$GLOBALS["ocultar_precios"]) {
		$pdf->Cell(10, 4);
		$pdf->Cell(20, 4, substr($row["codigo"], 0, 10), 0, 0, 'L');
		$pdf->Cell(105, 4, substr($desart, 0, 60), 0, 0, 'L');
		$pdf->Cell(15, 4, round($row["cantidad"], 0), 0, 0, 'C');
		$pdf->Cell(25, 4, number_format($row["precio_unidad"], 2, ",", "."), 0, 0, 'R');
		$pdf->Cell(25, 4, number_format($row["precio"], 2, ",", "."), 0, 0, 'R');
		if(strlen($desart) >= 60) {
			$pdf->Ln();
			$pdf->Cell(30, 4);
			$pdf->MultiCell(100, 4, substr($desart, 60, strlen($desart)), 0, 'L');
		}
		else $pdf->Ln();
	} 
	else {
		$pdf->Cell(10, 4);
		$pdf->Cell(40, 4, substr($row["codigo"], 0, 20), 0, 0, 'L');
		$pdf->Cell(135, 4, substr($desart, 0, 70), 0, 0, 'L');
		$pdf->Cell(15, 4, round($row["cantidad"], 0), 0, 0, 'C');
		if(strlen($desart) >= 70) {
			$pdf->Ln();
			$pdf->Cell(50, 4);
			$pdf->MultiCell(100, 4, substr($desart, 70, strlen($desart)), 0, 'L');
		}
		else $pdf->Ln();
	}
	$items++;
}

$sql = "SELECT id, saldo FROM recarga WHERE cliente = " . $GLOBALS["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
$saldo_actual = "";
if($row = mysqli_fetch_array($rs)) $saldo_actual = "\n(Saldo actual abonos: *** USD " . number_format($row["saldo"], 2, ".", ",") . " ***)";

$sql = "SELECT 
			b.metodo_pago, IF(b.metodo_pago = 'RC', '', b.referencia) AS referencia, b.monto_moneda, b.moneda, 
			b.monto_usd, c.nro_recibo, c.monto_usd  
		FROM 
			cobros_cliente AS a 
			JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id 
			LEFT OUTER JOIN recarga AS c ON c.cobro_cliente_reverso = a.id 
		WHERE a.id_documento = " . $GLOBALS["id"] . ";"; 
$rs = mysqli_query($link, $sql) or die(mysqli_error($link));
$pdf->Cell(10, 4);
if($GLOBALS["con_precio"]=="SW") {
	$pdf->Cell(20, 4, "TIPO", "B", 0, 'L');
	$pdf->Cell(20, 4, "REF. #", "B", 0, 'L');
	$pdf->Cell(20, 4, "MONTO", "B", 0, 'R');
} 
else {
	$pdf->Cell(60, 4, "", "B", 0, 'L');
}
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(60, 4);
$pdf->Cell(70, 4, mb_convert_encoding($saldo_actual, "ISO-8859-1", "UTF-8"), 0, 0, 'C');
$pdf->Ln();

$recibo = "";
$sw = false;
if($GLOBALS["con_precio"]=="SW") {
	while($row = mysqli_fetch_array($rs))
	{
		$sql = "SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = '" . $row["metodo_pago"] . "';";
		$rs2 = mysqli_query($link, $sql) or die(mysqli_error($link));
		$row2 = mysqli_fetch_array($rs2);

		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(10, 4);
		$pdf->Cell(20, 4, substr(mb_convert_encoding($row2["valor2"], "ISO-8859-1", "UTF-8"), 0, 11), 0, 0, 'L');
		$pdf->Cell(20, 4, $row["referencia"], 0, 0, 'L');
		$pdf->Cell(20, 4, $row["moneda"] . " " . $row["monto_moneda"], 0, 0, 'R');

		if(trim($row["nro_recibo"]) != "") {
			if($sw == false) {
				$pdf->SetFont('Arial','B',8);
				$recibo = str_pad($row["nro_recibo"], 7, "0", STR_PAD_LEFT) . " / USD " . number_format($row["monto_usd"], 2, ".", ",");
				$pdf->Cell(60, 4);
				$pdf->Cell(70, 4, "Nro. RECIBO: " . $recibo, 0, 0, 'C');
				$pdf->SetFont('Arial','',8);
				$sw = true;
			}
		}
		$pdf->Ln();
	}
}

$pdf->Ln(15);			
$pdf->EndReport($GLOBALS["id"]);
require("../include/desconnect.php");

// Finalizamos enviando el buffer limpio de fugas de texto
ob_end_clean();
$pdf->Output();
?>