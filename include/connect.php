<?php
if(!isset($_COOKIE["strcon"])) {
    // Si la petición es AJAX (Fetch), enviamos un error 401 y JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json', true, 401);
        echo json_encode(["estatus" => "error", "mensaje" => "Sesión expirada"]);
        exit;
    }

    // Si es una carga normal de página, mostramos el HTML que ya tenías
    session_destroy();
    echo '<h2 style="color: blue;">Falla de Conexión! Reinicie su sesión...</h2>';
    // ... tu código de botón ...
    die();
}

$strcon = $_COOKIE["strcon"];
$host="localhost";
//$user="drophqsc_drake";
//$password="Tomj@vas001";
//$data="drophqsc_mandrake";
$user="root";
$password="";
$data=$strcon;
$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 
date_default_timezone_set('America/La_Paz');

?>