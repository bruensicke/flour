<?php
$i = (isset($i))
	? $i
	: 0;

echo $this->Form->hidden("CollectionItem.$i.type", array('value' => 'bool'));
echo $this->Form->hidden("CollectionItem.$i.sequence", array('value' => $i));

echo $this->Html->div('left control');
	echo $this->Html->div('delrow', '');
echo $this->Html->tag('/div'); //div.left

echo $this->Html->div('left key');
	echo $this->Form->input("CollectionItem.$i.name", array(
		'class' => '',
		'label' => false,
	));
echo $this->Html->tag('/div'); //div.left

echo $this->Html->div('right control');
	echo $this->Html->div('handle', '');
echo $this->Html->tag('/div'); //div.right

echo $this->Html->div('right value');
	echo $this->Form->input("CollectionItem.$i.val", array(
		'type' => 'checkbox',
		'label' => false,
	));
echo $this->Html->tag('/div'); //div.right

