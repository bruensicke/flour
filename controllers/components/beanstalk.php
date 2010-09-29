<?php
/**
 * Component for interfacing with Pheanstalk.
 *
 * {{{
 *     public $components = array('Flour.Pheanstalk' => array(
 *         'host' => '127.0.0.1',
 *         'port' => '11300',
 *         'timeout' => '60',
 *     ))
 * }}}
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
App::import('Libs', 'Flour.Pheanstalk');
class BeanstalkComponent extends Object 
{

/**
 * current settings
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * default Settings
 *
 * @var array
 * @access protected
 */
	protected $defaultSettings = array(
		'host' => '127.0.0.1',
		'port' => '11300',
		'timeout' => '60',
	);

/**
 * Holds the service object which most method calls are
 * dispatched to.
 *
 * @var object An instance of `Pheanstalk`.
 */
	protected $_Pheanstalk;

/**
 * Initializes the component.
 * Here the Pheanstalk object is constructed and connect to the deamon.
 *
 * @param object $Controller The current controller object.
 * @param array $settings An array of settings.
 * @return void
 */
	public function initialize($Controller, $settings = array()) {
		$this->settings = array_merge($settings, $this->defaultSettings);
		extract($this->settings);
		$this->_Pheanstalk = new Pheanstalk($host, $port, $timeout);
	}

/**
 * Magic method. Dispatches method calls to the `Pheanstalk` object. Please see the API
 * documentation on that class to get an overview about all possible methods.
 *
 * @param string $method
 * @param array $args
 * @return mixed
 */
	public function __call($method, $args) {
		return call_user_func_array(array($this->_Pheanstalk, $method), $args);
	}
}