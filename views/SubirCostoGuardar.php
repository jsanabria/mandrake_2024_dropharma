<?php

namespace PHPMaker2024\mandrake;

// Page object
$SubirCostoGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
Execute("SET NAMES 'utf8';");
Execute("SET CHARACTER SET utf8;");

if (!isset($_FILES['uploadedFile']) or !$_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK or trim($_FILES['uploadedFile']['name']) == "") {
	$_SESSION['error'] = "Debe seleccionar un archivo. " . $_FILES['uploadedFile']['error'];
	header("Location: SubirCosto");
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
	header("Location: SubirCosto");
	die();
}

// directory in which the uploaded file will be moved
$uploadFileDir = 'carpetacarga/';
$dest_path = $uploadFileDir . $newFileName;

if(move_uploaded_file($fileTmpPath, $dest_path)) {
	$_SESSION['message'] = 'El archivo subio exitosamente.';

	$sql = "TRUNCATE TABLE costo_temp;";
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

			$sql = "INSERT INTO costo_temp
						(codigo, nombre, costo)
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
	$sql = "TRUNCATE TABLE articulo_anterior;";
	Execute($sql);

	$sql = "INSERT INTO articulo_anterior
				(fabricante, articulo, 
				codigo, costo_anterior,
				costo_nuevo) 
			SELECT 
				b.fabricante, b.id AS articulo, 
				a.codigo, b.ultimo_costo AS costo_anterior,
				a.costo AS costo_nuevo 
			FROM 
				costo_temp AS a 
				JOIN articulo AS b ON b.codigo = a.codigo;";
	Execute($sql);

	$sql = "UPDATE 
				articulo AS a 
				JOIN articulo_anterior AS b ON b.codigo = a.codigo 
			SET 
				a.ultimo_costo = b.costo_nuevo;";
	Execute($sql);
}
else {
  $_SESSION['error'] = 'Hay alg&uacute;n error en la copia del archivo al directorio de carga.';
}

header("Location: SubirCosto");

die();
?>

<?= GetDebugMessage() ?>
