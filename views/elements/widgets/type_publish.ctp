<?php
$data = (isset($data))
	? $data
	: array();

$caption = (isset($caption))
	? $caption
	: null;

$caption = (isset($data['caption']))
	? $data['caption']
	: $caption;

$model = (isset($data['model']))
	? $data['model']
	: Inflector::singularize(Inflector::humanize($this->params['controller']));

$status_default = (isset($this->passedArgs['status']))
	? $this->passedArgs['status']
	: Configure::read('Flour.'.$model.'.status.default');

$status_options = (isset($data['status_options']))
	? $data['status_options']
	: Configure::read('Flour.'.$model.'.status.options');


// $type = (isset($this->passedArgs['type']))
// 	? $this->passedArgs['type']
// 	: Configure::read('Flour.Content.types.default');
// 
// $status = (isset($this->passedArgs['status']))
// 	? $this->passedArgs['status']
// 	: Configure::read('Flour.Content.status.default');
// 
// $slug = (isset($this->passedArgs['slug']))
// 	? $this->passedArgs['slug']
// 	: null;
// 
// $name = (isset($this->passedArgs['name']))
// 	? $this->passedArgs['name']
// 	: null;
// 
// $tags = (isset($this->passedArgs['tags']))
// 	? $this->passedArgs['tags']
// 	: null;
$content = $header = $footer = array();

if($template == 'admin')
{
	$content[] = $this->Form->input('Widget.data.model', array(
		'type' => 'text',
	));

} else {

	$content[] = '';

	$content['status'] = array();
	$content['status'][] = String::insert(
		__('Status: <strong>:status</strong> :edit', true), array(
		'status' => $status_options[$status_default],
		'edit' => $this->Html->tag('span', $this->Html->link( __('edit', true), '#status', array('class' => 'section_toggle', 'rel' => 'status'))),
	));
	$content['status'][] = $this->Html->div('section section_status');
	$content['status'][] = $this->Form->input($model.'.status', array(
		'type' => 'select',
		'label' => false,
		'options' => $status_options,
		'default' => $status_default,
	));
	$content['status'][] = $this->Html->link( __('Ok', true), '#', array('class' => 'btn section_cancel', 'rel' => 'status'));
	$content['status'][] = $this->Html->link( __('cancel', true), '#', array('class' => 'section_cancel', 'rel' => 'status'));
	$content['status'][] = $this->Html->tag('/div'); //div.section_status

	$valid_replacements = array(
		'from' => $this->data[$model]['valid_from'],
		'to' => $this->data[$model]['valid_to'],
		'edit' => $this->Html->tag('span', $this->Html->link( __('edit', true), '#valid_range', array('class' => 'section_toggle', 'rel' => 'valid_range'))),
	);
	$valid_range = (isset($this->data[$model]['valid_from']) || isset($this->data[$model]['valid_to']))
		? String::insert( __('Publish on: :from<br />Unpublish: :to :edit', true), $valid_replacements)
		: String::insert( __('Publish <strong>immediately</strong> :edit', true), $valid_replacements);

	$content['valid_range'] = array();
	$content['valid_range'][] = $valid_range;
	$content['valid_range'][] = $this->Html->div('section section_valid_range');
	$content['valid_range'][] = $this->Form->input($model.'.valid_from', array(
		'type' => 'date',
		'label' => __('from', true),
	));
	$content['valid_range'][] = $this->Form->input($model.'.valid_to', array(
		'type' => 'date',
		'label' => __('to', true),
	));
	$content['valid_range'][] = $this->Html->link( __('Ok', true), '#', array('class' => 'btn section_cancel', 'rel' => 'valid_range'));
	$content['valid_range'][] = $this->Html->link( __('cancel', true), '#', array('class' => 'section_cancel', 'rel' => 'valid_range'));
	$content['valid_range'][] = $this->Html->tag('/div'); //div.valid_range

	// $content['visibility'] = String::insert(
	// 	__('Visibility: :visibility', true), array(
	// 	'status' => $status_options[$status_default]
	// ));


	$footer['actions'] = array();
	$footer['actions'][] = $this->Html->link( __('cancel', true), '#', array(
		'class' => '',
	));
	$footer['actions'][] = $this->Html->link( __('Save', true), '#', array(
		'class' => 'btn primary',
	));

}

echo $this->element('flour/box', compact('caption', 'header', 'footer', 'content'));
$script = <<<HTML
$().ready(function(){
	$('.section_toggle').live('click', function(event) {
		$(this).addClass('RevealMe').fadeOut();
		var rel = $(this).attr('rel');
		$('.section_' + rel).slideToggle();
	});
	$('.section_cancel').live('click', function(event) {
		var rel = $(this).attr('rel');
		$('.'+rel+' div.section').slideToggle();
		$('.RevealMe[rel='+rel+']').fadeIn();
	});
	$('.section_valid_range, .section_status').hide();
});
HTML;

echo $this->Html->scriptBlock($script);