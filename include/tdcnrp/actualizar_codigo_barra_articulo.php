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

    $sqlUsuario = "
        SELECT userlevelid
        FROM usuario
        WHERE username = '$username_sql'
        LIMIT 1
    ";

    $rsUsuario = mysqli_query($link, $sqlUsuario);

    if (!$rsUsuario || !($rowUsuario = mysqli_fetch_array($rsUsuario))) {
        throw new Exception("Usuario inválido.");
    }

    $nivel = intval($rowUsuario["userlevelid"]);

    /* Administrador */
    if ($nivel != -1) {

        $sqlPermiso = "
            SELECT permission
            FROM userlevelpermissions
            WHERE userlevelid = $nivel
              AND tablename LIKE '%}articulo'
            LIMIT 1
        ";

        $rsPermiso = mysqli_query($link, $sqlPermiso);

        if (!$rsPermiso || !($rowPermiso = mysqli_fetch_array($rsPermiso))) {
            throw new Exception("Usuario no autorizado para modificar código de barra.");
        }

        $permiso = intval($rowPermiso["permission"]);

        $puedeAgregarArticulo = (($permiso & 1) == 1);
        $puedeModificarArticulo = (($permiso & 4) == 4);

        if (!$puedeAgregarArticulo && !$puedeModificarArticulo) {
            throw new Exception("Usuario no autorizado para modificar código de barra.");
        }
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

        $codigo_de_barra = isset($_POST["codigo_de_barra"])
            ? trim($_POST["codigo_de_barra"])
            : "";

        /* Validar duplicado solamente si viene informado */
        if ($codigo_de_barra != "") {

            $sqlExiste = "
                SELECT id
                FROM articulo
                WHERE codigo_de_barra = ?
                  AND id <> ?
                LIMIT 1
            ";

            $stmtExiste = mysqli_prepare($link, $sqlExiste);

            if (!$stmtExiste) {
                throw new Exception(mysqli_error($link));
            }

            mysqli_stmt_bind_param(
                $stmtExiste,
                "si",
                $codigo_de_barra,
                $articulo
            );

            mysqli_stmt_execute($stmtExiste);

            $rsExiste = mysqli_stmt_get_result($stmtExiste);

            if (mysqli_fetch_assoc($rsExiste)) {
                mysqli_stmt_close($stmtExiste);

                throw new Exception(
                    "Ya existe otro artículo con ese código de barra."
                );
            }

            mysqli_stmt_close($stmtExiste);
        }

        $sql = "
            UPDATE articulo
            SET codigo_de_barra = ?
            WHERE id = ?
        ";

        $stmt = mysqli_prepare($link, $sql);

        if (!$stmt) {
            throw new Exception(mysqli_error($link));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $codigo_de_barra,
            $articulo
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        $response["success"] = true;

        echo json_encode(
            $response,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }
    
    throw new Exception("Acción inválida.");

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
