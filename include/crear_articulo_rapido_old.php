<?php
include "connect.php";

header("Content-Type: application/json; charset=utf-8");

$codigo = trim($_POST["codigo"] ?? "");
$nombre_comercial = trim($_POST["nombre_comercial"] ?? "");
$principio_activo = trim($_POST["principio_activo"] ?? "");
$presentacion = trim($_POST["presentacion"] ?? "");
$fabricante = intval($_POST["fabricante"] ?? 0);
$codigo_de_barra = trim($_POST["codigo_de_barra"] ?? "");
$categoria = $_POST["categoria"] ?? "";
$alicuota = trim($_POST["alicuota"] ?? "");
$articulo_inventario = ($_POST["articulo_inventario"] ?? "S") == "N" ? "N" : "S";
$username = trim($_POST["username"] ?? "");

if (
    $codigo == "" ||
    $nombre_comercial == "" ||
    $principio_activo == "" ||
    $fabricante <= 0 ||
    $categoria <= 0 ||
    $alicuota == ""
) {
    echo json_encode([
        "success" => false,
        "error" => "Debe completar todos los campos obligatorios."
    ], JSON_UNESCAPED_UNICODE);
    die();
}

$codigo = mysqli_real_escape_string($link, $codigo);
$nombre_comercial = mysqli_real_escape_string($link, $nombre_comercial);
$principio_activo = mysqli_real_escape_string($link, $principio_activo);
$presentacion = mysqli_real_escape_string($link, $presentacion);
$codigo_de_barra = mysqli_real_escape_string($link, $codigo_de_barra);
$alicuota = mysqli_real_escape_string($link, $alicuota);
$articulo_inventario = mysqli_real_escape_string($link, $articulo_inventario);

$sql = "
    INSERT INTO articulo
        (
            id,
            codigo,
            nombre_comercial,
            principio_activo,
            presentacion,
            fabricante,
            codigo_de_barra,
            categoria,
            unidad_medida_defecto,
            cantidad_por_unidad_medida,
            alicuota,
            articulo_inventario,
            activo
        )
    VALUES
        (
            NULL,
            '$codigo',
            '$nombre_comercial',
            '$principio_activo',
            '$presentacion',
            $fabricante,
            '$codigo_de_barra',
            '$categoria',
            'UDM001',
            1,
            '$alicuota',
            '$articulo_inventario',
            'S'
        )
";

if (!mysqli_query($link, $sql)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($link)
    ], JSON_UNESCAPED_UNICODE);
    die();
}

echo json_encode([
    "success" => true,
    "id" => mysqli_insert_id($link),
    "codigo" => $codigo,
    "nombre_comercial" => $principio_activo
], JSON_UNESCAPED_UNICODE);
?>