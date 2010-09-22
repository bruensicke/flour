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
		$this->_init();
	}

/**
 * startup Callback will be exectured before Controller action, but after beforeFilter
 *
 * @access public
 */
	public function startup()
	{
		if($this->Configuration)
		{
			$this->Configuration->_writeConfiguration();
		}
	}


/**
 * if database is connected, loads Configuration Model
 *
 * @return bool returns true if running with connected db, false otherwise.
 * @access protected
 */
	protected function _init()
	{
		//first, check if we run with database
		if(!file_exists(CONFIGS.'database.php'))
		{
			//TODO: check for active connection.
			return false;
		}

		uses('model' . DS . 'connection_manager');
		$db = ConnectionManager::getInstance();
		$connected = $db->getDataSource('default');
		if (!$connected->isConnected())
		{
			return false;
		}
		
		if(!$this->Configuration)
		{
			$this->Configuration = ClassRegistry::init('Flour.Configuration');
		}
		return true;
	}
}