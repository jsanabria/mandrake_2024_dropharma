<?php 
    // 1. Preparar condiciones dinámicas (Corregido: Ahora sí se acopla al WHERE)
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND c.tarifa = '$tipo'";
    }
    
    $contar = 0;

    // 2. Encabezado y estructura de la tabla con diseño mejorado
    $out .= <<<HTML
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 25%;">Artículo</th>
                    <th scope="col" style="width: 10%;" class="text-center">Cód. Art</th>
                    <th scope="col" style="width: 25%;">Cliente</th>
                    <th scope="col" style="width: 10%;" class="text-center">Cód. Cli</th>
                    <th scope="col" style="width: 10%;" class="text-end">Unidades</th>
                    <th scope="col" style="width: 10%;" class="text-end">Precio</th>
                    <th scope="col" style="width: 10%;" class="text-center">Fecha</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Ejecución de la consulta SQL (Inyectando $where_tarifa)
    $join_tarifa = "";
    $where_tarifa = "";
    
    if (!empty($tipo)) {
        // Solo acoplamos la tabla fabricante si el filtro viene activo
        $join_tarifa = "INNER JOIN fabricante AS c ON c.Id = b.fabricante";
        $where_tarifa = "AND c.tarifa = '$tipo'";
    }

    $sql = "SELECT 
                LPAD(a.nro_documento, 12, '0') AS codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo, 
                d.id AS codigo_articulo, 
                g.nombre AS cliente, 
                g.id AS codigo_cliente, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento, 
                (b.precio_unidad / a.tasa_dia) AS precio_unidad, 
                DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha 
            FROM 
                salidas AS a 
                -- Forzamos la conversión explícita de tipos si id_documento es VARCHAR e id es INT
                INNER JOIN entradas_salidas AS b 
                    ON b.tipo_documento = a.tipo_documento 
                    AND b.id_documento = a.id 
                INNER JOIN articulo AS d ON d.id = b.articulo 
                INNER JOIN cliente AS g ON g.id = a.cliente 
                $join_tarifa
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.documento = 'FC' 
                AND a.estatus = 'PROCESADO'
                AND a.fecha BETWEEN '{$fecha_desde} 00:00:00' AND '{$fecha_hasta} 23:59:59'
                $where_tarifa;";

    $rs = mysqli_query($link, $sql);
    
    // 4. Renderizado de las filas
	while($row = mysqli_fetch_array($rs)) {
        $contar++;
        
        // Convertimos a 0 si la cantidad o el precio vienen nulos desde la base de datos
        $cantidad_mov = $row["cantidad_movimiento"] ?? 0;
        $precio_unid  = $row["precio_unidad"] ?? 0;

        $unidades = number_format($cantidad_mov, 0, "", ".");
        $precio   = number_format($precio_unid, 2, '.', ',');
        
        $out .= <<<HTML
        <tr>
            <td class="text-wrap text-uppercase" style="font-size: 0.8rem;">{$row['articulo']}</td>
            <td class="text-center font-monospace text-secondary">{$row['codigo_articulo']}</td>
            <td class="text-wrap text-uppercase" style="font-size: 0.8rem;">{$row['cliente']}</td>
            <td class="text-center font-monospace text-secondary">{$row['codigo_cliente']}</td>
            <td class="text-end font-monospace">{$unidades}</td>
            <td class="text-end fw-bold text-dark">{$precio}</td>
            <td class="text-center text-nowrap">{$row['fecha']}</td>
        </tr>
HTML;

        // Límite de control para pantalla
        if($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="7" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas. Para ver el reporte completo, por favor usa el botón de exportar.
                </th>
            </tr>
HTML;
            break;
        }
    }
    // 5. Cierre y totales
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="7" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Ítems Visualizados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;

    // echo $out;
?>