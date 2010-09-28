<?php
$data = (isset($data))
	? $data
	: array();

$caption = (isset($caption))
	? $caption
	: null;

$options = (isset($options))
	? $options
	: Configure::read('Flour.Activities.types.options');


$content = array();

if($template == 'admin')
{
	$content[] = $this->Form->input('Widget.data.types', array(
		'type' => 'select',
		'options' => $options,
	));

	$content[] = $this->Form->input('Widget.data.limit', array(
		'type' => 'text',
	));

} else {

	$type = (isset($data['types']))
		? $data['types']
		: null;

	$limit = (isset($data['limit']))
		? $data['limit']
		: null;

	$content = $this->element('flour/activities', compact('type', 'limit'));

}

echo $this->element('flour/activities', compact('content', 'caption'));
