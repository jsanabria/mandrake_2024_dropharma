<?php

namespace PHPMaker2024\mandrake;

// Page object
$ListadoMaster = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"];
$url = "listado_master_buscar.php";
$titulo = $id;

?>
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>

<button type="button" class="btn btn-primary" id="regresar" name="regresar" onClick="js:window.history.back();">Regresar a Reportes</button>
<h3><?php echo "Reporte: " . $titulo; ?></h3>
<div class="container">

	<form class="row g-3">
	  <div class="col-auto">
	    <label for="staticEmail2" class="visually-hidden"></label>
	    <input type="text" readonly class="form-control-plaintext" id="staticEmail12" value="Rango de Fechas:">
	  </div>
	  <div class="col-auto">
	    <label for="inputPassword2" class="visually-hidden"></label>
	    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
	  </div>
	  <div class="col-auto">
	    <label for="inputPassword2" class="visually-hidden"></label>
	    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
	  </div>
	  <div class="col-auto">
	  	<!--
	    <label for="inputPassword2" class="visually-hidden"></label>
	    <input type="text" class="form-control" size="10" id="tipo" name="tipo">
		-->
			<div class="form-group">
			  <label for="tipo">Tipo</label>
			  <select id="tipo" name="tipo" class="form-control">
			  	<option value="">TODOS</option>
			  	<?php
			if($id=="CLIENTES IMS" or $id=="ARTICULOS IM" or $id=="FACTURAS IMS") {
		  		$sql = "SELECT id, nombre FROM tarifa WHERE activo = 'S';";
		  		$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
		  		}
		  	}
		  	elseif($id=="LIBRO COMPRA") {

		  	}
		  	elseif($id=="LIBRO VENTA") {
		  		echo '<option value="FC">FACTURA</option>';
		  		echo '<option value="NC">NOTA DE CREDITO</option>';
		  		echo '<option value="ND">NOTA DE DEBITO</option>';
		  	}
		  	elseif($id=="VENTAS POR LABORATORIO" or $id=="SALIDAS GENERALES POR LABORATORIO" or $id=="DESCARGA ENTRADAS A CONSIGNACION") {
				$sql = "SELECT id, nombre FROM fabricante ORDER BY nombre;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
		  		}
		  	}
		  	elseif($id=="VENTAS POR ARTICULO" or $id=="SALIDAS GENERALES POR ARTICULO" or $id=="SALIDAS GENERALES POR ARTICULO DETALLADO" or $id=="ENTRADAS GENERALES POR ARTICULO DETALLADO" or $id=="NOTAS DE ENTREGA DETALLADO" or $id=="PEDIDOS DE VENTAS DETALLADO" or $id=="VENTAS POR ARTICULO UTILIDAD") {
				$sql = "SELECT 
		  						campo_codigo AS id, campo_descripcion AS nombre 
		  					FROM tabla WHERE tabla = 'CATEGORIA' ORDER BY nombre;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
		  		}
		  	}
			elseif($id=="CLIENTES CON COMPRAS RECIENTES" or $id=="CLIENTES SIN COMPRAS RECIENTES") {
				$sql = "SELECT id, nombre FROM asesor ORDER BY nombre;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
		  		}
		  	}
		  	elseif($id=="CONSIGNACIONES POR CLIENTE" or $id=="FACTURAS POR CONSIGNACION") {
				$sql = "SELECT 
		  						DISTINCT a.cliente, b.nombre  
		  					FROM 
		  						salidas AS a 
		  						JOIN cliente AS b ON b.id = a.cliente 
		  					WHERE
		  						a.consignacion = 'S'
		  						AND a.tipo_documento IN ('TDCNET', 'TDCFCV') 
		  						AND a.estatus <> 'ANULADO'
		  					ORDER BY b.nombre;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["cliente"] . '">' . $row["nombre"] . '</option>';
		  		}
		   	}
		  	elseif($id=="INVENTARIO ENTRE FECHA") {
				$sql = "SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S' ORDER BY descripcion;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["codigo"] . '">' . $row["descripcion"] . '</option>';
		  		}
		  	}
		  	elseif($id=="VENTAS POR CLIENTE") {
				$sql = "SELECT campo_codigo, campo_descripcion FROM tabla WHERE tabla = 'CIUDAD' ORDER BY campo_descripcion;";
				$rows = ExecuteRows($sql);
		  		$cantidad = count($rows);
		  		for($i=0; $i<$cantidad; $i++) {
		  			$row = $rows[$i];
		  			echo '<option value="' . $row["campo_codigo"] . '">' . $row["campo_descripcion"] . '</option>';
		  		}
		  	}
			  	?>
			  </select>
			</div>
			<div id="xCliente">
			<?php
			if($id=="LIBRO VENTA") {
				echo '<div class="form-group">';
					echo '<select id="cliente" name="cliente" class="form-control">';
						echo '<option value="">Cliente</option>';
							$sql = "SELECT id, nombre FROM cliente ORDER BY nombre;";
							$rows = ExecuteRows($sql);
							$cantidad = count($rows);
							for($i=0; $i<$cantidad; $i++) {
								$row = $rows[$i];
								echo '<option value="' . $row["id"] . '">' . substr($row["nombre"], 0, 40) . '</option>';
							}
					echo '</select>';
				echo '</div> ';

				echo '<div class="form-group">';
					echo '<select id="asesor" name="asesor" class="form-control">';
						echo '<option value="">Asesor</option>';
							$sql = "SELECT id, nombre FROM asesor ORDER BY nombre;";
							$rows = ExecuteRows($sql);
							$cantidad = count($rows);
							for($i=0; $i<$cantidad; $i++) {
								$row = $rows[$i];
								echo '<option value="' . $row["id"] . '">' . substr($row["nombre"], 0, 40) . '</option>';
							}
					echo '</select>';
				echo '</div>';
			}
			?>
			</div>



	  </div>
	  <div class="col-auto">
	    <button type="button" class="btn btn-primary" id="buscar" name="buscar">Buscar</button>
	  </div>
	</form>

	<div>
		<div id="result">
		</div>
	</div>

</div>

<script type="text/javascript">
	$("#buscar").click(function(){
		var fecha_desde = $("#fecha_desde").val();
		var fecha_hasta = $("#fecha_hasta").val();
		var tipo = $("#tipo").val();
		var almacen = $("#almacen").val();
		var cliente = $("#cliente").val();
		var asesor = $("#asesor").val();

		if(fecha_desde=="" || fecha_hasta=="") {
			alert("Fecha Incorrectas!");
			return false;
		}
		$.ajax({
		  url : "<?php echo $url; ?>",
		  type: "GET",
		  data : {id: '<?php echo $id;?>', fecha_desde: fecha_desde, fecha_hasta: fecha_hasta, tipo: tipo, proveedor : 0, cliente : cliente, asesor : asesor},
		  beforeSend: function(){
		    $("#result").html("Espere. . . ");
		  }
		})
		.done(function(data) {
			//alert(data);
			$("#result").html(data);
		})
		.fail(function(data) {
			alert( "error" + data );
		})
		.always(function(data) {
			//alert( "complete" );
			//$("#result").html("Espere. . . ");
		});
	});

	$("#buscar").click(function(){
	});
	
</script>

<?= GetDebugMessage() ?>
