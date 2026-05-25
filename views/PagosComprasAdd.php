<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosComprasAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pagos_compras: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpagos_comprasadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagos_comprasadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
            ["pivote", [fields.pivote.visible && fields.pivote.required ? ew.Validators.required(fields.pivote.caption) : null], fields.pivote.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["pago", [fields.pago.visible && fields.pago.required ? ew.Validators.required(fields.pago.caption) : null, ew.Validators.float], fields.pago.isInvalid],
            ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
            ["anexos", [fields.anexos.visible && fields.anexos.required ? ew.Validators.required(fields.anexos.caption) : null], fields.anexos.isInvalid],
            ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "proveedor": <?= $Page->proveedor->toClientList($Page) ?>,
            "tipo_pago": <?= $Page->tipo_pago->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpagos_comprasadd" id="fpagos_comprasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos_compras">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_pagos_compras_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_pagos_compras_proveedor">
    <select
        id="x_proveedor"
        name="x_proveedor"
        class="form-control ew-select<?= $Page->proveedor->isInvalidClass() ?>"
        data-select2-id="fpagos_comprasadd_x_proveedor"
        data-table="pagos_compras"
        data-field="x_proveedor"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->proveedor->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>"
        <?= $Page->proveedor->editAttributes() ?>>
        <?= $Page->proveedor->selectOptionListHtml("x_proveedor") ?>
    </select>
    <?= $Page->proveedor->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<script>
loadjs.ready("fpagos_comprasadd", function() {
    var options = { name: "x_proveedor", selectId: "fpagos_comprasadd_x_proveedor" };
    if (fpagos_comprasadd.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x_proveedor", form: "fpagos_comprasadd" };
    } else {
        options.ajax = { id: "x_proveedor", form: "fpagos_comprasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.pagos_compras.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote->Visible) { // pivote ?>
    <div id="r_pivote"<?= $Page->pivote->rowAttributes() ?>>
        <label id="elh_pagos_compras_pivote" for="x_pivote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote->caption() ?><?= $Page->pivote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote->cellAttributes() ?>>
<span id="el_pagos_compras_pivote">
<input type="<?= $Page->pivote->getInputTextType() ?>" name="x_pivote" id="x_pivote" data-table="pagos_compras" data-field="x_pivote" value="<?= $Page->pivote->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote->formatPattern()) ?>"<?= $Page->pivote->editAttributes() ?> aria-describedby="x_pivote_help">
<?= $Page->pivote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_pagos_compras_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_pagos_compras_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" name="x_moneda" id="x_moneda" data-table="pagos_compras" data-field="x_moneda" value="<?= $Page->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->moneda->formatPattern()) ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <div id="r_pago"<?= $Page->pago->rowAttributes() ?>>
        <label id="elh_pagos_compras_pago" for="x_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pago->caption() ?><?= $Page->pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pago->cellAttributes() ?>>
<span id="el_pagos_compras_pago">
<input type="<?= $Page->pago->getInputTextType() ?>" name="x_pago" id="x_pago" data-table="pagos_compras" data-field="x_pago" value="<?= $Page->pago->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pago->formatPattern()) ?>"<?= $Page->pago->editAttributes() ?> aria-describedby="x_pago_help">
<?= $Page->pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago"<?= $Page->tipo_pago->rowAttributes() ?>>
        <label id="elh_pagos_compras_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_compras_tipo_pago">
    <select
        id="x_tipo_pago"
        name="x_tipo_pago"
        class="form-select ew-select<?= $Page->tipo_pago->isInvalidClass() ?>"
        <?php if (!$Page->tipo_pago->IsNativeSelect) { ?>
        data-select2-id="fpagos_comprasadd_x_tipo_pago"
        <?php } ?>
        data-table="pagos_compras"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Page->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_pago->getPlaceHolder()) ?>"
        <?= $Page->tipo_pago->editAttributes() ?>>
        <?= $Page->tipo_pago->selectOptionListHtml("x_tipo_pago") ?>
    </select>
    <?= $Page->tipo_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_pago->getErrorMessage() ?></div>
<?= $Page->tipo_pago->Lookup->getParamTag($Page, "p_x_tipo_pago") ?>
<?php if (!$Page->tipo_pago->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagos_comprasadd", function() {
    var options = { name: "x_tipo_pago", selectId: "fpagos_comprasadd_x_tipo_pago" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagos_comprasadd.lists.tipo_pago?.lookupOptions.length) {
        options.data = { id: "x_tipo_pago", form: "fpagos_comprasadd" };
    } else {
        options.ajax = { id: "x_tipo_pago", form: "fpagos_comprasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos_compras.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->anexos->Visible) { // anexos ?>
    <div id="r_anexos"<?= $Page->anexos->rowAttributes() ?>>
        <label id="elh_pagos_compras_anexos" for="x_anexos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->anexos->caption() ?><?= $Page->anexos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->anexos->cellAttributes() ?>>
<span id="el_pagos_compras_anexos">
<textarea data-table="pagos_compras" data-field="x_anexos" name="x_anexos" id="x_anexos" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->anexos->getPlaceHolder()) ?>"<?= $Page->anexos->editAttributes() ?> aria-describedby="x_anexos_help"><?= $Page->anexos->EditValue ?></textarea>
<?= $Page->anexos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->anexos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2"<?= $Page->pivote2->rowAttributes() ?>>
        <label id="elh_pagos_compras_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote2->cellAttributes() ?>>
<span id="el_pagos_compras_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" name="x_pivote2" id="x_pivote2" data-table="pagos_compras" data-field="x_pivote2" value="<?= $Page->pivote2->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote2->formatPattern()) ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpagos_comprasadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpagos_comprasadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("pagos_compras");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.// Write your table-specific startup script here, no need to add script tags.
    $("#x_pago").prop('readonly', true);
    $("#r_pivote").hide();
    $("#r_pivote2").hide();
    $("#x_proveedor").change(function(){
    	$("#r_pivote").show();
    	var proveedor = $("#x_proveedor").val();
    	$("#r_pivote").html("");
    	if(proveedor == "") {
    		$("#r_pivote").hide();
    		$("#r_pivote2").hide();
    		return true;
    	}
    	$.ajax({
    	  url : "include/Proveedor_Facturas_Buscar_Administrativas.php",
    	  type: "GET",
    	  data : {proveedor: proveedor},
    	  beforeSend: function(){
    	    $("#r_pivote").html("Por Favor Espere. . .");
    	    //////$("#monto").val(0.00);
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		var rs = '';
    		if(data == "0")
    			rs = '<div class="container"><div class="alert alert-success" role="alert">No hay facturas pendientes por pagar al proveedor</div></div>';
    		else {
    			//$("#x_monto").prop('readonly', false);
    			$("#x_monto_recibido").prop('readonly', false);
    			rs = data;
    		}
    		$("#r_pivote").html(rs);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    $("#x_tipo_pago").change(function(){
    	$("#r_pivote2").show();
    	var proveedor = $("#x_proveedor").val();
    	var tipo_pago = $("#x_tipo_pago").val();
    	var pagos = $("#pagos").val();
    	var moneda = $("#x_moneda").val();
    	var tasa_usd = $("#tasa_usd").val();
    	$("#r_pivote2").html("");
    	if(proveedor == "") {
    		//$("#r_pivote2").hide();
    		alert("Seleccione un proveedor");
    		location.reload();
    		return true;
    	}
    	$.ajax({
    	  url : "include/Proveedor_Tipo_Pago.php",
    	  type: "GET",
    	  data : {proveedor: proveedor, tipo_pago: tipo_pago, pagos: pagos, moneda: moneda, tasa_usd: tasa_usd},
    	  beforeSend: function(){
    	    $("#r_pivote2").html("Por Favor Espere. . .");
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		var rs = '';
    		rs = data;
    		//$("#x_monto").prop('readonly', false);
    		$("#r_pivote2").html(rs);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    $(document).ready(function() {
    	//alert("<?php echo isset($_GET["id_compra"]) ? $_GET["id_compra"] : 0; ?>");
    	var id = <?php echo isset($_GET["id_compra"]) ? intval($_GET["id_compra"]) : 0; ?>;
    	if(id != 0) {
    		$("#r_pivote").show();
    		$.ajax({
    		  url : "include/buscar_factura_proveedor.php",
    		  type: "GET",
    		  data : {id: id},
    		  beforeSend: function(){
    		  	$("#r_proveedor").html("Por Favor Espere. . .");
    		    $("#r_pivote").html("Por Favor Espere. . .");
    		    //////$("#monto").val(0.00);
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = data.split("|");
    			//$("#x_monto").prop('readonly', false);
    			$("#x_monto_recibido").prop('readonly', false);
    			$("#r_proveedor").html(rs[0]);
    			$("#r_pivote").html(rs[1]);
    			//////$("#monto").val(rs[2]);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    	<?php
    	$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    	?>
    	$("#x_moneda").val("<?php echo ExecuteScalar($sql); ?>");
    	$("#x_moneda").prop('readonly', true);
    	$("#r_moneda").hide();
    });
});
</script>
