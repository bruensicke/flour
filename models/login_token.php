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

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'LoginToken';

	/**
	 * Generates a LoginToken for given UserId
	 *
	 * @param string $user_id 
	 * @param string $expires 
	 * @return string $token_id
	 * @access public
	 */
	public function generate($user_id, $expires = null)
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

	/**
	 * refreshes the Expiration of a LoginToken for given $token_id
	 *
	 * @param string $token_id 
	 * @return mixed null or new $token_id if refreshed
	 * @access public
	 */
	public function refresh($token_id)
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

	/**
	 * Invalidates a given $token_id
	 *
	 * @param string $token_id 
	 * @return void
	 * @access public
	 */
	public function invalidate($token_id)
	{
		$this->create();
		$this->id = $token_id;
		$this->saveField('used', 1);
	}

	/**
	 * Invalidates all Tokens for a given $user_id
	 *
	 * @param string $user_id 
	 * @return void
	 * @access public
	 */
	public function invalidateAll($user_id)
	{
		$this->updateAll(array('used' => 1), array('user_id' => $user_id));
	}

}