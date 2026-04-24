<?php
include_once "include/connect.php"; // Asegúrate de que la ruta sea correcta según tu estructura

// Seleccionamos todos los usuarios
$sql = "SELECT id, username, password FROM usuario"; 
$rs = mysqli_query($link, $sql);

while ($row = mysqli_fetch_array($rs)) {
    $id = $row['id'];
    $pass_plano = $row['password'];

    // Generamos el hash seguro
    $nuevo_hash = password_hash($pass_plano, PASSWORD_DEFAULT);

    // Actualizamos la tabla
    $update = "UPDATE usuario SET password = '$nuevo_hash' WHERE id = $id";
    mysqli_query($link, $update);
    
    echo "Usuario " . $row['username'] . " actualizado.<br>";
}

echo "Proceso finalizado.";
?>