<?php
$this->title = __('Documentation', true);
$this->description = __('Please refer to this documentation to see, how flour works.', true);

echo $this->element('flour/nav.docs');
echo $this->element('flour/content_start');

echo $this->Html->div('span-24');
echo <<<HTML
<h2>Overview</h2>
<p><strong>flour</strong> is a CakePHP plugin that enables its users to start directly with coding business logic.</p>
<p>Its primary goal is to be a handy toolset for a wide range of applications. It currently constis of the following modules:</p>

<ul>
	<li>Contents</li>
	<li>Collections</li>
	<li>Configurations</li>
</ul>

<h3>Editions</h3>
<p>Before diving into each module, you should understand their similarities. They all work with editions of rows. To understand what an edition is, we talk about an example:</p>
<h4>Example</h4>
<p>Lets say, you have a front-page with a stylish and corporate look.</p>
<p>Now, you want to have a nice bunny at the time of eastern, but you want to make sure it appears and disappears automagically.</p>

<p>First, with the ContentLibrary it's as easy to access content like that:</p>
<pre><code>&amp;this->ContentLib->get('home'); //could, e.g. return the styled homepage, a logo or just one paragraph</code></pre>

<p>Now, you just duplicate your content called 'home', which creates another <em>edition</em> of it. 
	If you are a smart person, you write a little description, that the just-created edition is a specific version for eastern.</p>
<p>You change the publish time to start at 2 weeks before eastern and the end date to the day after easern.</p>
<p>Now, whenever you call for the Content called 'home' it returns the correct version for it, with a bunny on eastern, without on the other time.</p>
HTML;

$left = <<<HTML

<h3>Contents</h3>
<p><strong>Introduction:</strong> Contents are a flat library of typed data. Think of it as a large repository of different contents, you need all over your website/webapp. Each Content has a type for easier categorization.</p>
<p><strong>Manual:</strong></p>
<p>You can create yourself a content type like that:</p>
<ul>
	<li>Create an element below views/elements/contents</li>
	<li>It should be named like this <code><strong>type_</strong>your_short_name.ctp</code></li>
	<li>This file contains form-inputs, like found in <code>/flour/views/elements/contents/type_article.ctp</code></li>
	<li>Make sure to give a hidden field for the model you want to use.</li>
	<li>Use <code>ContentObject</code> if you just want to save data.</li>
</ul>

<p>You can use the ContentLib Helper to access your Content Library:</p>
<pre><code>echo &amp;this->ContentLib->render('test', 'article');
echo &amp;this->ContentLib->render('test', 'contents/item', array('plugin' => 'flour', 'template' => 'default'));

echo &amp;this->ContentLib->form('foo', array('plugin' => 'bar', 'baz' => 'test'));

echo &amp;this->ContentLib->form('article');
echo &amp;this->ContentLib->form('article', array('title' => 'prefilled title'));
</code></pre>

HTML;

$right = <<<HTML

HTML;




// $contents = array();
// $contents['install'] = 'Installation';
// $contents['contents'] = 'CONTENT';
// $contents['widgets'] = 'WIDGETS';
// 
// $tabs = array();
// 
// $tabs[] = $this->Html->link( __('Installation', true), '#install', array('rel' => 'install'));
// $tabs[] = $this->Html->link( __('Contents', true), '#contents', array('rel' => 'contents'));
// $tabs[] = $this->Html->link( __('Widgets', true), '#widgets', array('rel' => 'widgets'));
// 
// echo $this->element('flour/box', array(
// 	'caption' => __('Documentation', true),
// 	'content' => $contents,
// 	'class' => 'tabbing',
// 	'header' => $this->Html->nestedList($tabs, array('class' => 'tabs')),
// ));
// 
// $script = <<<HTML
// $().ready(function(){
// 	
// 	$('.box_content > div').hide();
// 	$('.box_content > div:first').show();
// 	$('ul.tabs li:first').addClass('active');
// 	$('ul.tabs li').bind('click', function()
// 	{ 
// 		$('ul.tabs li').removeClass('active');
// 		$(this).addClass('active');
// 		var type = $(this).find('a').attr('rel');
// 		$('.box_content > div').hide();
// 		$('.box_content > div.' + type).show(); 
// 	});
// 
// });
// HTML;
// echo $this->Html->tag('script', $script);
// 
// 
// echo <<<HTML
// <style>
// ul.tabs { list-style: none; margin: 0; background: #F2F2F2; }
// ul.tabs li { cursor: pointer; margin: 0; display: inline-block; padding: 10px; border-right: 1px solid #ccc; }
// ul.tabs li.active { background: #fff; }
// div.box_content { background: #fff; }
// div.widgets {  }
// div.widget_types {  }
// </style>
// HTML;



	echo $this->Html->div('span-12', $left);
	echo $this->Html->div('span-12 last', $right);

echo $this->Html->tag('/div'); //div.span-24

echo $this->element('flour/content_stop');
