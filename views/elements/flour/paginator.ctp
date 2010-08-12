<?php
$info = array(); $options = array('tag' => 'span', 'escape' => false);

if($paginator->hasPage(null, 2))
{
	$info[] = '<div class="btnbar">';
	$info[] = '<ul class="pager">';
	$info[] = '<li class="img">'.$paginator->first($this->Html->image('/flour/img/ico/resultset_first.png'), $options).'</li>';
	$info[] = '<li class="img">'.$paginator->prev($this->Html->image('/flour/img/ico/resultset_previous.png'), $options).'</li>';
	$info[] = $paginator->numbers(array('separator' => '', 'tag' => 'li'));
	$info[] = '<li class="img">'.$paginator->next($this->Html->image('/flour/img/ico/resultset_next.png'), $options).'</li>';
	$info[] = '<li class="img">'.$paginator->last($this->Html->image('/flour/img/ico/resultset_last.png'), $options).'</li>';
	$info[] = '</ul>';
	$info[] = '</div>';
}

$info[] = '<span>';

if(!empty($search))
{
	if(!is_array($search)) $search = array($search);
	$keywords = $text->toList($search, __('</strong> and <strong>', true));
	$info[] = __('Your search for <strong>'.$keywords.'</strong> resulted in ', true);
}

switch($paginator->counter(array('format' => '%count%')))
{
	case 0:
		$info[] = __('no results, at all.', true); break;
	case 1:
		$info[] = $paginator->counter(array('format' => __('only <strong>one</strong> hit.', true))); break;
	case 2:
		$info[] = $paginator->counter(array('format' => __('just <strong>two</strong> hits, showing both.', true))); break;
	case 3:
		$info[] = $paginator->counter(array('format' => __('at least <strong>three</strong> hits, showing all three.', true))); break;
	default:
		$info[] = $paginator->counter(array('format' => __('<strong>%count%</strong> hits. Showing <strong>%start%</strong> to <strong>%end%</strong>.', true)));
}
$info[] = '</span>';

echo $this->Html->div('paginator', join("\n", $info));
