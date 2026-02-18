<?php 
include 'connect.php';

// 1. Obtener fecha de la URL o usar la de hoy por defecto
$fecha_hasta = isset($_GET['fecha_hasta']) ? mysqli_real_escape_string($link, $_GET['fecha_hasta']) : date("Y-m-d");

$where = "";

// 2. Obtener tipo de documento de parámetros
$sql_param = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050' LIMIT 1;";
$rs_p = mysqli_query($link, $sql_param);
$tipo_documento = 'TDCNET';
if($row_p = mysqli_fetch_array($rs_p)) $tipo_documento = $row_p["tipo_documento"];

// 3. Consulta principal de inventario
$sql = "SELECT 
            inventario.articulo, 
            CAST(LTRIM(RTRIM(IFNULL(inventario.lote, ''))) AS CHAR) AS lote, 
            DATE_FORMAT(inventario.fecha_vencimiento, '%m/%Y') AS fecha_vencimiento, 
            SUM(inventario.cantidad) AS cantidad 
        FROM 
            (
                SELECT 
                    a.articulo, a.lote, a.fecha_vencimiento, SUM(a.cantidad_movimiento) AS cantidad 
                FROM 
                    entradas_salidas AS a 
                    JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                    JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                WHERE 
                    (
                        (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                        (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                    ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
                    $where 
                GROUP BY a.articulo, a.lote, a.fecha_vencimiento 

                UNION ALL 

                SELECT 
                    a.articulo, a.lote, a.fecha_vencimiento, SUM(a.cantidad_movimiento) AS cantidad 
                FROM 
                    entradas_salidas AS a 
                    JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                    JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                WHERE 
                    (
                        (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                        (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                    ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
                    $where 
                GROUP BY a.articulo, a.lote, a.fecha_vencimiento 
            ) AS inventario 
        GROUP BY inventario.articulo, inventario.lote, DATE_FORMAT(inventario.fecha_vencimiento, '%m/%Y') 
        HAVING SUM(inventario.cantidad) > 0;"; 

$rs = mysqli_query($link, $sql);

if(!$rs) die("Error en DB: " . mysqli_error($link));

// 4. Preparar cabeceras Excel
$filename = "INVENTARIO_A2_" . str_replace("-", "", $fecha_hasta) . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// 5. Salida en formato Tabla HTML (Mejor apariencia en Excel)
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    .text{ mso-number-format:"\@"; } /* Fuerza formato texto en Excel para códigos */
    .num{ mso-number-format:"\#\,\#\#0\.00"; }
    th { background-color: #1f4e78; color: #ffffff; font-weight: bold; border: 0.5pt solid #000; }
    td { border: 0.5pt solid #ccc; }
</style>

<table>
    <thead>
        <tr>
            <th>Lote</th>
            <th>Vencimiento</th>
            <th>Código A2</th>
            <th>Barras</th>
            <th>Fabricante</th>
            <th>Descripción</th>
            <th>Cod Cat</th>
            <th>Categoría</th>
            <th>Costo</th>
            <th>Precio</th>
            <th>Alicuota</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
    <?php
    while($row = mysqli_fetch_assoc($rs)) {
        $idArt = $row["articulo"];
        $sql_art = "SELECT 
                        LTRIM(RTRIM(IFNULL(a.codigo, ''))) AS codigo, a.codigo_de_barra, b.nombre AS fabricante, 
                        LTRIM(CONCAT(IFNULL(a.nombre_comercial, ''), ' ', IFNULL(a.principio_activo, ''), ' ', IFNULL(a.presentacion, ''))) AS descripcion, 
                        a.categoria AS cod_cat, c.campo_descripcion AS categoria, 
                        a.ultimo_costo, a.precio, 
                        (SELECT alicuota FROM alicuota WHERE codigo = a.alicuota AND activo = 'S' LIMIT 1) AS alicuota 
                    FROM 
                        articulo AS a 
                        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                        LEFT OUTER JOIN tabla AS c ON c.campo_codigo = a.categoria AND c.tabla = 'CATEGORIA' 
                    WHERE a.id = $idArt LIMIT 1";
        
        $rs_art = mysqli_query($link, $sql_art);
        if($row2 = mysqli_fetch_assoc($rs_art)) {
            echo "<tr>";
            echo "<td class='text'>{$row['lote']}</td>";
            echo "<td>{$row['fecha_vencimiento']}</td>";
            echo "<td class='text'>".trim($row['lote'])."-".trim($row2['codigo'])."</td>";
            echo "<td class='text'>{$row2['codigo_de_barra']}</td>";
            echo "<td>".htmlspecialchars($row2['fabricante'] ?? "")."</td>";
            echo "<td>".htmlspecialchars($row2['descripcion'] ?? "")."</td>";
            echo "<td>{$row2['cod_cat']}</td>";
            echo "<td>".htmlspecialchars($row2['categoria'] ?? "")."</td>";
            echo "<td class='num'>".number_format($row2['ultimo_costo'], 2, '.', '')."</td>";
            echo "<td class='num'>".number_format($row2['precio'], 2, '.', '')."</td>";
            echo "<td>{$row2['alicuota']}</td>";
            echo "<td>{$row['cantidad']}</td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>