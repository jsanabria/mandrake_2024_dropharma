<?php
// Página de arranque de Mandraje, para ejecutar tareas o scripts previos al inicio del Sistema

// 1) Para el envio automático de emails generales
include_once "email_general.php";

// Se reenvia a la página Home.php
header("Location: ../home.php");
?>
