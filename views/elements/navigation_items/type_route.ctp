<?php
//TODO: does it work?
$i = (isset($i))
	? $i
	: 0;

echo $this->Form->hidden("NavigationItem.$i.id");
echo $this->Form->hidden("NavigationItem.$i.type", array('value' => 'route'));

echo $this->Html->div('left control', $this->Html->div('delrow', ''));

echo $this->Html->div('left key');
	echo $this->Form->input("NavigationItem.$i.name", array(
		'class' => '',
		'label' => false,
	));
echo $this->Html->tag('/div'); //div.left

echo $this->Html->div('right control', $this->Html->div('handle', ''));

echo $this->Html->div('right value');
	echo $this->Form->input("NavigationItem.$i.link", array(
		'type' => 'text',
		'label' => false,
	));
echo $this->Html->tag('/div'); //div.right

