<?php
$default = <<<HTML
<div class="item clearfix" rel=":Widget.id">
	<p>:Widget.type</p>
	<h3>:Widget.name</h3>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" rel=":Widget.id">
	<h3>:Widget.type - :Widget.name</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" rel=":Widget.id">
	<p>:Widget.type - :Widget.name</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
