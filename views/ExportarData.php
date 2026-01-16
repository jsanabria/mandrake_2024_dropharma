<?php

namespace PHPMaker2024\mandrake;

// Page object
$ExportarData = &$Page;
?>
<?php
$Page->showMessage();
?>
<h2>Preceso efectuado</h2>
<?php


function _fputcsv($handle, $fields, $delimiter = ";", $enclosure = '', $escape_char = "\\", $record_seperator = "\r\n")
{
    $result = [];
    foreach ($fields as $field) {
        $result[] = $enclosure . str_replace($enclosure, $escape_char . $enclosure, $field) . $enclosure;
    }
    return fwrite($handle, implode($delimiter, $result) . $record_seperator);
}

$sql = "SELECT valor1 FROM parametro WHERE codigo = '022';";
$fecha = ExecuteScalar($sql);

// $salida = shell_exec('sh /home/parcelassh.sh');

$path = "/home4/drophqsc/dropharmadm.com/profit/";
// $path = "C:/laragon/www/mandrake/maker/";

// Clientes
$sql = "SELECT 
			a.id AS CODIGO, replace(a.nombre, ';', '') AS NOMBRE, replace(replace(direccion, ';', ''), '\r\n', ' ') AS DIRECCION, a.telefono1 AS TELEFONOS, 
			a.ci_rif AS RIF, b.nombre AS TIPO 
		FROM 
			cliente AS a LEFT OUTER JOIN tarifa AS b ON b.id = a.tarifa ORDER BY a.id;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "clientes.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['DIRECCION'], $value['TELEFONOS'], $value['RIF'], $value['TIPO']); 
        //fputcsv($f, $lineData, $delimiter);
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Asesores
$sql = "SELECT 
	a.id AS CODIGO, a.nombre AS NOMBRE, a.ci_rif AS CEDULA, IFNULL(a.telefono1, '') AS TELEFONO, 
	'' AS comentario 
FROM 
	asesor AS a ORDER BY a.id;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "asesores.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['CEDULA'], $value['TELEFONO'], $value['comentario']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Monedas
$sql = "SELECT 
	valor1 AS CODIGO, valor1 AS NOMBRE 
FROM parametro WHERE codigo = '006';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "monedas.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Proveedores
$sql = "SELECT 
	id AS CODIGO, nombre AS NOMBRE, replace(replace(IFNULL(direccion, ''), ';', ''), '\r\n', ' ') AS DIRECCION, IFNULL(telefono1, '') AS TELEFONO, 
	IFNULL(ci_rif, '') AS RIF 
FROM 
	proveedor ORDER BY id;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "proveedores.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['DIRECCION'], $value['TELEFONO'], $value['RIF']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Ventas
$sql = "SELECT 
	nro_documento AS NUMERO_FACTURA, cliente AS CODIGO_CLIENTE, 
	REPLACE(moneda, 'Bs. S', 'Bs.') AS MONEDA, IFNULL(tasa_dia, 1) AS TASA_MONEDA, (SELECT users.asesor FROM usuario AS users WHERE rtrim(ltrim(users.username)) = rtrim(ltrim(salidas.asesor)) LIMIT 0, 1) AS CODIGO_VENDEDOR, 
	-- asesor AS CODIGO_VENDEDOR, 
	fecha AS FECHA_EMISION, 
	DATE_ADD(IFNULL(fecha , '0000-00-00'), INTERVAL dias_credito DAY) AS FECHA_VENCIMIENTO, 
	IFNULL(nro_control, '') AS NUMERO_DE_CONTROL, monto_total AS TOTAL_BRUTO, iva AS MONTO_IMPUESTO, 
	total AS TOTAL_NETO, IFNULL(documento, 'FC') AS tipo_movimiento, IF(estatus = 'ANULADO', 1, 0) AS ANULADO, IFNULL(descuento, 0) AS DESCUENTO, IF(IFNULL(descuento, 0) = 0, 0, IFNULL(monto_sin_descuento, 0)-IFNULL(monto_total, 0)) AS MONTO_DESC
