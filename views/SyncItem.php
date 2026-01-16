<?php

namespace PHPMaker2024\mandrake;

// Page object
$SyncItem = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];

// Actualizo el campo fabricante en la tabla de tarifas con lo que diga el maestro de artículos
$sql = "UPDATE  
            tarifa_articulo AS a 
            JOIN articulo AS b ON b.id = a.articulo 
        SET 
            a.fabricante = b.fabricante
        WHERE	a.fabricante <> b.fabricante;";
Execute($sql);
// ---

$sql = "INSERT INTO tarifa_articulo
	(id, tarifa, fabricante, articulo, precio)
SELECT 
	NULL, $id AS tarifa, a.fabricante, a.id AS articulo, 0.00 AS precio 
FROM 
	articulo AS a 
	LEFT OUTER JOIN tarifa_articulo AS b ON b.articulo = a.id AND b.tarifa = $id 
WHERE 
	b.articulo IS NULL AND a.activo = 'S';";
Execute($sql);

/// Se actualizará el precio de los artículos si está activado el parámetro 046 y si es tarifa patron  ///
$sql = "SELECT valor1 from parametro WHERE codigo = '046';";
if($row = ExecuteRow($sql)) {
	if(trim($row["valor1"]) == "S") {
		$sql = "SELECT patron FROM tarifa WHERE id = $id;";
		if($row = ExecuteRow($sql)) {
			if(trim($row["patron"]) == "S") { 
				// Hago backup de la tabla de tarifas por artículo
				$sql = "TRUNCATE TABLE tarifa_articulo_temp";
				Execute($sql);

				$sql = "INSERT INTO tarifa_articulo_temp
							(id, tarifa, fabricante, articulo, precio)
						SELECT id, tarifa, fabricante, articulo, precio
							FROM tarifa_articulo;";
				Execute($sql);
				// Fin backup 

				// Se procede a calcular el nuevo precio en funcioón a los costos, si el costo es cero no se actualiza el precio 
				$sql = "TRUNCATE TABLE tarifa_temp;";
				Execute($sql);

				$sw = false;
				$sql = "SELECT valor1 from parametro WHERE codigo = '047';";
				if($row = ExecuteRow($sql)) {
					if(trim($row["valor1"]) == "S") $sw = true;
				}

				if($sw) {
					$sql = "SELECT 
							a.articulo, b.codigo, a.fabricante, IFNULL(c.descuento, 0) AS descuento, IFNULL(c.utilidad, 0) AS utilidad, IFNULL(b.ultimo_costo, 0) AS ultimo_costo    
						FROM 
							tarifa_articulo AS a 
							JOIN articulo AS b ON b.id = a.articulo 
							JOIN tarifa_descuento_utilidad AS c ON c.fabricante = b.fabricante AND c.fabricante = a.fabricante 
						WHERE 
							a.tarifa = $id AND RTRIM(LTRIM(IFNULL(b.codigo, ''))) <> '' AND IFNULL(c.utilidad, 0) > 0  -- AND a.articulo = 1731 -- AND b.ultimo_costo > 0  
						ORDER BY descuento DESC;";
				}
				else {
					$sql = "SELECT 
							a.articulo, b.codigo, a.fabricante, IFNULL(c.descuento, 0) AS descuento, IFNULL(c.utilidad, 0) AS utilidad, IFNULL(b.ultimo_costo, 0) AS ultimo_costo    
						FROM 
							tarifa_articulo AS a 
							JOIN articulo AS b ON b.id = a.articulo 
							JOIN fabricante AS c ON c.Id = b.fabricante AND c.Id = a.fabricante 
						WHERE 
							a.tarifa = $id AND RTRIM(LTRIM(IFNULL(b.codigo, ''))) <> '' AND IFNULL(c.utilidad, 0) > 0  -- AND a.articulo = 1731 -- AND b.ultimo_costo > 0  
						ORDER BY codigo;";
				}
				$rows = ExecuteRows($sql);

				foreach ($rows as $key => $value) { 
					$articulo = $value["articulo"];
					$fabricante = $value["fabricante"];
					$descuento = $value["descuento"]; // Desuento lineal 
					$utilidad = $value["utilidad"];
					$ultimo_costo = $value["ultimo_costo"];

					
					/* // Se toma el descuento de la existencia si hay cantidades disponible 
					$sql = "SELECT 
						a.id, 
						IFNULL(a.precio_unidad_sin_desc, IFNULL(a.costo_unidad, 0)) AS ultimo_costo, 
						IFNULL(a.descuento, 0) AS descuento, 
						(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad  
					FROM 
						entradas_salidas AS a 
						JOIN entradas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S' 
						LEFT OUTER JOIN (
								SELECT 
									a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
								FROM 
									entradas_salidas AS a 
									JOIN salidas AS b ON
										b.tipo_documento = a.tipo_documento
										AND b.id = a.id_documento 
									LEFT OUTER JOIN almacen AS c ON
										c.codigo = a.almacen AND c.movimiento = 'S'
								WHERE
									a.tipo_documento IN ('TDCNET','TDCASA') 
									AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$articulo' 
								GROUP BY a.id_compra
							) AS d ON d.id = a.id 
					WHERE
						((a.tipo_documento IN ('TDCNRP') 
						AND b.estatus = 'PROCESADO') OR 
						(a.tipo_documento IN ('TDCNRP') 
						AND b.estatus <> 'ANULADO' AND b.consignacion = 'S'))
						AND a.articulo = '$articulo' 
						AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 AND a.check_ne='N' 
					ORDER BY a.id ASC LIMIT 0, 1;";
					if($row = ExecuteRow($sql)) { 
						$descuento = ($row["descuento"] == 0 ? $descuento : $row["descuento"]); 
						// $ultimo_costo = ($row["ultimo_costo"] == 0 ? $ultimo_costo : ($ultimo_costo < $row["ultimo_costo"] ? $row["ultimo_costo"] : $ultimo_costo)); 
					} 
					else {
						$sql = "SELECT 
									id, 
									IFNULL(precio_unidad_sin_desc, IFNULL(costo_unidad, 0)) AS ultimo_costo, 
									IFNULL(descuento, 0) AS descuento   
								FROM 
									entradas_salidas 
								WHERE 
									tipo_documento IN ('TDCNRP') AND articulo = '$articulo' 
								ORDER BY id DESC;";
						if($row = ExecuteRow($sql)) {
							// $ultimo_costo = ($row["ultimo_costo"] == 0 ? $ultimo_costo : $row["ultimo_costo"]); 
						}
					}
					*/ //

					$costo = $ultimo_costo - ($ultimo_costo*($descuento/100));
					$precio = $costo + ($costo*($utilidad/100));

					$codigo = $value["codigo"]; 
					$nombre = ""; 
					$sql = "INSERT INTO tarifa_temp 
								(codigo, nombre, precio)
							VALUES 
								('$codigo', '$nombre', $precio)";
					Execute($sql);
				}

				/////////

				// Guardo el hitórico de precios 
				$sql = "UPDATE tarifa_anterior SET activo='N' WHERE activo='S';";
				Execute($sql);

				$sql = "DELETE FROM tarifa_anterior WHERE fecha = CURDATE();";
				Execute($sql);

				$sql = "INSERT INTO tarifa_anterior
							(id, fecha, 
							tarifa, fabricante, articulo, 
							codigo, precio_anterior, precio_nuevo, 
							activo) 
						SELECT 
							c.id, CURDATE() AS fecha, 
							c.tarifa, c.fabricante, c.articulo, 
							a.codigo, c.precio AS precio_anterior, a.precio AS precio_nuevo, 
							'S' AS activo 
						FROM 
							tarifa_temp AS a 
							JOIN articulo AS b ON b.codigo = a.codigo 
							JOIN tarifa_articulo AS c ON c.articulo = b.id 
							JOIN tarifa AS d ON d.id = c.tarifa 
						WHERE d.patron = 'S';";
				Execute($sql);

				$sql = "UPDATE 
							tarifa_articulo AS a 
							JOIN tarifa_anterior AS b ON b.id = a.id  
						SET 
							a.precio = b.precio_nuevo 
						WHERE b.activo = 'S';";
				Execute($sql);
				//////////////
				
				$sql = "UPDATE 
							articulo AS a 
							JOIN (SELECT 
									a.articulo, a.precio 
								FROM 
									tarifa_articulo AS a 
									JOIN tarifa AS b ON b.id = a.tarifa 
								WHERE b.patron = 'S') AS b ON b.articulo = a.id 
							SET 
								a.precio = b.precio;";
				Execute($sql);
			}
		}
	}
}
////////////////

header("Location: TarifaList");
exit();
?>

<?= GetDebugMessage() ?>
