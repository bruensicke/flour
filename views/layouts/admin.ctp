<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title><?php echo $page->title($this->pageTitle); ?></title>
	<?php
		echo $html->meta('icon');
		//echo $html->css(array('wife/default', 'wife/basics', 'wife/blueprint', 'wife/admin.form', 'wife/admin/layout', 'wife/admin/default'));
		echo $html->css(array('/cms/css/blueprint', '/cms/css/admin.basics', '/cms/css/admin.form'));
		echo $html->css(array('admin.layout'));
		echo '<!--[if IE]>'.$html->css('fix.ie').'<![endif]-->'; //IE FIXES

		echo $javascript->link(array('/cms/js/jquery/jquery', '/cms/js/jquery/dim', '/cms/js/init')); //core

		echo $javascript->link(array('/cms/js/jquery/thead')); //plugins

		//jquery layout
		echo $javascript->link(array('/cms/js/jquery/ui.all', '/cms/js/jquery/layout'));
		$layout_options = array(
			'applyDefaultStyles' => 'true',
			'initClosed' => 'true',
			'north__initClosed' => (in_array('north', $this->panels)) ? false : true,
			'east__initClosed' => (in_array('east', $this->panels)) ? false : true,
			'south__initClosed' => (in_array('south', $this->panels)) ? false : true,
			'west__initClosed' => (in_array('west', $this->panels)) ? false : true,
		);

		echo $html->tag('script', '$(document).ready(function(){ 
			layoutRght = $("#rght").layout({ initHidden: true, north__initHidden: false, south__initHidden: false, maxSize: 220, applyDefaultStyles: true });
			adminLayout = $("body").layout('.json_encode($layout_options).'); 
			layoutMain = $("#main").layout({ initHidden: true, north__initHidden: false, south__initHidden: false, applyDefaultStyles: true });
			//var layoutMain = $("#main").layout({ initHidden: true, north__initHidden: false, south__initHidden: false, center__overflow: "auto" });
		});');

		//echo $html->tag('script', '$(document).ready(function(){ var layoutMain = $("body > .ui-layout-main").layout({ initHidden: true, north__initHidden: false, south__initHidden: false }); });');
		//echo $html->tag('script', '$(document).ready(function(){ var layoutRght = $("body > .ui-layout-east").layout({ initHidden: true, north__initHidden: false, south__initHidden: false }); });');
		//echo $html->tag('script', '$(document).ready(function(){ var layoutRight = $("#rght").layout({ initHidden: true, north__initHidden: false, south__initHidden: false }); });');

		echo $scripts_for_layout;
		if(isset($head)) $head->print_registered();
	?>
</head>
<body class="admin">

<?php
	$this->put();

	echo $html->div('fleft', $this->element('nav.admin', array('plugin' => 'cms')));
	echo $html->div('fright', $this->element('nav.user', array('plugin' => 'cms')));
	$this->put('head', 'admin', 'before');
	
	//echo $this->element('nav.admin', array('plugin' => 'cms'));
	//$this->put('left', 'admin', 'before');
	
	echo $html->div('flash', $this->element('layout.flash', array('plugin' => 'cms')));
	echo $this->get('admin');

	$this->put();
?>
</body>
</html>
<?php echo $this->put(); ?>