<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcpdcProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$pedido = $_REQUEST["pedido"];

$sql = "UPDATE entradas SET estatus = 'PROCESADO', username = '" . CurrentUserName() . "' WHERE id = $pedido;";
Execute($sql);

header("Location: ViewInList?showmaster=view_in_tdcpdc&fk_id=" . $pedido . "&fk_tipo_documento=TDCPDC");
die();
?>
<?= GetDebugMessage() ?>