FROM salidas 
WHERE tipo_documento = 'TDCFCV' AND estatus IN ('PROCESADO','ANULADO') AND IFNULL(nro_control, '') <> '' AND fecha >= '$fecha';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasVentas.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_FACTURA'], $value['CODIGO_CLIENTE'], $value['MONEDA'], $value['TASA_MONEDA'], $value['CODIGO_VENDEDOR'], $value['FECHA_EMISION'], $value['FECHA_VENCIMIENTO'], $value['NUMERO_DE_CONTROL'], $value['TOTAL_BRUTO'], $value['MONTO_IMPUESTO'], $value['TOTAL_NETO'], $value['tipo_movimiento'], $value['ANULADO'], $value['DESCUENTO'], $value['MONTO_DESC']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Ventas Renglones
/*
	IF(@fila=b.nro_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=b.nro_documento AS NUMERO_FACTURA, IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0)+ 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0))+IFNULL(a.precio, 0)+ 0.0000000001, 2) AS MONTO_NETO,
	a.articulo AS ARTICULO, ABS(a.cantidad_movimiento) AS CANTIDAD
*/
$sql = "SELECT 
	IF(@fila=b.nro_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=b.nro_documento AS NUMERO_FACTURA, 
	a.articulo AS ARTICULO, 
	ABS(a.cantidad_movimiento) AS CANTIDAD, 
	ROUND(IFNULL(a.costo_unidad, 0)+ 0.0000000001, 2) AS COSTO_UNITARIO,
	ROUND(IFNULL(a.costo, 0)+ 0.0000000001, 2) AS COSTO_TOTAL,
	IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio_unidad, 0)+ 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio_unidad, 0))+IFNULL(a.precio_unidad, 0)+ 0.0000000001, 2) AS MONTO_UNITARIO,
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0))+IFNULL(a.precio, 0)+ 0.0000000001, 2) AS MONTO_TOTAL, 
	b.doc_afectado  
FROM 
	(SELECT @rownum:=1) r, 
	(SELECT @fila:='') v, 
	entradas_salidas AS a 
	JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
WHERE 
	a.tipo_documento = 'TDCFCV' AND b.estatus IN ('PROCESADO','ANULADO') AND IFNULL(b.nro_control, '') <> '' AND a.precio IS NOT NULL AND b.fecha >= '$fecha' ORDER BY a.id_documento, 1;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasVentasRenglones.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_RENGLON'], $value['NUMERO_FACTURA'], $value['ARTICULO'], $value['CANTIDAD'], $value['COSTO_UNITARIO'], $value['COSTO_TOTAL'], $value['PORCENTAJE_DEL_IMPUESTO'], $value['MONTO_IMPUESTO'], $value['MONTO_UNITARIO'], $value['MONTO_TOTAL'], $value['doc_afectado']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Compras
$sql = "SELECT 
	id AS NUMERO_FACTURA_SISTEMA, 
	nro_documento AS NUMERO_FACTURA, proveedor AS CODIGO_PROVEEDOR, 
	REPLACE(moneda, 'Bs. S', 'Bs.') AS MONEDA, IFNULL(tasa_dia, 0) AS TASA_MONEDA, 
	fecha AS FECHA_EMISION, 
	IFNULL(fecha, '0000-00-00') AS FECHA_VENCIMIENTO, 
	IFNULL(nro_control, '') AS NUMERO_DE_CONTROL, monto_total AS TOTAL_BRUTO, iva AS MONTO_IMPUESTO, 
	total AS TOTAL_NETO, IFNULL(documento, 'FC') AS tipo_movimiento, IF(estatus = 'ANULADO', 1, 0) AS ANULADO   
