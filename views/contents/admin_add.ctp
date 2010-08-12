<?php
$this->title = __('Contents', true);
$this->description = __('Add new Content.', true);

//switch type on named-param
$type = (isset($this->passedArgs['type']))
	? $this->passedArgs['type']
	: 'article';

echo $this->Form->create('Content', array('action' => $this->action));
echo $this->element('flour/content_start');

		echo $this->Html->div('span-14');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Enter Content Details.', true),
				'content' => $this->element('contents/type_'.$type),
				'class' => 'box',
			));

		echo $this->Html->tag('/div'); //div.span-14
		echo $this->Html->div('span-10 last');

			//TODO: use panel-element instead of box (must be created before :)
			echo $this->element('flour/box', array(
				'caption' => __('Control Content', true),
				'content' => $this->element('contents/form'),
				'class' => 'box',
			));

		echo $this->Html->tag('/div'); //div.span-10 last

echo $this->element('flour/content_stop');
echo $form->end('Ok');

?>