<?php
$this->title = __('Contents', true);
$this->description = __('Add new Content.', true);

$this->Nav->add('Primary', array(
	'name' => __('Cancel', true),
	'url' => array('controller' => 'contents', 'action' => 'index'),
	'type' => 'link',
	'ico' => 'cross',
	'confirm' => __('Are you sure you want to cancel?', true),
));

$this->Nav->add('Primary', array(
	'name' => __('Save', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

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
		'class' => 'box',
		'content' => array('type_details' => $this->element('contents/form_type', array('type' => $type))),
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
