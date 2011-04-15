<?php
/**
 * Account Membership model 
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class AccountMembership extends FlourAppModel {

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'AccountMembership';

	/**
	 * belongsTo associations
	 *
	 * @var string
	 * @access public
	 */
	public $belongsTo = array(
		'Flour.Account',
		'Flour.User',
	);
}