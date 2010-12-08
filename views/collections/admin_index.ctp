<?php
$this->title = __('Collections', true);
$this->description = __('These are all your configurations.', true);

$this->Nav->add('Primary', array(
	'name' => __('Create Collection', true),
	'url' => array('controller' => 'collections', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'caption' => __('Collections', true),
	'element' => 'collections/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');
