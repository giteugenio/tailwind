<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag $tag
 */
?>
<?php
$this->assign('title', __('Tag'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Tags'), ['action' => 'index']);
$this->Breadcrumbs->add(__('View Tag'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Tag') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tag->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <table class="table">
            <tr>
                <th class="w-40"><?= __('Title') ?></th>
                <td><?= h($tag->title) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Id') ?></th>
                <td><?= $this->Number->format($tag->id) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Created') ?></th>
                <td><?= h($tag->created) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Modified') ?></th>
                <td><?= h($tag->modified) ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <h2 class="card-title"><?= __('Related Articles') ?></h2>
        <?php if (!empty($tag->articles)): ?>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><?= __('Id') ?></th>
                        <th><?= __('User Id') ?></th>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Slug') ?></th>
                        <th><?= __('Body') ?></th>
                        <th><?= __('Published') ?></th>
                        <th><?= __('Created') ?></th>
                        <th><?= __('Modified') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tag->articles as $articles): ?>
                    <tr>
                        <td><?= h($articles->id) ?></td>
                        <td><?= h($articles->user_id) ?></td>
                        <td><?= h($articles->title) ?></td>
                        <td><?= h($articles->slug) ?></td>
                        <td><?= h($articles->body) ?></td>
                        <td><?= h($articles->published) ?></td>
                        <td><?= h($articles->created) ?></td>
                        <td><?= h($articles->modified) ?></td>
                        <td>
                            <div class="flex gap-1 justify-end">
                                <?= $this->Html->link(__('View'), ['controller' => 'Articles', 'action' => 'view', $articles->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Articles', 'action' => 'edit', $articles->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Articles', 'action' => 'delete', $articles->id], ['confirm' => __('Are you sure you want to delete # {0}?', $articles->id), 'class' => 'btn btn-ghost btn-xs text-error', 'block' => true]) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->fetch('postLink') ?>
