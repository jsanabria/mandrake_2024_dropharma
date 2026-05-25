<?php

namespace PHPMaker2024\mandrake;

// Page object
$ProveedorAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { proveedor: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fproveedoradd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedoradd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["ciudad", [fields.ciudad.visible && fields.ciudad.required ? ew.Validators.required(fields.ciudad.caption) : null], fields.ciudad.isInvalid],
            ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
            ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
            ["telefono2", [fields.telefono2.visible && fields.telefono2.required ? ew.Validators.required(fields.telefono2.caption) : null], fields.telefono2.isInvalid],
            ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
            ["email2", [fields.email2.visible && fields.email2.required ? ew.Validators.required(fields.email2.caption) : null, ew.Validators.email], fields.email2.isInvalid],
            ["cta_bco", [fields.cta_bco.visible && fields.cta_bco.required ? ew.Validators.required(fields.cta_bco.caption) : null], fields.cta_bco.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
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
            "ciudad": <?= $Page->ciudad->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
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
<form name="fproveedoradd" id="fproveedoradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <label id="elh_proveedor_ci_rif" for="x_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" name="x_ci_rif" id="x_ci_rif" data-table="proveedor" data-field="x_ci_rif" value="<?= $Page->ci_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ci_rif->formatPattern()) ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_proveedor_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="proveedor" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <div id="r_ciudad"<?= $Page->ciudad->rowAttributes() ?>>
        <label id="elh_proveedor_ciudad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ciudad->caption() ?><?= $Page->ciudad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ciudad->cellAttributes() ?>>
<span id="el_proveedor_ciudad">
    <select
        id="x_ciudad"
        name="x_ciudad"
        class="form-control ew-select<?= $Page->ciudad->isInvalidClass() ?>"
        data-select2-id="fproveedoradd_x_ciudad"
        data-table="proveedor"
        data-field="x_ciudad"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ciudad->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ciudad->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ciudad->getPlaceHolder()) ?>"
        <?= $Page->ciudad->editAttributes() ?>>
        <?= $Page->ciudad->selectOptionListHtml("x_ciudad") ?>
    </select>
    <?= $Page->ciudad->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ciudad->getErrorMessage() ?></div>
