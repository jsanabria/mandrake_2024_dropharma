<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeEntregaBuscarListar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$nota_entrega = $_POST["NotaEntrega"];
?>
	<form id="frm" name="frm" method="post" action="NotaDeEntregaVer">
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					&nbsp;
				</th>
				<th>
					Nro. Documento
				</th>
				<th>
					Fecha
				</th>
				<th>
					Cliente
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT a.id, 
					b.nombre, a.tipo_documento, a.nro_documento,
					date_format(a.fecha, '%d/%m/%Y') AS fecha,
					a.moneda, a.monto_total  
				FROM 
					salidas AS a 
					JOIN cliente AS b ON b.id = a.cliente 
				WHERE
					a.tipo_documento = 'TDCNET'
					AND a.nro_documento LIKE '%$nota_entrega'
					AND a.estatus = 'NUEVO' 
				ORDER BY a.id DESC";
		$rows = ExecuteRows($sql);
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<td>
					<input type="radio" name="xNota" id="xNota" value="<?php echo $value["id"]; ?>">
				</td>
				<td>
					<?php echo $value["nro_documento"]; ?>
				</td>
				<td>
					<?php echo $value["fecha"]; ?>
				</td>
				<td>
					<?php echo $value["nombre"]; ?>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td class="text-center" colspan="4">
					<input type="submit" class="btn btn-default" type="button" value="Ver Nota">
					&nbsp;
					<input type="button" class="btn btn-default" type="button" value="Regresar" onclick="js: history.back();"> 
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>

<?= GetDebugMessage() ?>
