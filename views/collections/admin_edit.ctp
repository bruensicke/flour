<?php
$this->title = __('Collections', true);
$this->description = __('Add new Collection.', true);

$this->Nav->add('Primary', array(
	'name' => __('Cancel', true),
	'url' => array('controller' => 'collections', 'action' => 'index'),
	'type' => 'link',
	'ico' => 'cross',
	'confirm' => __('Are you sure you want to cancel?', true),
));

$this->Nav->add('Primary', array(
	'name' => __('Save', true),
	'type' => 'button',
	'ico' => 'disk',
	'class' => 'positive',
));

echo $this->Form->create('Collection', array('action' => $this->action));
echo $this->Form->hidden('Collection.id');
echo $this->element('flour/content_start');

	echo $this->Html->div('span-24', $this->element('flour/editions'));

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Collection Details.', true),
				'class' => 'box',
				'content' => $this->element('collection_items/form'),
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control Collection', true),
				'content' => $this->element('collections/form'),
				'class' => 'box',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');

echo $this->element('collection_items/form_templates');