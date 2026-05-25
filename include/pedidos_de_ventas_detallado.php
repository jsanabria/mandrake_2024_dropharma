<?php 
    // 1. Preparar condiciones dinámicas de categorías
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.articulo IN (" . $tipo . ")";
    }
    
    // 2. Encabezado de sección y tabla responsiva Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Pedidos de Ventas Detallado</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle table-bordered" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap text-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Documento</th>
                    <th scope="col">Fecha</th>
                    <th scope="col" style="min-width: 180px;">Cliente</th>
                    <th scope="col">Código</th>
                    <th scope="col" style="min-width: 150px;">Nombre Comercial</th>
                    <th scope="col">Principio Activo</th>
                    <th scope="col">Presentación</th>
                    <th scope="col">Fabricante</th>
                    <th scope="col">Cant.</th>
                    <th scope="col">Estatus</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta de datos fiel a tu estructura original
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.id AS codigo, 
                g.nombre AS cliente, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                b.precio_unidad, 
                b.precio, 
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                (
                    (a.tipo_documento = 'TDCPDV' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
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
    
    // 4. Renderizado dinámico de filas con validación de nulos
    while ($row = mysqli_fetch_array($rs)) {
        
        $tipo_doc    = trim($row["tipo"] ?? 'N/A');
        $nro_doc     = trim($row["nro_documento"] ?? '');
        $fecha       = trim($row["fecha"] ?? '');
        $cliente     = trim($row["cliente"] ?? 'SIN CLIENTE');
        $codart      = trim($row["codart"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');
        $fabricante  = trim($row["fabricante"] ?? 'SIN FABRICANTE');
        $estatus     = trim($row["estatus"] ?? '');

        // Casteo de cantidad entera según tu archivo original
        $val_cantidad = intval($row["cantidad_movimiento"] ?? 0);

        // Clases dinámicas para badges de estatus
        $badge_class = ($estatus === 'PROCESADO') ? 'bg-success text-wrap' : 'bg-warning text-dark';

        $out .= <<<HTML
        <tr>
            <td class="text-nowrap text-uppercase text-secondary">{$tipo_doc}</td>
            <td class="font-monospace fw-bold text-center">{$nro_doc}</td>
            <td class="text-center text-nowrap">{$fecha}</td>
            <td class="text-uppercase text-wrap">{$cliente}</td>
            <td class="text-center font-monospace">{$codart}</td>
            <td class="text-uppercase text-wrap fw-bold">{$comercial}</td>
            <td class="text-uppercase text-wrap">{$principio}</td>
            <td class="text-uppercase text-wrap text-muted">{$presentacio}</td>
            <td class="text-uppercase text-wrap">{$fabricante}</td>
            <td class="text-center font-monospace fw-bold table-light">{$val_cantidad}</td>
            <td class="text-center"><span class="badge {$badge_class}" style="font-size:0.65rem;">{$estatus}</span></td>
        </tr>
HTML;

        $contar++;

        // Control de visualización (Top 20 en pantalla)
        if ($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="11" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas en pantalla. Para revisar la totalidad de los datos, por favor presiona el botón de exportación superior.
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
                <th colspan="11" class="text-end table-light py-3 pe-4 text-secondary">
                    Artículos: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>