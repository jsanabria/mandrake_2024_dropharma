<?php
session_start();

// Suponiendo que connect.php provee funciones seguras o la conexión base
include 'include/connect.php';

$id = isset($_GET["id"]) ? $_GET["id"] : "";

$fecha_desde = isset($_REQUEST["fecha_desde"]) ? $_REQUEST["fecha_desde"] : "";
$fecha_hasta = isset($_REQUEST["fecha_hasta"]) ? $_REQUEST["fecha_hasta"] : "";
$tipo        = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : "";
$articulo    = isset($_REQUEST["articulo"]) ? $_REQUEST["articulo"] : "";
$cliente     = isset($_REQUEST["cliente"]) ? $_REQUEST["cliente"] : "";
$asesor      = isset($_REQUEST["asesor"]) ? $_REQUEST["asesor"] : "";

$proveedor   = isset($_REQUEST["proveedor"]) ? intval($_REQUEST["proveedor"]) : 0;

$out = '';
$where = "";

// Correlación de las nuevas codificaciones limpias con sus respectivos archivos de lógica
switch($id) {
    case "ims_clientes":
        include("include/clientes_ims.php");
        break;
    case "ims_articulos":
        include("include/articulos_ims.php");
        break;
    case "ims_facturas":
        include("include/facturas_ims.php");
        break;
    case "tax_libro_compra":
        include("include/libro_de_compra.php");
        break;
    case "tax_libro_venta":
        include("include/libro_de_ventas.php");
        break;
    case "vta_laboratorio":
        include("include/ventas_por_laboratorio_rp.php");
        break;
    case "vta_articulo":
        include("include/ventas_por_articulo.php");
        break;
    case "vta_articulo_utilidad":
        include("include/ventas_por_articulo_utilidad.php");
        break;
    case "sal_laboratorio":
        include("include/salidas_genreales_por_laboratorio.php");
        break;
    case "sal_articulo":
        include("include/salidas_genreales_por_articulo.php");
        break;
    case "sal_articulo_detallado":
        include("include/salidas_genreales_por_articulo_detallado.php");
        break;
    case "cli_compras_recientes":
        include("include/clientes_con_compras_recientes.php");
        break;
    case "cli_sin_compras":
        include("include/clientes_sin_compras_recientes.php");
        break;
    case "aud_costo_vs_precio":
        include("include/factura_costo_vs_precio.php");
        break;
    case "inv_kardex":
        include("include/kardex_de_inventario.php");
        break;
    case "inv_entre_fechas":
        include("include/inventario_entre_fecha.php");
        break;
    case "cng_cliente":
        include("include/consignacion_por_cliente.php");
        break;
    case "cng_facturas":
        include("include/facturas_por_consignacion.php");
        break;
    case "vta_cliente":
        include("include/ventas_por_cliente.php");
        break;
    case "cng_descarga_entradas":
        include("include/descarga_entradas_consignacion.php");
        break;
    case "det_entradas_general":
        include("include/entradas_genreales_por_articulo_detallado.php");
        break;
    case "det_notas_entrega":
        include("include/notas_de_entrega_detallado.php");
        break;
    case "det_pedidos_venta":
        include("include/pedidos_de_ventas_detallado.php");
        break;
    case "sal_articulo_neas":
        include("include/salidas_genreales_por_articulo_neas.php");
        break;
    default:
        die("<div class='alert alert-danger'>El reporte solicitado no existe o no está codificado correctamente.</div>");
}

// Construcción segura de los query strings para la exportación
$query_params = http_build_query([
    'id'      => $id,
    'fd'      => $fecha_desde,
    'fh'      => $fecha_hasta,
    'tipo'    => $tipo,
    'articulo'    => $articulo,
    'cliente' => trim($cliente),
    'asesor'  => trim($asesor)
]);

// Botón estilizado para la sección superior de la respuesta AJAX con icono moderno
$boton_excel = '
<div class="mb-3 text-end">
    <button type="button" class="btn btn-success shadow-sm px-4" onclick="window.location.href=\'listado_master_buscar_excel.php?' . htmlspecialchars($query_params) . '\'">
        <i class="bi bi-file-earmark-excel me-1"></i> Exportar datos (TXT / XLS)
    </button>
</div>';

// Concatenamos el botón al inicio o al final del output según prefieras (aquí se inyecta al principio)
$out = $boton_excel . $out;

echo $out;
?>
