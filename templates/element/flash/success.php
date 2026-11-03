<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$params['alertClass'] = 'alert alert-success';
$params['icon'] = 'check-circle';

echo $this->element('TailwindUi.flash/default', compact('message', 'params'));
