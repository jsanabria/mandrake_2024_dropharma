<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    
    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" de categorías múltiple
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    $contar = 0;
    $total_unidades = 0;

    // 2. Encabezado de la tabla y botón de acción con diseño optimizado
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Salidas Generales por Artículo (NEAs)</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 10%;">Código</th>
                    <th scope="col" style="width: 25%;">Nombre Comercial</th>
                    <th scope="col" style="width: 30%;">Principio Activo</th>
                    <th scope="col" style="width: 15%;">Presentación</th>
                    <th scope="col" style="width: 12%;">Fabricante</th>
                    <th scope="col" style="width: 8%;" class="text-end">Cantidad</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL optimizada
    $sql = "SELECT 
                d.id,
                d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCNET','TDCASA') 
                AND a.estatus IN ('NUEVO', 'PROCESADO') 
                AND b.newdata = 'S' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND IFNULL(a.documento, '') <> 'TR'
                $where 
            GROUP BY d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            ORDER BY cantidad_movimiento DESC"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Renderizado seguro de las filas
    while($row = mysqli_fetch_array($rs)) {
        $contar++;

        // Fusión de nulos para compatibilidad total con PHP 8.1+
        $row_id        = $row["id"] ?? '';
        $row_codigo    = $row["codigo"] ?? '';
        $n_comercial   = trim($row["nombre_comercial"] ?? '');
        $p_activo      = trim($row["principio_activo"] ?? '');
        $presentacion  = trim($row["presentacion"] ?? '');
        $fabricante    = trim($row["fabricante"] ?? '');
        
        $cant_mov      = $row["cantidad_movimiento"] ?? 0;
        $total_unidades += $cant_mov;
        $cantidad_form = number_format($cant_mov, 0, "", ".");

        // Fallbacks visuales para textos vacíos
        $n_comercial_val  = !empty($n_comercial) ? $n_comercial : '<span class="text-muted">-</span>';
        $p_activo_val     = !empty($p_activo) ? $p_activo : '<span class="text-muted">-</span>';
        $presentacion_val = !empty($presentacion) ? $presentacion : '<span class="text-muted">-</span>';
        $fabricante_val   = !empty($fabricante) ? $fabricante : '<span class="text-muted">-</span>';

        $out .= <<<HTML
        <tr>
            <td class="font-monospace fw-bold">
                <a href="ListadoMasterGeneral?id={$id}&codigo={$row_id}&fecha_desde={$fecha_desde}&fecha_hasta={$fecha_hasta}" target="_blank" class="text-decoration-none">
                    {$row_codigo}
                </a>
            </td>
            <td class="text-uppercase text-wrap">{$n_comercial_val}</td>
            <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.8rem;">{$p_activo_val}</td>
            <td class="text-uppercase text-nowrap">{$presentacion_val}</td>
            <td class="text-uppercase text-wrap text-muted">{$fabricante_val}</td>
            <td class="text-end font-monospace fw-semibold text-dark">{$cantidad_form}</td>
        </tr>
HTML;
    }

    // 5. Fila de resumen de totales
    $total_articulos = number_format($contar, 0, "", ".");
    $total_unidades_fmt = number_format($total_unidades, 0, "", ".");

    $out .= <<<HTML
    <tr class="table-warning">
        <th colspan="2" class="text-start py-3 ps-4">
            Artículos: <span class="text-dark fw-bold">{$total_articulos}</span>
        </th>

        <th colspan="2" class="text-center py-3">
            Unidades: <span class="text-dark fw-bold">{$total_unidades_fmt}</span>
        </th>

        <th colspan="2" class="text-center py-3">
            
        </th>
    </tr>
HTML;
?>