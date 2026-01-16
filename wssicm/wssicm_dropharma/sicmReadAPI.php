<?php
$factura_detalle_json = file_get_contents("http://192.168.0.5/wsscc/sccAPI.php?user=365&app=factura_detalle&id=$factura");

$decoded_json = json_decode($factura_detalle_json, true);
$facturas_detalle = $decoded_json["listaFacturaDetalle"];

foreach ($facturas_detalle as $key => $value) {
	/*
	$id = $value["id"]; 
	$factura = $value["factura"]; 
	$servicio_tipo = $value["servicio_tipo"]; 
	$servicio = $value["servicio"]; 
	$cantidad_articulo = $value["cantidad_articulo"]; 
	$precio_unidad = $value["precio_unidad"]; 
	$precio = $value["precio"]; 
	$alicuota = $value["alicuota"]; 


	$sql = "INSERT INTO sco_factura_detalle(
					id, 
					factura, 
					servicio_tipo, 
					servicio, 
					cantidad_articulo, 
					precio_unidad, 
					precio, 
					alicuota)
			VALUES (
					$id, 
					$factura, 
					\"$servicio_tipo\", 
					\"$servicio\", 
					$cantidad_articulo, 
					$precio_unidad, 
					$precio, 
					$alicuota);"; 
	$rs = mysqli_query($link, $sql); 
	echo "$sql <br>";
	*/
}
?>