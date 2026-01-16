<?php

namespace PHPMaker2024\mandrake;

// Page object
$EliminarLinea = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php 

$id = $_REQUEST["id"];

$sql = "DELETE FROM entradas_salidas WHERE id = '$id'"; 
Execute($sql);

echo "1";
die();
?>

<?= GetDebugMessage() ?>
