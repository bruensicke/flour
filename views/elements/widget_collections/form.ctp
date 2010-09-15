<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.WidgetCollection.types.default');

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.WidgetCollection.status.default');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;

$tags = (isset($this->passedArgs['tags']))
	? $this->passedArgs['tags']
	: null;

echo $this->Form->input('WidgetCollection.type', array(
	'type' => 'select',
	'class' => 'auto_switch_type',
	'options' => Configure::read('Flour.WidgetCollection.types.options'),
	'default' => $type,
));

echo $this->Form->input('WidgetCollection.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.WidgetCollection.status.options'),
	'default' => $status,
));

echo $this->Form->input('WidgetCollection.name', array(
	'class' => 'slugify',
	'default' => $name,
));

echo $this->Form->input('WidgetCollection.slug', array(
	'class' => 'slug',
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('WidgetCollection.description', array(
	'type' => 'textarea',
	'class' => 'description elastic',
));

echo $this->Form->input('WidgetCollection.tags', array(
	'default' => $tags,
	'class' => 'tags',
));
