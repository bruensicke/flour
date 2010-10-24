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
		return $this->_Navigation->get($slug_or_id);
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
