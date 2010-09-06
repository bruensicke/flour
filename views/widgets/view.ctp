<?php
//TODO: accept named params?

$slug_or_id = (isset($this->passedArgs[0]))
	? $this->passedArgs[0]
	: false;

if(!empty($slug_or_id))
{
	echo $this->Widget->render($slug_or_id);
}