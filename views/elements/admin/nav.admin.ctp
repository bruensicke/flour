<?php

$this->Nav->add('Admin', array(
	'name' => __('Contents', true),
	'url' => array('controller' => 'contents', 'action' => 'index', 'admin' => true),
));

$this->Nav->add('Admin', array(
	'name' => __('Collections', true),
	'url' => array('controller' => 'collections', 'action' => 'index', 'admin' => true),
));

$this->Nav->add('Admin', array(
	'name' => __('Configurations', true),
	'url' => array('controller' => 'configurations', 'action' => 'index', 'admin' => true),
));

echo $this->Html->tag('nav', $this->Nav->show('Admin'), array('class' => 'nav_admin'));
