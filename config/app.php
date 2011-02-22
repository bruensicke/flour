<?php

$rootArray = pathinfo(ROOT);
$default_name = (APP_DIR != 'app')
	? APP_DIR
	: $rootArray['basename'];

$app_defaults = array(
	'name' => $default_name,
	'version' => '0.1',
	'title' => ':title - :name (:version)',
	'keywords' => '',
	'description' => '',
	'styles' => array('theme', 'app'),
	'scripts' => array('app', 'theme'),
);

$app_settings = Configure::read('App.Settings');

$settings = (!empty($app_settings))
	? array_merge($app_defaults, $app_settings)
	: $app_defaults;

Configure::write('App.Settings', $settings);
