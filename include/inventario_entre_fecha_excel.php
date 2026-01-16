<?php 
	if($tipo != "") $where = "AND a.almacen = '$tipo'";
	// LPAD(a.nro_documento, 12, '0') AS codigo, 
    $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
	$rs = mysqli_query($link, $sql);
    $tipo_documento = 'TDCNET';
  	if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];

	$sql = "SELECT 
				art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
				'UNIDAD' AS unidad_medida, art.principio_activo, 
				art.presentacion, art.nombre_comercial, 
				IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad,0)) AS salidas, 
				(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad,0))) AS existencia 
			FROM 
				(
					SELECT 
						a.id, a.codigo, a.codigo_de_barra, b.nombre, 
						'UNIDAD' AS unidad_medida, a.principio_activo, 
						a.presentacion, a.nombre_comercial 
					FROM 
						articulo AS a 
						LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
					WHERE 
						0 = 0
				) AS art 
				LEFT OUTER JOIN 
				(
					SELECT 
                        a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                    FROM 
                        entradas_salidas AS a 
                        JOIN salidas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                            (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                        ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
						$where 
					GROUP BY a.articulo
				) AS sal ON sal.articulo = art.Id 
				LEFT OUTER JOIN 
				(
                    SELECT 
                        a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                    FROM 
                        entradas_salidas AS a 
                        JOIN entradas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                            (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                        ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
						$where 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
			WHERE 1 ORDER BY art.nombre, art.principio_activo, art.presentacion;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
	    // Agregamos las nuevas columnas 'costo' y 'precio' a la fila actual ($row)
	    $idArt = $row["id"];
		$sql = "SELECT ultimo_costo, precio FROM articulo WHERE id = $idArt";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$costo = $row2["ultimo_costo"];
		$precio = $row2["precio"];


	    $row['costo'] = $costo;
	    $row['precio'] = $precio;		
		$developer_records[] = $row;
	}

	$filename = "INVENTARIO_" . date('Ymd') . ".xls";
?>