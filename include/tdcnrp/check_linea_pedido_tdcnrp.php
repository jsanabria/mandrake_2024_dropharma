<?php
include "../connect.php";
include "../funciones.php";

$item = intval($_REQUEST["item"]);

$sql = "UPDATE entradas_salidas SET check_ne = if(check_ne='S', 'N', 'S') WHERE id = $item;";
mysqli_query($link, $sql);

$sql = "SELECT articulo FROM entradas_salidas WHERE id = $item;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$articulo = $row["articulo"];

// CostoPromedio($articulo, $link);

echo json_encode('{"Salida":"OK"}', JSON_UNESCAPED_UNICODE);
?>