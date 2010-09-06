<?php



/**
 * Activity
 */

//types
Configure::write('Flour.Activity.types', array(
	'options' => array(
		'object_created' => __('Created object \':name\' [:id]', true),
		'object_updated' => __('Updated object \':name\' [:id]', true),
		'object_deleted' => __('Deleted object \':name\' [:id]', true),
		'user_loggedin' => __('user \':User.name\' logged in via \':User.loginType\'.', true),
		'user_loggedout' => __('user \':User.name\' logged out.', true),
	),
));




/**
 * Collections / CollectionItems
 */

//status
Configure::write('Flour.Collection.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//types
Configure::write('Flour.CollectionItem.types', array(
	'pattern' => 'collection_items/type_:type',
	'default' => 'text',
	'options' => array(
		'bool' => __('Switch', true),
		'text' => __('Textfield', true), // ONE key => value pair
		'array' => __('Array', true), // nested array with unlimited amount of key => value pairs
		// 'collection' => __('Collection', true), // a referenced collection with all its data under a key.
	),
));




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
		// 'array' => __('Array', true), //TODO: fix js on this one.
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




/**
 * Widgets
 */

//status
Configure::write('Flour.Widget.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//types
Configure::write('Flour.Widget.types', array(
	'pattern' => 'widgets/type_:type',
	'default' => 'html',
	'options' => array(
		'html' => __('Text', true),
		'element' => __('Element', true),
		'box' => __('Box', true),
	),
));

//templates
Configure::write('Flour.Widget.templates', array(
	'pattern' => 'widgets/template_:template',
	'markup' => ':header<div class="widget type_:type :class">:content</div>:footer',
	'default' => 'full',
	'options' => array(
		'full' => __('Full width column', true),
		'half' => __('2 half-width columns', true),
	),
));




