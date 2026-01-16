<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$host="localhost";
$user="root";
$password="";
$data="mandrake";


/*
$host="localhost";
$user="drophqsc_drake";
$password="Tomj@vas001";
$data="drophqsc_mandrake";
*/

$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 



function _fputcsv($handle, $fields, $delimiter = "|", $enclosure = '', $escape_char = "\\", $record_seperator = "\r\n")
{
    $result = [];
    // $i = 0;
    foreach ($fields as $field) {
    	// echo "$i) $field <br>";
    	// $i++;
        $result[] = $enclosure . str_replace($enclosure, $escape_char . $enclosure, $field) . $enclosure;
    }
    // die();
    return fwrite($handle, implode($delimiter, $result) . $record_seperator);
}

function _fputcsv2($handle, $fields, $delimiter = "|", $enclosure = '', $escape_char = "\\", $record_seperator = "\r\n")
{
    $result = [];
    // $i = 0;
    foreach ($fields as $field) {
    	// cho "$i) $field <br>";
    	// $i++;
    	try {
        $result[] = $enclosure . str_replace($enclosure, $escape_char . $enclosure, $field) . $enclosure;
        } catch (Exception $e) {
        	echo 'Caught exception: ',  $e->getMessage(), " $field \n";
        }
    }
    // echo "<br><br><br>";
    // die();
    return fwrite($handle, implode($delimiter, $result) . $record_seperator);
}


function _limpiarstr($string) 
{
	$string = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $string);
	$string = preg_replace('([^A-Za-z0-9 \._\-@])', '', $string);
	return $string;
}


// $path = "/home4/drophqsc/dropharmadm.com/ftpexportar/pedidos/";
$path = "C:\\laragon\\www\\mandrake_2024_dropharma\\maker\\FullTech360\\";

