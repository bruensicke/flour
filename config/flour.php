<?php



/**
 * Contents
 */

//status
Configure::write('Flour.Content.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//types
Configure::write('Flour.Content.types', array(
	'pattern' => 'contents/type_:type',
	'default' => 'article',
	'options' => array(
		'article' => __('Article', true),
		'blog' => __('Blogpost', true),
	),
));



/**
 * Configuration Options
 */

//status
Configure::write('Flour.Configuration.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//autoload
Configure::write('Flour.Configuration.autoload', array(
	'default' => 1,
	'options' => array(
		'0' => __('never', true),
		'1' => __('for me', true),
		// '2' => __('for specific user', true),
		'3' => __('for my group', true),
		// '4' => __('for specific group', true),
		'9' => __('always', true),
	),
));

//types
Configure::write('Flour.Configuration.types', array(
	'pattern' => 'configurations/type_:type',
	'default' => 'text',
	'options' => array(
		'bool' => __('Switch', true),
		'text' => __('Textfield', true),
		'array' => __('Array', true),
		// 'collection' => __('Collection', true), //TODO: later
	),
));

//categories
Configure::write('Flour.Configuration.categories', array(
	'default' => 'Flour',
	'options' => array(
		'App' => __('App', true),
		'Flour' => __('Flour', true),
	),
));

