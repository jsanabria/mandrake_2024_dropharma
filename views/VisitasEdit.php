<?php

namespace PHPMaker2024\mandrake;

// Page object
$VisitasEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fvisitasedit" id="fvisitasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { visitas: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fvisitasedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fvisitasedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["apellido", [fields.apellido.visible && fields.apellido.required ? ew.Validators.required(fields.apellido.caption) : null], fields.apellido.isInvalid],
            ["correo", [fields.correo.visible && fields.correo.required ? ew.Validators.required(fields.correo.caption) : null], fields.correo.isInvalid],
            ["telefono", [fields.telefono.visible && fields.telefono.required ? ew.Validators.required(fields.telefono.caption) : null], fields.telefono.isInvalid],
            ["producto", [fields.producto.visible && fields.producto.required ? ew.Validators.required(fields.producto.caption) : null], fields.producto.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["comentario", [fields.comentario.visible && fields.comentario.required ? ew.Validators.required(fields.comentario.caption) : null], fields.comentario.isInvalid],
            ["seguimiento", [fields.seguimiento.visible && fields.seguimiento.required ? ew.Validators.required(fields.seguimiento.caption) : null], fields.seguimiento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["fecha_registro", [fields.fecha_registro.visible && fields.fecha_registro.required ? ew.Validators.required(fields.fecha_registro.caption) : null, ew.Validators.datetime(fields.fecha_registro.clientFormatPattern)], fields.fecha_registro.isInvalid],
            ["usuario", [fields.usuario.visible && fields.usuario.required ? ew.Validators.required(fields.usuario.caption) : null], fields.usuario.isInvalid]
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
<input type="hidden" name="t" value="visitas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_visitas_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_visitas_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="visitas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_visitas_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_visitas_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="visitas" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->apellido->Visible) { // apellido ?>
    <div id="r_apellido"<?= $Page->apellido->rowAttributes() ?>>
        <label id="elh_visitas_apellido" for="x_apellido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->apellido->caption() ?><?= $Page->apellido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->apellido->cellAttributes() ?>>
<span id="el_visitas_apellido">
<input type="<?= $Page->apellido->getInputTextType() ?>" name="x_apellido" id="x_apellido" data-table="visitas" data-field="x_apellido" value="<?= $Page->apellido->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->apellido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->apellido->formatPattern()) ?>"<?= $Page->apellido->editAttributes() ?> aria-describedby="x_apellido_help">
<?= $Page->apellido->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->apellido->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->correo->Visible) { // correo ?>
    <div id="r_correo"<?= $Page->correo->rowAttributes() ?>>
        <label id="elh_visitas_correo" for="x_correo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->correo->caption() ?><?= $Page->correo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->correo->cellAttributes() ?>>
<span id="el_visitas_correo">
<input type="<?= $Page->correo->getInputTextType() ?>" name="x_correo" id="x_correo" data-table="visitas" data-field="x_correo" value="<?= $Page->correo->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->correo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->correo->formatPattern()) ?>"<?= $Page->correo->editAttributes() ?> aria-describedby="x_correo_help">
<?= $Page->correo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->correo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono->Visible) { // telefono ?>
    <div id="r_telefono"<?= $Page->telefono->rowAttributes() ?>>
        <label id="elh_visitas_telefono" for="x_telefono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono->caption() ?><?= $Page->telefono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono->cellAttributes() ?>>
<span id="el_visitas_telefono">
<input type="<?= $Page->telefono->getInputTextType() ?>" name="x_telefono" id="x_telefono" data-table="visitas" data-field="x_telefono" value="<?= $Page->telefono->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->telefono->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono->formatPattern()) ?>"<?= $Page->telefono->editAttributes() ?> aria-describedby="x_telefono_help">
<?= $Page->telefono->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->producto->Visible) { // producto ?>
    <div id="r_producto"<?= $Page->producto->rowAttributes() ?>>
        <label id="elh_visitas_producto" for="x_producto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->producto->caption() ?><?= $Page->producto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->producto->cellAttributes() ?>>
<span id="el_visitas_producto">
<input type="<?= $Page->producto->getInputTextType() ?>" name="x_producto" id="x_producto" data-table="visitas" data-field="x_producto" value="<?= $Page->producto->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->producto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->producto->formatPattern()) ?>"<?= $Page->producto->editAttributes() ?> aria-describedby="x_producto_help">
<?= $Page->producto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->producto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_visitas_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_visitas_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="visitas" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comentario->Visible) { // comentario ?>
    <div id="r_comentario"<?= $Page->comentario->rowAttributes() ?>>
        <label id="elh_visitas_comentario" for="x_comentario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comentario->caption() ?><?= $Page->comentario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->comentario->cellAttributes() ?>>
<span id="el_visitas_comentario">
<input type="<?= $Page->comentario->getInputTextType() ?>" name="x_comentario" id="x_comentario" data-table="visitas" data-field="x_comentario" value="<?= $Page->comentario->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->comentario->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->comentario->formatPattern()) ?>"<?= $Page->comentario->editAttributes() ?> aria-describedby="x_comentario_help">
<?= $Page->comentario->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comentario->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->seguimiento->Visible) { // seguimiento ?>
    <div id="r_seguimiento"<?= $Page->seguimiento->rowAttributes() ?>>
        <label id="elh_visitas_seguimiento" for="x_seguimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->seguimiento->caption() ?><?= $Page->seguimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->seguimiento->cellAttributes() ?>>
<span id="el_visitas_seguimiento">
<input type="<?= $Page->seguimiento->getInputTextType() ?>" name="x_seguimiento" id="x_seguimiento" data-table="visitas" data-field="x_seguimiento" value="<?= $Page->seguimiento->EditValue ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->seguimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->seguimiento->formatPattern()) ?>"<?= $Page->seguimiento->editAttributes() ?> aria-describedby="x_seguimiento_help">
<?= $Page->seguimiento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->seguimiento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_visitas_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_visitas_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="visitas" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <div id="r_fecha_registro"<?= $Page->fecha_registro->rowAttributes() ?>>
        <label id="elh_visitas_fecha_registro" for="x_fecha_registro" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_registro->caption() ?><?= $Page->fecha_registro->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_visitas_fecha_registro">
<input type="<?= $Page->fecha_registro->getInputTextType() ?>" name="x_fecha_registro" id="x_fecha_registro" data-table="visitas" data-field="x_fecha_registro" value="<?= $Page->fecha_registro->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha_registro->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_registro->formatPattern()) ?>"<?= $Page->fecha_registro->editAttributes() ?> aria-describedby="x_fecha_registro_help">
<?= $Page->fecha_registro->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_registro->getErrorMessage() ?></div>
<?php if (!$Page->fecha_registro->ReadOnly && !$Page->fecha_registro->Disabled && !isset($Page->fecha_registro->EditAttrs["readonly"]) && !isset($Page->fecha_registro->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fvisitasedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
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
    ew.createDateTimePicker("fvisitasedit", "x_fecha_registro", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <div id="r_usuario"<?= $Page->usuario->rowAttributes() ?>>
        <label id="elh_visitas_usuario" for="x_usuario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->usuario->caption() ?><?= $Page->usuario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->usuario->cellAttributes() ?>>
<span id="el_visitas_usuario">
<input type="<?= $Page->usuario->getInputTextType() ?>" name="x_usuario" id="x_usuario" data-table="visitas" data-field="x_usuario" value="<?= $Page->usuario->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->usuario->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->usuario->formatPattern()) ?>"<?= $Page->usuario->editAttributes() ?> aria-describedby="x_usuario_help">
<?= $Page->usuario->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->usuario->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fvisitasedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fvisitasedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("visitas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
