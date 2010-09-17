<?php

$nav = array();

$link = array('controller' => 'docs', 'action' => 'index');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Documentation', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'install');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Installation', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'contents');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Documentation / Contents', true), $link, array('class' => $active));

$link = array('controller' => 'docs', 'action' => 'widgets');
$active = ($this->here == Router::url($link)) ? 'active' : '';
$nav[] = $this->Html->link( __('Documentation / Widgets', true), $link, array('class' => $active));

echo $this->Html->tag('h1', __('This documentation is obviously work-in-progress', true));
echo $this->Html->nestedList($nav);

