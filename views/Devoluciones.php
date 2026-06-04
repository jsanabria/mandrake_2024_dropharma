<?php

namespace PHPMaker2024\mandrake;

// Page object
$Devoluciones = &$Page;
?>
<?php
$Page->showMessage();
?>
<!-- Formulario de Búsqueda -->
<form id="frm" name="frm" method="post" action="DevolucionesBuscar" class="mb-4">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <label for="NotaEntrega" class="form-label fw-bold text-secondary mb-2">Módulo de Devoluciones</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">
                            <!-- Icono de lupa opcional (puedes usar FontAwesome o Bootstrap Icons) -->
                            <i class="bi bi-search"></i> 🔍
                        </span>
                        <input name="NotaEntrega" id="NotaEntrega" type="text" class="form-control form-control-lg" placeholder="Buscar Nota de Entrega..." required>
                        <button type="submit" id="Buscar" class="btn btn-primary px-4 fw-bold">
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
if (isset($_REQUEST["sw"])) {
    if ($_REQUEST["sw"] == "1") {
?>
        <!-- Mensaje de Éxito Estilizado -->
        <div class="alert alert-success bg-success text-white shadow-sm border-0 p-4 rounded-3 d-flex align-items-start" role="alert">
            <span class="fs-2 me-3 lh-1">✅</span>
            <div>
                <h4 class="alert-heading fw-bold mb-2 text-white">¡Proceso Exitoso!</h4>
                <p class="mb-3 opacity-90 text-white fs-6">
                    <!-- Se ha generado un abono al cliente y los artículos ingresaron al inventario con una Nota de Recepción. -->
                    Los artículos ingresaron al inventario con una Nota de Recepción.
                </p>
                <hr class="border-white opacity-25 my-2">
                <p class="small mb-0 opacity-75 text-white fw-light">
                    <strong>Nota:</strong> Este proceso no anula la Nota de Recepción de origen y la misma no debe ser anulada.
                </p>
            </div>
        </div>
<?php
    }
}
?>
<?= GetDebugMessage() ?>
