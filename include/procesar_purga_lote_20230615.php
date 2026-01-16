<?php
session_start();

include "connect.php";

$purga = intval($_REQUEST["purga"]);
$username = $_REQUEST["username"];

$TomaFisica = new PurgaProcesar($purga, $username, $link);
unset($TomaFisica);

echo "1";

class PurgaProcesar {

	var $head_as;
	var $head_ae;
	var $purga;

	var $codart;
	var $lote;
	var $fecha;
	var $cantidad_articulo;
	var $almacen;

	var $link;
	var $username;

	function __construct($purga, $username, $link) {
		$this->link = $link;
		$this->username = $username;

		$this->head_as = 0;
		$this->head_ae = 0;
		$this->purga = $purga;

		$sql = "SELECT id, articulo, lote, fecha, cantidad_articulo, almacen FROM purga_detalle WHERE purga = $purga;"; 
		$rs = mysqli_query($this->link, $sql);
		while($row = mysqli_fetch_array($rs)) { 
			$purga_detalle = $row["id"];
			$this->codart = intval($row["articulo"]);
			$this->lote = $row["lote"];
			$this->fecha = $row["fecha"];
			$this->cantidad_articulo = intval($row["cantidad_articulo"]);
			$this->almacen = $row["almacen"];

			$sql2 = "SELECT 
					a.id, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
					(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad, c.descripcion AS almacen  
				FROM 
					entradas_salidas AS a 
					JOIN entradas AS b ON
						b.tipo_documento = a.tipo_documento
						AND b.id = a.id_documento 
					JOIN almacen AS c ON
						c.codigo = a.almacen AND c.movimiento = 'S' 
					LEFT OUTER JOIN (
							SELECT 
								a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
							FROM 
								entradas_salidas AS a 
								JOIN salidas AS b ON
									b.tipo_documento = a.tipo_documento
									AND b.id = a.id_documento 
								LEFT OUTER JOIN almacen AS c ON
									c.codigo = a.almacen AND c.movimiento = 'S'
							WHERE
								a.tipo_documento IN ('TDCNET','TDCASA') 
								AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '" . $this->codart . "' 
							GROUP BY a.id_compra
						) AS d ON d.id = a.id 
				WHERE
					((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
					AND b.estatus = 'PROCESADO') OR 
					(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
					AND b.estatus <> 'ANULADO' AND b.consignacion = 'S'))
					AND a.articulo = '" . $this->codart . "' 
					AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 
				ORDER BY a.fecha_vencimiento ASC;"; 
			$rs2 = mysqli_query($this->link, $sql2);
			$sw = true;
			$sw2 = false;
			while($row2 = mysqli_fetch_array($rs2)) { 
				if($this->lote == $row2["lote"]) { 
					/* Se crea la salida */
					if($this->head_as == 0) {
						$this->head_as = $this->Crear_head_as(); 
					}

					$this->Crear_detail_as($this->head_as, $row2["id"], $row2["cantidad"]);

					/* Se crea la entrada */
					if($this->cantidad_articulo > 0) {
						if($this->head_ae == 0) {
							$this->head_ae = $this->Crear_head_ae(); 
						}

						if($sw) 
							$this->Crear_detail_ae($this->head_ae, $row2["id"]);
						$sw = false;
					}
					$sql3 = "UPDATE purga_detalle SET procesado='S' WHERE id = $purga_detalle;";
					mysqli_query($this->link, $sql3);

					$sw2 = true;
				}
			}

			if($sw2 == false) {
				/* Se crea la entrada si no existe y se inserta el lote nuevo al inventario */

				if($this->cantidad_articulo > 0) {
					if($this->head_ae == 0) {
						$this->head_ae = $this->Crear_head_ae(); 
					}

					$this->Crear_detail_ae2($this->head_ae, $this->codart);
				}
				$sql3 = "UPDATE purga_detalle SET procesado='S' WHERE id = $purga_detalle;";
				mysqli_query($this->link, $sql3);
			}
		}

		$this->ActualizarUnidades();
		$sql = "UPDATE purga SET procesado='S', salidas = '" . $this->head_as . "', entradas = '" . $this->head_ae . "' WHERE id = $purga;";
		mysqli_query($this->link, $sql);
	}

	function Crear_head_as() {
		/*Asigno cliente */
		$sql = "SELECT id FROM cliente WHERE nombre LIKE '%ajuste%';";
		$rs = mysqli_query($this->link, $sql);
		$row = mysqli_fetch_array($rs);
		$cliente = $row["id"];

		/* Obtengo el consecutivo */
		$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = 'TDCASA';";
		$rs = mysqli_query($this->link, $sql);
		$row = mysqli_fetch_array($rs);
		$consecutivo = intval($row["cosecutivo"]) + 1;
		$consecutivo = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

		/* Cabecera Ajuste de Salida */
		$sql = "INSERT INTO salidas
					(id, tipo_documento, username, fecha,
					cliente, nro_documento,
					nota, estatus, documento, nombre, moneda)
				VALUES 
					(NULL, 'TDCASA', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "',
					$cliente, '$consecutivo',
					'CONTEO FISICO', 
					'PROCESADO', NULL, NULL, 'Bs');";
		mysqli_query($this->link, $sql);

		$rs = mysqli_query($this->link, "SELECT LAST_INSERT_ID() AS id;");
		$row = mysqli_fetch_array($rs);
		$newid = $row["id"];

		return $newid;
	}

	function Crear_detail_as($head, $id_compra, $cantidad) { 
		$sql = "INSERT INTO entradas_salidas
					(id, tipo_documento, id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					cantidad_movimiento, costo_unidad, costo, id_compra) 
				SELECT 
					NULL AS id, 'TDCASA' AS tipo_documento, $head AS id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					$cantidad AS cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					(-1)*$cantidad AS cantidad_movimiento, costo_unidad, costo, $id_compra AS id_compra 
				FROM 
					entradas_salidas WHERE id = $id_compra;"; 
		mysqli_query($this->link, $sql);
	}


	function Crear_head_ae() {
		/*Asigno proveedor */
		$proveedor = 1;

		/* Obtengo el consecutivo */
		$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM entradas WHERE tipo_documento = 'TDCAEN';";
		$rs = mysqli_query($this->link, $sql);
		$row = mysqli_fetch_array($rs);
		$consecutivo = intval($row["cosecutivo"]) + 1;
		$consecutivo = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

		/* Cabecera Ajuste de Entrada */
		$sql = "INSERT INTO entradas 
					(id, tipo_documento, username, fecha,
					proveedor, nro_documento,
					nota, estatus, documento, moneda)
				VALUES 
					(NULL, 'TDCAEN', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "',
					$proveedor, '$consecutivo',
					'CONTEO FISICO', 
					'PROCESADO', NULL, 'Bs');";
		mysqli_query($this->link, $sql);

		$rs = mysqli_query($this->link, "SELECT LAST_INSERT_ID() AS id;");
		$row = mysqli_fetch_array($rs);
		$newid = $row["id"];

		return $newid;
	}

	function Crear_detail_ae($head, $id_compra) { 
		$sql = "INSERT INTO entradas_salidas
					(id, tipo_documento, id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					cantidad_movimiento, costo_unidad, costo, id_compra)
				SELECT 
					NULL AS id, 'TDCAEN' AS tipo_documento, $head AS id_documento, 
					fabricante, articulo, '" . $this->almacen . "' AS almacen, '" . $this->lote . "' AS lote, '" . $this->fecha . "' AS fecha_vencimiento, 
					" . $this->cantidad_articulo . " AS cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					" . $this->cantidad_articulo . " AS cantidad_movimiento, costo_unidad, (" . $this->cantidad_articulo . "*costo_unidad) AS costo, NULL AS id_compra
				FROM 
					entradas_salidas WHERE id = $id_compra;";
		mysqli_query($this->link, $sql);
	}

	function Crear_detail_ae2($head, $articulo) { 
		$sql = "INSERT INTO entradas_salidas
					(id, tipo_documento, id_documento, 
					fabricante, articulo, almacen, lote, fecha_vencimiento, 
					cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
					cantidad_movimiento, costo_unidad, costo, id_compra)
				SELECT 
					NULL AS id, 'TDCAEN' AS tipo_documento, $head AS id_documento, 
					fabricante, id, '" . $this->almacen . "' AS almacen, '" . $this->lote . "' AS lote, '" . $this->fecha . "' AS fecha_vencimiento, 
					" . $this->cantidad_articulo . " AS cantidad_articulo, unidad_medida_defecto, cantidad_por_unidad_medida, 
					" . $this->cantidad_articulo . " AS cantidad_movimiento, ultimo_costo, (" . $this->cantidad_articulo . "*ultimo_costo) AS costo, NULL AS id_compra
				FROM 
					articulo WHERE id = $articulo;"; 
		mysqli_query($this->link, $sql);
	}

	function ActualizarUnidades() {
		$sql = "SELECT SUM(cantidad_articulo) AS cant FROM entradas_salidas WHERE id_documento = " . $this->head_as . " AND tipo_documento = 'TDCASA';";
		$rs = mysqli_query($this->link, $sql);
		$row = mysqli_fetch_array($rs);
		$cant = intval($row["cant"]);

		$sql = "UPDATE salidas SET unidades = $cant WHERE id = " . $this->head_as . ";";
		mysqli_query($this->link, $sql);

		$sql = "SELECT SUM(cantidad_articulo) AS cant FROM entradas_salidas WHERE id_documento = " . $this->head_ae . " AND tipo_documento = 'TDCAEN';";
		$rs = mysqli_query($this->link, $sql);
		$row = mysqli_fetch_array($rs);
		$cant = intval($row["cant"]);

		$sql = "UPDATE salidas SET unidades = $cant WHERE id = " . $this->head_ae . ";";
		mysqli_query($this->link, $sql);
	}

} // fin de la clase Verdura

?>