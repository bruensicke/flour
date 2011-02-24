<?php
$this->title = __('Contents', true);

$this->Nav->add('Primary', array(
	'name' => __('Edit', true),
	'url' => array('controller' => 'contents', 'action' => 'edit', $this->data['Content']['id']),
	'ico' => 'edit',
));

$this->Nav->add('Primary', array(
	'name' => __('Clone', true),
	'url' => array('controller' => 'contents', 'action' => 'clone', $this->data['Content']['id']),
));

// echo $this->element('admin/content_start');

// echo $this->Widget->type('editions');

$items = array();

$items[] = array(
	'type' => 'content',
	'target' => 'a',
	'data' => array(
		'id' => $this->data,
		'content_data' => 'template:summary',
	),
);

// $items[] = array(
// 	'type' => 'box',
// 	'target' => 'b',
// 	'data' => array(
// 		'caption' => __('Control Content', true),
// 		'content' => $this->element('contents/form'),
// 	),
// );

echo $this->Widget->row($items, 'full');

// echo $this->element('admin/content_stop');
