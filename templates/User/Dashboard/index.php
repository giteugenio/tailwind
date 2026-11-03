<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Inicio'));
?>
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <h1 class="text-2xl font-bold"><?= __('Área de usuario') ?></h1>
        <p><?= __('Consulta los artículos publicados.') ?></p>
        <div class="card-actions mt-4">
            <?= $this->Html->link(
                __('Ver artículos'),
                ['prefix' => 'User', 'controller' => 'Articles', 'action' => 'index'],
                ['class' => 'btn btn-primary'],
            ) ?>
        </div>
    </div>
</div>
