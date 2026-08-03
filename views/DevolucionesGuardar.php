<?php

namespace PHPMaker2024\mandrake;

// Page object
$DevolucionesGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// Saneamiento estricto de variables iniciales para evitar SQL Injection
$id       = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
$items    = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 0;
$txtNota  = isset($_POST["txtNota"]) ? trim($_POST["txtNota"]) : "";
$error    = "";
$newid    = 0;

// Estilos globales de Bootstrap aplicados directamente para notificaciones uniformes
echo '<div class="container my-5">';

if (strlen($txtNota) < 20) {
    echo '
    <div class="alert alert-warning shadow-sm border-start border-4 border-warning mb-4">
        <h4 class="alert-heading fw-bold">⚠️ Validación de Motivo</h4>
        <p class="mb-0">Debe indicar con un detalle mínimo de 20 caracteres las razones de la devolución.</p>
    </div>';
    echo '<a href="Devoluciones" class="btn btn-primary px-4 fw-bold">Regresar a Devoluciones</a>';
} else {
    $id_old = $id;

    // Consultas protegidas y seguras
    $sql = "SELECT cliente, IFNULL(descuento, 0) AS descuento, nro_documento FROM salidas WHERE id = $id_old;";
    $row = ExecuteRow($sql);
    
    $cliente     = intval($row["cliente"]);
    $descuento   = floatval($row["descuento"]);
    $nro_documento = $row["nro_documento"]; // Este valor viene interno del sistema
    // $pago_divisa = $row["pago_divisa"];

    // Obtener parámetros por defecto de forma segura
    $almacen = ExecuteScalar("SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';");
    $moneda  = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';");

    $proveedor = 1;
    // Escapar texto libre de forma obligatoria para evitar inyecciones SQL
    $nota = addslashes($txtNota); 

    $sw = true;
    
    // Verificación de duplicados en el mismo día
    $sql = "SELECT id FROM entradas 
            WHERE tipo_documento = 'TDCNRP' 
              AND cliente = $cliente 
              AND nota = '$nota' 
              AND DATE_FORMAT(fecha, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d');";
              
    if ($row_dup = ExecuteRow($sql)) { 
        $sw = false;
        $error = "La devolución se ejecutó, pero se detectó un intento de procesamiento duplicado para este cliente el día de hoy. Por favor REVISE el historial.";
    }
  
    if ($sw) {
        // OPCIONAL: Aquí se iniciaría una transacción si tu conector lo soporta: Execute("START TRANSACTION;");

        $sql = "INSERT INTO entradas
                    (id, tipo_documento, username, fecha, 
                    proveedor, nro_documento, almacen, estatus, 
                    id_documento_padre, consignacion, cliente, moneda, nota, descuento)
                VALUES 
                    (NULL, 'TDCNRP', '" . CurrentUserName() . "', NOW(), 
                    $proveedor, '', '$almacen', 'PROCESADO', 
                    $id_old, 'N', $cliente, '$moneda', '$nota', $descuento);";
        Execute($sql);

        // CAPTURA INMEDIATA: Guardamos este ID para que recargas o abonos no lo pisen
        $newid = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));

        $articulo_insertado_ok = false;
        
        for ($i = 1; $i <= $items; $i++) {
            $control1 = "x" . $i . "_Articulo";
            $control2 = "x" . $i . "_Cantidad";
            $control3 = "x" . $i . "_Costo";
            $control4 = "x" . $i . "_IdMovimiento";
            
            if (isset($_POST[$control1])) {
                $articulo     = intval($_POST[$control1]);
                $cantidad     = floatval($_POST[$control2]);
                $costo        = floatval($_POST[$control3]);
                $costo_total  = $cantidad * $costo;

                $id_movimiento_origen = isset($_POST[$control4])
                    ? intval($_POST[$control4])
                    : 0;

                $lote = "";
                $fecha_vencimiento = null;

                if ($id_movimiento_origen > 0) {
                    $sql_origen = "
                        SELECT
                            IFNULL(lote, '') AS lote,
                            fecha_vencimiento
                        FROM entradas_salidas
                        WHERE id = $id_movimiento_origen
                        AND id_documento = $id_old
                        AND tipo_documento = 'TDCNET'
                        AND articulo = $articulo
                        LIMIT 1;
                    ";

                    if ($row_origen = ExecuteRow($sql_origen)) {
                        $lote = trim($row_origen["lote"] ?? "");
                        $fecha_vencimiento = $row_origen["fecha_vencimiento"] ?? "1990-01-01";
                    }
                }

                $lote_sql = AdjustSql($lote);

                $fecha_vencimiento_sql =
                    !empty($fecha_vencimiento)
                        ? "'" . AdjustSql($fecha_vencimiento) . "'"
                        : "'1990-01-01'";

                $sql = "INSERT INTO entradas_salidas
                            (
                                id,
                                tipo_documento,
                                id_documento,
                                fabricante,
                                articulo,
                                almacen,
                                lote,
                                fecha_vencimiento,
                                cantidad_articulo,
                                articulo_unidad_medida,
                                cantidad_unidad_medida,
                                cantidad_movimiento,
                                costo_unidad,
                                costo,
                                check_ne
                            )
                        VALUES
                            (
                                NULL,
                                'TDCNRP',
                                $newid,
                                1,
                                $articulo,
                                '" . AdjustSql($almacen) . "',
                                '$lote_sql',
                                $fecha_vencimiento_sql,
                                $cantidad,
                                'UDM001',
                                1,
                                $cantidad,
                                $costo,
                                $costo_total,
                                'S'
                            );";
                Execute($sql);

                $articulo_insertado_ok = true;
            }
        }
    }

    // Si todo marchó bien con los artículos, calculamos finanzas y créditos
    if ($articulo_insertado_ok) {
        $nuevo_nro_documento = "DEV-" . $nro_documento;
        // 1. Actualizamos la nueva entrada (la devolución) con el consecuente correlativo
        $sql_entrada = "UPDATE entradas 
                        SET nro_documento = '$nuevo_nro_documento' 
                        WHERE id = $newid;";
        Execute($sql_entrada);

        // 2. Marcamos la salida original como devuelta en el campo email
        $sql_salida = "UPDATE salidas 
                       SET email = 'DEVOLUCION' 
                       WHERE id = $id_old;";
        Execute($sql_salida);

        $sql = "UPDATE entradas AS a 
        JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
              FROM entradas_salidas 
              WHERE tipo_documento = 'TDCNRP' AND id_documento = $newid 
              GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
        SET a.unidades = b.cantidad 
        WHERE a.id = $newid;";
        Execute($sql);

        /*
        $sql = "SELECT IFNULL(SUM(costo), 0) AS costo FROM entradas_salidas WHERE id_documento = $newid AND tipo_documento = 'TDCNRP'";
        $monto_moneda = floatval(ExecuteScalar($sql));
        $monto_moneda = ($monto_moneda - ($monto_moneda * ($descuento / 100)));

        $tasa = floatval(ExecuteScalar("SELECT tasa FROM tasa_usd WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;"));

        $referencia  = $newid;
        $metodo_pago = "DV";
        $monto_bs    = $tasa * $monto_moneda;
        $tasa_usd    = $tasa;
        $monto_usd   = $monto_moneda;
        $username    = CurrentUserName();

        // FLUJO DE NOTAS DE CRÉDITO Y ABONOS (CONDICIONAL)
        if ($descuento >= 25 || $pago_divisa == "S") {
            $nro_recibo = intval(ExecuteScalar("SELECT IFNULL(MAX(nro_recibo), 0)+1 FROM abono2;"));

            $sql = "INSERT INTO abono2 SET     
                      id = NULL, cliente = $cliente, fecha = NOW(), metodo_pago = 'IMPRIMIR',
                      nro_recibo = $nro_recibo, nota = 'POR DEVOLUCION | $nota', username = '$username';";
            Execute($sql);
            $Abono = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));
                                  
            $nro_recibo_recarga = intval(ExecuteScalar("SELECT IFNULL(MAX(nro_recibo), 0)+1 FROM recarga2;"));

            $sql = "INSERT INTO recarga2(
                        id, cliente, fecha, metodo_pago, monto_moneda, moneda, tasa_moneda,
                        monto_bs, tasa_usd, monto_usd, saldo, nota, username, reverso, nota_recepcion, nro_recibo, abono)
                    VALUES (
                        NULL, $cliente, NOW(), '$metodo_pago', $monto_moneda, '$moneda', $tasa,
                        $monto_bs, $tasa_usd, $monto_usd, 0, 'Devolución de Nota de Recepción según nota: $nota',
                        '$username', 'N', $referencia, '$nro_recibo_recarga', $Abono)"; 
            Execute($sql);
            
            $id_recarga = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));
            
            $saldo = floatval(ExecuteScalar("SELECT IFNULL(SUM(monto_usd), 0) FROM recarga2 WHERE cliente = $cliente;"));
            Execute("UPDATE recarga2 SET saldo = $saldo WHERE id = $id_recarga;");

            $monto_abono = floatval(ExecuteScalar("SELECT SUM(monto_usd) FROM recarga2 WHERE abono = $Abono;"));
            Execute("UPDATE border abono2 SET pago = $monto_abono WHERE id = $Abono;");
        } 
        else {
            // FLUJO CLIENTES REGULARES
            $nro_recibo = intval(ExecuteScalar("SELECT IFNULL(MAX(nro_recibo), 0)+1 FROM abono;"));

            $sql = "INSERT INTO abono SET     
                      id = NULL, cliente = $cliente, fecha = NOW(), metodo_pago = 'IMPRIMIR',
                      nro_recibo = $nro_recibo, nota = 'POR DEVOLUCION | $nota', username = '$username';";
            Execute($sql);
            $Abono = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));
                                  
            $nro_recibo_recarga = intval(ExecuteScalar("SELECT IFNULL(MAX(nro_recibo), 0)+1 FROM recarga;"));

            $sql = "INSERT INTO recarga(
                        id, cliente, fecha, metodo_pago, monto_moneda, moneda, tasa_moneda,
                        monto_bs, tasa_usd, monto_usd, saldo, nota, username, reverso, nota_recepcion, nro_recibo, abono)
                    VALUES (
                        NULL, $cliente, NOW(), '$metodo_pago', $monto_moneda, '$moneda', $tasa,
                        $monto_bs, $tasa_usd, $monto_usd, 0, 'Devolución de Nota de Recepción según nota: $nota',
                        '$username', 'N', $referencia, '$nro_recibo_recarga', $Abono)"; 
            Execute($sql);
            
            $id_recarga = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));
            
            $saldo = floatval(ExecuteScalar("SELECT IFNULL(SUM(monto_usd), 0) FROM recarga WHERE cliente = $cliente;"));
            Execute("UPDATE recarga SET saldo = $saldo WHERE id = $id_recarga;");

            $monto_abono = floatval(ExecuteScalar("SELECT SUM(monto_usd) FROM recarga WHERE abono = $Abono;"));
            Execute("UPDATE abono SET pago = $monto_abono WHERE id = $Abono;");
        }

        // Cierre de actualizaciones de la factura maestra
        Execute("UPDATE entradas SET nro_documento = 'ABONO - $nro_recibo', tasa_dia = $tasa, monto_total = $monto_usd, total = $monto_usd, monto_pagar = $monto_usd, monto_usd = $monto_usd WHERE id = $referencia;");
        Execute("UPDATE salidas SET email = 'DEVOLUCION' WHERE id = $id_old;");

        // REVERSIÓN DE PUNTOS FIDELIDAD
        $sql = "SELECT a.cantidad_movimiento, c.codigo_ims AS referencia, c.puntos_ventas 
                FROM entradas_salidas AS a 
                JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
                JOIN articulo AS c ON c.id = a.articulo 
                WHERE a.id_documento = $newid AND a.tipo_documento = 'TDCNRP';";
        $rows_puntos = ExecuteRows($sql);  
        
        foreach ($rows_puntos as $key => $value) {
            $ref = $value["referencia"];
            $puntos = (-1) * intval($value["puntos_ventas"]);

            $sql = "SELECT tipo FROM puntos WHERE cliente = $cliente AND nro_documento = '$nro_documento' AND referencia = '$ref';";
            if ($row_pt = ExecuteRow($sql)) {
                $nota_puntos = "SE HACE DEVOLUCION DE NOTA DE ENTREGA # $nro_documento Ref # $ref";

                $sql = "INSERT INTO puntos (id, cliente, fecha, tipo, nro_documento, referencia, puntos, saldo, nota, username)
                        VALUES (NULL, $cliente, '" . date("Y-m-d") . "', 'DV', '$nro_documento', '$ref', $puntos, 0, '$nota_puntos', '$username')";
                Execute($sql);
            
                $saldo_puntos = intval(ExecuteScalar("SELECT IFNULL(SUM(puntos), 0) FROM puntos WHERE cliente = $cliente;"));
                $iddoc = intval(ExecuteScalar("SELECT LAST_INSERT_ID();"));

                Execute("UPDATE puntos SET saldo = $saldo_puntos WHERE id = $iddoc;");      
            }
        }
        */
        
        // INTERFAZ DE ÉXITO Y REDIRECCIÓN LIMPIA
        echo '
        <div class="alert alert-success shadow-sm border-start border-4 border-success p-4 mb-4" role="alert">
            <h4 class="alert-heading fw-bold">🎉 ¡Proceso Completado con Éxito!</h4>
            <p class="mb-2">La devolución ha sido procesada y el inventario fue actualizado correctamente.</p>
            <hr>
            <p class="small text-muted mb-0">Redirigiendo al panel de devoluciones en unos instantes...</p>
        </div>';
        ?>
        <script>
            setTimeout(function() {
                window.location.href = "Devoluciones?sw=1";
            }, 1500);
        </script>
        <?php
    } else { 
        // Si falló el proceso o no se tildaron ítems
        if ($error != "" && $newid == 0) {
            echo '<div class="alert alert-danger shadow-sm p-4"><h4>❌ Error: ' . $error . '</h4></div>';
        } else {
            if ($newid > 0) {
                Execute("DELETE FROM entradas WHERE id = $newid");
            }
            echo '
            <div class="alert alert-warning shadow-sm border-start border-4 border-warning p-4 mb-4">
                <h4>⚠️ Solicitud Inválida</h4>
                <p class="mb-0">No se ha podido procesar la operación porque no seleccionó ningún artículo válido para la devolución.</p>
            </div>';
        }
        echo '<a href="Devoluciones" class="btn btn-secondary px-4">Volver a Intentar</a>';
    }
}

echo '</div>'; // Cierre del contenedor de Bootstrap
?>
<?= GetDebugMessage() ?>
