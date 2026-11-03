<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\I18n\I18n;

?>
<!DOCTYPE html>
<html lang="<?= h(str_replace('_', '-', (string)I18n::getLocale())) ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h(__('Área de usuario')) ?>: <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('app') ?>
    <?= $this->Html->script('TailwindUi.dismiss', ['defer' => true]) ?>
    <?= $this->Html->script('alpine.min', ['defer' => true]) ?>
    <?= $this->Html->script('htmx.min', ['defer' => true]) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <nav
        class="navbar flex-wrap gap-4 bg-base-100 px-4 shadow-sm sm:px-8"
        aria-label="<?= h(__('Navegación del área de usuario')) ?>"
    >
        <div class="flex-1 text-xl font-semibold">
            <?= $this->Html->link(
                __('Área de usuario'),
                ['prefix' => 'User', 'controller' => 'Dashboard', 'action' => 'index'],
            ) ?>
        </div>
        <div class="flex flex-wrap gap-4 text-sm">
            <?= $this->Html->link(
                __('Inicio'),
                ['prefix' => 'User', 'controller' => 'Dashboard', 'action' => 'index'],
            ) ?>
            <?= $this->Html->link(
                __('Artículos'),
                ['prefix' => 'User', 'controller' => 'Articles', 'action' => 'index'],
            ) ?>
            <?= $this->Html->link(
                __('Volver al sitio'),
                ['prefix' => false, 'controller' => 'Pages', 'action' => 'display', 'home'],
            ) ?>
        </div>
    </nav>
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8">
        <div class="space-y-6">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
</body>
</html>
