<?php

//which seperator to join more than one column
$seperator = (isset($seperator))
	? $seperator 
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

echo $header;
echo $this->Html->div('half', $a);
echo $this->Html->div('half', $b);
echo $footer;