// 1.- PRODUCTO (producto.txt)
$file = "producto.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$tasa = 1;
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0 ,1";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tasa = floatval($row["tasa"]);
$sql = "SELECT 
			IFNULL(a.codigo_de_barra, '') AS barra, 
			IFNULL(a.id, '') AS codprod, 
			CONCAT(IFNULL(a.principio_activo, ''), IFNULL(a.presentacion, '')) AS desprod, 
			'M' AS tipo, 
			(SELECT alicuota FROM alicuota WHERE codigo = a.alicuota AND activo = 'S') AS iva, 
			'N' AS regulado, 
			'N/A' AS Codprov, 
			ROUND((c.precio), 2) AS precio1, 
			a.cantidad_en_mano AS cantidad, 
			1 AS original, 
			a.descuento AS da, 
			0.00 AS oferta, 
			0.00 AS upre, 
			0.00 AS ppre, 
			ROUND((c.precio), 2) AS psugerido, 
			0.00 AS pgris, 
			0 AS nuevo, 
			/*
			(SELECT 
				IFNULL(a1.fecha, '') AS fecha  
			FROM 
				entradas AS a1 
				JOIN entradas_salidas AS b1 ON b1.id_documento = a1.id AND b1.tipo_documento = a1.tipo_documento 
			WHERE 
				a1.tipo_documento = 'TDCNRP' AND a1.estatus = 'PROCESADO' AND b1.articulo = a.id
			ORDER BY a1.fecha DESC 
			LIMIT 0, 1) AS fechafalla, 
			*/ 
			NOW()  AS fechafalla,
			'PRINCIPAL' AS tipocatalogo, 
			0 AS cuarentena, 
			0.00 AS dctoneto, 
			IFNULL(a.lote, '') AS lote, 
			IFNULL(a.fecha_vencimiento, '') AS fecvence, 
			b.nombre AS marcamodelo, 
			a.principio_activo AS pactivo, 
			a.ultimo_costo AS costo, 	
			'N/A' AS ubicacion, 
			IFNULL(a.nombre_comercial, '') AS descorta, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			NOW() AS feccatalogo, 
			(SELECT campo_descripcion FROM tabla WHERE tabla = 'LISTA_PEDIDO' AND campo_codigo = a.lista_pedido) AS departamento, 
			'N/A' AS grupo, 
			'N/A' AS subgrupo, 
			a.lista_pedido AS opc1, 
			'N/A' AS opc2, 
			'N/A' AS opc3, 
			ROUND((c.precio), 2) AS precio2, 
			ROUND((c.precio), 2) AS precio3, 
			0.00 AS precio4, 
			0.00 AS precio5, 
			0.00 AS precio6, 
			1 AS undmin, 
			99999999 AS undmax, 
			0 AS undmultiplo, 
			0 AS cantpub, 
			a.cantidad_en_mano AS cantreal, 
			0 AS manejalote, 
			0 AS indevolutivo, 
			'N/A' AS codcolor, 
			'N/A' AS codtalla, 
			IF(a.lista_pedido = 'PED001', 0, 1) AS psicotropico, 
			'NORMAL' AS clase, 
			'BSS' AS moneda, 
			$tasa AS factorcambiario, 
			IF(a.lista_pedido = 'PED002', 0, 1) AS refrigerado, 
			0 AS FlagFactOM, 
			0.00 AS dv, 
			0.00 AS dvDetalle, 
			0 AS SuperOFertaMincp, 
			0 AS dcredito, 
			0 AS cantcomp, 
			0.00 AS dct, 
			'N/A' AS codac3 
		FROM 
			articulo AS a 
			LEFT JOIN fabricante AS b ON b.Id = a.fabricante 
			INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = 2 
		WHERE 
			a.activo = 'S' AND a.cantidad_en_mano > 0 AND a.lista_pedido NOT IN ('PED001', 'PED005') AND b.activo = 'S';"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['barra'], $row['codprod'], _limpiarstr($row['desprod']), $row['tipo'], $row['iva'], $row['regulado'], $row['Codprov'], $row['precio1'], $row['cantidad'], $row['original'], $row['da'], $row['oferta'], $row['upre'], $row['ppre'], $row['psugerido'], $row['pgris'], $row['nuevo'], $row['fechafalla'], $row['tipocatalogo'], $row['cuarentena'], $row['dctoneto'], $row['lote'], $row['fecvence'], _limpiarstr($row['marcamodelo']), $row['pactivo'], $row['costo'], $row['ubicacion'], _limpiarstr($row['descorta']), $row['codisb'], $row['feccatalogo'], $row['departamento'], $row['grupo'], $row['subgrupo'], $row['opc1'], $row['opc2'], $row['opc3'], $row['precio2'], $row['precio3'], $row['precio4'], $row['precio5'], $row['precio6'], $row['undmin'], $row['undmax'], $row['undmultiplo'], $row['cantpub'], $row['cantreal'], $row['manejalote'], $row['indevolutivo'], $row['codcolor'], $row['codtalla'], $row['psicotropico'], $row['clase'], $row['moneda'], $row['factorcambiario'], $row['refrigerado'], $row['FlagFactOM'], $row['dv'], $row['dvDetalle'], $row['SuperOFertaMincp'], $row['dcredito'], $row['cantcomp'], $row['dct'], $row['codac3']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 


