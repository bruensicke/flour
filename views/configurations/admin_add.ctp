<?php
$this->title = __('Configurations', true);
$this->description = __('Add new Configuration.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: Configure::read('Flour.Configuration.types.default');

echo $this->Form->create('Configuration', array('action' => $this->action));
echo $this->element('flour/content_start');

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Configuration Details.', true),
				'class' => 'box',
				'content' => array(
					$this->element('configurations/form_head'),
					$this->Html->div('type_details', $this->element(
						String::insert(Configure::read('Flour.Configuration.types.pattern'), 
						array('type' => $type))
					)),
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

