<?php
App::import('Lib', 'Flour.ini_parser');
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.plugin', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.element', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.element_data', array(
		'type' => 'textarea',
	));


} else {

	$element = (isset($data['element']))
		? $data['element']
		: '';

	$element_data = (isset($data['element_data']))
		? ini_parser($data['element_data'])
		: array();

	$element_data['plugin'] = (isset($data['plugin']))
		? $data['plugin']
		: null;

	$data = array(
		'App' => Configure::read('App'),
		'Flour' => Configure::read('Flour'),
	);
	echo String::insert($this->element($element, $element_data), Set::flatten($data));
}
