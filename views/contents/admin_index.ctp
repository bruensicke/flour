<?php
$this->title = __('Contents', true);
$this->description = __('This is your content library.', true);

if(!empty($this->passedArgs['type']))
{
	$name = Configure::read('App.Content.types.options.'.$this->passedArgs['type']);
	$this->title = Inflector::pluralize($name);
	$this->description = Configure::read('App.Content.types.descriptions.'.$this->passedArgs['type']);

	$this->Nav->add('Primary', array(
		'name' => String::insert( __('Create :type', true), array('type' => $name)),
		'url' => array('controller' => 'contents', 'action' => 'add', 'type' => $this->passedArgs['type']),
		'type' => 'link',
		'ico' => 'add',
	));

} else {

	$types = Configure::read('App.Content.types');
	$children = array();
	foreach ($types['options'] as $type => $name)
	{
		$description = $types['descriptions'][$type];
		$children[] = array(
			// 'name' => String::insert(':name <span class="description">:description</span>', compact('name', 'description')),
			'name' => $name,
			'url' => array('controller' => 'contents', 'action' => 'add', 'type' => $type),
		);
	}

	$this->Nav->add('Primary', array(
		'name' => __('Create Content', true),
		'url' => array('controller' => 'contents', 'action' => 'add'),
		'type' => 'link',
		'ico' => 'add',
		'children' => $children,
	));

}

// echo $this->element('flour/content_start');
$Item = ClassRegistry::init('Item');
// $this->data = $Item->find('all');
// $shops = $Item->Shop->find('list');

$items = array();

$items[] = array(
	'type' => 'iterator',
	'target' => 'a',
	'data' => array(
		// 'caption' => $this->title,
		'element' => 'contents/item',
		'group' => 'Content.type',
		// 'group' => 'Item.shop_id',
		'paging' => false,
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

$items[] = array(
	'type' => 'html',
	'target' => 'b',
	'data' => array(
		'content' => $this->Html->nestedList(Configure::read('Flour.Content.types.options')),
		// 'before' => '<span>',
	),
);

// echo $this->Widget->row($items, 'full');
echo $this->Widget->row($items, 'col3-flex');

$viewURL = Router::url(array('controller' => 'contents', 'action' => 'view', 'admin' => true), true);
$editURL = Router::url(array('controller' => 'contents', 'action' => 'edit', 'admin' => true), true);
$script = <<<HTML
$(function()
{
	$('div.item').live('click', function(){
		var id = $(this).attr('data-id');
		$('div.item').removeClass('active');
		$(this).addClass('active');
		$('.content_right').load('$viewURL/'+id);
	});
	$('a.inline').live('click', function(){
		var href = $(this).attr('href');
		$('.content_right').load(href);
		return false;
	});
});
HTML;

echo $this->Html->scriptBlock($script);

//just an example for activites, on specific types
// echo $this->Widget->type('activities', array(
// 	'types' => array(
// 		'content_object_created',
// 		'content_object_updated',
// 		'content_object_deleted',
// 	)
// ));

// echo $this->element('flour/content_stop');

?>
			<!-- <div class="content_pane content_left">left</div>
			<div class="content_pane content_center">center</div>
			<div class="content_pane content_right">right</div> -->
