<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/*
$host="localhost";
$user="root";
$password="";
$data="mandrake";
*/

$host="localhost";
$user="drophqsc_drake";
$password="Tomj@vas001";
$data="drophqsc_mandrake";

$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 



function _fputcsv($handle, $fields, $delimiter = ";", $enclosure = '', $escape_char = "\\", $record_seperator = "\r\n")
{
    $result = [];
    foreach ($fields as $field) {
        $result[] = $enclosure . str_replace($enclosure, $escape_char . $enclosure, $field) . $enclosure;
    }
    return fwrite($handle, implode($delimiter, $result) . $record_seperator);
}

$path = "/home4/drophqsc/dropharmadm.com/ftpexportar/pedidos/";
// $path = "C:\\laragon\\www\\mandrake\\db\\Maquina_Fiscal\\pedidos\\";

// Listado de todos los pedidos de venta pendinetes por procesar
/*
$sql = "SELECT 
			DISTINCT a.cliente, a.nro_documento, a.id  
		FROM 
			salidas AS a 
			JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
			LEFT OUTER JOIN articulo AS c ON c.id = b.articulo 
		WHERE 
			a.tipo_documento = 'TDCPDV' AND a.estatus = 'NUEVO' 
		ORDER BY 1, 2;";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	$file = "PEDIDO" . $row["cliente"] . "_" . $row["nro_documento"] . ".TXT";
	$filename = $path . $file;
	$f = fopen($filename, 'w'); 
	$sql = "SELECT 
				a.cliente, CONCAT(IFNULL(c.principio_activo, ''), ' ', IFNULL(c.presentacion, '')) AS articulo, 
				ABS(b.cantidad_movimiento) AS cantidad, b.articulo AS codigo, '' AS archivo, c.codigo_de_barra 
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
				LEFT OUTER JOIN articulo AS c ON c.id = b.articulo 
			WHERE 
				a.tipo_documento = 'TDCPDV' AND a.id = " . $row["id"] . " AND a.estatus = 'NUEVO' 
			ORDER BY a.cliente, b.id_documento;"; 
	$rs2 = mysqli_query($link, $sql);
	while($row2 = mysqli_fetch_array($rs2)) {
		$lineData = array($row['cliente'], $row2['articulo'], $row2['cantidad'], $row2['codigo'], $file, $row2['codigo_de_barra']); 
	    //fputcsv($f, $lineData, $delimiter);
	    _fputcsv($f, $lineData);
	}
	fseek($f, 0); 
}
*/

// Listado del inventario 
$path = "/home4/drophqsc/dropharmadm.com/ftpexportar/";
// $path = "C:\\laragon\\www\\mandrake\\db\\Maquina_Fiscal\\";
$file = "inventario.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 
$sql = "SELECT 
			a.id AS codigo, CONCAT(IFNULL(a.principio_activo, ''), IFNULL(a.presentacion, '')) AS descripcion, 
			'MED' AS grupo, 'MEDICINA' AS grupo_des, a.codigo_de_barra, c.precio, 
			a.cantidad_en_mano AS existencia1, 0 AS existencia2, 0 AS existencia3, 0 AS existencia4, 
			(c.precio - (c.precio * (a.descuento/100))) AS oferta, 'NO' AS regulado, 
			(SELECT alicuota FROM alicuota WHERE codigo = a.alicuota AND activo = 'S') AS impuesto, 
			0 AS lote, 0 AS preempaque, 0 AS oferta_preempaque, a.fabricante, b.nombre AS fabricante_des, 
			ROUND((c.precio/(SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0 ,1)), 2) AS precio_usd 
		FROM 
			articulo AS a 
			LEFT JOIN fabricante AS b ON b.Id = a.fabricante 
			INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = 2 
		WHERE 
			a.activo = 'S' AND a.cantidad_en_mano > 0 AND a.lista_pedido NOT IN ('PED001', 'PED005') AND b.activo = 'S';"; 
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['codigo'], $row['descripcion'], $row['grupo'], $row['grupo_des'], $row['codigo_de_barra'], $row['precio'], $row['existencia1'], $row['existencia2'], $row['existencia3'], $row['existencia4'], $row['oferta'], $row['regulado'], $row['impuesto'], $row['lote'], $row['preempaque'], $row['oferta_preempaque'], $row['fabricante'], $row['fabricante_des'], $row['precio_usd']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 



