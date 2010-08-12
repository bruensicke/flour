<?php
/**
 * Model behavior Editionable.
 *
 * Regarding editions on models, including status valid_from and valid_to
 *
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 */
class EditionableBehavior extends ModelBehavior
{

/**
 * @access public
 */
	var $settings = array();

/**
 * Per default a Model called <Model>Field is used for storing the virtual fields. To overwrite pass an array like:
 * array('with' => OtherModel) as the actsAs parameter.
 *
 * @param object $Model
 * @param array $settings
 * @return array
 * @access public
 */
	function setup(&$Model, $settings = array())
	{

	}


}

?>