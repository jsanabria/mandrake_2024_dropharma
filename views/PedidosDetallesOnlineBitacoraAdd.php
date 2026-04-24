<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidosDetallesOnlineBitacoraAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pedidos_detalles_online_bitacora: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpedidos_detalles_online_bitacoraadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpedidos_detalles_online_bitacoraadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["cantidad_solicitada", [fields.cantidad_solicitada.visible && fields.cantidad_solicitada.required ? ew.Validators.required(fields.cantidad_solicitada.caption) : null, ew.Validators.float], fields.cantidad_solicitada.isInvalid],
            ["cantidadasignada", [fields.cantidadasignada.visible && fields.cantidadasignada.required ? ew.Validators.required(fields.cantidadasignada.caption) : null, ew.Validators.float], fields.cantidadasignada.isInvalid]
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpedidos_detalles_online_bitacoraadd" id="fpedidos_detalles_online_bitacoraadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pedidos_detalles_online_bitacora">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <label id="elh_pedidos_detalles_online_bitacora_id_documento" for="x_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_id_documento">
<input type="<?= $Page->id_documento->getInputTextType() ?>" name="x_id_documento" id="x_id_documento" data-table="pedidos_detalles_online_bitacora" data-field="x_id_documento" value="<?= $Page->id_documento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_documento->formatPattern()) ?>"<?= $Page->id_documento->editAttributes() ?> aria-describedby="x_id_documento_help">
<?= $Page->id_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_pedidos_detalles_online_bitacora_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_fabricante">
<input type="<?= $Page->fabricante->getInputTextType() ?>" name="x_fabricante" id="x_fabricante" data-table="pedidos_detalles_online_bitacora" data-field="x_fabricante" value="<?= $Page->fabricante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fabricante->formatPattern()) ?>"<?= $Page->fabricante->editAttributes() ?> aria-describedby="x_fabricante_help">
<?= $Page->fabricante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_pedidos_detalles_online_bitacora_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x_articulo" id="x_articulo" data-table="pedidos_detalles_online_bitacora" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_solicitada->Visible) { // cantidad_solicitada ?>
    <div id="r_cantidad_solicitada"<?= $Page->cantidad_solicitada->rowAttributes() ?>>
        <label id="elh_pedidos_detalles_online_bitacora_cantidad_solicitada" for="x_cantidad_solicitada" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_solicitada->caption() ?><?= $Page->cantidad_solicitada->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_solicitada->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_cantidad_solicitada">
<input type="<?= $Page->cantidad_solicitada->getInputTextType() ?>" name="x_cantidad_solicitada" id="x_cantidad_solicitada" data-table="pedidos_detalles_online_bitacora" data-field="x_cantidad_solicitada" value="<?= $Page->cantidad_solicitada->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_solicitada->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_solicitada->formatPattern()) ?>"<?= $Page->cantidad_solicitada->editAttributes() ?> aria-describedby="x_cantidad_solicitada_help">
<?= $Page->cantidad_solicitada->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_solicitada->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidadasignada->Visible) { // cantidadasignada ?>
    <div id="r_cantidadasignada"<?= $Page->cantidadasignada->rowAttributes() ?>>
        <label id="elh_pedidos_detalles_online_bitacora_cantidadasignada" for="x_cantidadasignada" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidadasignada->caption() ?><?= $Page->cantidadasignada->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidadasignada->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_cantidadasignada">
<input type="<?= $Page->cantidadasignada->getInputTextType() ?>" name="x_cantidadasignada" id="x_cantidadasignada" data-table="pedidos_detalles_online_bitacora" data-field="x_cantidadasignada" value="<?= $Page->cantidadasignada->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidadasignada->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidadasignada->formatPattern()) ?>"<?= $Page->cantidadasignada->editAttributes() ?> aria-describedby="x_cantidadasignada_help">
<?= $Page->cantidadasignada->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidadasignada->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpedidos_detalles_online_bitacoraadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpedidos_detalles_online_bitacoraadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("pedidos_detalles_online_bitacora");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
