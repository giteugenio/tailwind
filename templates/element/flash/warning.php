<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$params['alertClass'] = 'alert alert-warning';
$params['icon'] = 'exclamation-triangle';

echo $this->element('TailwindUi.flash/default', compact('message', 'params'));
