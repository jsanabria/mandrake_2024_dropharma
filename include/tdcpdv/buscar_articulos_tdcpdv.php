<?php
include "../connect.php";
include "ClaseBuscarArticulos_tdcpdv.php";

$lista_pedido = isset($_REQUEST["lista_pedido"]) ? trim(htmlspecialchars($_REQUEST["lista_pedido"])) : "";
$fabricante = isset($_REQUEST["fabricante"]) ? trim(htmlspecialchars($_REQUEST["fabricante"])) : "";
$articulo = isset($_REQUEST["articulo"]) ? trim(htmlspecialchars($_REQUEST["articulo"])) : "";
$pedido = isset($_REQUEST["pedido"]) ? trim(htmlspecialchars($_REQUEST["pedido"])) : "";
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? trim(htmlspecialchars($_REQUEST["tipo_documento"])) : "";
$descuentoG = isset($_REQUEST["descuentoG"]) ? trim(htmlspecialchars($_REQUEST["descuentoG"])) : "";
$cliente = isset($_REQUEST["cliente"]) ? trim(htmlspecialchars($_REQUEST["cliente"])) : "";

$pagina = intval(trim(isset($_REQUEST["pagina"])?$_REQUEST["pagina"]:0));
$username = $_POST["username"];

   // Datos para probar la clase
/*
$lista_pedido = "PED000";
$fabricante = "";
$articulo = "acetami";
$cliente = 6;
$pedido = 0;
$pagina = 0;
$username = "jsanabria";
*/

$buscar = new BuscarArticulos($pedido, $lista_pedido, $fabricante, $articulo, $cliente, $username, $pagina, $link);

$buscar->ArticulosDisponibilidadMayorCero();
$html = $buscar->imprimir_encanezado();
$html .= $buscar->ListarArticulosDisponibilidadMayorCero();

unset($buscar);
unset($link);


echo json_encode($html, JSON_UNESCAPED_UNICODE);

include "../desconnect.php";
?>
