<?php

namespace PHPMaker2021\mandrake;

// Page object
$ActTarifaPage = &$Page;
?>
<?php
$id = $_GET["id"];
$patron = $_GET["patron"];

die("$id -- $patron");

//$sql = "SELECT valor1 FROM parametro WHERE codigo = '005';";
$sql = "SELECT porcentaje FROM tarifa WHERE id = $id;";
$porc = floatval(ExecuteScalar($sql));

$sql = "UPDATE 
			tarifa_articulo AS a 
				JOIN 
			(SELECT fabricante, articulo, precio FROM tarifa_articulo
			WHERE tarifa = $patron) AS b 
				ON b.fabricante = a.fabricante AND b.articulo = a.articulo 
		SET 
			a.precio = (b.precio +  (b.precio * ($porc/100))) 
		WHERE a.tarifa = $id;";

Execute($sql);

header("Location: tarifalist.php");
?>

<?= GetDebugMessage() ?>
