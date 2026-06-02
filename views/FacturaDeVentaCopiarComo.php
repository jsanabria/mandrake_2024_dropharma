<?php

namespace PHPMaker2024\mandrake;

// Page object
$FacturaDeVentaCopiarComo = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];

$row = ExecuteRow("SELECT nro_documento, tipo_documento, documento FROM salidas WHERE id = $id;");
$doc = $row["nro_documento"];
$tipo_documento = $row["tipo_documento"];
$documento = $row["documento"];

switch($documento) {
    Case "FC":
        $titulo = "FACTURA";
        break;
    Case "NC":
        $titulo = "NOTA DE CRÉDITO";
        break;
    Case "ND":
        $titulo = "NOTA DE DÉBITO";
        break;
    Default:
        $titulo = "DOCUMENTO";
        break;
}
?>

<div class="container my-4">
    <div class="card card-default shadow-sm max-width-600 mx-auto">
        <div class="card-header bg-light text-primary">
            <h3 class="card-title mb-0">
                <i class="fa-solid fa-file-invoice-dollar"></i> Emitir Ajuste de <?= $titulo ?> #<?= $doc; ?>
            </h3>
        </div>
        <div class="card-body">
            <h5 class="text-secondary mb-3">Seleccione el tipo de documento a generar:</h5>
            
            <form name="frm" method="post" action="FacturaDeVentaDetalleCopia">
                <div class="list-group mb-4">
                    
                    <?php if ($documento != "NC"): ?>
                    <label class="list-group-item d-flex gap-3 align-items-center cursor-pointer">
                        <input class="form-check-input flex-shrink-0" type="radio" id="documento_nc" name="documento" value="NC010" <?= (($documento == "FC" || $documento == "ND") ? "checked" : "") ?>>
                        <span>
                            <strong class="d-block text-dark"><i class="fa-solid fa-file-circle-minus text-danger"></i> Nota de Crédito</strong>
                            <small class="text-muted">Crea una Copia de la <?= strtolower($titulo) ?> a una Nota de Crédito</small>
                        </span>
                    </label>
                    <?php endif; ?>

                    <?php if ($documento != "ND"): ?>
                    <label class="list-group-item d-flex gap-3 align-items-center cursor-pointer">
                        <input class="form-check-input flex-shrink-0" type="radio" id="documento_nd" name="documento" value="ND011" <?= ($documento == "NC" ? "checked" : "") ?>>
                        <span>
                            <strong class="d-block text-dark"><i class="fa-solid fa-file-circle-plus text-success"></i> Nota de Débito</strong>
                            <small class="text-muted">Crea una Copia de la <?= strtolower($titulo) ?> a una Nota de Débito</small>
                        </span>
                    </label>
                    <?php endif; ?>

                </div>

                <input type="hidden" id="doc" name="doc" value="<?php echo $doc; ?>">
                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                <input type="hidden" id="tipo_documento" name="tipo_documento" value="<?php echo $tipo_documento; ?>">
                
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="SalidasList?tipo=<?= $tipo_documento ?>" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-circle-check"></i> Crear Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= GetDebugMessage() ?>
