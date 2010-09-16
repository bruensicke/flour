<?php
$caption = (isset($caption))
	? $caption
	: '';

$btnbar = (isset($btnbar))
	? $btnbar
	: '';

$filters = (isset($filters))
	? $filters
	: '';

$actions = (isset($actions))
	? $actions
	: '';

$class = (isset($class))
	? $class
	: 'box clearfix';

$style = (isset($style))
	? $style
	: null;

$options = (isset($options))
	? $options
	: array('style' => $style);

$content = (isset($content))
	? $content
	: '';

$header = (isset($header))
	? $header
	: '';

$footer = (isset($footer))
	? $footer
	: '';

$before = (isset($before))
	? $before
	: '';

$after = (isset($after))
	? $after
	: '';


if(is_array($content))
{
	$contentArray = array();
	foreach($content as $key => $val)
	{
		if(is_numeric($key))
		{
			$contentArray[] = $val;
		} else {
			$contentArray[] = (is_array($val))
				? $this->Html->div($key, implode($val))
				: $this->Html->div($key, $val);
		}
	}
	$content = $contentArray;
}

echo $before;
echo $this->Html->div($class, null, $options);

	if (!empty($caption) || !empty($btnbar) || !empty($filters) || !empty($actions))
	{
		echo $this->Html->div('caption');

			echo (!empty($btnbar) && is_string($btnbar))
				? $this->Html->div('btnbar', $btnbar)
				: null;

			echo (!empty($btnbar) && is_array($btnbar))
				? $this->Html->nestedList($btnbar)
				: null;

			echo (!empty($caption) && is_string($caption))
				? $this->Html->tag('h2', $caption)
				: null;

			echo (!empty($caption) && is_array($caption))
				? $this->Html->nestedList($caption)
				: null;

			echo (!empty($actions) && is_string($actions))
				? $this->Html->div('actions', $actions)
				: null;

			echo (!empty($actions) && is_array($actions))
				? $this->Html->div('actions', $this->Html->nestedList($actions))
				: null;

			echo (!empty($filters) && is_string($filters))
				? $this->Html->div('filter', $filters)
				: null;

			echo (!empty($filters) && is_array($filters))
				? $this->Html->div('filter', $this->Html->nestedList($filters))
				: null;

		echo $this->Html->tag('/div'); //div.caption
	}

	echo $this->Html->div('box_wrapper');

		echo (!empty($header))
			? $this->Html->div('box_header', $header)
			: null;

		echo (!empty($content) && is_string($content))
			? $this->Html->div('box_content', $content)
			: null;

		echo (!empty($content) && is_array($content))
			? $this->Html->div('box_content', implode($content))
			: null;

		echo (!empty($footer))
			? $this->Html->div('box_footer', $footer)
			: null;

	echo $this->Html->tag('/div'); //div.box

echo $this->Html->tag('/div'); //div.box
echo $after;
