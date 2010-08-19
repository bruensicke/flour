<?php
$this->title = __('Configurations', true);
$this->description = __('Add new Configuration.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Configuration.defaultType');

echo $this->Form->create('Configuration', array('action' => $this->action));
echo $this->element('flour/content_start');

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Configuration Details.', true),
				'class' => 'box type_details',
				'content' => $this->element(
					String::insert(Configure::read('Flour.Configuration.pattern'), 
					array('type' => $type))
				),
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control Configuration', true),
				'content' => $this->element('configurations/form'),
				'class' => 'box',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');

$url = Router::url(array('action' => 'type'), true);
echo $this->Html->scriptBlock("$().ready(function(){
	$('.auto_switch_type').change(function() {
		type = $('.auto_switch_type').attr('value');
		if(type=='') {
			$('div.type_details').html('');
		} else {
			$.get('$url/type:' + type, function(data)
			{
				$('div.type_details').html(data);
			});
		}
	});
});");