FROM entradas 
WHERE tipo_documento = 'TDCFCC' AND estatus IN ('PROCESADO','ANULADO') AND IFNULL(nro_control, '') <> '' AND fecha >= '$fecha';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasCompras.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_FACTURA_SISTEMA'], $value['NUMERO_FACTURA'], $value['CODIGO_PROVEEDOR'], $value['MONEDA'], $value['TASA_MONEDA'], $value['FECHA_EMISION'], $value['FECHA_VENCIMIENTO'], $value['NUMERO_DE_CONTROL'], $value['TOTAL_BRUTO'], $value['MONTO_IMPUESTO'], $value['TOTAL_NETO'], $value['tipo_movimiento'], $value['ANULADO']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Compras Renglones
/*
	IF(@fila=a.id_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=a.id_documento AS NUMERO_FACTURA_SISTEMA, 
	b.nro_documento AS NUMERO_FACTURA, IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo, 0) + 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo, 0))+IFNULL(a.costo, 0)+ 0.0000000001, 2) AS MONTO_NETO,
	a.articulo AS ARTICULO, ABS(a.cantidad_movimiento) AS CANTIDAD
*/
$sql = "SELECT 
	IF(@fila=a.id_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=a.id_documento AS NUMERO_FACTURA_SISTEMA, 
	b.nro_documento AS NUMERO_FACTURA, 
	a.articulo AS ARTICULO, 
	ABS(a.cantidad_movimiento) AS CANTIDAD, 
	IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo_unidad, 0) + 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo_unidad, 0))+IFNULL(a.costo_unidad, 0)+ 0.0000000001, 2) AS MONTO_NETO, 
	ABS(a.cantidad_movimiento) * ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo_unidad, 0))+IFNULL(a.costo_unidad, 0)+ 0.0000000001, 2) AS MONTO_TOTAL 
FROM 
	(SELECT @rownum:=1) r, 
	(SELECT @fila:=0) v, 
	entradas_salidas AS a 
	JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
WHERE 
	a.tipo_documento = 'TDCFCC' AND b.estatus IN ('PROCESADO','ANULADO') AND IFNULL(nro_control, '') <> '' AND b.fecha >= '$fecha' ORDER BY a.id_documento, 1 ASC;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasComprasRenglones.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_RENGLON'], $value['NUMERO_FACTURA_SISTEMA'], $value['NUMERO_FACTURA'], $value['ARTICULO'], $value['CANTIDAD'], $value['PORCENTAJE_DEL_IMPUESTO'], $value['MONTO_IMPUESTO'], $value['MONTO_NETO'], $value['MONTO_TOTAL']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

// Articulos
$sql = "SELECT 
		a.id AS CODIGO, a.codigo AS CODIGO_ARTICULO, 
		IF(RTRIM(IFNULL(a.nombre_comercial, '')) = '', a.principio_activo, a.nombre_comercial) AS NOMBRE_COMERCIAL, 
		IFNULL(a.principio_activo, '') AS PRINCIPIO_ACTIVO,
		IFNULL(a.presentacion, '') AS PRESENTACION,
		IFNULL(b.nombre, '') AS FABRICANTE, 
		a.ultimo_costo AS COSTO, c.precio AS PRECIO, (SELECT alicuota FROM alicuota WHERE codigo = a.alicuota AND activo = 'S') AS TIPO_IVA 
FROM 
	articulo AS a 
	LEFT OUTER JOIN fabricante AS b ON b.id = a.fabricante
	LEFT OUTER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.fabricante = a.fabricante AND c.tarifa = 2 
	LEFT OUTER JOIN tarifa AS d ON d.id = c.tarifa AND d.patron = 'S';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "Articulos.csv";
	$f = fopen($filename, 'w'); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['CODIGO_ARTICULO'], $value['NOMBRE_COMERCIAL'], $value['PRINCIPIO_ACTIVO'], $value['PRESENTACION'], $value['FABRICANTE'], $value['COSTO'], $value['PRECIO'], $value['TIPO_IVA']); 
        _fputcsv($f, $lineData);
	}
    fseek($f, 0); 
}

echo '<div class="alert alert-success">
  		<strong>Proceso finalizado!</strong> ...
  	</div>';
?>

<?= GetDebugMessage() ?>
