<?php 
    // 1. Preparar condiciones dinámicas
    $where = "";
    $where2 = "";
    if (!empty($tipo)) {
        $where = "AND b.fabricante IN ($tipo)";
        $where2 = "AND a.fabricante IN ($tipo)";
    }
    
    $contar = 0;

    // 2. Encabezado de sección con botones estilizados (Bootstrap)
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0"><i class="bi bi-box-seam me-2"></i>Movimientos de Ventas por Fabricante</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.85rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Tipo Doc</th>
                    <th scope="col">Almacén</th>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre Comercial</th>
                    <th scope="col">Principio Activo</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Fabricante</th>
                    <th scope="col" class="text-center">Medida</th>
                    <th scope="col" class="text-end">Cantidad</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta unificada optimizada
    $sql = "SELECT 
                j.tipo_documento, i.codigo, 
                IFNULL(j.almacen, 'SIN MOVIMIENTO') AS almacen, 
                i.fabricante, 
                i.principio_activo, 
                i.presentacion, 
                i.nombre_comercial, 
                IFNULL(j.unidad_medida, '-') AS unidad_medida, 
                IFNULL(j.cantidad, 0) AS cantidad 
            FROM 
                (SELECT 
                    a.id AS articulo, a.codigo, b.nombre AS fabricante, 
                    a.principio_activo, a.presentacion, a.nombre_comercial 
                FROM 
                    articulo AS a 
                    JOIN fabricante AS b ON b.Id = a.fabricante 
                WHERE 
                    0=0 $where2) AS i 
                LEFT OUTER JOIN  
                (SELECT
                    b.tipo_documento, b.articulo, d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion AS unidad_medida, 
                    ABS(SUM(b.cantidad_movimiento)) AS cantidad  
                FROM 
                    salidas AS a 
                    JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
                    LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                    LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                    LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                    LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                WHERE 
                    a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    AND a.estatus  = 'PROCESADO' AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                    AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))
                    $where 
                GROUP BY 
                    b.tipo_documento, b.articulo, d.codigo, f.descripcion, c.nombre, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion) AS j ON j.articulo = i.articulo 
            ORDER BY i.fabricante, j.almacen DESC, j.cantidad DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Volcado de información controlado para pantalla
    while ($row = mysqli_fetch_array($rs)) {
        $tipo_documento     = htmlspecialchars($row["tipo_documento"]?? '');
        $almacen     = htmlspecialchars($row["almacen"]?? '');
        $codigo      = htmlspecialchars($row["codigo"]?? '');
        $comercial   = htmlspecialchars($row["nombre_comercial"]?? '');
        $principio   = htmlspecialchars($row["principio_activo"]?? '');
        $presentacion= htmlspecialchars($row["presentacion"]?? '');
        $fabricante  = htmlspecialchars($row["fabricante"]?? '');
        $medida      = htmlspecialchars($row["unidad_medida"]);
        $cantidad    = number_format(floatval($row["cantidad"]?? 0.00), 2, ",", ".");

        $out .= <<<HTML
                <tr>
                    <td class="text-nowrap">{$tipo_documento}</td>
                    <td class="text-nowrap">{$almacen}</td>
                    <td class="font-monospace text-secondary">{$codigo}</td>
                    <td><strong>{$comercial}</strong></td>
                    <td>{$principio}</td>
                    <td>{$presentacion}</td>
                    <td class="text-muted text-uppercase" style="font-size: 0.8rem;">{$fabricante}</td>
                    <td class="text-center text-nowrap">{$medida}</td>
                    <td class="text-end font-monospace fw-bold text-dark">{$cantidad}</td>
                </tr>
HTML;

        $contar++;

        // Control de límite en pantalla para mejorar rendimiento visual
        if ($contar >= 20) {
            $out .= <<<HTML
                <tr>
                    <th colspan="9" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                        ⚠️ Se visualizan las primeras {$contar} filas por rendimiento en pantalla. Use el botón superior para exportar el universo completo.
                    </th>
                </tr>
HTML;
            break;
        }
    }

    // 5. Cierre de tabla y pie de totales
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
                <tr class="table-light">
                    <th colspan="9" class="text-end py-2 pe-3 text-secondary">
                        Artículos listados en pantalla: <span class="text-dark">{$total_items}</span>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
HTML;
?>