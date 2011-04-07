<?php
/**
 * FlourAppController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
App::import('Lib', 'Flour.init');
class FlourAppController extends AppController
{

/**
 * helpers array
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Flour.TagCloud',
		'Flour.Widget',
		'Flour.Nav',
		'Flour.Collection',
	);

/**
 * components array
 *
 * @var array
 * @access public
 */
	public $components = array(
		'RequestHandler',
		'Flour.Config',
		'Flour.Flash',
		'Flour.Search',
		'Flour.Layout',
	);

}
