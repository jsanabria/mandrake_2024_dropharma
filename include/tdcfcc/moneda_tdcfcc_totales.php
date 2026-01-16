<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$username = $_REQUEST["username"]; 

$moneda = $_REQUEST["moneda"]; 
$tasa_usd = floatval($_REQUEST["tasa_usd"]); 


$sql = "SELECT descuento FROM entradas WHERE id = $pedido;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$descuento = floatval($row["descuento"]);
$descuentoG = $descuento;

$tipo_documento = "TDCFCC";

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

// Se actualiza el encabezado del padido de venta //
$sql = "SELECT
			SUM(precio_unidad_sin_desc) AS precio_unidad_sin_desc, 
			SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuentoG/100)), 0)) AS exento, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100)))) AS gravado, 
			SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
			SUM(IF(IFNULL(alicuota,0)=0, costo - (costo * ($descuentoG/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, costo - (costo * ($descuentoG/100))) * (IFNULL(alicuota,0)/100))) AS total, 
			COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
	    FROM 
	      entradas_salidas 
	    WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
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
		      monto_usd = $total_usd, moneda = '$moneda'  
			  -- monto_sin_descuento = $monto_sin_descuento, 
		    WHERE id = '$pedido'";
	mysqli_query($link, $sql);

	$sql = "SELECT nro_documento FROM entradas WHERE id = $pedido;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$nro_documento = $row["nro_documento"];

	$sql = "INSERT INTO audittrail
		(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
		VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Actualiza moneda y/o tasa del dia en Factura de Compra NRO $nro_documento', '$username', 'U', 'view_in_tdcfcc', 'id', '$pedido', '$moneda', '$tasa_usd');";
	mysqli_query($link, $sql);


	$html = '{
				"total":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo/$tasa_usd),2) : $costo) . '",
				"total_usd":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $costo : round(($costo*$tasa_usd),2)) . '",
				"monto_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento/$tasa_usd),2) : $monto_sin_descuento) . '",
	         	"total_usd_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento*$tasa_usd),2)) . '", 
	         	"estatus":"1"
	        }';
}
else {
	$html = '{
				"total":"0.00",
				"total_usd":"0.00",
				"monto_sin_descuento":"0.00",
	         	"total_usd_sin_descuento":"0.00", 
	         	"estatus":"1" 
	        }';
}

// $html = "test";
echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>