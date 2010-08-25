<?php
/**
 * Content Model
 * 
 * Every kind of model can be an item in the content library.
 * The library itself can be queried through various ways, giving
 * access to every item regarding the state of editions.
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends FlourAppModel
{

/**
 * Attached behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Flour.Polymorphic',
		'Flour.Taggable',
	);

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'model' => array(
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
