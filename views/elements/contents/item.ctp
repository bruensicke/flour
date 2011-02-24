<?php
$default = <<<HTML
<div class="item clearfix" data-id=":Content.id">
	<p>:Content.type</p>
	<h3>:Content.name</h3>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" data-id=":Content.id">
	<h3>:Content.type - :Content.name</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" data-id=":Content.id">
	<p>:Content.type - :Content.name</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
