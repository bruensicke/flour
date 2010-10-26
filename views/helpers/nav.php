<?php
/**
 * NavHelper
 *
 * Handles different ways of generating and outputting navigations.
 * 
 * 
 * Example Usage:
 * {{{
 * echo $this->Nav->show('foo'); //reads navigation with slug named "foo" from Navigationtable
 *
 * $slug = $this->Nav->create('Foo', array('description' => 'All Navigations for navigating foo.'));
 * $nav->add($slug, array('name' => 'Bar', 'url' => '#bar'));
 * $nav->add($slug, 'Baz', '#baz');
 * }}}
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 */
App::import('Lib', 'Flour.init');
class NavHelper extends AppHelper
{

/**
 * Navigation Model, will be set on _init()
 * 
 * @var Model $_Navigation
 * @access protected
 */
	protected $_Navigation = false;

/**
 * Reference to View Class
 * needed, to render elements within a helper
 * 
 * @var ViewClass $_View
 * @access protected
 */
	protected $_View = false;


/**
 * used for internal handling of complete Navigations
 *
 * @var array
 * @access public
 */
	public $_cache = array();

	public $navigationTemplate = array(
		'Navigation' => array(
			'type' => 'default',
			'template' => 'default',
			'status' => 1,
			'name' => '',
			'class' => '',
			'slug' => '',
		),
		'NavigationItem' => array(),
	);

	public $navigationItemTemplate = array(
		'NavigationItem' => array(
			'type' => 'default',
			'template' => '',
			'status' => 1,
			'name' => '',
			'class' => '',
			'url' => '',
		),
	);

/**
 * Constructor method
 *
 * @access public
 */
	public function __construct()
	{
		$this->_init();
	}

/**
 * gets the current Navigation with given $slug
 *
 * @param string $slug slug of Navigation to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active Navigation - false otherwise
 * @access public
 */
	public function get($slug_or_id)
	{
		if(array_key_exists($slug_or_id, $this->_cache))
		{
			return $this->_cache[$slug_or_id];
		}
		return $this->_Navigation->get($slug_or_id);
	}

/**
 * renders the current Navigation with given $slug
 * 
 * $options are:
 * 
 *  - element
 *  - template
 *  - data
 *  - item_element
 *  - item_template
 *  - item_data
 *
 * plugin-prefix for elements have a syntax like this: "$plugin:$element"
 * 
 * @param string | array $slug_or_id give slug of Navigation (from DB) or pass in data to render
 * @param array $options array with options
 * @return string $output the HTML rendered by the element
 * @access public
 */
	public function render($slug_or_data, $options = array())
	{
		$row_data = (is_array($slug_or_data))
			? $slug_or_data
			: $this->get($slug_or_data);

		$element = (isset($options['element']))
			? $options['element']
			: 'Flour:navigations/item';

		$template = (isset($options['template']))
			? $options['template']
			: null;

		$data = (isset($options['data']))
			? $options['data']
			: array();

		$item_element = (isset($options['item_element']))
			? $options['item_element']
			: 'Flour:navigation_items/item';

		$item_template = (isset($options['item_template']))
			? $options['item_template']
			: 'default';

		$item_data = (isset($options['item_data']))
			? $options['item_data']
			: array();

		//Check, if element is prefixed with $plugin: (e.g. "Flour:navigations/item")
		if (strpos($element, ':') !== false) {
			$parts = explode(':', $element, 2);
			$element = $parts[1];
		}
		if (strpos($item_element, ':') !== false) {
			$item_parts = explode(':', $item_element, 2);
			$item_element = $item_parts[1];
		}

		$plugin = (!empty($parts[0]))
			? $parts[0]
			: null;
		
		$item_plugin = (!empty($item_parts[0]))
			? $item_parts[0]
			: null;
		

		$output = array();
		$max = count($row_data['NavigationItem']);
		$i = 0;
		foreach($row_data['NavigationItem'] as $index => $item)
		{
			//prepare $row for $item_element
			$item_row = array(
				'Navigation' => $row_data['Navigation'],
				'NavigationItem' => $item,
			);

			//TODO: check for children
			$item_data = Set::merge($item_data, array(
				'plugin' => $item_plugin,
				'row' => $item_row,
				'first' => ($i == 0) ? 'first' : null,
				'last' => ($i == $max-1) ? 'last' : null,
				'max' => $max,
				'i' => $i++,
				'template' => (!empty($item['template']))
					? $item['template']
					: $item_template,
			));
			$output[] = $this->_View->element($item_element, $item_data);
		}

		$row_data['items'] = implode($output);

		$data = Set::merge($data, array(
			'plugin' => $plugin,
			'row' => $row_data,
			'template' => (!empty($template))
				? $template
				: $row_data['Navigation']['template'],
		));
		return $this->_View->element($element, $data);
	}

/**
 * Create a Navigation Container with given $slug and $data, also accepts $rows to insert NavigationItems
 * 
 * {{{
 * $this->Nav->create('primary');
 * $this->Nav->create('primary', array('name' => 'Toplevel Navigation', 'template' => 'short'));
 * }}}
 *
 * @param string $slug the slug of the Navigation
 * @param array $data to be set for the Navigation
 * @param array $rows a list of NavigationItems, as returned by a Navigation->find
 * @return array the new generated version of the Navigation
 * @access public
 */
	public function create($slug, $data = array(), $rows = array())
	{
		$this->_createLocal($slug, $data);

		if(!empty($rows))
		{
			foreach($rows as $row)
			{
				$this->add($slug, $row);
			}
		}
		return $this->_cache[$slug];
	}

