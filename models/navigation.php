<?php
/**
 * Navigation Model
 * 
 * a Navigation can save unlimited amount of NavigationItems
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Navigation extends FlourAppModel
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
 * hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'NavigationItem' => array(
			'className' => 'Flour.NavigationItem',
			'foreignKey' => 'navigation_id',
			'dependent' => false,
			'order' => 'NavigationItem.lft ASC',
		)
	);

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

/**
 * gets the current Navigation with given $slug
 *
 * @param string $slug slug of Navigation to retrieve (or id)
 * @return mixed array of $data, if found in database for the currently active Navigation - false otherwise
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
