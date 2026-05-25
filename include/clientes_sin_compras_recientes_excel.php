<?php
// Configuración de cabeceras para exportación a Excel
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=ClientesSinComprasRecientes.xls");
header("Pragma: no-cache");
header("Expires: 0");

include 'connect.php'; // Asegúrate de que este archivo contenga la conexión $link

$id = $_REQUEST["id"] ?? "";
$fecha_desde = $_REQUEST["fd"] ?? "";
$fecha_hasta = $_REQUEST["fh"] ?? "";
$tipo = $_REQUEST["tipo"] ?? ""; // Se asume que $tipo contiene el ID del asesor

$where = "";
if(trim($tipo) != "") {
    $where = "AND a.id = " . intval($tipo);
}

// Consulta SQL optimizada para identificar clientes sin compras en el periodo
$sql = "SELECT 
            f.codigo,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.nombre,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS nombre,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.direccion,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS direccion,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.ciudad,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS ciudad,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.telefono1,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS telefono1,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.telefono2,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS telefono2,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.ci_rif,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS ci_rif,
            REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.asesor,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS asesor 
        FROM 
        (SELECT 
                a.id AS codigo, 
                a.nombre, a.direccion, 
                b.campo_descripcion AS ciudad, 
                a.telefono1, a.telefono2, a.ci_rif,
                d.nombre AS asesor 
            FROM 
                cliente AS a 
                LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
                JOIN asesor_cliente AS c ON c.cliente = a.id $where 
                JOIN asesor AS d ON d.id = c.asesor) AS f 
        LEFT OUTER JOIN  
        (SELECT  
                b.id AS codigo 
            FROM 
                salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
            WHERE 
                a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
            GROUP BY b.id) g 
        ON g.codigo = f.codigo 
        WHERE g.codigo IS NULL";

$rs = mysqli_query($link, $sql);

if(!$rs) {
    die("Error en la consulta: " . mysqli_error($link));
}

// Generación del contenido de la tabla para Excel
echo "<table border='1'>";
echo "<tr>
        <th>CODIGO</th>
        <th>NOMBRE</th>
        <th>DIRECCION</th>
        <th>CIUDAD</th>
        <th>TELEFONO 1</th>
        <th>TELEFONO 2</th>
        <th>RIF</th>
        <th>ASESOR</th>
      </tr>";

while($row = mysqli_fetch_assoc($rs)) {
    echo "<tr>";
    echo "<td>" . $row["codigo"] . "</td>";
    echo "<td>" . $row["nombre"] . "</td>";
    echo "<td>" . $row["direccion"] . "</td>";
    echo "<td>" . $row["ciudad"] . "</td>";
    echo "<td>" . $row["telefono1"] . "</td>";
    echo "<td>" . $row["telefono2"] . "</td>";
    echo "<td>" . $row["ci_rif"] . "</td>";
    echo "<td>" . $row["asesor"] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>