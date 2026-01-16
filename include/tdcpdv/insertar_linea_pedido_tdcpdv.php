<?php
include "../connect.php";
// die(json_encode("Hello World", JSON_UNESCAPED_UNICODE));
$pedido = intval($_REQUEST["pedido"]); 
$cliente = $_REQUEST["cliente"]; 
$precioFull = $_REQUEST["precioFull"]; 
$descuento = $_REQUEST["descuento"]; 
$precio = $_REQUEST["precio"]; 
$moneda = $_REQUEST["moneda"]; 
$cantidad = intval($_REQUEST["cantidad"]); 
$total = $precio*$cantidad; 
$articulo = intval($_REQUEST["articulo"]); 
$tasa_usd = floatval($_REQUEST["tasa_usd"]); 
$username = $_REQUEST["username"]; 
$descuentoG = intval($_REQUEST["descuentoG"]); 
$descTransferencista = floatval($_REQUEST["descTransferencista"]); 
$nota = $_REQUEST["nota"]; 
$lista_pedido = $_REQUEST["lista_pedido"]; 
$dias_credito = intval($_REQUEST["dias_credito"]); 


$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];

$tipo_documento = "TDCPDV";

$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

$estatus = "NUEVO";

/*** Busco la tarifa del cliente ***/
$sql = "SELECT tarifa, nombre FROM cliente WHERE id = '$cliente';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = $row["tarifa"];
$nombre_cliente = $row["nombre"];

/*** Busco la alicuota del IVA asociada al artículo ***/
$sql = "SELECT alicuota, lista_pedido FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$codigo_alicuota = $row["alicuota"];
$lista_pedido = $row["lista_pedido"];

$sql = "SELECT alicuota FROM alicuota
    WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$alicuota = floatval($row["alicuota"]);

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

/**** ----- Manejo de las unidades de medida y el costo del artículo ----- ****/
$sql = "SELECT cantidad_por_unidad_medida, ultimo_costo FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
  $cantidad_unidad = intval($row["cantidad_por_unidad_medida"]);
else 
  $cantidad_unidad = 1;

$cantidad = abs($cantidad);
$cantidad_movimiento = (-1)*($cantidad_unidad * $cantidad);

$costo_unidad = $row["ultimo_costo"];
$costo = $cantidad*$costo_unidad;

/**** ----- Traigo Datos del Artículo ----- ****/
$sql = "SELECT
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
	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$consecutivo = intval($row["consecutivo"]) + 1;
	$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

	$sql = "INSERT INTO salidas 
				(id, tipo_documento, nro_documento, username, fecha, cliente, 
				nota, estatus, moneda, consignacion, descuento, descuento2, lista_pedido, dias_credito, nombre, direccion) 
			VALUES 
				(NULL, '$tipo_documento', '$nro_documento', '$username', '" . date("Y-m-d H:i:s") . "', $cliente, 
				'$nota', '$estatus', '$moneda', 'N', $descuentoG, $descTransferencista, '$lista_pedido', $dias_credito, '$nombre_cliente', 'CREADO DESDE EL SISTEMA ADMINISTRATIVO MANDRAKE');";
	mysqli_query($link, $sql);

	$sql = "SELECT LAST_INSERT_ID() AS pedido;";
	$result = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($result);
	$pedido = $row["pedido"];

	$sql = "INSERT INTO audittrail
		(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
		VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Crea Factura de Venta', '$username', 'I', 'view_out_tdcpdv', 'id', '$pedido', 'Nuevo nro de pedido', '');";
	mysqli_query($link, $sql);
}
else {
	/// Obtengo el $nro_documento  /////
	$sql = "SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$nro_documento = $row["nro_documento"];

	$sql = "UPDATE salidas SET descuento = $descuentoG, descuento2 = $descTransferencista WHERE id = $pedido;";
	mysqli_query($link, $sql);
}

$sql = "INSERT INTO entradas_salidas
			(id, tipo_documento, id_documento, fabricante, articulo, 
			almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
			costo_unidad, costo, 
			precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc, newdata)
		VALUES 
			(NULL, '$tipo_documento', $pedido, $fabricante, $articulo, 
			'$almacen', $cantidad, '$unidad_medida', $cantidad_unidad_medida, $cantidad_movimiento, 
			$costo_unidad, $costo, 
			$precio, $total, $alicuota, $descuento, $precioFull, 'S');"; 
// die(json_encode($sql, JSON_UNESCAPED_UNICODE));
mysqli_query($link, $sql);

$sql = "SELECT LAST_INSERT_ID() AS id_item;";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$id_item = $row["id_item"];

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Inserta Articulo en Pedido de Ventas NRO/ID $nro_documento/($pedido)', '$username', 'I', 'view_out_tdcpdv', 'id', '$pedido', '$articulo', '');";
mysqli_query($link, $sql);

//////////////// Actualizo Cabecera ////////////////
// Verifico si los artículos tienen una misma alicuota o varias por cada uno de ellos //
$sql = "SELECT 
    DISTINCT alicuota 
  FROM 
    entradas_salidas 
  WHERE 
    id_documento = '$pedido' AND tipo_documento = '$tipo_documento' ORDER BY 1 DESC LIMIT 0, 1;;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$xalicuota = floatval($row["alicuota"]);

// Se actualiza el encabezado del padido de venta //
$sql = "SELECT
			SUM(IFNULL(precio, 0)) AS precio_unidad_sin_desc, 
			SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuentoG/100)), 0)) AS exento, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuentoG/100)))) AS gravado, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuentoG/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
			SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuentoG/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuentoG/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuentoG/100))) * (IFNULL(alicuota,0)/100))) AS total, 
			COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
	    FROM 
	      entradas_salidas 
	    WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$monto_sin_descuento = floatval($row["precio_unidad_sin_desc"]);
$renglones = floatval($row["renglones"]);
$unidades = floatval($row["unidades"]);
/*
$costo = floatval($row["exento"]) + floatval($row["gravado"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);
*/
$xExento = floatval($row["exento"]);
$xExento = $xExento - ($xExento*($descTransferencista/100));
$xGravado = floatval($row["gravado"]);
$xGravado = $xGravado - ($xGravado*($descTransferencista/100));
$costo = $xExento + $xGravado;
$iva = $xGravado * (floatval($xalicuota)/100);
$total = $costo + $iva;

$total_usd = round((substr(strtolower(trim($moneda)), 0, 3)=="bs." ? ($total/$tasa_usd) : $total), 2);

$sql = "UPDATE salidas 
	    SET
	      monto_total = $costo,
	      alicuota_iva = $alicuota, 
	      iva = $iva,
	      total = $total, 
	      tasa_dia = $tasa_usd, 
	      monto_usd = $total_usd, tasa_dia = $tasa_usd, moneda = '$moneda', 
		  monto_sin_descuento = $monto_sin_descuento, nota = '$nota', 
		  dias_credito = $dias_credito 
	    WHERE id = '$pedido'";
mysqli_query($link, $sql);

/// Actualizo el campo unidades ///
$sql = "UPDATE 
			salidas AS a 
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
			"total":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo/$tasa_usd),2) : $costo)  . '",
			"renglones":"' . $renglones . '",
			"unidades":"' . $unidades . '",
			"total_usd":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $costo : round(($costo*$tasa_usd),2)) . '",
			"monto_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento/$tasa_usd),2) : $monto_sin_descuento) . '",
         	"mensaje":"Hello World", 
         	"total_usd_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento*$tasa_usd),2)) . '", 
         	"estatus":"1",
         	"id_item":"' . $id_item . '", 
         	"nro_documento":"' . $nro_documento . '"  
        }';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>