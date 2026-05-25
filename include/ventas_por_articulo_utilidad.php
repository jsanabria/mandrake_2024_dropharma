<?php 
    // 1. Sanitización de parámetros de filtrado y construcción segura del WHERE
    $where = "";
    if (!empty($tipo)) {
        // Mantiene la consistencia con el filtro por categoría/artículo usado en el módulo
        $where = " AND b.articulo IN ($tipo)"; 
    }

    // 2. Botonera de acciones superiores (Exportación e Impresión)
    $out .= '<div class="d-flex justify-content-between align-items-center mb-3">';
    $out .= '  <div>';
    $out .= '    <a href="reportes/ventas_por_articulo_utilidad.php?fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank" class="btn btn-outline-primary ms-2"><i class="bi bi-printer"></i> Imprimir PDF</a>';
    $out .= '  </div>';
    $out .= '</div>';

    // 3. Estructura de Tabla Adaptativa Bootstrap
    $out .= '<div class="table-responsive">';
    $out .= '<table class="table table-hover table-bordered table-striped align-middle" style="font-size: 0.85rem;">';
    $out .= '  <thead class="table-light text-uppercase font-monospace" style="font-size: 0.75rem;">';
    $out .= '    <tr>';
    $out .= '      <th scope="col" class="text-center">Código</th>';
    $out .= '      <th scope="col">Artículo / Descripción</th>';
    $out .= '      <th scope="col" class="text-end">Cantidad</th>';
    $out .= '      <th scope="col" class="text-end">Costo Und.</th>';
    $out .= '      <th scope="col" class="text-end">Costo Total</th>';
    $out .= '      <th scope="col" class="text-end">Precio Und.</th>';
    $out .= '      <th scope="col" class="text-end">Precio Total</th>';
    $out .= '      <th scope="col" class="text-end">Margen Utilidad</th>';
    $out .= '    </tr>';
    $out .= '  </thead>';
    $out .= '  <tbody>';

    // 4. Consulta SQL Optimizada con cálculos directos en BD
    $sql = "SELECT 
                d.id,
                d.codigo AS codigo,
                CONCAT(IFNULL(d.nombre_comercial, ''), ' ', IFNULL(d.principio_activo, ''), ' ', IFNULL(d.presentacion, '')) AS articulo, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
                ROUND(IFNULL(d.ultimo_costo, 0), 2) AS costo_unidad, 
                ROUND(SUM(ABS(b.cantidad_movimiento) * IFNULL(d.ultimo_costo, 0)), 2) AS costo_total, 
                ROUND(AVG(IFNULL(b.precio, 0) / NULLIF(ABS(b.cantidad_movimiento), 0)), 2) AS precio_unidad, 
                ROUND(SUM(IFNULL(b.precio, 0)), 2) AS venta_total
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV', 'TDCASA') 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC'
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, d.ultimo_costo
            ORDER BY 
                venta_total DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // Inicializadores de Acumuladores Globales para el Footer
    $contar       = 0;
    $total_unidades = 0;
    $total_costos   = 0;
    $total_ventas   = 0;

    while ($row = mysqli_fetch_assoc($rs)) {
        $id_art      = $row["id"];
        $codigo      = trim($row["codigo"] ?? '-');
        $articulo    = trim($row["articulo"] ?? 'Artículo sin descripción');
        $cant_mov    = floatval($row["cantidad_movimiento"] ?? 0);
        $costo_und   = floatval($row["costo_unidad"] ?? 0);
        $costo_tot   = floatval($row["costo_total"] ?? 0);
        $precio_und  = floatval($row["precio_unidad"] ?? 0);
        $venta_tot   = floatval($row["venta_total"] ?? 0);

        // Cálculo de Utilidad y Margen Porcentual Neta
        $utilidad_monto = $venta_tot - $costo_tot;
        $margen_porc    = ($venta_tot > 0) ? ($utilidad_monto / $venta_tot) * 100 : 0;

        // Acumulación de Totales
        $total_unidades += $cant_mov;
        $total_costos   += $costo_tot;
        $total_ventas   += $venta_tot;

        // Impresión condicional en pantalla (Límite visual de filas)
        if ($contar < 50) {
            $out .= '<tr>';
            $out .= '  <td class="text-center font-monospace">' . $codigo . '</td>';
            $out .= '  <td><a class="text-decoration-none fw-bold" href="ListadoMasterGeneral?id=' . $id . '&codigo=' . $id_art . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $articulo . '</a></td>';
            $out .= '  <td class="text-end font-monospace">' . number_format($cant_mov, 0, ",", ".") . '</td>';
            $out .= '  <td class="text-end font-monospace text-muted">' . number_format($costo_und, 2, ",", ".") . '</td>';
            $out .= '  <td class="text-end font-monospace">' . number_format($costo_tot, 2, ",", ".") . '</td>';
            $out .= '  <td class="text-end font-monospace text-muted">' . number_format($precio_und, 2, ",", ".") . '</td>';
            $out .= '  <td class="text-end font-monospace fw-bold">' . number_format($venta_tot, 2, ",", ".") . '</td>';
            
            // Colorización dinámica del margen de ganancia
            $badge_class = ($margen_porc > 15) ? 'text-success' : (($margen_porc > 0) ? 'text-warning' : 'text-danger');
            $out .= '  <td class="text-end font-monospace fw-bold ' . $badge_class . '">' . number_format($margen_porc, 2, ",", ".") . '%</td>';
            $out .= '</tr>';
        }
        $contar++;
    }

    // Alerta informativa si se superan los registros visibles en el navegador
    if ($contar > 50) {
        $out .= '<tr>';
        $out .= '  <td colspan="8" class="text-center table-warning text-secondary py-2" style="font-size: 0.8rem;">';
        $out .= '    ⚠️ Mostrando las primeras 50 de ' . $contar . ' filas calculadas. Para auditar el reporte completo utilice el botón "Exportar a Excel".';
        $out .= '  </td>';
        $out .= '</tr>';
    }

    // 5. Fila General de Totales Consolidados (Footer de la tabla)
    $utilidad_global_monto = $total_ventas - $total_costos;
    $margen_global_porc    = ($total_ventas > 0) ? ($utilidad_global_monto / $total_ventas) * 100 : 0;

    $out .= '  </tbody>';
    $out .= '  <tfoot class="table-dark font-monospace" style="border-top: 2px solid #222;">';
    $out .= '    <tr>';
    $out .= '      <td colspan="2" class="text-uppercase text-center fw-bold">Totales Consolidados</td>';
    $out .= '      <td class="text-end fw-bold">' . number_format($total_unidades, 0, ",", ".") . '</td>';
    $out .= '      <td class="text-end text-muted">-</td>';
    $out .= '      <td class="text-end fw-bold">' . number_format($total_costos, 2, ",", ".") . '</td>';
    $out .= '      <td class="text-end text-muted">-</td>';
    $out .= '      <td class="text-end fw-bold text-warning">' . number_format($total_ventas, 2, ",", ".") . '</td>';
    $out .= '      <td class="text-end fw-bold text-info">' . number_format($margen_global_porc, 2, ",", ".") . '%</td>';
    $out .= '    </tr>';
    $out .= '  </tfoot>';
    $out .= '</table>';
    $out .= '</div>';
?>