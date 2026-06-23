<?php
include "connect.php";

header("Content-Type: application/json; charset=utf-8");

$codigo = trim($_POST["codigo"] ?? "");
$nombre_comercial = trim($_POST["nombre_comercial"] ?? "");
$principio_activo = trim($_POST["principio_activo"] ?? "");
$presentacion = trim($_POST["presentacion"] ?? "");
$fabricante = intval($_POST["fabricante"] ?? 0);
$codigo_de_barra = trim($_POST["codigo_de_barra"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$alicuota = trim($_POST["alicuota"] ?? "");
$articulo_inventario = ($_POST["articulo_inventario"] ?? "S") == "N" ? "N" : "S";
$username = trim($_POST["username"] ?? "");

$costo = 0;
$precio = 0;

if (
    $codigo == "" ||
    $nombre_comercial == "" ||
    $principio_activo == "" ||
    $fabricante <= 0 ||
    $categoria == "" ||
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
$categoria = mysqli_real_escape_string($link, $categoria);
$alicuota = mysqli_real_escape_string($link, $alicuota);
$articulo_inventario = mysqli_real_escape_string($link, $articulo_inventario);

/* Validar código duplicado */
$sql = "SELECT id FROM articulo WHERE codigo = '$codigo' LIMIT 1";
$rs = mysqli_query($link, $sql);

if ($rs && mysqli_fetch_array($rs)) {
    echo json_encode([
        "success" => false,
        "error" => "Ya existe un artículo con ese código."
    ], JSON_UNESCAPED_UNICODE);
    die();
}

/* Validar código de barra duplicado solo si no está en blanco */
if ($codigo_de_barra != "") {
    $sql = "SELECT id FROM articulo WHERE codigo_de_barra = '$codigo_de_barra' LIMIT 1";
    $rs = mysqli_query($link, $sql);

    if ($rs && mysqli_fetch_array($rs)) {
        echo json_encode([
            "success" => false,
            "error" => "Ya existe un artículo con ese código de barra."
        ], JSON_UNESCAPED_UNICODE);
        die();
    }
}

/* Buscar tarifa patrón */
$tarifa_patron = 0;
$sql_tarifa_patron = "SELECT id FROM tarifa WHERE patron = 'S' LIMIT 1";
$rs_tarifa_patron = mysqli_query($link, $sql_tarifa_patron);

if ($row_tarifa = mysqli_fetch_array($rs_tarifa_patron)) {
    $tarifa_patron = intval($row_tarifa["id"]);
}

if ($tarifa_patron <= 0) {
    echo json_encode([
        "success" => false,
        "error" => "No existe tarifa patrón configurada."
    ], JSON_UNESCAPED_UNICODE);
    die();
}

/* Insertar artículo */
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

$articulo_id = mysqli_insert_id($link);

/* Buscar nombre de la categoría */
$nombre_categoria = "";

$sql_categoria = "
    SELECT campo_descripcion AS nombre_categoria
    FROM tabla
    WHERE tabla = 'CATEGORIA'
      AND campo_codigo = '$categoria'
    LIMIT 1
";

$rs_categoria = mysqli_query($link, $sql_categoria);

if ($row_categoria = mysqli_fetch_array($rs_categoria)) {
    $nombre_categoria = mysqli_real_escape_string(
        $link,
        $row_categoria["nombre_categoria"]
    );
}

/* Actualizar costo y precio en cero */
$sql_update_articulo = "
    UPDATE articulo 
    SET
        ultimo_costo = $costo,
        precio = $precio,
        categoria_madre = '$nombre_categoria'
    WHERE id = $articulo_id
";

if (!mysqli_query($link, $sql_update_articulo)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($link)
    ], JSON_UNESCAPED_UNICODE);
    die();
}

/* Insertar tarifa artículo */
$sql_insert_tarifa = "
    INSERT INTO tarifa_articulo (id, tarifa, fabricante, articulo, precio)
    SELECT NULL, $tarifa_patron, fabricante, id, $precio
    FROM articulo
    WHERE id = $articulo_id
";

if (!mysqli_query($link, $sql_insert_tarifa)) {
    echo json_encode([
        "success" => false,
        "error" => mysqli_error($link)
    ], JSON_UNESCAPED_UNICODE);
    die();
}

echo json_encode([
    "success" => true,
    "id" => $articulo_id,
    "codigo" => $codigo,
    "nombre_comercial" => $nombre_comercial
], JSON_UNESCAPED_UNICODE);
?>