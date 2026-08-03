<?php
session_start();

include 'include/connect.php';

$id          = isset($_GET["id"]) ? $_GET["id"] : "";
$fecha_desde = isset($_REQUEST["fd"]) ? $_REQUEST["fd"] : "";
$fecha_hasta = isset($_REQUEST["fh"]) ? $_REQUEST["fh"] : "";

$tipo        = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "";
$articulo    = isset($_REQUEST["articulo"]) ? $_REQUEST["articulo"] : "";
$cliente     = isset($_REQUEST["cliente"]) ? $_REQUEST["cliente"] : "";
$asesor      = isset($_REQUEST["asesor"]) ? $_REQUEST["asesor"] : "";

$where = "";
$excel = true;

// Variable de respaldo por si el include no define un nombre de archivo dinámico
$filename = "reporte_" . htmlspecialchars($id) . "_" . date('Ymd_His') . ".xls";

// Matriz de datos que llenarán los scripts incluidos
$developer_records = []; 

switch($id) {
    case "ims_clientes":
        include("include/clientes_ims_excel.php");
        break;
    case "ims_articulos":
        include("include/articulos_ims_excel.php");
        break;
    case "ims_facturas":
        include("include/facturas_ims_excel.php");
        break;
    case "tax_libro_compra":
        include("include/libro_de_compra_excel.php");
        break;
    case "tax_libro_venta":
        include("include/libro_de_ventas_excel.php");
        break;
    case "vta_laboratorio":
        include("include/ventas_por_laboratorio_rp_excel.php");
        break;
    case "vta_articulo":
        include("include/ventas_por_articulo_excel.php");
        break;
    case "vta_articulo_utilidad":
        include("include/ventas_por_articulo_utilidad_excel.php");
        break;
    case "sal_laboratorio":
        include("include/salidas_genreales_por_laboratorio_excel.php");
        break;
    case "sal_articulo":
        include("include/salidas_genreales_por_articulo_excel.php");
        break;
    case "sal_articulo_detallado":
        include("include/salidas_genreales_por_articulo_detallado_excel.php");
        break;
    case "cli_compras_recientes":
        include("include/clientes_con_compras_recientes_excel.php");
        break;
    case "cli_sin_compras":
        include("include/clientes_sin_compras_recientes_excel.php");
        break;
    case "aud_costo_vs_precio":
        include("include/factura_costo_vs_precio_excel.php");
        break;
    case "inv_kardex":
        include("include/kardex_de_inventario_excel.php");
        break;
    case "fin_resumen":
        include("include/financiero_resumen_excel.php");
        break;
    case "inv_entre_fechas":
        include("include/inventario_entre_fecha_excel.php");
        break;
    case "cng_cliente":
        include("include/consignacion_por_cliente_excel.php");
        break;
    case "cng_facturas":
        include("include/facturas_por_consignacion_excel.php");
        break;
    case "vta_cliente":
        include("include/ventas_por_cliente_excel.php");
        break;
    case "cng_descarga_entradas":
        include("include/descarga_entradas_consignacion_excel.php");
        break;
    case "det_entradas_general":
        include("include/entradas_genreales_por_articulo_detallado_excel.php");
        break;
    case "det_notas_entrega":
        include("include/notas_de_entrega_detallado_excel.php");
        break;
    case "det_pedidos_venta":
        include("include/pedidos_de_ventas_detallado_excel.php");
        break;
    case "sal_articulo_neas":
        include("include/salidas_genreales_por_articulo_neas_excel.php");
        break;
    case "otros":
        break;
    default:
        die("El reporte solicitado no existe.");
}

// Configuración dinámica de cabeceras según el tipo de salida solicitado
if ($excel) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
} else {
    header("Content-Type: text/html; charset=utf-8");
}
header("Content-Disposition: attachment; filename=" . $filename);

// Renderizado y formateo de la matriz de registros
if (!empty($developer_records) && is_array($developer_records)) {
    if ($excel) {
        $show_column = false;
        foreach ($developer_records as $record) {
            if (!$show_column) {
                // Cabeceras del Excel usando las llaves asociativas del primer registro
                echo implode("\t", array_keys($record)) . "\n";
                $show_column = true;
            }
            // Saneamos los datos para que saltos de línea internos no rompan las celdas de Excel
            $clean_values = array_map(function($value) {
                return preg_replace("/\r|\n/", " ", $value ?? '');
            }, array_values($record));

            echo implode("\t", $clean_values) . "\n";
        }
    } else {
        $show_column = true;
        foreach ($developer_records as $record) {
            if (!$show_column) {
                echo implode("", array_keys($record)) . "\n";
                $show_column = true;
            }
            echo implode("", array_values($record)) . "\n";
        }
    }
}

exit;
?>
