<?php
echo $this->Form->hidden('Content.model', array('value' => 'Flour.ContentObject'));
echo $this->Form->hidden('Content.foreign_id');

echo $this->Form->input('ContentObject.title');

echo $this->Form->input('ContentObject.excerpt', array(
	'type' => 'textarea',
	'class' => 'elastic',
));

echo $this->Form->input('ContentObject.body', array(
	'type' => 'textarea',
	'class' => 'elastic',
));
