<?php
/**
 * findme_procesador_calculos.php
 * Refactorización funcional de findme_cabecera_totales.php y findme_detalle.php sin salida HTML.
 */

function calcularYObtenerDetalleJSON($link, $tipo_documento, $id_documento) {
    // 1. Obtener descuentos base del documento
    $stmt = $link->prepare("SELECT IFNULL(descuento,0) AS descuento, IFNULL(descuento2,0) AS descuento2, tasa_dia FROM salidas WHERE tipo_documento = ? AND id = ?;");
    $stmt->bind_param("si", $tipo_documento, $id_documento);
    $stmt->execute();
    $rowDoc = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $descuento = floatval($rowDoc["descuento"] ?? 0);
    $descuento2 = floatval($rowDoc["descuento2"] ?? 0);
    $tasa = floatval($rowDoc["tasa_dia"] ?? 1);

    // 2. Evaluar número de alícuotas distintas
    $stmt = $link->prepare("SELECT COUNT(DISTINCT alicuota) AS cantidad FROM entradas_salidas WHERE tipo_documento = ? AND id_documento = ?;");
    $stmt->bind_param("si", $tipo_documento, $id_documento);
    $stmt->execute();
    $cantAli = $stmt->get_result()->fetch_assoc()["cantidad"] ?? 0;
    $stmt->close();

    $alicuota = 0;
    if ((int)$cantAli === 1) {
        $stmt = $link->prepare("SELECT DISTINCT alicuota FROM entradas_salidas WHERE tipo_documento = ? AND id_documento = ?;");
        $stmt->bind_param("si", $tipo_documento, $id_documento);
        $stmt->execute();
        $alicuota = floatval($stmt->get_result()->fetch_assoc()["alicuota"] ?? 0);
        $stmt->close();
    }

    // 3. Totalizar Movimiento (Consultas matemáticas exactas)
    $sqlTotales = "SELECT
                SUM(precio) AS precio, 
                SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * (?/100)), 0)) AS exento, 
                SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (?/100)))) AS gravado, 
                SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (?/100))) * (IFNULL(alicuota,0)/100)) AS iva
            FROM entradas_salidas
            WHERE tipo_documento = ? AND id_documento = ?;";
            
    $stmtTot = $link->prepare($sqlTotales);
    $stmtTot->bind_param("dddss", $descuento, $descuento, $descuento, $tipo_documento, $id_documento);
    $stmtTot->execute();
    $rowCalc = $stmtTot->get_result()->fetch_assoc();
    $stmtTot->close();

    $exento = floatval($rowCalc["exento"] ?? 0);
    $gravado = floatval($rowCalc["gravado"] ?? 0);

    $exento = $exento - ($exento * ($descuento2 / 100));
    $gravado = $gravado - ($gravado * ($descuento2 / 100));

    $monto_sin_descuento = floatval($rowCalc["precio"] ?? 0);
    $precio = $exento + $gravado;
    $iva = $gravado * ($alicuota / 100);
    $total = $precio + $iva;
    $monto_usd = ($tasa > 0) ? ($total / $tasa) : 0;

    // 4. Escribir actualización física en la cabecera de la salida
    $sqlUpdCab = "UPDATE salidas SET monto_total = ?, alicuota_iva = ?, iva = ?, total = ?, tasa_dia = ?, monto_usd = ?, monto_sin_descuento = ? WHERE tipo_documento = ? AND id = ?;";
    $stmtUpdCab = $link->prepare($sqlUpdCab);
    $stmtUpdCab->bind_param("dddddddss", $precio, $alicuota, $iva, $total, $tasa, $monto_usd, $monto_sin_descuento, $tipo_documento, $id_documento);
    $stmtUpdCab->execute();
    $stmtUpdCab->close();

    // 5. Actualizar cantidad neta de unidades
    $sqlUpdUnits = "UPDATE salidas AS a 
                    JOIN (
                        SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                        FROM entradas_salidas 
                        WHERE tipo_documento = ? AND id_documento = ? 
                        GROUP BY id_documento, tipo_documento
                    ) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
                    SET a.unidades = b.cantidad 
                    WHERE a.id = ?;";
    $stmtUnits = $link->prepare($sqlUpdUnits);
    $stmtUnits->bind_param("sii", $tipo_documento, $id_documento, $id_documento);
    $stmtUnits->execute();
    $stmtUnits->close();

    // 6. Recopilar listado pormenorizado de ítems del detalle
    $sqlDetalle = "SELECT 
                        a.id, b.nombre AS fabricante, IFNULL(a.descuento,0) AS descuento_item, IFNULL(a.descuento2,0) AS descuento2_item,
                        CONCAT(IFNULL(c.principio_activo, ''), ', ', IFNULL(c.presentacion, ''), ', ', IFNULL(c.nombre_comercial, '')) AS articulo, 
                        a.cantidad_articulo, d.descripcion AS unidad_medida, d.cantidad AS equivalencia, a.articulo AS codart, 
                        a.lote, a.fecha_vencimiento, a.precio_unidad, a.precio  
                    FROM entradas_salidas AS a 
                    LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                    LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
                    LEFT OUTER JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida 
                    WHERE a.tipo_documento = ? AND a.id_documento = ? 
                    ORDER BY articulo;";
                    
    $stmtDet = $link->prepare($sqlDetalle);
    $stmtDet->bind_param("si", $tipo_documento, $id_documento);
    $stmtDet->execute();
    $resDet = $stmtDet->get_result();

    $items = [];
    while($row = $resDet->fetch_assoc()) {
        $items[] = [
            "id" => (int)$row["id"],
            "fabricante" => $row["fabricante"],
            "articulo" => $row["articulo"],
            "lote" => $row["lote"],
            "fecha_vencimiento" => ($row["fecha_vencimiento"] === "1990-01-01" ? "" : $row["fecha_vencimiento"]),
            "cantidad" => floatval($row["cantidad_articulo"]),
            "unidad_medida" => $row["unidad_medida"] . ', ' . $row["equivalencia"],
            "precio_unidad" => floatval($row["precio_unidad"]),
            "total_linea" => floatval($row["precio"])
        ];
    }
    $stmtDet->close();

    // Estructura de salida consolidada
    return [
        "resumen" => [
            "descuento_porcentaje" => $descuento,
            "monto_sin_descuento" => $monto_sin_descuento,
            "monto_exento" => $exento,
            "monto_gravado" => $gravado,
            "monto_neto" => $precio,
            "alicuota_iva" => $alicuota,
            "monto_iva" => $iva,
            "total_bs" => $total,
            "tasa_dia" => $tasa,
            "total_usd" => $monto_usd
        ],
        "items" => $items,
        "contador_items" => count($items)
    ];
}