// 2.- CLIENTE (cliente.txt)
$file = "cliente.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			a.id AS codcli, 
			a.nombre AS nombre, 
			REPLACE(REPLACE(UPPER(a.ci_rif), '-', ''), 'J', '') AS rif, 
			IFNULL(a.direccion, '') AS direccion, 
			IFNULL(a.direccion, '') AS entrega, 
			IFNULL(a.telefono1, '') AS telefono, 
			IFNULL(a.contacto, '') AS contacto, 
			'N/A' AS zona, 
			'N/A' AS usuario, 
			'N/A' AS clave, 
			IFNULL(a.descuento, 0.00) AS ppago, 
			IFNULL(a.dias_credito, 0.00) AS dcredito, 
			IF(a.activo = 'S', 'ACTIVO', 'INACTIVO') AS estado, 
			'OFICINA' AS canal, 
			IFNULL(a.limite_credito, 0) AS limite, 
			IFNULL(a.condicion, '') AS tipo, 
			'LUNES' AS dcorte, 
			0.00 AS dcomercial, 
			'N/A' AS cadena, 
			'N/A' AS agenda, 
			0.00 AS dinternet, 
			IFNULL(a.ciudad, '') AS ruta, 
			'N/A' AS cb, 
			0.00 AS especial, 
			0.00 AS mpermiso, 
			0.00 AS dotro, 
			0.00 AS saldo, 
			IFNULL(a.email1, '') AS email, 
			'PRINCIPAL' AS tipocatalogo, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			1 AS usaprecio, 
			0 AS nuevo, 
			0 AS vencido, 
			0.00 AS saldoDs, 
			0.00 AS vencidoDs, 
			0.00 AS limiteDs, 
			0 AS critSepMoneda, 
			'' AS DctoPreferencial, 
			0 AS codisbactivo, 
			'N/A' AS codac3, 
			'N/A' AS coderp, 
			0 AS orden 
		FROM 
			cliente AS a 
		WHERE 1;"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['codcli'], _limpiarstr($row['nombre']), $row['rif'], _limpiarstr($row['direccion']), _limpiarstr($row['entrega']), $row['telefono'], _limpiarstr($row['contacto']), $row['zona'], $row['usuario'], $row['clave'], $row['ppago'], $row['dcredito'], $row['estado'], $row['canal'], $row['limite'], $row['tipo'], $row['dcorte'], $row['dcomercial'], $row['cadena'], $row['agenda'], $row['dinternet'], $row['ruta'], $row['cb'], $row['especial'], $row['mpermiso'], $row['dotro'], $row['saldo'], $row['email'], $row['tipocatalogo'], $row['codisb'], $row['usaprecio'], $row['nuevo'], $row['vencido'], $row['saldoDs'], $row['vencidoDs'], $row['limiteDs'], $row['critSepMoneda'], $row['DctoPreferencial'], $row['codisbactivo'], $row['codac3'], $row['coderp'], $row['orden']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 


// 3.- PROVEEDOR (proveedor.txt)
$file = "proveedor.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			a.id AS codprov, 
			IFNULL(a.nombre, '') AS nombre, 
			REPLACE(REPLACE(UPPER(a.ci_rif), '-', ''), 'J', '') AS rif, 
			IFNULL(a.direccion, '') AS direccion, 
			IFNULL(a.telefono1, '') AS telefono, 
			'' AS contacto, 
			IF(a.activo = 'S', 'ACTIVO', 'INACTIVO') AS estado, 
			IFNULL(a.email1, '') AS email, 
			1 AS diascred, 
			0.00 AS saldo, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			0.00 AS vencido, 
			0.00 AS saldoDs, 
			0.00 AS vencidoDs 
		FROM 
			proveedor AS a 
		WHERE 1;"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['codprov'], _limpiarstr($row['nombre']), _limpiarstr($row['rif']), _limpiarstr($row['direccion']), _limpiarstr($row['telefono']), $row['contacto'], $row['estado'], $row['email'], $row['diascred'], $row['saldo'], $row['codisb'], $row['vencido'], $row['saldoDs'], $row['vencidoDs']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 

// 4.- FACTURA (fact.txt)
$file = "fact.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			a.nro_documento AS factnum, 
			a.fecha AS fecha, 
			a.cliente AS codcli, 
			a.cliente AS descrip, 
			a.monto_total AS monto, 
			a.iva AS iva, 
			(SELECT IFNULL(SUM(precio), 0) FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento AND alicuota > 0)  AS gravable, 
			0.00 AS descuento, 
			a.total AS total, 
			'FACT' AS tipofac, 
			'01' AS codesta, 
			'01' AS codusua, 
			'N/A' AS codvend, 
			a.fecha AS fechav, 
			a.nro_control AS nroctrol, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM cliente WHERE id = a.cliente) AS rif, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			IFNULL(a.nota, '') AS observacion, 
			REPLACE(a.moneda, 'Bs. S', 'Bs.') AS codmoneda, 
			$tasa AS factorcambiario, 
			'' AS origen 
		FROM 
			salidas AS a 
		WHERE 
			a.tipo_documento = 'TDCFCV' AND 
			a.estatus IN ('PROCESADO','ANULADO') AND 
			IFNULL(a.nro_control, '') <> '' AND a.fecha >= '2025-01-01';"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['factnum'], $row['fecha'], $row['codcli'], _limpiarstr($row['descrip']), $row['monto'], $row['iva'], $row['gravable'], $row['descuento'], $row['total'], $row['tipofac'], $row['codesta'], $row['codusua'], $row['codvend'], $row['fechav'], $row['nroctrol'], $row['rif'], $row['codisb'], $row['observacion'], $row['codmoneda'], $row['factorcambiario'], $row['origen']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 


