<?php
//default-values
$id = (isset($id))
	? $id 
	: '';

//default-values
$name = (isset($name))
	? $name 
	: null;

$caption = (isset($caption))
	? $caption 
	: $name;

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
	: 'default';

//what data will be passed (at least) to the widget itself
$data = (isset($data))
	? $data 
	: array();

//which class a widget has
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

$before = (isset($before))
	? $before 
	: '';

$after = (isset($after))
	? $after 
	: '';

//get name of correct element
$element = $this->Widget->element($type);

//get parsed widget into $content
$content = $this->element($element, 
	compact(
		'id',
		'class',
		'before',
		'after',
		'header',
		'footer',
		'content',
		'caption',
		'data',
		'template'
	)
);

$markup = <<<HTML
<div class="widget type_:type :class">:content</div>
HTML;


echo String::insert($markup,
	compact(
		'id',
		'title',
		'type',
		'class',
		'content'
	)
);