// Listado de todas los facturas recientes de los clientes
$sql = "SELECT 
				a.id, a.cliente, REPLACE(a.nro_documento, 'FACT-', '') AS nro_documento, a.fecha,  
				0.00 AS tot1, 0.00 AS tot2, a.monto_total, a.alicuota_iva, a.iva, a.total 
			FROM 
				salidas AS a 
			WHERE 
				a.tipo_documento = 'TDCFCV' 
				AND a.estatus = 'PROCESADO' 
				AND a.fecha BETWEEN DATE_ADD(CURDATE(), INTERVAL -1 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
			ORDER BY a.id DESC;";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	$sql3 = "SELECT factura FROM ftp_fact_pedi_procesado WHERE factura = '" . $row["id"] . "';";
	$rs3 = mysqli_query($link, $sql3);
	if(!$row3 = mysqli_fetch_array($rs3)) {
		$path = "/home4/drophqsc/dropharmadm.com/ftpexportar/clientes/" . $row["cliente"] . "/";
		// $path = "C:\\laragon\\www\\mandrake\\db\\Maquina_Fiscal\\clientes\\" . $row["cliente"] . "\\";
		$file = $row["nro_documento"] . ".TXT";
		if (!file_exists($path)) {
		    mkdir($path, 0777, true);
		    // mkdir($path);
		}	
		$filename = $path . $file;
		$f = fopen($filename, 'w'); 
		$sql = "SELECT 
					REPLACE(a.nro_documento, 'FACT-', '') AS nro_documento, 
					ABS(b.cantidad_movimiento) AS cantidad, c.codigo_de_barra, 
					CONCAT(IFNULL(c.principio_activo, ''), ' ', IFNULL(c.presentacion, '')) AS articulo, 
					b.precio_unidad_sin_desc, (b.precio_unidad_sin_desc-b.precio_unidad) AS descuento1, 
					0 AS descuento2, 0 AS descuento3, b.precio_unidad, b.alicuota, b.precio, 
					0 AS regulado, c.id 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
					LEFT OUTER JOIN articulo AS c ON c.id = b.articulo 
				WHERE 
					a.tipo_documento = 'TDCFCV' AND a.id = " . $row["id"] . " 
					AND a.estatus = 'PROCESADO' 
				ORDER BY a.cliente, b.id_documento;"; 
		$rs2 = mysqli_query($link, $sql);
		while($row2 = mysqli_fetch_array($rs2)) {
			$lineData = array("D", $row2['nro_documento'], $row2['cantidad'], $row2['codigo_de_barra'], $row2['articulo'], $row2['precio_unidad_sin_desc'], $row2['descuento1'], $row2['descuento2'], $row2['descuento3'], $row2['precio_unidad'], $row2['alicuota'], $row2['precio'], $row2['regulado'], $row2['id']); 
		    //fputcsv($f, $lineData, $delimiter);
		    _fputcsv($f, $lineData);
		}
		$lineData = array("S", $row['tot1'], $row['tot2'], $row['monto_total'], $row['alicuota_iva'], $row['iva'], $row['total']); 
	    //fputcsv($f, $lineData, $delimiter);
	    _fputcsv($f, $lineData);
		fseek($f, 0); 

		$sql = "INSERT INTO ftp_fact_pedi_procesado	(id, factura, pedido, fecha_hora) VALUES (NULL, '" . $row["id"] . "', '', NOW())";
		mysqli_query($link, $sql);
	}
}

//echo 'Proceso finalizado!';
?>