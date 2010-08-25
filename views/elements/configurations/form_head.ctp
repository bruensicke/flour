<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Configuration.types.default');

$autoload = (isset($this->passedArgs['autoload']))
	? $this->passedArgs['autoload']
	: Configure::read('Flour.Configuration.autoload.default');

$category = (isset($this->passedArgs['category']))
	? $this->passedArgs['category']
	: Configure::read('Flour.Configuration.categories.default');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;


echo $this->Form->input('Configuration.category', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Configuration.categories.options'),
	'default' => $category,
));

echo $this->Form->input('Configuration.name', array(
	'class' => 'slugify',
	'default' => $name,
));

echo $this->Form->input('Configuration.slug', array(
	'class' => 'slug',
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Configuration.type', array(
	'type' => 'select',
	'class' => 'auto_switch_type',
	'options' => Configure::read('Flour.Configuration.types.options'),
	'default' => $type,
));
