<?php 
    // 1. Preparar condiciones dinámicas
    $where = "";
    if (!empty($tipo)) {
        // Sanitizar array o string de categorías
        $where = " AND b.articulo IN (" . $tipo . ")";
    }
    
    // 2. Encabezado de sección y tabla responsiva Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Entradas Generales por Artículo Detallado</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Documento</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col" style="min-width: 180px;">Proveedor</th>
                    <th scope="col" class="text-center">Código</th>
                    <th scope="col" style="min-width: 150px;">Nombre Comercial</th>
                    <th scope="col">Principio Activo</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Fabricante</th>
                    <th scope="col" class="text-center">Cant.</th>
                    <th scope="col" class="text-end">Costo U.</th>
                    <th scope="col" class="text-end">Costo Total</th>
                    <th scope="col" class="text-center">Estatus</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS proveedor, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                b.lote, 
                b.fecha_vencimiento, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                b.precio_unidad_sin_desc AS costo_ful, 
                b.descuento, 
                b.costo_unidad, 
                b.costo, 
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus  
            FROM 
                entradas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN proveedor AS g ON g.id = a.proveedor 
            WHERE 
                (
                    (a.tipo_documento = 'TDCNRP' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
                    OR (a.tipo_documento = 'TDCAEN' AND a.estatus IN ('NUEVO', 'PROCESADO'))
                )
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $contar = 0;
    
    // 4. Renderizado iterativo de las filas
    while ($row = mysqli_fetch_array($rs)) {
        $contar++;
        
        $tipo_doc    = trim($row["tipo"] ?? 'N/A');
        $nro_doc     = trim($row["nro_documento"] ?? '');
        $fecha       = trim($row["fecha"] ?? '');
        $proveedor   = trim($row["proveedor"] ?? 'SIN PROVEEDOR');
        $codart      = trim($row["codart"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');
        $fabricante  = trim($row["fabricante"] ?? 'SIN FABRICANTE');
        $estatus     = trim($row["estatus"] ?? '');

        // Formateo y resguardo numérico seguro para PHP 8
        $val_cantidad = floatval($row["cantidad_movimiento"] ?? 0);
        $val_costo_u  = floatval($row["costo_unidad"] ?? 0);
        $val_costo_t  = floatval($row["costo"] ?? 0);

        // Clases de estatus estilizadas
        $badge_class = ($estatus === 'PROCESADO') ? 'bg-success text-wrap' : 'bg-warning text-dark';

        $out .= <<<HTML
        <tr>
            <td class="text-nowrap text-uppercase">{$tipo_doc}</td>
            <td class="font-monospace fw-bold">{$nro_doc}</td>
            <td class="text-center text-nowrap">{$fecha}</td>
            <td class="text-uppercase text-wrap">{$proveedor}</td>
            <td class="text-center font-monospace">{$codart}</td>
            <td class="text-uppercase text-wrap">{$comercial}</td>
            <td class="text-uppercase text-wrap">{$principio}</td>
            <td class="text-uppercase text-wrap">{$presentacio}</td>
            <td class="text-uppercase text-wrap">{$fabricante}</td>
            <td class="text-center font-monospace">{$val_cantidad}</td>
HTML;
        
        // Inyección de formatos de moneda
        $out .= '<td class="text-end font-monospace">$' . number_format($val_costo_u, 2, ".", ",") . '</td>';
        $out .= '<td class="text-end font-monospace">$' . number_format($val_costo_t, 2, ".", ",") . '</td>';
        $out .= '<td class="text-center"><span class="badge ' . $badge_class . '" style="font-size:0.65rem;">' . $estatus . '</span></td>';
        $out .= '</tr>';

        // Límite visual preventivo en pantalla (Top 20)
        if ($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="13" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas en pantalla. Para exportar el universo completo de artículos, por favor usa el botón superior de descarga.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Cierre y totales de pie de página
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="13" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Registros Renderizados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>