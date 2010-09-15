<?php
$this->title = __('WidgetCollections', true);
$this->description = __('Add new WidgetCollection.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.WidgetCollection.types.default');

echo $this->Form->create('WidgetCollection', array('action' => $this->action));
echo $this->element('flour/content_start');

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter WidgetCollection Details.', true),
				'class' => 'box type_details',
				'content' => $this->element(
					String::insert(
						Configure::read('Flour.WidgetCollection.types.pattern'), 
						array('type' => $type)
					)),
			));

			echo $this->element('flour/box', array(
				'caption' => __('Please choose a Widget.', true),
				'content' => $this->element('widgets/library'),
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control WidgetCollection', true),
				'class' => 'box',
				'content' => $this->element('widget_collections/form'),
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');
