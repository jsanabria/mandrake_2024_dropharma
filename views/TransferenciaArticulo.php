<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticulo = &$Page;
?>
<?php
$Page->showMessage();
?>
	<form id="frm" name="frm" method="post" action="TransferenciaArticuloListar">
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input name="articulo" type="text" class="form-control" placeholder="Buscar Art&iacute;culo por C&oacute;digo o Descripci&oacute;n...">
      <span class="input-group-btn">
        <input type="submit" id="Buscar" class="btn btn-default" type="button" value="Buscar!">
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
  	</form>

<?= GetDebugMessage() ?>
