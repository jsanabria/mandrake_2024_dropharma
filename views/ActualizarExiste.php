<?php

namespace PHPMaker2024\mandrake;

// Page object
$ActualizarExiste = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$sql  = "SELECT id AS articulo FROM articulo WHERE articulo_inventario='S';";
$rows = ExecuteRows($sql);
foreach($rows as $clave => $valor) {
    $articulo = $valor["articulo"];
    Execute("CALL sp_onhand_item($articulo);");
}

header("Location: Home");

?>

<?= GetDebugMessage() ?>
