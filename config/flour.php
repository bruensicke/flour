<?php

//Contents
Configure::write('Flour.Content.pattern', 'contents/type_:type');
Configure::write('Flour.Content.defaultType', 'article');
Configure::write('Flour.Content.types', array(
	'article' => __('Article', true),
	'blog' => __('Blogpost', true),
));

Configure::write('Flour.Content.status', array(
	'0' => __('offline', true),
	'1' => __('draft', true),
	'2' => __('public', true),
));
