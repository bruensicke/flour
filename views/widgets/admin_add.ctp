<?php
$this->title = __('Widgets', true);
$this->description = __('Add new Widget.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Widget.types.default');

echo $this->Form->create('Widget', array('action' => $this->action));
echo $this->element('flour/content_start');

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Widget Details.', true),
				'class' => 'box type_details',
				'content' => $this->element('widget', array('type' => $type, 'template' => 'admin')),
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control Widget', true),
				'class' => 'box',
				'content' => $this->element('widgets/form'),
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');
