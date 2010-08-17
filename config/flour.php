<?php

//Contents
Configure::write('Flour.Content.pattern', 'contents/type_:type');
Configure::write('Flour.Content.defaultType', 'article');
Configure::write('Flour.Content.types', array(
	'article' => __('Article', true),
	'blog' => __('Blogpost', true),
));
Configure::write('Flour.Content.defaultStatus', 1);
Configure::write('Flour.Content.status', array(
	'0' => __('offline', true),
	'1' => __('draft', true),
	'2' => __('public', true),
));

//Configurations
Configure::write('Flour.Configuration.pattern', 'configurations/type_:type');
Configure::write('Flour.Configuration.defaultCategory', 'Flour');
Configure::write('Flour.Configuration.categories', array(
	'App' => __('App', true),
	'Flour' => __('Flour', true),
));
Configure::write('Flour.Configuration.defaultType', 'text');
Configure::write('Flour.Configuration.types', array(
	'text' => __('Textfield', true),
	'array' => __('Array', true),
	// 'collection' => __('Collection', true), //TODO: later
));
Configure::write('Flour.Configuration.defaultStatus', 1);
Configure::write('Flour.Configuration.status', array(
	'0' => __('offline', true),
	'1' => __('online', true),
));
