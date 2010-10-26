<?php
$default = <<<HTML
<ul :class rel=":Navigation.id">
	:items
</ul>
HTML;

$div = <<<HTML
<div :class rel=":Navigation.id">
	<span>:Navigation.name</span>
	<ul>
	:items
	</ul>
</div>
HTML;


$template = (empty($template))
	? 'default'
	: $template;


$row['class'] = (!empty($row['Navigation']['class']))
	? 'class="'.$row['Navigation']['class'].'"'
	: '';

echo String::insert($$template, Set::flatten($row));
