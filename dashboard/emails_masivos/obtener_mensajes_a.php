<?php
include '../../connect.php';

/*$sql = "SELECT COUNT(valor1) AS codigo FROM sco_parametro WHERE codigo = '040';";
$rs = mysql_query($sql); 
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];*/

$sql = "SELECT valor1 AS codigo, valor2 AS descripcion FROM parametro WHERE codigo = '016' ORDER BY 1;"; 
$rs = mysqli_query($link, $sql);

$items = array();
while($row = mysqli_fetch_array($rs)){
    array_push($items, $row);
}
//$result["rows"] = $items;

//echo json_encode($result);
echo json_encode($items);
?>
