<?php
$this->title = __('Configurations', true);
$this->description = __('These are all your configurations.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'configurations', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'configurations/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');
