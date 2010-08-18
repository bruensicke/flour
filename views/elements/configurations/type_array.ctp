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
echo $this->Form->hidden('Configuration.val'); //will be js-filled with Temp.key/val

echo $this->Html->div('row');
	echo $this->Form->input('Temp.0.key', array(
		'label' => __('Key', true),
		'type' => 'text',
		'class' => 'left',
	));

	echo $this->Form->input('Temp.0.val', array(
		'label' => __('Value', true),
		'type' => 'text',
		'class' => 'right',
	));
echo $this->Html->tag('/div'); //div.row


echo $this->Form->input('Configuration.autoload', array(
	'type' => 'checkbox',
));
