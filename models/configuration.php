<?php
/**
 * Configuration Model
 * 
 * @package flour
 * @author Dirk Brünsicke
 * @version $Id$
 * @copyright bruensicke.com GmbH
 **/
class Configuration extends FlourAppModel
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
 * Find methods
 *
 * @var array
 * @access public
 */
	public $_findMethods = array(
		'autoload' => true,
	);

/**
 * @var array controls validation
 * @access private
 */
	var $validate = array(
		'model' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'name' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
		'slug' => array(
			'notEmpty' => array('rule' => 'notEmpty', 'required' => true),
		),
	);

	public function _findAutoload($state, $query, $results = array())
	{
		if($state == 'before')
		{
			if (isset($query['conditions']) && is_array($query['conditions']))
			{
				$query['conditions'] = Set::merge(
					$query['conditions'], 
					array(
						'Configuration.status >' => 1,
						// 'Configuration.autoload' => 1,
					)
				);
			}
			return $query;
		}
		elseif($state == 'after')
		{
			return $results;
		}
	}

}
