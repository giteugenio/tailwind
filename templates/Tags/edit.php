<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 */
?>
<?php
$this->assign('title', __('Edit Tag'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Tags'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Edit Tag'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Edit Tag') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('View'), ['action' => 'view', $tag->id], ['class' => 'btn btn-ghost btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <?= $this->Form->create($tag) ?>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('articles._ids', ['options' => $articles]);
                ?>
        <div class="card-actions justify-end mt-4">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-ghost']) ?>
            <?= $this->Form->submit(__('Save Tag'), ['class' => 'primary']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->fetch('postLink') ?>
