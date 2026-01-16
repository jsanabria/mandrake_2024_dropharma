<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$items = intval($_POST["CantItem"]);


$sql = "SELECT id FROM cliente WHERE nombre LIKE '%ajuste%';";
$row = ExecuteRow($sql);
$cliente = $row["id"];

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$almacen = ExecuteScalar($sql);

/********************  creación Ajuste de Salida ********************/

/**** Obtengo el consecutivo ****/
$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = 'TDCASA';";
$consecutivo = intval(ExecuteScalar($sql)) + 1;
$consecutivo = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

/**** Cabecera Ajuste de Salida ****/
$sql = "INSERT INTO salidas
			(id, tipo_documento, username, fecha,
			cliente, nro_documento,
			nota, estatus, documento, nombre, moneda)
		VALUES 
			(NULL, 'TDCASA', '" . CurrentUserName() . "', '" . date("Y-m-d H:i:s") . "',
			$cliente, '$consecutivo',
			'TRANSFERENCIA', 
			'PROCESADO', 'TR', 'TRANSFERENCIA', 'Bs');";
ExecuteScalar($sql);

$newid = ExecuteScalar("SELECT LAST_INSERT_ID();");

/**** Detalle del movimiento ****/
$control1 = "";
$control2 = "";
$control3 = "";
$control4 = "";
$lote = 0;
$existencia = 0;
$almacen = "";
$cantidad = 0;
$sw = false;
for($i=1; $i<=$items; $i++) {
	$control1 = "x" . $i . "_Lote";
	$control2 = "x" . $i . "_Existencia";
	$control3 = "x" . $i . "_Almacen";
	$control4 = "x" . $i . "_Cantidad";
	if(isset($_POST[$control1])) {
		$lote = $_POST[$control1];
		$existencia = intval($_POST[$control2]);
		$almacen = $_POST[$control3];
		$cantidad = intval($_POST[$control4]);

		$myArr = explode("|", $lote);
		$articulo = $myArr[0];
		$xlote = $myArr[1];
		$fecha_vencimiento = $myArr[2]; 
		$xalmacen = $myArr[4];

        if (empty($fecha_vencimiento) || $fecha_vencimiento == "") {
            $sql_fecha = "NULL";
        } else {
            $sql_fecha = "'$fecha_vencimiento'";
        }

		$row = ExecuteRow("SELECT fabricante, ultimo_costo FROM articulo WHERE id = $articulo;");
		$fabricante = $row["fabricante"];
		$costo = $row["ultimo_costo"];

		// die("$articulo - $lote - $fecha_vencimiento - $almacen");

		$sql = "INSERT INTO entradas_salidas
					(id, tipo_documento, id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					cantidad_movimiento, costo_unidad, costo) 
				VALUES
					(NULL, 'TDCASA', $newid, 
					$fabricante, $articulo, '$xalmacen', '$xlote', $sql_fecha, 
					$cantidad, 'UDM001', 1, 
					(-1)*$cantidad, $costo, $cantidad*$costo);";
		Execute($sql);

		$sw = true;
	}
}
$sql = "SELECT SUM(cantidad_articulo) AS cant FROM entradas_salidas WHERE id_documento = $newid AND tipo_documento = 'TDCASA';";
$cant = intval(ExecuteScalar($sql));

$sql = "UPDATE salidas SET unidades = $cant WHERE id = $newid;";
Execute($sql);
/********************  ************************* ********************/


/********************  creación Ajuste de Entrada ********************/
$sql = "SELECT id FROM proveedor WHERE nombre LIKE '%ajuste%';";
$row = ExecuteRow($sql);
$proveedor = $row["id"];

/**** Obtengo el consecutivo ****/
$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM entradas WHERE tipo_documento = 'TDCAEN';";
$consecutivo = intval(ExecuteScalar($sql)) + 1;
$consecutivo = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

/**** Cabecera Ajuste de Salida ****/
$sql = "INSERT INTO entradas 
			(id, tipo_documento, username, fecha,
			proveedor, nro_documento,
			nota, estatus, documento, moneda)
		VALUES 
			(NULL, 'TDCAEN', '" . CurrentUserName() . "', '" . date("Y-m-d H:i:s") . "',
			$proveedor, '$consecutivo',
			'TRANSFERENCIA', 
			'PROCESADO', 'TR', 'Bs');";
ExecuteScalar($sql);

$newid2 = ExecuteScalar("SELECT LAST_INSERT_ID();");

/**** Detalle del movimiento ****/
$control1 = "";
$control2 = "";
$control3 = "";
$control4 = "";
$lote = 0;
$existencia = 0;
$almacen = "";
$cantidad = 0;
$sw = false;
for($i=1; $i<=$items; $i++) {
	$control1 = "x" . $i . "_Lote";
	$control2 = "x" . $i . "_Existencia";
	$control3 = "x" . $i . "_Almacen";
	$control4 = "x" . $i . "_Cantidad";
	if(isset($_POST[$control1])) {
		$lote = $_POST[$control1];
		$existencia = intval($_POST[$control2]);
		$almacen = $_POST[$control3];
		$cantidad = intval($_POST[$control4]);

		// die("$lote | $existencia | $almacen | $cantidad"); 
		$myArr = explode("|", $lote);
		$articulo = $myArr[0];
		$xlote = $myArr[1];
		$fecha_vencimiento = $myArr[2]; 
		$xalmacen = $myArr[4];

        if (empty($fecha_vencimiento) || $fecha_vencimiento == "") {
            $sql_fecha = "NULL";
        } else {
            $sql_fecha = "'$fecha_vencimiento'";
        }

		$row = ExecuteRow("SELECT fabricante, ultimo_costo FROM articulo WHERE id = $articulo;");
		$fabricante = $row["fabricante"];
		$costo = $row["ultimo_costo"];

		// die("$articulo - $lote - $fecha_vencimiento - $almacen");

		$sql = "INSERT INTO entradas_salidas
					(id, tipo_documento, id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					cantidad_movimiento, costo_unidad, costo)
				VALUES
					(NULL, 'TDCAEN', $newid2, 
					$fabricante, $articulo, '$almacen', '$xlote', $sql_fecha, 
					$cantidad, 'UDM001', 1, 
					$cantidad, $costo, $cantidad*$costo);";
		Execute($sql);

		$sw = true;
	}
}

header("Location: TransferenciaResultado?salida=$newid&entrada=$newid2");
die();
?>
<?= GetDebugMessage() ?>
