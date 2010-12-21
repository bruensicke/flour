<?php
/**
 * Account Membership Model
 *
 * You can use this model or copy it to your app.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class Account extends FlourAppModel  {
	public $name = 'Account';

	public $hasMany = array('Flour.AccountMembership');

	public $validate = array(
		'status' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}