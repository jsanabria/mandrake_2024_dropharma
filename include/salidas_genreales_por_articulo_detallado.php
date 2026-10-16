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

    include 'connect.php'; 
} else {
    // Inicializar parámetros en modo vista de pantalla integrada
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

// Estilos específicos para la tabla de alta densidad
$table_style = !$is_excel ? 'style="font-size: 0.72rem; border: 1px solid #dee2e6;"' : '';
$thead_style = !$is_excel ? 'class="table-light text-uppercase text-nowrap" style="font-size: 0.65rem; letter-spacing: 0.5px;"' : '';

// 3. Estructurar la tabla HTML incluyendo la nueva columna
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
            <th scope="col">Principio Activo</th>
            <th scope="col">Presentación</th>
            <th scope="col">Fabricante</th>
            <th scope="col" class="text-center">Lote</th>
            <th scope="col" class="text-center">Vencimiento</th>
            <th scope="col" class="text-end">Cant.</th>

            <th scope="col" class="text-end">Precio Unid SD.</th>
            <th scope="col" class="text-end">DES ART</th>
            <th scope="col" class="text-end">DES FAB</th>
            <th scope="col" class="text-end">DES COM</th>
            <th scope="col" class="text-end">DES TRA</th>

            <th scope="col" class="text-end">Precio Unid.</th>
            <th scope="col" class="text-end">Precio Unid Total</th>
            <th scope="col" class="text-end">Total Art.</th>
            <th scope="col" class="text-end">Total s/IVA</th>
            <th scope="col" class="text-end">IVA</th>
            <th scope="col" class="text-end">Total Fac.</th>
            <th scope="col" class="text-center">Moneda</th>
            <th scope="col" class="text-center">Tipo</th>
            <th scope="col" class="text-end">Unidades</th>
            <th scope="col" class="text-center">Doc NE</th>
        </tr>
    </thead>
    <tbody>
HTML;

// 4. Consulta SQL
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
            CASE WHEN a.documento = 'NC' THEN -ABS(b.cantidad_movimiento) ELSE ABS(b.cantidad_movimiento) END AS cantidad_movimiento,
            
            CASE WHEN a.documento = 'NC' THEN -ABS(b.precio_unidad_sin_desc) ELSE ABS(b.precio_unidad_sin_desc) END AS precio_unidad_sin_desc,
            b.descuento AS DesArt, 
            b.descuento2 AS DesFab, 
            a.descuento AS DesCom, 
            a.descuento2 AS DesTra,
            
            b.precio_unidad, 
            CASE WHEN a.documento = 'NC' THEN -ABS(b.precio) ELSE ABS(b.precio) END AS total_articulo, 
            CASE WHEN a.documento = 'NC' THEN -ABS(a.monto_total) ELSE ABS(a.monto_total) END AS monto_total, 
            CASE WHEN a.documento = 'NC' THEN -ABS(a.iva) ELSE ABS(a.iva) END AS iva, 
            CASE WHEN a.documento = 'NC' THEN -ABS(a.total) ELSE ABS(a.total) END AS total, 

            a.moneda, 

            (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
            CASE WHEN a.documento = 'NC' THEN -ABS(a.unidades) ELSE ABS(a.unidades) END AS unidades, 
            (SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE,
            COALESCE(NULLIF(a.documento, ''), 'FC') AS tipo_doc_real   
        FROM 
            salidas AS a 
            JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
            LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
            LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
            LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
        WHERE 
            (
                (a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND (a.documento IN ('FC', 'NC', 'ND') OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))) 
                OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO' AND a.factura = 'S')
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

// 5. Volcado seguro de las filas
while ($row = mysqli_fetch_array($rs)) {
    $contar++;

    $cliente      = trim($row["cliente"] ?? '');
    $asesor       = trim($row["asesor"] ?? '');
    $ciudad       = trim($row["ciudad"] ?? '');
    $n_comercial  = trim($row["nombre_comercial"] ?? '');
    $p_activo     = trim($row["principio_activo"] ?? '');
    $presentacion = trim($row["presentacion"] ?? '');
    $fabricante   = trim($row["fabricante"] ?? '');
    $lote         = trim($row["lote"] ?? '');
    $fec_venc     = trim($row["fecha_vencimiento"] ?? '');
    $tipo_doc     = trim($row["tipo"] ?? '');
    $doc_ne       = trim($row["DOC_NE"] ?? '');
    $tipo_doc_real= trim($row["tipo_doc_real"] ?? 'FC');

    if ($is_excel) {
        $cliente      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);
        $asesor       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $asesor);
        $ciudad       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);
        $n_comercial  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $n_comercial);
        $p_activo     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $p_activo);
        $presentacion = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $presentacion);
        $fabricante   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);
        $lote         = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $lote);
    }

    $raw_cant      = floatval($row["cantidad_movimiento"] ?? 0);

    $raw_precio_unidad_sin_desc  = floatval($row["precio_unidad_sin_desc"] ?? 0);
    $raw_DesArt      = floatval($row["DesArt"] ?? 0);
    $raw_DesFab      = floatval($row["DesFab"] ?? 0);
    $raw_DesCom      = floatval($row["DesCom"] ?? 0);
    $raw_DesTra      = floatval($row["DesTra"] ?? 0);

    $raw_unidades  = floatval($row["unidades"] ?? 0);
    $raw_precio_u  = floatval($row["precio_unidad"] ?? 0);
    
    // Aplicación en cascada de DES COM y DES TRA sobre PRECIO UNID.
    $raw_precio_u_tot = $raw_precio_u * (1 - ($raw_DesCom / 100)) * (1 - ($raw_DesTra / 100));

    $raw_tot_art   = floatval($row["total_articulo"] ?? 0);
    $raw_monto_tot = floatval($row["monto_total"] ?? 0);
    $raw_iva       = floatval($row["iva"] ?? 0);
    $raw_total     = floatval($row["total"] ?? 0);

    $cant_form      = number_format($raw_cant, 2, ",", ".");

    $form_precio_unidad_sin_desc = number_format($raw_precio_unidad_sin_desc, 2, ".", ",");
    $DesArt_cant      = number_format($raw_DesArt, 2, ",", ".");
    $DesFab_cant      = number_format($raw_DesFab, 2, ",", ".");
    $DesCom_cant      = number_format($raw_DesCom, 2, ",", ".");
    $DesTra_cant      = number_format($raw_DesTra, 2, ",", ".");

    $unidades_form     = number_format($raw_unidades, 2, ",", ".");
    $precio_u_form     = number_format($raw_precio_u, 2, ".", ",");
    $precio_u_tot_form = number_format($raw_precio_u_tot, 2, ".", ",");
    $tot_art_form      = number_format($raw_tot_art, 2, ".", ",");
    $monto_tot_form    = number_format($raw_monto_tot, 2, ".", ",");
    $iva_form          = number_format($raw_iva, 2, ".", ",");
    $total_form        = number_format($raw_total, 2, ".", ",");

    $moneda     = trim($row["moneda"] ?? '');

    $clase_fila = ($tipo_doc_real === 'NC') ? "table-danger text-danger" : "";

    $out .= <<<HTML
    <tr class="{$clase_fila}">
        <td class="text-center font-monospace text-secondary">{$row['id']}</td>
        <td class="font-monospace text-nowrap">{$row['nro_documento']}</td>
        <td class="text-center text-nowrap">{$row['fecha']}</td>
        <td class="text-uppercase text-wrap" style="max-width: 150px;">{$cliente}</td>
        <td class="text-uppercase text-nowrap">{$asesor}</td>
        <td class="text-uppercase text-nowrap">{$ciudad}</td>
        <td class="font-monospace">{$row['codart']}</td>
        <td class="text-uppercase text-wrap">{$n_comercial}</td>
        <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.65rem;">{$p_activo}</td>
        <td class="text-uppercase text-nowrap">{$presentacion}</td>
        <td class="text-uppercase text-wrap text-muted">{$fabricante}</td>
        <td class="text-center font-monospace">{$lote}</td>
        <td class="text-center text-nowrap">{$fec_venc}</td>
        <td class="text-end font-monospace">{$cant_form}</td>

        <td class="text-end font-monospace">{$form_precio_unidad_sin_desc}</td>
        <td class="text-end font-monospace">{$DesArt_cant}</td>
        <td class="text-end font-monospace">{$DesFab_cant}</td>
        <td class="text-end font-monospace">{$DesCom_cant}</td>
        <td class="text-end font-monospace">{$DesTra_cant}</td>

        <td class="text-end font-monospace">{$precio_u_form}</td>
        <td class="text-end font-monospace fw-bold">{$precio_u_tot_form}</td>
        <td class="text-end font-monospace">{$tot_art_form}</td>
        <td class="text-end font-monospace">{$monto_tot_form}</td>
        <td class="text-end font-monospace">{$iva_form}</td>
        <td class="text-end font-monospace fw-semibold">{$total_form}</td>

        <td class="text-center text-nowrap">{$moneda}</td>

        <td class="text-center text-nowrap">{$tipo_doc}</td>
        <td class="text-end font-monospace">{$unidades_form}</td>
        <td class="text-center font-monospace">{$doc_ne}</td>
    </tr>
HTML;
}

// 6. Fila final de totales (Colspan ajustado a 29 por la nueva columna)
$total_art_visualizados = number_format($contar, 0, "", ".");
$out .= <<<HTML
        <tr>
            <th colspan="29" class="text-end table-light py-3 pe-4 text-secondary">
                Total Artículos Procesados: <span class="text-dark">{$total_art_visualizados}</span>
            </th>
        </tr>
    </tbody>
</table>
HTML;

if (!$is_excel) {
    $out .= "</div>"; 
}

if (isset($_REQUEST["toexcel"]) && $_REQUEST["toexcel"] == "SI") {
    echo $out;
    exit();
}
?>