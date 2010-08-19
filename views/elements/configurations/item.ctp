<?php
$default = <<<HTML
<div class="item clearfix" rel=":Configuration.id">
	<div class="span-12">
		<h3>:Configuration.name</h3>
		<p><small>:Configuration.description</small></p>
	</div>
	<div class="span-12 last">
		<h4>:Configuration.type</h4>
		<p>:Configuration.val</p>
	</div>
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

$row['Configuration']['val'] = (isset($row['Configuration']['val']) && is_array($row['Configuration']['val']))
	? String::insert( __('<em>:num entries</em>', true), array('num' => count($row['Configuration']['val'])))
	: $row['Configuration']['val'];

echo String::insert($$template, Set::flatten($row));
