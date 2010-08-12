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
 * Per default a Model called <Model>Field is used for storing the virtual fields. To overwrite pass an array like:
 * array('with' => OtherModel) as the actsAs parameter.
 *
 * @param object $Model
 * @param array $settings
 * @return array
 * @access public
 */
	function setup(&$Model, $settings = array())
	{
		if(!in_array($Model->alias, array('FlourAppModel', 'Tagged', 'MetaField')))
		{
			$base = array('schema' => $Model->schema());
			$assoc = 'MetaField';
			$Model->bindModel(
				array('hasMany' => array(
						'MetaField' => array(
							'className' => 'Flour.MetaField',
							'dependent' => true,
							'foreignKey' => 'foreign_id',
							'conditions' => array('model' => $Model->alias),
						)
					)
				)
			);
			$defaults = array(
				'with' => $assoc,
				'foreignKey' => 'foreign_id',
				'dependent' => true,
				'conditions' => array('Model' => $Model->alias),
			);
			return $this->settings[$Model->alias] = am($base, $defaults, !empty($settings) ? $settings : array());
		}
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
		extract($this->settings[$Model->alias]);
		foreach ($results as $i => $item)
		{
			if (!isset($item[$with]))
			{
				continue;
			}
			foreach ($item[$with] as $field)
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
	function afterSave(&$Model)
	{
		extract($this->settings[$Model->alias]);
		$fields = array_diff_key($Model->data[$Model->alias], $schema);
		$id = $Model->id;
		foreach ($fields as $key => $val)
		{
			$field = $Model->{$with}->find('first', array(
				'fields' => array($with.'.id'),
				'conditions' => array($with.'.'.$foreignKey => $id, $with.'.name' => $key),
				'recursive' => -1,
			));
			$Model->{$with}->create(false);
			if ($field) {
				$Model->{$with}->set('id', $field[$with]['id']);
			} else {
				$Model->{$with}->set(array($foreignKey => $id, 'name' => $key));
			}
			$Model->{$with}->set('val', $val);
			$Model->{$with}->save();
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

}

?>