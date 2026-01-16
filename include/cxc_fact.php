<?php
// Incluir el archivo de configuración de PHPMaker para acceder a las funciones de DB
// require "../vendor/autoload.php"; // Ajusta la ruta si es necesario
require __DIR__ . "/../vendor/autoload.php";

use PHPMaker2024\mandrake\Config; // Asegúrate de usar el namespace correcto

// 1. Validación y Obtención de Parámetros
$fecha_desde = $_GET["fecha_desde"];
$fecha_hasta = $_GET["fecha_hasta"];
$tipo_documento = $_GET["tipo_documento"];

if ($tipo_documento != 'TDCFCV' || empty($fecha_desde) || empty($fecha_hasta)) {
    // Si falta algo, simplemente terminamos
    die("Error: Parámetros de consulta incompletos.");
}

// Se asume que 'connect.php' define $user, $password, $strcon
include "connect.php"; 

// 2. Preparar fechas y query (usando funciones de PHPMaker)
$date_obj_desde = DateTime::createFromFormat('d/m/Y', $fecha_desde);
$date_obj_hasta = DateTime::createFromFormat('d/m/Y', $fecha_hasta);

// 1. Validación y Obtención de Parámetros
// Usamos Get() de PHPMaker o nativa de PHP $_GET
$fecha_desde = $_GET["fecha_desde"] ?? Get("fecha_desde");
$fecha_hasta = $_GET["fecha_hasta"] ?? Get("fecha_hasta");
$tipo_documento = $_GET["tipo_documento"] ?? Get("tipo_documento");

if ($tipo_documento != 'TDCFCV' || empty($fecha_desde) || empty($fecha_hasta)) {
    die("Error: Parámetros de consulta incompletos.");
}

// 2. Convertir las fechas manualmente (d/m/Y a YYYY-MM-DD)
$date_obj_desde = DateTime::createFromFormat('d/m/Y', $fecha_desde);
$date_obj_hasta = DateTime::createFromFormat('d/m/Y', $fecha_hasta);

if ($date_obj_desde === false || $date_obj_hasta === false) {
    die("Error de formato de fecha. Asegúrese de que el formato sea DD/MM/AAAA.");
}

// 🚨 Reemplazo de AdjustSql(): Usamos mysqli_real_escape_string para seguridad.
$tipo_documento_safe = mysqli_real_escape_string($link, $tipo_documento);
$fecha_desde_db_safe = mysqli_real_escape_string($link, $date_obj_desde->format('Y-m-d'));
$fecha_hasta_db_safe = mysqli_real_escape_string($link, $date_obj_hasta->format('Y-m-d'));


// 3. Preparar query
$sql = "SELECT 
    a.cliente AS CODIGO_CLIENTE, 
    DATE_FORMAT(a.fecha, '%d/%m/%Y') AS FECHAEMISION, 
    DATE_ADD(a.fecha, INTERVAL a.dias_credito DAY) AS F_VENCIM, 
    a.nro_documento AS NoFact, 
    a.nro_control AS NoControl, 
    a.total AS MONTODOCUMENTO_BS, 
    a.monto_total AS BIMP, 
    a.iva AS IVA, 
    a.monto_usd AS MONTO_EN_$, 
    a.tasa_dia AS FACTOR_DE_CAMBIO  
FROM 
    salidas AS a  
WHERE 
    a.tipo_documento = '" . $tipo_documento_safe . "' AND a.estatus = 'PROCESADO' AND 
    a.fecha BETWEEN '" . $fecha_desde_db_safe . "' AND '" . $fecha_hasta_db_safe . " 23:59:59' 
ORDER BY a.nro_control"; 

// 4. 🚨 Reemplazo de ExecuteRows(): Ejecución con MySQLi estándar.
$rs = mysqli_query($link, $sql);

if (!$rs) {
    die("Error al ejecutar la consulta: " . mysqli_error($link));
}

$results = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $results[] = $row;
}
mysqli_free_result($rs);


// 5. Preparación de la Descarga CSV (Excel compatible)
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte_cxc_' . date('Ymd') . '.csv"');

$output = fopen('php://output', 'w');
fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Cabeceras
$headers = [
    'CODIGO_CLIENTE', 'FECHAEMISION', 'F_VENCIM', 'NoFact', 'NoControl', 
    'MONTODOCUMENTO_BS', 'BIMP', 'IVA', 'MONTO_EN_$', 'FACTOR_DE_CAMBIO'
];
fputcsv($output, $headers, ';'); 

// Escribir los datos
if (!empty($results)) {
    foreach ($results as $row) {
        fputcsv($output, $row, ';'); 
    }
}

fclose($output);
mysqli_close($link);
exit();
?>