<?php
$default = <<<HTML
<div class="item clearfix" rel=":WidgetCollection.id">
	<p>:WidgetCollection.type</p>
	<h3>:WidgetCollection.name</h3>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" rel=":WidgetCollection.id">
	<h3>:WidgetCollection.type - :WidgetCollection.name</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" rel=":WidgetCollection.id">
	<p>:WidgetCollection.type - :WidgetCollection.name</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
