<?php
/**
 * @var \App\View\AppView $this
 */
?>
<dialog x-data="{
    message: '', pending: null, submitting: false,
    open(event) {
        if (document.getElementById('app-confirm').open) return;
        const { trigger, formName } = event.detail;
        const form = formName ? document.forms.namedItem(formName) : trigger.form;
        if (!(form instanceof HTMLFormElement)) return;
        this.pending = { form, trigger };
        this.message = trigger.dataset.confirmMessage || '';
        this.submitting = false;
        document.getElementById('app-confirm').showModal();
    },
    confirm() {
        if (!this.pending || this.submitting) return;
        const { form, trigger } = this.pending;
        this.pending = null;
        this.submitting = true;
        document.getElementById('app-confirm').close();
        if (trigger.form === form && (trigger instanceof HTMLButtonElement || trigger instanceof HTMLInputElement)) {
            form.requestSubmit(trigger);
        } else {
            form.requestSubmit();
        }
    }
}" @app-confirm.window="open($event)" @close="if (!$el.open) pending = null" @cancel="pending = null" id="app-confirm" class="modal" aria-labelledby="app-confirm-title" aria-describedby="app-confirm-message">
    <div class="modal-box">
        <h2 id="app-confirm-title" class="text-lg font-bold mb-4"><?= __('Confirmar acción') ?></h2>
        <?= $this->Html->alert('', ['class' => 'warning', 'id' => 'app-confirm-message', 'x-text' => 'message']) ?>
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" data-confirm-cancel @click="$el.closest('dialog').close()" autofocus><?= __('Cancelar') ?></button>
            <button type="button" class="btn btn-error" data-confirm-accept @click="confirm()" :disabled="submitting"><?= __('Confirmar') ?></button>
        </div>
    </div>
</dialog>
