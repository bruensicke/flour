<?php
/**
 * FlourController
 * 
 * Shows Documentation of Flour.
 *
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class FlourController extends FlourAppController
{

	public $uses = null;

/**
 * Index action.
 *
 * @access public
 */
	function admin_index()
	{
		$this->layout = 'admin';
	}

}
