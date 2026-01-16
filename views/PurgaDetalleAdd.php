<?php

namespace PHPMaker2024\mandrake;

// Page object
$PurgaDetalleAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { purga_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpurga_detalleadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpurga_detalleadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
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
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
            "articulo": <?= $Page->articulo->toClientList($Page) ?>,
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpurga_detalleadd" id="fpurga_detalleadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="purga_detalle">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "purga") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="purga">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->purga->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_purga_detalle_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_purga_detalle_fabricante">
    <select
        id="x_fabricante"
        name="x_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="fpurga_detalleadd_x_fabricante"
        data-table="purga_detalle"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Page->fabricante->editAttributes() ?>>
        <?= $Page->fabricante->selectOptionListHtml("x_fabricante") ?>
    </select>
    <?= $Page->fabricante->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<script>
loadjs.ready("fpurga_detalleadd", function() {
    var options = { name: "x_fabricante", selectId: "fpurga_detalleadd_x_fabricante" };
    if (fpurga_detalleadd.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x_fabricante", form: "fpurga_detalleadd" };
    } else {
        options.ajax = { id: "x_fabricante", form: "fpurga_detalleadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_purga_detalle_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_purga_detalle_articulo">
    <select
        id="x_articulo"
        name="x_articulo"
        class="form-control ew-select<?= $Page->articulo->isInvalidClass() ?>"
        data-select2-id="fpurga_detalleadd_x_articulo"
        data-table="purga_detalle"
        data-field="x_articulo"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->articulo->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"
        <?= $Page->articulo->editAttributes() ?>>
        <?= $Page->articulo->selectOptionListHtml("x_articulo") ?>
    </select>
    <?= $Page->articulo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
<script>
loadjs.ready("fpurga_detalleadd", function() {
    var options = { name: "x_articulo", selectId: "fpurga_detalleadd_x_articulo" };
    if (fpurga_detalleadd.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x_articulo", form: "fpurga_detalleadd" };
    } else {
        options.ajax = { id: "x_articulo", form: "fpurga_detalleadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
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
        data-select2-id="fpurga_detalleadd_x_almacen"
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
loadjs.ready("fpurga_detalleadd", function() {
    var options = { name: "x_almacen", selectId: "fpurga_detalleadd_x_almacen" };
    if (fpurga_detalleadd.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x_almacen", form: "fpurga_detalleadd" };
    } else {
        options.ajax = { id: "x_almacen", form: "fpurga_detalleadd", limit: ew.LOOKUP_PAGE_SIZE };
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
loadjs.ready(["fpurga_detalleadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpurga_detalleadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
    <?php if (strval($Page->purga->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_purga" id="x_purga" value="<?= HtmlEncode(strval($Page->purga->getSessionValue())) ?>">
    <?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpurga_detalleadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpurga_detalleadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("purga_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
