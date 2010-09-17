<?php

//which seperator to join more than one column
$seperator = (isset($seperator))
	? $seperator
	: '';

//how many spans is the left column
$span = (isset($span))
	? $span
	: 8;

//how many spans are available at all
$span_max = (isset($span_max))
	? $span_max
	: 24;

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

$span_right = $span_max - $span;

echo $header;
echo $this->Html->div('span-'.$span, $a);
echo $this->Html->div('last span-'.$span_right, $b);
echo $footer;
