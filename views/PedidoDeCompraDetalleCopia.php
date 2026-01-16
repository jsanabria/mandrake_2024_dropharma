<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidoDeCompraDetalleCopia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = "TDCPDC";
	
$sql = "SELECT
			MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo
		FROM entradas WHERE tipo_documento = '$tipo_documento';";
$consecutivo = intval(ExecuteScalar($sql)) + 1;

$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
$username = CurrentUserName();
$estatus = "NUEVO";

$sql = "INSERT INTO entradas
			(id, tipo_documento, username,
			fecha, proveedor, nro_documento,
			almacen, monto_total, alicuota_iva,
			iva, total, nota,
			estatus, id_documento_padre, moneda)
		SELECT
			NULL, tipo_documento, '$username',
			'" . date("Y-m-d H:i:s") . "', proveedor, '$nro_documento',
			almacen, monto_total, alicuota_iva,
			iva, total, NULL,
			'$estatus', id_documento_padre, moneda
		FROM entradas
		WHERE
			id = '$id';";

Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota)
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota 
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

header("Location: ViewInList?showmaster=view_in_tdcpdc&fk_id=$newid&fk_tipo_documento=$tipo_documento");
?>

<?= GetDebugMessage() ?>
