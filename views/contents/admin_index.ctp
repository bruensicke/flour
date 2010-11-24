<?php
$this->title = __('Contents', true);
$this->description = __('This is your content library.', true);

// echo $this->Html->link(__('Add', true), array('controller' => 'contents', 'action' => 'add'));

$this->Nav->add('Primary', array(
	'name' => __('Add', true),
	'url' => array('controller' => 'contents', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));

echo $this->element('flour/content_start');

$items = array();

$items[] = array(
	'type' => 'iterator',
	'target' => 'a',
	'data' => array(
		'caption' => __('Contents', true),
		'element' => 'contents/item',
		'search' => true,
		),
);

$items[] = array(
	'type' => 'tags',
	'target' => 'b',
	'data' => array(
		'caption' => __('Tags', true),
		'data' => $tags,
		// 'before' => '<span>',
	),
);

echo $this->Widget->row($items, 'full');

echo $this->Widget->type('activities', array(
	'types' => array(
		'content_object_created',
		'content_object_updated',
		'content_object_deleted',
	)
));

echo $this->element('flour/content_stop');
