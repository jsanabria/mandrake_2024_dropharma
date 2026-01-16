<?php
session_start();

include 'include/connect.php';
$id = $_GET["id"];
$fecha_desde = $_REQUEST["fd"];
$fecha_hasta = $_REQUEST["fh"];

$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "";

$cliente = isset($_REQUEST["cliente"]) ? $_REQUEST["cliente"] : "";
$asesor = isset($_REQUEST["asesor"]) ? $_REQUEST["asesor"] : "";

$where = "";
$excel = true;
switch($id) {
case "CLIENTES IMS":
	include("include/clientes_ims_excel.php");
	break;
case "ARTICULOS IMS":
	include("include/articulos_ims_excel.php");
	break;
case "FACTURAS IMS":
	include("include/facturas_ims_excel.php");
	break;
case "LIBRO COMPRA":
	include("include/libro_de_compra_excel.php");
	break;
case "LIBRO VENTA":
	include("include/libro_de_ventas_excel.php");
	break;
case "VENTAS POR LABORATORIO":
	include("include/ventas_por_laboratorio_rp_excel.php");
	break;
case "VENTAS POR ARTICULO":
	include("include/ventas_por_articulo_excel.php");
	break;
case "VENTAS POR ARTICULO UTILIDAD":
	include("include/ventas_por_articulo_utilidad_excel.php");
	break;
case "SALIDAS GENERALES POR LABORATORIO":
	include("include/salidas_genreales_por_laboratorio_excel.php");
	break;
case "SALIDAS GENERALES POR ARTICULO":
	include("include/salidas_genreales_por_articulo_excel.php");
	break;
case "SALIDAS GENERALES POR ARTICULO DETALLADO":
	include("include/salidas_genreales_por_articulo_detallado_excel.php");
	break;
case "CLIENTES CON COMPRAS RECIENTES":
	include("include/clientes_con_compras_recientes_excel.php");
	break;
case "CLIENTES SIN COMPRAS RECIENTES":
	include("include/clientes_sin_compras_recientes_excel.php");
	break;
case "FACTURAS COSTO VS PRECIO":
	include("include/factura_costo_vs_precio_excel.php");
	break;
case "KARDEX DE INVENTARIO":
	include("include/kardex_de_inventario_excel.php");
	break;
case "INVENTARIO ENTRE FECHA":
	include("include/inventario_entre_fecha_excel.php");
	break;
case "CONSIGNACIONES POR CLIENTE":
	include("include/consignacion_por_cliente_excel.php");
	break;
case "FACTURAS POR CONSIGNACION":
	include("include/facturas_por_consignacion_excel.php");
	break;
case "VENTAS POR CLIENTE":
	include("include/ventas_por_cliente_excel.php");
	break;
case "DESCARGA ENTRADAS A CONSIGNACION":
	include("include/descarga_entradas_consignacion_excel.php");
	break;
case "ENTRADAS GENERALES POR ARTICULO DETALLADO":
	include("include/entradas_genreales_por_articulo_detallado_excel.php");
	break;
case "NOTAS DE ENTREGA DETALLADO":
	include("include/notas_de_entrega_detallado_excel.php");
	break;
case "PEDIDOS DE VENTAS DETALLADO":
	include("include/pedidos_de_ventas_detallado_excel.php");
	break;
case "otros": // Para configurarlo más adelante, por los momnetos funcionara el primero
	break;
default:
	die("Report does not exist...");
}

if($excel) {
	header("Content-Type: application/vnd.ms-excel");}
else {
	header("Content-Type: text/html; charset=utf-8");
}
header("Content-Disposition: attachment; filename=$filename");

if($excel) {
	$show_coloumn = false;
	foreach($developer_records as $record) {
		if(!$show_coloumn) {
			// display field/column names in first row
			echo implode("\t", array_keys($record)) . "\n";
			$show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	}
}
else {
	$show_coloumn = true;
	foreach($developer_records as $record) {
		if(!$show_coloumn) {
			// display field/column names in first row
			echo implode("", array_keys($record)) . "\n";
			$show_coloumn = true;
		}
		echo implode("", array_values($record)) . "\n";
	}
}


exit;

?>
