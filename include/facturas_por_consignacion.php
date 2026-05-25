<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" de clientes múltiple
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND a.cliente IN ($tipo_clean)";
        }
    }
    
    // 2. Encabezado de la página, botón de acción y tabla responsiva
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Facturas por Consignación</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 25%;">Cliente</th>
                    <th scope="col" style="width: 15%;">Documento</th>
                    <th scope="col" style="width: 10%;">Código</th>
                    <th scope="col" style="width: 26%;">Artículo</th>
                    <th scope="col" style="width: 8%;" class="text-end">Cantidad</th>
                    <th scope="col" style="width: 8%;" class="text-end">Precio</th>
                    <th scope="col" style="width: 8%;" class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL optimizada
    $sql = "SELECT 
                g.nombre AS cliente, 
                a.nro_documento, 
                d.codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
                ABS(b.cantidad_movimiento) AS cantidad_entregada, 
                b.precio_unidad, 
                b.precio  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV') 
                AND a.estatus = 'PROCESADO' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND a.consignacion = 'S' 
                $where 
            ORDER BY g.nombre, a.nro_documento, c.nombre, d.principio_activo, d.presentacion;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $cnt       = 0;
    $contar    = 0;
    $facturado = 0.00;

    // 4. Renderizado seguro del cuerpo de la tabla
    while($row = mysqli_fetch_array($rs)) {
        // Resoluciones de cadenas con fusión de nulos (PHP 8.1+)
        $row_cliente   = trim($row["cliente"] ?? '');
        $row_documento = trim($row["nro_documento"] ?? '');
        $row_codigo    = trim($row["codigo"] ?? '');
        $row_articulo  = trim($row["articulo"] ?? '');

        // Conversiones de tipo seguras
        $row_cantidad      = intval($row["cantidad_entregada"] ?? 0);
        $row_precio_unidad = floatval($row["precio_unidad"] ?? 0);
        $row_precio_total  = floatval($row["precio"] ?? 0);

        // Acumuladores de control
        $cnt++;
        $contar    += $row_cantidad;
        $facturado += $row_precio_total;

        // Formateo de salidas individuales
        $cantidad_form      = number_format($row_cantidad, 0, "", ".");
        $precio_unidad_form = number_format($row_precio_unidad, 2, ",", ".");
        $precio_total_form  = number_format($row_precio_total, 2, ",", ".");

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-wrap">{$row_cliente}</td>
            <td class="font-monospace text-nowrap">{$row_documento}</td>
            <td class="font-monospace text-secondary">{$row_codigo}</td>
            <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.8rem;">{$row_articulo}</td>
            <td class="text-end font-monospace">{$cantidad_form}</td>
            <td class="text-end font-monospace text-dark">{$precio_unidad_form}</td>
            <td class="text-end font-monospace fw-semibold text-dark">{$precio_total_form}</td>
        </tr>
HTML;
    }

    // 5. Cálculos para la fila de totales generales estructurada
    $total_items    = number_format($cnt, 0, "", ".");
    $total_cantidad = number_format($contar, 0, "", ".");
    $total_monto    = number_format($facturado, 2, ",", ".");

    $out .= <<<HTML
        </tbody>
        <tfoot class="table-light fw-bold" style="border-top: 2px solid #dee2e6;">
            <tr>
                <td colspan="4" class="text-end text-secondary py-3 pe-4">
                    Ítems Facturados: <span class="text-dark">{$total_items}</span> | Totales:
                </td>
                <td class="text-end font-monospace text-dark">{$total_cantidad}</td>
                <td></td>
                <td class="text-end font-monospace text-primary">{$total_monto}</td>
            </tr>
        </tfoot>
    </table>
</div>
HTML;
?>