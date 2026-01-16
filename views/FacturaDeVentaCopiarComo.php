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
case "FC":
    $titulo = "FACTURA";
    break;
case "NC":
    $titulo = "NOTA DE CREDITO";
    break;
case "ND":
    $titulo = "NOTA DE DEBITO";
    break;
}
?>

<div class="container">
  <div class="row">
      <h2>Copiar Documento <?= $titulo ?> # <?php echo $doc; ?> como</h2><br>
      <h4>Proceso de copiado de documento tipo:</h4><br>
  </div>
  <form name="frm" method="post" class="form-inline" action="FacturaDeVentaDetalleCopia">
    <div class="row text-aling-center">
      <div class="list-group mx-0">
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="FC003">
          <span>
            Factura de Venta
            <small class="d-block text-muted">Crea una Copia de la Factura de Ventas</small>
          </span>
        </label>
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="NC010" <?= (($documento=="FC" or $documento=="ND") ? "checked" : "") ?>>
          <span>
            Nota de Cr&eacute;dito
            <small class="d-block text-muted">Crea una Copia de la Factura de ventas a una Nota de Cr&eacute;dito</small>
          </span>
        </label>
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="ND011" <?= ($documento=="NC" ? "checked" : "") ?>>
          <span>
            Nota de D&eacute;bito
            <small class="d-block text-muted">Crea una Copia de la Factura de Ventas a una Nota de D&eacute;bito</small>
          </span>
        </label>
      </div>

        <input type="hidden" class="form-control" id="doc" name="doc" value="<?php echo $doc; ?>">
        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" class="form-control" id="tipo_documento" name="tipo_documento" value="<?php echo $tipo_documento; ?>">
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Realizar Copia</button>
  </form>

</div>


<?= GetDebugMessage() ?>
