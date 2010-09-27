<?php
$this->title = __('Contents', true);
$this->description = __('Add new Content.', true);

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->Form->hidden('Content.id');

echo $this->element('flour/content_start');

echo $this->Widget->type('editions');

$items = array();

$items[] = array(
	'type' => 'box',
	'target' => 'a',
	'data' => array(
		'caption' => __('Enter Content Details.', true),
		'class' => 'type_details box',
		'content' => $this->element('contents/form_type', array('type' => $type)),
	),
);

$items[] = array(
	'type' => 'box',
	'target' => 'b',
	'data' => array(
		'caption' => __('Control Content', true),
		'content' => $this->element('contents/form'),
	),
);

echo $this->Widget->row($items, 'sidebar_right');

echo $this->element('flour/content_stop');
echo $form->end('Ok');
