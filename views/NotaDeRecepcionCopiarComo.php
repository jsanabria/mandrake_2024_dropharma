<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeRecepcionCopiarComo = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"];

$row = ExecuteRow("SELECT nro_documento, tipo_documento, documento FROM entradas WHERE id = $id;");
$doc = $row["nro_documento"];
$tipo_documento = $row["tipo_documento"];
$documento = $row["documento"];

$titulo = "<b>COPIA PARCIAL O COMPLETA DE LA NOTA DE RECEPCION</b>";
?>

<div class="container">
  <div class="row">
      <h5>Copiar Documento <?= $titulo ?> # <?php echo $doc; ?></h5><br>
      <h5>Proceso de copiado de documento <b>(Nota: Los Items Chequedos en la recepci&oacute;n suman el inventario)</b></h5><br>
  </div>
  <form name="frm" method="post" class="form-inline" action="NotaDeRecepcionCopiarComoEng">
    <div class="row text-aling-center">
      <div class="list-group mx-0">
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="CP" checked>
          <span>
            Copia Parcial
            <small class="d-block text-muted">Crea una Copia de la Nota de los Item no Chequiados</small>
          </span>
        </label>
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="CC">
          <span>
            Copia Completa
            <small class="d-block text-muted">Crea una Copia de la Nota Original</small>
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
