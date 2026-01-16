<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntregaGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$id = $_POST["id"]; 
$cantidad = $_POST["cantidad"];
$nota = $_POST["nota"];
$username = $_POST["username"];

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$tasa = floatval(ExecuteScalar($sql));

$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$moneda = ExecuteScalar($sql);

/**** Consulto el tipo de documento y cliente ****/
$sql = "SELECT tipo_documento, cliente, estatus FROM salidas WHERE id = '$id';";
$row = ExecuteRow($sql);
$tipo = $row["tipo_documento"];
$cliente = $row["cliente"];
$estatus = $row["estatus"];

if($estatus == "PROCESADO") {
	header("Location: YaFueProcesado");
	die();
}

/**** Consulto si el cliente compra a consignación y si se aplica al documento, esto puede cambiarse luego al editar la nota de entrega ****/
$sql = "SELECT consignacion FROM cliente WHERE id = $cliente";
$row = ExecuteRow($sql);
$consignacion = $row["consignacion"];

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$row = ExecuteRow($sql);
$almacen = $row["almacen"];

/**** Se obtiene el consecutivo del tipo de documento ****/
$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = 'TDCNET';";
$row = ExecuteRow($sql);
$consecutivo = intval($row["consecutivo"]) + 1; 
$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

$tipo_documento = 'TDCNET';

/**** Inserto el encabezado de la nota de entrega ****/
$sql = "INSERT INTO salidas
			(id, tipo_documento, username, fecha,
			cliente, nro_documento,
			nota, estatus,
			id_documento_padre, asesor, consignacion, fecha_bultos, fecha_despacho, asesor_asignado,
			dias_credito, tasa_dia, moneda, descuento, descuento2)
		SELECT 
			NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "',
			cliente, '$nro_documento' AS factura,
			'$nota' AS nota, 
			'NUEVO' AS estatus, id, asesor, '$consignacion', NULL, NULL, asesor_asignado,
			dias_credito, $tasa, '$moneda', descuento, descuento2 
		FROM salidas 
		WHERE id = '$id';"; 
Execute($sql);

// Obtengo el id de la nueva factura
$row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
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
	if(isset($_POST["id_$i"])) {
		$dt = $_POST["id_$i"];
		$lot = $_POST["lote_$i"];
		$cnt = $_POST["cantidad_$i"];
		$un = $_POST["unidad_$i"];
		$articulo = $_POST["articulo_$i"];

		$myArr = explode("|", $lot);
		$lote = $myArr[0];
		$fecha_vencimiento = $myArr[1]; 
		$almacen = $myArr[3];


		$cantidad_um = 1;

		$asignado = $cnt * $cantidad_um;

		$sql = "SELECT a.fabricante, IFNULL(b.alicuota, 0) as alicuota 
				FROM 
					articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota 
				WHERE a.id = $articulo AND b.activo = 'S';"; 
		if($articulo == "") die();
		$row = ExecuteRow($sql);
		$fabricante = $row["fabricante"];
		$alicuota = $row["alicuota"];
		// die("$lote - $fecha_vencimiento - $almacen - $articulo - $fabricante - $alicuota");

		/*** Consulto la cantidad solicitada por el cliente y precio dado al mismo por el artículo ***/
		$sql = "SELECT 
					a.cantidad_movimiento, a.precio_unidad, a.alicuota,
					a.precio_unidad, IFNULL(a.descuento, 0.00) AS descuento, a.precio_unidad_sin_desc 
				FROM entradas_salidas AS a 
				WHERE a.id = '$dt';";
		$row = ExecuteRow($sql);
		$solicitado = $row["cantidad_movimiento"];
		$precio_unidad = $row["precio_unidad"];
		$descuento = floatval($row["descuento"]);
		$precio_unidad_sin_desc = floatval($row["precio_unidad_sin_desc"]);
		

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
					'$un', '$cantidad_um', '$asignado', '$precio_unidad', '$precio', '$alicuota', '0',
					$descuento, $precio_unidad_sin_desc);";  
		Execute($sql);

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
			Execute($sql);
		}
	}
}


$sql = "SELECT
			SUM(precio) AS precio, 
			SUM((precio * (alicuota/100))) AS iva, 
			SUM(precio) + SUM((precio * (alicuota/100))) AS total 
		FROM entradas_salidas
		WHERE tipo_documento = '$tipo_documento' AND 
			id_documento = '$new_id'"; 
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
			tipo_documento = '$tipo_documento' 
			AND id_documento = '$new_id';";
$row = ExecuteRow($sql);
if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
	$sql = "SELECT 
				DISTINCT alicuota 
			FROM 
				entradas_salidas
			WHERE 
				tipo_documento = '$tipo_documento' 
				AND id_documento = '$new_id';";
	$row = ExecuteRow($sql);
	$alicuota = floatval($row["alicuota"]);
}

$monto_usd = $total/(substr(strtoupper($moneda), 0, 2) == "BS" ? 1 : $tasa);
$sql = "UPDATE salidas 
		SET
			monto_total = $precio,
			alicuota_iva = $alicuota,
			iva = $iva,
			total = $total, monto_usd = $monto_usd 
		WHERE id = '$new_id'"; 
Execute($sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
            salidas AS a 
            JOIN (SELECT 
                        id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                    FROM 
                        entradas_salidas 
                    WHERE tipo_documento = 'TDCNET' AND id_documento = $new_id 
                    GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
        SET 
            a.unidades = b.cantidad 
        WHERE a.id = $new_id;";
Execute($sql);
/**************/

$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = '$id'";
ExecuteRow($sql);

$sql = "SELECT articulo FROM entradas_salidas WHERE id_documento = $id AND tipo_documento = 'TDCPDV';";
$rows = ExecuteRows($sql);
foreach ($rows as $key => $value) {
    $articulo = $value["articulo"];
    Execute("CALL sp_onhand_item($articulo);");
}

header("Location: CrearNotaEntradaUpdate?id=$new_id");
die();
?>

<?= GetDebugMessage() ?>
