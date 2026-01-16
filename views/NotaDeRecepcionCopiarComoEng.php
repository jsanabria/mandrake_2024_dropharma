<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeRecepcionCopiarComoEng = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo_documento"];
$doc = $_REQUEST["doc"];
	
$documento = substr($_REQUEST["documento"], 0, 2);

$username = CurrentUserName();
$estatus = "NUEVO";

$sql = "SELECT tasa_dia, monto_usd, moneda, descuento FROM entradas WHERE id = $id AND tipo_documento = '$tipo_documento';";
$row = ExecuteRow($sql);
$tasa_usd = floatval($row["tasa_dia"]); 
$moneda = $row["moneda"]; 
$descuentoG = $row["descuento"];

$sql = "INSERT INTO entradas
            (id, tipo_documento, username,
            fecha, proveedor, documento, nro_documento,
            almacen, monto_total, alicuota_iva,
            iva, total, nota,
            estatus, id_documento_padre, moneda, tasa_dia, monto_usd, descuento)
        SELECT
            NULL, tipo_documento, '$username',
            '" . date("Y-m-d H:i:s") . "', proveedor, '$documento', nro_documento,
            almacen, monto_total, alicuota_iva,
            iva, total, NULL,
            '$estatus', id_documento_padre, moneda, tasa_dia, monto_usd, descuento 
        FROM entradas
        WHERE
            id = '$id';";
Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$newid = ExecuteScalar($sql);

if($documento == "CP") {
    $sql = "INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento, 
                fabricante, articulo, almacen, 
                cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                cantidad_movimiento, alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc, check_ne)
            SELECT
                NULL, tipo_documento, $newid, 
                fabricante, articulo, almacen, 
                cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                cantidad_movimiento, alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc, check_ne 
            FROM
                entradas_salidas
            WHERE
                tipo_documento = '$tipo_documento' AND id_documento = '$id' AND check_ne = 'N';";
}
else {
    $sql = "INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento, 
                fabricante, articulo, almacen, 
                cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                cantidad_movimiento, alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc, check_ne)
            SELECT
                NULL, tipo_documento, $newid, 
                fabricante, articulo, almacen, 
                cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                cantidad_movimiento, alicuota, costo_unidad, costo, descuento, precio_unidad_sin_desc, check_ne 
            FROM
                entradas_salidas
            WHERE
                tipo_documento = '$tipo_documento' AND id_documento = '$id';";
}
Execute($sql);

// Verifico si los artículos tienen una misma alicuota o varias por cada uno de ellos //
$sql = "SELECT 
            COUNT(DISTINCT alicuota) AS cantidad  
        FROM 
            entradas_salidas 
        WHERE 
            id_documento = '$newid' AND tipo_documento = '$tipo_documento';";
$row = ExecuteRow($sql);
if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
    $sql = "SELECT 
        DISTINCT alicuota 
        FROM 
        entradas_salidas 
        WHERE 
        id_documento = '$newid' AND tipo_documento = '$tipo_documento';";
    $row = ExecuteRow($sql);
    $alicuota = floatval($row["alicuota"]);
}


$sql = "SELECT
			SUM(precio_unidad_sin_desc) AS precio_unidad_sin_desc, 
			SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuentoG/100)), 0)) AS exento, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100)))) AS gravado, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
			SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuentoG/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100))) * (IFNULL(alicuota,0)/100))) AS total, 
			COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
	    FROM 
	      entradas_salidas 
	    WHERE id_documento = '$newid' AND tipo_documento = '$tipo_documento';"; 
$row = ExecuteRow($sql);
$monto_sin_descuento = floatval($row["precio_unidad_sin_desc"]);
$costo = floatval($row["exento"]) + floatval($row["gravado"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);
$renglones = floatval($row["renglones"]);
$unidades = floatval($row["unidades"]);
$total_usd = round((substr(strtolower(trim($moneda)), 0, 3)=="bs." ? ($total/$tasa_usd) : $total), 2);

$sql = "UPDATE entradas 
	    SET
	      monto_total = $costo, 
	      alicuota_iva = $alicuota, 
	      iva = $iva, 
	      total = $total, 
	      monto_usd = $total_usd, moneda = '$moneda' 
	    WHERE id = '$newid'"; 
Execute($sql);

header("Location: ViewInTdcnrpEdit/$newid?showdetail=");
exit();
?>

<?= GetDebugMessage() ?>
