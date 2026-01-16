<?php
/**** Tasa del día ****/
$sql = "SELECT descuento, tasa_dia FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = $id;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$descuento = floatval($row["descuento"]);
$tasa = floatval($row["tasa_dia"]);

/**** Totalizo Movimiento ****/
$sql = "SELECT
			SUM(precio) AS precio, 
			SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
			SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
		FROM entradas_salidas
		WHERE tipo_documento = '$tipo_documento' AND 
			id_documento = '$id'"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$exento = floatval($row["exento"]);
$gravado = floatval($row["gravado"]);
$monto_sin_descuento = floatval($row["precio"]);
$precio = floatval($row["exento"]) + floatval($row["gravado"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);


/*** Indico que alicuota iva se coloca en el encabezado del documento ***/
$sql = "SELECT 
			COUNT(DISTINCT alicuota ) AS cantidad  
		FROM 
			entradas_salidas
		WHERE 
			tipo_documento = '$tipo_documento' 
			AND id_documento = '$id';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
	$sql = "SELECT 
				DISTINCT alicuota 
			FROM 
				entradas_salidas
			WHERE 
				tipo_documento = '$tipo_documento' 
				AND id_documento = '$id';"; 
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) $alicuota = floatval($row["alicuota"]);
	else $alicuota = 0;
}


$sql = "UPDATE salidas 
	SET
		monto_total = $precio, 
		alicuota_iva = $alicuota, 
		iva = $iva,
		total = $total,
		tasa_dia = $tasa, 
		monto_usd = total/$tasa,
		monto_sin_descuento = $monto_sin_descuento 
	WHERE tipo_documento = '$tipo_documento' AND 
		id = $id";
mysqli_query($link, $sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $id GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $id;";

mysqli_query($link, $sql);
/**************/
?>