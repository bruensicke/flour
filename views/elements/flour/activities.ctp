<?php
$Activity = ClassRegistry::init('Flour.Activity');
$activity_items = $Activity->get();

echo $this->element('flour/iterator', array(
	'caption' => __('Activity Log', true),
	'data' => $activity_items,
	'element' => 'activities/item',
	'paging' => false,
));
