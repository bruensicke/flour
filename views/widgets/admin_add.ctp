<?php
$this->title = __('Widgets', true);
$this->description = __('Add new Widget.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Widget.types.default');

echo $this->Form->create('Widget', array('action' => $this->action));
echo $this->element('flour/content_start');

$items = array();

$items[] = array(
	'type' => 'box',
	'target' => 'a',
	'data' => array(
		'caption' => __('Enter Widget Details.', true),
		'class' => 'box type_details',
		//'content' => $this->element('flour/widget', array('type' => $type, 'template' => 'admin')),
		'content' => $this->Widget->type($type, array(), array('template' => 'admin')),
	),
);

$items[] = array(
	'type' => 'box',
	'target' => 'b',
	'data' => array(
		'caption' => __('Control Widget', true),
		'content' => $this->element('widgets/form'),
	),
);

echo $this->Widget->row($items, 'sidebar_right');

echo $this->element('flour/content_stop');
echo $form->end('Ok');
