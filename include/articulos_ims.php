<?php
    // 1. Preparar condiciones dinámicas (Corregido: El alias correcto para tarifa es 'f')
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND f.tarifa = '$tipo'";
    } else {
        $where_tarifa = "AND f.tarifa = 2";
    }
    
    $contar = 0;

    // 2. Construcción del encabezado de la tabla con mejor diseño
    $out .= <<<HTML
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 12%;">Código</th>
                    <th scope="col" style="width: 43%;">Nombre / Presentación</th>
                    <th scope="col" style="width: 20%;">Laboratorio</th>
                    <th scope="col" style="width: 10%;" class="text-end">Precio</th>
                    <th scope="col" style="width: 15%;" class="text-center">Barra</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Ejecución de la consulta SQL
    $sql = "SELECT DISTINCT 
                d.id AS codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS nombre, 
                IFNULL(c.nombre, ' ') AS fabricante, 
                IFNULL(f.precio, 0) AS precio, 
                IFNULL(d.codigo_de_barra, ' ') AS codigo_de_barra  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN tarifa_articulo AS f ON f.fabricante = b.fabricante AND f.articulo = b.articulo AND f.tarifa = 2 
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC'  
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where_tarifa;"; 

    $rs = mysqli_query($link, $sql);

    // 4. Renderizado del cuerpo de la tabla (Bucle)
    while($row = mysqli_fetch_array($rs)) {
        $contar++;
        $precio_formateado = number_format($row["precio"], 2, '.', ',');
        $barra = !empty(trim($row["codigo_de_barra"])) ? $row["codigo_de_barra"] : '<span class="text-muted">-</span>';

        $out .= <<<HTML
        <tr>
            <td class="fw-bold text-secondary font-monospace">{$row['codigo']}</td>
            <td class="text-wrap text-uppercase">{$row['nombre']}</td>
            <td class="text-muted text-wrap">{$row['fabricante']}</td>
            <td class="text-end fw-bold text-dark">{$precio_formateado}</td>
            <td class="text-center font-monospace text-secondary">{$barra}</td>
        </tr>
HTML;

        // Límite de visualización en pantalla
        if($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="5" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas. Para ver el reporte completo, por favor usa el botón de exportar.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Fila final de totales
    $total_articulos = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="5" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Artículos Visualizados: <span class="text-dark">{$total_articulos}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;

    // echo $out;
?>