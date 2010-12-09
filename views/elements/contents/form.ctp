<?php
//retrieve the content_type to be used
$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.Content.status.default');

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

if(!empty($this->passedArgs['type']))
{
	echo $this->Form->hidden('Content.type', array('value' => $this->passedArgs['type']));
} else {
	echo $this->Form->input('Content.type', array(
		'type' => 'select',
		'class' => 'auto_switch_type',
		'options' => Configure::read('Flour.Content.types.options'),
		'default' => Configure::read('Flour.Content.types.default'),
	));
}

echo $this->Form->input('Content.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Content.status.options'),
	'default' => $status,
));

echo $this->Form->input('Content.name', array(
	'class' => 'slugify',
	'default' => $name,
));

echo $this->Form->input('Content.slug', array(
	'class' => 'slug',
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Content.description', array(
	'type' => 'textarea',
	'class' => 'description elastic',
));

echo $this->Form->input('Content.tags', array(
	'default' => $tags,
	'class' => 'tags',
));
