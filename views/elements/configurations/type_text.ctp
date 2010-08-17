<?php
$category = (isset($this->passedArgs['category']))
	? $this->passedArgs['category']
	: Configure::read('Flour.Configuration.defaultCategory');

echo $this->Form->input('Configuration.category', array(
	'type' => 'select',
	'options' => Configure::read('Flour.Configuration.categories'),
	'default' => $category,
));

echo $this->Form->input('Configuration.title');

echo $this->Form->input('Configuration.val', array(
	'type' => 'textarea',
));

echo $this->Form->input('Configuration.autoload', array(
	'type' => 'checkbox',
));

