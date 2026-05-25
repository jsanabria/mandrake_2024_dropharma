<?php
    // 1. Forzar descarga de archivo excel de forma plana sin conversiones HTML molestas
    $filename = "PEDIDOS_DETALLADO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Preparar condiciones dinámicas de categorías
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.articulo IN (" . $tipo . ")";
    }

    // 3. Consulta de base de datos
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS cliente, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                a.tipo_documento = 'TDCPDV' AND a.estatus IN ('NUEVO', 'PROCESADO')
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Fila inicial de títulos planos delimitada por tabulaciones (\t)
    echo "TIPO\tDOCUMENTO\tFECHA\tCLIENTE\tCODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tFABRICANTE\tCANTIDAD\tESTATUS\n";

    // 5. Ciclo iterativo de datos con sanitización de strings
    while ($row = mysqli_fetch_assoc($rs)) {
        
        // Limpieza estricta de tabulaciones y saltos dentro de los textos
        $tipo        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["tipo"] ?? ''));
        $documento   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nro_documento"] ?? ''));
        $fecha       = trim($row["fecha"] ?? '');
        $cliente     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["cliente"] ?? ''));
        $codart      = trim($row["codart"] ?? '');
        $comercial   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $principio   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presentacio = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));
        $fabricante  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $estatus     = trim($row["estatus"] ?? '');

        // Obtener la cantidad numérica directa para evitar problemas de tipos en Excel
        $cantidad    = $row["cantidad_movimiento"] ?? 0;

        // Impresión en flujo estructurado
        echo "{$tipo}\t{$documento}\t{$fecha}\t{$cliente}\t{$codart}\t{$comercial}\t{$principio}\t{$presentacio}\t{$fabricante}\t{$cantidad}\t{$estatus}\n";
    }

    exit();
?>