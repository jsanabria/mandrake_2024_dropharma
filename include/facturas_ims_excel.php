<?php 
    // 1. Recibir parámetros de la URL
    $fecha_desde = isset($_GET['fd']) ? $_GET['fd'] : '';
    $fecha_hasta = isset($_GET['fh']) ? $_GET['fh'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    // 2. Preparar condiciones dinámicas
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND c.tarifa = '$tipo'";
    }

    // 3. Consulta SQL
    $join_tarifa = "";
    $where_tarifa = "";
    
    if (!empty($tipo)) {
        // Solo acoplamos la tabla fabricante si el filtro viene activo
        $join_tarifa = "INNER JOIN fabricante AS c ON c.Id = b.fabricante";
        $where_tarifa = "AND c.tarifa = '$tipo'";
    }

    $sql = "SELECT 
                LPAD(a.nro_documento, 12, '0') AS codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo, 
                d.id AS codigo_articulo, 
                g.nombre AS cliente, 
                g.id AS codigo_cliente, 
                ABS(b.cantidad_movimiento) AS cantidad, 
                (b.precio_unidad / a.tasa_dia) AS precio_unidad, 
				(b.precio / a.tasa_dia) AS precio, 
                DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha 
            FROM 
                salidas AS a 
                -- Forzamos la conversión explícita de tipos si id_documento es VARCHAR e id es INT
                INNER JOIN entradas_salidas AS b 
                    ON b.tipo_documento = a.tipo_documento 
                    AND b.id_documento = a.id 
                INNER JOIN articulo AS d ON d.id = b.articulo 
                INNER JOIN cliente AS g ON g.id = a.cliente 
                $join_tarifa
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.documento = 'FC' 
                AND a.estatus = 'PROCESADO'
                AND a.fecha BETWEEN '{$fecha_desde} 00:00:00' AND '{$fecha_hasta} 23:59:59'
                $where_tarifa;";


    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 4. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)
    $filename = "FACMES_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 5. Definir títulos de las columnas (Separados por tabulación \t)
    echo "PRODUCTO\tCLIENTE\tCANTIDAD\tPRECIO UNIDAD\tPRECIO TOTAL\tFECHA\n";

    // 6. Recorrer el universo completo de datos recolectados sin truncados
    foreach ($developer_records as $row) {
        // Blindaje contra Null (PHP 8.1+) asignando 0 de respaldo para operaciones y formato
        $producto      = trim($row["articulo"] ?? '');
        $cliente       = trim($row["cliente"] ?? '');
        $cantidad      = $row["cantidad"] ?? 0;
        $precio_unidad = $row["precio_unidad"] ?? 0;
        $precio_total  = $row["precio"] ?? 0;
        $fecha         = trim($row["fecha"] ?? '');

        // Sanitización de textos para evitar desfases de columnas por comillas o saltos de línea
        $producto      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $producto);
        $cliente       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);

        // Imprimir fila formateada para Excel
        echo "{$producto}\t{$cliente}\t{$cantidad}\t{$precio_unidad}\t{$precio_total}\t{$fecha}\n";
    }
    exit();
?>