	//if $name is array, it is used as $options, $url is omitted

/**
 * adds a NavigationItem to Navigation with slug $slug
 *
 * @param string $slug name of this Navigation
 * @param string|array if string, $name of the link, if is_array, it behaves like $data
 * @param string|array $url to be used as url (can be an array or null)
 * @param array $data use all fields available in NavigationItem schema
 * @return array full array of Navigation including NavigationItem
 * @todo should also work with $rows of NavigationItems
 * @access public
 */
	public function add($slug, $name = null, $url = null, $data = array())
	{
		//consolidate input-params
		$data = (is_array($name))
			? Set::merge($data, $name)
			: Set::merge($data, array('name' => $name, 'url' => $url));

		$this->_createLocal($slug);

		return $this->_addLocal($slug, $data);
	}


/* internal methods */

/**
 * Create an internal, cached version of a Navigation, named with $slug
 *
 * @param string|array $slug if string, $slug for Navigation, if array, it behaves like $data (second param is omitted)
 * @param array $data array with keys named as found in the Navigation Schema
 * @return array the new generated version of the Navigation
 * @access public
 */
	public function _createLocal($slug, $data = array())
	{
		if(is_array($slug))
		{
			$data = $this->_normalizeRow($slug);
			$slug = (!empty($data['Navigation']['slug']))
				? $data['Navigation']['slug']
				: Inflector::slug($data['Navigation']['name']);
		} else {
			$data = $this->_normalizeRow($data);
		}

		if(!array_key_exists($slug, $this->_cache))
		{
			$this->navigationTemplate['Navigation']['id'] = String::uuid(); //we generate a random id here, so we have one for our template
			$this->_cache[$slug] = Set::merge($this->navigationTemplate, $data);
		}
		return $this->_cache[$slug];
	}

/**
 * Add NavigationItems to an internal, cached version named $slug
 *
 * @param string $slug 
 * @param array $data 
 * @return bool
 * @access public
 */
	public function _addLocal($slug, $data = array())
	{
		$this->_createLocal($slug);
		$this->_cache[$slug]['NavigationItem'][] = Set::merge($this->navigationItemTemplate['NavigationItem'], $data);
		return $this->_cache[$slug];
	}


/**
 * Make sure we have the modelname as key
 *
 * @param array $row
 * @return array with "$key" key
 */
	function _normalizeRow($row, $key = 'Navigation')
	{
		if (!is_array($row)) return null;
 		if (array_key_exists($key, $row)) return $row;
		return array($key => $row);
	}

/**
 * will be executed on initialization, references the current
 * View Class, to be able to render elements.
 * 
 * if database is connected, loads Navigation Model, to interact with 
 * NavigationLibrary
 *
 * @return bool returns true if running with connected db, false otherwise.
 * @access protected
 */
	protected function _init()
	{
		$this->_View = ClassRegistry::getObject('view');

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
		
		if(!$this->_Navigation)
		{
			$this->_Navigation = ClassRegistry::init('Flour.Navigation');
		}
		return true;
	}

}
