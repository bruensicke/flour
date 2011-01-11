<?php
/**
 * Account Membership model 
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class AccountMembership extends FlourAppModel {
	var $name = 'AccountMembership';

	var $belongsTo = array(
		'Flour.Account',
		'Flour.User',
	);
}