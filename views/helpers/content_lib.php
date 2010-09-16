<?php
/**
 * ContentLibHelper
 *
 * Provides access to Content Library through a set of various functions.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config/flour.php');
class ContentLibHelper extends AppHelper
{

/**
 * Content Model, will be set on _init()
 * 
 * @var Model $_Content
 * @access protected
 */
	protected $_Content = false;

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
 * gets the current ContentObject with given $slug
 *
 * @param string $slug slug of ContentObject to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active content - false otherwise
 * @access public
 */
	public function get($slug)
	{
		return $this->_Content->get($slug);
	}

/**
 * renders the current ContentObject with given $slug
 *
 * @param string $slug slug of ContentObject to render
 * @param string $template name of template 
 * @param array $data array with data to be passed into the element
 * @return string $output the HTML rendered by the element
 * @access public
 */
	public function render($slug, $template = 'contents/item', $data = array())
	{
		$row_data = $this->get($slug);
		return $this->_View->element($template,
			array_merge(
				$data,
				array(
					'row' => $row_data,
				)
			)
		);
	}

/**
 * prints out the correct form for a given $type.
 * You can pass in $data into the element to control its behavior
 * 
 * Put all your custom types below view/elements/contents/ and name them type_foo.ctp
 * To access types within a plugin, you have to pass 'plugin' => 'my_plugin' within $data.
 * 
 * $options are keys to control behavior of the output. Keys are:
 *  o  full     -  set to true, to also return the generic form-controls.
 *
 * @param string $type name of the specific type you want to render, e.g. 'blog' or 'article'
 * @param array $data array with data to be passed into the element
 * @param string $options array with options to control the behavior of this method (see examples)
 * @return string $output the HTML rendered by the element
 * @access public
 */
	public function form($type = null, $data = array(), $options = array())
	{
		$data['plugin'] = (isset($data['plugin']))
			? $data['plugin']
			: 'flour';

		$out = (isset($options['full']) && $options['full'] === true)
			? $this->_View->element('contents/form', array('plugin' => 'flour'))
			: '';

		$out .= $this->_View->element(
			String::insert(Configure::read('Flour.Content.types.pattern'),
				array(
					'type' => $type,
				)
			),
			$data
		);
		
		return $out;
	}

/**
 * will be executed on initialization, references the current
 * View Class, to be able to render elements.
 * 
 * if database is connected, loads Content Model, to interact with 
 * ContentLibrary
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
		
		if(!$this->_Content)
		{
			$this->_Content = ClassRegistry::init('Flour.Content');
		}
		return true;
	}

}