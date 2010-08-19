<?php
//give $full = true to show all fields
$full = (isset($full))
	? $full
	: false;

//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Configuration.defaultType');

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.Configuration.defaultStatus');

$autoload = (isset($this->passedArgs['autoload']))
	? $this->passedArgs['autoload']
	: Configure::read('Flour.Configuration.defaultAutoload');

$category = (isset($this->passedArgs['category']))
	? $this->passedArgs['category']
	: Configure::read('Flour.Configuration.defaultCategory');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;

$tags = (isset($this->passedArgs['tags']))
	? $this->passedArgs['tags']
	: null;

if($full)
{
	echo $this->Form->input('Configuration.category', array(
		'type' => 'select',
		'options' => Configure::read('Flour.Configuration.categories'),
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
		'options' => Configure::read('Flour.Configuration.types'),
		'default' => $type,
	));
}

echo $this->Form->input('Configuration.autoload', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Configuration.autoload'),
	'default' => $autoload,
));

//fields for finding the specific user / or group
/*
echo $this->Form->input('Configuration.user_id', array());
echo $this->Form->input('Configuration.group_id', array());
*/

echo $this->Form->input('Configuration.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Configuration.status'),
	'default' => $status,
));

echo $this->Form->input('Configuration.description', array(
	'type' => 'textarea',
	'class' => 'description elastic',
));

echo $this->Form->input('Configuration.tags', array(
	'default' => $tags,
));
