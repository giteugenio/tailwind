<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Tag[]|\Cake\Collection\CollectionInterface $tags
 */
?>
<?php
$this->assign('title', __('Tags'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Tags'), ['action' => 'index']);
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Tags') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <div class="flex gap-2">
        <?= $this->Html->link(__('Add Tag'), ['action' => 'add'], ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
</div>

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('title') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $tag) : ?>
                    <tr>
                        <td><?= $this->Number->format($tag->id) ?></td>
                        <td><?= h($tag->title) ?></td>
                        <td><?= h($tag->created) ?></td>
                        <td><?= h($tag->modified) ?></td>
                        <td>
                            <div class="flex gap-1 justify-end">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $tag->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tag->id], ['class' => 'btn btn-ghost btn-xs']) ?>
                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tag->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tag->id), 'class' => 'btn btn-ghost btn-xs text-error', 'block' => true]) ?>
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
