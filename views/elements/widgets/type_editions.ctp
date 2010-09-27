<?php
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.caption', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.template', array(
		'type' => 'text',
	));

} else {

	$element_data = array();

	$element_data['caption'] = (isset($data['caption']))
		? $data['caption']
		: null;

	$element_data['template'] = (isset($data['template']))
		? $data['template']
		: null;


	$data = array(
		'App' => Configure::read('App'),
		'Flour' => Configure::read('Flour'),
	);
	echo String::insert($this->element('flour/editions', $element_data), Set::flatten($data));
}
