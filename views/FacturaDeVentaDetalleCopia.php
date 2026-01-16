<?php

namespace PHPMaker2024\mandrake;

// Page object
$FacturaDeVentaDetalleCopia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo_documento"];

$documento = substr($_REQUEST["documento"], 0, 2);
$codigo = substr($_REQUEST["documento"], 2, 3);
$doc = $_REQUEST["doc"];

$username = CurrentUserName();
$estatus = "NUEVO";

if($documento == "FC") {
	$sql = "INSERT INTO salidas
			(id, tipo_documento, username, documento, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor, moneda,
			id_documento_padre, tasa_dia, monto_usd, dias_credito, fecha_bultos, fecha_despacho, asesor_asignado, descuento, descuento2)
		SELECT
			NULL, '$tipo_documento', '$username', '$documento', NULL, 
			'" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0,
			nota, '$estatus', asesor, moneda,
			id_documento_padre, tasa_dia, monto_usd, dias_credito, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', asesor_asignado, descuento, descuento2 
		FROM
			salidas 
		WHERE
			id = '$id';";
}
else {
	$sql = "INSERT INTO salidas
			(id, tipo_documento, username, documento, nro_documento, doc_afectado, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, asesor, moneda,
			tasa_dia, monto_usd, fecha_bultos, fecha_despacho, asesor_asignado, descuento, descuento2) 
		SELECT
			NULL, '$tipo_documento', '$username', '$documento', NULL, '$doc', 
			'" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0,
			nota, '$estatus', asesor, moneda,
			tasa_dia, monto_usd, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', asesor_asignado, descuento, descuento2 
		FROM
			salidas 
		WHERE
			id = '$id';";
}
Execute($sql);
/****************/

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);


$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, precio_unidad, precio, lote, fecha_vencimiento,
			descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra) 
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			" . ($documento == "NC" ? "(-1)*" : "") . "ABS(cantidad_movimiento), 
            alicuota, precio_unidad, precio, lote, fecha_vencimiento,
			descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra 
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

header("Location: TdcfcvAdd?tipo_documento=TDCFCV&pedido=$newid");
exit();
?>

<?= GetDebugMessage() ?>