// 5.- FACTREN (factren.txt)
$file = "factren.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			@fila:=b.nro_documento AS factnum, 
			a.articulo AS codprod, 
			IF(@fila=b.nro_documento, @rownum:=@rownum+1, @rownum:=1) AS renglon, 
			(SELECT CONCAT(IFNULL(principio_activo, ''), IFNULL(presentacion, '')) FROM articulo WHERE id = a.articulo) AS desprod, 
			(SELECT IFNULL(codigo_de_barra, '') FROM articulo WHERE id = a.articulo) AS referencia,	
			ABS(a.cantidad_movimiento) AS cantidad, 
			ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio_unidad, 0))+IFNULL(a.precio_unidad, 0)+ 0.0000000001, 2) AS precio,
			ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio, 0))+IFNULL(a.precio, 0)+ 0.0000000001, 2) AS subtotal, 
			ROUND((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio_unidad, 0)+ 0.0000000001, 2) AS impuesto, 
			(a.precio_unidad_sin_desc - ROUND(((IFNULL(a.alicuota, 0)/100)*IFNULL(a.precio_unidad, 0))+IFNULL(a.precio_unidad, 0)+ 0.0000000001, 2)) AS descto, 
			IFNULL(a.lote, '') AS nrolote, 
			IFNULL(a.fecha_vencimiento, '') AS fechal, 
			b.fecha AS fecfactura, 
			(SELECT REPLACE(REPLACE(UPPER(IFNULL(ci_rif, '')), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			1 AS codprov, 
			(SELECT IFNULL(nombre, '') FROM fabricante WHERE id = a.fabricante) AS marca 
		FROM 
			(SELECT @rownum:=1) r, 
			(SELECT @fila:='') v, 
			entradas_salidas AS a 
			JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
		WHERE 
			a.tipo_documento = 'TDCFCV' AND 
			b.estatus IN ('PROCESADO','ANULADO') AND 
			IFNULL(b.nro_control, '') <> '' AND 
			a.precio IS NOT NULL AND b.fecha >= '2025-01-01' ORDER BY a.id_documento, renglon;"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['factnum'], $row['codprod'], $row['renglon'], _limpiarstr($row['desprod']), _limpiarstr($row['referencia']), $row['cantidad'], $row['precio'], $row['subtotal'], $row['impuesto'], $row['descto'], $row['nrolote'], $row['fechal'], $row['fecfactura'], $row['codisb'], $row['codprov'], $row['marca']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 


// 9.- VENDEDOR (vendedor.txt)
$file = "vendedor.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			b.username AS codigo, 
			a.nombre AS nombre, 
			'' AS tipo, 
			1 AS supervisor, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb, 
			'N/A' AS codAC3 
		FROM 
			asesor AS a 
			JOIN usuario AS b ON b.asesor = a.id 
		WHERE 1;"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['codigo'], _limpiarstr($row['nombre']), $row['tipo'], $row['supervisor'], $row['codisb'], $row['codAC3']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 


// 10.- CATEGORIA (categoria.txt)
$file = "categoria.txt";
$filename = $path . $file;
$f = fopen($filename, 'w'); 

$sql = "SELECT 
			a.campo_codigo AS codigo, 
			a.campo_descripcion AS nombre, 
			(SELECT REPLACE(REPLACE(UPPER(ci_rif), '-', ''), 'J', '') AS rif FROM compania) AS codisb   
		FROM 
			tabla AS a 
		WHERE tabla = 'LISTA_PEDIDO';"; 

$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) {
	$lineData = array($row['codigo'], _limpiarstr($row['nombre']), $row['codisb']); 
    //fputcsv($f, $lineData, $delimiter);
    _fputcsv($f, $lineData);
}
fseek($f, 0); 

//echo 'Proceso finalizado!';
?>