<?php
$this->title = __('Contents', true);
$this->description = __('Add new Content.', true);

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->Form->hidden('Content.id');

echo $this->element('flour/content_start');

	echo $this->Html->div('span-24', $this->element('flour/editions'));

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Content Details.', true),
				'class' => 'box type_details',
				'content' => $this->element('contents/form_type', array('type' => $type)),
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control Content', true),
				'class' => 'box',
				'content' => $this->element('contents/form'),
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');
