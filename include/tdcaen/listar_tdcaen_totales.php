<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 

$tipo_documento = "TDCAEN";

$sql = "SELECT IFNULL(descuento, 0) AS descuento, nro_documento, moneda, tasa_dia FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
	$nro_documento = $row["nro_documento"];
}
else  {
	$nro_documento = "0000000";
}

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

$html = '{
			"pedido":"' . $pedido . '",
			"renglones":"' . $renglones . '",
			"unidades":"' . $unidades . '",
         	"mensaje":"Hello World", 
         	"estatus":"1",  
	        "nro_documento":"' . $nro_documento . '"  
        }';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>