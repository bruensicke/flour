<?php
$this->title = __('Configurations', true);
$this->description = __('These are all your configurations.', true);

$this->Nav->add('Primary', array(
	'name' => __('Create Configuration', true),
	'url' => array('controller' => 'configurations', 'action' => 'add'),
	'type' => 'link',
	'ico' => 'add',
));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'configurations/item',
	'group' => 'Configuration.category',
	'search' => true,
));

echo $this->TagCloud->display($tags, array('before' => '<span>'));

echo $this->element('flour/content_stop');

$style = <<<HTML
div.group { background: silver; }

HTML;


echo $this->Html->tag('style', $style);