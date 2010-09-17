<?php
/**
 * FlourAppController
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
require_once(dirname(__FILE__).'/config/init.php');
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
		'Flour.Widget',
	);

/**
 * components array
 *
 * @var array
 * @access public
 */
	var $components = array(
		'RequestHandler',
		'Flour.Config',
		'Flour.Flash',
		'Flour.Search',
	);

}
