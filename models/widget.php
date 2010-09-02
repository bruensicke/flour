<?php
/**
 * Widget Model
 * 
 * a Widget is an instance of a specific type with a saved configuration
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
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

}
