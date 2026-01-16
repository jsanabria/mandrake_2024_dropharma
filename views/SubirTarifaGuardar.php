<?php

namespace PHPMaker2024\mandrake;

// Page object
$SubirTarifaGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
Execute("SET NAMES 'utf8';");
Execute("SET CHARACTER SET utf8;");

if (!isset($_FILES['uploadedFile']) or !$_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK or trim($_FILES['uploadedFile']['name']) == "") {
	$_SESSION['error'] = "Debe seleccionar un archivo. " . $_FILES['uploadedFile']['error'];
	header("Location: SubirTarifa");
	die();
}

// get details of the uploaded file
$fichero = $_FILES["uploadedFile"];
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

$allowedfileExtensions = array('csv');

if (!in_array($fileExtension, $allowedfileExtensions)) {
	$_SESSION['error'] = "El archivo debe ser tipo .csv";
	header("Location: SubirTarifa");
	die();
}

// directory in which the uploaded file will be moved
$uploadFileDir = 'carpetacarga/';
$dest_path = $uploadFileDir . $newFileName;

if(move_uploaded_file($fileTmpPath, $dest_path)) {
	$_SESSION['message'] = 'El archivo subio exitosamente.';

	// Hago backup de la tabla de tarifas por artículo
	$sql = "TRUNCATE TABLE tarifa_articulo_temp";
	Execute($sql);

	$sql = "INSERT INTO tarifa_articulo_temp
				(id, tarifa, fabricante, articulo, precio)
			SELECT id, tarifa, fabricante, articulo, precio
				FROM tarifa_articulo;";
	Execute($sql);
	// Fin backup 

	$sql = "TRUNCATE TABLE tarifa_temp;";
	Execute($sql);

	$linea = 0;
	//Abrimos nuestro archivo
	$archivo = fopen($dest_path, "r");
	//Lo recorremos
	$codigo = "";
	$nombre = "";
	$precio = 0.00;
	while (($datos = fgetcsv($archivo, 0, ";")) == true) {
		$num = count($datos);
		//Recorremos las columnas de esa linea
		if($num == 3) {
			for($columna = 0; $columna < $num; $columna++) {
				switch($columna) {
				case 0:	
					$codigo = $datos[$columna];
					break;
				case 1:	
					$nombre = $datos[$columna];
					break;
				case 2:	
					$precio = floatval($datos[$columna]);
					break;
				}
			}

			$sql = "INSERT INTO tarifa_temp
						(codigo, nombre, precio)
					VALUES 
						('$codigo', '$nombre', $precio)";
			Execute($sql);
		}
		$codigo = "";
		$nombre = "";
		$precio = 0.00;
	}
	//Cerramos el archivo
	fclose($archivo);

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
			// WHERE 1;";
	Execute($sql);

	/* En caso de resturar la tarifa anterior 
	$sql = "UPDATE 
				tarifa_articulo AS a 
				JOIN tarifa_articulo_temp AS b ON b.id = a.id 
			SET 
				a.precio = b.precio; ";
	*/
}
else {
  $_SESSION['error'] = 'Hay alg&uacute;n error en la copia del archivo al directorio de carga.';
}

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

header("Location: SubirTarifa");
exit();
?>

<?= GetDebugMessage() ?>
