<?php

//sidebar width
$width = (isset($width))
	? $width
	: '300px';

//which seperator to join more than one column
$seperator = (isset($seperator))
	? $seperator
	: '';

//template class
$class = (isset($class))
	? $class
	: '';

//template style
$style = (isset($style))
	? ' style="'.$style.'"'
	: '';

//content 'a'
$a = (isset($a))
	? $a
	: '';

//content 'b'
$b = (isset($b))
	? $b
	: '';

//content 'c'
$a = (isset($c))
	? $a.$seperator.$c
	: $a;

//content 'd'
$a = (isset($d))
	? $a.$seperator.$d
	: $a;

//content 'e'
$a = (isset($e))
	? $a.$seperator.$e
	: $a;

//content 'f'
$a = (isset($f))
	? $a.$seperator.$f
	: $a;


//show a header before the widget?
$header = (isset($header))
	? $header 
	: '';

//show a footer after the widget?
$footer = (isset($footer))
	? $footer 
	: '';

$template = (isset($template))
	? $template
	: <<<HTML
	:header
	<div class="clearfix :class":style>
		<div class="sidebar sidebar_right b" style="width::width;">:b</div>
		<div class="a" style="margin-right::width;">:a</div>
	</div>
	:footer
HTML;

echo String::insert($template, compact('header', 'footer', 'a', 'b', 'class', 'style', 'width'));
