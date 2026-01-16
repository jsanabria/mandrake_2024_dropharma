<?php 
include "connect.php"; 
date_default_timezone_set('America/La_Paz');

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];
$tasa = str_replace(",", ".", $_REQUEST["tasa"]);
$monto = str_replace(",", ".", $_REQUEST["monto"]);
$tipo_documento = "TDCFCV";

/*** Busco el artículo: DIFERENCIA INDEXACION ***/
$sql = "SELECT id, fabricante from articulo WHERE principio_activo = 'DIFERENCIA INDEXACION';";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
$articulo = $row["id"];
$fabricante = $row["fabricante"];


/*** Busco el almacen ***/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$almacen = $row["almacen"];

/*** Busco la alicuota del IVA asociada al artículo ***/
$sql = "SELECT alicuota FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$codigo_alicuota = $row["alicuota"];

$sql = "SELECT alicuota FROM alicuota
    WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$alicuota = floatval($row["alicuota"]);


$sql = "INSERT INTO salidas
			(id, tipo_documento, username, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor, moneda,
			tasa_dia, monto_usd, fecha_bultos, fecha_despacho, asesor_asignado, id_documento_padre_nd, dias_credito) 
		SELECT
			NULL, '$tipo_documento', '$username', 'NULL', 
			'" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0,
			nota, 'NUEVO', asesor, moneda,
			$tasa AS tasa_dia, $monto AS monto_usd, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', asesor_asignado, $id, 0   
		FROM
			salidas 
		WHERE
			id = '$id';"; 
mysqli_query($link, $sql); 

$sql = "SELECT LAST_INSERT_ID() AS lastid;";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
$newid = $row["lastid"];

$codigo = "011";
$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigo';";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
$numero = intval($row["valor1"]) + 1;
$prefijo = trim($row["valor2"]);
$padeo = intval($row["valor3"]);
$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
$sql = "UPDATE parametro SET valor1='$numero' 
	WHERE codigo = '$codigo';";
mysqli_query($link, $sql); 

//// Nro Ctrol ////
// Tomo el siguiente número de control de factura
// Pregunto si el consecutivo del Nro de Control de factura es el mismo
// Para Notas de Débito y Nota de Crédito
$sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
if($row["valor1"] == "S") {
	$codigoCRTL = "030";
}
else {
	switch($codigo) {
	case "003":
		$codigoCRTL = "030";
		break;
	case "010":
		$codigoCRTL = "031";
		break;
	case "011":
		$codigoCRTL = "032";
		break;
	}
}

$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigoCRTL';";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
$numero = intval($row["valor1"]) + 1;
$prefijo = trim($row["valor2"]);
$padeo = intval($row["valor3"]);
$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
$sql = "UPDATE parametro SET valor1='$numero' 
		WHERE codigo = '$codigoCRTL';";
mysqli_query($link, $sql); 

$sql = "SELECT nro_documento, monto_total FROM salidas WHERE id = $id";
$rs = mysqli_query($link, $sql); 
if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}
$row = mysqli_fetch_array($rs);
$NF = $row["nro_documento"];
$monto_total = $row["monto_total"];

$sql = "UPDATE salidas SET nro_documento='$factura', nota='Documento Asociado: $NF', documento = 'ND', doc_afectado = '$NF', nro_control = '$facturaCTRL'  
		WHERE id = '$newid';";
mysqli_query($link, $sql); 

$precio = floatval($monto*$tasa) - $monto_total;
$precio_ful = $precio;
$total = $precio;

$sql = "INSERT INTO entradas_salidas
		    (id, tipo_documento, id_documento, 
		    fabricante, articulo, almacen, 
		    cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
		    precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc)
		  VALUES 
		    (NULL, '$tipo_documento', $newid, 
		    $fabricante, $articulo, '$almacen', 
		    1, 'UDM001', 1, -1, 
		    $precio, $total, $alicuota, 0, $precio_ful);"; 
mysqli_query($link, $sql);

// Se actualiza el encabezado del padido de venta //
$sql = "SELECT
	      SUM(precio) AS precio, 
	      SUM((precio * (alicuota/100))) AS iva, 
	      SUM(precio) + SUM((precio * (alicuota/100))) AS total 
	    FROM 
	      entradas_salidas
	    WHERE tipo_documento = '$tipo_documento' AND 
	      id_documento = '$newid';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$precio = floatval($row["precio"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);

$sql = "UPDATE salidas 
	    SET
	      monto_total = $precio,
	      alicuota_iva = $alicuota, 
	      iva = $iva,
	      total = $total
	    WHERE tipo_documento = '$tipo_documento' AND 
	      id = '$newid';";
mysqli_query($link, $sql);

include "desconnect.php"; 

$url = "../SalidasEdit?showdetail=entradas_salidas&id=$newid&tipo=$tipo_documento";
header("Location: $url");
?>
