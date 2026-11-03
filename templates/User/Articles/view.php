<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 */
$this->assign('title', h($article->title));
$this->Breadcrumbs->add(__('Inicio'), ['prefix' => 'User', 'controller' => 'Dashboard', 'action' => 'index']);
$this->Breadcrumbs->add(__('Artículos'), ['prefix' => 'User', 'controller' => 'Articles', 'action' => 'index']);
$this->Breadcrumbs->add(h($article->title));
?>
<div>
    <h1 class="text-2xl font-bold break-words"><?= h($article->title) ?></h1>
    <?= $this->Breadcrumbs->render() ?>
</div>
<article class="card bg-base-100 shadow">
    <div class="card-body min-w-0">
        <p class="break-words"><?= __('Autor: {0}', h($article->user->email ?? '')) ?></p>
        <p><?= __('Creado: {0}', h($article->created)) ?></p>
        <p><?= __('Actualizado: {0}', h($article->modified)) ?></p>
        <div class="space-y-4 break-words"><?= $this->Text->autoParagraph(h($article->body)) ?></div>
        <h2 class="font-semibold mt-4"><?= __('Etiquetas') ?></h2>
        <?php if ($article->tags) : ?>
            <ul class="flex flex-wrap gap-2">
                <?php foreach ($article->tags as $tag) : ?>
                    <li class="badge badge-outline"><?= h($tag->title) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p><?= __('Sin etiquetas.') ?></p>
        <?php endif; ?>
    </div>
</article>
<?= $this->Html->link(
    __('Volver a artículos'),
    ['prefix' => 'User', 'controller' => 'Articles', 'action' => 'index'],
    ['class' => 'btn btn-ghost'],
) ?>
