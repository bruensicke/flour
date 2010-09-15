<?php
echo $this->element(
	String::insert(Configure::read('Flour.WidgetCollection.types.pattern'), array('type' => $type))
);
