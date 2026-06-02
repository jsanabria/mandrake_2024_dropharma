<?php
/**
 * findme_item.php
 * Estandarizado a JSON y blindado con Prepared Statements (MySQLi POO).
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";

$response = [
    "success" => false,
    "cant" => 0,
    "id" => 0,
    "data" => [],
    "error" => ""
];

try {
    $findme = trim($_REQUEST["findme"] ?? "");

    if ($findme === "") {
        throw new Exception("Coloque datos para realizar la búsqueda.");
    }

    $likeFindme = "%" . $findme . "%";

    $sql = "SELECT 
                a.id, 
                CONCAT(
                    IFNULL(a.nombre_comercial, ''), ' ', 
                    IFNULL(a.principio_activo, ''), ' ', 
                    IFNULL(a.presentacion, ''), ' (', 
                    IFNULL(b.nombre, ''), ')'
                ) AS articulo 
            FROM 
                articulo AS a 
                JOIN fabricante AS b ON b.Id = a.fabricante 
            WHERE 
                a.codigo = ? 
                OR a.codigo_de_barra = ? 
                OR a.principio_activo LIKE ? 
                OR a.nombre_comercial LIKE ? 
            ORDER BY 2;";

    $stmt = $link->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta de artículos: " . $link->error);
    }

    $stmt->bind_param("ssss", $findme, $findme, $likeFindme, $likeFindme);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = [
            "id" => (int)$row["id"],
            "articulo" => $row["articulo"]
        ];
    }
    $stmt->close();

    $cant = count($items);
    $response["success"] = true;
    $response["cant"] = $cant;
    $response["data"] = $items;
    
    if ($cant === 1) {
        $response["id"] = $items[0]["id"];
    }

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;