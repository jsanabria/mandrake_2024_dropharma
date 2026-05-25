<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    
    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números en caso de filtrar por ID de Asesor
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND a.id = $tipo_clean";
        }
    }

    $contar = 0;

    // 2. Encabezado de la página, botón de acción y tabla responsiva
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Clientes sin Compras Recientes</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 8%;">Código</th>
                    <th scope="col" style="width: 22%;">Nombre</th>
                    <th scope="col" style="width: 25%;">Dirección</th>
                    <th scope="col" style="width: 12%;">Ciudad</th>
                    <th scope="col" style="width: 10%;">Teléfono 1</th>
                    <th scope="col" style="width: 10%;">Teléfono 2</th>
                    <th scope="col" style="width: 10%;">RIF</th>
                    <th scope="col" style="width: 13%;">Asesor</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL optimizada utilizando subconsultas limpias
    $sql = "SELECT 
                f.codigo, 
                f.nombre, f.direccion, 
                f.ciudad, 
                f.telefono1, f.telefono2, f.ci_rif,
                f.asesor 
            FROM 
                (SELECT 
                    a.id AS codigo, 
                    a.nombre, a.direccion, 
                    b.campo_descripcion AS ciudad, 
                    a.telefono1, a.telefono2, a.ci_rif, 
                    d.nombre AS asesor 
                FROM 
                    cliente AS a 
                    LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
                    JOIN asesor_cliente AS c ON c.cliente = a.id $where 
                    JOIN asesor AS d ON d.id = c.asesor) AS f 
                LEFT OUTER JOIN  
                (SELECT  
                    b.id AS codigo, 
                    b.nombre, b.direccion, 
                    c.campo_descripcion AS ciudad, 
                    b.telefono1, b.telefono2, b.ci_rif 
                FROM 
                    salidas AS a 
                    JOIN cliente AS b ON b.id = a.cliente 
                    LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
                WHERE 
                    a.tipo_documento = 'TDCFCV' 
                    AND a.estatus = 'PROCESADO'
                    AND a.documento = 'FC' 
                    AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                GROUP BY b.id, b.nombre, b.direccion, c.campo_descripcion, b.telefono1, b.telefono2, b.ci_rif, a.cliente) AS g 
                ON g.codigo = f.codigo 
            WHERE g.nombre IS NULL;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Renderizado seguro del cuerpo de la tabla
    while($row = mysqli_fetch_array($rs)) {
        $contar++;

        // Fusión de nulos para compatibilidad total con PHP 8.1+
        $row_codigo    = $row["codigo"] ?? '';
        $nombre        = trim($row["nombre"] ?? '');
        $direccion     = trim($row["direccion"] ?? '');
        $ciudad        = trim($row["ciudad"] ?? '');
        $telefono1     = trim($row["telefono1"] ?? '');
        $telefono2     = trim($row["telefono2"] ?? '');
        $ci_rif        = trim($row["ci_rif"] ?? '');
        $asesor        = trim($row["asesor"] ?? '');

        // Fallbacks visuales para textos vacíos
        $nombre_disp    = !empty($nombre) ? $nombre : '<span class="text-muted">-</span>';
        $direccion_disp = !empty($direccion) ? $direccion : '<span class="text-muted">-</span>';
        $ciudad_disp    = !empty($ciudad) ? $ciudad : '<span class="text-muted">-</span>';
        $telefono1_disp = !empty($telefono1) ? $telefono1 : '<span class="text-muted">-</span>';
        $telefono2_disp = !empty($telefono2) ? $telefono2 : '<span class="text-muted">-</span>';
        $ci_rif_disp    = !empty($ci_rif) ? $ci_rif : '<span class="text-muted">-</span>';
        $asesor_disp    = !empty($asesor) ? $asesor : '<span class="text-muted">-</span>';

        $out .= <<<HTML
        <tr>
            <td class="font-monospace fw-bold text-secondary">{$row_codigo}</td>
            <td class="text-uppercase text-wrap">{$nombre_disp}</td>
            <td class="text-uppercase text-wrap text-muted" style="font-size: 0.8rem; line-height: 1.3;">{$direccion_disp}</td>
            <td class="text-uppercase">{$ciudad_disp}</td>
            <td class="text-nowrap">{$telefono1_disp}</td>
            <td class="text-nowrap">{$telefono2_disp}</td>
            <td class="text-nowrap font-monospace text-uppercase">{$ci_rif_disp}</td>
            <td class="text-uppercase text-nowrap">{$asesor_disp}</td>
        </tr>
HTML;
    }

    // 5. Fila de resumen de totales al pie de la tabla
    $total_clientes = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="8" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Clientes sin Compras: <span class="text-dark">{$total_clientes}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>