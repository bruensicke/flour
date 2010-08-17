<?php
$this->title = __('Configurations', true);
$this->description = __('Add new Configuration.', true);

echo $this->Form->create('Configuration', array('action' => $this->action));
echo $this->Form->hidden('Configuration.id');

echo $this->element('flour/content_start');

	echo $this->Html->div('span-24', $this->element('flour/editions'));

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Configuration Details.', true),
				'content' => $this->element('configurations/type_'.$type),
				'class' => 'box',
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

debug(Configure::read('Flour'));