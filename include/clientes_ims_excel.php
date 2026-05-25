<?php
    // 1. Recibir parámetros de la URL
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $fecha_desde = isset($_GET['fd']) ? $_GET['fd'] : '';
    $fecha_hasta = isset($_GET['fh']) ? $_GET['fh'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    // 2. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)
    $filename = "reporte_clientes_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Incluir el archivo de conexión (Asegúrate de cambiarlo por tu ruta real)
    // include_once("conexion.php"); 

    // 3. Preparar condiciones dinámicas
    $where_tarifa = "";
    if (!empty($tipo)) {
        $where_tarifa = "AND a.tarifa = '$tipo'"; 
    }

    // 4. Consulta SQL (Sin el 'die' y sin límite de 20 registros)
    $sql = "SELECT DISTINCT  
                b.id AS codigo, 
                b.nombre, b.direccion, 
                c.campo_descripcion AS ciudad, 
                '' AS estado, 
                b.telefono1, b.telefono2, b.ci_rif 
            FROM 
                salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
                LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
            WHERE 
                a.tipo_documento = 'TDCFCV' 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where_tarifa;"; 

    $rs = mysqli_query($link, $sql);

    // 5. Definir títulos de las columnas (Separados por tabulación \t)
    echo "CODIGO\tNOMBRE\tDIRECCION\tCIUDAD\tESTADO\tTELEFONO 1\tTELEFONO 2\tRIF\n";

    // 6. Recorrer el universo completo de datos
    while($row = mysqli_fetch_array($rs)) {
        // 1. Forzar conversión a string y resolver nulos
        $codigo    = trim($row["codigo"] ?? '');
        $nombre    = trim($row["nombre"] ?? '');
        $direccion = trim($row["direccion"] ?? '');
        $ciudad    = trim($row["ciudad"] ?? '');
        $estado    = trim($row["estado"] ?? '');
        $telefono1 = trim($row["telefono1"] ?? '');
        $telefono2 = trim($row["telefono2"] ?? '');
        $ci_rif    = trim($row["ci_rif"] ?? '');

        // 2. Sanitizar profundamente textos largos para evitar saltos de línea, tabuladores y comillas conflictivas
        $nombre    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $nombre);
        $direccion = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $direccion);
        $ciudad    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);

        // 3. Imprimir fila formateada para Excel
        echo "{$codigo}\t{$nombre}\t{$direccion}\t{$ciudad}\t{$estado}\t{$telefono1}\t{$telefono2}\t{$ci_rif}\n";
    }

    exit();
?>