<?php
/**
 * Configuration Model
 * 
 * Configurations are database driven Configures.
 * Each Configurations stores a value under a key.
 * The type of the value can be one of the following:
 * 
 *   o  bool  -  value is a boolean value false / true (0 / 1)
 *   o  text  -  value is just a text-value
 *   o  array -  value is json_encoded
 *   o  collection - value is empty, you need collection_slug filled
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class Configuration extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'Configuration';

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
	 * Custom find methods
	 *
	 * @var array $_findMethods
	 * @access public
	 */
	public $_findMethods = array(
		'autoload' => true,
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 * @access public
	 */
	public $validate = array(
		'category' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
/*		'type' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'status' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'autoload' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
*/	);

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
					'Configuration.autoload >' => 0,
				)
			);
			return $query;
		}
		elseif($state == 'after')
		{
			return $results;
		}
	}

	/**
	 * extracts an array in the following structure:
	 * 
	 * {{{
	 *     'App' => array(
	 *         'foo.name' => 'baz',
	 *         'bar.name' => 'foobarbaz',
	 *     ),
	 *     'Flour' => array(
	 *         'foo2.name' => 'baz2',
	 *         'bar2.name' => 'foobarbaz2',
	 *     ),
	 * }}}
	 *
	 * all set options are specific per user, regarding the 
	 * autoload-option and current user/group id.
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
		$user = $this->_getUser();
		foreach($data as $index => $item)
		{
			//unwrap $item
			$item = (isset($item[$this->alias]))
				? $item[$this->alias]
				: $item;

			$continue = false;
			extract($item);
			switch($autoload)
			{
				case 1: //for myself
				case 2: //for specific user
					$continue = ($user_id == $user['id'])
						? true
						: false;
					break;
				
				case 3: //for my group
				case 4: //for specific group
					$continue = ($group_id == $user['group_id'])
						? true
						: false;

				case 9:
					$continue = true;
					break;
				
				default:
					$continue = false;
			}

			if($continue)
			{
				//create category array, if necessary
				if(!array_key_exists($category, $output))
				{
					$output[$category] = array();
				}

				//handle every type different
				switch($type)
				{
					case 'array':
						$valArray = array();
						foreach($val as $index => $subitem)
						{
							if(empty($subitem['key'])) continue;
							$valArray[$subitem['key']] = $subitem['val'];
						}
						$output[$category][$name] = $valArray;
						break;
					case 'text':
					default:
						$output[$category][$name] = $val;
						break;
				}
			}
		}
		return $output;
	}

	/**
	 * Reads settings from database and writes them using the Configure class
	 * 
	 * @return void
	 * @access public
	 */
	public function _writeConfiguration()
	{
		$this->_cache = (Configure::read())
			? false
			: true;
		if (($this->_config = Cache::read('configuration.all')) === false)
		{
			$this->_config = $this->find('autoload');
			if($this->_cache === true) Cache::write('configuration.all', $this->_config);
		}
		$this->_currentConfig = $this->extract($this->_config);
		foreach($this->_currentConfig as $category => $item)
		{
			foreach($item as $key => $val)
			{
				Configure::write($category.'.'.$key, $val);
			}
		}
	}

}
