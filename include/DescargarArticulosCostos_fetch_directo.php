<?php
session_start();

include "connect.php";

// --- Database Configuration ---
$servername = "localhost"; // Your database host
$username = "$user"; // Your database username
$password = "$password"; // Your database password
$dbname = "$strcon"; // Your database name

// --- Establish Database Connection ---
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- SQL Query (Optimized for MySQL < 8.0) ---
$sql = "SELECT 
            a.codigo, 
            a.nombre_comercial, 
            a.principio_activo, 
            a.presentacion, 
            b.nombre AS fabricante, 
            a.codigo_de_barra, 
            a.cantidad_en_mano, 
            (SELECT precio_unidad_sin_desc 
            FROM entradas_salidas 
            WHERE tipo_documento = 'TDCNRP' AND articulo = a.id AND check_ne = 'S' 
            ORDER BY id DESC LIMIT 1, 1) AS costo_anterior, 
            (SELECT precio_unidad_sin_desc 
            FROM entradas_salidas 
            WHERE tipo_documento = 'TDCNRP' AND articulo = a.id AND check_ne = 'S' 
            ORDER BY id DESC LIMIT 0, 1) AS costo_ultimo, 
            a.ultimo_costo, a.precio 
        FROM 
            articulo AS a 
            JOIN fabricante AS b ON b.Id = a.fabricante 
        WHERE a.activo = 'S' ORDER BY b.nombre, a.principio_activo;";

// --- Execute Query ---
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// --- Set Headers for CSV Download ---
$filename = "reporte_articulos_" . date('Ymd_His') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// --- Open PHP output stream for writing CSV data ---
$output = fopen('php://output', 'w');

// --- Write CSV Headers (Column Names) ---
$column_headers = array();
while ($fieldinfo = $result->fetch_field()) {
    $column_headers[] = $fieldinfo->name;
}
fputcsv($output, $column_headers, ';'); // Using semicolon (;) as delimiter for Excel compatibility

// --- Write Data Rows ---
while ($row = $result->fetch_assoc()) {
    // Ensure numeric values are formatted correctly for Excel (e.g., replace comma with dot for decimals if needed)
    // For most cases, directly writing the values should be fine.
    // If you encounter issues with numbers in Excel, you might need to format them.
    // Example: $row['cantidad_en_mano'] = str_replace('.', ',', $row['cantidad_en_mano']);

    fputcsv($output, $row, ';'); // Write each row to the CSV file
}

// --- Close Resources ---
fclose($output);
$result->free();
$conn->close();

exit(); // Terminate script execution after file download
?>