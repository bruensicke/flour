<?php
$this->title = __('Contents', true);
$this->description = __('This is your content library.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'contents', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'contents/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');
