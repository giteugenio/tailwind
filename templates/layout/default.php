<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html lang="<?= h(str_replace('_', '-', (string)\Cake\I18n\I18n::getLocale())) ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
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
    <nav class="navbar flex-wrap gap-4 bg-base-100 px-4 shadow-sm sm:px-8" aria-label="Main navigation">
        <div class="flex-1 text-xl font-semibold">
            <a href="<?= $this->Url->build('/') ?>"><span>Cake</span>PHP</a>
        </div>
        <div class="flex flex-wrap gap-4 text-sm">
            <?= $this->Html->link(__('Articles'), ['plugin' => null, 'controller' => 'Articles', 'action' => 'index']) ?>
            <?= $this->Html->link(__('Tags'), ['plugin' => null, 'controller' => 'Tags', 'action' => 'index']) ?>
        </div>
    </nav>
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-8">
        <div class="space-y-6">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <?= $this->element('confirm') ?>
    <footer>
    </footer>
</body>
</html>
