<?php
$this->title = __('Widget Collections', true);
$this->description = __('These are all your widget collections.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'widget_collections', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'widget_collections/item',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');

