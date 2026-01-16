<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$articulo = intval($_REQUEST["articulo"]); 
$tasa_usd = floatval($_REQUEST["tasa_usd"]); 
$moneda = $_REQUEST["moneda"]; 
$username = $_REQUEST["username"]; 
$descuento = floatval($_REQUEST["descuento"]); 
$id_item = intval($_REQUEST["id_item"]); 
$nota = $_REQUEST["nota"]; 

$tipo_documento = "TDCFCC";
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

/**** ----- Actualizo el campo descuento en la cabecera del pedido ----- ****/
$sql = "UPDATE entradas SET descuento = $descuento, nota = '$nota', tasa_dia = $tasa_usd, moneda = '$moneda' WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

$sql = "SELECT nro_documento FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];

/**** ----- Elimino el item ----- ****/
$sql = "DELETE FROM entradas_salidas WHERE id = $id_item;";
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
mysqli_query($link, $sql);


$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Articulo de Factura de Compra NRO $nro_documento Articulo $articulo', '$username', 'D', 'view_in_tdcfcc', 'id', '$pedido', '$articulo', '');";
mysqli_query($link, $sql);


/**** ----- Valido si elimino la cabecera ----- ****/
$sql = "SELECT * FROM entradas_salidas WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
	/// Obtengo el $nro_documento  /////
	$sql = "SELECT nro_documento FROM entradas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$nro_documento = $row["nro_documento"];

	//////////////// Actualizo Cabecera ////////////////
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

	// Se actualiza el encabezado del documento //
	$sql = "SELECT
				SUM(precio_unidad_sin_desc) AS precio_unidad_sin_desc, 
				SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuento/100)), 0)) AS exento, 
				SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuento/100)))) AS gravado, 
				SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
				SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total, 
				COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
		    FROM 
		      entradas_salidas 
		    WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
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
		      tasa_dia = $tasa_usd, 
		      monto_usd = $total_usd, tasa_dia = $tasa_usd, moneda = '$moneda' 
			  -- monto_sin_descuento = $monto_sin_descuento, 
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
	         	"nro_documento":"' . $nro_documento . '"  
	        }';


} 
else {
	$sql = "DELETE FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
	mysqli_query($link, $sql);
	$nro_documento = "0000000";

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
	         	"nro_documento":"' . $nro_documento . '"  
	        }';
}

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

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>