<?php
$title = isset($title)
	? $title
	: null;

$excerpt = isset($excerpt)
	? $excerpt
	: null;

$body = isset($body)
	? $body
	: null;

echo $this->Form->hidden('Content.model', array('value' => 'Flour.ContentObject'));
echo $this->Form->hidden('Content.foreign_id');

echo $this->Form->input('ContentObject.title', array(
	'type' => 'text',
	'value' => $title,
));

echo $this->Form->input('ContentObject.excerpt', array(
	'type' => 'textarea',
	'class' => 'elastic',
	'value' => $excerpt,
));

echo $this->Form->input('ContentObject.body', array(
	'type' => 'textarea',
	'class' => 'elastic',
	'value' => $body,
));
