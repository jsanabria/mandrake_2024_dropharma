<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearFacturaVenta = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$id = $_REQUEST["id"];

/// Busco la moneda por defecto del sistema ///
$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$moneda = ExecuteScalar($sql);

/// Busco la tasa del día ///
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$tasa = floatval(ExecuteScalar($sql));
$tasa_usd = $tasa;

/// Pregunto si el sistema factura en Bs ///
$sql = "SELECT valor1 AS fact_bs FROM parametro WHERE codigo = '053';";
$fact_bs = ExecuteScalar($sql);

if($fact_bs == "S") $moneda = "Bs.";
else $tasa = 1;

$sql = "SELECT consignacion, descuento, descuento2 FROM salidas WHERE id = '$id';";
$row = ExecuteRow($sql);
$consignacion = $row["consignacion"];
$descuento = floatval($row["descuento"]);
$descuento2 = floatval($row["descuento2"]);

if($consignacion == "S") {
	header("Location: FacturaConsignacion?id=$id");
	die();
}


$sql = "SELECT tipo_documento FROM salidas WHERE id = '$id';";
$tipo = ExecuteScalar($sql);

// Tomo el número de días de crédito por defecto
$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
$row = ExecuteRow($sql);
$nota = "";  // "Crédito a " . $row["valor1"] . " " . $row["valor2"];
// $dias_credito = intval($row["valor1"]);

// Proceso para saber si genero más de una factura para la nota de entrega //
// Consulto la cantidad de lineas por factura
$sql = "SELECT valor1 AS lineas FROM parametro WHERE codigo = '008';";
$LineasFactura = intval(ExecuteScalar($sql));
$LineasFactura = ($LineasFactura == 0 ? 35 : $LineasFactura);

$sql = "SELECT (COUNT(*)/$LineasFactura) AS cantidad FROM entradas_salidas WHERE id_documento = '$id' AND tipo_documento = '$tipo';";
$cantidad = ExecuteScalar($sql);
$deci = abs($cantidad - intval($cantidad));
$cantidad = intval($cantidad);
if($deci > 0) $cantidad++;

