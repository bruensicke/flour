<?php
/**
 * CollectionItem Model
 * 
 * a CollectionItem is one row in a Collection
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class CollectionItem extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'CollectionItem';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 * @access public
	 */
	public $belongsTo = array(
		'Collection' => array(
			'className' => 'Flour.Collection',
		),
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
		'val' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

}
