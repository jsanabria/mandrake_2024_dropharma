<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $id          = isset($id) ? trim($id) : '';
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $articulo    = isset($articulo) ? trim($articulo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $reporte = $id;

    $where = "";
    if ($tipo !== "") {
        $where = "AND a.almacen = '" . mysqli_real_escape_string($link, $tipo) . "'";
    }

    $where2 = "";
    if ($articulo !== "") {
        $where2 = "AND a.id = '" . mysqli_real_escape_string($link, $articulo) . "'";
        $where = "AND a.articulo = '" . mysqli_real_escape_string($link, $articulo) . "'";
    }

    // Obtener el tipo de documento por defecto desde los parámetros (Código 050)
    $sql_param = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
    $rs_param = mysqli_query($link, $sql_param);
    $tipo_documento = 'TDCNET';
    
    if ($rs_param && $row_param = mysqli_fetch_array($rs_param)) {
        $tipo_documento = $row_param["tipo_documento"];
    }
    
    $cnt = 0;

    // 2. Encabezado de la página, botón de acción y tabla responsiva compacta al 100% de ancho
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Existencia e Inventario entre Fechas</h4>
    </div>

    <div class="table-responsive overflow-auto" style="max-height: 75vh; border: 1px solid #dee2e6;">
        <table class="table table-sm table-hover table-striped align-middle m-0" style="font-size: 0.825rem; border-collapse: separate; border-spacing: 0; width: 100%;">
            <thead class="table-light text-uppercase position-sticky top-0 start-0 z-3 shadow-sm" style="font-size: 0.725rem; letter-spacing: 0.3px;">
                <tr>
                    <th scope="col" style="width: 14%;">Laboratorio</th>
                    <th scope="col" style="width: 24%;">Artículo</th>
                    <th scope="col" style="width: 8%;" class="text-nowrap">Código</th>
                    <th scope="col" style="width: 12%;" class="text-nowrap">Cód. Barra</th>
                    <th scope="col" style="width: 10%;">Uni. Med.</th>
                    <th scope="col" style="width: 8%;" class="text-end">Entradas</th>
                    <th scope="col" style="width: 8%;" class="text-end">Salidas</th>
                    <th scope="col" style="width: 8%;" class="text-end">Exist.</th>
                    <th scope="col" style="width: 8%;" class="text-end">Pedido</th>
                    <th scope="col" style="width: 4%;" class="text-end text-nowrap">Costo U.</th>
                    <th scope="col" style="width: 4%;" class="text-end text-nowrap">Precio U.</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL de Inventario unificada
    $sql_inv = "SELECT 
                    art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
                    'UNIDAD' AS unidad_medida, art.principio_activo, 
                    art.presentacion, art.nombre_comercial, art.cantidad_en_pedido, 
                    IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad,0)) AS salidas, 
                    (IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad,0))) AS existencia 
                FROM 
                    (
                        SELECT 
                            a.id, a.codigo, a.codigo_de_barra, b.nombre, 
                            'UNIDAD' AS unidad_medida, a.principio_activo, 
                            a.presentacion, a.nombre_comercial, a.cantidad_en_pedido 
                        FROM 
                            articulo AS a 
                            LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
                        WHERE 
                            0 = 0 $where2 
                    ) AS art 
                    LEFT OUTER JOIN 
                    (
                        SELECT 
                            a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                        FROM 
                            entradas_salidas AS a 
                            JOIN salidas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                -- (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                                (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                            ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
                            $where 
                        GROUP BY a.articulo
                    ) AS sal ON sal.articulo = art.Id 
                    LEFT OUTER JOIN 
                    (
                        SELECT 
                            a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                        FROM 
                            entradas_salidas AS a 
                            JOIN entradas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                                (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                            ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
                            $where 
                        GROUP BY a.articulo
                    ) AS ent ON ent.articulo = art.Id 
                WHERE 1 ORDER BY art.nombre, art.principio_activo, art.presentacion;"; 

    $rs = mysqli_query($link, $sql_inv);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Renderizado seguro de las filas del inventario
    while($row = mysqli_fetch_array($rs)) {
        $idArt           = $row["id"] ?? 0; 
        $laboratorio     = trim($row["laboratorio"] ?? ''); 
        $codigo          = trim($row["codigo"] ?? ''); 
        $codigo_de_barra = trim($row["codigo_de_barra"] ?? ''); 
        $unidad_medida   = trim($row["unidad_medida"] ?? 'UNIDAD');
        
        $nombre_comercial = trim($row["nombre_comercial"] ?? '');
        $principio_activo = trim($row["principio_activo"] ?? '');
        $presentacion     = trim($row["presentacion"] ?? '');
        $nombre = trim($nombre_comercial . ' ' . $principio_activo . ' ' . $presentacion);

        // Conversión y redondeo de cantidades de inventario
        $entradas   = floatval($row["entradas"] ?? 0);
        $salidas    = floatval($row["salidas"] ?? 0);
        $existencia = floatval($row["existencia"] ?? 0);
        $pedido = floatval($row["cantidad_en_pedido"] ?? 0);

        // Subconsulta para costo y precio del artículo actual (Escapado con intval)
        $sql_art = "SELECT ultimo_costo, precio FROM articulo WHERE id = " . intval($idArt);
        $rs2 = mysqli_query($link, $sql_art);
        
        $costo  = 0.00;
        $precio = 0.00;
        
        if ($rs2 && $row2 = mysqli_fetch_array($rs2)) {
            $costo  = floatval($row2["ultimo_costo"] ?? 0);
            $precio = floatval($row2["precio"] ?? 0);
        }

        // Formateo de salidas numéricas
        $entradas_form   = number_format($entradas, 2, '.', ',');
        $salidas_form    = number_format($salidas, 2, '.', ',');
        $existencia_form = number_format($existencia, 2, '.', ',');
        $pedido_form = number_format($pedido, 2, '.', ',');
        $costo_form      = number_format($costo, 2, ',', '.');
        $precio_form     = number_format($precio, 2, ',', '.');

        // Fallbacks visuales para celdas vacías
        $laboratorio_disp     = !empty($laboratorio) ? $laboratorio : '<span class="text-muted">-</span>';
        $codigo_disp          = !empty($codigo) ? $codigo : '<span class="text-muted">-</span>';
        $codigo_de_barra_disp = !empty($codigo_de_barra) ? $codigo_de_barra : '<span class="text-muted">-</span>';

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-wrap">{$laboratorio_disp}</td>
            <td class="text-uppercase text-wrap">{$nombre}</td>
            <td class="font-monospace fw-bold text-secondary">{$codigo_disp}</td>
            <td class="font-monospace text-muted">{$codigo_de_barra_disp}</td>
            <td class="text-uppercase">{$unidad_medida}</td>
            <td class="text-end font-monospace">{$entradas_form}</td>
            <td class="text-end font-monospace">{$salidas_form}</td>
            <td class="text-end font-monospace fw-semibold text-dark">{$existencia_form}</td>
            <td class="text-end font-monospace">{$pedido_form}</td>
            <td class="text-end font-monospace">{$costo_form}</td>
            <td class="text-end font-monospace text-primary">{$precio_form}</td>
        </tr>
HTML;

        $cnt++;
    }

    // 5. Pie de tabla con totalizadores
    $total_items = number_format($cnt, 0, "", ".");
    $out .= <<<HTML
            <tr class="position-sticky bottom-0 table-light evaluation-footer z-2 shadow-sm" style="border-top: 2px solid #dee2e6;">
                <th colspan="11" class="text-end py-3 pe-4 text-secondary">
                    Total Ítems: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;

    // Restaurar el ID del reporte original
    $id = $reporte;
?>