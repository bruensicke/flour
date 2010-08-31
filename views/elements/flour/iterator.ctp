<?php
$element = (isset($element))
	? $element 
	: 'generic'; //TODO: check on generic element

$box_element = (isset($box_element))
	? $box_element 
	: 'flour/box';

$paginator_element = (isset($paginator_element))
	? $paginator_element 
	: 'flour/paginator';

$header = (isset($header))
	? $header 
	: '';

$footer = (isset($footer))
	? $footer 
	: '';

$data = (isset($data))
	? $data
	: $this->data;

$empty = (isset($empty))
	? $empty
	: $this->element('flour/empty');

$search = (isset($search))
	? $search
	: null;

$preserveNamedParams = (isset($preserveNamedParams))
	? $preserveNamedParams
	: true;

$current_searchterms = (isset($current_searchterms))
	? $current_searchterms
	: '';

$date = (isset($date))
	? $date
	: null;

$filters = (isset($filters))
	? $filters
	: null;

/* Grouping */
$group = (isset($group))
	? $group
	: null;

$current_group = (isset($current_group))
	? $current_group
	: '';

$group_class = (isset($group_class))
	? $group_class
	: 'group';

$group_header = (isset($group_header))
	? $group_header 
	: $this->Html->div($group_class.' group_:group_name', ':group_name');

$group_footer = (isset($group_footer))
	? $group_footer 
	: '';

$group_items_class = (isset($group_items_class))
	? $group_items_class
	: 'group_items';

$group_items_before = (isset($group_items_before))
	? $group_items_before
	: $this->Html->div($group_items_class);

$group_items_after = (isset($group_items_after))
	? $group_items_after
	: $this->Html->tag('/div');

/* Displayment */
$label = (isset($label))
	? $label
	: null;

$caption = (isset($caption))
	? $caption
	: null;

$class = (isset($class))
	? $class
	: null;

$items_class = (isset($items_class))
	? $items_class
	: 'items';

$btnbar = (isset($btnbar))
	? $btnbar
	: null;

$actions = (isset($actions))
	? $actions
	: null;

$template = (isset($template))
	? $template
	: '{{rows}}';

//maybe in named params?
$element_template = (isset($this->passedArgs['view']))
		? $this->passedArgs['view']
		: null;

//fallback, it is given
$element_template = (isset($element_template))
	? $element_template
	: 'default';

$row_options = (isset($row_options))
	? $row_options
	: array('template' => $element_template);


/* BEGIN OF RENDERING */
$box_content = $btnbar_content = array();

//needed for daterange picker
//echo $this->Html->script(array('global/jquery/daterange'));
//echo $this->Html->css(array('global/jquery/daterange'));

