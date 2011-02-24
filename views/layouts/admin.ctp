<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		// echo $this->Html->css('cake.generic');

echo $this->Html->css(array(
	'/flour/css/admin',
	'/flour/css/jquery.jscrollpane',
));
echo $this->Html->script(array(
	'/flour/js/jquery',
	'/flour/js/jquery.field',
	'/flour/js/jquery.ui',
	'/flour/js/jquery.slug',
	'/flour/js/jquery.elastic',
	'/flour/js/jquery.overscroll',
	'/flour/js/jquery.jscrollpane',
	// '/flour/js/jquery.token',
));

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header"><?php echo $this->element('admin/header'); ?></div>
		<div id="contents">
			<?php echo $content_for_layout; ?>
		</div>
		<div id="footer"><?php echo $this->element('admin/footer'); ?></div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
	<script type="text/javascript" charset="utf-8">
$(function()
{
    // $(".content_pane").overscroll();
	// $('.content_pane').jScrollPane();
});
	</script>
</body>
</html>