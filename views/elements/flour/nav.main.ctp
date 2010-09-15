<?php

$nav = array();

$link = array('controller' => 'contents', 'action' => 'index');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Contents', true), $link, array('class' => $active));

$link = array('controller' => 'widgets', 'action' => 'index');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Widgets', true), $link, array('class' => $active));

$link = array('controller' => 'widget_collections', 'action' => 'index');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Widget Collections', true), $link, array('class' => $active));

$link = array('controller' => 'collections', 'action' => 'index');
$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
$nav[] = $this->Html->link( __('Collections', true), $link, array('class' => $active));

$link = array('controller' => 'configurations', 'action' => 'index');
$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
$nav[] = $this->Html->link( __('Configurations', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'index');
$active = (stristr($this->here, Router::url($link))) ? 'active' : '';
$nav[] = $this->Html->link( __('Docs', true), $link, array('class' => $active));

echo $this->Html->nestedList($nav);

