<?php
session_start();

include "connect.php";

// --- Database Configuration ---
$servername = "localhost"; // Your database host
$username = "$user"; // Your database username
$password = "$password"; // Your database password
$dbname = "$strcon"; // Your database name

// --- Establecer conexión a la base de datos ---
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Consulta SQL (Optimizada para MySQL < 8.0) ---
// Esta versión usa LEFT JOINs con subconsultas en la cláusula ON para mejor rendimiento.
$sql = "SELECT 
            a.id, 
            a.codigo, 
            a.nombre_comercial, 
            a.principio_activo, 
            a.presentacion, 
            b.nombre AS fabricante, 
            a.codigo_de_barra, 
            a.cantidad_en_mano, 
            -- a.precio, 
            (SELECT precio_unidad_sin_desc 
            FROM entradas_salidas 
            WHERE tipo_documento = 'TDCNRP' AND articulo = a.id AND check_ne = 'S' AND IFNULL(precio_unidad_sin_desc, 0) > 0 
            ORDER BY id DESC LIMIT 1, 1) AS costo_anterior, 
            (SELECT precio_unidad_sin_desc 
            FROM entradas_salidas 
            WHERE tipo_documento = 'TDCNRP' AND articulo = a.id AND check_ne = 'S' AND IFNULL(precio_unidad_sin_desc, 0) > 0 
            ORDER BY id DESC LIMIT 0, 1) AS costo_actual, 
            a.ultimo_costo AS promedio_ponderado  
        FROM 
            articulo AS a 
            JOIN fabricante AS b ON b.Id = a.fabricante 
        WHERE a.activo = 'S' ORDER BY b.nombre, a.principio_activo;";

// --- Ejecutar Consulta ---
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// --- Configurar Cabeceras para Descarga CSV ---
$filename = "reporte_articulos_costo_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// --- Abrir el stream de salida de PHP para escribir datos CSV ---
$output = fopen('php://output', 'w');

// --- Escribir Cabeceras CSV (Nombres de Columnas) ---
$column_headers = array();
while ($fieldinfo = $result->fetch_field()) {
    $column_headers[] = $fieldinfo->name;
}
// Añadir el nombre de la nueva columna calculada
$column_headers[] = "precio";
$column_headers[] = "ult_recep";
fputcsv($output, $column_headers, ';'); // Usando punto y coma (;) como delimitador

// --- Escribir Filas de Datos ---
while ($row = $result->fetch_assoc()) {
    $articulo = $row['id'];
    $precio = 0.00;
    $fecha_recepcion = "";
    $sql = "SELECT precio FROM tarifa_articulo WHERE tarifa = 2 AND articulo = ? LIMIT 0, 1;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $articulo);
    $stmt->execute();
    $stmt->bind_result($precio);
    $stmt->fetch();
    $stmt->close();
    $row['precio_tarifa'] = number_format($precio, 2, '.', '');

    $sql = "SELECT 
                DATE_FORMAT(b.fecha, '%d/%m/%Y') AS fecha_recepcion 
            FROM 
                entradas_salidas AS a 
                JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
            WHERE a.articulo = ? AND a.tipo_documento IN ('TDCNRP') 
            ORDER BY a.id DESC LIMIT 0, 1;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $articulo);
    $stmt->execute();
    $stmt->bind_result($fecha_recepcion);
    $stmt->fetch();
    $stmt->close();
    $row['fecha_recepcion'] = $fecha_recepcion;

    if((float)$row['costo_anterior'] <= 0) $row['costo_anterior'] = (float)$row['costo_actual'];

    // Si necesitas procesar otros campos, hazlo aquí.
    // Ejemplo: $row['cantidad_en_mano'] = (int)$row['cantidad_en_mano'];

    fputcsv($output, $row, ';'); // Escribir cada fila (incluyendo la nueva columna) al archivo CSV
}

// --- Cerrar Recursos ---
fclose($output);
$result->free();
$conn->close();

exit(); // Terminar la ejecución del script después de la descarga del archivo

?>