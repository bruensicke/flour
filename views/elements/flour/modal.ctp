<?php
if(defined('FLOUR_MODAL_PRESENT'))
{
	return;
}

echo $this->Html->script(array('/flour/js/jquery.modal'));

// echo <<<HTML
// <div id="modal" class="jqmWindow">
// 	<div id="jqmTitle"><button class="jqmClose">Close X</button>
// 		<span id="jqmTitleText">Title of modal window</span>
// 	</div>
// 	<div id="jqmContent"></div>
// </div>
// HTML;






echo $this->Html->div('jqmWindow', '', array('id' => 'modal'));
define('FLOUR_MODAL_PRESENT', true);
?>
<script>
	var t = $('#modal');
	var load=function(h)
	{
		
	};
	var hide=function(h){
		h.o.remove();
		h.w.fadeOut(200);
		t.html("<?php __('Please wait...'); ?>");
	};
	$().ready(function(){
		$('#modal').jqm({trigger: $("a[class*=modal], li.modal a"), ajax: '@href', target: t, modal: false, onLoad: load, onHide: hide, overlay: 60}); //TODO: change to class modal, not link
	});
</script>
<style>
.jqmWindow {
	display: none;

	position: fixed;
	top: 17%;
	left: 50%;

	margin-left: -300px;
	width: 600px;

	background-color: #EEE;
	color: #333;
	border: 4px solid #555;
	padding: 12px;
	}

.jqmOverlay { background-color: #000; }

/* Background iframe styling for IE6. Prevents ActiveX bleed-through (<select> form elements, etc.) */
* iframe.jqm {
	position:absolute;top:0;left:0;z-index:-1;
	width: expression(this.parentNode.offsetWidth+'px');
	height: expression(this.parentNode.offsetHeight+'px');
}

/* Fixed posistioning emulation for IE6
     Star selector used to hide definition from browsers other than IE6
     For valid CSS, use a conditional include instead */
* html .jqmWindow {
	 position: absolute;
	 top: expression((document.documentElement.scrollTop || document.body.scrollTop) + Math.round(17 * (document.documentElement.offsetHeight || document.body.clientHeight) / 100) + 'px');
}
</style>