<?php
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.element', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.caption', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.label', array(
		'type' => 'text',
	));

	//show dropdown, show textbox, element-selector or widget-selector accordingly
	// echo $this->Form->input('Widget.data.content_type', array(
	// 	'type' => 'select',
	// 	'options' => $content_types,
	// ));

} else {

	$element_data = array();

	$element_data['element'] = (isset($data['element']))
		? $data['element']
		: null;

	$element_data['search'] = (isset($data['search']))
		? $data['search']
		: null;

	$element_data['filters'] = (isset($data['filters']))
		? $data['filters']
		: null;

	$element_data['group'] = (isset($data['group']))
		? $data['group']
		: null;

	$element_data['header'] = (isset($data['header']))
		? $data['header']
		: null;

	$element_data['caption'] = (isset($data['caption']))
		? $data['caption']
		: null;

	$element_data['label'] = (isset($data['label']))
		? $data['label']
		: null;

	$element_data['footer'] = (isset($data['footer']))
		? $data['footer']
		: null;

	$element_data['class'] = (isset($data['class']) && !empty($data['class']))
		? $data['class']
		: null;

	$data = array(
		'App' => Configure::read('App'),
		'Flour' => Configure::read('Flour'),
	);
	echo String::insert($this->element('flour/iterator', $element_data), Set::flatten($data));
}
