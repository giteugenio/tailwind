<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\Paging\PaginatedResultSet $articles
 */
$this->assign('title', __('Artículos'));
$this->Breadcrumbs->add(__('Inicio'), ['prefix' => 'User', 'controller' => 'Dashboard', 'action' => 'index']);
$this->Breadcrumbs->add(__('Artículos'));
// Render individual links: this installed plugin's links() templates omit href.
$this->Paginator->setTemplates([
    'prevActive' => '<a class="join-item btn btn-sm" rel="prev" href="{{url}}"{{attrs}}>{{text}}</a>',
    'nextActive' => '<a class="join-item btn btn-sm" rel="next" href="{{url}}"{{attrs}}>{{text}}</a>',
    'prevDisabled' => '<span class="join-item btn btn-sm btn-disabled" aria-disabled="true">{{text}}</span>',
    'nextDisabled' => '<span class="join-item btn btn-sm btn-disabled" aria-disabled="true">{{text}}</span>',
    'number' => '<a class="join-item btn btn-sm" href="{{url}}"{{attrs}}>{{text}}</a>',
    'current' => '<span class="join-item btn btn-sm btn-active" aria-current="page">{{text}}</span>',
    'ellipsis' => '<span class="join-item btn btn-sm btn-disabled">…</span>',
]);
?>
<div>
    <h1 class="text-2xl font-bold"><?= __('Artículos') ?></h1>
    <?= $this->Breadcrumbs->render() ?>
</div>
<?php if ($articles->items()->isEmpty()) : ?>
    <p class="card bg-base-100 p-6 shadow"><?= __('No hay artículos publicados.') ?></p>
<?php else : ?>
    <div class="grid gap-4">
        <?php foreach ($articles as $article) : ?>
            <article class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title break-words"><?= h($article->title) ?></h2>
                    <p class="break-words"><?= __('Autor: {0}', h($article->user->email ?? '')) ?></p>
                    <p><?= __('Fecha: {0}', h($article->created)) ?></p>
                    <div class="card-actions">
                        <?= $this->Html->link(
                            __('Leer artículo'),
                            ['prefix' => 'User', 'controller' => 'Articles', 'action' => 'view', $article->id],
                            ['class' => 'btn btn-primary btn-sm'],
                        ) ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <nav aria-label="<?= h(__('Paginación de artículos')) ?>">
        <div class="join">
            <?= $this->Paginator->prev(__('Anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Siguiente')) ?>
        </div>
    </nav>
<?php endif; ?>
