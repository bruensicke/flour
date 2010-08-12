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

	var $components = array(
		'Flour.Flash',
	);

}
