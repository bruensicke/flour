<?php
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

	function my_split($string)
	{
		$out = array();
		$rows = explode("\r\n", $string);
		foreach($rows as $row)
		{
			list($key, $val) = explode(':', $row, 2);
			$out[$key] = $val;
		}
		return $out;
	}

	$element = (isset($data['element']))
		? $data['element']
		: '';

	$element_data = (isset($data['element_data']))
		? my_split($data['element_data'])
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
