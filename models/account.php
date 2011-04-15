<?php
/**
 * Account Model
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class Account extends FlourAppModel  {

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'Account';

	/**
	 * hasMany associations
	 *
	 * @var array
	 * @access public
	 */
	public $hasMany = array('Flour.AccountMembership');

	/**
	 * Validation rules
	 *
	 * @var array
	 * @access public
	 */
	public $validate = array(
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);
}