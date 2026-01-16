<?php

namespace PHPMaker2024\mandrake;

// Page object
$YaFueProcesado = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="alert alert-danger" role="alert">
	El pedido ya fu&eacute; procesado; !!! Verifique !!! 
	<a class="btn btn-primary" href="salidaslist.php?tipo=TDCNET">Click aqu&iacute; para continuar.</a>
</div>


<?= GetDebugMessage() ?>
