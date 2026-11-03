<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ArticlesTag[]|\Cake\Collection\CollectionInterface $articlesTags
 */
?>
<?php
$this->assign('title', __('Articles Tags'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles Tags'), ['action' => 'index']);
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Articles Tags') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Add Articles Tag'), ['action' => 'add'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('article_id') ?></th>
                        <th><?= $this->Paginator->sort('tag_id') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articlesTags as $articlesTag) : ?>
                    <tr>
                        <td><?= $articlesTag->hasValue('article') ? $this->Html->link($articlesTag->article->title, ['controller' => 'Articles', 'action' => 'view', $articlesTag->article->id]) : '' ?></td>
                        <td><?= $articlesTag->hasValue('tag') ? $this->Html->link($articlesTag->tag->title, ['controller' => 'Tags', 'action' => 'view', $articlesTag->tag->id]) : '' ?></td>
                        <td><?= h($articlesTag->created) ?></td>
                        <td><?= h($articlesTag->modified) ?></td>
                        <td>
                            <div class="flex gap-1 justify-end">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $articlesTag->article_id, $articlesTag->tag_id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $articlesTag->article_id, $articlesTag->tag_id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $articlesTag->article_id, $articlesTag->tag_id], ['confirm' => __('Are you sure you want to delete # {0}?', $articlesTag->article_id), 'class' => 'btn btn-ghost btn-xs text-error', 'block' => true]) ?>
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
