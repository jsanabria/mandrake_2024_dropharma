<?php

namespace PHPMaker2024\mandrake;

// Page object
$PurgaDetalleEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fpurga_detalleedit" id="fpurga_detalleedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { purga_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpurga_detalleedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpurga_detalleedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid]
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
            "almacen": <?= $Page->almacen->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="purga_detalle">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "purga") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="purga">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->purga->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_purga_detalle_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_purga_detalle_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->fabricante->getDisplayValue($Page->fabricante->EditValue) ?></span></span>
<input type="hidden" data-table="purga_detalle" data-field="x_fabricante" data-hidden="1" name="x_fabricante" id="x_fabricante" value="<?= HtmlEncode($Page->fabricante->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_purga_detalle_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_purga_detalle_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->articulo->getDisplayValue($Page->articulo->EditValue) ?></span></span>
<input type="hidden" data-table="purga_detalle" data-field="x_articulo" data-hidden="1" name="x_articulo" id="x_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
    <div id="r_almacen"<?= $Page->almacen->rowAttributes() ?>>
        <label id="elh_purga_detalle_almacen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->almacen->caption() ?><?= $Page->almacen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->almacen->cellAttributes() ?>>
<span id="el_purga_detalle_almacen">
    <select
        id="x_almacen"
        name="x_almacen"
        class="form-control ew-select<?= $Page->almacen->isInvalidClass() ?>"
        data-select2-id="fpurga_detalleedit_x_almacen"
        data-table="purga_detalle"
        data-field="x_almacen"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->almacen->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->almacen->getPlaceHolder()) ?>"
        <?= $Page->almacen->editAttributes() ?>>
        <?= $Page->almacen->selectOptionListHtml("x_almacen") ?>
    </select>
    <?= $Page->almacen->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->almacen->getErrorMessage() ?></div>
<?= $Page->almacen->Lookup->getParamTag($Page, "p_x_almacen") ?>
<script>
loadjs.ready("fpurga_detalleedit", function() {
    var options = { name: "x_almacen", selectId: "fpurga_detalleedit_x_almacen" };
    if (fpurga_detalleedit.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x_almacen", form: "fpurga_detalleedit" };
    } else {
        options.ajax = { id: "x_almacen", form: "fpurga_detalleedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.almacen.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <div id="r_lote"<?= $Page->lote->rowAttributes() ?>>
        <label id="elh_purga_detalle_lote" for="x_lote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lote->caption() ?><?= $Page->lote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lote->cellAttributes() ?>>
<span id="el_purga_detalle_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" name="x_lote" id="x_lote" data-table="purga_detalle" data-field="x_lote" value="<?= $Page->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->lote->formatPattern()) ?>"<?= $Page->lote->editAttributes() ?> aria-describedby="x_lote_help">
<?= $Page->lote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_purga_detalle_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_purga_detalle_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="purga_detalle" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" size="10" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpurga_detalleedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fpurga_detalleedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <div id="r_cantidad_articulo"<?= $Page->cantidad_articulo->rowAttributes() ?>>
        <label id="elh_purga_detalle_cantidad_articulo" for="x_cantidad_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_articulo->caption() ?><?= $Page->cantidad_articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el_purga_detalle_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" name="x_cantidad_articulo" id="x_cantidad_articulo" data-table="purga_detalle" data-field="x_cantidad_articulo" value="<?= $Page->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_articulo->formatPattern()) ?>"<?= $Page->cantidad_articulo->editAttributes() ?> aria-describedby="x_cantidad_articulo_help">
<?= $Page->cantidad_articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="purga_detalle" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpurga_detalleedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpurga_detalleedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("purga_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
