<?php 
    // 1. Preparar condiciones dinámicas
    $where = "";
    if (!empty($tipo)) {
        $where = " AND c.tarifa = " . intval($tipo);
    }
    
    // Cálculo dinámico del período anterior para el Inventario Inicial
    $anoi = substr($fecha_desde, 0, 4);
    $mesi = substr($fecha_desde, 5, 2);

    if ($mesi == "01") {
        $fecha_desde_i = strval(intval($anoi) - 1) . "-12-01";
        $fecha_hasta_i = strval(intval($anoi) - 1) . "-12-31";
    } else { 
        $mesi = str_pad(intval($mesi) - 1, 2, "0", STR_PAD_LEFT);
        $fecha_desde_i = $anoi . "-$mesi-01"; 

        if ($mesi == "02") {
            if ((intval($anoi) % 4 == 0 && intval($anoi) % 100 != 0) || intval($anoi) % 400 == 0) {
                $fecha_hasta_i = $anoi . "-$mesi-29";
            } else {
                $fecha_hasta_i = $anoi . "-$mesi-28";
            }
        } elseif (in_array($mesi, ["04", "06", "09", "11"])) {
            $fecha_hasta_i = $anoi . "-$mesi-30";
        } else {
            $fecha_hasta_i = $anoi . "-$mesi-31";
        }
    }

    // Mantener el rango histórico completo si así estaba definido originalmente
    $fecha_desde_i = "2020-01-01";

    $contar = 0;

    // 2. Encabezado de sección y tabla responsiva Bootstrap 5
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Kardex de Inventario</h4>
        <div>
            <button class="btn btn-primary" onclick="window.location.href='listado_master_buscar_excel.php?id={$id}&fd={$fecha_desde}&fh={$fecha_hasta}&tipo={$tipo}'">
                <i class="bi bi-file-earmark-excel"></i> Exportar a TXT/XLS
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle table-bordered" style="font-size: 0.78rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-center text-nowrap" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th rowspan="2" class="align-middle">Fabricante</th>
                    <th rowspan="2" class="align-middle">Nombre Comercial</th>
                    <th rowspan="2" class="align-middle">Principio Activo</th>
                    <th rowspan="2" class="align-middle">Presentación</th>
                    <th colspan="3" class="table-primary text-dark py-1">Inventario Inicial</th>
                    <th colspan="3" class="table-warning text-dark py-1">Movimientos del Período</th>
                    <th colspan="3" class="table-success text-dark py-1">Inventario Final</th>
                </tr>
                <tr>
                    <th>Exist.</th>
                    <th>Costo U.</th>
                    <th>Total Costo</th>
                    <th>Exist.</th>
                    <th>Costo U.</th>
                    <th>Total Costo</th>
                    <th>Exist.</th>
                    <th>Costo U.</th>
                    <th>Total Costo</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. CONSULTA CORREGIDA: Se resuelven las fechas uniendo las tablas de origen (entradas/salidas)
    $sql = "SELECT 
                c.nombre AS fabricante,
                x.nombre_comercial,
                x.principio_activo,
                x.presentacion,
                x.ultimo_costo,
                
                -- Inventario Inicial consolidado
                (IFNULL(inv_ini.entradas, 0) - IFNULL(inv_ini.salidas, 0)) AS exit_ii,
                IFNULL(inv_ini.costo_ult, 0) AS costo_und_ii,
                
                -- Movimientos del período actual consolidado
                (IFNULL(movs.entradas, 0) - IFNULL(movs.salidas, 0)) AS exit_mov,
                IFNULL(movs.costo_ult, 0) AS costo_und_mov,
                
                -- Variables de cálculo directo de flujos para el inventario final
                IFNULL(inv_ini.entradas, 0) AS ent_ii,
                IFNULL(inv_ini.salidas, 0) AS sal_ii,
                IFNULL(movs.entradas, 0) AS ent_mov,
                IFNULL(movs.salidas, 0) AS sal_mov
                
            FROM articulo AS x
            LEFT OUTER JOIN fabricante AS c ON c.Id = x.fabricante
            
            -- Subconsulta corregida para Inventario Inicial
            LEFT JOIN (
                SELECT 
                    es.articulo,
                    SUM(IF(es.tipo_documento IN ('TDCNRP', 'TDCAEN'), es.cantidad_movimiento, 0)) AS entradas,
                    SUM(IF(es.tipo_documento IN ('TDCNNET', 'TDCASA'), ABS(es.cantidad_movimiento), 0)) AS salidas,
                    (
                        SELECT cc.costo_unidad 
                        FROM entradas_salidas cc 
                        JOIN salidas cs ON cs.id = cc.id_documento AND cs.tipo_documento = cc.tipo_documento
                        WHERE cc.articulo = es.articulo 
                          AND cs.tipo_documento = 'TDCFCV' AND cs.estatus = 'PROCESADO'
                          AND cs.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 23:59:59'
                        ORDER BY cc.id DESC LIMIT 1
                    ) AS costo_ult
                FROM entradas_salidas es
                LEFT JOIN entradas e ON e.id = es.id_documento AND e.tipo_documento = es.tipo_documento
                LEFT JOIN salidas s ON s.id = es.id_documento AND s.tipo_documento = es.tipo_documento
                WHERE (e.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 23:59:59')
                   OR (s.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 23:59:59')
                GROUP BY es.articulo
            ) AS inv_ini ON inv_ini.articulo = x.id
            
            -- Subconsulta corregida para Período en Consulta
            LEFT JOIN (
                SELECT 
                    es.articulo,
                    SUM(IF(es.tipo_documento IN ('TDCNRP', 'TDCAEN'), es.cantidad_movimiento, 0)) AS entradas,
                    SUM(IF(es.tipo_documento IN ('TDCNNET', 'TDCASA'), ABS(es.cantidad_movimiento), 0)) AS salidas,
                    (
                        SELECT cc.costo_unidad 
                        FROM entradas_salidas cc 
                        JOIN salidas cs ON cs.id = cc.id_documento AND cs.tipo_documento = cc.tipo_documento
                        WHERE cc.articulo = es.articulo 
                          AND cs.tipo_documento = 'TDCFCV' AND cs.estatus = 'PROCESADO'
                          AND cs.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                        ORDER BY cc.id DESC LIMIT 1
                    ) AS costo_ult
                FROM entradas_salidas es
                LEFT JOIN entradas e ON e.id = es.id_documento AND e.tipo_documento = es.tipo_documento
                LEFT JOIN salidas s ON s.id = es.id_documento AND s.tipo_documento = es.tipo_documento
                WHERE (e.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59')
                   OR (s.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59')
                GROUP BY es.articulo
            ) AS movs ON movs.articulo = x.id
            
            WHERE 1=1 $where
            ORDER BY c.nombre, x.principio_activo, x.presentacion;";

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Renderizado iterativo de filas
    while ($row = mysqli_fetch_array($rs)) {
        
        $fabricante  = trim($row["fabricante"] ?? 'SIN FABRICANTE');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');

        // Valores calculados del inventario inicial
        $exit_ii      = floatval($row["exit_ii"]);
        $costo_und_ii = floatval($row["costo_und_ii"]);
        $costo_ii     = abs($exit_ii * $costo_und_ii);

        // Valores de los movimientos intermedios
        $exit_mov      = floatval($row["exit_mov"]);
        $costo_und_mov = floatval($row["costo_und_mov"]);
        $costo_mov     = abs($exit_mov * $costo_und_mov);

        // Lógica de derivación del Inventario Final conforme a tu código fuente
        $ent_ii  = floatval($row["ent_ii"]);
        $sal_ii  = floatval($row["sal_ii"]);
        $ent_mov = floatval($row["ent_mov"]);
        $sal_mov = floatval($row["sal_mov"]);
        
        $exit_if      = ($ent_ii + $ent_mov) - ($sal_ii + $sal_mov);
        $costo_und_if = floatval($row["ultimo_costo"] ?? 0);
        $costo_if     = abs($exit_if * $costo_und_if);

        $out .= <<<HTML
        <tr>
            <td class="text-uppercase text-wrap">{$fabricante}</td>
            <td class="text-uppercase text-wrap">{$comercial}</td>
            <td class="text-uppercase text-wrap">{$principio}</td>
            <td class="text-uppercase text-wrap">{$presentacio}</td>
            
            <td class="text-center font-monospace table-primary">{$exit_ii}</td>
            <td class="text-end font-monospace table-primary">\$ {number_format($costo_und_ii, 2, ".", ",")}</td>
            <td class="text-end font-monospace table-primary fw-bold">\$ {number_format($costo_ii, 2, ".", ",")}</td>
            
            <td class="text-center font-monospace table-warning">{$exit_mov}</td>
            <td class="text-end font-monospace table-warning">\$ {number_format($costo_und_mov, 2, ".", ",")}</td>
            <td class="text-end font-monospace table-warning fw-bold">\$ {number_format($costo_mov, 2, ".", ",")}</td>
            
            <td class="text-center font-monospace table-success fw-bold">{$exit_if}</td>
            <td class="text-end font-monospace table-success">\$ {number_format($costo_und_if, 2, ".", ",")}</td>
            <td class="text-end font-monospace table-success fw-bold">\$ {number_format($costo_if, 2, ".", ",")}</td>
        </tr>
HTML;

        $contar++;

        // Control de visualización en pantalla (Top 20)
        if ($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="13" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas en pantalla. Para revisar la totalidad del Kardex, por favor presiona el botón de exportación superior.
                </th>
            </tr>
HTML;
            break;
        }
    }

    // 5. Cierre y totales
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="13" class="text-end table-light py-3 pe-4 text-secondary">
                    Total Ítems Procesados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>