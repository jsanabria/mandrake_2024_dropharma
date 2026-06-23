<?php

namespace PHPMaker2024\mandrake;

// Page object
$ListadoMasterGeneral = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
    // 1. Inicializar y sanitizar parámetros de entrada para evitar "Undefined variable"
    $id          = isset($_GET["id"]) ? trim($_GET["id"]) : '';
    $codigo      = isset($_GET["codigo"]) ? trim($_GET["codigo"]) : '';
    $fecha_desde = isset($_REQUEST["fecha_desde"]) ? trim($_REQUEST["fecha_desde"]) : '';
    $fecha_hasta = isset($_REQUEST["fecha_hasta"]) ? trim($_REQUEST["fecha_hasta"]) : '';
    $tipo        = isset($_REQUEST["tipo"]) ? trim($_REQUEST["tipo"]) : '';

    $out = '';

    // Sanitizar parámetros de fecha y ID para la cláusula WHERE
    $where = "";

    switch($id) {
        case "vta_articulo":
            if ($codigo !== "") {
                // Sanitizar para permitir solo números y comas en la cláusula IN
                $codigo_clean = preg_replace('/[^0-9,]/', '', $codigo);
                if (!empty($codigo_clean)) {
                    $where = "AND d.id IN ($codigo_clean)";
                }
            }
            
            // Construcción del encabezado de la tabla responsiva estilo premium
            $out .= <<<HTML
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" style="font-size: 0.875rem; border: 1px solid #dee2e6;">
                    <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th scope="col" style="width: 15%;">Nombre Comercial</th>
                            <th scope="col" style="width: 15%;">Principio Activo</th>
                            <th scope="col" style="width: 10%;">Presentación</th>
                            <th scope="col" style="width: 12%;">Fabricante</th>
                            <th scope="col" style="width: 10%;">Documento</th>
                            <th scope="col" style="width: 8%;" class="text-center">Fecha</th>
                            <th scope="col" style="width: 18%;">Cliente</th>
                            <th scope="col" style="width: 6%;" class="text-end">Cantidad</th>
                            <th scope="col" style="width: 6%;" class="text-end">Precio Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;

            $sql = "SELECT
                        d.id, 
                        a.nro_documento, 
                        date_format(a.fecha, '%d/%m/%Y') AS fecha, b.precio_unidad, 
                        d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante,  
                        g.id AS codigo, 
                        g.nombre AS cliente, 
                        SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
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
                    GROUP BY d.id, a.nro_documento, date_format(a.fecha, '%d/%m/%Y'), b.precio_unidad, g.id, g.nombre, c.nombre  
                    ORDER BY cantidad_movimiento DESC;"; 

            $rows = ExecuteRows($sql);
            $contar = count($rows);

            $art = 0;
            for($i = 0; $i < $contar; $i++) {
                $row = $rows[$i];

                // Asignaciones seguras contra nulos (PHP 8.1+)
                $n_comercial  = trim($row["nombre_comercial"] ?? '');
                $p_activo     = trim($row["principio_activo"] ?? '');
                $presentacion = trim($row["presentacion"] ?? '');
                $fabricante   = trim($row["fabricante"] ?? '');
                $nro_doc      = trim($row["nro_documento"] ?? '');
                $fecha        = trim($row["fecha"] ?? '');
                $cliente      = trim($row["cliente"] ?? '');

                // Conversiones seguras de valores numéricos
                $raw_cant     = intval($row["cantidad_movimiento"] ?? 0);
                $raw_precio_u = floatval($row["precio_unidad"] ?? 0);

                $art += $raw_cant;

                // Formateo de números
                $cant_form     = number_format($raw_cant, 0, "", ".");
                $precio_u_form = number_format($raw_precio_u, 2, ",", ".");

                // Placeholders para celdas vacías
                $n_comercial_val  = !empty($n_comercial) ? $n_comercial : '<span class="text-muted">-</span>';
                $p_activo_val     = !empty($p_activo) ? $p_activo : '<span class="text-muted">-</span>';
                $presentacion_val = !empty($presentacion) ? $presentacion : '<span class="text-muted">-</span>';
                $fabricante_val   = !empty($fabricante) ? $fabricante : '<span class="text-muted">-</span>';
                $cliente_val      = !empty($cliente) ? $cliente : '<span class="text-muted">-</span>';

                $out .= <<<HTML
                <tr>
                    <td class="text-uppercase text-wrap">{$n_comercial_val}</td>
                    <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.8rem;">{$p_activo_val}</td>
                    <td class="text-uppercase">{$presentacion_val}</td>
                    <td class="text-uppercase text-muted">{$fabricante_val}</td>
                    <td class="font-monospace">{$nro_doc}</td>
                    <td class="text-center text-nowrap">{$fecha}</td>
                    <td class="text-uppercase text-wrap">{$cliente_val}</td>
                    <td class="text-end font-monospace fw-semibold">{$cant_form}</td>
                    <td class="text-end font-monospace text-dark">{$precio_u_form}</td>
                </tr>
HTML;
            }

            $total_clientes  = number_format($contar, 0, "", ".");
            $total_unidades  = number_format($art, 0, "", ".");

            $out .= <<<HTML
                    </tbody>
                    <tfoot class="table-light fw-bold" style="border-top: 2px solid #dee2e6;">
                        <tr>
                            <td colspan="7" class="text-end text-secondary py-3 pe-4">
                                Clientes con Compras: <span class="text-dark">{$total_clientes}</span> | Total Unidades:
                            </td>
                            <td class="text-end font-monospace text-primary">{$total_unidades}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
HTML;
            break;

        case "sal_articulo":
        case "sal_articulo_neas":
            if ($codigo !== "") {
                $where = "AND d.id = " . intval($codigo);
            }

            // Encabezado común para las salidas de artículos detalladas (15 columnas de alta densidad)
            $out .= <<<HTML
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" style="font-size: 0.78rem; border: 1px solid #dee2e6;">
                    <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Documento</th>
                            <th scope="col" class="text-center">Fecha</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Código</th>
                            <th scope="col">Nombre Comercial</th>
                            <th scope="col">Principio Activo</th>
                            <th scope="col">Presentación</th>
                            <th scope="col">Fabricante</th>
                            <th scope="col" class="text-end">Cant</th>
                            <th scope="col" class="text-end">Precio Unidad</th>
                            <th scope="col" class="text-end">Total Artículo</th>
                            <th scope="col" class="text-end">Total s/IVA</th>
                            <th scope="col" class="text-end">Total Factura</th>
                            <th scope="col" class="text-end">Unidades</th>
                        </tr>
                    </thead>
                    <tbody>
HTML;

            // Selección de consulta según el tipo de reporte ("sal_articulo" o "sal_articulo_neas")
            if ($id == "sal_articulo") {
                $sql = "SELECT
                            a.id, 
                            a.estatus, 
                            a.nro_documento, 
                            date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                            g.id AS codigo, 
                            g.nombre AS cliente,
                            c.nombre AS fabricante, 
                            ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                            b.precio_unidad, 
                            b.precio AS total_articulo, 
                            a.monto_total, a.total,  
                            (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
                            d.nombre_comercial, d.principio_activo, d.presentacion, d.codigo AS codart, 
                            a.unidades 
                        FROM 
                            salidas AS a 
                            JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                            LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                            LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                            LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
                        WHERE 
                            (
                                (a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND a.documento = 'FC') 
                                OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO' AND a.factura = 'S')
                            )
                            AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                            $where 
                        ORDER BY cantidad_movimiento DESC;"; 
            } else {
                // "sal_articulo_neas"
                $sql = "SELECT
                            a.id, 
                            a.estatus, 
                            a.nro_documento, 
                            date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                            g.id AS codigo, 
                            g.nombre AS cliente,
                            c.nombre AS fabricante, 
                            ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                            b.precio_unidad, 
                            b.precio AS total_articulo, 
                            a.monto_total, a.total,  
                            (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
                            d.nombre_comercial, d.principio_activo, d.presentacion, d.codigo AS codart, 
                            a.unidades 
                        FROM 
                            salidas AS a 
                            JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                            LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                            LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                            LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
                        WHERE 
                            (a.tipo_documento IN ('TDCNET', 'TDCASA') AND a.estatus IN ('NUEVO', 'PROCESADO')) 
                            AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                            $where 
                        ORDER BY cantidad_movimiento DESC;"; 
            }

            $rows = ExecuteRows($sql);
            $contar = count($rows);
              
            $art = 0;
            for($i = 0; $i < $contar; $i++) {
                $row = $rows[$i];

                $estatus_salida = strtoupper(trim($row["estatus"] ?? ""));

                $filaWarning = "";
                $iconoWarning = "";

                if ($estatus_salida == "NUEVO") {
                    $filaWarning = 'style="background-color:#fff3cd !important;"';
                    $iconoWarning = '<i class="fa-solid fa-triangle-exclamation text-warning me-1" title="NEA pendiente / documento en estatus NUEVO"></i>';
                }

                // Asignaciones seguras contra nulos (PHP 8.1+)
                $tipo_doc     = trim($row["tipo"] ?? '');
                $nro_doc      = trim($row["nro_documento"] ?? '');
                $fecha        = trim($row["fecha"] ?? '');
                $cliente      = trim($row["cliente"] ?? '');
                $cod_art      = trim($row["codart"] ?? '');
                $n_comercial  = trim($row["nombre_comercial"] ?? '');
                $p_activo     = trim($row["principio_activo"] ?? '');
                $presentacion = trim($row["presentacion"] ?? '');
                $fabricante   = trim($row["fabricante"] ?? '');

                // Conversiones seguras de valores numéricos
                $raw_cant       = intval($row["cantidad_movimiento"] ?? 0);
                $raw_precio_u   = floatval($row["precio_unidad"] ?? 0);
                $raw_total_art  = floatval($row["total_articulo"] ?? 0);
                $raw_monto_total = floatval($row["monto_total"] ?? 0);
                $raw_total      = floatval($row["total"] ?? 0);
                $raw_unidades   = intval($row["unidades"] ?? 0);

                $art += $raw_cant;

                // Formateo de números
                $cant_form        = number_format($raw_cant, 0, "", ".");
                $precio_u_form    = number_format($raw_precio_u, 2, ",", ".");
                $total_art_form   = number_format($raw_total_art, 2, ",", ".");
                $monto_total_form = number_format($raw_monto_total, 2, ",", ".");
                $total_form       = number_format($raw_total, 2, ",", ".");
                $unidades_form    = number_format($raw_unidades, 0, "", ".");

                // Placeholders para celdas vacías
                $cliente_val      = !empty($cliente) ? $cliente : '<span class="text-muted">-</span>';
                $n_comercial_val  = !empty($n_comercial) ? $n_comercial : '<span class="text-muted">-</span>';
                $p_activo_val     = !empty($p_activo) ? $p_activo : '<span class="text-muted">-</span>';
                $presentacion_val = !empty($presentacion) ? $presentacion : '<span class="text-muted">-</span>';
                $fabricante_val   = !empty($fabricante) ? $fabricante : '<span class="text-muted">-</span>';

                $out .= <<<HTML
                <tr {$filaWarning}>
                    <td class="text-nowrap">{$tipo_doc}</td>
                    <td class="font-monospace text-nowrap">{$iconoWarning}{$nro_doc}</td>
                    <td class="text-center text-nowrap">{$fecha}</td>
                    <td class="text-uppercase text-wrap">{$cliente_val}</td>
                    <td class="font-monospace text-secondary">{$cod_art}</td>
                    <td class="text-uppercase text-wrap">{$n_comercial_val}</td>
                    <td class="text-uppercase text-wrap text-secondary" style="font-size: 0.7rem;">{$p_activo_val}</td>
                    <td class="text-uppercase">{$presentacion_val}</td>
                    <td class="text-uppercase text-muted">{$fabricante_val}</td>
                    <td class="text-end font-monospace fw-semibold">{$cant_form}</td>
                    <td class="text-end font-monospace">{$precio_u_form}</td>
                    <td class="text-end font-monospace">{$total_art_form}</td>
                    <td class="text-end font-monospace">{$monto_total_form}</td>
                    <td class="text-end font-monospace">{$total_form}</td>
                    <td class="text-end font-monospace text-muted">{$unidades_form}</td>
                </tr>
HTML;
            }

            $total_registros = number_format($contar, 0, "", ".");
            $total_art       = number_format($art, 0, "", ".");

            $out .= <<<HTML
                    </tbody>
                    <tfoot class="table-light fw-bold" style="border-top: 2px solid #dee2e6;">
                        <tr>
                            <td colspan="9" class="text-end text-secondary py-3 pe-4">
                                Registros: <span class="text-dark">{$total_registros}</span> | Total Unidades:
                            </td>
                            <td class="text-end font-monospace text-primary">{$total_art}</td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
HTML;
            break;

        default:
            die("El reporte no existe...");
    }

    echo $out;
?>
<?= GetDebugMessage() ?>
