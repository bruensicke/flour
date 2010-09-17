<?php
$this->title = __('Documentation', true);
$this->description = __('Please refer to this documentation to see, how flour works.', true);

echo $this->element('flour/nav.docs');
echo $this->element('flour/content_start');


$plugin = 'flour'; //needed for template-compact

$widget_list = $this->Widget->explain();
$widget_list = $this->Html->nestedList($widget_list);

$a = <<<HTML
<h2>Widgets</h2>
<p>Widgets are, what you expect them to be. There are two flavors of Widgets:</p>
<ul>
	<li>File-based</li>
	<li>Database-based</li>
</ul>

<p>The later is just a database saved version of what is usually done in a file. That is, to make it easy for clients, to configure there widgets to their liking.</p>
<p>Find below some examaples of Widget types.</p>
HTML;

$b = <<<HTML
<h3></h3>
<p>There are different types of Widgets, currently these are available</p>
$widget_list
HTML;
echo $this->element('templates/half', compact('a', 'b', 'plugin'));

/* next row */

$a = <<<HTML
<h3>Usage</h3>
<p>Here are some Examples of how to use Widgets</p>
HTML;
$b = <<<HTML
HTML;
echo $this->element('templates/half', compact('a', 'b', 'plugin'));


$a = <<<HTML
<pre><code>&amp;this->Widget->type('Flour.html', array(
	'content' => 'FOO',
));
</code></pre>
HTML;

$b = $this->Widget->type('Flour.html', array('content' => 'FOO'));
// $b = $this->Widget->type('Flour.html', array('content' => 'huhu'), array('plugin' => 'flour'));

echo $this->element('templates/half', compact('a', 'b', 'plugin'));

echo $this->element('flour/content_stop');
