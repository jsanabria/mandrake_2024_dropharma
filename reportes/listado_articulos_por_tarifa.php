<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect.php");

$username = trim($_REQUEST["username"]);
$codcliente = trim($_REQUEST["codcliente"]);
$tarifa = trim($_REQUEST["tarifa"]);

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];

$descuento = 0;

$sql = "SELECT cliente FROM usuario WHERE username = '$username';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $codcliente = intval($row["cliente"]);
else $codcliente = 0;

if($codcliente > 0) {
    $sql = "SELECT descuento FROM cliente WHERE id = $codcliente;";
    $rs = mysqli_query($link, $sql);
    if($row = mysqli_fetch_array($rs)) $descuento = intval($row["descuento"]);
    else $descuento = 0;
}


if($tarifa == "") {
	$sql = "SELECT tarifa FROM cliente WHERE id = $codcliente"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$tarifa = intval($row["tarifa"]);
}

$sql = "SELECT nombre FROM tarifa WHERE id = $tarifa;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$GLOBALS["titulo"] = "ARTICULOS TARIFA " . $row["nombre"];
$GLOBALS["condicion_comercial"] = $descuento; 

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];

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
		$direccion = $row["direccion"]; 
		$cia =  $row["nombre"];
		$logo =  $row["logo"];

		
		if(trim($logo) != "") {
			$this->Image("../carpetacarga/$logo", 10, 10, 50);
		}
		
		$this->Ln(8);
		$this->SetFont('Arial','',8);
		$this->Cell(200, 5, "Fecha: " . date("d/m/Y"),0,0,'R');
		$this->Ln();
		$this->Cell(200, 5, "Hora: " . date("H:i:s"),0,0,'R');

		$this->Ln(10);
		
		$this->SetFont('Arial','B',14);
		$this->Cell(200, 6, mb_convert_encoding(substr($GLOBALS["titulo"], 0, 55), "UTF-8", mb_detect_encoding($GLOBALS["titulo"])),0,0,'C');
		if(floatval($GLOBALS["condicion_comercial"]) > 0) {
			$this->Ln();
			$cc = "Descuento Comercial " . $GLOBALS["condicion_comercial"] . "%";
			$this->Cell(200, 6, mb_convert_encoding($cc, "UTF-8", mb_detect_encoding($GLOBALS["titulo"])),0,0,'C');
		}
		$this->SetFont('Arial','B',8);


		$this->Ln(5);
		

		require("../include/desconnect.php");
		$this->Ln(6);

		//$this->Cell(5, 6);
		$this->Cell(20, 6, "LAB.", 1, 0, 'L');
		$this->Cell(50, 6, "MEDICAMENTO", 1, 0, 'L');
		$this->Cell(45, 6, "PRESENTACION", 1, 0, 'L');
		$this->Cell(25, 6, "CODBAR", 1, 0, 'L');
		$this->Cell(35, 6, "PRECIO", 1, 0, 'R');
		$this->Cell(10, 6, "DESC", 1, 0, 'R');
		$this->Cell(15, 6, "CANT", 1, 0, 'C');
		$this->Cell(10, 6, "U.M.", 1, 0, 'C');
		$this->Ln(6);
	}
	
	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function EndReport($items)
	{
		//$this->AddPage();
		//require("../connect.php");
		$this->SetFont('Arial','B',8);
		$this->Ln();
		$this->Cell(200, 5, "TOTAL ARTICULOS: "  . $items, 0, 0, 'R');
	}
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(2,10,10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);

$sql = "SELECT 
        a.id, 
        a.foto, IFNULL(a.nombre_comercial, '') AS nombre_comercial, IFNULL(b.nombre, '')  AS fabricante, 
        IFNULL(a.principio_activo, '') AS principio_activo, IFNULL(a.presentacion, '') AS presentacion, c.precio AS precio, 
        (a.cantidad_en_mano+a.cantidad_en_pedido)-a.cantidad_en_transito AS cantidad_en_mano, 
        d.descripcion AS unidad_medida, a.descuento, IFNULL(a.codigo_de_barra, '') AS codbar  
      FROM 
        articulo AS a 
        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
        INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
        INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
      WHERE 
        a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 AND b.activo = 'S' 
      ORDER BY a.principio_activo, a.presentacion;"; 

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$items = 0;
while($row = mysqli_fetch_array($rs))
{

    $sql = "SELECT 
                SUM(x.cantidad_movimiento) AS cantidad_en_mano, MAX(fecha_vencimiento) AS fecha_vencimiento   
            FROM 
                (
                    SELECT 
                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                        a.fecha_vencimiento, 
                        a.cantidad_movimiento 
                    FROM 
                        entradas_salidas AS a 
                        JOIN entradas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                            (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                        ) AND a.articulo = " . $row["id"] . " AND a.almacen = '$almacen' AND a.newdata = 'S' 
                    UNION ALL SELECT 
                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                        a.fecha_vencimiento, 
                        a.cantidad_movimiento  
                    FROM 
                        entradas_salidas AS a 
                        JOIN salidas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                            (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                        ) AND a.articulo = " . $row["id"] . " AND a.almacen = '$almacen' AND a.newdata = 'S' 
                ) AS x 
            WHERE 1;";
    $result2 = mysqli_query($link, $sql);
    $row2 = mysqli_fetch_array($result2);
    $FechaVencimiento = isset($row2["fecha_vencimiento"]) ? $row2["fecha_vencimiento"] : "";
    $onHand = floatval($row2["cantidad_en_mano"]);

	if($onHand > 0) {
		$pdf->SetFont('Arial', '', 8);

		//$pdf->Cell(5, 5);
		$pdf->Cell(20, 5, $row["fabricante"], 1, 0, 'L');
		$art = (trim($row["nombre_comercial"]) == "" ? "" : trim($row["nombre_comercial"]) . " ") . trim($row["principio_activo"]);
		$pdf->Cell(50, 5, substr($art, 0, 25), 1, 0, 'L');
		$pre = trim($row["presentacion"]);
		$pdf->Cell(45, 5, substr($pre, 0, 25), 1, 0, 'L');
		$pdf->Cell(25, 5, trim($row["codbar"]), 1, 0, 'L');
		// $pdf->Cell(35, 5, number_format($row["precio"], 2, ".", ",") . " USD", 1, 0, 'R');
		$precio = floatval($row["precio"]);
		$precio = $precio - ($precio * ($row["descuento"]/100));
		$precio = $precio - ($precio * ($descuento/100));
		$pdf->Cell(35, 5, number_format($row["precio"], 2, ".", ",") . " / " . number_format($precio, 2, ",", "") . " USD", 1, 0, 'R');
		$pdf->Cell(10, 5, (floatval($row["descuento"]) == 0 ? '' : number_format($row["descuento"], 0, ".", ",") . "%"), 1, 0, 'R');
		$pdf->Cell(15, 5, "", 1, 0, 'C');
		$pdf->Cell(10, 5, substr($row["unidad_medida"], 0, 4), 1, 0, 'C');
		$pdf->Ln();
		$items++;

		if(strlen($art) > 25 or strlen($pre) > 25) {
			//$pdf->Cell(5, 5);
			$pdf->Cell(20, 5, "", 1, 0, 'C');
			$pdf->Cell(50, 5, substr($art, 25, 25), 1, 0, 'L');
			$pdf->Cell(45, 5, substr($pre, 25, 25), 1, 0, 'L');
			$pdf->Cell(95, 5, "", 1, 0, 'C');
			$pdf->Ln();
		}
	}
}

$pdf->EndReport($items);

	
require("../include/desconnect.php");

$pdf->Output();
?>