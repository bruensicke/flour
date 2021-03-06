<?php
/**
 * Flour App Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
App::import('Lib', 'Flour.init');
class FlourAppModel extends AppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'FlourAppModel';

	/**
	 * useDbConfig set explicitly to 'flour'
	 *
	 * @var string useDbConfig
	 * @access public
	 */
	public $useDbConfig = 'flour';

	/**
	 * Attached behaviors
	 *
	 * @var array
	 * @access public
	 */
	public $actsAs = array(
		'Flour.Flexible',
		// 'Flour.Editionable', //TODO: develop editionable behavior
	);

	/**
	 * flour beforeSave
	 *
	 * @access public
	 */
	public function beforeSave()
	{
		parent::beforeSave();
		$this->_addUserdata();
		return true;
	}
	
	/**
	 * flour soft delete
	 *
	 * @access public
	 */
	public function delete($id = null, $cascade = true) {
		if (!empty($id)) {
			$this->id = $id;
		}
		$id = $this->id;

		if(!$this->hasField('deleted')) {
			return parent::delete($id, $cascade);
		}
		
		$user = $this->_getUser();
		if($user) {
			$this->data[$this->alias]['deleted_by'] = $user;
		}
		$this->data[$this->alias]['deleted'] = date('Y-m-d H:i:s');
		
		// TODO: handle cascade delete / soft delete
		
		return $this->save($this->data);
	}
	
	/**
	 * flour undelete
	 *
	 * @access public
	 */
	public function undelete($id) {
		$this->id = $id;
		if(!$this->hasField('deleted') || !$this->exists()){
			return false;
		}
		$this->data = $this->read(null, $id);
		$this->_addUserData();
		$this->data[$this->alias]['deleted'] = null;
		$this->data[$this->alias]['deleted_by'] = null;
		
		return $this->save($this->data);
	}
	
	/**
	 * flour find
	 *
	 * @var array
	 * @access private
	 * @todo add missing params to fulfill the standard find() signature
	 */
	public function find($type, $options = array())
	{
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
		return parent::find($type, $options);
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
		$slug = (isset($options['slug']))
			? $options['slug']
			: null;

		if(!empty($options['id']))
		{
			$this->id = $options['id'];
		}

		$this->_setValid($options, $type);

		// find all editions with inherited id
		if(isset($this->id) && empty($slug)) {
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

		$options['conditions'] = array_merge(
			$options['conditions'],
			array(
				$this->alias.'.slug' => $slug,
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

	function _addUserData()
	{
		$user_id = $this->_getUser('id');
		if(!$user_id) return false;
		
		if(!isset($this->data[$this->alias][$this->primaryKey])) {
			$this->data[$this->alias]['user_id'] = $user_id;
			$this->data[$this->alias]['created_by'] = $user_id;
		}
		$this->data[$this->alias]['modified_by'] = $user_id;
	}

	function _getUser($field = null)
	{
		$user = Configure::read('Flour.User');
		if(!empty($user))
			return (isset($field))
				? $user[$field]
				: $user;
		
		$user = Configure::read('Auth.User');
		if(!empty($user))
			return (isset($field))
				? $user[$field]
				: $user;

/*
		//TODO: find UserClass, ask for user there.
		if(get_declared_classes())
		$user = Configure::read('Auth.User.id');
		if(!empty($user))
			return (isset($field))
				? $user[$field]
				: $user;
*/
		return false;
	}
	
	/**
	 * Customized paginateCount method (copied from CakeDC Tags.Taggable Plugin)
	 *
	 * @param array
	 * @param integer
	 * @param array
	 * @return void
	 * @access public
	 */
	public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		} else {
			return $this->find('count', array_merge($parameters, $extra));
		}
	}

	/**
	 * Logs all errors in db to flour log, also logs $this->data to investigate the issue
	 *
	 * @return void
	 * @access public
	 */
	public function onError() {
		$db = ConnectionManager::getDataSource('flour');
		$err = $db->lastError();
		$this->log($err, 'flour');
		$this->log($this->data, 'flour');
	}
}
