<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosComprasDetalleEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fpagos_compras_detalleedit" id="fpagos_compras_detalleedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pagos_compras_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpagos_compras_detalleedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagos_compras_detalleedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["pagos_compras", [fields.pagos_compras.visible && fields.pagos_compras.required ? ew.Validators.required(fields.pagos_compras.caption) : null, ew.Validators.integer], fields.pagos_compras.isInvalid],
            ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["tasa_moneda", [fields.tasa_moneda.visible && fields.tasa_moneda.required ? ew.Validators.required(fields.tasa_moneda.caption) : null, ew.Validators.float], fields.tasa_moneda.isInvalid],
            ["monto_bs", [fields.monto_bs.visible && fields.monto_bs.required ? ew.Validators.required(fields.monto_bs.caption) : null, ew.Validators.float], fields.monto_bs.isInvalid],
            ["tasa_usd", [fields.tasa_usd.visible && fields.tasa_usd.required ? ew.Validators.required(fields.tasa_usd.caption) : null, ew.Validators.float], fields.tasa_usd.isInvalid],
            ["monto_usd", [fields.monto_usd.visible && fields.monto_usd.required ? ew.Validators.required(fields.monto_usd.caption) : null, ew.Validators.float], fields.monto_usd.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null, ew.Validators.integer], fields.banco.isInvalid]
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
<input type="hidden" name="t" value="pagos_compras_detalle">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pagos_compras->Visible) { // pagos_compras ?>
    <div id="r_pagos_compras"<?= $Page->pagos_compras->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_pagos_compras" for="x_pagos_compras" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pagos_compras->caption() ?><?= $Page->pagos_compras->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pagos_compras->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_pagos_compras">
<input type="<?= $Page->pagos_compras->getInputTextType() ?>" name="x_pagos_compras" id="x_pagos_compras" data-table="pagos_compras_detalle" data-field="x_pagos_compras" value="<?= $Page->pagos_compras->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pagos_compras->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pagos_compras->formatPattern()) ?>"<?= $Page->pagos_compras->editAttributes() ?> aria-describedby="x_pagos_compras_help">
<?= $Page->pagos_compras->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pagos_compras->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago"<?= $Page->metodo_pago->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_metodo_pago">
<input type="<?= $Page->metodo_pago->getInputTextType() ?>" name="x_metodo_pago" id="x_metodo_pago" data-table="pagos_compras_detalle" data-field="x_metodo_pago" value="<?= $Page->metodo_pago->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->metodo_pago->formatPattern()) ?>"<?= $Page->metodo_pago->editAttributes() ?> aria-describedby="x_metodo_pago_help">
<?= $Page->metodo_pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="pagos_compras_detalle" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda"<?= $Page->monto_moneda->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_monto_moneda">
<input type="<?= $Page->monto_moneda->getInputTextType() ?>" name="x_monto_moneda" id="x_monto_moneda" data-table="pagos_compras_detalle" data-field="x_monto_moneda" value="<?= $Page->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_moneda->formatPattern()) ?>"<?= $Page->monto_moneda->editAttributes() ?> aria-describedby="x_monto_moneda_help">
<?= $Page->monto_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" name="x_moneda" id="x_moneda" data-table="pagos_compras_detalle" data-field="x_moneda" value="<?= $Page->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->moneda->formatPattern()) ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
    <div id="r_tasa_moneda"<?= $Page->tasa_moneda->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_tasa_moneda" for="x_tasa_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_moneda->caption() ?><?= $Page->tasa_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tasa_moneda->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_tasa_moneda">
<input type="<?= $Page->tasa_moneda->getInputTextType() ?>" name="x_tasa_moneda" id="x_tasa_moneda" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" value="<?= $Page->tasa_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->tasa_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tasa_moneda->formatPattern()) ?>"<?= $Page->tasa_moneda->editAttributes() ?> aria-describedby="x_tasa_moneda_help">
<?= $Page->tasa_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
    <div id="r_monto_bs"<?= $Page->monto_bs->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_monto_bs" for="x_monto_bs" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_bs->caption() ?><?= $Page->monto_bs->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_bs->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_monto_bs">
<input type="<?= $Page->monto_bs->getInputTextType() ?>" name="x_monto_bs" id="x_monto_bs" data-table="pagos_compras_detalle" data-field="x_monto_bs" value="<?= $Page->monto_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_bs->formatPattern()) ?>"<?= $Page->monto_bs->editAttributes() ?> aria-describedby="x_monto_bs_help">
<?= $Page->monto_bs->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_bs->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
    <div id="r_tasa_usd"<?= $Page->tasa_usd->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_tasa_usd" for="x_tasa_usd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_usd->caption() ?><?= $Page->tasa_usd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tasa_usd->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_tasa_usd">
<input type="<?= $Page->tasa_usd->getInputTextType() ?>" name="x_tasa_usd" id="x_tasa_usd" data-table="pagos_compras_detalle" data-field="x_tasa_usd" value="<?= $Page->tasa_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->tasa_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tasa_usd->formatPattern()) ?>"<?= $Page->tasa_usd->editAttributes() ?> aria-describedby="x_tasa_usd_help">
<?= $Page->tasa_usd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_usd->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <div id="r_monto_usd"<?= $Page->monto_usd->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_monto_usd" for="x_monto_usd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_usd->caption() ?><?= $Page->monto_usd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_monto_usd">
<input type="<?= $Page->monto_usd->getInputTextType() ?>" name="x_monto_usd" id="x_monto_usd" data-table="pagos_compras_detalle" data-field="x_monto_usd" value="<?= $Page->monto_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_usd->formatPattern()) ?>"<?= $Page->monto_usd->editAttributes() ?> aria-describedby="x_monto_usd_help">
<?= $Page->monto_usd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_usd->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_pagos_compras_detalle_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_pagos_compras_detalle_banco">
<input type="<?= $Page->banco->getInputTextType() ?>" name="x_banco" id="x_banco" data-table="pagos_compras_detalle" data-field="x_banco" value="<?= $Page->banco->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco->formatPattern()) ?>"<?= $Page->banco->editAttributes() ?> aria-describedby="x_banco_help">
<?= $Page->banco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpagos_compras_detalleedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpagos_compras_detalleedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("pagos_compras_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
