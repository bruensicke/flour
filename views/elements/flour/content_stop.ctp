<?php
$url = Router::url(array('action' => 'type'), true);
echo $this->Html->scriptBlock("$().ready(function(){
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

