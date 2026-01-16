<?php
session_start();

// Incluir el autoloader de Composer para cargar las clases de PhpSpreadsheet
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment; // Para centrar texto si es necesario

$id_pedido = isset($_REQUEST["id_pedido"]) ? $_REQUEST["id_pedido"] : 0;
$nro_documento = "";  

// Se asume que 'connect.php' define $user, $password, $strcon
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

// --- Crear un nuevo objeto Spreadsheet ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Pedido de Venta'); // Establecer el título de la hoja

// --- Estilos básicos para las cabeceras de la factura (opcional) ---
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
];
$sheet->getStyle('A:E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

// --- Consulta SQL para el encabezado de la factura ---
$sql_header = "SELECT 
                    a.cliente AS COD_CLIENTE, 
                    a.nro_documento, 
                    DATE_FORMAT(a.fecha, '%d/%m/%Y') AS FECHA, a.nro_documento AS NO_PEDIDO, b.ci_rif AS CLIENTE_RIF, b.nombre AS CLIENTE_NOMBRE 
                FROM 
                    salidas AS a 
                    JOIN cliente AS b ON b.id = a.cliente 
                WHERE a.id = $id_pedido AND a.tipo_documento = 'TDCPDV'; ";

$result_header = $conn->query($sql_header);

if (!$result_header) {
    die("Query failed for header: " . $conn->error);
}

$current_row = 1; // Fila inicial para escribir en la hoja de Excel

// Estructura el encabezado de la factura en el archivo Excel
if ($row_header = $result_header->fetch_assoc()) {
    // Fila 1: CLIENTE RIF y FECHA
    $nro_documento = $row_header["nro_documento"]; 
    $sheet->setCellValue('A' . $current_row, "CLIENTE RIF");
    $sheet->setCellValue('B' . $current_row, $row_header["CLIENTE_RIF"]);
    $sheet->setCellValue('C' . $current_row, $row_header["COD_CLIENTE"]);
    $sheet->setCellValue('D' . $current_row, "FECHA");
    $sheet->setCellValue('E' . $current_row, $row_header["FECHA"]);
    $sheet->getStyle('A' . $current_row . ':E' . $current_row)->applyFromArray($headerStyle);
    $current_row++;

    // Fila 2: CLIENTE NOMBRE y NO PEDIDO
    $sheet->setCellValue('A' . $current_row, "CLIENTE NOMBRE");
    $sheet->setCellValue('B' . $current_row, $row_header["CLIENTE_NOMBRE"]);
    $sheet->setCellValue('D' . $current_row, "NO PEDIDO");
    $sheet->setCellValue('E' . $current_row, $row_header["NO_PEDIDO"]);
    $sheet->getStyle('A' . $current_row . ':E' . $current_row)->applyFromArray($headerStyle);
    $current_row += 1; // Dejar una fila en blanco antes del detalle

    // Fila 2: CLIENTE NOMBRE y NO PEDIDO
    $sheet->setCellValue('A' . $current_row, "DEPOSITO");
    $sheet->setCellValue('B' . $current_row, "01");
    $sheet->setCellValue('D' . $current_row, "");
    $sheet->setCellValue('E' . $current_row, "");
    $sheet->getStyle('A' . $current_row . ':E' . $current_row)->applyFromArray($headerStyle);
    $current_row += 1; // Dejar una fila en blanco antes del detalle
} 
$result_header->free();

// --- Preparo el detalle del pedido ---
$sql_detail = "SELECT 
                    CAST(IF(LTRIM(IFNULL(b.codigo_de_barra, ''))='', b.codigo, b.codigo_de_barra) AS CHAR) AS COD_ART,  
                    CONCAT(IFNULL(b.nombre_comercial, ''), IFNULL(b.principio_activo, ''), IFNULL(b.presentacion, '')) AS DESCRIPCION, 
                    CAST(ABS(a.cantidad_movimiento) AS UNSIGNED) AS CANT, a.precio_unidad AS P_UNT_$, a.precio AS P_TOTAL_$  
                FROM 
                    entradas_salidas AS a 
                    JOIN articulo AS b ON b.id = a.articulo  
                WHERE a.id_documento = $id_pedido AND a.tipo_documento = 'TDCPDV';";

$result_detail = $conn->query($sql_detail);

if (!$result_detail) {
    die("Query failed for detail: " . $conn->error);
}

// --- Escribir Cabeceras de las Columnas del Detalle ---
$column_headers_detail = [];
while ($fieldinfo = $result_detail->fetch_field()) {
    $column_headers_detail[] = $fieldinfo->name;
}
$sheet->fromArray($column_headers_detail, NULL, 'A' . $current_row);
$sheet->getStyle('A' . $current_row . ':' . $sheet->getHighestColumn() . $current_row)->applyFromArray($headerStyle);
$current_row++; // Mover a la siguiente fila para los datos

// --- Escribir Filas de Datos del Detalle ---
$total = 0.00;
if ($result_detail->num_rows > 0) {
    while ($row_detail = $result_detail->fetch_assoc()) {
        $sheet->fromArray($row_detail, NULL, 'A' . $current_row);
        $current_row++;
        $total += floatval($row_detail["P_TOTAL_$"]);
    }
}

// --- Escribo el total del pedido --//  
$sheet->setCellValue('D' . $current_row, "TOTAL:");
$sheet->setCellValue('E' . $current_row, $total);
// --- Ajustar el ancho de las columnas automáticamente (opcional) ---
foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// --- Configurar Cabeceras para Descarga de Archivo .xlsx ---
$filename = $nro_documento . "_pedido_de_venta.xls"; // Cambiado a .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
header('Cache-Control: max-age=0'); // Necesario para IE, etc.

// --- Crear el escritor y guardar el archivo directamente en la salida del navegador ---
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// --- Cerrar Recursos ---
$result_detail->free();

/* Pongo el pedido en procesado */
$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = $id_pedido AND tipo_documento = 'TDCPDV';";
// $conn->query($sql);

$conn->close();

exit(); // Terminar la ejecución del script
?>