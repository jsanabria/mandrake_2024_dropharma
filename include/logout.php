<?php
session_start();
echo "Sesion Finalizada";
session_destroy();
header("Location: ../login");
?>