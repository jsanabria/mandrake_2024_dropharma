<?php

namespace PHPMaker2024\mandrake;

// Page object
$CodigoProveedorBuscar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$codigo = $_GET["codigo"];
$laboratorio = $_GET["laboratorio"];
$accion = $_GET["accion"];

$sql = "SELECT COUNT(codigo_proveedor) AS cantidad
		FROM articulo
		WHERE codigo_proveedor = '$codigo' AND laboratorio = '$laboratorio';";
$cantidad = ExecuteScalar($sql);

if($cantidad > ($accion == "I" ? 0 : 1)) $cantidad = 1;

$out = '<div id="outtext">' . $cantidad . '</div>';
echo $out;
?>

<?= GetDebugMessage() ?>
