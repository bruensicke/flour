<?php
/**
 * NavigationItem Model
 * 
 * a NavigationItem is one row in a Navigation
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class NavigationItem extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'NavigationItem';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 * @access public
	 */
	public $belongsTo = array(
		'Navigation' => array(
			'className' => 'Flour.Navigation',
		),
	);

	/**
	 * behaviors attached to model
	 *
	 * @var string
	 * @access public
	 */
	public $actsAs = array(
		'Tree',
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
		'link' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

}
