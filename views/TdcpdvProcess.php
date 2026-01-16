<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcpdvProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$pedido = $_REQUEST["pedido"];

$sql = "UPDATE salidas SET estatus = 'PROCESADO', username = '" . CurrentUserName() . "' WHERE id = $pedido;";
Execute($sql);

header("Location: ViewOutTdcpdvList");
die();
?>
<?= GetDebugMessage() ?>
