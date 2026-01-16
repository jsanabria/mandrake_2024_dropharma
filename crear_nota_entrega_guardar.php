<?php

include "connect.php";
include "rutinas.php";

$id = $_POST["id"];
$cantidad = $_POST["cantidad"];
$nota = $_POST["nota"];
$username = $_POST["username"];

/**** Consulto el tipo de documento y cliente ****/
$sql = "SELECT tipo_documento, cliente, estatus FROM salidas WHERE id = '$id';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tipo = $row["tipo_documento"];
$cliente = $row["cliente"];
$estatus = $row["estatus"];

if($estatus == "PROCESADO") {
	header("Location: ya_fue_procesado.php");
	die();
}

/**** Consulto si el cliente compra a consignación y si se aplica al documento, esto puede cambiarse luego al editar la nota de entrega ****/
$sql = "SELECT consignacion FROM cliente WHERE id = $cliente";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$consignacion = $row["consignacion"];

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$almacen = $row["almacen"];

/**** Se obtiene el consecutivo del tipo de documento ****/
$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = 'TDCNET';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$consecutivo = intval($row["consecutivo"]) + 1; 
$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

$tipo_documento = 'TDCNET';

/**** Inserto el encabezado de la nota de entrega ****/
$sql = "INSERT INTO salidas
			(id, tipo_documento, username, fecha,
			cliente, nro_documento,
			nota, estatus,
			id_documento_padre, asesor, consignacion)
		SELECT 
			NULL, '$tipo_documento', '$username', NOW(),
			cliente, '$nro_documento' AS factura,
			'$nota' AS nota, 
			'NUEVO' AS estatus, id, asesor, '$consignacion' 
		FROM salidas 
		WHERE id = '$id';"; 
mysqli_query($link, $sql);

// Obtengo el id de la nueva factura
$rs = mysqli_query($link, "SELECT LAST_INSERT_ID() AS id;");
$row = mysqli_fetch_array($rs);
$new_id = $row["id"];

$dt = "";
$lot = "";
$cnt = "";
$un = "";

$fabricante = '';
$articulo = "";
$lote = "";
$fecha_vencimiento = "";
$alicuota = "";
for($i = 0; $i < $cantidad; $i++) {	
	$dt = $_POST["id_$i"];
	$lot = $_POST["lote_$i"];
	$cnt = $_POST["cantidad_$i"];
	$un = $_POST["unidad_$i"];

	$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$un';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$cantidad_um = floatval($row["cantidad"]);

	$asignado = $cnt * $cantidad_um;

	/*** Consulto la información del lote a descontar las cantidades ***/
	$sql = "SELECT 
				a.fabricante, a.articulo, a.lote, a.fecha_vencimiento, 
				a.alicuota, a.almacen 
			FROM entradas_salidas AS a 
			WHERE a.id = '$lot';"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$fabricante = $row["fabricante"];
	$articulo = $row["articulo"];
	$lote = $row["lote"];
	$fecha_vencimiento = $row["fecha_vencimiento"];
	$alicuota = $row["alicuota"];
	$almacen = $row["almacen"];

	$sql = "SELECT IFNULL(b.alicuota, 0) as alicuota 
			FROM 
				articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota 
			WHERE a.id = $articulo AND b.activo = 'S';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$alicuota = $row["alicuota"];

	/*** Consulto la cantidad solicitada por el cliente y precio dado al mismo por el artículo ***/
	$sql = "SELECT 
				a.cantidad_movimiento, a.precio_unidad, a.alicuota,
				a.precio_unidad, a.descuento, a.precio_unidad_sin_desc 
			FROM entradas_salidas AS a 
			WHERE a.id = '$dt';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$solicitado = $row["cantidad_movimiento"];
	$precio_unidad = $row["precio_unidad"];
	$descuento = $row["descuento"];
	$precio_unidad_sin_desc = $row["precio_unidad_sin_desc"];
	

	$precio = $asignado * $precio_unidad;


	$asignado *= -1;

	$sql = "INSERT INTO entradas_salidas 
				(id, tipo_documento, id_documento, 
				fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, 
				articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, id_compra,
				descuento, precio_unidad_sin_desc) 
			VALUES 
				(NULL, '$tipo_documento', '$new_id', 
				'$fabricante', '$articulo', '$lote', '$fecha_vencimiento', '$almacen', '$cnt', 
				'$un', '$cantidad_um', '$asignado', '$precio_unidad', '$precio', '$alicuota', '$lot',
				$descuento, $precio_unidad_sin_desc);";  
	mysqli_query($link, $sql);

	if((abs($solicitado) - abs($asignado)) > 0) {
		$pendiente = abs($solicitado) - abs($asignado);
		$pendiente *= -1;
		
		$precio = $pendiente * $precio_unidad;
		$sql = "INSERT INTO entradas_salidas 
					(id, tipo_documento, id_documento, 
					fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, 
					articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, id_compra,
					descuento, precio_unidad_sin_desc) 
				VALUES 
					(NULL, '$tipo_documento', '$new_id', 
					'$fabricante', '$articulo', '$lote', '$fecha_vencimiento', '$almacen', '" . abs($pendiente/$cantidad_um) ."', 
					'$un', '$cantidad_um', NULL, '$precio_unidad', '" . (abs($pendiente/$cantidad_um) * $precio_unidad) . "', NULL, NULL,
					$descuento, $precio_unidad_sin_desc);";
		mysqli_query($link, $sql);
	}

	ActInv($articulo); 
}


$sql = "SELECT
			SUM(precio) AS precio, 
			SUM((precio * (alicuota/100))) AS iva, 
			SUM(precio) + SUM((precio * (alicuota/100))) AS total 
		FROM entradas_salidas
		WHERE tipo_documento = '$tipo_documento' AND 
			id_documento = '$new_id'"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$precio = floatval($row["precio"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);

/*** Indico que alicuota iva se coloca en el encabezado del documento ***/
$sql = "SELECT 
			COUNT(DISTINCT alicuota ) AS cantidad  
		FROM 
			entradas_salidas
		WHERE 
			tipo_documento = '$tipo_documento' 
			AND id_documento = '$new_id';";
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
				AND id_documento = '$new_id';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$alicuota = floatval($row["alicuota"]);
}


$sql = "UPDATE salidas 
		SET
			monto_total = $precio,
			alicuota_iva = $alicuota,
			iva = $iva,
			total = $total
		WHERE id = '$new_id'"; 
mysqli_query($link, $sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $new_id;";
mysqli_query($link, $sql);
/**************/

$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = '$id'";
mysqli_query($link, $sql);

header("Location: crear_nota_entrada_update.php?id=$new_id");
?>
