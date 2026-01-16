<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaRecepcion = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];

$sql = "SELECT tipo_documento, consignacion FROM entradas WHERE id = '$id';";
$row = ExecuteRow($sql);
$tipo = $row["tipo_documento"];
$consignacion = strtoupper($row["consignacion"]);

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$almacen = ExecuteScalar($sql);

/**** Si es compra a consignación cambio el almacen ****/
if($consignacion == "S") {
	$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '014';";
	$almacen = ExecuteScalar($sql);
}

$sql = "INSERT INTO entradas
			(id, tipo_documento, username, fecha, 
			proveedor, nro_documento, almacen, estatus, 
			id_documento_padre, consignacion, 
            moneda, tasa_dia, monto_usd, descuento)
		SELECT 
			NULL, 'TDCNRP', '" . CurrentUserName() . "', '" . date("Y-m-d H:i:s") . "', 
			proveedor, '', '$almacen', 'NUEVO', 
			'$id', consignacion, 
            moneda, tasa_dia, monto_usd, descuento  
		FROM entradas 
		WHERE id = '$id';";
Execute($sql);

$newid = ExecuteScalar("SELECT LAST_INSERT_ID();");

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, 
			fabricante, articulo, almacen, 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, lote, fecha_vencimiento,
			costo_unidad, costo, descuento, precio_unidad_sin_desc, alicuota) 
		SELECT 
			NULL, 'TDCNRP', '$newid', 
			fabricante, articulo, '$almacen', 
			cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
			cantidad_movimiento, lote, fecha_vencimiento, costo_unidad, costo,
			descuento, precio_unidad_sin_desc, alicuota 
		FROM entradas_salidas
		WHERE id_documento = '$id' AND tipo_documento = '$tipo'";
Execute($sql);

/* El último costo se estable en el check de la recepción
$sql = "UPDATE 
            entradas_salidas AS a 
            JOIN articulo AS b ON b.id = a.articulo 
        SET 
            b.ultimo_costo = a.precio_unidad_sin_desc
        WHERE 
            a.id_documento = '$id' AND a.tipo_documento = '$tipo';";
Execute($sql);
*/

$sql = "UPDATE entradas SET estatus = 'PROCESADO', id_documento_padre = '$newid'  WHERE id = '$id'";
Execute($sql);

$sql = "UPDATE 
            entradas AS a 
            JOIN (SELECT 
                        id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                    FROM 
                        entradas_salidas 
                    WHERE tipo_documento = 'TDCNRP' AND id_documento = $newid 
                    GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
        SET 
            a.unidades = b.cantidad 
        WHERE a.id = $newid;";
Execute($sql);


//header("Location: entradasedit.php?showdetail=entradas_salidas&id=$newid&tipo=TDCNRP");
header("Location: ViewInTdcnrpEdit/$newid?showdetail=");
die();
?>

<?= GetDebugMessage() ?>
