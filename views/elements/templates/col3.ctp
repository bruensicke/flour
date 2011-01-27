<?php
App::import('Lib', 'Flour.div_explode');

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

$a = (is_array($a))
	? implode($seperator, div_explode($a))
	: $a;

//content 'b'
$b = (isset($b))
	? $b
	: '';

$b = (is_array($b))
	? implode($seperator, div_explode($b))
	: $b;

//content 'c'
$c = (isset($c))
	? $c
	: '';

$c = (is_array($c))
	? implode($seperator, div_explode($c))
	: $c;

//content 'd'
$a = (isset($d) && is_string($d))
	? $a.$seperator.$d
	: $a;

$a = (isset($d) && is_array($d))
	? $a.implode($seperator, div_explode($d))
	: $a;

//content 'e'
$a = (isset($e) && is_string($e))
	? $a.$seperator.$e
	: $a;

$a = (isset($e) && is_array($e))
	? $a.implode($seperator, div_explode($e))
	: $a;

//content 'f'
$a = (isset($f) && is_string($f))
	? $a.$seperator.$f
	: $a;

$a = (isset($f) && is_array($f))
	? $a.implode($seperator, div_explode($f))
	: $a;

//to be prepended
$before = (isset($before))
	? $before
	: '';

//to be prepended
$before = (is_array($before))
	? implode($seperator, div_explode($before))
	: $before;

//to be appended
$after = (isset($after))
	? $after
	: '';

//to be prepended
$after = (is_array($after))
	? implode($seperator, div_explode($after))
	: $after;

$template = (isset($template))
	? $template
	: <<<HTML
	:before
	<div class="clearfix :class":style>
		<div class="third a" style="float: left; width: 33.3%;">:a</div>
		<div class="third b" style="float: left; width: 33.3%;">:b</div>
		<div class="third c" style="float: left; width: 33.3%;">:c</div>
	</div>
	:after
HTML;

echo String::insert(String::insert($template, compact('before', 'after')), compact('a', 'b', 'c', 'class', 'style'));

if(!empty($script))
{
	echo $this->Html->tag('script', (is_array($script)) ? implode($script) : $script);
}