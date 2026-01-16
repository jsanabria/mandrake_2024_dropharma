<?php 
include "connect.php";

$clase = trim(isset($_REQUEST["clase"]) ? $_REQUEST["clase"] : "");
$grupo = trim(isset($_REQUEST["grupo"]) ? $_REQUEST["grupo"] : "");
$cuenta = trim(isset($_REQUEST["cuenta"]) ? $_REQUEST["cuenta"] : "");
$subcuenta = trim(isset($_REQUEST["subcuenta"]) ? $_REQUEST["subcuenta"] : "");

$segmento = $_REQUEST["segmento"];

switch($segmento) {
case 1:
  $where = "clase = '$clase'";
  break;
case 2:
  $where = "clase = '$clase' AND grupo = '$grupo'";
  break;
case 3:
  $where = "clase = '$clase' AND grupo = '$grupo' AND cuenta = '$cuenta'";
  break;
case 4:
  $where = "clase = '$clase' AND grupo = '$grupo' AND cuenta = '$cuenta' AND subcuenta = '$subcuenta'";
  break;
default: 
  $where = "0<>0";
}


$sql = "SELECT 
          clase, grupo, cuenta, subcuenta, descripcion
        FROM 
          cont_plancta 
        WHERE 
          $where;"; 
$rs = mysqli_query($link, $sql);

if($row = mysqli_fetch_array($rs)) {
  $cta = trim($row["clase"]);
  $cta .= trim($row["grupo"]) == "" ? "" : "." . trim($row["grupo"]);
  $cta .= trim($row["cuenta"]) == "" ? "" : "." . trim($row["cuenta"]);
  $cta .= trim($row["subcuenta"]) == "" ? "" : "." . trim($row["subcuenta"]);
  
  $cta = '<strong>Cuentas Existe: ' . $cta . '</strong> - ' . $row["descripcion"];

  $out = '<div class="alert alert-success" role="alert">' . $cta . '</div>';
}
else {
  $cta = "!!! NO EXISTE !!!";
  $out = '<div class="alert alert-danger" role="alert">' . $cta . '</div>';
}


echo "$out";

?>
