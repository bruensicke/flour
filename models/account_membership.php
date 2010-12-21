<?php
/**
 * Account Membership model 
 *
 * You can use this model or copy it to your app.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright brünsicke.com GmbH
 **/
class AccountMembership extends FlourAppModel {
	var $name = 'AccountMembership';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Flour.Account',
		'Flour.User',
	);
}