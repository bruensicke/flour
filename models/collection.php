<?php
/**
 * Collection Model
 * 
 * a Collection can save unlimited amount of CollectionItems
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Collection extends FlourAppModel
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
		'CollectionItem' => array(
			'className' => 'Flour.CollectionItem',
			'foreignKey' => 'collection_id',
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

}
