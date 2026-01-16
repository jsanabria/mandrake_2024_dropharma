<?php

namespace PHPMaker2024\mandrake;

// Page object
$FacturaDeCompraDetalleCopia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = "TDCFCC";
	
$documento = substr($_REQUEST["documento"], 0, 2);
$doc = $_REQUEST["doc"];

$username = CurrentUserName();
$estatus = "NUEVO";

if($documento == "FC") {
    $sql = "INSERT INTO entradas
                (id, tipo_documento, username,
                fecha, proveedor, documento, nro_documento,
                almacen, monto_total, alicuota_iva,
                iva, total, nota,
                estatus, id_documento_padre, moneda, tasa_dia, monto_usd, descuento)
            SELECT
                NULL, tipo_documento, '$username',
                '" . date("Y-m-d H:i:s") . "', proveedor, '$documento', '$nro_documento',
                almacen, monto_total, alicuota_iva,
                iva, total, NULL,
                '$estatus', id_documento_padre, moneda, tasa_dia, monto_usd, descuento 
            FROM entradas
            WHERE
                id = '$id';";
}
else {
    $sql = "INSERT INTO entradas
                (id, tipo_documento, username,
                fecha, proveedor, documento, nro_documento, doc_afectado, 
                almacen, monto_total, alicuota_iva,
                iva, total, nota,
                estatus, id_documento_padre, moneda)
            SELECT
                NULL, tipo_documento, '$username',
                '" . date("Y-m-d H:i:s") . "', proveedor, '$documento', '$nro_documento', '$doc', 
                almacen, monto_total, alicuota_iva,
                iva, total, NULL,
                '$estatus', id_documento_padre, moneda
            FROM entradas
            WHERE
                id = '$id';"; 
}
Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc)
		SELECT
			NULL, tipo_documento, $newid, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			" . ($documento == "NC" ? "(-1)*" : "") . "ABS(cantidad_movimiento), 
            alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc  
		FROM
			entradas_salidas
		WHERE
			tipo_documento = '$tipo_documento' AND id_documento = '$id';";
Execute($sql);

header("Location: ViewInTdcfccEdit/$newid?showdetail=");
exit();
?>

<?= GetDebugMessage() ?>
