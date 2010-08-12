<?php
if(empty($editions))
{
	return;
}

$items = array();
foreach ($editions as $index => $item)
{
	$items[] = $this->Html->link($item['Content']['name'], array('action' => 'edit', $item['Content']['id']));
	// debug($item);
}

echo $this->Html->nestedList($items, array('class' => 'editions'));
