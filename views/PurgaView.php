<?php

namespace PHPMaker2024\mandrake;

// Page object
$PurgaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fpurgaview" id="fpurgaview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { purga: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpurgaview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpurgaview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Client script
    // Write your table-specific client script here, no need to add script tags.
    window.Procesar = function (id, almacen) {
    	if(confirm("Seguro de procesar este conteo? Esta operación agotará la existencia con un ajuste de salida y creará una ajuste de entrada con la cantidad indicada.")) {
    		$.ajax({
    		  url: '../include/procesar_purga_lote.php',
    		  type: 'GET',
    		  data: { "purga": id, "username": '<?php echo CurrentUserName(); ?>', "almacen": almacen },
    		  beforeSend: function(){
    		  }
    		})
    		.done(function(data) {
    		  if(data == "1") {
    		  	alert("Conteo procesado exitosamente!");
    		  	location.reload();
    		  }
    		  else {
    		  	alert("NO SE EJECUTO EL PROCESO... " + data)
    		  }
    		})
    		.fail(function(data) {
    		})
    		.always(function(data) {
    		});
    	}
    };
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="purga">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_purga_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_purga__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_purga_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota"<?= $Page->nota->cellAttributes() ?>>
<span id="el_purga_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->procesado->Visible) { // procesado ?>
    <tr id="r_procesado"<?= $Page->procesado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_procesado"><?= $Page->procesado->caption() ?></span></td>
        <td data-name="procesado"<?= $Page->procesado->cellAttributes() ?>>
<span id="el_purga_procesado">
<span<?= $Page->procesado->viewAttributes() ?>>
<?= $Page->procesado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->username_procesa->Visible) { // username_procesa ?>
    <tr id="r_username_procesa"<?= $Page->username_procesa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_username_procesa"><?= $Page->username_procesa->caption() ?></span></td>
        <td data-name="username_procesa"<?= $Page->username_procesa->cellAttributes() ?>>
<span id="el_purga_username_procesa">
<span<?= $Page->username_procesa->viewAttributes() ?>>
<?= $Page->username_procesa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->salidas->Visible) { // salidas ?>
    <tr id="r_salidas"<?= $Page->salidas->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_salidas"><?= $Page->salidas->caption() ?></span></td>
        <td data-name="salidas"<?= $Page->salidas->cellAttributes() ?>>
<span id="el_purga_salidas">
<span<?= $Page->salidas->viewAttributes() ?>>
<?php if (!EmptyString($Page->salidas->getViewValue()) && $Page->salidas->linkAttributes() != "") { ?>
<a<?= $Page->salidas->linkAttributes() ?>><?= $Page->salidas->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->salidas->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->entradas->Visible) { // entradas ?>
    <tr id="r_entradas"<?= $Page->entradas->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_purga_entradas"><?= $Page->entradas->caption() ?></span></td>
        <td data-name="entradas"<?= $Page->entradas->cellAttributes() ?>>
<span id="el_purga_entradas">
<span<?= $Page->entradas->viewAttributes() ?>>
<?php if (!EmptyString($Page->entradas->getViewValue()) && $Page->entradas->linkAttributes() != "") { ?>
<a<?= $Page->entradas->linkAttributes() ?>><?= $Page->entradas->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->entradas->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("purga_detalle", explode(",", $Page->getCurrentDetailTable())) && $purga_detalle->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("purga_detalle", "TblCaption") ?>&nbsp;<?= str_replace("%s", "light", str_replace("%c", Container("purga_detalle")->Count, $Language->phrase("DetailCount"))) ?></h4>
<?php } ?>
<?php include_once "PurgaDetalleGrid.php" ?>
<?php } ?>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("#btnCerrar").click(function() {
    });

    /*
    $("#btnProcesar").click(function() { 
        var id = $("#idPurga").value();
        var almacen = $("#AlmacenOrigen").value();
        alert(id);
        alert(almacen);
        Procesar(id, almacen);
    });
    */
    $("#btnAceptar").click(function() {
    	var xuser = $("#xusername").val();
    	var xpass = $("#xpassword").val();
    	var idPurga = $("#idPurga").val(); 
    	var usercaja = "<?php echo CurrentUserName(); ?>";
    	var almacen = $("#AlmacenOrigen").val();
    	$.ajax({
    	  url : "../include/Validar_Usuario.php",
    	  type: "GET",
    	  data : {usernama: xuser, password: xpass, idPurga: idPurga, usercaja: usercaja},
    	  beforeSend: function(){
    	  }
    	})
    	.done(function(MyResult) {
    		if(MyResult == "S") {
    			Procesar(idPurga, almacen);
    		}
    		else {
    			alert("!!! NO AUTORIZADO !!!");
    		}
    		$('#ventanaModal').modal('hide');
    		$("#xusername").val("");
    		$("#xpassword").val("");
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	});
    });
});
</script>
<?php } ?>
