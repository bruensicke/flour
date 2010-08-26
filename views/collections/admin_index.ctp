<?php
$this->title = __('Collections', true);
$this->description = __('These are all your configurations.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'collections', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'collections/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');
