<?php
/**
 * ContentObject Model
 * 
 * If you want to save something in the content library, without having
 * a specific model for that, you can use this one. It is flexible and
 * therefore can save any amount of data, regardless of their nesting.
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @copyright bruensicke.com GmbH
 **/
class ContentObject extends FlourAppModel
{

	/**
	 * Name
	 *
	 * @var string $name
	 * @access public
	 */
	public $name = 'ContentObject';

}
