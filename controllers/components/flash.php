<?php
/**
 * Flash Component
 * 
 * Wrapper class to "setFlash" setting the key according to message type
 * 
 * Example: error() generates <div id="errorMessage" class="message">
 * Benefit: allows better styling based on state
 * 
 * Built in redirect after flash:
 * {{{
 *   $this->Flash->error('go away', '/');
 *   $this->Flash->success( __('welcome!', true), array('action' => 'dashboard'));
 *   $this->Flash->success( __('welcome :user!', true), array('user' => 'd1rk'), array('action' => 'dashboard'));
 * }}}
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
App::import('Core', array('String', 'Set'));
class FlashComponent extends Object 
{
/**
 * Other components to load
 *
 * @var public $components
 * @access public
 */
	public $components = array(
		'Session',
		'RequestHandler',
	);

/**
 * if set to true, responds with json on ajax-calls
 *
 * @var boolean $filterAjax
 * @access public
 */
	public $filterAjax = true;

/**
 * if set to true, will preserve all named params in urls, on redirects
 *
 * @var boolean $preserveNamedParams
 * @access public
 */
	public $preserveNamedParams = true;

/**
 * calling controller object
 *
 * @var Controller $Controller
 * @access protected
 */
	protected $Controller = null;

/**
 * Intialize Callback
 *
 * @param object Controller object
 * @access public
 */
	public function initialize(&$controller)
	{
		$this->Controller = $controller;
		if(!in_array('Session', $this->Controller->components))
		{
			array_unshift($this->Controller->components, 'Session');
		}
	}

	public function msg($element = 'flash_info', $message = '', $redirect = false, $options = array())
	{
		$replace = (array_key_exists('replace', $options) && is_array($options['replace']) && !empty($options['replace']))
			? $options['replace']
			: $this->Controller->data;

		$message = (is_array($replace) && !empty($replace))
			? String::insert($message, Set::flatten($replace))
			: $message;

		$params = (array_key_exists('params', $options) && is_array($options['params']) && !empty($options['params']))
			? $options['params']
			: null;

		$key = (array_key_exists('key', $options) && is_string($options['key']) && !empty($options['key']))
			? $options['key']
			: 'flash';

		if(array_key_exists('named', $options) && is_bool($options['named']))
		{
			$this->preserveNamedParams = $options['named'];
		}

		// Halt and put JSON if request was AJAX. 
		if ($this->RequestHandler->isAjax() && $this->filterAjax) {
			$this->RequestHandler->respondAs('json');
			$output = array(
				'message' => $message,
				'type' => $element,
				'time' => time(),
			);
			if(!empty($params))
			{
				$output['params'] = $params;
			}
			if(!empty($redirect))
			{
				$output['redirect'] = $redirect;
			}
			die(json_encode($output));
		}
		$this->Controller->Session->setFlash($message, $element, $params, $key);

		if (!empty($redirect))
		{
			if($redirect === true)
			{
				$redirect = $this->Controller->referer();
			}

			//TODO: convert $redirect to array if its a string
			if($this->preserveNamedParams && is_array($this->Controller->params['named']) && is_array($redirect))
			{
				$redirect = array_merge($this->Controller->params['named'], $redirect);
			}
			$this->Controller->redirect($redirect);
			return $redirect;
		}
		return true;
	}
	
	public function error($message, $redirect = false, $options = array())
	{
		return $this->msg('flash_error', $message, $redirect, $options);
	}
	
	public function success($message, $redirect = false, $options = array())
	{
		return $this->msg('flash_success', $message, $redirect, $options);
	}
	
	public function info($message, $redirect = false, $options = array())
	{
		return $this->msg('flash_info', $message, $redirect, $options);
	}
}