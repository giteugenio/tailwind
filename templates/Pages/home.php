<?php
$this->append('css');
?>
<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<?php
$this->end();
?>

<main class="mx-auto max-w-lg p-6">
    <h1 class="mb-4 text-2xl font-bold">Your first form</h1>
    <?= $this->Form->create(null) ?>
    <?= $this->Form->control('name', ['label' => 'Your name']) ?>
    <?= $this->Form->control('email', ['type' => 'email']) ?>
    <?= $this->Form->button('Preview', ['type' => 'button', 'class' => 'primary']) ?>
    <?= $this->Form->end() ?>
</main>