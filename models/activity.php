<?php
/**
 * Activity Model
 * 
 * Activities are log entries with a specific type, enriched with an array of data.
 * Each type has a message-text, that can contain placeholders, that will be replaced
 * by data, taken from $data.
 * 
 * Activity types can be added or overwritten via Configures:
 * 
 *   o  Flour.Activities.types.options
 * 
 * Additionally to $type and $data, one can reference a foreign model and id.
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class Activity extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'Activity';

	/**
	 * Attached behaviors
	 *
	 * @var array
	 * @access public
	 */
	public $actsAs = array(
		'Flour.Polymorphic',
		// 'Flour.Taggable',
	);

	/**
	 * Activity types
	 * taken from Flour.Activities.types.options
	 *
	 * @var array
	 * @access public
	 */
	public $types = array();

	/**
	 * Custom find methods
	 *
	 * @var array $_findMethods
	 * @access public
	 */
	public $_findMethods = array(
		'types' => true,
	);

	/**
	 * conditions for custom find and paginate count methods
	 *
	 * @var array $findConditions
	 * @access public
	 */
	public $findQueries = array(
		'types' => array(
			'conditions' => array(
				
			),
			'group' => array(
				'Activity.type'
			),
			'fields' => array(
				'DISTINCT(`type`) as type',
				'COUNT(`type`) as count',
			),
		),
	);

/**
	 * constructor auto-sets $this->types to a merged set of:
	 * 
	 *   o  Flour.Activity.types.options
	 *   o  App.Activity.types.options
	 *
	 * @access public
	 */
	public function __construct()
	{
		FlourEvent::subscribe('all', 'Activity');
		
		$appTypes = (Configure::read('App.Activity.types.options'))
			? Configure::read('App.Activity.types.options')
			: array();

		$flourTypes = (Configure::read('Flour.Activity.types.options'))
			? Configure::read('Flour.Activity.types.options')
			: array();
		$this->types = array_merge($flourTypes, $appTypes);
		return parent::__construct();
	}

	/**
	 * writes an activity log-entry.
	 *
	 * @param string $type one of $this->types
	 * @param array $data array with data, that will be stored and used for inserts in type_text
	 * @param string|array|object $model one of the following:
	 *   - 'User' (pulls data from $data['User']['id'])
	 *   - array('model' => 'User', 'id' => $id)
	 *   - $this->User (Object / Instance of User Model)
	 * @return mixed parsed text of type, or false on error
	 * @access public
	 */
	public function write($type, $data = array(), $model = null)
	{
		if(!array_key_exists($type, $this->types)) {
			return false;
		}

		$row = array(
			'type' => $type,
			'data' => $data,
		);

		if(!empty($model) && is_string($model) && isset($data[$model]) && isset($data[$model]['id']))
		{
			$row['model'] = $model;
			$row['foreign_id'] = $data[$model]['id'];
		}
		
		if(!empty($model) && is_array($model) && array_key_exists('model', $model) && array_key_exists('id', $model))
		{
			$row['model'] = $model['model'];
			$row['foreign_id'] = $model['id'];
		}

		if(!empty($model) && is_object($model))
		{
			$row['model'] = $model->alias;
			$row['foreign_id'] = $model->id;
		}

		if($this->create($row) && $this->save())
		{
			//everything went fine
			return $this->parse($type, $data);
		}
		return false;
	}


	public static function all($data = array(), $options = array()) {
		return ClassRegistry::init('Flour.Activity')->write($options['event_type'], $data);
	}

	/**
	 * beforeSave callback, takes care of parsing the message string of
	 * type, to be enriched with data.
	 *
	 * @param string $options 
	 * @return mixed returns output of parent::beforeSave()
	 * @access public
	 */
	public function beforeSave($options = null)
	{
		//extract data, because it got encoded by flexible already.
		$this->data[$this->alias]['data'] = json_decode($this->data[$this->alias]['data'], true);
		$this->data[$this->alias]['message'] = $this->parse($this->data[$this->alias]['type'], $this->data[$this->alias]['data']);
		$this->data[$this->alias]['data'] = json_encode($this->data[$this->alias]['data']);
		return parent::beforeSave($options);
	}

	/**
	 * overwritten to supress deletion of activities
	 *
	 * @return false
	 * @access public
	 */
	public function delete()
	{
		return false;
	}

	/**
	 * After flattening $data, inserts strings in message text of given $type.
	 * 
	 * works like that:
	 * 
	 * {{{
	 * $this->Activity->parse('object_created', array('name' => 'foo', 'id' => 'bar'));
	 * //returns: "Created object 'foo' [bar]"
	 * }}}
	 *
	 * @param string $type one of $this->types
	 * @param array $data data that gets flattened and String::inserted into message of type $text
	 * @return mixed returns parsed string or false, if $type does not exist
	 * @access public
	 */
	public function parse($type, $data)
	{
		if(!array_key_exists($type, $this->types)) {
			return false;
		}
		return String::insert($this->types[$type], Set::flatten($data));
	}

	/**
	 * overwriting find for a new option 'type'
	 * 
	 * use like this:
	 * 
	 * {{{
	 * $this->Activity->find('all', array('type' => array('foo', 'bar', 'baz')))
	 * }}}
	 *
	 * @param string $type kind of find, usually 'first', or 'all'
	 * @param array $options query data with conditions and everything, new key for $type, see code-example
	 * @return mixed returns parent::find();
	 * @access public
	 */
	public function find($type, $options = array())
	{
		if (!isset($options['conditions']))
		{
			$options['conditions'] = array();
		} 

		if (isset($options['conditions']) && is_string($options['conditions']))
		{
			$options['conditions'] = array($options['conditions']);
		} 

		if (isset($options['type']))
		{
			$options['conditions'] = array_merge($options['conditions'], array($this->alias.'.type' => $options['type']));
			unset($options['type']);
		}
		return parent::find($type, $options);
	}

	/**
	 * shortcut to get a list of recent Activities
	 *
	 * @param string $limit number of items to retrieve
	 * @param array $options will be passed as second param into find('all', $options);
	 * @return array returns resultset from $this->find('all')
	 * @access public
	 */
	public function get($limit = 25, $options = array())
	{
		$defaults = array(
			'order' => 'Activity.created DESC',
		);
		$options = array_merge($options, $defaults);
		$options['limit'] = $limit;
		return $this->find('all', $options);
	}


	/**
	 * custom find method 'types'
	 *
	 * @param string $state 'before' or 'after'
	 * @param string $query
	 * @param string $results 
	 * @return mixed based on $state, returns $query or $results
	 * @access protected
	 */
	protected function _findTypes($state, $query, $results = array()) {
		if ($state == 'before') {
			$query = Set::merge(
				$query,
				$this->findQueries['types']
			);
			return $query;
		} elseif ($state == 'after') {
			$return = array();
			foreach($results as $row)
			{
				$return[] = array(
					'type' => $row['Activity']['type'],
					'message' => $this->types[$row['Activity']['type']],
					'count' => $row[0]['count'],
				);
			}
			return $return;
		}
	}
}
