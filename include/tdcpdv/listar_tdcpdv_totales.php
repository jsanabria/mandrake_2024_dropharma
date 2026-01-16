<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 

$sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$moneda = $row["moneda"];

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$tasa_usd = floatval($row["tasa"]);

$tipo_documento = "TDCPDV";

$sql = "SELECT IFNULL(descuento, 0) AS descuento, IFNULL(descuento2, 0) AS descuento2, nro_documento, moneda, tasa_dia FROM salidas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
	$descuento = $row["descuento"];
	$descuento2 = $row["descuento2"];
	$nro_documento = $row["nro_documento"];
	$moneda = $row["moneda"];
	$tasa_usd = floatval($row["tasa_dia"]);
}
else  {
	$descuento = 0;
	$descuento2 = 0;
	$nro_documento = "0000000";
}
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

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
			SUM(precio) AS precio_unidad_sin_desc, MAX(alicuota) AS alicuota,  
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
$xExento = $xExento - ($xExento*($descuento2/100));
$xGravado = floatval($row["gravado"]);
$xGravado = $xGravado - ($xGravado*($descuento2/100));
$costo = $xExento + $xGravado;
$iva = $xGravado * (floatval($xalicuota)/100);
$total = $costo + $iva;


$total_usd = round((substr(strtolower(trim($moneda)), 0, 3)=="bs." ? ($total/$tasa_usd) : $total), 2);

$sql = "UPDATE salidas 
	    SET
	      monto_total = $costo,
	      iva = $iva,
	      total = $total, 
	      tasa_dia = $tasa_usd, 
	      monto_usd = $total_usd 
		  -- monto_sin_descuento = $monto_sin_descuento, 
	    WHERE id = '$pedido'";
mysqli_query($link, $sql);

$html = '{
			"pedido":"' . $pedido . '",
			"total":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo/$tasa_usd),2) : $costo) . '",
			"renglones":"' . $renglones . '",
			"unidades":"' . $unidades . '",
			"total_usd":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $costo : round(($costo*$tasa_usd),2)) . '",
			"descuento":"' . $descuento . '",
			"monto_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento/$tasa_usd),2) : $monto_sin_descuento) . '",
         	"mensaje":"Hello World", 
         	"total_usd_sin_descuento":"' . (strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento*$tasa_usd),2)) . '", 
         	"estatus":"1",  
	        "nro_documento":"' . $nro_documento . '", 
	        "descTransferencista":"' . intval($descuento2) . '"   
        }';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>