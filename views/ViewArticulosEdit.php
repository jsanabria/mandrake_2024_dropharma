<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewArticulosEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_articulosedit" id="fview_articulosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_articulos: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_articulosedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_articulosedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["nombre_comercial", [fields.nombre_comercial.visible && fields.nombre_comercial.required ? ew.Validators.required(fields.nombre_comercial.caption) : null], fields.nombre_comercial.isInvalid],
            ["principio_activo", [fields.principio_activo.visible && fields.principio_activo.required ? ew.Validators.required(fields.principio_activo.caption) : null], fields.principio_activo.isInvalid],
            ["presentacion", [fields.presentacion.visible && fields.presentacion.required ? ew.Validators.required(fields.presentacion.caption) : null], fields.presentacion.isInvalid],
            ["cantidad_en_mano", [fields.cantidad_en_mano.visible && fields.cantidad_en_mano.required ? ew.Validators.required(fields.cantidad_en_mano.caption) : null], fields.cantidad_en_mano.isInvalid],
            ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid]
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_articulos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_view_articulos_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_view_articulos_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="x_codigo" id="x_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_view_articulos_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_view_articulos_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->fabricante->getDisplayValue($Page->fabricante->EditValue) ?></span></span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="x_fabricante" id="x_fabricante" value="<?= HtmlEncode($Page->fabricante->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
    <div id="r_nombre_comercial"<?= $Page->nombre_comercial->rowAttributes() ?>>
        <label id="elh_view_articulos_nombre_comercial" for="x_nombre_comercial" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre_comercial->caption() ?><?= $Page->nombre_comercial->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el_view_articulos_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nombre_comercial->getDisplayValue($Page->nombre_comercial->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre_comercial" data-hidden="1" name="x_nombre_comercial" id="x_nombre_comercial" value="<?= HtmlEncode($Page->nombre_comercial->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
    <div id="r_principio_activo"<?= $Page->principio_activo->rowAttributes() ?>>
        <label id="elh_view_articulos_principio_activo" for="x_principio_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->principio_activo->caption() ?><?= $Page->principio_activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->principio_activo->cellAttributes() ?>>
<span id="el_view_articulos_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->principio_activo->getDisplayValue($Page->principio_activo->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_principio_activo" data-hidden="1" name="x_principio_activo" id="x_principio_activo" value="<?= HtmlEncode($Page->principio_activo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
    <div id="r_presentacion"<?= $Page->presentacion->rowAttributes() ?>>
        <label id="elh_view_articulos_presentacion" for="x_presentacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->presentacion->caption() ?><?= $Page->presentacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->presentacion->cellAttributes() ?>>
<span id="el_view_articulos_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->presentacion->getDisplayValue($Page->presentacion->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_presentacion" data-hidden="1" name="x_presentacion" id="x_presentacion" value="<?= HtmlEncode($Page->presentacion->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
    <div id="r_cantidad_en_mano"<?= $Page->cantidad_en_mano->rowAttributes() ?>>
        <label id="elh_view_articulos_cantidad_en_mano" for="x_cantidad_en_mano" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_en_mano->caption() ?><?= $Page->cantidad_en_mano->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el_view_articulos_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cantidad_en_mano->getDisplayValue($Page->cantidad_en_mano->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="x_cantidad_en_mano" id="x_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <div id="r_ultimo_costo"<?= $Page->ultimo_costo->rowAttributes() ?>>
        <label id="elh_view_articulos_ultimo_costo" for="x_ultimo_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ultimo_costo->caption() ?><?= $Page->ultimo_costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_view_articulos_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" name="x_ultimo_costo" id="x_ultimo_costo" data-table="view_articulos" data-field="x_ultimo_costo" value="<?= $Page->ultimo_costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ultimo_costo->formatPattern()) ?>"<?= $Page->ultimo_costo->editAttributes() ?> aria-describedby="x_ultimo_costo_help">
<?= $Page->ultimo_costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="view_articulos" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_articulosedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_articulosedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("view_articulos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
