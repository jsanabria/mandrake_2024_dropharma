<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPlanctaAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plancta: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcont_planctaadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_planctaadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["clase", [fields.clase.visible && fields.clase.required ? ew.Validators.required(fields.clase.caption) : null], fields.clase.isInvalid],
            ["grupo", [fields.grupo.visible && fields.grupo.required ? ew.Validators.required(fields.grupo.caption) : null], fields.grupo.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["subcuenta", [fields.subcuenta.visible && fields.subcuenta.required ? ew.Validators.required(fields.subcuenta.caption) : null], fields.subcuenta.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["activa", [fields.activa.visible && fields.activa.required ? ew.Validators.required(fields.activa.caption) : null], fields.activa.isInvalid]
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
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "activa": <?= $Page->activa->toClientList($Page) ?>,
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
<form name="fcont_planctaadd" id="fcont_planctaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->clase->Visible) { // clase ?>
    <div id="r_clase"<?= $Page->clase->rowAttributes() ?>>
        <label id="elh_cont_plancta_clase" for="x_clase" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clase->caption() ?><?= $Page->clase->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->clase->cellAttributes() ?>>
<span id="el_cont_plancta_clase">
<input type="<?= $Page->clase->getInputTextType() ?>" name="x_clase" id="x_clase" data-table="cont_plancta" data-field="x_clase" value="<?= $Page->clase->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->clase->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->clase->formatPattern()) ?>"<?= $Page->clase->editAttributes() ?> aria-describedby="x_clase_help">
<?= $Page->clase->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->clase->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
    <div id="r_grupo"<?= $Page->grupo->rowAttributes() ?>>
        <label id="elh_cont_plancta_grupo" for="x_grupo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo->caption() ?><?= $Page->grupo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->grupo->cellAttributes() ?>>
<span id="el_cont_plancta_grupo">
<input type="<?= $Page->grupo->getInputTextType() ?>" name="x_grupo" id="x_grupo" data-table="cont_plancta" data-field="x_grupo" value="<?= $Page->grupo->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->grupo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->grupo->formatPattern()) ?>"<?= $Page->grupo->editAttributes() ?> aria-describedby="x_grupo_help">
<?= $Page->grupo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->grupo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta"<?= $Page->cuenta->rowAttributes() ?>>
        <label id="elh_cont_plancta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_plancta_cuenta">
<input type="<?= $Page->cuenta->getInputTextType() ?>" name="x_cuenta" id="x_cuenta" data-table="cont_plancta" data-field="x_cuenta" value="<?= $Page->cuenta->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->cuenta->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cuenta->formatPattern()) ?>"<?= $Page->cuenta->editAttributes() ?> aria-describedby="x_cuenta_help">
<?= $Page->cuenta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
    <div id="r_subcuenta"<?= $Page->subcuenta->rowAttributes() ?>>
        <label id="elh_cont_plancta_subcuenta" for="x_subcuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->subcuenta->caption() ?><?= $Page->subcuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->subcuenta->cellAttributes() ?>>
<span id="el_cont_plancta_subcuenta">
<input type="<?= $Page->subcuenta->getInputTextType() ?>" name="x_subcuenta" id="x_subcuenta" data-table="cont_plancta" data-field="x_subcuenta" value="<?= $Page->subcuenta->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->subcuenta->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->subcuenta->formatPattern()) ?>"<?= $Page->subcuenta->editAttributes() ?> aria-describedby="x_subcuenta_help">
<?= $Page->subcuenta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->subcuenta->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_cont_plancta_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_plancta_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="cont_plancta" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_cont_plancta_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_plancta_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fcont_planctaadd_x_moneda"
        <?php } ?>
        data-table="cont_plancta"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<?php if (!$Page->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("fcont_planctaadd", function() {
    var options = { name: "x_moneda", selectId: "fcont_planctaadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_planctaadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fcont_planctaadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fcont_planctaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_plancta.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
    <div id="r_activa"<?= $Page->activa->rowAttributes() ?>>
        <label id="elh_cont_plancta_activa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activa->caption() ?><?= $Page->activa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activa->cellAttributes() ?>>
<span id="el_cont_plancta_activa">
<template id="tp_x_activa">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_plancta" data-field="x_activa" name="x_activa" id="x_activa"<?= $Page->activa->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activa" class="ew-item-list"></div>
<selection-list hidden
    id="x_activa"
    name="x_activa"
    value="<?= HtmlEncode($Page->activa->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activa"
    data-target="dsl_x_activa"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activa->isInvalidClass() ?>"
    data-table="cont_plancta"
    data-field="x_activa"
    data-value-separator="<?= $Page->activa->displayValueSeparatorAttribute() ?>"
    <?= $Page->activa->editAttributes() ?>></selection-list>
<?= $Page->activa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_planctaadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_planctaadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_plancta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $("#x_clase").change(function(){
    	if($("#x_clase").val().trim() == '') {
    		return true;
    	}
    	$("#x_grupo").val("");
    	$("#x_cuenta").val("");
    	$("#x_subcuenta").val("");
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"clase": $("#x_clase").val(),
    		"segmento": 1
    	};
    	var url = "include/buscar_cuenta.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = response;
    			$("#cuenta").html(content);
    		}
    	});
    });
    $("#x_grupo").change(function(){
    	if($("#x_clase").val().trim() == '' || $("#x_grupo").val().trim() == '') {
    		$("#x_grupo").val("");
    		return true;
    	}
    	$("#x_cuenta").val("");
    	$("#x_subcuenta").val("");
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"clase": $("#x_clase").val(),
    		"grupo": $("#x_grupo").val(),
    		"segmento": 2
    	};
    	var url = "include/buscar_cuenta.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = response;
    			$("#cuenta").html(content);
    		}
    	});
    });
    $("#x_cuenta").change(function(){
    	if($("#x_clase").val().trim() == '' || $("#x_grupo").val().trim() == '' || $("#x_cuenta").val().trim() == '') {
    		$("#x_cuenta").val("");
    		return true;
    	}
    	$("#x_subcuenta").val("");
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"clase": $("#x_clase").val(),
    		"grupo": $("#x_grupo").val(),
    		"cuenta": $("#x_cuenta").val(),
    		"segmento": 3
    	};
    	var url = "include/buscar_cuenta.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = response;
    			$("#cuenta").html(content);
    		}
    	});
    });
    $("#x_subcuenta").change(function(){
    	if($("#x_clase").val().trim() == '' || $("#x_grupo").val().trim() == '' || $("#x_cuenta").val().trim() == '' || $("#x_subcuenta").val().trim() == '') {
    		$("#x_subcuenta").val("");
    		return true;
    	}
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"clase": $("#x_clase").val(),
    		"grupo": $("#x_grupo").val(),
    		"cuenta": $("#x_cuenta").val(),
    		"subcuenta": $("#x_subcuenta").val(),
    		"segmento": 4
    	};
    	var url = "include/buscar_cuenta.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = response;
    			$("#cuenta").html(content);
    		}
    	});
    });
});
</script>
