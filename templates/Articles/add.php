<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Article $article
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 * @var \App\Model\Entity\Tag[]|\Cake\Collection\CollectionInterface $tags
 */
?>
<?php
$this->assign('title', __('Add Article'));
$this->Breadcrumbs->add(__('Home'), '/');
$this->Breadcrumbs->add(__('Articles'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Add Article'));
?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?= __('Add Article') ?></h1>
        <?= $this->Breadcrumbs->render() ?>
    </div>
    <?= $this->Html->link(__('Back'), ['action' => 'index'], ['class' => 'btn btn-ghost btn-sm']) ?>
</div>

<div class="card bg-base-100 shadow max-w-3xl">
    <div class="card-body">
        <?= $this->Form->create($article) ?>
        <?php
            echo $this->Form->control('user_role', [
                'label' => __('User role'),
                'options' => ['user' => __('User'), 'editor' => __('Editor')],
                'value' => $role,
                'hx-get' => $this->Url->build(['action' => 'usersByRole']),
                'hx-trigger' => 'change',
                'hx-target' => '#user-selector',
                'hx-swap' => 'outerHTML',
                'hx-sync' => 'this:replace',
            ]);
            echo $this->element('article_user_selector', compact('users'));
            echo $this->Form->control('title');
            echo $this->Form->control('slug');
            echo $this->Form->control('body');
            echo $this->Form->control('published');
            echo $this->Form->control('tags._ids', ['options' => $tags]);
                ?>
        <div class="card-actions justify-end mt-4">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-ghost']) ?>
            <?= $this->Form->submit(__('Save Article'), ['class' => 'primary']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
