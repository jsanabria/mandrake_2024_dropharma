<?php

namespace PHPMaker2024\mandrake;

// Page object
$MainReport = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="panel panel-default">
	 <div class="panel-heading">Seleccione un Reporte</div>
	 <div class="panel-body">
	 	<form method="get" action="ListadoMaster">
		 <ul style="list-style-type:none;">
		 	<li><input type="radio" id="id" name="id" value="CLIENTES IMS" checked="checked">  EXPORTAR CLIENTES IMS</li>
		 	<li><input type="radio" id="id" name="id" value="ARTICULOS IMS">  EXPORTAR ARTICULOS IMS</li>
		 	<li><input type="radio" id="id" name="id" value="FACTURAS IMS">  EXPORTAR FACTURAS IMS</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="LIBRO COMPRA">  LIBRO DE COMPRAS</li>
		 	<li><input type="radio" id="id" name="id" value="LIBRO VENTA">  LIBRO DE VENTAS</li>
		 	<li><input type="radio" id="id" name="id" value="FACTURAS COSTO VS PRECIO">  FACTURAS COSTOS VS PRECIO</li>
		 	<li><input type="radio" id="id" name="id" value="KARDEX DE INVENTARIO">  KARDEX DE INVENTARIO</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="ENTRADAS GENERALES POR ARTICULO DETALLADO">  ENTRADAS GENERALES POR ARTICULO DETALLADO</li>
		 	<li><input type="radio" id="id" name="id" value="PEDIDOS DE VENTAS DETALLADO">  PEDIDOS DE VENTAS DETALLADO</li>
		 	<li><input type="radio" id="id" name="id" value="NOTAS DE ENTREGA DETALLADO">  NOTAS DE ENTREGA DETALLADO</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="VENTAS POR LABORATORIO">  VENTAS POR LABORATORIO (FACTURAS)</li>
		 	<li><input type="radio" id="id" name="id" value="VENTAS POR ARTICULO">  VENTAS POR ARTICULO (FACTURAS)</li>
		 	<li><input type="radio" id="id" name="id" value="VENTAS POR ARTICULO UTILIDAD">  VENTAS POR ARTICULO (FACTURAS UTILIDAD NETA)</li>
		 	<li><input type="radio" id="id" name="id" value="SALIDAS GENERALES POR LABORATORIO">  SALIDAS GENERALES POR LABORATORIO (FACTURAS + AJUSTE SALIDAS)</li>
		 	<li><input type="radio" id="id" name="id" value="SALIDAS GENERALES POR ARTICULO">  SALIDAS GENERALES POR ARTICULO (FACTURAS + AJUSTE SALIDAS)</li>
		 	<li><input type="radio" id="id" name="id" value="VENTAS POR CLIENTE">  VENTAS POR CLIENTE (FACTURAS SIN IVA Y CANTIDAD DE UNIDADES)</li>
		 	<li><input type="radio" id="id" name="id" value="SALIDAS GENERALES POR ARTICULO DETALLADO">  SALIDAS GENERALES POR ARTICULO DETALLADO</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="CONSIGNACIONES POR CLIENTE">  CONSIGNACIONES POR CLIENTE</li>
		 	<li><input type="radio" id="id" name="id" value="FACTURAS POR CONSIGNACION">  FACTURAS POR CONSIGNACION</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="CLIENTES CON COMPRAS RECIENTES">  CLIENTES CON COMPRAS RECIENTES</li>
		 	<li><input type="radio" id="id" name="id" value="CLIENTES SIN COMPRAS RECIENTES">  CLIENTES SIN COMPRAS RECIENTES</li>
		 	<li><hr><li>
		 	<li><input type="radio" id="id" name="id" value="INVENTARIO ENTRE FECHA">  INVENTARIO ENTRE FECHA</li>
		 	<li><input type="radio" id="id" name="id" value="DESCARGA ENTRADAS A CONSIGNACION">  DESCARGA ENTRADAS A CONSIGNACION</li>
		 	<li><hr><li>
		 </ul>

		 <!--<buttom class="btn btn-primary">Generate<buttom>-->
		 <input type="submit" class="btn btn-primary" value="Generar">
		</form>
	 </div>
 </div>

<?= GetDebugMessage() ?>
