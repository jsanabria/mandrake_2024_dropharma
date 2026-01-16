<?php

namespace PHPMaker2024\mandrake;

// Page object
$ClienteConsignacionProcesar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$cliente = $_REQUEST["xCliente"]; 

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


$tipo = "TDCNET";

$sql = "SELECT tarifa FROM cliente WHERE id = $cliente;"; 
$tarifa = ExecuteScalar($sql);

// Tomo el número de días de crédito por defecto
$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
$row = ExecuteRow($sql);
$nota = ""; // "Crédito a " . $row["valor1"] . " " . $row["valor2"];
// $dias_credito = intval($row["valor1"]);

// Proceso para saber si genero más de una factura para la nota de entrega //
// Consulto la cantidad de lineas por factura
$sql = "SELECT valor1 AS lineas FROM parametro WHERE codigo = '008';";
$LineasFactura = intval(ExecuteScalar($sql));
$LineasFactura = ($LineasFactura == 0 ? 35 : $LineasFactura);

$cant = 0;
foreach ($_POST as $key => $value) {
	if(substr($key, 0, 9) == "cantidad_") {
	 	if(intval($value) > 0) 
	 		$cant++;
	}
} 

$cantidad = $cant/$LineasFactura;
$deci = abs($cantidad - intval($cantidad));
$cantidad = intval($cantidad);

if($deci > 0) $cantidad++;