//searchform + daterange
//TODO: switch for search-form
if(!empty($search))
{
	echo $this->Form->create('Flour', array('url' => array('controller' => $this->params['controller'])));
}

	if(!empty($search))
	{
		if(!empty($current_searchterms))
		{
			$url = array('action' => $this->action);
			if($preserveNamedParams && isset($this->params['named'])) {
				$params = $this->params['named'];
				unset($params['search']);
				$url = array_merge($url, $params);
			}
			$btnbar_content[] = $this->Html->tag('span', $this->Html->link( __('reset', true), $url));
		}
		$btnbar_content[] = '&nbsp;'; //needed for placement in caption (line-height)
		$btnbar_content[] = $this->Form->hidden('Flour.search', array('value' => 1));
		if($preserveNamedParams && isset($this->params['named']) && !empty($this->params['named']))
		{
			$searchParams = $this->params['named'];
			unset($searchParams['page']);
			$btnbar_content[] = $this->Form->hidden('Flour.params', array('value' => json_encode($searchParams)));
		}
		$btnbar_content[] = $this->Form->input('Flour.search', array(
			'label' => false,
			'value' => $current_searchterms,
			'class' => 'search',
			'div' => false,
			'title' => __('Search', true),
		));
		$btnbar_content[] = '&nbsp;'; //needed for placement in caption (line-height)
	}

	//input for date
	if(!empty($date))
	{
		$date_options = array(
			'class' => 'daterange',
			'div' => false,
			'label' => false,
			'autocomplete' => 'off'
		);
		if(!empty($this->params['named']['date'])) $date_options['value'] = $this->params['named']['date'];
		echo $this->Html->div('daterange', $this->Form->input($model.'.date', $date_options));
	}

	//filters
	if(!empty($filters))
	{
		$hereParsed = Router::parse((str_replace($this->base, '', $this->here)));
		$here = array_merge(array('controller' => $hereParsed['controller'], 'action' => $hereParsed['action']), $hereParsed['named']);
		unset($here['page']);
		//prepare filters
		$merge = $filter = array(); //prepare an array that will fit together search-conditions
		if(!empty($this->params['named']['search'])) $merge['search'] = $this->params['named']['search']; //add searchterm, if entered
		if(!empty($this->params['named']['date'])) $merge['date'] = $this->params['named']['date']; //add from_date, if entered
		foreach($filters as $name => $link)
		{
			$active = (array_merge($link, $merge) == $here) ? 'active' : null;
			$filter[] = $this->Html->link( $name, array_merge($link, $merge), array('class' => $active));
		}
		$filters = $filter;
	}

	//rows
	if(count($data))
	{
		$rows = array();
		$content = array();
		$i = 0;

		foreach($data as $ind => $row)
		{
			
			$row = (isset($prefix))
				? array($prefix => $row)
				: $row;

			if($group)
			{
				$temp = Set::flatten($row);
				$current_group = $temp[$group];
				if(!isset($rows[$current_group])) $rows[$current_group] = array();

				$rows[$current_group][] = $this->element($element,
					array_merge(
						$row_options, 
						array(
							'row' => $row,
							'group' => $current_group,
							'i' => $i++,
							'even' => ($i % 2)
								? 'even'
								: 'odd'
						)
					)
				);

			} else {

				$rows[] = $this->element($element,
					array_merge(
						$row_options, 
						array(
							'row' => $row,
							'i' => $i++,
							'even' => ($i % 2)
								? 'even'
								: 'odd'
						)
					)
				);

			}

		}

		//insertion of item-template in main-template
		$connector = (Configure::read())
			? "\n"
			: '';

		if($group)
		{
			foreach($rows as $group_name => $rows)
			{
				$content[] = String::insert($group_header, array('group_name' => $group_name));
				$content[] = String::insert($group_items_before, array('group_name' => $group_name));
				$content[] = str_replace('{{rows}}', implode($connector, $rows), $template);
				$content[] = String::insert($group_items_after, array('group_name' => $group_name));
				$content[] = String::insert($group_footer, array('group_name' => $group_name));
			}
		} else {
			$content[] = $header;
			$content[] = str_replace('{{rows}}', implode($connector, $rows), $template);
			$content[] = $footer;
		}
		$content = implode($connector, $content);

	} else {
		$content = $empty;
	}

	if(!empty($current_searchterms) && isset($this->Text) && is_object($this->Text))
	{
		//highlights the searchterm in output
		$content = $this->Text->highlight(
			$content,
			$current_searchterms,
			array(
				'format' => '<span class="highlight">\1</span>', //format of replace
				'html' => true, //will take care of html
			)
		);
	}

	$box_content[] = $this->Html->div($items_class, $content);

	//paginator
	$footer = (isset($this->Paginator) && is_object($this->Paginator))
		? $this->element($paginator_element, array('search' => $current_searchterms))
		: null;

	$btnbar_content[] = $btnbar;
	$btnbar_content = implode($btnbar_content);

echo $this->element($box_element, array(
	'class' => $class,
	'caption' => $caption,
	'btnbar' => $btnbar_content,
	'filters' => $filters,
	'actions' => $actions,
	'label' => (!empty($label))
		? $label
		: null,
	'content' => implode($box_content),
	'footer' => $footer,
));



if(!empty($search))
{
	echo $this->Html->div('hide', $this->Form->submit( __('Go', true), array('class' => 'btnEnter')));
	echo $this->Form->end();
}

$url = Router::url(array('controller' => $this->params['controller'], 'action' => 'edit'));

echo $this->Html->scriptBlock('
	$("tr, div.items div.item").dblclick(function(){ var id = $(this).attr("rel"); document.location = "'.$url.'/" + id; });
	$("div.actions").hide();
');
