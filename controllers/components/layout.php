<?php
/**
 * LayoutComponent
 *
 * Can be configured in AppController like this:
 *
 * {{{
 *	var $components = array(
 *		'Flour.Layout' => array('admin' => 'cox', 'default' => 'front'),
 *	);
 * }}}
 *
 * Or write this in your bootstrap.php
 *
 * {{{
 *  Configure::write('App.Layout.themes', array(
 *  	'view' => 'Theme', //optional
 *  	'default' => 'cox',
 *  	'layout' => 'default', //you can use :prefix as var
 *  	'options' => array(
 *  		'admin' => 'cox',
 *			'default' => 'cox',
 *  	),
 *  ));
 * }}}
 *
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
App::import('Lib', 'Flour.init');
class LayoutComponent extends Object
{

	/**
	 * Settings for LayoutComponent
	 *
	 * @var array
	 */
	public $settings = array(
		'view' => 'Theme',
		'layout' => ':prefix',
		'themes' => array(),
	);

	/**
	 * Instance of calling Controller
	 *
	 * @var object Controller
	 * @access public
	 */
	public $__controller;

	/**
	 * called by Cake on instantiation of Component
	 *
	 * @param object $controller Controller, calling this Component 
	 * @param array $settings array of Settings, can be configured in Controller
	 * @return void
	 * @access public
	 */
	public function initialize(&$controller, $settings = array())
	{
		$this->__controller = $controller;


		if(Configure::read('App.Layout')) {
			$this->settings = Set::merge(Configure::read('App.Layout'), $this->settings);
		}
		$this->settings = Set::merge($this->settings, $settings);
		$this->setup();
	}

	/**
	 * sets everything up in Controller, according to $this->settings
	 *
	 * @param array $settings array of Settings, can be configured in Controller
	 * @return void
	 */
	function setup($settings = array())
	{
		if(!empty($settings)) $this->settings = $settings;
		
		$prefix = (!empty($this->__controller->params['prefix']))
			? $this->__controller->params['prefix']
			: 'default';

		$theme = (!empty($this->settings['themes'][$prefix]))
			? $this->settings['themes'][$prefix]
			: low(Configure::read('App.Settings.name'));

		$view = (!empty($this->settings['view']))
			? $this->settings['view']
			: 'Theme';

		$layout = String::insert($this->settings['layout'], compact('prefix'));

		$this->__controller->view = $view;
		$this->__controller->theme = $theme;
		$this->__controller->layout = $layout;

		Configure::write('App.Layout.current', compact('prefix', 'theme', 'view', 'layout'));
	}

}
