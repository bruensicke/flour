<?php
$default = <<<HTML
<div class="item clearfix" rel=":Configuration.id">
	<p>:Configuration.type</p>
	<h3>:Configuration.name</h3>
</div>
HTML;

$short = <<<HTML
<div class="item clearfix" rel=":Configuration.id">
	<h3>:Configuration.type - :Configuration.name</h3>
</div>
HTML;

$list = <<<HTML
<div class="item clearfix" rel=":Configuration.id">
	<p>:Configuration.type - :Configuration.name</p>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
