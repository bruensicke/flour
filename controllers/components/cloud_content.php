<?php
/**
 * CloudContent Component
 * 
 * Component to interact with CloudContent Webservice.
 * 
 * CloudContent stores and retrieves an arbitrary amount of data regardless
 * of its structure and hierarchical depth.
 * 
 * You can categorize your data with self-defined data-types and giving tags.
 * see http://www.cloudcontent.cc for more information.
 * 
 * how to use:
 * {{{
 *  Configure::write('Service.CloudContent.app', 'username_appname');
 *  Configure::write('Service.CloudContent.userId', 'username');  //optional
 *  Configure::write('Service.CloudContent.key', 'foo');
 *  Configure::write('Service.CloudContent.secret', 'bar');
 * }}}
 * 
 * You can also give options as Component setting like this:
 * 
 * {{{
 *     public $components = array('Flour.CloudContent' => array(
 *         'host' => 'service.cloudcontent.cc',
 *         'app' => 'username_appname',
 *         'userId' => 'username',
 *         'key' => 'foo',
 *         'secret' => 'bar',
 *     ))
 * }}}
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/

App::import('Libs', 'Flour.CloudContentService');
class CloudContentComponent extends Object
{
/**
 * Holds the service object which most method calls are
 * dispatched to.
 *
 * @var object An instance of `CloudContentService`.
 */
	protected $_Service;

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
		'app' => false,
		'key' => false,
		'secret' => false,
		'userId' => 'internal:administrator',
		'responseType' => 'array',
	);

/**
 * available types in this account
 *
 * @var array
 * @access protected
 */
	protected $_types = array();

/**
 * Initializes the component.
 * Here the Service object is constructed and connect to the remote service.
 *
 * @param object $Controller The current controller object.
 * @param array $settings An array of settings.
 * @return void
 */
	public function initialize($Controller, $settings = array())
	{
		if (Configure::read('Service.CloudContent.host') !== null) {
			$this->defaultSettings['host'] = Configure::read('Service.CloudContent.host');
		}
		if (Configure::read('Service.CloudContent.app') !== null) {
			$this->defaultSettings['app'] = Configure::read('Service.CloudContent.app');
		}
		if (Configure::read('Service.CloudContent.key') !== null) {
			$this->defaultSettings['key'] = Configure::read('Service.CloudContent.key');
		}
		if (Configure::read('Service.CloudContent.secret') !== null) {
			$this->defaultSettings['secret'] = Configure::read('Service.CloudContent.secret');
		}
		if (Configure::read('Service.CloudContent.userId') !== null) {
			$this->defaultSettings['userId'] = Configure::read('Service.CloudContent.userId');
		}
		if (Configure::read('Service.CloudContent.responseType') !== null) {
			$this->defaultSettings['responseType'] = Configure::read('Service.CloudContent.responseType');
		}
		$this->settings = array_merge($this->defaultSettings, $settings);
		$this->_Service = new CloudContentService($this->settings);
	}

/**
 * Magic method. Dispatches method calls to the `Service` object. Please see the API
 * documentation on that class to get an overview about all possible methods.
 *
 * @param string $method
 * @param array $args
 * @return mixed
 */
	public function __call($method, $args)
	{
		return call_user_func_array(array($this->_Service, $method), array_values($args));
	}

/**
 * retrieves a flat array with keys = id of type, and value is name of type
 *
 * @param bool $refresh set to true to refresh cache
 * @return array
 * @access public
 */
	public function types($refresh = false)
	{
		if(!$refresh && !empty($this->_types))
		{
			return $this->_types;
		}
		$data = $this->index('types');
		if(!empty($data) && isset($data['body']['data']))
		{
			foreach($data['body']['data'] as $row)
			{
				$result[$row['_id']] = $row['meta']['title'];
			}
			$this->_types = $result;
		}
		return $this->_types;
	}

/**
 * gets documents as list, optionally filtered by type
 *
 * @param string $type 
 * @return mixed array with data, or false if given type does not exist
 * @access public
 */
	public function find($type = null)
	{
		$this->types(); //refresh types
		$reversedTypes = array_flip($this->_types);
		$result = array();

		if(!array_key_exists($type, $reversedTypes))
		{
			return false;
		}

		$data = (!empty($type))
			? $this->getByType($reversedTypes[$type])
			: $this->index('documents');

		if(!empty($data) && isset($data['body']['data']))
		{
			foreach($data['body']['data'] as $row)
			{
				$temp = array(
					'meta' => $row['meta'],
					'data' => $row['data'],
				);
				$temp['meta']['id'] = $row['_id'];
				$temp['meta']['type'] = $this->_types[$row['meta']['type']];
				$result[] = $temp;
			}
		}
		return $result;
	}

}
