<?php
$type = (isset($type))
	? $type
	: null;

$limit = (isset($limit))
	? $limit
	: 50;

$order = (isset($order))
	? $order
	: null;

$order = (isset($order))
	? $order
	: null;

$group = (isset($group))
	? $group
	: null;

$Activity = ClassRegistry::init('Flour.Activity');
$activity_items = $Activity->get($limit, compact('type', 'order'));

echo $this->element('flour/iterator', array(
	'caption' => __('Activity Log', true),
	'data' => $activity_items,
	'element' => 'activities/item',
	'group' => $group,
	'paging' => false,
));
