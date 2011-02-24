<?php
App::import('Lib', 'Flour.ini_parser');
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.content_id', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.template', array(
		'type' => 'text',
	));

	echo $this->Form->input('Widget.data.content_data', array(
		'type' => 'textarea',
	));



} else {

	$content_id = (isset($data['content_id']))
		? $data['content_id']
		: null;

	$template = (isset($data['template']))
		? $data['template']
		: 'contents/item';

	$content_data = (isset($data['content_data']))
		? ini_parser($data['content_data'])
		: array();

	echo $this->ContentLib->render($content_id, $template, $content_data);
}
