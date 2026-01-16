<?php

namespace PHPMaker2024\mandrake;

// Page object
$ImagenesCliente = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
$sql = "SELECT nombre, foto1, foto2 FROM cliente WHERE id = $id;";
$row = ExecuteRow($sql);
$nombre = $row["nombre"];
$foto1 = $row["foto1"];
$foto2 = $row["foto2"];
?>
<h1><?php echo $nombre; ?></h1>
<img src="<?php echo "carpetacarga/$foto1"; ?>" width="400" class="img-thumbnail" alt="Cinque Terre">
<img src="<?php echo "carpetacarga/$foto2"; ?>" width="400" class="img-thumbnail" alt="Cinque Terre">


<?= GetDebugMessage() ?>
