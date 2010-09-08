<?php
$default = <<<HTML
<div class="item clearfix" rel=":Activity.id">
	<p>:Activity.type</p>
	<h3>:Activity.message</h3>
</div>
HTML;

echo String::insert($$template, Set::flatten($row));
