<?php
    // 1. Configuración de Cabeceras HTTP limpias para descargas Excel sin alertas
    $filename = "KARDEX_INVENTARIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Control dinámico de parámetros de filtrado
    $where = "";
    if (!empty($tipo)) {
        $where = "AND c.tarifa = " . intval($tipo);
    }

    // Desglosar fechas para cálculo previo
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

    // 3. CONSULTA SERIALIZADA DE ALTO RENDIMIENTO
    $sql = "SELECT 
                x.id,
                c.nombre AS fabricante,
                x.nombre_comercial,
                x.principio_activo,
                x.presentacion,
                IFNULL(inv_ini.exit_ii, 0) AS exit_ii,
                IFNULL(inv_ini.costo_und_ii, 0) AS costo_und_ii,
                IFNULL(inv_ini.costo_ii, 0) AS costo_ii,
                IFNULL(movs.exit_mov, 0) AS exit_mov,
                IFNULL(movs.costo_und_mov, 0) AS costo_und_mov,
                IFNULL(movs.costo_mov, 0) AS costo_mov
            FROM articulo AS x
            LEFT OUTER JOIN fabricante AS c ON c.Id = x.fabricante
            LEFT JOIN (
                SELECT 
                    articulo,
                    SUM(cantidad_movimiento) AS exit_ii,
                    AVG(costo_unidad) AS costo_und_ii,
                    SUM(cantidad_movimiento * costo_unidad) AS costo_ii
                FROM entradas_salidas
                WHERE fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 23:59:59'
                GROUP BY articulo
            ) AS inv_ini ON inv_ini.articulo = x.id
            LEFT JOIN (
                SELECT 
                    articulo,
                    SUM(cantidad_movimiento) AS exit_mov,
                    AVG(costo_unidad) AS costo_und_mov,
                    SUM(cantidad_movimiento * costo_unidad) AS costo_mov
                FROM entradas_salidas
                WHERE fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                GROUP BY articulo
            ) AS movs ON movs.articulo = x.id
            WHERE 1=1 $where
            ORDER BY c.nombre, x.principio_activo, x.presentacion;";

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Encabezados planos delimitados por tabulaciones (\t)
    echo "ID\tFABRICANTE\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tEXISTENCIA II\tCOSTO UNIDAD II\tCOSTO II\tEXISTENCIA MOV\tCOSTO UND MOV\tCOSTO MOV\tEXISTENCIA IF\tCOSTO UNIDAD IF\tCOSTO IF\n";

    // 5. Volcado iterativo sanitizado
    while($row = mysqli_fetch_assoc($rs)) {
        $id         = $row["id"] ?? '';
        $fabricante = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $comercial  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $activo     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presenta   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));

        // Números puros
        $exit_ii      = floatval($row["exit_ii"]);
        $costo_und_ii = floatval($row["costo_und_ii"]);
        $costo_ii     = floatval($row["costo_ii"]);

        $exit_mov      = floatval($row["exit_mov"]);
        $costo_und_mov = floatval($row["costo_und_mov"]);
        $costo_mov     = floatval($row["costo_mov"]);

        // Derivaciones finales
        $exit_if  = $exit_ii + $exit_mov;
        $costo_if = $costo_ii + $costo_mov;
        $costo_und_if = ($exit_if != 0) ? ($costo_if / $exit_if) : 0;

        // Escritura de la línea de datos tabulada
        echo "{$id}\t{$fabricante}\t{$comercial}\t{$activo}\t{$presenta}\t{$exit_ii}\t{$costo_und_ii}\t{$costo_ii}\t{$exit_mov}\t{$costo_und_mov}\t{$costo_mov}\t{$exit_if}\t{$costo_und_if}\t{$costo_if}\n";
    }

    exit();
?>