<?php
session_start();

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=tarifa.xls");
//header("Progma; no-cache");
header("Expires: 0");

include "include/connect.php";

$username = trim($_REQUEST["username"]);
$codcliente = trim($_REQUEST["codcliente"]);
$tarifa = trim($_REQUEST["tarifa"]);

$descuento = 0;

/**** Valido de donde se toma el descuento del cliente (Condición Comercial) ****/
/**** En la tabla usuario tomo al valor del campo cliente ****/
/**** y si existe tomo el valor descuento de la tabla cliente ****/

$sql = "SELECT cliente FROM usuario WHERE username = '$username';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $codcliente = intval($row["cliente"]);
else $codcliente = 0;

if($codcliente > 0) {
    $sql = "SELECT descuento FROM cliente WHERE id = $codcliente;";
    $rs = mysqli_query($link, $sql);
    if($row = mysqli_fetch_array($rs)) $descuento = intval($row["descuento"]);
    else $descuento = 0;
}

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];

if($tarifa == "") {
	$sql = "SELECT tarifa FROM cliente WHERE id = $codcliente"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$tarifa = intval($row["tarifa"]);
}

$sql = "SELECT nombre FROM tarifa WHERE id = $tarifa;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);

$titulo = "ARTICULOS TARIFA " . $row["nombre"];


$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cia =  $row["id"];


$sql = "SELECT 
			a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
			a.direccion, a.telefono1, a.email1, logo  
		FROM 
			compania AS a 
			LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
		WHERE a.id = '$cia';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$ciudad = $row["ciudad"];
$direccion = $row["direccion"]; 
$cia =  $row["nombre"];
$logo =  $row["logo"];

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];

?>

<div class="container" id="Exportar_a_Exel">
  <table class="table table-bordered" border="1" rules="all">
	<thead>
	  <tr>
		<th colspan="11"><center><?php echo $cia; ?></center></th>
		<th colspan="4">Fecha: <?php echo date("d/m/Y"); ?></th>
	  </tr>
	  <tr>
		<th colspan="11"></th>
		<th colspan="4">Hora: <?php echo date("H:i:s"); ?></th>
	  </tr>
	  <tr class="well">
		<th colspan="15"><strong><center><?php echo $titulo; ?></th>
	  </tr>
	  <tr class="well">
		<th>LABORATORIO</th>
		<th>NOMBRE</th>
		<th>MEDICAMENTO</th>
		<th>PRESENTACION</th>
		<th>TIPO LISTA</th>
		<th>COD BARRA</th>
		<th>VENCIMIENTO</th>
		<th>PRECIO</th>
		<th>DESC1 %</th>
        <th>DESC2 %</th>
		<th>PRECIO</th>
		<th>DISP</th>
		<th>CANT</th>
		<th>U.M.</th>
		<th>CODIGO</th>
	  </tr>
	</thead>
	<tbody>
	<?php
		$items = 0;

		$sql = "SELECT 
				a.id, a.codigo, 
				a.foto, a.nombre_comercial, b.nombre AS fabricante, 
				a.principio_activo, a.presentacion, c.precio AS precio, 
				(a.cantidad_en_mano+a.cantidad_en_pedido)-a.cantidad_en_transito AS cantidad_en_mano, 
				d.descripcion AS unidad_medida, a.descuento, a.codigo_de_barra, c.precio-(c.precio*(a.descuento/100)) AS precio_total, 
						(SELECT campo_descripcion FROM tabla WHERE tabla = 'LISTA_PEDIDO' AND campo_codigo = a.lista_pedido) AS lista_pedido, 
						a.cantidad_en_mano   
			  FROM 
				articulo AS a 
				LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
				INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
				INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
			  WHERE 
				a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 
			  ORDER BY a.principio_activo, a.presentacion;"; 


		$rs = mysqli_query($link, $sql);
		while($row = mysqli_fetch_array($rs)) {
		    $sql = "SELECT 
		                SUM(x.cantidad_movimiento) AS cantidad_en_mano, MAX(fecha_vencimiento) AS fecha_vencimiento   
		            FROM 
		                (
		                    SELECT 
		                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		                        a.fecha_vencimiento, 
		                        a.cantidad_movimiento 
		                    FROM 
		                        entradas_salidas AS a 
		                        JOIN entradas AS b ON
		                            b.tipo_documento = a.tipo_documento
		                            AND b.id = a.id_documento 
		                        JOIN almacen AS c ON
		                            c.codigo = a.almacen AND c.movimiento = 'S'
		                    WHERE 
		                        (
		                            (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
		                            (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
		                        ) AND a.articulo = " . $row["id"] . " AND a.almacen = '$almacen' AND a.newdata = 'S' 
		                    UNION ALL SELECT 
		                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		                        a.fecha_vencimiento, 
		                        a.cantidad_movimiento  
		                    FROM 
		                        entradas_salidas AS a 
		                        JOIN salidas AS b ON
		                            b.tipo_documento = a.tipo_documento
		                            AND b.id = a.id_documento 
		                        JOIN almacen AS c ON
		                            c.codigo = a.almacen AND c.movimiento = 'S'
		                    WHERE 
		                        (
		                            (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
		                            (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
		                        ) AND a.articulo = " . $row["id"] . " AND a.almacen = '$almacen' AND a.newdata = 'S' 
		                ) AS x 
		            WHERE 1;";
		    $result2 = mysqli_query($link, $sql);
		    $row2 = mysqli_fetch_array($result2);
		    $FechaVencimiento = isset($row2["fecha_vencimiento"]) ? $row2["fecha_vencimiento"] : "";
		    $onHand = floatval($row2["cantidad_en_mano"]);

			if($onHand > 0) {
                echo '<tr>';
                echo '<td>' .$row["fabricante"] . '</td>';
                echo '<td>' .$row["nombre_comercial"] . '</td>';
                echo '<td>' . substr($row["principio_activo"] . "(" . $row["nombre_comercial"] . ")", 0, 60) . '</td>';
                echo '<td>' . substr($row["presentacion"], 0, 60) . '</td>';
                echo '<td>' .$row["lista_pedido"] . '</td>';
                echo '<td>' .$row["codigo_de_barra"] . '</td>';
                echo '<td>' .$FechaVencimiento . '</td>';
                echo '<td>' . number_format($row["precio"], 2, ",", "") . '</td>';
                echo '<td>' . number_format($row["descuento"], 2, ",", "") . '</td>';
                echo '<td>' . number_format($descuento, 2, ",", "") . '</td>';
                echo '<td>' . number_format(floatval($row["precio_total"]) - (floatval($row["precio_total"])*($descuento/100)), 2, ",", "") . '</td>';
                echo '<td>' . intval($onHand) . '</td>';
                echo '<td></td>';
                echo '<td>' . $row["unidad_medida"] . '</td>';
                echo '<td>' . $row["codigo"] . '</td>';
                echo '</tr>';
                $items++;
            }
		}
	?>
	  <tr>
		<th colspan="15">Total Art&iacute;culos: <?php echo $items; ?></th>
	  </tr>
	</tbody>
  </table>
</div>
