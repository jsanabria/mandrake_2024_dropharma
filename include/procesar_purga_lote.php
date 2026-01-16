<?php
session_start();

include "connect.php";

$purga = intval($_REQUEST["purga"]);
$username = $_REQUEST["username"]; 
$almacen = $_REQUEST["almacen"]; 

$TomaFisica = new PurgaProcesar($purga, $username, $link, $almacen);
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
	var $procesado;

	var $link;
	var $username;

	function __construct($purga, $username, $link, $AlmacenOrigen) {
		$this->link = $link;
		$this->username = $username;

		$this->head_as = 0;
		$this->head_ae = 0;
		$this->purga = $purga;

		$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
		$tipo_documento = 'TDCNET';
		$rs = mysqli_query($this->link, $sql);
		if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];


		if($AlmacenOrigen == "all") $where = "";
		else $where = " AND a.almacen = '$AlmacenOrigen' ";

		$sql = "SELECT id, articulo, lote, fecha, cantidad_articulo, almacen, procesado FROM purga_detalle WHERE purga = $purga;"; 
		$rs = mysqli_query($this->link, $sql);
		while($row = mysqli_fetch_array($rs)) { 
			$purga_detalle = $row["id"];
			$this->codart = intval($row["articulo"]);
			$this->lote = $row["lote"];
			$this->fecha = $row["fecha"];
			$this->cantidad_articulo = intval($row["cantidad_articulo"]);
			$this->almacen = $row["almacen"]; 
			$this->procesado = $row["procesado"]; 

			$sql2 = "SELECT 
                x.articulo, x.lote, x.fecha AS fecha_vencimiento, x.fecha_vencimiento AS fecha, x.almacen, x.check_ne, SUM(x.cantidad_movimiento) AS cantidad 
            FROM 
                (
                    SELECT 
                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                        a.fecha_vencimiento, 
                        a.cantidad_movimiento, a.almacen, a.check_ne 
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
                        ) AND a.articulo = " . $this->codart . " AND a.newdata = 'S' 
                    UNION ALL SELECT 
                        a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                        a.fecha_vencimiento, 
                        a.cantidad_movimiento, a.almacen, a.check_ne 
                    FROM 
                        entradas_salidas AS a 
                        JOIN salidas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                        ) AND a.articulo = " . $this->codart . " AND a.newdata = 'S' 
                ) AS x 
            WHERE 1 
            GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.almacen, x.check_ne 
            HAVING SUM(x.cantidad_movimiento) <> 0 
            ORDER BY x.fecha ASC;"; 

			$rs2 = mysqli_query($this->link, $sql2);
			$sw = true;
			$sw2 = false;
			while($row2 = mysqli_fetch_array($rs2)) { 
				if($this->procesado == "N") { 
					/* Se crea la salida */
					if($this->head_as == 0) {
						$this->head_as = $this->Crear_head_as(); 
					}

					$this->Crear_detail_as($this->head_as, $row2["articulo"], $row2["lote"], $row2["fecha"], $row2["almacen"], $row2["cantidad"]);

					/* Se crea la entrada */
					if($this->cantidad_articulo > 0) {
						if($this->head_ae == 0) {
							$this->head_ae = $this->Crear_head_ae(); 
						}

						if($sw) 
							$this->Crear_detail_ae($this->head_ae, $this->codart);
						$sw = false;
					}
					$sql3 = "UPDATE purga_detalle SET procesado='S' WHERE id = $purga_detalle;";
					mysqli_query($this->link, $sql3);

					$sw2 = true;
				} 
				else $sw2 = true;
			}

			if($sw2 == false) {
				/* Se crea la entrada si no existe y se inserta el lote nuevo al inventario */

				if($this->cantidad_articulo > 0) {
					if($this->head_ae == 0) {
						$this->head_ae = $this->Crear_head_ae(); 
					}

					$this->Crear_detail_ae($this->head_ae, $this->codart);
				}
				$sql3 = "UPDATE purga_detalle SET procesado='S' WHERE id = $purga_detalle;";
				mysqli_query($this->link, $sql3);
			}
		}

		$this->ActualizarUnidades();
		$sql = "UPDATE purga SET procesado='S', salidas = '" . $this->head_as . "', entradas = '" . $this->head_ae . "', username_procesa = '" . $this->username . "' WHERE id = $purga;";
		mysqli_query($this->link, $sql);

		$sql3 = "UPDATE entradas_salidas SET check_ne='N' WHERE id_documento = " . $this->head_ae . " AND tipo_documento='TDCAEN';";
		mysqli_query($this->link, $sql3);
	}

	function Crear_head_as() {
		/*Asigno cliente */
		$sql = "SELECT id FROM cliente WHERE nombre LIKE '%ajuste%';";
		$rs = mysqli_query($this->link, $sql);
		if($row = mysqli_fetch_array($rs)) $cliente = $row["id"];
		else $cliente = 491;

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

	function Crear_detail_as($head, $articulo, $lote, $fecha, $almacen, $cantidad) { 
		$sql = "SELECT 
					fabricante, unidad_medida_defecto, cantidad_por_unidad_medida, ultimo_costo 
				FROM 
					articulo WHERE id = $articulo;";
		$rs = mysqli_query($this->link, $sql); 
		$row = mysqli_fetch_array($rs); 
		$fabricante = $row["fabricante"]; 
		$unidad_medida_defecto = $row["unidad_medida_defecto"]; 
		$cantidad_por_unidad_medida = $row["cantidad_por_unidad_medida"]; 
		$ultimo_costo = $row["ultimo_costo"]; 

		$sql = "INSERT INTO entradas_salidas
				SET 
					id = NULL, 
					tipo_documento = 'TDCASA', 
					id_documento = $head, 
					fabricante = $fabricante, 
					articulo = $articulo, 
					almacen = '$almacen', 
					lote = '$lote', 
					fecha_vencimiento = '$fecha', 
					cantidad_articulo = $cantidad, 
					articulo_unidad_medida = '$unidad_medida_defecto', 
					cantidad_unidad_medida = $cantidad_por_unidad_medida, 
					cantidad_movimiento = (-1)*$cantidad, 
					costo_unidad = $ultimo_costo, 
					costo = $cantidad*$ultimo_costo, 
					check_ne = 'S';"; 
		mysqli_query($this->link, $sql);
	}


	function Crear_head_ae() {
		/*Asigno proveedor */
		$sql = "SELECT id FROM proveedor WHERE nombre LIKE '%ajuste%';";
		$rs = mysqli_query($this->link, $sql);
		if($row = mysqli_fetch_array($rs)) $proveedor = $row["id"];
		else $proveedor = 24;

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

	function Crear_detail_ae($head, $articulo) { 
		$sql = "SELECT 
					fabricante, unidad_medida_defecto, cantidad_por_unidad_medida, ultimo_costo 
				FROM 
					articulo WHERE id = $articulo;";
		$rs = mysqli_query($this->link, $sql); 
		$row = mysqli_fetch_array($rs); 
		$fabricante = $row["fabricante"]; 
		$unidad_medida_defecto = $row["unidad_medida_defecto"]; 
		$cantidad_por_unidad_medida = $row["cantidad_por_unidad_medida"]; 
		$ultimo_costo = $row["ultimo_costo"]; 

		$sql = "INSERT INTO entradas_salidas
				SET 
					id = NULL, 
					tipo_documento = 'TDCAEN', 
					id_documento = $head, 
					fabricante = $fabricante, 
					articulo = $articulo, 
					almacen = '" . $this->almacen . "', 
					lote = '" . $this->lote . "', 
					fecha_vencimiento = '" . $this->fecha . "', 
					cantidad_articulo = " . $this->cantidad_articulo . ", 
					articulo_unidad_medida = '$unidad_medida_defecto', 
					cantidad_unidad_medida = $cantidad_por_unidad_medida, 
					cantidad_movimiento = " . $this->cantidad_articulo . ", 
					costo_unidad = $ultimo_costo, 
					costo = " . $this->cantidad_articulo . "*$ultimo_costo, 
					check_ne = 'N';"; 
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

		$sql = "UPDATE entradas SET unidades = $cant WHERE id = " . $this->head_ae . ";";
		mysqli_query($this->link, $sql);
	}

} // fin de la clase Verdura

?>