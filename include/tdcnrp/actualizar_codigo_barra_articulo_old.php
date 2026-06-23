<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

include "../connect.php";

$response = [
    "success" => false,
    "data" => null,
    "error" => ""
];

try {
    $accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : "";
    $articulo = isset($_POST["articulo"]) ? intval($_POST["articulo"]) : 0;
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";

    if ($articulo <= 0) {
        throw new Exception("Artículo inválido.");
    }

    if ($username === "") {
        throw new Exception("Usuario inválido.");
    }

    $username_sql = mysqli_real_escape_string($link, $username);
    $sqlPermiso = "SELECT valor1 AS usuario 
                   FROM parametro 
                   WHERE codigo = '045' 
                     AND valor1 = '$username_sql'
                   LIMIT 1;";
    $rsPermiso = mysqli_query($link, $sqlPermiso);

    if (!$rsPermiso || !mysqli_fetch_array($rsPermiso)) {
        throw new Exception("Usuario no autorizado para modificar código de barra.");
    }

    if ($accion === "get") {
        $sql = "SELECT 
                    a.codigo,
                    b.nombre AS fabricante,
                    a.nombre_comercial,
                    a.principio_activo AS nombre_articulo,
                    a.presentacion,
                    IFNULL(a.codigo_de_barra, '') AS codigo_de_barra
                FROM articulo AS a
                    JOIN fabricante AS b ON b.Id = a.fabricante
                WHERE a.id = ?;";

        $stmt = mysqli_prepare($link, $sql);
        if (!$stmt) {
            throw new Exception(mysqli_error($link));
        }

        mysqli_stmt_bind_param($stmt, "i", $articulo);
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($rs);
        mysqli_stmt_close($stmt);

        if (!$row) {
            throw new Exception("Artículo no encontrado.");
        }

        $response["success"] = true;
        $response["data"] = $row;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($accion === "update") {
        $codigo_de_barra = isset($_POST["codigo_de_barra"]) ? trim($_POST["codigo_de_barra"]) : "";

        $sql = "UPDATE articulo 
                SET codigo_de_barra = ?
                WHERE id = ?;";

        $stmt = mysqli_prepare($link, $sql);
        if (!$stmt) {
            throw new Exception(mysqli_error($link));
        }

        mysqli_stmt_bind_param($stmt, "si", $codigo_de_barra, $articulo);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        $response["success"] = true;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    throw new Exception("Acción inválida.");

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
