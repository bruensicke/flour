<?php
/**
 * Flour Connection Manager
 * 
 * Will be called on init and makes sure, there is an entry 'flour'
 *
 * If no entry is present, 'default' will be cloned and table_prefix will be set
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
if(!in_array('flour', ConnectionManager::sourceList())) {
	$cm =& ConnectionManager::getInstance();
	$flour = Set::merge(
		$cm->config->default, //current default connection
		Configure::read('Flour.Connection.types.options.default') //merge in settings for flour defaults
	);
	ConnectionManager::create('flour', $flour);
}

