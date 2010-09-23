<?php
$types = Configure::read('Flour.NavigationItem.types.options');

echo $this->Html->div('hideme');
foreach($types as $type => $name)
{
	echo $this->Html->div('row clearfix type_'.$type, $this->element(
		String::insert(Configure::read('Flour.NavigationItem.types.pattern'), 
		array('type' => $type))
	));
}
echo $this->Html->tag('/div'); //div.hideme
