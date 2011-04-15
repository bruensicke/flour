<?php



/**
 * Activity
 */
Configure::write('Flour.Activity', array(
	'types' => array(
		'options' => array(
			'object_created' => __('Created object \':name\' [:id]', true),
			'object_updated' => __('Updated object \':name\' [:id]', true),
			'object_deleted' => __('Deleted object \':name\' [:id]', true),
			'content_object_created' => __('Created content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
			'content_object_updated' => __('Updated content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
			'content_object_deleted' => __('Deleted content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
			'user_loggedin' => __('user \':User.name\' logged in via \':User.loginType\'.', true),
			'user_loggedout' => __('user \':User.name\' logged out.', true),
		),
	),
	'subscribers' => array(), //can be filled with subscriptions
));


/**
 * Connections
 */
Configure::write('Flour.Connection', array(
	'types' => array(
		'default' => 'default',
		'options' => array(
			'default' => array(
				'prefix' => 'flour_',
			),
			'sqlite' => array(
				'driver' => 'Flour.DboSqlite3',
				'database' => FLOUR.'/config/flour.sqlite',
			),
		),
	),
));


/**
 * Collections
 */
Configure::write('Flour.Collection', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
));


/**
 * CollectionItems
 */
Configure::write('Flour.CollectionItem', array(
	'types' => array(
		'pattern' => 'collection_items/type_:type',
		'default' => 'text',
		'options' => array(
			'bool' => __('Switch', true), // bool - 0 / 1
			'text' => __('Textfield', true), // ONE key => value pair
			// 'array' => __('Array', true), // nested array with unlimited amount of key => value pairs
			// 'collection' => __('Collection', true), // a referenced collection with all its data under a key.
		),
	),
));


/**
 * Contents
 */
Configure::write('Flour.Content', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
	'types' => array(
		'pattern' => 'contents/type_:type',
		'default' => 'article',
		'options' => array(
			'article' => __('Article', true),
			'blog' => __('Blogpost', true),
		),
		'descriptions' => array(
			'article' => __('An Article is a defined block of Content with a title, an excerpt and a body.', true),
			'blog' => __('A Blogpost has a title and body-text.', true),
		),
	),
));


/**
 * Configuration
 */
Configure::write('Flour.Configuration', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
	'types' => array(
		'pattern' => 'configurations/type_:type',
		'default' => 'text',
		'options' => array(
			'bool' => __('Switch', true),
			'text' => __('Textfield', true),
			'array' => __('Array', true),
			// 'collection' => __('Collection', true), //TODO: later
		),
	),
	'categories' => array(
		'default' => 'Flour',
		'options' => array(
			'App' => __('App', true),
			'Flour' => __('Flour', true),
		),
	),
	'autoload' => array(
		'default' => 1,
		'options' => array(
			'0' => __('never', true),
			'1' => __('for me', true),
			// '2' => __('for specific user', true),
			'3' => __('for my group', true),
			// '4' => __('for specific group', true),
			'9' => __('always', true),
		),
	),
));


/**
 * Navigations
 */
Configure::write('Flour.Navigation', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
));


/**
 * NavigationItems
 */
Configure::write('Flour.NavigationItem', array(
	'types' => array(
		'pattern' => 'navigation_items/type_:type',
		'default' => 'url',
		'options' => array(
			'url' => __('URL', true), //url, like http://google.de, or /about/team or /feed.rss
			'route' => __('Route', true), //route like: controller:foo,action:bar,etc
		),
	),
));


/**
 * Widgets / WidgetCollections
 */
Configure::write('Flour.Widget', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
	'types' => array(
		'pattern' => 'widgets/type_:type',
		'element' => 'flour/widget',
		'default' => 'html',
		'options' => array(
			'html' => __('Text', true),
			'element' => __('Element', true),
			'box' => __('Box', true),
		),
		'descriptions' => array(
			'html' => __('Just a plain HTML element, fill in whatever you want to appear.', true),
			'element' => __('Render out an element', true),
			'box' => __('Show a styled box with classes already filled.', true),
		),
	),
	'templates' => array(
		'pattern' => 'templates/:template',
		'markup' => '<div class="widget type_:type :class">:content</div>',
		'default' => 'full',
		'options' => array(
			'full' => __('Full width column', true),
			'half' => __('2 half-width columns', true),
		),
	),
));


/**
 * WidgetCollections // DRAFT!
 */
Configure::write('Flour.WidgetCollection', array(
	'status' => array(
		'default' => 1,
		'options' => array(
			'0' => __('offline', true),
			'1' => __('draft', true),
			'2' => __('online', true),
		),
	),
	'types' => array(
		'pattern' => 'widget_collections/type_:type',
		'default' => 'stack',
		'options' => array(
			'stack' => __('Stack', true),
			'row' => __('Row', true),
			// 'rows' => __('Rows', true), //come on, this is complex
		),
	),
));



