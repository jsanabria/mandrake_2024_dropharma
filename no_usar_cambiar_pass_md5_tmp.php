<?php
include_once "include/connect.php"; // Verifica que la ruta a tu conexión sea correcta

// Seleccionamos todos los usuarios
$sql = "SELECT id, username, password FROM usuario"; 
$rs = mysqli_query($link, $sql);

if (!$rs) {
    die("Error en la consulta: " . mysqli_error($link));
}

echo "<h3>Iniciando actualización de contraseñas a MD5...</h3>";

while ($row = mysqli_fetch_array($rs)) {
    $id = $row['id'];
    $pass_plano = $row['password'];

    // Generamos el hash en formato MD5 (el que usa tu configuración actual)
    $nuevo_hash = md5($pass_plano);

    // Actualizamos la tabla
    $update = "UPDATE usuario SET password = '$nuevo_hash' WHERE id = $id";
    
    if (mysqli_query($link, $update)) {
        echo "Usuario <b>" . $row['username'] . "</b> actualizado correctamente.<br>";
    } else {
        echo "Error al actualizar usuario " . $row['username'] . ": " . mysqli_error($link) . "<br>";
    }
}

echo "<br><b>Proceso finalizado.</b>";
?>