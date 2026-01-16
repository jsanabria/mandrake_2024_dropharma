<?php 
	include 'connect.php';

	$sql = "SELECT 
				id AS CODIGO, nombre AS NOMBRE, ci_rif AS RIF, direccion AS DIRECCION, telefono1 AS TELEFONO, 
				0 AS DIAS_CREDITO, IF(IFNULL(tipo_iva, 0) = 0, 'N', 'S') AS CONTRIBUYENTE_ESPECIAL 
			FROM proveedor WHERE 1;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
	    $newRow = array(
	        'CODIGO' => htmlspecialchars(str_replace(["\r", "\n"], "", $row['CODIGO'] ?? '')),
	        'NOMBRE' => htmlspecialchars(eliminarCaracteresEspeciales(str_replace(["\r", "\n"], "", $row['NOMBRE'] ?? ''))),
	        'RIF' => trim(htmlspecialchars(str_replace(["\r", "\n"], "", $row['RIF'] ?? ''))),
	        'DIRECCION' => htmlspecialchars(eliminarCaracteresEspeciales(str_replace(["\r", "\n"], "", $row['DIRECCION'] ?? ''))),
	        'TELEFONO' => htmlspecialchars(str_replace(["\r", "\n"], "", $row['TELEFONO'] ?? '')),
	        'DIAS_CREDITO' => htmlspecialchars(str_replace(["\r", "\n"], "", $row['DIAS_CREDITO'] ?? '')),
	        'CONTRIBUYENTE_ESPECIAL' => htmlspecialchars(str_replace(["\r", "\n"], "", $row['CONTRIBUYENTE_ESPECIAL'] ?? ''))
	    );

	    // Agregamos la nueva fila al array principal
		$developer_records[] = $newRow;
	}

	$filename = "PROVEEDORES_" . date('Ymd') . ".xls";

    // Generar las cabeceras para la descarga del archivo Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Escribir los encabezados de las columnas en el archivo
    $flag = false;
    foreach($developer_records as $row) {
        if(!$flag) {
            // mostrar encabezados
            echo implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        // mostrar datos de las filas
        echo implode("\t", array_values($row)) . "\n";
    }

function eliminarCaracteresEspeciales($cadena) {
    // Reemplaza todo lo que NO sea letra (a-z, A-Z), número (0-9), guion bajo (_), o espacio/guion (-)
    // El 's' al final asegura que el punto '.' coincida con saltos de línea si es necesario.
    $resultado = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $cadena);
    return $resultado;
}
?>
