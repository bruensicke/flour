<?php



/**
 * Contents
 */
Configure::write('Flour.Content.pattern', 'contents/type_:type');

//types
Configure::write('Flour.Content.defaultType', 'article');
Configure::write('Flour.Content.types', array(
	'article' => __('Article', true),
	'blog' => __('Blogpost', true),
));

//status
Configure::write('Flour.Content.defaultStatus', 1);
Configure::write('Flour.Content.status', array(
	'0' => __('offline', true),
	'1' => __('draft', true),
	'2' => __('public', true),
));



/**
 * Configuration Options
 */
Configure::write('Flour.Configuration.pattern', 'configurations/type_:type');

//categories
Configure::write('Flour.Configuration.defaultCategory', 'Flour');
Configure::write('Flour.Configuration.categories', array(
	'App' => __('App', true),
	'Flour' => __('Flour', true),
));

//types
Configure::write('Flour.Configuration.defaultType', 'text');
Configure::write('Flour.Configuration.types', array(
	'text' => __('Textfield', true),
	'array' => __('Array', true),
	// 'collection' => __('Collection', true), //TODO: later
));

//status
Configure::write('Flour.Configuration.defaultStatus', 1);
Configure::write('Flour.Configuration.status', array(
	'0' => __('offline', true),
	'1' => __('online', true),
));

//autoload
Configure::write('Flour.Configuration.defaultAutoload', 0);
Configure::write('Flour.Configuration.autoload', array(
	'0' => __('never', true),
	'1' => __('just for me', true),
	// '2' => __('for specific user', true),
	'3' => __('for my group', true),
	// '4' => __('for specific group', true),
	'9' => __('always', true),
));
