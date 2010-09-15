<?php
echo $this->Html->link('', array('controller' => 'widgets', 'action' => 'library'), array('class' => 'addrow modal'));


echo $this->Html->div('rows items');
if(!empty($this->data))
{

	for ($i = 0; $i < $size; $i++)
	{
		$type = (isset($this->data['WidgetCollectionItem'][$i]['type']))
			? $this->data['WidgetCollectionItem'][$i]['type']
			: Configure::read('Flour.CollectionItem.types.default');

		echo $this->Html->div('row clearfix type_'.$type,
			$this->element(
				String::insert(
					Configure::read('Flour.WidgetCollectionItem.types.pattern'), 
					array('type' => $type)
				),
				array('i' => $i)
			)
		);
	}

}
echo $this->Html->tag('/div'); //div.rows

echo $this->Html->div('item clearfix');
	echo $this->Html->div('left control', '&nbsp;');
	echo $this->Html->div('left');
		echo $this->Form->input("WidgetCollectionItem.0.type", array(
		));
		echo $this->Form->input("WidgetCollectionItem.0.slug", array(
		));
	echo $this->Html->tag('/div'); //div.left
	echo $this->Html->div('left');
		echo $this->Form->input("WidgetCollectionItem.0.data", array(
		));
		echo $this->Form->input("WidgetCollectionItem.0.class", array(
		));
	echo $this->Html->tag('/div'); //div.left

echo $this->Html->div('right control');
	echo $this->Html->div('handle', '');
echo $this->Html->tag('/div'); //div.right

echo $this->Html->tag('/div'); //div.row



$style = <<<HTML
div.clearfix,
div.rows,
div.row,
div.handle,
div.control,
div.key,
div.add,
div.del,
div.value { padding: 0; }
div.rows div.input { padding: 6px; }
div.rows { border: 1px solid #ddd; border-bottom: 0; }
div.row { border-bottom: 1px solid #ddd; }
div.left { float: left; width: 40%; }
div.right { float: right; width: 40%; }

div.type_array div.rows { border-color: #eee; }

div.control { width: 44px; }
div.key { width: 40%; }
div.value { width: 40%; }

div.placeholder { padding: 0; background: #ddd; padding-bottom: 1px; }
HTML;

$style .= ".add, .addrow { display: block; height: 20px; min-width: 20px; margin: 0 2px; background: #e5e5e5 url('{$this->base}/flour/img/ico_plus.png') no-repeat center; cursor: pointer; }";
$style .= ".del, .delrow { height: 20px; min-width: 20px; margin: 21px; background: url('{$this->base}/flour/img/ico_minus.png') no-repeat center; }";
$style .= ".handle { cursor: move; height: 44px; border-left: 1px solid #ddd; background: url('{$this->base}/flour/img/ico_move.png') no-repeat center; }";

echo $this->Html->tag('style', $style);
echo $this->Html->scriptBlock("$().ready(function(){

	$('.add').live('click', function(){ 
		var type = $(this).attr('rel');
		// var uuidRegex = /^((?-i:0x)?[A-Fa-f0-9]{32}| [A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}| \{[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}\})$/mi;
		// var result = uuidRegex.test(type);
		console.log(type);

	});

	$('.rows').sortable({
		axis: 'y',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		update: function(){ renumber(); }
	});

	function renumber()
	{
		var i = 0;
		$('div.rows.items div.row').each(function(){
			var inputs = $(this).find('input');
			inputs.each(function(){
				var name = $(this).attr('name');
				$(this).attr('name', name.replace(/\[\d+\]/g, '[' + i + ']'));
			});
			$(this).find('input[name*=\'sequence\']').setValue(i);
			i++;
		});
	}


	// $('#addWidget').dialog({
	// 	autoOpen: false
	// });
		
});");

// echo $this->element('widgets/library');
echo $this->element('flour/modal');
