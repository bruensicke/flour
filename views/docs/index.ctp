<?php
$path = Router::url(array('controller' => 'docs', 'action' => 'index', '/'), true).'/';
$content = $this->Markdown->transform($content);
$content = str_replace('</code></pre>', '</pre>', $content);
$content = str_replace('<pre><code>', '<pre lang="php">', $content);
$content = $this->Geshi->highlight($content);
$content = str_replace('href="/', 'href="'.$path, $content);
echo $content;
