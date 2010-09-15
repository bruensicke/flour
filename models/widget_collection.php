<?php
/**
 * WidgetCollection Model
 * 
 * a Widget Collection is a set of Widgets in a template.
 * 
 * According to its type, it can consist of a stack of Widgets, a row
 * with Widgets within a given template or a full featured complex layout
 * with unlimited rows and unlimited widgets.
 *  
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class WidgetCollection extends FlourAppModel
{

/**
 * Attached behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'Flour.Taggable',
	);

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'type' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

}
