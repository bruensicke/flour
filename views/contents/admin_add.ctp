<?php
$this->title = __('Create Content', true);
$this->description = __('Create new Content.', true);

if(!empty($this->passedArgs['type']))
{
	$name = Configure::read('App.Content.types.options.'.$this->passedArgs['type']);
	$this->title = String::insert( __('Create :type', true), array('type' => $name));
	$this->description = Configure::read('App.Content.types.descriptions.'.$this->passedArgs['type']);

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
}


//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Content.types.default');

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->element('flour/content_start');

$items = array();

if(empty($this->passedArgs['type']))
{
	$items[] = array(
		'type' => 'box',
		'target' => 'a',
		'data' => array(
			'caption' => __('Choose Content type', true),
			'class' => 'box',
			'content' => $this->element('contents/chooser'),
		),
	);
	
} else {

	$items[] = array(
		'type' => 'box',
		'target' => 'a',
		'data' => array(
			'caption' => __('Enter Content Details.', true),
			'class' => 'box',
			'content' => array('type_details' => $this->element('contents/form_type', array('type' => $type))),
		),
	);

	// $items[] = array(
	// 	'type' => 'publish',
	// 	'target' => 'b',
	// 	'data' => array(
	// 		'caption' => __('Control Content (unfinished)', true),
	// 	),
	// );

	$items[] = array(
		'type' => 'box',
		'target' => 'b',
		'data' => array(
			'caption' => __('Control Content', true),
			'content' => $this->element('contents/form'),
		),
	);
}

echo $this->Widget->row($items, 'sidebar_right');

echo $this->element('flour/content_stop');
echo $form->end();

