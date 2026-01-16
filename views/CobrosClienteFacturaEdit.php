<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteFacturaEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcobros_cliente_facturaedit" id="fcobros_cliente_facturaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente_factura: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcobros_cliente_facturaedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_cliente_facturaedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["abono", [fields.abono.visible && fields.abono.required ? ew.Validators.required(fields.abono.caption) : null], fields.abono.isInvalid],
            ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null], fields.monto.isInvalid],
            ["retivamonto", [fields.retivamonto.visible && fields.retivamonto.required ? ew.Validators.required(fields.retivamonto.caption) : null], fields.retivamonto.isInvalid],
            ["retiva", [fields.retiva.visible && fields.retiva.required ? ew.Validators.required(fields.retiva.caption) : null], fields.retiva.isInvalid],
            ["retislrmonto", [fields.retislrmonto.visible && fields.retislrmonto.required ? ew.Validators.required(fields.retislrmonto.caption) : null], fields.retislrmonto.isInvalid],
            ["retislr", [fields.retislr.visible && fields.retislr.required ? ew.Validators.required(fields.retislr.caption) : null], fields.retislr.isInvalid],
            ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null], fields.comprobante.isInvalid]
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
<input type="hidden" name="t" value="cobros_cliente_factura">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cobros_cliente") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cobros_cliente">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cobros_cliente->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue) ?></span></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <div id="r_abono"<?= $Page->abono->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_abono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->abono->caption() ?><?= $Page->abono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->abono->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->abono->getDisplayValue($Page->abono->EditValue) ?></span></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="x_abono" id="x_abono" value="<?= HtmlEncode($Page->abono->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto"<?= $Page->monto->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto->getDisplayValue($Page->monto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="x_monto" id="x_monto" value="<?= HtmlEncode($Page->monto->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
    <div id="r_retivamonto"<?= $Page->retivamonto->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_retivamonto" for="x_retivamonto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retivamonto->caption() ?><?= $Page->retivamonto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->retivamonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retivamonto">
<span<?= $Page->retivamonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->retivamonto->getDisplayValue($Page->retivamonto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="x_retivamonto" id="x_retivamonto" value="<?= HtmlEncode($Page->retivamonto->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
    <div id="r_retiva"<?= $Page->retiva->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_retiva" for="x_retiva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retiva->caption() ?><?= $Page->retiva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->retiva->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retiva">
<input type="<?= $Page->retiva->getInputTextType() ?>" name="x_retiva" id="x_retiva" data-table="cobros_cliente_factura" data-field="x_retiva" value="<?= $Page->retiva->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->retiva->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->retiva->formatPattern()) ?>"<?= $Page->retiva->editAttributes() ?> aria-describedby="x_retiva_help">
<?= $Page->retiva->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->retiva->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
    <div id="r_retislrmonto"<?= $Page->retislrmonto->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_retislrmonto" for="x_retislrmonto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retislrmonto->caption() ?><?= $Page->retislrmonto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->retislrmonto->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislrmonto">
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->retislrmonto->getDisplayValue($Page->retislrmonto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="x_retislrmonto" id="x_retislrmonto" value="<?= HtmlEncode($Page->retislrmonto->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
    <div id="r_retislr"<?= $Page->retislr->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_retislr" for="x_retislr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->retislr->caption() ?><?= $Page->retislr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->retislr->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_retislr">
<input type="<?= $Page->retislr->getInputTextType() ?>" name="x_retislr" id="x_retislr" data-table="cobros_cliente_factura" data-field="x_retislr" value="<?= $Page->retislr->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->retislr->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->retislr->formatPattern()) ?>"<?= $Page->retislr->editAttributes() ?> aria-describedby="x_retislr_help">
<?= $Page->retislr->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->retislr->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <div id="r_comprobante"<?= $Page->comprobante->rowAttributes() ?>>
        <label id="elh_cobros_cliente_factura_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante->caption() ?><?= $Page->comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->comprobante->cellAttributes() ?>>
<span id="el_cobros_cliente_factura_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->EditValue) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><span class="form-control-plaintext"><?= $Page->comprobante->getDisplayValue($Page->comprobante->EditValue) ?></span></a>
<?php } else { ?>
<span class="form-control-plaintext"><?= $Page->comprobante->getDisplayValue($Page->comprobante->EditValue) ?></span>
<?php } ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="x_comprobante" id="x_comprobante" value="<?= HtmlEncode($Page->comprobante->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cobros_cliente_factura" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcobros_cliente_facturaedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcobros_cliente_facturaedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cobros_cliente_factura");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
