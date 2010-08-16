<?php
/**
 * Content Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Content extends FlourAppModel
{
	var $actsAs = array(
		'Flour.Polymorphic',
		'Flour.Taggable',
	);

	/**
	 * @var array controls validation
	 * @access private
	 */
	var $validate = array(
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
?>