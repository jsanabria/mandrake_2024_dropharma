<?php 
function CostoUltimo($articulo, $link) {  
    // Buscamos directamente el precio unitario del registro más reciente con check_ne = 'S'
    $sql = "SELECT precio_unidad_sin_desc AS ultimo_costo 
            FROM entradas_salidas 
            WHERE tipo_documento = 'TDCNRP' AND articulo = $articulo AND check_ne = 'S' 
            ORDER BY id DESC LIMIT 1;";
            
    $rs = mysqli_query($link, $sql);
    
    if ($rs && mysqli_num_rows($rs) > 0) {
        $row = mysqli_fetch_array($rs);
        $ultimo_costo = $row["ultimo_costo"];

        // Actualizamos la tabla (Nota: asegúrate si tu tabla se llama 'articulo' o 'articulos')
        $sqlUpdate = "UPDATE articulo SET ultimo_costo = $ultimo_costo WHERE id = $articulo AND newdata = 'S';";  
        mysqli_query($link, $sqlUpdate);
    }
}

function CostoPromedio($articulo, $link) {  
    // Definimos la cantidad de últimas compras a promediar (ej. 5) y la fecha de corte
    $limite_compras = 5;
    $fecha_corte = '2025-01-01';

    // Consulta que une detalle y cabecera para filtrar por fecha y limitar el historial
    $sql = "SELECT IFNULL(AVG(precio_unidad_sin_desc), 0) AS costo_promedio FROM (
                SELECT es.precio_unidad_sin_desc 
                FROM entradas_salidas AS es
                JOIN entradas AS e ON es.id_documento = e.id AND es.tipo_documento = e.tipo_documento
                WHERE es.tipo_documento = 'TDCNRP' 
                  AND es.articulo = $articulo 
                  AND es.check_ne = 'S'
                  AND e.fecha >= '$fecha_corte'
                  AND IFNULL(es.precio_unidad_sin_desc, 0) > 0
                ORDER BY es.id DESC 
                LIMIT $limite_compras
            ) AS ultimas_compras;";
            
    $rs = mysqli_query($link, $sql);
    
    if ($rs) {
        $row = mysqli_fetch_array($rs);
        $costo_promedio = $row["costo_promedio"];

        // Actualizamos el costo en la tabla articulo
        $sqlUpdate = "UPDATE articulo SET ultimo_costo = $costo_promedio WHERE id = $articulo;";  
        mysqli_query($link, $sqlUpdate);
    }
}

function CostoPromedioPonderado($articulo, $link) {
    // 1. Obtener la existencia actual y el costo actual del artículo
    $sqlArt = "SELECT cantidad_en_mano, ultimo_costo FROM articulo WHERE id = $articulo;";
    $rsArt = mysqli_query($link, $sqlArt);
    if (!$rsArt || mysqli_num_rows($rsArt) == 0) return;
    
    $rowArt = mysqli_fetch_array($rsArt);
    $existencia_actual = (float)$rowArt["cantidad_en_mano"];
    $costo_actual = (float)$rowArt["ultimo_costo"];

    // 2. Obtener la última recepción pendiente de ponderar o la más reciente (desde la fecha de corte)
    // Unimos con 'entradas' para asegurar la fecha >= '2025-01-01'
    $sqlUltima = "SELECT es.cantidad_articulo, es.precio_unidad_sin_desc 
                  FROM entradas_salidas AS es
                  JOIN entradas AS e ON es.id_documento = e.id AND es.tipo_documento = e.tipo_documento
                  WHERE es.tipo_documento = 'TDCNRP' 
                    AND es.articulo = $articulo 
                    AND es.check_ne = 'S' 
                    AND e.fecha >= '2025-01-01'
                    AND IFNULL(es.precio_unidad_sin_desc, 0) > 0
                  ORDER BY es.id DESC 
                  LIMIT 1;";
                  
    $rsUltima = mysqli_query($link, $sqlUltima);
    if (!$rsUltima || mysqli_num_rows($rsUltima) == 0) return;

    $rowUltima = mysqli_fetch_array($rsUltima);
    $cantidad_nueva = (float)$rowUltima["cantidad_articulo"];
    $costo_nuevo = (float)$rowUltima["precio_unidad_sin_desc"];

    // 3. Aplicar la fórmula de Promedio Ponderado
    // Cuidado con dividir entre cero si la existencia más la nueva cantidad es 0
    $denominador = $existencia_actual + $cantidad_nueva;
    
    if ($denominador > 0) {
        $costo_ponderado = (($existencia_actual * $costo_actual) + ($cantidad_nueva * $costo_nuevo)) / $denominador;
    } else {
        $costo_ponderado = $costo_nuevo; // Si no hay inventario previo, vale el nuevo costo
    }

    // 4. Actualizar el nuevo costo ponderado en la tabla articulo
    $sqlUpdate = "UPDATE articulo SET ultimo_costo = $costo_ponderado WHERE id = $articulo;";
    mysqli_query($link, $sqlUpdate);
}

?>