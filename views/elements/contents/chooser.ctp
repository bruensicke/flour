<?php

if(isset($type) && !empty($type))
{
	echo Configure::read('App.Content.types.descriptions.'.$type);
} else {
	echo $this->Html->para('hint', __('There are different types of Contents to create:', true));
	extract(Configure::read('App.Content.types'));
	$explanations = array();
	foreach ($descriptions as $type => $description)
	{
		$name = $this->Html->link($options[$type], array('controller' => 'contents', 'action' => 'add', 'type' => $type));
		$explanations[$name] = $description;
	}

	echo $this->Collection->show($explanations, true);
}

