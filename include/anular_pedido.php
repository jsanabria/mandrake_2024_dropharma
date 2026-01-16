<?php
include "connect.php";

$id = isset($_REQUEST["id"]) ? trim(htmlspecialchars($_REQUEST["id"])) : "";
$username = isset($_REQUEST["username"]) ? trim(htmlspecialchars($_REQUEST["username"])) : "";

$sql = "UPDATE salidas SET estatus = 'ANULADO', username = '$username' WHERE id = $id and estatus = 'NUEVO';"; 
mysqli_query($link, $sql);

include "desconnect.php";

echo json_encode("Anulado Exitosamente.", JSON_UNESCAPED_UNICODE);
// echo "Anulado Exitosamente.";
?>
