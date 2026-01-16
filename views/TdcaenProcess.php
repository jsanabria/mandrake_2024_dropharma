<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcaenProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$pedido = $_REQUEST["pedido"];

$sql = "UPDATE entradas SET estatus = 'PROCESADO', username = '" . CurrentUserName() . "' WHERE id = $pedido;";
Execute($sql);

header("Location: ViewInList?showmaster=view_in_tdcaen&fk_id=" . $pedido . "&fk_tipo_documento=TDCAEN");
die();
?>
<?= GetDebugMessage() ?>
