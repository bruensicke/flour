<?php
if(!function_exists('div_explode'))
{
	function div_explode($data)
	{
		$dataArray = array();
		foreach($data as $key => $val)
		{
			if(is_numeric($key))
			{
				$dataArray[] = $val;
			} else {
				$dataArray[] = (is_array($val))
					? '<div class="'.$key.'">'.implode($val).'</div>' 
					: '<div class="'.$key.'">'.$val.'</div>';
			}
		}
		return $dataArray;
	}
}
