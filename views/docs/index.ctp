<?php
$path = Router::url(array('controller' => 'docs', 'action' => 'index', '/'), true).'/';
echo str_replace('href="/', 'href="'.$path, $this->Markdown->transform($content));
