<?php 
    // 1. Preparar condiciones dinámicas
    $where = "";
    if (!empty($tipo)) {
        $where = "AND c.tarifa = " . intval($tipo);
    }
    
    // 2. Encabezado de sección y tabla responsiva adaptada a Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Factura: Costo vs Precio</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Fabricante</th>
                    <th scope="col" style="min-width: 200px;">Artículo / Presentación</th>
                    <th scope="col" class="text-center">Cod. Art.</th>
                    <th scope="col" style="min-width: 180px;">Cliente</th>
                    <th scope="col" class="text-center">Cod. Cli.</th>
                    <th scope="col" class="text-center">Unidades</th>
                    <th scope="col" class="text-end">Costo U.</th>
                    <th scope="col" class="text-end">Precio U.</th>
                    <th scope="col" class="text-center">Fecha</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL optimizada
    $sql = "SELECT 
                c.nombre AS fabricante, 
                LPAD(a.nro_documento, 12, '0') AS codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo, 
                d.id AS codigo_articulo, 
                g.nombre AS cliente, 
                g.id AS codigo_cliente, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento, 
                b.costo_unidad, 
                b.precio_unidad, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
                $where 
            ORDER BY a.fecha;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $contar = 0;
    
    // 4. Renderizado seguro protegiendo formatos numéricos
    while($row = mysqli_fetch_array($rs)) {
        $contar++;
        
        $fabricante = trim($row["fabricante"] ?? 'SIN FABRICANTE');
        $articulo   = trim($row["articulo"] ?? 'SIN DETALLE');
        $cod_art    = trim($row["codigo_articulo"] ?? '');
        $cliente    = trim($row["cliente"] ?? 'CLIENTE GENÉRICO');
        $cod_cli    = trim($row["codigo_cliente"] ?? '');
        $fecha      = trim($row["fecha"] ?? '');

        // Validaciones numéricas contra nulos para PHP 8.x
        $val_cantidad = floatval($row["cantidad_movimiento"] ?? 0);
        $val_costo    = floatval($row["costo_unidad"] ?? 0);
        $val_precio   = floatval($row["precio_unidad"] ?? 0);

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-wrap">{$fabricante}</td>
            <td class="text-uppercase text-wrap">{$articulo}</td>
            <td class="text-center font-monospace">{$cod_art}</td>
            <td class="text-uppercase text-wrap">{$cliente}</td>
            <td class="text-center font-monospace">{$cod_cli}</td>
            <td class="text-center font-monospace">{$val_cantidad}</td>
HTML;
        
        // Inyección controlada de formatos numéricos corregidos
        $out .= '<td class="text-end font-monospace">' . number_format($val_costo, 2, ".", ",") . '</td>';
        $out .= '<td class="text-end font-monospace">' . number_format($val_precio, 2, ".", ",") . '</td>';
        $out .= '<td class="text-center text-nowrap">' . $fecha . '</td>';
        $out .= '</tr>';

        // Límite en pantalla (Top 20)
        if($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="9" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas. Para ver el reporte completo, por favor usa el botón de exportar.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Totales de pie de tabla
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="9" class="text-end table-light py-3 pe-4 text-secondary">
                    Total ítems Procesados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>