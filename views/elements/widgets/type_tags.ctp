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

	$before = (isset($data['before']))
		? $data['before']
		: null;

	$data = (isset($data['data']))
		? $data['data']
		: $this->data;

	echo $this->TagCloud->display($data, compact('before'));
}
