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
 * defaults
 *
 * @var array $_defaults
 * @access protected
 */
	protected $_defaults = array(
		'fullsearch',
		'search',
		'tags',
		'date',
		'from',
		'to',
		'status',
	);

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
		$this->Configuration = ClassRegistry::init('Configuration');
	}

/**
 * startup Callback will be exectured before Controller action, but after beforeFilter
 *
 * @access public
 */
	public function startup()
	{
		$configurations = $this->Configuration->find('autoload');
		$this->write($configurations);
	}

	public function write($data = array())
	{
		foreach($data as $key => $value)
		{
			Configure::write($key, $value);
		}
	}


/**
* Reads settings from database and writes them using the Configure class
* 
* @return void
* @access private
*/
	function _configureAppSettings() {
		$settings = array();
		$this->loadModel('Setting');
		$Setting = $this->Setting;
		if (($settings = Cache::read("settings.all")) === false)
		{
			$settings = $this->Setting->find('all');
			Cache::write("settings.all", $settings);
		}
		foreach($settings as $_setting)
		{
			if ($_setting['Setting']['value'] !== null) {
				Configure::write("{$_setting['Setting']['category']}.{$_setting['Setting']['setting']}", $_setting['Setting']['value']);
			}
		}
	}


}