<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotificacionesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notificaciones: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fnotificacionesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotificacionesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["notificar", [fields.notificar.visible && fields.notificar.required ? ew.Validators.required(fields.notificar.caption) : null], fields.notificar.isInvalid],
            ["asunto", [fields.asunto.visible && fields.asunto.required ? ew.Validators.required(fields.asunto.caption) : null], fields.asunto.isInvalid],
            ["notificacion", [fields.notificacion.visible && fields.notificacion.required ? ew.Validators.required(fields.notificacion.caption) : null], fields.notificacion.isInvalid],
            ["notificados", [fields.notificados.visible && fields.notificados.required ? ew.Validators.required(fields.notificados.caption) : null], fields.notificados.isInvalid],
            ["notificados_efectivos", [fields.notificados_efectivos.visible && fields.notificados_efectivos.required ? ew.Validators.required(fields.notificados_efectivos.caption) : null], fields.notificados_efectivos.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["enviado", [fields.enviado.visible && fields.enviado.required ? ew.Validators.required(fields.enviado.caption) : null], fields.enviado.isInvalid],
            ["adjunto", [fields.adjunto.visible && fields.adjunto.required ? ew.Validators.fileRequired(fields.adjunto.caption) : null], fields.adjunto.isInvalid]
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
            "notificar": <?= $Page->notificar->toClientList($Page) ?>,
            "_username": <?= $Page->_username->toClientList($Page) ?>,
            "enviado": <?= $Page->enviado->toClientList($Page) ?>,
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
<form name="fnotificacionesadd" id="fnotificacionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notificaciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->notificar->Visible) { // notificar ?>
    <div id="r_notificar"<?= $Page->notificar->rowAttributes() ?>>
        <label id="elh_notificaciones_notificar" for="x_notificar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificar->caption() ?><?= $Page->notificar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notificar->cellAttributes() ?>>
<span id="el_notificaciones_notificar">
    <select
        id="x_notificar"
        name="x_notificar"
        class="form-select ew-select<?= $Page->notificar->isInvalidClass() ?>"
        <?php if (!$Page->notificar->IsNativeSelect) { ?>
        data-select2-id="fnotificacionesadd_x_notificar"
        <?php } ?>
        data-table="notificaciones"
        data-field="x_notificar"
        data-value-separator="<?= $Page->notificar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->notificar->getPlaceHolder()) ?>"
        <?= $Page->notificar->editAttributes() ?>>
        <?= $Page->notificar->selectOptionListHtml("x_notificar") ?>
    </select>
    <?= $Page->notificar->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->notificar->getErrorMessage() ?></div>
<?= $Page->notificar->Lookup->getParamTag($Page, "p_x_notificar") ?>
<?php if (!$Page->notificar->IsNativeSelect) { ?>
<script>
loadjs.ready("fnotificacionesadd", function() {
    var options = { name: "x_notificar", selectId: "fnotificacionesadd_x_notificar" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fnotificacionesadd.lists.notificar?.lookupOptions.length) {
        options.data = { id: "x_notificar", form: "fnotificacionesadd" };
    } else {
        options.ajax = { id: "x_notificar", form: "fnotificacionesadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.notificaciones.fields.notificar.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asunto->Visible) { // asunto ?>
    <div id="r_asunto"<?= $Page->asunto->rowAttributes() ?>>
        <label id="elh_notificaciones_asunto" for="x_asunto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asunto->caption() ?><?= $Page->asunto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->asunto->cellAttributes() ?>>
<span id="el_notificaciones_asunto">
<input type="<?= $Page->asunto->getInputTextType() ?>" name="x_asunto" id="x_asunto" data-table="notificaciones" data-field="x_asunto" value="<?= $Page->asunto->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->asunto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->asunto->formatPattern()) ?>"<?= $Page->asunto->editAttributes() ?> aria-describedby="x_asunto_help">
<?= $Page->asunto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asunto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificacion->Visible) { // notificacion ?>
    <div id="r_notificacion"<?= $Page->notificacion->rowAttributes() ?>>
        <label id="elh_notificaciones_notificacion" for="x_notificacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificacion->caption() ?><?= $Page->notificacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notificacion->cellAttributes() ?>>
<span id="el_notificaciones_notificacion">
<textarea data-table="notificaciones" data-field="x_notificacion" name="x_notificacion" id="x_notificacion" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificacion->getPlaceHolder()) ?>"<?= $Page->notificacion->editAttributes() ?> aria-describedby="x_notificacion_help"><?= $Page->notificacion->EditValue ?></textarea>
<?= $Page->notificacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificacion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificados->Visible) { // notificados ?>
    <div id="r_notificados"<?= $Page->notificados->rowAttributes() ?>>
        <label id="elh_notificaciones_notificados" for="x_notificados" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificados->caption() ?><?= $Page->notificados->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notificados->cellAttributes() ?>>
<span id="el_notificaciones_notificados">
<textarea data-table="notificaciones" data-field="x_notificados" name="x_notificados" id="x_notificados" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificados->getPlaceHolder()) ?>"<?= $Page->notificados->editAttributes() ?> aria-describedby="x_notificados_help"><?= $Page->notificados->EditValue ?></textarea>
<?= $Page->notificados->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificados->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notificados_efectivos->Visible) { // notificados_efectivos ?>
    <div id="r_notificados_efectivos"<?= $Page->notificados_efectivos->rowAttributes() ?>>
        <label id="elh_notificaciones_notificados_efectivos" for="x_notificados_efectivos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notificados_efectivos->caption() ?><?= $Page->notificados_efectivos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->notificados_efectivos->cellAttributes() ?>>
<span id="el_notificaciones_notificados_efectivos">
<textarea data-table="notificaciones" data-field="x_notificados_efectivos" name="x_notificados_efectivos" id="x_notificados_efectivos" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notificados_efectivos->getPlaceHolder()) ?>"<?= $Page->notificados_efectivos->editAttributes() ?> aria-describedby="x_notificados_efectivos_help"><?= $Page->notificados_efectivos->EditValue ?></textarea>
<?= $Page->notificados_efectivos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notificados_efectivos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_notificaciones__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_notificaciones__username">
<?php
if (IsRTL()) {
    $Page->_username->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x__username" class="ew-auto-suggest">
    <input type="<?= $Page->_username->getInputTextType() ?>" class="form-control" name="sv_x__username" id="sv_x__username" value="<?= RemoveHtml($Page->_username->EditValue) ?>" autocomplete="off" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
</span>
<selection-list hidden class="form-control" data-table="notificaciones" data-field="x__username" data-input="sv_x__username" data-value-separator="<?= $Page->_username->displayValueSeparatorAttribute() ?>" name="x__username" id="x__username" value="<?= HtmlEncode($Page->_username->CurrentValue) ?>"></selection-list>
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
<script>
loadjs.ready("fnotificacionesadd", function() {
    fnotificacionesadd.createAutoSuggest(Object.assign({"id":"x__username","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->_username->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.notificaciones.fields._username.autoSuggestOptions));
});
</script>
<?= $Page->_username->Lookup->getParamTag($Page, "p_x__username") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_notificaciones_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_notificaciones_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="notificaciones" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
    <div id="r_enviado"<?= $Page->enviado->rowAttributes() ?>>
        <label id="elh_notificaciones_enviado" for="x_enviado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enviado->caption() ?><?= $Page->enviado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->enviado->cellAttributes() ?>>
<span id="el_notificaciones_enviado">
    <select
        id="x_enviado"
        name="x_enviado"
        class="form-select ew-select<?= $Page->enviado->isInvalidClass() ?>"
        <?php if (!$Page->enviado->IsNativeSelect) { ?>
        data-select2-id="fnotificacionesadd_x_enviado"
        <?php } ?>
        data-table="notificaciones"
        data-field="x_enviado"
        data-value-separator="<?= $Page->enviado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->enviado->getPlaceHolder()) ?>"
        <?= $Page->enviado->editAttributes() ?>>
        <?= $Page->enviado->selectOptionListHtml("x_enviado") ?>
    </select>
    <?= $Page->enviado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->enviado->getErrorMessage() ?></div>
<?php if (!$Page->enviado->IsNativeSelect) { ?>
<script>
loadjs.ready("fnotificacionesadd", function() {
    var options = { name: "x_enviado", selectId: "fnotificacionesadd_x_enviado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fnotificacionesadd.lists.enviado?.lookupOptions.length) {
        options.data = { id: "x_enviado", form: "fnotificacionesadd" };
    } else {
        options.ajax = { id: "x_enviado", form: "fnotificacionesadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.notificaciones.fields.enviado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
    <div id="r_adjunto"<?= $Page->adjunto->rowAttributes() ?>>
        <label id="elh_notificaciones_adjunto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->adjunto->caption() ?><?= $Page->adjunto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->adjunto->cellAttributes() ?>>
<span id="el_notificaciones_adjunto">
<div id="fd_x_adjunto" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_adjunto"
        name="x_adjunto"
        class="form-control ew-file-input"
        title="<?= $Page->adjunto->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="notificaciones"
        data-field="x_adjunto"
        data-size="255"
        data-accept-file-types="<?= $Page->adjunto->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->adjunto->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->adjunto->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_adjunto_help"
        <?= ($Page->adjunto->ReadOnly || $Page->adjunto->Disabled) ? " disabled" : "" ?>
        <?= $Page->adjunto->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->adjunto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->adjunto->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_adjunto" id= "fn_x_adjunto" value="<?= $Page->adjunto->Upload->FileName ?>">
<input type="hidden" name="fa_x_adjunto" id= "fa_x_adjunto" value="0">
<table id="ft_x_adjunto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnotificacionesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnotificacionesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("notificaciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
