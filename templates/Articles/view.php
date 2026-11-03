<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 */
?>
<?php
$this->assign('title', __('Article'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles'), ['action' => 'index']);
$this->Breadcrumbs->add(__('View Article'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Article') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $article->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $article->id], ['confirm' => __('Are you sure you want to delete # {0}?', $article->id), 'class' => 'btn btn-error btn-sm btn-outline', 'block' => true]) ?>
        <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <table class="table">
            <tr>
                <th class="w-40"><?= __('User') ?></th>
                <td><?= $article->hasValue('user') ? $this->Html->link($article->user->email, ['controller' => 'Users', 'action' => 'view', $article->user->id]) : '' ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Title') ?></th>
                <td><?= h($article->title) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Slug') ?></th>
                <td><?= h($article->slug) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Id') ?></th>
                <td><?= $this->Number->format($article->id) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Created') ?></th>
                <td><?= h($article->created) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Modified') ?></th>
                <td><?= h($article->modified) ?></td>
            </tr>
            <tr>
                <th class="w-40"><?= __('Published') ?></th>
                <td><?= $article->published ? __('Yes') : __('No'); ?></td>
            </tr>
        </table>
        <div class="mt-4">
            <h3 class="font-semibold mb-1"><?= __('Body') ?></h3>
            <div class="prose max-w-none"><?= $this->Text->autoParagraph(h($article->body)); ?></div>
        </div>
    </div>
</div>
<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <h2 class="card-title"><?= __('Related Tags') ?></h2>
        <?php if (!empty($article->tags)): ?>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><?= __('Id') ?></th>
                        <th><?= __('Title') ?></th>
                        <th><?= __('Created') ?></th>
                        <th><?= __('Modified') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($article->tags as $tags): ?>
                    <tr>
                        <td><?= h($tags->id) ?></td>
                        <td><?= h($tags->title) ?></td>
                        <td><?= h($tags->created) ?></td>
                        <td><?= h($tags->modified) ?></td>
                        <td>
                            <div class="flex gap-1 justify-end">
                                <?= $this->Html->link(__('View'), ['controller' => 'Tags', 'action' => 'view', $tags->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tags', 'action' => 'edit', $tags->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tags', 'action' => 'delete', $tags->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tags->id), 'class' => 'btn btn-ghost btn-xs text-error', 'block' => true]) ?>
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
