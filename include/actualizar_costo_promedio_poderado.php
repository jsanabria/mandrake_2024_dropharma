<?php 
    $sql = "SELECT id FROM articulo WHERE 1;";
    $rows = ExecuteRows($sql);
    foreach ($rows as $key => $value) {
  		$sql2 = "SELECT 
					b.id, b.nro_documento, b.fecha  
				FROM 
					entradas_salidas AS a 
					JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
				WHERE 
					a.articulo = " . (int)$value["id"] . " 
					AND b.estatus = 'PROCESADO' 
					AND a.check_ne = 'S' 
					AND a.tipo_documento = 'TDCNRP' AND YEAR(b.fecha) > 2024 
				ORDER BY b.id DESC LIMIT 0, 1;";
		$rows2 = ExecuteRows($sql2);
		foreach ($rows2 as $key2 => $value2) { 
			$pedido = (int)$value2["id"];
			$sql3 = "SELECT 
						articulo, SUM(costo_unidad) AS costo, SUM(cantidad_movimiento) AS cantidad 
					FROM 
						entradas_salidas WHERE id_documento = $pedido AND tipo_documento = 'TDCNRP' 
					GROUP BY articulo;";
			$rows3 = ExecuteRows($sql3); 
			foreach ($rows3 as $key3 => $value3) {
				CalcularCostoPromedioPonderado((int)$value3["articulo"], (float)$value3["costo"], (int)$value3["cantidad"], $pedido);
			}
			echo "ARTICULO: " . $value["id"] . " PEDIDO RPC: $pedido NRO: " . $value["nro_documento"] . " Fecha: " . $value["nro_documento"];
		}
    }
?>
