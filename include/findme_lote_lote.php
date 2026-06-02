<?php
/**
 * findme_lote.php
 * Retorna la metadata del artículo seleccionado y la lista de lotes con stock activo.
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";

$response = [
    "success" => false,
    "articulo" => null,
    "lotes" => [],
    "error" => ""
];

try {
    $codart = (int)($_REQUEST["id"] ?? 0);

    if ($codart <= 0) {
        throw new Exception("Identificador de artículo no válido.");
    }

    // 1. Obtener tipo de documento para control de inventario
    $tipo_documento_inventario = 'TDCNET';
    $resParam = $link->query("SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';");
    if ($resParam && $rowParam = $resParam->fetch_assoc()) {
        $tipo_documento_inventario = $rowParam["tipo_documento"];
    }

    // 2. Metadata base del Artículo
    $sqlArt = "SELECT 
                    b.nombre AS fabricante, 
                    CONCAT(IFNULL(a.principio_activo, ''), ', ', 
                           IFNULL(a.presentacion, ''), ', ', 
                           IFNULL(a.nombre_comercial, '')) AS articulo 
                FROM 
                    articulo AS a 
                    LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                WHERE 
                    a.id = ?;";
    
    $stmtArt = $link->prepare($sqlArt);
    if (!$stmtArt) throw new Exception("Error de preparación: " . $link->error);
    
    $stmtArt->bind_param("i", $codart);
    $stmtArt->execute();
    $resArt = $stmtArt->get_result()->fetch_assoc();
    $stmtArt->close();

    if (!$resArt) {
        throw new Exception("Artículo no encontrado.");
    }

    $response["articulo"] = [
        "id" => $codart,
        "fabricante" => $resArt["fabricante"],
        "nombre" => $resArt["articulo"]
    ];

    // 3. Consulta unificada de Existencias por Lotes y Almacenes con movimiento
    $sqlLotes = "SELECT 
                  x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, SUM(x.cantidad_movimiento) AS cantidad 
                FROM 
                  (
                    SELECT 
                       a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
                       IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
                       a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
                    FROM 
                       entradas_salidas AS a 
                       JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                       JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                       ((a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') 
                       OR (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO'))
                       AND a.articulo = ? AND a.newdata = 'S' 
                    
                    UNION ALL 
                    
                    SELECT 
                       a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
                       IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
                       a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
                    FROM 
                       entradas_salidas AS a 
                       JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                       JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                       (a.tipo_documento IN (?, 'TDCASA') AND b.estatus <> 'ANULADO') 
                       AND a.articulo = ? AND a.newdata = 'S' 
                  ) AS x 
                WHERE 1 
                GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
                HAVING SUM(x.cantidad_movimiento) <> 0 
                ORDER BY x.fecha_vencimiento, x.lote ASC;";

    $stmtLotes = $link->prepare($sqlLotes);
    if (!$stmtLotes) throw new Exception("Error de lotes: " . $link->error);
    
    $stmtLotes->bind_param("isi", $codart, $tipo_documento_inventario, $codart);
    $stmtLotes->execute();
    $resLotes = $stmtLotes->get_result();

    while ($rowLote = $resLotes->fetch_assoc()) {
        $cantidad = (int)$rowLote["cantidad"];
        if ($cantidad > 0) {
            $response["lotes"][] = [
                "lote" => $rowLote["lote"],
                "fecha_vencimiento" => $rowLote["fecha_vencimiento"],
                "fecha_formateada" => ($rowLote["fecha"] === '01/01/1990' ? '' : $rowLote["fecha"]),
                "cantidad" => $cantidad,
                "codigo_almacen" => $rowLote["codalm"],
                "almacen" => $rowLote["almacen"],
                // Mantenemos este string compuesto en el JSON por si tu JS actual parsea el value con split('|')
                "value_string" => $rowLote["lote"] . "|" . $rowLote["fecha_vencimiento"] . "|" . $cantidad . "|" . $rowLote["codalm"]
            ];
        }
    }
    $stmtLotes->close();
    $response["success"] = true;

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;