<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $articulo    = isset($articulo) ? trim($articulo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

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

    // 2. Consulta SQL optimizada para obtener los registros del inventario
    $sql = "SELECT 
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

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        // Obtenemos de forma complementaria el costo y el precio para cada artículo
        $idArt = $row["id"];
        $sql_art = "SELECT ultimo_costo, precio FROM articulo WHERE id = " . intval($idArt);
        $rs2 = mysqli_query($link, $sql_art);
        
        $costo = 0.00;
        $precio = 0.00;
        
        if ($rs2 && $row2 = mysqli_fetch_array($rs2)) {
            $costo  = floatval($row2["ultimo_costo"] ?? 0);
            $precio = floatval($row2["precio"] ?? 0);
        }

        $row['costo'] = $costo;
        $row['precio'] = $precio;       
        $developer_records[] = $row;
    }

// 3. Configurar cabeceras HTTP de descarga forzada para Excel (.xls)
    $filename = "INVENTARIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Marca UTF-8 BOM para forzar acentos y caracteres especiales limpios
    echo "\xEF\xBB\xBF"; 
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Inventario</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
    .texto { mso-number-format:"\@"; }
    .num { mso-number-format:"0\.00"; text-align: right; }
    .entero { mso-number-format:"0"; text-align: center; }
    th { background-color: #002060; color: #FFFFFF; font-weight: bold; text-align: center; border: 0.5pt solid #000000; }
    td { border: 0.5pt solid #D9D9D9; vertical-align: middle; }
</style>
</head>
<body>
<table border="1">
    <thead>
        <tr>
            <th>LABORATORIO</th>
            <th>ARTICULO</th>
            <th>CODIGO</th>
            <th>CODIGO BARRA</th>
            <th>UNIDAD MEDIDA</th>
            <th>ENTRADAS</th>
            <th>SALIDA</th>
            <th>EXISTENCIA</th>
            <th>PEDIDO</th>
            <th>COSTO C/U</th>
            <th>PRECIO C/U</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $laboratorio     = trim($row["laboratorio"] ?? '');
        $codigo          = trim($row["codigo"] ?? '');
        $codigo_de_barra = trim($row["codigo_de_barra"] ?? '');
        $unidad_medida   = trim($row["unidad_medida"] ?? 'UNIDAD');
        
        $nombre_comercial = trim($row["nombre_comercial"] ?? '');
        $principio_activo = trim($row["principio_activo"] ?? '');
        $presentacion     = trim($row["presentacion"] ?? '');
        $articulo         = trim($nombre_comercial . ' ' . $principio_activo . ' ' . $presentacion);

        // Conversión a valores numéricos flotantes puros
        $entradas   = (float)($row["entradas"] ?? 0);
        $salidas    = (float)($row["salidas"] ?? 0);
        $existencia = (float)($row["existencia"] ?? 0);
        $pedido     = (float)($row["cantidad_en_pedido"] ?? 0);
        $costo      = (float)($row["costo"] ?? 0);
        $precio     = (float)($row["precio"] ?? 0);

        echo "<tr>";
        echo "<td class='texto'>" . htmlspecialchars($laboratorio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($articulo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>{$codigo_de_barra}</td>";
        echo "<td class='texto'>{$unidad_medida}</td>";
        
        // Celdas de números enteros/cantidades
        echo "<td class='entero'>{$entradas}</td>";
        echo "<td class='entero'>{$salidas}</td>";
        echo "<td class='entero'>{$existencia}</td>";
        echo "<td class='entero'>{$pedido}</td>";
        
        // Celdas monetarias formateadas automáticamente según la región del usuario
        echo "<td class='num'>{$costo}</td>";
        echo "<td class='num'>{$precio}</td>";
        echo "</tr>";
    }
?>
    </tbody>
</table>
</body>
</html>
<?php
    exit();
?>