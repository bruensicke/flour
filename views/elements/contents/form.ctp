<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Content.defaultType');

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.Content.defaultStatus');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;

$tags = (isset($this->passedArgs['tags']))
	? $this->passedArgs['tags']
	: null;

echo $this->Form->error('Content.model', array(
	'notEmpty' => __('You must give a model to use.', true)
));

echo $this->Form->input('Content.type', array(
	'type' => 'select',
	'class' => 'auto_switch_type',
	'options' => Configure::read('Flour.Content.types'),
	'default' => $type,
));

echo $this->Form->input('Content.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Content.status'),
	'default' => $status,
));

echo $this->Form->input('Content.name', array(
	'default' => $name,
));

echo $this->Form->input('Content.slug', array(
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Content.description', array(
	'type' => 'textarea',
));

echo $this->Form->input('Content.tags', array(
	'default' => $tags,
));
