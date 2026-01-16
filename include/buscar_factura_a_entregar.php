<?php
session_start();
$_COOKIE["strcon"] = isset($_REQUEST["data"]) ? $_REQUEST["data"] : "";
require("connect.php");

$xIdPadre = intval($_REQUEST["id_padre"]);

$sql = "SELECT id FROM salidas WHERE id_documento_padre = $xIdPadre;"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $xId = $row["id"];
else $xId = 0;

if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}

header("Location: ../ViewFacturasAEntregarEdit?id=$xId");
require("desconnect.php");
?>