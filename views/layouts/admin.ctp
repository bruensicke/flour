<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->tag('title', String::insert(Configure::read('Admin.Settings.title'), Set::flatten(array_merge(Configure::read('Admin.Settings'), array('title' => $title_for_layout)))));
	echo $this->Html->css(Configure::read('Admin.Settings.styles'));
	echo $this->Html->script(Configure::read('Admin.Settings.scripts'));
	echo $scripts_for_layout;
	?>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
	<meta name="robots" content="noindex"/>
	<meta http-equiv="x-dns-prefetch-control" content="off"/>
</head>
<body>
	<div id="page">
		<header id="header">
			<?php echo $this->element('admin/header'); ?>
		</header>
		<div id="main">
			<?php
			echo $this->element('admin/flash');
			echo $content_for_layout;
			?>
			<div class="clear"></div>
		</div>
		<footer id="footer">
			<?php echo $this->element('admin/footer'); ?>
		</footer>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>