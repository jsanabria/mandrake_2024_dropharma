<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números en caso de filtrar por ID de Asesor
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND a.cliente = $tipo_clean";
        }
    }

    // 2. Consulta SQL limpia (el formateo de cadenas se realiza en PHP para optimizar el rendimiento de la base de datos)
    $sql = "SELECT  
                DISTINCT b.id AS codigo, 
                b.nombre, 
                b.direccion,
                c.campo_descripcion AS ciudad, 
                b.telefono1, 
                b.telefono2, 
                b.ci_rif, 
                d.nombre AS asesor 
            FROM 
                salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
                LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
                JOIN asesor_cliente AS cc ON cc.cliente = a.cliente $where 
                JOIN asesor AS d ON d.id = cc.asesor 
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
            ORDER BY b.nombre;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP de descarga forzada para Excel (.xls)
    $filename = "MAECLI_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Imprimir los títulos de las columnas (separados por tabulación \t)
    echo "CODIGO\tNOMBRE\tDIRECCION\tCIUDAD\tTELEFONO 1\tTELEFONO 2\tRIF\tASESOR\n";

    // 5. Volcado e impresión sanitizada de los registros
    foreach ($developer_records as $row) {
        // Asignaciones seguras con operador de fusión de nulos (PHP 8.1+)
        $codigo    = trim($row["codigo"] ?? '');
        $nombre    = trim($row["nombre"] ?? '');
        $direccion = trim($row["direccion"] ?? '');
        $ciudad    = trim($row["ciudad"] ?? '');
        $telefono1 = trim($row["telefono1"] ?? '');
        $telefono2 = trim($row["telefono2"] ?? '');
        $ci_rif    = trim($row["ci_rif"] ?? '');
        $asesor    = trim($row["asesor"] ?? '');

        // Limpiar strings de caracteres de control o tabuladores que rompan el diseño en Excel
        $nombre    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $nombre);
        $direccion = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $direccion);
        $ciudad    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);
        $asesor    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $asesor);

        // Limpieza específica para números telefónicos y RIF (remover guiones, espacios y paréntesis)
        $telefono1 = str_replace(array(' ', '-', '(', ')', '"', "\t", "\n", "\r"), '', $telefono1);
        $telefono2 = str_replace(array(' ', '-', '(', ')', '"', "\t", "\n", "\r"), '', $telefono2);
        $ci_rif    = str_replace(array(' ', '-', '"', "\t", "\n", "\r"), '', $ci_rif);

        // Imprimir fila formateada por delimitador de tabulaciones
        echo "{$codigo}\t{$nombre}\t{$direccion}\t{$ciudad}\t{$telefono1}\t{$telefono2}\t{$ci_rif}\t{$asesor}\n";
    }

    exit();
?>