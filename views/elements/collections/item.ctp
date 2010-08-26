<?php
$default = <<<HTML
<div class="item clearfix" rel=":Collection.id">
	<h3>:Collection.name</h3>
	<p>:Collection.description</p>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" rel=":Collection.id">
	<h3>:Collection.name - :Collection.description</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" rel=":Collection.id">
	<p>:Collection.name - :Collection.description</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
