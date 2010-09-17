<?php
$this->title = __('Documentation', true);
$this->description = __('Please refer to this documentation to see, how flour works.', true);

echo $this->element('flour/nav.docs');
echo $this->element('flour/content_start');


$plugin = 'flour'; //needed for template-compact

$a = <<<HTML
<h2>Installation</h2>
<p>To use flour within a CakePHP installation, you need to have it under <code>ROOT/plugins/flour</code></p>
<p>If you want to use it as a git submodule, try the following:</p>
<pre><code>git submodule add http://github.com/bruensicke/flour.git plugins/flour</code></pre>

HTML;

$b = <<<HTML
<h3>Usage</h3>
<p>First, checkout your projects repository:</p>
<pre><code>git clone git://github.com/foo/bar.git</code></pre>
<pre><code>git submodule init</code></pre>
<pre><code>git submodule update</code></pre>
HTML;
echo $this->element('templates/half', compact('a', 'b', 'plugin'));


echo $this->element('flour/content_stop');
