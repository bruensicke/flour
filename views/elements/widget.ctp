<?php
//default-values
$id = (isset($id))
	? $id 
	: null;

//default-values
$name = (isset($name))
	? $name 
	: null;

//default-values
$description = (isset($description))
	? $description 
	: null;

//default-values
$intro = (isset($intro))
	? $intro 
	: null;

//which widget to use, defaults to 'generic'
$type = (isset($type))
	? $type 
	: Configure::read('Flour.Widget.types.default');

//which widget to use, defaults to 'generic'
$status = (isset($status))
	? $status 
	: Configure::read('Flour.Widget.status.default');

//which template in the widget to use, defaults to 'default'
$template = (isset($template))
	? $template 
	: Configure::read('Flour.Widget.templates.default');

//what data will be passed (at least) to the widget itself
$data = (isset($data))
	? $data 
	: array();

//which widget to use, defaults to 'generic'
$class = (isset($class))
	? $class 
	: '';

//has a title?
$title = (isset($title))
	? $title 
	: '';

//show a header before the widget?
$header = (isset($header))
	? $header 
	: '';

//show a footer before the widget?
$footer = (isset($footer))
	? $footer 
	: '';

$content = $this->element(
	String::insert(
		Configure::read('Flour.Widget.types.pattern'), 
		array('type' => $type)
	), 
	array(
		'data' => $data,
		'template' => $template,
	)
);

echo String::insert(
	Configure::read('Flour.Widget.templates.markup'),
	compact(
		'header',
		'footer',
		'id',
		'type',
		'status',
		'title',
		'slug',
		'name',
		'description',
		'intro',
		'class',
		'content'
	)
);
