<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
$params += ['alertClass' => 'alert', 'icon' => 'information-circle'];

echo $this->element('TailwindUi.flash/default', compact('message', 'params'));
