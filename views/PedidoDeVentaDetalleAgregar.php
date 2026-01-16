<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidoDeVentaDetalleAgregar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$cliente = $_REQUEST["id"];
$tipo_documento = "TDCPDV";
	
$sql = "SELECT
			MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo
		FROM salidas WHERE tipo_documento = '$tipo_documento';";
$consecutivo = intval(ExecuteScalar($sql)) + 1;

$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
$username = CurrentUserName();
$estatus = "NUEVO";

$sql = "INSERT INTO salidas
			(id, tipo_documento, username, nro_documento, 
			fecha, cliente, monto_total, alicuota_iva, iva, total,
			nota, estatus, fecha_bultos, fecha_despacho)
		VALUES (NULL, '$tipo_documento', '$username', '$nro_documento', 
			'" . date("Y-m-d H:i:s") . "', $cliente, 0, 0, 0, 0,
			'', '$estatus', NULL, NULL)";
Execute($sql);

$sql = "SELECT LAST_INSERT_ID();";
$id = ExecuteScalar($sql);

header("Location: PedidoDeVentaDetalle?id=$id");
die();
?>

<?= GetDebugMessage() ?>
