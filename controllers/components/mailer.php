<?php
/**
 * MailerComponent
 * This is a wrapper for uniform sending mails thru a mailing layer e.g. cake mail component
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class MailerComponent extends Object
{
	public $components = array('Flour.Postmark');

	public $delivery = null;

	public $__message = null; //text of message

	public $to = null;
	public $subject = null;
	public $template = null;

	public $settings = array();

	function initialize(&$controller, $settings=array())
	{
		$this->controller = $controller;
		$this->settings = Configure::read('App.Mailer.settings');
		$this->Email = $this->Postmark;
	}
	
	function startup( &$controller )
	{
	}

	public function reset()
	{
		$this->template = null;
		$this->to = array();
		$this->from = null;
		$this->replyTo = null;
		$this->return = null;
		$this->cc = array();
		$this->bcc = array();
		$this->subject = null;
		return $this->Email->reset();
	}

	/**
	 * Configure the defaults
	 *
	 * @access protected
	 */
	protected function prepare()
	{
		$this->settings = Configure::read('App.Mailer.settings');
		
		// default bcc
		if(!empty($this->settings['bcc']))
		{
			if(is_array($this->settings['bcc']))
			{
				$this->Email->bcc = array_merge($this->Email->bcc, $this->settings['bcc']);
			} else {
				$this->Email->bcc[] = $this->settings['bcc'];
			}
		}

		// subject formatting
		$this->Email->subject = String::insert($this->settings['subject'], array('subject' => $this->subject));

		//which emailaddresses to use
		$this->Email->replyTo = $this->resolveAddress($this->settings['replyTo']);
		$this->Email->from = $this->resolveAddress($this->settings['from']);

		//technical settings
		if(empty($this->settings['charset']))
		{
			$this->Email->charset = 'utf8';
		} else {
			$this->Email->charset = $this->settings['charset'];
		}
		
		if(!empty($this->settings['wordWrap']))
		{
			$this->Email->WordWrap = $this->settings['wordWrap'];
		}

		if(!empty($this->settings['sendAs']))
		{
			$this->Email->sendAs = $this->settings['sendAs'];
		}

		//method of sending
		if(!empty($this->settings['delivery']))
		{
			$this->Email->delivery = $this->settings['delivery'];
		}

	}

	function send($content = null, $template = null, $layout = null)
	{
		$this->prepare();

		$this->Email->to = $this->to;
		$this->Email->template = $this->template;

		if(Configure::read() > 0)
		{
			$this->Email->to = $this->resolveAddress('debug');
			// $this->Email->delivery = 'mail'; //debug, mail, smtp, postmark
			$this->Email->bcc = array();
			$this->Email->subject = 'DEBUG: '.$this->Email->subject;
		}
		$response = $this->Email->send($content, $template, $layout);
		
		// save activity log
		if (!is_array($response))
		{
			$response = array('ErrorCode' => $response);
		}
		
		$response['data'] = array(
			'delivery' => $this->Email->delivery,
			'to' => $this->Email->to,
			'from' => $this->Email->from,
			'template' => $this->Email->template,
			'bcc' => $this->Email->bcc,
			'subject' => $this->Email->subject,
		);
		$this->Activity = ClassRegistry::init('Flour.Activity');
		$this->Activity->write('mail_out', $response, 'Mailer');
		
		return $response;
	}

	//get all or one specific adress
	public function resolveAddress($key)
	{
		$this->addresses = Configure::read('App.Mailer.addresses.options');
		return (!is_null($key))
			? $this->addresses[$key]
			: $this->addresses;
	}

/**
 * notify the given user
 *
 * ### Options
 * - 'language' - 3char of language
 * - 'template' - Slug of the mail template
 * - 'subject' - Subject of the mail
 * - 'data' - array for the mail template
 *
 * @param $options
 */
	public function notifyUser($user_id, $options)
	{
		$this->controller->loadModel('User');
		$this->User = &$this->controller->User;

		if(!empty($options['subject'])){
			$this->subject = $options['subject'];
		}else{
			$this->subject = "";
		}
		if(!empty($options['template'])){
			$this->template = $options['template'];
		}else{
			return false;
		}
		if(empty($options['data'])){
			$options['data'] = array();
		}
		if(empty($options['language'])) {
			$options['language'] = $this->User->setting('language', null, $user_id);
		}
		if(!empty($options['language']) && $options['language'] != 'deu'){
			$this->template = $options['language'].DS.$this->template;
			//$options['language'] = Configure::read('Config.language');
			$options['language'] = null;
		}

		$user = $this->User->read(array('email'), $user_id);
		$this->to = $user['User']['email'];

		$this->controller->set('data', $options['data']);
		return $this->send();
	}

}