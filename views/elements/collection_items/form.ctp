<?php
$types = Configure::read('Flour.CollectionItem.types.options');

$size = (isset($this->data['CollectionItem']) && !empty($this->data['CollectionItem']))
	? count($this->data['CollectionItem'])
	: 1;

echo $this->Html->div('clearfix');
	echo $this->Html->div('left control', '&nbsp;');
	echo $this->Html->div('left', __('Key', true));
	echo $this->Html->div('left', __('Value', true));
echo $this->Html->tag('/div'); //div.row

echo $this->Html->div('rows items');
	for ($i = 0; $i < $size; $i++)
	{
		$type = (isset($this->data['CollectionItem'][$i]['type']))
			? $this->data['CollectionItem'][$i]['type']
			: Configure::read('Flour.CollectionItem.types.default');

		echo $this->Html->div('row clearfix type_'.$type,
			$this->element(
				String::insert(
					Configure::read('Flour.CollectionItem.types.pattern'), 
					array('type' => $type)
				),
				array('i' => $i)
			)
		);
	}
echo $this->Html->tag('/div'); //div.rows

echo $this->Html->div('clearfix');
	echo $this->Html->div('left');
		echo $this->Form->input("CollectionRow.type", array(
			'label' => false,
			'type' => 'select',
			'class' => 'row_type_select',
			'options' => Configure::read('Flour.CollectionItem.types.options'),
			'default' => Configure::read('Flour.CollectionItem.types.default'),
		));
	echo $this->Html->tag('/div'); //div.left
	echo $this->Html->div('left', $this->Html->div('addrow', ''));
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

$style .= ".add, .addrow { height: 20px; margin: 2px; background: url('{$this->base}/flour/img/ico_plus.png') no-repeat center; }";
$style .= ".del, .delrow { height: 20px; margin: 1px; background: url('{$this->base}/flour/img/ico_minus.png') no-repeat center; }";
$style .= ".handle { cursor: move; height: 44px; border-left: 1px solid #ddd; background: url('{$this->base}/flour/img/ico_move.png') no-repeat center; }";

echo $this->Html->tag('style', $style);
echo $this->Html->scriptBlock("$().ready(function(){
	$('.hideme').hide();
	$('.rows').sortable({
		axis: 'y',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		update: function(){ renumber(); }
	});
	$('.delrow').live('click', function(){
		var size = $('div.row').length;
		if(size == 1) return;
		var agree = confirm('Are you sure?');
		if(!agree) return;
		$(this).parents('div.row').remove();
		renumber();
	});
	$('.addrow').live('click', function(){
		var type = $('.row_type_select').val();
		var clon = $('.hideme > .row.type_' + type).clone().appendTo('.rows.items');
		renumber();
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
});");

