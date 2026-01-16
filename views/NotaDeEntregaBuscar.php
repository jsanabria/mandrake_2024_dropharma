<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeEntregaBuscar = &$Page;
?>
<?php
$Page->showMessage();
?>
	<form id="frm" name="frm" method="post" action="NotaDeEntregaBuscarListar">
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input name="NotaEntrega" type="text" class="form-control" placeholder="Buscar Nota de Entrega...">
      <span class="input-group-btn">
        <input type="submit" id="Buscar" class="btn btn-default" type="button" value="Buscar!">
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
  	</form>



<?= GetDebugMessage() ?>
