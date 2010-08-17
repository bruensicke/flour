<?php
/**
 * FlourAppController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
require_once(dirname(__FILE__).'/config/flour.php'); //temp
class FlourAppController extends AppController
{

/**
 * helpers array
 *
 * @var array
 * @access public
 */
	var $helpers = array(
		'Flour.TagCloud',
	);

/**
 * components array
 *
 * @var array
 * @access public
 */
	var $components = array(
		'Flour.Flash',
		'Flour.Search',
	);

/**
* Reads settings from database and writes them using the Configure class
* 
* @return void
* @access private
* @author Jose Diaz-Gonzalez
*/
	function _configureAppSettings() {
		$settings = array();
		$this->loadModel('Setting');
		$Setting = $this->Setting;
		if (($settings = Cache::read("settings.all")) === false)
		{
			$settings = $this->Setting->find('all');
			Cache::write("settings.all", $settings);
		}
		foreach($settings as $_setting)
		{
			if ($_setting['Setting']['value'] !== null) {
				Configure::write("{$_setting['Setting']['category']}.{$_setting['Setting']['setting']}", $_setting['Setting']['value']);
			}
		}
	}
}
