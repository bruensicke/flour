<?php
$types = Configure::read('Flour.CollectionItem.types.options');

echo $this->Html->div('hideme');
foreach($types as $type => $name)
{
	echo $this->Html->div('row clearfix type_'.$type, $this->element(
		String::insert(Configure::read('Flour.CollectionItem.types.pattern'), 
		array('type' => $type))
	));
}
echo $this->Html->tag('/div'); //div.hideme
