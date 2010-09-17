<?php

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


//to be prepended
$before = (isset($before))
	? $before
	: '';

//to be appended
$after = (isset($after))
	? $after
	: '';

$template = (isset($template))
	? $template
	: <<<HTML
	:before
	<div class="clearfix :class":style>
		<div class="half a">:a</div>
		<div class="half b">:b</div>
	</div>
	:after
HTML;

echo String::insert($template, compact('before', 'after', 'a', 'b', 'class', 'style'));