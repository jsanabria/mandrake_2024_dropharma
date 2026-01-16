<?php

namespace PHPMaker2024\mandrake;

// Page object
$ActualizarTarifaPatron = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];
$patron = $_REQUEST["patron"];

//$sql = "SELECT valor1 FROM parametro WHERE codigo = '005';";
$sql = "SELECT porcentaje FROM tarifa WHERE id = $id;";
$porc = floatval(ExecuteScalar($sql));

$swAplicaTarifaPatron = false;


/// ACTUALIZAR TARIFA EN BASE A COSTOS FABRICANTE; sólo para las tarifas no patron  ///
$sql = "SELECT valor1 from parametro WHERE codigo = '046';";
if(!$row = ExecuteRow($sql)) $swAplicaTarifaPatron = true;
else {
	if(trim($row["valor1"]) != "S") $swAplicaTarifaPatron = true; 
	else {
		$sql = "SELECT patron FROM tarifa WHERE id = $id;";
		if($row = ExecuteRow($sql)) {
			if(trim($row["patron"]) == "S") $swAplicaTarifaPatron = true;
			else { 
				// Hago backup de la tabla de tarifas por artículo
				$sql = "TRUNCATE TABLE tarifa_articulo_temp";
				Execute($sql);

				$sql = "INSERT INTO tarifa_articulo_temp
							(id, tarifa, fabricante, articulo, precio)
						SELECT id, tarifa, fabricante, articulo, precio
							FROM tarifa_articulo WHERE tarifa = $id;";
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
							JOIN tarifa_descuento_utilidad AS c ON c.fabricante = b.fabricante AND c.fabricante = a.fabricante AND c.tarifa = a.tarifa 
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
					Si se aplica copiar el código de las sincronización */

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
				$sql = "TRUNCATE TABLE tarifa_anterior;";
				Execute($sql);

				$sql = "INSERT INTO tarifa_anterior
							(id, fecha, 
							tarifa, fabricante, articulo, 
							codigo, precio_anterior, precio_nuevo, 
							activo) 
						SELECT 
							c.id, CURDATE() AS fecha, 
							c.tarifa, c.fabricante, c.articulo, 
							a.codigo, c.precio AS precio_anterior, ROUND((a.precio + (a.precio * ($porc/100))), 2) AS precio_nuevo, 
							'S' AS activo 
						FROM 
							tarifa_temp AS a 
							JOIN articulo AS b ON b.codigo = a.codigo 
							JOIN tarifa_articulo AS c ON c.articulo = b.id 
							JOIN tarifa AS d ON d.id = c.tarifa 
						WHERE d.id = $id;";
				Execute($sql);

				$sql = "UPDATE 
							tarifa_articulo AS a 
							JOIN tarifa_anterior AS b ON b.id = a.id  
						SET 
							a.precio = b.precio_nuevo 
						WHERE a.tarifa = $id AND b.activo = 'S';";
				Execute($sql);
				//////////////
				
				$swAplicaTarifaPatron = false;
			}
		}
	}
}
////////////////

if($swAplicaTarifaPatron) {
	$sql = "UPDATE 
			tarifa_articulo AS a 
				JOIN 
			(SELECT fabricante, articulo, precio FROM tarifa_articulo
			WHERE tarifa = $patron) AS b 
				ON b.fabricante = a.fabricante AND b.articulo = a.articulo 
		SET 
			a.precio = ROUND((b.precio + (b.precio * ($porc/100))), 2) 
		WHERE a.tarifa = $id;";
	Execute($sql);
}


header("Location: TarifaList");
exit();
?>

<?= GetDebugMessage() ?>
