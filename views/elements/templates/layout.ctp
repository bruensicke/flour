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
$d = (isset($d))
	? $d
	: '';

$d = (is_array($d))
	? implode($seperator, div_explode($d))
	: $d;

//content 'e'
$e = (isset($e))
	? $e
	: '';

$e = (is_array($e))
	? implode($seperator, div_explode($e))
	: $e;

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
	<div class="layoutContainer clearfix :class":style>
		<div class="pane ui-layout-center a">:a</div>
		<div class="pane ui-layout-north d">:d</div>
		<div class="pane ui-layout-south e">:e</div>
		<div class="pane ui-layout-east c">:c</div>
		<div class="pane ui-layout-west b">:b</div>
	</div>
	:after
HTML;
echo String::insert(String::insert($template, compact('before', 'after')), compact('a', 'b', 'c', 'd', 'e', 'class', 'style'));

if(!empty($script))
{
	echo $this->Html->tag('script', (is_array($script)) ? implode($script) : $script);
}
$layout_script = <<<HTML
$().ready(function () {
	$('div.layoutContainer').layout({ applyDefaultStyles: true });
});
HTML;

$layout_style = <<<HTML
	html, body {
		width:		100%;
		height:		100%;
		padding:	0;
		margin:		0;
		overflow:	auto; /* when page gets too small */
	}
	.layoutContainer {
		background:	#999;
		height:		100%;
		margin:		0 auto;
		width:		100%;
		max-width:	900px;
		min-width:	700px;
		_width:		700px; /* min-width for IE6 */
	}
	.pane {
		display:	none; /* will appear when layout inits */
	}
HTML;

echo $this->Html->script('/flour/js/jquery.layout');
echo $this->Html->tag('script', $layout_script);
echo $this->Html->tag('style', $layout_style);
