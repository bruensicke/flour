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
	public $settings = array(
		'view' => 'Theme',
		'layout' => ':prefix',
	);

	public $__controller;

	public $__flourHelpers = array(
	);

	public function initialize(&$controller, $settings = array())
	{
		$this->__controller = $controller;
		$this->settings = Set::merge($settings, $this->settings, Configure::read('App.Layout.themes.options'));
		$this->setup();
	}

	function setup()
	{
		$prefix = (!empty($this->__controller->params['prefix']))
			? $this->__controller->params['prefix']
			: 'default';

		$theme = (!empty($this->settings[$prefix]))
			? $this->settings[$prefix]
			: Configure::read('App.Layout.themes.default');

		$view = (!empty($this->settings['view']))
			? $this->settings['view']
			: Configure::read('App.Layout.themes.view');

		$this->__controller->view = 'Theme';
		$this->__controller->theme = $theme;
		$this->__controller->layout = String::insert($this->settings['layout'], compact('prefix'));
	}

}
