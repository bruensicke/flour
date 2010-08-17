<?php
/**
 * Config(uration) Component
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class ConfigComponent extends Object 
{
/**
 * calling controller object
 *
 * @var Controller $Controller
 * @access protected
 */
	protected $Controller = null;

/**
 * Configuration model
 *
 * @var Model $Configuration
 * @access protected
 */
	protected $Configuration = null;

/**
 * Intialize Callback
 *
 * @param object Controller object
 * @access public
 */
	public function initialize(&$controller, $settings = array())
	{
		$this->Controller = $controller;
		$this->Configuration = ClassRegistry::init('Flour.Configuration');
	}

/**
 * startup Callback will be exectured before Controller action, but after beforeFilter
 *
 * @access public
 */
	public function startup()
	{
		$this->Configuration->_writeConfiguration();
	}

}