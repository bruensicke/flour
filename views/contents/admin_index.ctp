<?php
$this->title = __('Contents', true);
$this->description = __('This is your content library.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'contents', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'caption' => __('Contents', true),
	'element' => 'contents/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));


//example of showing activites, restricted to certain types.
echo $this->element('flour/activities', array('type' => array(
	'content_object_created',
	'content_object_updated',
	'content_object_deleted',
)));

echo $this->element('flour/content_stop');
