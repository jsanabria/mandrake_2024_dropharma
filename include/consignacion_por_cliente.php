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
        <h4 class="text-secondary m-0">Consignaciones por Cliente</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 20%;">Cliente</th>
                    <th scope="col" style="width: 10%;">Documento</th>
                    <th scope="col" style="width: 8%;">Código</th>
                    <th scope="col" style="width: 22%;">Artículo</th>
                    <th scope="col" style="width: 8%;" class="text-end">Entregado</th>
                    <th scope="col" style="width: 8%;" class="text-end">Facturado</th>
                    <th scope="col" style="width: 8%;" class="text-end">Pendiente</th>
                    <th scope="col" style="width: 8%;" class="text-end">Venta</th>
                    <th scope="col" style="width: 8%;" class="text-end">Factura</th>
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
                b.cantidad_movimiento_consignacion AS cantidad_facturada, 
                (ABS(b.cantidad_movimiento) - b.cantidad_movimiento_consignacion) AS cantidad_pendiente, 
                b.precio AS venta, 
                (b.precio_unidad * b.cantidad_movimiento_consignacion) AS facturado
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCNET') 
                AND a.estatus <> 'ANULADO' 
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
    $contar2   = 0;
    $venta     = 0.00;
    $facturado = 0.00;

    // 4. Renderizado seguro del cuerpo de la tabla
    while($row = mysqli_fetch_array($rs)) {
        // Resoluciones de cadenas con fusión de nulos (PHP 8.1+)
        $row_cliente   = trim($row["cliente"] ?? '');
        $row_documento = trim($row["nro_documento"] ?? '');
        $row_codigo    = trim($row["codigo"] ?? '');
        $row_articulo  = trim($row["articulo"] ?? '');

        // Validaciones numéricas seguras
        $row_entregada = intval($row["cantidad_entregada"] ?? 0);
        $row_facturada = intval($row["cantidad_facturada"] ?? 0);
        $row_pendiente = intval($row["cantidad_pendiente"] ?? 0);

        $row_venta     = floatval($row["venta"] ?? 0);
        $row_facturado = floatval($row["facturado"] ?? 0);

        // Acumuladores de control
        $cnt++;
        $contar    += $row_entregada;
        $contar2   += $row_facturada;
        $venta     += $row_venta;
        $facturado += $row_facturado;

        // Formateo de los campos numéricos de la fila
        $entregada_form = number_format($row_entregada, 0, "", ".");
        $facturada_form = number_format($row_facturada, 0, "", ".");
        $pendiente_form = number_format($row_pendiente, 0, "", ".");
        
        $venta_form     = number_format($row_venta, 2, ",", ".");
        $facturado_form = number_format($row_facturado, 2, ",", ".");

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-wrap">{$row_cliente}</td>
            <td class="font-monospace text-nowrap">{$row_documento}</td>
            <td class="font-monospace text-secondary">{$row_codigo}</td>
            <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.8rem;">{$row_articulo}</td>
            <td class="text-end font-monospace">{$entregada_form}</td>
            <td class="text-end font-monospace text-success">{$facturada_form}</td>
            <td class="text-end font-monospace text-danger">{$pendiente_form}</td>
            <td class="text-end font-monospace text-dark">{$venta_form}</td>
            <td class="text-end font-monospace fw-semibold text-dark">{$facturado_form}</td>
        </tr>
HTML;
    }

    // 5. Cálculos para la fila de totales generales alineados
    $total_items     = number_format($cnt, 0, "", ".");
    $total_entregado = number_format($contar, 0, "", ".");
    $total_facturado = number_format($contar2, 0, "", ".");
    $total_pendiente = number_format(($contar - $contar2), 0, "", ".");
    
    $total_venta_form     = number_format($venta, 2, ",", ".");
    $total_facturado_form = number_format($facturado, 2, ",", ".");

    $out .= <<<HTML
        </tbody>
        <tfoot class="table-light fw-bold" style="border-top: 2px solid #dee2e6;">
            <tr>
                <td colspan="4" class="text-end text-secondary py-3pe-4">
                    Ítems Procesados: <span class="text-dark">{$total_items}</span> | Totales:
                </td>
                <td class="text-end font-monospace">{$total_entregado}</td>
                <td class="text-end font-monospace text-success">{$total_facturado}</td>
                <td class="text-end font-monospace text-danger">{$total_pendiente}</td>
                <td class="text-end font-monospace text-dark">{$total_venta_form}</td>
                <td class="text-end font-monospace text-primary">{$total_facturado_form}</td>
            </tr>
        </tfoot>
    </table>
</div>
HTML;
?>