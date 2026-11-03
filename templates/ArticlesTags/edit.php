<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ArticlesTag $articlesTag
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 * @var \App\Model\Entity\Tag[]|\Cake\Collection\CollectionInterface $tags
 */
?>
<?php
$this->assign('title', __('Edit Articles Tag'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles Tags'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Edit Articles Tag'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Edit Articles Tag') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('View'), ['action' => 'view', $articlesTag->article_id, $articlesTag->tag_id], ['class' => 'btn btn-ghost btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $articlesTag->article_id, $articlesTag->tag_id], ['confirm' => __('Are you sure you want to delete # {0}?', $articlesTag->article_id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <?= $this->Form->create($articlesTag, ['url' => ['action' => 'edit', $this->getRequest()->getParam('pass.0'), $this->getRequest()->getParam('pass.1')]]) ?>
        <?php
            echo $this->Form->control('article_id', ['type' => 'select', 'options' => $articles]);
            echo $this->Form->control('tag_id', ['type' => 'select', 'options' => $tags]);
        ?>
        <div class="card-actions justify-end mt-4">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-ghost']) ?>
            <?= $this->Form->submit(__('Save Articles Tag'), ['class' => 'primary']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->fetch('postLink') ?>
