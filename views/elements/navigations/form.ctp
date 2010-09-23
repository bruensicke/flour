<?php

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.Navigation.status.default');

$slug = (isset($this->passedArgs['slug']))
	? $this->passedArgs['slug']
	: null;

$name = (isset($this->passedArgs['name']))
	? $this->passedArgs['name']
	: null;

$tags = (isset($this->passedArgs['tags']))
	? $this->passedArgs['tags']
	: null;

echo $this->Form->input('Navigation.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Navigation.status.options'),
	'default' => $status,
));

echo $this->Form->input('Navigation.name', array(
	'class' => 'slugify',
	'default' => $name,
));

echo $this->Form->input('Navigation.slug', array(
	'class' => 'slug',
	'default' => $slug,
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Navigation.description', array(
	'type' => 'textarea',
	'class' => 'description elastic',
));

echo $this->Form->input('Navigation.tags', array(
	'default' => $tags,
	'class' => 'tags',
));
