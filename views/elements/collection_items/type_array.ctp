<?php
$i = (isset($i))
	? $i
	: 0;

// echo $this->Form->hidden('CollectionItem.val'); //will be js-filled with Temp.key/val
echo $this->Form->hidden("CollectionItem.$i.type", array('value' => 'array'));
echo $this->Form->hidden("CollectionItem.$i.sequence", array('value' => $i));

$size = (isset($this->data['CollectionItem']['val']) && !empty($this->data['CollectionItem']['val']))
	? count($this->data['CollectionItem']['val'])
	: 2;

echo $this->Html->div('clearfix');
	echo $this->Html->div('left control', $this->Html->div('del', ''));
	echo $this->Html->div('left', __('Key', true));
	echo $this->Html->div('left', __('Value', true));
echo $this->Html->tag('/div'); //div.row

echo $this->Html->div('rows');
	for ($i = 0; $i < $size; $i++)
	{
		echo $this->Html->div('row clearfix');

			echo $this->Html->div('left control');
				echo $this->Html->div('add', '');
				echo $this->Html->div('del', '');
			echo $this->Html->tag('/div'); //div.left

			echo $this->Html->div('left key');
				echo $this->Form->input("CollectionItem.val.$i.key", array(
					'label' => false,
					'type' => 'text',
					'class' => 'left',
				));
			echo $this->Html->tag('/div'); //div.left

			echo $this->Html->div('right control');
				echo $this->Html->div('handle', '');
			echo $this->Html->tag('/div'); //div.right

			echo $this->Html->div('right value');
				echo $this->Form->input("CollectionItem.val.$i.val", array(
					'label' => false,
					'type' => 'text',
					'class' => 'right',
				));
			echo $this->Html->tag('/div'); //div.right

		echo $this->Html->tag('/div'); //div.row
	}
echo $this->Html->tag('/div'); //div.rows

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

div.control { width: 44px; }
div.key { width: 40%; }
div.value { width: 40%; }

div.placeholder { padding: 0; background: #ddd; padding-bottom: 1px; }
HTML;

$style .= ".add { height: 20px; margin: 2px; background: url('{$this->base}/flour/img/ico_plus.png') no-repeat center; }";
$style .= ".del { height: 20px; margin: 1px; background: url('{$this->base}/flour/img/ico_minus.png') no-repeat center; }";
$style .= ".handle { cursor: move; height: 44px; border-left: 1px solid #ddd; background: url('{$this->base}/flour/img/ico_move.png') no-repeat center; }";
/*
echo $this->Html->tag('style', $style);
echo $this->Html->scriptBlock("$().ready(function(){
	$('.rows').sortable({
		axis: 'y',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		update: function(){ renumber(); }
	});
	$('.del').live('click', function(){
		var size = $('div.row').length;
		if(size == 1) return;
		var agree = confirm('Are you sure?');
		if(!agree) return;
		$(this).parents('div.row').remove();
		renumber();
	});
	$('.add').live('click', function(){
		var clon = $(this).parents('div.row').clone().insertAfter($(this).parents('div.row'));
		renumber();
	});
	function renumber()
	{
		var i = 0;
		$('.row').each(function(){
			var inputs = $(this).find('input');
			inputs.each(function(){
				var name = $(this).attr('name');
				$(this).attr('name', name.replace(/\[\d+\]/g, '[' + i + ']'));
			});
			i++;
		});
	}
});");
*/
