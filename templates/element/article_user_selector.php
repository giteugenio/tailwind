<?php
/** @var \App\View\AppView $this */
?>
<div id="user-selector">
    <?= $this->Form->control('user_id', ['options' => $users, 'empty' => __('Select a user')]) ?>
</div>
