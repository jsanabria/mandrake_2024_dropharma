<?php

namespace PHPMaker2024\mandrake;

// Page object
$DevolucionesBuscar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$nota_entrega = isset($_POST["NotaEntrega"]) ? trim($_POST["NotaEntrega"]) : (isset($_GET["NotaEntrega"]) ? trim($_GET["NotaEntrega"]) : "");

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
            AND a.estatus = 'PROCESADO' 
        ORDER BY a.id DESC LIMIT 0, 100"; 
$rows = ExecuteRows($sql);

// Contamos el total de órdenes encontradas
$total_ordenes = count($rows);
?>

<form id="frm" name="frm" method="post" action="DevolucionesVer">
    <div class="container my-4">
        
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 class="mb-0 text-dark fw-bold">Resultados de la Búsqueda</h4>
                <p class="text-muted small mb-0">Selecciona una orden de entrega para proceder con la devolución.</p>
            </div>
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm">
                <?php echo $total_ordenes; ?> <?php echo ($total_ordenes == 1) ? 'Orden encontrada' : 'Ordenes encontradas'; ?>
            </span>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Nro. Documento</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_ordenes > 0) {
                            foreach ($rows as $key => $value) {
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="xNota" id="xNota_<?php echo $value["id"]; ?>" value="<?php echo $value["id"]; ?>" required>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary"><?php echo $value["nro_documento"]; ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted"><i class="bi bi-calendar3 me-1"></i> <?php echo $value["fecha"]; ?></span>
                                    </td>
                                    <td class="text-dark fw-medium">
                                        <?php echo $value["nombre"]; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <span class="fs-3 d-block mb-2">🔍</span>
                                    No se encontraron órdenes de entrega que coincidan con la búsqueda.
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-light border px-4" onclick="history.back();">
                Regresar
            </button>
            <?php if ($total_ordenes > 0): ?>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    Ver Nota
                </button>
            <?php endif; ?>
        </div>

    </div>
</form>
<?= GetDebugMessage() ?>
