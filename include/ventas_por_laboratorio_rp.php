<?php 
    // 1. Preparar condiciones dinámicas de fabricante (laboratorio)
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.fabricante IN (" . $tipo . ")";
    }
    
    // 2. Encabezado de sección y tabla responsiva Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Ventas por Laboratorio</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle table-bordered" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap text-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Almacén</th>
                    <th scope="col">Código</th>
                    <th scope="col" style="min-width: 150px;">Nombre Comercial</th>
                    <th scope="col">Nombre Artículo</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Fabricante</th>
                    <th scope="col">Medida</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Asesor</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta de datos idéntica a tu estructura original con GROUP BY
    $sql = "SELECT
                d.codigo, 
                f.descripcion AS almacen, 
                c.nombre AS fabricante, 
                d.principio_activo, 
                d.presentacion, 
                d.nombre_comercial, 
                e.descripcion AS unidad_medida, 
                ABS(SUM(b.cantidad_movimiento)) AS cantidad, 
                g.nombre AS asesor_asignado  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                LEFT OUTER JOIN usuario AS g ON g.username = a.asesor_asignado 
            WHERE 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND a.estatus = 'PROCESADO' 
                AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND IFNULL(a.documento, '') = 'FC' 
                $where 
            GROUP BY 
                d.codigo, f.descripcion, c.nombre, d.principio_activo, 
                d.presentacion, d.nombre_comercial, e.descripcion, g.nombre 
            ORDER BY fabricante, cantidad DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $contar = 0;
    
    // 4. Renderizado de filas con validación estricta para PHP 8.x
    while ($row = mysqli_fetch_array($rs)) {
        
        $almacen     = trim($row["almacen"] ?? 'PRINCIPAL');
        $codigo      = trim($row["codigo"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');
        $fabricante  = trim($row["fabricante"] ?? 'SIN LABORATORIO');
        $medida      = trim($row["unidad_medida"] ?? 'UND');
        $asesor      = trim($row["asesor_asignado"] ?? 'SIN ASESOR');

        // Conversión entera segura de la agrupación sumada
        $val_cantidad = intval($row["cantidad"] ?? 0);

        $out .= <<<HTML
        <tr>
            <td class="text-nowrap text-secondary text-uppercase">{$almacen}</td>
            <td class="font-monospace text-center">{$codigo}</td>
            <td class="text-uppercase text-wrap fw-bold text-dark">{$comercial}</td>
            <td class="text-uppercase text-wrap">{$principio}</td>
            <td class="text-uppercase text-wrap text-muted">{$presentacio}</td>
            <td class="text-uppercase text-wrap fw-semibold text-secondary">{$fabricante}</td>
            <td class="text-center text-uppercase text-nowrap">{$medida}</td>
            <td class="text-center font-monospace fw-bold table-light">{$val_cantidad}</td>
            <td class="text-nowrap text-uppercase small text-muted">{$asesor}</td>
        </tr>
HTML;

        $contar++;

        // Control preventivo de visualización en interfaz (Top 20 en pantalla HTML)
        if ($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="9" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas de resumen. Para revisar la totalidad de los datos por laboratorio, por favor utiliza la exportación superior.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Cierre de tabla con sumatorias de registros únicos agrupados
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="9" class="text-end table-light py-3 pe-4 text-secondary">
                    Renglones Mostrados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>