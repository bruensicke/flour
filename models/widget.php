<?php
/**
 * Widget Model
 * 
 * a Widget is an instance of a specific type with a saved configuration
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class Widget extends FlourAppModel
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
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'type' => array(
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
 * gets the current Widget with given $slug
 *
 * @param string $slug slug of Widget to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active content - false otherwise
 * @access public
 */
	public function get($slug_or_id)
	{
		$field = (Validation::uuid($slug_or_id))
			? 'id'
			: 'slug';

		$data = $this->find('current', array($field => $slug_or_id));
		if(empty($data))
		{
			return false;
		}
		return $data;
	}

}
