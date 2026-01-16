<?php 
	if($tipo != "") $where = "AND a.almacen = '$tipo'";
	// LPAD(a.nro_documento, 12, '0') AS codigo, 
    $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
	$rs = mysqli_query($link, $sql);
    $tipo_documento = 'TDCNET';
  	if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];

	$sql = "SELECT 
				inventario.articulo, inventario.lote, inventario.fecha_vencimiento, SUM(inventario.cantidad) AS cantidad 
			FROM 
				(
					SELECT 
                        a.articulo, a.lote, a.fecha_vencimiento, SUM(a.cantidad_movimiento) AS cantidad 
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
					GROUP BY a.articulo, a.lote, a.fecha_vencimiento 
	      UNION ALL SELECT 
                        a.articulo, a.lote, a.fecha_vencimiento, SUM(a.cantidad_movimiento) AS cantidad 
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
					GROUP BY a.articulo, a.lote, a.fecha_vencimiento 
				) AS inventario 
			GROUP BY inventario.articulo, inventario.lote, inventario.fecha_vencimiento 
			HAVING SUM(inventario.cantidad) >0;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
	    // Agregamos las nuevas columnas 'costo' y 'precio' a la fila actual ($row)
	    $idArt = $row["id"];
		$sql = "SELECT 
					a.codigo, a.codigo_de_barra, b.nombre AS fabricante, 
					CONCAT(IFNULL(a.principio_activo, ''), ' ', IFNULL(a.presentacion, '')) AS descripcion, 
					a.categoria cod_cat, c.campo_descripcion AS categoria, 
					a.ultimo_costo, a.precio, (SELECT alicuota FROM alicuota WHERE codigo = a.alicuota) AS alicuota 
				FROM 
					articulo AS a 
					LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
					LEFT OUTER JOIN tabla AS c ON c.campo_codigo = a.categoria AND c.tabla = 'CATEGORIA' 
				WHERE a.id = $idArt";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$codigo = $row2["codigo"];
		$codigo_de_barra = $row2["codigo_de_barra"];
		$fabricante = $row2["fabricante"];
		$descripcion = $row2["descripcion"];
		$cod_cat = $row2["cod_cat"];
		$categoria = $row2["categoria"];
		$costo = $row2["ultimo_costo"];
		$precio = $row2["precio"];
		$alicuota = $row2["alicuota"];


		$row['codigo'] = $codigo;
		$row['codigo_de_barra'] = $codigo_de_barra;
		$row['fabricante'] = $fabricante;
		$row['descripcion'] = $descripcion;
		$row['cod_cat'] = $cod_cat;
		$row['categoria'] = $categoria;
	    $row['costo'] = $costo;
	    $row['precio'] = $precio;
	    $row['alicuota'] = $alicuota;		
		$developer_records[] = $row;
	}

	$filename = "INVENTARIO_" . date('Ymd') . ".xls";
?>
