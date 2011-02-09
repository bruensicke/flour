<?php

$nav = array();

$link = array('controller' => 'docs', 'action' => 'index');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Documentation', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'index', 'install');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Installation', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'index', 'contents');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Contents', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'index', 'widgets');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Widgets', true), $link, array('class' => $active));

echo $this->Html->nestedList($nav);

