<?php
if(!function_exists('ini_parser'))
{
	function ini_parser($string)
	{
		$out = array();
		$rows = explode("\r\n", $string);
		foreach($rows as $row)
		{
			list($key, $val) = explode(':', $row, 2);
			$out[$key] = $val;
		}
		return $out;
	}
}
