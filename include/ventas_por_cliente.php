<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    
    $where = "";
    if ($tipo !== "") {
        $where = "AND b.id = '" . mysqli_real_escape_string($link, $tipo) . "'";
    }
    
    $contar   = 0;
    $facturas = 0; 
    $monto    = 0.00;
    $unidades = 0;

    // 2. Encabezado de la tabla y botón de acción con diseño optimizado
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Ventas por Cliente</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 25%;">Ciudad</th>
                    <th scope="col" style="width: 43%;">Cliente</th>
                    <th scope="col" style="width: 10%;" class="text-end">Facturas</th>
                    <th scope="col" style="width: 11%;" class="text-end">Total</th>
                    <th scope="col" style="width: 11%;" class="text-end">Unidades</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL unificada
    $sql = "SELECT
                c.campo_descripcion AS ciudad, b.nombre AS cliente, 
                COUNT(a.nro_documento) AS facturas, 
                SUM(a.monto_total) AS total, SUM(a.unidades) AS unidades 
            FROM 
                salidas AS a 
                LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
                LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD' 
            WHERE 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
                AND a.estatus  = 'PROCESADO' 
                AND a.tipo_documento = 'TDCFCV' 
                AND IFNULL(a.documento, '') = 'FC' 
                $where 
            GROUP BY 
                c.campo_descripcion, b.nombre 
            ORDER BY 4 DESC;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Renderizado seguro del cuerpo de la tabla
    while($row = mysqli_fetch_array($rs)) {
        $ciudad_val  = trim($row["ciudad"] ?? '');
        $cliente_val = trim($row["cliente"] ?? '');
        
        // Formatos condicionales para registros vacíos
        $ciudad_disp  = !empty($ciudad_val) ? $ciudad_val : '<span class="text-muted">SIN CIUDAD</span>';
        $cliente_disp = !empty($cliente_val) ? $cliente_val : '<span class="text-muted">CLIENTE NO REGISTRADO</span>';

        // Castings y protección nula para PHP 8.1+
        $val_facturas = intval($row["facturas"] ?? 0);
        $val_total    = floatval($row["total"] ?? 0);
        $val_unidades = intval($row["unidades"] ?? 0);

        // Sumas acumuladas de control
        $contar++;
        $facturas += $val_facturas;
        $monto    += $val_total;
        $unidades += $val_unidades;

        // Formateo de salidas numéricas individuales
        $facturas_form = number_format($val_facturas, 0, "", ".");
        $total_form    = number_format($val_total, 2, ",", ".");
        $unidades_form = number_format($val_unidades, 0, "", ".");

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-nowrap">{$ciudad_disp}</td>
            <td class="text-uppercase text-wrap">{$cliente_disp}</td>
            <td class="text-end font-monospace text-secondary">{$facturas_form}</td>
            <td class="text-end font-monospace fw-semibold text-dark">{$total_form}</td>
            <td class="text-end font-monospace text-muted">{$unidades_form}</td>
        </tr>
HTML;
    }

    // 5. Totales acumulados al pie de la tabla
    $total_clientes_form = number_format($contar, 0, "", ".");
    $total_facturas_form = number_format($facturas, 0, "", ".");
    $total_monto_form    = number_format($monto, 2, ",", ".");
    $total_unidades_form = number_format($unidades, 0, "", ".");

    $out .= <<<HTML
            <tr>
                <th colspan="2" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Clientes: <span class="text-dark">{$total_clientes_form}</span>
                </th>
                <th class="text-end font-monospace table-light py-3 text-secondary">{$total_facturas_form}</th>
                <th class="text-end font-monospace table-light py-3 text-primary">{$total_monto_form}</th>
                <th class="text-end font-monospace table-light py-3 text-muted">{$total_unidades_form}</th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>