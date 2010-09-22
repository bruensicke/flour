<?php
$path = dirname(dirname(__FILE__));
define('FLOUR', $path);

@include_once(FLOUR.'/config/env.php');
@include_once(FLOUR.'/config/init.php');
@include_once(FLOUR.'/config/flour.php');

