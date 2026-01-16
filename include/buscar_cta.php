<?php 
include "connect.php";

$texto = $_REQUEST["texto"];

$sql = "SELECT id, codigo, descripcion
        FROM view_plancta 
        WHERE codigo LIKE '$texto%' OR descripcion LIKE '%$texto%' ORDER BY codigo;";
$rs = mysqli_query($link, $sql);

$out = '<select class="form-control" id="cuenta" name="cuenta">
  <option value=""></option>';
  while($row = mysqli_fetch_array($rs)) {
    $out .= '<option value="' . $row["id"] . '">' . $row["codigo"] . '; ' . $row["descripcion"] .  '</option>';
  }
$out .= '</select>';


echo "$out";

?>
