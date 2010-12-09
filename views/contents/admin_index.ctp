<?php
$this->title = __('Contents', true);
$this->description = __('This is your content library.', true);
$add_name = __('Create Content', true);
$add_url = array('controller' => 'contents', 'action' => 'add');

if(isset($this->passedArgs['type']))
{
	//TODO: have default-types
	$this->title = $this->passedArgs['type']; //Configure::read('App.');
	$this->description = ''; //TODO: read description

	$add_name =  String::insert( __('Create :type', true), array('type' => $this->passedArgs['type']));
	$add_url['type'] = $this->passedArgs['type'];
}

$this->Nav->add('Primary', array(
	'name' => $add_name,
	'url' => $add_url,
	'type' => 'link',
	'ico' => 'add',
));

echo $this->element('flour/content_start');

$items = array();

$items[] = array(
	'type' => 'iterator',
	'target' => 'a',
	'data' => array(
		'caption' => __('Contents', true),
		'element' => 'contents/item',
		'search' => true,
		),
);

$items[] = array(
	'type' => 'tags',
	'target' => 'b',
	'data' => array(
		'caption' => __('Tags', true),
		'data' => $tags,
		// 'before' => '<span>',
	),
);

echo $this->Widget->row($items, 'full');

//just an example for activites, on specific types
// echo $this->Widget->type('activities', array(
// 	'types' => array(
// 		'content_object_created',
// 		'content_object_updated',
// 		'content_object_deleted',
// 	)
// ));

echo $this->element('flour/content_stop');
