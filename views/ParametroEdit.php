<?php

namespace PHPMaker2024\mandrake;

// Page object
$ParametroEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fparametroedit" id="fparametroedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { parametro: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fparametroedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fparametroedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["valor1", [fields.valor1.visible && fields.valor1.required ? ew.Validators.required(fields.valor1.caption) : null], fields.valor1.isInvalid],
            ["valor2", [fields.valor2.visible && fields.valor2.required ? ew.Validators.required(fields.valor2.caption) : null], fields.valor2.isInvalid],
            ["valor3", [fields.valor3.visible && fields.valor3.required ? ew.Validators.required(fields.valor3.caption) : null], fields.valor3.isInvalid],
            ["valor4", [fields.valor4.visible && fields.valor4.required ? ew.Validators.required(fields.valor4.caption) : null], fields.valor4.isInvalid]
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
<input type="hidden" name="t" value="parametro">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_parametro_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_parametro_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x_codigo" id="x_codigo" data-table="parametro" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" size="30" maxlength="3" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_parametro_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_parametro_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->descripcion->getDisplayValue($Page->descripcion->EditValue))) ?>"></span>
<input type="hidden" data-table="parametro" data-field="x_descripcion" data-hidden="1" name="x_descripcion" id="x_descripcion" value="<?= HtmlEncode($Page->descripcion->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor1->Visible) { // valor1 ?>
    <div id="r_valor1"<?= $Page->valor1->rowAttributes() ?>>
        <label id="elh_parametro_valor1" for="x_valor1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor1->caption() ?><?= $Page->valor1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor1->cellAttributes() ?>>
<span id="el_parametro_valor1">
<input type="<?= $Page->valor1->getInputTextType() ?>" name="x_valor1" id="x_valor1" data-table="parametro" data-field="x_valor1" value="<?= $Page->valor1->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor1->formatPattern()) ?>"<?= $Page->valor1->editAttributes() ?> aria-describedby="x_valor1_help">
<?= $Page->valor1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor2->Visible) { // valor2 ?>
    <div id="r_valor2"<?= $Page->valor2->rowAttributes() ?>>
        <label id="elh_parametro_valor2" for="x_valor2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor2->caption() ?><?= $Page->valor2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor2->cellAttributes() ?>>
<span id="el_parametro_valor2">
<input type="<?= $Page->valor2->getInputTextType() ?>" name="x_valor2" id="x_valor2" data-table="parametro" data-field="x_valor2" value="<?= $Page->valor2->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor2->formatPattern()) ?>"<?= $Page->valor2->editAttributes() ?> aria-describedby="x_valor2_help">
<?= $Page->valor2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor3->Visible) { // valor3 ?>
    <div id="r_valor3"<?= $Page->valor3->rowAttributes() ?>>
        <label id="elh_parametro_valor3" for="x_valor3" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor3->caption() ?><?= $Page->valor3->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor3->cellAttributes() ?>>
<span id="el_parametro_valor3">
<input type="<?= $Page->valor3->getInputTextType() ?>" name="x_valor3" id="x_valor3" data-table="parametro" data-field="x_valor3" value="<?= $Page->valor3->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor3->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor3->formatPattern()) ?>"<?= $Page->valor3->editAttributes() ?> aria-describedby="x_valor3_help">
<?= $Page->valor3->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor3->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor4->Visible) { // valor4 ?>
    <div id="r_valor4"<?= $Page->valor4->rowAttributes() ?>>
        <label id="elh_parametro_valor4" for="x_valor4" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor4->caption() ?><?= $Page->valor4->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor4->cellAttributes() ?>>
<span id="el_parametro_valor4">
<input type="<?= $Page->valor4->getInputTextType() ?>" name="x_valor4" id="x_valor4" data-table="parametro" data-field="x_valor4" value="<?= $Page->valor4->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor4->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor4->formatPattern()) ?>"<?= $Page->valor4->editAttributes() ?> aria-describedby="x_valor4_help">
<?= $Page->valor4->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor4->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="parametro" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fparametroedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fparametroedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("parametro");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
