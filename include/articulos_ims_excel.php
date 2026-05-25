<?php 
    // 1. Recibir parámetros URL (Por si acaso no estaban inicializados)
    $fecha_desde = isset($_GET['fd']) ? $_GET['fd'] : '';
    $fecha_hasta = isset($_GET['fh']) ? $_GET['fh'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    // 2. Preparar condiciones dinámicas
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND f.tarifa = '$tipo'";
    } else {
        $where_tarifa = "AND f.tarifa = 2";
    }
    
    // 3. Consulta SQL
    $sql = "SELECT DISTINCT 
                b.articulo AS codigo, 
                LTRIM(REPLACE(REPLACE(CONCAT(REPLACE(IFNULL(d.nombre_comercial, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.principio_activo, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.presentacion, ' '), '\n', '')), '\n', ''), '\r', '')) AS nombre, 
                SUBSTRING(RPAD(IFNULL(c.nombre, ' '), 32, ' '), 1, 32) AS fabricante, 
                IFNULL(f.precio, 0) AS precio, 
                SUBSTRING(RPAD(IFNULL(d.codigo_de_barra, ' '), 13, '0'), 1, 13) AS codigo_de_barra  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN fabricante AS c ON c.Id = d.fabricante  
                LEFT OUTER JOIN tarifa_articulo AS f ON f.fabricante = d.fabricante AND f.articulo = b.articulo AND f.tarifa = 2 
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
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
    $filename = "MAEINV_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 5. Definir títulos de las columnas (Separados por tabulación \t)
    echo "CODIGO\tNOMBRE\tLABORATORIO\tPRECIO\tBARRA\n";

    // 6. Recorrer el universo completo de datos recolectados
    foreach ($developer_records as $row) {
        // Blindaje contra Null (PHP 8.1+) y limpieza de espacios
        $codigo     = trim($row["codigo"] ?? '');
        $nombre     = trim($row["nombre"] ?? '');
        $fabricante = trim($row["fabricante"] ?? '');
        $precio     = trim($row["precio"] ?? '0');
        $barra      = trim($row["codigo_de_barra"] ?? '');

        // Sanitización profunda para evitar que comillas o caracteres ocultos rompan las celdas
        $nombre     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $nombre);
        $fabricante = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);

        // Imprimir fila en formato plano para Excel
        echo "{$codigo}\t{$nombre}\t{$fabricante}\t{$precio}\t{$barra}\n";
    }

    exit();
?>