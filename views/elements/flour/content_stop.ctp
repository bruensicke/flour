<?php
$url = Router::url(array('action' => 'type'), true);
$tags_url = Router::url(array('controller' => 'tags', 'action' => 'get'), true);
echo $this->Html->script('/flour/js/jquery.wymeditor');
echo $this->Html->scriptBlock("$().ready(function(){
	// $('.tags').tokenInput('$tags_url', {});

	// $('.elastic').elastic();

	$('.elastic').wymeditor({skin: 'bruensicke'});

	$('.slugify').slug({slug:'slug', hide: false});
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

