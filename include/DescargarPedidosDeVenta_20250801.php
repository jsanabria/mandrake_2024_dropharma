<?php
session_start();

$id_pedido = isset($_REQUEST["id_pedido"]) ? $_REQUEST["id_pedido"] : 0;
$id_pedido = 128172;
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

// --- Configurar Cabeceras para Descarga CSV ---
// $filename = $id_pedido . "_pedido_de_venta_" . date('Ymd_His') . ".xls";
$filename = $id_pedido . "_pedido_de_venta.xls";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// --- Abrir el stream de salida de PHP para escribir datos CSV ---
$output = fopen('php://output', 'w');

// --- Consulta SQL (Optimizada para MySQL < 8.0) ---
// Esta versión usa LEFT JOINs con subconsultas en la cláusula ON para mejor rendimiento.
$sql = "SELECT 
            DATE_FORMAT(a.fecha, '%d/%m/%Y') AS FECHA, a.nro_documento AS NO_PEDIDO, b.ci_rif AS CLIENTE_RIF, b.nombre AS CLIENTE_NOMBRE 
        FROM 
            salidas AS a 
            JOIN cliente AS b ON b.id = a.cliente 
        WHERE a.id = $id_pedido AND a.tipo_documento = 'TDCPDV'; ";

// --- Ejecutar Consulta ---
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Estructura el encabezado de la factura // 
if ($row = $result->fetch_assoc()) { 
    $column_headers = array();
    $column_headers[] = "CLIENTE RIF";
    $column_headers[] = $row["CLIENTE_RIF"];
    $column_headers[] = "";
    $column_headers[] = "FECHA";
    $column_headers[] = $row["FECHA"];
    fputcsv($output, $column_headers, ';'); 
    unset($column_headers);

    $column_headers = array();
    $column_headers[] = "CLIENTE NOMBRE";
    $column_headers[] = $row["CLIENTE_NOMBRE"];
    $column_headers[] = "";
    $column_headers[] = "NO PEDIDO";
    $column_headers[] = $row["NO_PEDIDO"];
    fputcsv($output, $column_headers, ';'); 
    unset($column_headers);
} 
else {

}
$result->free();

// Preparo el detalle del pedido //
$sql = "SELECT 
            b.codigo AS COD_ART, 
            CONCAT(IFNULL(b.nombre_comercial, ''), IFNULL(b.principio_activo, ''), IFNULL(b.presentacion, '')) AS DESCRIPCION, 
            CAST(ABS(a.cantidad_movimiento) AS UNSIGNED) AS CANT, a.precio_unidad AS P_UNT_$, a.precio AS P_TOTAL_$  
        FROM 
            entradas_salidas AS a 
            JOIN articulo AS b ON b.id = a.articulo  
        WHERE a.id_documento = $id_pedido AND a.tipo_documento = 'TDCPDV';";

// --- Ejecutar Consulta ---
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
// --- Escribir Cabeceras CSV (Nombres de Columnas) ---
$column_headers = array();
while ($fieldinfo = $result->fetch_field()) {
    $column_headers[] = $fieldinfo->name;
}
fputcsv($output, $column_headers, ';'); // Usando punto y coma (;) como delimitador

// --- Escribir Filas de Datos ---
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row, ';'); // Escribir cada fila (incluyendo la nueva columna) al archivo CSV
}

// --- Cerrar Recursos ---
fclose($output);
$result->free();
$conn->close();

exit(); // Terminar la ejecución del script después de la descarga del archivo

?>