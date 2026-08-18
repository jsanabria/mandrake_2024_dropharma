<?php

namespace PHPMaker2024\mandrake;

// Page object
$FtpSubirPedidos = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

// 1. Verificación de seguridad y bloqueos
if (trim($_COOKIE["strcon"] ?? '') !== "dropharm_mandrake") {
    header("Location: Home");
    die();
}

$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
$bloquea = ExecuteScalar($sql);
if ($bloquea === "SI") { 
    header("Location: Home");
    die();
} 

// 2. Parámetros globales
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$row = ExecuteRow($sql);
$almacen = $row["almacen"] ?? '';
$tipo_documento = "TDCPDV";

/**
 * Función modular para procesar lotes de pedidos FTP
 */
function procesarArchivosFTP(
    string $path, 
    string $pathOld, 
    string $prefix, 
    string $delimiter, 
    string $username, 
    string $tipoDocumento
): int {
    $cnt = 0;
    $arrFiles = is_dir($path) ? scandir($path) : [];

    foreach ($arrFiles as $archivo) { 
        if ($archivo === '.' || $archivo === '..') {
            continue;
        }

        if (str_starts_with($archivo, $prefix)) { 
            $sql = "SELECT factura FROM ftp_fact_pedi_procesado WHERE pedido = '$archivo';";
            
            if (!ExecuteRow($sql)) {
                // Extracción de datos del nombre de archivo
                $myfile = str_replace([$prefix, ".txt"], "", strtolower($archivo));
                $separator = ($prefix === "PEDIDO") ? "_" : "-";
                $arrDatos = explode($separator, $myfile);

                if ($prefix === "PEDIDO") {
                    $cliente = $arrDatos[0] ?? null;
                    $pedido  = $arrDatos[1] ?? null;
                } else {
                    $cliente = $arrDatos[1] ?? null;
                    $pedido  = $arrDatos[2] ?? null;
                }

                $xSql = "SELECT IFNULL(descuento, 0) AS descuento FROM cliente WHERE id = $cliente;";
                $xDescuento = floatval(ExecuteScalar($xSql));

                $nota = "CREADO VIA FTP DESDE ARCHVO $archivo Nro Pedido $pedido";
                $filePath = $path . $archivo;

                if (!file_exists($filePath)) {
                    continue;
                }

                // Lectura del archivo de texto
                $fp = fopen($filePath, "r");
                $arrx = [];
                $lista = [];

                while (!feof($fp)) { 
                    $linea = fgets($fp);
                    if (trim($linea) !== "") { 
                        $detalle = explode($delimiter, $linea);

                        if ($prefix === "PEDIDO") {
                            $articulo = $detalle[3] ?? null;
                            $nombre   = $detalle[1] ?? null;
                            $cantidad = $detalle[2] ?? null;
                        } else {
                            $articulo = $detalle[0] ?? null;
                            $nombre   = $detalle[1] ?? null;
                            $cantidad = $detalle[2] ?? null;
                        }

                        $sql = "SELECT codigo, lista_pedido FROM articulo WHERE id = $articulo;";
                        $row = ExecuteRow($sql);
                        $cod_articulo = $row["codigo"] ?? '';
                        $lista_pedido = $row["lista_pedido"] ?? '';

                        $arrx[] = [
                            "articulo"     => $articulo,
                            "cod_articulo" => $cod_articulo,
                            "lista_pedido" => $lista_pedido,
                            "nombre"       => $nombre,
                            "cantidad"     => $cantidad
                        ];

                        if (!in_array($lista_pedido, $lista, true)) {
                            $lista[] = $lista_pedido;
                        }
                    }
                } 
                fclose($fp);

                // Inserción de cabecera y detalles por lista
                foreach ($lista as $listaPedido) { 
                    $sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = '$tipoDocumento';";
                    $row = ExecuteRow($sql);
                    $consecutivo = intval($row["consecutivo"] ?? 0) + 1; 
                    $nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

                    $fechaActual = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO salidas
                                (id, tipo_documento, username, fecha, cliente, nro_documento, nota, estatus, asesor, lista_pedido, asesor_asignado, moneda, descuento, nombre)
                            VALUES  
                                (NULL, '$tipoDocumento', '$username', '$fechaActual', $cliente, '$nro_documento', '$nota', 'NUEVO', '$username', '$listaPedido', '$username', 'USD', $xDescuento, '$username');";
                    Execute($sql);

                    $row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
                    $new_id = $row["id"];                       
                    $insart = new PdvLineaGuardar();

                    foreach ($arrx as $value2) {
                        if ($value2["lista_pedido"] === $listaPedido) {
                            $cod_articulo = $value2["cod_articulo"];
                            $cantidad     = $value2["cantidad"];

                            $sql = "SELECT b.activo, a.activo AS artact  
                                    FROM articulo AS a 
                                    JOIN fabricante AS b ON b.Id = a.fabricante 
                                    WHERE a.codigo = '$cod_articulo';";

                            if ($row101 = ExecuteRow($sql)) {
                                if ($row101["activo"] === "S" && $row101["artact"] === "S") {
                                    $insart->insertar_articulo($tipoDocumento, $new_id, $cliente, $cod_articulo, $listaPedido, $cantidad, 2);
                                }
                            }
                        }
                    }

                    $insart->ActualizarCabecera();
                    unset($insart);
                    $cnt++;
                }

                // Registrar en log y mover archivo
                $sql = "INSERT INTO ftp_fact_pedi_procesado (id, factura, pedido, fecha_hora) VALUES (NULL, '', '$archivo', NOW())";
                Execute($sql);

                if (!file_exists($pathOld)) {
                    mkdir($pathOld, 0777, true);
                }
                rename($filePath, $pathOld . $archivo);
            }
        }
    }

    return $cnt;
}

// -------------------------------------------------------------
// Proceso FTP 1
// -------------------------------------------------------------
$path1     = "/home2/dropharm/dropharmadm/ftpexportar/pedidos/";
$path1_old = "/home2/dropharm/dropharmadm/ftpexportar/pedidos_old/";

$cnt1 = procesarArchivosFTP($path1, $path1_old, "PEDIDO", ";", "FTP", $tipo_documento);

echo '<div class="alert alert-primary" role="alert">
        Proceso FTP culminado, total de pedidos generados ' . $cnt1 . '
      </div>';

// -------------------------------------------------------------
// Proceso FTP 2
// -------------------------------------------------------------
$path2     = "/home2/dropharm/dropharmadm/ftpexportar2/salidas/";
$path2_old = "/home2/dropharm/dropharmadm/ftpexportar2/pedidos_old/";

$cnt2 = procesarArchivosFTP($path2, $path2_old, "PED", "|", "FTP2", $tipo_documento);

echo '<div class="alert alert-primary" role="alert">
        Proceso FTP2 culminado, total de pedidos generados ' . $cnt2 . '
      </div>';

?>
<?= GetDebugMessage() ?>
