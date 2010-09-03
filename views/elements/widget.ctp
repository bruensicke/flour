<?php

//which widget to use, defaults to 'generic'
$class = (isset($class))
	? $class 
	: Configure::read('Flour.Widget.types.class');

//which widget to use, defaults to 'generic'
$type = (isset($type))
	? $type 
	: Configure::read('Flour.Widget.types.default');

//which template in the widget to use, defaults to 'default'
$template = (isset($template))
	? $template 
	: Configure::read('Flour.Widget.templates.default');

//what data will be passed (at least) to the widget itself
$widget_data = (isset($widget_data))
	? $widget_data 
	: array();

//show a header before the widget?
$header = (isset($header))
	? $header 
	: '';

//show a footer before the widget?
$footer = (isset($footer))
	? $footer 
	: '';

// debug($class);
// $class = String::insert($class, array('type' => $type, 'class' => $widget_data['class']));


echo $header;
	echo $this->Html->div($class, $this->element($this->Widget->element($type), array(
			'data' => $widget_data,
			'template' => $template,
		)
	));
echo $footer;
