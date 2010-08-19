<?php
/**
 * Configuration Model
 * 
 * type is one of the following:
 * 
 *   o  text  -  value is just a text-value
 *   o  array -  value is json_encoded
 *   o  collection - value is empty, you need collection_slug filled
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Configuration extends FlourAppModel
{
/**
 * Attached behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Flour.Taggable',
	);

/**
 * Find methods
 *
 * @var array
 * @access public
 */
	public $_findMethods = array(
		'autoload' => true,
	);

/**
 * @var array controls validation
 * @access private
 */
	var $validate = array(
		'category' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

/**
 * configurations
 *
 * @var array $configurations
 * @access protected
 */
	protected $_config = array(
	);

/**
 * cache flag, set to false to disable caching
 *
 * @var boolean $_cache
 * @access protected
 */
	protected $_cache = true;

/**
 * called after save, deletes the cache
 *
 * @param boolean $created True if this save created a new record
 * @return void
 * @access public
 */
	public function afterSave($created)
	{
		Cache::delete('configuration.all');
	}

/**
 * custom find method 'autoload'
 *
 * @param string $state 'before' or 'after'
 * @param string $query 
 * @param string $results 
 * @return mixed based on $state, returns $query or $results
 * @access public
 */
	public function _findAutoload($state, $query, $results = array())
	{
		if($state == 'before')
		{
			$query['conditions'] = Set::merge(
				$query['conditions'], 
				array(
					'Configuration.status >=' => 1,
					'Configuration.autoload' => 1,
				)
			);
			return $query;
		}
		elseif($state == 'after')
		{
			return $this->extract($results);
		}
	}

/**
 * extracts an array in the following structure:
 * 
 * {{{
 * 
 * }}}
 *
 * @param array $data results from a find-call 
 * @return array list of key => values, in a nested array of categories
 * @access public
 */
	public function extract($data = array())
	{
		$data = isset($data[$this->alias])
			? $data[$this->alias]
			: $data;

		$output = array();
		foreach($data as $index => $item)
		{
			$item = (isset($item[$this->alias]))
				? $item[$this->alias]
				: $item;

			extract($item);
			if(!array_key_exists($category, $output)) $output[$category] = array();

			switch($type)
			{
				case 'array':
					$valArray = array();
					foreach($val as $index => $subitem)
					{
						if(empty($subitem['key'])) continue;
						$valArray[$subitem['key']] = $subitem['val'];
					}
					$output[$category][$title] = $valArray;
					break;
				case 'text':
				default:
					$output[$category][$title] = $val;
					break;
			}
			
		}
		return $output;
	}

/**
* Reads settings from database and writes them using the Configure class
* 
* @return void
* @access private
*/
	function _writeConfiguration()
	{
		if (($this->_config = Cache::read('configuration.all')) === false)
		{
			$this->_config = $this->find('autoload');
			if($this->_cache === true) Cache::write('configuration.all', $this->_config);
		}
		foreach($this->_config as $category => $item)
		{
			foreach($item as $key => $val)
			{
				Configure::write($category.'.'.$key, $val);
			}
		}
	}

}
