<?php
/**
 * Reporte de Entradas de Consignación
 * Este script procesa las entradas de consignación pendientes de reporte.
 */

$reporte = $id;
$CurrentUserName = "admin";

// Filtro por fabricante si es necesario
$where = "";
if(trim($tipo) != "") {
    $where = " AND b.fabricante = '" . mysqli_real_escape_string($link, $tipo) . "'";
}

// Limpieza de tabla temporal previa
$sql_clear = "DELETE FROM temp_consignacion WHERE username = '" . mysqli_real_escape_string($link, $CurrentUserName) . "';";
mysqli_query($link, $sql_clear);

// Consulta principal para obtener entradas de consignación no reportadas
$sql = "SELECT 
            b.fabricante, b.articulo, b.id, a.nro_documento, b.cantidad_movimiento, 
            a.id AS id_documento, a.tipo_documento 
        FROM 
            entradas AS a 
            JOIN entradas_salidas AS b ON b.id_documento = a.id 
                AND b.tipo_documento = a.tipo_documento 
        WHERE 
            a.consignacion = 'S' 
            AND a.consignacion_reportada = 'N' 
            AND a.tipo_documento IN ('TDCNRP', 'TDCINP') 
            $where";

$rs = mysqli_query($link, $sql);

// Generación de encabezado de tabla
$out = '<table class="table table-hover table-bordered">';
$out .= '<thead><tr>';
$out .= '<th>TIPO DOC</th><th>NRO DOC</th><th>FABRICANTE</th><th>CODIGO</th><th>ARTICULO</th>';
$out .= '<th>CANT. MOV</th><th>CANT. ENTRE FECHAS</th><th>CANT. ACUMULADA</th><th>CANT. PENDIENTE</th>';
$out .= '</tr></thead><tbody>';

if ($rs) {
    while($row = mysqli_fetch_array($rs)) {
        // Lógica de cálculo de cantidades (pendiente de integrar con consulta de rangos si es necesario)
        $cantidad_movimiento = $row["cantidad_movimiento"];
        $cantidad_entre_fechas = 0; // Implementar lógica de fecha si aplica
        $cantidad_acumulada = 0;
        $cantidad_pendiente = $cantidad_movimiento - $cantidad_acumulada;

        $out .= '<tr>';
        $out .= '<td>' . $row["tipo_documento"] . '</td>';
        $out .= '<td>' . $row["nro_documento"] . '</td>';
        $out .= '<td>' . $row["fabricante"] . '</td>';
        $out .= '<td>' . $row["id"] . '</td>';
        $out .= '<td>' . $row["articulo"] . '</td>';
        $out .= '<td>' . number_format($cantidad_movimiento, 2) . '</td>';
        $out .= '<td>' . number_format($cantidad_entre_fechas, 2) . '</td>';
        $out .= '<td>' . number_format($cantidad_acumulada, 2) . '</td>';
        $out .= '<td>' . number_format($cantidad_pendiente, 2) . '</td>';
        $out .= '</tr>';
    }
}

$out .= '</tbody></table>';

echo $out;
?>