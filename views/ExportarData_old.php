<?php

namespace PHPMaker2021\mandrake;

// Page object
$ExportarData = &$Page;
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

$delimiter = ";";
$enclosure = " ";
$escape_char = "\\";
$record_seperator = "\r\n";

$sql = "SELECT valor1 FROM parametro WHERE codigo = '022';";
$fecha = ExecuteScalar($sql);
$fecha = '2021-01-01';

// $salida = shell_exec('sh /home/parcelassh.sh');

$path = "/home4/drophqsc/dropharmadm.com/profit/";
// $path = "C:/laragon/www/mandrake/maker/";

// Clientes
$sql = "SELECT 
			a.id AS CODIGO, a.nombre AS NOMBRE, replace(direccion, ';', '') AS DIRECCION, a.telefono1 AS TELEFONOS, 
			a.ci_rif AS RFI  
		FROM 
			cliente AS a;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "clientes.csv";
	// Create a file pointer

	//$f = fopen('php://memory', 'w'); 
	$f = fopen($filename, 'w'); 
	// Set column headers 

	// $fields = array('CODIGO', 'NOMBRE', 'DIRECCION', 'TELEFONOS', 'RFI');
	fputcsv($f, $fields, $delimiter, $escape_char); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['DIRECCION'], $value['TELEFONOS'], $value['RFI']); 
        //fputcsv($f, $lineData, $delimiter);
        //fputcsv($f, $lineData, $delimiter, $enclosure, $record_seperator);
        _fputcsv($f, $lineData);
	}
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    //header('Content-Type: text/csv'); 
    //header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    //fpassthru($f);	
}

// Asesores
$sql = "SELECT 
	a.id AS CODIGO, a.nombre AS NOMBRE, a.ci_rif AS CEDULA, IFNULL(a.telefono1, '') AS TELEFONO, 
	'' AS comentario 
FROM 
	asesor AS a;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "asesores.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['CEDULA'], $value['TELEFONO'], $value['comentario']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
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
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
	}
    fseek($f, 0); 
}

// Proveedores
$sql = "SELECT 
	id AS CODIGO, nombre AS NOMBRE, replace(IFNULL(direccion, ''), ';', '') AS DIRECCION, IFNULL(telefono1, '') AS TELEFONO, 
	IFNULL(ci_rif, '') AS RIF 
FROM 
	proveedor;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "proveedores.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['CODIGO'], $value['NOMBRE'], $value['DIRECCION'], $value['TELEFONO'], $value['RIF']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
	}
    fseek($f, 0); 
}

// Ventas
$sql = "SELECT 
	nro_documento AS NUMERO_FACTURA, cliente AS CODIGO_CLIENTE, 
	REPLACE(moneda, 'Bs. S', 'Bs.') AS MONEDA, 1 AS TASA_MONEDA, (SELECT users.asesor FROM usuario AS users WHERE rtrim(ltrim(users.username)) = rtrim(ltrim(salidas.asesor)) LIMIT 0, 1) AS CODIGO_VENDEDOR, 
	-- asesor AS CODIGO_VENDEDOR, 
	fecha AS FECHA_EMISION, 
	IFNULL(fecha, '0000-00-00') AS FECHA_VENCIMIENTO, 
	IFNULL(nro_control, '') AS NUMERO_DE_CONTROL, monto_total AS TOTAL_BRUTO, iva AS MONTO_IMPUESTO, 
	total AS TOTAL_NETO, IFNULL(documento, 'FC') AS tipo_movimiento 
FROM salidas 
WHERE tipo_documento = 'TDCFCV' AND estatus = 'PROCESADO' AND fecha >= '$fecha';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasVentas.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_FACTURA'], $value['CODIGO_CLIENTE'], $value['MONEDA'], $value['TASA_MONEDA'], $value['CODIGO_VENDEDOR'], $value['FECHA_EMISION'], $value['FECHA_VENCIMIENTO'], $value['NUMERO_DE_CONTROL'], $value['TOTAL_BRUTO'], $value['MONTO_IMPUESTO'], $value['TOTAL_NETO'], $value['tipo_movimiento']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
	}
    fseek($f, 0); 
}

// Ventas Renglones
$sql = "SELECT 
	IF(@fila=b.nro_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=b.nro_documento AS NUMERO_FACTURA, IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0)+ 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0))+IFNULL(a.precio, 0)+ 0.0000000001, 2) AS MONTO_NETO 
FROM 
	(SELECT @rownum:=1) r, 
	(SELECT @fila:='') v, 
	entradas_salidas AS a 
	JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
WHERE 
	a.tipo_documento = 'TDCFCV' AND b.estatus = 'PROCESADO' AND a.precio IS NOT NULL ORDER BY a.id_documento, 1;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasVentasRenglones.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_RENGLON'], $value['NUMERO_FACTURA'], $value['PORCENTAJE_DEL_IMPUESTO'], $value['MONTO_IMPUESTO'], $value['MONTO_NETO']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
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
	total AS TOTAL_NETO, IFNULL(documento, 'FC') AS tipo_movimiento  
FROM entradas 
WHERE tipo_documento = 'TDCFCC' AND estatus = 'PROCESADO' AND fecha >= '$fecha';";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasCompras.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_FACTURA_SISTEMA'], $value['NUMERO_FACTURA'], $value['CODIGO_PROVEEDOR'], $value['MONEDA'], $value['TASA_MONEDA'], $value['FECHA_EMISION'], $value['FECHA_VENCIMIENTO'], $value['NUMERO_DE_CONTROL'], $value['TOTAL_BRUTO'], $value['MONTO_IMPUESTO'], $value['TOTAL_NETO'], $value['tipo_movimiento']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
	}
    fseek($f, 0); 
}

// Compras Renglones
$sql = "SELECT 
	IF(@fila=a.id_documento, @rownum:=@rownum+1, @rownum:=1) AS NUMERO_RENGLON, 
	@fila:=a.id_documento AS NUMERO_FACTURA_SISTEMA, 
	b.nro_documento AS NUMERO_FACTURA, IFNULL(a.alicuota, 0) AS PORCENTAJE_DEL_IMPUESTO, 
	ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo, 0) + 0.0000000001, 2) AS MONTO_IMPUESTO, 
	ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.costo, 0))+IFNULL(a.costo, 0)+ 0.0000000001, 2) AS MONTO_NETO 
FROM 
	(SELECT @rownum:=1) r, 
	(SELECT @fila:=0) v, 
	entradas_salidas AS a 
	JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
WHERE 
	a.tipo_documento = 'TDCFCC' AND b.estatus = 'PROCESADO' ORDER BY a.id_documento, 1 ASC;";
$rows = ExecuteRows($sql);
if($rows > 0) {
	$filename = $path . "FacturasComprasRenglones.csv";
	$f = fopen($filename, 'w'); 
	fputcsv($f, $fields, $delimiter); 

	foreach ($rows as $key => $value) {
		$lineData = array($value['NUMERO_RENGLON'], $value['NUMERO_FACTURA_SISTEMA'], $value['NUMERO_FACTURA'], $value['PORCENTAJE_DEL_IMPUESTO'], $value['MONTO_IMPUESTO'], $value['MONTO_NETO']); 
        fputcsv($f, $lineData, $delimiter, $enclosure, $escape_char);
	}
    fseek($f, 0); 
}

echo '<div class="alert alert-success">
  		<strong>Proceso finalizado!</strong> ...
  	</div>';

?>

<?= GetDebugMessage() ?>
