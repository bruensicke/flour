<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Widget.types.default');

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.Widget.status.default');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;

$tags = (isset($this->passedArgs['tags']))
	? $this->passedArgs['tags']
	: null;

echo $this->Form->input('Widget.type', array(
	'type' => 'select',
	'class' => 'auto_switch_type',
	'options' => Configure::read('Flour.Widget.types.options'),
	'default' => $type,
));

echo $this->Form->input('Widget.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Widget.status.options'),
	'default' => $status,
));

echo $this->Form->input('Widget.name', array(
	'class' => 'slugify',
	'default' => $name,
));

echo $this->Form->input('Widget.slug', array(
	'class' => 'slug',
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Widget.description', array(
	'type' => 'textarea',
	'class' => 'description elastic',
));

echo $this->Form->input('Widget.tags', array(
	'default' => $tags,
	'class' => 'tags',
));
