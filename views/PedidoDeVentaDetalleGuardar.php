<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidoDeVentaDetalleGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
/////////////////////////////////////////////

$tipo_documento = "TDCPDV";
$id = $_REQUEST["id"];
$moneda = $_REQUEST["moneda"];
$username = $_REQUEST["username"];
$start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : "";
$contador = isset($_REQUEST["contador"]) ? $_REQUEST["contador"] : "";
$nota = $_REQUEST["nota"];

$pagina = $_REQUEST["pagina"];
$switch_page = $_REQUEST["switch_page"];

$articulo = 0;
$cantidad_movimiento = 0;
$cantidad = 0;
$precio = 0;
$precio_ful = 0;
$descuento = 0;
$total = 0;

$salida = "";

$btnSend = isset($_REQUEST["btnSend"]) ? trim($_REQUEST["btnSend"]) : "";
$txtArticulo = trim($_REQUEST["txtArticulo"]);

// --- Valido que aun el pedido esté en estatus NUEVO para poderlo modificar 21/12/2020 --- //
$sql = "SELECT estatus FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = '$id';"; 
$status_doc = ExecuteScalar($sql);
if($status_doc != "NUEVO") {
	$_SESSION['error'] = "Este pedido pas&oacute; a recibido para ser procesado y no se puede modificar. !!! ESTATUS ACTUAL $status_doc !!!";
	header("Location: PedidoDeVentaDetalle?id=$id");
	die();
}
// --- //

if($btnSend == "") {
	header("Location: PedidoDeVentaDetalle?id=$id&art=$txtArticulo");
	die();
}

//die("btnSend = $btnSend -- switch_page = $switch_page -- Location: pedido_de_venta_detalle.php?id=$id&pagina=$pagina");

if($switch_page == "S") {
	header("Location: PedidoDeVentaDetalle?id=$id&pagina=$pagina");
	die();
}

$sql = "SELECT 
		  date_format(a.fecha, '%d/%m/%Y') AS fecha, a.nro_documento, b.nombre AS cliente, 
		  a.cliente AS codcliente 
		FROM 
		  salidas AS a 
		  LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
		WHERE 
		  a.id = $id;";
if($row = ExecuteRow($sql)) {
  $documento = $row["nro_documento"];
  $fecha = $row["fecha"];
  $cliente = $row["cliente"];
  $codcliente = $row["codcliente"];
}

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 21-01-2021
$sql = "UPDATE 
			salidas AS a 
		SET 
			a.nota = '$nota' 
		WHERE a.id = $id;";
Execute($sql);
/**************/

/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
// ActualizarExitencia();

/////////////////////////////////////////////
?>

<div class="container">
	<div class="text-left">
		<h2>Pedido de Venta # <?php echo $documento; ?> Actualizado Exitosamente</h2>
	</div>

	<div>
		<dl>
		<dt>Cliente</dt>
		<dd>- <?php echo $cliente; ?></dd>
		<dt>Nro. Documento</dt>
		<dd>- <?php echo "# " . $documento; ?></dd>
		<dt>Fecha</dt>
		<dd>- <?php echo $fecha; ?></dd>
		</dl>     		
	</div>

	<?php if(trim($salida) != "") { ?>
		<div class="alert alert-danger" role="alert">Los siguientes art&iacute;culos no se agregaron: <br> <?php echo $salida; ?></div>
	<?php } ?>

	<div>
		<div class="row">
		  <div class="col-md-1 text-center">
			<?php 
			$url = "reportes/pedido_de_venta.php?id=$id&tipo=TDCPDV";
			echo '<a class="fas fa-print fa-3x" target="_blank" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"></a>';
			?>
		  </div>

		  <div class="col-md-1 text-center">
			<?php 
			$url = "PedidoDeVentaDetalle?id=$id";
			echo '<a class="fas fa-edit fa-3x" href="' . $url . '" data-toggle="tooltip" title="Editar" data-placement="bottom"></a>';
			?>
		  </div>

		  <div class="col-md-1 text-center">
			<?php 
			//$url = "PedidoDeVentaDetalleAgregar?id=$codcliente";
			$url = "SalidasAdd?showdetail=";
			echo '<a class="fas fa-plus fa-3x" href="' . $url . '" data-toggle="tooltip" title="Crear Nuevo Pedido" data-placement="bottom"></a>';
			?>
		  </div>

		  <div class="col-md-1 text-center">
			<?php 
			$url = "SalidasList?tipo=TDCPDV";
			echo '<a class="fas fa-list fa-3x" href="' . $url . '" data-toggle="tooltip" title="Listar Pedidos" data-placement="bottom"></a>';
			?>
		  </div>

		  <div class="col-md-1 text-center">
			<?php 
			$url = "include/pedido_de_venta_cerrar.php?id=$id&username=" . CurrentUserName();
			echo '<a class="fas fa-lock fa-5x" href="' . $url . '" data-toggle="tooltip" title="Cerrar Pedido" data-placement="bottom"></a>';
			?>
		  </div>

		  <div class="col-md-7 text-center">
		  </div>
		</div>
	</div>

</div>

<?= GetDebugMessage() ?>
