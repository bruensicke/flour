<?php
$this->title = __('Widgets', true);
$this->description = __('These are all your widgets.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'widgets', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'widgets/item',
	'group' => 'Widget.type',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');

$style = <<<HTML
div.group { background: red; }
div.group_items {  }
HTML;


echo $this->Html->tag('style', $style);