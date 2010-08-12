<?php
//retrieve the content_type to be used
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: 'article';

$status = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: 0;

echo $this->Form->error('Content.model', array(
	'notEmpty' => __('You must give a model to use.', true)
));

echo $this->Form->input('Content.type', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Content.types'),
	'default' => $type,
));

echo $this->Form->input('Content.status', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Content.status'),
	'default' => $status,
));

echo $this->Form->input('Content.name');
echo $this->Form->input('Content.slug', array(
	'errors' => array(
		'notEmpty' => __('This field is required.', true)
	)
));

echo $this->Form->input('Content.description');

echo $this->Form->input('Content.tags');
