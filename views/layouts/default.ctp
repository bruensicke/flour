<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link( __('flour: a CakePHP plugin', true), array('controller' => 'contents', 'action' => 'index', 'plugin' => 'flour')); ?></h1>
		</div>
		<div id="navigation">
			<div class="btnbar"><?php echo $this->element('flour/nav.main'); ?></div>
			<?php echo $this->element('flour/nav.sub'); ?>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<small>running cake <?php echo Configure::version() ?></small>
			<?php echo $this->Html->image('cake.power.gif', array('border' => '0', 'url' => 'http://bruensicke.com/')); ?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>