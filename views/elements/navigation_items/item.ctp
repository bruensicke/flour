<?php
$default = <<<HTML
<li :class>
	:link
</li>
HTML;

$important = <<<HTML
<li :class>
	<h3>:link</h3>
</li>
HTML;

$tag = <<<HTML
:tag
	:link
:tagclose
HTML;

$template = (empty($template))
	? 'default'
	: $template;


// $tow['tag'] = $this->Html->tag($tag, null, $options);

$row['class'] = explode(' ', $row['NavigationItem']['class']);
$row['class'][] = $first;
$row['class'][] = $last;
$row['class'] = array_filter($row['class']);

$row['class'] = (!empty($row['class']))
	? 'class="'.implode(' ', $row['class']).'"'
	: '';


switch($row['NavigationItem']['type'])
{
	// case 'button':
	// 	$row['link'] = (!empty($row['NavigationItem']['url']))
	// 		? $this->Html->link($row['NavigationItem']['name'], $row['NavigationItem']['url'])
	// 		: $this->Html->tag('span', $row['NavigationItem']['name']);
	// 	break;

	case 'default':
	default:
		$row['link'] = (!empty($row['NavigationItem']['url']))
			? $this->Html->link($row['NavigationItem']['name'], $row['NavigationItem']['url'])
			: $this->Html->tag('span', $row['NavigationItem']['name']);
		break;
}






// debug($row);
echo String::insert($$template, Set::flatten($row));
