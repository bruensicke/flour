<?php
$default = <<<HTML
<div class="item clearfix" rel=":Navigation.id">
	<h3>:Navigation.name</h3>
	<p>:Navigation.description</p>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" rel=":Navigation.id">
	<h3>:Navigation.name - :Navigation.description</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" rel=":Navigation.id">
	<p>:Navigation.name - :Navigation.description</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
