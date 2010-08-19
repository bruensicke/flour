<?php

echo $this->Form->hidden('Configuration.val'); //will be js-filled with Temp.key/val

$size = (isset($this->data['Configuration']['val']) && !empty($this->data['Configuration']['val']))
	? count($this->data['Configuration']['val'])
	: 1;

for ($i = 0; $i < $size; $i++)
{
	echo $this->Html->div('row');
		echo $this->Html->link( __('[+]', true), '#', array('class' => 'add'));
		echo $this->Html->link( __('[-]', true), '#', array('class' => 'del'));
		echo $this->Form->input("Configuration.val.$i.key", array(
			'label' => __('Key', true),
			'type' => 'text',
			'class' => 'left',
		));

		echo $this->Form->input("Configuration.val.$i.val", array(
			'label' => __('Value', true),
			'type' => 'text',
			'class' => 'right',
		));
	echo $this->Html->tag('/div'); //div.row
}

echo $this->Html->scriptBlock("$().ready(function(){
	$('a.del').live('click', function(){
		$(this).parent().remove();
		renumber();
		return false;
	});
	$('a.add').live('click', function(){
		var clon = $(this).parent().clone().insertAfter($(this).parent());
		renumber();
		return false;
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

