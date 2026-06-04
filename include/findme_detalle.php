<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aseguramos la conexión de forma autónoma
if (!isset($link)) {
    require_once __DIR__ . "/connect.php"; 
}

// Capturamos parámetros vengan por el flujo inicial o por la petición AJAX (POST/GET)
$id_documento   = isset($_REQUEST["id_documento"]) ? intval($_REQUEST["id_documento"]) : (isset($id_documento) ? $id_documento : 0);
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? $_REQUEST["tipo_documento"] : (isset($tipo_documento) ? $tipo_documento : 'TDCNET');

// Sincronizamos la variable $id para el resto del script heredado
$id = $id_documento;

?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aseguramos la conexión de forma autónoma
if (!isset($link)) {
    require_once __DIR__ . "/connect.php"; 
}

// Capturamos parámetros vengan por el flujo inicial o por la petición AJAX (POST/GET)
$id_documento   = isset($_REQUEST["id_documento"]) ? intval($_REQUEST["id_documento"]) : (isset($id_documento) ? $id_documento : 0);
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? $_REQUEST["tipo_documento"] : (isset($tipo_documento) ? $tipo_documento : 'TDCNET');

// Sincronizamos la variable $id para el resto del script heredado
$id = $id_documento;

?>
                <div class="card-header bg-light py-2"><span class="small fw-bold text-secondary">Artículos en la Cesta</span></div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="text-center" style="width: 50px;">Acción</th>
                                <th>Fabricante</th>
                                <th>Artículo</th>
                                <th>Lot / Venc</th>
                                <th class="text-end">Cant.</th>
                                <th>U.M.</th>
                                <th class="text-end">Precio U.</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="ResultadoDetalle">
                            <?php 
                            $sql = "SELECT 
                                        a.id, b.nombre AS fabricante, 
                                        CONCAT(IFNULL(c.principio_activo, ''), ', ', IFNULL(c.presentacion, ''), ', ', IFNULL(c.nombre_comercial, ''), ' COD:', IFNULL(c.codigo, '')) AS articulo, 
                                        a.cantidad_articulo, d.descripcion AS unidad_medida, 
                                        a.articulo AS codart, a.articulo_unidad_medida,  
                                        a.lote, a.fecha_vencimiento, a.precio_unidad, a.precio 
                                    FROM 
                                        entradas_salidas AS a 
                                        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                                        LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
                                        LEFT OUTER JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida 
                                    WHERE 
                                        a.tipo_documento = '$tipo_documento' AND a.id_documento = '$id' 
                                    ORDER BY articulo;";
                            $rs = mysqli_query($link, $sql);
							while($row = mysqli_fetch_array($rs)) {
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="EliminarItem(<?= $row['id']; ?>)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td><?= htmlspecialchars($row["fabricante"]); ?></td>
                                    <td><?= htmlspecialchars($row["articulo"]); ?></td>
                                    <td class="text-muted small">
                                        <?= htmlspecialchars($row["lote"]) . ($row["fecha_vencimiento"] == "1990-01-01" ? "" : " (" . $row["fecha_vencimiento"] . ")"); ?>
                                    </td>
                                    <td class="text-end fw-bold"><?= $row["cantidad_articulo"]; ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row["unidad_medida"]); ?></span></td>
                                    <td class="text-end"><?= number_format(floatval($row["precio_unidad"]), 2, ",", "."); ?></td>
                                    <td class="text-end fw-bold"><?= number_format(floatval($row["precio"]), 2, ",", "."); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
