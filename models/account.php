<?php
/**
 * Account Model
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class Account extends FlourAppModel  {
	public $name = 'Account';

	public $hasMany = array('Flour.AccountMembership');

	public $validate = array(
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);
}