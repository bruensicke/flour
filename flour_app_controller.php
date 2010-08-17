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
		// 'Flour.Config',
		'Flour.Flash',
		'Flour.Search',
	);

}
