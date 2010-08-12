<?php
/**
 * Model behavior Flexible.
 *
 * Allows a Model to save an unlimited number of virtual fields. 
 * It even take care of values that are of type array.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 */
class FlexibleBehavior extends ModelBehavior
{

/**
 * @access public
 */
	var $settings = array();

/**
 * @access private
 */
	private $__defaultSettings = array(
		'with' => 'Flour.MetaField',
		'dependent' => true,
		'foreignKey' => 'foreign_id',
	);

/**
 * enter all models here, that for sure do not need to be flexible.
 * @access public
 */
	var $modelBlacklist = array(
		'AppModel',
		'FlourAppModel',
		'Tagged',
		'MetaField',
	);

/**
 * Setting up configuration for every model using this behavior. To overwrite pass an array like:
 * array('with' => OtherModel) as the actsAs parameter.
 *
 * @param string $Model 
 * @param array $settings 
 * @return array $settings for this model (if not in blacklist)
 */
	public function setup(&$Model, $settings = array())
	{
		if(in_array($Model->alias, $this->modelBlacklist))
		{
			return;
		}
		$this->settings[$Model->alias] = array_merge(
			$this->__defaultSettings,
			$settings
		);
		$class = pluginSplit($this->settings[$Model->alias]['with']);
		$base = array(
			'className' => $this->settings[$Model->alias]['with'],
			'modelName' => $class[1],
			'schema' => $Model->schema(),
		);
		$this->settings[$Model->alias] = array_merge(
			$this->settings[$Model->alias],
			$base
		);
		$this->__bind($Model);
		return $this->settings[$Model->alias];
	}

/**
 * Merges the virtual fields into the main Model record.
 *
 * @param object $Model
 * @param array $results
 * @param boolean $primary
 * @return array
 * @access public
 */
	function afterFind(&$Model, $results, $primary)
	{
		if(in_array($Model->alias, $this->modelBlacklist))
		{
			return $results;
		}
		extract($this->settings[$Model->alias]);
		foreach ($results as $i => $item)
		{
			if (!isset($item[$modelName]))
			{
				continue;
			}
			foreach ($item[$modelName] as $field)
			{
				$results[$i][$Model->alias][$field['name']] = $field['val'];
			}
		}
		$results = $this->unserialize_items($results); //will convert JSON Strings back to arrays
		return $results;
	}

/**
 * Converts all field-values that are arrays into JSON-objects.
 *
 * @param object $Model
 * @access public
 */
	function beforeSave(&$Model)
	{
		$fields = $Model->data[$Model->alias];
		foreach ($fields as $key => $val)
		{
			if(is_array($val))
			{
				$val = json_encode($val);
			}
			$Model->data[$Model->alias][$key] = $val;
		}
	}

/**
 * Saves all fields that do not belong to the current Model into 'with' helper model.
 *
 * @todo should be refactored to make a find('all') and it should take care of arrays for itself
 * @param object $Model
 * @access public
 */
	function afterSave(&$Model, $created)
	{
		if(in_array($Model->alias, $this->modelBlacklist))
		{
			return $results;
		}
		extract($this->settings[$Model->alias]);
		$fields = array_diff_key($Model->data[$Model->alias], $schema);
		$id = $Model->id;
		foreach ($fields as $key => $val)
		{
			$field = $Model->{$modelName}->find('first', array(
				'fields' => array($modelName.'.id'),
				'conditions' => array($modelName.'.'.$foreignKey => $id, $modelName.'.name' => $key),
				'recursive' => -1,
			));
			$Model->{$modelName}->create(false);
			if ($field) {
				$Model->{$modelName}->set('id', $field[$modelName]['id']);
			} else {
				$Model->{$modelName}->set(array(
					$foreignKey => $id,
					'model' => $Model->alias,
					'name' => $key,
				));
			}
			$Model->{$modelName}->set('val', $val);
			$Model->{$modelName}->save();
		}
	}

/**
 * Checks if string is serialized array/object
 * json_decode returns NULL in some environments, if $str is not encoded
 *
 * @param string string to check
 * @access public
 * @return boolean
 */
	function isSerialized($str)
	{
		// json_decode returns NULL in some environments, if $str is not encoded
		return (// check json notation
				preg_match('/^[\{].*[\}]$|^[\[].*[\]]$/',$str)
				&& 
				// handle NULL - values
				($str === "null"
				|| @json_decode($str) !== NULL)
				&&
				// handle FALSE - values
				( $str == json_decode(false)
				|| @json_decode($str) !== false )
		);
	}

/**
 * Unserializes the fields of an array (if the value itself was serialized)
 *
 * @param array $arr
 * @return array
 * @access public
 */
	function unserialize_items($arr)
	{
		foreach($arr as $key => $val)
		{
			if(is_array($val))
			{
				$val = $this->unserialize_items($val);
			} elseif($this->isSerialized($val))
			{
				$val = json_decode($val, true); //converts to array
			}
			$arr[$key] = $val;
		}
		return $arr;
	}

	function __bind(&$Model)
	{
		extract($this->settings[$Model->alias]);
		$Model->bindModel(
			array('hasMany' => array($modelName => compact('className', 'dependent', 'foreignKey', 'conditions')))
		);
	}

}

?>