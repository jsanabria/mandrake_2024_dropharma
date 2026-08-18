<?php 
// 1. Detectar si la solicitud es una descarga de Excel directa
$is_excel = false;
if (isset($_REQUEST["toexcel"]) && $_REQUEST["toexcel"] == "SI") {
    $is_excel = true;
    header('Content-type: application/vnd.ms-excel; charset=utf-8');
    header("Content-Disposition: attachment; filename=SalidasGeneralesPorArticuloDetallado.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Inicializar parámetros desde la URL en modo descarga
    $id          = isset($_REQUEST["id"]) ? trim($_REQUEST["id"]) : '';
    $fecha_desde = isset($_REQUEST["fd"]) ? trim($_REQUEST["fd"]) : '';
    $fecha_hasta = isset($_REQUEST["fh"]) ? trim($_REQUEST["fh"]) : '';
    $tipo        = isset($_REQUEST["tipo"]) ? trim($_REQUEST["tipo"]) : '';

    include 'connect.php'; // Incluir la conexión sólo si se llama de forma independiente
} else {
    // Inicializar parámetros en modo vista de pantalla integrada para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
}

// 2. Sanitizar el filtro de categorías dinámico
$where = '';
if ($tipo !== "") {
    $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
    if (!empty($tipo_clean)) {
        $where = "AND b.articulo IN ($tipo_clean)";
    }
}

$out = "";

// 3. Si no es Excel, construimos el botón de descarga y abrimos el contenedor responsivo
if (!$is_excel) {
    /*
	$out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Salidas Generales por Artículo (Detallado)</h4>
        <button class="btn btn-primary" onclick="window.location.href = 'include/salidas_genreales_por_articulo_detallado.php?toexcel=SI&id={$id}&fd={$fecha_desde}&fh={$fecha_hasta}&tipo={$tipo}'">
            <i class="bi bi-file-earmark-excel"></i> Exportar a TXT/XLS
        </button>
    </div>
    <div class="table-responsive">
HTML; 
	*/
}

// Estilos específicos para la tabla de alta densidad
$table_style = !$is_excel ? 'style="font-size: 0.72rem; border: 1px solid #dee2e6;"' : '';
$thead_style = !$is_excel ? 'class="table-light text-uppercase text-nowrap" style="font-size: 0.65rem; letter-spacing: 0.5px;"' : '';

// 4. Estructurar la tabla HTML
$out .= <<<HTML
<table class="table table-hover table-striped align-middle" {$table_style}>
    <thead {$thead_style}>
        <tr>
            <th scope="col" class="text-center">ID</th>
            <th scope="col">Documento</th>
            <th scope="col" class="text-center">Fecha</th>
            <th scope="col">Cliente</th>
            <th scope="col">Asesor</th>
            <th scope="col">Ciudad</th>
            <th scope="col">Código</th>
            <th scope="col">Nombre Comercial</th>
            <th scope="col">Artículo</th>
            <th scope="col">Presentación</th>
            <th scope="col">Fabricante</th>
            <th scope="col" class="text-center">Lote</th>
            <th scope="col" class="text-center">Vencimiento</th>
            <th scope="col" class="text-end">Cant.</th>
            <th scope="col" class="text-end">Precio Unid.</th>
            <th scope="col" class="text-end">Total Art.</th>
            <th scope="col" class="text-end">Total s/IVA</th>
            <th scope="col" class="text-end">IVA</th>
            <th scope="col" class="text-end">Total Fac.</th>
            <th scope="col" class="text-center">Tipo</th>
            <th scope="col" class="text-end">Unidades</th>
            <th scope="col" class="text-center">Doc NE</th>
            <th scope="col" class="text-center">Nota</th>
        </tr>
    </thead>
    <tbody>
HTML;

// 5. Consulta SQL optimizada
$sql = "SELECT
            a.id, 
            a.nro_documento, 
            date_format(a.fecha, '%d/%m/%Y') AS fecha, 
            g.id AS codigo, 
            g.nombre AS cliente, 
            (SELECT nombre FROM usuario WHERE username = a.asesor_asignado LIMIT 0, 1) AS asesor, 
            (SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = g.ciudad) AS ciudad, 
            d.codigo AS codart, 
            d.nombre_comercial, d.principio_activo, d.presentacion, 
            c.nombre AS fabricante, 
            b.lote, b.fecha_vencimiento,
            ABS(b.cantidad_movimiento) AS cantidad_movimiento,
            b.precio_unidad, 
            b.precio AS total_articulo, 
            a.monto_total, a.iva, a.total,  
            (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
            a.unidades, 
            (SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE, a.nota    
        FROM 
            salidas AS a 
            JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
            LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
            LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
            LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
        WHERE 
            (
                (a.tipo_documento = 'TDCNET' AND a.estatus IN ('NUEVO','PROCESADO')) 
                OR (a.tipo_documento = 'TDCASA' AND a.estatus IN ('NUEVO','PROCESADO') AND IFNULL(a.documento, '') <> 'TR')
            )
            AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
            $where 
        ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

$rs = mysqli_query($link, $sql);

if (!$rs) {
    var_dump(mysqli_error($link));
    die();
}

$contar = 0;

// 6. Volcado seguro de las filas
while ($row = mysqli_fetch_array($rs)) {
    $contar++;

    // Desinfectar textos contra nulos (PHP 8.1+)
    $cliente     = trim($row["cliente"] ?? '');
    $asesor      = trim($row["asesor"] ?? '');
    $ciudad      = trim($row["ciudad"] ?? '');
    $n_comercial = trim($row["nombre_comercial"] ?? '');
    $p_activo    = trim($row["principio_activo"] ?? '');
    $presentacion = trim($row["presentacion"] ?? '');
    $fabricante  = trim($row["fabricante"] ?? '');
    $lote        = trim($row["lote"] ?? '');
    $fec_venc    = trim($row["fecha_vencimiento"] ?? '');
    $tipo_doc    = trim($row["tipo"] ?? '');
    $doc_ne      = trim($row["DOC_NE"] ?? '');
    $nota      = trim($row["nota"] ?? '');

    // Sanitización de textos para que no se descuadre en Excel plano
    if ($is_excel) {
        $cliente     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);
        $asesor      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $asesor);
        $ciudad      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);
        $n_comercial = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $n_comercial);
        $p_activo    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $p_activo);
        $presentacion = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $presentacion);
        $fabricante  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);
        $lote        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $lote);
    }

    // Resoluciones numéricas y conversiones de tipo seguras
    $raw_cant      = intval($row["cantidad_movimiento"] ?? 0);
    $raw_unidades  = intval($row["unidades"] ?? 0);
    $raw_precio_u  = floatval($row["precio_unidad"] ?? 0);
    $raw_tot_art   = floatval($row["total_articulo"] ?? 0);
    $raw_monto_tot = floatval($row["monto_total"] ?? 0);
    $raw_iva       = floatval($row["iva"] ?? 0);
    $raw_total     = floatval($row["total"] ?? 0);

    // Formatear montos numéricos
    $cant_form      = number_format($raw_cant, 0, "", ".");
    $unidades_form  = number_format($raw_unidades, 0, "", ".");
    $precio_u_form  = number_format($raw_precio_u, 2, ".", ",");
    $tot_art_form   = number_format($raw_tot_art, 2, ".", ",");
    $monto_tot_form = number_format($raw_monto_tot, 2, ".", ",");
    $iva_form       = number_format($raw_iva, 2, ".", ",");
    $total_form     = number_format($raw_total, 2, ".", ",");

    $out .= <<<HTML
    <tr>
        <td class="text-center font-monospace text-secondary">{$row['id']}</td>
        <td class="font-monospace text-nowrap">{$row['nro_documento']}</td>
        <td class="text-center text-nowrap">{$row['fecha']}</td>
        <td class="text-uppercase text-wrap" style="max-width: 150px;">{$cliente}</td>
        <td class="text-uppercase text-nowrap">{$asesor}</td>
        <td class="text-uppercase text-nowrap">{$ciudad}</td>
        <td class="font-monospace">{$row['codart']}</td>
        <td class="text-uppercase text-wrap">{$n_comercial}</td>
        <td class="text-uppercase text-wrap text-muted" style="font-size: 0.65rem;">{$p_activo}</td>
        <td class="text-uppercase text-nowrap">{$presentacion}</td>
        <td class="text-uppercase text-wrap text-muted">{$fabricante}</td>
        <td class="text-center font-monospace">{$lote}</td>
        <td class="text-center text-nowrap">{$fec_venc}</td>
        <td class="text-end font-monospace">{$cant_form}</td>
        <td class="text-end font-monospace">{$precio_u_form}</td>
        <td class="text-end font-monospace">{$tot_art_form}</td>
        <td class="text-end font-monospace">{$monto_tot_form}</td>
        <td class="text-end font-monospace">{$iva_form}</td>
        <td class="text-end font-monospace fw-semibold">{$total_form}</td>
        <td class="text-center text-nowrap">{$tipo_doc}</td>
        <td class="text-end font-monospace">{$unidades_form}</td>
        <td class="text-center font-monospace">{$doc_ne}</td>
        <td class="text-center font-monospace">{$nota}</td>
    </tr>
HTML;
}

// 7. Fila final de totales
$total_art_visualizados = number_format($contar, 0, "", ".");
$out .= <<<HTML
        <tr>
            <th colspan="23" class="text-end table-light py-3 pe-4 text-secondary">
                Total Artículos Procesados: <span class="text-dark">{$total_art_visualizados}</span>
            </th>
        </tr>
    </tbody>
</table>
HTML;

if (!$is_excel) {
    $out .= "</div>"; // Cierre del contenedor table-responsive
}

// 8. Flujo final del renderizado
if (isset($_REQUEST["toexcel"]) && $_REQUEST["toexcel"] == "SI") {
    echo $out;
    exit();
}
?>