<?= $Page->ciudad->Lookup->getParamTag($Page, "p_x_ciudad") ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_ciudad", selectId: "fproveedoradd_x_ciudad" };
    if (fproveedoradd.lists.ciudad?.lookupOptions.length) {
        options.data = { id: "x_ciudad", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_ciudad", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor.fields.ciudad.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <label id="elh_proveedor_direccion" for="x_direccion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->direccion->cellAttributes() ?>>
<span id="el_proveedor_direccion">
<textarea data-table="proveedor" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1"<?= $Page->telefono1->rowAttributes() ?>>
        <label id="elh_proveedor_telefono1" for="x_telefono1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono1->cellAttributes() ?>>
<span id="el_proveedor_telefono1">
<input type="<?= $Page->telefono1->getInputTextType() ?>" name="x_telefono1" id="x_telefono1" data-table="proveedor" data-field="x_telefono1" value="<?= $Page->telefono1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono1->formatPattern()) ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <div id="r_telefono2"<?= $Page->telefono2->rowAttributes() ?>>
        <label id="elh_proveedor_telefono2" for="x_telefono2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono2->caption() ?><?= $Page->telefono2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono2->cellAttributes() ?>>
<span id="el_proveedor_telefono2">
<input type="<?= $Page->telefono2->getInputTextType() ?>" name="x_telefono2" id="x_telefono2" data-table="proveedor" data-field="x_telefono2" value="<?= $Page->telefono2->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono2->formatPattern()) ?>"<?= $Page->telefono2->editAttributes() ?> aria-describedby="x_telefono2_help">
<?= $Page->telefono2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div id="r_email1"<?= $Page->email1->rowAttributes() ?>>
        <label id="elh_proveedor_email1" for="x_email1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->email1->cellAttributes() ?>>
<span id="el_proveedor_email1">
<input type="<?= $Page->email1->getInputTextType() ?>" name="x_email1" id="x_email1" data-table="proveedor" data-field="x_email1" value="<?= $Page->email1->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email1->formatPattern()) ?>"<?= $Page->email1->editAttributes() ?> aria-describedby="x_email1_help">
<?= $Page->email1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <div id="r_email2"<?= $Page->email2->rowAttributes() ?>>
        <label id="elh_proveedor_email2" for="x_email2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email2->caption() ?><?= $Page->email2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->email2->cellAttributes() ?>>
<span id="el_proveedor_email2">
<input type="<?= $Page->email2->getInputTextType() ?>" name="x_email2" id="x_email2" data-table="proveedor" data-field="x_email2" value="<?= $Page->email2->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email2->formatPattern()) ?>"<?= $Page->email2->editAttributes() ?> aria-describedby="x_email2_help">
<?= $Page->email2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <div id="r_cta_bco"<?= $Page->cta_bco->rowAttributes() ?>>
        <label id="elh_proveedor_cta_bco" for="x_cta_bco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cta_bco->caption() ?><?= $Page->cta_bco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cta_bco->cellAttributes() ?>>
<span id="el_proveedor_cta_bco">
<input type="<?= $Page->cta_bco->getInputTextType() ?>" name="x_cta_bco" id="x_cta_bco" data-table="proveedor" data-field="x_cta_bco" value="<?= $Page->cta_bco->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->cta_bco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cta_bco->formatPattern()) ?>"<?= $Page->cta_bco->editAttributes() ?> aria-describedby="x_cta_bco_help">
<?= $Page->cta_bco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cta_bco->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_proveedor_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_activo"
        <?php } ?>
        data-table="proveedor"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<?php if (!$Page->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_activo", selectId: "fproveedoradd_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_activo", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("proveedor_articulo", explode(",", $Page->getCurrentDetailTable())) && $proveedor_articulo->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("proveedor_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProveedorArticuloGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fproveedoradd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fproveedoradd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Funcionalidad de formateo estandarizado (Ej: V-12345678)
    function formatearRIF(valor) {
        var limpio = valor.replace(/[.,-\s]/g, "").trim(); // Limpia puntos, comas, guiones y espacios
        if (limpio.length > 1 && limpio.charAt(1) !== '-') {
            return limpio.charAt(0).toUpperCase() + "-" + limpio.substring(1);
        }
        return limpio.toUpperCase();
    }
    $("#x_ci_rif").change(function() {
        var $input = $(this);
        var rifOriginal = $input.val();
        if (rifOriginal.trim() == '') return;

        // Detectar automáticamente el entorno mediante la URL para que sirva en ambos formularios
        var tipoEntidad = (window.location.href.toLowerCase().indexOf("proveedor") > -1) ? 'PROVEEDOR' : 'CLIENTE';

        // Realizar la petición esperando el JSON estructurado
        $.getJSON("RifBuscar", { "ci_rif": rifOriginal, "tipo": tipoEntidad, "accion": 'I' })
        .done(function(data) {
            // Validación basada en la respuesta del nuevo backend JSON
            if (data && data.existe) {
                $("#msgRifDuplicado").html("El RIF/CI <b>" + rifOriginal + "</b> ya se encuentra registrado en " + tipoEntidad.toLowerCase() + "s.");

                // Instanciar y levantar la modal de Bootstrap
                var modalElement = document.getElementById('modalRifDuplicado');
                if (modalElement) {
                    var modalRif = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modalRif.show();

                    // Manejo de acciones en los botones
                    $("#btnCancelarRif").off("click").on("click", function() {
                        $input.val("").focus();
                        modalRif.hide();
                    });
                    $("#btnContinuarRif").off("click").on("click", function() {
                        $input.val(formatearRIF(rifOriginal));
                        modalRif.hide();
                    });
                }
            } else {
                // Si no existe, aplicar formato visual limpio de forma estándar
                $input.val(formatearRIF(rifOriginal));
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la validación de RIF: ", textStatus, errorThrown);
            // Resguardo: si falla la red o el script, al menos se le da formato al campo
            $input.val(formatearRIF(rifOriginal));
        });
    });
});
</script>
