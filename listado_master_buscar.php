<?php
session_start();

include 'include/connect.php';
$id = $_GET["id"];

$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "";

$cliente = isset($_REQUEST["cliente"]) ? $_REQUEST["cliente"] : "";
$asesor = isset($_REQUEST["asesor"]) ? $_REQUEST["asesor"] : "";

$out = '';

$proveedor = intval($_REQUEST["proveedor"]);

/*$f = explode("-", $fecha_desde);
$fecdesde = $f["1"] . "-" . $f["2"] . "-" . $f["0"];
$f = explode("-", $fecha_hasta);
$fechasta = $f["1"] . "-" . $f["2"] . "-" . $f["0"];*/

$where = "";
switch($id) {
case "CLIENTES IMS":
	include("include/clientes_ims.php");
	break;
case "ARTICULOS IMS":
	include("include/articulos_ims.php");
	break;
case "FACTURAS IMS":
	include("include/facturas_ims.php");
	break;
case "LIBRO COMPRA":
	include("include/libro_de_compra.php");
	break;
case "LIBRO VENTA":
	include("include/libro_de_ventas.php");
	break;
case "VENTAS POR LABORATORIO":
	include("include/ventas_por_laboratorio_rp.php");
	break;
case "VENTAS POR ARTICULO":
	include("include/ventas_por_articulo.php");
	break;
case "VENTAS POR ARTICULO UTILIDAD":
	include("include/ventas_por_articulo_utilidad.php");
	break;
case "SALIDAS GENERALES POR LABORATORIO":
	include("include/salidas_genreales_por_laboratorio.php");
	break;
case "SALIDAS GENERALES POR ARTICULO":
	include("include/salidas_genreales_por_articulo.php");
	break;
case "SALIDAS GENERALES POR ARTICULO DETALLADO":
	include("include/salidas_genreales_por_articulo_detallado.php");
	break;
case "CLIENTES CON COMPRAS RECIENTES":
	include("include/clientes_con_compras_recientes.php");
	break;
case "CLIENTES SIN COMPRAS RECIENTES":
	include("include/clientes_sin_compras_recientes.php");
	break;
case "FACTURAS COSTO VS PRECIO":
	include("include/factura_costo_vs_precio.php");
	break;
case "KARDEX DE INVENTARIO":
	include("include/kardex_de_inventario.php");
	break;
case "INVENTARIO ENTRE FECHA":
	include("include/inventario_entre_fecha.php");
	break;
case "CONSIGNACIONES POR CLIENTE":
	include("include/consignacion_por_cliente.php");
	break;
case "FACTURAS POR CONSIGNACION":
	include("include/facturas_por_consignacion.php");
	break;
case "VENTAS POR CLIENTE":
	include("include/ventas_por_cliente.php");
	break;
case "DESCARGA ENTRADAS A CONSIGNACION":
	include("include/descarga_entradas_consignacion.php");
	break;
case "ENTRADAS GENERALES POR ARTICULO DETALLADO":
	include("include/entradas_genreales_por_articulo_detallado.php");
	break;
case "NOTAS DE ENTREGA DETALLADO":
	include("include/notas_de_entrega_detallado.php");
	break;
case "PEDIDOS DE VENTAS DETALLADO":
	include("include/pedidos_de_ventas_detallado.php");
	break;
case "otros": // Para configurarlo más adelante, por los momentos funcionara el primero
	break;
default:
	die("El reporte no existet...");
}

$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '&cliente= ' . $cliente . '&asesor=' . $asesor . '\'">Exportar a TXT/XLS</button>';

echo $out;

?>
