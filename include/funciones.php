<?php 

function CostoPromedio($articulo, $link) { 
	$sql = "SELECT 
				IFNULL((SUM(a.precio_unidad_sin_desc)/COUNT(a.precio_unidad_sin_desc)), 0) AS ultimo_costo 
			FROM 
				entradas_salidas AS a 
			WHERE a.tipo_documento = 'TDCNRP' AND a.articulo = $articulo AND a.check_ne = 'S' 
			ORDER BY a.id DESC LIMIT 0, 1;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$ultimo_costo = $row["ultimo_costo"];

	/* Se actualiza el costo del artículo según la ultima recepción o factura de compra */
	$sql = "UPDATE articulo SET ultimo_costo = $ultimo_costo WHERE id = $articulo;"; 
	mysqli_query($link, $sql);	
}

?>