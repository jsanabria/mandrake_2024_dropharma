<?php

namespace PHPMaker2024\mandrake;

// Page object
$VerificarVenta = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"];

$sql = "UPDATE venta SET estatus = 'VERIFICADO' WHERE id = '$id';";
Execute($sql);

header("VerificarVenta?showdetail=view_venta_detalle&id=$id");
?>

<?= GetDebugMessage() ?>
