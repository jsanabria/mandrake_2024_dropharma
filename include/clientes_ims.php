<?php
    // 1. Preparar condiciones dinámicas (Corregido: Ahora sí se incluye en la consulta)
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND a.tarifa = '$tipo'"; 
    }
    
    $contar = 0;

    // 2. Construcción del encabezado de la tabla con mejor diseño
    $out .= <<<HTML
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" style="width: 8%;">Código</th>
                    <th scope="col" style="width: 22%;">Nombre</th>
                    <th scope="col" style="width: 30%;">Dirección</th>
                    <th scope="col" style="width: 12%;">Ciudad</th>
                    <th scope="col" style="width: 8%;" class="text-center">Estado</th>
                    <th scope="col" style="width: 10%;">Teléfono 1</th>
                    <th scope="col" style="width: 10%;">Teléfono 2</th>
                    <th scope="col" style="width: 10%;">RIF</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Ejecución de la consulta SQL
    $sql = "SELECT DISTINCT  
                b.id AS codigo, 
                b.nombre, b.direccion, 
                c.campo_descripcion AS ciudad, 
                '' AS estado, 
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
                $where_tarifa;"; 

    $rs = mysqli_query($link, $sql);

    // 4. Renderizado del cuerpo de la tabla (Bucle)
    while($row = mysqli_fetch_array($rs)) {
        $contar++;
        
        // Validación para campos vacíos (como el Estado o Teléfono 2)
        $estado = !empty($row['estado']) ? $row['estado'] : '<span class="text-muted">-</span>';
        $telefono2 = !empty($row['telefono2']) ? $row['telefono2'] : '<span class="text-muted">-</span>';
        
        $out .= <<<HTML
        <tr>
            <td class="fw-bold text-secondary">{$row['codigo']}</td>
            <td class="text-wrap text-uppercase">{$row['nombre']}</td>
            <td class="text-muted text-wrap" style="font-size: 0.8rem; line-height: 1.3;">{$row['direccion']}</td>
            <td>{$row['ciudad']}</td>
            <td class="text-center">{$estado}</td>
            <td class="text-nowrap">{$row['telefono1']}</td>
            <td class="text-nowrap">{$telefono2}</td>
            <td class="text-nowrap font-monospace">{$row['ci_rif']}</td>
        </tr>
HTML;

        // Límite de visualización en pantalla
        if($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="8" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas. Para ver el reporte completo, por favor usa el botón de exportar.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Fila final de totales
    $total_clientes = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="8" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Clientes Visualizados: <span class="text-dark">{$total_clientes}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;

    // echo $out;
?>