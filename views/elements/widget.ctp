<?php
//which widget to use, defaults to 'generic'
$type = (isset($type))
	? $type 
	: Configure::read('Flour.Widget.types.default');

//which template in the widget to use, defaults to 'default'
$template = (isset($template))
	? $template 
	: Configure::read('Flour.Widget.templates.default');

//what data will be passed (at least) to the widget itself
$data = (isset($data))
	? $data 
	: array();

//show a header before the widget?
$header = (isset($header))
	? $header 
	: '';

//show a footer before the widget?
$footer = (isset($footer))
	? $footer 
	: '';

$class_replacing = (isset($class))
	? array('type' => $type, 'class' => $class)
	: array('type' => $type);

$class = (isset($class))
	? String::insert($class, $class_replacing)
	: String::insert(Configure::read('Flour.Widget.types.class'), $class_replacing);

echo $header;
	echo $this->Html->div($class, $this->element($this->Widget->element($type), array(
			'data' => $data,
			'template' => $template,
		)
	));
echo $footer;
