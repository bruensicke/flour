<?php
$path = dirname(dirname(__FILE__));
define('FLOUR', $path);

@include_once(FLOUR.'/config/env.php');
@include_once(FLOUR.'/config/app.php');
@include_once(FLOUR.'/config/flour.php');
@include_once(dirname(__FILE__).'/connection.php'); //makes sure, there is a 'flour' connection
@include_once(dirname(__FILE__).'/flour_event.php'); //load EventClass
