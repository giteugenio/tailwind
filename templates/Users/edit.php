<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Article[]|\Cake\Collection\CollectionInterface $articles
 */
?>
<?php
$this->assign('title', __('Edit User'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Users'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Edit User'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Edit User') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ['class' => 'btn btn-ghost btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <?= $this->Form->create($user) ?>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('password');
        ?>
        <div class="card-actions justify-end mt-4">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-ghost']) ?>
            <?= $this->Form->submit(__('Save User'), ['class' => 'primary']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->fetch('postLink') ?>
