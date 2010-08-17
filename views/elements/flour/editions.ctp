<?php
if(empty($editions))
{
	return;
}
$model = (isset($model))
	? $model
	: Inflector::singularize(Inflector::humanize($this->params['controller']));

$items = array();
foreach ($editions as $index => $item)
{
	$items[] = $this->Html->link($item[$model]['name'], array('action' => 'edit', $item[$model]['id']));
	// debug($item);
}
echo $this->Html->tag('h3', __('Editions', true));
echo $this->Html->nestedList($items, array('class' => 'editions'));
