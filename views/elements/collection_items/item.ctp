<?php
$default = <<<HTML
<div class="item clearfix" rel=":Collection.id">
	<h3>:Collection.name</h3>
	<p>:Collection.description</p>
</div>
HTML;


echo String::insert($$template, Set::flatten($row));
