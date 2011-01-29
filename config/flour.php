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
		'content_object_created' => __('Created content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
		'content_object_updated' => __('Updated content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
		'content_object_deleted' => __('Deleted content object \':Content.name\' of type \':Content.type\' [:Content.id]', true),
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
		// 'array' => __('Array', true), // nested array with unlimited amount of key => value pairs
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
	'descriptions' => array(
		'article' => __('An Article is a defined block of Content with a title, an excerpt and a body.', true),
		'blog' => __('A Blogpost has a title and body-text.', true),
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


/**
 * Navigations / NavigationItems
 */

//status
Configure::write('Flour.Navigation.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//types
Configure::write('Flour.NavigationItem.types', array(
	'pattern' => 'navigation_items/type_:type',
	'default' => 'url',
	'options' => array(
		'url' => __('URL', true), //url, like http://google.de, or /about/team or /feed.rss
		'route' => __('Route', true), //route like: controller:foo,action:bar,etc
	),
));






/**
 * Widgets / WidgetCollections
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
	'element' => 'flour/widgets',
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
));

//templates
Configure::write('Flour.Widget.templates', array(
	'pattern' => 'templates/:template',
	'markup' => '<div class="widget type_:type :class">:content</div>',
	'default' => 'full',
	'options' => array(
		'full' => __('Full width column', true),
		'half' => __('2 half-width columns', true),
	),
));

/* WidgetCollections */

//status
Configure::write('Flour.WidgetCollection.status', array(
	'default' => 1,
	'options' => array(
		'0' => __('offline', true),
		'1' => __('draft', true),
		'2' => __('online', true),
	),
));

//types
Configure::write('Flour.WidgetCollection.types', array(
	'pattern' => 'widget_collections/type_:type',
	'default' => 'stack',
	'options' => array(
		'stack' => __('Stack', true),
		'row' => __('Row', true),
		// 'rows' => __('Rows', true), //come on, this is complex
	),
));




