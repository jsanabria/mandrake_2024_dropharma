<?php 
    // 1. Condición dinámica por categoría basada en tu archivo original
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.articulo IN ($tipo)";
    }
    
    // 2. Encabezado de sección y tabla responsiva Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Ventas por Artículo</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle table-bordered" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap text-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col" style="min-width: 180px;">Nombre Comercial</th>
                    <th scope="col">Principio Activo</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Laboratorio</th>
                    <th scope="col">Cantidad</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta idéntica a tu estructura original de Excel
    $sql = "SELECT 
                d.id,
                d.codigo, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                c.nombre AS fabricante, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            ORDER BY 
                cantidad_movimiento DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $contar = 0;

    // 4. Renderizado seguro compatible con PHP 8.x
    while ($row = mysqli_fetch_array($rs)) {
        
        $item_id     = $row["id"] ?? 0;
        $codigo      = trim($row["codigo"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');
        $fabricante  = trim($row["fabricante"] ?? 'SIN LABORATORIO');
        $cantidad    = intval($row["cantidad_movimiento"] ?? 0);

        $out .= <<<HTML
        <tr>
            <td class="font-monospace text-center">
                <a href="ListadoMasterGeneral?id={$id}&codigo={$item_id}&fecha_desde={$fecha_desde}&fecha_hasta={$fecha_hasta}" target="_blank" class="fw-bold text-decoration-none">
                    {$codigo}
                </a>
            </td>
            <td class="text-uppercase text-wrap fw-bold text-dark">{$comercial}</td>
            <td class="text-uppercase text-wrap">{$principio}</td>
            <td class="text-uppercase text-wrap text-muted">{$presentacio}</td>
            <td class="text-uppercase text-wrap fw-semibold text-secondary">{$fabricante}</td>
            <td class="text-center font-monospace fw-bold table-light">{$cantidad}</td>
        </tr>
HTML;

        $contar++;

        // Control preventivo de rendimiento en pantalla (Top 20)
        if ($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="6" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualiza el Top {$contar} de artículos más vendidos en pantalla. Para ver la totalidad del inventario, utiliza la exportación a Excel.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Cierre de tabla con contadores
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="6" class="text-end table-light py-3 pe-4 text-secondary">
                    Artículos Mostrados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>