for($xy = 0; $xy < $cantidad; $xy++) {
	//////echo "Pagina $xy de ($cantidad-1) <br>";
	// Tomo el siguiente número de factura
	$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '003';";
	$row = ExecuteRow($sql);
	$numero = intval($row["valor1"]) + 1;
	$prefijo = trim($row["valor2"]);
	$padeo = intval($row["valor3"]);
	$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
	$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '003';";
	Execute($sql);

	// Tomo el siguiente número de control de factura
	$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '030';";
	$row = ExecuteRow($sql);
	$numero = intval($row["valor1"]) + 1;
	$prefijo = trim($row["valor2"]);
	$padeo = intval($row["valor3"]);
	$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
	$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '030';";
	Execute($sql);

	$sql = "SELECT 
				 b.username 
			FROM 
				asesor_cliente AS a 
				JOIN usuario AS b ON b.asesor = a.asesor  
			WHERE a.cliente = $cliente;";
	if(ExecuteRow($sql)) $asesor = $row["username"];
	else $asesor = "";
	
	// Inserto el encabezado de la factura
	// VIENE DE NOTA DE ENTREGA No. $id
    // '" . date("Y-m-d H:i:s") . "'
	$sql = "INSERT INTO salidas
				(id, tipo_documento, username, fecha,
				cliente, nro_documento,
				nota, estatus, moneda, tasa_dia, 
				id_documento_padre, asesor, documento, dias_credito, consignacion, nro_control, asesor_asignado)
			VALUES 
				(NULL, 'TDCFCV', '" . CurrentUserName() . "', NOW(),
				$cliente, '$factura',
				'$nota', 'NUEVO', '$moneda', $tasa_usd, 
				0, '$asesor', 'FC', dias_credito, 'S', '$facturaCTRL', '$asesor');"; 
	ExecuteScalar($sql);

	// Obtengo el id de la nueva factura
	$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");


	$idd = 0;
	$cantd = 0;
	$ctrl = 0;
	$i = 0;
	$contador = 0;
	foreach ($_POST as $key => $value) {
		if(substr($key, 0, 9) == "cantidad_") {
		 	if(intval($value) > 0) { 
		 		if($i >= ($xy*$LineasFactura)) {
			 		$idd = substr($key, 9, strlen($key));
			 		$cantd = (-1) * intval($value);

					$sql = "SELECT 
								(SELECT ultimo_costo FROM articulo WHERE id = a.articulo) AS costo_unidad,
								((SELECT ultimo_costo FROM articulo WHERE id = a.articulo) * ABS($cantd)) AS costo,
								((SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = a.articulo LIMIT 0, 1) - ((SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = a.articulo LIMIT 0, 1) * (IFNULL(a.descuento, 0)/100))) * $tasa AS precio_unidad, 
								(((SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = a.articulo LIMIT 0, 1) - ((SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = a.articulo LIMIT 0, 1) * (IFNULL(a.descuento, 0)/100))) * $tasa) * ABS($cantd) AS precio, 
								(SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = a.articulo LIMIT 0, 1) * $tasa AS precio_unidad_sin_desc
								FROM entradas_salidas AS a 
							WHERE a.id = $idd;"; 
				    $rows100 = ExecuteRow($sql);
				    $xcosto_unidad = floatval($rows100["costo_unidad"]);
				    $xcosto = floatval($rows100["costo"]);
				    $xprecio_unidad = floatval($rows100["precio_unidad"]);
				    $xprecio = floatval($rows100["precio"]);
				    $xprecio_unidad_sin_desc = floatval($rows100["precio_unidad_sin_desc"]);
					$sql = "INSERT INTO entradas_salidas
								(id, tipo_documento, id_documento, 
								fabricante, articulo, almacen, 
								cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
								cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
								costo_unidad, costo, cantidad_movimiento_consignacion, id_consignacion,
								id_compra, descuento, precio_unidad_sin_desc)
							SELECT 
								NULL, 'TDCFCV', '$factura_id', 
								a.fabricante, a.articulo, a.almacen, 
								ABS($cantd), a.articulo_unidad_medida, a.cantidad_unidad_medida, 
								$cantd, a.lote, a.fecha_vencimiento,
								$xprecio_unidad AS precio_unidad,
								$xprecio AS precio, a.alicuota,
								$xcosto_unidad AS costo_unidad,
								$xcosto AS costo, 
								0, a.id, a.id_compra, IFNULL(a.descuento, 0) AS descuento, $xprecio_unidad_sin_desc AS precio_unidad_sin_desc  
							FROM entradas_salidas AS a 
							WHERE a.id = $idd;"; 

					Execute($sql);

					$sql = "UPDATE entradas_salidas 
							SET 
								cantidad_movimiento_consignacion = IFNULL(cantidad_movimiento_consignacion, 0) + ABS($cantd) 
							WHERE id = $idd;";
					Execute($sql);

			 		if($ctrl >= ($LineasFactura-1)) break;
					$ctrl++;
		 		}
		 		$i++;
		 	}
		}
	} 

	$sql = "SELECT 
				DISTINCT a.id 
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento= a.tipo_documento 
				JOIN articulo AS c ON c.id = b.articulo 
				JOIN fabricante AS d ON d.id = b.fabricante 
			WHERE 
				a.cliente = $cliente AND a.tipo_documento = 'TDCNET' AND 
				a.estatus = 'NUEVO' AND a.consignacion = 'S' 
			ORDER BY a.id;";
	$rows = ExecuteRows($sql);

	foreach ($rows as $key => $value) {
		/// Marco como procesada la Nota de Entrega si no hay items con cantidades pendientes ///
		$sql = "SELECT 
					COUNT(id) AS cantidad 
				FROM 
					entradas_salidas 
				WHERE 
					id_documento = " . $value["id"] . " AND tipo_documento = '$tipo' 
					AND (ABS(cantidad_movimiento) - IFNULL(cantidad_movimiento_consignacion, 0)) > 0;";
		$xCant = intval(ExecuteScalar($sql)); 
		if($xCant == 0) {
			$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = " . $value["id"] . "";
			Execute($sql);

			$sql = "UPDATE salidas 
					SET
						direccion = IF(RTRIM(IFNULL(direccion, '')) = '', '" . $value["id"] . "', CONCAT(direccion, ',', '" . $value["id"] . "'))
					WHERE id = '$factura_id'"; 
			Execute($sql); 
		}
	}


	//// Se coloca el precio total en el encabezado ////
	$sql = "SELECT
				SUM(precio) AS precio, 
				SUM((precio * (IFNULL(alicuota,0)/100))) AS iva, 
				SUM(precio) + SUM((precio * (IFNULL(alicuota,0)/100))) AS total 
			FROM entradas_salidas
			WHERE tipo_documento = 'TDCFCV' AND 
				id_documento = '$factura_id'"; 
	$row = ExecuteRow($sql); 
	$precio = floatval($row["precio"]);
	$iva = floatval($row["iva"]);
	$total = floatval($row["total"]);

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


	$sql = "UPDATE salidas 
			SET
				monto_total = $precio,
				alicuota_iva = $alicuota,
				iva = $iva,
				total = $total
			WHERE id = '$factura_id'"; 
	Execute($sql); 

	/* Se actualizan las cantidades de unidades en el encabezado de la salida */
	// 28-01-2021
	$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $factura_id;";
	Execute($sql);
	/**************/
}

///////////////////////////////////////////
header("Location: ViewOutTdcfcvList");
die();
?>

<?= GetDebugMessage() ?>
