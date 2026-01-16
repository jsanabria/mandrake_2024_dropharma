<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidoDeVentaDetalleCopia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = "TDCPDV";
	
$sql = "SELECT
			MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo
		FROM salidas WHERE tipo_documento = '$tipo_documento';";
$consecutivo = intval(ExecuteScalar($sql)) + 1;

$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
$username = CurrentUserName();
$estatus = "NUEVO";

$sql = "INSERT INTO salidas
			(id, tipo_documento, username, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor)
		SELECT
			NULL, tipo_documento, '$username', '$nro_documento', 
			'" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0,
			'', '$estatus', asesor 
		FROM
			salidas 
		WHERE
			id = '$id';";
Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, descuento, precio_unidad_sin_desc) 
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, descuento, precio_unidad_sin_desc 
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $newid;";
Execute($sql);
/**************/


/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
ActualizarExitencia();

header("Location: pedido_de_venta_detalle.php?id=$newid");
?>

<?= GetDebugMessage() ?>
