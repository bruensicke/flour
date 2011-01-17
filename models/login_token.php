<?php
/**
 * Skillnote LoginToken
 * 
 * @package skillnote
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 *
 * how to setup:
 * {{{
 *  Configure::write('Cookie.loginToken.expires', '2 days');
 * }}}
 **/
class LoginToken extends FlourAppModel {

	function generate($user_id, $expires = null)
	{
		if(!$expires) {
			$expires = date('Y-m-d H:i:s', strtotime(Configure::read('Cookie.loginToken.expires')));
		}

		$this->create();
		$this->set('user_id', $user_id);
		$this->set('expires', $expires);
		$this->save();
		$token_id = $this->getLastInsertId();
		return $token_id;
	}
	
	function refresh($token_id)
	{
		$old_token = $this->read(null, $token_id);

		if (!empty($old_token))
		{
			$new_token_id = $this->generate($old_token['LoginToken']['user_id']);
			$this->invalidate($token_id);
			return $new_token_id;
		}
		return null;
	}
	
	function invalidate($token_id)
	{
		$this->create();
		$this->id = $token_id;
		$this->saveField('used', 1);
	}
	
	function invalidateAll($user_id)
	{
		$this->updateAll(array('used' => 1), array('user_id' => $user_id));
	}

}