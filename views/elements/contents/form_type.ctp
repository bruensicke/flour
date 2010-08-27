<?php
$type = (isset($type))
	? $type
	: Configure::read('Flour.Content.types.default');

echo $this->element(
	String::insert(Configure::read('Flour.Content.types.pattern'), 
	array('type' => $type))
);
