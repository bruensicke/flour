<?php
$this->title = __('Navigations', true);
$this->description = __('These are all your configurations.', true);

echo $this->Html->link(__('Add', true), array('controller' => 'navigations', 'action' => 'add'));

echo $this->element('flour/content_start');

echo $this->element('flour/iterator', array(
	'element' => 'navigations/item',
	'search' => true,
));


// $items = array();
// $items[] = array(
// 	'type' => 'box',
// 	'target' => 'b',
// 	'data' => array(
// 		'caption' => __('Control Widget', true),
// 		'content' => $this->element('widgets/form'),
// 	),
// );
// echo $this->Widget->row($items, 'sidebar_left');




echo $this->element('flour/content_stop');
