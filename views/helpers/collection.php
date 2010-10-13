<?php
/**
 * CollectionHelper
 *
 * Provides access to Collections through a set of various functions.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 */
App::import('Lib', 'Flour.init');
class CollectionHelper extends AppHelper
{

/**
 * Collection Model, will be set on _init()
 * 
 * @var Model $_Collection
 * @access protected
 */
	protected $_Collection = false;

/**
 * Other helpers to load
 *
 * @var public $helpers
 * @access public
 */
	public $helpers = array('Html');

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
 * gets the current Collection with given $slug
 *
 * @param string $slug slug of Collection to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active content - false otherwise
 * @access public
 */
	public function get($slug)
	{
		return $this->_Collection->get($slug);
	}


	function show($data = array(), $options = array()) {
		if(is_string($data)) {
			$data = $this->get($data, $options);
		}
		
		$output = array();
		
		if(is_bool($options) && $options) {
			foreach($data as $term => $value) {
				$def = is_array($value) 
					? $value[0] 
					: $value;
				$def_opt = is_array($value)
					? $value[1]
					: array();

				$output[] = $this->Html->tag('dt', $term);
				$output[] = $this->Html->tag('dd', $def, $def_opt);
			}
			$options = array();
		} else {
			foreach($data as $values) {
				$term = is_array($values[0])
					? $values[0][0]
					: $values[0];
				$term_opt = is_array($values[0]) 
					? $values[0][1]
					: array();
				
				$def = is_array($values[1])
					? $values[1][0]
					: $values[1];
				$def_opt = (is_array($values[1]) && count($values[1]) > 1)
					? $values[1][1]
					: array();
				
				$output[] = $this->Html->tag('dt', $term, $term_opt);
				$output[] = $this->Html->tag('dd', $def, $def_opt);
			}
		}
		
		isset($options['class'])
			? $options['class'] .= ' clearfix'
			: $options['class'] = 'clearfix';
		
		$output = $this->Html->tag('dl', implode($output), $options);
		
		return $output;
	}

/**
 * will be executed on initialization, references the current
 * View Class, to be able to render elements.
 * 
 * if database is connected, loads Collection Model, to interact with 
 * CollectionLibrary
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
		
		if(!$this->_Collection)
		{
			$this->_Collection = ClassRegistry::init('Flour.Collection');
		}
		return true;
	}

}