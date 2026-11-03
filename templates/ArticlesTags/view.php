<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ArticlesTag $articlesTag
 */
?>
<?php
$this->assign('title', __('Articles Tag'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles Tags'), ['action' => 'index']);
$this->Breadcrumbs->add(__('View Articles Tag'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Articles Tag') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $articlesTag->article_id, $articlesTag->tag_id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $articlesTag->article_id, $articlesTag->tag_id], ['confirm' => __('Are you sure you want to delete # {0}?', $articlesTag->article_id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <table class="table">
            <tr>
                <th class="w-40"><?= __('Article') ?></th>
                <td><?= $articlesTag->hasValue('article') ? $this->Html->link($articlesTag->article->title, ['controller' => 'Articles', 'action' => 'view', $articlesTag->article->id]) : '' ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Tag') ?></th>
                <td><?= $articlesTag->hasValue('tag') ? $this->Html->link($articlesTag->tag->title, ['controller' => 'Tags', 'action' => 'view', $articlesTag->tag->id]) : '' ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Created') ?></th>
                <td><?= h($articlesTag->created) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Modified') ?></th>
                <td><?= h($articlesTag->modified) ?></td>
            </tr>
        </table>
    </div>
</div>

<?= $this->fetch('postLink') ?>
