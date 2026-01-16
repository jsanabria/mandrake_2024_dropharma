<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$articulo = intval($_REQUEST["articulo"]); 
$tasa_usd = floatval($_REQUEST["tasa_usd"]); 
$moneda = $_REQUEST["moneda"]; 
$username = $_REQUEST["username"]; 
$descuento = floatval($_REQUEST["descuento"]); 
$descTransferencista = floatval($_REQUEST["descTransferencista"]); 
$id_item = intval($_REQUEST["id_item"]); 
$nota = $_REQUEST["nota"]; 
$consignacion = $_REQUEST["consignacion"]; 
$doc_afectado = $_REQUEST["doc_afectado"]; 

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

// Verifico si los artículos tienen una misma alicuota o varias por cada uno de ellos //
$sql = "SELECT 
	      COUNT(DISTINCT alicuota) AS cantidad  
	    FROM 
	      entradas_salidas 
	    WHERE 
	      id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
  $sql = "SELECT 
        DISTINCT alicuota 
      FROM 
        entradas_salidas 
      WHERE 
        id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
  $rs = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($rs);
  $alicuota = floatval($row["alicuota"]);
}


///////// INI - Pare reversar consignaciones /////////////
$sql = "SELECT consignacion, IFNULL(id_documento_padre, 0) AS id_documento_padre, IFNULL(direccion, '') AS direccion FROM salidas WHERE id = $pedido;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);  
$consignacion = $row["consignacion"];
$id_documento_padre = intval($row["id_documento_padre"]);
$direccion = $row["direccion"];

if($consignacion == "S") {
	$sql = "SELECT 
				cantidad_movimiento, id_consignacion
			FROM 	
				entradas_salidas 			
			WHERE 
				id = '$id_item';";
	$rs = mysqli_query($link, $sql);  
	if ($row = mysqli_fetch_array($rs)) {
		if(intval($row["id_consignacion"]) > 0) {
			$cantidad = intval($row["cantidad_movimiento"]);
			$sql = "UPDATE entradas_salidas
					SET cantidad_movimiento_consignacion = (cantidad_movimiento_consignacion + ($cantidad))
					WHERE id = " . $row["id_consignacion"] . ";";
			mysqli_query($link, $sql); 
		}		# code...
	}

	if($id_documento_padre > 0) {
		$sql = "UPDATE salidas
			SET estatus = 'NUEVO'
			WHERE id = $id_documento_padre;";
		mysqli_query($link, $sql); 
	}

	if(trim($direccion) != "") {
		// Naso en que hayan varias notas de entrega en la misma factura. Informacion Guardada en el campo direccion
		$sql = "UPDATE salidas
				SET estatus = 'NUEVO'
				WHERE id IN ($direccion);";
		mysqli_query($link, $sql); 
	}
}
///////// FIN - Pare reversar consignaciones /////////////


/**** ----- Actualizo el campo descuento en la cabecera del pedido ----- ****/
$sql = "UPDATE salidas SET descuento = $descuento, descuento2 = $descTransferencista, nota = '$nota', tasa_dia = $tasa_usd, moneda = '$moneda' WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

/**** ----- Elimino el item ----- ****/
$sql = "DELETE FROM entradas_salidas WHERE id = $id_item;";
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
mysqli_query($link, $sql);

$sql = "SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];

/// Obtengo el $nro_documento  /////
$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Articulo de Factura de Venta NRO/ID $nro_documento/($pedido) Articulo $articulo', '$username', 'D', 'view_out_tdcfcv', 'id', '$pedido', '$articulo', '');";
mysqli_query($link, $sql);


/**** ----- Valido si elimino la cabecera ----- ****/
$sql = "SELECT * FROM entradas_salidas WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
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

	// Se actualiza el encabezado del documento //
	$sql = "SELECT
				SUM(precio) AS precio_unidad_sin_desc, 
				SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento, 
				SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado, 
				SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
				SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total, 
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
			  monto_sin_descuento = $monto_sin_descuento 
		    WHERE id = '$pedido'";
	mysqli_query($link, $sql);


	$html = '{
				"pedido":"' . $pedido . '",
				"total":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo/$tasa_usd),2) : $costo) . '",
				"renglones":"' . $renglones . '",
				"unidades":"' . $unidades . '",
				"total_usd":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $costo : round(($costo*$tasa_usd),2)) . '",
				"monto_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento/$tasa_usd),2) : $monto_sin_descuento) . '",
	         	"mensaje":"Hello World", 
	         	"total_usd_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento*$tasa_usd),2)) . '", 
	         	"estatus":"1",  
	         	"consignacion":"' . $consignacion . '", 
	         	"doc_afectado":"' . $doc_afectado . '", 
	         	"nro_documento":"' . $nro_documento . '"  
	        }';
} 
else {
	$sql = "DELETE FROM salidas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
	mysqli_query($link, $sql);
	// $nro_documento = "0000000";

	$html = '{
				"pedido":"0",
				"total":"0.00",
				"renglones":"0",
				"unidades":"0",
				"total_usd":"0.00",
				"monto_sin_descuento":"0.00",
	         	"mensaje":"Hello World", 
	         	"total_usd_sin_descuento":"0.00", 
	         	"estatus":"1", 
	         	"consignacion":"' . $consignacion . '", 
	         	"doc_afectado":"' . $doc_afectado . '", 
	         	"nro_documento":"' . $nro_documento . '"  
	        }';
}

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


echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>