$limite = 0;
for($xy = 0; $xy < $cantidad; $xy++) {

	/**** Almacen por defecto ****/
	$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
	$almacen = ExecuteScalar($sql);


	/**********************/
	// Inserto el encabezado de la factura
	// VIENE DE NOTA DE ENTREGA No. $id
	$sql = "INSERT INTO salidas
				(id, tipo_documento, username, fecha,
				cliente, nro_documento,
				nota, estatus,
				id_documento_padre, asesor, documento, nro_control, asesor_asignado, 
				dias_credito, descuento, descuento2, moneda, tasa_dia) 
			SELECT 
				NULL, 'TDCFCV', '" . CurrentUserName() . "', '" . date("Y-m-d H:i:s") . "',
				cliente, '$factura' AS factura,
				'$nota' AS nota, 
				'NUEVO' AS estatus, id, asesor, 'FC', '$facturaCTRL', asesor_asignado,
				dias_credito, descuento, descuento2, '$moneda', $tasa_usd 
			FROM salidas 
			WHERE id = '$id';";
	ExecuteScalar($sql);

	// Obtengo el id de la nueva factura
	$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");

	$sql = "SELECT 
				a.id, a.articulo  
            FROM entradas_salidas AS a 
            WHERE a.id_documento = '$id' AND a.tipo_documento = '$tipo' LIMIT $limite, $LineasFactura;";
    $rows100 = ExecuteRows($sql);
    foreach ($rows100 as $key => $value) {
    	# code...
    	$ultimo_costo = floatval(ExecuteScalar("SELECT ultimo_costo FROM articulo WHERE id = " . $value["articulo"]));
	    $sql = "INSERT INTO entradas_salidas
	                (id, tipo_documento, id_documento, 
	                fabricante, articulo, almacen, 
	                cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
	                cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
	                costo_unidad, costo, id_compra,
	                descuento, precio_unidad_sin_desc)
	            SELECT 
	                NULL, 'TDCFCV', '$factura_id', 
	                a.fabricante, a.articulo, '$almacen', 
	                ABS(a.cantidad_articulo), a.articulo_unidad_medida, a.cantidad_unidad_medida, 
	                a.cantidad_movimiento, a.lote, a.fecha_vencimiento, (a.precio_unidad_sin_desc-(a.precio_unidad_sin_desc*(a.descuento/100)))*$tasa AS precio_unidad, 
	                ABS(a.cantidad_articulo)*((a.precio_unidad_sin_desc-(a.precio_unidad_sin_desc*(a.descuento/100)))*$tasa) AS precio, a.alicuota,
	                $ultimo_costo AS costo_unidad,
	                ($ultimo_costo * ABS(cantidad_articulo)) AS costo, id_compra, 
	                a.descuento, a.precio_unidad_sin_desc*$tasa 
	            FROM entradas_salidas AS a 
	            WHERE a.id = '" . $value["id"] . "';"; 
		Execute($sql);
	}

	//$sql = "UPDATE salidas SET estatus = 'PROCESADO', id_documento_padre = '$factura_id' WHERE id = '$id'";
	$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = '$id'";
	Execute($sql);

	///////////////////////////////////////////
	/*** Indico que alicuota iva se coloca en el encabezado del documento ***/
	$sql = "SELECT 
				COUNT(DISTINCT alicuota ) AS cantidad  
			FROM 
				entradas_salidas
			WHERE 
				tipo_documento = 'TDCFCV' 
				AND id_documento = '$factura_id';";
	$row = ExecuteRow($sql); 
	if(intval($row["cantidad"]) > 1) $alicuota = 0;
	else {
		$sql = "SELECT 
					DISTINCT alicuota 
				FROM 
					entradas_salidas
				WHERE 
					tipo_documento = 'TDCFCV' 
					AND id_documento = '$factura_id';";
		$row = ExecuteRow($sql); 
		$alicuota = floatval($row["alicuota"]);
	}

	//// Se coloca el precio total en el encabezado ////
	$sql = "SELECT 
                SUM(precio) AS precio_unidad_sin_desc, 
                SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento, 
                SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado, 
                SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
                SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
			FROM entradas_salidas
			WHERE tipo_documento = 'TDCFCV' AND 
				id_documento = '$factura_id'"; 
	$row = ExecuteRow($sql); 
    $xExento = floatval($row["exento"]);
    $xExento = $xExento - ($xExento*($descuento2/100));
    $xGravado = floatval($row["gravado"]);
    $xGravado = $xGravado - ($xGravado*($descuento2/100));
    $precio = $xExento + $xGravado;
    $iva = $xGravado * (floatval($alicuota)/100);
    $total = $precio + $iva;

    $monto_sin_descuento = floatval($row["precio_unidad_sin_desc"]);

	$sql = "UPDATE salidas 
			SET
				monto_total = $precio,
				alicuota_iva = $alicuota,
				iva = $iva,
				total = $total, 
                monto_sin_descuento = $monto_sin_descuento 
			WHERE id = '$factura_id'"; 
	Execute($sql); 

	/* Se actualizan las cantidades de unidades en el encabezado de la salida */
	// 28-01-2021
    $sql = "UPDATE 
                salidas AS a 
                JOIN (SELECT 
                            id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                        FROM 
                            entradas_salidas 
                        WHERE tipo_documento = 'TDCFCV' AND id_documento = $factura_id 
                        GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
            SET 
                a.unidades = b.cantidad 
            WHERE a.id = $factura_id;";
	Execute($sql);
	/**************/

	$limite += $LineasFactura;
}
///////////////////////////////////////////

header("Location: ViewOutTdcfcvList");
// header("Location: TdcfcvAdd?tipo_documento=TDCFCV&pedido=$factura_id");
die();
?>
<?= GetDebugMessage() ?>
