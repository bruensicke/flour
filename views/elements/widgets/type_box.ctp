<?php
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.header', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.caption', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.label', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.content', array(
		'type' => 'textarea',
		'class' => 'elastic',
	));

	echo $this->Form->input('Widget.data.footer', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.class', array(
		'type' => 'text',
	));



} else {

	$element_data = array();

	$element_data['header'] = (isset($data['header']))
		? $data['header']
		: null;

	$element_data['caption'] = (isset($data['caption']))
		? $data['caption']
		: null;

	$element_data['label'] = (isset($data['label']))
		? $data['label']
		: null;

	$element_data['content'] = (isset($data['content']))
		? $data['content']
		: null;

	$element_data['footer'] = (isset($data['footer']))
		? $data['footer']
		: null;

	$element_data['class'] = (isset($data['class']))
		? $data['class']
		: null;

	$data = array(
		'App' => Configure::read('App'),
		'Flour' => Configure::read('Flour'),
	);
	echo String::insert($this->element('/flour/box', $element_data), Set::flatten($data));
}
