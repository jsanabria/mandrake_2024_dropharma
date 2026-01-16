<?php

namespace PHPMaker2024\mandrake;

// Page object
$RifBuscar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$ci_rif = $_GET["ci_rif"];
$tipo = $_GET["tipo"];
$accion = $_GET["accion"];

if($tipo == "CLIENTE")
	$sql = "SELECT COUNT(ci_rif) AS cantidad FROM cliente WHERE ci_rif = '$ci_rif';";
else
	$sql = "SELECT COUNT(ci_rif) AS cantidad FROM proveedor WHERE ci_rif = '$ci_rif';";
$cantidad = ExecuteScalar($sql);

if($cantidad > ($accion == "I" ? 0 : 1)) $cantidad = 1;

$out = '<div id="outtext">' . $cantidad . '</div>';
echo $out;
?>

<?= GetDebugMessage() ?>
