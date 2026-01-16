<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$proveedor = $_REQUEST["proveedor"]; 
$cantidad = intval($_REQUEST["cantidad"]); 
$articulo = intval($_REQUEST["articulo"]); 
$username = $_REQUEST["username"]; 
$nota = $_REQUEST["nota"]; 
$lote = $_REQUEST["lote"]; 
$vence = $_REQUEST["vence"]; 
$vence = ($vence == "" ? "1990-01-01" : $vence);

$tipo_documento = "TDCAEN";

$estatus = "NUEVO";
$almacen = $_REQUEST["almacen"]; 

/**** Almacen por defecto ****/
/*
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$almacen = $row["almacen"];
*/

/**** ----- Manejo de las unidades de medida ----- ****/
$sql = "SELECT cantidad_por_unidad_medida, ultimo_costo FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
  $cantidad_unidad = intval($row["cantidad_por_unidad_medida"]);
else 
  $cantidad_unidad = 1;

$cantidad = abs($cantidad);
$cantidad_movimiento = $cantidad_unidad * $cantidad;

$costo_unidad = $row["ultimo_costo"];
$costo = $cantidad*$costo_unidad;

/**** ----- Traigo Datos del Artículo ----- ****/
$sql = "SELECT
	      (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano,
	      unidad_medida_defecto, principio_activo, presentacion, nombre_comercial, cantidad_por_unidad_medida AS cantidad_unidad_medida, 
	      articulo_inventario, unidad_medida_defecto, 
	      CONCAT(IFNULL(principio_activo, ''), ' - ', IFNULL(presentacion, ''), ' - ', IFNULL(nombre_comercial, '')) AS nombre, fabricante   
	    FROM articulo
	    WHERE id = '$articulo';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$unidad_medida = $row["unidad_medida_defecto"];
$nombre_articulo = $row["nombre"];
$fabricante = $row["fabricante"];
$articulo_inventario = $row["articulo_inventario"];
$cantidad_unidad_medida = intval($row["cantidad_unidad_medida"]);

$sql = "SELECT descripcion AS um FROM unidad_medida
  WHERE codigo = '$unidad_medida';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$unidad_medida_nombre = $row["um"];


if($pedido == 0) {
	/* Obetengo el consecutivo del nro de documento */
	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM entradas WHERE tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$consecutivo = intval($row["consecutivo"]) + 1;
	$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

	$sql = "INSERT INTO entradas 
				(id, tipo_documento, username, fecha, proveedor, nro_documento, almacen, 
				nota, estatus) 
			VALUES 
				(NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "', $proveedor, '$nro_documento', '$almacen', 
				'$nota', '$estatus');";
	mysqli_query($link, $sql);

	$sql = "SELECT LAST_INSERT_ID() AS pedido;";
	$result = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($result);
	$pedido = $row["pedido"];

	$sql = "INSERT INTO audittrail
		(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
		VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Crea Ajuste de Entrada NRO $nro_documento', '$username', 'I', 'view_in_tdcaen', 'id', '$pedido', '$nro_documento', '');";
	mysqli_query($link, $sql);
}
else {
	/// Obtengo el $nro_documento  /////
	$sql = "SELECT nro_documento FROM entradas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$nro_documento = $row["nro_documento"];
}


$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, fabricante, articulo, 
			almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
			costo_unidad, costo, 
			newdata, lote, fecha_vencimiento)
		VALUES 
			(NULL, '$tipo_documento', $pedido, $fabricante, $articulo, 
			'$almacen', $cantidad, '$unidad_medida', $cantidad_unidad_medida, $cantidad_movimiento, 
			$costo_unidad, $costo, 
			'S', '$lote', '$vence');";
mysqli_query($link, $sql);

$sql = "SELECT LAST_INSERT_ID() AS id_item;";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$id_item = $row["id_item"];

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Inserta Articulo en Ajuste de Entrada NRO $nro_documento', '$username', 'I', 'view_in_tdcaen', 'id', '$pedido', '$articulo', '');";
mysqli_query($link, $sql);


// Se actualiza el encabezado del padido de venta //
$sql = "SELECT
			COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
	    FROM 
	      entradas_salidas 
	    WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$renglones = floatval($row["renglones"]);
$unidades = floatval($row["unidades"]);

/**** ----- Actualizo campos en la cabecera del pedido ----- ****/
$sql = "UPDATE entradas SET nota = '$nota' WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

/// Actualizo el campo unidades ///
$sql = "UPDATE 
			entradas AS a 
			JOIN (SELECT 
						id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
					FROM 
						entradas_salidas 
					WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido 
					GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $pedido;";
mysqli_query($link, $sql);

$html = '{
			"pedido":"' . $pedido . '",
			"renglones":"' . $renglones . '",
			"unidades":"' . $unidades . '",
         	"mensaje":"Hello World", 
         	"estatus":"1",
         	"id_item":"' . $id_item . '", 
         	"nro_documento":"' . $nro_documento . '"  
        }';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>