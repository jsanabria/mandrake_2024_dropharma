<?php
session_start();

include "connect.php";

$cliente = $_REQUEST["cliente"];

// $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga WHERE cliente = $cliente;"; 
$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";

$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$id = $row["id"];
$saldo = $row["saldo"];
?>
<input type="text" id="x_disponible" name="x_disponible" class="form-control text-left input-sm" value="<?php echo number_format($saldo, 2, ",", "."); ?>" size="30" readonly="yes">
<input type="text" id="x_referencia" name="x_referencia" class="form-control text-left input-sm" value="<?php echo $id; ?>" size="10" readonly="yes">
<?php
include "desconnect.php";
?>
