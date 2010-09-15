<?php
$tabs = array(
	'widgets' => __('Widget Library', true),
	'widget_types' => __('Widget Types', true),
);

echo $this->Html->tag('ul', null, array('class' => 'tabs'));
foreach($tabs as $tab => $name)
{
	echo $this->Html->tag('li', $name, array('rel' => $tab));
}
echo $this->Html->tag('/ul');

echo $this->element('flour/iterator', array(
	'element' => 'widgets/item',
	'class' => 'widgets box tab_content clearfix',
	'data' => $this->Widget->find(),
	// 'group' => 'Widget.type',
	'row_options' => array('template' => 'library_item'),
));

echo $this->element('flour/iterator', array(
	'element' => 'widgets/item',
	'class' => 'widget_types box tab_content clearfix',
	'data' => $this->Widget->explain(),
	'row_options' => array('template' => 'library_types'),
));

echo $this->Html->scriptBlock("$().ready(function(){
	$('div.box.widget_types').hide();
	$('ul.tabs li:first').addClass('active');
	$('ul.tabs li').bind('click', function()
	{ 
		$('ul.tabs li').removeClass('active');
		var type = $(this).addClass('active').attr('rel');
		$('div.box.widgets, div.box.widget_types').hide();
		$('div.box.' + type).show(); 
	});
	
		
});");

echo <<<HTML
<style>
ul.tabs { list-style: none; margin: 0; background: #F2F2F2; }
ul.tabs li { cursor: pointer; margin: 0; display: inline-block; padding: 10px; border-right: 1px solid #ccc; }
ul.tabs li.active { background: #fff; }
div.tab_content { background: #fff; }
div.widgets {  }
div.widget_types {  }
</style>
HTML;
