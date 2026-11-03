<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 */
?>
<?php
$this->assign('title', __('Articles'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles'), ['action' => 'index']);
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Articles') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Add Article'), ['action' => 'add'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('user_id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= $this->Paginator->sort('slug') ?></th>
                        <th><?= $this->Paginator->sort('published') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) : ?>
                    <tr>
                        <td><?= $this->Number->format($article->id) ?></td>
                        <td><?= $article->hasValue('user') ? $this->Html->link($article->user->email, ['controller' => 'Users', 'action' => 'view', $article->user->id]) : '' ?></td>
                        <td><?= h($article->title) ?></td>
                        <td><?= h($article->slug) ?></td>
                        <td><?= h($article->published) ?></td>
                        <td><?= h($article->created) ?></td>
                        <td><?= h($article->modified) ?></td>
                        <td>
                            <div class="flex gap-1 justify-end">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $article->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $article->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete # {0}?', $article->id), 'class' => 'btn btn-ghost btn-xs text-error', 'block' => true]) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <?= $this->Paginator->links() ?>
</div>

<?= $this->fetch('postLink') ?>
