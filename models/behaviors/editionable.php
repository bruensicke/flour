<?php
/**
 * Model behavior Editionable.
 *
 * Regarding editions on models, including status valid_from and valid_to
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 */
class EditionableBehavior extends ModelBehavior
{

/**
 * Settings array
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * Default settings
 * methods		- toggle the usage of custom findMethods
 * 					active   - finds only items with status > 0 
 * 					current  - finds current active item, depending on valid from/to
 * 					editions - finds all items with the same slug, ignoring state and valid from/to
 * 					deleted  - only items that are soft-deleted
 *
 * @var array
 * @access protected
 */
	protected $_defaults = array(
		'methods' => array(
			'active' => 1,
			'current' => 1,
			'editions' => 1,
			'deleted' => 1,
		)
	);

/**
 * Setup
 *
 * @param AppModel $Model
 * @param array $settings
 * @access public
 */
	function setup(&$Model, $settings = array())
	{
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		$Model->_findMethods = array_merge(
			$Model->_findMethods,
			$this->settings[$Model->alias]['methods']
		);
	}

	/**
	 * flour beforeSave
	 *
	 * @access public
	 */
	function beforeSave(&$Model)
	{		
		$Model->_addUserdata();
		return true;
	}


	/**
	 * flour soft delete
	 *
	 * @access public
	 */
	function delete(&$Model, $id = null, $cascade = true) {
		if (!empty($id)) {
			$Model->id = $id;
		}
		$id = $Model->id;

		if(!$Model->hasField('deleted')) {
			return $Model->delete($id, $cascade);
		}
		
		$user = $Model->_getUser();
		if($user) {
			$Model->data[$Model->alias]['deleted_by'] = $user;
		}
		$Model->data[$Model->alias]['deleted'] = date('Y-m-d H:i:s');
		
		// TODO: handle cascade delete / soft delete
		
		return $Model->save();
	}
	
	/**
	 * flour undelete
	 *
	 * @access public
	 */
	function undelete(&$Model, $id) {
		$Model->id = $id;
		if(!$Model->hasField('deleted') || !$Model->exists()){
			return false;
		}
		$Model->data = $Model->read(null, $id);
		$Model->_addUserData();
		$Model->data[$Model->alias]['deleted'] = null;
		$Model->data[$Model->alias]['deleted_by'] = null;
		
		return $Model->save();
	}
	
	/**
	 * Run before a model is about to be find, used only fetch for non-deleted records.
	 *
	 * @param object $Model Model about to be deleted.
	 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
	 * @return mixed Set to false to abort find operation, or return an array with data used to execute query
	 * @access public
	 */
	function beforeFind(&$Model, $queryData)
	{
		// debug($Model->);
	}

	function _findEditions(&$Model, $state, $query, $results = array())
	{
		if($state == 'before')
		{
			$slug = null;
		
			// find all editions with inherited id
			if(isset($Model->id)) {
				$slug = $Model->field('slug');
			}
		
			// find all editions with same slug by reference id:
			if(isset($options['conditions'][$this->alias.'.'.$this->primaryKey])) {
				$slug = $this->field('slug', $options['conditions']);
			}
		
			// find all editions with given slug
			if(isset($options['conditions'][$this->alias.'.slug'])) {
				$slug = $options['conditions'][$this->alias.'.slug'];
			}

			unset($options['conditions'][$this->alias.'.'.$this->primaryKey]);
			$options['conditions'] = array_merge(
				$options['conditions'],
				array(
					$this->alias.'.slug' => $slug,
				)
			);
		
			$type = 'all';
			return $query;
		}
		elseif ($state == 'after') {
			if (empty($results)) {
				return array();
			}
			return $results;
		}
	}

	/**
	 * flour find
	 *
	 * @var array
	 * @access private
	 * @todo add missing params to fulfill the standard find() signature
	 */
	function find(&$Model, $type, $options = array())
	{
		if (isset($options['conditions']) && is_string($options['conditions'])) {
			$options['conditions'] = array($options['conditions']);
		} 
		
		if (!isset($options['conditions']) ) {
			$options['conditions'] = array();
		} 
		
		if (!isset($options['contain']) ) {
			// This kills all non-explicit contains like hasMany etc
			// $options['contain'] = array();
		} 
		
		if (!isset($options['order']) ) {
			$options['order'] = array();
		}
		
		if($this->hasField('deleted') && ! isset($options['conditions'][$this->alias.'.'.$this->primaryKey])) {
			$options['conditions'][$this->alias.'.deleted'] = null;
		}
		
		switch ($type) {
			case 'active':
				$this->_setActive($options, $type);
				break;
			case 'current':
				$this->_setCurrent($options, $type);
				break;
			case 'editions':
				$this->_setEditions($options, $type);
				break;
			case 'deleted':
				$this->_setDeleted($options, $type);
				break;
		}
		return $Model->find($type, $options);
	}



	/**
	 * flour find
	 *
	 * @var array
	 * @access private
	 * @todo add missing params to fulfill the standard find() signature
	 */
	function _setActive(&$options, &$type)
	{
		$this->_setValid($options, $type);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.status >' => 0,
			)
		);
		$type = (isset($options['conditions'][$this->alias.'.'.$this->primaryKey]))
			? 'first'
			: 'all';
	}

	/**
	 * undocumented function
	 *
	 * @param string $options 
	 * @param string $type 
	 * @return void
	 */
	function _setCurrent(&$options, &$type) {
		$this->_setValid($options, $type);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.status >' => 0,
			)
		);
		$options['order'] = array_merge(
			$options['order'],
			array(
				$this->alias.'.valid_from' => 'DESC',
			)
		);
		$type = 'first';
	}
	
	/**
	 * undocumented function
	 *
	 * @param string $options 
	 * @param string $type 
	 * @return void
	 * @access public
	 */
	function _setEditions(&$options, &$type) {
		$slug = null;
		
		// find all editions with inherited id
		if(isset($this->id)) {
			$slug = $this->field('slug');
		}
		
		// find all editions with same slug by reference id:
		if(isset($options['conditions'][$this->alias.'.'.$this->primaryKey])) {
			$slug = $this->field('slug', $options['conditions']);
		}
		
		// find all editions with given slug
		if(isset($options['conditions'][$this->alias.'.slug'])) {
			$slug = $options['conditions'][$this->alias.'.slug'];
		}

		unset($options['conditions'][$this->alias.'.'.$this->primaryKey]);
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.slug' => $slug,
			)
		);
		
		$type = 'all';
	}
	
	function _setDeleted(&$options, &$type) {
		unset($options['conditions'][$this->alias.'.deleted']);
		
		$options['conditions'] = array_merge(
			$options['conditions'],
			array( 'NOT' => array(
					$this->alias.'.deleted' => NULL
				),
			)
		);
		$type = 'all';
	}
	
	function _setValid(&$options, &$type) {
		if(!$this->hasField('valid_from') || !$this->hasField('valid_to')) {
			return;
		}
		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				'AND' => array(
					array(
						'OR' => array(
							$this->alias.'.valid_from <=' => date('Y-m-d H:i:s'),
							$this->alias.'.valid_from' => null,
						)
					),
					array(
						'OR' => array(
							$this->alias.'.valid_to >=' => date('Y-m-d H:i:s'),
							$this->alias.'.valid_to' => null,
						),
					),
				),
			)
		);
	}
}

?>