<?php
$data = (isset($data))
	? $data 
	: array();


if($template == 'admin')
{
	echo $this->Form->input('Widget.data.content', array(
		'type' => 'textarea',
		'default' => '<p>put your html here</p>',
	));


} else {

	$content = (isset($data['content']))
		? $data['content']
		: '';

	$data = array(
		'App' => Configure::read('App'),
		'Flour' => Configure::read('Flour'),
	);
	echo String::insert($content, Set::flatten($data));
}
