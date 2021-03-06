<?php
/**
 * User Model
 * 
 * You can use this model or copy it to your app.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright brünsicke.com GmbH
 **/
class User extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'User';

	/**
	 * Attached behaviors
	 *
	 * @var array
	 * @access public
	 */
	public $actsAs = 'Flour.Taggable';

	/**
	 * hasMany associations
	 *
	 * @var array
	 * @access public
	 */
	public $hasMany = array(
		'Flour.LoginToken',
		//'Flour.Activity',
		'Flour.AccountMembership',
	);
	
	/**
	 * Validation rules
	 *
	 * @var array
	 * @access public
	 */
	public $validate = array(
		'name' => array(
			'isUnique' => array('rule' => 'isUnique'),
		),
		'email' => array(
			'isUnique' => array('rule' => 'isUnique'),
			'isEmail' => array('rule' => 'email'),
		),
	);
	
	/**
	 * persists a User via Authsome Component
	 *
	 * @param string $user 
	 * @param string $duration 
	 * @return string $token
	 * @access public
	 */
	public function authsomePersist($user, $duration)
	{
		$userId = $user['User']['id'];
 		$token = $this->LoginToken->generate($userId, date('Y-m-d H:i:s', strtotime($duration)));
		return $token;
	}
	
	/**
	 * Logs a user in via given $type
	 *
	 * @param string $type 
	 * @param array $credentials 
	 * @return false|array false on error, user-data otherwise
	 * @access public
	 */
	public function authsomeLogin($type, $credentials = array())
	{
		switch ($type)
		{
			case 'guest':
				// You can return any non-null value here, if you don't
				// have a guest account, just return an empty array
				return array();
				
			case 'credentials':

				// This is the logic for validating the login
				$conditions = array(
					'User.name' => $credentials['name'],
					'User.password' => Authsome::hash($credentials['password']),
				);
				break;
				
			case 'cookie':
				list($token, $userId) = split(':', $credentials['token']);
				$duration = $credentials['duration'];

// 				$loginToken = $this->LoginToken->find('first', array(
// 					'conditions' => array(
// 						'user_id' => $userId,
// 						'token' => $token,
// 						'duration' => $duration,
// 						'used' => false,
// 						'expires <=' => date('Y-m-d H:i:s', strtotime($duration)),
// 					),
// 					'contain' => false
// 				));

				if (!$loginToken) {
					return false;
				}

				$loginToken['LoginToken']['used'] = true;
// 				$this->LoginToken->save($loginToken);

				$conditions = array(
					'User.id' => $loginToken['LoginToken']['user_id']
				);
				break;
			default:
				return null;
		}
		$user = $this->find('first', compact('conditions')); //TODO: do not find activities
		if (!$user) {
			return false;
		}
		$user['User']['loginType'] = $type;
		return $user;
	}
}