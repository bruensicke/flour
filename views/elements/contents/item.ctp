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

$summary = <<<HTML
<div class="summary clearfix" data-id=":Content.id">
	<header class="meta">
		<nav>
			<ul>
				<li>:edit</li>
			</ul>
		</nav>
	</header>
	<h2>:Content.name</h2>
	<p>:Content.description</p>
	<fieldset>
		<dl>
			<dt>Type</dt>
			<dd>:Content.type</dd>
			<dt>Model</dt>
			<dd>:Content.model</dd>
			<dt>Status</dt>
			<dd>:Content.status_text</dd>
			<dt>Created</dt>
			<dd>:Content.created</dd>
		</dl>
	</fieldset>
</div>
HTML;

$template = (empty($template))
	? 'default'
	: $template;

$row['Content']['status_text'] = Configure::read('Flour.Content.status.options.'.$row['Content']['status']);

$row['edit'] = $this->Html->link( __('edit', true), array('controller' => 'contents', 'action' => 'edit', 'admin' => true, $row['Content']['id']), array('class' => 'inline'));

echo String::insert($$template, Set::flatten($row));
